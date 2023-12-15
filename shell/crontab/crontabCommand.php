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

class CrontabCommand
{
    /**
     * 全局配置信息
     */
    protected $configInfo = array();

    protected $db = null;
    protected $model = null;

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

        loadClass('core_config');
        $this->db = loadClass('core_db');
    }

    public function __construct()
    {
        Application::$settings = Application::model('setting')->get_settings();
        $this->loadDb();
        $this->model = Application::model();
    }

    /**
     * 缓存查询到的数据， 以供其他程序使用
     */
    public function doCache_action ()
    {
        $siteConfig = Application::config()->get('www.icodebang.com.inc');
        $showCategoryList = $siteConfig->show_category_list;
        //var_dump($showCategoryList,get_setting('cache_level_low'));

        //$cache_key = 'ModelArticle_getListInDiffCategory_' . base64_encode(serialize($showCategoryList)) .'_idDESC_14';
        $cache_key = generateCacheKey('Article', 'getListInDiffCategory', $showCategoryList, 'id DESC', 14);
        $itemList = $this->model->model('article')->getListInDiffCategory($showCategoryList, 'id DESC', 14);
        //var_dump($cache_key, get_setting('cache_level_low') * 2);

        Application::cache()->set($cache_key, $itemList, get_setting('cache_level_low') );
    }

}

// 执行更新文章来源网址和作者内容
$commandString = isset($argv[1]) ? $argv[1] : '';
$CrontabCommand = new CrontabCommand ();
$methodString   = $commandString . '_action';
if (method_exists($CrontabCommand, $methodString)) {
    echo 'Runing ' . $methodString . "\r\n";
    $CrontabCommand->$methodString();
}

$endTime = microtime(true);

echo 'Running cost: ' , ($endTime - $startTime), "\r\n";
exit(0);
/* EOF */
