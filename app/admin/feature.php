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

class feature extends AdminController
{
    public function setup()
    {
        if (!$this->user_info['permission']['is_administortar'])
        {
            H::redirect_msg(Application::lang()->_t('你没有访问权限, 请重新登录'), '/');
        }

        View::assign('menu_list', $this->model('admin')->fetch_menu_list('feature/list'));
    }

    public function list_action()
    {
        $this->crumb(Application::lang()->_t('专题管理'), 'admin/feature/list/');

        $feature_list = $this->model('feature')->get_feature_list('id DESC', $_GET['page'], $this->per_page);

        View::assign('pagination', Application::pagination()->initialize(array(
            'base_url' => get_js_url('/admin/feature/list/'),
            'total_rows' => $this->model('feature')->found_rows(),
            'per_page' => 20
        ))->create_links());

        View::assign('list', $feature_list);

        View::output('admin/feature/list');
    }

    public function add_action()
    {
        $this->crumb(Application::lang()->_t('添加专题'), 'admin/feature/add/');

        View::output("admin/feature/edit");
    }

    public function edit_action()
    {
        $this->crumb(Application::lang()->_t('编辑专题'), "admin/feature/list/");

        if ($topics_list = $this->model('topic')->getTopicsByIds($this->model('feature')->get_topics_by_feature_id($_GET['feature_id'])))
        {
            foreach ($topics_list AS $key => $val)
            {
                $feature_topics[] = $val['topic_title'];
            }

            if ($feature_topics)
            {
                View::assign('feature_topics', implode("\n", $feature_topics));
            }
        }

        View::assign('feature', $this->model('feature')->get_feature_by_id($_GET['feature_id']));

        View::output('admin/feature/edit');
    }
}