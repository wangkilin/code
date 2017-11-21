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
	var $per_page;

	public function get_access_rule()
	{
		$rule_action['rule_type'] = "white"; //黑名单,黑名单中的检查  'white'白名单,白名单以外的检查
		$rule_action['actions'] = array();
		return $rule_action;
	}

	function setup()
	{
		HTTP::setHeaderNoCache();

		$this->per_page = get_setting('notifications_per_page');
	}

	public function list_action()
	{
		if ($_GET['limit'])
		{
			$per_page = intval($_GET['limit']);
		}
		else
		{
			$per_page = $this->per_page;
		}

		$list = $this->model('notify')->list_notification($this->user_id, $_GET['flag'], intval($_GET['page']) * $per_page . ', ' . $per_page);

		if (!$list AND $this->user_info['notification_unread'] != 0)
		{
			$this->model('account')->update_notification_unread($this->user_id);
		}

		View::assign('flag', $_GET['flag']);
		View::assign('list', $list);

		if ($_GET['template'] == 'header_list')
		{
			View::output("notifications/ajax/header_list");
		}
		else if (is_mobile())
		{
			View::output('m/ajax/notifications_list');
		}
		else
		{
			View::output("notifications/ajax/list");
		}
	}

	public function read_notification_action()
	{
		if (isset($_GET['notification_id']))
		{
			$this->model('notify')->read_notification($_GET['notification_id'], $this->user_id);
		}
		else
		{
			$this->model('notify')->mark_read_all($this->user_id);
		}

		H::ajax_json_output(Application::RSM(null, 1, null));
	}
}