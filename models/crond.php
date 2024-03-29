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

class crondModel extends Model
{
    public function start()
    {
        if (!Application::cache()->get('crond_timer_half_minute'))
        {
            $call_actions[] = 'half_minute';

            Application::cache()->set('crond_timer_half_minute', time(), 30, 'crond');
        }

        if (!Application::cache()->get('crond_timer_minute'))
        {
            $call_actions[] = 'minute';

            Application::cache()->set('crond_timer_minute', time(), 60, 'crond');
        }

        if (!Application::cache()->get('crond_timer_five_minutes'))
        {
            $call_actions[] = 'five_minutes';

            Application::cache()->set('crond_timer_five_minutes', time(), 300, 'crond');
        }

        if (!Application::cache()->get('crond_timer_ten_minutes'))
        {
            $call_actions[] = 'ten_minutes';

            Application::cache()->set('crond_timer_ten_minutes', time(), 600, 'crond');
        }

        if (gmdate('YW', Application::cache()->get('crond_timer_week')) != gmdate('YW', time()))
        {
            $call_actions[] = 'week';

            Application::cache()->set('crond_timer_week', time(), 259200, 'crond');
        }
        else if (gmdate('Y-m-d', Application::cache()->get('crond_timer_day')) != gmdate('Y-m-d', time()))
        {
            $call_actions[] = 'day';

            Application::cache()->set('crond_timer_day', time(), 86400, 'crond');
        }
        else if (!Application::cache()->get('crond_timer_hour'))
        {
            $call_actions[] = 'hour';

            Application::cache()->set('crond_timer_hour', time(), 3600, 'crond');
        }
        else if (!Application::cache()->get('crond_timer_half_hour'))
        {
            $call_actions[] = 'half_hour';

            Application::cache()->set('crond_timer_half_hour', time(), 1800, 'crond');
        }

        return $call_actions;
    }

    // 每半分钟执行
    public function half_minute()
    {
        $this->model('edm')->run_task();
    }

    // 每分钟执行
    public function minute()
    {
        @unlink(TEMP_PATH . 'plugins_table.php');
        @unlink(TEMP_PATH . 'plugins_model.php');

        if ($this->model('reputation')->calculate(Application::cache()->get('reputation_calculate_start'), 100))
        {
            Application::cache()->set('reputation_calculate_start', (intval(Application::cache()->get('reputation_calculate_start')) + 100), 604800);
        }
        else
        {
            Application::cache()->set('reputation_calculate_start', 0, 604800);
        }

        $this->model('online')->delete_expire_users();

        if (check_extension_package('project'))
        {
            $expire_orders = $this->fetch_all('product_order', 'add_time < ' . (time() - 600) . ' AND payment_time = 0 AND cancel_time = 0 AND refund_time = 0');

            if ($expire_orders)
            {
                foreach ($expire_orders AS $order_info)
                {
                    $this->model('project')->cancel_project_order_by_id($order_info['id']);
                }
            }
        }

        $this->model('email')->send_mail_queue(120);
    }

    // 每五分钟执行
    public function five_minutes()
    {
        if (check_extension_package('ticket'))
        {
            if (get_setting('weibo_msg_enabled') == 'ticket')
            {
                $this->model('ticket')->save_weibo_msg_to_ticket_crond();
            }

            $receiving_email_global_config = get_setting('receiving_email_global_config');

            if ($receiving_email_global_config['enabled'] == 'ticket')
            {
                $this->model('ticket')->save_received_email_to_ticket_crond();
            }
        }

        $this->model('admin')->notifications_crond();

        $this->model('active')->send_valid_email_crond();
    }

    // 每十分钟执行
    public function ten_minutes()
    {
        if (get_setting('weibo_msg_enabled') == 'Y')
        {
            $this->model('openid_weibo_weibo')->get_msg_from_sina_crond();
        }
    }

    // 每半小时执行
    public function half_hour()
    {
        $this->model('search_fulltext')->clean_cache();

        if (check_extension_package('project'))
        {
            $this->model('project')->send_project_open_close_notify();
        }

        $receiving_email_global_config = get_setting('receiving_email_global_config');

        if ($receiving_email_global_config['enabled'] == 'Y')
        {
            $this->model('edm')->receive_email_crond();
        }
    }

    // 每小时执行
    public function hour()
    {
        $this->model('system')->clean_session();
    }

    // 每日时执行
    public function day()
    {
        $this->model('answer')->calc_best_answer();
        $this->model('question')->auto_lock_question();
        $this->model('active')->clean_expire();

        if ((!get_setting('db_engine') OR get_setting('db_engine') == 'MyISAM') AND !defined('IN_SAE'))
        {
            $this->query('OPTIMIZE TABLE `' . get_table('sessions') . '`');
            $this->query('OPTIMIZE TABLE `' . get_table('search_cache') . '`');
            $this->query('REPAIR TABLE `' . get_table('sessions') . '`');
        }
    }

    // 每周执行
    public function week()
    {
        $this->model('notify')->clean_mark_read_notifications(2592000);
        $this->model('system')->clean_break_attach();
        $this->model('email')->mail_queue_error_clean();
    }
}
