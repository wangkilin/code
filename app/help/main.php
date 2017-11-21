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
        $rule_action['rule_type'] = "white"; //'black'黑名单,黑名单中的检查  'white'白名单,白名单以外的检查

        if ($this->user_info['permission']['visit_chapter'] AND $this->user_info['permission']['visit_site'])
        {
            $rule_action['actions'][] = 'index';
        }

        return $rule_action;
    }

    public function setup()
    {
        if (get_setting('enable_help_center') != 'Y')
        {
            H::redirect_msg(Application::lang()->_t('本站未启用帮助中心'), '/');
        }
    }

    public function index_action()
    {
        if ($_GET['id'])
        {
            $chapter_list = $this->model('help')->get_chapter_list();

            if (!$chapter_list)
            {
                header('HTTP/1.1 404 Not Found');

                H::redirect_msg(Application::lang()->_t('指定章节不存在'), '/');
            }

            View::assign('chapter_list', $chapter_list);

            foreach ($chapter_list AS $chapter_info)
            {
                if ($chapter_info['url_token'] == $_GET['id'])
                {
                    $chapter = $chapter_info;

                    break;
                }
            }

            if (!$chapter)
            {
                $chapter = $chapter_list[$_GET['id']];
            }

            if (!$chapter)
            {
                header('HTTP/1.1 404 Not Found');

                H::redirect_msg(Application::lang()->_t('指定章节不存在'), '/help/');
            }

            View::assign('chapter_info', $chapter);

            $data_list = $this->model('help')->get_data_list($chapter['id']);

            if ($data_list)
            {
                View::assign('data_list', $data_list);
            }

            $this->crumb($chapter['title'], '/help/' . ($chapter['url_token']) ? $chapter['url_token'] : $chapter['id']);

            View::output('help/index');
        }
        else
        {
            $chapter_list = $this->model('help')->get_chapter_list();

            if ($chapter_list)
            {
                View::assign('chapter_list', $chapter_list);
            }

            $data_list = $this->model('help')->get_data_list(null, 5);

            if ($data_list)
            {
                View::assign('data_list', $data_list);
            }

            $this->crumb(Application::lang()->_t('帮助中心'), '/help/');

            View::output('help/square');
        }
    }
}
