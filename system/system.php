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

if (! defined('INC_PATH')) {
    define('INC_PATH', dirname(__FILE__) . '/');
}

require_once INC_PATH . 'init.php';

if (defined('G_GZIP_COMPRESS') AND G_GZIP_COMPRESS === true 
    && @ini_get('zlib.output_compression') == false
    && extension_loaded('zlib')
    && isset($_SERVER['HTTP_ACCEPT_ENCODING']) AND strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false
) {
    ob_start('ob_gzhandler');
}

require_once INC_PATH . 'Application.php';
require_once INC_PATH . 'Controller.php';
require_once INC_PATH . 'Model.php';
