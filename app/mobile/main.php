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

    public function setup()
    {
        HTTP::redirect('/m/');
    }

    public function index_action()
    {
        HTTP::redirect('/m/');
    }
}