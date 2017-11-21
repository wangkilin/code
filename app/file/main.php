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
	public function download_action()
	{
		$url = @base64_decode($_GET['url']);

		if (! $url)
		{
			HTTP::error_404();
		}

		$path = get_setting('upload_dir') . '/' . str_replace(get_setting('upload_url'), '', $url);

		if (strstr($path, '..') OR !file_exists($path))
		{
			HTTP::error_404();
		}

		HTTP::force_download_header(base64_decode($_GET['file_name']), filesize($path));

		readfile($path);
	}
}
