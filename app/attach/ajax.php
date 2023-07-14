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

class ajax extends BaseController
{
    public function get_access_rule()
    {
        $rule_action['rule_type'] = 'white';
        $rule_action['actions'] = array();

        return $rule_action;
    }

    public function setup()
    {
        HTTP::setHeaderNoCache();
    }

    /**
     * 处理上传的教程附件
     */
    public function upload_attach_action ()
    {
        $filename = 'upload_file';
        $batchKey = $_GET['batchKey'];
        $module   = isset($_GET['type']) ? $_GET['type'] : 'no_module_name';

        return $this->processUploadAttach($module, $batchKey, $filename);
    }

    /**
     * 获取教程附件
     */
    public function get_attach_list_action ()
    {
        // 根据模块类型和模块文章id获取附件列表
        $this->getAttachListByItemTypeAndId($_POST['type'], $_POST['id']);
    }
}
