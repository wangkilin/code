<?php
require_once( INC_PATH . 'verdor/AliyunApi/Util/Autoloader.php');

class Aliyun_ApiCurlRequest
// extends ApiCurlRequest
{
    /**
     * API 请求URL公共前缀
     */
    public $baseUrl = 'https://ocrapi-advanced.taobao.com/';
    

    protected $apiUriList = array(
        // 通用文字识别－高精版
        'OcrAdvanced' => 'ocrservice/advanced',
    );

    protected $appKey = null;
    protected $appSecret = null;

    /**
     * 构造函数
     * @param array $options
     */
    public function __construct ($authInfos, $options=array() )
    {
        $this->appId     = $appId;
        $this->appSecret = $appSecret;
        $this->token     = $token;
        if (isset($authInfos['appKey'], $authInfos['appSecret'])) {
            $this->appKey = $authInfos['appKey'];
            $this->appSecret = $authInfos['appSecret'];
        }

        // set options
        $this->setOptions($options);
    }

    public function ocrAdcanced ($filepath, $prob=false, $charInfo=false, $rotate=false, $table=false)
    {
        $this->request($host, $uri, $method, $appKey, $appSecret);

        return $this->post($host, $uri, $headers, $params);
    }

    public function request ($host, $uri, $method, $headers=array(), $signHeaders=array(), $params=array())
    {
        if (! $this->appKey || ! $this->appSecret) {
            return null;
        }
        $request = new HttpRequest($host, $uri, $method, $this->appKey, $this->appSecret);
        
        foreach ($headers as $_key=>$_value) {
            //注意：业务header部分，如果没有则无此行(如果有中文，请做Utf8ToIso88591处理)
            //mb_convert_encoding("headervalue2中文", "ISO-8859-1", "UTF-8");
            $request->setHeader($_key, $_value);
        }

        foreach ($params as $_key => $_value) {
            //注意：业务query部分，如果没有则无此行；请不要、不要、不要做UrlEncode处理
            $request->setQuery($_key, $_value);
        }

        in_array(SystemHeader::X_CA_TIMESTAMP, $signHeaders) OR array_unshift($signHeaders, SystemHeader::X_CA_TIMESTAMP);
        foreach ($signHeaders as $_value) {
            //指定参与签名的header
            $request->setSignHeader($_value);
        }

        $response = HttpClient::execute($request);
        
        return $response;
    }

    public function get ($host, $uri, $headers, $params)
    {

    }

    public function post ($host, $uri, $headers, $params)
    {

    }

    public function delete ($host, $uri, $headers, $params)
    {

    }

    public function put ($host, $uri, $headers, $params)
    {

    }


	/**
	*method=GET请求示例
	*/
    public function doGet() {
		//域名后、query前的部分
		$path = "/get";
		$request = new HttpRequest($this::$host, $path, HttpMethod::GET, $this::$appKey, $this::$appSecret);

        //设定Content-Type，根据服务器端接受的值来设置
		$request->setHeader(HttpHeader::HTTP_HEADER_CONTENT_TYPE, ContentType::CONTENT_TYPE_TEXT);
		
        //设定Accept，根据服务器端接受的值来设置
		$request->setHeader(HttpHeader::HTTP_HEADER_ACCEPT, ContentType::CONTENT_TYPE_TEXT);
        //如果是调用测试环境请设置
		//$request->setHeader(SystemHeader::X_CA_STAG, "TEST");


        //注意：业务header部分，如果没有则无此行(如果有中文，请做Utf8ToIso88591处理)
		//mb_convert_encoding("headervalue2中文", "ISO-8859-1", "UTF-8");
		$request->setHeader("b-header2", "headervalue2");
		$request->setHeader("a-header1", "headervalue1");

        //注意：业务query部分，如果没有则无此行；请不要、不要、不要做UrlEncode处理
		$request->setQuery("b-query2", "queryvalue2");
		$request->setQuery("a-query1", "queryvalue1");

        //指定参与签名的header
		$request->setSignHeader(SystemHeader::X_CA_TIMESTAMP);
		$request->setSignHeader("a-header1");
		$request->setSignHeader("b-header2");

		$response = HttpClient::execute($request);
		print_r($response);
	}

	/**
	*method=POST且是表单提交，请求示例
	*/
	public function doPostForm() {
		//域名后、query前的部分
		$path = "/postform";
		$request = new HttpRequest($this::$host, $path, HttpMethod::POST, $this::$appKey, $this::$appSecret);

        //设定Content-Type，根据服务器端接受的值来设置
		$request->setHeader(HttpHeader::HTTP_HEADER_CONTENT_TYPE, ContentType::CONTENT_TYPE_FORM);
		
        //设定Accept，根据服务器端接受的值来设置
		$request->setHeader(HttpHeader::HTTP_HEADER_ACCEPT, ContentType::CONTENT_TYPE_JSON);
        //如果是调用测试环境请设置
		//$request->setHeader(SystemHeader::X_CA_STAG, "TEST");


        //注意：业务header部分，如果没有则无此行(如果有中文，请做Utf8ToIso88591处理)
		//mb_convert_encoding("headervalue2中文", "ISO-8859-1", "UTF-8");
		$request->setHeader("b-header2", "headervalue2");
		$request->setHeader("a-header1", "headervalue1");

        //注意：业务query部分，如果没有则无此行；请不要、不要、不要做UrlEncode处理
		$request->setQuery("b-query2", "queryvalue2");
		$request->setQuery("a-query1", "queryvalue1");

		//注意：业务body部分，如果没有则无此行；请不要、不要、不要做UrlEncode处理
		$request->setBody("b-body2", "bodyvalue2");
		$request->setBody("a-body1", "bodyvalue1");

        //指定参与签名的header
		$request->setSignHeader(SystemHeader::X_CA_TIMESTAMP);
		$request->setSignHeader("a-header1");
		$request->setSignHeader("b-header2");

		$response = HttpClient::execute($request);
		print_r($response);
	}

	/**
	*method=POST且是非表单提交，请求示例
	*/
	public function doPostString() {
		//域名后、query前的部分
		$path = "/poststring";
		$request = new HttpRequest($this::$host, $path, HttpMethod::POST, $this::$appKey, $this::$appSecret);
		//传入内容是json格式的字符串
		$bodyContent = "{\"inputs\": [{\"image\": {\"dataType\": 50,\"dataValue\": \"base64_image_string(此行)\"},\"configure\": {\"dataType\": 50,\"dataValue\": \"{\"side\":\"face(#此行此行)\"}\"}}]}";

        //设定Content-Type，根据服务器端接受的值来设置
		$request->setHeader(HttpHeader::HTTP_HEADER_CONTENT_TYPE, ContentType::CONTENT_TYPE_JSON);
		
        //设定Accept，根据服务器端接受的值来设置
		$request->setHeader(HttpHeader::HTTP_HEADER_ACCEPT, ContentType::CONTENT_TYPE_JSON);
        //如果是调用测试环境请设置
		//$request->setHeader(SystemHeader::X_CA_STAG, "TEST");


        //注意：业务header部分，如果没有则无此行(如果有中文，请做Utf8ToIso88591处理)
		//mb_convert_encoding("headervalue2中文", "ISO-8859-1", "UTF-8");
		$request->setHeader("b-header2", "headervalue2");
		$request->setHeader("a-header1", "headervalue1");

        //注意：业务query部分，如果没有则无此行；请不要、不要、不要做UrlEncode处理
		$request->setQuery("b-query2", "queryvalue2");
		$request->setQuery("a-query1", "queryvalue1");

		//注意：业务body部分，不能设置key值，只能有value
		if (0 < strlen($bodyContent)) {
			$request->setHeader(HttpHeader::HTTP_HEADER_CONTENT_MD5, base64_encode(md5($bodyContent, true)));
			$request->setBodyString($bodyContent);
		}

		//指定参与签名的header
		$request->setSignHeader(SystemHeader::X_CA_TIMESTAMP);
		$request->setSignHeader("a-header1");
		$request->setSignHeader("b-header2");

		$response = HttpClient::execute($request);
		print_r($response);
	}


	/**
	*method=POST且是非表单提交，请求示例
	*/
	public function doPostStream() {
		//域名后、query前的部分
		$path = "/poststream";
		$request = new HttpRequest($this::$host, $path, HttpMethod::POST, $this::$appKey, $this::$appSecret);
		//Stream的内容
		$bytes = array();
		//传入内容是json格式的字符串
		$bodyContent = "{\"inputs\": [{\"image\": {\"dataType\": 50,\"dataValue\": \"base64_image_string(此行)\"},\"configure\": {\"dataType\": 50,\"dataValue\": \"{\"side\":\"face(#此行此行)\"}\"}}]}";

        //设定Content-Type，根据服务器端接受的值来设置
		$request->setHeader(HttpHeader::HTTP_HEADER_CONTENT_TYPE, ContentType::CONTENT_TYPE_STREAM);
		
        //设定Accept，根据服务器端接受的值来设置
		$request->setHeader(HttpHeader::HTTP_HEADER_ACCEPT, ContentType::CONTENT_TYPE_JSON);
        //如果是调用测试环境请设置
		//$request->setHeader(SystemHeader::X_CA_STAG, "TEST");


        //注意：业务header部分，如果没有则无此行(如果有中文，请做Utf8ToIso88591处理)
		//mb_convert_encoding("headervalue2中文", "ISO-8859-1", "UTF-8");
		$request->setHeader("b-header2", "headervalue2");
		$request->setHeader("a-header1", "headervalue1");

        //注意：业务query部分，如果没有则无此行；请不要、不要、不要做UrlEncode处理
		$request->setQuery("b-query2", "queryvalue2");
		$request->setQuery("a-query1", "queryvalue1");

		//注意：业务body部分，不能设置key值，只能有value
		foreach($bytes as $byte) { 
            $bodyContent .= chr($byte); 
        }
		if (0 < strlen($bodyContent)) {
			$request->setHeader(HttpHeader::HTTP_HEADER_CONTENT_MD5, base64_encode(md5($bodyContent, true)));
			$request->setBodyStream($bodyContent);
		}

		//指定参与签名的header
		$request->setSignHeader(SystemHeader::X_CA_TIMESTAMP);
		$request->setSignHeader("a-header1");
		$request->setSignHeader("b-header2");

		$response = HttpClient::execute($request);
		print_r($response);
	}

	//method=PUT方式和method=POST基本类似，这里不再举例

	/**
	*method=DELETE请求示例
	*/
    public function doDelete() {
		//域名后、query前的部分
		$path = "/delete";
		$request = new HttpRequest($this::$host, $path, HttpMethod::DELETE, $this::$appKey, $this::$appSecret);

        //设定Content-Type，根据服务器端接受的值来设置
		$request->setHeader(HttpHeader::HTTP_HEADER_CONTENT_TYPE, ContentType::CONTENT_TYPE_TEXT);
		
        //设定Accept，根据服务器端接受的值来设置
		$request->setHeader(HttpHeader::HTTP_HEADER_ACCEPT, ContentType::CONTENT_TYPE_TEXT);
        //如果是调用测试环境请设置
		//$request->setHeader(SystemHeader::X_CA_STAG, "TEST");


        //注意：业务header部分，如果没有则无此行(如果有中文，请做Utf8ToIso88591处理)
		//mb_convert_encoding("headervalue2中文", "ISO-8859-1", "UTF-8");
		$request->setHeader("b-header2", "headervalue2");
		$request->setHeader("a-header1", "headervalue1");

        //注意：业务query部分，如果没有则无此行；请不要、不要、不要做UrlEncode处理
		$request->setQuery("b-query2", "queryvalue2");
		$request->setQuery("a-query1", "queryvalue1");

        //指定参与签名的header
		$request->setSignHeader(SystemHeader::X_CA_TIMESTAMP);
		$request->setSignHeader("a-header1");
		$request->setSignHeader("b-header2");

		$response = HttpClient::execute($request);
		print_r($response);
	}


	/**
	*method=HEAD请求示例
	*/
    public function doHead() {
		//域名后、query前的部分
		$path = "/head";
		$request = new HttpRequest($this::$host, $path, HttpMethod::HEAD, $this::$appKey, $this::$appSecret);

        //设定Content-Type，根据服务器端接受的值来设置
		$request->setHeader(HttpHeader::HTTP_HEADER_CONTENT_TYPE, ContentType::CONTENT_TYPE_TEXT);
		
        //设定Accept，根据服务器端接受的值来设置
		$request->setHeader(HttpHeader::HTTP_HEADER_ACCEPT, ContentType::CONTENT_TYPE_TEXT);
        //如果是调用测试环境请设置
		//$request->setHeader(SystemHeader::X_CA_STAG, "TEST");


        //注意：业务header部分，如果没有则无此行(如果有中文，请做Utf8ToIso88591处理)
		//mb_convert_encoding("headervalue2中文", "ISO-8859-1", "UTF-8");
		$request->setHeader("b-header2", "headervalue2");
		$request->setHeader("a-header1", "headervalue1");

        //注意：业务query部分，如果没有则无此行；请不要、不要、不要做UrlEncode处理
		$request->setQuery("b-query2", "queryvalue2");
		$request->setQuery("a-query1", "queryvalue1");

        //指定参与签名的header
		$request->setSignHeader(SystemHeader::X_CA_TIMESTAMP);
		$request->setSignHeader("a-header1");
		$request->setSignHeader("b-header2");

		$response = HttpClient::execute($request);
		print_r($response);
	}


}
