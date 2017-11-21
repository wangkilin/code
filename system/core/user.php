<?php
class core_user
{
	public function __construct()
	{
		/*if (Application::session()->client_info AND ! $_COOKIE[G_COOKIE_PREFIX . '_user_login'])
		{
			// Cookie 清除则 Session 也清除
			unset(Application::session()->client_info);
		}*/

		if (! Application::session()->client_info AND $_COOKIE[G_COOKIE_PREFIX . '_user_login'])
		{
			$auth_hash_key = md5(G_COOKIE_HASH_KEY . $_SERVER['HTTP_USER_AGENT']);

			// 解码 Cookie
			$sso_user_login = json_decode(Application::crypt()->decode($_COOKIE[G_COOKIE_PREFIX . '_user_login'], $auth_hash_key), true);

			if ($sso_user_login['user_name'] AND $sso_user_login['password'] AND $sso_user_login['uid'])
			{
				if ($user_info = Application::model('account')->check_hash_login($sso_user_login['user_name'], $sso_user_login['password']))
				{
					Application::session()->client_info['__CLIENT_UID'] = $user_info['uid'];
					Application::session()->client_info['__CLIENT_USER_NAME'] = $user_info['user_name'];
					Application::session()->client_info['__CLIENT_PASSWORD'] = $sso_user_login['password'];

					return true;
				}
			}

			HTTP::set_cookie('_user_login', '', null, '/', null, false, true);

			return false;
		}
	}

	public function get_info($key)
	{
		return Application::session()->client_info['__CLIENT_' . strtoupper($key)];
	}

	/**
	 * 查看用户是否具有某个权限
	 * @param string $permissionName 权限名称
	 * @return boolean
	 */
	static public function checkPermission ($permissionName)
	{
		$permissionList = Application::session()->permission;

		return is_array($permissionList) && !empty($permissionList[$permissionName]);
	}
}
