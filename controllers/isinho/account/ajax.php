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
define('IN_AJAX', TRUE);


include_once __DIR__ . '/../SinhoBaseController.php';

class ajax extends SinhoBaseController
{
    public function get_access_rule()
    {
        $rule_action['rule_type'] = 'white'; //黑名单,黑名单中的检查  'white'白名单,白名单以外的检查

        $rule_action['actions'] = array(
            'login_process',
        );

        return $rule_action;
    }

    public function setup()
    {
        HTTP::setHeaderNoCache();
    }

    public function login_process_action()
    {
        // 验证码检查
        if (!Application::captcha()->is_validate($_POST['seccode_verify'])) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('请填写正确的验证码')));
        }

        // 检查用户名密码， 获取用户信息
        $user_info = $this->model('account')->check_login($_POST['user_name'], $_POST['password']);
        // 账号错误
        if (! $user_info) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('请输入正确的帐号或密码')));
        }

        // 用户状态被禁用
        if ($user_info['forbidden'] == 1) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('抱歉, 你的账号已经被禁止登录')));
        }
        // 设置cookie过期时间
        $expire = 60 * 60 * 24;
        if ($_POST['net_auto_login']) {
            $expire = 60 * 60 * 24 * 360;
        }
        // 更新用户登录时间。 属于 register_shutdown_function 行为
        $this->model('account')->update_user_last_login($user_info['uid']);
        // 将旧信息失效
        $this->model('account')->logout();

        $url = '/admin/';
        $this->model('account')->setcookie_login($user_info['uid'], $_POST['user_name'], $_POST['password'], $user_info['salt'], $expire);
        if ($_POST['return_url'] AND !strstr($_POST['return_url'], '/logout') ) {
            $url = get_js_url($_POST['return_url']);
        }
        $this->model('admin')->set_admin_login($user_info['uid']);

        H::ajax_json_output(Application::RSM(array(
                'url' => $url
            ), 1, null));


    }


}

/* EOF */
