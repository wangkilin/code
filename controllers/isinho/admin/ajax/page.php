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

    /**
     * 设置已读
     */
    public function set_read_action ()
    {
        if (! $this->user_info['uid']) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('没有权限')));
        }
        if (! $_POST['page_id']) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('参数错误')));
        }

        if (! $this->model()->fetch_row('page_read_record', 'page_id = ' . $_POST['page_id'] . ' AND user_id = ' . $this->user_info['uid'])) {
            $this->model()->insert('page_read_record', array('user_id'=>$this->user_info['uid'], 'page_id'=> $_POST['page_id'], 'read_time'=>date('Y-m-d H:i:s')));
        }
        H::ajax_json_output(Application::RSM( null, 1, Application::lang()->_t('完成设置')) );
    }

    /**
     * 设置/取消 置顶
     */
    public function set_top_action ()
    {
        if (! $this->user_info['uid']) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('没有权限')));
        }
        if (! $_POST['page_id']) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('参数错误')));
        }

        $this->model()->update('pages', array('is_top'=>$_POST['top']), 'id='.$_POST['page_id']);

        H::ajax_json_output(Application::RSM( array('url'=>''), 1, Application::lang()->_t('完成设置')) );
    }
}

/* EOF */
