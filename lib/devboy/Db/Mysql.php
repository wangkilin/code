<?php
importClass('Db_Abstract');

class Db_Mysql extends Db_Abstract
{
    /**
     * Connect to MySQL
     *
     * @return resource connection
     */
    protected function _connect($dbConfig)
    {

        $func = empty($dbConfig['pconnect']) ? 'mysql_connect' : 'mysql_pconnect';

        $connection = $func(
            $dbConfig['host'] . ':' . $dbConfig['port'],
            $dbConfig['username'],
            $dbConfig['password']
        );

        if (is_resource($connection)) {
        	throw new Exception('Connected to DB failed');
        	return false;
        }
        $this->_connection = $connection;
        $this->useDatabase($database);

        $this->query("SET NAMES '" . $this->_config['charset'] . "';");

        return $this->_connection;
    }

    /**
     * Select Database
     *
     * @param string $database
     * @return boolean
     */
    public function useDatabase($database)
    {
        return mysql_select_db($database, $this->_connection);
    }

    /**
     * Close mysql connection
     */
    public function close()
    {
        if (is_resource($this->_connection)) {
            mysql_close($this->_connection);
        }
    }

    /**
     * Fress result memory
     */
    public function free()
    {
    	if (is_resource($this->_query)) {
            mysql_free_result($this->_query);
    	}
    }

    /**
     * Query sql
     *
     * @param string $sql
     * @return this
     */
    public function query($sql)
    {
        $this->_lastSql = $sql;
		Application::getInstance()->log($sql, Log::LEVEL_SQL);
        $this->_query = mysql_query($sql, $this->_connection);
        if (! $this->_query) {
        	$error = $this->getError();
        	$error = $error['code'] . '::'  . $error['msg'];
        	Application::getInstance()->log($error, Log::LEVEL_ERROR);
        }

        return $this;
    }

    /**
     * Return the rows affected of the last sql
     *
     * @return int
     */
    public function affectedRows()
    {
        return mysql_affected_rows($this->_connection);
    }

    /**
     * Check if specified fetch type function is exising?
     * @param string $type
     * @return string mysql fetch function name
     */
    protected function _getFetchFunc ($type)
    {
    	null===$type AND $type = 'assoc';
    	$funcName = 'mysql_fetch_' . $type;
    	if (! function_exists($funcName)) {
    		$funcName = 'mysql_fetch_assoc';
    	}

    	return $funcName;
    }

    /**
     * Fetch one row result
     *
     * @param string $type
     * @return array|object
     */
    public function fetch($type = null)
    {
    	$func = $this->_getFetchFunc($type);

        return $func($this->_query);
    }

    /**
     * Fetch All query result
     *
     * @param string $type
     * @return array
     */
    public function fetchAll($type = null)
    {
        $result = array();
    	$func = $this->_getFetchFunc($type);
        while ($row = $func($this->_query)) {
            $result[] = $row;
        }
        mysql_free_result($this->_query);

        return $result;
    }

    /**
     * 获取最后插入的自增id
     *
     * @return int
     */
    public function lastInsertId()
    {
        return mysql_insert_id($this->_connection);
    }

    /**
     * 开启事务
     */
    public function beginTransaction()
    {
        @ mysql_query('START TRANSACTION', $this->_connection);
    }

    /**
     * Commit transaction
     *
     * @return boolean
     */
    public function commit()
    {
        $result = mysql_query('COMMIT', $this->_connection);

        return $result;
    }

    /**
     * 回滚事务
     *
     * @return boolean
     */
    public function rollBack()
    {
        $result = mysql_query('ROLLBACK', $this->_connection);

        return $result;
    }

    /**
     * 转义字符创
     *
     * @param string $str
     * @return string
     */
    public function escapeString($str)
    {
        return mysql_escape_string($str);
    }

    /**
     * 获取mysql错误
     *
     * @return array
     */
    public function getError()
    {
    	$errorInfo = array('code' => @ mysql_errno($this->_connection),
    			           'msg'  => @ mysql_error($this->_connection) . ' SQL::' . $this->_lastSql
    			     );

        return $errorInfo;
    }
}