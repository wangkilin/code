<?php

class tag extends AdminController
{
    public function setup()    {}

    /**
     * 标签列表管理
     */
    public function list_action()
    {
        $this->crumb(Application::lang()->_t('标签管理'), 'admin/tag/list/');

        if ($_POST) {
            foreach ($_POST as $key => $val) {
                if ($key == 'keyword') {
                    $val = rawurlencode($val);
                }
                $param[] = $key . '-' . $val;
            }

            H::ajax_json_output(Application::RSM(array(
                'url' => get_js_url('/admin/tag/list/' . implode('__', $param))
            ), 1, null));
        }

        $tagList      = $this->getTagByConditions($_GET, 'id DESC', $this->per_page, $_GET['page']);
        $total_rows   = $this->model('tag')->found_rows();
        $relationList = $this->model('tag')->getTagCategoryRelations();
        $categoryList = $this->model('tag')->getAllTagCategory();

        $url_param = array();

        foreach($_GET as $key => $val) {
            if (!in_array($key, array('app', 'c', 'act', 'page'))) {
                $url_param[] = $key . '-' . $val;
            }
        }

        View::assign('pagination', Application::pagination()->initialize(array(
            'base_url' => get_js_url('/admin/tag/list/') . implode('__', $url_param),
            'total_rows' => $total_rows,
            'per_page' => $this->per_page
        ))->create_links());

        View::assign('tags_count', $total_rows);
        View::assign('list', $tagList);
        View::assign('categoryList', $categoryList);
        View::assign('relationList', $relationList);
        View::import_js(array('js/bootstrap-multiselect.js'));
        View::import_css(G_STATIC_URL . '/css/bootstrap-multiselect.css');

        View::output('admin/tag/list');
    }

    /**
     * 显示标签分类列表
     */
    public function list_category_action()
    {
        $this->crumb(Application::lang()->_t('标签分类'), 'admin/tag/category/');

        $cagegoryList = $this->model('tag')->getTagCategory('', 'id DESC', $this->per_page, $_GET['page']);
        $total_rows = $this->model('tag')->found_rows();


        View::assign('pagination', Application::pagination()->initialize(array(
            'base_url' => get_js_url('/admin/tag/list_category/'),
            'total_rows' => $total_rows,
            'per_page' => $this->per_page
        ))->create_links());

        View::assign('list', $cagegoryList);

        View::output('admin/tag/list_category');
    }

    /**
     * 编辑/添加新标签
     */
    public function tag_action()
    {
        if ($_GET['id']) {
            $this->crumb(Application::lang()->_t('标签编辑'), 'admin/tag/tag/');

            $tagInfo = $this->model('tag')->getTagById($_GET['id']);

            if (! $tagInfo) {
                H::redirect_msg(Application::lang()->_t('标签不存在'), '/admin/tag/list/');
            }

            $tagInfo['category_ids'] = $this->model('tag')->getTagCategoryRelations('tag', $_GET['id']);
            View::assign('tagInfo', $tagInfo);


        } else {
            $this->crumb(Application::lang()->_t('新建标签'), 'admin/tag/tag/');
        }

        View::assign('categoryList', $this->model('tag')->getAllTagCategory());

        View::import_js(array('js/fileupload.js', 'js/bootstrap-multiselect.js'));
        View::import_css(G_STATIC_URL . '/css/bootstrap-multiselect.css');

        View::output('admin/tag/tag');
    }

    /**
     * 编辑/添加新标签分类
     */
    public function category_action()
    {
        if ($_GET['id']) {
            $this->crumb(Application::lang()->_t('标签分类编辑'), 'admin/'.CONTROLLER.'/'.ACTION.'/');

            $categoryInfo = $this->model('tag')->getTagCategoryById($_GET['id']);

            if (! $categoryInfo) {
                H::redirect_msg(Application::lang()->_t('标签分类不存在'), '/admin/'.CONTROLLER.'/list_category/');
            }

            View::assign('categoryInfo', $categoryInfo);

        } else {
            $this->crumb(Application::lang()->_t('新建标签分类'), 'admin/'.CONTROLLER.'/'.ACTION.'/');
        }

        View::import_js(array('js/fileupload.js'));

        View::output('admin/tag/category');
    }
}