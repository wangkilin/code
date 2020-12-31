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

class core_lang
{
	private $lang = array();

	/**
	 * 构造函数
	 * 将制定的翻译语言载入
	 */
	public function __construct()
	{
		if (!defined('SYSTEM_LANG') OR SYSTEM_LANG == '') {
			return;
		}

		$language_file = WEB_ROOT_DIR . 'language/' . SYSTEM_LANG . '.php';

		if (file_exists($language_file)) {
			require $language_file;
		}

		if (is_array($language)) {
			$this->lang = $language;
		}
	}

	/**
	 * 翻译文字
	 * @param unknown $string
	 * @param string $replace
	 * @param string $display
	 * @return Ambigous <mixed, multitype:>
	 */
	public function translate($string, $replace = null, $display = false)
	{
		$search = '%s';

		if (is_array($replace)) {
			$search = array();
			for ($i=0; $i<count($replace); $i++) {
				$search[] = '%s' . $i;
			};
		}

		if ($translate = $this->lang[trim($string)]) {
			$string = $translate;
			if (isset($replace)) {
				$string = str_replace($search, $replace, $string);
			}

		} else if (isset($replace)) {
		    $string = str_replace($search, $replace, $string);
		}

		if (!$display) {
			return $string;
		}

		echo $string;
	}

	public function _t($string, $replace = null, $display = false)
	{
		return $this->translate($string, $replace, $display);
	}
}
