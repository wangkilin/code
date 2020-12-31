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

class core_config
{
	private $config = array();

	function get($config_id)
	{
		if (defined('IN_SAE'))
		{
			switch ($config_id)
			{
				case 'database':
					return (object)array(
						'charset' => 'utf8',
						'prefix' => 'aws_',
						'driver' => 'PDO_MYSQL',
						'master' => array(
							'host' => SAE_MYSQL_HOST_M . ':' . SAE_MYSQL_PORT,
							'username' =>  SAE_MYSQL_USER,
							'password' => SAE_MYSQL_PASS,
							'dbname' => SAE_MYSQL_DB
						),
						'slave' => array(
							'host' => SAE_MYSQL_HOST_S . ':' . SAE_MYSQL_PORT,
							'username' =>  SAE_MYSQL_USER,
							'password' => SAE_MYSQL_PASS,
							'dbname' => SAE_MYSQL_DB
						)
					);
				break;
			}
		}

		if (isset($this->config[$config_id]))
		{
			return $this->config[$config_id];
		}
		else
		{
			return $this->load_config($config_id);
		}
	}

    /**
     * 从配置文件中载入配置信息
     * @param string $config_id  配置信息名称， 对应到配置文件名称
     *
     * @return object 配置信息
     */
	public function load_config($config_id)
	{
		if (defined('CONF_PATH')) {
		    $confFile = CONF_PATH . '/' . $config_id . '.php';
		} else {
		    $confFile = INC_PATH . 'config/' . $config_id . '.php';
		}
		if (substr($config_id, -10) == '.extension' OR !file_exists($confFile))
		{
			throw new Zend_Exception('The configuration file config/' . $config_id . '.php does not exist.');
		}
        include_once($confFile);

		if (! is_array($config))
		{
			throw new Zend_Exception('Your config/' . $config_id . '.php file does not appear to contain a valid configuration array.');
		}

		$this->config[$config_id] = (object)$config;

		return $this->config[$config_id];
	}

    /**
     * 设置配置信息， 将配置信息存储到文件中
     * @param string $config_id 配置信息全局名称， 会被转换成文件名
     * @param array  $data      配置信息关联数组
     *
     * @return int  >0表示文件写入成功， =0表示文件写入失败
     */
	public function set($config_id, $data)
	{
		if (!$data || ! is_array($data))
		{
			throw new Zend_Exception('config data type error');
		}

		$content = "<?php\n\n";

		foreach($data as $key => $val) {
			if (is_array($val)) {
				$content .= "\$config['{$key}'] = " . var_export($val, true) . ";";
			} else if (is_bool($val)) {
				$content .= "\$config['{$key}'] = " . ($val ? 'true' : 'false') . ";";
			} else {
				$content .= "\$config['{$key}'] = '" . addcslashes($val, "'") . "';";
			}

			$content .= "\r\n";
		}

		if (defined('CONF_PATH')) {
		    $confFile = CONF_PATH . '/' . $config_id . '.php';
		} else {
		    $confFile = INC_PATH . 'config/' . $config_id . '.php';
		}
		$fp = @fopen($confFile, "w");

		@chmod($confFile, 0755);

		$fwlen = @fwrite($fp, $content);

		@fclose($fp);

		return $fwlen;
	}
}
