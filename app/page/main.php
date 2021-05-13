<?php
/*
+--------------------------------------------------------------------------
|   WeCenter [#RELEASE_VERSION#]
|   ========================================
|   by WeCenter Software
|   © 2011 - 2014 WeCenter. All Rights Reserved
|   http://www.wecenter.com
|   ========================================
|   Support: WeCenter@qq.com
|
+---------------------------------------------------------------------------
*/


if (!defined('iCodeBang_Com'))
{
    die;
}

class main extends Controller
{
    public function get_access_rule()
    {
        $rule_action['rule_type'] = 'black';
        $rule_action['actions'] = array();

        return $rule_action;
    }

    /**
     * 分类下的页面数量较少， 将网页作为导航菜单
     */
    public function pageIsNavMenu ($page_info)
    {
        if ($page_info['category_id']) {
            View::assign('page_list', $this->model()->fetch_all('pages', 'category_id = ' . $page_info['category_id']));
        }

        View::output('page/index');
    }

    /**
     * 将分类作为导航菜单, 显示
     */
    public function categoryIsNaMenuAndShowPage ($page_info)
    {
        $categoryInfo =$this->model()->fetch_row('page_category', 'id = ' . $page_info['category_id']);
        if (! $categoryInfo) { // 禁止通过id访问分类下的内容
            HTTP::error_404();
        }

        $_GET['category'] = $categoryInfo['url_token']=='' ? $categoryInfo['id'] : $categoryInfo['url_token'];
        $categoryList = $this->model()->fetch_all('page_category', ' parent_id = ' . $categoryInfo['parent_id']);
        $prevPageInfo = $this->model()->fetch_row('pages', 'id < ' .$page_info['id']. ' AND publish_time<= ' .time(). ' AND enabled = 1 AND category_id = ' . $page_info['category_id'], 'is_top DESC, id DESC');
        $nextPageInfo = $this->model()->fetch_row('pages', 'id > ' .$page_info['id']. ' AND publish_time<= ' .time(). ' AND enabled = 1 AND category_id = ' . $page_info['category_id'], 'is_top DESC, id DESC');

        View::assign('prevPageInfo', $prevPageInfo);
        View::assign('nextPageInfo', $nextPageInfo);
        View::assign('categoryList', $categoryList);

        View::output('page/showPageInCategory');
    }

    /**
     * 显示分类下的页面
     */
    public function showPageListInCategory ($categoryToken)
    {
        //$this->per_page = 2;
        if (is_numeric($categoryToken)) {
            $categoryInfo =$this->model()->fetch_row('page_category', 'id = ' . $categoryToken);
            if ($categoryInfo && $categoryInfo['url_token']) { // 禁止通过id访问分类下的内容
                HTTP::error_404();
            }
        } else {
            $categoryInfo = $this->model()->fetch_row('page_category', 'url_token = "' . $this->model()->quote($categoryToken) .'"' );
        }

        if (! $categoryInfo) {
            HTTP::error_404();
        }

        View::assign('categoryInfo', $categoryInfo);
        $categoryList = $this->model()->fetch_all('page_category', ' parent_id = ' . $categoryInfo['parent_id']);

        View::assign('categoryList', $categoryList);

        $pageList  = $this->model()->fetch_page('pages', 'enabled = 1 AND publish_time <=' . time() . ' AND category_id = '.$categoryInfo['id'], 'id DESC', $_GET['page'], $this->per_page, true, '*');
        $totalRows   = $this->model()->found_rows();
        View::assign('pageList', $pageList);

        $url_param = array();
        foreach($_GET as $key => $val) {
            if (!in_array($key, array('app', 'c', 'act', 'page'))) {
                $url_param[] = $key . '-' . $val;
            }
        }
        View::assign('pagination', Application::pagination()->initialize(array(
            'base_url'   => get_js_url('/page/') . implode('__', $url_param),
            'total_rows' => $totalRows,
            'per_page'   => $this->per_page
        ))->create_links());

        View::output('page/showPageListInCategory');
    }

    public function index_action()
    {
        if ($_GET['id']) { // 访问指定页面
            $page_info = $this->model('page')->getPageByToken($_GET['id']);
            if (!$page_info OR $page_info['enabled'] == 0) { // 页面信息没找到， 或者页面没有启用， 显示404
                HTTP::error_404();
            }
        } else if ($_GET['category']) { // 访问指定分类

            $this->showPageListInCategory($_GET['category']);
            return;

        } else { // 没有指定页面， 也没有指定分类， 直接404
            HTTP::error_404();
            return;
        }

        $page_info['url_token'] = empty($page_info['url_token']) ? $page_info['id'] : $page_info['url_token'];
        if ($page_info['title']) {

            $this->crumb($page_info['title'], '/' . $page_info['url_token'] );
            //View::assign('page_title', $page_info['title']);
        }

        if ($page_info['keywords']) {
            View::set_meta('keywords', $page_info['keywords']);
        }

        if ($page_info['description']) {
            View::set_meta('description', $page_info['description']);
        }

        View::assign('page_info', $page_info);

        $categoryInfo =$this->model()->fetch_row('page_category', 'id = ' . $page_info['category_id']);

        switch ($categoryInfo['display_mode']) {
            case 3:
            case 2:
                $this->categoryIsNaMenuAndShowPage($page_info);
                break;
            case 1:
            default:
                $this->pageIsNavMenu($page_info);
                break;


        }
    }
}
