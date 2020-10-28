<?php
class main extends BaseController
{
    public function get_access_rule()
    {
        $rule_action['rule_type'] = "white"; //'black'黑名单,黑名单中的检查  'white'白名单,白名单以外的检查

        if ($this->user_info['permission']['visit_explore'] AND $this->user_info['permission']['visit_site'])
        {
            $rule_action['actions'][] = 'index';
        }

        return $rule_action;
    }

    public function setup()
    {
        // 引入系统 CSS 文件
        View::import_css(array(
            'admin/css/common.css',
        ));
    }

    public function index_action()
    {
        echo 'HELLO';
        EXIT;
    }

    /**
     *
     */
    public function index_square_action ()
    {
        HTTP::redirect('/lugutong/index/');
    }
}

/* EOF */
