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

class categoryModel extends Model
{
    protected $table = 'category';

    /**
     * 根据指定条件获取话题列表
     * @param string $where
     * @param string $order
     * @param number $limit
     * @param string $page
     * @return Ambigous <multitype:, string>
     */
    public function getCategoryList($where = null, $order = 'id DESC', $limit = 10, $page = null)
    {
        $list = array();
        if ($_list = $this->fetch_page($this->table, $where, $order, $page, $limit)) {
            foreach ($_list AS $key => $val) {
                if (!$val['url_token']) {
                    $_list[$key]['url_token'] = rawurlencode($val['title']);
                }
                $list[$_list[$key]['id']] = $_list[$key];
            }
        }

        return $list;
    }

    public function update_category_info($category_id, $title, $parent_id, $url_token)
    {
        return $this->update('category', array(
            'title' => htmlspecialchars($title),
            'parent_id' => intval($parent_id),
            'url_token' => $url_token
        ), 'id = ' . intval($category_id));
    }

    public function set_category_sort($category_id, $sort)
    {
        return $this->update('category', array(
            'sort' => intval($sort)
        ), 'id = ' . intval($category_id));
    }

    public function add_category($type, $title, $parent_id)
    {
        return $this->insert('category', array(
            'type' => $type,
            'title' => $title,
            'parent_id' => intval($parent_id),
        ));
    }

    public function delete_category($type, $category_id)
    {
        $childs = $this->model('system')->fetch_category_data($type, $category_id);

        if ($childs)
        {
            foreach($childs as $key => $val)
            {
                $this->delete_category($type, $val['id']);
            }
        }

        $this->delete('reputation_category', 'category_id = ' . intval($category_id));

        $this->delete('nav_menu', "type = 'category' AND type_id = " . intval($category_id));

        return $this->delete('category', 'id = ' . intval($category_id));
    }

    public function contents_exists($category_id)
    {
        if ($this->fetch_one('question', 'question_id', 'category_id = ' . intval($category_id)) OR $this->fetch_one('article', 'id', 'category_id = ' . intval($category_id)))
        {
            return true;
        }
    }

    public function check_url_token($url_token, $category_id)
    {
        return $this->count('category', "url_token = '" . $this->quote($url_token) . "' AND id != " . intval($category_id));
    }

    public function move_contents($from_id, $target_id)
    {
        if (!$from_id OR !$target_id)
        {
            return false;
        }

        $this->update('question', array(
            'category_id' => intval($target_id)
        ), 'category_id = ' . intval($from_id));

        $this->update('article', array(
            'category_id' => intval($target_id)
        ), 'category_id = ' . intval($from_id));

        $this->update('posts_index', array(
            'category_id' => intval($target_id)
        ), 'category_id = ' . intval($from_id));
    }



    /**
     * 通过id获取分类信息
     * @param int $id 分类id
     * @return array
     */
    public function getCategoryById ($id)
    {
        static $list = array();

        if (! $list && ($_list = $this->fetch_all('category')) ) {
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
    public function getCategoryByToken($token)
    {
        static $list = array();

        if (! $list && ($_list = $this->fetch_all('category')) ) {
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
    public function updateCategory($id, $data)
    {
        $id = intval($id);
        $result = false;
        if (! $itemInfo = $this->getCategoryById($id)) {
            return $result;
        }
        if ($set = $this->processCategoryData($data)) {
            if (isset($set['url_token']) && $set['url_token']==$id) {
                $set['url_token'] = '';
            }
            $result = $this->update('category', $set, 'id = ' . $id);

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
    protected function processCategoryData ($data)
    {
        $set = array();
        if (isset($data['title'])) {
            $set['title'] = htmlspecialchars(str_replace(array('-', '/'), '_', $data['title']));
        }
        if (isset($data['pic'])) {
            $set['pic'] = htmlspecialchars($data['pic']);
        }
        if (isset($data['type'])) {
            $set['type'] = $data['type'];
        }
        if (isset($data['meta_words'])) {
            $set['meta_words'] = htmlspecialchars($data['meta_words']);
        }
        if (isset($data['views'])) {
            $set['views'] = intval($data['views']);
        }
        if (isset($data['sort'])) {
            $set['sort'] = intval($data['sort']);
        }
        if (isset($data['parent_id'])) {
            $set['parent_id'] = intval($data['parent_id']);
        }
        if (isset($data['url_token'])) {
            $set['url_token'] = str_replace(array('-', '/'), '_', $data['url_token']);
        }

        return $set;
    }

    /**
     * 分类阅读加1
     * @param int $id
     * @return boolean
     */
    public function addViews ($id)
    {
        settype($id, 'int');
        $cacheKey = 'updateCategoryView_' . md5(session_id()) . '_' . $id;

        if (! Application::cache()->get($cacheKey) ) {
            Application::cache()->set($cacheKey, time(), 60);
            $this->increase('category', 'views', 'id = ' . $id, false);
        }

        return true;
    }

    /**
     * 获取分类列表
     */
    public function getCategoryListByType($type)
    {
        $categoryList = array();

        $categoryAll = $this->fetch_all('category', '`type` = \'' . $this->quote($type) . '\'', 'id ASC');

        foreach($categoryAll as $key => $val) {
            if (!$val['url_token']) {
                $val['url_token'] = $val['id'];
            }

            $categoryList[$val['id']] = $val;
        }

        return $categoryList;
    }

    /**
     * 获取全部分类
     * @param string $bindKey 按照哪个键值返回数组
     */
    public function getAllCategories ($bindKey=null, $type=null)
    {
        static $categoryList = null;
        if (! is_array($categoryList) ) {
            $categoryList = $this->fetch_all('category', null, 'id ASC');
        }
        if ($bindKey && $categoryList) {
            $keys = array_column($categoryList, $bindKey);

            return array_combine($keys, $categoryList);
        }

        return $categoryList;
    }

    /**
     * 获取指定分类下的子集id和本身id列表
     *
     * @param int $categoryId 分类id
     * @return array 分类id列表
     */
    public function getCategoryAndChildIds($categoryId)
    {
        $ids= array(intval($categoryId));
        $categoryList = $this->getAllCategories('id');
        if(isset($categoryList[$categoryId])) {
            $path = $categoryList[$categoryId]['path'] . $categoryId . '/';
            foreach ($categoryList as $_item) {
                if (strpos($_item['path'], $path)===0) {
                    $ids [] = $_item['id'];
                }
            }
        }

        return $ids;
    }


}

/* EOF */
