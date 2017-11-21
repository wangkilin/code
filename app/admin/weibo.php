<?php
/*
+--------------------------------------------------------------------------
|   WeCenter [#RELEASE_VERSION#]
|   =======
=================================
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

class weibo extends AdminController
{
    public function msg_action()
    {
        $this->crumb(Application::lang()->_t('消息接收'), 'admin/weibo/msg/');

        $services_info = $this->model('openid_weibo_weibo')->get_services_info();

        if ($services_info)
        {
            foreach ($services_info AS $service_info)
            {
                $service_uids[] = $service_info['uid'];
            }

            $service_users_info = $this->model('account')->getUsersByIds($service_uids);

            View::assign('service_users_info', $service_users_info);
        }

        $tmp_service_users_info = Application::cache()->get('tmp_service_account');

        if ($tmp_service_users_info)
        {
            View::assign('tmp_service_users_info', $tmp_service_users_info);
        }

        View::assign('published_user', get_setting('weibo_msg_published_user'));

        View::assign('menu_list', $this->model('admin')->fetch_menu_list('weibo/msg'));

        View::output('admin/weibo/msg');
    }
}
