<?php
/********************************************************/
/*****                 @!!@                          ****/
/********************************************************/
/**
 *@FileName    : F.php
 *@Author      : Kilin WANG <wangkilin@126.com>
 *@Date        : 2016年4月8日
 *@Homepage    : http://www.kinful.com
 *@Version     : 0.1
 */

/**
 * URL组装 支持不同URL模式
 * @param string $url URL表达式，格式：'[分组/模块/操作#锚点@域名]?参数1=值1&参数2=值2...'
 * @param string|array $vars 传入的参数，支持数组和字符串
 * @param string $suffix 伪静态后缀，默认为true表示获取配置值
 * @param boolean $domain 是否显示域名
 *
 * @return string
 */

function U($url='', $vars='', $suffix=true, $domain=false)
{
	// 解析URL
	$info   =  parse_url($url);
	$url    =  isset($info['path']) ? $info['path'] : D . C . '/' . A;

	if(isset($info['fragment'])) { // 解析锚点
		$anchor =   $info['fragment'];

		if(false !== strpos($anchor, '?')) { // 从锚点中解析参数

			list($anchor,$info['query']) = explode('?', $anchor, 2);

		}

	}

	// 解析参数
	if(is_string($vars)) { // a1=a&a2=b 转换成数组
		parse_str($vars, $vars);
	} else if(is_object($vars)) {
	    $vars = get_object_vars($vars);
	}
	$vars = is_array($vars) ? $vars : array(); // 数组参数为合法参数

	if(isset($info['query'])) { // 解析地址里面参数 合并到vars

		parse_str($info['query'], $params);
		$vars = array_merge($params,$vars);
	}

	// URL组装
	$isRootUrl = '/'===$url[0];

	if($isRootUrl) { // 定义路由
		$url = substr ( $url, 1 );
	} else {

		// 解析分组、模块和操作

		$url = trim ( $url, '/' );

		$path = explode ( '/', $url );

		$var = array ();

		$var [config_item ( 'function_trigger' )] = ! empty ( $path ) ? array_pop ( $path ) : A;
		$var [config_item ( 'controller_trigger' )] = ! empty ( $path ) ? array_pop ( $path ) : C;
		$var [config_item ( 'controller_trigger' )] = array_pop ( $path );
		if (is_null ( $var [config_item ( 'controller_trigger' )] )) {
			if (D == '') {
				unset ( $var [config_item ( 'controller_trigger' )] );
			} else {
				$var [config_item ( 'controller_trigger' )] = D;
			}
		}

	}



	if('QUERY_STRING'==config_item('uri_protocol')) { // 普通模式URL转换

		$url        =   F::getBaseUrl().'?'.http_build_query(array_reverse($var));

		if(!empty($vars)) {

			$vars   =   urldecode(http_build_query($vars));

			$url   .=   '&'.$vars;

		}

	}else{ // PATHINFO模式或者兼容URL模式

		if($isRootUrl) {

			$url    =   F::getBaseUrl().'/'.rtrim($url,$depr);

		}else{

			$url    =   F::getBaseUrl().'/'.implode('/', array_reverse($var));

		}

		if(!empty($vars)) { // 添加参数

			foreach ($vars as $var => $val){

				if('' !== trim($val))   $url .= $depr . $var . $depr . urlencode($val);

			}

		}

		if($suffix) {

			$suffix   =  $suffix===true?config_item('url_suffix'):$suffix;

			if($pos = strpos($suffix, '|')){

				$suffix = substr($suffix, 0, $pos);

			}

			if($suffix && '/' != substr($url,-1)){

				$url  .=  '.'.ltrim($suffix,'.');

			}

		}

	}

	if(isset($anchor)){
		$url  .= '#'.$anchor;
	}

	if($domain) {
		$url   =  '//' . $domain . $url;
	}

	return $url;

}


/**
 * 获取输入参数 支持过滤和默认值
 * 使用方法:
 * <code>
 * I('id',0); 获取id参数 自动判断get或者post
 * I('post.name','','htmlspecialchars'); 获取$_POST['name']
 * I('get.'); 获取$_GET
 * </code>
 * @param string $name 变量的名称 支持指定类型
 * @param mixed $default 不存在的时候默认值
 * @param mixed $filter 参数过滤方法
 * @return mixed
 */
function I($name, $default='', $filter=null)
{
    if(strpos($name,'.')) { // 指定参数来源
        list($method,$name) =   explode('.', $name, 2);
        $method = strtoupper($method);
    }else{ // 默认为自动判断
        switch($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $method  =  'POST';
                break;
            case 'PUT':
                parse_str(file_get_contents('php://input'), $PUT);
                $method = 'PUT';
                break;
            default:
                $method  = 'GET';
                break;
        }
    }
    switch($method) {
        case 'GET'     :
        case 'POST'    :
        case 'PUT'     :
        case 'REQUEST' :
        case 'SESSION' :
        case 'COOKIE'  :
        case 'SERVER'  :
        case 'GLOBALS' :
        	$input =& ${'_'.$method};
        	break;
        default:
            return NULL;
    }

    if (isset($input[$name])) { // 取值操作
        $data       =	$input[$name];
        if($filters) {
            $filters    =   explode(',',$filters);
            foreach($filters as $filter){
                if(function_exists($filter)) {
                    $data   =   is_array($data)?array_map($filter,$data):$filter($data); // 参数过滤
                }else{
                    return	 isset($default)?$default:NULL;
                }
            }
        }
    } else { // 变量默认值
        $data = isset($default) ? $default : NULL;
    }

    return $data;
}

class F
{
	/**
	 * 判断是否为手机访问
	 * @return bool
	 */
	static public function isMobile ()
	{
		$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
		// 没有强制web浏览， 并且是手机浏览器
		$isMobile = I('__ignoreMobile')!=='1'
		   && preg_match('/iemobile|mobile\ssafari|iphone\sos|android|symbian|series40/i', $ua);


		return $isMobile;
	}

	/**
	 * 获取站点根目录 URL
	 * @param bool $addScheme 是否添加协议前缀
	 *
	 * @return string
	 */
	static public function getBaseUrl($addDomain=true, $addScheme=false)
	{
		$cleanUrl = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : NULL;
		$cleanUrl = dirname(rtrim($_SERVER['PHP_SELF'], $cleanUrl));
		$cleanUrl = rtrim($_SERVER['HTTP_HOST'] . $cleanUrl, '/\\');

		if ($addScheme) {
			$scheme = is_https() ? 'https:' : 'http:';
		} else {
			$scheme = ':';
		}

		return $scheme . '//' . $cleanUrl;
	}
}