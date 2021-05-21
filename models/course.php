<?php
/*
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

class courseModel extends Model
{
    static public $courses = array();

    /**
     * 根据ids删除
     * @param array | int $ids 数据条目id列表
     * @return boolean
     */
    public function removeById ($id)
    {
        return $this->removeByIds(array($id));
    }
    /**
     * 根据ids删除
     * @param array | int $ids 数据条目id列表
     * @return boolean
     */
    public function removeByIds ($ids)
    {
        return $this->deleteByIds($ids, 'course');
    }

    /**
     *  根据id获取教程信息
     * @param unknown $id
     * @return boolean|Ambigous <>
     */
    public function getById ($id, $colName='')
    {
        return $this->getCourseById($id);
    }

    /**
     *  根据id获取教程信息
     * @param unknown $id
     * @return boolean|Ambigous <>
     */
    public function getCourseById($id)
    {
        if (!is_digits($id)) {
            return false;
        }

        if (! isset(self::$courses[$id])) {
            self::$courses[$id] = $this->fetch_row('course', 'id = ' . $id);
        }

        return self::$courses[$id];
    }

    /**
     *  根据token获取教程信息
     * @param string $token
     * @return boolean|Ambigous <>
     */
    public function getCourseByToken($token)
    {
        return $this->fetch_row('course', "url_token = '" . $this->quote($token)."'");
    }

    /**
     * 根据教程id列表获取教程列表信息
     * @param array $idsList
     * @return multitype:|multitype:unknown
     */
    public function getByIds ($idsList)
    {
        return $this->getCoursesByIds($idsList);
    }

    /**
     * 根据教程id列表获取教程列表信息
     * @param array $idsList
     * @return multitype:|multitype:unknown
     */
    public function getCoursesByIds($idsList)
    {
        $courses = array();
        if (! is_array($idsList) OR sizeof($idsList) == 0) {
            return $courses;
        }

        array_walk_recursive($idsList, 'intval');

        if ($list = $this->fetch_all('course', 'id IN(' . implode(',', $idsList) . ')')) {
            foreach ($list AS $key => $val) {
                $courses[$val['id']] = self::$courses[$val['id']] = $val;
            }
        }

        return $courses;
    }

    /**
     * 根据条件获取课程列表
     * @param string $where
     * @param string $order
     * @param number $perPage
     * @param number $pageNum
     * @return multitype:unknown
     */
    public function getCourseList ($where=null, $order='id DESC', $perPage=10, $pageNum=0)
    {
        $courseList = array();
        if ($list = $this->fetch_page('course', $where, $order, $pageNum, $perPage)) {
            foreach ($list as $key => $val) {
                $val['url_token']=='' AND $val['url_token'] = $val['id'];
                $courseList[$val['id']] = $val;
            }
        }

        return $courseList;
    }

    /**
     * 根据教程id， 获取对应教案下的教程列表
     *
     * @param int $id 教程id
     *
     * @return array 教程列表
     */
    public function getCourseListInTableByCourseId ($id)
    {
        $courseList = array();

        $list = $this->fetch_all('course_content_table', 'article_id='.intval($id));
        if (is_array($list) && count($list)==1) {
            $courseList = $this->fetch_all('course_content_table', 'table_id='.intval($list[0]['talbe_id']), 'sort asc');
        }

        return $courseList;
    }

    /**
     * 通过id获取上一篇 和 下一篇 信息
     * @param int $id 教程id
     * @param int $tableId [optional] 教案id
     */
    public function getPrevAndNextById ($id, $tableId=null)
    {

    }

    /**
     * 根据id设置课程推荐状态
     * @param int $id
     * @param int $status
     * @return number
     */
    public function setRecommendById ($id, $status)
    {
        $result = $this->update('course', array('is_recommend'=>$status), 'id = ' . intval($id));
        return $result;
    }

    /**
     * 教程文章阅读加1
     * @param int $id
     * @return boolean
     */
    public function addViews ($id)
    {
        settype($id, 'int');
        $cacheKey = 'updateCourseView_' . md5(session_id()) . '_' . $id;

        if (! Application::cache()->get($cacheKey) ) {
            Application::cache()->set($cacheKey, time(), 60);
            $this->increase('course', 'views', 'id = ' . $id, FALSE);
        }

        return true;
    }

    /**
     * 更新文章
     * @param int $id 文章id
     * @param array $data 文章信息
     * @return bool
     */
    public function updateCourse ($id, $data)
    {
        $result = false;
        if ($set = $this->processCourseData($data)) {
            if (isset($set['url_token']) && $set['url_token']==$id) {
                $set['url_token'] = '';
            }
            if (isset($set['tag_names'])) {
                $data['tag_names'] = $set['tag_names'];
                unset($set['tag_names']);
            }
            $result = $this->update('course', $set, 'id = ' . intval($id));

            // 设置了附件， 绑定附件和文章关系
            if ($set['has_attach'] && $data['batchKey']) {
                $this->model('attach')->bindAttachAndItem('course', $id, $data['batchKey']);
            }
        }
        if (! isset($data['tag_names'])) {
            $data['tag_names'] = array();
        } else {
            $data['tag_names'] = array_flip($data['tag_names']);
        }
        $bindTags = $this->model('topic')->getTopicsByArticleId($id, 'course');
        foreach ($bindTags as $_tagInfo) {
            if (! isset($data['tag_names'][$_tagInfo['topic_title']])) {// 删除了这个标签
                $this->model('topic')
                     ->removeTopicItemRelation(Application::user()->get_info('uid'),
                                                $_tagInfo['topic_id'],
                                                $id,
                                                'course');
            } else { // 已经存在的绑定关系，保持， 不用后续处理
                unset($data['tag_names'][$_tagInfo['title']]);
            }
        }
        // 新绑定的标签
        foreach ($data['tag_names'] as $tagName=>$val) {
            $tagId = null;
            if (Application::user()->checkPermission('create_topic')) {
                $tagId = $this->model('topic')->saveTopic($tagName, Application::user()->get_info('uid'));
            }
            if (! empty($tagId)) {
                $this->model('topic')
                     ->setTopicItemRelation(Application::user()->get_info('uid'), $tagId, $id, 'course');
            }
        }

        return $result;
    }

    /**
     * 处理标签数据， 供更新和添加使用
     * @param array $data
     * @return multitype:string NULL
     */
    protected function processCourseData ($data)
    {
        $set = array();
        if (isset($data['title'])) {
            $set['title'] = htmlspecialchars(str_replace(array('-', '/'), '_', $data['title']));
        }
        if (isset($data['title2'])) {
            $set['title2'] = htmlspecialchars(str_replace(array('-', '/'), '_', $data['title2']));
        }
        if (isset($data['url_token'])) {
            $set['url_token'] = str_replace(array('-', '/'), '_', $data['url_token']);
        }
        if (isset($data['content'])) {
            $set['content'] = $data['content'];
        }
        if (isset($data['meta_keyword'])) {
            $set['meta_keyword'] = htmlspecialchars($data['meta_keyword']);
        }
        if (isset($data['category_id'])) {
            $set['category_id'] = intval($data['category_id']);
        }
        if (isset($data['table_id'])) {
            $set['table_id'] = intval($data['table_id']);
        }
        if (isset($data['tag_names'])) {
            $set['tag_names']  = $data['tag_names'];
        }
        if (isset($data['uid'])) {
            $set['uid']  = $data['uid'];
        }
        if (isset($data['banner_id'])) {
            $set['banner_id']  = $data['banner_id'];
        }
        if (isset($data['pic'])) {
            $set['pic']  = $data['pic'];
        }
        if (! empty($data['attach_ids'])) {
            $set['has_attach'] = 1;
        }

        return $set;
    }

    /**
     * 添加新教程
     * @param array $data 教程信息数组
     * @return number
     */
    public function add ($data)
    {
        $id = 0;
        if ($set = $this->processCourseData($data)) {
            $set['add_time'] = time();
            if (isset($set['tag_names'])) {
                $data['tag_names'] = $set['tag_names'];
                unset($set['tag_names']);
            }
            $id = $this->insert('course', $set);

            // 设置了附件， 绑定附件和文章关系
            if ($set['has_attach'] && $data['batchKey']) {
                $this->model('attach')->bindAttachAndItem('course', $id, $data['batchKey']);
            }
        }

        // $this->model('search_fulltext')->push_index('course', $set['title'], $id);
        // $this->model('posts')->set_posts_index($id, 'course');

        // 处理绑定的话题
        if ($id && isset($data['tag_names'])) {
            foreach ($data['tag_names'] as $tagName) {
                $topicId = $this->model('topic')->saveTopic($tagName, $data['uid'], core_user::checkPermission('create_topic'));
                // 绑定话题和教程关系
                $this->model('topic')->setTopicItemRelation($data['uid'], $topicId, $id, 'course');
            }
        }


        return $id;
    }


    /**
     * 更新文章目录
     * @param int $id 文章目录id
     * @param array $data 文章目录信息
     * @return bool
     */
    public function updateContentTable ($id, $data)
    {
        $result = false;
        if ($set = $this->processContentTableData($data)) {
            $result = $this->update('course_content_table', $set, 'id = ' . intval($id));
        }

        return $result;
    }

    /**
     * 处理标签数据， 供更新和添加使用
     * @param array $data
     * @return multitype:string NULL
     */
    protected function processContentTableData ($data)
    {
        $set = array();
        if (isset($data['title'])) {
            $set['title'] = htmlspecialchars(str_replace(array('-', '/'), '_', $data['title']));
        }
        if (isset($data['link'])) {
            $set['link'] = $data['link'];
        }
        if (isset($data['description'])) {
            $set['description'] = htmlspecialchars($data['description']);
        }
        if (isset($data['article_id'])) {
            $set['article_id'] = intval($data['article_id']);
        }
        if (isset($data['category_id'])) { // 所属分类
            $set['category_id'] = intval($data['category_id']);
        }
        if (isset($data['table_id'])) { // 所属教程
            $set['table_id'] = intval($data['table_id']);
        }
        if (isset($data['sort'])) {
            $set['sort'] = intval($data['sort']);
        }
        if (isset($data['parent_id'])) {
            $set['parent_id'] = intval($data['parent_id']);
        }
        if (isset($data['from_type'])) {
            $set['from_type'] = $data['from_type'];
        }

        return $set;
    }

    /**
     * 添加新文章目录
     * @param unknown $data
     * @return number
     */
    public function addContentTable ($data)
    {
        $id = 0;
        if ($set = $this->processContentTableData($data)) {
            $set['add_time'] = time();
            $id = $this->insert('course_content_table', $set);
        }

        return $id;
    }
}
/* EOF */
