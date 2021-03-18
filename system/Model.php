<?php
defined('iCodeBang_Com') OR die('Access denied!');

/**
 * 数据库操作类
 */

class Model
{
    public $prefix;
    public $setting;

    private $_current_db = 'master';
    private $_shutdown_query = array();
    private $_found_rows = 0;

    protected $table = null;

    public function __construct($options=array())
    {
        $this->prefix = Application::config()->get('database')->prefix;

        $this->setup();

        $this->setOptions($options);

        if (! $this->table) {
            $className = get_class($this);
            $this->table = substr($className, 0, -5);
        }
    }

    public function setup()
    {}

    /**
     * 设置操作表名称
     *
     * @param unknown $table
     *
     * @return Model
     */
    public function setTable($table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * 根据id获取内容
     * @param unknown $id
     * @param string $table
     * @return boolean|multitype:
     */
    public function getById ($id, $table=null)
    {
        $itemInfo = array();
        if (!is_numeric($id)) {
            return $itemInfo;
        }
        $table = is_null($table) ? $this->table : $table;

        $itemInfo = $this->fetch_row($table, 'id = ' . $id);

        return $itemInfo;
    }

    /**
     * 根据ids获取分类列表
     * @param array $ids 分类id列表
     * @return array
     */
    public function getByIds ($ids, $table=null)
    {
        $list = array();
        if (is_numeric($ids)) {
            $ids = array($ids);
        }
        $arrayLength = count($ids);
        if (!is_array($ids) || $arrayLength==0) {
            return $list;
        }
        $table = is_null($table) ? $this->table : $table;
        array_walk($ids, 'intval_string');
        $where = $arrayLength == 1 ? 'id = ' . end($ids) : 'id IN (' . join(',', $ids) . ')';
        if (! $result = $this->fetch_all($table, $where) ) {
            return $list;
        }
        foreach ($result as $_item) {
            $list[$_item['id']] = $_item;
        }

        return $list;
    }

    /**
     * 设置属性
     * @param array $options
     * @return this
     */
    public function setOptions (array $options)
    {
        foreach ($options as $_k => $_v) {
            $this->setOption ($_k, $_v);
        }

        return $this;
    }

    /**
     * 设置属性
     * @param string $key 属性名称
     * @param mixed $value 属性值
     * @return this
     */
    public function setOption ($key, $value)
    {
        if (is_string($key) && '_'==$key[0]) {
            $this->$key = $value;
        }

        return $this;
    }

    public function model($model, $options = array())
    {
        return Application::model($model, $options);
    }

    /**
     * 获取表前缀
     */
    public function get_prefix()
    {
        return $this->prefix;
    }

    /**
     * 获取表名
     *
     * 直接写 SQL 的时候要用这个函数, 外部程序使用 get_table() 方法
     *
     * @access    public
     * @param    string
     * @return    string
     */
    public function get_table($name='', $withPrefix=true)
    {
        if (! $name && $this->table) {
            $name = $this->table;
        }

        $table = $withPrefix ? $this->get_prefix() . $name : $name;

        return $table;
    }

    /**
     * 获取系统 DB 类
     *
     * 此功能基于 Zend_DB 类库
     *
     * @return    object
     */
    public function db()
    {
        return Application::db($this->_current_db);
    }

    /**
     * 切换到主数据库
     *
     * 此功能用于数据库主从分离
     *
     * @return    object
     */
    public function master()
    {
        if ($this->_current_db == 'master')
        {
            return $this;
        }

        if (Application::config()->get('system')->debug)
        {
            $start_time = microtime(TRUE);
        }

        Application::db('master');

        if (Application::config()->get('system')->debug)
        {
            Application::debug_log('database', (microtime(TRUE) - $start_time), 'Master DB Seleted');
        }

        return $this;
    }

    /**
     * 切换到从数据库
     *
     * 此功能用于数据库主从分离
     *
     * @return    object
     */
    public function slave()
    {
        if (!Application::config()->get('database')->slave OR $this->_current_db == 'slave')
        {
            return $this;
        }

        if (Application::config()->get('system')->debug)
        {
            $start_time = microtime(TRUE);
        }

        Application::db('slave');

        if (Application::config()->get('system')->debug)
        {
            Application::debug_log('database', (microtime(TRUE) - $start_time), 'Slave DB Seleted');
        }

        return $this;
    }

    /**
     * 开始事务处理
     *
     * 此功能只在 Pdo 数据库驱动下有效
     */
    public function begin_transaction()
    {
        $this->master();

        $this->db()->beginTransaction();
    }

    /**
     * 事务处理回滚
     *
     * 此功能只在 Pdo 数据库驱动下有效
     */
    public function roll_back()
    {
        $this->master();

        $this->db()->roll_back();
    }

    /**
     * 事务处理提交
     *
     * 此功能只在 Pdo 数据库驱动下有效
     */
    public function commit()
    {
        $this->master();

        $this->db()->commit();
    }

    /**
     * 插入数据
     *
     * 面向对象数据库操作, 表名无需加表前缀, 数据也无需使用 $this->quote 进行过滤
     *
     * @param    string
     * @param    array
     * @return    int
     */
    public function insert($table, $data)
    {
        $this->master();

        foreach ($data AS $key => $val)
        {
            $debug_data['`' . $this->quote($key) . '`'] = "'" . $this->quote($val) . "'";
        }

        $sql = 'INSERT INTO `' . $this->get_table($table) . '` (' . implode(', ', array_keys($debug_data)) . ') VALUES (' . implode(', ', $debug_data) . ')';

        if (Application::config()->get('system')->debug)
        {
            $start_time = microtime(TRUE);
        }

        try {
            $rows_affected = $this->db()->insert($this->get_table($table), $data);
        } catch (Exception $e) {
            show_error("Database error\n------\n\nSQL: {$sql}\n\nError Message: " . $e->getMessage(), $e->getMessage());
        }

        if (Application::config()->get('system')->debug)
        {
            Application::debug_log('database', (microtime(TRUE) - $start_time), $sql);
        }

        $last_insert_id = $this->db()->lastInsertId();

        return $last_insert_id;
    }

    /**
     * 更新数据
     *
     * 面向对象数据库操作, 表名无需加表前缀, 数据也无需使用 $this->quote 进行过滤 ($where 条件除外)
     *
     * @param    string
     * @param    array
     * @param    string
     * @return    int
     */
    public function update($table, $data, $where = '')
    {
        $this->master();

        if (!$where) {
            throw new Zend_Exception('DB Update no where string.');
        }

        if ($data)
        {
            foreach ($data AS $key => $val)
            {
                $update_string[] = '`' . $key . "` = '" . $this->quote($val) . "'";
            }
        }

        $sql = 'UPDATE `' . $this->get_table($table) . '` SET ' . implode(', ', $update_string) . ' WHERE ' . $where;

        if (Application::config()->get('system')->debug)
        {
            $start_time = microtime(TRUE);
        }

        try {
            $rows_affected = $this->db()->update($this->get_table($table), $data, $where);
        } catch (Exception $e) {
            show_error("Database error\n------\n\nSQL: {$sql}\n\nError Message: " . $e->getMessage(), $e->getMessage());
        }

        if (Application::config()->get('system')->debug)
        {
            Application::debug_log('database', (microtime(TRUE) - $start_time), $sql);
        }

        return $rows_affected;
    }

    /**
     * 将对应列值增1
     * @param string $table
     * @param string $column
     * @param string $where
     * @param bool   $isExecutedNow 是否马上执行
     * @throws Zend_Exception
     * @return int
     */
    public function increase ($table, $column, $where='', $isExecutedNow=true)
    {
        if (!$where) {
            throw new Zend_Exception('DB Update no where string.');
        }

        $sql = 'UPDATE `' . $this->get_table($table) . '`
                SET `' . $column . '` = `' . $column . '` + 1
                WHERE ' . $where;
        // 不需要马上执行， 等程序结束后在运行
        if (! $isExecutedNow) {
            $this->shutdown_query($sql);
            return true;
        }

        $this->master();

        if (Application::config()->get('system')->debug) {
            $start_time = microtime(TRUE);
        }

        try {
            $stmt = $this->db()->query($sql);
            $affectedRows = $stmt->rowCount();
        } catch (Exception $e) {
            show_error("Database error\n------\n\nSQL: {$sql}\n\nError Message: " . $e->getMessage(), $e->getMessage());
        }

        if (Application::config()->get('system')->debug) {
            Application::debug_log('database', (microtime(TRUE) - $start_time), $sql);
        }

        return $affectedRows;
    }

    /**
     * 延迟查询
     *
     * 延迟查询会在页面渲染结束之前运行, 运行之前会将产生的用户先发送到用户浏览器
     *
     * @param    string
     */
    public function shutdown_query($query)
    {
        $this->_shutdown_query[] = $query;
    }

    /**
     * 延迟更新
     *
     * 延迟更新会在页面渲染结束之前运行, 运行之前会将产生的用户先发送到用户浏览器
     *
     * @param    string
     * @param    array
     * @param    string
     */
    public function shutdown_update($table, $data, $where = '')
    {
        if (!$where)
        {
            throw new Zend_Exception('DB Update no where string.');
        }

        if ($data)
        {
            foreach ($data AS $key => $val)
            {
                $update_string[] = '`' . $key . "` = '" . $this->quote($val) . "'";
            }
        }

        $sql = 'UPDATE `' . $this->get_table($table) . '` SET ' . implode(', ', $update_string) . ' WHERE ' . $where;

        $this->_shutdown_query[] = $sql;
    }

    /**
     * 根据字段id删除数据记录
     * @param string $table 表名
     * @param array|int $ids 字段id
     * @return boolean
     */
    public function deleteByIds ($ids, $table=null)
    {
        if (is_numeric($ids)) {
            $ids = array($ids);
        }
        if (is_null($table)) {
            $table = $this->table;
        }
        if (! is_array($ids) || count($ids)==0 || is_null($table)) {
            return false;
        }
        array_walk_recursive($ids, 'intval');
        if (count($ids)==1) {
            $where = "id = '" . end($ids) . "'";
        } else {
            $where = "FIND_IN_SET (id, '".join(',', $ids) ."' )";
        }
        $this->delete($table, $where);

        return true;
    }

    /**
     * 删除数据
     *
     * 面向对象数据库操作
     *
     * @param    string
     * @param    string
     * @return    int
     */
    public function delete($table, $where = '')
    {
        $this->master();

        if (!$where)
        {
            throw new Exception('DB Delete no where string.');
        }

        $sql = 'DELETE FROM `' . $this->get_table($table) . '` WHERE ' . $where;

        if (Application::config()->get('system')->debug)
        {
            $start_time = microtime(TRUE);
        }

        try {
            $rows_affected = $this->db()->delete($this->get_table($table), $where);
        } catch (Exception $e) {
            show_error("Database error\n------\n\nSQL: {$sql}\n\nError Message: " . $e->getMessage(), $e->getMessage());
        }

        if (Application::config()->get('system')->debug)
        {
            Application::debug_log('database', (microtime(TRUE) - $start_time), $sql);
        }

        return $rows_affected;
    }

    /**
     * Zend DB Select 对象别名
     *
     * @return    object
     */
    public function select()
    {
        $this->slave();

        return $this->db()->select();
    }

    /**
     * 获取查询全部数组数据
     *
     * 面向对象数据库操作, 查询结果返回数组
     *
     * @param    string
     * @param    string $where [optional]
     * @param    string [optional]
     * @param    integer[optional]
     * @param    integer[optional]
     * @return    array
     */
    public function fetch_all($table='', $where = null, $order = null, $limit = null, $offset = 0)
    {
        $this->slave();

        $select = $this->select();

        $select->from($this->get_table($table), '*');

        if ($where)
        {
            $select->where($where);
        }

        if ($order)
        {
            if (strstr($order, ','))
            {
                $all_order = explode(',', $order);

                foreach ($all_order AS $current_order)
                {
                    $select->order($current_order);
                }
            }
            else
            {
                $select->order($order);
            }
        }

        if ($limit)
        {
            if (strstr($limit, ','))
            {
                $limit = explode(',', $limit);

                $select->limit(intval($limit[1]), intval($limit[0]));
            }
            else if ($offset)
            {
                $select->limit($limit, $offset);
            }
            else
            {
                $select->limit($limit);
            }
        }

        $sql = $select->__toString();

        if (Application::config()->get('system')->debug)
        {
            $start_time = microtime(TRUE);
        }

        try {
            $result = $this->db()->fetchAll($select);
        } catch (Exception $e) {
            show_error("Database error\n------\n\nSQL: {$sql}\n\nError Message: " . $e->getMessage(), $e->getMessage());
        }

        if (Application::config()->get('system')->debug)
        {
            Application::debug_log('database', (microtime(TRUE) - $start_time), $sql);
        }

        return $result;
    }

    /**
     * 执行 SQL 语句
     *
     * 执行 SQL 语句, 表名要使用 get_table 函数获取, 外来数据要使用 $this->quote() 过滤
     *
     * @param    string
     * @param    integer
     * @param    integer
     * @param    string
     * @return    boolean
     */
    public function query($sql, $limit = null, $offset = null, $where = null)
    {
        $this->slave();

        if (!$sql)
        {
            throw new Exception('Query was empty.');
        }

        if ($where)
        {
            $sql .= ' WHERE ' . $where;
        }

        if ($limit)
        {
            $sql .= ' LIMIT ' . $limit;
        }

        if ($offset)
        {
            $sql .= ' OFFSET ' . $offset;
        }

        if (Application::config()->get('system')->debug)
        {
            $start_time = microtime(TRUE);
        }

        try {
            $result = $this->db()->query($sql);
        } catch (Exception $e) {
            show_error("Database error\n------\n\nSQL: {$sql}\n\nError Message: " . $e->getMessage(), $e->getMessage());
        }

        if (Application::config()->get('system')->debug)
        {
            Application::debug_log('database', (microtime(TRUE) - $start_time), $sql);
        }

        return $result;
    }

    /**
     * 查询一行数据, 返回数组, key 为 字段名
     *
     * 执行 SQL 语句, 表名要使用 get_table 函数获取, 外来数据要使用 $this->quote() 过滤
     *
     * @param    string
     * @param    string
     * @return    array
     */
    public function query_row($sql, $where = null)
    {
        $this->slave();

        if (!$sql)
        {
            throw new Exception('Query was empty.');
        }

        if ($where)
        {
            $sql .= ' WHERE ' . $where;
        }

        if (Application::config()->get('system')->debug)
        {
            $start_time = microtime(TRUE);
        }

        try {
            $result = $this->db()->fetchRow($sql);
        } catch (Exception $e) {
            show_error("Database error\n------\n\nSQL: {$sql}\n\nError Message: " . $e->getMessage(), $e->getMessage());
        }

        if (Application::config()->get('system')->debug)
        {
            Application::debug_log('database', (microtime(TRUE) - $start_time), $sql);
        }

        return $result;
    }

    /**
     * 查询全部数据, 返回数组
     *
     * 执行 SQL 语句, 表名要使用 get_table 函数获取, 外来数据要使用 $this->quote() 过滤
     *
     * @param    string
     * @param    integer
     * @param    integer
     * @param    string
     * @param    string
     * @return    array
     */
    public function query_all($sql, $limit = null, $offset = null, $where = null, $group_by = null, $order_by = '')
    {
        $this->slave();

        if (!$sql)
        {
            throw new Exception('Query was empty.');
        }

        if ($where)
        {
            $sql .= ' WHERE ' . $where;
        }

        if ($group_by) {
            $group_by = explode(',', $group_by);
            foreach($group_by as & $_item) {
                $_item = $this->quote($_item);
            }
            $sql .= " GROUP BY `" . join('`,`', $group_by) . "`";
        }

        if ($order_by) {
            $sql .= ' ORDER BY ' . $order_by;
        }

        if ($limit)
        {
            $sql .= ' LIMIT ' . $limit;
        }

        if ($offset)
        {
            $sql .= ' OFFSET ' . $offset;
        }

        if (Application::config()->get('system')->debug)
        {
            $start_time = microtime(TRUE);
        }

        try {
            $result = $this->db()->fetchAll($sql);
        } catch (Exception $e) {
            show_error("Database error\n------\n\nSQL: {$sql}\n\nError Message: " . $e->getMessage(), $e->getMessage());
        }

        if (Application::config()->get('system')->debug)
        {
            Application::debug_log('database', (microtime(TRUE) - $start_time), $sql);
        }

        return $result;
    }

    /**
     * 获取上一次查询中的 FOUND_ROWS() 结果
     *
     * 此函数需配合 $this->fetch_page() 使用
     *
     * @return    int
     */
    public function found_rows()
    {
        //$this->slave();
        //return $this->db()->fetchOne('SELECT FOUND_ROWS()');

        return $this->_found_rows;
    }

    /**
     * 获取查询全部数组数据, 并记录匹配记录总数
     *
     * 面向对象数据库操作, 查询结果返回数组, 此函数适用于需要分页的场景使用, 配合 $this->found_rows() 获取匹配记录总数
     *
     * @param    string
     * @param    string
     * @param    string
     * @param    integer
     * @param    integer
     * @param    boolean
     * @return    array
     */
    public function fetch_page($table, $where = null, $order = null, $page = null, $limit = 10, $rows_cache = true)
    {
        $this->slave();

        $select = $this->select();

        $select->from($this->get_table($table), '*');
        //$select->from($this->get_table($table), array(new Zend_Db_Expr('SQL_CALC_FOUND_ROWS *')));

        if ($where)
        {
            $select->where($where);
        }

        if ($order)
        {
            if (strstr($order, ','))
            {
                if ($all_order = explode(',', $order))
                {
                    foreach ($all_order AS $current_order)
                    {
                        $select->order($current_order);
                    }
                }
            }
            else
            {
                $select->order($order);
            }
        }

        if (!$page)
        {
            $page = 1;
        }

        if ($limit)
        {
            $select->limitPage($page, $limit);
        }

        $sql = $select->__toString();

        if (Application::config()->get('system')->debug)
        {
            $start_time = microtime(TRUE);
        }

        try {
            $result = $this->db()->fetchAll($select);
        } catch (Exception $e) {

            show_error("Database error\n------\n\nSQL: {$sql}\n\nError Message: " . $e->getMessage(), $e->getMessage());
        }

        if (Application::config()->get('system')->debug)
        {
            Application::debug_log('database', (microtime(TRUE) - $start_time), $sql);
        }

        if ($rows_cache)
        {
            $cache_key = 'db_rows_cache_' . md5($table . '_' . $where);

            $db_found_rows = Application::cache()->get($cache_key);
        }

        if (!$db_found_rows)
        {
            $db_found_rows = $this->count($table, $where);
        }

        if ($rows_cache AND $db_found_rows)
        {
            Application::cache()->set($cache_key, $db_found_rows, get_setting('cache_level_high'));
        }

        // Found rows
        $this->_found_rows = $db_found_rows;

        return $result;
    }

    /**
     * 查询一行数据, 返回数组, key 为 字段名
     *
     * query_row 的面向对象方法, 表名无需加表前缀, 数据也无需使用 $this->quote 进行过滤 ($where 条件除外)
     *
     * @param    string
     * @param    string
     * @return    array
     */
    public function fetch_row($table, $where = null, $order = null)
    {
        $this->slave();

        $select = $this->select();

        $select->from($this->get_table($table), '*');

        if ($where)
        {
            $select->where($where);
        }

        if ($order)
        {
            if (strstr($order, ','))
            {
                if ($all_order = explode(',', $order))
                {
                    foreach ($all_order AS $current_order)
                    {
                        $select->order($current_order);
                    }
                }
            }
            else
            {
                $select->order($order);
            }
        }

        $select->limit(1, 0);

        $sql = $select->__toString();

        if (Application::config()->get('system')->debug)
        {
            $start_time = microtime(TRUE);
        }

        try {
            $result = $this->db()->fetchRow($select);
        } catch (Exception $e) {
            show_error("Database error\n------\n\nSQL: {$sql}\n\nError Message: " . $e->getMessage(), $e->getMessage());
        }

        if (Application::config()->get('system')->debug)
        {
            Application::debug_log('database', (microtime(TRUE) - $start_time), $sql);
        }

        return $result;
    }

    /**
     * 查询单字段, 直接返回数据
     *
     * 面向对象数据库操作, 表名无需加表前缀, 数据也无需使用 $this->quote 进行过滤 ($where 条件除外)
     *
     * @param    string
     * @param    string
     * @param    string
     * @param    string
     * @return    mixed
     */
    public function fetch_one($table='', $column='*', $where = null, $order = null)
    {
        $this->slave();

        $select = $this->select();

        $select->from($this->get_table($table), $column);

        if ($where)
        {
            $select->where($where);
        }

        if ($order)
        {
            if (strstr($order, ','))
            {
                if ($all_order = explode(',', $order))
                {
                    foreach ($all_order AS $current_order)
                    {
                        $select->order($current_order);
                    }
                }
            }
            else
            {
                $select->order($order);
            }
        }

        $select->limit(1, 0);

        $sql = $select->__toString();

        if (Application::config()->get('system')->debug)
        {
            $start_time = microtime(TRUE);
        }

        try {
            $result = $this->db()->fetchOne($select);
        } catch (Exception $e) {
            show_error("Database error\n------\n\nSQL: {$sql}\n\nError Message: " . $e->getMessage(), $e->getMessage());
        }

        if (Application::config()->get('system')->debug)
        {
            Application::debug_log('database', (microtime(TRUE) - $start_time), $sql);
        }

        return $result;
    }

    /**
     * 获取记录总数, SELECT COUNT() 方法
     *
     * 面向对象数据库操作, 表名无需加表前缀, 数据也无需使用 $this->quote 进行过滤 ($where 条件除外)
     *
     * @param    string $table 表名
     * @param    string $where 查询条件
     * @param    string $column 列名称。 支持 distinct
     * @return    int
     */
    public function count($table, $where = '', $column='*')
    {
        $this->slave();

        $select = $this->select();
        $select->from($this->get_table($table), 'COUNT('.$column.') AS n');

        if ($where)
        {
            $select->where($where);
        }

        $sql = $select->__toString();

        if (Application::config()->get('system')->debug)
        {
            $start_time = microtime(TRUE);
        }

        try {
            $result = $this->db()->fetchRow($select);
        } catch (Exception $e) {
            show_error("Database error\n------\n\nSQL: {$sql}\n\nError Message: " . $e->getMessage(), $e->getMessage());
        }

        if (Application::config()->get('system')->debug)
        {
            Application::debug_log('database', (microtime(TRUE) - $start_time), $sql);
        }

        return $result['n'];
    }

    /**
     * 计算字段最大值, SELECT MAX() 方法
     *
     * 面向对象数据库操作, 表名无需加表前缀, 数据也无需使用 $this->quote 进行过滤 ($where 条件除外)
     *
     * @param    string
     * @param    string
     * @param    string
     * @return    int
     */
    public function max($table, $column, $where = '')
    {
        $this->slave();

        $select = $this->select();
        $select->from($this->get_table($table), 'MAX(' . $column . ') AS n');

        if ($where)
        {
            $select->where($where);
        }

        $sql = $select->__toString();

        if (Application::config()->get('system')->debug)
        {
            $start_time = microtime(TRUE);
        }

        try {
            $result = $this->db()->fetchRow($select);
        } catch (Exception $e) {
            show_error("Database error\n------\n\nSQL: {$sql}\n\nError Message: " . $e->getMessage(), $e->getMessage());
        }

        if (Application::config()->get('system')->debug)
        {
            Application::debug_log('database', (microtime(TRUE) - $start_time), $sql);
        }

        return $result['n'];
    }

    /**
     * 计算字段最小值, SELECT MIN() 方法
     *
     * 面向对象数据库操作, 表名无需加表前缀, 数据也无需使用 $this->quote 进行过滤 ($where 条件除外)
     *
     * @param    string
     * @param    string
     * @param    string
     * @return    int
     */
    public function min($table, $column, $where = '')
    {
        $this->slave();

        $select = $this->select();
        $select->from($this->get_table($table), 'MIN(' . $column . ') AS n');

        if ($where)
        {
            $select->where($where);
        }

        $row = $this->db()->fetchRow($select);

        $sql = $select->__toString();

        if (Application::config()->get('system')->debug)
        {
            $start_time = microtime(TRUE);
        }

        try {
            $result = $this->db()->fetchRow($select);
        } catch (Exception $e) {
            show_error("Database error\n------\n\nSQL: {$sql}\n\nError Message: " . $e->getMessage(), $e->getMessage());
        }

        if (Application::config()->get('system')->debug)
        {
            Application::debug_log('database', (microtime(TRUE) - $start_time), $sql);
        }

        return $result['n'];
    }

    /**
     * 计算字段总和, SELECT SUM() 方法
     *
     * 面向对象数据库操作, 表名无需加表前缀, 数据也无需使用 $this->quote 进行过滤 ($where 条件除外)
     *
     * @param    string
     * @param    string
     * @param    string
     * @return    int
     */
    public function sum($table, $column, $where = '')
    {
        $this->slave();

        $select = $this->select();
        $select->from($this->get_table($table), 'SUM(' . $column . ') AS n');

        if ($where)
        {
            $select->where($where);
        }

        $sql = $select->__toString();

        if (Application::config()->get('system')->debug)
        {
            $start_time = microtime(TRUE);
        }

        try {
            $result = $this->db()->fetchRow($select);
        } catch (Exception $e) {
            show_error("Database error\n------\n\nSQL: {$sql}\n\nError Message: " . $e->getMessage(), $e->getMessage());
        }

        if (Application::config()->get('system')->debug)
        {
            Application::debug_log('database', (microtime(TRUE) - $start_time), $sql);
        }

        return intval($result['n']);
    }

    /**
     * 添加引号防止数据库攻击
     *
     * 外部提交的数据需要使用此方法进行清理
     *
     * @param    string
     * @return    string
     */
    public function quote($string)
    {
        if (is_object($this->db()))
        {
            $_quote = $this->db()->quote($string);

            if (substr($_quote, 0, 1) == "'")
            {
                $_quote = substr(substr($_quote, 1), 0, -1);
            }

            return $_quote;
        }

        if (function_exists('mysql_escape_string'))
        {
            $string = @mysql_escape_string($string);
        }
        else
        {
            $string = addslashes($string);
        }

        return $string;
    }

    /**
     * Model 类析构, 执行延迟查询
     */
    public function __destruct()
    {
        $this->master();

        foreach ($this->_shutdown_query AS $key => $query)
        {
            $this->query($query);
        }
    }
}
