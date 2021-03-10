<?php
/**
 * +-------------------------------------------+
 * |   iCodeBang CMS [#RELEASE_VERSION#]       |
 * |   by iCodeBang.com Team                   |
 * |   © iCodeBang.com. All Rights Reserved    |
 * |   ------------------------------------    |
 * |   Support: icodebang@126.com              |
 * |   WebSite: http://www.icodebang.com       |
 * +-------------------------------------------+
 */

defined('iCodeBang_Com') OR die('Access denied!');

class cdn extends AdminController
{
    /**
     * 自调用方法， 显示
     */
    public function setup()
    {
        View::assign('menu_list', $this->model('admin')->fetch_menu_list('admin/cdn'));
    }

    /**
     * 文件列表
     */
    public function index_action()
    {
        if ($this->is_post()) {
            foreach ($_POST as $key => $val) {
                if ($key == 'start_date' OR $key == 'end_date') {
                    $val = base64_encode($val);
                }

                if ($key == 'keyword' OR $key == 'user_name') {
                    $val = rawurlencode($val);
                }

                $param[] = $key . '-' . $val;
            }

            H::ajax_json_output(Application::RSM(array(
                'url' => get_js_url('/admin/article/list/' . implode('__', $param))
            ), 1, null));
        }


        //$fileList = $this->getDirContent(WEB_ROOT_DIR . 'uploads');
        $fileList = core_filemanager::getDirContentByPage(WEB_ROOT_DIR . 'uploads', 1, 10000);$fileList = $fileList['files'];

        $url_param = array();
        foreach($_GET as $key => $val) {
            if (!in_array($key, array('app', 'c', 'act', 'page'))) {
                $url_param[] = $key . '-' . $val;
            }
        }

        View::assign('pagination', Application::pagination()->initialize(array(
            'base_url' => get_js_url('/admin/article/list/') . implode('__', $url_param),
            'total_rows' => $search_articles_total,
            'per_page' => $this->per_page
        ))->create_links());

        $this->crumb(Application::lang()->_t('CDN同步'), 'admin/article/list/');


        // 获取用户信息列表,
        $list = $this->model()->fetch_all('cdn_local_root');
        $bucketList = $this->model()->fetch_all('cdn_bucket');
        $bucketId = array_column($bucketList, 'id');
        $bucketList = array_combine($bucketId, $bucketList);
        foreach ($list as & $_item) {
            $_item['path'] = $_item['local_root_path'].' ('.$bucketList[$_item['cdn_bucket_id']]['cdn_name'] . '-' .$bucketList[$_item['cdn_bucket_id']]['bucket_name'].')';
        }
        $defaultItem = current($list);
        $selectModel = $this->model()->select()
                                     ->from($this->model()->get_table('cdn_local_file'), '*')
                                     ->where('file_path = "' . $this->model()->quote($defaultItem['local_root_path']) . '" AND  local_root_id = ' .$defaultItem['id']);
        $result = $this->model()->db()->fetchAll($selectModel);


        View::assign('itemOptions', buildSelectOptions($list, 'path', 'id', $_GET['path_id']) );
        View::assign('list', $fileList);

        View::output('admin/cdn/list');
    }


    /**
     * 递归获取指定目录下的文件
     */
    protected function getDirContent ($dir)
    {
        $fileList = core_filemanager::getDirContentByPage($dir, 1, 100000);
        $allList = $fileList['files'];

        foreach ($fileList['files'] as $_item) {
            if ($_item['type']=='dir') {
                $tmpFileList = $this->getDirContent($_item['name']);
                $allList = array_merge($allList, $tmpFileList);
            }
        }

        return $allList;
    }

}
