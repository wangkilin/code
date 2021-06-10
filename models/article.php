<?php
class articleModel extends Model
{
    protected $table = 'article';

    public function getRow ($where)
    {
        $conditions = [];
        foreach ($where AS $key => $val) {
            $conditions[] = '`' . $this->quote($key) . "`='" . $this->quote($val) . "'";
        }
        return  $this->fetch_row($this->table, join(' AND ', $conditions));
    }
    /**
     * 根据文章id获取文章信息
     * @param int $article_id
     * @return Ambigous <boolean, multitype:>
     */
    public function get_article_info_by_id($article_id)
    {
        $articleInfo = $this->getById($article_id, 'article');
        if ($articleInfo) {
            $postInfo = $this->fetch_row('posts_index', 'post_type="article" AND post_id =' . $article_id  );
            is_array($postInfo) AND $articleInfo = array_merge($postInfo, $articleInfo);
        }
        return $articleInfo;
    }

    public function get_article_info_by_ids($article_ids)
    {
        if (!is_array($article_ids) OR sizeof($article_ids) == 0)
        {
            return false;
        }

        array_walk_recursive($article_ids, 'intval_string');

        if ($articles_list = $this->fetch_all('article', 'id IN(' . implode(',', $article_ids) . ')'))
        {
            foreach ($articles_list AS $key => $val)
            {
                $result[$val['id']] = $val;
            }
        }

        return $result;
    }

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
            //'message' => htmlspecialchars($message),
            'message' => $message,
            'category_id' => intval($category_id)
        ), 'id = ' . intval($article_id));

        $this->model('posts')->set_posts_index($article_id, 'article');

        return true;
    }

    public function getListInDiffCategory (array $categoryIdList, $sort = 'id DESC', $limit=null)
    {
        $itemList = array();
        foreach ($categoryIdList as $_id) {
            $_item = $this->fetch_row($this->table, 'category_id = ' . $_id, 'id DESC');
            is_array($_item) && $itemList[$_item['id']] = $_item;
        }
        krsort($itemList);

        return array_slice($itemList, 0, $limit);


        $sql = '(SELECT * FROM ' . $this->get_table() . ' WHERE category_id = %d ORDER BY ' . $sort . ' LIMIT 1)';
        $sqlList = array();
        foreach ($categoryIdList as $_id) {
            $sqlList[] = sprintf($sql, $_id);
        }
        $sql = 'SELECT * FROM ('.join(' UNION ', $sqlList) . ') AS table1 ORDER BY ' . $sort;

        return $this->query_all($sql, $limit, 0, null, null, null);
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

    /**
     * 根据文章id获取文章的投票信息
     * @param string $type 投票的文章分类： article， comments， question， course。。。
     * @param int $id      文章id
     * @param int $rating  赞成票， 还是反对票
     * @param int $uid     投票用户的id
     * @return mixed
     */
    public function getVoteByArticleId($type, $id, $rating = null, $uid = null)
    {
        $vote = false;
        if ($votes = $this->getVoteByArticleIds($type, array($id), $rating, $uid)) {
            $vote = end($votes);
        }

        return $vote;
    }

    /**
     * 根据文章ids获取文章的投票信息
     * @param string $type 投票的文章分类： article， comments， question， course。。。
     * @param array $ids   文章id
     * @param int $rating  赞成票， 还是反对票
     * @param int $uid     投票用户的id
     * @return mixed
     */
    public function getVoteByArticleIds($type, $ids, $rating = null, $uid = null)
    {
        if (! is_array($ids) || sizeof($ids) == 0) {
            return false;
        }
        array_walk_recursive($ids, 'intval');

        $where[] = "`type` = '" . $this->quote($type) . "'";
        $where[] = 'item_id IN(' . implode(',', $ids) . ')';

        if ($rating) {
            $where[] = 'rating = ' . intval($rating);
        }

        if ($uid) {
            $where[] = 'uid = ' . intval($uid);
        }

        $votes = array();
        if ($result = $this->fetch_all('article_vote', implode(' AND ', $where))) {
            foreach ($result AS $val) {
                isset($votes[$val['item_id']]) OR $votes[$val['item_id']] = array();
                $votes[$val['item_id']][$val['item_uid']] = $val;
            }
        }

        return $votes;
    }

    /**
     * 获取文章id获取投票的用户信息
     * @param string $type 投票的文章分类： article， comments， question， course。。。
     * @param int $id      文章id
     * @param int $rating  赞成票， 还是反对票
     * @param int $quantity 获取数量
     */
    public function getVoteUsersByArticleId($type, $item_id, $rating = null, $quantity = null)
    {
        $accounts = $this->getVoteUsersByArticleIds($type, array($item_id), $rating, $quantity);
        if (is_array($accounts)) {
            $accounts = end($accounts);
        }

        return $accounts;
    }

    /**
     * 获取文章ids获取投票的用户信息
     * @param string $type 投票的文章分类： article， comments， question， course。。。
     * @param array $ids   文章id
     * @param int $rating  赞成票， 还是反对票
     * @param int $quantity 获取数量
     */
    public function getVoteUsersByArticleIds($type, $ids, $rating = null, $quantity = null)
    {
        if (! is_array($ids) || count($ids) == 0) {
            return false;
        }
        array_walk_recursive($ids, 'intval_string');

        $where[] = "`type` = '" . $this->quote($type) . "'";
        $where[] = count($ids)==1? ('item_id='.end($ids)) : ('item_id IN(' . implode(',', $ids) . ')');

        if ($rating) {
            $where[] = 'rating = ' . intval($rating);
        }

        $accounts = array();
        if ($article_votes = $this->fetch_all('article_vote', implode(' AND ', $where))) {
            foreach ($article_votes AS $val) {
                $uids[$val['uid']] = $val['uid'];
            }

            $users_info = $this->model('account')->getUsersByIds($uids);

            foreach ($article_votes AS $val) {
                $accounts[$val['item_id']][$val['uid']] = $users_info[$val['uid']];
            }
        }

        return $accounts;
    }
    /**
     *
     */
    public function getRelatedList($title, $limit = 10, $filterOutId=null, $categoryId=null)
    {
        $cache_key = 'article_related_list_' . md5($title) . '_' . $limit;

        if ($list = Application::cache()->get($cache_key)) {
            return $list;
        }

        if ($keywords = $this->model('system')->analysis_keyword($title)) {
            if (sizeof($keywords) <= 1) {
                return false;
            }

            if ($list = $this->query_all($this->model('search_fulltext')->bulid_query('article', 'title', $keywords, 'category_id =' . $categoryId), 500))
            {
                $list = aasort($list, 'score', 'DESC');

                $list = array_slice($list, 0, ($limit + 1));

                foreach ($list as $key => & $val) {
                    if ($val['id'] == $filterOutId) {
                        unset($list[$key]);
                        break;
                    }

                    $val = array(
                        'id'            => $val['id'],
                        'title'         => $val['title'],
                        'category_id'   => $val['category_id'],
                        'url_token'     => $val['url_token']
                    );
                }
            }
        }

        Application::cache()->set($cache_key, $list, get_setting('cache_level_low'));

        return $list;
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
