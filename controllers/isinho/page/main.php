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
defined('iCodeBang_Com') OR die('Access denied!');

class main extends Controller
{
    /**
     * 是否是内部方法访问。 外部方法只能查看对外公开的页面内容
     * @var bool
     */
    protected $_isInside = false;

    public function setup ()
    {
        $this->crumb = array();
        $this->crumb('首页', '/');
        $this->crumb(_t('动态'), '/page');
    }

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


        View::assign('_isInside', $this->_isInside);
        View::output('page/index');
    }

    /**
     * 将分类作为导航菜单, 显示
     */
    public function categoryIsNavMenuAndShowPage ($page_info)
    {
        $where = array();
        $where[] = $this->_isInside==true ? 'publish_area !=' . pageModel::PUBLIC_AREA_OUTSIDE : 'publish_area !=' . pageModel::PUBLIC_AREA_INSIDE;
        $where[] = 'belong_domain = ' . self::$domainId;

        $categoryInfo =$this->model()->fetch_row('page_category', 'id = ' . $page_info['category_id'] . ' AND ' . join(' AND ', $where));
        if (! $categoryInfo) { // 禁止通过id访问分类下的内容
            HTTP::error_404();
        }

        $navCategoryList = $this->model()->fetch_all('page_category', ' parent_id = ' . $categoryInfo['id']  . ' AND ' . join(' AND ', $where));
        if (! $navCategoryList) {
            $navCategoryList = $this->model()->fetch_all('page_category', ' parent_id = ' . $categoryInfo['parent_id']  . ' AND ' . join(' AND ', $where));
        }
        $navCategoryList = array_combine(array_column($navCategoryList, 'id'), $navCategoryList);
        View::assign('navCategoryList', $navCategoryList);

        $_GET['category'] = $categoryInfo['url_token']=='' ? $categoryInfo['id'] : $categoryInfo['url_token'];
        $categoryList = $this->model()->fetch_all('page_category', ' parent_id = ' . $categoryInfo['parent_id']  . ' AND ' . join(' AND ', $where));
        $prevPageInfo = $this->model()->fetch_row('pages', 'id < ' .$page_info['id']. ' AND publish_time<= ' .time(). ' AND enabled = 1 AND category_id = ' . $page_info['category_id']  . ' AND ' . join(' AND ', $where), 'is_top DESC, id DESC');
        $nextPageInfo = $this->model()->fetch_row('pages', 'id > ' .$page_info['id']. ' AND publish_time<= ' .time(). ' AND enabled = 1 AND category_id = ' . $page_info['category_id']  . ' AND ' . join(' AND ', $where), 'is_top DESC, id DESC');

        View::assign('prevPageInfo', $prevPageInfo);
        View::assign('nextPageInfo', $nextPageInfo);
        View::assign('categoryList', $categoryList);


        View::assign('_isInside', $this->_isInside);
        View::output('page/showPageInCategory');
    }

    /**
     * 显示分类下的页面
     */
    public function showPageListInCategory ($categoryToken)
    {
        //$this->per_page = 1;
        if (is_numeric($categoryToken)) {
            $categoryInfo =$this->model('page')->fetch_row('page_category', 'id = ' . $categoryToken);
            if ($categoryInfo && $categoryInfo['url_token']) { // 禁止通过id访问分类下的内容
                HTTP::error_404();
            }
        } else {
            $categoryInfo = $this->model('page')->fetch_row('page_category', 'url_token = "' . $this->model()->quote($categoryToken) .'"' );
        }
        // 如果指定的分类不属于当前访问域名， 设置分类信息为空， 不允许访问
        if (self::$domainId && self::$domainId!=$categoryInfo['belong_domain'] ) {
            $categoryInfo = null;
        }

        // 检查分类的访问范围是否和入口匹配。 限制外网访问内部信息
        if ($this->_isInside && $categoryInfo['publish_area'] == pageModel::PUBLIC_AREA_OUTSIDE) {
        // 内部访问不能访问外网分类
            $categoryInfo = null;
        } else if ($this->_isInside == false && $categoryInfo['publish_area'] == pageModel::PUBLIC_AREA_INSIDE) {
        // 外部访问不能访问内网分类
            $categoryInfo = null;
        }

        if (! $categoryInfo) {
            HTTP::error_404();
        }


        $where = array();
        $where[] = $this->_isInside==true ? 'publish_area !=' . pageModel::PUBLIC_AREA_OUTSIDE : 'publish_area !=' . pageModel::PUBLIC_AREA_INSIDE;
        self::$domainId > 0 AND $where[] =  'belong_domain = ' . self::$domainId;
        // 获取到全部分类， 按照id绑定
        $categoryList = $this->model()->fetch_all('page_category', join(' AND ', $where));
        $categoryList = array_combine(array_column($categoryList, 'id'), $categoryList);
        // 逐级查找上级分类， 设置导航栏
        $categoryCrumb = array($categoryInfo);
        $_parentId = $categoryInfo['parent_id'];
        while(isset($categoryList[$_parentId])) {
            array_unshift($categoryCrumb, $categoryList[$_parentId]);
            $_parentId = $categoryList[$_parentId]['parent_id'];
        }
        // 设置分类信息的导航栏
        foreach ($categoryCrumb as $_itemInfo) {
            $_itemInfo['id'] = empty($_itemInfo['url_token']) ? $_itemInfo['id'] : $_itemInfo['url_token'];
            $this->crumb($_itemInfo['title'], '/page/'.($this->_isInside==true?'inside_square/':'').'category-' .$_itemInfo['id'] .'.html');
        }

        View::assign('categoryInfo', $categoryInfo);
        $navCategoryList = $this->model()->fetch_all('page_category', ' parent_id = ' . $categoryInfo['id'] . ' AND ' . join(' AND ', $where));
        if (! $navCategoryList) {
            $navCategoryList = $this->model()->fetch_all('page_category', ' parent_id = ' . $categoryInfo['parent_id'] . ' AND ' . join(' AND ', $where));
        }
        $navCategoryList = array_combine(array_column($navCategoryList, 'id'), $navCategoryList);
        View::assign('navCategoryList', $navCategoryList);
        View::assign('categoryList', $categoryList);

        // 基于当前分类下，找到所有后代分类
        $tmpFind = true;
        $categoryIdList = array($categoryInfo['id']);
        $tmpCategoryIdList = $categoryIdList;
        while($tmpFind) {
            $tmpArray = array();
            foreach ($categoryList as $_categoryInfo) {
                if (in_array($_categoryInfo['parent_id'], $tmpCategoryIdList) ) {
                    $categoryIdList[] = $_categoryInfo['id'];
                    $tmpArray[] = $_categoryInfo['id'];
                }
            }

            if ($tmpArray) {
                $tmpCategoryIdList = $tmpArray;
            } else {
                $tmpFind = false;
                break;
            }
        }

        $where[] = 'enabled = 1 AND publish_time <=' . time() . ' AND category_id IN (' . join(',', $categoryIdList) . ')';
        $pageList  = $this->model()->fetch_page('pages', join(' AND ', $where), 'modify_time DESC,id DESC', $_GET['page'], $this->per_page, true, '*');
        $totalRows = $this->model()->found_rows();
        $userIds   = array_column($pageList, 'user_id');
        $userList  = array();
        if ($userIds) {
            $userList  = $this->model()->fetch_all('users', 'uid IN (' . join(',', $userIds) .')');
            $userList  = array_combine(array_column($userList, 'uid'), $userList);
        }
        $thumbList = array ();
        if ($pageList) { // 获取图片
            $thumbList = $this->model('attach')->getThumbListByItemids('page', array_column($pageList, 'id'));
        }

        View::assign('userList', $userList);
        View::assign('pageList', $pageList);
        View::assign('thumbList', $thumbList);

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


        View::assign('_isInside', $this->_isInside);
        View::output('page/showPageListInCategory');
    }

    /**
     *
     */
    public function index_square_action()
    {
        $this->_isInside = false; // 外部方法访问
        $this->index_square ();
    }

    /**
     * 内部访问首页
     */
    public function inside_square_action ()
    {
        $this->_isInside = true; // 内部访问
        if (! $this->user_info['uid']) {

            HTTP::error_404();
        }
        $this->index_square ();
    }

    public function index_square ()
    {
        if (is_mobile()) {
            //HTTP::redirect('/m/article/');
        }
        if ($_GET['category']) { // 访问指定分类
            $this->showPageListInCategory($_GET['category']);
            return;

        }

        $where = self::$domainId > 0 ? 'belong_domain = ' . self::$domainId : null;
        $categoryList = $this->model()->fetch_all('page_category', $where);
        $categoryList = array_combine(array_column($categoryList, 'id'), $categoryList);
        $navCategoryList = $this->model()->fetch_all('page_category', is_null($where) ? 'parent_id = 0' : ($where . ' AND parent_id = 0'));
        $navCategoryList = array_combine(array_column($navCategoryList, 'id'), $navCategoryList);
        View::assign('categoryList', $categoryList);
        View::assign('navCategoryList', $navCategoryList);

        $_sqlWhere = $this->_isInside ? 'publish_area != ' . pageModel::PUBLIC_AREA_OUTSIDE : 'publish_area != ' . pageModel::PUBLIC_AREA_INSIDE;
        $pageList  = $this->model()->fetch_page('pages', $_sqlWhere . ' AND enabled = 1 AND publish_time <=' . time() . ' AND ' . $where, 'modify_time DESC, id DESC', $_GET['page'], $this->per_page, true, '*');
        $totalRows = $this->model()->found_rows();
        $userIds   = array_column($pageList, 'user_id');
        $userList  = array();
        if ($userIds) {
            $userList  = $this->model()->fetch_all('users', 'uid IN (' . join(',', $userIds) .')');
            $userList  = array_combine(array_column($userList, 'uid'), $userList);
        }
        $thumbList = array ();
        if ($pageList) { // 获取图片
            $thumbList = $this->model('attach')->getThumbListByItemids('page', array_column($pageList, 'id'));
        }
        View::assign('userList', $userList);
        View::assign('pageList', $pageList);
        View::assign('thumbList', $thumbList);

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


        View::assign('_isInside', $this->_isInside);
        View::output('page/showPageListInCategory');
    }

    /**
     * 显示文章
     */
    public function index_action()
    {
        $this->_isInside = false; // 外部方法访问
        $this->index();
    }

    public function inside_index_action ()
    {
        $this->_isInside = true;
        if (! $this->user_info['uid']) {

            HTTP::error_404();
        }
        $this->index();
    }

    public function index ()
    {
        if ($_GET['id']) { // 访问指定页面
            $page_info = $this->model('page')->getPageByToken($_GET['id']);
            if (!$page_info OR $page_info['enabled'] == 0
              OR ($this->_isInside==false && $page_info['publish_area']==pageModel::PUBLIC_AREA_INSIDE)
              OR ($this->_isInside==true  && $page_info['publish_area']==pageModel::PUBLIC_AREA_OUTSIDE)
            ) {
            // 页面信息没找到， 或者页面没有启用，或者是内部信息页面: 显示404
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
        // 文章设置了关键字， 将关键字设置到页面的meta中
        if ($page_info['keywords']) {
            View::set_meta('keywords', $page_info['keywords']);
        }
        // 将文章的描述信息， 添加到页面的meta中
        if ($page_info['description']) {
            View::set_meta('description', $page_info['description']);
        }

        View::assign('page_info', $page_info);

        $where = self::$domainId > 0 ? 'belong_domain = ' . self::$domainId : null;
        // 获取到全部分类， 按照id绑定
        $categoryList = $this->model()->fetch_all('page_category', $where);
        $categoryList = array_combine(array_column($categoryList, 'id'), $categoryList);
        // 设置当前位置的分类信息
        $categoryInfo = $categoryList[$page_info['category_id']];
        // 逐级查找上级分类， 设置导航栏
        $categoryCrumb = array($categoryInfo);
        $_parentId = $categoryInfo['parent_id'];
        while(isset($categoryList[$_parentId])) {
            array_unshift($categoryCrumb, $categoryList[$_parentId]);
            $_parentId = $categoryList[$_parentId]['parent_id'];
        }
        // 设置分类信息的导航栏
        foreach ($categoryCrumb as $_itemInfo) {
            $_itemInfo['id'] = empty($_itemInfo['url_token']) ? $_itemInfo['id'] : $_itemInfo['url_token'];
            $this->crumb($_itemInfo['title'], '/page/'.($this->_isInside==true?'inside_square/':'').'category-' .$_itemInfo['id'] .'.html');
        }

        // 将文章标题添加到页面的面包屑导航
        if ($page_info['title']) {

            $this->crumb($page_info['title'], '/page/'.($this->_isInside==true?'inside_index/':'') . $page_info['url_token'] .'.html');
            //View::assign('page_title', $page_info['title']);
        }

        // 根据分类的显示属性，展现对应的页面内容
        switch ($categoryInfo['display_mode']) {
            case 3:
            case 2:
                $this->categoryIsNavMenuAndShowPage($page_info);
                break;
            case 1:
            default:
                $this->pageIsNavMenu($page_info);
                break;


        }
    }
}
