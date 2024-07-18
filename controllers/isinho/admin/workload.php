<?php
defined('iCodeBang_Com') OR die('Access denied!');

class workload extends SinhoBaseController
{
    public function setup ()
    {
        View::assign('hostConfig', $this->hostConfig);
    }

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

        $isSinhoCheckWorkload = $this->hasRolePermission(self::IS_SINHO_CHECK_WORKLOAD);
        $queryUserIds = array();
        $where = array();
        if (! $isSinhoCheckWorkload) {
            $where[] = 'user_id = '. intval($this->user_id);
        } else if ($_GET['user_id']) {// 解析用户id
            $queryUserIds = explode(',', $_GET['user_id']);
            foreach ($queryUserIds as & $_id) {
                $_id = intval($_id);
            }
            $where[] = 'user_id IN ( ' . join(', ',  $queryUserIds). ') ';
        }
        // 设置了时间范围， 获取指定时间范围内的数据
        if ($_GET['start_month']) {
            $where[] = '(belong_month >= ' . intval($_GET['start_month']) . ' OR belong_month IS NULL )';
        }
        if ($_GET['end_month'] && $_GET['end_month']!=date('Ym')) {
            $where[] = 'belong_month <= ' . intval($_GET['end_month']);
        }
        $quarlityList = (array) $this->model('sinhoWorkload')->fetch_page(sinhoWorkloadModel::QUARLITY_TABLE, join(' AND ', $where), 'id DESC', $_GET['page'], $this->per_page);
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

        // 获取用户信息列表,
        $userList = $this->model('sinhoWorkload')->getUserList(null, 'forbidden ASC,uid DESC', PHP_INT_MAX);


        if ($_GET['action']=='export') {
            $itemList  = array();
            foreach ($quarlityList AS $itemInfo) {
                $itemInfo['good_or_bad']         = $itemInfo['good_or_bad'] == 1 ? '⇧' : '⇩';
                $itemInfo['add_date']            = substr($itemInfo['add_date'], 0, 10);
                $itemInfo['user_name']           = $userList[$itemInfo['user_id']]['user_name'];
                $itemInfo['category']            = $bookList[$itemInfo['book_id']]['category'];
                $itemInfo['serial']              = $bookList[$itemInfo['book_id']]['serial'];
                $itemInfo['book_name']           = $bookList[$itemInfo['book_id']]['book_name'];
                $itemInfo['proofreading_times']  = $bookList[$itemInfo['book_id']]['proofreading_times'];
                $itemInfo['payable_amount']      = round($workloadList[$itemInfo['workload_id']]['payable_amount'] * $itemInfo['rate_num'] / 100 * $itemInfo['good_or_bad'], 2);
                $itemList[] = $itemInfo;
            }
            $phpExcel = & loadClass('Tools_Excel_PhpExcel');
            $headArr = array(
                'add_date'                    => '日期',
                'user_name'                   => '编辑',
                'category'                    => '书稿类别',
                'serial'                      => '系列',
                'book_name'                   => '书名',
                'proofreading_times'          => '校次',
                'content_table_pages'         => '类别',
                'working_times'               => '遍次',
                'good_or_bad'                 => '奖惩',
                'rate_num'                    => '考核比例',
                'payable_amount'              => '核算金额',
                'remarks'                     => '备注',
                'belong_month'                => '核算月份',
            );
            $fileName = '导出质量考核-' . date('Y-m-d') . '.xls';

            // 导出书稿
            $style = array(
                'width'   => array('A'=>10, 'B'=>10, 'C'=>10, //隐藏列，设置列宽为0 'D'=>0,
                                   'E'=>15,'F'=>20,'H'=>4,
                                   'I'=>4, 'L'=>20,), // 字符数算
                'height'  => array(1 => 20),      // 按照 磅 算
                'style'   => array (
                    'A1:M1'=> array (
                                'font'    => array(
                                                    'size'      => 9
                                ),
                                'fill'    => array(
                                                    'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
                                                    'color' => array('rgb' => 'E8F1E2'),
                                ),
                                'borders' => array(
                                                    'bottom'     => array(
                                                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                        'color' => array(
                                                            'rgb' => '999999'
                                                        )
                                                    ),
                                                    'right' => array(
                                                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                        'color' => array(
                                                            'rgb' => '999999'
                                                        ),
                                                    )
                                ),

                                'alignment'  => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                    )
                ),
            );
            $phpExcel->export($fileName, $headArr, $itemList, true, $style);
            return;
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

        View::assign('itemOptions', buildSelectOptions($userList, 'user_name', 'uid', $queryUserIds ) );


        View::import_js('js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js');
        View::import_js('js/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js');
        View::import_css('js/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css');
        View::import_js(G_STATIC_URL . '/js/bootstrap-multiselect.js');
        View::import_css(G_STATIC_URL . '/css/bootstrap-multiselect.css');

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
