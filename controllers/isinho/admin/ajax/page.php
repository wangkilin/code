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

class page extends AdminController
{
    public function setup()
    {
        HTTP::setHeaderNoCache();
    }

    /**
     * 获取指定页面的附件内容
     */
    public function get_attach_list_action ()
    {
        // 查看权限
        $this->checkPermission(self::IS_ROLE_ADMIN);
        // 根据页面id获取附件列表
        $this->getAttachListByItemTypeAndId('page', $_POST['id']);

        return;
    }
}

/* EOF */
