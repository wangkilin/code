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

/**
 * 前台控制器
 */
class SinhoBaseController extends BaseController
{

    const PERMISSION_VERIFY_WORKLOAD    = 'sinho_verify_workload';
    const PERMISSION_FILL_WORKLOAD      = 'sinho_fill_workload';
    const PERMISSION_BOOKLIST = 'sinho_modify_manuscript_param';
    const PERMISSION_CHECK_WORKLOAD     = 'sinho_check_workload';
    const PERMISSION_ADMINISTRATION     = 'sinho_administration';
    const SINHO_PERMISSION_LIST = array(
        self::PERMISSION_FILL_WORKLOAD,
        self::PERMISSION_BOOKLIST,
        self::PERMISSION_VERIFY_WORKLOAD,
        self::PERMISSION_CHECK_WORKLOAD,
        self::PERMISSION_ADMINISTRATION,
    );

    // const PERMISSION_BOOKLIST        = SinhoBaseController::PERMISSION_MODIFY_MANUSCRIPT_PARAM;
    // const PERMISSION_VERIFY_WORKLOAD = SinhoBaseController::PERMISSION_VERIFY_WORKLOAD;
    // const PERMISSION_FILL_WORKLOAD   = SinhoBaseController::PERMISSION_FILL_WORKLOAD;
    // const PERMISSION_CHECK_WORKLOAD  = SinhoBaseController::PERMISSION_CHECK_WORKLOAD;

    const IS_SINHO_BOOK_ADMIN = 0x04; // 新禾图书编辑
    const IS_SINHO_FILL_WORKLOAD = 0x08; // 填充工作量
    const IS_SINHO_VERIFY_WORKLOAD = 0x10; // 核对工作量
    const IS_SINHO_CHECK_WORKLOAD  = 0x20; // 查看工作量
    const IS_SINHO_ADMIN           = 0x40; // 行政管理

    const PERMISSION_MAP = array (
        self::IS_ROLE_ADMIN         => parent::PERMISSION_MAP[parent::IS_ROLE_ADMIN],
        self::IS_ROLE_MODERATOR     => parent::PERMISSION_MAP[parent::IS_ROLE_MODERATOR],
        self::IS_SINHO_BOOK_ADMIN   => self::PERMISSION_BOOKLIST,
        self::IS_SINHO_VERIFY_WORKLOAD   => self::PERMISSION_VERIFY_WORKLOAD,
        self::IS_SINHO_FILL_WORKLOAD     => self::PERMISSION_FILL_WORKLOAD,
        self::IS_SINHO_CHECK_WORKLOAD    => self::PERMISSION_CHECK_WORKLOAD,

    );

    public $user_id;
    public $user_info;

    public $per_page = 20; // 每页显示多少条

    public function __construct($process_setup=true)
    {
        parent::__construct(false);

        if ($_GET['app'] != 'admin')
        {
            return false;
        }

        View::import_clean();

        if (defined('SYSTEM_LANG'))
        {
            View::import_js(base_url() . '/language/' . SYSTEM_LANG . '.js');
        }

        if (HTTP::is_browser('ie', 8)) {
            View::import_js('js/jquery.js');
        }  else {
            View::import_js('js/jquery.2.js');
        }

        View::import_js(array(
            'admin/js/aws_admin.js',
            'admin/js/aws_admin_template.js',
            'js/jquery.form.js',
            'js/framework.js',
            'admin/js/global.js',
            'js/global.js',
            'js/icb_template.js',
            'js/app.js',
        ));

        View::import_css(array(
            'admin/css/common.css'
        ));

        if (in_array($_GET['act'], array(
            'login',
            'login_process',
        )))
        {
            return true;
        }

        $hasSinhoPermission = false;
        foreach ((array)$this->user_info['permission'] as $_key=>$_value) {
            if (strpos($_key, 'sinho_') ===0) {
                $hasSinhoPermission = true;
                break;
            }
        }

        $this->model('admin')->set_admin_login($this->user_info['uid']);
        $admin_info = json_decode(Application::crypt()->decode(Application::session()->admin_login), true);

        if ($admin_info['uid'])
        {
            if ($admin_info['uid'] != $this->user_id OR $admin_info['UA'] != $_SERVER['HTTP_USER_AGENT'])
            {
                // 将旧登录信息失效
                $this->model('account')->logout();

                if ($_POST['_post_type'] == 'ajax') {
                    H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('会话超时, 请重新登录')));
                } else {
                    H::redirect_msg(Application::lang()->_t('会话超时, 请重新登录'), '/account/login/url-' . base64_current_path());
                }
            }
        }
        else
        {
            if ($_POST['_post_type'] == 'ajax') {
                H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('会话超时, 请重新登录')));

            } else {

                // 将旧信息失效
                $this->model('account')->logout();
                HTTP::redirect('/account/login/url-' . base64_current_path());
            }
        }


        //View::assign('menu_list', $this->model('admin')->fetch_menu_list(null, 'sinho_admin_menu'));

        $this->setup();

    }

    /**
     * 根据菜单中的权限， 过滤掉无权限访问的菜单
     */
    public function filterAdminMenu ($adminMenuList)
    {
        $newAdminMenu = array();
        foreach ($adminMenuList as $_key => $_menuInfo) {
            if ($_menuInfo['children']) {
                $_children = array();
                foreach ($_menuInfo['children'] as $_key2=>$_menuInfo2) {
                    if (!$_menuInfo2['permission'] || $this->user_info['permission'][$_menuInfo2['permission']])  {

                        $_children[] = $_menuInfo2;
                    }
                }

                if ($_children) {
                    $_menuInfo['children'] = $_children;
                    $newAdminMenu[$_key] = $_menuInfo;
                }

            } else if ($_menuInfo['permission'] && !$this->user_info['permission'][$_menuInfo['permission']]) {
            } else {
                $newAdminMenu[$_key] = $_menuInfo;
            }
        }

        return $newAdminMenu;
    }
}



/* EOF */
