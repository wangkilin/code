<?php
require_once(dirname(__FILE__) . '/WechatAbstract.class.php');
require_once(dirname(__FILE__) . '/ConvertFormat.class.php');

class Request extends WechatAbstract
{
    /**
     * API 请求URL公共前缀
     */
    CONST API_URL_PREFIX = 'https://api.weixin.qq.com/';

    /**
     * 保持response的text格式
     */
    const DECODE_MODE_TEXT = 1;

    /**
     * 将response解码数组格式
     */
    const DECODE_MODE_ARRAY = 2;

    /**
     * 将response解码对象格式
     */
    const DECODE_MODE_OBJECT = 3;

    /**
     * 解码response的方式
     * @var int
     */
    public $decodeResponseMode = 1;

    /**
     * HTTP 要上传的文件
     * @var array
     */
    protected $httpUploadFiles = array();

    /**
     * 微信公众号配置
     */
    protected $appId = null;
    protected $appSecret = null;
    protected $token = null;// this token is used to generated signature. it's defined by User

    /**
     * 微信API请求的access token
     * @var string
     */
    protected $accessToken = null; // this token is got from remote server.
    protected $cacheAccessTokenCall = null; // 存放和获取token的方法


    protected $apiUriList = array(
        // 获取access_token
        'GetAccessToken' => 'cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s',
        /******* 自定义菜单 *******/
        // 设置菜单
        'SetMenu' => 'cgi-bin/menu/create?access_token=%s',
        // 获取菜单
        'GetMenu' => 'cgi-bin/menu/get?access_token=%s',


        'GetSubscriber'              => 'cgi-bin/user/info?access_token=%s&openid=%s&lang=zh_CN',
        'GetSubscriberList'          => 'cgi-bin/user/get?access_token=%s&next_openid=%s',
        // 获取素材总数
        'GetMaterialCount'           => 'cgi-bin/material/get_materialcount?access_token=%s',
        // 根据类型获取素材列表
        'GetMaterialListByType'      => 'cgi-bin/material/batchget_material?access_token=%s',

        // 添加摇一摇页面
        'ShakeArroundPageAdd'        => 'shakearound/page/add?access_token=%s',
        // 摇一摇状态
        'ShakeArroundStatus'         => 'shakearound/account/auditstatus?access_token=%s',
        // 申请摇一摇服务
        'ApplyShakeArround'          => 'shakearound/account/register?access_token=%s',
        // 添加摇一摇素材
        'AddShakeArroundMaterial'    => 'shakearound/material/add?access_token=%s&type=%s',
        // 申请摇一摇设备id
        'ApplyShakeArroundDeviceId'  => 'shakearound/device/applyid?access_token=%s',
        // 查看摇一摇设备申请状态
        'CheckShakeArroundDeviceApplyStatus' => 'shakearound/device/applystatus?access_token=%s',
        // 获取摇一摇设备列表
        'GetShakeArroundDeviceList'  => 'shakearound/device/search?access_token=%s',
    );

    /**
     * 构造函数
     * @param string $appId
     * @param string $appSecret
     * @param array $options
     */
    public function __construct ($appId, $appSecret, $options=array() )
    {
        $this->appId     = $appId;
        $this->appSecret = $appSecret;

        // set options
        $this->setOptions($options);
    }

    /**
     * 使用get方式发送数据
     * @param unknown $url
     * @return Ambigous <boolean, mixed>
     */
    public function get ($url)
    {
        return $this->request($url, 'GET');
    }

    /**
     * 使用post方式发送数据
     * @param unknown $url
     * @param unknown $params
     * @param unknown $files
     * @return Ambigous <boolean, mixed>
     */
    public function post ($url, $params=array(), $files=array())
    {
        return $this->request($url, 'POST', $params, $files);
    }


    /**
     * Attempt to detect the MIME type of a file using available extensions
     *
     * @param string $file File path
     * @return string MIME type
     */
    protected function detectFileMimeType($file)
    {
        // default application/octet-stream
        $mime = 'application/octet-stream';

        // First try with fileinfo functions
        if (function_exists('finfo_open')) {
            $mimeDetector = finfo_open(FILEINFO_MIME);
            $mime = finfo_file($mimeDetector, $file);
        } else if (function_exists('mime_content_type')) {
            $mime = mime_content_type($file);
        }

        return $mime;
    }

    /**
     * Encode data to a multipart/form-data part suitable for a POST request.
     *
     * @param string $boundary
     * @param string $name
     * @param mixed $value
     * @param string $filename
     * @param array $headers Associative array of optional headers @example ("Content-Transfer-Encoding" => "binary")
     * @return string
     */
    public function encodeFormData($boundary, $name, $value, $filename = null, $headers = array())
    {
        $ret = "--{$boundary}\r\n" .
            'Content-Disposition: form-data; name="' . $name . '"';

        if ($filename) {
            $ret .= '; filename="' . $filename . '"';
        }
        $ret .= "\r\n";

        foreach ($headers as $hname => $hvalue) {
            $ret .= "{$hname}: {$hvalue}\r\n";
        }
        $ret .= "\r\n";
        $ret .= "{$value}\r\n";

        return $ret;
    }


    /**
     * 载入待上传的文件信息
     *
     * @param  string $filepath file to upload
     * @param  string $formname Name of form element to send as
     * @param  string $data Data to send (if null, $filepath is read and sent)
     * @param  string $mime Content type to use (if $data is set and $mime is
     *                null, will be application/octet-stream)
     */
    protected function loadFileInfo($filepath, $nameInForm, $data = null, $mime = null)
    {
        if ($data === null) {
            $data  = file_get_contents($filepath);
            if ($data === false) {
                throw new Exception("Unable to read file '{$filename}'");
            }
            if (! $mime) {
                $mime = $this->detectFileMimeType($filepath);
            }
        }

        $fileInfo = array(
                'formname' => $nameInForm,
                'filename' => basename($filepath),
                'mime' => $mime,
                'data' => $data
        );

        return $fileInfo;
    }

    /**
     * 发送请求内容到微信服务器
     * @param unknown $url
     * @param string $method
     * @param unknown $params
     * @param unknown $files
     * @return mixed|boolean
     */
    protected function request ($url, $method='GET', $params=array(), $files=array())
    {
        $this->log($method .'::'. $url);
        if ($params) {
            $this->log($params);
        }
        if ($files) {
            $this->log($files);
        }
        $curlHandler = curl_init();
        if(stripos($url,"https://")!==false){
            curl_setopt($curlHandler, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($curlHandler, CURLOPT_SSL_VERIFYHOST, false);
            //curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        curl_setopt($curlHandler, CURLOPT_URL, $url);
        curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, 1 );
        $curlMethod = $method == 'GET' ? CURLOPT_HTTPGET : CURLOPT_POST;
        curl_setopt($curlHandler, $curlMethod, true);

        if ('POST'==$method) {
    		if (is_string($params)) {
    	        $rawData = & $params;
    	    } else if ($files) {
    	        curl_setopt($curlHandler, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
    	        $rawData = & $this->buildRawData ($params, $files);
    		} else {
    			$rawData = http_build_query($params);
    		}
    		curl_setopt($curlHandler, CURLOPT_POSTFIELDS, $rawData);
        }

		$response = curl_exec($curlHandler);
		$this->log($response);
		$curlInfo = curl_getinfo($curlHandler);
		if (intval($curlInfo["http_code"])!=200) {
		    $this->log($curlInfo);
		}

		curl_close($curlHandler);

		if(intval($curlInfo["http_code"])==200){
		    switch ($this->decodeResponseMode) {
		        case self::DECODE_MODE_ARRAY:
		            $response = ConvertFormat::json_decode($response, true);
		            break;
		        case self::DECODE_MODE_OBJECT:
		            $response = ConvertFormat::json_decode($response);
		            break;
		        case self::DECODE_MODE_TEXT:
		        default:
		            break;
		    }
			return $response;
		}else{
			return false;
		}
    }

    /**
     * 组装post主体内容
     * @param unknown $post
     * @param unknown $files
     * @return string
     */
    protected function & buildRawData ($params, $files)
    {
    	$rawData = '';
    	$boundary = '----iCodeBang.com---' . md5(microtime());
    	foreach ($params as $key=>$value) {
    		$rawData .= $this->encodeFormData($boundary, $key, $value);
    	}
    	// Encode files
    	foreach ($files as $nameInForm=>$file) {
    	    if (is_string($file)) {
    	        try {
    	            $file = $this->loadFileInfo($file, $nameInForm);
    	        } catch (Exception $e) {}
    	    }

    	    if (! is_array($file) || !isset($file['mime'])) {
    	        continue;
    	    }

    		$fhead = array('Content-Type' => $file['mime']);
    		$rawData .= $this->encodeFormData($boundary, $file['formname'], $file['data'], $file['filename'], $fhead);
    	}
    	$rawData .= "--{$boundary}--\r\n";

    	return $rawData;
    }

    /**
     * 获取token
     * @return
     */
    public function getAccessToken ()
    {
        static $accessTokenInfo = array('access_token'=>'', 'expires_in'=>0);
        if ($this->cacheAccessTokenCall && is_callable($this->cacheAccessTokenCall)) {
        	$accessTokenInfo = call_user_func($this->cacheAccessTokenCall);
        }
        if (($accessTokenInfo['expires_in'] - time()) >60 ) {
            return $accessTokenInfo['access_token'];
        }

        $url = sprintf(self::API_URL_PREFIX . $this->apiUriList['GetAccessToken'],
                      $this->appId, $this->appSecret
               );
        $response = $this->get($url);
        // 对响应数据做转换处理
        switch ($this->decodeResponseMode) {
            case self::DECODE_MODE_OBJECT:
                $result = (array) $response;
                break;
            case self::DECODE_MODE_ARRAY:
                $result = $response;
                break;
            case self::DECODE_MODE_TEXT:
            default:
                $result = ConvertFormat::json_decode($response,true);
                break;
        }
        if(isset($result['errmsg'])) {
            $this->errorDesc = "Error: code(".$result['errcode'].") message(".$result['errmsg'].")";
        } else {
            $result['expires_in'] = time() + $result['expires_in'];
            $accessTokenInfo = $result;
            // 调用缓存accesstoken函数以防止多次调用获取accesstotken
            if ($this->cacheAccessTokenCall && is_callable($this->cacheAccessTokenCall)) {
            	call_user_func($this->cacheAccessTokenCall, $accessTokenInfo);
            }
        }

        return $accessTokenInfo['access_token'];
    }

    /**
     * 设置公众号菜单
     * @param unknown $menuList
     * @return mixed
     */
    public function setMenu ($menuList)
    {
        $accessToken = $this->getAccessToken();
        $url = sprintf(self::API_URL_PREFIX.$this->apiUriList['SetMenu'], $accessToken);
        $response = $this->post($url, ConvertFormat::json_encode($menuList));
        $response = ConvertFormat::json_decode($response,true);

        return $response;
    }

    /**
     * 获取菜单
     * @return array
     */
    public function getMenu ()
    {
        $url = sprintf(self::API_URL_PREFIX.$this->apiUriList['GetMenu'],
                       $this->getAccessToken()
               );
        $response = $this->get($url);

        return $response;
    }

    /**
     * 检查摇一摇申请状态
     * @return Ambigous <mixed, boolean>
     */
    public function checkShakeArroundStatus ()
    {
        $result = false;
        $accessToken = $this->getAccessToken();
        $url = sprintf(self::API_URL_PREFIX.$this->apiUriList['ShakeArroundStatus'], $accessToken);
        $response = $this->get($url);

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
        $url = sprintf(self::API_URL_PREFIX.$this->apiUriList['AddShakeArroundMaterial'],
                       $this->getAccessToken(),
                       $type
               );
        $response = $this->post($url, array(), array('media'=>$filepath));

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
        $url = sprintf(self::API_URL_PREFIX.$this->apiUriList['ApplyShakeArround'],
                       $this->getAccessToken()
               );
        $response = $this->post($url, ConvertFormat::json_encode($info));

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
        $url = sprintf(self::API_URL_PREFIX.$this->apiUriList['GetMaterialCount'],
                $this->getAccessToken()
        );
        $response = $this->get($url);

        return $response;
    }

    public  function getMaterialListByType ($type='image', $offset=0, $count=20)
    {
        $params = array('type'=>$type, 'offset'=>$offset, 'count'=>$count);
        $url = sprintf(self::API_URL_PREFIX.$this->apiUriList['GetMaterialListByType'],
                $this->getAccessToken()
        );
        $response = $this->post($url, ConvertFormat::json_encode($params));

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
        $url = sprintf(self::API_URL_PREFIX.$this->apiUriList['CheckShakeArroundDeviceApplyStatus'],
                       $this->getAccessToken()
               );
        $response = $this->post($url, ConvertFormat::json_encode($params));

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
        $url = sprintf(self::API_URL_PREFIX.$this->apiUriList['ApplyShakeArroundDeviceId'],
                       $this->getAccessToken()
               );
        $response = $this->post($url, ConvertFormat::json_encode($params));

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
        $url = sprintf(self::API_URL_PREFIX.$this->apiUriList['GetShakeArroundDeviceList'],
                       $this->getAccessToken()
               );
        $response = $this->post($url, ConvertFormat::json_encode($params));

        return $response;
    }

    /**
     * 新增摇一摇出来的页面信息，包括在摇一摇页面出现的主标题、副标题、图片和点击进去的超链接。其中，图片必须为用素材管理接口上传至微信侧服务器后返回的链接。
     * @param string $title 在摇一摇页面展示的主标题，不超过6个汉字或12个英文字母
     * @param string $description 在摇一摇页面展示的副标题，不超过7个汉字或14个英文字母
     * @param string $iconUrl 在摇一摇页面展示的图片。图片需先上传至微信侧服务器，用“素材管理-上传图片素材”接口上传图片，返回的图片URL再配置在此处
     * @param string $pageUrl 点击进去的超链接
     * @param string $comment 页面的备注信息，不超过15个汉字或30个英文字母
     * @return unknown
     */
    public function addShakeArroundPage($title, $description, $iconUrl, $pageUrl, $comment='')
    {
        $params = array(
                'title'        => $title,
                'description'  => $description,
                'icon_url'      => $iconUrl,
                'page_url'      => $pageUrl,
                'comment'      => $comment,
        );
        $url = sprintf(self::API_URL_PREFIX.$this->apiUriList['ShakeArroundPageAdd'],
                       $this->getAccessToken()
               );
        $response = $this->post($url, ConvertFormat::json_encode($params));

        return $response;
    }


    // 上面方法已经验证通过

    // 后续方法尚未验证

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