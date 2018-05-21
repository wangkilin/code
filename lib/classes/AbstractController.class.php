<?php
class AbstractController extends CI_Controller
{
    /**
     * Reference to the CI singleton
     *
     * @var	object
     */
    protected static $instance;

    protected $_assignVars = array();

    public function __construct()
    {
        parent::__construct();
    }

    public function setTplEngine ($tplEngineName)
    {

    }

    public function display($tplFile='')
    {

    }

    public function fetch ($tplFile='')
    {

    }

    public function assign ($var, $value='')
    {

    }


}