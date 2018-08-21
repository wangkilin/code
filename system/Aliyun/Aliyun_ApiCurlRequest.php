<?php
require_once(dirname(__FILE__) . '/../ApiCurlRequest.php');

class Aliyun_ApiCurlRequest extends ApiCurlRequest
{
    /**
     * API 请求URL公共前缀
     */
    public $baseUrl = '';


    protected $apiUriList = array(
        // 获取access_token
        'GetAccessToken' => 'cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s',
        /******* 自定义菜单 *******/
        // 设置菜单
        'SetMenu' => 'cgi-bin/menu/create?access_token=%s',
    );

    /**
     * 构造函数
     * @param array $options
     */
    public function __construct ($appId, $appSecret, $token, $options=array() )
    {
        $this->appId     = $appId;
        $this->appSecret = $appSecret;
        $this->token     = $token;

        // set options
        $this->setOptions($options);
    }


}
