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
        $categoryList = $this->model()->fetch_all('category');
        if (!$_GET['pageId'] || $_GET['pageId']<2) {
            foreach ($categoryList as $_itemInfo) {
                echo  base_url(true) . '/article/category-' . $_itemInfo['url_token'] . '.html', "\r\n";
            }
        }
        //fetch_page($table, $where = null, $order = null, $page = null, $limit = 10, $rows_cache = true, $column='*', $distinct=false)
        $articleList = $this->model()->fetch_page('article', null, null, intval($_GET['pageId']), 10000, false, 'id');
        foreach ($articleList as $_itemInfo) {
            echo base_url(true) . '/article/' . $_itemInfo['id'] . ".html\r\n";
        }
        $courseList = $this->model()->fetch_page('course_content_table', 'article_id > 0 ', null, intval($_GET['pageId']), 10000, false, '*');
        $courseIds = array_column($courseList, 'article_id');
        if ($courseIds) {
            $categoryIds = array_column($categoryList, 'id');
            $categoryList = array_combine($categoryIds, $categoryList);
            $courseArticleList = $this->model()->fetch_page('course', 'id IN (' . join(',', $courseIds) . ')', null, intval($_GET['pageId']), 10000, false, '*');
            $courseIds = array_column($courseArticleList, 'id');
            $courseArticleList = array_combine($courseIds, $courseArticleList);

            try {
                foreach ($courseList as $_itemInfo) {
                    echo base_url(true) . '/course/' . $categoryList[$_itemInfo['category_id']]['url_token'] . '/'
                    . ($courseArticleList[$_itemInfo['article_id']]['url_token'] ==''?$_itemInfo['article_id'] : $courseArticleList[$_itemInfo['article_id']]['url_token'])
                    . ".html\r\n";
                }
            } catch (Exception $e) {
            }
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
