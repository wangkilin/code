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

class HTTP
{

	/**
	 * NO CACHE 文件头
	 *
	 * @param $type
	 * @param $charset
	 */
	public static function setHeaderNoCache($type = 'text/html', $charset = 'utf-8')
	{
		header('Expires: Mon, 26 Jul 1997 08:00:00 GMT');              // Date in the past
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
		header('Cache-Control: no-cache, must-revalidate');            // HTTP/1.1
		header('Pragma: no-cache');
		header('Content-Type: ' . $type . '; charset=' . $charset . '');
	}

	/**
	 * 获取 COOKIE
	 *
	 * @param $name
	 */
	public static function get_cookie($name)
	{
		if (isset($_COOKIE[G_COOKIE_PREFIX . $name]))
		{
			return $_COOKIE[G_COOKIE_PREFIX . $name];
		}

		return false;
	}

	/**
	 * 设置 COOKIE
	 *
	 * @param $name
	 * @param $value
	 * @param $expire
	 * @param $path
	 * @param $domain
	 * @param $secure
	 * @param $httponly
	 */
	public static function set_cookie($name, $value = '', $expire = null, $path = '/', $domain = null, $secure = false, $httponly = false)
	{
		if (! $domain and G_COOKIE_DOMAIN)
		{
			$domain = G_COOKIE_DOMAIN;
		}

		return setcookie(G_COOKIE_PREFIX . $name, $value, $expire, $path, $domain, $secure, $httponly);
	}

	/**
	 * 显示404页面不存在错误
	 * @param string $tplPath 模板文件路劲
	 */
	public static function error_404($tplPath='')
	{
		if ($_POST['_post_type'] == 'ajax') {
			H::ajax_json_output(Application::RSM(null, -1, 'HTTP/1.1 404 Not Found'));
		} else {
			header('HTTP/1.1 404 Not Found');

			$tplPath = $tplPath ? $tplPath : 'global/error_404';

			View::output($tplPath);
			exit;
		}
	}

	/**
	 * 显示404页面不存在错误
	 * @param string $tplPath 模板文件路劲
	 */
	public static function error_403($tplPath='')
	{
		if ($_POST['_post_type'] == 'ajax') {
			H::ajax_json_output(Application::RSM(null, -1, 'HTTP/1.1 403 Forbidden'));
		} else {
			header('HTTP/1.1 403 Forbidden');

			$tplPath = $tplPath ? $tplPath : 'global/error_403';

			View::output($tplPath);
			exit;
		}
	}

	/**
	 * 点击页面链接， 重新加载页面
	 * @param string $tplPath 模板文件路劲
	 */
	public static function click_and_reload($tplPath='')
	{
			$tplPath = $tplPath ? $tplPath : 'global/click_and_reload';

			View::output($tplPath);
			exit;
	}

	public static function parse_redirect_url($url)
	{
		if (substr($url, 0, 1) == '?')
		{
			$url = base_url() . $url;
		}
		else if (substr($url, 0, 1) == '/')
		{
			$url = get_js_url($url);
		}

		return $url;
	}

	public static function redirect($url)
	{
		if ($url = HTTP::parse_redirect_url($url)) {
			header('Location: ' . $url);
			exit;
		}
	}

	static function download_filename_header($filename)
	{
		if (preg_match('~&#([0-9]+);~', $filename))
		{
			$filename_conv = @iconv('utf-8', 'UTF-8//IGNORE', $filename);

			if ($filename_conv !== false)
			{
				$filename = $filename_conv;
			}

			$filename = preg_replace(
				'~&#([0-9]+);~e',
				"convert_int_to_utf8('\\1')",
				$filename
			);
		}

		$filename_charset = 'utf-8';

		$filename = preg_replace('#[\r\n]#', '', $filename);

		// Opera and IE have not a clue about this, mozilla puts on incorrect extensions.
		if (self::is_browser('mozilla'))
		{
			$filename = "filename*=" . $filename_charset . "''" . rawurlencode($filename);
			//$filename = "filename==?'utf-8'?B?" . base64_encode($filename) . "?=";
		}
		else
		{
			// other browsers seem to want names in UTF-8
			if ($filename_charset != 'utf-8' AND function_exists('iconv'))
			{
				$filename_conv = iconv($filename_charset, 'UTF-8//IGNORE', $filename);

				if ($filename_conv !== false)
				{
					$filename = $filename_conv;
				}
			}

			if (self::is_browser('opera') OR self::is_browser('konqueror') OR self::is_browser('safari'))
			{
				// Opera / Konqueror does not support encoded file names
				$filename = 'filename="' . str_replace('"', '', $filename) . '"';
			}
			else if (self::is_browser('ie'))
			{
				$filename = 'filename="' . str_replace('+', ' ', urlencode($filename)) . '"';
			}
			else
			{
				// encode the filename to stay within spec
				$filename = 'filename="' . rawurlencode($filename) . '"';
			}
		}

		return $filename;
	}

	static function force_download_header($filename, $filesize = 0, $modifytime = 0)
	{
		$range = 0;

		if ($_SERVER['HTTP_RANGE'])
		{
			list($range) = explode('-',(str_replace('bytes=', '', $_SERVER['HTTP_RANGE'])));
		}

		ob_end_clean();

		header("Cache-Control: no-cache, must-revalidate");
		header("Pragma: no-cache");
		header('Date: ' . gmdate('D, d M Y H:i:s', $modifytime) . ' GMT');
		header('Content-Disposition: attachment; ' . self::download_filename_header($filename));
		header("Content-Type: application/octet-stream");	// has bug with IE
		//header('HTTP/1.1 206 Partial Content');
		header('Accept-Ranges: bytes');

		if ($filesize)
		{
			if ($_SERVER['HTTP_RANGE'])
			{
				$rangesize = ($filesize - $range) > 0 ?  ($filesize - $range) : 0;

				header('Content-Length: ' . $rangesize);
				header('Content-Range: bytes ' . $range . '-' . ($filesize - 1) . '/' . ($filesize));
			}
			else
			{
				header('Content-Length: ' . $filesize);
			}
		}
	}

	/**
	 * Browser detection system - returns whether or not the visiting browser is the one specified
	 *
	 * @param	string	Browser name (opera, ie, mozilla, firebord, firefox... etc. - see $is array)
	 * @param	float	Minimum acceptable version for true result (optional)
	 *
	 * @return	boolean
	 */
	public static function is_browser($browser, $version = 0)
	{
		static $is;

		if (! is_array($is))
		{
			$useragent = strtolower($_SERVER['HTTP_USER_AGENT']);

			$is = array(
				'opera' => 0,
				'ie' => 0,
				'mozilla' => 0,
				'firebird' => 0,
				'firefox' => 0,
				'camino' => 0,
				'konqueror' => 0,
				'safari' => 0,
				'webkit' => 0,
				'webtv' => 0,
				'netscape' => 0,
				'mac' => 0
			);

			// detect opera
			# Opera/7.11 (Windows NT 5.1; U) [en]
			# Mozilla/4.0 (compatible; MSIE 6.0; MSIE 5.5; Windows NT 5.0) Opera 7.02 Bork-edition [en]
			# Mozilla/4.0 (compatible; MSIE 6.0; MSIE 5.5; Windows NT 4.0) Opera 7.0 [en]
			# Mozilla/4.0 (compatible; MSIE 5.0; Windows 2000) Opera 6.0 [en]
			# Mozilla/4.0 (compatible; MSIE 5.0; Mac_PowerPC) Opera 5.0 [en]
			if (strpos($useragent, 'opera') !== false)
			{
				preg_match('#opera(/| )([0-9\.]+)#', $useragent, $regs);
				$is['opera'] = $regs[2];
			}

			// detect internet explorer
			# Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; Q312461)
			# Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.0.3705)
			# Mozilla/4.0 (compatible; MSIE 5.22; Mac_PowerPC)
			# Mozilla/4.0 (compatible; MSIE 5.0; Mac_PowerPC; e504460WanadooNL)
			if (strpos($useragent, 'msie ') !== false and ! $is['opera'])
			{
				preg_match('#msie ([0-9\.]+)#', $useragent, $regs);
				$is['ie'] = $regs[1];
			}

			// detect macintosh
			if (strpos($useragent, 'mac') !== false)
			{
				$is['mac'] = 1;
			}

			// detect safari
			# Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en-us) AppleWebKit/74 (KHTML, like Gecko) Safari/74
			# Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en) AppleWebKit/51 (like Gecko) Safari/51
			if (strpos($useragent, 'applewebkit') !== false and $is['mac'])
			{
				preg_match('#applewebkit/(\d+)#', $useragent, $regs);
				$is['webkit'] = $regs[1];

				if (strpos($useragent, 'safari') !== false)
				{
					preg_match('#safari/([0-9\.]+)#', $useragent, $regs);
					$is['safari'] = $regs[1];
				}
			}

			// detect konqueror
			# Mozilla/5.0 (compatible; Konqueror/3.1; Linux; X11; i686)
			# Mozilla/5.0 (compatible; Konqueror/3.1; Linux 2.4.19-32mdkenterprise; X11; i686; ar, en_US)
			# Mozilla/5.0 (compatible; Konqueror/2.1.1; X11)
			if (strpos($useragent, 'konqueror') !== false)
			{
				preg_match('#konqueror/([0-9\.-]+)#', $useragent, $regs);
				$is['konqueror'] = $regs[1];
			}

			// detect mozilla
			# Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.4b) Gecko/20030504 Mozilla
			# Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.2a) Gecko/20020910
			# Mozilla/5.0 (X11; U; Linux 2.4.3-20mdk i586; en-US; rv:0.9.1) Gecko/20010611
			if (strpos($useragent, 'gecko') !== false and ! $is['safari'] and ! $is['konqueror'])
			{
				preg_match('#gecko/(\d+)#', $useragent, $regs);
				$is['mozilla'] = $regs[1];

				// detect firebird / firefox
				# Mozilla/5.0 (Windows; U; WinNT4.0; en-US; rv:1.3a) Gecko/20021207 Phoenix/0.5
				# Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.4b) Gecko/20030516 Mozilla Firebird/0.6
				# Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.4a) Gecko/20030423 Firebird Browser/0.6
				# Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.6) Gecko/20040206 Firefox/0.8
				if (strpos($useragent, 'firefox') !== false or strpos($useragent, 'firebird') !== false or strpos($useragent, 'phoenix') !== false)
				{
					preg_match('#(phoenix|firebird|firefox)( browser)?/([0-9\.]+)#', $useragent, $regs);
					$is['firebird'] = $regs[3];

					if ($regs[1] == 'firefox')
					{
						$is['firefox'] = $regs[3];
					}
				}

				// detect camino
				# Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en-US; rv:1.0.1) Gecko/20021104 Chimera/0.6
				if (strpos($useragent, 'chimera') !== false or strpos($useragent, 'camino') !== false)
				{
					preg_match('#(chimera|camino)/([0-9\.]+)#', $useragent, $regs);
					$is['camino'] = $regs[2];
				}
			}

			// detect web tv
			if (strpos($useragent, 'webtv') !== false)
			{
				preg_match('#webtv/([0-9\.]+)#', $useragent, $regs);
				$is['webtv'] = $regs[1];
			}

			// detect pre-gecko netscape
			if (preg_match('#mozilla/([1-4]{1})\.([0-9]{2}|[1-8]{1})#', $useragent, $regs))
			{
				$is['netscape'] = "$regs[1].$regs[2]";
			}
		}

		// sanitize the incoming browser name
		$browser = strtolower($browser);
		if (substr($browser, 0, 3) == 'is_')
		{
			$browser = substr($browser, 3);
		}

		// return the version number of the detected browser if it is the same as $browser
		if ($is["$browser"])
		{
			// $version was specified - only return version number if detected version is >= to specified $version
			if ($version)
			{
				if ($is["$browser"] >= $version)
				{
					return $is["$browser"];
				}
			}
			else
			{
				return $is["$browser"];
			}
		}

		// if we got this far, we are not the specified browser, or the version number is too low
		return 0;
	}

	public static function request($url, $method, $data = null, $timeout = 15, $header = null, $cookie = null)
	{
		if (defined('WECENTER_CURL_USERAGENT'))
		{
			$user_agent = WECENTER_CURL_USERAGENT;
		}
		else
		{
			$user_agent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_4) AppleWebKit/600.7.12 (KHTML, like Gecko) Version/8.0.7 Safari/600.7.12';
		}

		$headers = array(
			'API-RemoteIP' => fetch_ip()
		);

		if ($header)
		{
			$headers = array_merge($header, $headers);
		}

		$options = array(
			'useragent' => $user_agent,
			'timeout' => $timeout,
			'cookies' => $cookie,
			'verify' => false,
			'verifyname' => false,
		);

		switch (strtoupper($method))
		{
			default:
			case 'GET':
				$request = Services_Requests::get($url, $headers, $options);
			break;

			case 'POST':
				$request = Services_Requests::post($url, $headers, $data, $options);
			break;

			case 'DELETE':
				$request = Services_Requests::delete($url, $headers, $options);
			break;

			case 'PUT':
				$request = Services_Requests::put($url, $headers, $data, $options);
			break;

			case 'PATCH':
				$request = Services_Requests::put($url, $headers, $data, $options);
			break;
		}

		if ($request->status_code == 200)
		{
			return $request->body;
		}
	}
}
