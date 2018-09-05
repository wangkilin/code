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
            @unlink($destination);
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
                @unlink($_imageFile);
            }
            @rmdir($tmpDir);

            $toShowOcrStat = true;
            View::assign('filename', basename($_FILES['attach']['name']).'.xls');
        }

        //var_dump($chapterList);

        View::assign('toShowOcrStat', $toShowOcrStat);
        View::assign('chapterList', $chapterList);

        View::output('test/square');
    }

    /**
     * 将ocr识别的目录文件内容， 拼接成文本块， 以及目录行
     * @param array $ocrText aliyun识别出来的文字内容。 带有坐标和文字信息的列表数组
     */
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
                     && abs($_block['pos']['size'] - $ocrText[$j]['pos']['size']) / $_block['pos']['size'] < 1/6
                     ) {
                          $textBlock[$_k][] = $ocrText[$j];
                          continue 3;
                    }


                    // 合并同一段落？ 两条数据， x轴开头位置距离不超过3个字距离， a的y轴最大坐标 和 b 的y轴最小坐标， 在1个字范围
                    if (abs($ocrText[$j]['pos']['minY'] - $_block['pos']['maxY'])  < $_block['pos']['size'] * 1.5
                     && abs($ocrText[$j]['pos']['minX'] - $_block['pos']['minX'])  < $_block['pos']['size'] * 3
                     && abs($_block['pos']['size'] - $ocrText[$j]['pos']['size']) / $_block['pos']['size'] < 1/6
                     ) {
                         $textBlock[$_k][] = $ocrText[$j];
                         continue 3;
                    }

                }
            }

            $textBlock[] = [$ocrText[$j]];

        }
        //echo var_export($textBlock, true);

        foreach ($textBlock as & $_blocks) {
            //echo '<br/>';
            //echo var_export($_blocks, true);
            //echo '<br/>';
            $text = '';
            foreach ($_blocks as $_block) {
                $text .= ' ' . mb_ereg_replace ( '…+$', '', trim(trim($_block['word']), '.·')) ;
            }
            $_blocks = trim($text);
        }
        //var_dump($textBlock);

        $excelData = [];
        $textNum = count($textBlock);
        $lastPage = 0;
        for ($i=0; $i<$textNum; $i++) {
            if (is_numeric(trim($textBlock[$i], '() ') ) && isset($textBlock[$i-1])) {

                if ($i-2 > $lastPage && isset($textBlock[$i-2]) && mb_strpos($textBlock[$i-2], '第')===0) {
                    $excelData[] = [$textBlock[$i-2], ''];
                } else if ($i-3 > $lastPage && isset($textBlock[$i-3]) && mb_strpos($textBlock[$i-3], '第')===0) {
                    $text = mb_strlen($textBlock[$i-3] . $textBlock[$i-2]) > 5 ? $textBlock[$i-3] : ($textBlock[$i-3] . $textBlock[$i-2]);
                    $excelData[] = [$text, ''];
                } else if ($i-4 > $lastPage && isset($textBlock[$i-4]) && mb_strpos($textBlock[$i-4], '第')===0) {
                    $text = mb_strlen($textBlock[$i-4]) > 4 ? $textBlock[$i-4] : ($textBlock[$i-4] . $textBlock[$i-3]);
                    $text = mb_strlen($text) > 4 ? $text : ($text . $textBlock[$i-2]);
                    $excelData[] = [$text, ''];
                } else {
                    // $j = $i-1;
                    // $hasFound = false;
                    // while($j < $i-5 && $j>0) {
                    //     if (is_numeric(trim($textBlock[$j], '() ') )) {
                    //         $hasFound = true;
                    //         $j++;
                    //         break;
                    //     }
                    //     $j--;
                    // }
                    // $text = $textBlock[$j];
                    // while(mb_strlen($text) < 5 && isset($textBlock[$j+1])) {
                    //     $text = $text . $textBlock[$j];
                    //     $j++;
                    // }
                    // $excelData[] = [$text, ''];
                }
                $excelData[] = [$textBlock[$i-1], $textBlock[$i]];
                $lastPage = $i;
            }
        }

        return $excelData;
    }

    public function build_ocr_text_action ()
    {
        $newOcrText = array();
        $ocrTextFile = TEMP_PATH . 'ocr/20180901-501005b8a4799115f8/aliyunOcr-000001.png.php';
        //$ocrTextFile = TEMP_PATH . 'ocr/20180901-501005b8a4799115f8/aliyunOcr-000002.png.php';
        //$ocrTextFile = TEMP_PATH . 'ocr/20180901-754335b8a4f35aa212/aliyunOcr-000001.png.php';
        //$ocrTextFile = TEMP_PATH . 'ocr/20180901-754335b8a4f35aa212/aliyunOcr-000002.png.php';
        $ocrText = require($ocrTextFile);
        $ocrText = $ocrText['prism_wordsInfo'];
        $excelData = $this->buildOcrText($ocrText);

        //$phpExcel = & loadClass('Excel_PhpExcel', array('beforeDownload' => array($this, '__setExcelCellWidth')) );
        //$phpExcel->export(basename($ocrTextFile) . '.xls', array('章节', '页码'), $excelData);

        //var_dump($excelData);
        View::assign('chapterList', $excelData);

        View::output('test/square');
    }

    /**
     * 下载excel文件
     */
    public function download_excel_action ()
    {
        if (isset($_POST['excelData'], $_POST['filename']) ) {
            $phpExcel = & loadClass('Excel_PhpExcel', array('beforeDownload' => array($this, '__setExcelCellWidth')) );
            $phpExcel->export($_POST['filename'], array('章节', '页码'), json_decode($_POST['excelData'], true) );
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
