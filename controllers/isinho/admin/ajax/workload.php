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
}

/* EOF */
