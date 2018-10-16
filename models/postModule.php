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

class postModuleModel extends Model
{
    protected $table = 'post_module';

    /**
     * 根据指定条件获取话题列表
     * @param string $where
     * @param string $order
     * @param number $limit
     * @param string $page
     * @return Ambigous <multitype:, string>
     */
    public function getModuleList($where = null, $order = 'id DESC', $limit = 10, $page = null)
    {
        $list = array();
        if ($_list = $this->fetch_page($this->table, $where, $order, $page, $limit)) {
            foreach ($_list AS $key => $val) {
                if (!$val['url_token']) {
                    $_list[$key]['url_token'] = rawurlencode($val['title']);
                }
                $list[$_list[$key]['id']] = & $_list[$key];
            }
        }

        return $list;
    }

    /**
     * 通过id获取分类信息
     * @param int $id 分类id
     * @return array
     */
    public function getModuleById ($id)
    {
        static $list = array();

        if (! $list && ($_list = $this->fetch_all($this->table)) ) {
            foreach ($_list AS $key => $val) {
                if (!$val['url_token']) {
                    $val['url_token'] = $val['id'];
                }

                $list[$val['id']] = $val;
            }
        }

        $categoryInfo = isset($list[$id]) ? $list[$id] : array();

        return $categoryInfo;
    }

    /**
     * 通过token获取分类信息
     * @param string $token 分类token
     * @return array
     */
    public function getModuleByToken($token)
    {
        static $list = array();

        if (! $list && ($_list = $this->fetch_all($this->table)) ) {
            foreach ($_list AS $key => $val) {
                if (! $val['url_token']) {
                    $val['url_token'] = $val['id'];
                }

                $list[$val['url_token']] = $val;
            }
        }

        $categoryInfo = isset($list[$token]) ? $list[$token] : array();

        return $categoryInfo;
    }

    /**
     * 更新分类
     * @param int $id 分类id
     * @param array $data 分类数据列表
     * @return number
     */
    public function updateModule($id, $data)
    {
        $id = intval($id);
        $result = false;
        if (! $itemInfo = $this->getModuleById($id)) {
            return $result;
        }
        if ($set = $this->processModuleData($data)) {
            if (isset($set['url_token']) && $set['url_token']==$id) {
                $set['url_token'] = '';
            }
            $result = $this->update($this->table, $set, 'id = ' . $id);

            $uid = Application::user()->get_info('uid');
            if ($uid) {
                // 记录日志
//                 if ($data['title'] AND $data['title'] != $itemInfo['title']) {
//                     ACTION_LOG::save_action($uid, $id, ACTION_LOG::CATEGORY_TOPIC, ACTION_LOG::MOD_TOPIC, $data['title'], $itemInfo['title']);
//                 }

//                 if ($data['pic'] AND $data['pic'] != $itemInfo['pic']){
//                     ACTION_LOG::save_action($uid, $id, ACTION_LOG::CATEGORY_TOPIC, ACTION_LOG::MOD_TOPIC_PIC, $data['pic'], $itemInfo['pic']);
//                 }
            }
        }

        return $result;
    }

    /**
     * 处理分类数据， 供更新和添加使用
     * @param array $data
     * @return multitype:string NULL
     */
    protected function processModuleData ($data)
    {
        $set = array();
        if (isset($data['title'])) {
            $set['title'] = htmlspecialchars(str_replace(array('-', '/'), '_', $data['title']));
        }
        if (isset($data['url_token'])) {
            $set['url_token'] = str_replace(array('-', '/'), '_', $data['url_token']);
        }

        return $set;
    }
    /**
     * 添加postModule
     * @param string $title 标题
     * @param string $urlToken url token
     */
    public function addModule ($title, $urlToken)
    {
        $data = $this->processModuleData (array('title'=>$title, 'url_token'=>$urlToken));
        if($data) {
            return $this->insert($this->table, $data);
        }
        return null;
    }


}

/* EOF */
