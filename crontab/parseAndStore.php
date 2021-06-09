<?php

if (count($argv)<2) {
    echo "Usage: php -f parseAndStore.php method=MethodName MoreParams \r\n";
    echo "     method is mandatory \r\n";
    exit;
}


set_time_limit(0);

$parseAndStore = new parseAndStore($argv);

class parseAndStore
{
    /**
     * 命令行传递的参数
     */
    protected $argv = array();
    /**
     * 数据库操作实例
     */
    protected $db = null;

    /**
     * Model 类实例
     */
    protected $model = null;

    public function __construct ()
    {
        $this->loadArgv($GLOBALS['argv']);

        // 记录程序开始时间， 后续统一用这个时间做时间戳转换
        defined('APP_START_TIME') OR define('APP_START_TIME', time());
        $configInfo = require(dirname(__FILE__) . '/../config/parseConfig.php');
        require_once(ROOT_PATH . 'system/init.php');

        error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE ^ E_DEPRECATED);
        //error_reporting(E_ALL);

        loadClass('core_config');
        $this->db = loadClass('core_db');
        $this->model = Application::model();


        if (isset($this->argv['method']) && method_exists($this, $this->argv['method'])) {

            $this->{$this->argv['method']}();
        }
    }

    protected function loadArgv (array $argv)
    {
        foreach ($argv as $_item) {
            $_itemInfo = explode('=', $_item);
            if (isset($_itemInfo[1])) {
                $this->argv[$_itemInfo[0]] = $_itemInfo[1];
            }
        }
    }

    /**
     * 解析编程语言月度排行榜
     */
    public function parseTiobe ()
    {
        if (isset($this->argv['url'])) {
            $url = $this->argv['url'];
        } else {
            $url = 'https://www.tiobe.com/tiobe-index/';
        }
        $contentDomInfo = array(
            'id'    => 'top20',
        );
        try {
            $filecontent = file_get_contents($url);
            $linkHtmlParseModel = new Tools_Html_Parser();
            $contentDom = $linkHtmlParseModel->loadDomHTML($filecontent, 'utf-8')->getDom($contentDomInfo);
            $top20 = $linkHtmlParseModel->generateDomHtml($contentDom);
            $top20 = str_replace(
                        array('src="images', 'src="/images'),
                        array('src="https://www.tiobe.com/tiobe-index/images', 'src="https://www.tiobe.com/images'),
                        $top20
                    );
            $contentDomInfo = array('id' => 'otherPL');
            $contentDom = $linkHtmlParseModel->getDom($contentDomInfo);
            $morePl = $linkHtmlParseModel->generateDomHtml($contentDom);
            $morePl = str_replace(
                        array('src="images', 'src="/images'),
                        array('src="https://www.tiobe.com/tiobe-index/images', 'src="https://www.tiobe.com/images'),
                        $morePl
                    );

            $belongMonth = date('Ym');
            $hasRecord = Application::model()->fetch_row('tiobe_index', 'belong_month = ' . $belongMonth);
            if (! $hasRecord) {
                Application::model()->insert('tiobe_index', array('top20'=>$top20, 'more_pl'=>$morePl, 'belong_month'=>$belongMonth));
            }
        } catch (Exception $e) {}
    }
}
