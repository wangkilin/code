<?php
class main extends BaseController
{
    public function get_access_rule()
    {
        $rule_action['rule_type'] = "white"; //'black'黑名单,黑名单中的检查  'white'白名单,白名单以外的检查
        $rule_action['actions'][] = 'index';

        return $rule_action;
    }

    public function setup()
    {
    }

    public function index_action()
    {
        View::output('index');
    }

    /**
     *
     */
    public function index_square_action ()
    {
        View::output('index');
    }
}

/* EOF */
