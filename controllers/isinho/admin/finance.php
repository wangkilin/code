<?php
/**
 * +-------------------------------------------+
 * |   iCodeBang CMS [#RELEASE_VERSION#]       |
 * |   by iCodeBang.com Team                   |
 * |   © iCodeBang.com. All Rights Reserved    |
 * |   ------------------------------------    |
 * |   Support: icodebang@126.com              |
 * |   WebSite: http://www.icodebang.com       |
 * +-------------------------------------------+
 */

defined('iCodeBang_Com') OR die('Access denied!');

class finance extends SinhoBaseController
{
    /**
     * 财务首页
     */
    public function index_action()
    {
    }

    public function setup()
    {
        if (!$this->user_info['permission'] || !$this->user_info['permission'][self::PERMISSION_ADMINISTRATION]) {
            H::redirect_msg(Application::lang()->_t('你没有访问权限'), 'admin/');
        }
    }



    /**
     * 显示成本
     */
    public function show_costing_action()
    {
        $userList = $this->model('sinhoWorkload')->getUserList(null, 'uid ASC', PHP_INT_MAX);
        $userIds  = array_column($userList, 'uid');
        $userList = array_combine($userIds, $userList);
        $year = date('Y');
        $month = date('m');
        if ($_GET['year_month']) {
            $year = substr($_GET['year_month'], 0, 4);
            $month = substr($_GET['year_month'], 4);
        } else {
            $_GET['year_month'] = $year . $month;
        }
        if(! $_GET['end_year_month'] || $_GET['end_year_month'] < $_GET['year_month']) {
            $_GET['end_year_month'] = $_GET['year_month'];
        }
        $leaveList = $this->model('sinhoWorkload')->getAskLeaveByDate(date("$year-$month-01"), date("Y-m-t", strtotime($_GET['end_year_month'] . '01')));
        $userLeaveList = array();
        foreach ($leaveList as $_itemInfo) {
            isset($userLeaveList[$_itemInfo['user_id']]) OR $userLeaveList[$_itemInfo['user_id']] = array();
            $userLeaveList[$_itemInfo['user_id']][] = $_itemInfo;
        }

        View::assign('leaveYear', $year);
        View::assign('leaveMonth', $month);
        View::assign('userList', $userList);
        View::assign('leaveList', $leaveList);
        View::assign('userLeaveList', $userLeaveList);
        View::assign('menu_list', $this->filterAdminMenu($this->model('admin')->fetch_menu_list('admin/finance/show_costing','sinho_admin_menu')  ) );


        View::import_js(G_STATIC_URL . '/js/bootstrap-multiselect.js');
        View::import_js('js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js');
        View::import_js('js/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js');
        View::import_js('js/icb_template_isinho.com.js');
        View::import_css('js/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css');
        View::import_css(G_STATIC_URL . '/css/bootstrap-multiselect.css');
        View::import_js('js/functions.js');
        View::output('admin/administration/ask_leave');
    }

    /**
     * 导入工资页面
     */
    public function monthly_pay_import_action ()
    {
        if (!$this->user_info['permission'][self::PERMISSION_ADMINISTRATION]) {
            H::redirect_msg(Application::lang()->_t('你没有访问权限'), 'admin/');
        }

        $this->crumb(Application::lang()->_t('工资导入'), 'admin/finance/monthly_pay_import/');

        View::import_js('js/fileupload.js');
        View::import_js(G_STATIC_URL . '/js/bootstrap-multiselect.js');
        View::import_js('js/icb_template_isinho.com.js');
        View::import_js('js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js');
        View::import_js('js/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js');
        View::import_css('js/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css');
        View::import_css(G_STATIC_URL . '/css/bootstrap-multiselect.css');

        View::assign('menu_list', $this->filterAdminMenu($this->model('admin')->fetch_menu_list('admin/finance/monthly_pay','sinho_admin_menu')  ) );

        View::output('admin/finance/monthly_pay_import');
    }
    /**
     * 月度支出
     */
    public function monthly_pay_action ()
    {
        // 判断是否具有权限，不具有权限，跳转到首页
        if (!$this->user_info['permission'][self::PERMISSION_ADMINISTRATION]) {
            H::redirect_msg(Application::lang()->_t('你没有访问权限'), 'admin/');
        }
        // 按照搜索条件查询
        if ($this->is_post()) {
            foreach ($_POST as $key => $val) {
                if ($key == 'start_date') {
                    $val = base64_encode($val);
                } else  {
                    $val = rawurlencode($val);
                }

                $param[] = $key . '-' . $val;
            }

            H::ajax_json_output(Application::RSM(array(
                'url' => get_js_url('/admin/finance/monthly_pay/' . implode('__', $param))
            ), 1, null));
        }

        $this->per_page = 100;
        $month = $this->model('sinhoWorkload')->max(sinhoWorkloadModel::INCOME_OUTPUT_TABLE, 'belong_year_month');
        if (! $month) {
            $month = date('Y-m');
        }
        isset($_GET['page']) OR $_GET['page'] = 1;
        empty($_GET['start_month']) AND $_GET['start_month'] = $month;
        empty($_GET['end_month']) AND $_GET['end_month'] = $month;

        $_GET['start_month'] = str_replace('-', '', $_GET['start_month']);
        $_GET['end_month']   = str_replace('-', '', $_GET['end_month']);
        $_GET['start_month'] = $_GET['start_month'] > $_GET['end_month'] ? $_GET['end_month'] : $_GET['start_month'];

        $this->crumb(Application::lang()->_t('收入支出'), 'admin/finance/monthly_pay/');

        $where = array();
        if (isset($_GET['start_month'])) {
            $where[] = 'belong_year_month >= ' . $_GET['start_month'];
        }
        if (isset($_GET['end_month'])) {
            $where[] = 'belong_year_month <= ' . $_GET['end_month'];
        }

        $excelData = $this->model('sinhoWorkload')
                                ->fetch_all(
                                    sinhoWorkloadModel::FINANCE_DATA_TABLE,
                                    'belong_year_month >= ' . $_GET['start_month']
                                      . ' AND '.'belong_year_month <= ' . $_GET['end_month']
                                      . ' AND varname="income_output" ',
                                    'belong_year_month ASC'
                                );
        $incomeWhere = $outputWhere = $where;
        $incomeWhere[] = 'direction = 1';
        $incomeItemList   = $this->model('sinhoWorkload')
                           ->fetch_all(
                               sinhoWorkloadModel::INCOME_OUTPUT_TABLE,
                               join(' AND ', $incomeWhere),
                               'belong_year_month ASC, id ASC'
                            );
        $outputWhere[] = 'direction = -1';
        $outputItemList   = $this->model('sinhoWorkload')
                            ->fetch_all(
                                sinhoWorkloadModel::INCOME_OUTPUT_TABLE,
                                join(' AND ', $outputWhere),
                                'belong_year_month ASC, id ASC'
                            );
        $beginningValue   = $this->model('sinhoWorkload')->getBeginningValue($_GET['start_month']);

        // 设置分页导航展示内容
        $url_param = array();
        foreach($_GET as $key => $val) {
            if (!in_array($key, array('app', 'c', 'act', 'page'))) {
                $url_param[] = $key . '-' . $val;
            }
        }

        View::import_js('js/icb_template_isinho.com.js');
        View::import_js('js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js');
        View::import_js('js/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js');
        View::import_css('js/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css');

        View::assign('beginningValue', floatval($beginningValue['total']));
        View::assign('startMonth', $_GET['start_month']);
        View::assign('endMonth',   $_GET['end_month']);
        View::assign('incomeItemList',   $incomeItemList);
        View::assign('outputItemList',   $outputItemList);
        View::assign('excelData',  $excelData);
        View::assign('menu_list',  $this->filterAdminMenu($this->model('admin')->fetch_menu_list('admin/finance/monthly_pay','sinho_admin_menu')  ) );


        View::output('admin/finance/monthly_pay');
    }

    /**
     * 工资展现
     */
    public function salary_action ()
    {
        // 判断是否具有权限，不具有权限，跳转到首页
        if (!$this->user_info['permission'][self::PERMISSION_ADMINISTRATION]) {
            H::redirect_msg(Application::lang()->_t('你没有访问权限'), 'admin/');
        }
        // 按照搜索条件查询
        if ($this->is_post()) {
            foreach ($_POST as $key => $val) {
                if ($key == 'start_date') {
                    $val = base64_encode($val);
                } else  {
                    $val = rawurlencode($val);
                }

                $param[] = $key . '-' . $val;
            }

            H::ajax_json_output(Application::RSM(array(
                'url' => get_js_url('/admin/finance/salary/' . implode('__', $param))
            ), 1, null));
        }

        $this->per_page = 100;
        $month = $this->model('sinhoWorkload')->max('sinho_salary_detail', 'belong_year_month');
        if (! $month) {
            $month = date('Y-m');
        }
        isset($_GET['page']) OR $_GET['page'] = 1;
        empty($_GET['start_month']) AND $_GET['start_month'] = $month;
        empty($_GET['end_month']) AND $_GET['end_month'] = $month;
        empty($_GET['user_ids']) AND $_GET['user_ids'] = array();

        $_GET['start_month'] = str_replace('-', '', $_GET['start_month']);
        $_GET['end_month']   = str_replace('-', '', $_GET['end_month']);
        $_GET['user_ids']    = is_array($_GET['user_ids']) ? $_GET['user_ids'] : explode(',', strval($_GET['user_ids']) );

        $this->crumb(Application::lang()->_t('工资'), 'admin/finance/salary/');

        // 获取员工列表
        $userList = $this->model('sinhoWorkload')->getUserList('', 'uid DESC', PHP_INT_MAX, 1);
        //var_dump($userList);
        View::assign('itemOptions', buildSelectOptions($userList, 'user_name', 'uid', $_GET['user_ids']) );

        $where = array();
        if (isset($_GET['start_month'])) {
            $where[] = 'belong_year_month >= ' . $_GET['start_month'];
        }
        if (isset($_GET['end_month'])) {
            $where[] = 'belong_year_month <= ' . $_GET['end_month'];
        }
        if (!empty($_GET['user_ids'])) {
            $where[] = 'user_id in (' . join(', ', $_GET['user_ids']) . ')';
        }

        $salaryExcelData = $this->model('sinhoWorkload')
                                ->fetch_all(
                                    sinhoWorkloadModel::FINANCE_DATA_TABLE,
                                    'belong_year_month >= ' . $_GET['start_month']
                                      . ' AND '.'belong_year_month <= ' . $_GET['end_month']
                                      . ' AND varname="salary" ',
                                    'belong_year_month ASC'
                                );
        $salaryList = $this->model('sinhoWorkload')
                           ->fetch_page(
                               sinhoWorkloadModel::SALARY_DETAIL_TABLE,
                               join(' AND ', $where),
                               'belong_year_month ASC, user_id ASC',
                               $_GET['page'],
                               $this->per_page
                            );

        $totalRows = $this->model('sinhoWorkload')->found_rows();
        $insuranceBasis = $this->model('sinhoWorkload')
                               ->fetch_one('sinho_insurance_basis', 'basis_number', 'belong_year_month<='.$_GET['end_month'], 'belong_year_month DESC');

        // 设置分页导航展示内容
        $url_param = array();
        foreach($_GET as $key => $val) {
            if ( $key=='user_ids' && is_array($val) ) {
                $val = join(',', $val);
            }
            if (!in_array($key, array('app', 'c', 'act', 'page'))) {
                $url_param[] = $key . '-' . $val;
            }
        }
        View::assign('pagination', Application::pagination()->initialize(array(
            'base_url'   => get_js_url('/admin/finance/salary/') . implode('__', $url_param),
            'total_rows' => $totalRows,
            'per_page'   => $this->per_page
        ))->create_links());


        View::import_js(G_STATIC_URL . '/js/bootstrap-multiselect.js');
        View::import_js('js/icb_template_isinho.com.js');
        View::import_css(G_STATIC_URL . '/css/bootstrap-multiselect.css');
        View::import_js('js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js');
        View::import_js('js/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js');
        View::import_css('js/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css');

        View::assign('perPage',    $this->per_page);
        View::assign('pageId',     $_GET['page']);
        View::assign('totalRows', $totalRows);
        View::assign('startMonth', $_GET['start_month']);
        View::assign('endMonth',   $_GET['end_month']);
        View::assign('userIds',    $_GET['user_ids']);
        View::assign('userList',   array_combine(array_column($userList, 'uid'), $userList));
        View::assign('salaryList', $salaryList);
        View::assign('salaryExcelData', $salaryExcelData);
        View::assign('insuranceBasis', $insuranceBasis);
        View::assign('menu_list',  $this->filterAdminMenu($this->model('admin')->fetch_menu_list('admin/finance/salary','sinho_admin_menu')  ) );

        View::output('admin/finance/salary');
    }

    /**
     * 导入工资页面
     */
    public function salary_import_action ()
    {
        if (!$this->user_info['permission'][self::PERMISSION_ADMINISTRATION]) {
            H::redirect_msg(Application::lang()->_t('你没有访问权限'), 'admin/');
        }

        $this->crumb(Application::lang()->_t('工资导入'), 'admin/finance/salary_import/');

        View::import_js('js/fileupload.js');
        View::import_js(G_STATIC_URL . '/js/bootstrap-multiselect.js');
        View::import_js('js/icb_template_isinho.com.js');
        View::import_js('js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js');
        View::import_js('js/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js');
        View::import_css('js/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css');
        View::import_css(G_STATIC_URL . '/css/bootstrap-multiselect.css');

        View::assign('menu_list', $this->filterAdminMenu($this->model('admin')->fetch_menu_list('admin/finance/salary','sinho_admin_menu')  ) );

        View::output('admin/finance/salary_import');
    }

    public function salary_statistic_action ()
    {
        // 判断是否具有权限，不具有权限，跳转到首页
        if (!$this->user_info['permission'][self::PERMISSION_ADMINISTRATION]) {
            H::redirect_msg(Application::lang()->_t('你没有访问权限'), 'admin/');
        }
        // 按照搜索条件查询
        if ($this->is_post()) {
            foreach ($_POST as $key => $val) {
                if ($key == 'start_date') {
                    $val = base64_encode($val);
                } else  {
                    $val = rawurlencode($val);
                }

                $param[] = $key . '-' . $val;
            }

            H::ajax_json_output(Application::RSM(array(
                'url' => get_js_url('/admin/finance/salary/' . implode('__', $param))
            ), 1, null));
        }

        $this->per_page = 100;
        $month = $this->model('sinhoWorkload')->max('sinho_salary_detail', 'belong_year_month');
        if (! $month) {
            $month = date('Y-m');
        }
        empty($_GET['start_month']) AND $_GET['start_month'] = $month;
        empty($_GET['end_month']) AND $_GET['end_month'] = $month;
        empty($_GET['user_ids']) AND $_GET['user_ids'] = array();

        $_GET['start_month'] = str_replace('-', '', $_GET['start_month']);
        $_GET['end_month']   = str_replace('-', '', $_GET['end_month']);
        $_GET['user_ids']    = is_array($_GET['user_ids']) ? $_GET['user_ids'] : explode(',', strval($_GET['user_ids']) );

        $this->crumb(Application::lang()->_t('工资'), 'admin/finance/salary/');

        // 获取员工列表
        $userList = $this->model('sinhoWorkload')->getUserList('', 'uid DESC', PHP_INT_MAX, 1);
        //var_dump($userList);
        View::assign('itemOptions', buildSelectOptions($userList, 'user_name', 'uid', $_GET['user_ids']) );

        $salaryStatData = $this->model('sinhoWorkload')->getSalaryStatistic($_GET['start_month'], $_GET['end_month'], $_GET['user_ids']);
        $salaryStatData = array_combine(array_column($salaryStatData,'belong_year_month'), $salaryStatData);
        $gonghuiFeeData = $this->model('sinhoWorkload')->getGonghuiFee($_GET['start_month'], $_GET['end_month'], $_GET['user_ids']);
        $gonghuiFeeData = array_combine(array_column($gonghuiFeeData, 'belong_year_month'), $gonghuiFeeData);

        foreach ($salaryStatData as $_key => & $_itemInfo) {
            $_itemInfo['gongsi_quanbu'] = $_itemInfo['shifa_gongzi']
                                        + $_itemInfo['geren_heji']
                                        + $_itemInfo['gongsi_heji']
                                        + $gonghuiFeeData[$_key]['gonghuijingfei'];
            $_itemInfo['gonghuijingfei'] = $gonghuiFeeData[$_key]['gonghuijingfei'];
        }


        // 设置分页导航展示内容
        $url_param = array();
        foreach($_GET as $key => $val) {
            if ( $key=='user_ids' && is_array($val) ) {
                $val = join(',', $val);
            }
            if (!in_array($key, array('app', 'c', 'act', 'page'))) {
                $url_param[] = $key . '-' . $val;
            }
        }

        View::import_js(G_STATIC_URL . '/js/bootstrap-multiselect.js');
        View::import_css(G_STATIC_URL . '/css/bootstrap-multiselect.css');
        View::import_js('js/functions.js');
        View::import_js('js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js');
        View::import_js('js/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js');
        View::import_js('js/icb_template_isinho.com.js');
        View::import_css('js/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css');

        View::assign('statData', $salaryStatData);
        View::assign('startMonth', $_GET['start_month']);
        View::assign('endMonth',   $_GET['end_month']);
        View::assign('userIds',    $_GET['user_ids']);
        View::assign('menu_list', $this->filterAdminMenu($this->model('admin')->fetch_menu_list('admin/finance/salary', 'sinho_admin_menu') ) );

        View::output('admin/finance/salary_statistic');
    }



}

/* EOF */
