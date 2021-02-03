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
update icb_sinho_company_workload set delivery_date = '2020.10.28' where delivery_date = '';

update icb_sinho_company_workload set delivery_date =  FROM_UNIXTIME(unix_timestamp(delivery_date), '%Y-%m-%d');
*/

defined('iCodeBang_Com') OR die('Access denied!');
define('IN_AJAX', TRUE);

require_once __DIR__ . '/../../SinhoBaseController.php';

class books extends SinhoBaseController
{
    public function setup()
    {
        HTTP::setHeaderNoCache();
    }

    /**
     * 保存教程目录
     */
    public function save_action()
    {
        $this->checkPermission(self::IS_SINHO_FILL_WORKLOAD);

        if (!$_POST['serial'] && !$_POST['book_name'] && !$_POST['proofreading_times']) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('请输入参数')));
        }

        if ($_POST['id']) { // 更新
            Application::model('sinhoWorkload')->updateBook(intval($_POST['id']), $_POST);
            H::ajax_json_output(Application::RSM(array('url' => get_js_url('/admin/books/')), 1, Application::lang()->_t('教程保存成功')));
        } else { // 添加

            $_POST['delivery_date'] = strtotime($_POST['delivery_date'])>0 ? date('Y-m-d', strtotime($_POST['delivery_date'])) : date('Y-m-d');
            Application::model('sinhoWorkload')->addBook($_POST);

            H::ajax_json_output(Application::RSM(
                array('url' => get_js_url('/admin/books/')),
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
        $this->checkPermission(self::IS_SINHO_BOOK_ADMIN);

        if (! $_GET['id'] || ! $_POST['sinho_editor']) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('请输入参数')));
        }
        if (! ($bookInfo = $this->model('sinhoWorkload')->getBookById($_GET['id']) ) ) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('书稿不存在')));
        }

        $assigned = (array) $this->model('sinhoWorkload')->fetch_all(sinhoWorkloadModel::WORKLOAD_TABLE, 'book_id = ' . intval($_GET['id']) );
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
            $this->model('sinhoWorkload')
                 ->update(sinhoWorkloadModel::WORKLOAD_TABLE,
                            array('status' => sinhoWorkloadModel::STATUS_DELETE),
                            'id IN(' . join(',', $toBeRemoved). ') AND status = ' . sinhoWorkloadModel::STATUS_RECORDING
                    );
            //$this->model('sinhoWorkload')->deleteByIds ($toBeRemoved, sinhoWorkloadModel::WORKLOAD_TABLE);
        }

        foreach ($_POST['sinho_editor'] as $_userId) {
            if (! in_array($_userId, $assignedUserIds)) {
                $set = array('book_id' => $_GET['id'], 'user_id' => $_userId, 'status'=>sinhoWorkloadModel::STATUS_RECORDING);
                $this->model('sinhoWorkload')->insert(sinhoWorkloadModel::WORKLOAD_TABLE, $set);
            }
        }

        H::ajax_json_output(Application::RSM(null, 1, Application::lang()->_t('教程保存成功')));
    }

    public function assigned_action ()
    {
        $this->checkPermission(self::IS_SINHO_BOOK_ADMIN);

        if (! $_GET['id']) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('请输入参数')));
        }
        if (! ($bookInfo = $this->model('sinhoWorkload')->getBookById($_GET['id']) ) ) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('书稿不存在')));
        }

        $data = $this->model('sinhoWorkload')->fetch_all(sinhoWorkloadModel::WORKLOAD_TABLE, 'book_id = ' . intval($_GET['id']) );
        H::ajax_json_output(Application::RSM(array('data' => $data), 1, null));
    }


    /**
     * 设置书稿日期
     */
    public function set_date_action ()
    {
        $this->checkPermission(self::IS_SINHO_BOOK_ADMIN);


        if (! $_GET['id']) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('请输入参数')));
        }
        if (! ($bookInfo = $this->model('sinhoWorkload')->getBookById($_GET['id']) ) ) {
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
     *
     */
    public function import_action ()
    {

        $filename = 'upload_file';
        $dir = '/temp/' . gmdate('Ymd', APP_START_TIME);

        Application::upload()->initialize(array(
                        'allowed_types' => 'xls,xlsx',
                        'upload_path'   => get_setting('upload_dir') . $dir,
                        'is_image'      => true,
                        'max_size'      => get_setting('upload_avatar_size_limit')
        ));

        if (isset($_GET[$filename])) {
            Application::upload()->do_upload($_GET[$filename], file_get_contents('php://input'));
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
        $id_number_key                      = 'A'; // 序号
        $delivery_date_key                  = 'B'; // 发稿日期
        $return_date_key                    = 'C'; // 回稿日期
        $serial_key                         = 'D'; // 系列
        $book_name_key                      = 'E'; // 书名
        $proofreading_times_key             = 'F'; // 校次
        $content_table_pages_key            = 'G'; // 目录
        $text_pages_key                     = 'H'; // 成书
        $text_table_chars_per_page_key      = 'I'; // 千字/页
        $answer_pages_key                   = 'J'; // 答案
        $answer_chars_per_page_key          = 'K'; // 千字/页
        $test_pages_key                     = 'L'; // 试卷
        $test_chars_per_page_key            = 'M'; // 千字/页
        $test_answer_pages_key              = 'N'; // 试卷答案
        $test_answer_chars_per_page_key     = 'O'; // 千字/页
        $exercise_pages_key                 = 'P'; // 课后作业
        $exercise_chars_per_page_key        = 'Q'; // 千字/页
        $function_book_key                  = 'R'; // 功能册
        $function_book_chars_per_page_key   = 'S'; // 千字/页
        $function_answer_key                = 'T'; // 功能册答案
        $function_answer_chars_per_page_key = 'U'; // 千字/页
        $weight_key                         = 'V'; // 难度系数
        $total_chars_key                    = 'W'; // 字数（合计）
        $remarks_key                        = 'X'; // 备注
        $data = $phpExcel->parseFile($newFilePath);
        $batchKey = md5($this->user_id . rand(1, 1000000000) . microtime());
        $totalImport = 0; // 导入的数据条数

        $uniqueList = array(); // 有书稿会按照页码分多次提交。 将这样的书稿数据合并起来
        foreach($data['sheetDatas'] as $_index=>$dataList) {
            $sheetName = $data['sheetNames'][$_index];
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
                     ($dataLine[$total_chars_key ] ==='' && $dataLine[$weight_key] === '')  ||
                     ($dataLine[$serial_key]==='' && $dataLine[$book_name_key]==='' && $dataLine[$proofreading_times_key]==='' ) ||  // 系列，书名，校次均为空
                     ($dataLine[$serial_key]==='系列' && $dataLine[$book_name_key]==='书名' && $dataLine[$proofreading_times_key]==='校次')
                ) {

                    continue;
                }
                // 对重复的书稿， 做合并处理
                $_uniqueKey = $dataLine[$serial_key] . '/' . $dataLine[$book_name_key] . '/' . $dataLine[$proofreading_times_key];
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
                $dataLine[$delivery_date_key] = strtotime(str_replace('.','-',$dataLine[$delivery_date_key]))>0 ? date('Y-m-d', strtotime(str_replace('.','-',$dataLine[$delivery_date_key]))) : date('Y-m-d');
                // 根据系列，书名，校次获取书稿信息。
                $bookInfo = $this->model('sinhoWorkload')
                                 ->fetch_row(sinhoWorkloadModel::BOOK_TABLE,
                                             'serial            = "' . $this->model('sinhoWorkload')->quote($dataLine[$serial_key]) . '"
                                         AND book_name          = "' . $this->model('sinhoWorkload')->quote($dataLine[$book_name_key]) .'"
                                         AND proofreading_times = "' . $this->model('sinhoWorkload')->quote($dataLine[$proofreading_times_key]) .'"'

                                    ) ;
                //  核算字数， 保持小数点4位
                $dataLine[$total_chars_key] = sprintf('%.4f', round($dataLine[$total_chars_key], 4) );
                if ($bookInfo) { // 已存在书稿信息， 更新
                    $this->model('sinhoWorkload')
                         ->update(sinhoWorkloadModel::BOOK_TABLE,
                                  array(
                                    'id_number'                      => $dataLine[$id_number_key                      ],
                                    'delivery_date'                  => $dataLine[$delivery_date_key                  ],
                                    'return_date'                    => $dataLine[$return_date_key                    ],
                                    'serial'                         => $dataLine[$serial_key                         ],
                                    'book_name'                      => $dataLine[$book_name_key                      ],
                                    'proofreading_times'             => $dataLine[$proofreading_times_key             ],
                                    'content_table_pages'            => $dataLine[$content_table_pages_key            ],
                                    'text_pages'                     => $dataLine[$text_pages_key                     ],
                                    'text_table_chars_per_page'      => $dataLine[$text_table_chars_per_page_key      ],
                                    'answer_pages'                   => $dataLine[$answer_pages_key                   ],
                                    'answer_chars_per_page'          => $dataLine[$answer_chars_per_page_key          ],
                                    'test_pages'                     => $dataLine[$test_pages_key                     ],
                                    'test_chars_per_page'            => $dataLine[$test_chars_per_page_key            ],
                                    'test_answer_pages'              => $dataLine[$test_answer_pages_key              ],
                                    'test_answer_chars_per_page'     => $dataLine[$test_answer_chars_per_page_key     ],
                                    'exercise_pages'                 => $dataLine[$exercise_pages_key                 ],
                                    'exercise_chars_per_page'        => $dataLine[$exercise_chars_per_page_key        ],
                                    'function_book'                  => $dataLine[$function_book_key                  ],
                                    'function_book_chars_per_page'   => $dataLine[$function_book_chars_per_page_key   ],
                                    'function_answer'                => $dataLine[$function_answer_key                ],
                                    'function_answer_chars_per_page' => $dataLine[$function_answer_chars_per_page_key ],
                                    'weight'                         => $dataLine[$weight_key                         ],
                                    'total_chars'                    => $dataLine[$total_chars_key                    ],
                                    'remarks'                        => $dataLine[$remarks_key                        ],
                                    'modify_time'                    => time(),
                                    'sheet_name'                     => $sheetName,
                                    'batch_key'                      => $batchKey,
                                  ),
                                  array('id = ? ' => $bookInfo['id'])
                            );
                } else { // 没找到书稿， 添加新书稿
                    $this->model('sinhoWorkload')
                         ->insert(sinhoWorkloadModel::BOOK_TABLE,
                                  array(
                                    'id_number'                      => $dataLine[$id_number_key                      ],
                                    'delivery_date'                  => $dataLine[$delivery_date_key                  ],
                                    'return_date'                    => $dataLine[$return_date_key                    ],
                                    'serial'                         => $dataLine[$serial_key                         ],
                                    'book_name'                      => $dataLine[$book_name_key                      ],
                                    'proofreading_times'             => $dataLine[$proofreading_times_key             ],
                                    'content_table_pages'            => $dataLine[$content_table_pages_key            ],
                                    'text_pages'                     => $dataLine[$text_pages_key                     ],
                                    'text_table_chars_per_page'      => $dataLine[$text_table_chars_per_page_key      ],
                                    'answer_pages'                   => $dataLine[$answer_pages_key                   ],
                                    'answer_chars_per_page'          => $dataLine[$answer_chars_per_page_key          ],
                                    'test_pages'                     => $dataLine[$test_pages_key                     ],
                                    'test_chars_per_page'            => $dataLine[$test_chars_per_page_key            ],
                                    'test_answer_pages'              => $dataLine[$test_answer_pages_key              ],
                                    'test_answer_chars_per_page'     => $dataLine[$test_answer_chars_per_page_key     ],
                                    'exercise_pages'                 => $dataLine[$exercise_pages_key                 ],
                                    'exercise_chars_per_page'        => $dataLine[$exercise_chars_per_page_key        ],
                                    'function_book'                  => $dataLine[$function_book_key                  ],
                                    'function_book_chars_per_page'   => $dataLine[$function_book_chars_per_page_key   ],
                                    'function_answer'                => $dataLine[$function_answer_key                ],
                                    'function_answer_chars_per_page' => $dataLine[$function_answer_chars_per_page_key ],
                                    'weight'                         => $dataLine[$weight_key                         ],
                                    'total_chars'                    => $dataLine[$total_chars_key                    ],
                                    'remarks'                        => $dataLine[$remarks_key                        ],
                                    'add_time'                       => time(),
                                    'sheet_name'                     => $sheetName,
                                    'batch_key'                      => $batchKey,
                                  )
                        );
                }

                $prevInfo = $dataLine;
                $totalImport++;
            }
        }


        echo htmlspecialchars(json_encode(array(
                        'success' => true,
                        'thumb'   => get_setting('upload_url') . $filePath,
                        'file'    => $filePath,
                        'newFilePath'    => $newFilePath,
                        'batch_key'      => $batchKey,
                        'sheet_names'    => $data['sheetNames'],
                        'total_import'   => $totalImport,
        )), ENT_NOQUOTES);

    }

    /**
     * 将导入的书稿，
     */
    public function set_payed_action ()
    {
        $this->checkPermission(self::IS_SINHO_BOOK_ADMIN);
        if (empty($_POST['batch_key'])) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('操作错误')));
        }
        $this->model('sinhoWorkload')
             ->update(sinhoWorkloadModel::BOOK_TABLE,
                    array('is_payed'=>0),
                    array('batch_key = ? ' => $_POST['batch_key'])
            );
        if ($_POST['payed_sheets']) {
            foreach ($_POST['payed_sheets'] as $_sheetName) {
                $this->model('sinhoWorkload')
                     ->update(sinhoWorkloadModel::BOOK_TABLE,
                              array('is_payed'=>1),
                              array('batch_key = ? ' => $_POST['batch_key'],
                                    'sheet_name = ? ' => $_sheetName
                              )
                    );
            }
        }


        H::ajax_json_output(Application::RSM(
            array('url' => get_js_url('/admin/books/')),
            1,
            Application::lang()->_t('书稿导入成功')));

    }



    /**
     * 批量删除教程数据
     */
    public function remove_action()
    {
        $this->checkPermission(self::IS_SINHO_BOOK_ADMIN);
        if (empty($_POST['ids'])) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('请选择书稿进行操作')));
        }
        Application::model()->delete(sinhoWorkloadModel::BOOK_TABLE, 'id IN( ' . join(', ', $_POST['ids']) . ')' );

        H::ajax_json_output(Application::RSM(null, 1, null));
    }
}

/* EOF */
