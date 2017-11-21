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
        $rule_action['rule_type'] = 'black';
        $rule_action['actions'] = array();

        return $rule_action;
    }

    public function rule_action()
    {
        $this->crumb(Application::lang()->_t('%s 积分规则', get_setting('site_name')));

        if (get_setting('integral_system_enabled') != 'Y')
        {
            H::redirect_msg(Application::lang()->_t('本站未启用积分系统'), '/');
        }

        View::output('integral/rule');
    }
}