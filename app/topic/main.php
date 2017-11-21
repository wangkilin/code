<?php
class main extends BaseController
{
	/**
	 * 默认可进入的页面设置
	 * @return array
	 */
	public function get_access_rule()
	{
		$rule_action['rule_type'] = "white";	// 黑名单,黑名单中的检查  'white'白名单,白名单以外的检查

		if ($this->user_info['permission']['visit_topic']
				AND $this->user_info['permission']['visit_site']) {
			$rule_action['actions'][] = 'square';
			$rule_action['actions'][] = 'index';
		}

		return $rule_action;
	}

	/**
	 * 显示单个话题信息： 问题，文章， 最佳回复等， 以及相关作者信息
	 */
	public function index_action()
	{
		$this->mobileRedirect('/m/topic/' . $_GET['id']);

		$topic_info = null;
		// 获取话题信息
		if (is_digits($_GET['id'])) {
			$topic_info = $this->model('topic')->getTopicById($_GET['id']);
		}
		if (! $topic_info) {
			$topic_info = $this->model('topic')->getTopicByTitle($_GET['id']);
		}
		if (! $topic_info) {
			$topic_info = $this->model('topic')->getTopicByUrlToken($_GET['id']);
		}
		// 没有找到话题， 跳转
		if (! $topic_info) {
			HTTP::error_404();
		}
		// 话题已被合并到另外话题
		if ($topic_info['merged_id'] AND $topic_info['merged_id'] != $topic_info['topic_id']) {
			if ($this->model('topic')->getTopicById($topic_info['merged_id'])) {
				HTTP::redirect('/topic/' . $topic_info['merged_id'] . '?rf=' . $topic_info['topic_id']);
			} else {
				$this->model('topic')->remove_merge_topic($topic_info['topic_id'], $topic_info['merged_id']);
			}
		}
		// 强制使用 url_token访问话题, 并告知从哪个话题转向过来的
		if (urldecode($topic_info['url_token']) != $_GET['id']) {
			HTTP::redirect('/topic/' . $topic_info['url_token'] . (isset($_GET['rf']) ? '?rf=' . $_GET['rf'] : '') );
		}
		// 从合并话题转向过来， 知会用户当前话题是从相关话题合并过来的
		if (is_digits($_GET['rf']) and $_GET['rf']) {
			if ($from_topic = $this->model('topic')->getTopicById($_GET['rf'])) {
				$redirect_message[] = _t('话题 (%s) 已与当前话题合并', $from_topic['topic_title']);
			}
		}

		if ($topic_info['seo_title']) {
			View::assign('page_title', $topic_info['seo_title']);
		} else {
			$this->crumb($topic_info['topic_title'], '/topic/' . $topic_info['url_token']);
		}
		// 用户是否关注了这个话题： 据此页面展示 关注|取消关注
		if ($this->user_id) {
			$topic_info['isFollowed'] = $this->model('topic')->isTopicFollowed($this->user_id, $topic_info['topic_id']);
		}

		if ($topic_info['topic_description']) {
			View::set_meta('description', $topic_info['topic_title'] . ' - ' . cjk_substr(str_replace("\r\n", ' ', strip_tags($topic_info['topic_description'])), 0, 128, 'UTF-8', '...'));

		    $topic_info['topic_description'] = nl2br(FORMAT::parse_bbcode($topic_info['topic_description']));
		}

		View::assign('topic_info', $topic_info);
		// 最佳回复用户信息
		View::assign('best_answer_users', $this->model('topic')->getBestReplyUsersByTopicId($topic_info['topic_id'], 5));

		$page_keywords = array($topic_info['topic_title']);
		$related_topics_ids = array();
		// 获取相关话题
		if ($related_topics = $this->model('topic')->related_topics($topic_info['topic_id'])) {
			foreach ($related_topics AS $key => $val) {
				$related_topics_ids[$val['topic_id']] = $val['topic_id'];
				$page_keywords[] = $val['topic_title'];
			}
		}

		View::set_meta('keywords', implode(',', $page_keywords));
		View::set_meta('description', cjk_substr(str_replace("\r\n", ' ', strip_tags($topic_info['topic_description'])), 0, 128, 'UTF-8', '...'));
		// 获取子话题， 如果有。 加入到相关话题中
		if ($child_topic_ids = $this->model('topic')->getChildTopicIds($topic_info['topic_id'])) {
			foreach ($child_topic_ids AS $topic_id) {
				$related_topics_ids[$topic_id] = $topic_id;
			}
		}

		View::assign('related_topics', $related_topics);
		// 获取话题修改记录
		$log_list = ACTION_LOG::get_action_by_event_id($topic_info['topic_id'], 10, ACTION_LOG::CATEGORY_TOPIC, implode(',', array(
			ACTION_LOG::ADD_TOPIC,
			ACTION_LOG::MOD_TOPIC,
			ACTION_LOG::MOD_TOPIC_DESCRI,
			ACTION_LOG::MOD_TOPIC_PIC,
			ACTION_LOG::DELETE_TOPIC,
			ACTION_LOG::ADD_RELATED_TOPIC,
			ACTION_LOG::DELETE_RELATED_TOPIC
		)), -1);

		$log_list = $this->model('topic')->analysis_log($log_list);

		$contents_topic_id = $topic_info['topic_id'];
		$contents_topic_title = $topic_info['topic_title'];
		// 获取合并的话题
		if ($merged_topics = $this->model('topic')->getMergedTopicById($topic_info['topic_id'])) {
			foreach ($merged_topics AS $val) {
				$merged_topic_ids[] = $val['source_id'];
			}

			$contents_topic_id .= ',' . implode(',', $merged_topic_ids);

			if ($merged_topics_info = $this->model('topic')->getTopicsByIds($merged_topic_ids)) {
				foreach($merged_topics_info AS $key => $val) {
					$merged_topic_title[] = $val['topic_title'];
				}
			}

			if ($merged_topic_title) {
				$contents_topic_title .= ',' . implode(',', $merged_topic_title);
			}
		}
		// 全部话题 = 相关话题 + 子话题 + 合并话题
		$contents_related_topic_ids = array_merge($related_topics_ids, explode(',', $contents_topic_id));

		View::assign('contents_related_topic_ids', implode(',', $contents_related_topic_ids));
		// 获取和话题相关的最新条目： 文章， 问题。。。
		if ($posts_list = $this->model('posts')
				               ->get_posts_list(
				               		null, 1, get_setting('contents_per_page'),
				               		'new', $contents_related_topic_ids)) {
			foreach ($posts_list AS $key => $val) {
				if ($val['answer_count']) {
					$posts_list[$key]['answer_users'] = $this->model('question')->get_answer_users_by_question_id($val['question_id'], 2, $val['published_uid']);
				}
			}
		}
		View::assign('posts_list', $posts_list);
		View::assign('all_list_bit', View::output('index/ajax/list', false));
		// 获取推荐
		if ($posts_list = $this->model('posts')
				               ->get_posts_list(
				               		null, 1, get_setting('contents_per_page'),
				               		null, $contents_related_topic_ids, null,
				               		null, 30, true)) {
			foreach ($posts_list AS $key => $val) {
				if ($val['answer_count']) {
					$posts_list[$key]['answer_users'] = $this->model('question')->get_answer_users_by_question_id($val['question_id'], 2, $val['published_uid']);
				}
			}
		}
		View::assign('topic_recommend_list', $posts_list);
		View::assign('posts_list', $posts_list);
		View::assign('recommend_list_bit', View::output('index/ajax/list', false));
		// get_posts_list 参数
		// get_posts_list($post_type,         $page=1,         $per_page=10,
		//                $sort=null,         $topic_ids=null, $category_id=null,
		//                $answer_count=null, $day=30,         $is_recommend=false)


		View::assign('list', $this->model('topic')->getTopicBestAnswersInfo($contents_topic_id, $this->user_id, get_setting('contents_per_page')));
		View::assign('best_questions_list_bit', View::output('home/ajax/index_actions', false));
		// 获取问题
		View::assign('posts_list', $this->model('posts')->get_posts_list('question', 1, get_setting('contents_per_page'), 'new', explode(',', $contents_topic_id)));
		View::assign('all_questions_list_bit', View::output('index/ajax/list', false));
		// 获取文章
		View::assign('posts_list', $this->model('posts')
				                        ->get_posts_list('article', 1, get_setting('contents_per_page'),
				                        		'new', explode(',', $contents_topic_id))
				);
		View::assign('articles_list_bit', View::output('index/ajax/list', false));
		// 前端js用到的参数
		View::assign('contents_topic_id', $contents_topic_id);
		View::assign('contents_topic_title', $contents_topic_title);
		// 话题修改历史
		View::assign('log_list', $log_list);
		// 提示用户查看的话题是否被合并到当前话题中
		View::assign('redirect_message', $redirect_message);

		// 跟话题信息
		if ($topic_info['parent_id']) {
			View::assign('parent_topic_info', $this->model('topic')->getTopicById($topic_info['parent_id']));
		}

		View::output('topic/index');
	}

	/**
	 * 话题列表页面， 包含跟话题
	 */
	public function index_square_action()
	{
		$this->mobileRedirect('/m/topic/');
		// 今日话题： 在后台话题管理页面中设置今日话题. 随机获取一个
		if ($today_topics = rtrim(get_setting('today_topics'), ',')) {
			if (!$today_topic = Application::cache()->get('square_today_topic_' . md5($today_topics))) {
				if ($today_topic = $this->model('topic')->getTopicByTitle(array_random(explode(',', $today_topics)))) {
					$today_topic['best_answer_users'] = $this->model('topic')->getBestReplyUsersByTopicId($today_topic['topic_id'], 5);

					$today_topic['questions_list'] = $this->model('posts')->get_posts_list('question', 1, 3, 'new', explode(',', $today_topic['topic_id']));

					Application::cache()->set('square_today_topic_' . md5($today_topics), $today_topic, (strtotime('Tomorrow') - time()));
				}
			}

			View::assign('today_topic', $today_topic);
		}

		switch ($_GET['show']) {
			case 'topic':
				if (!$topics_list = Application::cache()->get('square_parent_topics_topic_list_' . intval($_GET['topic_id']) . '_' . intval($_GET['page']))) {
					$topic_ids[] = intval($_GET['topic_id']);

					if ($child_topic_ids = $this->model('topic')->getChildTopicIds($_GET['topic_id'])) {
						$topic_ids = array_merge($child_topic_ids, $topic_ids);
					}

					if ($topics_list = $this->model('topic')->getTopicList('topic_id IN(' . implode(',', $topic_ids) . ') AND merged_id = 0', 'discuss_count DESC', 20, $_GET['page'])) {
						$topics_list_total_rows = $this->model('topic')->found_rows();

						Application::cache()->set('square_parent_topics_topic_list_' . intval($_GET['topic_id']) . '_total_rows', $topics_list_total_rows, get_setting('cache_level_low'));
					}

					Application::cache()->set('square_parent_topics_topic_list_' . intval($_GET['topic_id']) . '_' . intval($_GET['page']), $topics_list, get_setting('cache_level_low'));

				} else {
					$topics_list_total_rows = Application::cache()->get('square_parent_topics_topic_list_' . intval($_GET['topic_id']) . '_total_rows');
				}

				View::assign('topics_list', $topics_list);
			break;

			case 'hot':
            default:
            	$cacheData = true;
				switch ($_GET['type']) {
					case 'follow':
						if ($topics_list = $this->model('topic')->get_focus_topic_list($this->user_id, calc_page_limit($_GET['page'], 20))) {
							$topics_list_total_rows = $this->user_info['topic_focus_count'];
						}
						$cacheData = false;
					break;

					case 'month':
					case 'week':
						$order = 'discuss_count_last_'.$_GET['type'].' DESC';
					    break;

					default:
						$order = 'discuss_count DESC';
					    break;
				}

				$cache_key = 'square_hot_topic_list' . md5($order) . '_' . intval($_GET['page']);

				if ($cacheData && (!$topics_list = Application::cache()->get($cache_key)) ) {
					if ($topics_list = $this->model('topic')->getTopicList(null, $order, 20, $_GET['page'])) {
						$topics_list_total_rows = $this->model('topic')->found_rows();

						Application::cache()->set('square_hot_topic_list_total_rows', $topics_list_total_rows, get_setting('cache_level_low'));
					}

					Application::cache()->set($cache_key, $topics_list, get_setting('cache_level_low'));
				} else if ($cacheData) {
					$topics_list_total_rows = Application::cache()->get('square_hot_topic_list_total_rows');
				}

				View::assign('topics_list', $topics_list);
			break;
		}

		View::assign('parent_topics', $this->model('topic')->getRootTopics());

		View::assign('new_topics', $this->model('topic')->getTopicList(null, 'topic_id DESC', 10));

		View::assign('pagination', Application::pagination()->initialize(array(
			'base_url' => get_js_url('/topic/show-' . $_GET['show'] . '__topic_id-' . $_GET['topic_id'] . '__day-' . $_GET['day']),
			'total_rows' => $topics_list_total_rows,
			'per_page' => 20
		))->create_links());

		$this->crumb(_t('话题广场'), '/topic/');

		View::output('topic/square');
	}

	/**
	 * 前台显示话题编辑表单页面
	 */
	public function edit_action()
	{
		if (!($this->user_info['permission']['is_administortar'] OR $this->user_info['permission']['is_moderator'])) {
			if (!$this->user_info['permission']['edit_topic']) {
				HTTP::error_404();
				//H::redirect_msg(_t('你没有权限进行此操作'));
			}
			if ($this->model('topic')->isTopicLocked($_GET['id'])) {
				H::redirect_msg(_t('已锁定的话题不能编辑'));
			}
		}
		if (! $topic_info = $this->model('topic')->getTopicById($_GET['id'])) {
			H::redirect_msg(_t('话题不存在'), '/');
		}

		$this->crumb(_t('话题编辑'), '/topic/edit/' . $topic_info['topic_id']);
		$this->crumb($topic_info['topic_title'], '/topic/' . $topic_info['topic_id']);

		View::assign('topic_info', $topic_info);
		View::assign('related_topics', $this->model('topic')->related_topics($_GET['id']));

		View::import_js('js/fileupload.js');

		if (get_setting('advanced_editor_enable') == 'Y') {
			import_editor_static_files();
		}

		View::output('topic/edit');
	}

	/**
	 * 在前台页面管理话题
	 */
	public function manage_action()
	{
		if (!($this->user_info['permission']['is_administortar']
				OR $this->user_info['permission']['is_moderator'])) {
			if (!$this->user_info['permission']['manage_topic']) {
				HTTP::error_404();
				//H::redirect_msg(_t('你没有权限进行此操作'));
			}
			if ($this->model('topic')->isTopicLocked($_GET['id'])) {
				H::redirect_msg(_t('已锁定的话题不能编辑'));
			}
		}
		if (! $topic_info = $this->model('topic')->getTopicById($_GET['id'])) {
			H::redirect_msg(_t('话题不存在'), '/');
		}

		$this->crumb(_t('话题管理'), '/topic/manage/' . $topic_info['topic_id']);
		$this->crumb($topic_info['topic_title'], '/topic/' . $topic_info['topic_id']);

		if ($merged_topics = $this->model('topic')->getMergedTopicById($topic_info['topic_id'])) {
			foreach ($merged_topics AS $key => $val) {
				$merged_topic_ids[] = $val['source_id'];
			}

			$merged_topics_info = $this->model('topic')->getTopicsByIds($merged_topic_ids);
		}

		View::assign('merged_topics_info', $merged_topics_info);
		View::assign('topic_info', $topic_info);

		if (!$topic_info['is_parent']) {
			View::assign('parent_topics', $this->model('topic')->getRootTopics());
		}

		View::output('topic/manage');
	}
}
