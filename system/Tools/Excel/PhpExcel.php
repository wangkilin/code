<?php
/**
 * phpExcel 组件类
 */
require_once( INC_PATH . 'vendor/phpoffice/phpexcel/Classes/PHPExcel.php');
/** Include PHPExcel_IOFactory */
require_once INC_PATH .  'vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';

class Tools_Excel_PhpExcel
{
    public $phpExcel;

    protected $hookBeforeDownload = null;

    public function __construct($options=array())
    {
        if (isset($options['beforeDownload']) && is_callable($options['beforeDownload'])) {
            $this->hookBeforeDownload = $options['beforeDownload'];
        }
    }

    /**
     * 解析excel文件, 获取每个单元格数据
     * @param string $filepath Excel文件路劲
     * @param int|array $sheetIndex 要获取的表序号。 int时为指定第几个表的数据。 array时为指定几个表的数据
     *
     * @return array
     */
    public function parseFile ($filepath, $sheetIndex=null)
    {
        $this->phpExcel = PHPExcel_IOFactory::load($filepath);
        $sheets = $this->phpExcel->getAllSheets();

        if (is_null($sheetIndex)) {
            $sheetIndex = range(0, count($sheets)-1);
        } if (! is_array($sheetIndex)) {
            $sheetIndex = array($sheetIndex);
        }
        $returnData = array('sheetNames' => array(), 'sheetDatas' => array());
        foreach($sheetIndex as $_index) {
            $data = array();
            // 如果不存在指定的表， 对应返回数据为空数组
            if (! isset($sheets[$_index])) {
                $returnData['sheetNames'][$_index] = $_index;
                $returnData['sheetDatas'][$_index] = $data;
                continue;
            }
            $currentSheet = $this->phpExcel->getSheet($_index);
            $returnData['sheetNames'][$_index] = $currentSheet->getTitle();

            $allColumn = $currentSheet->getHighestColumn();
            $allColumn++; // 将最大列 + 1. 作为列的边界

            $allRow = $currentSheet->getHighestRow();

            for ($currentRow = 1; $currentRow <= $allRow; $currentRow++) {
                $currentColumn = 'A';
                $i = 0; // 最多处理50列. 在边界范围内获取数据
                while ($currentColumn != $allColumn && $i<80) {
                    $address = $currentColumn.$currentRow;

                    $cell = $currentSheet->getCell($address)->getCalculatedValue();
                    if (is_object($cell)) {
                        $cell = $cell->__toString();
                    }
                    $data[$currentRow][$currentColumn] = $cell;

                    $currentColumn++; // Z + 1 => AA, AZ + 1 => BA
                    $i++;
                }
            }

            $returnData['sheetDatas'][$_index] = $data;
        }

        return $returnData;
    }

    /**
     * 获取Excel数据（导入）
     *
     * @param file     $file  Excel文件
     * @param interger $sheet Excel第几个工作簿，默认为0
     *
     * @return array
     */
    public function import($file, $sheet = 0, $flag = 0)
    {
        if ($flag == 1) {
            $phpReader = new PHPExcel_Reader_Excel5();
            $filename  = $file;
        } else {
            $exts = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = $file['tmp_name'];

            if ($exts == 'xls') {
                $phpReader = new PHPExcel_Reader_Excel5();
            }elseif ($exts == 'xlsx') {
                $phpReader = new PHPExcel_Reader_Excel2007();
            }
        }

        $this->phpExcel = $phpReader->load($filename);

        $currentSheet = $this->phpExcel->getSheet($sheet);

        $allColumn = $currentSheet->getHighestColumn();
        $allColumn++; // 将最大列 + 1. 作为列的边界

        $allRow = $currentSheet->getHighestRow();

        for ($currentRow = 1; $currentRow <= $allRow; $currentRow++) {
            $currentColumn = 'A';
            $i = 0; // 最多处理50列. 在边界范围内获取数据
            while ($currentColumn != $allColumn && $i<50) {
                $address = $currentColumn.$currentRow;

                $cell = $currentSheet->getCell($address)->getCalculatedValue();
                if (is_object($cell)) {
                    $cell = $cell->__toString();
                }
                $data[$currentRow][$currentColumn] = $cell;

                $currentColumn++; // Z + 1 => AA, AZ + 1 => BA
                $i++;
            }
        }

        return $data;
    }

    /**
     * 数据写入Excel（导出）
     *
     * @param string $fileName 文件名
     * @param array  $headArr  Excel第几个工作簿，默认为0
     * @param array  $data     Excel第几个工作簿，默认为0
     *
     * @return
     */
    public function export($fileName, $headArr, $data, $bindHeadKey=false)
    {
        //创建PHPExcel对象
        $objPHPExcel = new PHPExcel();
        $objProps = $objPHPExcel->getProperties();

        //设置表头
        $key = ord("A");
        foreach($headArr as $v){
            $colum = chr($key);
            $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);
            //$objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);
            $key += 1;
        }

        $column = 2;
        $objActSheet = $objPHPExcel->getActiveSheet();

        foreach($data as $key => $row){
            $span = ord("A");
            if ($bindHeadKey) {
                foreach ($headArr as $_k=>$_v) {
                    $value = isset($row[$_k]) ? $row[$_k] : '';
                    $j = chr($span);
                    $objActSheet->setCellValue($j.$column, $value);
                    $span++;
                }
            } else {
                foreach($row as $keyName=>$value){
                    $j = chr($span);
                    $objActSheet->setCellValue($j.$column, $value);
                    $span++;
                }
            }
            $column++;
        }

        //$fileName = iconv("utf-8", "gb2312", $fileName);

        //设置活动单指数到第一个表,所以Excel打开这是第一个表
        $objPHPExcel->setActiveSheetIndex(0);

        ob_end_clean();//清除缓冲区,避免乱码
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=\"$fileName\"");
        header('Cache-Control: max-age=0');
        if ($this->hookBeforeDownload) {
            call_user_func($this->hookBeforeDownload, $objPHPExcel);
        }

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

        //文件通过浏览器下载
        $objWriter->save('php://output');
        exit;
    }
}

/* EOF */
