<?php
importClass('Log');

class Base
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

    /**
     * 用于记录日志的回调信息
     * @var object
     */
    public $logger = null;

    /**
     * 信息加入log
     * @param string $message 记录的日志信息
     * @param string $level 日志级别
     */
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
        if (isset($index, $this->error[$index-1])) {
        // 获取指定错误
            $error = $this->error[$index-1];
        } else {// 未指定， 获取全部错误
            $error = $this->error ? $this->error : null;
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

        return $this->getError($count-1);
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

}

/* EOF */
