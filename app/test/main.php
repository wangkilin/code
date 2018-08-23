<?php
class main extends BaseController
{
    public function get_access_rule()
    {
        $rule_action['rule_type'] = 'white';

        $rule_action['actions'][] = 'square';
        $rule_action['actions'][] = 'index';

        return $rule_action;
    }

    public function index_action()
    {
        if ($_GET['notification_id']) {
            $this->model('notify')->read_notification($_GET['notification_id'], $this->user_id);
        }

        // 手机端请求
        if (is_mobile()) {
            HTTP::redirect('/m/course/' . $_GET['id']);
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
        $itemInfo['content'] = FORMAT::parse_attachs(nl2br(FORMAT::parse_bbcode($itemInfo['content'])));
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

        View::set_meta('description', $itemInfo['title'] . ' - ' . cjk_substr(str_replace("\r\n", ' ', strip_tags($itemInfo['message'])), 0, 128, 'UTF-8', '...'));

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
        if ($_FILES) {
            var_dump($_FILES);
        }
        View::assign('article_list', $article_list);

        View::output('test/square');
    }
}
