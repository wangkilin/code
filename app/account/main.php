<?php
defined('iCodeBang_Com') OR die('Access denied!');

class main extends BaseController
{
	public function get_access_rule()
	{
		$rule_action['rule_type'] = 'black';

		$rule_action['actions'] = array(
			'complete_profile'
		);

		return $rule_action;
	}

	public function setup()
	{
		HTTP::setHeaderNoCache();
	}

	public function index_action()
	{
		HTTP::redirect('/account/setting/');
	}

	public function captcha_action()
	{
		Application::captcha()->generate();
	}

	public function logout_action($return_url = null)
	{
		if ($_GET['return_url'])
		{
			$url = strip_tags(urldecode($_GET['return_url']));
		}
		else if (! $return_url)
		{
			$url = '/';
		}
		else
		{
			$url = $return_url;
		}

		if ($_GET['key'] != md5(session_id()))
		{
			H::redirect_msg(Application::lang()->_t('正在准备退出, 请稍候...'), '/account/logout/?return_url=' . urlencode($url) . '&key=' . md5(session_id()));
		}

		$this->model('account')->logout();

		$this->model('admin')->admin_logout();

		if (get_setting('ucenter_enabled') == 'Y')
		{
			if ($uc_uid = $this->model('ucenter')->is_uc_user($this->user_info['email']))
			{
				$sync_code = $this->model('ucenter')->sync_logout($uc_uid);
			}

			H::redirect_msg(Application::lang()->_t('您已退出站点, 现在将以游客身份进入站点, 请稍候...') . $sync_code, $url);
		}
		else
		{
			HTTP::redirect($url);
		}
	}

	public function login_action()
	{
		$url = base64_decode($_GET['url']);

		if ($this->user_id)
		{
			if ($url)
			{
				header('Location: ' . $url);
			}
			else
			{
				HTTP::redirect('/');
			}
		}

		if (is_mobile())
		{
			HTTP::redirect('/m/login/url-' . $_GET['url']);
		}

		$this->crumb(Application::lang()->_t('登录'), '/account/login/');

		View::import_css('css/login.css');

		// md5 password...
		if (get_setting('ucenter_enabled') != 'Y')
		{
			View::import_js('js/md5.js');
		}

		if ($_GET['url'])
		{
			$return_url = htmlspecialchars(base64_decode($_GET['url']));
		}
		else
		{
			$return_url = htmlspecialchars($_SERVER['HTTP_REFERER']);
		}

		View::assign('return_url', $return_url);

		View::output("account/login");
	}

	public function weixin_login_action()
	{
		if ($this->user_id OR !get_setting('weixin_app_id') OR !get_setting('weixin_app_secret') OR get_setting('weixin_account_role') != 'service')
		{
			HTTP::redirect('/');
		}

		$this->crumb(Application::lang()->_t('微信登录'), '/account/weixin_login/');

		View::output('account/weixin_login');
	}

	public function register_action()
	{
		if (is_mobile() AND !$_GET['ignore_ua_check'])
		{
			HTTP::redirect('/m/register/?email=' . $_GET['email'] . '&icode=' . $_GET['icode']);
		}

		if (get_setting('register_type') == 'close')
		{
			H::redirect_msg(Application::lang()->_t('本站目前关闭注册'), '/');
		}
		else if (get_setting('register_type') == 'invite' AND !$_GET['icode'])
		{
			if (get_setting('weixin_app_id') AND get_setting('weixin_account_role') == 'service')
			{
				HTTP::redirect('/account/weixin_login/command-REGISTER');
			}

			H::redirect_msg(Application::lang()->_t('本站只接受邀请注册'), '/');
		}
		else if (get_setting('register_type') == 'weixin')
		{
			H::redirect_msg(Application::lang()->_t('本站只能通过微信注册'), '/');
		}

		if ($_GET['icode'])
		{
			if ($this->model('invitation')->check_code_available($_GET['icode']))
			{
				View::assign('icode', $_GET['icode']);
			}
			else
			{
				H::redirect_msg(Application::lang()->_t('邀请码无效或已经使用, 请使用新的邀请码'), '/');
			}
		}

		if ($this->user_id)
		{
			HTTP::redirect('/');
		}

		$this->crumb(Application::lang()->_t('注册'), '/account/register/');

		View::assign('job_list', $this->model('work')->get_jobs_list());

		View::import_css('css/register.css');

		View::output('account/register');
	}

	public function sync_login_action()
	{
		if (get_setting('ucenter_enabled') == 'Y')
		{
			if ($uc_uid = $this->model('ucenter')->is_uc_user($this->user_info['email']))
			{
				$sync_code = $this->model('ucenter')->sync_login($uc_uid);
			}
		}

		if ($_GET['url'])
		{
			$url = base64_decode($_GET['url']);
		}

		$base_url = base_url();

		if (!$url OR strstr($url, '://') AND substr($url, 0, strlen($base_url)) != $base_url)
		{
			$url = '/';
		}

		H::redirect_msg(Application::lang()->_t('欢迎回来: %s , 正在带您进入站点...', $this->user_info['user_name']) . $sync_code, $url);
	}

	public function valid_email_action()
	{
		if (!Application::session()->valid_email)
		{
			HTTP::redirect('/');
		}

		if (!$user_info = $this->model('account')->get_user_info_by_email(Application::session()->valid_email))
		{
			HTTP::redirect('/');
		}

		if ($user_info['valid_email'])
		{
			H::redirect_msg(Application::lang()->_t('邮箱已通过验证，请返回登录'), '/account/login/');
		}

		$this->crumb(Application::lang()->_t('邮件验证'), '/account/valid_email/');

		View::import_css('css/register.css');

		View::assign('email', Application::session()->valid_email);

		View::output("account/valid_email");
	}

	public function valid_email_active_action()
	{
		if (!$active_code_row = $this->model('active')->get_active_code($_GET['key'], 'VALID_EMAIL'))
		{
			H::redirect_msg(Application::lang()->_t('链接已失效, 请使用最新的验证链接'), '/');
		}

		if ($active_code_row['active_time'] OR $active_code_row['active_ip'])
		{
			H::redirect_msg(Application::lang()->_t('邮箱已通过验证, 请返回登录'), '/account/login/');
		}

		$users = $this->model('account')->getUserById($active_code_row['uid']);

		if ($users['valid_email'])
		{
			H::redirect_msg(Application::lang()->_t('帐户已激活, 请返回登录'), '/account/login/');
		}

		$this->crumb(Application::lang()->_t('邮件验证'), '/account/valid_email/');

		View::assign('active_code', $_GET['key']);

		View::assign('email', $users['email']);

		View::import_css('css/register.css');

		View::output('account/valid_email_active');
	}

	public function complete_profile_action()
	{
		if ($this->user_info['email'])
		{
			HTTP::redirect('/');
		}

		View::import_css('css/register.css');

		View::output('account/complete_profile');
	}

	public function valid_approval_action()
	{
		if ($this->user_id AND $this->user_info['group_id'] != 3)
		{
			HTTP::redirect('/');
		}

		View::import_css('css/register.css');

		View::output('account/valid_approval');
	}
}