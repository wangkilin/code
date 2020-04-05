<?php
isset($config) OR $config = array();

$config['metal'] = array(
    'url_prefix'    => 'https://hq.smm.cn',
);


defined('ROOT_PATH') OR define('ROOT_PATH', realpath(dirname(__DIR__)) . DIRECTORY_SEPARATOR);

// 配置文件路径
define('CONF_PATH', ROOT_PATH . 'config' . DIRECTORY_SEPARATOR);

return $config;
