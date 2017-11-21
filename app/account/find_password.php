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

class find_password extends Controller
{

	public function get_access_rule()
	{
		$rule_action['rule_type'] = 'black'; //黑名单,黑名单中的检查  'white'白名单,白名单以外的检查
		$rule_action['actions'] = array();

		return $rule_action;
	}

	public function setup()
	{
		$this->crumb(Application::lang()->_t('找回密码'), '/account/find_password/');

		View::import_css('css/register.css');
	}

	public function index_action()
	{
		View::output('account/find_password/index');
	}

	public function process_success_action()
	{
		View::assign('email', Application::session()->find_password);

		View::output('account/find_password/process_success');
	}

	public function modify_action()
	{
		if (is_mobile())
		{
			HTTP::redirect('/m/find_password_modify/?key=' . $_GET['key']);
		}

		if (!$active_code_row = $this->model('active')->get_active_code($_GET['key'], 'FIND_PASSWORD'))
		{
			H::redirect_msg(Application::lang()->_t('链接已失效'), '/');
		}

		if ($active_code_row['active_time'] OR $active_code_row['active_ip'])
		{
			H::redirect_msg(Application::lang()->_t('链接已失效'), '/');
		}

		View::output('account/find_password/modify');
	}
}