<?php
defined('iCodeBang_Com') OR die('Access denied!');

class main extends SinhoBaseController
{
    const PERMISSION_BOOKLIST = 'sinho_book_list';

    public function index_action()
    {
        $this->crumb(Application::lang()->_t('概述'), 'admin/main/');

        // 获取工作量表中的半年内记录的最大月份， 获取半年内记录的最小月份。 将这段数据展示出来
        $belongMonth = $this->model('sinhoWorkload')->max(sinhoWorkloadModel::WORKLOAD_TABLE, 'belong_month', 'belong_month >= ' . date('Ym', strtotime('-6month')));
        $belongMinMonth = $this->model('sinhoWorkload')->min(sinhoWorkloadModel::WORKLOAD_TABLE, 'belong_month', 'belong_month >= ' . date('Ym', strtotime('-6month')));
        if (! $belongMonth) { // 如果没有工作量， 将上个月的月份作为记录最大月份
            $belongMonth = date('Ym', strtotime('-1month'));
        }
        if (! $belongMinMonth) {// 没有工作量记录， 将上个月作为记录的最小月份
            $belongMinMonth = $belongMonth;
        }
        // 当前记录中的年月份， 用记录的最大月份后延一个月
        $currentYearMonth = date('Ym', strtotime("{$belongMonth}01 +1month"));
        //$currentYearMonth = 202012;
        $currentYear  = intval(substr($currentYearMonth, 0, 4)); // 计算当前记录的年份
        $currentMonth = intval(substr($currentYearMonth, 4)); // 计算当前记录的月份
        $dayAmount = date('t', strtotime("{$currentYearMonth}01")); // 获取当前记录月份中的天数
        $workingDaysAmount = 0;// 工作日天数
        // 获取系统设置的工作日和休假安排， 将用于计算当前记录月份的工作日天数
        //$workdayHolidays = Application::config()->get('system')->sites[$_SERVER['HTTP_HOST']]['workday_holiday'];

        // 计算当月的工作日天数
        $nowMonth = date('n');
        $nowDay   = date('j');
        $scheduleList = $this->model('sinhoWorkload')->fetch_one(sinhoWorkloadModel::SCHEDULE_TABLE, 'month_'.$nowMonth, 'belong_year = ' . $currentYear);
        $workdayHolidays = array(
            $currentYear => array (
                'holiday' => array($nowMonth => array()),
                'workday' => array($nowMonth => array())
            )
        );
        // 将设置的作息时间， 归纳到 假日和工作日当中
        if ($scheduleList) {
            $scheduleList = json_decode($scheduleList);
            foreach ($scheduleList as $_dateString) {
                $weekIndex = date('N', strtotime($_dateString) );
                $workdayOrHoliday = ($weekIndex==6 OR $weekIndex==7) ? 'workday' : 'holiday';
                $workdayHolidays[$currentYear][$workdayOrHoliday][$nowMonth][] = date('j', strtotime($_dateString) );
            }
        }

        $nowPassedDays = 0;
        for($i=1; $i<=$dayAmount; $i++) {
            $_isWorkingDay = 0;
            $weekIndex = date('N', strtotime("{$currentYearMonth}" . sprintf('%02d', $i) ) );
            if (($weekIndex==6 OR $weekIndex==7) && (isset($workdayHolidays[$currentYear], $workdayHolidays[$currentYear]['workday'][$currentMonth])
               &&  in_array($i, $workdayHolidays[$currentYear]['workday'][$currentMonth])) ) {
                $workingDaysAmount++;
                $_isWorkingDay = 1;
            } else if (($weekIndex!=6 && $weekIndex!=7) && (!isset($workdayHolidays[$currentYear], $workdayHolidays[$currentYear]['holiday'][$currentMonth])
            || ! in_array($i, $workdayHolidays[$currentYear]['holiday'][$currentMonth])) ) {
                $workingDaysAmount++;
                $_isWorkingDay = 1;
            }

            if ($_isWorkingDay && $currentMonth == $nowMonth && $nowDay > $i) { //当前月份和计算月份在同一个月， 按照已经过完的天数计算
                $nowPassedDays++;
            }
        }
        if ($currentMonth != $nowMonth) { // 如果当前月份天数不等于计算月份天数， 那么认为计算月份已经结束，已过去工作天数为对应月份的天数
            $nowPassedDays = $workingDaysAmount;
        }
        View::assign('workingDaysAmount', $workingDaysAmount); // 当月的工作日天数
        View::assign('nowPassedDays', $nowPassedDays); // 当月已经过完多少工作日

        $warningMsgList = array();
        // 编辑用户： 显示当前工作量， 近期工作量趋势图， 绩效核算错误信息
        if ($this->hasRolePermission(self::IS_SINHO_FILL_WORKLOAD)) {
            $item   =  $this->model('sinhoWorkload')
                            ->fetch_row(sinhoWorkloadModel::WORKLOAD_TABLE,
                                        '`status` = '     . sinhoWorkloadModel::STATUS_VERIFYING
                                          . ' AND user_id = '    . $this->user_id
                                          . ' AND verify_remark <> ""'
                            ) ;
            if ($item) {
                $warningMsgList[] = '填写的工作量在核算汇总时出现疑义，请检查是否有误并重新提交！';
            }
            $personalWorkloadList = $this->model('sinhoWorkload')->getWorkloadStatByUserIds (array($this->user_id), sinhoWorkloadModel::STATUS_VERIFYING);
            if (! $personalWorkloadList) {
                $personalWorkloadList = $this->model('sinhoWorkload')->getWorkloadStatByUserIds (array($this->user_id), sinhoWorkloadModel::STATUS_RECORDING);
            }
            View::assign('currentWorkload', array_sum(array_column($personalWorkloadList, 'total_chars')));
            $startMonth = $belongMinMonth;
            $personalWorkloadList = array();
            while ($startMonth <= $belongMonth) {
                $personalWorkloadList[$startMonth] = $this->model('sinhoWorkload')->getWorkloadStatByUserIds (array($this->user_id), sinhoWorkloadModel::STATUS_VERIFIED, $startMonth);
                $personalWorkloadList[$startMonth] = array_sum(array_column($personalWorkloadList[$startMonth], 'total_chars'));
                $startMonth = date('Ym', strtotime("{$startMonth}01 +1month"));
            }
            $personalWorkloadList[$currentYearMonth]=View::$view->currentWorkload;
            View::assign('personalWorkloadList', $personalWorkloadList);
            //var_dump($personalWorkloadList);
        }

        $totalCharsListLastMonth = array();
        $userList = array();
        // 管理权限： 工作量榜单，已结算书稿
        if ($this->hasRolePermission(self::IS_SINHO_CHECK_WORKLOAD)) {
            $userList = $this->model('sinhoWorkload')->getUserList(null, 'uid DESC', PHP_INT_MAX);
            $userIds  = array_column($userList, 'uid');
            $userList = array_combine($userIds, $userList);
            // 按月获取每个人的工作量
            $workloadStatLastMonth = $this->model('sinhoWorkload')->getWorkloadStatByUserIds (array(), null, $belongMonth);
            $totalCharsListLastMonth = array_combine(array_column($workloadStatLastMonth,'user_id'), array_column($workloadStatLastMonth,'total_chars'));
            //$totalCharsWithoutWeightListLastMonth = array_combine(array_column($workloadStatLastMonth,'user_id'), array_column($workloadStatLastMonth,'total_chars_without_weight'));
            arsort($totalCharsListLastMonth, SORT_NUMERIC);


            $startMonth = $belongMinMonth;
            $itemList = array();
            $workloadUserIds = array();
            while ($startMonth <= $belongMonth) {
                $itemList[$startMonth] = $this->model('sinhoWorkload')->getWorkloadStatByUserIds (array(), sinhoWorkloadModel::STATUS_VERIFIED, $startMonth);
                $workloadUserIds = array_merge($workloadUserIds, array_column($itemList[$startMonth], 'user_id' ) ) ;
                //$itemList[$startMonth] = array_sum(array_column($itemList[$startMonth], 'total_chars'));
                $startMonth = date('Ym', strtotime("{$startMonth}01 +1month"));
            }
            $employeeWorkloadList = array();
            $employeeWorkloadList = array_fill_keys($workloadUserIds, array());
            if (isset($employeeWorkloadList[''])) {
                unset($employeeWorkloadList['']);
            }
            $allTotalChars = array();
            foreach ($itemList as $_month=>$_statList) {
                foreach ($employeeWorkloadList as $_userId=>$_v) {
                    $employeeWorkloadList[$_userId][$_month] = 0;
                }

                foreach ($_statList as $_userId=>$_statInfo) {
                    if (!$_userId) {
                        continue;
                    }
                    $employeeWorkloadList[$_userId][$_month] = $allTotalChars[] = $_statInfo['total_chars'];
                }
            }
            // 各项全局统计信息
            $globalStatMsgList = array();
            // 获取总书稿数
            // 获取总字数
            // 获取当前工作的书稿数；
            // 获取绩效总数
            // 导入工资表， 计算成本
            //
            $globalStatMsgList['书稿总数']        = $this->model('sinhoWorkload')
                                                      ->count(sinhoWorkloadModel::BOOK_TABLE);
            $globalStatMsgList['书稿总字数']    = number_format($this->model('sinhoWorkload')->sum(sinhoWorkloadModel::BOOK_TABLE, 'total_chars'), 4);
            $globalStatMsgList['正在工作中的书稿']   = $this->model('sinhoWorkload')
                                                      ->count(sinhoWorkloadModel::WORKLOAD_TABLE,
                                                             ' `status` = ' . sinhoWorkloadModel::STATUS_VERIFYING . ' OR  `status` = ' . sinhoWorkloadModel::STATUS_RECORDING,
                                                             'distinct book_id'
                                                            );
            $totalEmployees   = $this->model('sinhoWorkload')
                                     ->count(sinhoWorkloadModel::WORKLOAD_TABLE,
                                            '`status` = ' . sinhoWorkloadModel::STATUS_VERIFIED,
                                            'distinct user_id'
                                        );
            $globalStatMsgList['已发放绩效总额']   = $this->model('sinhoWorkload')
                                                    ->sum(sinhoWorkloadModel::WORKLOAD_TABLE, 'total_chars', ' `status` = ' . sinhoWorkloadModel::STATUS_VERIFIED)
                                                            * 2;
            //$globalStatMsgList['平均绩效']   = number_format($globalStatMsgList['已发放绩效'] / $totalEmployees, 4);
            $globalStatMsgList['已发放绩效总额']   = number_format($this->model('sinhoWorkload')
                                                                    ->sum(sinhoWorkloadModel::WORKLOAD_TABLE, 'total_chars', ' `status` = ' . sinhoWorkloadModel::STATUS_VERIFIED)
                                                            * 2, 4);
            $globalStatMsgList['当前核算中绩效']   = number_format($this->model('sinhoWorkload')
                                                                    ->sum(sinhoWorkloadModel::WORKLOAD_TABLE, 'total_chars', ' `status` = ' . sinhoWorkloadModel::STATUS_VERIFYING . ' OR  `status` = ' . sinhoWorkloadModel::STATUS_RECORDING)
                                                            * 2, 4);

            View::assign('globalStatMsgList', $globalStatMsgList);

            View::assign('monthList', array_keys($itemList));
            View::assign('allTotalChars', $allTotalChars);
            View::assign('employeeWorkloadList', $employeeWorkloadList);
        }

        View::assign('belongMonth', $belongMonth);
        View::assign('belongMinMonth', $belongMinMonth);
        View::assign('warningMsgList', $warningMsgList);
        View::assign('totalCharsListLastMonth', $totalCharsListLastMonth);
        //View::assign('totalCharsWithoutWeightListLastMonth', $totalCharsWithoutWeightListLastMonth);
        View::assign('userList', $userList);


        View::import_js('js/functions.js');
        View::import_js('js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js');
        View::import_js('js/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js');
        View::import_js('js/icb_template_isinho.com.js');
        View::import_css('js/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css');
        View::assign('menu_list', $this->filterAdminMenu($this->model('admin')->fetch_menu_list('admin/index', 'sinho_admin_menu') ) );

        View::output('admin/index');

    }

    /**
     * 填报工作量列表
     */
    public function fill_list_action ()
    {
        $this->checkPermission(self::IS_SINHO_FILL_WORKLOAD);
        $assigned = (array) $this->model('sinhoWorkload')->fetch_page(sinhoWorkloadModel::WORKLOAD_TABLE, 'user_id = ' . intval($this->user_id) . ' AND `status`<>' . sinhoWorkloadModel::STATUS_DELETE , 'id DESC', $_GET['page'], $this->per_page);

        $bookIds  = array_column($assigned, 'book_id');
        $bookList = array();
        if ($bookIds) {
            $bookList = $this->model('sinhoWorkload')->fetch_all(sinhoWorkloadModel::BOOK_TABLE, 'id IN (' . join(', ', $bookIds) . ')') ;
            $bookIds  = array_column($bookList, 'id');
            $bookList = array_combine($bookIds, $bookList);
        }
        View::assign('itemsList', $assigned);
        View::assign('booksList', $bookList);
        $totalRows     = $this->model('sinhoWorkload')->found_rows();

        $url_param = array();
        foreach($_GET as $key => $val) {
            if (!in_array($key, array('app', 'c', 'act', 'page'))) {
                $url_param[] = $key . '-' . $val;
            }
        }

        View::assign('totalChars', $this->model('sinhoWorkload')->getTotalCharsByUserIds (array($this->user_id), sinhoWorkloadModel::STATUS_VERIFYING));
        View::assign('thisUserId', $this->user_id);

        View::assign('pagination', Application::pagination()->initialize(array(
            'base_url'   => get_js_url('/admin/fill_list/') . implode('__', $url_param),
            'total_rows' => $totalRows,
            'per_page'   => $this->per_page
        ))->create_links());

        View::import_js('js/icb_template_isinho.com.js');
        View::import_js('js/functions.js');

        View::assign('menu_list', $this->filterAdminMenu($this->model('admin')->fetch_menu_list('admin/fill_list', 'sinho_admin_menu') ) );
        View::output('admin/workload/fill_list');
    }

    /**
     * 填充工作量
     */
    public function fill_workload_action ()
    {
        $itemInfo = $this->model('sinhoWorkload')->fetch_row(sinhoWorkloadModel::WORKLOAD_TABLE, 'id = ' . intval($_GET['id']). ' AND `status`<>' . sinhoWorkloadModel::STATUS_DELETE );

        if (! $itemInfo) {
            H::redirect_msg(Application::lang()->_t('操作失败'), 'admin/fill_list/');
        }
        $bookInfo = $this->model('sinhoWorkload')->fetch_row(sinhoWorkloadModel::BOOK_TABLE, 'id = ' . intval($itemInfo['book_id']));
        View::assign('bookInfo', $bookInfo);
        View::assign('itemInfo', $itemInfo);
        View::assign('menu_list', $this->filterAdminMenu($this->model('admin')->fetch_menu_list('admin/fill_list','sinho_admin_menu') ) );
        View::output('admin/workload/fill');
    }

    /**
     * 核算工作量
     */
    public function verify_list_action ()
    {
        $this->checkPermission(self::IS_SINHO_VERIFY_WORKLOAD);
        $toBeVerifiedList = $this->model('sinhoWorkload')->fetch_all(sinhoWorkloadModel::WORKLOAD_TABLE, 'status = ' . sinhoWorkloadModel::STATUS_VERIFYING, 'book_id,category,working_times');
        $bookIds  = array_column($toBeVerifiedList, 'book_id');
        $verifiedList = array();
        // if ($bookIds) {
        //     $verifiedList = $this->model('sinhoWorkload')->fetch_all(sinhoWorkloadModel::WORKLOAD_TABLE, 'book_id IN (' . join(',', $bookIds) . ') AND status = ' . sinhoWorkloadModel::STATUS_VERIFIED, 'book_id,category,working_times');
        // }
        // $allList = array_merge($toBeVerifiedList, $verifiedList);
        // 按照书稿，类别， 遍次排序。
        if ($bookIds) {
            $allList = $this->model('sinhoWorkload')->fetch_all(sinhoWorkloadModel::WORKLOAD_TABLE, 'book_id IN (' . join(',', $bookIds) . ') AND (status = ' . sinhoWorkloadModel::STATUS_VERIFIED . ' OR status = ' . sinhoWorkloadModel::STATUS_VERIFYING . ')', 'book_id,category,working_times');
        }

        $workloadList = array();
        foreach ($allList as $_itemInfo) {
            isset($workloadList[$_itemInfo['book_id']]) OR $workloadList[$_itemInfo['book_id']] = array();

            $workloadList[$_itemInfo['book_id']][] = $_itemInfo;
        }
        $bookIds = array_keys($workloadList);
        $bookList = array();
        if ($bookIds) {
            $bookList = $this->model('sinhoWorkload')->fetch_all(sinhoWorkloadModel::BOOK_TABLE, 'id IN (' . join(',', $bookIds) . ')', 'id DESC');
        }


        $userList = $this->model('sinhoWorkload')->getUserList(null, 'uid DESC', PHP_INT_MAX);
        $userIds  = array_column($userList, 'uid');
        $userList = array_combine($userIds, $userList);


        $belongMonth = $this->model('sinhoWorkload')->max(sinhoWorkloadModel::WORKLOAD_TABLE, 'belong_month', 'belong_month >= ' . date('Ym', strtotime('-3month')));
        if (!$_GET['belong_month'] || $_GET['belong_month'] > $belongMonth || $_GET['belong_month']<200001) { // 获取待核算月份的数据
            $totalCharsList = $this->model('sinhoWorkload')->getWorkloadStatByUserIds ($userIds, null, $belongMonth);
        } else {// 获取指定月份的数据
            $totalCharsList = $this->model('sinhoWorkload')->getWorkloadStatByUserIds ($userIds, null, $_GET['belong_month']);
            $belongMonth = $_GET['belong_month'];
        }
        $this->assign('belongMonth', substr($belongMonth,0,4).'-'.sprintf('%02d', substr($belongMonth,4)) );

        View::assign('userList', $userList);
        View::assign('itemsList', $bookList);
        View::assign('workloadList', $workloadList);
        View::assign('totalCharsList', $totalCharsList);

        View::assign('menu_list', $this->filterAdminMenu($this->model('admin')->fetch_menu_list('admin/verify_list', 'sinho_admin_menu')  ) );


        View::import_js('js/functions.js');
        View::import_js('js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js');
        View::import_js('js/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js');
        View::import_js('js/icb_template_isinho.com.js');
        View::import_css('js/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css');

        View::output('admin/workload/verify');
    }

    /**
     * 查看工作量
     */
    public function check_list_action ()
    {
        $this->checkPermission(self::IS_SINHO_CHECK_WORKLOAD);
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

        View::assign('menu_list', $this->filterAdminMenu($this->model('admin')->fetch_menu_list('admin/check_list','sinho_admin_menu') ) );
        View::output('admin/workload/check');
    }

    protected function check_by_user (& $userList, & $bookList)
    {
        $this->per_page = 30;
        $queryUserIds = array();
        //$where = 'status <> ' . sinhoWorkloadModel::STATUS_DELETE . ' AND status <> ' . sinhoWorkloadModel::STATUS_RECORDING;
        $where = 'status <> ' . sinhoWorkloadModel::STATUS_DELETE ;
        if ($_GET['id']) {
            $queryUserIds = explode(',', $_GET['id']);
            foreach ($queryUserIds as & $_id) {
                $_id = intval($_id);
            }

            $where .= ' AND user_id IN ( ' . join(', ',  $queryUserIds). ') ';
        }
        $belongMonth = array();
        if ($_GET['start_month']) {
            $where .= ' AND (belong_month >= ' . intval($_GET['start_month']) . ' OR belong_month IS NULL )';
            $belongMonth['start'] = intval($_GET['start_month']);
        }
        if ($_GET['end_month']) {
            $where .= ' AND belong_month <= ' . intval($_GET['end_month']);
            $belongMonth['end'] = intval($_GET['end_month']);
        }

        $allList = $this->model('sinhoWorkload')
                        ->fetch_page(sinhoWorkloadModel::WORKLOAD_TABLE,
                                    $where,
                                    'user_id,id desc',
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

        // 获取用户信息列表,
        $userList = $this->model('sinhoWorkload')->getUserList(null, 'uid DESC', PHP_INT_MAX);

        View::assign('itemOptions', buildSelectOptions($userList, 'user_name', 'uid', $queryUserIds ) );

        $userIds  = array_column($userList, 'uid');
        $userList = array_combine($userIds, $userList);


        $url_param = array();
        foreach($_GET as $key => $val) {
            if (!in_array($key, array('app', 'c', 'act', 'page'))) {
                $url_param[] = $key . '-' . $val;
            }
        }
        // 生成页码导航
        View::assign('pagination', Application::pagination()->initialize(array(
            'base_url'   => get_js_url('/admin/check_list/') . implode('__', $url_param),
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
        } else { // 查询全部书稿的工作量
            // 1. 获取到存在工作量的书稿id
            // 2. 根据书稿， 统计具有工作量书稿的总数
            $bookIdList = $this->model('sinhoWorkload')
                               ->query_all('SELECT DISTINCT  book_id FROM ' . $this->model('sinhoWorkload')->get_table(sinhoWorkloadModel::WORKLOAD_TABLE),
                                        $this->per_page,
                                        $this->per_page * intval($_GET['page']-1),
                                        '`status`<>'.sinhoWorkloadModel::STATUS_DELETE
                                          //.' and `status`<> ' . sinhoWorkloadModel::STATUS_RECORDING
                                         ,
                                        null,
                                        '`status` desc, belong_month desc'
                                    );  // ($sql, $limit = null, $offset = null, $where = null, $group_by = null, $order_by = '');
            $bookIds = array_column($bookIdList, 'book_id');
            $totalRows = $this->model('sinhoWorkload')
                              ->count(sinhoWorkloadModel::WORKLOAD_TABLE,
                                      '`status`<>'.sinhoWorkloadModel::STATUS_DELETE,
                                      'DISTINCT  book_id'
                                );
            //fetch_page($table, $where = null, $order = null, $page = null, $limit = 10, $rows_cache = true)
        }
        // 根据书稿id ， 获取到对应的工作量
        $allList = array();
        if ($bookIds) {
            $allList = $this->model('sinhoWorkload')
                            ->fetch_all ( sinhoWorkloadModel::WORKLOAD_TABLE,
                                        'book_id IN ( ' . join(', ',  $bookIds). ') '
                                        . ' AND status <> ' . sinhoWorkloadModel::STATUS_DELETE
                                        //. ' AND status <> ' . sinhoWorkloadModel::STATUS_RECORDING
                                        ,
                                    'book_id,category,working_times'
                                );
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

        $url_param = array();
        foreach($_GET as $key => $val) {
            if (!in_array($key, array('app', 'c', 'act', 'page'))) {
                $url_param[] = $key . '-' . $val;
            }
        }
        // 生成页码导航
        View::assign('pagination', Application::pagination()->initialize(array(
            'base_url'   => get_js_url('/admin/check_list/') . implode('__', $url_param),
            'total_rows' => $totalRows,
            'per_page'   => $this->per_page
        ))->create_links());

        View::assign('itemsList', $bookList);
        View::assign('workloadList', $workloadList);
        View::assign('totalRows', $totalRows);

    }


    /**
     * 修改密码界面
     */
    public function passwd_action ()
    {
        View::assign('menu_list', $this->filterAdminMenu($this->model('admin')->fetch_menu_list('admin/fill_list', 'sinho_admin_menu') ) );

        View::output("passwd");
    }
}

/* EOF */
