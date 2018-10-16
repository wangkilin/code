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

class category extends AdminController
{
    public function setup()
    {
        $this->crumb(Application::lang()->_t('分类管理'), "admin/category/list/");

        if (!$this->user_info['permission']['is_administortar'])
        {
            H::redirect_msg(Application::lang()->_t('你没有访问权限, 请重新登录'), '/');
        }

        View::assign('menu_list', $this->model('admin')->fetch_menu_list('category/list'));
    }

    public function list_action()
    {
        View::assign('list', json_decode($this->model('system')->build_category_json('question'), true));

        View::assign('category_option', $this->model('system')->build_category_html('question', 0, 0, null, false));

        View::assign('target_category', $this->model('system')->build_category_html('question', 0, null));

        View::output('admin/category/list');
    }

    public function edit_action()
    {
        if (!$category_info = $this->model('system')->get_category_info($_GET['category_id']))
        {
            H::redirect_msg(Application::lang()->_t('指定分类不存在'), '/admin/category/list/');
        }

        View::assign('category', $category_info);
        View::assign('category_option', $this->model('system')->build_category_html($category_info['type'], 0, $category['parent_id'], null, false));
        View::import_js('js/fileupload.js');
        View::output('admin/category/edit');
    }
    /**
     * 可发布内容的模块列表
     */
    public function module_action ()
    {
        View::assign('list', $this->model('postModule')->getModuleList(null, 'id DESC' ,PHP_INT_MAX));
        View::output('admin/category/module');
    }

    /**
     * 显示可发布内容模块的编辑表单
     */
    public function edit_post_module_action ()
    {
        if (!$itemInfo = $this->model('postModule')->getModuleById($_GET['id']))
        {
            H::redirect_msg(Application::lang()->_t('指定分类不存在'), '/admin/category/list/');
        }

        View::assign('itemInfo', $itemInfo);
        View::import_js('js/fileupload.js');
        View::output('admin/category/edit_module');
    }
}
