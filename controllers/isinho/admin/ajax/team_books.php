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

class team_books extends SinhoBaseController
{
    protected $bookSubjectList = array();

    public function setup()
    {
        $this->checkPermission(self::IS_SINHO_TEAM_LEADER);
        HTTP::setHeaderNoCache();

        $this->user_info['sinho_manage_subject'] = @json_decode($this->user_info['sinho_manage_subject'], true);

        if (! $this->user_info['sinho_manage_subject']) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('你没有管理任何学科的权限')));
        }

        $bookSubjectList = $this->model()->fetch_all('sinho_book_category');
        $this->bookSubjectList = array_combine(array_column($bookSubjectList, 'id'), $bookSubjectList);

    }

    /**
     * 保存书稿
     */
    public function save_action()
    {

        if (!$_POST['serial'] && !$_POST['book_name'] && !$_POST['proofreading_times']) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('请输入参数')));
        }
        // 查找是否已存在相同书稿。 已存在相同书稿， 提示
        $itemInfo = Application::model('sinhoWorkload')->fetch_row(
            sinhoWorkloadModel::BOOK_TABLE,
            'delivery_date         = "' . $this->model('sinhoWorkload')->quote($_POST['delivery_date']) . '"
            AND category           = "' . $this->model('sinhoWorkload')->quote($_POST['category']) . '"
            AND serial             = "' . $this->model('sinhoWorkload')->quote($_POST['serial']) . '"
            AND book_name          = "' . $this->model('sinhoWorkload')->quote($_POST['book_name'] ) .'"
            AND proofreading_times = "' . $this->model('sinhoWorkload')->quote($_POST['proofreading_times']) .'"'

        ) ;
        if ($itemInfo && $itemInfo['id']!=$_POST['id']) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('已存在系列、书名、校次完成相同的书稿')));
        }

        // 解析每个学科中用于搜索书名匹配的关键字。 匹配到关键字， 将书稿设置成对应的学科
        $keywordSubjectList = array();
        $keywordSubjectList1 = array();
        $bookSubjectList = $this->model()->fetch_all('sinho_book_category');
        $bookSubjectList = array_combine(array_column($bookSubjectList, 'id'), $bookSubjectList);
        foreach ($bookSubjectList as $_subjectCode => $_itemInfo) {
            $_itemInfo['keyword'] = explode(',', $_itemInfo['remark']);
            foreach ($_itemInfo['keyword'] as $_keyword) {
                if (mb_strlen($_keyword)==1) {
                    $keywordSubjectList1[$_keyword] = $_subjectCode;
                } else {
                    $keywordSubjectList[$_keyword] = $_subjectCode;
                }
            }
        }
        $keywordSubjectList = array_merge($keywordSubjectList, $keywordSubjectList1);

        $backurl = empty($_POST['backUrl']) ? get_js_url('/admin/team_books/') : base64_decode($_POST['backUrl']) ;
        $_POST['is_import'] = 0; // 书稿设置为手动录入， 非导入
        if ($_POST['id']) { // 更新
            $itemInfo = $this->model('sinhoWorkload')->getBookById($_POST['id']);
            if (!$itemInfo || (!in_array($itemInfo['category_id'], $this->user_info['sinho_manage_subject']) && $itemInfo['user_id']!=$this->user_id) ) {

                H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('书稿不存在')));
            }
            Application::model('sinhoWorkload')->updateBook(intval($_POST['id']), $_POST);
            H::ajax_json_output(Application::RSM(array('url' => $backurl), 1, Application::lang()->_t('书稿保存成功')));
        } else { // 添加
            $_POST['user_id']       = $this->user_id;
            $_POST['delivery_date'] = strtotime($_POST['delivery_date'])>0 ? date('Y-m-d', strtotime($_POST['delivery_date'])) : date('Y-m-d');


            // 获取书稿所属学科id
            if (! $_POST['category_id']) {
                $_POST['category_id'] = null;
                foreach ($keywordSubjectList as $_keyword=>$_subjectCode) {
                    if (strpos($_POST['book_name'], $_keyword)!==false) {
                        $_POST['category_id'] = $_subjectCode;
                        break;
                    }
                }
            }

            Application::model('sinhoWorkload')->addBook($_POST);

            H::ajax_json_output(Application::RSM(
                array('url' => get_js_url('/admin/team_books/')),
                1,
                Application::lang()->_t('书稿添加成功')));
        }
    }

    /**
     * 将书稿分配给编辑。 可以重新分配已经分配过的书稿。
     * 需要检查书稿是否已经记录了工作量
     */
    public function assign_action ()
    {
        if (! $_GET['id'] || $_POST['action']!='assign') {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('请输入参数')));
        }

        if (! ($bookInfo = $this->model('sinhoWorkload')->getBookById($_GET['id']) )
        || (!in_array($bookInfo['category_id'], $this->user_info['sinho_manage_subject']) && $bookInfo['user_id']!=$this->user_id)) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('书稿不存在')));
        }

        if (! $_POST['sinho_editor']) {
            $_POST['sinho_editor'] = array();
        }

        $assigned = (array) $this->model('sinhoWorkload')->fetch_all(sinhoWorkloadModel::WORKLOAD_TABLE, 'book_id = ' . intval($_GET['id']) .' AND status <> ' . sinhoWorkloadModel::STATUS_DELETE );
        $assignedUserIds = array_column($assigned, 'user_id');
        $toBeRemoved = array();
        foreach ($assigned as $_itemInfo) {
            if (in_array($_itemInfo['user_id'], $_POST['sinho_editor'])) {
                continue;
            }

            if ($_itemInfo['content_table_pages']!=0 || $_itemInfo['text_pages']!=0 || $_itemInfo['answer_pages']!=0
              || $_itemInfo['test_pages']!=0 || $_itemInfo['test_answer_pages']!=0 || $_itemInfo['exercise_pages']!=0
              || $_itemInfo['function_book']!=0 || $_itemInfo['function_answer']!=0  ) {
                $userInfo = $this->model('account')->getUserById($_itemInfo['user_id']);
                H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('*'.$userInfo['user_name'] . '* 已经在书稿上开始了工作，不能取消分配！')));
            }

            $toBeRemoved[] = $_itemInfo['id'];
        }
        if ($toBeRemoved) { // 取消绑定， 设置成删除状态
            // $this->model('sinhoWorkload')
            //      ->update(sinhoWorkloadModel::WORKLOAD_TABLE,
            //                 array('status' => sinhoWorkloadModel::STATUS_DELETE),
            //                 'id IN(' . join(',', $toBeRemoved). ')' // AND status = ' . sinhoWorkloadModel::STATUS_RECORDING
            //         );
            $this->model('sinhoWorkload')->deleteByIds ($toBeRemoved, sinhoWorkloadModel::WORKLOAD_TABLE);
        }

        foreach ($_POST['sinho_editor'] as $_userId) {
            if (! in_array($_userId, $assignedUserIds)) {
                $set = array(
                    'book_id'   => $_GET['id'],
                    'user_id'   => $_userId,
                    'status'    => sinhoWorkloadModel::STATUS_RECORDING,
                    'add_time'  => time(),
                );
                $this->model('sinhoWorkload')->insert(sinhoWorkloadModel::WORKLOAD_TABLE, $set);
            }
        }

        H::ajax_json_output(Application::RSM(array('url'=>''), 1, Application::lang()->_t('分配书稿成功')));
    }

    /**
     * 分配书稿到编辑/取消分配
     */
    public function assigned_action ()
    {
        if (! $_GET['id']) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('请输入参数')));
        }
        if (! ($bookInfo = $this->model('sinhoWorkload')->getBookById($_GET['id']) )
           || (!in_array($bookInfo['category_id'], $this->user_info['sinho_manage_subject']) && $bookInfo['user_id']!=$this->user_id)) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('书稿不存在'.$this->user_info['sinho_manage_subject'])));
        }

        $data = $this->model('sinhoWorkload')->fetch_all(sinhoWorkloadModel::WORKLOAD_TABLE, 'book_id = ' . intval($_GET['id']) .' AND status <> ' . sinhoWorkloadModel::STATUS_DELETE );
        H::ajax_json_output(Application::RSM(array('data' => $data), 1, null));
    }


    /**
     * 设置书稿日期
     */
    public function set_date_action ()
    {
        if (! $_GET['id']) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('请输入参数')));
        }
        if (! ($bookInfo = $this->model('sinhoWorkload')->getBookById($_GET['id']) )
        || (!in_array($bookInfo['category_id'], $this->user_info['sinho_manage_subject']) && $bookInfo['user_id']!=$this->user_id)) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('书稿不存在')));
        }

        $set = array();
        if ($_POST['delivery_date']) {
            $set['delivery_date'] = $_POST['delivery_date'];
        }
        if ($_POST['return_date']) {
            $set['return_date'] = $_POST['return_date'];
        }
        if ($set) {
            $this->model('sinhoWorkload')
                 ->update(sinhoWorkloadModel::BOOK_TABLE,
                    $set,
                    'id = ' . $bookInfo['id']
            );
            H::ajax_json_output(Application::RSM(null, 1, Application::lang()->_t('日期保存成功')));

        } else {

            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('操作错误！')));
        }

    }

    /**
     * 导入书稿
     */
    public function import_action ()
    {

        $filename = 'upload_file';
        $dir = '/temp/' . gmdate('Ymd', APP_START_TIME);

        Application::upload()->initialize(array(
                        'allowed_types' => 'xls,xlsx',
                        'upload_path'   => get_setting('upload_dir') . $dir,
                        'is_image'      => true,
                        'max_size'      => get_setting('upload_size_limit')
        ));

        if (isset($_GET[$filename]) && ($content=file_get_contents('php://input') && $content!==false)) {
            Application::upload()->do_upload($_GET[$filename], $content);
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
                        'success'        => true,
                        'thumb'          => get_setting('upload_url') . $filePath,
                        'file'           => $filePath,
                        'newFilePath'    => $newFilePath,
                        'batch_key'      => $batchKey,
                        'sheet_names'    => $data['sheetNames'],
        )), ENT_NOQUOTES);

    }

    /**
     * 导入书稿
     */
    public function do_import_action ()
    {
        $newFilePath = $_POST['filename'];
        $phpExcel = & loadClass('Tools_Excel_PhpExcel');
        $id_number_key                      = 'A'; // 序号
        $delivery_date_key                  = 'B'; // 发稿日期
        $return_date_key                    = 'C'; // 回稿日期
        $category_key                       = 'D'; // 类别
        $serial_key                         = 'E'; // 系列
        $book_name_key                      = 'F'; // 书名
        $proofreading_times_key             = 'G'; // 校次
        $content_table_pages_key            = 'H'; // 目录
        $text_pages_key                     = 'I'; // 成书
        $text_table_chars_per_page_key      = 'J'; // 千字/页
        $answer_pages_key                   = 'K'; // 答案
        $answer_chars_per_page_key          = 'L'; // 千字/页
        $test_pages_key                     = 'M'; // 试卷
        $test_chars_per_page_key            = 'N'; // 千字/页
        $test_answer_pages_key              = 'O'; // 试卷答案
        $test_answer_chars_per_page_key     = 'P'; // 千字/页
        $exercise_pages_key                 = 'Q'; // 课后作业
        $exercise_chars_per_page_key        = 'R'; // 千字/页
        $function_book_key                  = 'S'; // 功能册
        $function_book_chars_per_page_key   = 'T'; // 千字/页
        $function_answer_key                = 'U'; // 功能册答案
        $function_answer_chars_per_page_key = 'V'; // 千字/页
        $weight_key                         = 'W'; // 难度系数
        $total_chars_key                    = 'X'; // 字数（合计）
        $remarks_key                        = 'Y'; // 备注
        $data = $phpExcel->parseFile($newFilePath);
        $batchKey = md5($this->user_id . rand(1, 1000000000) . microtime());
        $totalImport = 0; // 导入的数据条数


        // 解析每个学科中用于搜索书名匹配的关键字。 匹配到关键字， 将书稿设置成对应的学科
        $keywordSubjectList = array();
        $keywordSubjectList1 = array();
        $bookSubjectList = $this->model()->fetch_all('sinho_book_category');
        $bookSubjectList = array_combine(array_column($bookSubjectList, 'id'), $bookSubjectList);
        foreach ($bookSubjectList as $_subjectCode => $_itemInfo) {
            $_itemInfo['keyword'] = explode(',', $_itemInfo['remark']);
            foreach ($_itemInfo['keyword'] as $_keyword) {
                if (mb_strlen($_keyword)==1) {
                    $keywordSubjectList1[$_keyword] = $_subjectCode;
                } else {
                    $keywordSubjectList[$_keyword] = $_subjectCode;
                }
            }
        }
        $keywordSubjectList = array_merge($keywordSubjectList, $keywordSubjectList1);

        $uniqueList = array(); // 有书稿会按照页码分多次提交。 将这样的书稿数据合并起来
        foreach($data['sheetDatas'] as $_index=>$dataList) {
            $sheetName = $data['sheetNames'][$_index];
            if (! in_array($sheetName, $_POST['payed_sheets'])) {
                continue;
            }
            $prevInfo = null;
            foreach ($dataList as $_rowNumber=>$dataLine) {
                if (1==$_rowNumber) { // 表头行， 忽略
                    continue;
                }
                foreach ($dataLine as $_key => $_value) {
                    // 去空格， 替换全角空格
                    $dataLine[$_key] = trim(str_replace(' ', ' ', $_value));
                }
                // 总字数为0， 权重为0 ， 不合法数据
                if (is_null($dataLine[$total_chars_key ]) || is_null($dataLine[$weight_key]) ||    // 权重为空
                     //($dataLine[$total_chars_key ] ==='' && $dataLine[$weight_key] === '')  ||
                     ($dataLine[$serial_key]==='' && $dataLine[$book_name_key]==='' && $dataLine[$proofreading_times_key]==='' ) ||  // 系列，书名，校次均为空
                     ($dataLine[$serial_key]==='系列' && $dataLine[$book_name_key]==='书名' && $dataLine[$proofreading_times_key]==='校次')
                ) {

                    continue;
                }
                // 对重复的书稿， 做合并处理
                $_uniqueKey = $dataLine[$delivery_date_key] . '/' . $dataLine[$category_key] . '/' . $dataLine[$serial_key] . '/' . $dataLine[$book_name_key] . '/' . $dataLine[$proofreading_times_key];
                if (isset($uniqueList[$_uniqueKey]) ) {
                    $dataLine[$content_table_pages_key            ] += doubleval($uniqueList[$_uniqueKey][$content_table_pages_key            ]);
                    $dataLine[$text_pages_key                     ] += doubleval($uniqueList[$_uniqueKey][$text_pages_key                     ]);
                    $dataLine[$answer_pages_key                   ] += doubleval($uniqueList[$_uniqueKey][$answer_pages_key                   ]);
                    $dataLine[$test_pages_key                     ] += doubleval($uniqueList[$_uniqueKey][$test_pages_key                     ]);
                    $dataLine[$test_answer_pages_key              ] += doubleval($uniqueList[$_uniqueKey][$test_answer_pages_key              ]);
                    $dataLine[$exercise_pages_key                 ] += doubleval($uniqueList[$_uniqueKey][$exercise_pages_key                 ]);
                    $dataLine[$function_book_key                  ] += doubleval($uniqueList[$_uniqueKey][$function_book_key                  ]);
                    $dataLine[$function_answer_key                ] += doubleval($uniqueList[$_uniqueKey][$function_answer_key                ]);
                    $dataLine[$total_chars_key                    ] += doubleval($uniqueList[$_uniqueKey][$total_chars_key                    ]);
                    $dataLine[$remarks_key                        ] .=  ';;;' . $uniqueList[$_uniqueKey][$remarks_key                        ];
                }
                $uniqueList[$_uniqueKey] = $dataLine;
                if (! isset($prevInfo)) {
                    $prevInfo = $dataLine;
                }
                // 考虑系列和书名存在合并单元格情况
                $dataLine[$id_number_key]==='' && $dataLine[$book_name_key]==='' && $dataLine[$serial_key]===''
                                               && $dataLine[$delivery_date_key]===''
                    AND $dataLine[$book_name_key] = $prevInfo[$book_name_key];
                $dataLine[$id_number_key]==='' && $dataLine[$serial_key]===''
                                                && $dataLine[$delivery_date_key]===''
                    AND $dataLine[$serial_key] = $prevInfo[$serial_key];
                $dataLine[$id_number_key]==='' && $dataLine[$return_date_key]===''
                    AND $dataLine[$return_date_key] = $prevInfo[$return_date_key];
                $dataLine[$id_number_key]==='' && $dataLine[$delivery_date_key]===''
                    AND $dataLine[$delivery_date_key] = $prevInfo[$delivery_date_key];
                $dataLine[$id_number_key]==='' AND $dataLine[$id_number_key]=$prevInfo[$id_number_key];

                // 发稿日期 字符串不包含年份， 需要将年份处理下
                $dataLine[$delivery_date_key] = str_replace(array('.','年','月','日', ' ',' '),array('-','-','-','','',''),$dataLine[$delivery_date_key]);
                $dataLine[$return_date_key] = str_replace(array('.','年','月','日', ' ',' '),array('-','-','-','','',''),$dataLine[$return_date_key]);
                if (strlen($dataLine[$delivery_date_key]) < 6 && strlen($dataLine[$delivery_date_key]) > 0 ) {
                    // 添加上年份后的日期，在当前日期之后， 年份减1
                    if (strtotime(date('Y') . '-' . $dataLine[$delivery_date_key]) > time()) {
                        $dataLine[$delivery_date_key] = (date('Y')-1) . '-' . $dataLine[$delivery_date_key];
                    } else {
                        $dataLine[$delivery_date_key] = date('Y') . '-' . $dataLine[$delivery_date_key];
                    }
                }
                if (strlen($dataLine[$return_date_key]) < 6 && strlen($dataLine[$return_date_key]) > 0 ) {
                    // 添加上年份后的日期，在当前日期之后， 年份减1
                    if (strtotime(date('Y') . '-' . $dataLine[$return_date_key]) > time()) {
                        $dataLine[$return_date_key] = (date('Y')-1) . '-' . $dataLine[$return_date_key];
                    } else {
                        $dataLine[$return_date_key] = date('Y') . '-' . $dataLine[$return_date_key];
                    }
                }

                $dataLine[$delivery_date_key] = strtotime($dataLine[$delivery_date_key])>0 ? date('Y-m-d', strtotime($dataLine[$delivery_date_key])) : date('Y-m-d');
                // 根据系列，书名，校次获取书稿信息。
                $bookInfo = $this->model('sinhoWorkload')
                                 ->fetch_row(sinhoWorkloadModel::BOOK_TABLE,
                                        'delivery_date           = "' . $this->model('sinhoWorkload')->quote($dataLine[$delivery_date_key]) . '"
                                         AND category            = "' . $this->model('sinhoWorkload')->quote($dataLine[$category_key]) . '"
                                         AND serial              = "' . $this->model('sinhoWorkload')->quote($dataLine[$serial_key]) . '"
                                         AND book_name           = "' . $this->model('sinhoWorkload')->quote($dataLine[$book_name_key]) .'"
                                         AND proofreading_times  = "' . $this->model('sinhoWorkload')->quote($dataLine[$proofreading_times_key]) .'"'

                                    ) ;

                //  核算字数， 保持小数点4位
                $dataLine[$total_chars_key] = sprintf('%.4f', round($dataLine[$total_chars_key], 4) );
                $bookData = array(
                    'id_number'                      => $dataLine[$id_number_key                      ],
                    'delivery_date'                  => $dataLine[$delivery_date_key                  ],
                    'return_date'                    => $dataLine[$return_date_key                    ],
                    'category'                       => $dataLine[$category_key                       ],
                    'serial'                         => $dataLine[$serial_key                         ],
                    'book_name'                      => $dataLine[$book_name_key                      ],
                    'proofreading_times'             => $dataLine[$proofreading_times_key             ],
                    'content_table_pages'            => $dataLine[$content_table_pages_key            ],
                    'text_pages'                     => rtrim(rtrim(bcdiv(round( $dataLine[$text_pages_key                     ] * 100000), 100000, 4), 0), '.'),
                    'text_table_chars_per_page'      => rtrim(rtrim(bcdiv(round( $dataLine[$text_table_chars_per_page_key      ] * 100000), 100000, 4), 0), '.'),
                    'answer_pages'                   => rtrim(rtrim(bcdiv(round( $dataLine[$answer_pages_key                   ] * 100000), 100000, 4), 0), '.'),
                    'answer_chars_per_page'          => rtrim(rtrim(bcdiv(round( $dataLine[$answer_chars_per_page_key          ] * 100000), 100000, 4), 0), '.'),
                    'test_pages'                     => rtrim(rtrim(bcdiv(round( $dataLine[$test_pages_key                     ] * 100000), 100000, 4), 0), '.'),
                    'test_chars_per_page'            => rtrim(rtrim(bcdiv(round( $dataLine[$test_chars_per_page_key            ] * 100000), 100000, 4), 0), '.'),
                    'test_answer_pages'              => rtrim(rtrim(bcdiv(round( $dataLine[$test_answer_pages_key              ] * 100000), 100000, 4), 0), '.'),
                    'test_answer_chars_per_page'     => rtrim(rtrim(bcdiv(round( $dataLine[$test_answer_chars_per_page_key     ] * 100000), 100000, 4), 0), '.'),
                    'exercise_pages'                 => rtrim(rtrim(bcdiv(round( $dataLine[$exercise_pages_key                 ] * 100000), 100000, 4), 0), '.'),
                    'exercise_chars_per_page'        => rtrim(rtrim(bcdiv(round( $dataLine[$exercise_chars_per_page_key        ] * 100000), 100000, 4), 0), '.'),
                    'function_book'                  => rtrim(rtrim(bcdiv(round( $dataLine[$function_book_key                  ] * 100000), 100000, 4), 0), '.'),
                    'function_book_chars_per_page'   => rtrim(rtrim(bcdiv(round( $dataLine[$function_book_chars_per_page_key   ] * 100000), 100000, 4), 0), '.'),
                    'function_answer'                => rtrim(rtrim(bcdiv(round( $dataLine[$function_answer_key                ] * 100000), 100000, 4), 0), '.'),
                    'function_answer_chars_per_page' => rtrim(rtrim(bcdiv(round( $dataLine[$function_answer_chars_per_page_key ] * 100000), 100000, 4), 0), '.'),
                    'weight'                         => $dataLine[$weight_key                         ],
                    'total_chars'                    => $dataLine[$total_chars_key                    ],
                    'remarks'                        => $dataLine[$remarks_key                        ],
                    'sheet_name'                     => $sheetName,
                    'batch_key'                      => $batchKey,
                );
                foreach ($bookData as $_tmpKey=>$_tmpValue) {
                    if ($_tmpValue==='0') {
                        $bookData[$_tmpKey] = '';
                    }
                }
                // 获取书稿所属学科id
                $bookData['category_id'] = 0;
                foreach ($keywordSubjectList as $_keyword=>$_subjectCode) {
                    if (strpos($bookData['book_name'], $_keyword)!==false) {
                        $bookData['category_id'] = $_subjectCode;
                        break;
                    }
                }

                if ($bookInfo) { // 已存在书稿信息， 更新
                    $bookInfo['user_id'] == 0 AND $bookData['user_id'] = $this->user_id;
                    if (1==$bookInfo['is_import']) { // 只有导入的数据可以更新
                        $bookData['modify_time']                    = time();
                        $this->model('sinhoWorkload')
                            ->update(sinhoWorkloadModel::BOOK_TABLE,
                                    $bookData,
                                    array('id = ? ' => $bookInfo['id'])
                                );
                    }
                } else { // 没找到书稿， 添加新书稿
                    $bookData['add_time']                   = time();
                    $bookData['user_id']                    = $this->user_id;
                    $this->model('sinhoWorkload')
                         ->insert(sinhoWorkloadModel::BOOK_TABLE, $bookData);
                }

                $prevInfo = $dataLine;
                $totalImport++;
            }
        }


        H::ajax_json_output(Application::RSM(
            array('url' => get_js_url('/admin/team_books/')),
            1,
            Application::lang()->_t('书稿导入成功。 共导入书稿：' . $totalImport)));

    }

    /**
     * 批量删除书稿数据. 删除书稿同时， 将对应的工作量信息也删除
     */
    public function set_book_category_action()
    {
        if (empty($_POST['ids'])) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('请选择书稿进行操作')));
        }
        if (empty($_POST['category_id'])) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('请选择学科进行操作')));
        }

        $bookList = $this->model('sinhoWorkload')->getBookList ('id IN ( ' . join(',' , $_POST['ids']) . ' )', null, count($_POST['ids']));
        foreach ($bookList as $bookInfo) {
            if (!in_array($bookInfo['category_id'], $this->user_info['sinho_manage_subject']) && $bookInfo['user_id']!=$this->user_id) {
                H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('书稿不存在')));
            }
        }

        $set = array('category_id'=>$_POST['category_id']);
        $this->model('sinhoWorkload')
                ->update(sinhoWorkloadModel::BOOK_TABLE,
                $set,
                'id IN ( ' . join(',' , $_POST['ids']) . ' )'
        );

        H::ajax_json_output(Application::RSM(array('url'=>''), 1, Application::lang()->_t('学科已设置')));
    }

    /**
     * 批量删除书稿数据. 删除书稿同时， 将对应的工作量信息也删除
     */
    public function remove_action()
    {
        if (empty($_POST['ids'])) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('请选择书稿进行操作')));
        }

        $bookList = $this->model('sinhoWorkload')->getBookList ('id IN ( ' . join(',' , $_POST['ids']) . ' )', null, count($_POST['ids']));
        foreach ($bookList as $bookInfo) {
            if (!in_array($bookInfo['category_id'], $this->user_info['sinho_manage_subject']) && $bookInfo['user_id']!=$this->user_id) {
                H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('书稿不存在')));
            }
        }

        Application::model()->delete(sinhoWorkloadModel::BOOK_TABLE, 'id IN( ' . join(', ', $_POST['ids']) . ')' );
        Application::model()->delete(sinhoWorkloadModel::WORKLOAD_TABLE, 'book_id IN( ' . join(', ', $_POST['ids']) . ')' );

        H::ajax_json_output(Application::RSM(array('url'=>''), 1, Application::lang()->_t('书稿已删除')));
    }


    /**
     * 设置书稿阶段属性
     */
    public function set_grade_action ()
    {
        if (! $_POST['book_id'] || ! isset($_POST['grade_level'])) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('请输入参数')));
        }
        if (! ($bookInfo = $this->model('sinhoWorkload')->getBookById($_POST['book_id']) )
        || (!in_array($bookInfo['category_id'], $this->user_info['sinho_manage_subject']) && $bookInfo['user_id']!=$this->user_id)) {
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
