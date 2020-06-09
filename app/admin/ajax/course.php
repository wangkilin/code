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

class course extends AdminController
{
    public function setup()
    {
        HTTP::setHeaderNoCache();
    }

    /**
     * 保存教程目录
     */
    public function save_table_action()
    {
        $this->checkPermission(self::IS_ROLE_ADMIN);

        $itemInfo = array();
        if ($_POST['item_sort'] && ($itemIds = explode(',', $_POST['item_sort'])) ) {
            foreach($itemIds as $key => $val) {
                $itemInfo[$val] = array('sort' => $key);
            }
        }

        if (!$_POST['title'] || !$_POST['category_id']) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('请输入参数')));
        }

        if (isset($_POST['id'])) { // 更新教程
            Application::model()->update('course_table', array('title'=>$_POST['title'], 'category_id'=>$_POST['category_id']), 'id =' . intval($_POST['id']));
            H::ajax_json_output(Application::RSM(array('url' => get_js_url('/admin/course/table/')), 1, Application::lang()->_t('教程保存成功')));
        } else { // 添加分类下的教程
            Application::model()->insert('course_table', array('title'=>$_POST['title'], 'category_id'=>$_POST['category_id']));

            H::ajax_json_output(Application::RSM(
                array('url' => get_js_url('/admin/course/table/')),
                1,
                Application::lang()->_t('教程保存成功')));
        }
    }

    /**
     * 批量删除教程数据
     */
    public function remove_table_action()
    {
        $this->checkPermission(self::IS_ROLE_ADMIN);
        if (empty($_POST['id'])) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('请选择教程进行操作')));
        }
        Application::model()->delete('course_table', 'id=' . intval($_POST['id']));

        H::ajax_json_output(Application::RSM(null, 1, null));
    }
}

/* EOF */
