<?php
/**
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

define('IN_AJAX', TRUE);

class ajax extends BaseController
{
	public function get_access_rule()
	{
		$rule_action['rule_type'] = 'white'; //黑名单,黑名单中的检查  'white'白名单,白名单以外的检查

		$rule_action['actions'] = array(
			'topic_info',
			'question_list',
			'get_focus_users'
		);

		return $rule_action;
	}

	public function setup()
	{
		HTTP::setHeaderNoCache();
	}

	/**
	 * 获取标签列表
	 */
    public function tag_list_action ()
    {
    	$conditions = $_GET;
    	if ($conditions['limit']) {
    		$perPage = intval($conditions['limit']);
    	}
    	if ($conditions['key']) {
    		$conditions['keyword'] = $conditions['key'];
    	}
    	$tagList = $this->model('topic')->getTopicsByConditions($conditions, null, $perPage);
    	H::ajax_json_output(array_values($tagList));
	}

	public function get_focus_users_action()
	{
		H::ajax_json_output($this->model('topic')->get_focus_users_by_topic($_GET['topic_id'], 18));
	}

	public function question_list_action()
	{
		if ($_GET['feature_id'])
		{
			if ($topic_ids = $this->model('feature')->get_topics_by_feature_id($_GET['feature_id']))
			{
				$_GET['topic_id'] = implode(',', $topic_ids);
			}
		}

		switch ($_GET['type'])
		{
			case 'best':
				$action_list = $this->model('topic')->getTopicBestAnswersInfo(intval($_GET['topic_id']), $this->user_id, intval($_GET['page']) * get_setting('contents_per_page') . ', ' . get_setting('contents_per_page'));
			break;

			case 'favorite':
				$action_list = $this->model('favorite')->get_item_list($_GET['topic_title'], $this->user_id, intval($_GET['page']) * get_setting('contents_per_page') . ', ' . get_setting('contents_per_page'));
			break;
		}

		View::assign('list', $action_list);

		if (is_mobile())
		{
			View::output('m/ajax/index_actions');
		}
		else
		{
			View::output('home/ajax/index_actions');
		}
	}

	public function topic_info_action()
	{
		$topic_info = $this->model('topic')->getTopicById($_GET['topic_id']);

		$topic_info['type'] = 'topic';

		$topic_info['topic_description'] = cjk_substr(strip_tags($topic_info['topic_description']), 0, 80, 'UTF-8', '...');

		$topic_info['focus_count'] = $topic_info['focus_count'];

		if ($this->user_id)
		{
			$topic_info['focus'] = $this->model('topic')->isTopicFollowed($this->user_id, $topic_info['topic_id']);
		}

		$topic_info['topic_pic'] = getMudulePicUrlBySize('topic', 'mid', $topic_info['topic_pic']);
		$topic_info['url'] = get_js_url('/topic/' . $topic_info['url_token']);

		H::ajax_json_output($topic_info);
	}

	public function edit_topic_action()
	{
		if (!($this->user_info['permission']['is_administortar'] OR $this->user_info['permission']['is_moderator']))
		{
			if (!$this->user_info['permission']['edit_topic'])
			{
				H::ajax_json_output(Application::RSM(null, '-1', _t('你没有权限进行此操作')));
			}
			else if ($this->model('topic')->isTopicLocked($_POST['topic_id']))
			{
				H::ajax_json_output(Application::RSM(null, '-1', _t('锁定的话题不能编辑')));
			}
			else if ($this->user_info['permission']['function_interval'] AND ((time() - Application::cache()->get('function_interval_timer_topic_' . $this->user_id)) < $this->user_info['permission']['function_interval']))
			{
				H::ajax_json_output(Application::RSM(null, '-1', _t('灌水预防机制已经打开, 在 %s 秒内不能操作', $this->user_info['permission']['function_interval'])));
			}
		}

		if (!$topic_info = $this->model('topic')->getTopicById($_POST['topic_id']))
		{
			H::ajax_json_output(Application::RSM(null, '-1', _t('话题不存在')));
		}

		if (!$_POST['topic_description'])
		{
			H::ajax_json_output(Application::RSM(null, '-1', _t('请填写话题描述')));
		}

		$this->model('topic')->update_topic($this->user_id, $_POST['topic_id'], null, $_POST['topic_description']);

		Application::cache()->set('function_interval_timer_topic_' . $this->user_id, time(), 86400);

		H::ajax_json_output(Application::RSM(array(
			'url' => get_js_url('/topic/' . $_POST['topic_id'])
		), 1, null));
	}

	public function save_related_topic_action()
	{
		if (!($this->user_info['permission']['is_administortar'] OR $this->user_info['permission']['is_moderator'])) {
			if (!$this->user_info['permission']['edit_topic']) {
				H::ajax_json_output(Application::RSM(null, '-1', _t('你没有权限进行此操作')));
			}
			if ($this->model('topic')->isTopicLocked($_GET['topic_id'])) {
				H::ajax_json_output(Application::RSM(null, '-1', _t('锁定的话题不能编辑')));
			}
		}

		if (!$this->model('topic')->getTopicById($_GET['topic_id'])) {
			H::ajax_json_output(Application::RSM(null, '-1', _t('话题不存在')));
		}

		if (!$topic_title = trim($_POST['topic_title'])) {
			H::ajax_json_output(Application::RSM(null, '-1', _t('请输入话题标题')));
		}

		if (strstr($_POST['topic_title'], '/') OR strstr($_POST['topic_title'], '-') OR strstr($_POST['topic_title'], '&'))
		{
			H::ajax_json_output(Application::RSM(null, '-1', _t('话题标题不能包含 / - &')));
		}

		if (get_setting('topic_title_limit') > 0 AND cjk_strlen($topic_title) > get_setting('topic_title_limit'))
		{
			H::ajax_json_output(Application::RSM(null, '-1', _t('话题标题字数不得超过 %s 字节', get_setting('topic_title_limit'))));
		}

		if (! $related_id = $this->model('topic')->saveTopic($topic_title, $this->user_id, $this->user_info['permission']['create_topic']))
		{
			H::ajax_json_output(Application::RSM(null, '-1', _t('话题已锁定或没有创建话题权限')));
		}

		if (!$this->model('topic')->save_related_topic($_GET['topic_id'], $related_id))
		{
			H::ajax_json_output(Application::RSM(null, '-1', _t('已经存在相同推荐话题')));
		}

		ACTION_LOG::save_action($this->user_id, $_GET['topic_id'], ACTION_LOG::CATEGORY_TOPIC, ACTION_LOG::ADD_RELATED_TOPIC, '', $related_id);

		H::ajax_json_output(Application::RSM(array(
			'related_id' => $related_id,
		), 1, null));
	}

	public function remove_related_topic_action()
	{
		if (!($this->user_info['permission']['is_administortar'] OR $this->user_info['permission']['is_moderator']))
		{
			if (!$this->user_info['permission']['edit_topic'])
			{
				H::ajax_json_output(Application::RSM(null, '-1', _t('你没有权限进行此操作')));
			}
			else if ($this->model('topic')->isTopicLocked($_GET['topic_id']))
			{
				H::ajax_json_output(Application::RSM(null, '-1', _t('锁定的话题不能编辑')));
			}
		}

		$this->model('topic')->remove_related_topic($_GET['topic_id'], $_GET['related_id']);

		ACTION_LOG::save_action($this->user_id, $_GET['topic_id'], ACTION_LOG::CATEGORY_TOPIC, ACTION_LOG::DELETE_RELATED_TOPIC, '', $_GET['related_id']);

		H::ajax_json_output(Application::RSM(null, 1, null));
	}

	/**
	 * 上传话题图片
	 */
	public function upload_topic_pic_action()
	{
		if ($this->hasRolePermission(self::IS_ROLE_ADMIN | self::IS_ROLE_ADMIN)) {
			if (!$this->user_info['permission']['edit_topic']) {
				H::ajax_json_output(Application::RSM(null, '-1', _t('你没有权限进行此操作')));
			}
			else if ($this->model('topic')->isTopicLocked($_GET['topic_id']))
			{
				H::ajax_json_output(Application::RSM(null, '-1', _t('锁定的话题不能编辑')));
			}
		}

		if (! $topic_info = $this->model('topic')->getTopicById($_GET['topic_id'])) {
			H::ajax_json_output(Application::RSM(null, '-1', _t('话题不存在')));
		}

		Application::upload()->initialize(array(
			'allowed_types' => 'jpg,jpeg,png,gif',
			'upload_path'   => get_setting('upload_dir') . '/topic/' . gmdate('Ymd', APP_START_TIME),
			'is_image'      => TRUE,
			'max_size'      => get_setting('upload_avatar_size_limit')
		))->do_upload('upload_file');
		// 检查上传结果
		$this->checkUploadFileResult();

		$upload_data = Application::upload()->data();
		// 生成不同尺寸的图片
		if ($upload_data['is_image'] == 1) {
			$picList = $this->resizeUploadedModulePic ('topic', $topic_info['topic_pic']);
		}

		$this->model('topic')->update_topic($this->user_id, $_GET['topic_id'], null, null, gmdate('Ymd', APP_START_TIME) . '/' . basename($picList['min']));

		echo htmlspecialchars(json_encode(array(
			'success' => true,
			'thumb' => get_setting('upload_url') . '/topic/' . gmdate('Ymd', APP_START_TIME) . '/' . basename($picList['mid'])
		)), ENT_NOQUOTES);
	}

	public function focus_topic_action()
	{
		H::ajax_json_output(Application::RSM(array(
			'type' => $this->model('topic')->switchTopicFollowed($this->user_id, intval($_POST['topic_id']))
		), '1', null));
	}

	public function lock_topic_action()
	{
		$this->model('topic')->lock_topic_by_id($_GET['topic_id'], $this->model('topic')->isTopicLocked($_GET['topic_id']));

		H::ajax_json_output(Application::RSM(null, 1));
	}

	public function set_parent_id_action()
	{
		if (!($this->user_info['permission']['is_administortar'] OR $this->user_info['permission']['is_moderator']))
		{
			if (!$this->user_info['permission']['manage_topic'])
			{
				H::ajax_json_output(Application::RSM(null, '-1', _t('你没有权限进行此操作')));
			}
			else if ($this->model('topic')->isTopicLocked($_POST['topic_id']))
			{
				H::ajax_json_output(Application::RSM(null, '-1', _t('锁定的话题不能编辑')));
			}
		}

		if (!$topic_info = $this->model('topic')->getTopicById($_POST['topic_id']))
		{
			H::ajax_json_output(Application::RSM(null, '-1', _t('话题不存在')));
		}

		if ($topic_info['is_parent'])
		{
			H::ajax_json_output(Application::RSM(null, '-1', _t('不允许为根话题设置根话题')));
		}

		if ($topic_info['topic_id'] == intval($_POST['parent_id']))
		{
			H::ajax_json_output(Application::RSM(null, '-1', _t('不允许将根话题设置为自己')));
		}

		$this->model('topic')->set_parent_id($topic_info['topic_id'], $_POST['parent_id']);

		H::ajax_json_output(Application::RSM(null, -1, null));
	}

	public function save_url_token_action()
	{
		if (!($this->user_info['permission']['is_administortar'] OR $this->user_info['permission']['is_moderator']))
		{
			if (!$this->user_info['permission']['manage_topic'])
			{
				H::ajax_json_output(Application::RSM(null, '-1', _t('你没有权限进行此操作')));
			}
			else if ($this->model('topic')->isTopicLocked($_POST['topic_id']))
			{
				H::ajax_json_output(Application::RSM(null, '-1', _t('锁定的话题不能编辑')));
			}
		}

		if (!$topic_info = $this->model('topic')->getTopicById($_POST['topic_id']))
		{
			H::ajax_json_output(Application::RSM(null, '-1', _t('话题不存在')));
		}

		if (!preg_match("/^(?!__)[a-zA-Z0-9_]+$/i", $_POST['url_token']))
		{
			H::ajax_json_output(Application::RSM(null, '-1', _t('话题别名只允许输入英文或数字')));
		}

		if ($this->model('topic')->check_url_token($_POST['url_token'], $topic_info['topic_id']))
		{
			H::ajax_json_output(Application::RSM(null, '-1', _t('话题别名已经被占用请更换一个')));
		}

		if (preg_match("/^[\d]+$/i", $_POST['url_token']))
		{
			H::ajax_json_output(Application::RSM(null, '-1', _t('话题别名不允许为纯数字')));
		}

		$this->model('topic')->update_url_token($_POST['url_token'], $topic_info['topic_id']);

		H::ajax_json_output(Application::RSM(array(
			'url' => get_js_url('/topic/' . $_POST['url_token'])
		), 1, null));
	}

	public function save_seo_title_action()
	{
		if (!($this->user_info['permission']['is_administortar'] OR $this->user_info['permission']['is_moderator']))
		{
			if (!$this->user_info['permission']['manage_topic'])
			{
				H::ajax_json_output(Application::RSM(null, '-1', _t('你没有权限进行此操作')));
			}
			else if ($this->model('topic')->isTopicLocked($_POST['topic_id']))
			{
				H::ajax_json_output(Application::RSM(null, '-1', _t('锁定的话题不能编辑')));
			}
		}

		if (!$topic_info = $this->model('topic')->getTopicById($_POST['topic_id']))
		{
			H::ajax_json_output(Application::RSM(null, '-1', _t('话题不存在')));
		}

		$this->model('topic')->update_seo_title($_POST['seo_title'], $topic_info['topic_id']);

		H::ajax_json_output(Application::RSM(array(
			'url' => get_js_url('/topic/' . $topic_info['url_token'])
		), 1, null));
	}

	public function lock_action()
	{
		if (! $this->user_info['permission']['is_moderator'] AND ! $this->user_info['permission']['is_administortar'])
		{
			H::ajax_json_output(Application::RSM(null, - 1, _t('你没有权限进行此操作')));
		}

		if (! $topic_info = $this->model('topic')->getTopicById($_POST['topic_id']))
		{
			H::ajax_json_output(Application::RSM(null, - 1, _t('话题不存在')));
		}

		$this->model('topic')->lock_topic_by_ids($_POST['topic_id'], !$topic_info['topic_lock']);

		H::ajax_json_output(Application::RSM(null, 1, null));
	}

	public function remove_action()
	{
		if (! $this->user_info['permission']['is_moderator'] AND ! $this->user_info['permission']['is_administortar'])
		{
			H::ajax_json_output(Application::RSM(null, - 1, _t('你没有权限进行此操作')));
		}

		$this->model('topic')->remove_topic_by_ids($_POST['topic_id']);

		H::ajax_json_output(Application::RSM(array(
			'url' => get_js_url('/topic/')
		), 1, null));
	}

	public function merge_topic_action()
	{
		if (!($this->user_info['permission']['is_administortar'] OR $this->user_info['permission']['is_moderator']))
		{
			if (!$this->user_info['permission']['manage_topic'])
			{
				H::ajax_json_output(Application::RSM(null, '-1', _t('你没有权限进行此操作')));
			}
			else if ($this->model('topic')->isTopicLocked($_POST['topic_id']))
			{
				H::ajax_json_output(Application::RSM(null, '-1', _t('锁定的话题不能编辑')));
			}
		}

		if (!$topic_info = $this->model('topic')->getTopicByTitle($_POST['topic_title']))
		{
			H::ajax_json_output(Application::RSM(null, '-1', _t('话题不存在')));
		}

		if ($topic_info['topic_id'] == $_POST['target_id'])
		{
			H::ajax_json_output(Application::RSM(null, '-1', _t('话题合并不能与自己合并')));
		}

		if ($topic_info['merged_id'])
		{
			$merged_topic_info = $this->model('topic')->getTopicById($topic_info['merged_id']);

			H::ajax_json_output(Application::RSM(null, '-1', _t('该话题已经与 %s 合并', $merged_topic_info['topic_title'])));
		}

		$this->model('topic')->mergeTwoTopicByIds($topic_info['topic_id'], $_POST['target_id'], $this->user_id);

		H::ajax_json_output(Application::RSM(null, 1, null));
	}

	public function remove_merge_topic_action()
	{
		if (!($this->user_info['permission']['is_administortar'] OR $this->user_info['permission']['is_moderator']))
		{
			if (!$this->user_info['permission']['manage_topic'])
			{
				H::ajax_json_output(Application::RSM(null, '-1', _t('你没有权限进行此操作')));
			}
			else if ($this->model('topic')->isTopicLocked($_POST['topic_id']))
			{
				H::ajax_json_output(Application::RSM(null, '-1', _t('锁定的话题不能编辑')));
			}
		}

		$this->model('topic')->remove_merge_topic($_POST['source_id'], $_POST['target_id']);

		H::ajax_json_output(Application::RSM(null, 1, null));
	}

	public function feature_topic_action()
	{
		if (!$this->user_info['permission']['is_administortar'] OR $this->user_info['permission']['is_moderator'])
		{
			if (!$this->user_info['permission']['manage_topic'])
			{
				H::ajax_json_output(Application::RSM(null, '-1', _t('你没有权限进行此操作')));
			}
			else if ($this->model('topic')->isTopicLocked($_POST['topic_id']))
			{
				H::ajax_json_output(Application::RSM(null, '-1', _t('锁定的话题不能编辑')));
			}
		}

		$topic_in_features = $this->model('feature')->get_topic_in_feature_ids($_POST['topic_id']);

		if ($_POST['feature_ids'])
		{
			foreach ($_POST['feature_ids'] AS $key => $feature_id)
			{
				if (in_array($feature_id, $topic_in_features))
				{
					unset($topic_in_features[$key]);
				}
				else
				{
					$this->model('feature')->add_topic($feature_id, $_POST['topic_id']);
				}
			}
		}

		foreach ($topic_in_features AS $key => $feature_id)
		{
			$this->model('feature')->delete_topic($feature_id, $_POST['topic_id']);
		}

		H::ajax_json_output(Application::RSM(null, 1, null));
	}

	public function remove_topic_relation_action()
	{
		if (!$_POST['topic_id'] OR !$_POST['item_id'] OR !$_POST['type'])
		{
			H::ajax_json_output(Application::RSM(null, '-1', _t('指定的项目不存在')));
		}

		switch ($_POST['type'])
		{
			case 'question':
				if (!$question_info = $this->model('question')->get_question_info_by_id($_POST['item_id']))
				{
					H::ajax_json_output(Application::RSM(null, '-1', _t('指定问题不存在')));
				}

				if (!$this->user_info['permission']['edit_question_topic'] AND $this->user_id != $question_info['published_uid'])
				{
					H::ajax_json_output(Application::RSM(null, '-1', _t('你没有权限进行此操作')));
				}
			break;

			case 'article':
				if (!$article_info = $this->model('article')->get_article_info_by_id($_POST['item_id']))
				{
					H::ajax_json_output(Application::RSM(null, '-1', _t('指定项目不存在')));
				}

				if (!$this->user_info['permission']['edit_question_topic'] AND $this->user_id != $article_info['uid'])
				{
					H::ajax_json_output(Application::RSM(null, '-1', _t('你没有权限进行此操作')));
				}
			break;
		}

		$this->model('topic')->removeTopicItemRelation($this->user_id, $_POST['topic_id'], $_POST['item_id'], $_POST['type']);

		H::ajax_json_output(Application::RSM(null, -1, null));
	}

	/**
	 *
	 */
	public function set_article_topic_relation_action()
	{
		if (!$_POST['topic_title'] OR !$_POST['item_id'] OR !$_POST['type']) {
			H::ajax_json_output(Application::RSM(null, '-1', _t('指定的项目不存在')));
		}

		switch ($_POST['type'])
		{
			case 'question':
				if (!$question_info = $this->model('question')->get_question_info_by_id($_POST['item_id']))
				{
					H::ajax_json_output(Application::RSM(null, '-1', _t('指定问题不存在')));
				}

				if (!$this->user_info['permission']['edit_question_topic'] AND $this->user_id != $question_info['published_uid'])
				{
					H::ajax_json_output(Application::RSM(null, '-1', _t('你没有权限进行此操作')));
				}
			break;

			case 'article':
				if (!$article_info = $this->model('article')->get_article_info_by_id($_POST['item_id']))
				{
					H::ajax_json_output(Application::RSM(null, '-1', _t('指定项目不存在')));
				}

				if (!$this->user_info['permission']['edit_question_topic'] AND $this->user_id != $article_info['uid'])
				{
					H::ajax_json_output(Application::RSM(null, '-1', _t('你没有权限进行此操作')));
				}
			break;
		}

		if (!($this->user_info['permission']['is_administortar'] OR $this->user_info['permission']['is_moderator']))
		{
			if ($this->user_info['permission']['function_interval'] AND Application::cache()->get('function_interval_timer_question_topic_last_edit_' . $this->user_id) == $_POST['item_id'])
			{
				Application::cache()->set('function_interval_timer_question_topic_' . $this->user_id, time(), 86400);
			}
			else if ($this->user_info['permission']['function_interval'] AND ((time() - Application::cache()->get('function_interval_timer_question_topic_' . $this->user_id)) < $this->user_info['permission']['function_interval']))
			{
				H::ajax_json_output(Application::RSM(null, '-1', _t('灌水预防机制已经打开, 在 %s 秒内不能操作', $this->user_info['permission']['function_interval'])));
			}
		}

		if (trim($_POST['topic_title']) == '')
		{
			H::ajax_json_output(Application::RSM(null, '-1', _t('请输入话题标题')));
		}

		if (strstr($_POST['topic_title'], '/') OR strstr($_POST['topic_title'], '-') OR strstr($_POST['topic_title'], '&'))
		{
			H::ajax_json_output(Application::RSM(null, '-1', _t('话题标题不能包含 / - &')));
		}

		if (! $this->model('topic')->getTopicIdByTitle($_POST['topic_title']) AND get_setting('topic_title_limit') AND cjk_strlen($_POST['topic_title']) > get_setting('topic_title_limit'))
		{
			H::ajax_json_output(Application::RSM(null, '-1', _t('话题标题字数不得超过 %s 字节', get_setting('topic_title_limit'))));
		}

		switch ($_POST['type'])
		{
			case 'question':
				if ($question_info['lock'] AND ! ($this->user_info['permission']['is_administortar'] or $this->user_info['permission']['is_moderator']))
				{
					H::ajax_json_output(Application::RSM(null, '-1', _t('锁定问题不能添加话题')));
				}
			break;

			case 'article':
				if ($article_info['lock'] AND ! ($this->user_info['permission']['is_administortar'] or $this->user_info['permission']['is_moderator']))
				{
					H::ajax_json_output(Application::RSM(null, '-1', _t('锁定文章不能添加话题')));
				}
			break;
		}

		if (sizeof($this->model('topic')->get_topics_by_item_id($_POST['item_id'], $_POST['type'])) >= get_setting('question_topics_limit') AND get_setting('question_topics_limit'))
		{
			H::ajax_json_output(Application::RSM(null, '-1', _t('单个问题或文章话题数量最多为 %s 个, 请调整话题数量', get_setting('question_topics_limit'))));
		}

		if (! $topic_id = $this->model('topic')->saveTopic($_POST['topic_title'], $this->user_id, $this->user_info['permission']['create_topic']))
		{
			H::ajax_json_output(Application::RSM(null, '-1', _t('话题已锁定或没有创建话题权限, 不能添加话题')));
		}

		$this->model('topic')->setTopicItemRelation($this->user_id, $topic_id, $_POST['item_id'], $_POST['type']);

		if (!($this->user_info['permission']['is_administortar'] OR $this->user_info['permission']['is_moderator']))
		{
			Application::cache()->set('function_interval_timer_question_topic_' . $this->user_id, time(), 86400);
			Application::cache()->set('function_interval_timer_question_topic_last_edit_' . $this->user_id, intval($_POST['item_id']), 86400);
		}

		H::ajax_json_output(Application::RSM(array(
			'topic_id' => $topic_id,
			'topic_url' => get_js_url('topic/' . $topic_id)
		), 1, null));
	}

	public function focus_topics_list_action()
	{
		if ($topics_list = $this->model('topic')->get_focus_topic_list($this->user_id, intval($_GET['page']) * 10 . ', 10'))
		{
			foreach ($topics_list AS $key => $val)
			{
				$topics_list[$key]['action_list'] = $this->model('posts')->get_posts_list('question', 1, 3, 'new', explode(',', $val['topic_id']));
			}
		}

		View::assign('topics_list', $topics_list);

		if (is_mobile())
		{
			View::output('m/ajax/focus_topics_list');
		}
		else
		{
			View::output('topic/ajax/focus_topics_list');
		}
	}
}