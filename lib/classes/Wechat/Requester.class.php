<?php
require_once(dirname(__FILE__) . '/WechatAbstract.class.php');
require_once(dirname(__FILE__) . '/ConvertFormat.class.php');

class WechatRequester extends WechatAbstract
{
    const API_URL_PREFIX = 'https://api.weixin.qq.com/';

    protected $appId = null;
    protected $appSecret = null;
    protected $token = null;// this token is used to generated signature. it's defined by User

    protected $apiUriList = array(
        // 获取access_token
        'GetAccessToken' => 'cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s',
        /******* 自定义菜单 *******/
        // 设置菜单
        'SetMenu' => 'cgi-bin/menu/create?access_token=%s',
        // 获取菜单
        'GetMenu' => 'cgi-bin/menu/get?access_token=%s',


        'GetSubscriber' => 'cgi-bin/user/info?access_token=%s&openid=%s&lang=zh_CN',
        'GetSubscriberList'=> 'cgi-bin/user/get?access_token=%s&next_openid=%s',
        'ShakeArroundStatus'=> 'shakearound/account/auditstatus?access_token=%s',
        'GetMaterialCount'=> 'cgi-bin/material/get_materialcount?access_token=%s',
        'GetMaterialListByType'=> 'cgi-bin/material/batchget_material?access_token=%s',
        'ApplyShakeArround'=> 'shakearound/account/register?access_token=%s',
        'AddShakeArroundMaterial'=> 'shakearound/material/add?access_token=%s&type=%s',
        'ApplyShakeArroundDeviceId'=> 'shakearound/device/applyid?access_token=%s',
        'CheckShakeArroundDeviceApplyStatus' => 'shakearound/device/applystatus?access_token=%s',
        'GetShakeArroundDeviceList' => 'shakearound/device/search?access_token=%s',
    );

    /**
     * 构造函数
     * @param unknown $appId
     * @param unknown $appSecret
     * @param unknown $token
     * @param unknown $options
     */
    public function __construct ($appId, $appSecret, $token, $options=array() )
    {
        $this->appId     = $appId;
        $this->appSecret = $appSecret;
        $this->token     = $token;
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
		$curlInfo = curl_getinfo($curlHandler);

		curl_close($curlHandler);

		if(intval($curlInfo["http_code"])==200){
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

        if (($accessTokenInfo['expires_in'] - time()) >60 ) {
            return $accessTokenInfo['access_token'];
        }

        $url = sprintf(self::API_URL_PREFIX . $this->apiUriList['GetAccessToken'],
                      $this->appId, $this->appSecret
               );
        $response = $this->get($url);
        $result = ConvertFormat::json_decode($response,true);
        if(isset($result['errmsg'])) {
            $this->errorDesc = "Error: code(".$result['errcode'].") message(".$result['errmsg'].")";
        } else {
            $result['expires_in'] = time() + $result['expires_in'];
            $accessTokenInfo = $result;
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
        $response = json_decode($response,true);

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
        $response = $this->request($url, null, 'GET');

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

/* EOF */

$config =  array(
        // 配置文件
        'REPORT_CLICK_URL' => 'http://dig-visitor.oradt.com/fvisitor',
        'Neo4j'            => array (
                'url'      => 'http://118.186.66.169:80/db/data/transaction',
                'user'     => 'neo4j',
                'pass'     => 'neo654321',
        ),
        // 微信配置
        'Wechat'           => array (
                'AppID'             => 'wxd74253d6ee55fe12',
                'AppSecret'         => 'ecd90fa62cd26bb716ebad464fca2fda',
                'Token'             => 'weixin',
                'EncodingAESKey'    => 'S0vTZ2ZhED24Fo5kt4niVIaHWjdKOHumV4GBoN1sGH0',
                'menu'              => array (
                        'button' => array (
                                array('name'=>'点击交互',
                                        'sub_button'=> array(
                                                array(  'type'=>'click',
                                                        'key'=>'click1',
                                                        'name'=>'Click1'),
                                                array(  'type'=>'click',
                                                        'key'=>'click2',
                                                        'name'=>'Click2'),
                                                array(
                                                        'type'=>'view',
                                                        'key'=>'view',
                                                        'url'=>'http://dev.orayun.com/demo/wechat/view',
                                                        'name'=>'View'),
                                        )
                                ),
                                array('name'=>'拍照扫码',
                                        'sub_button'=> array(
                                                array(
                                                        'type'=>'scancode_push',
                                                        'key'=>'scancode_push',
                                                        'url'=>'http://dev.orayun.com/demo/wechat/scanCodePush',
                                                        'name'=>'scancode_push'),
                                                array(
                                                        'type'=>'scancode_waitmsg',
                                                        'key'=>'scancode_waitmsg',
                                                        'url'=>'http://dev.orayun.com/demo/wechat/scanCodeWaitMsg',
                                                        'name'=>'scancode_waitmsg'),
                                                array(
                                                        'type'=>'pic_sysphoto',
                                                        'key'=>'pic_sysphoto',
                                                        'url'=>'http://dev.orayun.com/demo/wechat/picSysPhoto',
                                                        'name'=>'pic_sysphoto'),
                                                array(
                                                        'type'=>'pic_photo_or_album',
                                                        'key'=>'pic_photo_or_album',
                                                        'url'=>'http://dev.orayun.com/demo/wechat/picPhotoOrAlbum',
                                                        'name'=>'pic_photo_or_album'),
                                                array(
                                                        'type'=>'pic_weixin',
                                                        'key'=>'pic_weixin',
                                                        'url'=>'http://dev.orayun.com/demo/wechat/picWeixin',
                                                        'name'=>'pic_weixin'),
                                        )
                                ),
                                array('name'=>'媒体查询',
                                        'sub_button'=> array(
                                                array(
                                                        'type'=>'location_select',
                                                        'key'=>'location_select',
                                                        'url'=>'http://dev.orayun.com/demo/wechat/locationSelect',
                                                        'name'=>'location_select'),
                                                        //                                                 array(
                                                                //                                                         'type'=>'media_id',
                                                                //                                                         'key'=>'media_id',
                                                                //                                                         'url'=>'http://dev.orayun.com/demo/wechat/mediaId',
                                                                //                                                         'name'=>'media_id'),
                                        //                                                 array(
                                                //                                                         'type'=>'view_limited',
                                        //                                                         'key'=>'view_limited',
                                        //                                                         'url'=>'http://dev.orayun.com/demo/wechat/viewLimited',
                                        //                                                         'name'=>'view_limited'),
                                        )
                                        ),

                                )
                        )
                ),
        );

$request = new WechatRequester($config['Wechat']['AppID'],
                               $config['Wechat']['AppSecret'],
                               $config['Wechat']['AppSecret']
            );

//echo $request->getAccessToken(), "\r\n";

$request->setMenu($config['Wechat']['menu']);

//var_dump($request->checkShakeArroundStatus());
//var_dump($request->addShakeArroundMaterial('C:\\Users\\wangzx\\Pictures\\scannerInfo.png'));
echo $request->getMenu();
