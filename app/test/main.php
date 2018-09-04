<?php
class main extends BaseController
{
    public function get_access_rule()
    {
        $rule_action['rule_type'] = 'white';

        $rule_action['actions'][] = 'square';
        $rule_action['actions'][] = 'index';
        $rule_action['actions'][] = 'build_ocr_text';

        return $rule_action;
    }

    public function index_action()
    {
    }

    public function index_square_action()
    {
        $toShowOcrStat = false;
        if (isset($_FILES, $_FILES['attach'], $_FILES['attach']['tmp_name'])
           && is_file($_FILES['attach']['tmp_name']) && $_FILES['attach']['type']=='application/pdf' ) {
            //var_dump($_FILES['attach']);
            $tmpDir = TEMP_PATH . 'ocr/' . uniqid(date('Ymd-').rand(1, 100000));
            mkdir($tmpDir, 0777, true);
            $destination = $tmpDir . '/' .basename($_FILES['attach']['tmp_name']) . '.pdf';
            move_uploaded_file($_FILES['attach']['tmp_name'], $destination);
            //echo system('pdf2image');
            $cwd = getcwd();
            chdir($tmpDir);
            $command = sprintf(Application::config()->get('aliyun')->commandConvertPdfToPng, $destination);
            exec($command, $output, $return);
            //var_dump($command, $output, $return);
            chdir($cwd);
            $images = glob(realpath($tmpDir) . '/*.png');
            //var_dump($images);
            $appKey    = Application::config()->get('aliyun')->appKey;
            $appSecret = Application::config()->get('aliyun')->appSecret;
            $aliyunRequester = & loadClass('Aliyun_ApiCurlRequest', ['appKey'=>$appKey, 'appSecret'=>$appSecret]);
            $chapterList = array();
            foreach ($images as $_imageFile) {
                $response = $aliyunRequester->ocrAdcanced($_imageFile);
                if ($response->getHttpStatusCode()=='200' && $response->getBody()) {
                    $ocrText = json_decode($response->getBody(), true);

                    $ocrText = $ocrText['prism_wordsInfo'];

                    $chapterList = array_merge($chapterList, $this->buildOcrText($ocrText));

                    //$var = var_export($ocrText, true);
                    //error_log($var, 3, $_imageFile.'.php');
                    //var_dump(json_decode($response->getBody(), true) );
                }
            }

            $toShowOcrStat = true;
            View::assign('filename', basename($_FILES['attach']['name']).'.xls');
        }

        //var_dump($chapterList);

        View::assign('toShowOcrStat', $toShowOcrStat);
        View::assign('chapterList', $chapterList);

        View::output('test/square');
    }

    protected function buildOcrText($ocrText)
    {
        foreach ($ocrText as $_key => $_ocrInfo) {
            $minX = $minY = null;
            $maxX = $maxY = null;
            foreach ($_ocrInfo['pos'] as $_posInfo) {
                if (! isset($minX)) {
                    $minX = $_posInfo['x'];
                    $minY = $_posInfo['y'];
                    $maxX = $_posInfo['x'];
                    $maxY = $_posInfo['y'];
                }
                $minX = $_posInfo['x'] > $minX ? $minX : $_posInfo['x'];
                $minY = $_posInfo['y'] > $minY ? $minY : $_posInfo['y'];
                $maxX = $_posInfo['x'] > $maxX ? $_posInfo['x'] : $maxX;
                $maxY = $_posInfo['y'] > $maxY ? $_posInfo['y'] : $maxY;
            }
            $ocrText[$_key]['pos'] = ['minX'=>$minX, 'minY'=>$minY, 'maxX'=>$maxX, 'maxY'=>$maxY, 'size'=>$maxY-$minY];
        }

        //var_dump($ocrText);
        $textBlock = [];
        $i = count($ocrText);
        for ($j=0; $j<$i; $j++) {
            if (! $textBlock) {
                $textBlock[0] = [$ocrText[$j]];
                continue;
            }

            foreach ($textBlock as $_k => $_blocks) {
                foreach ($_blocks as $_block) {
                    // 合并 同一行 ？，两条数据 y轴坐标差不多 < 5px ？，a的x轴末尾坐标 和 b的x轴开头坐标距离不超过 3个字距离；
                    // 两条数据的字体大小， 差不多。 因为是识别的字体， 字体大小有偏差
                    // 合并后，将最大坐标位置， 需要重新计算 ？
                    if ($ocrText[$j]['pos']['maxY'] - abs($_block['pos']['maxY'])  < 5
                     && abs($ocrText[$j]['pos']['minX'] - $_block['pos']['maxX']) < $_block['pos']['size'] * 3
                     && abs($_block['pos']['size'] - $_block['pos']['size']) / $_block['pos']['size'] < 1/8
                     ) {
                          $textBlock[$_k][] = $ocrText[$j];
                          continue 3;
                    }


                    // 合并同一段落？ 两条数据， x轴开头位置距离不超过3个字距离， a的y轴最大坐标 和 b 的y轴最小坐标， 在1个字范围
                    if (abs($ocrText[$j]['pos']['minY'] - $_block['pos']['maxY'])  < $_block['pos']['size'] * 1.5
                     && abs($ocrText[$j]['pos']['minX'] - $_block['pos']['minX'])  < $_block['pos']['size'] * 3
                     && abs($_block['pos']['size'] - $_block['pos']['size']) / $_block['pos']['size'] < 1/8
                     ) {
                         $textBlock[$_k][] = $ocrText[$j];
                         continue 3;
                    }

                }
            }

            $textBlock[] = [$ocrText[$j]];

        }

        foreach ($textBlock as & $_blocks) {
            $text = '';
            foreach ($_blocks as $_block) {
                $text .= ' ' . mb_ereg_replace ( '…+$', '', trim(trim($_block['word']), '.·')) ;
            }
            $_blocks = trim($text);
        }

        $excelData = [];
        $textNum = count($textBlock);
        for ($i=0; $i<$textNum; $i++) {
            if (is_numeric(trim($textBlock[$i], '() ') ) && isset($textBlock[$i-1])) {
                if (isset($textBlock[$i-2]) && mb_strpos($textBlock[$i-2], '第')===0) {
                    $excelData[] = [$textBlock[$i-2], ''];
                }
                $excelData[] = [$textBlock[$i-1], $textBlock[$i]];
            }
        }

        return $excelData;
    }

    public function build_ocr_text_action ()
    {
        $newOcrText = array();
        $ocrTextFile = TEMP_PATH . 'ocr/20180901-501005b8a4799115f8/aliyunOcr-000001.png.php';
        $ocrTextFile = TEMP_PATH . 'ocr/20180901-501005b8a4799115f8/aliyunOcr-000002.png.php';
        $ocrTextFile = TEMP_PATH . 'ocr/20180901-754335b8a4f35aa212/aliyunOcr-000001.png.php';
        $ocrTextFile = TEMP_PATH . 'ocr/20180901-754335b8a4f35aa212/aliyunOcr-000002.png.php';
        $ocrText = require($ocrTextFile);
        $ocrText = $ocrText['prism_wordsInfo'];
        $excelData = $this->buildOcrText($ocrText);

        $phpExcel = & loadClass('Excel_PhpExcel', array('beforeDownload' => array($this, '__setExcelCellWidth')) );
        $phpExcel->export(basename($ocrTextFile) . '.xls', array('章节', '页码'), $excelData);

        //var_dump($textBlock);
        View::output('test/square');
    }

    /**
     * 下载excel文件
     */
    public function download_excel_action ()
    {
        if (isset($_POST['excelData'], $_POST['filename']) ) {
            $phpExcel = & loadClass('Excel_PhpExcel', array('beforeDownload' => array($this, '__setExcelCellWidth')) );
            $phpExcel->export($_POST['filename'] . '.xls', array('章节', '页码'), $_POST['excelData']);  
        } else {

        }
    }

    /**
     * 回调方法，excel在下载前， 设置下列宽度 
     */
    public function __setExcelCellWidth ($phpExcelModel)
    {
        $phpExcelModel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $phpExcelModel->getActiveSheet()->getDefaultColumnDimension('B')->setWidth(20);
    }
}
