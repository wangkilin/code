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

    public function login_qr_code_action()
    {
        include(INC_PATH . 'Services/phpqrcode/qrlib.php');
		
        QRcode::png($this->model('openid_weixin_weixin')->get_oauth_url(get_js_url('/m/weixin/qr_login/token-' . $this->model('openid_weixin_weixin')->request_client_login_token(session_id())), 'snsapi_userinfo', 'OAUTH_REDIRECT'), null, QR_ECLEVEL_L, 4);
    }
}
