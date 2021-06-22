<?php
defined('iCodeBang_Com') OR die('Access denied!');

class workload extends SinhoBaseController
{
    const PERMISSION_BOOKLIST = 'sinho_book_list';

    public function index_action()
    {

    }

    /**
     * 填报工作量列表
     */
    public function fill_quarylity_action ()
    {
        $this->checkPermission(self::IS_SINHO_VERIFY_WORKLOAD);
        $assigned = (array) $this->model('sinhoWorkload')->fetch_page(sinhoWorkloadModel::WORKLOAD_TABLE, 'user_id = ' . intval($this->user_id) . ' AND `status`<>' . sinhoWorkloadModel::STATUS_DELETE , 'id DESC', $_GET['page'], $this->per_page);

        $bookIds  = array_column($assigned, 'book_id');
        $bookList = array();
        if ($bookIds) {
            $bookList = $this->model('sinhoWorkload')->fetch_all(sinhoWorkloadModel::BOOK_TABLE, 'id IN (' . join(', ', $bookIds) . ')') ;
            $bookIds  = array_column($bookList, 'id');
            $bookList = array_combine($bookIds, $bookList);
        }
        View::assign('itemsList', $assigned);
        View::assign('booksList', $bookList);
        $totalRows     = $this->model('sinhoWorkload')->found_rows();

        $url_param = array();
        foreach($_GET as $key => $val) {
            if (!in_array($key, array('app', 'c', 'act', 'page'))) {
                $url_param[] = $key . '-' . $val;
            }
        }

        View::assign('totalChars', $this->model('sinhoWorkload')->getTotalCharsByUserIds (array($this->user_id), sinhoWorkloadModel::STATUS_VERIFYING));
        View::assign('thisUserId', $this->user_id);

        View::assign('pagination', Application::pagination()->initialize(array(
            'base_url'   => get_js_url('/admin/fill_list/') . implode('__', $url_param),
            'total_rows' => $totalRows,
            'per_page'   => $this->per_page
        ))->create_links());

        View::import_js('js/icb_template_isinho.com.js');
        View::import_js('js/functions.js');

        View::assign('menu_list', $this->filterAdminMenu($this->model('admin')->fetch_menu_list('admin/fill_list', 'sinho_admin_menu') ) );
        View::output('admin/workload/fill_list');
    }
}

/* EOF */
