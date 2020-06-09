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

class course extends AdminController
{

    /**
     * 教程文章列表
     */
    public function list_action()
    {
        $this->crumb(Application::lang()->_t('教程管理'), 'admin/course/list/');

        if ($_POST) {// 搜索教程
            foreach ($_POST as $key => $val) {
                if ($key == 'keyword') {
                    $val = rawurlencode($val);
                }

                $param[] = $key . '-' . $val;
            }

            H::ajax_json_output(Application::RSM(array(
                'url' => get_js_url('/admin/course/list/' . implode('__', $param))
            ), 1, null));
        }

        $selected = array();
        $where = array();

        if (! empty($_GET['category_id'])) {
            $where[] = 'category_id = ' . intval($_GET['category_id']);
            $selected = array($_GET['category_id']);
        }

        if ($_GET['keyword']) {
            $where[] = "title LIKE '" . $this->model('course')->quote($_GET['keyword']) . "%'";
        }

        if ($_GET['views_min'] OR $_GET['views_min'] == '0') {
            $where[] = 'views >= ' . intval($_GET['views_min']);
        }

        if ($_GET['views_max'] OR $_GET['views_max'] == '0') {
            $where[] = 'views <= ' . intval($_GET['views_max']);
        }

        if ($_GET['start_date']) {
            $where[] = 'add_time >= ' . strtotime($_GET['start_date']);
        }

        if ($_GET['end_date']) {
            $where[] = 'add_time <= ' . strtotime('+1 day', strtotime($_GET['end_date']));
        }

        $courseList = $this->model('course')->getCourseList(implode(' AND ', $where), 'id DESC', $this->per_page, $_GET['page']);
        $parentIds = array();
        foreach ($courseList as $_courseInfo) {
            $parentIds[] = $_courseInfo['category_id'];
        }
        $totalRows     = $this->model('course')->found_rows();
        $categoryList = $this->model('category')->getByIds($parentIds);

        $url_param = array();

        foreach($_GET as $key => $val) {
            if (!in_array($key, array('app', 'c', 'act', 'page'))) {
                $url_param[] = $key . '-' . $val;
            }
        }

        View::assign('pagination', Application::pagination()->initialize(array(
            'base_url'   => get_js_url('/admin/course/list/') . implode('__', $url_param),
            'total_rows' => $totalRows,
            'per_page'   => $this->per_page
        ))->create_links());
        $categoryList = $this->model('category')->getAllCategories('id');
        View::assign('itemOptions', buildSelectOptions(getListInTreeList($categoryList), 'title', 'id', null, array('module'=>'data-module') ) );
        //View::assign('itemOptions', $this->buildCategoryDropdownHtml('0', $selected, '--'));
        View::assign('totalRows', $totalRows);
        View::assign('itemsList', $courseList);
        View::assign('parentItemsList', $categoryList);

        View::output('admin/course/list');
    }

    /**
     * 教程归类管理。 列表教程
     */
    public function table_action()
    {
        // POST方式选择了分类， GET方式跳转到对应分类内容展示
        if (isset($_POST['category_id'])) {
            H::ajax_json_output(Application::RSM(array(
                'url' => get_js_url('/admin/course/content_table/__category_id-'.$_POST['category_id'])
            ), 1, null));
        }
        // breadcrumb 内容
        $this->crumb(Application::lang()->_t('教程归类'), 'admin/course/table/');
        $tableList = array();
        $where = null;
        // GET方式展示指定分类下的文章目录
        if (isset($_GET['category_id'])) {
            $categoryInfo = $this->model('category')->getById($_GET['category_id']);
            View::assign('parentTitle', $categoryInfo['title']);

            $where = "category_id = "  . intval($_GET['category_id']);
            View::assign('parent_id', $_GET['category_id']);
        }
        $tableList= Application::model()->fetch_all('course_table', $where, 'category_id ASC');
        // 构建分类选择菜单
        $categoryList = $this->model('category')->getAllCategories('id');
        View::assign('itemOptions', buildSelectOptions(getListInTreeList($categoryList), 'title', 'id', null, array('module'=>'data-module') ) );
        View::assign('list', $tableList);
        View::assign('categoryList', $categoryList);

        View::output('admin/course/table_manage');
    }

    /**
     * 编辑教程
     */
    public  function edit_table_action ()
    {
        $selected = null;
        if ($_GET['id']) { // 有id， 获取对应的教程信息，显示编辑表单
            $this->crumb(Application::lang()->_t('教程编辑'), 'admin/course/edit_table/');
            // 获取对应教程
            $info = Application::model()->getById($_GET['id'], 'course_table');
            // 没有找到对应教程， 页面跳转
            if (!$info) {
                H::redirect_msg(Application::lang()->_t('教程不存在'), '/admin/course/table/');
            }
            $selected = $info['category_id'];
            View::assign('itemInfo', $info);
            $categoryList = $this->model('category')->getAllCategories('id');
            View::assign('itemOptions', buildSelectOptions(getListInTreeList($categoryList), 'title', 'id', $selected, array('module'=>'data-module','title'=>'title') ) );

            View::output('admin/course/edit_table');
        }

    }

    /**
     * 教程目录管理
     */
    public function content_table_action()
    {
        // POST方式选择了分类， GET方式跳转到对应分类内容展示
        if (isset($_POST['table_id'], $_POST['load_table'])) {
            H::ajax_json_output(Application::RSM(array(
                'url' => get_js_url('/admin/course/content_table/__table_id-'.$_POST['table_id'])
            ), 1, null));
        }
        // breadcrumb 内容
        $this->crumb(Application::lang()->_t('教程目录'), 'admin/course/content_table/');
        $articleList = array();
        $contentTable = array();
        $selectedCategory = isset($_GET['category_id']) ? $_GET['category_id'] : null;
        $selectedTable = null;
        // GET方式展示指定分类下的文章目录
        if (isset($_GET['table_id'])) {
            $typeInfo = explode('-', $_GET['table_id']);
            $selectedTable = $_GET['table_id'];

            // 获取对应教程
            $info = Application::model()->getById($_GET['table_id'], 'course_table');
            $selectedCategory = $info['category_id'];
            //$categoryInfo = $this->model('category')->getById($_GET['category_id']);
            View::assign('parentTitle', $info['title']);

            $where = "category_id = "  . intval($selectedCategory);
            $articleList  = $this->model('course')->getCourseList($where, 'id DESC', PHP_INT_MAX, 0);
            $where = "table_id = "  . intval($_GET['table_id']);
            $contentTable = Application::model()->fetch_all('course_content_table', $where, 'sort ASC');
            View::assign('parent_id', $_GET['table_id']);
            View::assign('table_id', $_GET['table_id']);
        }

        View::assign('topicOptions', $this->model('topic')->buildTopicDropdownHtml($selectedCategory));
        //View::assign('itemOptions', $this->buildCategoryDropdownHtml($selected, '--'));
        // 构建分类选择菜单
        $categoryList = $this->model('category')->getAllCategories('id');
        View::assign('itemOptions', buildSelectOptions(getListInTreeList($categoryList), 'title', 'id', $selectedCategory) );
        // 构建分类选择菜单
        $tableList = Application::model()->fetch_all('course_table', '', 'category_id,sort');
        View::assign('tableOptions', buildSelectOptions(getListInTreeList($tableList), 'title', 'id', $selectedTable, array('category_id'=>'data-category') ) );

        View::assign('list', $articleList);
        View::assign('contentTable', $contentTable);

        View::assign('categoryId', $selectedCategory);
        View::assign('parentItemsList', $categoryList);

        View::output('admin/course/course_table');
    }

    /**
     * 新建编辑教程
     */
    public function course_action()
    {
        $tags = array();
        $selected = null;
        if ($_GET['id']) {
            $this->crumb(Application::lang()->_t('教程编辑'), 'admin/course/course/');

            $articleInfo = $this->model('course')->getById($_GET['id']);

            if (!$articleInfo) {
                H::redirect_msg(Application::lang()->_t('教程不存在'), '/admin/course/course/');
            }
            $selected = $articleInfo['category_id'];

            $bindTopics = $this->model('topic')->getTopicsByArticleId($_GET['id'], 'course');

            View::assign('article_info', $articleInfo);
        } else {
            $this->crumb(Application::lang()->_t('新建教程'), 'admin/course/course/');
        }

        if (!$_GET['id'] OR ($this->hasRolePermission(self::IS_ROLE_ADMIN | self::IS_ROLE_MODERATOR) OR $articleInfo['uid'] == $this->user_id)) {
            View::assign('batchKey', $this->getBatchUploadAccessKey());
        }

        View::assign('bindTopics', $bindTopics);

        $categoryList = $this->model('category')->getAllCategories('id');
        View::assign('itemOptions', buildSelectOptions(getListInTreeList($categoryList), 'title', 'id', $selected, array('module'=>'data-module','title'=>'title') ) );
        //View::assign('itemOptions', $this->buildCategoryDropdownHtml('0', $selected, '--'));

        View::assign('recent_topics', @unserialize($this->user_info['recent_topics']));

        View::import_js('js/fileupload.js');
        if (get_setting('advanced_editor_enable') == 'Y') {
            import_editor_static_files();
        }

        View::output('admin/course/course');
    }

    /**
     * 编辑课后作业
     */
    public function homework_action ()
    {
        if (! $_GET['id'] || ! ($itemInfo = $this->model('course')->getById($_GET['id']) ) ) {
            H::redirect_msg(Application::lang()->_t('指定教程不存在'), '/admin/course/list/');
        }

        $model = $this->model('homework');

        $itemList = $model->getByCourseId($_GET['id']);

        $this->assign('itemList', $itemList);
        $this->assign('batchKey', $this->getBatchUploadAccessKey());
        $this->assign('item', $itemInfo);
        $this->crumb(Application::lang()->_t('课后作业'), 'admin/course/homework/');
        View::import_js('js/fileupload.js');

        View::output('admin/course/homework');
    }
}
