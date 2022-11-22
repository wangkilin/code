<?php
/**
 * phpExcel 组件类
 */

class Tools_Excel_PhpExcel
{
    public $phpExcel;

    const VENDOR_NAME_EXCEL = 'PhpExcel';
    const VENDOR_NAME_SPREAD_SHEET = 'PhpSpreadsheet';
    protected $vendorName = 'PhpExcel';

    protected $hookBeforeDownload = null;

    public function __construct($options=array())
    {
        if (isset($options['beforeDownload']) && is_callable($options['beforeDownload'])) {
            $this->hookBeforeDownload = $options['beforeDownload'];
        }

        if (isset($options['vendor_name']) && $options['vendor_name']==self::VENDOR_NAME_SPREAD_SHEET) {

            require_once INC_PATH .  'vendor/autoload.php';
            $this->vendorName = self::VENDOR_NAME_SPREAD_SHEET;
        } else {
            require_once( INC_PATH . 'vendor/phpoffice/phpexcel/Classes/PHPExcel.php');
            /** Include PHPExcel_IOFactory */
            require_once INC_PATH .  'vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';
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
        if ($this->vendorName == self::VENDOR_NAME_SPREAD_SHEET) {
            $inputFileType =  @ ucfirst(pathinfo($filepath, PATHINFO_EXTENSION));
            $inputFileType == '' AND $inputFileType = 'Xls';
            $reader = PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
            $reader->setLoadAllSheets();
            $this->phpExcel = $reader->load($filepath);
        } else {
            $this->phpExcel = PHPExcel_IOFactory::load($filepath);
        }
        $sheets = $this->phpExcel->getAllSheets();

        if (is_null($sheetIndex)) {
            $sheetIndex = range(0, count($sheets)-1);
        } if (! is_array($sheetIndex)) {
            $sheetIndex = array($sheetIndex);
        }
        $returnData = array('sheetNames' => array(), 'sheetDatas' => array(), 'sheetStyles' => array());
        foreach($sheetIndex as $_index) {
            $data = array();
            $style = array();
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
                    $style[$currentRow][$currentColumn] = $currentSheet->getCell($address)->getStyle()->getFill()->getStartColor()->getRGB();

                    $currentColumn++; // Z + 1 => AA, AZ + 1 => BA
                    $i++;
                }
            }

            $returnData['sheetDatas'][$_index] = $data;
            $returnData['sheetStyles'][$_index] = $style;
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

            $filename = $file;
            if ($this->vendorName == self::VENDOR_NAME_SPREAD_SHEET) {
                $inputFileType =  @ ucfirst(pathinfo($filename, PATHINFO_EXTENSION));
                $inputFileType == '' AND $inputFileType = 'Xls';
                $reader = PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                $reader->setLoadAllSheets();
            } else {
                $reader = new PHPExcel_Reader_Excel5();
            }
        } else {
            $exts = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = $file['tmp_name'];

            if ($this->vendorName == self::VENDOR_NAME_SPREAD_SHEET) {
                $exts == '' AND $exts = 'Xls';
                $reader = PhpOffice\PhpSpreadsheet\IOFactory::createReader($exts);
                $reader->setLoadAllSheets();
            } else {

                if ($exts == 'xls') {
                    $reader = new PHPExcel_Reader_Excel5();
                }elseif ($exts == 'xlsx') {
                    $reader = new PHPExcel_Reader_Excel2007();
                }
            }
        }

        $this->phpExcel = $reader->load($filename);

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
     * @param bool   $bindHeadKey
     * @param array  $cellStyleInfoList
     *
     *                   $sharedStyle->applyFromArray(
     *                       array('fill' 	=> array(
     *                                                   'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
     *                                                   'color'		=> array('argb' => 'FFCCFFCC',
     *                                                                       //'rgb' => '808080'
     *                                                   )
     *                                               ),
     *
     *                           'font'    => array(
     *                               'name'      => 'Arial',
     *                               'bold'      => true,
     *                               'italic'    => false,
     *                               'underline' => PHPExcel_Style_Font::UNDERLINE_DOUBLE,
     *                               'strike'    => false,
     *                               'color'     => array(
     *                                   'rgb' => '808080'
     *                               )
     *                           ),
     *                           'borders' => array(
     *                               'bottom'     => array(
     *                                   'style' => PHPExcel_Style_Border::BORDER_DASHDOT,
     *                                   'color' => array(
     *                                       'rgb' => '808080'
     *                                   )
     *                               ),
     *                               'top'     => array(
     *                                   'style' => PHPExcel_Style_Border::BORDER_DASHDOT,
     *                                   'color' => array(
     *                                       'rgb' => '808080'
     *                                   )
     *                               ),
     *                               'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
     *                           ),
     *
     *                           )
     *                       );
     *
     *                   $objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle1, "A1:T100");
     *
     *                   #设置单元格宽高
     *                   $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(20);#设置单元格行高
     *                   $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);#设置单元格宽度
     *                   $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
     *                   $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
     *                   $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
     *
     *
     * @return
     */
    public function export($fileName, $headArr, $data, $bindHeadKey=false, $cellStyleInfoList=array())
    {
        require_once( INC_PATH . 'vendor/phpoffice/phpexcel/Classes/PHPExcel.php');
        /** Include PHPExcel_IOFactory */
        require_once INC_PATH .  'vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';
        //创建PHPExcel对象
        $objPHPExcel = new PHPExcel();
        $objProps = $objPHPExcel->getProperties();


        //设置活动单指数到第一个表,所以Excel打开这是第一个表
        $objPHPExcel->setActiveSheetIndex(0);

        foreach ($cellStyleInfoList as $_key => $_itemInfo) {
            switch (strtolower($_key)) {
                case 'width':
                    foreach ($_itemInfo as $_columnOrRowName => $_unit) {
                        if (preg_match("/^[a-z]+$/i",$_columnOrRowName) && preg_match("/^\d+$/", $_unit)) {
                            $objPHPExcel->getActiveSheet()->getColumnDimension($_columnOrRowName)->setWidth($_unit);#设置单元格宽度
                        }
                        continue;
                    }
                    break;

                case 'height':
                    foreach ($_itemInfo as $_columnOrRowName => $_unit) {
                        if (preg_match("/^\d+$/i",$_columnOrRowName) && preg_match("/^\d+$/", $_unit)) {
                            $objPHPExcel->getActiveSheet()->getRowDimension($_columnOrRowName)->setRowHeight($_unit);
                        }
                        continue;
                    }
                    break;

                case 'style':
                    static $_styleIndex = 1;
                    foreach($_itemInfo as $_columnOrRowName => $_unit) {
                        ${'sharedStyle'.$_styleIndex} = new PHPExcel_Style();
                        ${'sharedStyle'.$_styleIndex}->applyFromArray( $_unit);
                        $objPHPExcel->getActiveSheet()->setSharedStyle(${'sharedStyle'.$_styleIndex}, $_columnOrRowName);

                        $_styleIndex++;
                        //var_dump($_styleIndex, $_unit);
                    }
                    break;
            }
        }

        //设置表头
        $key = ord("A");
        foreach($headArr as $v){
            $colum = chr($key);
            $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);
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
