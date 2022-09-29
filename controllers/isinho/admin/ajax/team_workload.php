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

class team_workload extends SinhoBaseController
{
    protected $bookSubjectList = array();

    public function setup()
    {
        $this->checkPermission(self::IS_SINHO_TEAM_LEADER);
        HTTP::setHeaderNoCache();

        $this->user_info['sinho_manage_subject'] = @json_decode($this->user_info['sinho_manage_subject'], true);

        if (! $this->user_info['sinho_manage_subject']) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('你没有管理任何学科的权限')));
        }

        $bookSubjectList = $this->model()->fetch_all('sinho_book_category');
        $this->bookSubjectList = array_combine(array_column($bookSubjectList, 'id'), $bookSubjectList);
    }

    public function getUserList ()
    {
        // 获取所有组， 解析组管理的学科。 然后获取到组id
        $groupIds = array(-1);
        $groupList = $this->model('account')->get_user_group_list(0, 1);
        foreach ($groupList as $itemInfo) {
            $itemInfo['permission'] = unserialize($itemInfo['permission']);
            if ($itemInfo['permission'] && in_array($itemInfo['permission']['sinho_subject'], $this->user_info['sinho_manage_subject']) ) {
                $groupIds[] = $itemInfo['group_id'];
            }
        }
        // 根据组id获取用户列表
        $userList = array();
        $userList = $this->model('sinhoWorkload')->fetch_all('users', 'group_id IN (' . join(', ', $groupIds) . ')') ;
        $userIds  = array_column($userList, 'uid');
        $userList = array_combine($userIds, $userList);

        return $userList;
    }

    /**
     * 填充质量考核
     */
    public function fill_quarlity_action ()
    {
        if (empty($_POST['workload_id'])) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('参数错误')));
        }
        $itemInfo = Application::model('sinhoWorkload')->fetch_row(sinhoWorkloadModel::WORKLOAD_TABLE, 'id = ' . intval($_POST['workload_id']));
        if (! $itemInfo) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('参数错误，对应的工作量不存在')));
        }

        $userList = $this->getUserList();
        if (! isset($userList[$itemInfo['user_id']])) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('参数错误，对应的编辑不在管理范围')));
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
        if (empty($_POST['workload_id'])) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('参数错误')));
        }

        $itemInfo = Application::model('sinhoWorkload')->fetch_row(sinhoWorkloadModel::QUARLITY_TABLE, 'workload_id = ' . intval($_POST['workload_id']));
        is_array($itemInfo) OR $itemInfo = array();


        $userList = $this->getUserList();
        if (! isset($userList[$itemInfo['user_id']])) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('参数错误，对应的编辑不在管理范围')));
        }
        //$itemInfo = array('good_or_bad'=>-1, 'rate_num'=>5, 'remarks'=>'hello world');

        H::ajax_json_output(Application::RSM($itemInfo, 0));
    }

    /**
     * 删除质量考核
     */
    public function remove_quarlity_action ()
    {
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

        $userList = $this->getUserList();
        if (! isset($userList[$itemInfo['user_id']])) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('参数错误，对应的编辑不在管理范围')));
        }

        Application::model('sinhoWorkload')->delete(sinhoWorkloadModel::QUARLITY_TABLE, 'workload_id = ' . intval($_POST['workload_id']));

        H::ajax_json_output(Application::RSM(Application::lang()->_t('成功删除质量考核记录')));
    }
}

/* EOF */
