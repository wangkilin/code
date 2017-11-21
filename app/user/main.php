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
		if ($this->user_info['permission']['visit_people'] AND $this->user_info['permission']['visit_site'])
		{
			$rule_action['rule_type'] = 'black';
		}
		else
		{
			$rule_action['rule_type'] = 'white';
		}

		return $rule_action;
	}

	public function index_action()
	{
		if (isset($_GET['notification_id']))
		{
			$this->model('notify')->read_notification($_GET['notification_id'], $this->user_id);
		}

		if (is_mobile())
		{
			HTTP::redirect('/m/user/' . $_GET['id']);
		}

		if (is_digits($_GET['id']))
		{
			if (!$user = $this->model('account')->getUserById($_GET['id'], TRUE))
			{
				$user = $this->model('account')->get_user_info_by_username($_GET['id'], TRUE);
			}
		}
		else if ($user = $this->model('account')->get_user_info_by_username($_GET['id'], TRUE))
		{

		}
		else
		{
			$user = $this->model('account')->get_user_info_by_url_token($_GET['id'], TRUE);
		}

		if (!$user)
		{
			HTTP::error_404();
		}

		if ($user['forbidden'] AND !$this->user_info['permission']['is_administortar'] AND !$this->user_info['permission']['is_moderator'])
		{
			header('HTTP/1.1 404 Not Found');

			H::redirect_msg(Application::lang()->_t('该用户已被封禁'), '/');
		}

		if (urldecode($user['url_token']) != $_GET['id'])
		{
			HTTP::redirect('/user/' . $user['url_token']);
		}

		$this->model('people')->update_views($user['uid']);

		View::assign('user', $user);

		$job_info = $this->model('account')->get_jobs_by_id($user['job_id']);

		View::assign('job_name', $job_info['job_name']);

		if ($user['weibo_visit'])
		{
			if ($users_sina = $this->model('openid_weibo_oauth')->get_weibo_user_by_uid($user['uid']))
			{
				View::assign('sina_weibo_url', 'http://www.weibo.com/' . $users_sina['id']);
			}
		}

		View::assign('education_experience_list', $this->model('education')->get_education_experience_list($user['uid']));

		$jobs_list = $this->model('work')->get_jobs_list();

		if ($work_experience_list = $this->model('work')->get_work_experience_list($user['uid']))
		{
			foreach ($work_experience_list as $key => $val)
			{
				$work_experience_list[$key]['job_name'] = $jobs_list[$val['job_id']];
			}
		}

		View::assign('work_experience_list', $work_experience_list);

		View::assign('user_follow_check', $this->model('follow')->user_follow_check($this->user_id, $user['uid']));

		$this->crumb(Application::lang()->_t('%s 的个人主页', $user['user_name']), 'user/' . $user['url_token']);

		View::import_css('css/user.css');

		View::assign('reputation_topics', $this->model('people')->get_user_reputation_topic($user['uid'], $user['reputation'], 12));

		View::assign('fans_list', $this->model('follow')->get_user_fans($user['uid'], 5));
		View::assign('friends_list', $this->model('follow')->get_user_friends($user['uid'], 5));
		View::assign('focus_topics', $this->model('topic')->get_focus_topic_list($user['uid'], 10));

		View::assign('user_actions_questions', $this->model('actions')->get_user_actions($user['uid'], 5, ACTION_LOG::ADD_QUESTION, $this->user_id));
		View::assign('user_actions_answers', $this->model('actions')->get_user_actions($user['uid'], 5, ACTION_LOG::ANSWER_QUESTION, $this->user_id));
		View::assign('user_actions', $this->model('actions')->get_user_actions($user['uid'], 5, implode(',', array(
			ACTION_LOG::ADD_QUESTION,
			ACTION_LOG::ANSWER_QUESTION,
			ACTION_LOG::ADD_REQUESTION_FOCUS,
			ACTION_LOG::ADD_AGREE,
			ACTION_LOG::ADD_TOPIC,
			ACTION_LOG::ADD_TOPIC_FOCUS,
			ACTION_LOG::ADD_ARTICLE
		)), $this->user_id));

		View::output('user/index');
	}

	public function index_square_action()
	{
		if (is_mobile())
		{
			HTTP::redirect('/m/user/');
		}

		if (!$_GET['page'])
		{
			$_GET['page'] = 1;
		}

		$this->crumb(Application::lang()->_t('用户列表'), '/user/');

		if ($_GET['topic_id'])
		{
			if ($helpful_users = $this->model('topic')->get_helpful_users_by_topic_ids($this->model('topic')->getChildTopicIds($_GET['topic_id']), get_setting('contents_per_page'), 4))
			{
				foreach ($helpful_users AS $key => $val)
				{
					$users_list[$key] = $val['user_info'];
					$users_list[$key]['experience'] = $val['experience'];


					foreach ($val['experience'] AS $exp_key => $exp_val)
					{
						$users_list[$key]['total_agree_count'] += $exp_val['agree_count'];
					}
				}
			}
		}
		else
		{
			$where = array();

			if ($_GET['group_id'])
			{
				$where[] = 'group_id = ' . intval($_GET['group_id']);
			}

			$users_list = $this->model('account')->get_users_list(implode('', $where), calc_page_limit($_GET['page'], get_setting('contents_per_page')), true, false, 'reputation DESC');

			$where[] = 'forbidden = 0 AND group_id <> 3';

			View::assign('pagination', Application::pagination()->initialize(array(
				'base_url' => get_js_url('/user/group_id-' . $_GET['group_id']),
				'total_rows' => $this->model('account')->get_user_count(implode(' AND ', $where)),
				'per_page' => get_setting('contents_per_page')
			))->create_links());
		}

		if ($users_list)
		{
			foreach ($users_list as $key => $val)
			{
				if ($val['reputation'])
				{
					$reputation_users_ids[] = $val['uid'];
					$users_reputations[$val['uid']] = $val['reputation'];
				}

				$uids[] = $val['uid'];
			}

			if (!$_GET['topic_id'])
			{
				$reputation_topics = $this->model('people')->get_users_reputation_topic($reputation_users_ids, $users_reputations, 5);

				foreach ($users_list as $key => $val)
				{
					$users_list[$key]['reputation_topics'] = $reputation_topics[$val['uid']];
				}
			}

			if ($uids AND $this->user_id)
			{
				$users_follow_check = $this->model('follow')->users_follow_check($this->user_id, $uids);
			}

			foreach ($users_list as $key => $val)
			{
				$users_list[$key]['focus'] = $users_follow_check[$val['uid']];
			}

			View::assign('users_list', array_values($users_list));
		}

		if (!$_GET['group_id'])
		{
			View::assign('parent_topics', $this->model('topic')->getRootTopics());
		}

		View::assign('custom_group', $this->model('account')->get_user_group_list(0, 1));

		View::output('user/square');
	}
}
