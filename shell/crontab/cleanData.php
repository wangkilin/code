<?php


// 设置运行时间不限制
@set_time_limit(0);
$startTime = microtime(true);
defined('WEB_ROOT_DIR') OR define('WEB_ROOT_DIR', realpath(dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'www' ). DIRECTORY_SEPARATOR );

// 系统根路径
defined('ROOT_PATH') OR define('ROOT_PATH', realpath(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR);

// 配置文件路径
define('CONF_PATH', ROOT_PATH . 'config' . DIRECTORY_SEPARATOR);

require_once(ROOT_PATH . 'system/init.php');
error_reporting(E_ALL);
//sleep(rand(1,1000)); // 随机中断
$cleanDataModel = new CleanData();
// 清除没有绑定内容的附件；
$cleanDataModel->cleanUnbindAttach();

$dirList = core_filemanager::getDirContentByPage(WEB_ROOT_DIR . 'uploads' . DS . 'article' . DS, 1, 1000000, 'dir');

$ignoreDirs = array('nav_menu');

foreach ($dirList['files'] as $_dirName) {
    if (in_array($_dirName, $ignoreDirs )) { //
        continue;
    }
    $cleanDataModel->cleanAttach(WEB_ROOT_DIR . 'uploads' . DS . 'article'. DS . $_dirName['name'] . DS);
}
//exit;

$endTime = microtime(true);

echo 'Running cost: ' , ($endTime - $startTime), "\r\n";
/* EOF */
