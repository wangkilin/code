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
        $rule_action['rule_type'] = 'white'; //黑名单,黑名单中的检查  'white'白名单,白名单以外的检查
        $rule_action['actions'] = array();

        return $rule_action;
    }

    public function index_action()
    {
        if (!$this->user_info['email'])
        {
            H::redirect_msg(Application::lang()->_t('当前帐号没有提供 Email, 此功能不可用'));
        }

        $this->crumb(Application::lang()->_t('邀请好友'), '/invitation/');

        View::output('invitation/index');
    }
}