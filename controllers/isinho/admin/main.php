<?php
defined('iCodeBang_Com') OR die('Access denied!');

class main extends SinhoBaseController
{
    const PERMISSION_BOOKLIST = 'sinho_book_list';

    public function index_action()
    {
        $this->crumb(Application::lang()->_t('概述'), 'admin/main/');

        $this->hostConfig->sinho_feature_list['enable_page_manage'] == true
         AND View::assign('intranetNewsList', $this->model()->fetch_page('pages', 'publish_area != ' . pageModel::PUBLIC_AREA_OUTSIDE . ' AND enabled = 1 AND publish_time <=' . time(), 'modify_time desc') );

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
        $scheduleList = $this->model('sinhoWorkload')->fetch_one(sinhoWorkloadModel::SCHEDULE_TABLE, 'month_'.$currentMonth, 'belong_year = ' . $currentYear);
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
                $workdayHolidays[$currentYear][$workdayOrHoliday][$currentMonth][] = date('j', strtotime($_dateString) );
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

        // 获取加班天数， 周末带稿天数
        $overtimeHours = 0;
        $leaveList = $this->model('sinhoWorkload')->getAskLeaveByDate(date("$currentYear-$currentMonth-01"), date("Y-m-t", strtotime("$currentYear-$currentMonth-01")));
        foreach ($leaveList as $_itemInfo) {
            if ($this->user_id != $_itemInfo['user_id'] || $_itemInfo['leave_type']!= self::LEAVE_TYPE_WEEKEND_WORKLOAD) {
                continue;
            }
            $overtimeHours += $_itemInfo['leave_period'];
        }

        View::assign('overtimeHours', $overtimeHours);
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
            $personalQuarlityList = array();
            while ($startMonth <= $belongMonth) {
                $personalQuarlityList[$startMonth] = $this->model('sinhoWorkload')->getQuarlityStatByUserIds(array($this->user_id), array('start'=>$belongMonth, 'end'=>$startMonth));
                $personalQuarlityList[$startMonth] = array_sum(array_column($personalQuarlityList[$startMonth], 'quarlity_num'));
                $personalWorkloadList[$startMonth] = $this->model('sinhoWorkload')->getWorkloadStatByUserIds (array($this->user_id), sinhoWorkloadModel::STATUS_VERIFIED, $startMonth);
                $personalWorkloadList[$startMonth] = array_sum(array_column($personalWorkloadList[$startMonth], 'total_chars'));
                $startMonth = date('Ym', strtotime("{$startMonth}01 +1month"));
            }
            $personalWorkloadList[$currentYearMonth]=View::$view->currentWorkload;
            //$personalQuarlityList[$currentYearMonth]=0;
            View::assign('personalWorkloadList', $personalWorkloadList);
            View::assign('personalQuarlityList', $personalQuarlityList);
            //var_dump($personalQuarlityList);
        }

        $totalCharsListLastMonth = array();
        $userList = array();
        // 管理权限： 工作量榜单，已结算书稿
        if ($this->hasRolePermission(self::IS_SINHO_CHECK_WORKLOAD)) {
            $quarlityList = $this->model('sinhoWorkload')->getQuarlityStatByUserIds(array(), array('start'=>$belongMonth, 'end'=>$belongMonth));
            $quarlityList = array_combine(array_column($quarlityList,'user_id'), array_column($quarlityList,'quarlity_num'));
            $userList = $this->model('sinhoWorkload')->getUserList(null, 'uid DESC', PHP_INT_MAX);
            $userIds  = array_column($userList, 'uid');
            $userList = array_combine($userIds, $userList);
            // 按月获取每个人的工作量
            $workloadStatLastMonth = $this->model('sinhoWorkload')->getWorkloadStatByUserIds (array(), null, $belongMonth);
            $totalCharsListLastMonth = array_combine(array_column($workloadStatLastMonth,'user_id'), array_column($workloadStatLastMonth,'total_chars'));
            $totalCharsWithoutWeightListLastMonth = array_combine(array_column($workloadStatLastMonth,'user_id'), array_column($workloadStatLastMonth,'total_chars_without_weight'));
            $totalCharsWeightLt1ListLastMonth = array_combine(array_column($workloadStatLastMonth,'user_id'), array_column($workloadStatLastMonth,'total_chars_weight_lt_1'));
            arsort($totalCharsListLastMonth, SORT_NUMERIC);
            // 获取当前工作月份员工的工作量
            $workloadStatCurrentMonth = $this->model('sinhoWorkload')->getWorkloadStatByUserIds (array(), array(sinhoWorkloadModel::STATUS_VERIFYING, sinhoWorkloadModel::STATUS_RECORDING), array('start'=>$currentYearMonth) );
            $workloadStatCurrentMonth = array_combine(array_column($workloadStatCurrentMonth,'user_id'), array_column($workloadStatCurrentMonth,'total_chars'));
            arsort($workloadStatCurrentMonth, SORT_NUMERIC);


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
                    if (!$_statInfo['user_id']) {
                        continue;
                    }
                    $employeeWorkloadList[$_statInfo['user_id']][$_month] = $allTotalChars[] = $_statInfo['total_chars'];
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
            View::assign('quarlityStat', $quarlityList);
            View::assign('allTotalChars', $allTotalChars);
            View::assign('employeeWorkloadList', $employeeWorkloadList);
        }

        View::assign('belongMonth', $belongMonth);
        View::assign('belongMinMonth', $belongMinMonth);
        View::assign('warningMsgList', $warningMsgList);
        View::assign('totalCharsListLastMonth', $totalCharsListLastMonth);
        View::assign('totalCharsWithoutWeightListLastMonth', $totalCharsWithoutWeightListLastMonth);
        View::assign('totalCharsWeightLt1ListLastMonth', $totalCharsWeightLt1ListLastMonth);
        View::assign('userList', $userList);
        View::assign('workloadStatCurrentMonth', $workloadStatCurrentMonth);


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

        View::assign('hostConfig', $this->hostConfig);
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
        View::import_js(G_STATIC_URL . '/js/bootstrap-multiselect.js');
        View::import_css(G_STATIC_URL . '/css/bootstrap-multiselect.css');

        View::assign('menu_list', $this->filterAdminMenu($this->model('admin')->fetch_menu_list('admin/fill_list', 'sinho_admin_menu') ) );
        View::output('admin/workload/fill_list');
    }

    /**
     * 填充工作量
     */
    public function fill_workload_action ()
    {
        View::assign('hostConfig', $this->hostConfig);
        $itemInfo = $this->model('sinhoWorkload')
                         ->fetch_row(
                            sinhoWorkloadModel::WORKLOAD_TABLE,
                            'id = ' . intval($_GET['id'])
                            . ' AND user_id = ' . $this->user_id
                            . ' AND `status`<>' . sinhoWorkloadModel::STATUS_DELETE );

        if (! $itemInfo) {
            H::redirect_msg(Application::lang()->_t('操作失败'), 'admin/fill_list/');
        }
        $bookInfo = $this->model('sinhoWorkload')->fetch_row(sinhoWorkloadModel::BOOK_TABLE, 'id = ' . intval($itemInfo['book_id']));

        // 根据书稿id ， 获取到对应的工作量
        $allList = $this->model('sinhoWorkload')
                        ->fetch_all ( sinhoWorkloadModel::WORKLOAD_TABLE,
                                    'book_id = ' . intval($itemInfo['book_id'])
                                    . ' AND status <> ' . sinhoWorkloadModel::STATUS_DELETE
                            );
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
        $bookList = array($bookInfo);

        View::assign('itemsList', $bookList);
        View::assign('workloadList', $workloadList);
        View::assign('userList', $userList);
        View::assign('quarlityList', array());


        View::assign('doLoadFillWorkloadForm', true);

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
            $allList = $this->model('sinhoWorkload')->fetch_all(sinhoWorkloadModel::WORKLOAD_TABLE, 'book_id IN (' . join(',', $bookIds) . ') AND (status != ' . sinhoWorkloadModel::STATUS_DELETE . ' )', 'book_id,category,working_times');
        }

        $workloadList = array();
        foreach ($allList as $_itemInfo) {
            isset($workloadList[$_itemInfo['book_id']]) OR $workloadList[$_itemInfo['book_id']] = array();

            $workloadList[$_itemInfo['book_id']][] = $_itemInfo;
        }
        $bookIds = array_keys($workloadList);
        $bookList = array();
        if ($bookIds) {
            $orderby = $_GET['orderby']=='book' ? 'category,serial,book_name,proofreading_times' : 'id DESC';
            $bookList = $this->model('sinhoWorkload')->fetch_all(sinhoWorkloadModel::BOOK_TABLE, 'id IN (' . join(',', $bookIds) . ')', $orderby);
        }


        $queryUserIds = array();
        if ($_GET['user_id']) {
            $queryUserIds = explode(',', $_GET['user_id']);
            foreach ($queryUserIds as & $_id) {
                $_id = intval($_id);
            }
        }
        $userList = $this->model('sinhoWorkload')->getUserList(null, 'uid DESC', PHP_INT_MAX);

        View::assign('itemOptions', buildSelectOptions($userList, 'user_name', 'uid', $queryUserIds ) );
        $userIds  = array_column($userList, 'uid');
        $userList = array_combine($userIds, $userList);

        $queryUserIds = empty($queryUserIds) ? $userIds : $queryUserIds;
        $belongMonth = $this->model('sinhoWorkload')->max(sinhoWorkloadModel::WORKLOAD_TABLE, 'belong_month', 'belong_month >= ' . date('Ym', strtotime('-3month')));
        if (! $belongMonth) { // 如果没有工作量， 将上个月的月份作为记录最大月份
            $belongMonth = date('Ym', strtotime('-1month'));
        }
        $endBelongMonth = isset($_GET['end_month']) ? $_GET['end_month'] : $belongMonth;
        if (!$_GET['belong_month'] || $_GET['belong_month'] > $belongMonth || $_GET['belong_month']<200001) { // 获取待核算月份的数据
            $totalCharsList = $this->model('sinhoWorkload')->getWorkloadStatByUserIds ($queryUserIds, null, array('start'=>$belongMonth,'end'=>$endBelongMonth), 'user_id,belong_month');
        } else {// 获取指定月份的数据
            $belongMonth = $_GET['belong_month'];
            $totalCharsList = $this->model('sinhoWorkload')->getWorkloadStatByUserIds ($queryUserIds, null, array('start'=>$belongMonth,'end'=>$endBelongMonth), 'user_id,belong_month');
        }
        $this->assign('belongMonth', substr($belongMonth,0,4).'-'.sprintf('%02d', substr($belongMonth,4)) );
        $this->assign('endBelongMonth', substr($endBelongMonth,0,4).'-'.sprintf('%02d', substr($endBelongMonth,4)) );

        View::assign('userList', $userList);
        View::assign('itemsList', $bookList);
        View::assign('workloadList', $workloadList);
        View::assign('totalCharsList', $totalCharsList);

        View::assign('menu_list', $this->filterAdminMenu($this->model('admin')->fetch_menu_list('admin/verify_list', 'sinho_admin_menu')  ) );


        View::import_js('js/functions.js');
        View::import_js('js/icb_template_isinho.com.js');

        View::import_js(G_STATIC_URL . '/js/bootstrap-multiselect.js');
        View::import_css(G_STATIC_URL . '/css/bootstrap-multiselect.css');
        View::import_js('js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js');
        View::import_js('js/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js');
        View::import_css('js/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css');
        View::import_css('isinho.com/animate.min.css');

        View::output('admin/workload/verify');
    }

    /**
     * 查看工作量
     */
    public function check_list_action ()
    {
        $this->checkPermission(self::IS_SINHO_CHECK_WORKLOAD | self::IS_SINHO_ADMIN);
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

        View::assign('isSinhoAdmin', $this->hasRolePermission(self::IS_SINHO_ADMIN));
        View::assign('amountPerPage', $this->per_page);
        View::assign('userList', $userList);
        View::assign('bookList', $bookList);

        View::assign('menu_list', $this->filterAdminMenu($this->model('admin')->fetch_menu_list('admin/check_list','sinho_admin_menu') ) );
        View::output('admin/workload/check');
    }

    /**
     * 按照用户查询工作量
     */
    protected function check_by_user (& $userList, & $bookList)
    {
        $this->per_page = 30;
        $queryUserIds = array();
        //$where = 'status <> ' . sinhoWorkloadModel::STATUS_DELETE . ' AND status <> ' . sinhoWorkloadModel::STATUS_RECORDING;
        $where = 'status <> ' . sinhoWorkloadModel::STATUS_DELETE ;
        if ($_GET['id']) { // 解析用户id
            $queryUserIds = explode(',', $_GET['id']);
            foreach ($queryUserIds as & $_id) {
                $_id = intval($_id);
            }
            // 获取指定用户数据
            $where .= ' AND user_id IN ( ' . join(', ',  $queryUserIds). ') ';
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
                                    'belong_month desc,user_id,id desc',
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
            $where = array();
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
            if ($_GET['good_or_bad']) {
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
            'base_url'   => get_js_url('/admin/check_list/') . implode('__', $url_param),
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
     * 修改密码界面
     */
    public function passwd_action ()
    {
        View::assign('menu_list', $this->filterAdminMenu($this->model('admin')->fetch_menu_list('admin/fill_list', 'sinho_admin_menu') ) );

        View::output("passwd");
    }
}

/* EOF */
