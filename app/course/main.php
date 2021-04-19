<?php
class main extends BaseController
{
    /**
     * 做黑名单检查。 不做访问限制
     */
    public function get_access_rule()
    {
        // 黑名单检查
        $rule_action['rule_type'] = 'black';

        //var_dump($this->user_info);exit;
        if ($this->user_info['permission']['visit_site'])
        {
            //$rule_action['actions'][] = 'square';
            //$rule_action['actions'][] = 'index';
        }

        return $rule_action;
    }

    public function go_action ()
    {
        echo 'aa';
    }

    /**
     * 全部分类，流转到这里。 在这里判断分类内容，做后续处理
     * @todo
     */
    public function default_action()
    {
        // 只有管理员权限， 才可以通过id访问
        if (is_numeric($_GET['id']) && $this->hasRolePermission (parent::IS_ROLE_ADMIN | parent::IS_ROLE_MODERATOR)) {
            $itemInfo = $this->model('course')->getById($_GET['id']);
        } else {
            $itemInfo = $this->model('course')->getCourseByToken($_GET['id']);
        }
        // 指定文章没有找到
        if (! $itemInfo) {
            HTTP::error_404();
        }
        // 手机端请求
        if (is_mobile()) {
            //HTTP::redirect('/m/course/' . ACTION .'/id-' . $_GET['id']);
        }
        // 重新赋值 id， 替换可能存在的url_token当作的id
        $_GET['id'] = $itemInfo['id'];
        $_GET['url_token'] = $itemInfo['url_token']=='' ? $itemInfo['id'] : $itemInfo['url_token'];

        // 获取教程所在教案中的教程列表
        $courseIds = array();
        $courseListInTable = array();
        if (isset($_GET['table_id'])) { // 设置了教案id
            $courseListInTable = $this->model()->fetch_all('course_content_table', 'table_id='.intval($_GET['table_id']), 'sort asc');
            $isCourseInTable = false;
            // 检查教程是否和教案对应上
            foreach($courseListInTable as $_tmpItem) {
                if ($_tmpItem['article_id'] == $itemInfo['id']) {
                    $isCourseInTable = true;
                    break;
                }
                if (strpos($_tmpItem['link'], $_SERVER['REQUEST_URI']) ) {
                    $isCourseInTable = true;
                    break;
                }
            }
            // 指定文章没有找到, 没在对应的教案中
            if (! $isCourseInTable) {
                HTTP::error_404();
            }
        } else {
            // 根据文章， 尝试获取对应教案下的教程
            $tableList = $this->model()->fetch_all('course_content_table', 'article_id='.intval($itemInfo['id']));
            if (is_array($tableList) && count($tableList)==1) { // 教程只对应一个教案， 直接获取教案下的全部教程
                $_GET['table_id'] = intval($tableList[0]['table_id']);
                $courseListInTable = $this->model()->fetch_all('course_content_table', 'table_id='. $_GET['table_id'], 'sort asc');
            }
        }
        $prevNextIds = array();
        if ($courseListInTable) { // 教程在教案中，获取教案信息
            $courseIds = $prevNextIds = array_column($courseListInTable, 'article_id');

            $courseTableInfo = $this->model()->fetch_row('course_table', 'id='.intval($courseListInTable[0]['table_id']));
            $courseTableList = array($courseTableInfo);
            // 绑定教案id和教案中的教程列表
            $courseListInTable = array($courseListInTable[0]['table_id'] => $courseListInTable);
        } else { // 教程没有加入到教案中， 获取对应分类下的全部教案和教程
            $courseTableList = $this->model()->fetch_all('course_table', 'category_id='.intval($itemInfo['category_id']), 'sort asc');
            foreach ($courseTableList as $_itemInfo) {
                $_tmpList = $this->model()->fetch_all('course_content_table', 'table_id='.intval($_itemInfo['id']), 'sort asc');
                if ($_tmpList) {
                    $courseListInTable[$_itemInfo['id']] = $_tmpList;
                    $courseIds = $courseIds + array_column($courseListInTable, 'article_id');
                }
            }
        }

        $courseList = count($courseIds)>0 ? $this->model('course')->getByIds($courseIds):array();

        $nextItem = $prevItem = array();
        if (! empty($_GET['table_id']) && isset($courseListInTable[$_GET['table_id']])) {
            $length = count($courseListInTable[$_GET['table_id']]);
            for ($index = 0; $index<$length; $index++) {

                if ($courseListInTable[$_GET['table_id']][$index]['article_id'] == $itemInfo['id']) {
                    $prevKey = $index-1;
                    while($prevKey>=0) {
                        $prevItem = isset($courseListInTable[$_GET['table_id']][$prevKey])
                                && $courseListInTable[$_GET['table_id']][$prevKey]['from_type']!='chapter'
                                ? $courseListInTable[$_GET['table_id']][$prevKey] : null;
                        if ($prevItem) {
                            $prevItem['link']=='' AND $prevItem['link'] = './'.$courseList[$prevItem['article_id']]['url_token'].'.html';
                            break;
                        }
                        $prevKey--;
                    }
                    $nextKey = $index+1;
                    while($nextKey<$length) {
                        $nextItem = isset($courseListInTable[$_GET['table_id']][$nextKey])
                                && $courseListInTable[$_GET['table_id']][$nextKey]['from_type']!='chapter'
                                ? $courseListInTable[$_GET['table_id']][$nextKey] : null;
                        if ($nextItem) {
                            $nextItem['link']=='' AND $nextItem['link'] = './'.$courseList[$nextItem['article_id']]['url_token'].'.html';
                            break;
                        }
                        $nextKey++;
                    }

                    break;
                }
            }
        }

        // 文章有附件
        if ($itemInfo['has_attach']) {
            $itemInfo['attachs'] = $this->model('publish')->getAttachListByItemTypeAndId('course', $itemInfo['id'], 'min');

            $itemInfo['attachs_ids'] = FORMAT::parse_attachs($itemInfo['content'], true);
        }
        // 文章内容做bbc转换
        //$itemInfo['content'] = FORMAT::parse_attachs(FORMAT::parse_bbcode($itemInfo['content']));
        // 查看本人是否为此文章投票
        if ($this->user_id) {
            $itemInfo['vote_info'] = $this->model('article')->getVoteByArticleId('article', $itemInfo['id'], null, $this->user_id);
        }
        // 获取全部投票的用户
        $itemInfo['vote_users'] = $this->model('article')->getVoteUsersByArticleId('article', $itemInfo['id'], 1, 10);

        View::assign('itemInfo', $itemInfo);
        View::assign('courseList', $courseList);
        View::assign('nextItem', $nextItem);
        View::assign('prevItem', $prevItem);

        View::assign('itemList', $courseListInTable);
        View::assign('tableList', $courseTableList);


        //$articleTags = $this->model('tag')->getTagsByArticleIds($itemInfo['id'], 'course');
        if ($articleTags) {
            View::assign('article_topics', $articleTags);
            $tagIds = array_keys($articleTags);
        }

        View::assign('reputation_topics', $this->model('people')->get_user_reputation_topic($itemInfo['user_info']['uid'], $user['reputation'], 5));

        $this->crumb($itemInfo['title'], '/article/' . $itemInfo['id']);

        View::assign('human_valid', human_valid('answer_valid_hour'));

        if ($_GET['item_id']) {
            $comments[] = $this->model('article')->get_comment_by_id($_GET['item_id']);
        } else {
            $comments = $this->model('article')->get_comments($itemInfo['id'], $_GET['page'], 100);
        }

        if ($comments AND $this->user_id) {
            foreach ($comments AS $key => $val) {
                $comments[$key]['vote_info'] = $this->model('article')->getVoteByArticleId('comment', $val['id'], 1, $this->user_id);
                $comments[$key]['message'] = $this->model('question')->parse_at_user($val['message']);

            }
        }

        if ($this->user_id)
        {
            View::assign('user_follow_check', $this->model('follow')->user_follow_check($this->user_id, $itemInfo['uid']));
        }

        View::assign('question_related_list', $this->model('question')->get_related_question_list(null, $itemInfo['title']));

        $this->model('article')->update_views($itemInfo['id']);

        View::assign('comments', $comments);
        View::assign('comments_count', $itemInfo['comments']);

        View::assign('human_valid', human_valid('answer_valid_hour'));

        View::assign('pagination', Application::pagination()->initialize(array(
            'base_url' => get_js_url('/article/id-' . $itemInfo['id']),
            'total_rows' => $itemInfo['comments'],
            'per_page' => 100
        ))->create_links());

        View::set_meta('keywords', implode(',', $this->model('system')->analysis_keyword($itemInfo['title'])));

        View::set_meta('description', $itemInfo['title'] . ' - ' . cjk_substr(str_replace(array("\r","\n", '  '), array('','',' '), strip_tags($itemInfo['content'])), 0, 128, 'UTF-8', '...'));

        View::assign('attach_access_key', md5($this->user_id . time()));

        $recommend_posts = $this->model('posts')->get_recommend_posts_by_topic_ids($article_topic_ids);

        if ($recommend_posts) {
            foreach ($recommend_posts as $key => $value) {
                if ($value['id'] AND $value['id'] == $itemInfo['id']) {
                    unset($recommend_posts[$key]);

                    break;
                }
            }

            View::assign('recommend_posts', $recommend_posts);
        }

        View::output('course/index');
    }

    public function index_action()
    {
        if ($_GET['notification_id'])
        {
            $this->model('notify')->read_notification($_GET['notification_id'], $this->user_id);
        }

        // 手机端请求
        if (is_mobile()) {
            //HTTP::redirect('/m/course/' . $_GET['id']);
        }
        if (is_numeric($_GET['id'])) {
            $itemInfo = $this->model('course')->getById($_GET['id']);
        } else {
            $itemInfo = $this->model('course')->getCourseByToken($_GET['id']);
        }
        // 指定文章没有找到
        if (! $itemInfo) {
            HTTP::error_404();
        }

        // 文章有附件
        if ($itemInfo['has_attach']) {
            $itemInfo['attachs'] = $this->model('publish')->getAttachListByItemTypeAndId('course', $itemInfo['id'], 'min');

            $itemInfo['attachs_ids'] = FORMAT::parse_attachs($itemInfo['content'], true);
        }
        // 文章内容做bbc转换
        $itemInfo['content'] = FORMAT::parse_attachs(FORMAT::parse_bbcode($itemInfo['content']));
        // 查看本人是否为此文章投票
        if ($this->user_id) {
            $itemInfo['vote_info'] = $this->model('article')->getVoteByArticleId('article', $itemInfo['id'], null, $this->user_id);
        }
        // 获取全部投票的用户
        $itemInfo['vote_users'] = $this->model('article')->getVoteUsersByArticleId('article', $itemInfo['id'], 1, 10);

        View::assign('itemInfo', $itemInfo);

        //$articleTags = $this->model('tag')->getTagsByArticleIds($itemInfo['id'], 'course');
        if ($articleTags) {
            View::assign('article_topics', $articleTags);
            $tagIds = array_keys($articleTags);
        }

        View::assign('reputation_topics', $this->model('people')->get_user_reputation_topic($itemInfo['user_info']['uid'], $user['reputation'], 5));

        $this->crumb($itemInfo['title'], '/article/' . $itemInfo['id']);

        View::assign('human_valid', human_valid('answer_valid_hour'));

        if ($_GET['item_id']) {
            $comments[] = $this->model('article')->get_comment_by_id($_GET['item_id']);
        } else {
            $comments = $this->model('article')->get_comments($itemInfo['id'], $_GET['page'], 100);
        }

        if ($comments AND $this->user_id) {
            foreach ($comments AS $key => $val) {
                $comments[$key]['vote_info'] = $this->model('article')->getVoteByArticleId('comment', $val['id'], 1, $this->user_id);
                $comments[$key]['message'] = $this->model('question')->parse_at_user($val['message']);

            }
        }

        if ($this->user_id)
        {
            View::assign('user_follow_check', $this->model('follow')->user_follow_check($this->user_id, $itemInfo['uid']));
        }

        View::assign('question_related_list', $this->model('question')->get_related_question_list(null, $itemInfo['title']));

        $this->model('article')->update_views($itemInfo['id']);

        View::assign('comments', $comments);
        View::assign('comments_count', $itemInfo['comments']);

        View::assign('human_valid', human_valid('answer_valid_hour'));

        View::assign('pagination', Application::pagination()->initialize(array(
            'base_url' => get_js_url('/article/id-' . $itemInfo['id']),
            'total_rows' => $itemInfo['comments'],
            'per_page' => 100
        ))->create_links());

        View::set_meta('keywords', implode(',', $this->model('system')->analysis_keyword($itemInfo['title'])));

        View::set_meta('description', $itemInfo['title'] . ' - ' . cjk_substr(str_replace("\r\n", ' ', strip_tags($itemInfo['content'])), 0, 128, 'UTF-8', '...'));

        View::assign('attach_access_key', md5($this->user_id . time()));

        $recommend_posts = $this->model('posts')->get_recommend_posts_by_topic_ids($article_topic_ids);

        if ($recommend_posts) {
            foreach ($recommend_posts as $key => $value) {
                if ($value['id'] AND $value['id'] == $itemInfo['id']) {
                    unset($recommend_posts[$key]);

                    break;
                }
            }

            View::assign('recommend_posts', $recommend_posts);
        }

        View::output('course/index');
    }

    public function index_square_action()
    {
        if (is_mobile())
        {
            //HTTP::redirect('/m/article/');
        }

        $this->crumb(Application::lang()->_t('教程'), '/course/');

        if ($_GET['category']) { // 通过开发语言分类， 查找教程
            $category_info = $this->getCategoryInfoByIdOrToken($_GET['category']);
        }

        if ($_GET['feature_id']) {
        // 通过专题查找话题（Tags）, 再通过话题找文章
            $article_list = $this->model('article')
                                 ->get_articles_list_by_topic_ids($_GET['page'],
                                                  get_setting('contents_per_page'),
                                                  'add_time DESC',
                                                  $this->model('feature')->get_topics_by_feature_id($_GET['feature_id'])
                                 );

            $article_list_total = $this->model('article')->article_list_total;

            if ($feature_info = $this->model('feature')->get_feature_by_id($_GET['feature_id'])) {
                $this->crumb($feature_info['title'], '/course/feature_id-' . $feature_info['id']);

                View::assign('feature_info', $feature_info);
            }

        } else {
            $article_list = $this->model('article')->get_articles_list($category_info['id'], $_GET['page'], get_setting('contents_per_page'), 'add_time DESC');

            $article_list_total = $this->model('article')->found_rows();
        }

        if ($article_list)
        {
            foreach ($article_list AS $key => $val)
            {
                $article_ids[] = $val['id'];

                $article_uids[$val['uid']] = $val['uid'];
            }

            $article_topics = $this->model('topic')->get_topics_by_item_ids($article_ids, 'article');
            $article_users_info = $this->model('account')->getUsersByIds($article_uids);

            foreach ($article_list AS $key => $val)
            {
                $article_list[$key]['user_info'] = $article_users_info[$val['uid']];
            }
        }

        if ($category_info)
        {
            View::assign('category_info', $category_info);

            $this->crumb($category_info['title'], '/article/category-' . $category_info['id']);

            $meta_description = $category_info['title'];

            if ($category_info['description'])
            {
                $meta_description .= ' - ' . $category_info['description'];
            }

            View::set_meta('description', $meta_description);
        }

        $this->_prepareDataByCheckingTplFile('course/square');

        View::assign('article_list', $article_list);
        View::assign('article_topics', $article_topics);

        View::assign('hot_articles', $this->model('article')->get_articles_list(null, 1, 10, 'votes DESC', 30));

        View::assign('pagination', Application::pagination()->initialize(array(
            'base_url' => get_js_url('/course/category_id-' . $_GET['category_id'] . '__feature_id-' . $_GET['feature_id']),
            'total_rows' => $article_list_total,
            'per_page' => get_setting('contents_per_page')
        ))->create_links());

        View::output('course/square');
    }
}
