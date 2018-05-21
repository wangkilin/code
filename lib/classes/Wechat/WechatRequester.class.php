<?php
require_once(dirname(__FILE__) . '/WechatAbstract.class.php');
require_once(dirname(__FILE__) . '/ConvertFormat.class.php');

class WechatRequester extends WechatAbstract
{
    protected $httpClient = null;

    /*
     * HTTP 要上传的文件
     * @var array
     */
    protected $httpUploadFiles = array();

    protected $appId = null;
    protected $appSecret = null;
    protected $token = null;// this token is used to generated signature. it's defined by User
    protected $accessToken = null; // this token is got from remote server.

    protected $urlList = array(
        'GetAccessToken' => 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s',
        'SetMenu' => 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=%s',
        'GetMenu' => 'https://api.weixin.qq.com/cgi-bin/menu/get?access_token=%s',
        'GetSubscriber' => 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=%s&openid=%s&lang=zh_CN',
        'GetSubscriberList'=> 'https://api.weixin.qq.com/cgi-bin/user/get?access_token=%s&next_openid=%s',
        'ShakeArroundStatus'=> 'https://api.weixin.qq.com/shakearound/account/auditstatus?access_token=%s',
        'GetMaterialCount'=> 'https://api.weixin.qq.com/cgi-bin/material/get_materialcount?access_token=%s',
        'GetMaterialListByType'=> 'https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=%s',
        'ApplyShakeArround'=> 'https://api.weixin.qq.com/shakearound/account/register?access_token=%s',
        'AddShakeArroundMaterial'=> 'https://api.weixin.qq.com/shakearound/material/add?access_token=%s&type=%s',
        'ApplyShakeArroundDeviceId'=> 'https://api.weixin.qq.com/shakearound/device/applyid?access_token=%s',
        'CheckShakeArroundDeviceApplyStatus' => 'https://api.weixin.qq.com/shakearound/device/applystatus?access_token=%s',
        'GetShakeArroundDeviceList' => 'https://api.weixin.qq.com/shakearound/device/search?access_token=%s',
    );

    public $debug = false;

    public function __construct ($appId, $appSecret, $token)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->token = $token;
        set_include_path(get_include_path() . PATH_SEPARATOR . LIB_ROOT_PATH . '3rdParty');


        include 'Zend/Loader/AutoloaderFactory.php';
        \Zend\Loader\AutoloaderFactory::factory(array(
                'Zend\Loader\StandardAutoloader' => array('autoregister_zf' => true)
        ));
    }

    public function getRequestUrl ($requestType, $params = array())
    {
        $url = null;

        switch($requestType) {
            case 'GetAccessToken':
                $url = sprintf($this->urlList['GetAccessToken'], $this->appId, $this->appSecret);
                break;

            case 'AddShakeArroundMaterial':
                $url = sprintf($this->urlList[$requestType], $this->accessToken, $params['type']);
                break;

            default :
                if(isset($this->urlList[$requestType])) {
                    $url = sprintf($this->urlList[$requestType], $this->accessToken);
                }
                break;
        }

        return $url;
    }

    public function setRequestUrl ($requestType, $url)
    {
        if(isset($this->urlList[$requestType])) {
            $this->urlList[$requestType] = $url;
        }

        return $this;
    }

    /**
     * 初始化http请求客户端
     * @param string $url
     * @param array  $config
     *
     * @return Zend_Http_Client
     */
    protected function getHttpClient ($url = null, $config = array())
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
        $httpRequestConfig = array_merge($config, $httpRequestConfig);

        // 实例化http client
        $this->httpClient = new \Zend\Http\Client($url, $httpRequestConfig);

        return $this->httpClient;
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

    public function request ($uri, $rawdata=null, $method='POST')
    {

    	$starttime = date('Y-m-d H:i:s');
        trace('[HttpRequest] ['.$starttime.'] --Start--' , '', 'DEBUG');
        trace($uri);
        trace($rawdata);

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
                if (in_array ( $method, $_postMethods )) {
                    // PUT and POST requires to set the content-type
                    // $httpClient->setEncType(Zend_Http_Client::ENC_URLENCODED);
                    $httpClient->setEncType ( \Zend\Http\Client::ENC_FORMDATA );
                }
                // 设置上传的文件
                if ($this->httpUploadFiles) {
                    foreach ( $this->httpUploadFiles as $_key => $_uploadFilePath ) {
                        $httpClient->setFileUpload ( $_uploadFilePath, $_key );
                    }
                }
                if ($rawdata) {
                    $httpClient->setRawBody($rawdata);
                }
                // 传递请求方法
                $this->httpResponse = $httpClient->setMethod( $method )->send();
                // 获取响应
                if (200 <= $this->httpResponse->getStatusCode () && $this->httpResponse->getStatusCode ()<300) { // 正常响应
                    $result = $this->httpResponse->getBody ();
                    $resultInfo = @json_decode ( $result, true );
                } else { // 失败返回错误
                    $result = new \ErrorCoder ( \ErrorCoder::ERR_HTTP_RESPONSE_CODE_ERROR );
                }

                break;// 成功执行请求， 跳出循环
            }

        } catch (\Zend\Http\Client\Exception $e) { // 截获异常， 返回错误
            $result = new \ErrorCoder(\ErrorCoder::ERR_BAD_REQUEST);
        }
        $endtime = date('Y-m-d H:i:s');

        return $result;
    }

    /**
     * 获取token
     * @return
     */
    public function getAccessToken ()
    {
        $accessToken = null;

        $url = $this->getRequestUrl('GetAccessToken');
        $response = $this->request($url);
        trace($response);
        $result = ConvertFormat::json_decode($response,true);
        if(isset($result['errmsg'])) {
            $this->errorDesc = "Error: code(".$result['errcode'].") message(".$result['errmsg'].")";
        } else {
            $accessToken = $result['access_token'];
        }

        return $accessToken;
    }

    public function setAccessToken ($accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    public function setMenu ($menuList)
    {
        $url = $this->getRequestUrl('SetMenu');
        $response = $this->request($url, ConvertFormat::json_encode($menuList), 'POST');

        $response = ConvertFormat::json_decode($response,true);
        trace($response);

        return $response;
    }

    public function checkShakeArroundStatus ()
    {
        $result = false;
        $url = $this->getRequestUrl('ShakeArroundStatus');
        $response = $this->request($url, null, 'GET');
        trace($response);

        //$response = ConvertFormat::json_decode($response,true);

        return $response;
    }

    /**
     * 申请开通微信摇一摇功能
     *
     * @param array $info
     *
     * http://3gimg.qq.com/shake_nearby/Qualificationdocuments.html
     * @return string
     */
    public function applyShakeArround ($info)
    {
        $url = $this->getRequestUrl('ApplyShakeArround');
        $response = $this->request($url, ConvertFormat::json_encode($info), 'POST');
        trace($response);

        return $response;
    }

    /**
     * 添加摇一摇素材
     * @param string $filepath 图片路径
     * @param string $type 素材类型： icon 或者  license
     * @return \ErrorCoder
     */
    public function addShakeArroundMaterial ($filepath, $type='icon')
    {
        $url = $this->getRequestUrl('AddShakeArroundMaterial', array('type'=>$type));
        $this->setUploadFile($filepath, 'media');
        $response = $this->request($url, null, 'POST');
        trace($response);

        return $response;
    }

    /**
     * 申请摇一摇设备id
     * @param int $quantity 申请的数量
     * @param string $apply_reason 申请理由描述
     * @param string $comment 备注信息
     * @param int $poi_id 摇一摇门店id
     * @return \ErrorCoder
     */
    public function applyShakeArroundDeviceId ($quantity, $apply_reason, $comment=null, $poi_id=null)
    {
        $params = array(
                'quantity'     => $quantity,
                'apply_reason' => $apply_reason,
                'comment'      => $comment,
                'poi_id'       => $poi_id
        );
        $url = $this->getRequestUrl('ApplyShakeArroundDeviceId');
        $response = $this->request($url, ConvertFormat::json_encode($params), 'POST');
        trace($response);

        return $response;
    }

    /**
     * 查询摇一摇设备id申请状态
     * @param int $applyId 申请的批次ID
     * @return \ErrorCoder
     */
    public function checkShakeArroundDeviceApplyStatus ($applyId)
    {
        $params = array(
                'apply_id'     => $applyId,
        );
        $url = $this->getRequestUrl('CheckShakeArroundDeviceApplyStatus');
        $response = $this->request($url, ConvertFormat::json_encode($params), 'POST');
        trace($response);

        return $response;
    }

    /**
     * 获取摇一摇设备列表
     * @param number $lastSeen 前一次查询列表末尾的设备ID ， 第一次查询last_seen 为0
     * @param number $count 待查询的设备数量，不能超过50个
     * @param string $applyId 批次ID，申请设备ID时所返回的批次ID；当type为3时，此项为必填
     */
    public function getShakeArroundDeviceList ($lastSeen = 0, $count = 50, $applyId=null)
    {
        $params = array(
                'last_seen'    => $lastSeen,
                'count'        => $count,
                'apply_id'     => $applyId,
        );
        $params['type'] = $applyId ? 3 : 2;
        $url = $this->getRequestUrl('GetShakeArroundDeviceList');
        $response = $this->request($url, ConvertFormat::json_encode($params), 'POST');
        trace($response);

        return $response;
    }

    /**
     * 获取素材总数
     * 返回说明
        {
          "voice_count":COUNT,
          "video_count":COUNT,
          "image_count":COUNT,
          "news_count":COUNT
        }
     * 返回参数说明
     * 参数 	描述
        voice_count 	语音总数量
        video_count 	视频总数量
        image_count 	图片总数量
        news_count 	图文总数量
     * @return json
     */
    public function getMaterialCount ()
    {
        $url = $this->getRequestUrl('GetMaterialCount');
        $response = $this->request($url, null, 'GET');
        trace($response);

        return $response;
    }

    public  function getMaterialListByType ($type='image', $offset=0, $count=20)
    {
        $params = array('type'=>$type, 'offset'=>$offset, 'count'=>$count);
        $url = $this->getRequestUrl('GetMaterialListByType');
        $response = $this->request($url, ConvertFormat::json_encode($params), 'POST');
        trace($response);

        return $response;
    }

    public function getMenu ($menuList)
    {
        $result = false;
        $url = $this->getRequestUrl('GetMenu');
        echo $url;
        $response = $this->getHttpClient()
                         ->setUri($url)
                         ->request('GET');
        if(200!=$response->getStatus()) {
            return $result;
        }
        $response = ConvertFormat::json_decode($response->getBody(),true);
        echo print_r($response,true);
        if(isset($response['errcode'])) {
            if(0==$response['errcode']) {
                $result = $response;
            } else {
                $this->errorDesc = "Error: code(".$response['errcode'].") message(".$response['errmsg'].")";
            }
        }

        return $result;
    }

    public function getSubscriber ($openId)
    {
        $url = sprintf($this->getRequestUrl('GetSubscriber'), $this->accessToken, $openId);
        $response = $this->getHttpClient($url)
                         ->request();
        if(200==$response->getStatus()) {
            $response = $response->getBody();
            $subscriberInfo = ConvertFormat::json_decode($response, true);
        } else {
            $subscriberInfo = null;
        }

        return $subscriberInfo;
    }

    public function getSubscriberList ($nextOpenId=null)
    {
        $url = sprintf($this->getRequestUrl('GetSubscriberList'), $this->accessToken, $nextOpenId);
        $response = $this->getHttpClient($url)
                         ->request();
        if(200==$response->getStatus()) {
            $response = $response->getBody();
            $subscriberList = ConvertFormat::json_decode($response, true);
        } else {
            $subscriberList = null;
        }

        return $subscriberList;
    }


}