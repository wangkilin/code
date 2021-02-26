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

        View::assign('menu_list', $this->filterAdminMenu($this->model('admin')->fetch_menu_list('admin/administration/holiday','sinho_admin_menu') ) );

        View::import_js('js/functions.js');
        View::output('admin/administration/holiday');
    }
}

/* EOF */
