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

class administration extends SinhoBaseController
{
    CONST LEAVE_TYPE_PRIVATE                = 1  ; // '事假',
    CONST LEAVE_TYPE_SICK                   = 2  ; // '病假',
    CONST LEAVE_TYPE_ANNUAL                 = 3  ; // '年假',
    CONST LEAVE_TYPE_WEDDING                = 4  ; // '婚假',
    CONST LEAVE_TYPE_MATERNITY              = 5  ; // '产假',
    CONST LEAVE_TYPE_PERIOD                 = 6  ; // '生理',
    CONST LEAVE_TYPE_BODY_CHECK             = 8  ; // '产检',
    CONST LEAVE_TYPE_FUNERAL                = 9  ; // '丧假',
    CONST LEAVE_TYPE_NO_REASON              = 20 ; // '旷工',
    CONST LEAVE_TYPE_OVERTIME               = 50 ; // '++ 加班',
    CONST LEAVE_TYPE_WEEKEND_WORKLOAD       = 51 ; // 周末带稿
    CONST LEAVE_TYPE_HOMEWORK               = 55 ; // '🏠居家办公'

    protected $leaveTypeList = array (
        self::LEAVE_TYPE_PRIVATE            => array('name' => '事假',          'icon' => 'icon-private-leave'),
        self::LEAVE_TYPE_SICK               => array('name' => '病假',          'icon' => 'icon-sick-leave'),
        self::LEAVE_TYPE_ANNUAL             => array('name' => '年假',          'icon' => 'icon-annual-leave'),
        self::LEAVE_TYPE_WEDDING            => array('name' => '婚假',          'icon' => 'icon-wedding-leave'),
        self::LEAVE_TYPE_MATERNITY          => array('name' => '产假',          'icon' => 'icon-maternity-leave'),
        self::LEAVE_TYPE_PERIOD             => array('name' => '生理假',        'icon' => 'icon-period-leave'),
        self::LEAVE_TYPE_BODY_CHECK         => array('name' => '产检',          'icon' => 'icon-body-check'),
        self::LEAVE_TYPE_FUNERAL            => array('name' => '丧假',          'icon' => 'icon-funeral-leave'),
        self::LEAVE_TYPE_NO_REASON          => array('name' => '旷工',          'icon' => 'icon-leave'),
        self::LEAVE_TYPE_OVERTIME           => array('name' => '++ 加班',       'icon' => 'icon-add-to-list'),
        self::LEAVE_TYPE_WEEKEND_WORKLOAD   => array('name' => '周末带稿',       'icon' => 'icon-attach'),
        self::LEAVE_TYPE_HOMEWORK           => array('name' => '🏠居家办公',     'icon' => 'icon-home'),
    );

    /**
     * 教程文章列表
     */
    public function index_action()
    {
    }

    /**
     * 考勤管理
     */
    public function ask_leave_action()
    {
        $userList = $this->model('sinhoWorkload')->getUserList(null, 'forbidden ASC,uid ASC', PHP_INT_MAX);
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
        View::assign('leaveTypeList', $this->leaveTypeList);

        View::assign('leaveYear', $year);
        View::assign('leaveMonth', $month);
        View::assign('userList', $userList);
        View::assign('leaveList', $leaveList);
        View::assign('userLeaveList', $userLeaveList);
        View::assign('menu_list', $this->filterAdminMenu($this->model('admin')->fetch_menu_list('admin/administration/ask_leave','sinho_admin_menu') ) );

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
     * 假期设置
     */
    public function holiday_action ()
    {
        $itemList = $this->model('sinhoWorkload')->fetch_all(sinhoWorkloadModel::SCHEDULE_TABLE);
        $scheduleList = array();
        foreach ($itemList as $_annualScheduleList) {
            foreach ($_annualScheduleList as $_key=>$_monthlySchedule) {
                if ('belong_year' == $_key || !$_monthlySchedule || '[]'==$_monthlySchedule) {
                    continue;
                }
                $_monthlySchedule = json_decode($_monthlySchedule, true);
                $scheduleList = array_merge($scheduleList, $_monthlySchedule);
            }
        }

        $workingtime = $this->model()->fetch_one('sinho_key_value', 'value', 'varname = "workingtime"');
        if ($workingtime) {
            $workingtime = @json_decode($workingtime, true);
        } else {
            $workingtime = array();
        }

        View::assign('workingtime', $workingtime);
        View::assign('scheduleList', $scheduleList);
        View::assign('menu_list', $this->filterAdminMenu($this->model('admin')->fetch_menu_list('admin/administration/holiday','sinho_admin_menu') ) );

        View::import_js('js/calendar.js');
        View::import_js('js/functions.js');
        View::import_js('js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js');
        View::import_js('js/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js');
        View::import_css('js/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css');

        View::output('admin/administration/holiday');
    }

    /**
     * 编辑管理
     */
    public function editor_action ()
    {
        if (!$this->user_info['permission'][self::PERMISSION_ADMINISTRATION]) {
            H::redirect_msg(Application::lang()->_t('你没有访问权限'), 'admin/');
        }
        if ($this->is_post()) {
            foreach ($_POST as $key => $val) {
                if ($key == 'start_date' OR $key == 'end_date') {
                    $val = base64_encode($val);
                } else  {
                    $val = rawurlencode($val);
                }

                $param[] = $key . '-' . $val;
            }

            H::ajax_json_output(Application::RSM(array(
                'url' => get_js_url('/admin/administration/editor/' . implode('__', $param))
            ), 1, null));
        }

        $this->crumb(Application::lang()->_t('编辑设置'), 'admin/administration/editor/');

        //$this->per_page=5;
        $where = array();
        if ($_GET['keyword']) {
            $where[] = "title LIKE '" . $this->model('course')->quote($_GET['keyword']) . "%'";
        }
        $userList = $this->model('sinhoWorkload')->getUserList($where, 'uid DESC', $this->per_page, $_GET['page']);
        $userIds  = array_column($userList, 'uid');
        $totalRows = $this->model('sinhoWorkload')->found_rows();

        $url_param = array();
        foreach($_GET as $key => $val) {
            if (!in_array($key, array('app', 'c', 'act', 'page'))) {
                $url_param[] = $key . '-' . $val;
            }
        }

        View::assign('pagination', Application::pagination()->initialize(array(
            'base_url'   => get_js_url('/admin/administration/editor/') . implode('__', $url_param),
            'total_rows' => $totalRows,
            'per_page'   => $this->per_page
        ))->create_links());
        //$categoryList = $this->model('category')->getAllCategories('id');
        $groupList = $this->model('account')->get_user_group_list(0, 1);
        foreach ($groupList as & $_item) {
            $_item['permission'] = unserialize($_item['permission']);
        }
        $userAttributes = array();
        $itemList = array();
        if ($userIds) {
            $itemList = $this->model()->fetch_all('users_attribute', 'uid IN ('.join(',', $userIds).')');
        }
        foreach ($itemList as $_itemInfo) {
            isset($userAttributes[$_itemInfo['uid']]) OR $userAttributes[$_itemInfo['uid']] = array();
            if ($_itemInfo['decode_method'] && function_exists($_itemInfo['decode_method'])) {
                if ($_itemInfo['decode_method']=='json_decode') {
                    $_itemInfo['attr_value'] = json_decode($_itemInfo['attr_value'], true);
                } else {
                    $_itemInfo['attr_value'] = $_itemInfo['decode_method'] ($_itemInfo['attr_value']);
                }
            }
            if($_itemInfo['attr_key']=='sinho_more_subject') {
                foreach ($_itemInfo['attr_value'] as & $_subject) {
                    $_subject = SinhoBaseController::SUBJECT_LIST[$_subject]['name'];
                }
            }
            $userAttributes[$_itemInfo['uid']][$_itemInfo['attr_key']] = $_itemInfo['attr_value'];
        }

        //View::assign('moreSubjects', $moreSubjectList);
        View::assign('userAttributes', $userAttributes);
        View::assign('itemsList', $userList);
        View::assign('groupList', $groupList);
        View::assign('itemOptions', buildSelectOptions($userList, 'user_name', 'uid' ) );
        //View::assign('itemOptions', $this->buildCategoryDropdownHtml('0', $selected, '--'));
        View::assign('totalRows', $totalRows);
        View::assign('amountPerPage', $this->per_page);

        View::import_js(G_STATIC_URL . '/js/bootstrap-multiselect.js');
        View::import_js('js/icb_template_isinho.com.js');
        View::import_css(G_STATIC_URL . '/css/bootstrap-multiselect.css');

        View::assign('menu_list', $this->filterAdminMenu($this->model('admin')->fetch_menu_list(null,'sinho_admin_menu')  ) );


        View::output('admin/administration/user_list');
    }

    /**
     * 编辑员工信息
     */
    public function editor_edit_action ()
    {
        if (!$this->user_info['permission'][self::PERMISSION_ADMINISTRATION]) {
            H::redirect_msg(Application::lang()->_t('你没有访问权限'), 'admin/');
        }

        $userInfo = $this->model('account')->getUserById($_GET['id']);
        $groupList = $this->model('account')->get_user_group_list(0, 1);
        $itemList = $this->model()->fetch_all('users_attribute', 'uid='.$_GET['id']);
        $userAttributes = array();
        $moreSubject = array();
        foreach ($itemList as $_itemInfo) {
            if ($_itemInfo['attr_key']  =="sinho_more_subject") {
                $moreSubject = json_decode($_itemInfo['attr_value']);
            } else {
                $userAttributes[$_itemInfo['attr_key']] = $_itemInfo['attr_value'];
            }
        }

        View::assign('userAttributes', $userAttributes);
        View::assign('moreSubjects', $moreSubject);
        View::assign('userInfo', $userInfo);
        View::assign('groupList', $groupList);
        View::assign('groupOptions', buildSelectOptions($groupList, 'group_name', 'group_id', $userInfo['group_id'] ) );
        View::assign('menu_list', $this->filterAdminMenu($this->model('admin')->fetch_menu_list('admin/administration/editor','sinho_admin_menu')  ) );

        View::import_js(G_STATIC_URL . '/js/functions.js');
        View::import_js(G_STATIC_URL . '/js/bootstrap-multiselect.js');
        View::import_css(G_STATIC_URL . '/css/bootstrap-multiselect.css');

        View::output('admin/administration/user_edit');
    }

    /**
     * 组列表
     */
    public function group_list_action()
    {
        $this->crumb(Application::lang()->_t('组管理'), "admin/administration/group_list/");

        $this->checkPermission(self::IS_SINHO_ADMIN | self::IS_ROLE_ADMIN);

        View::assign('custom_group', $this->model('account')->get_user_group_list(0, 1));
        View::assign('menu_list', $this->filterAdminMenu($this->model('admin')->fetch_menu_list('admin/administration/group_list', 'sinho_admin_menu')) );
        View::output('admin/administration/group_list');
    }

    /**
     * 编辑组信息
     */
    public function group_edit_action()
    {
        $this->crumb(Application::lang()->_t('修改组'), "admin/administration/group_list/");

        $this->checkPermission(self::IS_SINHO_ADMIN | self::IS_ROLE_ADMIN);

        if (! $group = $this->model('account')->get_user_group_by_id($_GET['group_id'])) {
            H::redirect_msg(Application::lang()->_t('用户组不存在'), '/admin/administration/group_list/');
        }

        $booleanParamList = array (
            'sinho'     => array (
                SinhoBaseController::PERMISSION_BOOKLIST        => Application::lang()->_t('允许管理稿件'),
                SinhoBaseController::PERMISSION_FILL_WORKLOAD   => Application::lang()->_t('允许添加个人工作量'),
                SinhoBaseController::PERMISSION_VERIFY_WORKLOAD => Application::lang()->_t('允许核算工作量'),
                SinhoBaseController::PERMISSION_CHECK_WORKLOAD  => Application::lang()->_t('允许查阅工作量'),
                SinhoBaseController::PERMISSION_ADMINISTRATION  => Application::lang()->_t('允许管理行政&人事'),
            ),
        );

        View::assign('booleanParamList', $booleanParamList);
        View::assign('group', $group);
        View::assign('group_pms', $group['permission']);
        View::assign('menu_list', $this->filterAdminMenu($this->model('admin')->fetch_menu_list('admin/administration/group_list', 'sinho_admin_menu')) );
        View::output('admin/administration/group_edit');
    }


}

/* EOF */
