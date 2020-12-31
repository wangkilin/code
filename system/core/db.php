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

class core_db
{
	private $db;
	private $current_db_object;

	public function __construct()
	{
		if (loadClass('core_config')->get('system')->debug)
		{
			$start_time = microtime(TRUE);
		}

		if (loadClass('core_config')->get('database')->charset)
		{
			loadClass('core_config')->get('database')->master['charset'] = loadClass('core_config')->get('database')->charset;

			if (loadClass('core_config')->get('database')->slave)
			{
				loadClass('core_config')->get('database')->slave['charset'] = loadClass('core_config')->get('database')->charset;
			}
		}

		$this->db['master'] = Zend_Db::factory(loadClass('core_config')->get('database')->driver, loadClass('core_config')->get('database')->master);

		try
		{
			$this->db['master']->query("SET sql_mode = ''");
		}
		catch (Exception $e)
		{
			throw new Zend_Exception('Can\'t connect master database: ' . $e->getMessage());
		}

		if (loadClass('core_config')->get('system')->debug AND class_exists('Application', false))
		{
			Application::debug_log('database', (microtime(TRUE) - $start_time), 'Connect Master DB');
		}

		if (loadClass('core_config')->get('database')->slave)
		{
			if (loadClass('core_config')->get('system')->debug)
			{
				$start_time = microtime(TRUE);
			}

			$this->db['slave'] = Zend_Db::factory(loadClass('core_config')->get('database')->driver, loadClass('core_config')->get('database')->slave);

			try
			{
				$this->db['slave']->query("SET sql_mode = ''");
			}
			catch (Exception $e)
			{
				throw new Zend_Exception('Can\'t connect slave database: ' . $e->getMessage());
			}

			if (loadClass('core_config')->get('system')->debug AND class_exists('Application', false))
			{
				Application::debug_log('database', (microtime(TRUE) - $start_time), 'Connect Slave DB');
			}
		}
		else
		{
			$this->db['slave'] =& $this->db['master'];
		}

		if (!defined('MYSQL_VERSION'))
		{
			define('MYSQL_VERSION', $this->db['master']->getServerVersion());
		}

		//Zend_Db_Table_Abstract::setDefaultAdapter($this->db['master']);
		$this->setObject();
	}

	public function setObject($db_object_name = 'master')
	{
		if (isset($this->db[$db_object_name]))
		{
			Zend_Registry::set('dbAdapter', $this->db[$db_object_name]);
			Zend_Db_Table_Abstract::setDefaultAdapter($this->db[$db_object_name]);

			$this->current_db_object = $db_object_name;

			return $this->db[$db_object_name];
		}

		throw new Zend_Exception('Can\'t find this db object: ' . $db_object_name);
	}
}
