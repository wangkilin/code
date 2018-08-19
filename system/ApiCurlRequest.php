<?php
require_once(dirname(__FILE__) . '/BaseClass.php');

class ApiCurlRequest extends BaseClass
{
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
     * API 请求URL公共前缀
     */
    public $baseUrl = '';

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

    /**
     * 使用get方式发送数据
     * @param unknown $url
     * @return Ambigous <boolean, mixed>
     */
    public function get ($url, $headers=array(), $params=array())
    {
        return $this->request($url, 'GET', $headers, $params);
    }

    /**
     * 使用post方式发送数据
     * @param unknown $url
     * @param unknown $params
     * @param unknown $files
     * @return Ambigous <boolean, mixed>
     */
    public function post ($url, $headers=array(), $params=array(), $files=array())
    {
        return $this->request($url, 'POST', $headers, $params, $files);
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
    protected function request ($url, $method='GET', $headers=array(), $params=array(), $files=array())
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
        if ('POST'==$method && $files) {
            $headers[] = 'Content-Type: multipart/form-data';
        }
        if ($headers) {
            curl_setopt($curlHandler, CURLOPT_HTTPHEADER, $headers);
        }

        if ('POST'==$method) {
        		if (is_string($params)) {
        	        $rawData = & $params;
        	    } else if ($files) {
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


}
