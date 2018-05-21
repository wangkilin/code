<?php
importClass('Db_Query');

Abstract class Db_Abstract
{
	/**
	 * 数据库连接对象
	 *
	 * @var object|resource|null
	 */
	protected $_connection = null;

	/**
	 * 数据库设置
	 *
	 * @var array
	 */
	protected $_config = array(
			'host' => '',
			'port' => '',
			'username' => '',
			'password' => '',
			'database' => '',
			'charset'  => '',
			'options'  => array()
	);

	/**
	 * Query handler
	 *
	 * @var resource
	 */
	protected $_query = null;

	/**
	 * Last query sql
	 *
	 * @var string
	 */
	protected $_lastSql;

	/**
	 * Constructor.
	 * @param  array $config
	 */
	public function __construct($config)
	{
		foreach ($config as $_key=>$_value) {
			$_key = strtolower($_key);
			if (isset($this->_config[$_key])) {
				$this->_config[$_key] = $_value;
			}
		}

		if (''==$this->_config['host'] || ''==$this->_config['database']) {
			throw new Exception('Wrong database connection params', Constants::ERROR_DB_CONNECT_PARAMS);
		}

		$this->connectDb();
	}

	/**
	 * Get db connection
	 *
	 * @return resource
	 */
	public function getConnection()
	{
		return $this->_connection;
	}

	/**
	 * Returns the underlying database connection object or resource.
	 * If not presently connected, this initiates the connection.
	 *
	 * @return object|resource|null
	 */
	public function connectDb()
	{
		is_object($this->_connection) OR $this->_connect($this->_config);

		return $this;
	}

	/**
	 * Get SQL result
	 *
	 * @param string $sql
	 * @param string $type
	 * @return mixed
	 */
	public function sql($sql, $type = null)
	{
		$sql = trim($sql);
		$query = $this->query($sql);

		$_sqlParts = explode(' ', $sql, 2);
		switch (strtoupper(trim($_sqlParts[0]))) {
			case 'SELECT':
				$query = $this->fetchAll($type);
				break;

			case 'INSERT':
				$query = false===$query ? false : $this->lastInsertId();
				break;

			case 'UPDATE':
			case 'DELETE':
				$query = false===$query ? false : $this->affectedRows();
				break;

			default:
				break;
		}

		return $query;
	}

	protected function buildSql ($queryInfo)
	{

	}

	/**
	 * Get a result row
	 *
	 * @param string $sql
	 * @param string $type
	 * @return array
	 */
	public function row($sql, $type = null)
	{
		$this->query($sql);

		return $this->fetch($type);
	}

	/**
	 * Get first column of result
	 *
	 * @param string $sql
	 * @return string
	 */
	public function col($sql)
	{
		$this->query($sql);
		$result = $this->fetch();
		return empty($result) ? null : current($result);
	}

	/**
	 * Find data
	 *
	 * @param array $conditions
	 * @return array
	 */
	public function select($table, $conditions)
	{
		$result = array();

		if (is_string($conditions) ) {
			$conditions = array('where' => $conditions);
		}
		$conditions = settype($conditions, 'array');
		$conditions['table'] = $table;
		$conditions['query'] = self::SQL_SELECT;
		$sql = $this->buildSql($conditions);

		$data = $this->query($sql);

		return $data;
	}

	/**
	 * Insert
	 *
	 * @param array $data
	 * @param string $table
	 * @return boolean
	 */
	public function insert($table, $dataSet)
	{
		$keys = '';
		$values = '';
		foreach ($dataSet as $key => $value) {
			$keys .= "`$key`,";
			$values .= "'" . $this->escape($value) . "',";
		}
		$sql = "insert into $table (" . substr($keys, 0, -1) . ") values (" . substr($values, 0, -1) . ");";
		return $this->result($sql);
	}

	/**
	 * Update table
	 *
	 * @param array $data
	 * @param string $where
	 * @param string $table
	 * @return int
	 */
	public function update($table, $dataSet, $whereSet)
	{
		$tmp = array();

		foreach ($data as $key => $value) {
			$tmp[] = "`$key`='" . $this->escape($value) . "'";
		}

		$str = implode(',', $tmp);

		$sql = "update $table set " . $str . " where $where";

		return $this->result($sql);
	}

	/**
	 * Delete from table
	 *
	 * @param string $where
	 * @param string $table
	 * @return int
	 */
	public function delete($table, $whereSet)
	{
		$sql = "delete from $table where $where";
		return $this->result($sql);
	}

	/**
	 * Count num rows
	 *
	 * @param string $where
	 * @param string $table
	 * @return int
	 */
	public function count($where, $table)
	{
		$sql = "select count(1) as cnt from $table where $where";
		$this->query($sql);
		$result = $this->fetch();
		return empty($result['cnt']) ? 0 : $result['cnt'];
	}

	/**
	 * Get last query sql
	 *
	 * @return string
	 */
	public function lastSql()
	{
		return $this->_lastSql;
	}

	public function beginTransaction () {	}

	public function commit () {	}

	public function rollBack () {	}

	public function free () {}

	public function close () {}

	public function charset ($charset) {}


	abstract protected function _connect($params);

	abstract public function getError();

	abstract public function query($sql);

	abstract public function affectedRows();

	abstract public function lastInsertId();

	abstract public function fetch($type);

	abstract public function fetchAll($type);

	abstract public function escapeString($string);

}

/* EOF */
