<?php
/*
+-------------------------------------------+
|   iCodeBang CMS [#RELEASE_VERSION#]       |
|   by iCodeBang.com Team                   |
|   © iCodeBang.com. All Rights Reserved    |
|   ------------------------------------    |
|   Support: icodebang@126.com              |
|   WebSite: http://www.icodebang.com       |
+-------------------------------------------+
*/

defined('iCodeBang_Com') OR die('Access denied!');

class main extends BaseController
{
    public function get_access_rule()
    {
        $rule_action['rule_type'] = 'black';
        $rule_action['actions'] = array();

        return $rule_action;
    }

    /**
     * 百度url提交
     */
    public function baidu_article_txt_action()
    {
        $_GET['pageId'] = intval($_GET['pageId']);
        // 查看是否有缓存， 有缓存直接输出缓存
        $cache_key = str_replace(array('.',':'), '_',$_SERVER['HTTP_HOST']) . '_' .MODULE . '_' .CONTROLLER . '_' . ACTION . '_pageId_' . $_GET['pageId'];
        if (empty($_GET['doGenerateCache']) && ($pageContent = Application::cache()->get($cache_key) )) {
            echo $pageContent;
            return;
        }

        $pageContent = '';
        // 没有缓存， 获取分类，文章，教程 页面链接
        $categoryList = $this->model()->fetch_all('category');
        if (!$_GET['pageId'] || $_GET['pageId']<2) {
            foreach ($categoryList as $_itemInfo) {
                $pageContent .= base_url(true) . '/article/category-' . $_itemInfo['url_token'] . '.html' . "\r\n";
            }
        }

        //fetch_page($table, $where = null, $order = null, $page = null, $limit = 10, $rows_cache = true, $column='*', $distinct=false)
        //
        $limit = $_GET['pageId'] > 5 ? 48000 : 20000;
        $articleList = $this->model()->fetch_page('article', null, null, intval($_GET['pageId']), $limit, false, 'id');
        foreach ($articleList as $_itemInfo) {
            $pageContent .= base_url(true) . '/article/' . $_itemInfo['id'] . ".html\r\n";
        }
        $courseList = $this->model()->fetch_page('course_content_table', 'article_id > 0 ', null, intval($_GET['pageId']), $limit, false, '*');
        $courseIds = array_column($courseList, 'article_id');
        if ($courseIds) {
            $categoryIds = array_column($categoryList, 'id');
            $categoryList = array_combine($categoryIds, $categoryList);
            $courseArticleList = $this->model()->fetch_page('course', 'id IN (' . join(',', $courseIds) . ')', null, 1, $limit, false, '*');
            $courseIds = array_column($courseArticleList, 'id');
            $courseArticleList = array_combine($courseIds, $courseArticleList);

            try {
                foreach ($courseList as $_itemInfo) {
                    $pageContent .= base_url(true) . '/course/' . $categoryList[$_itemInfo['category_id']]['url_token'] . '/id-'
                    . ($courseArticleList[$_itemInfo['article_id']]['url_token'] ==''?$_itemInfo['article_id'] : urlencode($courseArticleList[$_itemInfo['article_id']]['url_token']) )
                    . '__table_id-' . $_itemInfo['table_id']
                    . ".html\r\n";
                }
            } catch (Exception $e) {
            }
        }
        echo $pageContent;
        if($pageContent) {
            // 缓存100天
            Application::cache()->set($cache_key, $pageContent, 60*60*24*100);
        } else {
            foreach ($categoryList as $_itemInfo) {
                echo base_url(true) . '/article/category-' . $_itemInfo['url_token'] . '.html' . "\r\n";
            }
            echo base_url(true);
        }
    }

    public function index_action ()
    {
        if(''!=$_GET['id'] && method_exists($this, $_GET['id'] . '_action')) {
            $method = $_GET['id'] . '_action';
            $this->$method();
        } else {
            return;
        }
    }

    public function index_square_action()
    {
        echo 'square';
    }
}
