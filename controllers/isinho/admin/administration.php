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
    /**
     * 教程文章列表
     */
    public function index_action()
    {
    }

    /**
     * 新建编辑书稿
     */
    public function ask_leave_action()
    {
        $userList = $this->model('sinhoWorkload')->getUserList(null, 'uid ASC', PHP_INT_MAX);
        $userIds  = array_column($userList, 'uid');
        $userList = array_combine($userIds, $userList);
        $year = date('Y');
        $month = date('m');
        if ($_GET['year_month']) {
            $year = substr($_GET['year_month'], 0, 4);
            $month = substr($_GET['year_month'], 4);
        }

        $leaveList = $this->model('sinhoWorkload')->getAskLeaveByDate(date("$year-$month-01"), date("$year-$month-t"));

        View::assign('leaveYear', $year);
        View::assign('leaveMonth', $month);
        View::assign('userList', $userList);
        View::assign('leaveList', $leaveList);
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

        View::assign('scheduleList', $scheduleList);
        View::assign('menu_list', $this->filterAdminMenu($this->model('admin')->fetch_menu_list('admin/administration/holiday','sinho_admin_menu') ) );

        View::import_js('js/calendar.js');
        View::import_js('js/functions.js');
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
        $moreSubjectList = $this->model()->fetch_all('users_attribute', 'attr_key ="sinho_more_subject"');
        $userIds = array_column($moreSubjectList, 'uid');
        $moreSubjectList = array_combine($userIds, $moreSubjectList);
        foreach ($moreSubjectList as & $_item) {
            $_item = json_decode($_item['attr_value']);
            foreach ($_item as & $_subject) {
                $_subject = SinhoBaseController::SUBJECT_LIST[$_subject]['name'];
            }
        }

        View::assign('moreSubjects', $moreSubjectList);
        View::assign('itemsList', $userList);
        View::assign('groupList', $groupList);
        View::assign('itemOptions', buildSelectOptions($userList, 'user_name', 'uid' ) );
        //View::assign('itemOptions', $this->buildCategoryDropdownHtml('0', $selected, '--'));
        View::assign('totalRows', $totalRows);
        View::assign('amountPerPage', $this->per_page);

        View::import_js(G_STATIC_URL . '/js/bootstrap-multiselect.js');
        View::import_js('js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js');
        View::import_js('js/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js');
        View::import_js('js/icb_template_isinho.com.js');
        View::import_css('js/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css');
        View::import_css(G_STATIC_URL . '/css/bootstrap-multiselect.css');

        View::assign('menu_list', $this->filterAdminMenu($this->model('admin')->fetch_menu_list(null,'sinho_admin_menu')  ) );


        View::output('admin/administration/user_list');
    }

    public function editor_edit_action ()
    {
        if (!$this->user_info['permission'][self::PERMISSION_ADMINISTRATION]) {
            H::redirect_msg(Application::lang()->_t('你没有访问权限'), 'admin/');
        }

        $userInfo = $this->model('account')->getUserById($_GET['id']);
        $groupList = $this->model('account')->get_user_group_list(0, 1);
        $moreSubject = $this->model()->fetch_row('users_attribute', 'uid='.$_GET['id'] . ' AND attr_key ="sinho_more_subject"');
        if ($moreSubject) {
            $moreSubject = json_decode($moreSubject['attr_value']);
        } else {
            $moreSubject = array();
        }

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
}

/* EOF */
