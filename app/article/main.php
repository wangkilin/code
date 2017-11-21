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

		if (! $article_info = $this->model('article')->get_article_info_by_id($_GET['id']))
		{
			HTTP::error_404();
		}

		if ($article_info['has_attach'])
		{
			$article_info['attachs'] = $this->model('publish')->getAttachListByItemTypeAndId('article', $article_info['id'], 'min');

			$article_info['attachs_ids'] = FORMAT::parse_attachs($article_info['message'], true);
		}

		$article_info['user_info'] = $this->model('account')->getUserById($article_info['uid'], true);

		$article_info['message'] = FORMAT::parse_attachs(nl2br(FORMAT::parse_bbcode($article_info['message'])));

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
			View::assign('sidebar_hot_topics', $this->model('module')->sidebar_hot_topics($category_info['id']));
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
