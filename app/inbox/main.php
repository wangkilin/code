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
        $rule_action['rule_type'] = "white"; //黑名单,黑名单中的检查  'white'白名单,白名单以外的检查

        return $rule_action;
    }

    public function setup()
    {
        $this->crumb(Application::lang()->_t('私信'), '/inbox/');
    }


    public function index_action()
    {
        $this->model('account')->update_inbox_unread($this->user_id);

        if ($inbox_dialog = $this->model('message')->get_inbox_message($_GET['page'], get_setting('contents_per_page'), $this->user_id))
        {
            $inbox_total_rows = $this->model('message')->found_rows();

            foreach ($inbox_dialog as $key => $val)
            {
                $dialog_ids[] = $val['id'];

                if ($this->user_id == $val['recipient_uid'])
                {
                    $inbox_dialog_uids[] = $val['sender_uid'];
                }
                else
                {
                    $inbox_dialog_uids[] = $val['recipient_uid'];
                }
            }
        }

        if ($inbox_dialog_uids)
        {
            if ($users_info_query = $this->model('account')->getUsersByIds($inbox_dialog_uids))
            {
                foreach ($users_info_query as $user)
                {
                    $users_info[$user['uid']] = $user;
                }
            }
        }

        if ($dialog_ids)
        {
            $last_message = $this->model('message')->get_last_messages($dialog_ids);
        }

        if ($inbox_dialog)
        {
            foreach ($inbox_dialog as $key => $value)
            {
                if ($value['recipient_uid'] == $this->user_id AND $value['recipient_count']) // 当前处于接收用户
                {
                    $data[$key]['user_name'] = $users_info[$value['sender_uid']]['user_name'];
                    $data[$key]['url_token'] = $users_info[$value['sender_uid']]['url_token'];

                    $data[$key]['unread'] = $value['recipient_unread'];
                    $data[$key]['count'] = $value['recipient_count'];

                    $data[$key]['uid'] = $value['sender_uid'];
                }
                else if ($value['sender_uid'] == $this->user_id AND $value['sender_count']) // 当前处于发送用户
                {
                    $data[$key]['user_name'] = $users_info[$value['recipient_uid']]['user_name'];
                    $data[$key]['url_token'] = $users_info[$value['recipient_uid']]['url_token'];

                    $data[$key]['unread'] = $value['sender_unread'];
                    $data[$key]['count'] = $value['sender_count'];
                    $data[$key]['uid'] = $value['recipient_uid'];
                }

                $data[$key]['last_message'] = $last_message[$value['id']];
                $data[$key]['update_time'] = $value['update_time'];
                $data[$key]['id'] = $value['id'];
            }
        }

        View::assign('list', $data);

        View::assign('pagination', Application::pagination()->initialize(array(
            'base_url' => get_js_url('/inbox/'),
            'total_rows' => $inbox_total_rows,
            'per_page' => get_setting('contents_per_page')
        ))->create_links());

        View::output('inbox/index');
    }

    public function delete_dialog_action()
    {
        $this->model('message')->delete_dialog($_GET['dialog_id'], $this->user_id);

        if ($_SERVER['HTTP_REFERER'])
        {
            HTTP::redirect($_SERVER['HTTP_REFERER']);
        }
        else
        {
            HTTP::redirect('/inbox/');
        }
    }

    public function read_action()
    {
        if (!$dialog = $this->model('message')->get_dialog_by_id($_GET['id']))
        {
            H::redirect_msg(Application::lang()->_t('指定的站内信不存在'), '/inbox/');
        }

        if ($dialog['recipient_uid'] != $this->user_id AND $dialog['sender_uid'] != $this->user_id)
        {
            H::redirect_msg(Application::lang()->_t('指定的站内信不存在'), '/inbox/');
        }

        $this->model('message')->set_message_read($_GET['id'], $this->user_id);

        if ($list = $this->model('message')->get_message_by_dialog_id($_GET['id']))
        {
            if ($dialog['sender_uid'] != $this->user_id)
            {
                $recipient_user = $this->model('account')->getUserById($dialog['sender_uid']);
            }
            else
            {
                $recipient_user = $this->model('account')->getUserById($dialog['recipient_uid']);
            }

            foreach ($list as $key => $val)
            {
                if ($dialog['sender_uid'] == $this->user_id AND $val['sender_remove'])
                {
                    unset($list[$key]);
                }
                else if ($dialog['sender_uid'] != $this->user_id AND $val['recipient_remove'])
                {
                    unset($list[$key]);
                }
                else
                {
                    $list[$key]['message'] = FORMAT::parse_links($val['message']);

                    $list[$key]['user_name'] = $recipient_user['user_name'];
                    $list[$key]['url_token'] = $recipient_user['url_token'];
                }
            }
        }

        $this->crumb(Application::lang()->_t('私信对话') . ': ' . $recipient_user['user_name'], '/inbox/read/' . intval($_GET['id']));

        View::assign('list', $list);
        View::assign('recipient_user', $recipient_user);

        View::output('inbox/read');
    }
}
