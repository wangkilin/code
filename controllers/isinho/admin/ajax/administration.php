<?php
/**
 * +-------------------------------------------+
 * |   iCodeBang CMS [#RELEASE_VERSION#]       |
 * |   by iCodeBang.com Team                   |
 * |   © iCodeBang.com. All Rights Reserved    |
 * |   ------------------------------------    |
 * |   Support: icodebang@126.com              |
 * |   WebSite: http://www.icodebang.com       |
 * +-------------------------------------------+
 */

defined('iCodeBang_Com') OR die('Access denied!');
define('IN_AJAX', TRUE);

class administration extends SinhoBaseController
{

    /**
     * 根据日期获取请假数据
     */
    public function get_ask_leave_action ()
    {
        $this->checkPermission(self::IS_SINHO_ADMIN);
        if (! $_POST['start_date'] || !$_POST['end_date']) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t("参数传递错误")));
        }
        $userIds = isset($_POST['user_id']) ? $_POST['user_id'] : array();
        $itemList = $this->model('sinhoWorkload')->getAskLeaveByDate($_POST['start_date'], $_POST['end_date'], $userIds);

        H::ajax_json_output(Application::RSM($itemList, 1));
    }

    /**
     * 新建编辑书稿
     */
    public function ask_leave_action()
    {
        $this->checkPermission(self::IS_SINHO_ADMIN);

        $total = count($_POST['leave_type']);
        $scope = array();
        for($i=0; $i<$total; $i++) {
            if (!$_POST['leave_type'][$i] || !$_POST['leave_start_time'][$i] || !$_POST['leave_end_time'][$i]
              || !$_POST['leave_period'][$i] || !$_POST['user_id']) {

                H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t("第 ".($i+1)." 条请假信息不完整")));
            }

            if (strtotime($_POST['leave_start_time'][$i]) >= strtotime($_POST['leave_end_time'][$i])) {
                H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t("第 ".($i+1)." 条请假信息起止时间有误")));
            }

            isset($_POST['__leave_start_time']) OR $_POST['__leave_start_time'] = array();
            isset($_POST['__leave_end_time']) OR $_POST['__leave_end_time'] = array();
            $_POST['__leave_start_time'][$i] = strtotime($_POST['leave_start_time'][$i]);
            $_POST['__leave_end_time'][$i] = strtotime($_POST['leave_end_time'][$i]);
            $max = max($_POST['__leave_start_time'][$i], $_POST['__leave_end_time'][$i]);
            $min = min($_POST['__leave_start_time'][$i], $_POST['__leave_end_time'][$i]);
            $_POST['__leave_start_time'][$i] = $min;
            $_POST['__leave_end_time'][$i] = $max;
            // 查看请求的数据内部是否有重叠时间
            foreach ($scope as $_itemInfo) {
                if($min>=$_itemInfo[1] || $max<=$_itemInfo[0]) { // 请假时间段，不能在已有时间段范围内
                    continue;
                } else {
                    H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t("$min>{$_itemInfo[1]} || $max<{$_itemInfo[0]}第 ".($i+1)." 条请假时间有误，存在重叠情况")));
                    break;
                }
            }
            $scope[] = array($min, $max);
            // 查看请求的数据是否和数据库内有重复数据
            $itemList = $this->model('sinhoWorkload')->getAskLeaveByDate(date('Y-m-d H:i:s', $min+1), date('Y-m-d H:i:s', $max-1), $_POST['user_id']);
            foreach ($itemList as $_key => $_itemInfo) { //
                if (in_array($_itemInfo['id'], $_POST['id']) ) {
                    unset($itemList[$_key]);
                }
            }
            if (! $itemList) { // 没有重叠数据
                continue;
            }
            if ( count($itemList)>1 || $itemList[0]['id']!=$_POST['id'][$i]) { // 有超过一个数据记录，或者不是更新数据记录， 说明有重叠数据
                H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t("第 ".($i+1)." 条请假信息时间有冲突，存在重叠情况")));
                break;
            }
        }

        for($i=0; $i<$total; $i++) {
            $set = array(
                'user_id'           => $_POST['user_id'],
                'leave_type'        => $_POST['leave_type'][$i],
                'leave_start_time'  => $_POST['__leave_start_time'][$i],
                'leave_end_time'    => $_POST['__leave_end_time'][$i],
                'leave_period'      => $_POST['leave_period'][$i],
                'status'            => 1,
                'apply_time'        => time(),
                'remarks'           => strval($_POST['remarks'][$i]),
            );
            if ($_POST['id'][$i]) { // 更新请假
                $_id = intval($_POST['id'][$i]);
                $this->model('sinhoWorkload')->delete(sinhoWorkloadModel::ASK_LEAVE_DATE_TABLE, 'ask_leave_id=' . $_id);
                $this->model('sinhoWorkload')->update(sinhoWorkloadModel::ASK_LEAVE_TABLE, $set, 'id = ' .  $_id);
            } else { // 新请假内容
                $_id = $this->model('sinhoWorkload')->insert(sinhoWorkloadModel::ASK_LEAVE_TABLE, $set);
            }

            $j = 0;
            $startDate = date('Y-m-d', $_POST['__leave_start_time'][$i]);
            $endTime   = $_POST['__leave_end_time'][$i];
            while (strtotime("$startDate+{$j}day")<$endTime) {
                $set2 = array(
                    'ask_leave_id'          => $_id,
                    'belong_year'           => date('Y', strtotime("$startDate+{$j}day")),
                    'belong_month'          => date('m', strtotime("$startDate+{$j}day")),
                    'belong_day'            => date('d', strtotime("$startDate+{$j}day")),
                );
                $this->model('sinhoWorkload')->insert(sinhoWorkloadModel::ASK_LEAVE_DATE_TABLE, $set2);
                $j++;
            }
        }

        if ($_POST['leave_date']) {
            $returnMsg = $this->model('sinhoWorkload')->getAskLeaveByDate(date("Y-m-01", strtotime($_POST['leave_date'])), date("Y-m-t", strtotime($_POST['leave_date'])) );
        } else {
            $returnMsg = array('url' => get_js_url('/admin/administration/ask_leave/'));
        }
        H::ajax_json_output(Application::RSM($returnMsg, 1, Application::lang()->_t('请假信息保存成功')));
    }
    /**
     * 假期设置
     */
    public function set_holiday_action ()
    {
        $this->checkPermission(self::IS_SINHO_ADMIN);
        if (! $_POST['year'] || !$_POST['days']) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t("参数传递错误")));
        }
        $monthlyDays = array();
        if (isset($_POST['month'])) {
            $month = intval($_POST['month']);
            $monthlyDays = array($month => array());
        } else {
            $monthlyDays = array(
                1 => array(),
                2 => array(),
                3 => array(),
                4 => array(),
                5 => array(),
                6 => array(),
                7 => array(),
                8 => array(),
                9 => array(),
                10=> array(),
                11=> array(),
                12=> array(),
            );
        }
        foreach ($_POST['days'] as $_item) {
            if($_POST['year']!=substr($_item, 0, 4)) {
                continue;
            }
            $month = intval(substr($_item, 4, 2));
            isset($monthlyDays[$month]) AND $monthlyDays[$month][] = $_item;
        }

        $year = intval($_POST['year']);
        $scheduleInfo = $this->model('sinhoWorkload')->fetch_row(sinhoWorkloadModel::SCHEDULE_TABLE, 'belong_year = ' . $year);
        $set = array('belong_year' => $year);
        foreach ($monthlyDays as $_month => $_days) {
            $set['month_' . $_month] = json_encode($_days);
        }
        if ($scheduleInfo) {
            $this->model('sinhoWorkload')->update(sinhoWorkloadModel::SCHEDULE_TABLE, $set, 'belong_year = ' . $year);
        } else {
            $this->model('sinhoWorkload')->insert(sinhoWorkloadModel::SCHEDULE_TABLE, $set);
        }

        H::ajax_json_output(Application::RSM(null, 1, Application::lang()->_t('作息安排保存成功')));
    }

    /**
     * 保存编辑信息
     */
    public function editor_edit_action ()
    {
        $this->checkPermission(self::IS_SINHO_ADMIN);

        if (! $_POST['group_id'] || !$_POST['id']) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t("参数传递错误")));
        }

        if (! ($userInfo = $this->model('account')->getUserById($_POST['id']) ) ) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t("参数无效") ) );
        }
        if (! ($groupInfo = $this->model('account')->get_user_group_by_id($_POST['group_id']) ) ) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t("参数无效") ) );
        }

        $this->model('account')->setAccountInfos(array('group_id'=>$_POST['group_id']), $_POST['id']);
        if (! $_POST['more_subject']) {
            $_POST['more_subject'] = array();
        }
        if ($this->model()->fetch_row('users_attribute', 'uid='.$userInfo['uid'] . ' AND attr_key ="sinho_more_subject"') ) {
            $this->model()->update('users_attribute',
                                   array('attr_value'=>json_encode($_POST['more_subject'])),
                                   'uid='.$userInfo['uid'] . ' AND attr_key ="sinho_more_subject"'
                            );
        } else {
            $this->model()->insert('users_attribute',
                                    array(
                                        'attr_value'            => json_encode($_POST['more_subject']),
                                        'uid'                   => $userInfo['uid'],
                                        'attr_key'              => "sinho_more_subject",
                                        'remark'                => '设置的责编的副科。在稿子分配时，根据主副科优先选择对应的责编'
                                    )
            );
        }

        H::ajax_json_output(Application::RSM(array('url' => get_js_url('/admin/administration/editor/')), 1, Application::lang()->_t('保存成功')));
    }
}

/* EOF */
