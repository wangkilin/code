<?php
defined('BASE_PATH') OR exit('Access not allowed');

abstract class Controller
{
    protected $_config = null;

    /**
     * The home directory of model
     *
     * @var string
     */
    protected $_modelDir = null;
    //protected $model = null;

    /**
     * The home directory of view
     *
     * @var string
     */
    protected $_viewDir = null;
    protected $_viewPrefix = '';
    //protected $view = null;

    protected $_helperDir = null;

    protected $_widgetDir = null;

    /**
     * Form keys
     *
     * @var array
     */
    protected $_keys = array();

    /**
     * Error
     *
     * @var array
    */
    protected $_error;

    /**
     * Constructor
     *
     * Init $_modelsHome & $_viewsHome from config if they are null
     */
    public function __construct()
    {
        foreach (loadClass() as $key=>$instance) {
            $this->$key = $instance;
        }

        $this->_modelDir = APP_PATH . DS . 'models' . DS;
        $this->_viewDir  = APP_PATH . DS . 'views' . DS;
        if (''!==$this->Router->getDir()) {
            $this->_viewPrefix  .= $this->Router->getDir() . $this->Config->get('tplPathSeparator');
        }
        $this->_viewPrefix .= $this->Router->getController() . $this->Config->get('tplPathSeparator');

        //var_dump($this->_modelDir, $this->_viewDir);
    }

    /**
     * 空方法，在具体Action被执行前调用。 可在具体控制器中实现功能， 如权限控制
     */
    public function ready ()
    {
    	return;
    }

    /**
     * View
     *
     * @param array $config
     * @return Cola_View
     */
    protected function getView()
    {
    	if (! is_object($this->view)) {
    		$this->view = & loadClass('View', BASE_PATH);
    	}

    	return $this->view;
    }

    protected function setView ($viewInstance)
    {
    	if (is_object($viewInstance)) {
    		$this->view = $viewInstance;
    	}

    	return $this;
    }

    /**
     * Display the view
     *
     * @param string $tpl
     */
    protected function display($tpl = null)
    {
        if (empty($tpl)) {
            $tpl = $this->_viewDir
                 . $this->_viewPrefix
                 . $this->Router->getAction();
        } else if (strpos($tpl, $this->Config->get('tplPathSeparator'))) {
        	$tpl = $this->_viewPrefix . $tpl;
        }
        $tpl .= $this->Config->get('tplSuffix');
        //echo $tpl;

        $this->view->display($tpl);
    }

    protected function show ($content)
    {
    	$this->view->show($content);
    }

    /**
     * Instantiated model
     *
     * @param string $name
     * @param string $dir
     * @return Cola_Model
     */
    public function getModel($class=null, $dir='', $options=array(), $alias=null)
    {
    	if (is_null($class)) {
    		return $this->model;
    	}
        $class = ucfirst($name) . 'Model';
        $model = loadClass($class, $this->_modelDir);

        return $model;
    }

    public function setModel ($modelInstance)
    {
    	if (is_object($modelInstance)) {
    		$this->model = $modelInstance;
    	}

    	return $this;
    }

    protected function setPath ($type, $dir)
    {
    	if (is_dir($dir)) {
            $type = '_' . strtolower($type);
            $this->$type = $dir;
    	}

        return $this;
    }

    /**
     * Set model home directory
     *
     * @param string $dir
     * @return Controller
     */
    public function setModelDir($dir)
    {
        $this->setPath('model', $dir);

        return $this;
    }

    /**
     * Set view home directory
     *
     * @param string $dir
     * @return Controller
     */
    public function setViewDir($dir)
    {
        $this->setPath('view', $dir);

        return $this;
    }

    public function setHelperDir ($dir)
    {
        $this->setPath('helper', $dir);

        return $this;
    }

    public function setWidgetDir ($dir)
    {
        $this->setPath('widget', $dir);

        return $this;
    }

    /**
     * Redirect to other url
     *
     * @param string $url
     */
    protected function redirect($url)
    {
        Response::redirect($url);
    }

    /**
     * Dynamic set vars
     *
     * @param string $key
     * @param mixed $value
     */
    public function __set($key, $value = null)
    {
        $this->$key = $value;
    }

    /**
     * Dynamic get vars
     *
     * @param string $key
     */
    public function __get($key)
    {
        switch ($key) {
            case 'view':
                return $this->getView();

            case 'model':
                $class = get_class($this);
                $this->model = $this->model(substr($class, 0, -10));
                return $this->model;

            case 'helper':
                $this->helper = new Cola_Helper();
                return $this->helper;

            case 'com':
                $this->com = new Cola_Com();
                return $this->com;

            case 'request':
                $this->request = new Cola_Request();
                return $this->request;

            case 'response':
                $this->response = new Cola_Response();
                return $this->response;

            default:
                throw new Cola_Exception('Undefined property: ' . get_class($this) . '::' . $key);
        }
    }
}
