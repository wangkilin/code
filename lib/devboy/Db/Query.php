<?php
importClass('Base');
class Db_Query extends Base
{
	protected $_dbAdapter = null;
	protected $_prefix = '';

    /**
     * The initial values for the $_parts array.
     * NOTE: It is important for the 'FOR_UPDATE' part to be last to ensure
     * meximum compatibility with database adapters.
     *
     * @var array
     */
    protected static $_partsInit = array(
        'distinct'     => false,
        'columns'      => array(),
        'from'         => array(),
    	'join'         => array(),
        'where'        => array(),
        'group'        => array(),
        'having'       => array(),
        'order'        => array(),
        'limitCount'   => null,
        'limitOffset'  => null,
    );

    protected $_action = "SELECT";


    /**
     * Specify legal join types.
     *
     * @var array
     */
    protected static $_joinTypes = array(
        'inner join',
        'left join',
        'right join',
        'full join',
        'cross join',
        'natural join',
    );

    /**
     * The component parts of a SELECT statement.
     * Initialized to the $_partsInit array in the constructor.
     *
     * @var array
     */
    protected $_parts = array();

    /**
     * Tracks which columns are being select from each table and join.
     *
     * @var array
     */
    protected $_tableCols = array();

    /**
     * Class constructor
     *
     * @param Db_Abstract $adapter
     */
    public function __construct($adapter, $tablePrefix='')
    {
        $this->_dbAdapter = $adapter;
        $this->_prefix = $tablePrefix;
        $this->_parts = self::$_partsInit;
    }

    public function select ($table=null, $cols=null, array $where=array())
    {
    	$this->reset();
    	$this->_action = 'SELECT';
    	is_null($table) OR $this->from($table);
    	is_null($cols)  OR $this->columns($cols);
    	count($where)>0 AND $this->where($where);

    	return $this;
    }

    public function delete ($table, array $where)
    {
    	$this->reset();
    	$this->_action = 'DELETE';
    	$this->from($table)->where($where);

    	return $this;
    }

    public function update ($table, array $cols, array $where)
    {
    	$this->reset();
    	$this->_action = 'UPDATE';
    	$this->from($table)->columns($cols)->where($where);

    	return $this;
    }

    public function insert ($table, array $cols)
    {
    	$this->reset();
    	$this->_action = 'INSERT';
    	$this->from($table)->columns($cols);

    	return $this;
    }

    /**
     * Makes the query SELECT DISTINCT.
     *
     * @param bool $flag Whether or not the SELECT is DISTINCT (default true).
     * @return Zend_Db_Select This Zend_Db_Select object.
     */
    public function distinct($flag = true)
    {
        $this->_parts['distinct'] = (bool) $flag;
        return $this;
    }

    /**
     *
     * The first parameter can be null or an empty string, in which case
     * no correlation name is generated or prepended to the columns named
     * in the second parameter.
     *
     * @param  array|string $name array('alias'=>'tableName') | tableName
     * @param  array|string $cols array('col1', 'col2') | col1
     * @param  string $dbSchema 数据库名
     * @return
     */
    public function from($table)
    {
    	return $this->_join('from', $table, null);
    }

    /**
     * Add an INNER JOIN table and colums to the query
     * Rows in both tables are matched according to the expression
     * in the $cond argument.  The result set is comprised
     * of all cases where rows from the left table match
     * rows from the right table.
     *
     * The $name and $cols parameters follow the same logic
     * as described in the from() method.
     *
     * @param  array|string|Zend_Db_Expr $name The table name.
     * @param  string $cond Join on this condition.
     * @param  array|string $cols The columns to select from the joined table.
     * @param  string $dbSchema The database name to specify, if any.
     * @return Zend_Db_Select This Zend_Db_Select object.
     */
    public function joinInner($name, $cond)
    {
        return $this->_join('inner join', $name, $cond);
    }
    public function join($name, $cond)
    {
        return $this->joinInner($name, $cond);
    }
    public function joinLeft($name, $cond)
    {
        return $this->_join('left join', $name, $cond);
    }
    public function joinRight($name, $cond)
    {
        return $this->_join('right join', $name, $cond);
    }
    public function joinFull($name, $cond)
    {
        return $this->_join('full join', $name, $cond);
    }

    /**
     * @param  string $type Type of join; inner, left, right are currently supported
     * @param  array|string $name Table name
     * @param  string $cond Join on this condition
     * @return Zend_Db_Select This Zend_Db_Select object
     */
    protected function _join($type, $name, $cond)
    {
    	if (! is_array($name)) {
    		$name = array($name);
    	}
    	reset($name);
    	$tableAlias = null;
    	list($_tableAlias, $tableName) = each($name);
    	if (is_string($_tableAlias)) {
    		$tableAlias = $_tableAlias;
    	}
        if (preg_match('/^(.+)\s+AS\s+(.+)$/i', $tableName, $m)) {
            $tableName = $m[1];
            $tableAlias = $m[2];
        }
        $tableInfo = explode('.', $tableName);
        if (! isset($tableInfo[1])) {
        	$tableName = $this->_prefix . $tableName;
        }
        if ('from'==$type) {
	        $this->_parts['from'][] = array(
	        		'table' => $tableName,
	        		'alias' => $tableAlias,

	        );
        } else {
	        $this->_parts['join'][] = array(
	        		'type'  => $type,
	        		'table' => $tableName,
	        		'alias' => $tableAlias,
	        		'cond'  => $cond,
	        );
        }

        return $this;
    }

    /**
     * Render FROM clause
     *
     * @param string   $sql SQL query
     * @return string
     */
    protected function _renderJoin($sql)
    {

        $tables = array();

        foreach ($this->_parts['join'] as $tableInfo) {
            $tmp = strtoupper($tableInfo['type']) . ' ' . $tableInfo['table'];
        	if (isset($tableInfo['alias'])) {
        		$tmp .= ' AS ' . $tableInfo['alias'];
        	}

            if ($tableInfo['cond']) {
                $tmp .= "\n\tON " . $tableInfo['cond'];
            }

            $tables[] = $tmp;
        }

        if ($tables) {
            $sql .= implode("\n", $tables);
        }

        return $sql;
    }

    /**
     * Render FROM clause
     *
     * @param string   $sql SQL query
     * @return string
     */
    protected function _renderFrom($sql)
    {

        $tables = array();

        foreach ($this->_parts['from'] as $tableInfo) {
        	if (isset($tableInfo['alias']) && 'SELECT'==$this->_action) {
        		$tables[] = $tableInfo['table'] . ' AS ' . $tableInfo['alias'];
        	} else {
        		$tables[] = $tableInfo['table'];
        	}

        	if ('SELECT'!=$this->_action) {
        		break;
        	}
        }

        if ($tables) {
        	switch ($this->_action) {
        		case 'SELECT':
        		case 'DELETE':
        			$sql .= ' FROM ';
        			break;

        		case 'INSERT':
        			$sql = ' INTO ';
        			break;

        		case 'UPDATE':
        			break;
        	}
            $sql .= implode(",\n", $tables);
        }

        return $sql;
    }

    /**
     * Adds a WHERE condition to the query by AND.
     *
     * If a value is passed as the second param, it will be quoted
     * and replaced into the condition wherever a question-mark
     * appears. Array values are quoted and comma-separated.
     *
     * <code>
     * // simplest but non-secure
     * $select->where("id = $id");
     *
     * // secure (ID is quoted but matched anyway)
     * $select->where('id = ?', $id);
     *
     * // alternatively, with named binding
     * $select->where('id = :id');
     * </code>
     *
     * Note that it is more correct to use named bindings in your
     * queries for values other than strings. When you use named
     * bindings, don't forget to pass the values when actually
     * making a query:
     *
     * <code>
     * $db->fetchAll($select, array('id' => 5));
     * </code>
     *
     * @param string|array   $cond  The WHERE condition.
     * @param mixed    $value OPTIONAL The value to quote into the condition.
     * @param int      $type  OPTIONAL The type of the given value
     * @return Zend_Db_Select This Zend_Db_Select object.
     */
    public function where($cond, $value = null)
    {
    	if (is_array($cond)) {
    		$tmpCond = array();
    		foreach ($cond as $_condition=>$_value) {
    			$tmpCond[] = $this->_parseWhereCondition($_condition, $_value);
    		}
    		$cond = join(' AND ', $tmpCond);
    		$value = null;
    		//var_dump($tmpCond, $value);
    	}
    	$this->_parts['where'][] = $this->_where($cond, $value, true);

        return $this;
    }

    protected function _parseWhereCondition ($condition, $value = null)
    {
    	if ($value !== null) {
    		if (is_array($value)) {
    			foreach ($value as & $val) {
    				$val = $this->_dbAdapter->escapeString($val);
    			}
    			$value = implode(', ', $value);
    		} else {
    			$value = $this->_dbAdapter->escapeString($value);
    		}

    		$condition = str_replace('?', $value, $condition);
    	}

    	return $condition;
    }

    /**
     * Internal function for creating the where clause
     *
     * @param string   $condition
     * @param mixed    $value  optional
     * @param boolean  $bool  true = AND, false = OR
     * @return string  clause
     */
    protected function _where($condition, $value = null, $bool = true)
    {
    	$condition = $this->_parseWhereCondition($condition, $value);

        $cond = "";
        if ($this->_parts['where']) {
        	$cond = true===$bool ? 'AND ' : 'OR ';
        }

        return $cond . "($condition)";
    }

    /**
     * Render WHERE clause
     *
     * @param string   $sql SQL query
     * @return string
     */
    protected function _renderWhere($sql)
    {
        if ($this->_parts['from'] && $this->_parts['where']) {
            $sql .= "\nWHERE " .  implode(' ', $this->_parts['where']);
        }

        return $sql;
    }

    /**
     * Adds a WHERE condition to the query by OR.
     *
     * Otherwise identical to where().
     *
     * @param string   $cond  The WHERE condition.
     * @param mixed    $value OPTIONAL The value to quote into the condition.
     * @param int      $type  OPTIONAL The type of the given value
     * @return Zend_Db_Select This Zend_Db_Select object.
     *
     * @see where()
     */
    public function orWhere($cond, $value = null)
    {
    	if (is_array($cond)) {
    		$tmpCond = array();
    		foreach ($cond as $_condition=>$_value) {
    			$tmpCond[] = $this->_where($_condition, $_value, false);
    		}
    		$cond = join(' OR ' . $tmpCond);
    		$value = null;
    	}
    	$this->_parts['where'][] = $this->_where($cond, $value, false);

        return $this;
    }

    /**
     * Adds grouping to the query.
     *
     * @param  array|string $spec The column(s) to group by.
     * @return Zend_Db_Select This Zend_Db_Select object.
     */
    public function group($spec)
    {
        is_array($spec) OR $spec = array($spec);

        foreach ($spec as $val) {
            $this->_parts['group'][] = $val;
        }

        return $this;
    }

    /**
     * Adds a HAVING condition to the query by AND.
     *
     * If a value is passed as the second param, it will be quoted
     * and replaced into the condition wherever a question-mark
     * appears. See {@link where()} for an example
     *
     * @param string $cond The HAVING condition.
     * @param mixed    $value OPTIONAL The value to quote into the condition.
     * @param int      $type  OPTIONAL The type of the given value
     * @return Zend_Db_Select This Zend_Db_Select object.
     */
    public function having($cond, $value = null)
    {
    	return $this->_having($cond, $value, true);
    }

    protected function _having($cond, $value = null, $isAndHaving=true)
    {
        if ($value !== null) {
            $value = $this->_dbAdapter->escapeString($value);
            $cond = str_replace('?', $value, $cond);
        }

        if ($this->_parts['having']) {
            $this->_parts['having'][] = ($isAndHaving ? 'AND' : 'OR') . " ($cond)";
        } else {
            $this->_parts['having'][] = "($cond)";
        }

        return $this;
    }
    public function orHaving($cond, $value = null)
    {
    	return $this->_having($cond, $value, false);
    }

    /**
     * Adds a row order to the query.
     *
     * @param mixed $spec The column(s) and direction to order by.
     * @return Zend_Db_Select This Zend_Db_Select object.
     */
    public function order($spec)
    {
        is_array($spec) OR $spec = array($spec);

        // force 'ASC' or 'DESC' on each order spec, default is ASC.
        foreach ($spec as $val) {
            if (empty($val)) {
                continue;
            }
            $direction = 'ASC';
            if (preg_match('/(.*\W)(ASC|DESC)\b/si', $val, $matches)) {
                $val = trim($matches[1]);
                $direction = $matches[2];
            }
            $this->_parts['order'][] = $val . ' ' . $direction;
        }

        return $this;
    }

    /**
     * Render ORDER clause
     *
     * @param string   $sql SQL query
     * @return string
     */
    protected function _renderOrder($sql)
    {
        if ($this->_parts['order']) {
            $sql .= "\n ORDER BY " . implode(', ', $this->_parts['order']);
        }

        return $sql;
    }

    /**
     * Sets a limit count and offset to the query.
     *
     * @param int $count OPTIONAL The number of rows to return.
     * @param int $offset OPTIONAL Start returning after this many rows.
     * @return Zend_Db_Select This Zend_Db_Select object.
     */
    public function limit($count = null, $offset = null)
    {
        $this->_parts['limitCount']  = (int) $count;
        $this->_parts['limitOffset'] = (int) $offset;
        return $this;
    }

    /**
     * Render LIMIT OFFSET clause
     *
     * @param string   $sql SQL query
     * @return string
     */
    protected function _renderLimitoffset($sql)
    {
        $count = 0;
        $offset = 0;

        if (!empty($this->_parts['limitOffset'])) {
            $offset = (int) $this->_parts['limitOffset'];
            $count = PHP_INT_MAX;
        }

        if (!empty($this->_parts['limitCount'])) {
            $count = (int) $this->_parts['limitCount'];
        }

        /*
         * Add limits clause
         */
        if ($count > 0) {
            $sql = trim($this->_dbAdapter->limit($sql, $count, $offset));
        }

        return $sql;
    }

    /**
     * Sets the limit and count by page number.
     *
     * @param int $page Limit results to this page number.
     * @param int $rowCount Use this many rows per page.
     * @return Zend_Db_Select This Zend_Db_Select object.
     */
    public function limitPage($page, $rowCount)
    {
        $page     = ($page > 0)     ? $page     : 1;
        $rowCount = ($rowCount > 0) ? $rowCount : 1;
        $this->limit((int) $rowCount, (int) $rowCount * ($page - 1));
        return $this;
    }

    /**
     * Get part of the structured information for the currect query.
     *
     * @param string $part
     * @return mixed
     * @throws Zend_Db_Select_Exception
     */
    public function getPart($part)
    {
        $part = strtolower($part);
        $value = isset($this->_parts[$part]) ? $this->_parts[$part] : null;
        return $value;
    }

    /**
     * Clear parts of the Select object, or an individual part.
     *
     * @param string $part OPTIONAL
     * @return Zend_Db_Select
     */
    public function reset($part = null)
    {
        if ($part == null) {
            $this->_parts = self::$_partsInit;
        } else if (array_key_exists($part, self::$_partsInit)) {
            $this->_parts[$part] = self::$_partsInit[$part];
        }
        return $this;
    }

    /**
     * Converts this object to an SQL SELECT string.
     *
     * @return string|null This object as a SELECT string. (or null if a string cannot be produced.)
     */
    public function makeSql()
    {
        $sql = $this->_action;
        $parts = array();
        switch ($this->_action) {
        	case 'SELECT':
        		$parts = array_keys(self::$_partsInit);
        		break;

        	case 'INSERT':
        		$parts = array('from', 'columns');
        		break;

        	case 'DELETE':
        	case 'UPDATE':
        		$parts = array('from', 'columns', 'where');
        		break;
        }
        foreach (array_keys(self::$_partsInit) as $part) {
            $method = '_render' . ucfirst($part);
            //echo $method . "<br/>";
            if (in_array($part, $parts) && method_exists($this, $method)) {
            	//echo $sql . "<br/>";
                $sql = $this->$method($sql) . "\n";
            }
        }
        return $sql;
    }


    /**
     * Adds to the internal table-to-column mapping array.
     *
     * @param  array|string $cols The list of columns; preferably as
     * an array, but possibly as a string containing one column.
     * @return void
     */
    public function columns($cols='*')
    {
        is_array($cols) OR $cols = array($cols);
        foreach (array_filter($cols) as $alias => $col) {
        	$this->_parts['columns'][] = array($alias => $col);
        }

        return $this;
    }

    /**
     * Render DISTINCT clause
     *
     * @param string   $sql SQL query
     * @return string|null
     */
    protected function _renderColumns($sql)
    {
	    	switch ($this->_action) {
	    		case 'SELECT':
	    			if (! $this->_parts['columns']) {
	    				$this->_parts['columns'] = array('*');
	    			}
	    			$tmpCols = array();
	    			foreach ($this->_parts['columns'] as $alias=>$col) {
	    			    $col = is_string($alias) ? ($col . ' AS ' . $alias) : $col;
	    			    $tmpCols[] = $col;
	    			}
	    			$sql .= ' ' . implode(', ', $this->_parts['columns']);
	    			break;

	    		case 'INSERT':
	    			$tmpCols = array();
	    			$tmpVals = array();
	    			foreach ($this->_parts['columns'] as $col=>$val) {
	    			    $tmpCols[] = $col;
	    			    $tmpVals[] = $this->_dbAdapter->escapeString($val);
	    			}
	    			$sql .= "\t (" . implode(', ', $tmpCols) . ')';
	    			$sql .= "\nVALUES \n\t('" . implode("', '", $tmpVals) . "')";
	    			break;

	    		case 'UPDATE':
	    			$tmpSets = array();
	    			foreach ($this->_parts['columns'] as $col=>$val) {
	    			    $tmpSets[] = $col . "='" . $this->_dbAdapter->escapeString($val) . "'";
	    			}
	    			$sql .=  ' SET '. implode(",\n \t", $tmpSets);
	    			break;

	    		case 'DELETE':
	    			break;

	    	}

        return $sql;
    }

    /**
     * Render DISTINCT clause
     *
     * @param string   $sql SQL query
     * @return string
     */
    protected function _renderDistinct($sql)
    {
        if ($this->_parts['distinct']) {
            $sql .= ' DISTINCT';
        }

        return $sql;
    }

    /**
     * Render GROUP clause
     *
     * @param string   $sql SQL query
     * @return string
     */
    protected function _renderGroup($sql)
    {
        if ($this->_parts['from'] && $this->_parts['group']) {
            $sql .= "\nGROUP BY " . implode(",\n\t", $this->_parts['group']);
        }

        return $sql;
    }

    /**
     * Render HAVING clause
     *
     * @param string   $sql SQL query
     * @return string
     */
    protected function _renderHaving($sql)
    {
        if ($this->_parts['from'] && $this->_parts['having']) {
            $sql .= "\nHAVING " . implode(' ', $this->_parts['having']);
        }

        return $sql;
    }

    /**
     * Implements magic method.
     *
     * @return string This object as a SELECT string.
     */
    public function __toString()
    {
        $sql = $this->makeSql();

        return $sql;
    }

}

/* EOF */