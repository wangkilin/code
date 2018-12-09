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
        $categoryList = $this->model('category')->getAllCategories('id', 'course');
        View::assign('itemOptions', buildSelectOptions(getListInTreeList($categoryList), 'title', 'id', null, array('module'=>'data-module') ) );
        //View::assign('itemOptions', $this->buildCategoryDropdownHtml('0', $selected, '--'));
        View::assign('totalRows', $totalRows);
        View::assign('itemsList', $courseList);
        View::assign('parentItemsList', $categoryList);

        View::output('admin/course/list');
    }

    /**
     * 教程目录管理
     */
    public function content_table_action()
    {
        if (isset($_POST['parent_id'])) {
            H::ajax_json_output(Application::RSM(array(
                'url' => get_js_url('/admin/course/content_table/__parent_id-'.$_POST['parent_id'])
            ), 1, null));
        }
        $this->crumb(Application::lang()->_t('教程目录'), 'admin/course/content_table/');
        $articleList = array();
        $contentTable = array();
        $selected = array();
        if (isset($_GET['parent_id'])) {
            $typeInfo = explode('-', $_GET['parent_id']);
            $selected = $_GET['parent_id'];
            $topicInfo = $this->model('category')->getById($_GET['parent_id']);
            View::assign('parentTitle', $topicInfo['title']);
            $where = "parent_id = "  . intval($_GET['parent_id']);
            $articleList  = $this->model('course')->getCourseList($where, 'id DESC', PHP_INT_MAX, 0);
            $where = "category_id = "  . intval($_GET['parent_id']);
            $contentTable = $this->model('course')->fetch_all('course_content_table', $where, 'sort ASC');
            View::assign('parent_id', $_GET['parent_id']);
        }

        View::assign('topicOptions', $this->model('topic')->buildTopicDropdownHtml($selected));
        View::assign('itemOptions', $this->buildCategoryDropdownHtml('0', $selected, '--'));
        View::assign('list', $articleList);
        View::assign('contentTable', $contentTable);

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

        $categoryList = $this->model('category')->getAllCategories('id', 'course');
        View::assign('itemOptions', buildSelectOptions(getListInTreeList($categoryList, 'course'), 'title', 'id', $selected, array('module'=>'data-module','title'=>'title') ) );
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
