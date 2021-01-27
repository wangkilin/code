<?php
defined('iCodeBang_Com') OR die('Access denied!');

class adminModel extends Model
{
    /**
     * 获取菜单列表， 根据传入id，找到对应选定菜单
     * @param string    $select_id          选定的菜单id
     * @param string    $menuConfigName     对应的菜单配置文件
     *
     * @return array 格式化后的菜单数组
     */
    public function fetch_menu_list($select_id=null, $menuConfigName='admin_menu')
    {
        $_select_id = $select_id;
        // 传入空id， 将 controller/action 作为id
        if (null===$select_id) {
            $controller = loadClass('core_uri')->controller;
            $action     = loadClass('core_uri')->action;
            $_select_id = $controller . '/' .$action;
            if (''!=loadClass('core_uri')->app) {
                $_select_id = loadClass('core_uri')->app . '/' . $_select_id;
            }
        }
        // 获取菜单配置信息
        $admin_menu = (array)Application::config()->get($menuConfigName);
        $isFound = false;
        $tmpList = array();

        foreach($admin_menu as $m_id => $menu) {
            if (! $menu['children']) {
                if ($menu['id']==$_select_id || $menu['url'] == $_select_id || $menu['url'] == $_select_id.'/') {
                    $admin_menu[$m_id]['select'] = true;
                    $isFound = true;
                }
                continue;
            }
            foreach($menu['children'] as $c_id => $c_menu) {
                if ($_select_id == $c_menu['id']) {
                    $admin_menu[$m_id]['children'][$c_id]['select'] = true;
                    $admin_menu[$m_id]['select'] = true;
                    $isFound = true;

                    break 2;
                }
                if (null===$select_id) {
                // 没有传递固定菜单id， 根据controller 和action来定位选中项
                    $parseInfo = explode('/', $c_menu['id'], 2);
                    isset($tmpList[$parseInfo[0]]) OR $tmpList[$parseInfo[0]]=array();
                    isset($parseInfo[1]) OR $parseInfo[1] = 0;
                    $tmpList[$parseInfo[0]][$parseInfo[1]] = array($m_id, $c_id);
                }
            }
        }

        // 没有找到对应选中菜单， 查看是否有对应控制器的菜单
        if (! $isFound && isset($controller, $tmpList[$controller])) {
            if (isset($tmpList[$controller][0])) {
                $m_id = $tmpList[$controller][0][0];
                $c_id = $tmpList[$controller][0][1];
                $admin_menu[$m_id]['children'][$c_id]['select'] = true;
                $admin_menu[$m_id]['select'] = true;
            }
        }

        return $admin_menu;
    }

    public function set_admin_login($uid)
    {
        Application::session()->admin_login = Application::crypt()->encode(json_encode(array(
            'uid' => $uid,
            'UA' => $_SERVER['HTTP_USER_AGENT'],
            'ip' => fetch_ip()
        )));
    }

    public function admin_logout()
    {
        if (isset(Application::session()->admin_login))
        {
            unset(Application::session()->admin_login);
        }
    }

    public function notifications_crond()
    {
        //$last_version = json_decode(curl_get_contents('http://wenda.wecenter.com/api/version_check.php'), true);
        $last_version = array('version'=>0, 'build_day'=>'');
        $admin_notifications = Application::cache()->get('admin_notifications');

        if (!$admin_notifications)
        {
            $admin_notifications = get_setting('admin_notifications');
        }

        $admin_notifications = array(
                                // 内容审核
                                'answer_approval' => $this->count('approval', 'type = "answer"'),
                                'question_approval' => $this->count('approval', 'type = "question"'),
                                'article_approval' => $this->count('approval', 'type = "article"'),
                                'article_comment_approval' => $this->count('approval', 'type = "article_comment"'),
                                'unverified_modify_count' => $this->count('question', 'unverified_modify_count <> 0'),

                                // 用户举报
                                'user_report' => $this->count('report', 'status = 0'),

                                // 注册审核
                                'register_approval' => $this->count('users', 'group_id = 3'),

                                // 认证审核
                                'verify_approval' => $this->count('verify_apply', 'status = 0'),

                                // 程序更新
                                'last_version' => array(
                                                        'version' => $last_version['version'],
                                                        'build_day' => $last_version['build_day']
                                                    ),

                                // 新浪微博 Access Token 更新
                                'sina_users' => $admin_notifications['sina_users'],

                                // 邮件导入失败
                                'receive_email_error' => $admin_notifications['receive_email_error']
                            );

        if (get_setting('weibo_msg_enabled') == 'question')
        {
            $admin_notifications['weibo_msg_approval'] = $this->count('weibo_msg', 'question_id IS NULL AND ticket_id IS NULL');
        }

        $receiving_email_global_config = get_setting('receiving_email_global_config');

        if ($receiving_email_global_config['enabled'] == 'question')
        {
            $admin_notifications['received_email_approval'] = $this->count('received_email', 'question_id IS NULL AND ticket_id IS NUL');
        }

        Application::cache()->set('admin_notifications', $admin_notifications, 1800);

        return $this->model('setting')->set_vars(array('admin_notifications' => $admin_notifications));
    }

    public function get_notifications_texts()
    {
        $notifications = Application::cache()->get('admin_notifications');

        if (!$notifications)
        {
            $notifications = get_setting('admin_notifications');
        }

        if (!$notifications)
        {
            return false;
        }

        if ($notifications['question_approval'])
        {
            $notifications_texts[] = array(
                                            'url' => 'admin/approval/list/',
                                            'text' => Application::lang()->_t('有 %s 个问题待审核', $notifications['question_approval'])
                                        );
        }

        if ($notifications['unverified_modify_count'])
        {
            $notifications_texts[] = array(
                                            'url' => 'admin/approval/list/type-unverified_modify',
                                            'text' => Application::lang()->_t('有 %s 个问题修改待审核', $notifications['unverified_modify_count'])
                                        );
        }

        if ($notifications['answer_approval'])
        {
            $notifications_texts[] = array(
                                            'url' => 'admin/approval/list/type-answer',
                                            'text' => Application::lang()->_t('有 %s 个回答待审核', $notifications['answer_approval'])
                                        );
        }

        if ($notifications['article_approval'])
        {
            $notifications_texts[] = array(
                                            'url' => 'admin/approval/list/type-article',
                                            'text' => Application::lang()->_t('有 %s 篇文章待审核', $notifications['article_approval'])
                                        );
        }

        if ($notifications['article_comment_approval'])
        {
            $notifications_texts[] = array(
                                            'url' => 'admin/approval/list/type-article_comment',
                                            'text' => Application::lang()->_t('有 %s 个文章评论待审核', $notifications['article_comment_approval'])
                                        );
        }

        if ($notifications['weibo_msg_approval'])
        {
            $notifications_texts[] = array(
                                            'url' => 'admin/approval/list/type-weibo_msg',
                                            'text' => Application::lang()->_t('有 %s 个微博消息待审核', $notifications['weibo_msg_approval'])
                                        );
        }

        if ($notifications['received_email_approval'])
        {
            $notifications_texts[] = array(
                                            'url' => 'admin/approval/list/type-received_email',
                                            'text' => Application::lang()->_t('有 %s 个邮件咨询待审核', $notifications['received_email_approval'])
                                        );
        }

        if ($notifications['user_report'])
        {
            $notifications_texts[] = array(
                                            'url' => 'admin/question/report_list/',
                                            'text' => Application::lang()->_t('有 %s 个用户举报待查看', $notifications['user_report'])
                                        );
        }

        if (get_setting('register_valid_type') == 'approval' AND $notifications['register_approval'])
        {
            $notifications_texts[] = array(
                                            'url' => 'admin/user/register_approval_list/',
                                            'text' => Application::lang()->_t('有 %s 个新用户待审核', $notifications['register_approval'])
                                        );
        }

        if ($notifications['verify_approval'])
        {
            $notifications_texts[] = array(
                                            'url' => 'admin/user/verify_approval_list/',
                                            'text' => Application::lang()->_t('有 %s 个认证申请待审核', $notifications['verify_approval'])
                                        );
        }

        if ($notifications['last_version']['build_day'] > G_VERSION_BUILD)
        {
            $notifications_texts[] = array(
                                            'url' => 'http://www.wecenter.com/downloads/',
                                            'text' => Application::lang()->_t('程序需要更新，最新版本为 %s', $notifications['last_version']['version'])
                                        );
        }

        if (get_setting('weibo_msg_enabled') == 'Y' AND $notifications['sina_users'])
        {
            foreach ($notifications['sina_users'] AS $sina_user)
            {
                $notifications_texts[] = array(
                                                'url' => 'admin/weibo/msg/',
                                                'text' => Application::lang()->_t('用户 %s 的微博账号需要更新 Access Token，请重新授权', $sina_user['user_name'])
                                            );
            }
        }

        $receiving_email_global_config = get_setting('receiving_email_global_config');

        if ($receiving_email_global_config['enabled'] == 'Y' AND $notifications['receive_email_error'])
        {
            foreach ($notifications['receive_email_error'] AS $error_msg)
            {
                $notifications_texts[] = array(
                                                'url' => 'admin/edm/receiving/id-' . $error_msg['id'],
                                                'text' => Application::lang()->_t('邮件导入失败，错误为 %s，请重新配置', $error_msg['msg'])
                                            );
            }
        }

        return $notifications_texts;
    }
}
