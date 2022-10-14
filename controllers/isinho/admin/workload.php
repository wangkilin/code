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


    /**
     * 编辑自行添加书稿和工作量
     */
    public function report_action ()
    {
        $bookInfo = $itemInfo = array();
        // 获取书稿所属学科列表
        $bookSubjectList = $this->model()->fetch_all('sinho_book_category');
        $bookSubjectList = array_combine(array_column($bookSubjectList, 'id'), $bookSubjectList);
        View::assign('bookSubjectList', $bookSubjectList);
        View::assign('bookSubjectOptions',
                     buildSelectOptions(
                         $bookSubjectList,
                         'name',
                         'id',
                         null,
                         array(
                             'remark'     => 'data-subject_keyword'
                         )
                    )
                );


        // 获取用户信息列表,
        $userList = $this->model('sinhoWorkload')->getUserList('forbidden = 0', 'uid DESC', PHP_INT_MAX);

        View::assign('userOptions', buildSelectOptions($userList, 'user_name', 'uid' ) );

        View::assign('formAction', 'admin/ajax/workload/report/');
        View::assign('flagIsEditorReporting', true); // 通知View页面， 这里是编辑自主上报
        View::assign('hostConfig', $this->hostConfig);
        View::assign('bookInfo', $bookInfo);
        View::assign('itemInfo', $itemInfo);
        View::assign('menu_list', $this->filterAdminMenu($this->model('admin')->fetch_menu_list('admin/fill_list','sinho_admin_menu') ) );


        View::import_js('js/functions.js');
        View::import_js(G_STATIC_URL . '/js/bootstrap-multiselect.js');
        View::import_css(G_STATIC_URL . '/css/bootstrap-multiselect.css');


        View::output('admin/workload/fill');
    }
}

/* EOF */
