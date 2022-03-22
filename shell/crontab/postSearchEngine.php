<?php

/**
 *
 */
class PostSearchEngine
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

    const API_BAIDU_POST_URL = 'http://data.zz.baidu.com/urls?site=%s&token=lRre6F9JOmuYOdU4';

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
     * 提交内容到bing
     *
     *
     *       POST /webmaster/api.svc/json/SubmitContent?apikey=EEDECC1EA4AE341CC57365E075EBC8B6 HTTP/1.1 ​
     *       Content-Type: application/json; charset=utf-8
     *       Host: ssl.bing.com​
     *
     *       {
     *           "siteUrl":"http://yoursite.com",​
     *           "url": "http://yoursite.com/url1",
     *           "httpMessage": "SFRUUC8xLjEgMjAwIE9LCkRhdGU6IFN1biwgMTAgT2N0IDIwMTcgMjM6MjY6MDcgR01UCkFjY2VwdC1SYW5nZXM6IGJ5dGVzCkNvbnRlbnQtTGVuZ3RoOiAxMwpDb25uZWN0aW9uOiBjbG9zZQpDb250ZW50LVR5cGU6IHRleHQvaHRtbAoKSGVsbG8gd29ybGQh",
     *           "structuredData": "",
     *           "dynamicServing":"0",
     *       }
     *
     *       JSON response sample:
     *
     *       HTTP/1.1 200 OK
     *       Content-Length: 10
     *       Content-Type: application/json; charset=utf-8
     *
     *       {
     *          "d":null
     *      }
     */
    public function postToBing ()
    {
        $configInfo = loadClass('core_config')->get('searchEngine.inc');
        $startArticleId = $this->model->fetch_one('key_value', 'value', "varname='search_engine_article_bing_id'");
        var_dump($startArticleId);
        if (! $startArticleId) {
            return;
        }
        $articleList = $this->model->fetch_all('article', 'id >=' . intval($startArticleId), 'id ASC', 100);
        var_dump(count($articleList));
        if (! is_array($articleList) ) {
            return;
        }
        $header = array(
            'Content-Type' => 'application/json; charset=utf-8',
           // 'Host'         => 'ssl.bing.com​',
        );
        $urlList = array ();
        // 一次将4个网站的内容提交到搜索引擎
        $domainList = array(
            'www.icodebang.com',
            'www.icodebang.cn',
            'www.devboy.cn',
            'www.popnic.cn',
            'www.kinful.com',
            'www.scanonly.com',
            'www.3dwindy.com',
            'www.ekotlin.com',
            'www.ukotlin.com',
            'www.teacup.com.cn',
        );
        foreach ($domainList as $_domain) {
            $urlList[$_domain] = array();
        }
        $tmpArticleId = $startArticleId;
        foreach ($articleList as $_itemInfo) {

            foreach ($domainList as $_domain) {
                $urlList[$_domain] [] = "http://".$_domain."/article/" . ($_itemInfo['url_token']==='' ? $_itemInfo['id'] : urlencode($_itemInfo['url_token']) ) . '.html';
            }

            $tmpArticleId = $_itemInfo['id'];
        }

        try {
            foreach ($domainList as $_domain) {
                $params = array(
                    "siteUrl" => "http://" . $_domain,
                    "urlList" => $urlList[$_domain],
                );
                $response = HTTP::request(
                            'http://ssl.bing.com/webmaster/api.svc/json/SubmitUrlbatch?apikey=' . $configInfo->bing['apikey'],
                            'post',
                            json_encode($params),
                            15,
                            $header
                        );

                var_dump($response);
            }

            $this->model->update('key_value', array('value'=>$tmpArticleId+1), 'varname="search_engine_article_bing_id"');
        } catch (Exception $e) {
            var_dump($e);
        }
        exit;

        foreach ($articleList as $_itemInfo) {
            $data = array(
                           "siteUrl"            => "http://www.icodebang.com",
                           "url"                => "http://www.icodebang.com/article/" . $_itemInfo['id'] . '.html',
                           "httpMessage"        => $_itemInfo['message'],
                           "structuredData"     => "",
                           "dynamicServing"     => "0",
            );
            $data['httpMessage'] = base64_encode($data['httpMessage']);
            try {
            $response = HTTP::request(
                          'http://ssl.bing.com/webmaster/api.svc/json/SubmitContent?apikey=' . $configInfo->bing['apikey'],
                          'post',
                          json_encode($data),
                          15,
                          $header
                        );

            var_dump($response);

            $this->model->update('key_value', array('value'=>$_itemInfo['id']+1), 'varname="search_engine_article_bing_id"');
            } catch (Exception $e) {
                var_dump($e);
                exit;
            }
        }

    }

    public function postToBaidu ()
    {
        $hasError = false;
        $urls = array();
        //$api = 'http://data.zz.baidu.com/urls?site=www.icodebang.com&token=lRre6F9JOmuYOdU4';
        $domainList = array(
            'www.icodebang.com',
            'www.icodebang.cn',
            'www.devboy.cn',
            'www.kinful.com',
            'www.scanonly.com',
            'www.3dwindy.com',
            'www.ekotlin.com',
            'www.ukotlin.com',
            'www.teacup.com.cn',
        );
        $startPageId = $this->model->fetch_one('key_value', 'value', "varname='search_engine_baidu_page_id'");

        $urlList = array();
        $categoryList = $this->model->fetch_all('category');
        $articleList = $this->model->fetch_page('article', null, null, $startPageId, 900, false, 'id');

        $courseArticleList = array();
        $courseList = $this->model->fetch_page('course_content_table', 'article_id > 0 ', null, $startPageId, 100, false, '*');
        $courseIds = array_column($courseList, 'article_id');
        if ($courseIds) {
            $categoryIds = array_column($categoryList, 'id');
            $categoryList = array_combine($categoryIds, $categoryList);
            $courseArticleList = $this->model->fetch_page('course', 'id IN (' . join(',', $courseIds) . ')', null, 1, 1000, false, '*');
            $courseIds = array_column($courseArticleList, 'id');
            $courseArticleList = array_combine($courseIds, $courseArticleList);

        }


        foreach ($domainList as $_domain) {
            $api = sprintf(self::API_BAIDU_POST_URL, $_domain);
            $urlList = array();
            foreach ($articleList as $_itemInfo) {
                $urlList[] = 'http://' . $_domain . '/article/' . $_itemInfo['id'] . ".html";
            }
            foreach ($courseList as $_itemInfo) {
                $urlList[] = 'http://' . $_domain . '/course/' . $categoryList[$_itemInfo['category_id']]['url_token'] . '/id-'
                . ($courseArticleList[$_itemInfo['article_id']]['url_token'] ==''?$_itemInfo['article_id'] : urlencode($courseArticleList[$_itemInfo['article_id']]['url_token']) )
                . '__table_id-' . $_itemInfo['table_id']
                . ".html\r\n";
            }

            var_dump($api, count($urlList));


                $ch = curl_init();
                $options =  array(
                    CURLOPT_URL => $api,
                    CURLOPT_POST => true,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POSTFIELDS => implode("\n", $urlList),
                    CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
                );
                curl_setopt_array($ch, $options);
                $result = curl_exec($ch);
                echo $result;
        }

        if (! $hasError) {

            $this->model->update('key_value', array('value'=>$startPageId+1), 'varname="search_engine_baidu_page_id"');
        }
    }

}
// 设置运行时间不限制
@set_time_limit(0);
$startTime = microtime(true);
defined('WEB_ROOT_DIR') OR define('WEB_ROOT_DIR', realpath(dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'www' ). DIRECTORY_SEPARATOR );

// 系统根路径
defined('ROOT_PATH') OR define('ROOT_PATH', realpath(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR);
// 配置文件路径
define('CONF_PATH', ROOT_PATH . 'config' . DIRECTORY_SEPARATOR);


//sleep(rand(1,1000)); // 随机中断
$postSearchEngineModel = new PostSearchEngine();


// 执行更新文章来源网址和作者内容
if (isset($argv[1])) {
    switch ($argv[1]) {
        case  'bing':
            $postSearchEngineModel->postToBing();
            break;

        case 'baidu':
            $postSearchEngineModel->postToBaidu();
            break;

        default:
            break;
    }
} else {
}
//exit;

$endTime = microtime(true);

echo 'Running cost: ' , ($endTime - $startTime), "\r\n";

/* EOF */
