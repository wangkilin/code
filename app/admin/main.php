<?php
defined('iCodeBang_Com') OR die('Access denied!');

class main extends AdminController
{
    public function index_action()
    {
        $this->crumb(Application::lang()->_t('概述'), 'admin/main/');

        if (!defined('IN_SAE'))
        {
            $writable_check = array(
                'cache' => is_really_writable(ROOT_PATH . 'cache/'),
                'tmp' => is_really_writable(ROOT_PATH . './tmp/'),
                get_setting('upload_dir') => is_really_writable(get_setting('upload_dir'))
            );

            View::assign('writable_check', $writable_check);
        }

        View::assign('users_count', $this->model('system')->count('users'));
        View::assign('users_valid_email_count', $this->model('system')->count('users', 'valid_email = 1'));
        View::assign('question_count', $this->model('system')->count('question'));
        View::assign('answer_count', $this->model('system')->count('answer'));
        View::assign('question_count', $this->model('system')->count('question'));
        View::assign('question_no_answer_count', $this->model('system')->count('question', 'answer_count = 0'));
        View::assign('best_answer_count', $this->model('system')->count('question', 'best_answer > 0'));
        View::assign('topic_count', $this->model('system')->count('topic'));
        View::assign('attach_count', $this->model('system')->count('attach'));
        View::assign('approval_question_count', $this->model('publish')->count('approval', "type = 'question'"));
        View::assign('approval_answer_count', $this->model('publish')->count('approval', "type = 'answer'"));

        $admin_menu = (array)Application::config()->get('admin_menu');

        $admin_menu[0]['select'] = true;

        View::assign('menu_list', $admin_menu);

        View::output('admin/index');
    }

    public function login_action()
    {
        if (!$this->user_info['permission']['is_administortar'] AND !$this->user_info['permission']['is_moderator'])
        {
            H::redirect_msg(Application::lang()->_t('你没有访问权限, 请重新登录'), '/');
        }
        else if (Application::session()->admin_login)
        {
            $admin_info = json_decode(Application::crypt()->decode(Application::session()->admin_login), true);

            if ($admin_info['uid'])
            {
                HTTP::redirect('/admin/');
            }
        }

        View::import_css('admin/css/login.css');

        View::output('admin/login');
    }

    public function logout_action($return_url = '/')
    {
        $this->model('admin')->admin_logout();

        HTTP::redirect($return_url);
    }

    public function settings_action()
    {
        $this->crumb(Application::lang()->_t('系统设置'), 'admin/settings/');

        if (!$this->user_info['permission']['is_administortar'])
        {
            H::redirect_msg(Application::lang()->_t('你没有访问权限, 请重新登录'), '/');
        }

        if (!$_GET['category'])
        {
            $_GET['category'] = 'site';
        }

        switch ($_GET['category'])
        {
            case 'interface':
                View::assign('styles', $this->model('setting')->get_ui_styles());
            break;

            case 'register':
                View::assign('notification_settings', get_setting('new_user_notification_setting'));
                View::assign('notify_actions', $this->model('notify')->notify_action_details);
            break;
        }

        View::assign('setting', get_setting(null, false));

        View::assign('menu_list', $this->model('admin')->fetch_menu_list('SETTINGS_' . strtoupper($_GET['category'])));

        View::output('admin/settings');
    }

    public function nav_menu_action()
    {
        $this->crumb(Application::lang()->_t('导航设置'), 'admin/nav_menu/');

        if (!$this->user_info['permission']['is_administortar']) {
            H::redirect_msg(Application::lang()->_t('你没有访问权限, 请重新登录'), '/');
        }

        View::assign('nav_menu_list', $this->model('menu')->getNavMenuWithModuleInLink());

        View::assign('category_list', $this->model('system')->build_category_html('question', 0, 0, null, true));
        // 话题列表数据
        View::assign('tag_list', $this->model('topic')->buildTopicDropdownHtml());
        View::assign('setting', get_setting());

        View::import_js(array(
            'js/fileupload.js',
        ));

        View::assign('menu_list', $this->model('admin')->fetch_menu_list('nav_menu'));

        View::output('admin/nav_menu');
    }

    /**
     * 技能导航菜单
     */
    public function tag_nav_menu_action()
    {
        $this->crumb(Application::lang()->_t('技能导航'), 'admin/tag_nav_menu/');

        if (!$this->user_info['permission']['is_administortar']) {
            H::redirect_msg(Application::lang()->_t('你没有访问权限, 请重新登录'), '/');
        }

        View::assign('nav_menu_list', $this->model('menu')->getTagMenuList());
        View::assign('category_list', $this->model('system')->build_category_html('question', 0, 0, null, true));

        View::assign('tag_list', $this->model('topic')->buildTopicDropdownHtml());

        View::assign('setting', get_setting());

        View::import_js(array(
            'js/fileupload.js',
        ));

        View::output('admin/tag_nav_menu');
    }
}