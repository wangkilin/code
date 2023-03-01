<?php
/*
+--------------------------------------------------------------------------
|   WeCenter [#RELEASE_VERSION#]
|   ========================================
|   by WeCenter Software
|   © 2011 - 2014 WeCenter. All Rights Reserved
|   http://www.wecenter.com
|   ========================================
|   Support: WeCenter@qq.com
|
+---------------------------------------------------------------------------
*/

if (!defined('iCodeBang_Com'))
{
    die;
}

class main extends Controller
{
    public function get_access_rule()
    {
        $rule_action['rule_type'] = 'black';

        return $rule_action;
    }

    public function setup()
    {
        HTTP::setHeaderNoCache();
    }

    public function run_action()
    {
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');             // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: no-cache, must-revalidate');           // HTTP/1.1
        header('Pragma: no-cache');                                   // HTTP/1.0

        @set_time_limit(0);
        // @todo 增加了服务器负载。 待优化


        // 匿名访问， 限制ip访问次数
        $ip_address = fetch_ip();
        $cache_key = str_replace(array('.',':'), '_',$ip_address . $_SERVER['HTTP_HOST']) . 'website_allow_visit_page_number';
        if ($visitPageNumber = Application::cache()->get($cache_key) ) {
            $visitPageNumber--;
        } else {
            $visitPageNumber = 1;
        }
        Application::cache()->set($cache_key, $visitPageNumber, get_setting('cache_level_high'));

        // 匿名访问的网站攻击, 访问同一个页面次数
        $cache_key = md5($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REQUEST_URI']. $_SERVER['HTTP_HOST']) . '_website_allow_visit_page_number';
        if ($visitPageNumberUserUriAgent = Application::cache()->get($cache_key) ) {
            $visitPageNumberUserUriAgent--;
        } else {
            $visitPageNumberUserUriAgent = 1;
        }
        Application::cache()->set($cache_key, $visitPageNumberUserUriAgent, get_setting('cache_level_low'));

        // 匿名访问的网站攻击. 同一个浏览器，每天访问次数
        $cache_key = md5($_SERVER['HTTP_USER_AGENT']. $_SERVER['HTTP_HOST']) . '_website_allow_visit_page_number';
        if ($visitPageNumberUserAgent = Application::cache()->get($cache_key) ) {
            $visitPageNumberUserAgent--;
        } else {
            $visitPageNumberUserAgent = 1;
        }
        Application::cache()->set($cache_key, $visitPageNumberUserAgent, get_setting('cache_level_low'));

        return;

        if ($call_actions = $this->model('crond')->start())
        {
            foreach ($call_actions AS $call_action)
            {
                if ($plugins = Application::plugins()->parse('crond', 'main', $call_action))
                {
                    foreach ($plugins AS $plugin_file)
                    {
                        include($plugin_file);
                    }
                }

                $call_function = $call_action;

                $this->model('crond')->$call_function();
            }
        }

        if (Application::config()->get('system')->debug)
        {
            View::output('global/debuger.php');
        }
    }
}
