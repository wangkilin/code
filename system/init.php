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

define('iCodeBang_Com', true);
define('LOWEST_PHP_VERSION', '5.3.0');
//define('SYSTEM_LANG', 'en_US');
// 检查php运行版本
if (substr(PHP_VERSION, -4) == 'hhvm') { // HHVM版本不支持
    die('Error: iCodeBang does not support HHVM!');
} else if (version_compare(PHP_VERSION, LOWEST_PHP_VERSION, '<')) {
    die('Error: iCodeBang requires PHP version ' . LOWEST_PHP_VERSION . ' or newer');
}

define('START_TIME', microtime(true));
define('TIMESTAMP', time());

defined('DS') OR define('DS', DIRECTORY_SEPARATOR);

defined('ROOT_PATH') OR define('ROOT_PATH', dirname(dirname(__FILE__)) . DS);
defined('INC_PATH')  OR define('INC_PATH', dirname(__FILE__) . DS);
defined('CONF_PATH') OR define('CONF_PATH', ROOT_PATH . 'config' . DS);

if (function_exists('memory_get_usage')) {
    define('MEMORY_USAGE_START', memory_get_usage());
}


if (defined('SAE_TMP_PATH')) {
    define('IN_SAE', true);
}

@ini_set('display_errors', '0');

if (defined('IN_SAE')) {
    error_reporting(0);

    define('TEMP_PATH', rtrim(SAE_TMP_PATH, '/') . '/');

} else {
    if (version_compare(PHP_VERSION, '5.4', '>=')) {
        error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_WARNING & ~E_DEPRECATED);
    }else {
        error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);
    }

    define('TEMP_PATH', ROOT_PATH . 'tmp/');
}

if (function_exists('get_magic_quotes_gpc') && @get_magic_quotes_gpc()) { // GPC 进行反向处理
    if (! function_exists('stripslashes_gpc')) {
        /**
         * 去除参数转义符号
         *
         * @param mixed $value 待处理的参数
         *
         */
        function stripslashes_gpc(& $value)
        {
            $value = stripslashes($value);
        }
    }

    array_walk_recursive($_GET, 'stripslashes_gpc');
    array_walk_recursive($_POST, 'stripslashes_gpc');
    array_walk_recursive($_COOKIE, 'stripslashes_gpc');
    array_walk_recursive($_REQUEST, 'stripslashes_gpc');
}

if (file_exists(CONF_PATH . 'environment.inc.php')) {// 包含环境参数
    include_once(CONF_PATH . 'environment.inc.php');
}

require_once(ROOT_PATH . 'version.php');
require_once(INC_PATH . 'functions.inc.php');

array_walk_recursive($_GET, 'remove_invisible_characters');
array_walk_recursive($_POST, 'remove_invisible_characters');
array_walk_recursive($_COOKIE, 'remove_invisible_characters');
array_walk_recursive($_REQUEST, 'remove_invisible_characters');

if (@ini_get('register_globals'))
{
    if ($_REQUEST)
    {
        foreach ($_REQUEST AS $name => $value)
        {
            unset($$name);
        }
    }

    if ($_COOKIE)
    {
        foreach ($_COOKIE AS $name => $value)
        {
            unset($$name);
        }
    }
}


require_once(INC_PATH . 'functions.app.php');

if (file_exists(INC_PATH . 'config.inc.php')) {
    require_once(INC_PATH . 'config.inc.php');
}

loadClass('core_autoload');

date_default_timezone_set('Etc/GMT-8');

require_once INC_PATH . 'Application.php';
require_once INC_PATH . 'Controller.php';
require_once INC_PATH . 'Model.php';

if (isset($_SERVER['HTTP_HOST']) && file_exists(CONF_PATH . $_SERVER['HTTP_HOST'] .'.inc.php')) {// 包含网站独立的配置参数
    //include_once(CONF_PATH . $_SERVER['HTTP_HOST'] .'.inc.php');
    Application::config()->load_config($_SERVER['HTTP_HOST'] .'.inc');
}

defined('CACHE_PATH') OR define('CACHE_PATH', ROOT_PATH . 'cache'. DS);
defined('VIEW_PATH') OR define('VIEW_PATH', ROOT_PATH . 'views' . DS);
// 定义控制器文件存放的顶级目录
defined('CONTROLLER_DIR') OR define('CONTROLLER_DIR', 'app');

if (defined('G_GZIP_COMPRESS') AND G_GZIP_COMPRESS === true
    && @ini_get('zlib.output_compression') == false
    && extension_loaded('zlib')
    && isset($_SERVER['HTTP_ACCEPT_ENCODING']) AND strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false
) {
    ob_start('ob_gzhandler');
}
