<?php
/**
 * WebService.class.php
 * @author wangzx
 */

/**
 * WebService class 实现了发送HTTP请求到服务器端， 获取服务器响应
 * @abstract
 */


class WebService
{
    /*
     * HTTP client 实例化模型
     * @var Zend_Http_Client
     */
    protected $httpClient = null;

    /*
     * HTTP 请求方法: Get, Post, Put, Delete
     * @var string
     */
    protected $httpMethod = '';

    /*
     * HTTP 请求参数
     * @var array
     */
    protected $httpParams = array();

    /*
     * HTTP 请求头部信息
     * @var array
     */
    protected $httpHeaders = array();

    /*
     * HTTP 要上传的文件
     * @var array
     */
    protected $httpUploadFiles = array();

    /*
     * HTTP 请求URI
     * @var string
     */
    protected $httpUri = '';

    /*
     * HTTP response
     * @var Zend_Http_Response
     */
    protected $httpResponse;

    protected $requestContent = '';

    protected $httpClientConfig = array();

    /*
     * 通过HTTP请求新建数据
     * @const string
     */
    const HTTP_CRUD_C = 'C';
    /*
     * 通过HTTP请求读取数据
     * @const string
     */
    const HTTP_CRUD_R = 'R';
    /*
     * 通过HTTP请求更新数据
     * @const string
     */
    const HTTP_CRUD_U = 'U';
    /*
     * 通过HTTP请求删除数据
     * @const string
     */
    const HTTP_CRUD_D = 'D';


    /**
     * 构造函数
     * 载入zend类文件，初始化请求为读取
     */
    public function __construct()
    {
        require_once('Zend/Http/Client.php');
        require_once 'Zend/Loader/AutoloaderFactory.php';
        Zend_Loader_AutoloaderFactory::factory();
        // 设置默认的请求方法为读取
        $this->setCRUD(self::HTTP_CRUD_R);
    }

    /**
     * 初始化http请求客户端
     * @param string $url
     * @param array  $config
     *
     * @return Zend_Http_Client
     */
    public function getHttpClient ($url = null, $config = array())
    {
        // 合并配置参数
        settype($config, 'array');
        isset($config['timeout']) OR $config['timeout'] = 30;
        // 优先使用 curl
        if(function_exists('curl_init')) { // 系统存在curl模块
            $httpRequestConfig = array('ssltransport' => 'tls',
                'adapter'=>'Zend_Http_Client_Adapter_Curl',
                'curloptions'=>array(CURLOPT_SSL_VERIFYPEER=>false),
                'request_timeout'   => $config['timeout'],
            );
        } else { //系统没有 curl模块，通过socket建立http连接
            $httpRequestConfig = array('adapter'=>'Zend_Http_Client_Adapter_Socket',);
        }
        $httpRequestConfig = array_merge($httpRequestConfig, $config, $this->httpClientConfig);

        // 实例化http client
        $this->httpClient = new Zend_Http_Client($url, $httpRequestConfig);

        return $this->httpClient;
    }

    public function setClientConfig ($config)
    {
        settype($config, 'array');

        if ($this->httpClient instanceof Zend_Http_Client) {
            $this->httpClient->setConfig($config);
        } else {
            $this->httpClientConfig = array_merge($this->httpClientConfig, $config);
        }

        return $this;
    }

    protected function _convertRequestMethod ($crud = self::HTTP_CRUD_R)
    {
        // 通过参数首字母判断
        switch(strtoupper($crud[0])) {
            case self::HTTP_CRUD_C : // 新建
                $httpMethod = Zend_Http_Client::POST;
                break;

            case self::HTTP_CRUD_U : // 更新
                $httpMethod = Zend_Http_Client::PUT;
                break;

            case self::HTTP_CRUD_D : // 删除
                $httpMethod = Zend_Http_Client::DELETE;
                break;

            case self::HTTP_CRUD_R : // 读取
            default: // 默认是读取
                $httpMethod = Zend_Http_Client::GET;
                break;
        }

        return $httpMethod;
    }

    /**
     * 设置数据操作方法： 新建/更新/读取/删除 . 将对应不同的HTTP请求method
     * @param string $crud 请求的CRUD操作
     *
     * @return object Self instance
     */
    public function setCRUD($crud = self::HTTP_CRUD_R)
    {
        $this->httpMethod = $this->_convertRequestMethod($crud);

        return $this;
    }

    /**
     * 设置HTTP请求的URI
     * @param string $uri HTTP请求的URI
     *
     * @return object Self instance
     */
    public function setUri($uri)
    {
        $this->httpUri = $uri;

        return $this;
    }

    /**
     * 设置HTTP请求参数
     * @param array $params 键值对关联数组
     *
     * @return object Self instance
     */
    public function setParams($params)
    {
        $this->httpParams = array_merge($this->httpParams, (array) $params);

        return $this;
    }

    /**
     * 设置HTTP请求头信息
     * @param array $headers 键值对关联数组
     *
     * @return object Self instance
     */
    public function setHeaders($headers)
    {
        $this->httpHeaders = array_merge($this->httpHeaders, (array) $headers);

        return $this;
    }

    public function setRawBody ($content)
    {
        $this->requestContent = $content;

        return $this;
    }

    /**
     * 设置上传的文件
     * @param string $filePath 要上传的文件路径
     * @param string $nameInForm 文件在上传处理中对应的名字
     *
     * @return object Self instance
     */
    public function setUploadFile ($filePath, $nameInForm)
    {
        settype ($nameInForm, 'string');
        $filePath = realpath($filePath);
        if (is_file($filePath)) {
            $this->httpUploadFiles[$nameInForm] = $filePath;
        }

        return $this;
    }

    /**
     * 获取HTTP连接，打包参数，发送HTTP请求
     * @param string $uri
     * @param string $crudMethod
     * @param array $params
     * @param array $headers
     *
     * return mixed The response string or error info array
     */
    public function request($uri=null, $crudMethod=null, $params=array(), $headers=array())
    {
    	$starttime = date('Y-m-d H:i:s');

        if($uri) { // 设置请求接口
            $this->setUri($uri);
        }
        if($crudMethod) { // 设置请求方法
            $this->setCRUD($crudMethod);
        }
        $params = $params ? $params : array();
        // 设置请求参数
        $this->setParams($params);

        if($headers) { // 设置请求头信息
            $this->setHeaders($headers);
        }

        // 取得http client 实例
        $httpClient = $this->getHttpClient();
        // 尝试发送请求并获取响应
        try {
            $i = 0;
            while($i++<2) {
                $httpClient->setUri ( $uri );
                // 将请求参数传递到http client中
                $_postMethods = array (
                    Zend_Http_Client::POST,
                    Zend_Http_Client::PUT
                );

                if (in_array ( $this->httpMethod, $_postMethods )) {
                    // PUT and POST requires to set the content-type
                    // $httpClient->setEncType(Zend_Http_Client::ENC_URLENCODED);
                    $httpClient->setEncType ( Zend_Http_Client::ENC_FORMDATA );
                    $httpClient->setParameterPost ( $this->httpParams );
                } else {
                    $httpClient->setParameterGet ( $this->httpParams );
                }
                // 设置请求的头信息
                if ($this->httpHeaders) {
                    $httpClient->setHeaders ( $this->httpHeaders );
                }
                // 设置上传的文件
                if ($this->httpUploadFiles) {
                    foreach ( $this->httpUploadFiles as $_key => $_uploadFilePath ) {
                        $httpClient->setFileUpload ( $_uploadFilePath, $_key );
                    }
                }

                if ('' != $this->requestContent) {
                    $httpClient->setRawData($this->requestContent);
                }

                // 传递请求方法
                $this->httpResponse = $httpClient->setMethod( $this->httpMethod )->request();
                // 获取响应
                if (200 <= $this->httpResponse->getStatus () && $this->httpResponse->getStatus ()<300) { // 正常响应
                    $result = $this->httpResponse->getBody ();
                } else { // 失败返回错误
                    $result = new ErrorCoder ( ErrorCoder::ERR_HTTP_RESPONSE_CODE_ERROR );
                }

                break;// 成功执行请求， 跳出循环
            }

        } catch (\Exception $e) { // 截获异常， 返回错误
            $result = new ErrorCoder(ErrorCoder::ERR_BAD_REQUEST);
        }

        $endtime = date('Y-m-d H:i:s');

        return $result;
    }

    /**
     * Download file from internet
     * @param string $url   The file URL to be downloaded
     * @param string $destination   The file destination to be stored
     * @param bool   $overwrite If overwrite the existing file
     * @return boolean|ErrorCoder
     * @example
     *      $url = 'http://192.168.30.190/Test/xxxxx.zip';
     *      $destination = '../../Public/test/mycards/cards/myCards.zip';
     *      WebService::downloadFile($url, $destination);
     */
    static public function downloadFile ($url, $destination, $overwrite=true)
    {
        // destinantion file existing, and not set overwrite
        if (true!==$overwrite && is_file($destination)) {
            return new \ErrorCoder(\ErrorCoder::ERR_FILE_EXISTING);
        }

        // 打开远程文件句柄
        $readFilePointer = @ fopen($url, 'rb');
        if (! $readFilePointer) {// 打开远程文件失败
            return new \ErrorCoder(\ErrorCoder::ERR_FILE_OPEN_REMOTE_FAILED);
        }
        // 创建目录
        $dirPath = dirname($destination);
        $destination = $dirPath . DIRECTORY_SEPARATOR . basename($destination);

        if (! is_dir($dirPath)) {
            $result = mkdir($dirPath, 0777, true);
            if (! $result) {// 创建目录失败
                return new \ErrorCoder(\ErrorCoder::ERR_FILE_CANNOT_MKDIR);
            }
        }
        // 创建本地文件句柄
        $writeFilePointer = @fopen($destination, 'wb');
        if (! $writeFilePointer) {// 创建本地文件失败
            return new \ErrorCoder(\ErrorCoder::ERR_FILE_CANNOT_WRITE);
        }

        // 写入文件
        while (! feof($readFilePointer)) {
            $readContents = fread($readFilePointer, 1024);
            fwrite($writeFilePointer, $readContents);
        }
        // 关闭文件句柄
        fclose($readFilePointer);
        fclose($writeFilePointer);

        return true;
    }


}

/* EOF */
