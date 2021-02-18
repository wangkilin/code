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

require_once __DIR__ . '/../../SinhoBaseController.php';

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

    public function statistic_total_chars_action ()
    {
        $this->checkPermission(self::IS_SINHO_CHECK_WORKLOAD);

        if (isset($_GET['start'])) {

        }

        // 获取工作量表中的半年内记录的最大月份， 获取半年内记录的最小月份。 将这段数据展示出来
        $belongMonth = $this->model('sinhoWorkload')->max(sinhoWorkloadModel::WORKLOAD_TABLE, 'belong_month', 'belong_month >= ' . date('Ym', strtotime('-6month')));
        $belongMinMonth = $this->model('sinhoWorkload')->min(sinhoWorkloadModel::WORKLOAD_TABLE, 'belong_month', 'belong_month >= ' . date('Ym', strtotime('-6month')));
        if (! $belongMonth) { // 如果没有工作量， 将上个月的月份作为记录最大月份
            $belongMonth = date('Ym', strtotime('-1month'));
        }
        if (! $belongMinMonth) {// 没有工作量记录， 将上个月作为记录的最小月份
            $belongMinMonth = $belongMonth;
        }
        // 当前记录中的年月份， 用记录的最大月份后延一个月
        $currentYearMonth = date('Ym', strtotime("{$belongMonth}01 +1month"));

        $userList = $this->model('sinhoWorkload')->getUserList(null, 'uid DESC', PHP_INT_MAX);
        $userIds  = array_column($userList, 'uid');
        $userList = array_combine($userIds, $userList);
        // 按月获取每个人的工作量
        $workloadStatLastMonth = $this->model('sinhoWorkload')->getWorkloadStatByUserIds (array(), null, $belongMonth);
        $totalCharsListLastMonth = array_combine(array_column($workloadStatLastMonth,'user_id'), array_column($workloadStatLastMonth,'total_chars'));
        //$totalCharsWithoutWeightListLastMonth = array_combine(array_column($workloadStatLastMonth,'user_id'), array_column($workloadStatLastMonth,'total_chars_without_weight'));
        arsort($totalCharsListLastMonth, SORT_NUMERIC);


        $startMonth = $belongMinMonth;
        $itemList = array();
        $workloadUserIds = array();
        while ($startMonth <= $belongMonth) {
            $itemList[$startMonth] = $this->model('sinhoWorkload')->getWorkloadStatByUserIds (array(), sinhoWorkloadModel::STATUS_VERIFIED, $startMonth);
            $workloadUserIds = array_merge($workloadUserIds, array_column($itemList[$startMonth], 'user_id' ) ) ;
            //$itemList[$startMonth] = array_sum(array_column($itemList[$startMonth], 'total_chars'));
            $startMonth = date('Ym', strtotime("{$startMonth}01 +1month"));
        }
        $employeeWorkloadList = array();
        $employeeWorkloadList = array_fill_keys($workloadUserIds, array());
        if (isset($employeeWorkloadList[''])) {
            unset($employeeWorkloadList['']);
        }
        $allTotalChars = array();
        foreach ($itemList as $_month=>$_statList) {
            foreach ($employeeWorkloadList as $_userId=>$_v) {
                $employeeWorkloadList[$_userId][$_month] = 0;
            }

            foreach ($_statList as $_userId=>$_statInfo) {
                if (!$_userId) {
                    continue;
                }
                $employeeWorkloadList[$_userId][$_month] = $allTotalChars[] = $_statInfo['total_chars'];
            }
        }
    }

    public function statistic_monthly_chars_action ()
    {
        $this->checkPermission(self::IS_SINHO_CHECK_WORKLOAD);
    }
}

/* EOF */
