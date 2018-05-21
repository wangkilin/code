<?php
importClass('Db_Abstract');

/**
 * MySQLi库函数实现数据库操作类
 * @author zhoumingxia
 *
 */
class Db_Mysqli extends Db_Abstract
{
    /**
     * Connect to database
     */
    protected function _connect($dbConfig)
    {
        $this->_connection = mysqli_init();

        $connected = @ mysqli_real_connect(
            $this->_connection,
            $dbConfig['host'],
            $dbConfig['username'],
            $dbConfig['password'],
            $dbConfig['database'],
            $dbConfig['port']
        );

        if ($connected === false || mysqli_connect_errno()) {
        	throw new Exception('Connected to DB failed');
        	return false;
        }

        $this->useDatabase($dbConfig['database']);

        if (!empty($dbConfig['charset'])) {
        	$this->_connection->set_charset($dbConfig['charset']);
        }

    }

    /**
     * Select Database
     *
     * @param string $database
     * @return boolean
     */
    public function useDatabase($database)
    {
        return $this->_connection->select_db($database);
    }

    /**
     * Close db connection
     *
     */
    public function close()
    {
        $this->_connection->close();
    }

    /**
     * Free query result
     *
     */
    public function free()
    {
        if ($this->_query) {
        	$this->_query->free();
        }
    }

    /**
     * Query SQL
     *
     * @param string $sql
     * @return Cola_Com_Db_Mysqli
     */
    public function query($sql)
    {
        $this->_lastSql = $sql;
		Application::getInstance()->log($sql, Log::LEVEL_SQL);
        $this->_query = $this->_connection->query($sql);
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
        return $this->_connection->affected_rows;
    }

    /**
     * Check if specified fetch type function is exising?
     * @param string $type
     * @return string mysql fetch function name
     */
    protected function _getFetchFunc ($type)
    {
    	null===$type AND $type = 'assoc';
    	$funcName = 'fetch_' . $type;
    	if (! method_exists($this->_query, $funcName)) {
    		$funcName = 'fetch_assoc';
    	}

    	return $funcName;
    }

    /**
     * Fetch result
     *
     * @param string $type
     * @return mixed
     */
    public function fetch($type = null)
    {
    	$func = $this->_getFetchFunc($type);

        return $this->_query->$func();
    }

    /**
     * Fetch all results
     *
     * @param string $type
     * @return mixed
     */
    public function fetchAll($type = null)
    {
        $result = array();
    	$func = $this->_getFetchFunc($type);
        while ($row = $this->_query->$func()) {
            $result[] = $row;
        }
        $this->_query->free();

        return $result;
    }

    /**
     * Get last insert id
     *
     * @return mixed
     */
    public function lastInsertId()
    {
        return $this->_connection->insert_id;
    }

    /**
     * Begin transaction
     *
     */
    public function beginTransaction()
    {
        $this->_connection->autocommit(false);
    }

    /**
     * Commit transaction
     *
     */
    public function commit()
    {
        $this->_connection->commit();
        $this->_connection->autocommit(true);
    }

    /**
     * Rollback
     *
     */
    public function rollBack()
    {
        $this->_connection->rollback();
        $this->_connection->autocommit(true);
    }

    /**
     * Escape string
     *
     * @param string $str
     * @return string
     */
    public function escapeString($str)
    {
        return  $this->_connection->real_escape_string($str);
    }

    /**
     * 获取mysqli错误
     *
     * @return array
     */
    public function getError()
    {
        if ($this->_connection) {
            $errno = $this->_connection->errno;
            $error = $this->_connection->error;
        } else {
            $errno = mysqli_connect_errno();
            $error = mysqli_connect_error();
        }

        $errorInfo = array('code' => $errno, 'msg' => $error);

        return $errorInfo;
    }
}