<?php
defined('iCodeBang_Com') OR die('Access denied!');

class workload extends SinhoBaseController
{
    public function index_action()
    {
        HTTP::redirect('/admin/');
    }

    /**
     * 填报工作量列表
     */
    public function quarlity_list_action ()
    {
        $this->checkPermission(self::IS_SINHO_FILL_WORKLOAD | self::IS_SINHO_CHECK_WORKLOAD);
        $where = null;
        if (! $this->hasRolePermission(self::IS_SINHO_CHECK_WORKLOAD)) {
            $where = 'user_id = '. intval($this->user_id);
        } else if ($_GET['user_id']) {
            $where = 'user_id = '. intval($_GET['user_id']);
        }
        $quarlityList = (array) $this->model('sinhoWorkload')->fetch_page(sinhoWorkloadModel::QUARLITY_TABLE, $where, 'id DESC', $_GET['page'], $this->per_page);
        $totalRows     = $this->model('sinhoWorkload')->found_rows();

        $bookIds  = array_column($quarlityList, 'book_id');
        $bookList = array();
        if ($bookIds) {
            $bookList = $this->model('sinhoWorkload')->fetch_all(sinhoWorkloadModel::BOOK_TABLE, 'id IN (' . join(', ', $bookIds) . ')') ;
            $bookIds  = array_column($bookList, 'id');
            $bookList = array_combine($bookIds, $bookList);
        }
        $workloadIds  = array_column($quarlityList, 'workload_id');
        $workloadList = array();
        if ($workloadIds) {
            $workloadList = $this->model('sinhoWorkload')->fetch_all(sinhoWorkloadModel::WORKLOAD_TABLE, 'id IN (' . join(', ', $workloadIds) . ')') ;
            $workloadIds  = array_column($workloadList, 'id');
            $workloadList = array_combine($workloadIds, $workloadList);
        }
        if ($this->hasRolePermission(self::IS_SINHO_CHECK_WORKLOAD)) {
            $userIds  = array_column($quarlityList, 'user_id');
            $userList = array();
            if ($userIds) {
                $userList = $this->model('sinhoWorkload')->fetch_all('users', 'uid IN (' . join(', ', $userIds) . ')') ;
                $userIds  = array_column($userList, 'uid');
                $userList = array_combine($userIds, $userList);
            }
            View::assign('userList', $userList);
        }

        View::assign('hasCheckPermission', $this->hasRolePermission(self::IS_SINHO_CHECK_WORKLOAD));
        View::assign('itemsList', $quarlityList);
        View::assign('booksList', $bookList);
        View::assign('workloadList', $workloadList);

        $url_param = array();
        foreach($_GET as $key => $val) {
            if (!in_array($key, array('app', 'c', 'act', 'page'))) {
                $url_param[] = $key . '-' . $val;
            }
        }

        View::assign('thisUserId', $this->user_id);

        View::assign('pagination', Application::pagination()->initialize(array(
            'base_url'   => get_js_url('/admin/workload/quarlity_list/') . implode('__', $url_param),
            'total_rows' => $totalRows,
            'per_page'   => $this->per_page
        ))->create_links());

        View::import_js('js/icb_template_isinho.com.js');
        View::import_js('js/functions.js');

        View::assign('menu_list', $this->filterAdminMenu($this->model('admin')->fetch_menu_list('admin/workload/quarlity_list', 'sinho_admin_menu') ) );
        View::output('admin/workload/quarlity_list');
    }
}

/* EOF */
