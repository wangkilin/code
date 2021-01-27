<?php
/*
+--------------------------------------------------------------------------
|   WeCenter [#RELEASE_VERSION#]
|   ========================================
|   by WeCenter Software
|   © 2011 - 2014 WeCenter. All Rights Reserved
|   http://www.wecenter.com
|   ========================================
|   Support: WeCenter@qq.com
|
+---------------------------------------------------------------------------
*/
// select c.`系列`, p.* from personal_workload p left join company_workload c on p.`系列` = c.`系列` and p.`书名` = c.`书名` and p.`校次` = c.`校次`; ## 匹配公司稿件和个人分配的稿件

/*

select *
from (
    select
        '公司' AS `数据源`,
        c.`id` ,
        c.`序号` ,
        '' AS `责编`,
        c.`发稿日期`,
        c.`回稿日期` AS `日期`,
        c.`系列` ,
        c.`书名` ,
        c.`校次` ,
        '' AS `类别` ,
        '' AS `遍次` ,
        c.`目录` ,
        c.`正文` ,
        c.`目+正千字/页` ,
        c.`答案` ,
        c.`千字/页` ,
        c.`试卷` ,
        c.`试卷千字/页` ,
        c.`试卷答案` ,
        c.`试卷答案千字/页` ,
        c.`课后作业` ,
        c.`课后作业千字/页` ,
        c.`功能册` ,
        c.`功能册千字/页` ,
        c.`功能册答案` ,
        c.`功能册答案千字/页` ,
        c.`难度系数` AS `系数`,
        c.`字数（合计）` ,
        c.`字数（未乘系数）` ,
        2 AS `单价`,
        '' AS `应发金额` ,
        '' AS `考核奖罚比例` ,
        '' AS `考核奖罚金额` ,
        '' AS `实发金额` ,

        c.`备注`
    from company_workload c
    inner join personal_workload p
        on p.`系列` = c.`系列`
        and p.`书名` = c.`书名`
        and p.`校次` = c.`校次`
    group by c.id                     ## 获取分配出去的 公司稿件内容，作为基准，供校验; ## 获取分配出去的 公司稿件内容，作为基准，供校验

    union

    select
        '员工' AS `数据源`,
        p.`id` ,
        '' AS `序号`,
        p.`责编`,
        '' AS `发稿日期`,
        p.`结算日期` AS `日期`,
        p.`系列` ,
        p.`书名` ,
        p.`校次` ,
        p.`类别` ,
        p.`遍次` ,
        p.`目录` ,
        p.`正文` ,
        p.`千字/页` ,
        p.`答案` ,
        p.`答案千字/页` ,
        p.`试卷` ,
        p.`试卷千字/页` ,
        p.`试卷答案` ,
        p.`试卷答案千字/页` ,
        p.`课后作业` ,
        p.`课后作业千字/页` ,
        p.`功能册` ,
        p.`功能册千字/页` ,
        p.`功能册答案` ,
        p.`功能册答案千字/页` ,
        p.`系数` ,
        p.`核算总字数（千）` ,
        p.`核算总字数（千）`/ p.`系数`  AS`字数（未乘系数）` ,
        p.`单价` ,
        p.`应发金额` ,
        p.`考核奖罚比例` ,
        p.`考核奖罚金额` ,
        p.`实发金额` ,
        p.`备注（具体校稿页码）`
    from `personal_workload` p
    ) as union_all
order by
  `系列` ,
  `书名` ,
  `校次` ,
  `类别` ,
  `遍次`
;

# 第一步， 匹配书稿，修正书稿填充的错误
select c.`serial`, p.*
from icb_sinho_employee_workload_copy p
left join icb_sinho_company_workload_copy c
  on p.`serial` = c.`serial`
  and p.`book_name` = c.`book_name`  # 注释掉本行和下一行，判断是否是系列不匹配
  and p.`proofreading_times` = c.`proofreading_times` # 注释掉本行， 判断是否是 书名不匹配
where  p.belong_month = '202012' ; # # 匹配公司稿件和个人分配的稿件


# 第二步， 获取数据:
select * from (
    select
        '公司' AS `数据源`,
        c.`id` ,
        c.id_number `序号` ,
        '' AS `责编`,
        c. delivery_date `发稿日期`,
        c.return_date AS `日期`,
        c.serial `系列` ,
        c.book_name `书名` ,
        c.proofreading_times `校次` ,
        '' AS `类别` ,
        '' AS `遍次` ,
        c.content_table_pages `目录` ,
        c.text_pages `正文` ,
        c.text_table_chars_per_page `目+正千字/页` ,
        c.answer_pages `答案` ,
        c.answer_chars_per_page `千字/页` ,
        c.test_pages `试卷` ,
        c.`test_chars_per_page` `试卷千字/页` ,
        c.test_answer_pages `试卷答案` ,
        c.test_answer_chars_per_page `试卷答案千字/页` ,
        c.exercise_pages `课后作业` ,
        c.exercise_chars_per_page `课后作业千字/页` ,
        c.function_book `功能册` ,
        c.function_book_chars_per_page `功能册千字/页` ,
        c.function_answer `功能册答案` ,
        c.function_answer_chars_per_page `功能册答案千字/页` ,
        c.weight  AS `系数`,
        c.total_chars `字数（合计）` ,
        c.total_chars_without_weight `字数（未乘系数）` ,
        2 AS `单价`,
        '' AS `应发金额` ,
        '' AS `考核奖罚比例` ,
        '' AS `考核奖罚金额` ,
        '' AS `实发金额` ,
        c.remarks `备注`,
        '' AS `状态`

    from icb_sinho_company_workload_copy c
    inner join icb_sinho_employee_workload_copy p
        on      p.`serial` = c.`serial`
            and p.`book_name` = c.`book_name`
            and p.`proofreading_times` = c.`proofreading_times`
    where p.status = 0
    group by c.id                     ## 获取分配出去的 公司稿件内容，作为基准，供校验;

    UNION

    select
        '员工' AS `数据源`,
        p.`id` ,
        '' AS `序号`,
        p.user_name AS `责编`,
        '' AS `发稿日期`,
        p.settlement_date AS `日期`,
        p.serial AS `系列` ,
        p.book_name AS `书名` ,
        p.proofreading_times AS `校次` ,
        p.category AS `类别` ,
        p.working_times AS `遍次` ,
        p.content_table_pages `目录` ,
        p.text_pages `正文` ,
        p.text_table_chars_per_page `千字/页` ,
        p.answer_pages `答案` ,
        p.answer_chars_per_page `答案千字/页` ,
        p.test_pages `试卷` ,
        p.test_chars_per_page `试卷千字/页` ,
        p.test_answer_pages `试卷答案` ,
        p.test_answer_chars_per_page `试卷答案千字/页` ,
        p.exercise_pages `课后作业` ,
        p.exercise_chars_per_page `课后作业千字/页` ,
        p.function_book `功能册` ,
        p.function_book_chars_per_page `功能册千字/页` ,
        p.function_answer `功能册答案` ,
        p.`function_answer_chars_per_page` `功能册答案千字/页` ,
        p.weight `系数` ,
        p.total_chars `核算总字数（千）` ,
        p.total_chars/p.weight AS  `字数（未乘系数）` , # `核算总字数（千）`/ p.`系数`  AS`字数（未乘系数）` ,
        p.price `单价` ,
        p.payable_amount `应发金额` ,
        p.assessment_rate `考核奖罚比例` ,
        p.assessment_amount `考核奖罚金额` ,
        p.actual_amount `实发金额` ,
        p.remarks `备注（具体校稿页码）`,
        case p.status when 1 then '已结算' else '未结算' end AS `状态`
    from `icb_sinho_employee_workload_copy` p2
    join `icb_sinho_employee_workload_copy` p
        on  p.serial = p2.`serial`
            and p.book_name = p2.book_name
            and p.proofreading_times = p2.proofreading_times
    where p2.status = 0

) as union_all
order by
  `系列` ,
  `书名` ,
  `校次` ,
  `类别` ,
  `遍次`,
  `状态` DESC
;


*/

if (!defined('iCodeBang_Com'))
{
    die;
}

class main extends Controller
{
    public function get_access_rule()
    {
        $rule_action['rule_type'] = 'white';

        if ($this->user_info['permission']['visit_question'] AND $this->user_info['permission']['visit_site'])
        {
            $rule_action['actions'][] = 'square';
            $rule_action['actions'][] = 'index';
        }

        return $rule_action;
    }

    public function index_action()
    {
        if ($_GET['notification_id'])
        {
            $this->model('notify')->read_notification($_GET['notification_id'], $this->user_id);
        }

        if (is_mobile())
        {
            HTTP::redirect('/m/article/' . $_GET['id']);
        }
        if(is_numeric($_GET['id'])) {
            $article_info = $this->model('article')->get_article_info_by_id($_GET['id']);
            if ($article_info && $article_info['url_token']!=='') {
                $article_info = null;
            }
        } else {
            $article_info = $this->model('article')->getRow(array('url_token'=>$_GET['id']));
        }

        if (! $article_info) {
            HTTP::error_404();
        }
        $_GET['id'] = $article_info['id'];

        if ($article_info['has_attach'])
        {
            $article_info['attachs'] = $this->model('publish')->getAttachListByItemTypeAndId('article', $article_info['id'], 'min');

            $article_info['attachs_ids'] = FORMAT::parse_attachs($article_info['message'], true);
        }

        $article_info['user_info'] = $this->model('account')->getUserById($article_info['uid'], true);
        if ($article_info['content_type'] != 1) {
            $article_info['message'] = FORMAT::parse_attachs(nl2br(FORMAT::parse_bbcode($article_info['message'])));
        }

        if ($this->user_id)
        {
            $article_info['vote_info'] = $this->model('article')->getVoteByArticleId('article', $article_info['id'], null, $this->user_id);
        }

        $article_info['vote_users'] = $this->model('article')->getVoteUsersByArticleId('article', $article_info['id'], 1, 10);

        View::assign('article_info', $article_info);

        $article_topics = $this->model('topic')->get_topics_by_item_id($article_info['id'], 'article');

        if ($article_topics)
        {
            View::assign('article_topics', $article_topics);

            foreach ($article_topics AS $topic_info)
            {
                $article_topic_ids[] = $topic_info['topic_id'];
            }
        }

        View::assign('reputation_topics', $this->model('people')->get_user_reputation_topic($article_info['user_info']['uid'], $user['reputation'], 5));

        $this->crumb($article_info['title'], '/article/' . $article_info['id']);

        View::assign('human_valid', human_valid('answer_valid_hour'));

        if ($_GET['item_id'])
        {
            $comments[] = $this->model('article')->get_comment_by_id($_GET['item_id']);
        }
        else
        {
            $comments = $this->model('article')->get_comments($article_info['id'], $_GET['page'], 100);
        }

        if ($comments AND $this->user_id)
        {
            foreach ($comments AS $key => $val)
            {
                $comments[$key]['vote_info'] = $this->model('article')->getVoteByArticleId('comment', $val['id'], 1, $this->user_id);
                $comments[$key]['message'] = $this->model('question')->parse_at_user($val['message']);

            }
        }

        if ($this->user_id)
        {
            View::assign('user_follow_check', $this->model('follow')->user_follow_check($this->user_id, $article_info['uid']));
        }

        View::assign('question_related_list', $this->model('question')->get_related_question_list(null, $article_info['title']));

        $this->model('article')->update_views($article_info['id']);

        View::assign('comments', $comments);
        View::assign('comments_count', $article_info['comments']);

        View::assign('human_valid', human_valid('answer_valid_hour'));

        View::assign('pagination', Application::pagination()->initialize(array(
            'base_url' => get_js_url('/article/id-' . $article_info['id']),
            'total_rows' => $article_info['comments'],
            'per_page' => 100
        ))->create_links());

        View::set_meta('keywords', implode(',', $this->model('system')->analysis_keyword($article_info['title'])));

        View::set_meta('description', $article_info['title'] . ' - ' . cjk_substr(str_replace("\r\n", ' ', strip_tags($article_info['message'])), 0, 128, 'UTF-8', '...'));

        View::assign('attach_access_key', md5($this->user_id . time()));

        $recommend_posts = $this->model('posts')->get_recommend_posts_by_topic_ids($article_topic_ids);

        if ($recommend_posts)
        {
            foreach ($recommend_posts as $key => $value)
            {
                if ($value['id'] AND $value['id'] == $article_info['id'])
                {
                    unset($recommend_posts[$key]);

                    break;
                }
            }

            View::assign('recommend_posts', $recommend_posts);
        }

        View::output('article/index');
    }

    public function index_square_action()
    {
        if (is_mobile())
        {
            HTTP::redirect('/m/article/');
        }

        $this->crumb(Application::lang()->_t('文章'), '/article/');

        if ($_GET['category'])
        {
            if (is_digits($_GET['category']))
            {
                $category_info = $this->model('system')->get_category_info($_GET['category']);
            }
            else
            {
                $category_info = $this->model('system')->get_category_info_by_url_token($_GET['category']);
            }
        }

        if ($_GET['feature_id'])
        {
            $article_list = $this->model('article')->get_articles_list_by_topic_ids($_GET['page'], get_setting('contents_per_page'), 'add_time DESC', $this->model('feature')->get_topics_by_feature_id($_GET['feature_id']));

            $article_list_total = $this->model('article')->article_list_total;

            if ($feature_info = $this->model('feature')->get_feature_by_id($_GET['feature_id']))
            {
                $this->crumb($feature_info['title'], '/article/feature_id-' . $feature_info['id']);

                View::assign('feature_info', $feature_info);
            }
        }
        else
        {
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

        // 导航
        if (View::is_output('block/content_nav_menu.php', 'article/square'))
        {
            View::assign('content_nav_menu', $this->model('menu')->getNavMenuWithModuleInLink('article'));
        }

        //边栏热门话题
        if (View::is_output('block/sidebar_hot_topics.php', 'article/square'))
        {
            View::assign('sidebar_hot_topics', $this->model('system')->sidebar_hot_topics($category_info['id']));
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

        View::assign('article_list', $article_list);
        View::assign('article_topics', $article_topics);

        View::assign('hot_articles', $this->model('article')->get_articles_list(null, 1, 10, 'votes DESC', 30));

        View::assign('pagination', Application::pagination()->initialize(array(
            'base_url' => get_js_url('/article/category_id-' . $_GET['category_id'] . '__feature_id-' . $_GET['feature_id']),
            'total_rows' => $article_list_total,
            'per_page' => get_setting('contents_per_page')
        ))->create_links());

        View::output('article/square');
    }
}

/* EOF */
