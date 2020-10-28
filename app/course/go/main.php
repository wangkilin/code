<?php
class main extends BaseController
{
    public function get_access_rule()
    {
        $rule_action['rule_type'] = 'white';

        if ($this->user_info['permission']['visit_question'] AND $this->user_info['permission']['visit_site'])
        {
            $rule_action['actions'][] = 'square';
            $rule_action['actions'][] = 'index';
        }

        return $rule_action;
    }

    public function color_action ()
    {
        echo 'go/color';
    }

}
