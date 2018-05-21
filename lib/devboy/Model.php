<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

importClass('Base');
importClass('Db_Query');

class Model extends Base
{
	/**
	 * 数据库只读模式
	 */
	const DB_MODE_READ = 1;
	/**
	 * 数据库只写模式
	 */
	const DB_MODE_WRITE = 2;
	/**
	 * 数据库读写模式
	 */
	const DB_MODE_READ_WRITE = 3;

	/**
	 * 存储连接池， 存放每个连接实例对象
	 * @var array
	 */
	private $_pool = array();
	/**
	 * 当前连接对象的别名
	 * @var unknown
	 */
	private $_currentAlias = null;

	protected $_db = null;

	protected $_dbMode = self::DB_MODE_READ_WRITE;

	protected $_table = '';

	protected $_prefix = '';

	protected $_pk = 'id';

	/**
	 * Class constructor
	 * @param string|array $config 数据表名称或者数据库连接参数数组
	 *
	 * @return	void
	 */
	public function __construct($config=array())
	{
	    if (is_string($config)) {
			$config = array('table'=>$config);
			$oConfig = & loadClass('Config');
			$config = array_merge($config, $oConfig->get('db'));
		}

		if (is_array($config) && isset($config['type'])) {
			$class = ucfirst($config['type']);
			if(''==$config['dir']) {
				$class = 'Db_' . $class;
			}
			isset($config['params']) or $config['params'] = $config;
			isset($config['dir'])    or $config['dir']    = '';
			isset($config['alias'])  or $config['alias']  = $class;
			isset($config['mode'])   or $config['mode']   = self::DB_MODE_READ_WRITE;
			empty($config['pk'])     or $this->_pk = $config['pk'];

		    $db = $this->dbFactory($class, $config['dir'], $config['params']);
		    if (is_object($db)) {
		        $this->_currentAlias = $config['alias'];
		        $this->_pool[$this->_currentAlias] = array('db' => $db, 'mode' => $config['mode']);
		    }

		    isset($config['table'])  AND $this->setTable($config['table']);
		    isset($config['prefix']) AND $this->setPrefix($config['prefix']);
		}
	}

	/**
	 * 数据库类实例工厂
	 * @param unknown $storageType
	 * @param string $dir
	 * @param unknown $config
	 */
	public function dbFactory ($class, $dir='', $config=null)
	{
	    $dir = is_string($dir) && is_dir($dir) ? $dir : null;
		$db = & loadClass($class, $dir, $config);

		//var_dump($db);
		if ($db instanceof Db_Abstract) {
			return $db;
		}


		return null;
	}

	public function setPrefix($prefix)
	{
		if (is_string($prefix)) {
			$this->_prefix = $prefix;
		}

		return $this;
	}

	public function getPrefix ()
	{
		return $this->_prefix;
	}

	/**
	 * 获取数据存储模型实例
	 * @param string $alias 连接别名
	 * @return null|object
	 */
	public function getDb ($alias=null)
	{
		$alias = isset($alias) ? $alias : $this->_currentAlias;
		return isset($alias, $this->_pool[$alias]) ? $this->_pool[$alias]['db'] : null;
	}

	public function setDb ($db, $alias)
	{
		if ($db instanceof Db_Abstract) {
			$this->_db = $db;
		}

		return $this;
	}

	/**
	 * 根据读写模式，随机获取数据库连接实例
	 * @param unknown $mode
	 * @return mixed|unknown
	 */
	public function getRandDbByMode ($mode)
	{
		$db = $this->_pool[$this->_currentAlias]['db'];
		$_dbList = array();
		foreach ($this->_pool as $_dbInfo) {
			if ($mode==$_dbInfo['mode'] || self::DB_MODE_READ_WRITE==$_dbInfo['mode']) {
				$_dbList[] = & $_dbInfo['db'];
			}
		}
		if ($_dbList) {
			$index = rand(0, count($_dbList)-1);
			$db = $_dbList[$index];
		}

		return $db;
	}

	public function setTable ($table)
	{
		is_string($table) AND $this->_table = $table;

		return $this;
	}

	public function getTable ($hasPrefix=true)
	{
		$table = $hasPrefix===true ? $this->_prefix . $this->_table : $this->_table;

		return $table;
	}

	public function loadDefaultDb ()
	{
		$config = & loadClass('Config');
		$dbSetting = $config->get('db');

	}

    /**
     * Load data
     *
     * @param int $id
     * @return array
     */
    public function getById($id, $colName='')
    {
        ''==$colName OR $colName = $this->_pk;
    	$where = array($colName=>$id);

    	return $this->_db->find($this->table(), array('where'=>$where) );
    }

    /**
     * Find result
     *
     * @param array|string $conditions 查询数据条件
     * @param string $table
     * @return array
     */
    public function select ($conditions=array(), $table='')
    {
    	$db = $this->getRandDbByMode(self::DB_MODE_READ);
    	$table = ''==$table ? $this->table() : $table;
        if (is_string($conditions)) {
        	$conditions = array('where' => $conditions);
        }

        $result = $db->select($table, $conditions);

        return $result;
    }

    /**
     * Count result
     *
     * @param string $where
     * @param string $table
     * @return int
     */
    public function count($where, $table = null)
    {
        if (null == $table) $table = $this->_table;

        try {
            $result = $this->db->count($where, $table);
            return $result;
        } catch (Exception $e) {
            $this->error(array('code' => self::SYSTEM_ERROR, 'msg' => $e->getMessage()));
            return false;
        }
    }

    /**
     * Query SQL
     *
     * @param string|array $sql SQL语句或者是待拼接的数据
     * @return mixed
     */
    public function query($query)
    {
    	$result = false;
		if (is_array($query)) {
			if (! isset($query['action']) ) {
				throw new Exception("Wrong query param,no action specified: )" . __METHOD__);
			    return $result;
			}
			$oQuery = new Db_Query($this->_db, $this->_prefix);
			$oQuery->query($query['action'], $queryInfo);
			$query = $oQuery->__toString();
		}
        $result = $this->sql($query);

        return $result;
    }

    /**
     * Get SQL result
     *
     * @param string $sql SQL语句
     * @param string $selectResultType 如果是select时，指定查询结果返回的类型： assoc|array|object
     * @return array
     */
    public function sql($sql, $selectResultType=null)
    {
    	$_sqlParts = explode(' ', trim($sql), 2);

    	if ('SELECT'==strtoupper(trim($_sqlParts[0])) ) {
    	    $db = $this->getRandDbByMode(self::DB_MODE_READ);
    	} else {
    		$db = $this->getRandDbByMode(self::DB_MODE_WRITE);
    	}

        $result = $db->sql($sql, $selectResultType);

        return $result;
    }

    /**
     * Insert
     *
     * @param array $data
     * @param string $table
     * @return boolean
     */
    public function insert($data, $table = null)
    {
        if (null == $table) $table = $this->_table;

        try {
            $result = $this->db->insert($data, $table);
            return $result;
        } catch (Exception $e) {
            $this->error(array('code' => self::SYSTEM_ERROR, 'msg' => $e->getMessage()));
            return false;
        }
    }

    /**
     * Update
     *
     * @param int $id
     * @param array $data
     * @return boolean
     */
    public function update($id, $data, $where='')
    {
        $where = $this->_pk . '=' . (is_int($id) ? $id : "'$id'");

        try {
            $result = $this->db->update($data, $where, $this->_table);
            return true;
        } catch (Exception $e) {
            $this->error(array('code' => self::SYSTEM_ERROR, 'msg' => $e->getMessage()));
            return false;
        }
    }

    /**
     * Delete
     *
     * @param string $where
     * @param string $table
     * @return boolean
     */
    public function delete($id, $col = null, $where)
    {
        if (is_null($col)) $col = $this->_pk;

        $where = $col . '=' . (is_int($id) ? $id : "'$id'");

        try {
            $result = $this->db->delete($where, $this->_table);
            return $result;
        } catch (Exception $e) {
            $this->error(array('code' => self::SYSTEM_ERROR, 'msg' => $e->getMessage()));
            return false;
        }
    }

    /**
     * Dynamic set vars
     *
     * @param string $key
     * @param mixed $value
     */
    public function __set($key, $value = null)
    {
        $this->$key = $value;
    }

    /**
     * Dynamic get vars
     *
     * @param string $key
     */
    public function __get($key)
    {
        switch ($key) {
            case 'db' :
                $this->db = $this->db();
                return $this->db;

            case 'cache' :
                $this->cache = $this->cache();
                return $this->cache;

            case 'helper':
                $this->helper = new Cola_Helper();
                return $this->helper;

            case 'com':
                $this->com = new Cola_Com();
                return $this->com;

            default:
                throw new Exception('Undefined property: ' . get_class($this). '::' . $key);
        }
    }

}
