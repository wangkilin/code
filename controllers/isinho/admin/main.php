<?php
defined('iCodeBang_Com') OR die('Access denied!');

require_once __DIR__ . '/../SinhoBaseController.php';

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
        $workdayHolidays = Application::config()->get('system')->sites[$_SERVER['HTTP_HOST']]['workday_holiday'];

        // 计算当月的工作日天数
        $nowMonth = date('n');
        $nowDay   = date('j');
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

            View::assign('monthList', array_keys($itemList));
            View::assign('allTotalChars', $allTotalChars);
            View::assign('employeeWorkloadList', $employeeWorkloadList);
        }
        View::assign('belongMonth', $belongMonth);
        View::assign('warningMsgList', $warningMsgList);
        View::assign('totalCharsListLastMonth', $totalCharsListLastMonth);
        //View::assign('totalCharsWithoutWeightListLastMonth', $totalCharsWithoutWeightListLastMonth);
        View::assign('userList', $userList);


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
        if ($bookIds) {
            $verifiedList = $this->model('sinhoWorkload')->fetch_all(sinhoWorkloadModel::WORKLOAD_TABLE, 'book_id IN (' . join(',', $bookIds) . ') AND status = ' . sinhoWorkloadModel::STATUS_VERIFIED, 'book_id,category,working_times');
        }
        $allList = array_merge($toBeVerifiedList, $verifiedList);

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
        $this->checkPermission(self::IS_SINHO_BOOK_ADMIN);
        $toBeVerifiedList = $this->model('sinhoWorkload')->fetch_all(sinhoWorkloadModel::WORKLOAD_TABLE, 'status = ' . sinhoWorkloadModel::STATUS_VERIFYING, 'book_id,category,working_times');
        $bookIds  = array_column($toBeVerifiedList, 'book_id');
        $verifiedList = array();
        if ($bookIds) {
            $verifiedList = $this->model('sinhoWorkload')->fetch_all(sinhoWorkloadModel::WORKLOAD_TABLE, 'book_id IN (' . join(',', $bookIds) . ') AND status = ' . sinhoWorkloadModel::STATUS_VERIFIED, 'book_id,category,working_times');
        }
        $allList = array_merge($toBeVerifiedList, $verifiedList);

        $workloadList = array();
        foreach ($allList as $_itemInfo) {
            isset($workloadList[$_itemInfo['book_id']]) OR $workloadList[$_itemInfo['book_id']] = array();

            $workloadList[$_itemInfo['book_id']][] = $_itemInfo;
        }
        $bookIds = array_keys($workloadList);
        $bookList = $this->model('sinhoWorkload')->fetch_all(sinhoWorkloadModel::BOOK_TABLE, 'id IN (' . join(',', $bookIds) . ')', 'id DESC');

        $userList = $this->model('sinhoWorkload')->getUserList(null, 'uid DESC', PHP_INT_MAX);
        $userIds  = array_column($userList, 'uid');
        $userList = array_combine($userIds, $userList);

        View::assign('userList', $userList);
        View::assign('itemsList', $bookList);
        View::assign('workloadList', $workloadList);

        View::assign('menu_list', $this->filterAdminMenu($this->model('admin')->fetch_menu_list('admin/check_list','sinho_admin_menu') ) );
        View::output('admin/workload/check');
    }


}

/* EOF */
