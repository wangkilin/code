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
class zcourse{



    public function get_comment_by_id($comment_id)
    {
        if ($comment = $this->fetch_row('article_comments', 'id = ' . intval($comment_id)))
        {
            $comment_user_infos = $this->model('account')->getUsersByIds(array(
                $comment['uid'],
                $comment['at_uid']
            ));

            $comment['user_info'] = $comment_user_infos[$comment['uid']];
            $comment['at_user_info'] = $comment_user_infos[$comment['at_uid']];
        }

        return $comment;
    }

    public function get_comments_by_ids($comment_ids)
    {
        if (!is_array($comment_ids) OR !$comment_ids)
        {
            return false;
        }

        array_walk_recursive($comment_ids, 'intval_string');

        if ($comments = $this->fetch_all('article_comments', 'id IN (' . implode(',', $comment_ids) . ')'))
        {
            foreach ($comments AS $key => $val)
            {
                $article_comments[$val['id']] = $val;
            }
        }

        return $article_comments;
    }

    public function get_comments($article_id, $page, $per_page)
    {
        if ($comments = $this->fetch_page('article_comments', 'article_id = ' . intval($article_id), 'add_time ASC', $page, $per_page))
        {
            foreach ($comments AS $key => $val)
            {
                $comment_uids[$val['uid']] = $val['uid'];

                if ($val['at_uid'])
                {
                    $comment_uids[$val['at_uid']] = $val['at_uid'];
                }
            }

            if ($comment_uids)
            {
                $comment_user_infos = $this->model('account')->getUsersByIds($comment_uids);
            }

            foreach ($comments AS $key => $val)
            {
                $comments[$key]['user_info'] = $comment_user_infos[$val['uid']];
                $comments[$key]['at_user_info'] = $comment_user_infos[$val['at_uid']];
            }
        }

        return $comments;
    }

    public function remove_article($article_id)
    {
        if (!$article_info = $this->get_article_info_by_id($article_id))
        {
            return false;
        }

        $this->delete('article_comments', "article_id = " . intval($article_id)); // 删除关联的回复内容

        $this->delete('topic_relation', "`type` = 'article' AND item_id = " . intval($article_id));        // 删除话题关联

        ACTION_LOG::delete_action_history('associate_type = ' . ACTION_LOG::CATEGORY_QUESTION . ' AND associate_action IN(' . ACTION_LOG::ADD_ARTICLE . ', ' . ACTION_LOG::ADD_AGREE_ARTICLE . ', ' . ACTION_LOG::ADD_COMMENT_ARTICLE . ') AND associate_id = ' . intval($article_id));    // 删除动作

        // 删除附件
        if ($attachs = $this->model('publish')->getAttachListByItemTypeAndId('article', $article_id))
        {
            foreach ($attachs as $key => $val)
            {
                $this->model('publish')->remove_attach($val['id'], $val['access_key']);
            }
        }

        $this->model('notify')->delete_notify('model_type = 8 AND source_id = ' . intval($article_id));    // 删除相关的通知

        $this->model('posts')->remove_posts_index($article_id, 'article');

        $this->shutdown_update('users', array(
            'article_count' => $this->count('article', 'uid = ' . intval($uid))
        ), 'uid = ' . intval($uid));

        return $this->delete('article', 'id = ' . intval($article_id));
    }

    public function remove_comment($comment_id)
    {
        $comment_info = $this->get_comment_by_id($comment_id);

        if (!$comment_info)
        {
            return false;
        }

        $this->delete('article_comments', 'id = ' . $comment_info['id']);

        $this->update('article', array(
            'comments' => $this->count('article_comments', 'article_id = ' . $comment_info['article_id'])
        ), 'id = ' . $comment_info['article_id']);

        return true;
    }

    public function update_article($article_id, $uid, $title, $message, $topics, $category_id, $create_topic)
    {
        if (!$article_info = $this->model('article')->get_article_info_by_id($article_id))
        {
            return false;
        }

        $this->delete('topic_relation', 'item_id = ' . intval($article_id) . " AND `type` = 'article'");

        if (is_array($topics))
        {
            foreach ($topics as $key => $topic_title)
            {
                $topic_id = $this->model('topic')->saveTopic($topic_title, $uid, $create_topic);

                $this->model('topic')->setTopicItemRelation($uid, $topic_id, $article_id, 'article');
            }
        }

        $this->model('search_fulltext')->push_index('article', htmlspecialchars($title), $article_info['id']);

        $this->update('article', array(
            'title' => htmlspecialchars($title),
            'message' => htmlspecialchars($message),
            'category_id' => intval($category_id)
        ), 'id = ' . intval($article_id));

        $this->model('posts')->set_posts_index($article_id, 'article');

        return true;
    }

    public function get_articles_list($category_id, $page, $per_page, $order_by, $day = null)
    {
        $where = array();

        if ($category_id)
        {
            $where[] = 'category_id = ' . intval($category_id);
        }

        if ($day)
        {
            $where[] = 'add_time > ' . (time() - $day * 24 * 60 * 60);
        }

        return $this->fetch_page('article', implode(' AND ', $where), $order_by, $page, $per_page);
    }

    public function get_articles_list_by_topic_ids($page, $per_page, $order_by, $topic_ids)
    {
        if (!$topic_ids)
        {
            return false;
        }

        if (!is_array($topic_ids))
        {
            $topic_ids = array(
                $topic_ids
            );
        }

        array_walk_recursive($topic_ids, 'intval_string');

        $result_cache_key = 'article_list_by_topic_ids_' . md5(implode('_', $topic_ids) . $order_by . $page . $per_page);

        $found_rows_cache_key = 'article_list_by_topic_ids_found_rows_' . md5(implode('_', $topic_ids) . $order_by . $page . $per_page);

        if (!$result = Application::cache()->get($result_cache_key) OR $found_rows = Application::cache()->get($found_rows_cache_key))
        {
            $topic_relation_where[] = '`topic_id` IN(' . implode(',', $topic_ids) . ')';
            $topic_relation_where[] = "`type` = 'article'";

            if ($topic_relation_query = $this->query_all("SELECT item_id FROM " . get_table('topic_relation') . " WHERE " . implode(' AND ', $topic_relation_where)))
            {
                foreach ($topic_relation_query AS $key => $val)
                {
                    $article_ids[$val['item_id']] = $val['item_id'];
                }
            }

            if (!$article_ids)
            {
                return false;
            }

            $where[] = "id IN (" . implode(',', $article_ids) . ")";
        }


        if (!$result)
        {
            $result = $this->fetch_page('article', implode(' AND ', $where), $order_by, $page, $per_page);

            Application::cache()->set($result_cache_key, $result, get_setting('cache_level_high'));
        }


        if (!$found_rows)
        {
            $found_rows = $this->found_rows();

            Application::cache()->set($found_rows_cache_key, $found_rows, get_setting('cache_level_high'));
        }

        $this->article_list_total = $found_rows;

        return $result;
    }

    public function lock_article($article_id, $lock_status = true)
    {
        return $this->update('article', array(
            'lock' => intval($lock_status)
        ), 'id = ' . intval($article_id));
    }

    public function article_vote($type, $item_id, $rating, $uid, $reputation_factor, $item_uid)
    {
        $this->delete('article_vote', "`type` = '" . $this->quote($type) . "' AND item_id = " . intval($item_id) . ' AND uid = ' . intval($uid));

        if ($rating)
        {
            if ($article_vote = $this->fetch_row('article_vote', "`type` = '" . $this->quote($type) . "' AND item_id = " . intval($item_id) . " AND rating = " . intval($rating) . ' AND uid = ' . intval($uid)))
            {
                $this->update('article_vote', array(
                    'rating' => intval($rating),
                    'time' => time(),
                    'reputation_factor' => $reputation_factor
                ), 'id = ' . intval($article_vote['id']));
            }
            else
            {
                $this->insert('article_vote', array(
                    'type' => $type,
                    'item_id' => intval($item_id),
                    'rating' => intval($rating),
                    'time' => time(),
                    'uid' => intval($uid),
                    'item_uid' => intval($item_uid),
                    'reputation_factor' => $reputation_factor
                ));
            }
        }

        switch ($type)
        {
            case 'article':
                $this->update('article', array(
                    'votes' => $this->count('article_vote', "`type` = '" . $this->quote($type) . "' AND item_id = " . intval($item_id) . " AND rating = 1")
                ), 'id = ' . intval($item_id));

                switch ($rating)
                {
                    case 1:
                        ACTION_LOG::save_action($uid, $item_id, ACTION_LOG::CATEGORY_QUESTION, ACTION_LOG::ADD_AGREE_ARTICLE);
                    break;

                    case -1:
                        ACTION_LOG::delete_action_history('associate_type = ' . ACTION_LOG::CATEGORY_QUESTION . ' AND associate_action = ' . ACTION_LOG::ADD_AGREE_ARTICLE . ' AND uid = ' . intval($uid) . ' AND associate_id = ' . intval($item_id));
                    break;
                }
            break;

            case 'comment':
                $this->update('article_comments', array(
                    'votes' => $this->count('article_vote', "`type` = '" . $this->quote($type) . "' AND item_id = " . intval($item_id) . " AND rating = 1")
                ), 'id = ' . intval($item_id));
            break;
        }

        $this->model('account')->sum_user_agree_count($item_uid);

        return true;
    }

    public function getVoteByArticleId($type, $item_id, $rating = null, $uid = null)
    {
        if ($article_vote = $this->getVoteByArticleIds($type, array(
            $item_id
        ), $rating, $uid))
        {
            return end($article_vote[$item_id]);
        }
    }

    public function getVoteByArticleIds($type, $item_ids, $rating = null, $uid = null)
    {
        if (!is_array($item_ids))
        {
            return false;
        }

        if (sizeof($item_ids) == 0)
        {
            return false;
        }

        array_walk_recursive($item_ids, 'intval_string');

        $where[] = "`type` = '" . $this->quote($type) . "'";
        $where[] = 'item_id IN(' . implode(',', $item_ids) . ')';

        if ($rating)
        {
            $where[] = 'rating = ' . intval($rating);
        }

        if ($uid)
        {
            $where[] = 'uid = ' . intval($uid);
        }

        if ($article_votes = $this->fetch_all('article_vote', implode(' AND ', $where)))
        {
            foreach ($article_votes AS $key => $val)
            {
                $result[$val['item_id']][] = $val;
            }
        }

        return $result;
    }

    public function getVoteUsersByArticleId($type, $item_id, $rating = null, $limit = null)
    {
        $where[] = "`type` = '" . $this->quote($type) . "'";
        $where[] = 'item_id = ' . intval($item_id);

        if ($rating)
        {
            $where[] = 'rating = ' . intval($rating);
        }

        if ($article_votes = $this->fetch_all('article_vote', implode(' AND ', $where)))
        {
            foreach ($article_votes AS $key => $val)
            {
                $uids[$val['uid']] = $val['uid'];
            }

            return $this->model('account')->getUsersByIds($uids);
        }
    }

    public function getVoteUsersByArticleIds($type, $item_ids, $rating = null, $limit = null)
    {
        if (! is_array($item_ids))
        {
            return false;
        }

        if (sizeof($item_ids) == 0)
        {
            return false;
        }

        array_walk_recursive($item_ids, 'intval_string');

        $where[] = "`type` = '" . $this->quote($type) . "'";
        $where[] = 'item_id IN(' . implode(',', $item_ids) . ')';

        if ($rating)
        {
            $where[] = 'rating = ' . intval($rating);
        }

        if ($article_votes = $this->fetch_all('article_vote', implode(' AND ', $where)))
        {
            foreach ($article_votes AS $key => $val)
            {
                $uids[$val['uid']] = $val['uid'];
            }

            $users_info = $this->model('account')->getUsersByIds($uids);

            foreach ($article_votes AS $key => $val)
            {
                $vote_users[$val['item_id']][$val['uid']] = $users_info[$val['uid']];
            }

            return $vote_users;
        }
    }

    public function update_views($article_id)
    {
        if (Application::cache()->get('update_views_article_' . md5(session_id()) . '_' . intval($article_id)))
        {
            return false;
        }

        Application::cache()->set('update_views_article_' . md5(session_id()) . '_' . intval($article_id), time(), 60);

        $this->shutdown_query("UPDATE " . $this->get_table('article') . " SET views = views + 1 WHERE id = " . intval($article_id));

        return true;
    }

    public function set_recommend($article_id)
    {
        $this->update('article', array(
            'is_recommend' => 1
        ), 'id = ' . intval($article_id));

        $this->model('posts')->set_posts_index($article_id, 'article');
    }

    public function unset_recommend($article_id)
    {
        $this->update('article', array(
            'is_recommend' => 0
        ), 'id = ' . intval($article_id));

        $this->model('posts')->set_posts_index($article_id, 'article');
    }
}
