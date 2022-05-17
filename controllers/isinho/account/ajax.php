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
        // 领导不需要验证码 验证码检查
        if (md5(md5($_POST['user_name'])) !== '6299e7aca975a4542a1d74ddeae19fd9' &&  !Application::captcha()->is_validate($_POST['seccode_verify'])) {
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
        $expire = 60 * 60 * 12;
        if ($_POST['net_auto_login']) {
            $expire = 60 * 60 * 24 * 360;
        }
        // 更新用户登录时间。 属于 register_shutdown_function 行为
        $this->model('account')->update_user_last_login($user_info['uid']);
        // 将旧信息失效
        if (isset(Application::session()->client_info)) {
            unset(Application::session()->client_info);
        }
        if (isset(Application::session()->permission)) {
            unset(Application::session()->permission);
        }

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

    /**
     * 修改密码
     */
    public function modify_password_action()
    {
        if (!$_POST['old_password'])
        {
            H::ajax_json_output(Application::RSM(null, '-1', Application::lang()->_t('请输入当前密码')));
        }

        if ($_POST['password'] != $_POST['re_password'])
        {
            H::ajax_json_output(Application::RSM(null, '-1', Application::lang()->_t('请输入相同的确认密码')));
        }

        if (strlen($_POST['password']) < 6 OR strlen($_POST['password']) > 16)
        {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('密码长度不符合规则')));
        }

        if ($this->model('account')->update_user_password($_POST['old_password'], $_POST['password'], $this->user_id, $this->user_info['salt']))
        {
            H::ajax_json_output(Application::RSM(null, '-1', Application::lang()->_t('密码修改成功, 请牢记新密码')));
        }
        else
        {
            H::ajax_json_output(Application::RSM(null, '-1', Application::lang()->_t('请输入正确的当前密码')));
        }
    }


}

/* EOF */
