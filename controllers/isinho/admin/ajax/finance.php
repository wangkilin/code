<?php
/**
+-------------------------------------------+
|   iCodeBang CMS [#RELEASE_VERSION#]       |
|   by iCodeBang.com Team                   |
|   © iCodeBang.com. All Rights Reserved    |
|   ------------------------------------    |
|   Support: icodebang@126.com              |
|   WebSite: http://www.icodebang.com       |
+-------------------------------------------+
*/

defined('iCodeBang_Com') OR die('Access denied!');
define('IN_AJAX', TRUE);

class finance extends SinhoBaseController
{
    public function setup()
    {
        HTTP::setHeaderNoCache();
    }

    /**
     * 解析excel文件， 返回工作表列表，供前台选择具体哪张表要导入
     */
    public function finance_import_action ()
    {

        $filename = 'upload_file';
        $dir = '/temp/' . gmdate('Ymd', APP_START_TIME);

        Application::upload()->initialize(array(
                        'allowed_types' => 'xls,xlsx',
                        'upload_path'   => get_setting('upload_dir') . $dir,
                        'is_image'      => true,
                        'max_size'      => get_setting('upload_size_limit')
        ));

        if (isset($_GET[$filename]) && ($content=file_get_contents('php://input')) && $content) {
            Application::upload()->do_upload($_GET[$filename], );
        } else if (isset($_FILES[$filename])) {
            Application::upload()->do_upload($filename);
        } else {
            return false;
        }
        // 检查上传结果
        $this->checkUploadFileResult();
        $uploadData = Application::upload()->data();
        $filePath = $dir . '/' . $uploadData['file_name'];
        $newFilePath = TEMP_PATH .$uploadData['file_name'].$uploadData['file_ext'];
        rename(get_setting('upload_dir') . $dir . '/' . $uploadData['file_name'], $newFilePath);

        $phpExcel = & loadClass('Tools_Excel_PhpExcel');

        $data = $phpExcel->parseFile($newFilePath);
        $batchKey = md5($this->user_id . rand(1, 1000000000) . microtime());

        echo htmlspecialchars(json_encode(array(
                        'success' => true,
                        'thumb'   => get_setting('upload_url') . $filePath,
                        'file'    => $filePath,
                        'newFilePath'    => $newFilePath,
                        'batch_key'      => $batchKey,
                        'sheet_names'    => $data['sheetNames'],
                        'origin_name'    => $uploadData['orig_name'],
                        'sheetDatas'      => $data,
        )), ENT_NOQUOTES);

    }

    /**
     * 导入工资
     */
    public function salary_do_import_action ()
    {
        $newFilePath = $_POST['filename'];      // 待导入的文件路径
        $sheetName   = $_POST['salary_sheets']; // 要导入的工作表
        $batchKey    = md5($this->user_id . rand(1, 1000000000) . microtime());;     // 批处理的key值
        $belongMonth = str_replace('-', '', $_POST['start_month']); // 数据归属月份

        $phpExcel    = & loadClass('Tools_Excel_PhpExcel');

        $user_id_key			     = '姓名'; // int(11) '用户id',
        $shifa_gongzi_key			 = '实发工资'; // decimal(7,2) '实发工资',
        $jiben_gongzi_key			 = '基本工资'; // decimal(7,2) '基本工资',
        $jintie_key			         = '津贴'; // decimal(6,2) '津贴',
        $zhiliangkaohe_key			 = '质量考核奖惩'; // decimal(6,2) '质量考核奖惩',
        $jixiao_key			         = '绩效'; // decimal(7,2) '绩效',
        $chaoejiangli_key			 = '超额奖励'; // decimal(7,2) '超额奖励',
        $jiben_heji_key              = array('name'=>'小计', 'index'=>1); // 基本收入合计 = 基本工资+津贴+质量考核奖励+绩效+超额奖励
        $quanqinjiang_key			 = '全勤奖'; // decimal(6,2) '全勤奖',
        $jiabanbutie_key			 = '加班补贴'; // decimal(7,2) '加班补贴',
        $kaoqin_heji_key             = array('name'=>'小计', 'index'=>2); // 考勤合计： = 全勤奖+加班补贴
        $queqinkoukuan_key			 = '缺勤扣款'; // decimal(7,2) '缺勤扣款',
        $qingjiakoukuan_key			 = '请假扣款'; // decimal(7,2) '请假扣款',
        $chidaokoukuan_key			 = '迟到扣款'; // decimal(7,2) '迟到扣款',
        $koukuan_heji_key            = array('name'=>'小计', 'index'=>3); // 扣款合计： = 缺勤+请假+迟到
        $gongzi_heji_key             = "工资\n合计"; // 工资合计： = 基本工资+津贴+质量考核奖励+绩效+超额奖励+全勤奖+加班补贴
        $shangnianpingjun_key		 = '上年平均工资'; // decimal(7,2) '上年平均工资',
        $yanglao_geren_key			 = '养老保险（个人）'; // decimal(6,2) '养老保险 个人',
        $yiliao_geren_key			 = '医疗保险（个人）'; // decimal(6,2) '医疗保险 个人',
        $shiye_geren_key			 = '失业保险（个人）'; // decimal(6,2) '失业保险 个人',
        $gongshang_geren_key		 = '工伤保险（个人）'; // decimal(6,2) '工伤保险 个人',
        $shengyu_geren_key			 = '生育保险（个人）'; // decimal(6,2) '生育保险 个人',
        $gongjijin_geren_key		 = '住房公积金（个人）'; // decimal(6,2) '公积金 个人',
        $geren_heji_key              = array('name'=>'小计', 'index'=>4); // 个人五险一金合计
        $yingshui_gongzi_key		 = '应计个税工资'; // decimal(7,2) '应税工资',
        $geshui_key			         = '应扣个税'; // decimal(6,2) '个税',
        $yanglao_gongsi_key			 = '养老保险（公司）'; // decimal(6,2) '养老保险 公司',
        $yiliao_gongsi_key			 = '医疗保险（公司）'; // decimal(6,2) '医疗保险 公司',
        $shiye_gongsi_key			 = '失业保险（公司）'; // decimal(6,2) '失业保险 公司',
        $gongshang_gongsi_key		 = '工伤保险（公司）'; // decimal(6,2) '工伤保险 公司',
        $shengyu_gongsi_key			 = '生育保险（公司）'; // decimal(6,2) '生育保险 公司',
        $gongjijin_gongsi_key		 = '住房公积金（公司）'; // decimal(6,2) '公积金 公司',
        $gongsi_heji_key             = '公司五险一金小计'; // 公司五险一金合计
        $yingchuqin_key		    	 = '应出勤天数'; // float '应出勤天数',
        $shijichuqin_key			 = '实际出勤天数'; // float '实际出勤天数',
        $canbu_key			         = '午餐补助天数'; // smallint(6) '餐补天数',
        $bingjia_xiaoshi_key		 = '病假小时数'; // float '病假小时数',
        $bingjia_tianshu_key		 = '病假天数'; // float '病假天数',
        $bingjia_kouchu_key			 = '病假扣除'; // decimal(6,2) '病假扣除',
        $shijia_xiaoshi_key			 = '事假小时数'; // float '事假小时数',
        $shijia_tianshu_key			 = '事假天数'; // float '事假天数',
        $shijia_kouchu_key			 = '事假扣除'; // decimal(6,2) '事假扣除',
        $fadingjiari_key			 = '法定假日天数'; // float '法定假日天数',
        $remark_key			         = array('name'=>'备注', 'index'=>2); // varchar(400) '备注信息',
        $keynameList = array (
            'user_id_key'			     => null,
            'shifa_gongzi_key'			 => null,
            'jiben_gongzi_key'			 => null,
            'jintie_key'			     => null,
            'zhiliangkaohe_key'			 => null,
            'jixiao_key'			     => null,
            'chaoejiangli_key'			 => null,
            'jiben_heji_key'             => null,
            'quanqinjiang_key'			 => null,
            'jiabanbutie_key'			 => null,
            'kaoqin_heji_key'            => null,
            'queqinkoukuan_key'			 => null,
            'qingjiakoukuan_key'		 => null,
            'chidaokoukuan_key'			 => null,
            'koukuan_heji_key'           => null,
            'gongzi_heji_key'            => null,
            'shangnianpingjun_key'		 => null,
            'yanglao_geren_key'			 => null,
            'yiliao_geren_key'			 => null,
            'shiye_geren_key'			 => null,
            'gongshang_geren_key'		 => null,
            'shengyu_geren_key'			 => null,
            'gongjijin_geren_key'		 => null,
            'geren_heji_key'             => null,
            'yingshui_gongzi_key'		 => null,
            'geshui_key'		         => null,
            'yanglao_gongsi_key'		 => null,
            'yiliao_gongsi_key'			 => null,
            'shiye_gongsi_key'			 => null,
            'gongshang_gongsi_key'		 => null,
            'shengyu_gongsi_key'		 => null,
            'gongjijin_gongsi_key'		 => null,
            'gongsi_heji_key'            => null,
            'yingchuqin_key'	    	 => null,
            'shijichuqin_key'			 => null,
            'canbu_key'			         => null,
            'bingjia_xiaoshi_key'		 => null,
            'bingjia_tianshu_key'		 => null,
            'bingjia_kouchu_key'		 => null,
            'shijia_xiaoshi_key'		 => null,
            'shijia_tianshu_key'		 => null,
            'shijia_kouchu_key'			 => null,
            'fadingjiari_key'			 => null,
            'remark_key'		         => null,
        );

        $data = $phpExcel->parseFile($newFilePath);

        $sheetIndex = array_search($sheetName, $data['sheetNames']);

        $insuranceBasis = $this->model('sinhoWorkload')
                               ->fetch_one('sinho_insurance_basis', 'basis_number', 'belong_year_month<='.$belongMonth, 'belong_year_month DESC');
        $this->model('sinhoWorkload')->delete(sinhoWorkloadModel::FINANCE_DATA_TABLE, 'varname="salary" and belong_year_month = "' . $this->model()->quote($belongMonth) .'"');
        $this->model('sinhoWorkload')->insert(sinhoWorkloadModel::FINANCE_DATA_TABLE, array('varname'=>"salary", 'belong_year_month'=>$belongMonth, 'data_json'=>json_encode($data['sheetDatas'][$sheetIndex], JSON_UNESCAPED_UNICODE)));

        $totalImport = 0; // 导入的数据条数
        $debug = array();
        foreach ($data['sheetDatas'][$sheetIndex] as $_rowNumber=>$dataLine) {
            if (1==$_rowNumber) { // 表头行， 忽略
                foreach ($keynameList as $_keyname=>$_value) {
                    if (! is_array(${$_keyname})) { // 待搜索的键名称为字符串，直接搜索表头获取对应的列

                        $keynameList[$_keyname] = array_search(${$_keyname}, $dataLine);
                        continue;
                    }

                    // 待搜索的键名为数组。 说明表头中有多个列能对应上， 根据指定的位置获取唯一列
                    $_i = 1;
                    foreach ($dataLine as $_columnName => $_v) {
                        //$debug .=  ${$_keyname}['name'] ." == $_v,".$_i . '='.${$_keyname}['index'] . ';';
                        if (${$_keyname}['name'] == $_v && $_i==${$_keyname}['index']) {
                            $keynameList[$_keyname] = $_columnName;
                            $_i++;
                        } else if (${$_keyname}['name'] == $_v) {
                            $_i++;
                        }
                    }
                }

                continue;
            }

            foreach ($dataLine as $_key => $_value) {
                // 去空格， 替换全角空格
                $dataLine[$_key] = trim(str_replace(' ', ' ', $_value));
            }

            $salaryData = array();
            foreach ($keynameList as $_keyname=>$_v) {
                if (! $_v) {
                    continue;
                }

                if ($dataLine[$keynameList[$_keyname]]==='') {
                    continue;
                }

                if ($_keyname=='user_id_key') {
                    $_username = $dataLine[$keynameList[$_keyname]];
                    $userId = $this->model('sinhoWorkload')
                                    ->fetch_one('users', 'uid', 'user_name = "' . $this->model()->quote($_username) . '"');
                    $salaryData['user_id'] = $userId;
                    continue;
                }


                $salaryData[substr($_keyname, 0, -4)] = $dataLine[$keynameList[$_keyname]];
            }
            // 没有序号内容， 或者没找到用户
            if (!is_numeric($dataLine['A']) || !$salaryData['user_id']) {
                break;
            }
            $salaryData['batch_key'] = $batchKey;
            $salaryData['belong_year_month'] = $belongMonth;
            $debug[] = $salaryData;
            $salaryData['gonghuijingfei_gongzi'] = $salaryData['shangnianpingjun'] >=$insuranceBasis ? $salaryData['shangnianpingjun'] : $insuranceBasis;
            $salaryData['gonghuijingfei_gongzi'] = $salaryData['yanglao_geren'] > 0 ? $salaryData['gonghuijingfei_gongzi'] : 0;

            $this->model('sinhoWorkload')
                 ->addSalaryDetail($salaryData);
        }
        $this->model('sinhoWorkload')->delete(sinhoWorkloadModel::SALARY_DETAIL_TABLE, 'belong_year_month = "' . $this->model()->quote($belongMonth) . '" AND batch_key != "' . $batchKey . '"');


        H::ajax_json_output(Application::RSM(
            array('url' => get_js_url('/admin/finance/salary/start_month-' . $belongMonth .'__end_month-'.$belongMonth)),
            1,
            Application::lang()->_t('工资导入成功！')
            )
        );

        return;

        echo htmlspecialchars(json_encode(array(
                        'success' => true,
                        'thumb'   => get_setting('upload_url') . $filePath,
                        'file'    => $filePath,
                        'newFilePath'    => $newFilePath,
                        'batch_key'      => $batchKey,
                        'sheet_names'    => $data['sheetNames'],
                        'keyname_list'   => $keynameList,
                        'sheetDatas'     => $data['sheetDatas'][$sheetIndex],
                        'debug'          => $debug,
        )), ENT_NOQUOTES);

    }

    /**
     * 导入收支
     */
    public function monthly_pay_do_import_action ()
    {
        $newFilePath = $_POST['filename'];      // 待导入的文件路径
        $sheetName   = $_POST['excel_sheets']; // 要导入的工作表
        $batchKey    = md5($this->user_id . rand(1, 1000000000) . microtime());;     // 批处理的key值
        $belongMonth = str_replace('-', '', $_POST['start_month']); // 数据归属月份

        $phpExcel    = & loadClass('Tools_Excel_PhpExcel');
        // 下面是 支出表和收入表入口关键词
        // 沈阳编校基地2022.3.1-3.31支出表
        // 沈阳编校基地2022.3.1-3.31收入表
        // 支出表和收入表的表头信息
        // 序号	项目	单价	数量	总价	发票日期(无发票的为交易日期)	备注
        // 序号	公司/机构			总价	日期	备注
        $data = $phpExcel->parseFile($newFilePath);
        $sheetIndex = 0; // 第一个表是收支明细表；

        // 解析Excel内容
        $debug = array();
        $direction = 0; // 收入支出方向： 1 收入， -1 支出；
        foreach ($data['sheetDatas'][$sheetIndex] as $_rowNumber=> $dataLine) {
            // 支出表 进入表头，设置收支方向为：支出
            if (strpos($dataLine['A'], '沈阳编校基地')!==false && strpos($dataLine['A'], '支出表')) {
                $direction = -1;
                continue;
            }
            // 收入表  进入表头，设置收支方向为： 收入
            if (strpos($dataLine['A'], '沈阳编校基地')!==false && strpos($dataLine['A'], '收入表')) {
                $direction = 1;
                continue;
            }

            foreach ($dataLine as $_key => $_value) {
                // 去空格， 替换全角空格
                $dataLine[$_key] = trim(str_replace(' ', ' ', $_value));
            }

            // 解析支出表表头
            if ($direction==-1 && $dataLine['A']=='序号') {
                $xiangmu_key  = array_search('项目', $dataLine);
                $danjia_key   = array_search('单价', $dataLine);
                $shuliang_key = array_search('数量', $dataLine);
                $zongjia_key  = array_search('总价', $dataLine);
                $riqi_key     = array_search('发票日期(无发票的为交易日期)', $dataLine);
                $beizhu_key   = array_search('备注', $dataLine);

                continue;
            }
            // 解析收入表表头
            if ($direction==1 && $dataLine['A']=='序号') {
                $gongsi_key  = array_search('公司/机构', $dataLine);
                $zongjia_key  = array_search('总价', $dataLine);
                $riqi_key     = array_search('日期', $dataLine);
                $beizhu_key   = array_search('备注', $dataLine);
                continue;
            }

            // 进入表体数据， 如果第一列不是数字， 继续处理下一行
            if (! is_numeric($dataLine['A'])) {
                continue;
            }

            // 解析数据， 存放数据库中
            $itemData = array('batch_key'=> $batchKey, 'belong_year_month'=> $belongMonth, 'direction'=>$direction);

            if (preg_match_all("/(\d+)\.(\d+)/", $dataLine[$riqi_key], $match) ) {
                $match[2][0] = strlen($match[2][0]) > 2 ? round(substr($match[2][0], 0, 3)/10, 0) : $match[2][0];
                $dataLine[$riqi_key] = $match[1][0] . "月" . $match[2][0] . '日';
            }
            $itemData['deal_date'] = is_numeric($dataLine[$riqi_key]) ? date('n月j日',($dataLine[$riqi_key]-2) * 24*60*60) : $dataLine[$riqi_key];
            $data['sheetDatas'][$sheetIndex][$_rowNumber][$riqi_key] = $itemData['deal_date'];
            $itemData['remark']    = $dataLine[$beizhu_key];
            if ($direction==1) { // 收入表
                $itemData['item_name'] = $dataLine[$gongsi_key];
                $itemData['price']     = $dataLine[$zongjia_key];
                $itemData['amount']    = 1;
                $itemData['has_receipt'] = 1;
            } else if ($direction==-1) { // 支出表
                $itemData['item_name'] = $dataLine[$xiangmu_key];
                $itemData['price']     = $dataLine[$danjia_key];
                $itemData['amount']    = $dataLine[$shuliang_key];
                $itemData['has_receipt'] = intval(! in_array($data['sheetStyles'][$sheetIndex][$_rowNumber]['B'], array('000000','FFFFFF')) );
            } else {
                continue;
            }

            $this->model('sinhoWorkload')
                 ->insert(sinhoWorkloadModel::INCOME_OUTPUT_TABLE, $itemData);
        }
        // 更新保存的excel数据信息
        $this->model('sinhoWorkload')->delete(sinhoWorkloadModel::FINANCE_DATA_TABLE, 'varname="income_output" and belong_year_month = "' . $this->model()->quote($belongMonth) .'"');
        $this->model('sinhoWorkload')->insert(sinhoWorkloadModel::FINANCE_DATA_TABLE, array('varname'=>"income_output", 'belong_year_month'=>$belongMonth, 'data_json'=>json_encode($data, JSON_UNESCAPED_UNICODE)));
        $this->model('sinhoWorkload')->delete(sinhoWorkloadModel::INCOME_OUTPUT_TABLE, 'belong_year_month = "' . $this->model()->quote($belongMonth) . '" AND batch_key != "' . $batchKey . '"');
        // 移除对应月份的非本次上传数据

        H::ajax_json_output(Application::RSM(
            array('url' => get_js_url('/admin/finance/monthly_pay/start_month-' . $belongMonth .'__end_month-'.$belongMonth)),
            1,
            Application::lang()->_t('数据导入成功！')
            )
        );

        return;

        echo htmlspecialchars(json_encode(array(
                        'success' => true,
                        'thumb'   => get_setting('upload_url') . $filePath,
                        'file'    => $filePath,
                        'newFilePath'    => $newFilePath,
                        'batch_key'      => $batchKey,
                        'sheet_names'    => $data['sheetNames'],
                        'keyname_list'   => $keynameList,
                        'sheetDatas'     => $data['sheetDatas'][$sheetIndex],
                        'debug'          => $debug,
        )), ENT_NOQUOTES);

    }

    /**
     * 批量删除书稿数据. 删除书稿同时， 将对应的工作量信息也删除
     */
    public function remove_action()
    {
        $this->checkPermission(self::IS_SINHO_BOOK_ADMIN);
        if (empty($_POST['ids'])) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('请选择书稿进行操作')));
        }
        Application::model()->delete(sinhoWorkloadModel::BOOK_TABLE, 'id IN( ' . join(', ', $_POST['ids']) . ')' );
        Application::model()->delete(sinhoWorkloadModel::WORKLOAD_TABLE, 'book_id IN( ' . join(', ', $_POST['ids']) . ')' );

        H::ajax_json_output(Application::RSM(null, 1, null));
    }


    /**
     * 设置书稿阶段属性
     */
    public function set_grade_action ()
    {
        $this->checkPermission(self::IS_SINHO_BOOK_ADMIN);

        if (! $_POST['book_id'] || ! isset($_POST['grade_level'])) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('请输入参数')));
        }
        if (! ($bookInfo = $this->model('sinhoWorkload')->getBookById($_POST['book_id']) ) ) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('书稿不存在')));
        }

        $set = array('grade_level'=>$_POST['grade_level']);
        $this->model('sinhoWorkload')
                ->update(sinhoWorkloadModel::BOOK_TABLE,
                $set,
                'id = ' . $bookInfo['id']
        );
        H::ajax_json_output(Application::RSM(null, 0, Application::lang()->_t('保存成功')));

    }
}

/* EOF */
