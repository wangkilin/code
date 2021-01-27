<?php
defined('iCodeBang_Com') OR die('Access denied!');

include_once __DIR__ . '/../SinhoBaseController.php';

class main extends SinhoBaseController
{

    public function get_access_rule()
    {
        $rule_action['rule_type'] = 'black';

        $rule_action['actions'] = array();

        return $rule_action;
    }

    public function setup()
    {
        HTTP::setHeaderNoCache();
    }

    public function index_action()
    {
        HTTP::redirect('/');
    }

    public function captcha_action()
    {
        Application::captcha()->generate();
    }

    public function logout_action($return_url = null)
    {
        if ($_GET['return_url'])
        {
            $url = strip_tags(urldecode($_GET['return_url']));
        }
        else if (! $return_url)
        {
            $url = '/';
        }
        else
        {
            $url = $return_url;
        }

        if ($_GET['key'] != md5(session_id()))
        {
            H::redirect_msg(Application::lang()->_t('正在准备退出, 请稍候...'), '/account/logout/?return_url=' . urlencode($url) . '&key=' . md5(session_id()));
        }

        $this->model('account')->logout();

        $this->model('admin')->admin_logout();

        if (get_setting('ucenter_enabled') == 'Y')
        {
            if ($uc_uid = $this->model('ucenter')->is_uc_user($this->user_info['email']))
            {
                $sync_code = $this->model('ucenter')->sync_logout($uc_uid);
            }

            H::redirect_msg(Application::lang()->_t('您已退出站点, 现在将以游客身份进入站点, 请稍候...') . $sync_code, $url);
        }
        else
        {
            HTTP::redirect($url);
        }
    }

    /**
     * 新禾员工登录
     */
    public function login_action()
    {
        $url = base64_decode($_GET['url']);

        if ($this->user_id) {
            if ($url) {
                header('Location: ' . $url);
            } else {
                HTTP::redirect('/admin/');
            }
        }

        $this->crumb(Application::lang()->_t('登录'), '/account/login/');

        //View::import_css('css/login.css');
        View::import_css('admin/css/login.css');


        if ($_GET['url']) {
            $return_url = htmlspecialchars(base64_decode($_GET['url']));
        } else {
            $return_url = htmlspecialchars($_SERVER['HTTP_REFERER']);
        }

        View::assign('return_url', $return_url);

        View::output("login");
    }


}

/* EOF */
