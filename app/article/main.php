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


if (!defined('iCodeBang_Com'))
{
    die;
}

class main extends BaseController
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

    public function index_action_bak()
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

    public function index_action()
    {
        // 移动端请求， 重定向到移动页面
        mobileRedirect('/m/article/'. $_GET['id']);

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

        if ($article_info['has_attach']) {
            $article_info['attachs'] = $this->model('publish')->getAttachListByItemTypeAndId('article', $article_info['id'], 'min');

            $article_info['attachs_ids'] = FORMAT::parse_attachs($article_info['message'], true);
        }

        $this->crumb($article_info['title'], '/article/' . $article_info['id']);
        View::assign('article_info', $article_info);

        //$this->model('article')->update_views($article_info['id']);

        View::assign('human_valid', human_valid('answer_valid_hour'));

        View::set_meta('keywords', implode(',', $this->model('system')->analysis_keyword($article_info['title'])));

        View::set_meta('description', $article_info['title'] . ' - ' . cjk_substr(str_replace("\r\n", ' ', strip_tags($article_info['message'])), 0, 128, 'UTF-8', '...'));

        View::assign('attach_access_key', md5($this->user_id . time()));

        // 根据标题搜索相关文章
        //$relatedList = $this->model('article')->getRelatedList($article_info['title'], 20, $article_info['id'], $article_info['category_id']);
        $relatedList = array();
        View::assign('recommend_posts', $relatedList);

        $latestArticleList = $this->model('article')->get_articles_list($article_info['category_id'], 1, 20, 'id DESC');
        View::assign('latestArticleList', $latestArticleList);

        View::output('article/index');
    }

    /**
     * 显示分类文章列表
     */
    public function showDataInCategory ($categoryInfo)
    {
        $cache_key = preg_replace('/[^a-z_0-9]/i', '_',$_SERVER['HTTP_HOST'] . '_'.MODULE . '_' . __CLASS__ . '_' . __FUNCTION__ . '_' .$categoryInfo['id']);
        if (empty($_GET['doGenerateCache']) && ($pageContent = Application::cache()->get($cache_key) )) {
            View::assign('mainContent', $pageContent);
            View::output('global/cache_show.php');
            return;
        }

        View::assign('category_info', $categoryInfo);

        $this->crumb($categoryInfo['title'], '/category-' . $categoryInfo['id']);
        // 组装meta关键字
        $meta_description = $categoryInfo['title'];
        if ($categoryInfo['meta_words']){
            $meta_description .= ' - ' . $categoryInfo['meta_words'];
        }

        View::set_meta('description', $meta_description);

        $this->per_page = 24;
        $allCategoryList = $this->model('category')->getCategoryAndChildIds();
        $categoryList = array($categoryInfo['id'] => $allCategoryList[$categoryInfo['id']]);
        //var_dump($categoryList);

        View::assign('listColClass', 'col-sm-6 col-xs-12 nopadding');
        View::assign('show_image', true);
        foreach ($categoryList as & $_itemInfo) {
            $_itemInfo['category_ids'] = $_itemInfo['childIds'];
            if ($_itemInfo['category_ids']) {
                $_itemInfo['category_ids'] = array_unique($_itemInfo['category_ids']);
                View::assign('posts_list', $this->model('posts')->get_posts_list(null, $_GET['page'], get_setting('contents_per_page') * 0 + $this->per_page, $_GET['sort_type'], null, $_itemInfo['category_ids']) );
                $_itemInfo['posts_list'] = View::output('block/post_title_list_with_category', false);
                $_itemInfo['course_table_list'] = $this->model('course')->fetch_all('course_table', 'category_id IN ('.join(',', $_itemInfo['category_ids']).')');
            }
        }

        $pagination = Application::pagination()->initialize(array(
            'base_url' => get_js_url('/category-' . $categoryInfo['url_token']),
            'total_rows' => $this->model('posts')->get_posts_list_total(),
            'per_page' => $this->per_page,
        ))->create_links();


        $courseFirstPageList = $this->model('course')->fetch_all('course_content_table', '`sort` IN (0,1)', 'sort DESC');
        $tableIds = array_column($courseFirstPageList, 'table_id');
        $courseFirstPageList = array_combine($tableIds, $courseFirstPageList);
        View::assign('courseFirstPageList', $courseFirstPageList);


        View::assign('itemList', $categoryList);
        View::assign('categoryList', $allCategoryList);
        View::assign('listColClass', 'col-sm-6 col-xs-12 nopadding');
        View::assign('show_image', true);

        View::import_css('isinho.com/owl.theme.default.css');
        View::import_css('isinho.com/owl.carousel.min.css');
        View::import_js('isinho.com/owl.carousel.min.js');

        View::assign('pagination', $pagination);

        //return View::output('index/show_data_in_category', false);
        //return;

        $pageContent = View::output('index/show_data_in_category_no_head_foot', false);
        Application::cache()->set($cache_key, $pageContent .'<!-- cached ' . date('Y-m-d H:i:s') . ' -->', get_setting('cache_level_low'));
        View::assign('mainContent', $pageContent);

        return View::output('global/cache_show.php', false);
    }

    public function index_square_action()
    {
        // 移动端请求， 重定向到移动页面
        mobileRedirect('/m/article/');

        $this->crumb(Application::lang()->_t('文章'), '/article/');



        View::import_css('isinho.com/owl.theme.default.css');
        View::import_css('isinho.com/owl.carousel.min.css');
        View::import_js('isinho.com/owl.carousel.min.js');

        // 获取指定分类下的文章数据
        if ($_GET['category']) {
            if (is_digits($_GET['category'])) {
                $category_info = $this->model('system')->get_category_info($_GET['category']);
            }
            else {
                $category_info = $this->model('system')->get_category_info_by_url_token($_GET['category']);
            }
            $this->categoryInfo = $category_info;

            $pageContent = $this->showDataInCategory($category_info);
            echo $pageContent;
            return;

        } else {
            $cache_key = str_replace(array('.',':'), '_',$_SERVER['HTTP_HOST']) . 'website_channel_page_article';
            if (empty($_GET['doGenerateCache']) && ($pageContent = Application::cache()->get($cache_key) )) {
                View::assign('mainContent', $pageContent);
                View::output('global/cache_show.php');
                return;
            }

            $siteConfig = Application::config()->get('www.icodebang.com.inc');
            $topCategoryList = $siteConfig->top5_language_ids;
            $showCategoryList = $siteConfig->show_category_list;

            $allCategoryList = $this->model('category')->getCategoryAndChildIds();
            $categoryList = array(); $categoryIds = array();
            foreach ($topCategoryList as $_categoryId) {
                $categoryList[$_categoryId] = $allCategoryList[$_categoryId];
                $categoryIds = array_merge($categoryIds, $allCategoryList[$_categoryId]['childIds']);
            }
            //var_dump(array_diff(array_keys($allCategoryList), $categoryIds ) ) ;
            View::assign('listColClass', 'col-sm-12 col-xs-12 nopadding');
            View::assign('show_image', true);

            foreach ($categoryList as & $_itemInfo) {
                $_itemInfo['category_ids'] = $_itemInfo['childIds'];
                $_itemInfo['category_ids'] = array_unique($_itemInfo['category_ids']);
                View::assign('posts_list', $this->model('posts')->get_posts_list(null, $_GET['page'], get_setting('contents_per_page') * 0 + 12, $_GET['sort_type'], null, $_itemInfo['category_ids']) );
                $_itemInfo['posts_list'] = View::output('block/post_title_list_with_category', false);
                //$_itemInfo['course_table_list'] = $this->model('course')->fetch_all('course_table', 'category_id IN ('.join(',', $_itemInfo['category_ids']).')');
            }

            View::assign('showCategoryList', $showCategoryList);
            View::assign('categoryList', $allCategoryList);
            View::assign('itemList', $categoryList);



            //$posts_list = $this->model('posts')->get_posts_list(null, $_GET['page'], get_setting('contents_per_page'), $_GET['sort_type'], null, $category_info['id'], $_GET['answer_count'], $_GET['day'], $_GET['is_recommend']);
            $posts_list = $this->model('article')->getListInDiffCategory($showCategoryList, 'id DESC', 14);
            foreach ($posts_list as & $_itemInfo) {
                $_itemInfo['post_type'] = 'article';
                $_itemInfo['category_info'] = $allCategoryList[$_itemInfo['category_id']];
            }

            $where = array();
            $articleIds = array_column($posts_list, 'id');
            $where[] = '(item_type="article" AND item_id IN (' . join(',', $articleIds) . ') )';
            $itemList = $this->model('attach')->fetch_all('', join(' OR ', $where));
            $attachList = array();
            foreach($itemList as $_item) {
                isset($attachList[$_item['item_type']]) OR $attachList[$_item['item_type']] = array();
                $attachList[$_item['item_type']][$_item['item_id']] = $this->model('publish')->parse_attach_data(array($_item), $_item['item_type']);
                $attachList[$_item['item_type']][$_item['item_id']] = array_pop($attachList[$_item['item_type']][$_item['item_id']]);
            }
            View::assign('show_image', false);
            View::assign('showArticleCategory', true);
            View::assign('showArticleTime', true);
            View::assign('attach_list', $attachList);
            View::assign('posts_list', $posts_list);
            //View::assign('posts_list_html', View::output('block/post_list_with_article_thumb', false));
            View::assign('posts_list_html', View::output('block/post_title_list_with_category', false));



            View::assign('listColClass', 'col-sm-4 col-xs-6 nopadding');
            View::assign('show_image', false);
            View::assign('showArticleTime', false);
            View::assign('showArticleCategory', true);
            View::assign('categoryCssClass', 'pull-right');
            $moreCategoryIds = array_diff(array_keys($allCategoryList), $categoryIds );
            View::assign('posts_list', $this->model('posts')->get_posts_list(null, $_GET['page'], get_setting('contents_per_page') * 0 + 45, $_GET['sort_type'], null, $moreCategoryIds) );
            View::assign('more_posts_list', View::output('block/post_title_list_with_category', false) );
            View::assign('moreCategoryIds', $moreCategoryIds);


        }

        $article_list = $this->model('article')->get_articles_list($category_info['id'], $_GET['page'], get_setting('contents_per_page'), 'add_time DESC');


        if ($article_list) {
            foreach ($article_list AS $key => $val) {
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


        // 查看并准备模版中用到的块数据
        $this->_prepareDataByCheckingTplFile('article/square', 'article');

        View::assign('article_list', $article_list);
        View::assign('article_topics', $article_topics);

        View::assign('hot_articles', $this->model('article')->get_articles_list(null, 1, 15, 'votes DESC', 30));

        View::assign('pagination', Application::pagination()->initialize(array(
            'base_url' => get_js_url('/article/category_id-' . $_GET['category_id'] . '__feature_id-' . $_GET['feature_id']),
            'total_rows' => $article_list_total,
            'per_page' => get_setting('contents_per_page')
        ))->create_links());


        $pageContent = View::output('article/article_home_no_head_foot', false);
        empty($_GET['doGenerateCache']) OR Application::cache()->set($cache_key, $pageContent .'<!-- cached ' . date('Y-m-d H:i:s') . ' -->', get_setting('cache_level_low'));
        View::assign('mainContent', $pageContent);
        View::output('global/cache_show.php');
    }

    public function index_square_action_bak()
    {
        // 移动端请求， 重定向到移动页面
        mobileRedirect('/m/article/');

        $this->crumb(Application::lang()->_t('文章'), '/article/');

        if ($_GET['category']) {
            if (is_digits($_GET['category'])) {
                $category_info = $this->model('system')->get_category_info($_GET['category']);
            }
            else {
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

        View::output('article/article_home');
    }
}

