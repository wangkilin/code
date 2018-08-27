<?php
require_once(dirname(__FILE__) . '/../ApiCurlRequest.php');

class Aliyun_ApiCurlRequest extends ApiCurlRequest
{
    /**
     * API 请求URL公共前缀
     */
    public $baseUrl = 'https://ocrapi-advanced.taobao.com/';
    

    protected $apiUriList = array(
        // 通用文字识别－高精版
        'OcrAdvanced' => 'ocrservice/advanced',
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

    public function ocrAdcanced ($filepath, $prob=false, $charInfo=false, $rotate=false, $table=false)
    {

    }


}
