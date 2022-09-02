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
    const SUBJECT_LIST = array(
        1 => array('name'=>"语文", 'keyword'=>array('语文','7语','8语','9语','七语','八语','九语','语',)),
        2 => array('name'=>"数学", 'keyword'=>array('数学','7数','8数','9数','七数','八数','九数','数',)),
        3 => array('name'=>"英语", 'keyword'=>array('英语','7英','8英','9英','七英','八英','九英','英',)),
        4 => array('name'=>"物理", 'keyword'=>array('物理','7物','8物','9物','七物','八物','九物','物',)),
        5 => array('name'=>"化学", 'keyword'=>array('化学','7化','8化','9化','七化','八化','九化','化',)),
        6 => array('name'=>"地理", 'keyword'=>array('地理','7地','8地','9地','七地','八地','九地','地',)),
        7 => array('name'=>"政治", 'keyword'=>array('政治','7政','8政','9政','七政','八政','九政','政','7道','8道','9道','七道','八道','九道','道法')),
        8 => array('name'=>"历史", 'keyword'=>array('历史','7历','8历','9历','七历','八历','九历','历',)),
        9 => array('name'=>"生物", 'keyword'=>array('生物','7生','8生','9生','七生','八生','九生','生',)),
    );
    const SUBJECT_CATEGORIZE = array (
        array(1,3,6,7,8), array(2,4,5,9)

    );

    const PERMISSION_VERIFY_WORKLOAD    = 'sinho_verify_workload';
    const PERMISSION_FILL_WORKLOAD      = 'sinho_fill_workload';
    const PERMISSION_BOOKLIST           = 'sinho_modify_manuscript_param';
    const PERMISSION_CHECK_WORKLOAD     = 'sinho_check_workload';
    const PERMISSION_ADMINISTRATION     = 'sinho_administration';
    const PERMISSION_SUBJECT            = 'sinho_subject';
    const PERMISSION_TEAM_LEADER        = 'sinho_permission_team_leader';
    const PERMISSION_PAGE_ADMIN         = 'sinho_permission_page_admin';  // 页面内容管理权限

    const SINHO_PERMISSION_LIST = array(
        self::PERMISSION_FILL_WORKLOAD,
        self::PERMISSION_BOOKLIST,
        self::PERMISSION_VERIFY_WORKLOAD,
        self::PERMISSION_CHECK_WORKLOAD,
        self::PERMISSION_ADMINISTRATION,
        self::PERMISSION_SUBJECT,
        self::PERMISSION_TEAM_LEADER,
        self::PERMISSION_PAGE_ADMIN,
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
    const IS_SINHO_TEAM_LEADER     = 0x80; // 是否是组长
    const IS_SINHO_PAGE_ADMIN      = 0x100; // 是否是页面内容管理人员

    const PERMISSION_MAP = array (
        self::IS_ROLE_ADMIN              => parent::PERMISSION_MAP[parent::IS_ROLE_ADMIN],
        self::IS_ROLE_MODERATOR          => parent::PERMISSION_MAP[parent::IS_ROLE_MODERATOR],
        self::IS_SINHO_BOOK_ADMIN        => self::PERMISSION_BOOKLIST,
        self::IS_SINHO_VERIFY_WORKLOAD   => self::PERMISSION_VERIFY_WORKLOAD,
        self::IS_SINHO_FILL_WORKLOAD     => self::PERMISSION_FILL_WORKLOAD,
        self::IS_SINHO_CHECK_WORKLOAD    => self::PERMISSION_CHECK_WORKLOAD,
        self::IS_SINHO_ADMIN             => self::PERMISSION_ADMINISTRATION,
        self::IS_SINHO_TEAM_LEADER       => self::PERMISSION_TEAM_LEADER,
        self::IS_SINHO_PAGE_ADMIN        => self::PERMISSION_PAGE_ADMIN,
    );

    public $user_id;
    public $user_info;

    public $per_page = 20; // 每页显示多少条

    public function __construct($process_setup=true)
    {
        parent::__construct(false);

        if ($this->user_info['uid']) {
            $userAttributes = $this->model()->fetch_all('users_attribute', 'uid = ' . $this->user_info['uid']);
            foreach($userAttributes as $_tmpInfo) {
                if (strpos($_tmpInfo['attr_key'], 'sinho_permission') === 0) {
                    $this->user_info['permission'][$_tmpInfo['attr_key']] = $_tmpInfo['attr_value'];
                }
            }
        }

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
                    empty($_menuInfo2['permission']) OR settype($_menuInfo2['permission'], 'array');
                    $hasPermission = false;

                    foreach ($_menuInfo2['permission'] as $_tmpPermissionName) {
                        if ($this->user_info['permission'][$_tmpPermissionName]) {
                            $hasPermission = true;
                            break;
                        }
                    }
                    if (!$_menuInfo2['permission'] || $hasPermission)  {

                        $_children[] = $_menuInfo2;
                    }
                }

                if ($_children) {
                    $_menuInfo['children'] = $_children;
                    $newAdminMenu[$_key] = $_menuInfo;
                }

                continue;

            }


            empty($_menuInfo['permission']) OR settype($_menuInfo['permission'], 'array');
            $hasPermission = false;
            foreach ($_menuInfo['permission'] as $_tmpPermissionName) {
                if ($this->user_info['permission'][$_tmpPermissionName]) {
                    $hasPermission = true;
                    break;
                }
            }
            if ($_menuInfo['permission'] && !$hasPermission) {
            } else {
                $newAdminMenu[$_key] = $_menuInfo;
            }
        }

        return $newAdminMenu;
    }
}



/* EOF */
