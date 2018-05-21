<?php
defined('BASE_PATH') OR exit('Access not allowed');

require_once BASE_PATH . 'Constants.php';

if (! function_exists('loadClass')) {
    /**
     * Class registry
     *
     * This function acts as a singleton. If the requested class does not
     * exist it is instantiated and set to a static variable. If it has
     * previously been instantiated the variable is returned.
     *
     * @param	string	the class name being requested
     * @param	string	the directory where the class should be found
     * @param	string	an optional argument to pass to the class constructor
     * @return	object
     */
    function & loadClass($class = '', $inDir = '', $param = NULL, $alias=NULL)
    {
        static $_classes = array();

        if (''==$class) {
            return $_classes;
        }

        $alias = isset($alias) ? $alias : $class;
        $class = str_replace('.', '_', $class);

        // 类已被实例化过
        if (isset($_classes[$alias])) {
            return $_classes[$alias];
        }
        importClass($class, $inDir);

        if (! class_exists($class, false)) {
            throw new Exception('Can not load the specified class: ' . $class . ' in dir:'.$inDir, Constants::ERROR_LOAD_CLASS_FAIL);
            return null;
        }

        // Keep track of what we just loaded
        //isLoaded($class);

        $_classes[$alias] = isset($param) ? new $class($param) : new $class();

        return $_classes[$alias];
    }
}

if (! function_exists('importClass')) {
    function importClass ($class, $inDir = '')
    {
    	if (class_exists($class, false)) {
    		return;
    	}
    	
        if (!is_string($inDir) || ''==$inDir) {
        	$inDir = BASE_PATH;
        } else {
        	$inDir = rtrim($inDir, DS) . DS;
        }

        $classFile = $inDir . preg_replace('/_+/', DS, $class).'.php';

        if (false === class_exists($class, false) && file_exists($classFile)) {
            require_once($classFile);
        } else {
        	echo "<br/>file not exist:" . $classFile;
        }
    }
}

if (! function_exists('errorHandler')) {
    function errorHandler ()
    {
    }
}
if (! function_exists('exceptionHandler')) {
    function exceptionHandler ()
    {
    }
}
if (! function_exists('shutdownHandler')) {
    function shutdownHandler ()
    {
    }
}

if (! function_exists('addLog')) {
	/**
	 *
	 * 需要判断系统设置的日志级别
	 *
	 * @param unknown $level
	 * @param unknown $message
	 */
	function addLog ($level, $message)
	{
		static $config =  NULL;
		static $log = NULL;

		if (is_null($config)) {
			$config = & loadClass('Config', BASE_PATH);
		}
		if (is_null($log)) {
		    $log = & loadClass('Log', BASE_PATH);
		}

		$toCacheLog = $config->get('log.doLogAfterRunning')===true;
		$log->write($level, $message, $toCacheLog);
	}
}

if ( ! function_exists('isPhp')) {
    /**
     * Determines if the current version of PHP is equal to or greater than the supplied value
     *
     * @param	string
     * @return	bool	TRUE if the current version is $version or higher
     */
    function isPhp($version)
    {
        static $_is_php;
        $version = (string) $version;

        if ( ! isset($_is_php[$version])) {
            $_is_php[$version] = version_compare(PHP_VERSION, $version, '>=');
        }

        return $_is_php[$version];
    }
}


/* EOF */