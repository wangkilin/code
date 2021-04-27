<?php
class main extends BaseController
{
    public function get_access_rule()
    {
        $rule_action['rule_type'] = "white"; //'black'黑名单,黑名单中的检查  'white'白名单,白名单以外的检查

        if ($this->user_info['permission']['visit_explore'] AND $this->user_info['permission']['visit_site'])
        {
            $rule_action['actions'][] = 'index';
        }

        return $rule_action;
    }

    public function setup()
    {
        if (is_mobile() AND !$_GET['ignore_ua_check'])  {
            switch ($_GET['app']) {
                default:
                    HTTP::redirect('/m/');
                    break;
            }
        }
    }

    public function showDataInCategory ($categoryInfo)
    {

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
//var_dump($categoryInfo);
        $pagination = Application::pagination()->initialize(array(
            'base_url' => get_js_url('/category-' . $categoryInfo['url_token']),
            'total_rows' => $this->model('posts')->get_posts_list_total(),
            'per_page' => $this->per_page,
        ))->create_links();


        View::assign('itemList', $categoryList);
        View::assign('categoryList', $allCategoryList);
        View::assign('listColClass', 'col-sm-6 col-xs-12 nopadding');
        View::assign('show_image', true);

        View::import_css('isinho.com/owl.theme.default.css');
        View::import_css('isinho.com/owl.carousel.min.css');
        View::import_js('isinho.com/owl.carousel.min.js');

        View::assign('pagination', $pagination);
        View::output('index/show_data_in_category');
        return;
    }

    public function index_action()
    {
        // 移动端请求， 重定向到移动页面
        mobileRedirect('/m/index/' . $_GET['id']);

        // 已登录用户
        if ($this->user_id) {
            $this->crumb(Application::lang()->_t('发现'), '/index');
            // 资料不完善
            if (! $this->user_info['email']) {
                HTTP::redirect('/account/complete_profile/');
            }
        }
        // 查看分类下的列表。 先获取分类信息
        if ($_GET['category']) {
            if (is_digits($_GET['category'])) {
                $category_info = $this->model('system')->get_category_info($_GET['category']);
            } else {
                $category_info = $this->model('system')->get_category_info_by_url_token($_GET['category']);
            }
        }
        // 获取到分类信息， 将分类信息传递到前端
        if ($category_info) {

            return $this->showDataInCategory($category_info);
        }

        $cache_key = $_SERVER['HTTP_HOST'] . 'website_homepage';
        if ($pageContent = Application::cache()->get($cache_key)) {
            echo  $pageContent . '<!-- cache -->';
            return;
        }
        // 没设置排序， 也没有设置推荐， 按照最新排序
        $_GET['sort_type'] = 'new';

        // 查看并准备模版中用到的块数据
        $this->_prepareDataByCheckingTplFile('index/index');
        $allCategoryList = $this->model('category')->getCategoryAndChildIds();
        if ($category_info) {
            $categoryList = array($category_info['id'] => $allCategoryList[$category_info['id']]);
        } else {
            $categoryList = $allCategoryList;
        }
        //var_dump($categoryList);

        View::assign('listColClass', 'col-sm-6 col-xs-12 nopadding');
        View::assign('show_image', true);
        View::assign('showArticleCategory', true);

        $courseFirstPageList = $this->model('course')->fetch_all('course_content_table', '`sort` IN (0,1)', 'sort DESC');
        $tableIds = array_column($courseFirstPageList, 'table_id');
        $courseFirstPageList = array_combine($tableIds, $courseFirstPageList);
        View::assign('courseFirstPageList', $courseFirstPageList);
        foreach (View::$view->content_nav_menu as & $_itemInfo) {
            $_itemInfo['category_ids'] = array();
            foreach ($_itemInfo['child'] as $_childInfo) {
                if ('category' == $_childInfo['type']) {
                    $_itemInfo['category_ids'] = array_merge($_itemInfo['category_ids'], (array)$categoryList[$_childInfo['type_id']]['childIds']);
                }
            }
            if ($_itemInfo['category_ids']) {
                $_itemInfo['category_ids'] = array_unique($_itemInfo['category_ids']);
                View::assign('posts_list', $this->model('posts')->get_posts_list(null, $_GET['page'], get_setting('contents_per_page') * 0 + 24, $_GET['sort_type'], null, $_itemInfo['category_ids']) );
                $_itemInfo['posts_list'] = View::output('block/post_title_list_with_category', false);
                $_itemInfo['course_table_list'] = $this->model('course')->fetch_all('course_table', 'category_id IN ('.join(',', $_itemInfo['category_ids']).')');
            }
        }

        View::assign('categoryList', $allCategoryList);

        View::import_css('isinho.com/owl.theme.default.css');
        View::import_css('isinho.com/owl.carousel.min.css');
        View::import_js('isinho.com/owl.carousel.min.js');

        $pageContent = View::output('index/index', false);
        Application::cache()->set($cache_key, $pageContent, get_setting('cache_level_normal'));

        echo $pageContent;
    }

    public function index_bak_action()
    {
        // 移动端请求， 重定向到移动页面
        mobileRedirect('/m/index/' . $_GET['id']);

        // 已登录用户
        if ($this->user_id) {
            $this->crumb(Application::lang()->_t('发现'), '/index');
            // 资料不完善
            if (! $this->user_info['email']) {
                HTTP::redirect('/account/complete_profile/');
            }
        }
        // 查看分类下的列表。 先获取分类信息
        if ($_GET['category']) {
            if (is_digits($_GET['category'])) {
                $category_info = $this->model('system')->get_category_info($_GET['category']);
            } else {
                $category_info = $this->model('system')->get_category_info_by_url_token($_GET['category']);
            }
        }
        // 获取到分类信息， 将分类信息传递到前端
        if ($category_info) {
            View::assign('category_info', $category_info);

            $this->crumb($category_info['title'], '/category-' . $category_info['id']);
            // 组装meta关键字
            $meta_description = $category_info['title'];
            if ($category_info['description']){
                $meta_description .= ' - ' . $category_info['description'];
            }

            View::set_meta('description', $meta_description);
        }
        // 查看并准备模版中用到的块数据
        $this->_prepareDataByCheckingTplFile('index/index');
        // 没设置排序， 也没有设置推荐， 按照最新排序
        if (! $_GET['sort_type'] AND !$_GET['is_recommend']){
            $_GET['sort_type'] = 'new';
        }

        if ($_GET['sort_type'] == 'hot') {// 按照热门排序
            $posts_list = $this->model('posts')->get_hot_posts(null, $category_info['id'], null, $_GET['day'], $_GET['page'], get_setting('contents_per_page'));
        } else {// 按照指定排序规则排序
            $posts_list = $this->model('posts')->get_posts_list(null, $_GET['page'], get_setting('contents_per_page'), $_GET['sort_type'], null, $category_info['id'], $_GET['answer_count'], $_GET['day'], $_GET['is_recommend']);
        }
        $courseList = $this->model('posts')->getPostsInTypeCategoryIds('course');
        $questionList = $this->model('posts')->getPostsInTypeCategoryIds('question');
        $articleList = $this->model('posts')->getPostsInTypeCategoryIds('article');

        $articleIds = array();
        $courseIds  = array();
        $questionIds = array();
        $postIds = array();
        if ($posts_list) {
            foreach ($posts_list AS $key => $val) {
                if ($val['answer_count']) {
                    $posts_list[$key]['answer_users'] = $this->model('question')->get_answer_users_by_question_id($val['question_id'], 2, $val['published_uid']);
                }
                switch ($val['post_type']) {
                    case 'article':
                    case 'course':
                    case 'question':
                        ${$val['post_type'] . 'Ids'} = isset($val['question_id']) ? $val['question_id']:intval($val['id']);
                        break;
                    default:
                        break;
                }
                isset($postIds[$val['post_type']]) OR $postIds[$val['post_type']] = array();
                $postIds[$val['post_type']][] = isset($val['question_id']) ? $val['question_id']:intval($val['id']);
            }
        }
        $pagination = Application::pagination()->initialize(array(
            'base_url' => get_js_url('/sort_type-' . preg_replace("/[\(\)\.;']/", '', $_GET['sort_type']) . '__category-' . $category_info['id'] . '__day-' . intval($_GET['day']) . '__is_recommend-' . intval($_GET['is_recommend'])),
            'total_rows' => $this->model('posts')->get_posts_list_total(),
            'per_page' => get_setting('contents_per_page')
        ))->create_links();

        // @todo 获取各个分类的topics。 页面显示topics
        $article_topics = $this->model('topic')->getTopicsByArticleIds($articleIds, 'article');
        $course_topics  = $this->model('topic')->getTopicsByArticleIds($courseIds, 'course');
        $question_topics = $this->model('topic')->getTopicsByArticleIds($questionIds, 'question');

        $where = array();
        foreach($postIds as $_itemType=>$_ids) {
            $where[] = '(item_type="'.$_itemType.'" AND item_id IN (' . join(',', $_ids) . ') )';
        }
        $itemList = $this->model('attach')->fetch_all('', join(' OR ', $where));
        $attachList = array();
        foreach($itemList as $_item) {
            isset($attachList[$_item['item_type']]) OR $attachList[$_item['item_type']] = array();
            $attachList[$_item['item_type']][$_item['item_id']] = $this->model('publish')->parse_attach_data(array($_item), $_item['item_type']);
            $attachList[$_item['item_type']][$_item['item_id']] = array_pop($attachList[$_item['item_type']][$_item['item_id']]);
        }
        //var_dump($attachList);
        //var_dump($course_topics);
        View::assign('show_image', true);
        View::assign('attach_list', $attachList);
        View::assign('posts_list', $courseList);
        View::assign('article_topics', $article_topics);
        View::assign('course_topics', $course_topics);
        View::assign('question_topics', $question_topics);
        //var_dump($courseList,$articleList);
        //View::assign('courseList', View::output('index/ajax/list', false));
        View::assign('posts_list', $articleList);
        //View::assign('articleList', View::output('index/ajax/list', false));
        View::assign('posts_list', $questionList);
        //View::assign('questionList', View::output('index/ajax/list', false));
        View::assign('posts_list', $posts_list);
        View::assign('posts_list_bit', View::output('block/post_list', false));




        View::assign('pagination', $pagination);
        View::output('index/index.php.bak');
    }
}
