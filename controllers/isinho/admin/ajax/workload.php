<?php
/**
+-------------------------------------------+
|   iCodeBang CMS [#RELEASE_VERSION#]       |
|   by iCodeBang.com Team                   |
|   © iCodeBang.com. All Rights Reserved    |
|   ------------------------------------    |
|   Support: icodebang@126.com              |
|   WebSite: http://www.icodebang.com       |
+-------------------------------------------+
*/

defined('iCodeBang_Com') OR die('Access denied!');
define('IN_AJAX', TRUE);

class workload extends SinhoBaseController
{
    public function setup()
    {
        HTTP::setHeaderNoCache();
    }

    /**
     * 保存工作量
     */
    public function fill_action()
    {
        $this->checkPermission(self::IS_SINHO_FILL_WORKLOAD);

        if (!$_POST['id'] || !$_POST['book_id']) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('请输入参数')));
        }
        $itemInfo = $this->model('sinhoWorkload')->fetch_row(sinhoWorkloadModel::WORKLOAD_TABLE, 'id='.intval($_POST['id']).' AND book_id='.intval($_POST['book_id']));
        if (! $itemInfo || $itemInfo['user_id'] !=$this->user_id) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('没找到对应书稿工足量')));
        }

        // 计算total_chars
        if (isset($itemInfo['total_chars'])) {// 计算应付金额
            $itemInfo['payable_amount'] = round($itemInfo['total_chars'] * 2, 2);
        }
        // 保存填充的工作量内容
        $_POST['fill_time'] = time();
        Application::model('sinhoWorkload')->fillWorkload(intval($_POST['id']), $_POST);
        if (! $itemInfo['fill_time']) { // 第一次填充书稿工作量， 加入填充时间
            Application::model('sinhoWorkload')->update(sinhoWorkloadModel::WORKLOAD_TABLE, array('fill_time'=>time()), 'id = ' . intval($_POST['id']) );
        }

        H::ajax_json_output(Application::RSM(array('url' => get_js_url('/admin/fill_list/')), 1, Application::lang()->_t('工作量保存成功')));

    }

    /**
     * 分叉工作量
     */
    public function fill_more_action ()
    {
        $this->checkPermission(self::IS_SINHO_FILL_WORKLOAD);

        if (!$_POST['id']) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('操作错误')));
        }
        $itemInfo = $this->model('sinhoWorkload')->fetch_row(sinhoWorkloadModel::WORKLOAD_TABLE, 'id='.intval($_POST['id']));
        if (! $itemInfo || $itemInfo['user_id'] !=$this->user_id) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('参数错误')));
        }

        $set = array(
            'user_id'   => $this->user_id,
            'book_id'   => $itemInfo['book_id'],
            'status'    => sinhoWorkloadModel::STATUS_RECORDING,
            'is_branch' => 1,
            'add_time'  => time(),
        );

        $id = $this->model('sinhoWorkload')->insert(sinhoWorkloadModel::WORKLOAD_TABLE, $set);

        H::ajax_json_output(Application::RSM(array('url' => get_js_url('/admin/fill_list/')), 1, null ) );
    }

    /**
     * 加入核算工作量
     */
    public function queue_action()
    {
        $this->checkPermission(self::IS_SINHO_FILL_WORKLOAD);
        if (empty($_POST['id'])) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('操作方法错误')));
        }

        $itemInfo = $this->model('sinhoWorkload')->fetch_row(sinhoWorkloadModel::WORKLOAD_TABLE, 'id='.intval($_POST['id']));
        if (! $itemInfo || $itemInfo['user_id'] !=$this->user_id || ($itemInfo['status']!=sinhoWorkloadModel::STATUS_RECORDING)) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('参数错误')));
        }


        $this->model('sinhoWorkload')
             ->update(sinhoWorkloadModel::WORKLOAD_TABLE,
                array('status' => sinhoWorkloadModel::STATUS_VERIFYING),
                'id =' . intval($_POST['id']) . ' AND status = ' . sinhoWorkloadModel::STATUS_RECORDING
             );

        H::ajax_json_output(Application::RSM(null, 1, null));
    }

    /**
     * 撤回工作量的核算
     */
    public function rollback_action()
    {
        $this->checkPermission(self::IS_SINHO_VERIFY_WORKLOAD | self::IS_SINHO_FILL_WORKLOAD);
        if (empty($_POST['id'])) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('操作方法错误')));
        }

        $itemInfo = $this->model('sinhoWorkload')->fetch_row(sinhoWorkloadModel::WORKLOAD_TABLE, 'id='.intval($_POST['id']));
        if (! $itemInfo || $itemInfo['status']!=sinhoWorkloadModel::STATUS_VERIFYING) {
        // 没找到条目， 或者条目不是正在核算中的状态
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('参数错误')));
        }
        if (! $this->hasRolePermission(self::IS_SINHO_VERIFY_WORKLOAD) && $itemInfo['user_id']!=$this->user_id) {
        // 检查是否有核酸全部工作量权限。 如果没有，只能撤回自己的工作量状态
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('只能撤回自己的工作量核算')));

        }


        $this->model('sinhoWorkload')
             ->update(sinhoWorkloadModel::WORKLOAD_TABLE,
                array('status' => sinhoWorkloadModel::STATUS_RECORDING),
                'id =' . intval($_POST['id']) . ' AND status = ' . sinhoWorkloadModel::STATUS_VERIFYING
             );

        H::ajax_json_output(Application::RSM(null, 1, null));
    }

    /**
     * 删除工作量
     */
    public function remove_action()
    {
        $this->checkPermission(self::IS_SINHO_FILL_WORKLOAD);
        if (empty($_POST['id'])) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('操作方法错误')));
        }

        $itemInfo = $this->model('sinhoWorkload')->fetch_row(sinhoWorkloadModel::WORKLOAD_TABLE, 'id='.intval($_POST['id']));
        if (! $itemInfo || $itemInfo['user_id'] !=$this->user_id || $itemInfo['is_branch']!=1 || ($itemInfo['status']!=sinhoWorkloadModel::STATUS_RECORDING)) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('参数错误')));
        }


        $this->model('sinhoWorkload')
             ->update(sinhoWorkloadModel::WORKLOAD_TABLE,
                array('status' => sinhoWorkloadModel::STATUS_DELETE),
                'id =' . intval($_POST['id']) . ' AND status = ' . sinhoWorkloadModel::STATUS_RECORDING
             );
        //Application::model()->delete(sinhoWorkloadModel::WORKLOAD_TABLE, 'id=' . intval($_POST['id']) . ' AND is_branch=1 AND status = 0 AND user_id = ' . $this->user_id);

        H::ajax_json_output(Application::RSM(null, 1, null));
    }

    /**
     * 确认核算工作量
     */
    public function confirm_action ()
    {
        $this->checkPermission(self::IS_SINHO_VERIFY_WORKLOAD);

        if ($_POST['id'] && is_array($_POST['id'])) {
            $this->model('sinhoWorkload')
                 ->update(sinhoWorkloadModel::WORKLOAD_TABLE,
                        array(
                            'status'        => sinhoWorkloadModel::STATUS_VERIFIED,
                            'belong_month'  => date('Ym', strtotime('-1month'))
                        ),
                        'id IN(' .join(',', $_POST['id']) . ') AND status = ' . sinhoWorkloadModel::STATUS_VERIFYING
                    );
            $this->model('sinhoWorkload')
                    ->update(sinhoWorkloadModel::QUARLITY_TABLE,
                        array(
                            'status'        => sinhoWorkloadModel::STATUS_VERIFIED,
                            'belong_month'  => date('Ym', strtotime('-1month'))
                        ),
                        'status = ' . sinhoWorkloadModel::STATUS_VERIFYING
                    );
            H::ajax_json_output(Application::RSM(array('url' => get_js_url('/admin/verify_list/')), 1, Application::lang()->_t('工作量核算已保存')));
        } else {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('参数错误')));
        }
    }

    /**
     * 将核算错误内容记录到对应的数据记录。 编辑在查看列表时， 标识出错误位置
     */
    public function mark_warning_action ()
    {
        $this->checkPermission(self::IS_SINHO_VERIFY_WORKLOAD);

        if ($_POST['params'] && is_array($_POST['params'])) {

            $this->model('sinhoWorkload')
                 ->update(sinhoWorkloadModel::WORKLOAD_TABLE,
                          array('verify_remark'=>''),
                          'status = ' . sinhoWorkloadModel::STATUS_VERIFYING
                   );
            foreach ($_POST['params'] as $_paramInfo) {
                $this->model('sinhoWorkload')
                     ->update(sinhoWorkloadModel::WORKLOAD_TABLE,
                            array('verify_remark'=>json_encode($_paramInfo['tds'])),
                            'id = ' . intval($_paramInfo['line'])
                        );
            }
            H::ajax_json_output(Application::RSM(array('url' => get_js_url('/admin/verify_list/')), 1, Application::lang()->_t('已将核算错误反馈！')));
        } else {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('参数错误')));
        }
    }

    /**
     * 根据起止月份，统计员工的工作量。 得到员工的工作量榜单
     */
    public function statistic_total_chars_action ()
    {
        $this->checkPermission(self::IS_SINHO_CHECK_WORKLOAD);

        if ($_POST['start_month']) {
            $_POST['start_month'] = str_replace('-', '', $_POST['start_month']);
        } else {
            $_POST['start_month'] = $this->model('sinhoWorkload')->min(sinhoWorkloadModel::WORKLOAD_TABLE, 'belong_month');
        }
        $_POST['start_month'] > 202001 OR $_POST['start_month'] = 202001;

        if ($_POST['end_month']) {
            $_POST['end_month'] = str_replace('-', '', $_POST['end_month']);
        } else {
            $_POST['end_month'] = $this->model('sinhoWorkload')->max(sinhoWorkloadModel::WORKLOAD_TABLE, 'belong_month');
        }
        $_POST['end_month']> 202001 OR $_POST['end_month'] = 202001;

        $start = $_POST['start_month'] >= $_POST['end_month'] ? $_POST['end_month'] : $_POST['start_month'];
        $end   = $_POST['start_month'] >= $_POST['end_month'] ? $_POST['start_month'] : $_POST['end_month'];

        $userList = $this->model('sinhoWorkload')->getUserList(null, 'uid DESC', PHP_INT_MAX);
        $userIds  = array_column($userList, 'uid');
        $userList = array_combine($userIds, $userList);
        // 获取每个人的工作量
        $quarlityStat = $this->model('sinhoWorkload')->getQuarlityStatByUserIds (array(), array('start'=>$start, 'end'=>$end));
        $quarlityStat = array_combine(array_column($quarlityStat,'user_id'), array_column($quarlityStat,'quarlity_num'));
        $workloadStatLastMonth = $this->model('sinhoWorkload')->getWorkloadStatByUserIds (array(), sinhoWorkloadModel::STATUS_VERIFIED, array('start'=>$start, 'end'=>$end));
        $totalCharsList = array_combine(array_column($workloadStatLastMonth,'user_id'), array_column($workloadStatLastMonth,'total_chars'));
        $totalCharsWithoutWeightListLastMonth = array_combine(array_column($workloadStatLastMonth,'user_id'), array_column($workloadStatLastMonth,'total_chars_without_weight'));
        $totalCharsWeightLt1ListLastMonth = array_combine(array_column($workloadStatLastMonth,'user_id'), array_column($workloadStatLastMonth,'total_chars_weight_lt_1'));
        arsort($totalCharsList);
        foreach ($totalCharsList as $_userId=> & $_item) {
            $_item = array(
                'name'                  => $userList[$_userId]['user_name'],
                'total'                 => $_item,
                'totalWithoutWeight'    => $totalCharsWithoutWeightListLastMonth[$_userId],
                'totalCharsWeightLt1'   => $totalCharsWeightLt1ListLastMonth[$_userId],
                'quarlityStat'          => $quarlityStat[$_userId],
            );
        }

        H::ajax_json_output(Application::RSM(array_values($totalCharsList)));
    }

    /**
     * 按照月度， 汇总责编的工作量
     */
    public function statistic_monthly_chars_action ()
    {
        $this->checkPermission(self::IS_SINHO_CHECK_WORKLOAD);

        $userList = $this->model('sinhoWorkload')->getUserList(null, 'uid DESC', PHP_INT_MAX);
        $userIds  = array_column($userList, 'uid');
        $userList = array_combine($userIds, array_column($userList, 'user_name') );

        $minMonth = $this->model('sinhoWorkload')->min(sinhoWorkloadModel::WORKLOAD_TABLE, 'belong_month');
        $maxMonth = $this->model('sinhoWorkload')->max(sinhoWorkloadModel::WORKLOAD_TABLE, 'belong_month');
        $minMonth > 0 OR $minMonth = date('Ym', strtotime('-1month'));
        $maxMonth > 0 OR $maxMonth = $minMonth;
        if ($_POST['start_month']) {
            $_POST['start_month'] = str_replace('-', '', $_POST['start_month']);
        } else {
            $_POST['start_month'] = $minMonth;
        }
        $_POST['start_month'] > $minMonth OR $_POST['start_month'] = $minMonth;

        if ($_POST['end_month']) {
            $_POST['end_month'] = str_replace('-', '', $_POST['end_month']);
        } else {
            $_POST['end_month'] = $maxMonth;
        }
        $_POST['end_month'] > $maxMonth AND $_POST['end_month'] = $maxMonth;

        $start = $_POST['start_month'] >= $_POST['end_month'] ? $_POST['end_month'] : $_POST['start_month'];
        $end   = $_POST['start_month'] >= $_POST['end_month'] ? $_POST['start_month'] : $_POST['end_month'];

        $itemList = array();
        $workloadUserIds = array();
        while ($start <= $end) {
            $itemList[$start] = $this->model('sinhoWorkload')->getWorkloadStatByUserIds (array(), sinhoWorkloadModel::STATUS_VERIFIED, $start);
            $workloadUserIds = array_merge($workloadUserIds, array_column($itemList[$start], 'user_id' ) ) ;
            $start = date('Ym', strtotime("{$start}01 +1month"));
        }
        $employeeWorkloadList = array();
        $employeeWorkloadList = array_fill_keys($workloadUserIds, array());
        if (isset($employeeWorkloadList[''])) {
            unset($employeeWorkloadList['']);
        }
        if (isset($employeeWorkloadList[0])) {
            unset($employeeWorkloadList[0]);
        }
        $allTotalChars = array();
        foreach ($itemList as $_month=>$_statList) {
            foreach ($employeeWorkloadList as $_userId=>$_v) {
                $employeeWorkloadList[$_userId][$_month] = 0;
            }

            foreach ($_statList as $_statInfo) {
                if (!$_statInfo['user_id']) {
                    continue;
                }
                $employeeWorkloadList[$_statInfo['user_id']][$_month] = $allTotalChars[] = $_statInfo['total_chars'];
            }
        }

        H::ajax_json_output(Application::RSM(array('stat'=>$employeeWorkloadList, 'employee'=>$userList)));
    }

    /**
     * 填充质量考核
     */
    public function fill_quarlity_action ()
    {
        $this->checkPermission(self::IS_SINHO_CHECK_WORKLOAD);

        if (empty($_POST['workload_id'])) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('参数错误')));
        }
        $itemInfo = Application::model('sinhoWorkload')->fetch_row(sinhoWorkloadModel::WORKLOAD_TABLE, 'id = ' . intval($_POST['workload_id']));
        if (! $itemInfo) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('参数错误，对应的工作量不存在')));
        }

        $data = array(
            'rate_num'     => $_POST['rate'],
            'good_or_bad'  => $_POST['good_or_bad'],
            'remarks'      => $_POST['remarks'],
            'add_date'     => date('Y-m-d H:i:s'),
            'workload_id'  => $_POST['workload_id'],
            'book_id'      => $itemInfo['book_id'],
            'user_id'      => $itemInfo['user_id'],
            'status'       => sinhoWorkloadModel::STATUS_VERIFYING,
        );
        $itemInfo = Application::model('sinhoWorkload')->fetch_row(sinhoWorkloadModel::QUARLITY_TABLE, 'workload_id = ' . intval($_POST['workload_id']));
        if ($itemInfo) {
            if ($itemInfo['belong_month']) {
                H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('考核已经结算，不能修改！')));
            }

            Application::model('sinhoWorkload')->update(sinhoWorkloadModel::QUARLITY_TABLE, $data, 'workload_id = ' . intval($_POST['workload_id']));
        } else {

            Application::model('sinhoWorkload')->insert(sinhoWorkloadModel::QUARLITY_TABLE, $data);
        }

        H::ajax_json_output(Application::RSM(Application::lang()->_t('质量考核已记录'), 0));
    }

    /**
     * 根绝workload_id 获取质量考核信息
     *
     * @return array
     */
    public function get_quarlity_action ()
    {
        $this->checkPermission(self::IS_SINHO_CHECK_WORKLOAD);

        if (empty($_POST['workload_id'])) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('参数错误')));
        }

        $itemInfo = Application::model('sinhoWorkload')->fetch_row(sinhoWorkloadModel::QUARLITY_TABLE, 'workload_id = ' . intval($_POST['workload_id']));
        is_array($itemInfo) OR $itemInfo = array();
        //$itemInfo = array('good_or_bad'=>-1, 'rate_num'=>5, 'remarks'=>'hello world');

        H::ajax_json_output(Application::RSM($itemInfo, 0));
    }

    /**
     * 删除质量考核
     */
    public function remove_quarlity_action ()
    {
        $this->checkPermission(self::IS_SINHO_CHECK_WORKLOAD);

        if (empty($_POST['workload_id'])) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('参数错误')));
        }

        $itemInfo = Application::model('sinhoWorkload')->fetch_row(sinhoWorkloadModel::QUARLITY_TABLE, 'workload_id = ' . intval($_POST['workload_id']));

        if (! $itemInfo) {
            H::ajax_json_output(Application::RSM(Application::lang()->_t('参数错误'), -1));
        }
        if ($itemInfo['belong_month'] > 0) {
            H::ajax_json_output(Application::RSM(Application::lang()->_t('考核已经结算，不能删除'), -1));
        }

        Application::model('sinhoWorkload')->delete(sinhoWorkloadModel::QUARLITY_TABLE, 'workload_id = ' . intval($_POST['workload_id']));

        H::ajax_json_output(Application::RSM(Application::lang()->_t('成功删除质量考核记录')));
    }
}

/* EOF */
