<?php
abstract class BaseClass
{
    /**
     * 是否进入debug模式
     * @var bool
     */
    public $debug = false;

    /**
     * 错误信息的代码和描述内容, 每条错误信息为一个数组:array('code'=>0, 'msg'=>'')
     * @var array
     */
    protected $error = array();

    protected $hooks = array();

    /**
     * 用于记录日志的回调信息
     * @var object
     */
    public $logger = null;

    public function log ($message, $level='debug')
    {
        if ($this->logger && is_callable($this->logger)) {
            try {
                call_user_func_array($this->logger, array($message, $level));
            } catch (Exception $e) {}
        }
    }

    /**
     * 设置debug模式
     * @param bool $debug 是否开启debug
     * @return self
     */
    public function setDebug ($debug)
    {
        $this->debug = (bool) $debug;

        return $this;
    }

    /**
     * 获取错误信息, 如果指定序号， 返回指定序号的错误
     * @return multitype:
     */
    public function getError ($index=null)
    {
        $error = null;

        if (isset($index)) {
            // 获取指定错误
            if (isset($this->error[$index-1])) {
                $error = $this->error[$index-1];
            }
        } else {// 未指定， 获取全部错误
            $error = $this->error;
        }

        return $error;
    }

    /**
     * 获取最后错误信息
     * @return mixed 错误信息数组或者空
     */
    public function getLastError ()
    {
        $count = count($this->error);

        return $this->error[$count-1];
    }

    /**
     * 添加错误信息
     * @param string $errorDesc 错误信息
     * @param int $errorCode 错误号码
     * @return int 返回错误排序号
     */
    public function setError ($errorDesc, $errorCode)
    {
        static $errorIndex = 0;

        $error = array('code' => $errorCode, 'msg' => $errorDesc);
        array_push($this->error, $error);
        $errorIndex++;

        return $errorIndex;
    }

    /**
     * 设置属性
     * @param array $options
     * @return this
     */
    public function setOptions ($options)
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
        $this->$key = $value;
        return $this;
    }

    /**
     * 设置回掉钩子
     * @param string $name 钩子名称
     * @param callback $callbackName 回掉函数／方法
     * @param int  $priority 设置回调优先级
     */
    public function setHook ($name, $callbackName, $priority=null)
    {
        if (is_callable($callbackName)) { // 确认回调是可被调用的
            isset($this->hooks) OR $this->hooks[$name] = []; // 回调数组设置
            isset($priority) OR $priority = count($this->hooks[$name]); // 默认把回调放到最后位置
            isset($this->hooks[$name][$priority]) OR $this->hooks[$name][$priority] = [];

            $this->hooks[$name][$priority] [] = $callbackName;
        }

        return $this;
    }

    /**
     * 获取回掉钩子
     * @param string $name 钩子名称
     * @param int  $priority 对应回调优先级
     */
    public function getHook ($name, $priority=null)
    {
        $hook = null;

        if (isset($this->hooks[$name])) {
            if (isset($priority) ) {
                if (isset($this->hooks[$name][$priority]) ) {
                    $hook = $this->hooks[$name][$priority];
                }
            } else {
                $hook = $this->hooks[$name];
            }
        }
        if (is_array($hook) && 1==count($hook)) {
            $hook = array_pop($hook);
        }

        return $hook;
    }

    /**
     * 序列调用方法/函数。
     * @param string $callbackList 逗号分隔的回调函数/方法列表
     * @param mixed  $value 参数
     *
     * @return mixed
     */
    public function sequenceCall ($callbackList, $value)
    {
        if (is_string($callbackList)) {
            $callbackList = explode('|', $callbackList);
        }
        if (is_array($callbackList)) {
            foreach ($callbackList as $_callback) {
                if (is_string($_callback)) {
                    $_callback = trim($_callback);
                }
                if (is_callable($_callback)) {
                    $value = call_user_func($_callback, $value);
                }
            }
        }

        return $value;
    }

    /**
     * 序列调用方法/函数。
     * @param string $callbackList 逗号分隔的回调函数/方法列表
     * @param mixed  $value 参数
     *
     * @return mixed
     */
    public function doCall ($callbackList, $value)
    {
        return $this->sequenceCall($callbackList, $value);
    }

}

/* EOF */
