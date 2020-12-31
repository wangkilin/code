<?php
defined('iCodeBang_Com') OR die('Access denied!');

/**
 * 前台控制器
 */
class Controller
{
    public $user_id;
    public $user_info;

    static public $crumb = array();

    public function __construct($process_setup = true)
    {
        //Application::config()->get('system')->debug AND defined('IN_AJAX') AND sleep(2);
        // 获取当前用户 User ID
        $this->user_id = Application::user()->get_info('uid');

        if ($this->user_info = $this->model('account')->getUserById($this->user_id, TRUE))
        {// 找到用户相关信息，根据用户相关信息， 获取对应的组信息
            $user_group = $this->model('account')->get_user_group($this->user_info['group_id'], $this->user_info['reputation_group']);

            if ($this->user_info['default_timezone'])
            {
                date_default_timezone_set($this->user_info['default_timezone']);
            }

            $this->model('online')->online_active($this->user_id, $this->user_info['last_active']);
        }
        else if ($this->user_id)
        {// 用户存在cookie，但是没找到用户，应该是数据库做了删除用户操作
            $this->model('account')->logout();
        }
        else
        {// 没有用户信息， 获取游客组信息
            $user_group = $this->model('account')->get_user_group_by_id(99);

            if ($_GET['fromuid'])
            {
                HTTP::set_cookie('fromuid', $_GET['fromuid']);
            }
        }

        $this->user_info['group_name'] = $user_group['group_name'];
        $this->user_info['permission'] = $user_group['permission'];

        Application::session()->permission = $this->user_info['permission'];

        if ($this->user_info['forbidden'] == 1)
        {
            $this->model('account')->logout();

            H::redirect_msg(Application::lang()->_t('抱歉, 你的账号已经被禁止登录'), '/');
        }
        else
        {
            View::assign('user_id', $this->user_id);
            View::assign('user_info', $this->user_info);
        }

        if ($this->user_id and ! $this->user_info['permission']['human_valid'])
        {
            unset(Application::session()->human_valid);
        }
        else if ($this->user_info['permission']['human_valid'] and ! is_array(Application::session()->human_valid))
        {
            Application::session()->human_valid = array();
        }

        // 引入系统 CSS 文件
        View::import_css(array(
            'css/common.css',
            'css/link.css',
            'js/fancybox/style.css',
            //'js/editor/ckeditor.4.11/plugins/codesnippet/lib/highlight/styles/rainbow.css',
            "//cdnjs.cloudflare.com/ajax/libs/highlight.js/10.4.1/styles/monokai-sublime.min.css",
        ));

        if (defined('SYSTEM_LANG'))
        {
            View::import_js(base_url() . '/language/' . SYSTEM_LANG . '.js');
        }

        if (HTTP::is_browser('ie', 8))
        {
            View::import_js(array(
                'js/jquery.js',
                'js/respond.js'
            ));
        }
        else
        {
            View::import_js('js/jquery.2.js');
        }

        // 引入系统 JS 文件
        View::import_js(array(
            'js/jquery.form.js',
            'js/framework.js',
            'js/aws.js',
            'js/aw_template.js',
            'js/global.js',
            'js/icb_template.js',
            'js/app.js',
        ));

        // 产生面包屑导航数据
        $this->crumb(get_setting('site_name'), base_url());

        // 载入插件
        if ($plugins = Application::plugins()->parse($_GET['app'], $_GET['c'], 'setup'))
        {
            foreach ($plugins as $plugin_file)
            {
                include $plugin_file;
            }
        }

        if (get_setting('site_close') == 'Y' AND $this->user_info['group_id'] != 1 AND !in_array($_GET['app'], array('admin', 'account', 'upgrade')))
        {
            $this->model('account')->logout();

            H::redirect_msg(get_setting('close_notice'), '/account/login/');
        }

        if ($_GET['ignore_ua_check'] == 'TRUE')
        {
            HTTP::set_cookie('_ignore_ua_check', 'TRUE', (time() + 3600 * 24 * 7));
        }

        // 执行控制器 Setup 动作
        if ($process_setup)
        {
            $this->setup();
        }
    }

    /**
     * 控制器 Setup 动作
     *
     * 每个继承于此类库的控制器均会调用此函数
     *
     * @access    public
     */
    public function setup() {}

    /**
     * 判断当前访问类型是否为 POST
     *
     * 调用 $_SERVER['REQUEST_METHOD']
     *
     * @access    public
     * @return    boolean
     */
    public function is_post()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * 调用系统 Model
     *
     * 于控制器中使用 $this->model('class')->function() 进行调用
     *
     * @access    public
     * @param    string
     * @return    object
     */
    public function model($model = null, $options = null)
    {
        return Application::model($model, $options);
    }

    /**
     * 产生面包屑导航数据
     *
     * 产生面包屑导航数据并生成浏览器标题供前端使用
     *
     * @access    public
     * @param    string
     * @param    string
     */
    public function crumb($name, $url = null)
    {
        if (is_array($name)) {
            foreach ($name as $key => $value) {
                $this->crumb($key, $value);
            }

            return $this;
        }

        $name = htmlspecialchars_decode($name);
        $crumb_template = $this->crumb;

        if (strlen($url) > 1 and substr($url, 0, 1) == '/') {
            $url = base_url() . substr($url, 1);
        }

        $this->crumb[] = array(
            'name' => $name,
            'url' => $url
        );

        $crumb_template['last'] = array(
            'name' => $name,
            'url'  => $url
        );

        View::assign('crumb', $crumb_template);

        $title = '';
        foreach ($this->crumb as $crumb) {
            $title = $crumb['name'] . ' - ' . $title;
        }

        View::assign('page_title', htmlspecialchars(rtrim($title, ' - ')));

        return $this;
    }

    public function publish_approval_valid($content = null)
    {
        if ($this->user_info['permission']['is_administortar'] OR $this->user_info['permission']['is_moderator'])
        {
            return false;
        }

        if ($default_timezone = get_setting('default_timezone'))
        {
            date_default_timezone_set($default_timezone);
        }

        if ($this->user_info['permission']['publish_approval'] == 1)
        {
            if (!$this->user_info['permission']['publish_approval_time']['start'] AND !$this->user_info['permission']['publish_approval_time']['end'])
            {
                if ($this->user_info['default_timezone'])
                {
                    date_default_timezone_set($this->user_info['default_timezone']);
                }

                return true;
            }

            if ($this->user_info['permission']['publish_approval_time']['start'] < $this->user_info['permission']['publish_approval_time']['end'])
            {
                if (intval(date('H')) >= $this->user_info['permission']['publish_approval_time']['start'] AND intval(date('H')) < $this->user_info['permission']['publish_approval_time']['end'])
                {
                    if ($this->user_info['default_timezone'])
                    {
                        date_default_timezone_set($this->user_info['default_timezone']);
                    }

                    return true;
                }
            }

            if ($this->user_info['permission']['publish_approval_time']['start'] > $this->user_info['permission']['publish_approval_time']['end'])
            {
                if (intval(date('H')) >= $this->user_info['permission']['publish_approval_time']['start'] OR intval(date('H')) < $this->user_info['permission']['publish_approval_time']['end'])
                {
                    if ($this->user_info['default_timezone'])
                    {
                        date_default_timezone_set($this->user_info['default_timezone']);
                    }

                    return true;
                }
            }

            if ($this->user_info['permission']['publish_approval_time']['start'] == $this->user_info['permission']['publish_approval_time']['end'])
            {
                if (intval(date('H')) == $this->user_info['permission']['publish_approval_time']['start'])
                {
                    if ($this->user_info['default_timezone'])
                    {
                        date_default_timezone_set($this->user_info['default_timezone']);
                    }
                    return true;
                }
            }
        }

        if ($this->user_info['default_timezone'])
        {
            date_default_timezone_set($this->user_info['default_timezone']);
        }

        if ($content AND H::sensitive_word_exists($content))
        {
            return true;
        }

        return false;
    }

    public function assign ($name, $value)
    {
        View::assign($name, $value);

        return $this;
    }

    public function display ($tpl)
    {
        View::output($tpl);
    }

    public function fetch ($tpl)
    {
        return View::output($tpl, false);
    }
}



class AdminController extends BaseController
{

    public $per_page = 20; // 每页显示多少条

    public function __construct()
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

        if (HTTP::is_browser('ie', 8))
        {
            View::import_js('js/jquery.js');
        }
        else
        {
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

        $admin_info = json_decode(Application::crypt()->decode(Application::session()->admin_login), true);

        if ($admin_info['uid'])
        {
            if ($admin_info['uid'] != $this->user_id OR $admin_info['UA'] != $_SERVER['HTTP_USER_AGENT'] OR !Application::session()->permission['is_administortar'] AND !Application::session()->permission['is_moderator'])
            {
                unset(Application::session()->admin_login);

                if ($_POST['_post_type'] == 'ajax')
                {
                    H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('会话超时, 请重新登录')));
                }
                else
                {
                    H::redirect_msg(Application::lang()->_t('会话超时, 请重新登录'), '/admin/login/url-' . base64_current_path());
                }
            }
        }
        else
        {
            if ($_POST['_post_type'] == 'ajax')
            {
                H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('会话超时, 请重新登录')));
            }
            else
            {
                HTTP::redirect('/admin/login/url-' . base64_current_path());
            }
        }


        View::assign('menu_list', $this->model('admin')->fetch_menu_list());

        $this->setup();

    }

}


/* EOF */
