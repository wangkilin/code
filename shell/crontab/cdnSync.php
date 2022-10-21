<?php
// 设置运行时间不限制
@set_time_limit(0);
$startTime = microtime(true);
defined('WEB_ROOT_DIR') OR define('WEB_ROOT_DIR', realpath(dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'www' ). DIRECTORY_SEPARATOR );

// 系统根路径
defined('ROOT_PATH') OR define('ROOT_PATH', realpath(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR);
// 配置文件路径
define('CONF_PATH', ROOT_PATH . 'config' . DIRECTORY_SEPARATOR);

require_once(ROOT_PATH . 'system/init.php');

//sleep(rand(1,1000)); // 随机中断
$cdnSyncModel = new CdnSync();

// 执行更新文章来源网址和作者内容
if (isset($argv[1]) && $argv[1]=='upload') {
    $cdnSyncModel->uploadFileToCdn();
} else if (isset($argv[1]) && $argv[1]=='load') {

    $localDirList = $cdnSyncModel->getModel()->fetch_all('cdn_local_root');
    // 将文件载入上传队列
    foreach ($localDirList as $_item) {
        $cdnSyncModel->loadFileIntoDb($_item['id'], WEB_ROOT_DIR . $_item['local_root_path'], WEB_ROOT_DIR);
    }
} else {

    $localDirList = $cdnSyncModel->getModel()->fetch_all('cdn_local_root');
    // 将文件载入上传队列
    foreach ($localDirList as $_item) {
        $cdnSyncModel->loadFileIntoDb($_item['id'], WEB_ROOT_DIR . $_item['local_root_path'], WEB_ROOT_DIR);
    }
    // 将队列中的文件上传
    $cdnSyncModel->uploadFileToCdn();
}
//exit;

$endTime = microtime(true);

echo 'Running cost: ' , ($endTime - $startTime), "\r\n";
/* EOF */
