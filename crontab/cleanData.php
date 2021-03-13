<?php

/**
 *
 */
class CleanData
{
    /**
     * http请求失败
     */
    const ERROR_HTTP_REQUEST   = 1;
    /**
     * 解析分类错误
     */
    const ERROR_PARSE_CATEGORY = 2;
    /**
     * 解析内容错误
     */
    const ERROR_PARSE_CONTENT  = 4;

    /**
     * 待解析的http内容
     */
    private $_content = '';

    /**
     * 全局配置信息
     */
    protected $configInfo = array();

    protected $db = null;
    protected $model = null;

    /**
     * 主分类信息列表
     */
    protected $categoryList = [];

    /**
     * 调试信息
     */
    protected $debugInfos = [];

    /**
     * 是否有错误发生。 如有错误， 需要将错误信息发送到邮件
     */
    protected $hasError = false;

    public function getDebugInfo ()
    {
        return $this->debugInfos;
    }

    public function hasError ()
    {
        return $this->hasError;
    }

    public function loadDb ()
    {
        require_once(ROOT_PATH . 'system/init.php');

        loadClass('core_config');
        $this->db = loadClass('core_db');
    }

    public function __construct ($configInfo=array())
    {

        $this->loadDb();
        $this->model = Application::model();
    }

    public function getDb ()
    {
        return $this->db;
    }

    public function getModel ()
    {
        return $this->model;
    }

    /**
     * 清理附件垃圾。 没有和数据库中的数据条目绑定的文件， 移除
     */
    public function cleanAttach ($dir)
    {
        // 获取目录下的文件列表
        $fileList = core_filemanager::getDirContentByPage($dir, 1, 1000000);
        // 如果目录返回的文件为空格， 删除这个目录
        if ($fileList['total']==0) {
            //rename($dir, rtrim($dir) . '.remove');
            rmdir($dir);
        }
        // 逐一处理文件
        $dir = rtrim($dir, DS);
        foreach ($fileList['files'] as $_item) {
            if ($_item['type']=='dir') { // 如果是目录， 递归执行
                $this->cleanAttach($dir . DS . $_item['name']);
                continue;
            }

            if (strpos($_item['name'], '_')) { // 忽略缩略图文件
                continue;
            }

            // 在附件表中查找文件。 如果没找到， 将文件删除
            if ($this->getModel()->fetch_row('attach', 'file_location ="' . $_item['name'] .'"')) {
                continue;
            } else {
                echo $dir . DS . $_item['name'], "\r\n";
                $_filenameInfo = explode('.', $_item['name']);
                if (count($_filenameInfo)==2) { // 删除缩小的图片
                    @ unlink($_filenameInfo[0] . '_90x90.' . $_filenameInfo[1]);
                    @ unlink($_filenameInfo[0] . '_170x110.' . $_filenameInfo[1]);
                }
                @ unlink($dir . DS . $_item['name']);
            }
        }

    }

}
// 设置运行时间不限制
@set_time_limit(0);
$startTime = microtime(true);
defined('WEB_ROOT_DIR') OR define('WEB_ROOT_DIR', realpath(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'www' ). DIRECTORY_SEPARATOR );

// 系统根路径
defined('ROOT_PATH') OR define('ROOT_PATH', realpath(dirname(__DIR__)) . DIRECTORY_SEPARATOR);
// 配置文件路径
define('CONF_PATH', ROOT_PATH . 'config' . DIRECTORY_SEPARATOR);

//sleep(rand(1,1000)); // 随机中断
$cleanDataModel = new CleanData();
$dirList = core_filemanager::getDirContentByPage(WEB_ROOT_DIR . 'uploads' . DS, 1, 1000000, 'dir');

$ignoreDirs = array('nav_menu');

foreach ($dirList['files'] as $_dirName) {
    if (in_array($_dirName, $ignoreDirs )) { //
        continue;
    }
    $cleanDataModel->cleanAttach(WEB_ROOT_DIR . 'uploads' . DS . $_dirName['name'] . DS);
}
//exit;

$endTime = microtime(true);

echo 'Running cost: ' , ($endTime - $startTime), "\r\n";
/* EOF */
