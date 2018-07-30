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
        $rule_action['actions'] = array(
            'explore'
        );

        if ($this->user_info['permission']['visit_explore'] AND $this->user_info['permission']['visit_site'])
        {
            $rule_action['actions'][] = 'index';
        }

        return $rule_action;
    }

    public function setup()
    {
        if (is_mobile() AND !$_GET['ignore_ua_check'])
        {
            switch ($_GET['app'])
            {
                default:
                    HTTP::redirect('/m/');
                break;
            }
        }
    }

    public function index_action()
    {
        if (! $this->user_id)
        {
            HTTP::redirect('/index/');
        }

        if (! $this->user_info['email'])
        {
            HTTP::redirect('/account/complete_profile/');
        }

        // 边栏可能感兴趣的人或话题
        if (View::is_output('block/sidebar_recommend_users_topics.php', 'home/index')) {
            $recommend_users_topics = $this->model('module')->recommend_users_topics($this->user_id);

            View::assign('sidebar_recommend_users_topics', $recommend_users_topics);
        }

        // 边栏热门用户
        if (View::is_output('block/sidebar_hot_users.php', 'home/index'))
        {
            $sidebar_hot_users = $this->model('module')->sidebar_hot_users($this->user_id);

            View::assign('sidebar_hot_users', $sidebar_hot_users);
        }

        $this->crumb(Application::lang()->_t('动态'), '/home/');

        View::import_js('js/app/index.js');

        View::output('home/index');
    }
}