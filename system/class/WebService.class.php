<?php
namespace Model;
/**
 * WebService.class.php
 * @author wangzx
 */

/**
 * WebService class 实现了发送HTTP请求到服务器端， 获取服务器响应
 * @abstract
 */
import('ErrorCoder', LIB_ROOT_PATH.'Classes/');
import('GFile', LIB_ROOT_PATH.'Classes/');
set_include_path(get_include_path() . PATH_SEPARATOR . LIB_ROOT_PATH . '3rdParty');


include 'Zend/Loader/AutoloaderFactory.php';
\Zend\Loader\AutoloaderFactory::factory(array(
     'Zend\Loader\StandardAutoloader' => array('autoregister_zf' => true)
        ));

class WebService
{
    /*
     * HTTP client 实例化模型
     * @var Zend_Http_Client
     */
	// 设定日志记录函数是否已经注册
	static $isFuncRegisteredFlag = false;
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
     * PHP包含路径
     * @var string
     */
    protected static $includePath = '';

    /*
     * HTTP response
     * @var Zend_Http_Response
     */
    protected $httpResponse;

    /**
     * Batch request pool 批量请求参数列表
     * @var array
     */
    protected $batchRequestPool = array();

    /**
     * Batch response pool 批量请求后的响应数据列表
     * @var array
     */
    protected $batchResponsePool = array();

    protected $requestContent = '';

    /*
     * 通过HTTP请求新建数据
     * @const string
     */
    const OC_HTTP_CRUD_C = 'C';
    /*
     * 通过HTTP请求读取数据
     * @const string
     */
    const OC_HTTP_CRUD_R = 'R';
    /*
     * 通过HTTP请求更新数据
     * @const string
     */
    const OC_HTTP_CRUD_U = 'U';
    /*
     * 通过HTTP请求删除数据
     * @const string
     */
    const OC_HTTP_CRUD_D = 'D';


    /**
     * 构造函数
     * 载入zend类文件，初始化请求为读取
     */
    public function __construct()
    {
        // 设定包含路径。 将自添加的Zend路径加入到包含路径中
        if('' == self::$includePath) {
            self::$includePath = LIB_ROOT_PATH . '3rdParty/' . PATH_SEPARATOR . get_include_path();
            //set_include_path(self::$includePath);

            require_once('Zend/Http/Client.php');
            // 设置默认的请求方法为读取
            $this->setCRUD(self::OC_HTTP_CRUD_R);
        }

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
        // 优先使用 curl
        if(function_exists('curl_init')) { // 系统存在curl模块
            $httpRequestConfig = array('ssltransport' => 'tls',
                'adapter'=>'\Zend\Http\Client\Adapter\Curl',
                'curloptions'=>array(CURLOPT_SSL_VERIFYPEER=>false));
        } else { //系统没有 curl模块，通过socket建立http连接
            $httpRequestConfig = array('adapter'=>'\Zend\Http\Client\Adapter\Socket',);
        }

        // 合并配置参数
        settype($config, 'array');
        //$config['timeout'] = 300;
        $httpRequestConfig = array_merge($config, $httpRequestConfig);


        // 实例化http client
        $this->httpClient = new \Zend\Http\Client($url, $httpRequestConfig);

        return $this->httpClient;
    }

    protected function _convertRequestMethod ($crud = self::OC_HTTP_CRUD_R)
    {
        // 通过参数首字母判断
        switch(strtoupper($crud[0])) {
            case self::OC_HTTP_CRUD_C : // 新建
                $httpMethod = \Zend\Http\Request::METHOD_POST;
                break;

            case self::OC_HTTP_CRUD_U : // 更新
                $httpMethod = \Zend\Http\Request::METHOD_PUT;
                break;

            case self::OC_HTTP_CRUD_D : // 删除
                $httpMethod = \Zend\Http\Request::METHOD_DELETE;
                break;

            case self::OC_HTTP_CRUD_R : // 读取
            default: // 默认是读取
                $httpMethod = \Zend\Http\Request::METHOD_GET;
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
    public function setCRUD($crud = self::OC_HTTP_CRUD_R)
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
        /*$session = session(MODULE_NAME);
        if((isset($session['reg_type']))&&($session['reg_type']==1)&&$session['bizid']){
            $params['bizid'] = $session['bizid'];
        }*/
        $this->httpParams = (array) $params;

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
        $this->httpHeaders = (array) $headers;

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

    public function addRequestIntoBatch ($bindKey, $url, $crudMethod=null, $params=array(),$seq=null)
    {
        // 参数类型转换
        settype($bindKey, 'string');
        settype($url, 'string');

        // 如果没设置绑定的关键字， 直接返回
        if (''===$bindKey) {
            return $this;
        }
        // 将传递的参数缓存
        $this->batchRequestPool[$bindKey] = array(
            'url'       => $url,
            'method'    => $crudMethod,
            'params'    => $params,
        );
        if($seq != 'null'){
            $this->batchRequestPool[$bindKey]['seq'] = $seq;
        }else{
        	$this->batchRequestPool[$bindKey]['seq'] = 0;
        }
        return $this;
    }

    public function removeRequestFromBatch ($bindKey='')
    {
        settype($bindKey, 'string');
        if (''===$bindKey) {
            $this->batchRequestPool = array();
        } else if (isset($this->batchRequestPool[$bindKey])) {
            unset($this->batchRequestPool[$bindKey]);
        }

        return;
    }

    /**
     *
     */
    public function sendBatchRequest ()
    {
        if (! $this->batchRequestPool) {
            return $this;
        }
        $requestParams = array();
        $seq = 1;
        foreach ($this->batchRequestPool as $_bindKey => $_params) {
            $_url = substr($_params['url'], strlen(WEB_SERVICE_ROOT_URL));
            $method = $this->_convertRequestMethod($_params['method']);
            $requestParams[$_bindKey] = array(
                'url'   => $_url,
                'method'=> $method,
                'object'=> $_params['params']
            );
            if(isset($_params['seq'])){
                $requestParams[$_bindKey]['seq'] = $_params['seq'];
            }else{
                $requestParams[$_bindKey]['seq'] = $seq;
            }
            $seq++;
        }
        $requestParams = array('object'=>json_encode($requestParams));
        // web service 接口路径
        $webServiceRootUrl = C('API_BATCH');
        // 设置请求方法为 读取
        $crudMethod = self::OC_HTTP_CRUD_C;
        // 发送http请求
        //var_dump($webServiceRootUrl, $crudMethod, $requestParams );exit;
        $response = $this->request ( $webServiceRootUrl, $crudMethod, $requestParams );
        // 解析http 请求
        $this->parseBatchResponse($response);

        return $this->batchResponsePool;
    }

    /**
     * Parse response returned by batch request
     * 解析批量请求后的响应数据
     * @param string $response 批量请求的响应数据
     * @return void
     */
    protected function parseBatchResponse($response)
    {
        $this->batchResponsePool = array();
        $response = parseApi($response);
        foreach ($response as $_bindKey => $_tmpResponse) {
            $this->batchResponsePool[$_bindKey] = $_tmpResponse;
        }

        return;
    }

    /**
     * Get response returned by batch request 获取批量请求的响应
     * @param string $bindKey 绑定的关键字
     * @return mixed
     */
    public function getBatchResponse ($bindKey='')
    {
        settype($bindKey, 'string');
        $response = null;
        if(''===$bindKey) {
            $response = $this->batchResponsePool;
        } else {
            $response = $this->batchResponsePool[$bindKey];
        }

        return $response;
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
        trace('[HttpRequest] ['.$starttime.'] --Start--' , '', 'DEBUG');
        // 查看是否需要重新获取token
        $session = session(MODULE_NAME);
        if (isset($session['accesstoken'], $session['tokenExpireTime'])
            && ($session['tokenExpireTime'] <= (time()-60 ) ) ) {
            // token 即将过期或者已过期， session仍然有效， 重新获取token.
            $this->_autoLoadToken();
            $session = session(MODULE_NAME);
        }

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
        if (!isset($this->httpHeaders['clientId']) && isset($session['clientId'])) {
            // 设置头信息包括clientId
            $this->httpHeaders['clientId'] = $session['clientId'];
        }
        if (!isset($this->httpHeaders['AccessToken']) && isset($session['accesstoken'])) {
            // 设置头信息包括token
            $this->httpHeaders['AccessToken'] = $session['accesstoken'];
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
                    \Zend\Http\Request::METHOD_POST,
                    \Zend\Http\Request::METHOD_PUT
                );
                if (in_array ( $this->httpMethod, $_postMethods )) {
                    // PUT and POST requires to set the content-type
                    // $httpClient->setEncType(Zend_Http_Client::ENC_URLENCODED);
                    $httpClient->setEncType ( \Zend\Http\Client::ENC_FORMDATA );
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
                    /*
                    if(count($this->httpUploadFiles)>1){
                        foreach ( $this->httpUploadFiles as $k => $v ) {
                            $key = key($v);
                            $httpClient->setFileUpload ( $v[$key],$key );
                        }
                    }else{
                    */
                    foreach ( $this->httpUploadFiles as $_key => $_uploadFilePath ) {
                        $httpClient->setFileUpload ( $_uploadFilePath, $_key );
                    }
                    //}
                }
                if ('' != $this->requestContent) {
                    $httpClient->setRawBody($this->requestContent);
                }
                // 传递请求方法
                $this->httpResponse = $httpClient->setMethod( $this->httpMethod )->send();
                // 获取响应
                if (200 <= $this->httpResponse->getStatusCode () && $this->httpResponse->getStatusCode ()<300) { // 正常响应
                    $result = $this->httpResponse->getBody ();
                    $resultInfo = @json_decode ( $result, true );
                    if (isset($resultInfo['head'], $resultInfo['head']['error']) && 100002==$resultInfo['head']['error']['errorcode']) {
                        $_isValidUser = $this->_autoLoadToken();
                        if (true===$_isValidUser) {
                            $this->httpHeaders['AccessToken'] = $_SESSION[MODULE_NAME]['accesstoken'];
                            continue;
                        } else {
                            //$result = 'caonima';
                            session(MODULE_NAME, null);
                            redirect(U(MODULE_NAME.'/index/index'));
                        }
                    }
                } else { // 失败返回错误
                    $result = new \ErrorCoder ( \ErrorCoder::ERR_HTTP_RESPONSE_CODE_ERROR );
                }

                break;// 成功执行请求， 跳出循环
            }

        } catch (\Exception $e) { // 截获异常， 返回错误
            $result = new \ErrorCoder(\ErrorCoder::ERR_BAD_REQUEST);
            trace($e->getMessage(), '', 'ERR' );
        }

        if(APP_DEBUG) {
            $status = 'Unknow';
            if ($this->httpResponse instanceof \Zend\Http\Response) {
                $status = $this->httpResponse->getStatusCode();
            }
            $logMessage = '[DEBUG] WebService: URI->' . $this->httpUri
                . "\tMethod->" . $this->httpMethod;
            if ($this->httpHeaders) {
                $logMessage = $logMessage . "\tHeader->" . print_r($this->httpHeaders, true);
            }
            if ($this->httpParams) {
                $logMessage = $logMessage . "\tParams->" . print_r($this->httpParams, true);
            }
            if (''!==$this->requestContent) {
                $logMessage = $logMessage . "\tRawBody->" . print_r($this->requestContent, true);
            }
            if ($this->httpUploadFiles) {
                $logMessage = $logMessage . "\tUploadFile->" . print_r($this->httpUploadFiles, true);
            }
            $logMessage = $logMessage . "\tHttpCode->" . $status
                . "\tResult->" . print_r($this->httpResponse?$this->httpResponse->getBody():$result, true);//$result

            trace($logMessage, '', 'DEBUG', true);
        }
        $endtime = date('Y-m-d H:i:s');
        trace('[HttpRequest] ['.$endtime.'] --End--' , '', 'DEBUG');
        (C('DEBUG_TYPE_INT') & C(MODULE_NAME)) && $this->actionLogs(true,true);
        return $result;
    }

    /**
     * Load token automatically by sending username&password in session again
     *
     * @return mixed If token is loaded successfully, return true; or return ErrorCoder
     */
    protected function _autoLoadToken ()
    {
        trace('[HttpRequest] ['.date('Y-m-d H:i:s').'] --Start-- autoLoadToken' , '', 'DEBUG');
        // 取得用户名和密码
        $session = session(MODULE_NAME);
        $params = array (
            'user'     => $session['username'],
            'passwd'   => $session['password'],
            'type'     => $session['userType'],
        	'ismd5'    => 1,
            'ip'       => get_client_ip()
        );
        // 取得http client 实例
        $httpClient = $this->getHttpClient();
        // 尝试发送请求并获取响应
        $uri = rtrim(C('WEB_SERVICE_ROOT_URL'), '/') . C('API_LOGIN_URL');
        try {
            $httpClient->setUri($uri);
            // 将请求参数传递到http client中
            $httpMethod = \Zend\Http\Request::METHOD_POST;
            $httpClient->setParameterPost($params);
            // 传递请求方法
            $response = $httpClient->setMethod($httpMethod)->send();
            // 获取响应内容
            if(200==$response->getStatusCode()) { // 正常响应
                $result = json_decode($response->getBody(), true);
                if (isset($result['body'])) {
                    // 重新设置token的session内容
                    $session['accesstoken'] = $result['body']['accesstoken'];
                    $session['tokenExpireTime'] = $result['body']['expiration'] + time();
                    session(MODULE_NAME, $session);
                    $result = true;
                } else {
                    $errorCode = $result['head']['error']['errorcode'];
                    $result = new \ErrorCoder($errorCode);
                }

            } else { // 失败返回错误
                $result = new \ErrorCoder(\ErrorCoder::ERR_HTTP_RESPONSE_CODE_ERROR);
            }

        } catch (\Exception $e) { // 截获异常， 返回错误
            $result = new \ErrorCoder(\ErrorCoder::ERR_BAD_REQUEST);
            trace($e->getMessage(), '', 'DEBUG' );
        }

        if(APP_DEBUG) {
            $status = 'Unknow';
            if ($response instanceof \Zend\Http\Response) {
                $status = $response->getStatusCode();
            }
            $logMessage = '[DEBUG] WebService: URI->' . $uri . "\tMethod->" . $httpMethod;
            if ($params) {
                $logMessage = $logMessage . "\tParams->" . print_r($params, true);
            }
            $logMessage = $logMessage . "\tHttpCode->" . $status
                . "\tResult->" . print_r($result, true);

            trace($logMessage, '', 'DEBUG' );
        }

        trace('[HttpRequest] ['.date('Y-m-d H:i:s').'] --End-- autoLoadToken' , '', 'DEBUG');
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
        $result = \GFile::mkdir($dirPath);
        if (! $result) {// 创建目录失败
            return new \ErrorCoder(\ErrorCoder::ERR_FILE_CANNOT_MKDIR);
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

    /**
     * @param string $imagePath 头像地址
     * @param bool $isSystemImage 是否是系统头像
     * @return
     */
    public function getImageFromApi ($imagePath, $apiurl)
    {
        // web service 接口路径
        $webServiceRootUrl = rtrim(C('WEB_SERVICE_ROOT_URL'), '/') . $apiurl;
        // 设置请求方法为 读取
        $crudMethod = WebService::OC_HTTP_CRUD_R;
        // 设置请求参数
        $params = array('path'=>$imagePath);
        // 发送http请求
        $response = $this->request($webServiceRootUrl, $crudMethod, $params);
        // 解析http 请求
        if($response instanceof \ErrorCoder) { // 请求错误。 错误处理
            $errorMessage = $response->getErrorDesc();
            $errorCode = $response->getErrorCode();

            // @todo 错误处理逻辑

        } else {

        }

        return $response;
    }
    /**
     * 获取图片资源信息
     * @param unknown $path
     */
    public function getImageUrl($path,$type){
        switch($type)
        {
            case 'head':
                header('Content-type: png');
                $imgurl = './images/cards_logo.png';
                $ApiName = '/account/avatar';
                break;
            case 'scan':
                header('Content-type: png');
                $imgurl = './images/cardScreenShot.jpg';
                $ApiName = '/scancard/picture';
                break;
            case 'sns':
                header('Content-type: png');
                $imgurl = './images/background/sns_vodie_img.png';
                $ApiName ='/download';
                break;
            case 'orange':
                header("Content-type:audio/mp4");
                $imgurl = './images/background/sns_vodie_img.png';
                $ApiName ='/download';
                break;
            case 'newsfile': // 企业新闻资源文件
                header("Content-type:png");
                $imgurl = './images/background/sns_vodie_img.png';
                $ApiName ='/accountbiz/newsfile';
                break;
            case 'pick': //获取卡包图片
                header('Content-type: png');
                $imgurl = './images/background/cardPackage_imgpicbg.png';
                $ApiName ='/cardpackage/download';
                break;
            default:
                header('Content-type: png');
                $ApiName = '/account/avatar';
                $imgurl = './images/background/cards_logo.png';
        }
        if($path == ''){
            echo file_get_contents($imgurl);
        }else{
            // web service 接口路径
            $webServiceRootUrl = rtrim(C('WEB_SERVICE_ROOT_URL'), '/') . $ApiName;
            // 设置请求方法为 读取
            $crudMethod = self::OC_HTTP_CRUD_R;
            // 设置请求参数
            $params = array();
            $params['path'] = $path;
            // 发送http请求
            $response = $this->request($webServiceRootUrl, $crudMethod, $params);
            // 解析http 请求
            if($response instanceof \ErrorCoder) { // 请求错误。 错误处理
                $errorMessage = $response->getErrorDesc();
                $errorCode = $response->getErrorCode();
                echo file_get_contents($imgurl);
            } else {// 解析查询结果
                $status = json_decode($response,true);
                if(isset($status['head']['status']) && $status['head']['status'] == '1'){
                    echo file_get_contents($imgurl);
                }else{
                    echo $response;
                }
            }
        }
    }
    /**
     * 记录操作日志
     * @param boolean $switch true 合法操作 | false 是非法操作
     * @param boolean $ApiStatus 是否记录api信息
     */
    public function actionLogs($switch = true,$ApiStatus = false){
    	$controller = strtolower(CONTROLLER_NAME);
    	$action = strtolower(ACTION_NAME);
    	$logsConfig = C('LOGS.'.$controller);
    	$logsConfig = $logsConfig[$action];
    	if(!isset($logsConfig['switch']) || $logsConfig['switch'] == '1'){
	    	if (false === self::$isFuncRegisteredFlag) { // 日志记录函数没有注册
	    		$_SESSION['logInfo'] = array();
	    		self::$isFuncRegisteredFlag = true; // 将标识置位
	    		register_shutdown_function(array($this, 'doLogRequest')); // 登记进程关闭回调
	    	}
    	}else{
    		return ;
    	}
    	$_SESSION['logInfo']['status'] = true === $switch?'1':'0';
    	if($ApiStatus && $switch){

			$logMessage = array();
			if ($this->httpResponse instanceof \Zend\Http\Response) {
				$status = $this->httpResponse->getStatusCode();
			    $getBody = json_decode($this->httpResponse->getBody(),true);
			    $logStatus = $getBody['head']['status'];
			} else {
				$status = '50x';
			    $logStatus = '50x';
			}
			$logMessage['WebServiceURI'] = $this->httpUri;
			$logMessage['Method'] = $this->httpMethod;
			$logMessage['Header'] = is_array($this->httpHeaders)?json_encode($this->httpHeaders, true):$this->httpHeaders;
			$logMessage['Params'] = is_array($this->httpParams)?json_encode($this->httpParams, true):$this->httpParams;
			$logMessage['HttpCode'] = $status;
			$logMessage['ResultStatus'] = $logStatus;
			$logMessage['status'] = $switch;

			$_SESSION['logInfo']['content'][] = $logMessage;
			$_SESSION['logInfo']['wrtype'] = $this->httpMethod;

    	}

    }
    // PHP程序结束后写入日志
    public function doLogRequest(){
    	$controller = strtolower(CONTROLLER_NAME);
    	$action = strtolower(ACTION_NAME);
    	$userInfo = session(MODULE_NAME);
    	$params = array('userName'=>$userInfo['username'],'userId'=>$userInfo['adminid'],'modelName'=>$controller,'type'=>$action,'loginIp'=>get_client_ip());
    	$params['action'] = 'add';
    	$contact = isset($_SESSION['logInfo']['content'])?$_SESSION['logInfo']['content']:array();
    	$params['content'] = json_encode($contact);
    	$params['wrtype'] = ($_SESSION['logInfo']['wrtype'])?$_SESSION['logInfo']['wrtype']:\Zend\Http\Request::METHOD_GET;
    	$params['status'] = $_SESSION['logInfo']['status'];

    	// 尝试发送请求并获取响应
    	try {
    		// 取得http client 实例
    		$httpClient = $this->getHttpClient();
    		$httpClient->setUri ( C('API_ADMIN_APISTORE_ADMINLOG') );

    		$httpClient->setEncType ( \Zend\Http\Client::ENC_FORMDATA );
    		$httpClient->setParameterPost ( $params );

    		$httpHeaders = array();
    		if (isset($userInfo['clientId'])) {
    			// 设置头信息包括clientId
    			$httpHeaders['clientId'] = $userInfo['clientId'];
    		}
    		if (isset($userInfo['accesstoken'])) {
    			// 设置头信息包括token
    			$httpHeaders['AccessToken'] = $userInfo['accesstoken'];
    		}
    		$httpClient->setHeaders ( $httpHeaders );
    		// 传递请求方法
    		$this->httpResponse = $httpClient->setMethod( \Zend\Http\Request::METHOD_POST )->send();
    	}catch (\Exception $e){
    	}
    }



    /**
     * 调用api， 并解析响应数据
     * @param string $webServiceRootUrl Restful调用的URL
     * @param string $crudMethod Restful调用的方法 cRUD
     * @param array $params 传递的参数
     * @param array $headers 传递的Restful头信息
     * @return array
     */
    public function callAndParseApi ($webServiceRootUrl, $crudMethod=null, $params=array(), $headers=array())
    {
        // 发送http请求
        $response = $this->request($webServiceRootUrl, $crudMethod, $params, $headers);
        //* 解析http 请求
        $response = parseApi($response);

        return $response;
    }


}

/* EOF */
