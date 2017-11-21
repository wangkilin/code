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

class help extends AdminController
{
    public function setup()
    {
        $this->crumb(Application::lang()->_t('帮助中心'), "admin/help/list/");

        if (!$this->user_info['permission']['is_administortar'])
        {
            H::redirect_msg(Application::lang()->_t('你没有访问权限, 请重新登录'), '/');
        }

        View::assign('menu_list', $this->model('admin')->fetch_menu_list('help/list'));
    }

    public function list_action()
    {
        View::assign('chapter_list', $this->model('help')->get_chapter_list());

        View::output('admin/help/list');
    }

    public function edit_action()
    {
        if ($_GET['id'])
        {
            $chapter_info = $this->model('help')->get_chapter_by_id($_GET['id']);

            if (!$chapter_info)
            {
                H::redirect_msg(Application::lang()->_t('指定章节不存在'), '/admin/help/list/');
            }

            View::assign('chapter_info', $chapter_info);

            $data_list = $this->model('help')->get_data_list($chapter_info['id']);

            if ($data_list)
            {
                View::assign('data_list', $data_list);
            }
        }

        View::output('admin/help/edit');
    }
}
