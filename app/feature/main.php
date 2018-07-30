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
        $rule_action['rule_type'] = "white"; //黑名单,黑名单中的检查  'white'白名单,白名单以外的检查

        if ($this->user_info['permission']['visit_feature'] AND $this->user_info['permission']['visit_site'])
        {
            $rule_action['actions'] = array(
                'index'
            );
        }

        return $rule_action;
    }

    public function setup()
    {
        $this->crumb(Application::lang()->_t('专题'), '/feature/');
    }

    public function index_action()
    {
        if (is_digits($_GET['id']))
        {
            $feature_info = $this->model('feature')->get_feature_by_id($_GET['id']);
        }
        else
        {
            $feature_info = $this->model('feature')->get_feature_by_url_token($_GET['id']);
        }

        if (!$feature_info)
        {
            header('HTTP/1.1 404 Not Found');

            H::redirect_msg(Application::lang()->_t('专题不存在'), '/');
        }

        if (!$feature_info['enabled'])
        {
            H::redirect_msg(Application::lang()->_t('专题未启用'), '/');
        }

        if ($feature_info['url_token'] != $_GET['id'] AND !$_GET['sort_type'] AND !$_GET['is_recommend'])
        {
            HTTP::redirect('/feature/' . $feature_info['url_token']);
        }

        if (! $topic_list = $this->model('topic')->getTopicsByIds($this->model('feature')->get_topics_by_feature_id($feature_info['id'])))
        {
            H::redirect_msg(Application::lang()->_t('专题下必须包含一个以上话题'), '/');
        }

        if ($feature_info['seo_title'])
        {
            View::assign('page_title', $feature_info['seo_title']);
        }
        else
        {
            $this->crumb($feature_info['title'], '/feature/' . $feature_info['url_token']);
        }

        View::assign('sidebar_hot_topics', $topic_list);

        View::assign('feature_info', $feature_info);

        View::import_js('js/app/feature.js');

        View::output('feature/detail');
    }
}