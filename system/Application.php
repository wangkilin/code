<?php
class Application
{
    private static $config;
    /**
     * @var core_db $db DB instance
     */
    private static $db;
    private static $form;
    private static $upload;
    private static $image;
    private static $pagination;
    private static $cache;
    private static $lang;
    private static $session;
    private static $captcha;
    private static $mail;
    private static $user;
    private static $crypt;

    public static $session_type = 'file';

    private static $models = array();
    private static $plugins = array();

    public static $settings = array();
    public static $_debug = array();

    /**
     * 系统运行
     */
    public static function run()
    {
        self::init();

        loadClass('core_uri')->set_rewrite();

        // 传入应用目录, 返回控制器对象。
        $handle_controller = self::create_controller(loadClass('core_uri')->controller, loadClass('core_uri')->app_dir);

        $action_method = loadClass('core_uri')->action . '_action';

        // 判断 控制器存在否
        if (! is_object($handle_controller) ) {
            HTTP::error_404();
        }
        // 判断action方法存在否
        if (! method_exists($handle_controller, $action_method)) {
            // 判断默认方法存在否？
            if (! method_exists($handle_controller, $action_method = 'default_action')) {
                HTTP::error_404();
            }
        }

        if (method_exists($handle_controller, 'get_access_rule')) {
            $access_rule = $handle_controller->get_access_rule();
        }

        // 判断访问规则使用白名单还是黑名单, 默认使用黑名单
        if ($access_rule) {
            // 黑名单, 黑名单中的检查 'white' 白名单,白名单以外的检查 (默认是黑名单检查)
            if (isset($access_rule['rule_type']) AND $access_rule['rule_type'] == 'white') {
                if ((! $access_rule['actions']) OR (! in_array(loadClass('core_uri')->action, $access_rule['actions']) )) {
                    self::login();
                }
            } else if (isset($access_rule['actions']) AND in_array(loadClass('core_uri')->action, $access_rule['actions']))     {
            // 非白就是黑名单
                self::login();
            }

        } else {
            self::login();
        }

        define('MODULE',     basename(rtrim(loadClass('core_uri')->app_dir, '\\/')));
        define('CONTROLLER', loadClass('core_uri')->controller);
        define('ACTION',     loadClass('core_uri')->action);
        if (method_exists($handle_controller, 'beforeAction') ){
            $handle_controller->beforeAction();
        }

        // 不是登录页面， 也不是注册用户， 限制访问次数
        if (ACTION != 'login' && ACTION!='captcha' && ACTION!='logout' && ! $handle_controller->user_id && !preg_match('/spider|bot/i', $_SERVER['HTTP_USER_AGENT'])) {
            //var_dump(MODULE, CONTROLLER, ACTION);
            // 匿名访问， 限制ip访问次数
            $ip_address = fetch_ip();
            $cache_key = str_replace(array('.',':'), '_',$ip_address . $_SERVER['HTTP_HOST']) . 'website_allow_visit_page_number';
            if ($visitPageNumber = Application::cache()->get($cache_key) ) {
                $visitPageNumber++;
                if ($visitPageNumber > 200) {
                    HTTP::error_403();
                }
            } else {
                $visitPageNumber = 1;
            }
            Application::cache()->set($cache_key, $visitPageNumber, get_setting('cache_level_high'));

            // 匿名访问的网站攻击, 访问同一个页面次数
            $cache_key = md5($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REQUEST_URI']. $_SERVER['HTTP_HOST']) . '_website_allow_visit_page_number';
            if ($visitPageNumberUserUriAgent = Application::cache()->get($cache_key) ) {
                $visitPageNumberUserUriAgent++;
                if ($visitPageNumberUserUriAgent > 30) {
                    HTTP::error_403();
                }
            } else {
                $visitPageNumberUserUriAgent = 1;
            }
            Application::cache()->set($cache_key, $visitPageNumberUserUriAgent, get_setting('cache_level_low'));

            // 匿名访问的网站攻击. 同一个浏览器，每天访问次数
            $cache_key = md5($_SERVER['HTTP_USER_AGENT']) . '_website_allow_visit_page_number';
            if ($visitPageNumberUserAgent = Application::cache()->get($cache_key) ) {
                $visitPageNumberUserAgent++;
                if ($visitPageNumberUserAgent > 200) {
                    HTTP::error_403();
                }
            } else {
                $visitPageNumberUserAgent = 1;
            }
            Application::cache()->set($cache_key, $visitPageNumberUserAgent, get_setting('cache_level_low'));
        }

        // 执行
        if (empty($_GET['id']) AND method_exists($handle_controller, loadClass('core_uri')->action . '_square_action')) {
            $action_method = loadClass('core_uri')->action . '_square_action';
        }

        $handle_controller->$action_method();
    }

    /**
     * 系统初始化
     */
    private static function init()
    {
        // 记录程序开始时间， 后续统一用这个时间做时间戳转换
        defined('APP_START_TIME') OR define('APP_START_TIME', time());
        set_exception_handler(array('Application', 'exception_handle'));

        self::$config = loadClass('core_config');
        self::$db = loadClass('core_db');

        self::$plugins = loadClass('core_plugins');

        self::$settings = self::model('setting')->get_settings();

        if ((!defined('G_SESSION_SAVE') OR G_SESSION_SAVE == 'db') AND get_setting('db_version') > 20121123)
        {
            Zend_Session::setSaveHandler(new Zend_Session_SaveHandler_DbTable(array(
                'name'                     => get_table('sessions'),
                'primary'                => 'id',
                'modifiedColumn'        => 'modified',
                'dataColumn'            => 'data',
                'lifetimeColumn'        => 'lifetime',
                //'authIdentityColumn'    => 'uid'
            )));

            self::$session_type = 'db';
        }

        Zend_Session::setOptions(array(
            'name' => G_COOKIE_PREFIX . '_Session',
            'cookie_domain' => G_COOKIE_DOMAIN
        ));

        if (G_SESSION_SAVE == 'file' AND G_SESSION_SAVE_PATH)
        {
            Zend_Session::setOptions(array(
                'save_path' => G_SESSION_SAVE_PATH
            ));
        }

        Zend_Session::start();

        self::$session = new Zend_Session_Namespace(G_COOKIE_PREFIX . '_Anwsion');

        if ($default_timezone = get_setting('default_timezone'))
        {
            date_default_timezone_set($default_timezone);
        }

        if ($img_url = get_setting('img_url'))
        {
            define('G_STATIC_URL', $img_url);
        }
        else
        {
            define('G_STATIC_URL', base_url() . '/static');
        }

        if (self::config()->get('system')->debug)
        {
            if ($cornd_timer = self::cache()->getGroup('crond'))
            {
                foreach ($cornd_timer AS $cornd_tag)
                {
                    if ($cornd_runtime = self::cache()->get($cornd_tag))
                    {
                        Application::debug_log('crond', 0, 'Tag: ' . str_replace('crond_timer_', '', $cornd_tag) . ', Last run time: ' . date('Y-m-d H:i:s', $cornd_runtime));
                    }
                }
            }
        }
    }

    /**
     * 创建 Controller
     *
     * 根据传入的控制器名称与 app_dir 载入 Controller 相关文件
     *
     * @access    public
     * @param    string
     * @param    string
     * @return    object
     */
    public static function create_controller($controller, $app_dir)
    {
        if ($app_dir == '' OR trim($controller, '/') === '')
        {
            return false;
        }

        $class_file = $app_dir . $controller . '.php';

        $controller_class = str_replace('/', '_', $controller);

        if (! file_exists($class_file))
        {
            return false;
        }

        if (! class_exists($controller_class, false))
        {
            require_once $class_file;
        }

        // 解析路由查询参数
        loadClass('core_uri')->parse_args();

        if (class_exists($controller_class, false))
        {
            return new $controller_class();
        }

        return false;
    }

    /**
     * 异常处理
     *
     * 获取系统异常 & 处理
     *
     * @access    public
     * @param    object
     */
    public static function exception_handle($exception)
    {
        $exception_message = "Application error\n------\nMessage: " . $exception->getMessage() . "\nFile: " . $exception->getFile() . "\nLine: " . $exception->getLine() . "\n------\nBuild: " . G_VERSION . " " . G_VERSION_BUILD . "\nPHP Version: " . PHP_VERSION . "\nURI: " . $_SERVER['REQUEST_URI'] . "\nUser Agent: " . $_SERVER['HTTP_USER_AGENT'] . "\nAccept Language: " . $_SERVER['HTTP_ACCEPT_LANGUAGE'] . "\nIP Address: " . fetch_ip() . "\n------\n" . $exception->getTraceAsString();

        show_error($exception_message, $exception->getMessage());
    }

    /**
     * 格式化系统返回消息
     *
     * 格式化系统返回的消息 json 数据包给前端进行处理
     *
     * @access    public
     * @param    array
     * @param    integer
     * @return    array('rsm'=>'数据内容','errno'=>'错误代码', 'err'=>'错误信息')
     */
    public static function RSM($rsm, $errno = 0, $err = '')
    {
        return array(
            'rsm'   => $rsm,
            'errno' => (int)$errno,
            'err'   => $err,
        );
    }

    /**
     * 检查用户登录状态. 如果未登录， 显示登录页面
     *
     * 检查用户登录状态并带领用户进入相关操作
     */
    public static function login()
    {
        if (! Application::user()->get_info('uid')) {
            if ($_POST['_post_type'] == 'ajax') {
            // ajax请求， 回话已过期
                H::ajax_json_output(self::RSM(null, -1, Application::lang()->_t('会话超时, 请重新登录')));
            } else {
            // 中心登录
                HTTP::redirect('/account/login/url-' . base64_current_path());
            }
        }
    }

    /**
     * 工厂方法， 根据类名字，实例化核心库类
     * @param string $className
     * @param array  $params
     * @return object
     */
    protected static function factory ($className, $params=null)
    {
        if (! self::$$className) {
            self::$$className = & loadClass('core_' . $className, $params);
        } else if ( isset($params) && method_exists(self::$$className, 'setOptions')) {
            self::$$className->setOptions($params);
        }

        return self::$$className;
    }

    /**
     * 获取系统配置
     *
     * 调用 core/config.php
     *
     * @access    public
     * @return    core_config object
     */
    public static function config()
    {
        return self::factory('config');
    }

    /**
     * 获取用户信息类
     *
     * 调用 core/user.php
     *
     * @access    public
     * @return    core_user
     */
    public static function user()
    {
        return self::factory('user');
    }

    /**
     * 获取系统上传类
     *
     * 调用 core/upload.php
     *
     * @access    public
     * @return    core_lang
     */
    public static function upload()
    {
        return self::factory('upload');
    }

    /**
     * 获取系统图像处理类
     *
     * 调用 core/image.php
     *
     * @access    public
     * @return    core_lang
     */
    public static function image()
    {
        return self::factory('image');
    }

    /**
     * 获取系统语言处理类
     *
     * 调用 core/lang.php
     *
     * @access    public
     * @return    core_lang
     */
    public static function lang()
    {
        return self::factory('lang');
    }

    /**
     * 获取系统验证码处理类
     *
     * 调用 core/captcha.php
     *
     * @access    public
     * @return    core_captcha
     */
    public static function captcha()
    {
        return self::factory('captcha');
    }

    /**
     * 获取系统缓存处理类
     *
     * 调用 core/cache.php
     *
     * @access    public
     * @return    core_cache
     */
    public static function cache()
    {
        return self::factory('cache');
    }

    /**
     * 获取系统表单提交验证处理类
     *
     * 调用 core/form.php
     *
     * @access    public
     * @return    core_form
     */
    public static function form()
    {
        return self::factory('form');
    }

    /**
     * 获取系统邮件处理类
     *
     * 调用 core/mail.php
     *
     * @access    public
     * @return    core_mail
     */
    public static function mail()
    {
        return self::factory('mail');
    }

    /**
     * 获取系统插件处理类
     *
     * 调用 core/plugins.php
     *
     * @access    public
     * @return    core_plugins
     */
    public static function plugins()
    {
        return self::factory('plugins');
    }

    /**
     * 获取系统分页处理类
     *
     * 调用 core/pagination.php
     *
     * @access    public
     * @return    core_pagination
     */
    public static function pagination()
    {
        return self::factory('pagination');
    }

    /**
     * 调用系统 Session
     *
     * 此功能基于 Zend_Session 类库
     *
     * @access    public
     * @return    Zend_Session_Namespace object
     */
    public static function session()
    {
        return self::$session;
    }

    /**
     * 调用系统数据库
     *
     * 此功能基于 Zend_DB 类库
     *
     * @access    public
     * @param    string
     * @return    core_db object
     */
    public static function db($db_object_name = 'master')
    {
        if (!self::$db)
        {
            self::$db = loadClass('core_db');
        }

        return self::$db->setObject($db_object_name);
    }

    /**
     * 加密处理类
     *
     * 调用 core/crypt.php
     *
     * @access    public
     * @return    core_crypt
     */
    public static function crypt()
    {
        return self::factory('crypt');
    }

    /**
     * 记录系统 Debug 事件
     *
     * 打开 debug 功能后相应事件会在页脚输出
     *
     * @access    public
     * @param    string
     * @param    string
     * @param    string
     */
    public static function debug_log($type, $expend_time, $message)
    {
        self::$_debug[$type][] = array(
            'expend_time' => $expend_time,
            'log_time' => microtime(true),
            'message' => $message
        );
    }

    /**
     * 调用系统 Model
     *
     * 根据命名规则调用相应的 Model 并初始化类库保存于 self::$models 数组, 防止重复初始化
     *
     * @access   public
     * @param    string
     * @return   Model object
     */
    public static function model($model_class = null, $options=null)
    {
        if (! $model_class) {
            $model_class = 'Model';
        } else if (! strstr($model_class, 'Model')) {
            $model_class .= 'Model';
        }

        if (! isset(self::$models[$model_class])) {
            self::$models[$model_class] = new $model_class();
        }

        if ($options) {
            self::$models[$model_class]->setOptions($options);
        }

        return self::$models[$model_class];
    }
}
