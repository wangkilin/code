<?php
defined('BASE_PATH') OR exit('Access not allowed');

/**
 * Router Class
 *
 * Parses URIs and determines routing
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/general/routing.html
 */
class Router
{

	/**
	 * Config class object
	 *
	 * @var	object
	 */
	public $config;

	/**
	 * Request class object
	 * @var object
	 */
	protected $request;

	/**
	 * List of routes
	 *
	 * @var	array
	 */
	public $routes =	array();

	protected $_controller = 'index';
	protected $_dir = '';
	protected $_action = 'index';

	/**
	 * Class constructor
	 *
	 * Runs the route mapping function.
	 *
	 * @return	void
	 */
	public function __construct($request = null, $routing = NULL)
	{
		$this->config = & loadClass('Config', BASE_PATH);
		if (! ($request instanceof Request)) {
		    $request = & loadClass('Request', BASE_PATH);
		}
		$this->request = $request;
		//$this->uri    = & loadClass('URI', BASE_PATH);
        $this->loadDefaultRoute();

        $this->parse();

        return;


		$this->enable_query_strings = ( ! is_cli() && $this->config->item('enable_query_strings') === TRUE);

		// If a directory override is configured, it has to be set before any dynamic routing logic
		is_array($routing) && isset($routing['directory']) && $this->set_directory($routing['directory']);
		$this->_set_routing();

		// Set any routing overrides that may exist in the main index file
		if (is_array($routing))
		{
			empty($routing['controller']) OR $this->set_class($routing['controller']);
			empty($routing['function'])   OR $this->set_method($routing['function']);
		}

		addLog('info', 'Router Class Initialized');
	}

	public function setRouting ($routing)
	{
	    return $this;
	}

	/**
	 * 开始路由
	 */
	public function route ()
	{
	    //@todo
	}

	/**
	 * 从配置文件总加载默认的路由信息
	 */
	protected function loadDefaultRoute ()
	{
	    if (true===$this->config->get('isSupportControllerDir')) {
            $this->_dir = $this->config->get('defaultControllerDir');
	    }
        $this->_controller = $this->config->get('defaultController');
        $this->_action = $this->config->get('defaultAction');
	}

	/**
	 * 在路由配置中匹配请求的路径
	 * @param string $pathinfo 请求的pathinfo
	 * @return string 匹配到了路由配置, 返回路由对应的pathinfo
	 */
	protected function matchRouteConfig ($pathinfo)
	{
	    $routeSetting = $this->config->get('route');
	    foreach ($routeSetting as $_key=>$_value) {
	        if ($_value) {
	            $pathinfo = $routeSetting[$key];
	            break;
	        }
	    }

	    return $pathinfo;
	}

	/**
	 * 从pathinfo中载入相应参数： dir， controller， action以及相关get参数
	 * @param unknown $pathinfo
	 */
	protected function loadParamsFromPathInfo ($pathinfo)
	{
	    $pathinfo = trim($pathinfo, $this->config->get('queryKeyValueSeparator'));
	    $params = explode($this->config->get('queryKeyValueSeparator'), $pathinfo);
        $matched = true;
        echo 'aaa';
        if ( true===$this->config->get('isSupportControllerDir') ) {
        	echo 'aaa';
        	$_param = array_shift($params);
            if (preg_match('/^[a-z0-9]+(\.[a-z]+)?$/i', $_param)) {
            	$_param = explode('.', $_param);
            	$this->_dir = $_param[0];
            	$matched = count($_param) === 1;
            } else {
            	$matched = false;
            }
        }
        if ($matched && $params) {
        	$_param = array_shift($params);
            if (preg_match('/^[a-z0-9]+(\.[a-z]+)?$/i', $_param)) {
            	$_param = explode('.', $_param);
            	$this->_controller = $_param[0];
            	$matched = count($_param) === 1;
            } else {
            	$matched = false;
            }
        }
        if ($matched && $params) {
        	$_param = array_shift($params);
            if (preg_match('/^[a-z0-9]+(\.[a-z]+)?$/i', $_param)) {
            	$_param = explode('.', $_param);
            	$this->_action = $_param[0];
            }
        }

        $params = array_chunk($params, 2);
        foreach ($params as $_get) {
            if (! isset($_get[1])) {
                $_get[1] = '';
            }
            $this->request->setGet($_get[0], $_get[1], false);
        }
	}

	public function parse ()
	{
	    $hasBeenParsed = false; // 是否已经解析完成
        $urlQueryPathKey    = $this->config->get('urlPathQueryName');
        $dirKeyInUrl        = $this->config->get('dirKeyInUrl');
        $controllerKeyInUrl = $this->config->get('controllerKeyInUrl');
        $actionKeyInUrl     = $this->config->get('actionKeyInUrl');
        $isUrlCaseSensitive = $this->config->get('urlCaseSensitive');

        // 从get中获取路由信息。 级别较低
        if ( true===$this->config->get('isSupportControllerDir')
          && $this->request->get($dirKeyInUrl) !==null ) {
            $this->_dir = strval($this->request->get($dirKeyInUrl));
        }
        if ($this->request->get($controllerKeyInUrl) !== null) {
            $this->_controller = strval($this->request->get($controllerKeyInUrl));
        }
        if ($this->request->get($actionKeyInUrl) !== null) {
            $this->_action = strval($this->request->get($actionKeyInUrl));
        }

        $pathInfo = $this->request->getUri()->getPathInfo();
        // 查看请求的路径是否能够匹配路由配置， 如果匹配路由配置， 重新设定pathinfo
        $pathInfo!='' && $pathInfo = $this->matchRouteConfig($pathInfo);

        if ($pathInfo) {
            $this->loadParamsFromPathInfo ($pathInfo);
        }

        return;


        // URL常量
        define('__SELF__',strip_tags($_SERVER[C('URL_REQUEST_URI')]));

        // 获取模块名称
        define('MODULE_NAME', defined('BIND_MODULE')? BIND_MODULE : self::getModule($varModule));

        if(!defined('__APP__')){
            $urlMode        =   C('URL_MODEL');
            if($urlMode == URL_COMPAT ){// 兼容模式判断
                define('PHP_FILE',_PHP_FILE_.'?'.$varPath.'=');
            }elseif($urlMode == URL_REWRITE ) {
                $url    =   dirname(_PHP_FILE_);
                if($url == '/' || $url == '\\')
                    $url    =   '';
                define('PHP_FILE',$url);
            }else {
                define('PHP_FILE',_PHP_FILE_);
            }
            // 当前应用地址
            define('__APP__',strip_tags(PHP_FILE));
        }
        // 模块URL地址
        $moduleName    =   defined('MODULE_ALIAS')? MODULE_ALIAS : MODULE_NAME;
        define('__MODULE__',(defined('BIND_MODULE') || !C('MULTI_MODULE'))? __APP__ : __APP__.'/'.($urlCase ? strtolower($moduleName) : $moduleName));

        if('' != $_SERVER['PATH_INFO'] && (!C('URL_ROUTER_ON') ||  !Route::check()) ){   // 检测路由规则 如果没有则按默认规则调度URL
            Hook::listen('path_info');
            // 检查禁止访问的URL后缀
            if(C('URL_DENY_SUFFIX') && preg_match('/\.('.trim(C('URL_DENY_SUFFIX'),'.').')$/i', $_SERVER['PATH_INFO'])){
                send_http_status(404);
                exit;
            }

            // 去除URL后缀
            $_SERVER['PATH_INFO'] = preg_replace(C('URL_HTML_SUFFIX')? '/\.('.trim(C('URL_HTML_SUFFIX'),'.').')$/i' : '/\.'.__EXT__.'$/i', '', $_SERVER['PATH_INFO']);

            $depr   =   C('URL_PATHINFO_DEPR');
            $paths  =   explode($depr,trim($_SERVER['PATH_INFO'],$depr));

            if(!defined('BIND_CONTROLLER')) {// 获取控制器
                if(C('CONTROLLER_LEVEL')>1){// 控制器层次
                    $_GET[$varController]   =   implode('/',array_slice($paths,0,C('CONTROLLER_LEVEL')));
                    $paths  =   array_slice($paths, C('CONTROLLER_LEVEL'));
                }else{
                    $_GET[$varController]   =   array_shift($paths);
                }
            }
            // 获取操作
            if(!defined('BIND_ACTION')){
                $_GET[$varAction]  =   array_shift($paths);
            }
            // 解析剩余的URL参数
            $var  =  array();
            if(C('URL_PARAMS_BIND') && 1 == C('URL_PARAMS_BIND_TYPE')){
                // URL参数按顺序绑定变量
                $var    =   $paths;
            }else{
                preg_replace_callback('/(\w+)\/([^\/]+)/', function($match) use(&$var){$var[$match[1]]=strip_tags($match[2]);}, implode('/',$paths));
            }
            $_GET   =  array_merge($var,$_GET);
        }
        // 获取控制器的命名空间（路径）
        define('CONTROLLER_PATH',   self::getSpace($varAddon,$urlCase));
        // 获取控制器和操作名
        define('CONTROLLER_NAME',   defined('BIND_CONTROLLER')? BIND_CONTROLLER : self::getController($varController,$urlCase));
        define('ACTION_NAME',       defined('BIND_ACTION')? BIND_ACTION : self::getAction($varAction,$urlCase));

        // 当前控制器的UR地址
        $controllerName    =   defined('CONTROLLER_ALIAS')? CONTROLLER_ALIAS : CONTROLLER_NAME;
        define('__CONTROLLER__',__MODULE__.$depr.(defined('BIND_CONTROLLER')? '': ( $urlCase ? parse_name($controllerName) : $controllerName )) );

        // 当前操作的URL地址
        define('__ACTION__',__CONTROLLER__.$depr.(defined('ACTION_ALIAS')?ACTION_ALIAS:ACTION_NAME));

	}

	public function getDir ()
	{
	    return $this->_dir;
	}

	public function getController ()
	{
	    return $this->_controller;
	}

	public function getAction ()
	{
	    return $this->_action;
	}

	/**
	 * Current class name
	 *
	 * @var	string
	 */
	public $class =		'';

	/**
	 * Current method name
	 *
	 * @var	string
	 */
	public $method =	'index';

	/**
	 * Sub-directory that contains the requested controller class
	 *
	 * @var	string
	 */
	public $directory;

	/**
	 * Default controller (and method if specific)
	 *
	 * @var	string
	 */
	public $default_controller;

	/**
	 * Translate URI dashes
	 *
	 * Determines whether dashes in controller & method segments
	 * should be automatically replaced by underscores.
	 *
	 * @var	bool
	 */
	public $translate_uri_dashes = FALSE;

	/**
	 * Enable query strings flag
	 *
	 * Determines whether to use GET parameters or segment URIs
	 *
	 * @var	bool
	 */
	public $enable_query_strings = FALSE;

	// --------------------------------------------------------------------

	/**
	 * Class constructor
	 *
	 * Runs the route mapping function.
	 *
	 * @return	void
	 */
	public function ___construct($routing = NULL)
	{
		$this->config =& load_class('Config', 'core');
		$this->uri =& load_class('URI', 'core');

		$this->enable_query_strings = ( ! is_cli() && $this->config->item('enable_query_strings') === TRUE);

		// If a directory override is configured, it has to be set before any dynamic routing logic
		is_array($routing) && isset($routing['directory']) && $this->set_directory($routing['directory']);
		$this->_set_routing();

		// Set any routing overrides that may exist in the main index file
		if (is_array($routing))
		{
			empty($routing['controller']) OR $this->set_class($routing['controller']);
			empty($routing['function'])   OR $this->set_method($routing['function']);
		}

		log_message('info', 'Router Class Initialized');
	}

	// --------------------------------------------------------------------

	/**
	 * Set route mapping
	 *
	 * Determines what should be served based on the URI request,
	 * as well as any "routes" that have been set in the routing config file.
	 *
	 * @return	void
	 */
	protected function _set_routing()
	{
		// Are query strings enabled in the config file? Normally CI doesn't utilize query strings
		// since URI segments are more search-engine friendly, but they can optionally be used.
		// If this feature is enabled, we will gather the directory/class/method a little differently
		if ($this->enable_query_strings)
		{
			// If the directory is set at this time, it means an override exists, so skip the checks
			if ( ! isset($this->directory))
			{
				$_d = $this->config->item('directory_trigger');
				$_d = isset($_GET[$_d]) ? trim($_GET[$_d], " \t\n\r\0\x0B/") : '';

				if ($_d !== '')
				{
					$this->uri->filter_uri($_d);
					$this->set_directory($_d);
				}
			}

			$_c = trim($this->config->item('controller_trigger'));
			if ( ! empty($_GET[$_c]))
			{
				$this->uri->filter_uri($_GET[$_c]);
				$this->set_class($_GET[$_c]);

				$_f = trim($this->config->item('function_trigger'));
				if ( ! empty($_GET[$_f]))
				{
					$this->uri->filter_uri($_GET[$_f]);
					$this->set_method($_GET[$_f]);
				}

				$this->uri->rsegments = array(
					1 => $this->class,
					2 => $this->method
				);
			}
			else
			{
				$this->_set_default_controller();
			}

			// Routing rules don't apply to query strings and we don't need to detect
			// directories, so we're done here
			return;
		}

		// Load the routes.php file.
		if (file_exists(APPPATH.'config/routes.php'))
		{
			include(APPPATH.'config/routes.php');
		}

		if (file_exists(APPPATH.'config/'.ENVIRONMENT.'/routes.php'))
		{
			include(APPPATH.'config/'.ENVIRONMENT.'/routes.php');
		}

		// Validate & get reserved routes
		if (isset($route) && is_array($route))
		{
			isset($route['default_controller']) && $this->default_controller = $route['default_controller'];
			isset($route['translate_uri_dashes']) && $this->translate_uri_dashes = $route['translate_uri_dashes'];
			unset($route['default_controller'], $route['translate_uri_dashes']);
			$this->routes = $route;
		}

		// Is there anything to parse?
		if ($this->uri->uri_string !== '')
		{
			$this->_parse_routes();
		}
		else
		{
			$this->_set_default_controller();
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Set request route
	 *
	 * Takes an array of URI segments as input and sets the class/method
	 * to be called.
	 *
	 * @used-by	CI_Router::_parse_routes()
	 * @param	array	$segments	URI segments
	 * @return	void
	 */
	protected function _set_request($segments = array())
	{
		$segments = $this->_validate_request($segments);
		// If we don't have any segments left - try the default controller;
		// WARNING: Directories get shifted out of the segments array!
		if (empty($segments))
		{
			$this->_set_default_controller();
			return;
		}

		if ($this->translate_uri_dashes === TRUE)
		{
			$segments[0] = str_replace('-', '_', $segments[0]);
			if (isset($segments[1]))
			{
				$segments[1] = str_replace('-', '_', $segments[1]);
			}
		}

		$this->set_class($segments[0]);
		if (isset($segments[1]))
		{
			$this->set_method($segments[1]);
		}
		else
		{
			$segments[1] = 'index';
		}

		array_unshift($segments, NULL);
		unset($segments[0]);
		$this->uri->rsegments = $segments;
	}

	// --------------------------------------------------------------------

	/**
	 * Set default controller
	 *
	 * @return	void
	 */
	protected function _set_default_controller()
	{
		if (empty($this->default_controller))
		{
			show_error('Unable to determine what should be displayed. A default route has not been specified in the routing file.');
		}

		// Is the method being specified?
		if (sscanf($this->default_controller, '%[^/]/%s', $class, $method) !== 2)
		{
			$method = 'index';
		}

		if ( ! file_exists(APPPATH.'controllers/'.$this->directory.ucfirst($class).'.php'))
		{
			// This will trigger 404 later
			return;
		}

		$this->set_class($class);
		$this->set_method($method);

		// Assign routed segments, index starting from 1
		$this->uri->rsegments = array(
			1 => $class,
			2 => $method
		);

		log_message('debug', 'No URI present. Default controller set.');
	}

	// --------------------------------------------------------------------

	/**
	 * Validate request
	 *
	 * Attempts validate the URI request and determine the controller path.
	 *
	 * @used-by	CI_Router::_set_request()
	 * @param	array	$segments	URI segments
	 * @return	mixed	URI segments
	 */
	protected function _validate_request($segments)
	{
		$c = count($segments);
		$directory_override = isset($this->directory);

		// Loop through our segments and return as soon as a controller
		// is found or when such a directory doesn't exist
		while ($c-- > 0)
		{
			$test = $this->directory
				.ucfirst($this->translate_uri_dashes === TRUE ? str_replace('-', '_', $segments[0]) : $segments[0]);

			if ( ! file_exists(APPPATH.'controllers/'.$test.'.php')
				&& $directory_override === FALSE
				&& is_dir(APPPATH.'controllers/'.$this->directory.$segments[0])
			)
			{
				$this->set_directory(array_shift($segments), TRUE);
				continue;
			}

			return $segments;
		}

		// This means that all segments were actually directories
		return $segments;
	}

	// --------------------------------------------------------------------

	/**
	 * Parse Routes
	 *
	 * Matches any routes that may exist in the config/routes.php file
	 * against the URI to determine if the class/method need to be remapped.
	 *
	 * @return	void
	 */
	protected function _parse_routes()
	{
		// Turn the segment array into a URI string
		$uri = implode('/', $this->uri->segments);

		// Get HTTP verb
		$http_verb = isset($_SERVER['REQUEST_METHOD']) ? strtolower($_SERVER['REQUEST_METHOD']) : 'cli';

		// Is there a literal match?  If so we're done
		if (isset($this->routes[$uri]))
		{
			// Check default routes format
			if (is_string($this->routes[$uri]))
			{
				$this->_set_request(explode('/', $this->routes[$uri]));
				return;
			}
			// Is there a matching http verb?
			elseif (is_array($this->routes[$uri]) && isset($this->routes[$uri][$http_verb]))
			{
				$this->_set_request(explode('/', $this->routes[$uri][$http_verb]));
				return;
			}
		}

		// Loop through the route array looking for wildcards
		foreach ($this->routes as $key => $val)
		{
			// Check if route format is using http verb
			if (is_array($val))
			{
				if (isset($val[$http_verb]))
				{
					$val = $val[$http_verb];
				}
				else
				{
					continue;
				}
			}

			// Convert wildcards to RegEx
			$key = str_replace(array(':any', ':num'), array('[^/]+', '[0-9]+'), $key);

			// Does the RegEx match?
			if (preg_match('#^'.$key.'$#', $uri, $matches))
			{
				// Are we using callbacks to process back-references?
				if ( ! is_string($val) && is_callable($val))
				{
					// Remove the original string from the matches array.
					array_shift($matches);

					// Execute the callback using the values in matches as its parameters.
					$val = call_user_func_array($val, $matches);
				}
				// Are we using the default routing method for back-references?
				elseif (strpos($val, '$') !== FALSE && strpos($key, '(') !== FALSE)
				{
					$val = preg_replace('#^'.$key.'$#', $val, $uri);
				}

				$this->_set_request(explode('/', $val));
				return;
			}
		}

		// If we got this far it means we didn't encounter a
		// matching route so we'll set the site default route
		$this->_set_request(array_values($this->uri->segments));
	}

	// --------------------------------------------------------------------

	/**
	 * Set class name
	 *
	 * @param	string	$class	Class name
	 * @return	void
	 */
	public function set_class($class)
	{
		$this->class = str_replace(array('/', '.'), '', $class);
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch the current class
	 *
	 * @deprecated	3.0.0	Read the 'class' property instead
	 * @return	string
	 */
	public function fetch_class()
	{
		return $this->class;
	}

	// --------------------------------------------------------------------

	/**
	 * Set method name
	 *
	 * @param	string	$method	Method name
	 * @return	void
	 */
	public function set_method($method)
	{
		$this->method = $method;
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch the current method
	 *
	 * @deprecated	3.0.0	Read the 'method' property instead
	 * @return	string
	 */
	public function fetch_method()
	{
		return $this->method;
	}

	// --------------------------------------------------------------------

	/**
	 * Set directory name
	 *
	 * @param	string	$dir	Directory name
	 * @param	bool	$appent	Whether we're appending rather than setting the full value
	 * @return	void
	 */
	public function set_directory($dir, $append = FALSE)
	{
		if ($append !== TRUE OR empty($this->directory))
		{
			$this->directory = str_replace('.', '', trim($dir, '/')).'/';
		}
		else
		{
			$this->directory .= str_replace('.', '', trim($dir, '/')).'/';
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch directory
	 *
	 * Feches the sub-directory (if any) that contains the requested
	 * controller class.
	 *
	 * @deprecated	3.0.0	Read the 'directory' property instead
	 * @return	string
	 */
	public function fetch_directory()
	{
		return $this->directory;
	}

}

/**
 * ThinkPHP内置的Dispatcher类
 * 完成URL解析、路由和调度
 */
class Dispatcher {

    /**
     * URL映射到控制器
     * @access public
     * @return void
     */
    static public function dispatch() {
        $varPath        =   C('VAR_PATHINFO');
        $varAddon       =   C('VAR_ADDON');
        $varModule      =   C('VAR_MODULE');
        $varController  =   C('VAR_CONTROLLER');
        $varAction      =   C('VAR_ACTION');
        $urlCase        =   C('URL_CASE_INSENSITIVE');
        if(isset($_GET[$varPath])) { // 判断URL里面是否有兼容模式参数
            $_SERVER['PATH_INFO'] = $_GET[$varPath];
            unset($_GET[$varPath]);
        }elseif(IS_CLI){ // CLI模式下 index.php module/controller/action/params/...
            $_SERVER['PATH_INFO'] = isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : '';
        }

        // 开启子域名部署
        if(C('APP_SUB_DOMAIN_DEPLOY')) {
            $rules      = C('APP_SUB_DOMAIN_RULES');
            if(isset($rules[$_SERVER['HTTP_HOST']])) { // 完整域名或者IP配置
                define('APP_DOMAIN',$_SERVER['HTTP_HOST']); // 当前完整域名
                $rule = $rules[APP_DOMAIN];
            }else{
                if(strpos(C('APP_DOMAIN_SUFFIX'),'.')){ // com.cn net.cn
                    $domain = array_slice(explode('.', $_SERVER['HTTP_HOST']), 0, -3);
                }else{
                    $domain = array_slice(explode('.', $_SERVER['HTTP_HOST']), 0, -2);
                }
                if(!empty($domain)) {
                    $subDomain = implode('.', $domain);
                    define('SUB_DOMAIN',$subDomain); // 当前完整子域名
                    $domain2   = array_pop($domain); // 二级域名
                    if($domain) { // 存在三级域名
                        $domain3 = array_pop($domain);
                    }
                    if(isset($rules[$subDomain])) { // 子域名
                        $rule = $rules[$subDomain];
                    }elseif(isset($rules['*.' . $domain2]) && !empty($domain3)){ // 泛三级域名
                        $rule = $rules['*.' . $domain2];
                        $panDomain = $domain3;
                    }elseif(isset($rules['*']) && !empty($domain2) && 'www' != $domain2 ){ // 泛二级域名
                        $rule      = $rules['*'];
                        $panDomain = $domain2;
                    }
                }
            }

            if(!empty($rule)) {
                // 子域名部署规则 '子域名'=>array('模块名[/控制器名]','var1=a&var2=b');
                if(is_array($rule)){
                    list($rule,$vars) = $rule;
                }
                $array      =   explode('/',$rule);
                // 模块绑定
                define('BIND_MODULE',array_shift($array));
                // 控制器绑定
                if(!empty($array)) {
                    $controller  =   array_shift($array);
                    if($controller){
                        define('BIND_CONTROLLER',$controller);
                    }
                }
                if(isset($vars)) { // 传入参数
                    parse_str($vars,$parms);
                    if(isset($panDomain)){
                        $pos = array_search('*', $parms);
                        if(false !== $pos) {
                            // 泛域名作为参数
                            $parms[$pos] = $panDomain;
                        }
                    }
                    $_GET   =  array_merge($_GET,$parms);
                }
            }
        }
        // 分析PATHINFO信息
        if(!isset($_SERVER['PATH_INFO'])) {
            $types   =  explode(',',C('URL_PATHINFO_FETCH'));
            foreach ($types as $type){
                if(0===strpos($type,':')) {// 支持函数判断
                    $_SERVER['PATH_INFO'] =   call_user_func(substr($type,1));
                    break;
                }elseif(!empty($_SERVER[$type])) {
                    $_SERVER['PATH_INFO'] = (0 === strpos($_SERVER[$type],$_SERVER['SCRIPT_NAME']))?
                    substr($_SERVER[$type], strlen($_SERVER['SCRIPT_NAME']))   :  $_SERVER[$type];
                    break;
                }
            }
        }

        $depr = C('URL_PATHINFO_DEPR');
        define('MODULE_PATHINFO_DEPR',  $depr);

        if(empty($_SERVER['PATH_INFO'])) {
            $_SERVER['PATH_INFO'] = '';
            define('__INFO__','');
            define('__EXT__','');
        }else{
            define('__INFO__',trim($_SERVER['PATH_INFO'],'/'));
            // URL后缀
            define('__EXT__', strtolower(pathinfo($_SERVER['PATH_INFO'],PATHINFO_EXTENSION)));
            $_SERVER['PATH_INFO'] = __INFO__;
            if(!defined('BIND_MODULE') && (!C('URL_ROUTER_ON') || !Route::check())){
                if (__INFO__ && C('MULTI_MODULE')){ // 获取模块名
                    $paths      =   explode($depr,__INFO__,2);
                    $allowList  =   C('MODULE_ALLOW_LIST'); // 允许的模块列表
                    $module     =   preg_replace('/\.' . __EXT__ . '$/i', '',$paths[0]);
                    if( empty($allowList) || (is_array($allowList) && in_array_case($module, $allowList))){
                        $_GET[$varModule]       =   $module;
                        $_SERVER['PATH_INFO']   =   isset($paths[1])?$paths[1]:'';
                    }
                }
            }
        }

        // URL常量
        define('__SELF__',strip_tags($_SERVER[C('URL_REQUEST_URI')]));

        // 获取模块名称
        define('MODULE_NAME', defined('BIND_MODULE')? BIND_MODULE : self::getModule($varModule));

        // 检测模块是否存在
        if( MODULE_NAME && (defined('BIND_MODULE') || !in_array_case(MODULE_NAME,C('MODULE_DENY_LIST')) ) && is_dir(APP_PATH.MODULE_NAME)){
            // 定义当前模块路径
            define('MODULE_PATH', APP_PATH.MODULE_NAME.'/');
            // 定义当前模块的模版缓存路径
            C('CACHE_PATH',CACHE_PATH.MODULE_NAME.'/');
            // 定义当前模块的日志目录
            C('LOG_PATH',  realpath(LOG_PATH).'/'.MODULE_NAME.'/');

            // 模块检测
            Hook::listen('module_check');

            // 加载模块配置文件
            if(is_file(MODULE_PATH.'Conf/config'.CONF_EXT))
                C(load_config(MODULE_PATH.'Conf/config'.CONF_EXT));
            // 加载应用模式对应的配置文件
            if('common' != APP_MODE && is_file(MODULE_PATH.'Conf/config_'.APP_MODE.CONF_EXT))
                C(load_config(MODULE_PATH.'Conf/config_'.APP_MODE.CONF_EXT));
            // 当前应用状态对应的配置文件
            if(APP_STATUS && is_file(MODULE_PATH.'Conf/'.APP_STATUS.CONF_EXT))
                C(load_config(MODULE_PATH.'Conf/'.APP_STATUS.CONF_EXT));

            // 加载模块别名定义
            if(is_file(MODULE_PATH.'Conf/alias.php'))
                Think::addMap(include MODULE_PATH.'Conf/alias.php');
            // 加载模块tags文件定义
            if(is_file(MODULE_PATH.'Conf/tags.php'))
                Hook::import(include MODULE_PATH.'Conf/tags.php');
            // 加载模块函数文件
            if(is_file(MODULE_PATH.'Common/function.php'))
                include MODULE_PATH.'Common/function.php';

            $urlCase        =   C('URL_CASE_INSENSITIVE');
            // 加载模块的扩展配置文件
            load_ext_file(MODULE_PATH);
        }else{
            E(L('_MODULE_NOT_EXIST_').':'.MODULE_NAME);
        }

        if(!defined('__APP__')){
            $urlMode        =   C('URL_MODEL');
            if($urlMode == URL_COMPAT ){// 兼容模式判断
                define('PHP_FILE',_PHP_FILE_.'?'.$varPath.'=');
            }elseif($urlMode == URL_REWRITE ) {
                $url    =   dirname(_PHP_FILE_);
                if($url == '/' || $url == '\\')
                    $url    =   '';
                define('PHP_FILE',$url);
            }else {
                define('PHP_FILE',_PHP_FILE_);
            }
            // 当前应用地址
            define('__APP__',strip_tags(PHP_FILE));
        }
        // 模块URL地址
        $moduleName    =   defined('MODULE_ALIAS')? MODULE_ALIAS : MODULE_NAME;
        define('__MODULE__',(defined('BIND_MODULE') || !C('MULTI_MODULE'))? __APP__ : __APP__.'/'.($urlCase ? strtolower($moduleName) : $moduleName));

        if('' != $_SERVER['PATH_INFO'] && (!C('URL_ROUTER_ON') ||  !Route::check()) ){   // 检测路由规则 如果没有则按默认规则调度URL
            Hook::listen('path_info');
            // 检查禁止访问的URL后缀
            if(C('URL_DENY_SUFFIX') && preg_match('/\.('.trim(C('URL_DENY_SUFFIX'),'.').')$/i', $_SERVER['PATH_INFO'])){
                send_http_status(404);
                exit;
            }

            // 去除URL后缀
            $_SERVER['PATH_INFO'] = preg_replace(C('URL_HTML_SUFFIX')? '/\.('.trim(C('URL_HTML_SUFFIX'),'.').')$/i' : '/\.'.__EXT__.'$/i', '', $_SERVER['PATH_INFO']);

            $depr   =   C('URL_PATHINFO_DEPR');
            $paths  =   explode($depr,trim($_SERVER['PATH_INFO'],$depr));

            if(!defined('BIND_CONTROLLER')) {// 获取控制器
                if(C('CONTROLLER_LEVEL')>1){// 控制器层次
                    $_GET[$varController]   =   implode('/',array_slice($paths,0,C('CONTROLLER_LEVEL')));
                    $paths  =   array_slice($paths, C('CONTROLLER_LEVEL'));
                }else{
                    $_GET[$varController]   =   array_shift($paths);
                }
            }
            // 获取操作
            if(!defined('BIND_ACTION')){
                $_GET[$varAction]  =   array_shift($paths);
            }
            // 解析剩余的URL参数
            $var  =  array();
            if(C('URL_PARAMS_BIND') && 1 == C('URL_PARAMS_BIND_TYPE')){
                // URL参数按顺序绑定变量
                $var    =   $paths;
            }else{
                preg_replace_callback('/(\w+)\/([^\/]+)/', function($match) use(&$var){$var[$match[1]]=strip_tags($match[2]);}, implode('/',$paths));
            }
            $_GET   =  array_merge($var,$_GET);
        }
        // 获取控制器的命名空间（路径）
        define('CONTROLLER_PATH',   self::getSpace($varAddon,$urlCase));
        // 获取控制器和操作名
        define('CONTROLLER_NAME',   defined('BIND_CONTROLLER')? BIND_CONTROLLER : self::getController($varController,$urlCase));
        define('ACTION_NAME',       defined('BIND_ACTION')? BIND_ACTION : self::getAction($varAction,$urlCase));

        // 当前控制器的UR地址
        $controllerName    =   defined('CONTROLLER_ALIAS')? CONTROLLER_ALIAS : CONTROLLER_NAME;
        define('__CONTROLLER__',__MODULE__.$depr.(defined('BIND_CONTROLLER')? '': ( $urlCase ? parse_name($controllerName) : $controllerName )) );

        // 当前操作的URL地址
        define('__ACTION__',__CONTROLLER__.$depr.(defined('ACTION_ALIAS')?ACTION_ALIAS:ACTION_NAME));

        //保证$_REQUEST正常取值
        $_REQUEST = array_merge($_POST,$_GET,$_COOKIE);	// -- 加了$_COOKIE.  保证哦..
    }

    /**
     * 获得控制器的命名空间路径 便于插件机制访问
     */
    static private function getSpace($var,$urlCase) {
        $space  =   !empty($_GET[$var])?strip_tags($_GET[$var]):'';
        unset($_GET[$var]);
        return $space;
    }

    /**
     * 获得实际的控制器名称
     */
    static private function getController($var,$urlCase) {
        $controller = (!empty($_GET[$var])? $_GET[$var]:C('DEFAULT_CONTROLLER'));
        unset($_GET[$var]);
        if($maps = C('URL_CONTROLLER_MAP')) {
            if(isset($maps[strtolower($controller)])) {
                // 记录当前别名
                define('CONTROLLER_ALIAS',strtolower($controller));
                // 获取实际的控制器名
                return   ucfirst($maps[CONTROLLER_ALIAS]);
            }elseif(array_search(strtolower($controller),$maps)){
                // 禁止访问原始控制器
                return   '';
            }
        }
        if($urlCase) {
            // URL地址不区分大小写
            // 智能识别方式 user_type 识别到 UserTypeController 控制器
            $controller = parse_name($controller,1);
        }
        return strip_tags(ucfirst($controller));
    }

    /**
     * 获得实际的操作名称
     */
    static private function getAction($var,$urlCase) {
        $action   = !empty($_POST[$var]) ?
        $_POST[$var] :
        (!empty($_GET[$var])?$_GET[$var]:C('DEFAULT_ACTION'));
        unset($_POST[$var],$_GET[$var]);
        if($maps = C('URL_ACTION_MAP')) {
            if(isset($maps[strtolower(CONTROLLER_NAME)])) {
                $maps =   $maps[strtolower(CONTROLLER_NAME)];
                if(isset($maps[strtolower($action)])) {
                    // 记录当前别名
                    define('ACTION_ALIAS',strtolower($action));
                    // 获取实际的操作名
                    if(is_array($maps[ACTION_ALIAS])){
                        parse_str($maps[ACTION_ALIAS][1],$vars);
                        $_GET   =   array_merge($_GET,$vars);
                        return $maps[ACTION_ALIAS][0];
                    }else{
                        return $maps[ACTION_ALIAS];
                    }

                }elseif(array_search(strtolower($action),$maps)){
                    // 禁止访问原始操作
                    return   '';
                }
            }
        }
        return strip_tags( $urlCase? strtolower($action) : $action );
    }

    /**
     * 获得实际的模块名称
     */
    static private function getModule($var) {
        $module   = (!empty($_GET[$var])?$_GET[$var]:C('DEFAULT_MODULE'));
        unset($_GET[$var]);
        if($maps = C('URL_MODULE_MAP')) {
            if(isset($maps[strtolower($module)])) {
                // 记录当前别名
                define('MODULE_ALIAS',strtolower($module));
                // 获取实际的模块名
                return   ucfirst($maps[MODULE_ALIAS]);
            }elseif(array_search(strtolower($module),$maps)){
                // 禁止访问原始模块
                return   '';
            }
        }
        return strip_tags(ucfirst($module));
    }

}

/**
 * Typecho组件基类
 *
 * TODO 增加cache缓存
 * @package Router
 */
class Typecho_Router
{
    /**
     * 当前路由名称
     *
     * @access public
     * @var string
     */
    public static $current;

    /**
     * 已经解析完毕的路由表配置
     *
     * @access private
     * @var mixed
     */
    private static $_routingTable = array();

    /**
     * 全路径
     *
     * @access private
     * @var string
    */
    private static $_pathInfo = NULL;

    /**
     * 解析路径
     *
     * @access public
     * @param string $pathInfo 全路径
     * @param mixed $parameter 输入参数
     * @return mixed
     */
    public static function match($pathInfo, $parameter = NULL)
    {
        foreach (self::$_routingTable as $key => $route) {
            if (preg_match($route['regx'], $pathInfo, $matches)) {
                self::$current = $key;

                try {
                    /** 载入参数 */
                    $params = NULL;

                    if (!empty($route['params'])) {
                        unset($matches[0]);
                        $params = array_combine($route['params'], $matches);
                    }

                    $widget = Typecho_Widget::widget($route['widget'], $parameter, $params);

                    return $widget;

                } catch (Exception $e) {
                    if (404 == $e->getCode()) {
                        Typecho_Widget::destory($route['widget']);
                        continue;
                    }

                    throw $e;
                }
            }
        }

        return false;
    }

    /**
     * 设置全路径
     *
     * @access public
     * @param string $pathInfo
     * @return void
     */
    public static function setPathInfo($pathInfo = '/')
    {
        self::$_pathInfo = $pathInfo;
    }

    /**
     * 获取全路径
     *
     * @access public
     * @return string
     */
    public static function getPathInfo()
    {
        if (NULL === self::$_pathInfo) {
            self::setPathInfo();
        }

        return self::$_pathInfo;
    }

    /**
     * 路由分发函数
     *
     * @param string $path 目的文件所在目录
     * @return void
     * @throws Typecho_Route_Exception
     */
    public static function dispatch()
    {
        /** 获取PATHINFO */
        $pathInfo = self::getPathInfo();

        foreach (self::$_routingTable as $key => $route) {
            if (preg_match($route['regx'], $pathInfo, $matches)) {
                self::$current = $key;

                try {
                    /** 载入参数 */
                    $params = NULL;

                    if (!empty($route['params'])) {
                        unset($matches[0]);
                        $params = array_combine($route['params'], $matches);
                    }

                    $widget = Typecho_Widget::widget($route['widget'], NULL, $params);

                    if (isset($route['action'])) {
                        $widget->{$route['action']}();
                    }

                    return;

                } catch (Exception $e) {
                    if (404 == $e->getCode()) {
                        Typecho_Widget::destory($route['widget']);
                        continue;
                    }

                    throw $e;
                }
            }
        }

        /** 载入路由异常支持 */
        throw new Typecho_Router_Exception("Path '{$pathInfo}' not found", 404);
    }

    /**
     * 路由反解析函数
     *
     * @param string $name 路由配置表名称
     * @param string $value 路由填充值
     * @param string $prefix 最终合成路径的前缀
     * @return string
     */
    public static function url($name, array $value = NULL, $prefix = NULL)
    {
        $route = self::$_routingTable[$name];

        //交换数组键值
        $pattern = array();
        foreach ($route['params'] as $row) {
            $pattern[$row] = isset($value[$row]) ? $value[$row] : '{' . $row . '}';
        }

        return Typecho_Common::url(vsprintf($route['format'], $pattern), $prefix);
    }

    /**
     * 设置路由器默认配置
     *
     * @access public
     * @param mixed $routes 配置信息
     * @return void
     */
    public static function setRoutes($routes)
    {
        if (isset($routes[0])) {
            self::$_routingTable = $routes[0];
        } else {
            /** 解析路由配置 */
            $parser = new Typecho_Router_Parser($routes);
            self::$_routingTable = $parser->parse();
        }
    }

    /**
     * 获取路由信息
     *
     * @param string $routeName 路由名称
     * @static
     * @access public
     * @return void
     */
    public static function get($routeName)
    {
        return isset(self::$_routingTable[$routeName]) ? self::$_routingTable[$routeName] : NULL;
    }
}


class core_uri
{
    var $params = array(
            'sep_value' => '-',	// 赋值分隔符
            'sep_var' => '__',	// 变量分割符
            'sep_act' => '/'	// 动作分割符
    );

    // 默认控制器
    var $default_vars = array(
            'app_dir' => 'explore',
            'controller' => 'main',
            'action' => 'index'
    );

    var $app_dir = '';
    var $controller = '';
    var $action = '';

    var $request_main = '';
    var $index_script = '';

    var $args_var_str = '';

    public function __construct()
    {
        if (!defined('G_INDEX_SCRIPT'))
        {
            return false;
        }

        if (G_INDEX_SCRIPT == '')
        {
            $this->index_script = '?/';
        }
        else
        {
            $this->index_script = G_INDEX_SCRIPT;
        }

        if ($_SERVER['REQUEST_URI'])
        {
            if (isset($_SERVER['HTTP_X_REWRITE_URL']))
            {
                $request_main = $_SERVER['HTTP_X_REWRITE_URL'];
            }
            else
            {
                $request_main = $_SERVER['REQUEST_URI'];
            }

            $requests = explode($this->index_script, $request_main);

            if (count($requests) == 1 AND dirname($_SERVER['SCRIPT_NAME']) != '/')
            {
                $request_main = preg_replace('/^' . preg_quote(dirname($_SERVER['SCRIPT_NAME']), '/') . '/i', '', $request_main);
            }
            else if (count($requests) == 2)
            {
                if ($requests[0] != '/')
                {
                    $request_main = str_replace($requests[0], '', $request_main);
                }

                $request_main = str_replace($this->index_script, '', $request_main);
            }
        }
        else if ($_SERVER['QUERY_STRING'])
        {
            $request_main = $_SERVER['QUERY_STRING'];
        }

        $request_main = ltrim($request_main, "/\\");

        $base_script = basename($_SERVER['SCRIPT_NAME']);

        if (!strstr($request_main, '=') AND !strstr($request_main, '/') AND !strstr($request_main, '-') AND !strstr($request_main, '.'))
        {
            $request_main .= '/';
        }

        if (strstr($base_script, '.php'))
        {
            $request_main = str_replace($base_script . '/', '', $request_main);
        }

        if (count($requests) == 1)
        {
            $request_main = $this->_parse_uri($request_main);
        }

        $this->request_main = $request_main;
    }

    public function set_rewrite()
    {
        if (!defined('G_INDEX_SCRIPT'))
        {
            return false;
        }

        if (!$this->request_main OR $this->index_script == $this->request_main)
        {
            $this->controller = 'main';
            $this->action = 'index';

            return $this;
        }

        $request = explode('?', $this->request_main, 2);

        if (count($request) == 1)
        {
            $request = explode('&', $this->request_main, 2);
        }

        $uri = array(
                'first' => array_shift($request),
                'last' => ltrim(implode($request), '?')
        );

        if ($uri['last'])
        {
            parse_str($uri['last'], $query_string);

            foreach ($query_string AS $key => $val)
            {
                if (!$_GET[$key])
                {
                    if (! strstr($val, '%'))
                    {
                        $_GET[$key] = $val;
                    }
                    else
                    {
                        $_GET[$key] = urldecode($val);
                    }
                }
            }
        }

        $request = explode($this->params['sep_act'], $uri['first']);

        $uri['first'] = array(
                'pattern' => '',
                'args' => $request
        );

        $__app_dir = $this->default_vars['app_dir'];	// 应用目录
        $this->controller = $this->default_vars['controller'];	// 控制器
        $this->action = $this->default_vars['action'];	// 动作

        $this->args_var_str = '';

        // 删除空值
        foreach ($uri['first']['args'] AS $key => $val)
        {
            if (strstr($val, $this->params['sep_value']) AND !$start_key)
            {
                $start_key = $key;
            }
            else if ($start_key)
            {
                $uri['first']['args'][$start_key] .= $this->params['sep_act'] . $val;

                unset($uri['first']['args'][$key]);
            }
        }

        $args_count = count($uri['first']['args']);

        switch ($args_count)
        {
            default:
                return $this;
                break;

            case 1:
                $this->args_var_str = end($uri['first']['args']);
                break;

            case 2:
                $this->args_var_str = end($uri['first']['args']);

                $__app_dir = $uri['first']['args'][0] ? $uri['first']['args'][0] : $this->default_vars['app_dir'];	// 应用目录
                break;

            case 3:
                $this->args_var_str = end($uri['first']['args']);

                $__app_dir = $uri['first']['args'][0] ? $uri['first']['args'][0] : $this->default_vars['app_dir'];	// 应用目录

                if (file_exists(ROOT_PATH . 'app/' . $__app_dir . '/' . $uri['first']['args'][1] . '.php'))
                {
                    $this->controller = $uri['first']['args'][1];	// 控制器
                }
                else
                {
                    $this->controller = $this->default_vars['controller'];	// 控制器
                    $this->action = $uri['first']['args'][1];	// 动作
                }
                break;

            case 4:
                $this->args_var_str = end($uri['first']['args']);

                $__app_dir = $uri['first']['args'][0] ? $uri['first']['args'][0] : $this->default_vars['app_dir'];	// 应用目录
                $this->controller = $uri['first']['args'][1] ? $uri['first']['args'][1] : $this->default_vars['controller'];	// 控制器
                $this->action = $uri['first']['args'][2] ? $uri['first']['args'][2] : $this->default_vars['action'];	// 动作
                break;

            case 5:
                $this->args_var_str = end($uri['first']['args']);

                $__app_dir = $uri['first']['args'][0] ? $uri['first']['args'][0] : $this->default_vars['app_dir'];	// 应用目录
                $this->controller = $uri['first']['args'][2] ? $uri['first']['args'][1] . '/' . $uri['first']['args'][2] : $this->default_vars['controller'];	// 控制器
                $this->action = $uri['first']['args'][3] ? $uri['first']['args'][3] : $this->default_vars['action'];	// 动作
                break;
        }

        $this->app_dir = ROOT_PATH . 'app/' . $__app_dir . '/';

        $_GET['c'] = $this->controller;
        $_GET['act'] = $this->action;
        $_GET['app'] = $__app_dir;

        return $this;
    }

    public function parse_args()
    {
        if ($args_var_str = $this->args_var_str)
        {
            if (defined('WECENTER_URI_SET_LAST_STRING_TO_ID'))
            {
                $_GET['id'] = urldecode($args_var_str);
            }
            else
            {
                // 兼容 __param-test 写法
                if (substr($args_var_str, 0, strlen($this->params['sep_var'])) == $this->params['sep_var'])
                {
                    $args_var_str = substr($args_var_str, strlen($this->params['sep_var']));
                }

                if (!strstr($args_var_str, '-'))
                {
                    $_GET['id'] = urldecode($args_var_str);
                }

                $uri['last'] = explode($this->params['sep_var'], $args_var_str);

                foreach ($uri['last'] as $val)
                {
                    @list($k, $v) = explode($this->params['sep_value'], $val, 2);

                    if ($k)
                    {
                        if (! strstr($v, '%'))
                        {
                            $_GET[$k] = $v;
                        }
                        else
                        {
                            $_GET[$k] = urldecode($v);
                        }
                    }
                }
            }
        }

        foreach ($_GET AS $key => $val)
        {
            if (strstr($key, '/'))
            {
                unset($_GET[$key]);
            }
        }

        return $this;
    }

    private function _parse_uri($request_main)
    {
        if (get_setting('url_rewrite_enable') == 'Y' AND $request_routes = get_request_route(false))
        {
            if (!$request_main)
            {
                $request_main = '/';
            }

            foreach($request_routes as $key => $val)
            {
                if (preg_match('/^' . $val[0] . '/', $request_main))
                {
                    $request_main = preg_replace('/^' . $val[0] . '/', $val[1], $request_main);

                    return $request_main;
                }
            }
        }

        return $request_main;
    }
}