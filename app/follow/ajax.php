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

define('IN_AJAX', TRUE);

if (!defined('iCodeBang_Com'))
{
	die;
}

class ajax extends Controller
{
	public function setup()
	{
		HTTP::setHeaderNoCache();
	}

	public function follow_people_action()
	{
		if (! $_POST['uid'] OR $_POST['uid'] == $this->user_id)
		{
			die;
		}

		// 首先判断是否存在关注
		if ($this->model('follow')->user_follow_check($this->user_id, $_POST['uid']))
		{
			$action = 'remove';

			$this->model('follow')->user_follow_del($this->user_id, $_POST['uid']);
		}
		else
		{
			$action = 'add';

			$this->model('follow')->user_follow_add($this->user_id, $_POST['uid']);

			$this->model('notify')->send($this->user_id, $_POST['uid'], notifyModel::TYPE_PEOPLE_FOCUS, notifyModel::CATEGORY_PEOPLE, $this->user_id, array(
				'from_uid' => $this->user_id
			));

			$this->model('email')->action_email('FOLLOW_ME', $_POST['uid'], get_js_url('/user/' . $this->user_info['url_token']), array(
				'user_name' => $this->user_info['user_name'],
			));
		}

		H::ajax_json_output(Application::RSM(array(
			'type' => $action
		), 1, null));
	}
}