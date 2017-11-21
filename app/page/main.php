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
		$rule_action['rule_type'] = 'black';
		$rule_action['actions'] = array();

		return $rule_action;
	}

	public function index_action()
	{
		if (!$page_info = $this->model('page')->getPageByToken($_GET['id']) OR $page_info['enabled'] == 0)
		{
			HTTP::error_404();
		}

		if ($page_info['title'])
		{
			View::assign('page_title', $page_info['title']);
		}

		if ($page_info['keywords'])
		{
			View::set_meta('keywords', $page_info['keywords']);
		}

		if ($page_info['description'])
		{
			View::set_meta('description', $page_info['description']);
		}

		View::assign('page_info', $page_info);

		View::output('page/index');
	}
}