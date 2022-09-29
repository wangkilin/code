<?php
defined('iCodeBang_Com') OR die('Access denied!');

class team_workload extends SinhoBaseController
{
    const PERMISSION_BOOKLIST = 'sinho_book_list';

    protected $bookSubjectList = array();
    public function setup()
    {
        $this->checkPermission(self::IS_SINHO_TEAM_LEADER);

        $this->user_info['sinho_manage_subject'] = @json_decode($this->user_info['sinho_manage_subject'], true);

        if (! $this->user_info['sinho_manage_subject']) {
            H::redirect_msg(Application::lang()->_t('你没有管理任何学科的权限'), 'admin/');
        }

        $bookSubjectList = $this->model()->fetch_all('sinho_book_category');
        $this->bookSubjectList = array_combine(array_column($bookSubjectList, 'id'), $bookSubjectList);

        View::assign('bookSubjectList', $this->bookSubjectList);

    }

    /**
     * 查看工作量
     */
    public function check_list_action ()
    {
        $this->checkPermission(self::IS_SINHO_TEAM_LEADER);
        $_GET['page'] = intval($_GET['page']) >0 ? intval($_GET['page']) : 1;

        $userList = array();
        $bookList = array();
        switch (strtolower($_GET['by'])) {
            case 'user':
                $this->check_by_user($userList, $bookList);
                break;
            case 'book':
            default:
                $_GET['by'] = 'book';
                $this->check_by_book($userList, $bookList);
                break;
        }

        View::assign('amountPerPage', $this->per_page);
        View::assign('userList', $userList);
        View::assign('bookList', $bookList);

        View::assign('menu_list', $this->filterAdminMenu($this->model('admin')->fetch_menu_list('admin/team_workload','sinho_admin_menu') ) );
        View::output('admin/workload/check');
    }

    /**
     * 按照用户查询工作量
     */
    protected function check_by_user (& $userList, & $bookList)
    {

        // 获取所有组， 解析组管理的学科。 然后获取到组id
        $groupIds = array(-1);
        $groupList = $this->model('account')->get_user_group_list(0, 1);
        foreach ($groupList as $itemInfo) {
            $itemInfo['permission'] = unserialize($itemInfo['permission']);
            if ($itemInfo['permission'] && in_array($itemInfo['permission']['sinho_subject'], $this->user_info['sinho_manage_subject']) ) {
                $groupIds[] = $itemInfo['group_id'];
            }
        }
        // 根据组id获取用户列表
        $userList = array();
        $userList = $this->model('sinhoWorkload')->fetch_all('users', 'group_id IN (' . join(', ', $groupIds) . ')') ;
        $userIds  = array_column($userList, 'uid');
        $userList = array_combine($userIds, $userList);

        $this->per_page = 30;
        $queryUserIds = array();
        //$where = 'status <> ' . sinhoWorkloadModel::STATUS_DELETE . ' AND status <> ' . sinhoWorkloadModel::STATUS_RECORDING;
        $where = 'status <> ' . sinhoWorkloadModel::STATUS_DELETE ;
        if ($_GET['id']) { // 解析用户id
            $queryUserIds = explode(',', $_GET['id']);
            foreach ($queryUserIds as & $_id) {
                $_id = intval($_id);
                in_array($_id, $userIds) OR $_id = 0;
            }
            // 获取指定用户数据
            $where .= ' AND user_id IN ( ' . join(', ',  $queryUserIds). ') ';
        } else {

            // 获取可管理用户数据
            $where .= ' AND user_id IN ( ' . join(', ',  $userIds). ') ';
        }
        $belongMonth = array();
        if ($_GET['start_month']) {
            $where .= ' AND (belong_month >= ' . intval($_GET['start_month']) . ' OR belong_month IS NULL )';
            $belongMonth['start'] = intval($_GET['start_month']);
        }
        if ($_GET['end_month'] && $_GET['end_month']!=date('Ym')) {
            $where .= ' AND belong_month <= ' . intval($_GET['end_month']);
            $belongMonth['end'] = intval($_GET['end_month']);
        }

        $allList = $this->model('sinhoWorkload')
                        ->fetch_page(sinhoWorkloadModel::WORKLOAD_TABLE,
                                    $where,
                                    'belong_month desc,user_id',
                                    $_GET['page'],
                                    $this->per_page
                            );
        $totalRows = $this->model('sinhoWorkload')->found_rows();
        $bookIds = array_column($allList, 'book_id');
        // 根据书稿id列表获取书稿信息
        $bookList = array();
        if ($bookIds) {
            $bookList = $this->model('sinhoWorkload')
                            ->fetch_all(sinhoWorkloadModel::BOOK_TABLE,
                                    'id IN (' . join(', ', $bookIds) . ')'
                                );
            $bookIds = array_column($bookList, 'id');
            $bookList = array_combine($bookIds, $bookList);
        }

        View::assign('itemOptions', buildSelectOptions($userList, 'user_name', 'uid', $queryUserIds ) );

        $url_param = array();
        foreach($_GET as $key => $val) {
            if (!in_array($key, array('app', 'c', 'act', 'page'))) {
                $url_param[] = $key . '-' . $val;
            }
        }
        // 生成页码导航
        View::assign('pagination', Application::pagination()->initialize(array(
            'base_url'   => get_js_url('/admin/team_workload/check_list/') . implode('__', $url_param),
            'total_rows' => $totalRows,
            'per_page'   => $this->per_page
        ))->create_links());

        $totalCharsList = array();
        if ($allList && ($totalRows / $this->per_page) <= $_GET['page'] || ($totalRows / $this->per_page)<=1) {
            $totalCharsList = $this->model('sinhoWorkload')
                                    ->getWorkloadStatByUserIds (
                                        $queryUserIds,
                                        array(sinhoWorkloadModel::STATUS_VERIFIED,
                                              sinhoWorkloadModel::STATUS_VERIFYING,
                                              sinhoWorkloadModel::STATUS_RECORDING
                                                ),
                                        $belongMonth,
                                        null  // 不按照user_id分组
                                    );
            //var_dump($totalCharsList);
        }
        View::assign('itemsList', $allList);
        View::assign('workloadList', $allList);
        View::assign('totalRows', $totalRows);
        View::assign('totalCharsList', array_pop($totalCharsList));

        View::import_js(G_STATIC_URL . '/js/bootstrap-multiselect.js');
        View::import_js('js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js');
        View::import_js('js/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js');
        View::import_css('js/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css');
        View::import_css(G_STATIC_URL . '/css/bootstrap-multiselect.css');
    }
    /**
     * 根据书稿查看工作量。 工作量在每个书稿下面排放
     * @param array $userList 用户列表变量占位符， 将变量数据填充回传
     * @param array $bookList 书稿列表变量占位符， 将变量数据填充回传
     */
    protected function check_by_book (& $userList, & $bookList)
    {
        // 每页显示10本书稿的工作量
        $this->per_page = 10;
        if (isset($_GET['id'])) { // 查询指定书稿的工作量
            $bookIds = array( intval($_GET['id']) );
            $totalRows = 1;

            $itemInfo = $this->model('sinhoWorkload')->getBookById($_GET['id']);

            if (!$itemInfo || (!in_array($itemInfo['category_id'], $this->user_info['sinho_manage_subject']) && $itemInfo['user_id']!=$this->user_id) ) {
                H::redirect_msg(Application::lang()->_t('书稿不存在'), '/admin/team_workload/check_list/');
            }
        } else { // 查询全部书稿的工作量
            // 1. 获取到存在工作量的书稿id
            // 2. 根据书稿， 统计具有工作量书稿的总数

            // 先限定图书分类在组长的管理范围内
            $where = array('(category_id IN ('. join(',', $this->user_info['sinho_manage_subject']).') OR user_id = '.$this->user_id.')');

            if ($_GET['start_date']) {
                $where[] = 'delivery_date >="' . date('Y-m-d', strtotime(base64_decode($_GET['start_date'])) ) . '"';
            }
            if ($_GET['end_date']) {
                $where[] = 'delivery_date <="' . date('Y-m-d', strtotime(base64_decode($_GET['end_date'])) ) . '"';
            }
            if ($_GET['serial']) {
                //$where[] = ' (MATCH(serial) AGAINST("' . $this->model()->quote(rawurldecode($_GET['serial'])) . '") )';
                $where[] = 'serial like "%' . $this->model()->quote(rawurldecode($_GET['serial'])) .'%"';
            }
            if ($_GET['book_name']) {
                //$where[] = ' (MATCH(book_name) AGAINST("' . $this->model()->quote(rawurldecode($_GET['book_name'])) . '") )';
                $where[] = 'book_name like "%' . $this->model()->quote(rawurldecode($_GET['book_name'])) .'%"';
            }
            if ($_GET['proofreading_times']) {
                //$where[] = ' (MATCH(proofreading_times) AGAINST("' . $this->model()->quote(rawurldecode($_GET['proofreading_times'])) . '") )';
                $where[] = 'proofreading_times like "%' . $this->model()->quote(rawurldecode($_GET['proofreading_times'])) .'%"';
            }
            if ($_GET['good_or_bad']) { // 按照奖惩查询书稿。 先查奖惩对应的工作量，再根据工作量找书稿
                $tmpList = $this->model('sinhoWorkload')->fetch_all(sinhoWorkloadModel::QUARLITY_TABLE, 'good_or_bad IN ("' . join('","', $_GET['good_or_bad']) . '")');
                $bookIds = array(-1);
                if ($tmpList) {
                    $tmpWorkloadIds = array_column($tmpList, 'workload_id');
                    $tmpList = $this->model('sinhoWorkload')->fetch_all(sinhoWorkloadModel::WORKLOAD_TABLE, 'id IN (' . join(',', $tmpWorkloadIds) . ')');
                    count($tmpList) > 0 AND $bookIds = array_column($tmpList, 'book_id');
                }
                $where[] = 'id IN (' . join(',', $bookIds) . ')';
            }

            if ($where) {
                $where = join(' AND ', $where);
            } else {
                $where = null;
            }

            $booksList  = $this->model('sinhoWorkload')->fetch_page(sinhoWorkloadModel::BOOK_TABLE, $where, 'delivery_date DESC, id DESC', null, PHP_INT_MAX, true, 'id');
            $bookIds = array();
            $totalRows = 0;
            if (isset($_GET['export_all']) || isset($_GET['export_workload'])) {
                $this->per_page = PHP_INT_MAX;
                $_GET['page']   = 1;
            }
            if ($booksList) {
                $bookIdList = $this->model('sinhoWorkload')
                                ->query_all('SELECT DISTINCT  book_id FROM ' . $this->model('sinhoWorkload')->get_table(sinhoWorkloadModel::WORKLOAD_TABLE),
                                            $this->per_page,
                                            $this->per_page * intval($_GET['page']-1),
                                            '`status`<>'.sinhoWorkloadModel::STATUS_DELETE
                                            . ' AND book_id IN (0, ' . join(',', array_column($booksList, 'id')) . ')'
                                            //.' and `status`<> ' . sinhoWorkloadModel::STATUS_RECORDING
                                            ,
                                            null,
                                            'book_id DESC, `status` desc, belong_month desc'
                                        );  // ($sql, $limit = null, $offset = null, $where = null, $group_by = null, $order_by = '');
                $bookIds = array_column($bookIdList, 'book_id');
                $totalRows = $this->model('sinhoWorkload')
                                ->count(sinhoWorkloadModel::WORKLOAD_TABLE,
                                        '`status`<>'.sinhoWorkloadModel::STATUS_DELETE
                                        . ' AND book_id IN (0, ' . join(',', array_column($booksList, 'id')) . ')',
                                        'DISTINCT  book_id'
                                    );
                //fetch_page($table, $where = null, $order = null, $page = null, $limit = 10, $rows_cache = true)
            }
        }
        // 根据书稿id ， 获取到对应的工作量
        $allList = array();
        if ($bookIds) {
            $allList = $this->model('sinhoWorkload')
                            ->fetch_all ( sinhoWorkloadModel::WORKLOAD_TABLE,
                                        'book_id IN (' . join(', ',  $bookIds). ') '
                                        . ' AND status <> ' . sinhoWorkloadModel::STATUS_DELETE
                                        //. ' AND status <> ' . sinhoWorkloadModel::STATUS_RECORDING
                                        ,
                                    'book_id,category,working_times'
                                );
        }
        // 根据工作量ids获取相应的质量奖惩信息表内容
        $workloadIds = array_column($allList, 'id');
        $quarlityList = array();
        if ($workloadIds) {
            $quarlityList = $this->model('sinhoWorkload')
                                ->fetch_all(sinhoWorkloadModel::QUARLITY_TABLE,
                                            'workload_id IN (0, ' . join(',', $workloadIds) . ')'
                                            . ' AND status <> ' . sinhoWorkloadModel::STATUS_DELETE
                                    );
            $workloadIds = array_column($quarlityList, 'workload_id');
            $quarlityList = array_combine($workloadIds, $quarlityList); // 按照工作量id对质量考核信息归组
        }

        // 获取用户信息列表,
        $userIds = array_column($allList, 'user_id');
        $userList = array();
        if ($userIds) {
            $userList = $this->model('sinhoWorkload')->getUserList('uid IN (' . join(',', $userIds) . ')', 'uid DESC', PHP_INT_MAX);
        }
        $userIds  = array_column($userList, 'uid');
        $userList = array_combine($userIds, $userList);

        // 将工作量按照书稿id分组
        $workloadList = array();
        foreach ($allList as $_itemInfo) {
            isset($workloadList[$_itemInfo['book_id']]) OR $workloadList[$_itemInfo['book_id']] = array();

            $workloadList[$_itemInfo['book_id']][] = $_itemInfo;
        }

        // 根据书稿id列表获取书稿信息
        $bookList = array();
        if ($bookIds) {
            $bookList = $this->model('sinhoWorkload')
                             ->fetch_all(sinhoWorkloadModel::BOOK_TABLE,
                                    'id IN (' . join(', ', $bookIds) . ')'
                                );
        }

        if (isset($_GET['export_all']) || isset($_GET['export_workload'])) {
            $phpExcel = & loadClass('Tools_Excel_PhpExcel');
            $headArr = array(
                'date'                              => '日期',
                'user_name'                         => '责编',
                //'delivery_date'                     => '发稿日期',
                //'return_date'                       => '回稿日期',
                'category'                          => '类别',
                'serial'                            => '系列',
                'book_name'                         => '书名',
                'proofreading_times'                => '校次',
                'content_table_pages'               => '目录',
                'text_pages'                        => '正文',
                'text_table_chars_per_page'         => '目录+正文千字/页',
                'answer_pages'                      => '答案',
                'answer_chars_per_page'             => '答案千字/页',
                'test_pages'                        => '试卷',
                'test_chars_per_page'               => '试卷千字/页',
                'test_answer_pages'                 => '试卷答案',
                'test_answer_chars_per_page'        => '试卷答案千字/页',
                'exercise_pages'                    => '课后作业',
                'exercise_chars_per_page'           => '课后作业千字/页',
                'function_book'                     => '功能册',
                'function_book_chars_per_page'      => '功能册千字/页',
                'function_answer'                   => '功能册答案',
                'function_answer_chars_per_page'    => '功能册答案千字/页',
                'weight'                            => '难度系数',
                'total_chars'                       => '字数（合计）',
                'payable_amount'                    => '应发金额',
                'remarks'                           => '备注'
            );
            $fileName = '导出书稿工作量-' . date('Y-m-d') . '.xls';
            $itemList = array();
            $bookLines = array(); // 书稿在excel中的行数
            $workloadLines = $payedLines = array();
            $_bookLine = 2;
            foreach ($bookList as $_item) {
                if (isset($_GET['export_all'])) { // 导出书稿
                    $_item['date'] = substr($_item['delivery_date'], 5) . '~' . substr($_item['return_date'], 5);
                    $_item['user_name'] = '';
                    $itemList[] = $_item;
                    $bookLines[] = $_bookLine++;
                }
                foreach ($workloadList[$_item['id']] as $_item2) {
                    $_item2['category'          ] = $_item['category'          ];
                    $_item2['serial'            ] = $_item['serial'            ];
                    $_item2['book_name'         ] = $_item['book_name'         ];
                    $_item2['proofreading_times'] = $_item['proofreading_times'];
                    if ($_item2['add_time']) {
                        $_item2['date'] = date('m-d', $_item2['add_time']);
                    } else {
                        $_item2['date'] = substr($_item['delivery_date'], 5);
                    }
                    $_item2['date'] .= '~';
                    if ($_item2['fill_time']) {
                        $_item2['date'] .= date('m-d', $_item2['fill_time']);
                    }
                    $_item2['user_name'] = $userList[$_item2['user_id']]['user_name'];
                    $itemList[] = $_item2;
                    $_item2['status'] == 1 ? ($payedLines[] = $_bookLine++) : ($workloadLines[] = $_bookLine++);
                }
            }

            // 导出书稿
            $style = array(
                'width'   => array('A'=>12, 'B'=>10, 'C'=>10, 'D'=>15,'E'=>20,'G'=>4,'H'=>4, 'Y'=>40,), // 字符数算
                'height'  => array(1 => 24),      // 按照 磅 算
                'style'   => array (
                    'A1:Y1'=> array (
                                'font'    => array(
                                                    'size'      => 9
                                ),
                                'fill'    => array(
                                                    'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
                                                    'color' => array('rgb' => 'f2dede'),
                                ),
                                'borders' => array(
                                                    'bottom'     => array(
                                                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                        'color' => array(
                                                            'rgb' => '999999'
                                                        )
                                                    ),
                                                    'right' => array(
                                                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                        'color' => array(
                                                            'rgb' => '999999'
                                                        ),
                                                    )
                                ),

                                'alignment'  => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'wrap' => TRUE)
                    )
                ),
            );
            if (isset($_GET['export_all'])) {
                foreach ($bookLines as $_bookLine) {
                    $style['style']['A' . $_bookLine . ':Y' .$_bookLine] = array(
                        'font'    => array(
                            'size'      => 9
                        ),
                        'fill'    => array(
                                            'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
                                            'color' => array('rgb' => 'ECECEC'),
                        ),
                        'borders' => array(
                                            'bottom'     => array(
                                                'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                'color' => array(
                                                    'rgb' => '999999'
                                                )
                                            ),
                                            'right' => array(
                                                'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                'color' => array(
                                                    'rgb' => '999999'
                                                ),
                                            )
                        ),
                        'alignment'  => array('wrap' => TRUE)
                    );
                }
            }
                foreach ($workloadLines as $_line) {
                    $style['style']['A' . $_line . ':Y' .$_line] = array(
                        'alignment'  => array('wrap' => TRUE),

                        'borders' => array(
                            'bottom'     => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array(
                                    'rgb' => 'aaaaaa'
                                )
                            ),
                            'right' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array(
                                    'rgb' => 'aaaaaa'
                                ),
                            )
                        ),
                    );
                }
                foreach ($payedLines as $_line) {
                    $style['style']['A' . $_line . ':Y' .$_line] = array(
                        'fill'    => array(
                                            'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
                                            'color' => array('rgb' => 'dbedf7'),
                        ),
                        'alignment'  => array('wrap' => TRUE),

                        'borders' => array(
                            'bottom'     => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array(
                                    'rgb' => 'aaaaaa'
                                )
                            ),
                            'right' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array(
                                    'rgb' => 'aaaaaa'
                                ),
                            )
                        ),
                    );
                }
            $phpExcel->export($fileName, $headArr, $itemList, true, $style);
            // 导出书稿
           // $phpExcel->export($fileName, $headArr, $itemList, true);
           // return;
        }

        $url_param = array();
        foreach($_GET as $key => $val) {
            if (!in_array($key, array('app', 'c', 'act', 'page'))) {
                $url_param[] = $key . '-' . $val;
            }
        }
        // 生成页码导航
        View::assign('pagination', Application::pagination()->initialize(array(
            'base_url'   => get_js_url('/admin/'.(CONTROLLER=='team_workload' ? 'team_workload/':'').'check_list/') . implode('__', $url_param),
            'total_rows' => $totalRows,
            'per_page'   => $this->per_page
        ))->create_links());

        View::assign('itemsList', $bookList);
        View::assign('workloadList', $workloadList);
        View::assign('quarlityList', $quarlityList);
        View::assign('totalRows', $totalRows);

        View::import_js(G_STATIC_URL . '/js/bootstrap-multiselect.js');
        View::import_css(G_STATIC_URL . '/css/bootstrap-multiselect.css');
    }



    /**
     * 填报工作量列表
     */
    public function quarlity_list_action ()
    {
        $this->checkPermission(self::IS_SINHO_TEAM_LEADER);

        // 获取所有组， 解析组管理的学科。 然后获取到组id
        $groupIds = array(-1);
        $groupList = $this->model('account')->get_user_group_list(0, 1);
        foreach ($groupList as $itemInfo) {
            $itemInfo['permission'] = unserialize($itemInfo['permission']);
            if ($itemInfo['permission'] && in_array($itemInfo['permission']['sinho_subject'], $this->user_info['sinho_manage_subject']) ) {
                $groupIds[] = $itemInfo['group_id'];
            }
        }
        // 根据组id获取用户列表
        $userList = array();
        $userList = $this->model('sinhoWorkload')->fetch_all('users', 'group_id IN (' . join(', ', $groupIds) . ')') ;
        $userIds  = array_column($userList, 'uid');
        $userList = array_combine($userIds, $userList);

        $where = null;
        if ($_GET['user_id']) {
            if (in_array($_GET['user_id'], $userIds)) {
                $where = 'user_id = '. intval($_GET['user_id']);
            } else {
                $where = '1 = 0';
            }
        } else {
            $where = 'user_id IN (' . join(',', $userIds) . ')';
        }
        $quarlityList = (array) $this->model('sinhoWorkload')->fetch_page(sinhoWorkloadModel::QUARLITY_TABLE, $where, 'id DESC', $_GET['page'], $this->per_page);
        $totalRows     = $this->model('sinhoWorkload')->found_rows();

        $bookIds  = array_column($quarlityList, 'book_id');
        $bookList = array();
        if ($bookIds) {
            $bookList = $this->model('sinhoWorkload')->fetch_all(sinhoWorkloadModel::BOOK_TABLE, 'id IN (' . join(', ', $bookIds) . ')') ;
            $bookIds  = array_column($bookList, 'id');
            $bookList = array_combine($bookIds, $bookList);
        }
        $workloadIds  = array_column($quarlityList, 'workload_id');
        $workloadList = array();
        if ($workloadIds) {
            $workloadList = $this->model('sinhoWorkload')->fetch_all(sinhoWorkloadModel::WORKLOAD_TABLE, 'id IN (' . join(', ', $workloadIds) . ')') ;
            $workloadIds  = array_column($workloadList, 'id');
            $workloadList = array_combine($workloadIds, $workloadList);
        }
        if ($this->hasRolePermission(self::IS_SINHO_CHECK_WORKLOAD)) {
            $userIds  = array_column($quarlityList, 'user_id');
            $userList = array();
            if ($userIds) {
                $userList = $this->model('sinhoWorkload')->fetch_all('users', 'uid IN (' . join(', ', $userIds) . ')') ;
                $userIds  = array_column($userList, 'uid');
                $userList = array_combine($userIds, $userList);
            }
            View::assign('userList', $userList);
        }

        View::assign('hasCheckPermission', false);
        View::assign('itemsList', $quarlityList);
        View::assign('booksList', $bookList);
        View::assign('workloadList', $workloadList);

        $url_param = array();
        foreach($_GET as $key => $val) {
            if (!in_array($key, array('app', 'c', 'act', 'page'))) {
                $url_param[] = $key . '-' . $val;
            }
        }

        View::assign('thisUserId', $this->user_id);

        View::assign('pagination', Application::pagination()->initialize(array(
            'base_url'   => get_js_url('/admin/team_workload/quarlity_list/') . implode('__', $url_param),
            'total_rows' => $totalRows,
            'per_page'   => $this->per_page
        ))->create_links());

        View::import_js('js/icb_template_isinho.com.js');
        View::import_js('js/functions.js');

        View::assign('menu_list', $this->filterAdminMenu($this->model('admin')->fetch_menu_list('admin/team_workload/quarlity_list', 'sinho_admin_menu') ) );
        View::output('admin/workload/quarlity_list');
    }

}

/* EOF */
