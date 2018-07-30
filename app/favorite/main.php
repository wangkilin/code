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
        $rule_action['rule_type'] = 'white';

        $rule_action['actions'] = array();

        return $rule_action;
    }

    public function setup()
    {
        $this->crumb(Application::lang()->_t('我的收藏'), '/favorite/');
    }

    public function index_action()
    {
        if ($_GET['tag'])
        {
            $this->crumb(Application::lang()->_t('标签') . ': ' . $_GET['tag'], '/favorite/tag-' . $_GET['tag']);
        }

        //边栏可能感兴趣的人或话题
        if (View::is_output('block/sidebar_recommend_users_topics.php', 'favorite/index'))
        {
            $recommend_users_topics = $this->model('module')->recommend_users_topics($this->user_id);

            View::assign('sidebar_recommend_users_topics', $recommend_users_topics);
        }

        if ($action_list = $this->model('favorite')->get_item_list($_GET['tag'], $this->user_id, calc_page_limit($_GET['page'], get_setting('contents_per_page'))))
        {
            foreach ($action_list AS $key => $val)
            {
                $item_ids[] = $val['item_id'];
            }

            View::assign('list', $action_list);
        }
        else
        {
            if (!$_GET['page'] OR $_GET['page'] == 1)
            {
                $this->model('favorite')->remove_favorite_tag(null, null, $_GET['tag'], $this->user_id);
            }
        }

        if ($item_ids)
        {
            $favorite_items_tags = $this->model('favorite')->get_favorite_items_tags_by_item_id($this->user_id, $item_ids);

            View::assign('favorite_items_tags', $favorite_items_tags);
        }

        View::assign('favorite_tags', $this->model('favorite')->get_favorite_tags($this->user_id));

        View::assign('pagination', Application::pagination()->initialize(array(
            'base_url' => get_js_url('/favorite/tag-' . $_GET['tag']),
            'total_rows' => $this->model('favorite')->count_favorite_items($this->user_id, $_GET['tag']),
            'per_page' => get_setting('contents_per_page')
        ))->create_links());

        View::output('favorite/index');
    }
}