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

class main extends BaseController
{
	public function get_access_rule()
	{
		$rule_action['rule_type'] = "white";

		if ($this->user_info['permission']['search_avail'] AND $this->user_info['permission']['visit_site'])
		{
			$rule_action['rule_type'] = "black"; //'black'黑名单,黑名单中的检查  'white'白名单,白名单以外的检查
		}

		$rule_action['actions'] = array();

		return $rule_action;
	}

	public function setup()
	{
		HTTP::setHeaderNoCache();

		$this->crumb(Application::lang()->_t('搜索'), '/search/');
	}

	public function index_action()
	{
		if ($_POST['q'])
		{
			$url = '/search/q-' . base64_encode($_POST['q']);

			if ($_GET['is_recommend'])
			{
				$url .= '__is_recommend-1';
			}

			HTTP::redirect($url);
		}

		$keyword = htmlspecialchars(base64_decode($_GET['q']));

		$this->crumb($keyword, '/search/q-' . urlencode($keyword));

		if (!$keyword)
		{
			HTTP::redirect('/');
		}

		View::assign('keyword', $keyword);
		View::assign('split_keyword', implode(' ', $this->model('system')->analysis_keyword($keyword)));

		View::output('search/index');
	}
}