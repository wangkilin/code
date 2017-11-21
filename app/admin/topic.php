<?php
class topic extends AdminController
{
	public function setup()
	{
		View::assign('menu_list', $this->model('admin')->fetch_menu_list('topic/list'));
	}

	/**
	 * 话题列表管理页面
	 */
	public function list_action()
	{
		$this->crumb(Application::lang()->_t('话题管理'), 'admin/topic/list/');
		$this->checkPostAndRedirect('/admin/topic/list/', array('keyword'));

		$where = array();
		if ($_GET['keyword']) {
			$where[] = "topic_title LIKE '" . $this->model('topic')->quote($_GET['keyword']) . "%'";
		}

		if ($_GET['discuss_count_min'] OR $_GET['discuss_count_min'] == '0') {
			$where[] = 'discuss_count >= ' . intval($_GET['discuss_count_min']);
		}

		if ($_GET['discuss_count_max'] OR $_GET['discuss_count_max'] == '0') {
			$where[] = 'discuss_count <= ' . intval($_GET['discuss_count_max']);
		}

		if ($_GET['start_date']) {
			$where[] = 'add_time >= ' . strtotime($_GET['start_date']);
		}

		if ($_GET['end_date']) {
			$where[] = 'add_time <= ' . strtotime('+1 day', strtotime($_GET['end_date']));
		}

		$topic_list = $this->model('topic')->getTopicList(implode(' AND ', $where), 'topic_id DESC', $this->per_page, $_GET['page']);

		$total_rows = $this->model('topic')->found_rows();

		if ($topic_list) {
			foreach ($topic_list AS $key => $topic_info) {
				$action_log = ACTION_LOG::get_action_by_event_id($topic_info['topic_id'], 1, ACTION_LOG::CATEGORY_TOPIC, implode(',', array(
					ACTION_LOG::ADD_TOPIC,
					ACTION_LOG::MOD_TOPIC,
					ACTION_LOG::MOD_TOPIC_DESCRI,
					ACTION_LOG::MOD_TOPIC_PIC,
					ACTION_LOG::DELETE_TOPIC,
					ACTION_LOG::ADD_RELATED_TOPIC,
					ACTION_LOG::DELETE_RELATED_TOPIC
				)), -1);

				$action_log = $action_log[0];

				$topic_list[$key]['last_edited_uid'] = $action_log['uid'];

				$topic_list[$key]['last_edited_time'] = $action_log['add_time'];

				$last_edited_uids[] = $topic_list[$key]['last_edited_uid'];
			}

			$users_info_query = $this->model('account')->getUsersByIds($last_edited_uids,false, false, true);

			if ($users_info_query) {
				foreach ($users_info_query AS $user_info) {
					$users_info[$user_info['uid']] = $user_info;
				}
			}
		}

		$parent_topic_list = $this->model('topic')->getRootTopics();

		$url_param = array();
		foreach($_GET as $key => $val) {
			if (!in_array($key, array('app', 'c', 'act', 'page'))) {
				$url_param[] = $key . '-' . $val;
			}
		}

		View::assign('pagination', Application::pagination()->initialize(array(
			'base_url' => get_js_url('/admin/topic/list/') . implode('__', $url_param),
			'total_rows' => $total_rows,
			'per_page' => $this->per_page
		))->create_links());

		View::assign('topics_count', $total_rows);
		View::assign('list', $topic_list);
		View::assign('users_info', $users_info);
		View::assign('parent_topic_list', $parent_topic_list);

		View::output('admin/topic/list');
	}

	/**
	 * 跟话题管理
	 */
	public function parent_action()
	{
		$this->crumb(Application::lang()->_t('根话题'), 'admin/topic/parent/');

		$topic_list = $this->model('topic')->getTopicList('is_parent = 1', 'topic_id DESC', $this->per_page, $_GET['page']);
		$total_rows = $this->model('topic')->found_rows();

		if ($topic_list) {
			foreach ($topic_list AS $key => $topic_info) {
				$action_log = ACTION_LOG::get_action_by_event_id($topic_info['topic_id'], 1, ACTION_LOG::CATEGORY_TOPIC, implode(',', array(
					ACTION_LOG::ADD_TOPIC,
					ACTION_LOG::MOD_TOPIC,
					ACTION_LOG::MOD_TOPIC_DESCRI,
					ACTION_LOG::MOD_TOPIC_PIC,
					ACTION_LOG::DELETE_TOPIC,
					ACTION_LOG::ADD_RELATED_TOPIC,
					ACTION_LOG::DELETE_RELATED_TOPIC
				)), -1);

				$action_log = $action_log[0];

				$topic_list[$key]['last_edited_uid'] = $action_log['uid'];

				$topic_list[$key]['last_edited_time'] = $action_log['add_time'];

				$last_edited_uids[] = $topic_list[$key]['last_edited_uid'];
			}

			$users_info_query = $this->model('account')->getUsersByIds($last_edited_uids);

			foreach ($users_info_query AS $user_info) {
				$users_info[$user_info['uid']] = $user_info;
			}
		}

		View::assign('pagination', Application::pagination()->initialize(array(
			'base_url' => get_js_url('/admin/topic/parent/'),
			'total_rows' => $total_rows,
			'per_page' => $this->per_page
		))->create_links());

		View::assign('list', $topic_list);
		View::assign('users_info', $users_info);

		View::output('admin/topic/parent');
	}

	public function edit_action()
	{
		if ($_GET['topic_id'])
		{
			$this->crumb(Application::lang()->_t('话题编辑'), 'admin/topic/edit/');

			$topic_info = $this->model('topic')->getTopicById($_GET['topic_id']);

			if (!$topic_info)
			{
				H::redirect_msg(Application::lang()->_t('话题不存在'), '/admin/topic/list/');
			}

			View::assign('topic_info', $topic_info);
		}
		else
		{
			$this->crumb(Application::lang()->_t('新建话题'), 'admin/topic/edit/');
		}

		View::assign('parent_topics', $this->model('topic')->getRootTopics());

		View::import_js(array('js/fileupload.js', 'js/bootstrap-multiselect.js'));
		View::import_css(G_STATIC_URL . '/css/bootstrap-multiselect.css');

		View::output('admin/topic/edit');
	}
}