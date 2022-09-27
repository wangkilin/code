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

class team_books extends SinhoBaseController
{
    protected $bookSubjectList = array();
    public function setup()
    {
        $this->checkPermission(self::IS_SINHO_TEAM_LEADER);

        $this->user_info['sinho_manage_subject'] = @json_decode($this->user_info['sinho_manage_subject'], true);

        if (! $this->user_info['sinho_manage_subject']) {
            H::redirect_msg(Application::lang()->_t('你没有管理任何学科的权限'), 'admin/');
        }

        $bookSubjectList = $this->model()->fetch_all('sinho_book_category');
        $this->bookSubjectList = array_combine(array_column($bookSubjectList, 'id'), $bookSubjectList);

        View::assign('bookSubjectList', $this->bookSubjectList);

    }

    /**
     * 书稿列表
     */
    public function index_action()
    {
        // post参数，搜索书稿。 组装参数变更get方式获取搜索列表
        if ($this->is_post()) {
            foreach ($_POST as $key => $val) {
                if ($key == 'start_date' OR $key == 'end_date') {// 按照日期搜索
                    $val = base64_encode($val);
                } else if (is_array($_POST[$key])) {
                    $val = rawurlencode(join(',', $_POST[$key]));
                } else {
                    $val = rawurlencode($val);
                }

                $param[] = $key . '-' . $val;
            }
            // 跳转到get方式
            H::ajax_json_output(Application::RSM(array(
                'url' => get_js_url('/admin/'.CONTROLLER.'/index/' . implode('__', $param))
            ), 1, null));
        }

        $this->per_page = 30;
        $this->crumb(Application::lang()->_t('书稿分管理'), 'admin/team_books/');
        // 只能管理指定学科下的书稿， 以及自己创建的书稿
        $where = array('(category_id IN ('. join(',', $this->user_info['sinho_manage_subject']).') OR user_id = '.$this->user_id.')');
        if ($_GET['start_date']) { // 发稿开始日期
            $where[] = 'delivery_date >="' . date('Y-m-d', strtotime(base64_decode($_GET['start_date'])) ) . '"';
        }
        if ($_GET['end_date']) { // 发稿结束日期
            $where[] = 'delivery_date <="' . date('Y-m-d', strtotime(base64_decode($_GET['end_date'])) ) . '"';
        }
        if ($_GET['category']) { // 按照分类搜素
            $where[] = 'category like "%' . $this->model()->quote(rawurldecode($_GET['category'])) .'%"';
        }
        if ($_GET['serial']) { // 按照系列搜索
            //$where[] = ' (MATCH(serial) AGAINST("' . $this->model()->quote(rawurldecode($_GET['serial'])) . '") )';
            $where[] = 'serial like "%' . $this->model()->quote(rawurldecode($_GET['serial'])) .'%"';
        }
        if ($_GET['book_name']) { // 按照书名搜索
            //$where[] = ' (MATCH(book_name) AGAINST("' . $this->model()->quote(rawurldecode($_GET['book_name'])) . '") )';
            $where[] = 'book_name like "%' . $this->model()->quote(rawurldecode($_GET['book_name'])) .'%"';
        }
        if ($_GET['proofreading_times']) { // 按照校次搜索
            //$where[] = ' (MATCH(proofreading_times) AGAINST("' . $this->model()->quote(rawurldecode($_GET['proofreading_times'])) . '") )';
            $where[] = 'proofreading_times like "%' . $this->model()->quote(rawurldecode($_GET['proofreading_times'])) .'%"';
        }
        if (isset($_GET['grade_level']) && $_GET['grade_level']!=='') { // 按照级别搜索
            $_GET['grade_level'] = explode(',', $_GET['grade_level']);
            foreach($_GET['grade_level'] as & $_level) {
                $_level = intval($_level);
            }
            $where[] = 'grade_level IN (' . join(',', $_GET['grade_level']) . ')';
        }
        if (isset($_GET['is_payed']) && $_GET['is_payed']!=='') { // 按照支付状态搜索。 设置了支付状态
            $where[] = 'is_payed = ' . intval($_GET['is_payed']) ;
        }

        if ($where) { // 组装搜索条件
            $where = join(' AND ', $where);
        } else {
            $where = null;
        }
        // 解析每个学科中用于搜索书名匹配的关键字。 匹配到关键字， 将书稿设置成对应的学科
        $keywordSubjectList = array();
        $keywordSubjectList1 = array();
        $bookSubjectList = $this->model()->fetch_all('sinho_book_category');
        $bookSubjectList = array_combine(array_column($bookSubjectList, 'id'), $bookSubjectList);
        foreach ($bookSubjectList as $_subjectCode => $_itemInfo) {
            $_itemInfo['keyword'] = explode(',', $_itemInfo['remark']);
            foreach ($_itemInfo['keyword'] as $_keyword) {
                if (mb_strlen($_keyword)==1) {
                    $keywordSubjectList1[$_keyword] = $_subjectCode;
                } else {
                    $keywordSubjectList[$_keyword] = $_subjectCode;
                }
            }
        }
        $keywordSubjectList = array_merge($keywordSubjectList, $keywordSubjectList1);
        // 获取书稿的配置信息
        $bookBelongYears = $this->model('sinhoWorkload')->fetch_one('sinho_key_value', 'value', 'varname="bookBelongYear"');
        $bookBelongYears = json_decode($bookBelongYears, true);

        if ($_GET['action']=='export') {
            $itemList  = $this->model('sinhoWorkload')->getBookList($where, 'delivery_date DESC, id DESC', PHP_INT_MAX, $_GET['page']);
            $phpExcel = & loadClass('Tools_Excel_PhpExcel');
            $headArr = array(
                'id_number'                         => '序号',
                'delivery_date'                     => '发稿日期',
                'return_date'                       => '回稿日期',
                'serial'                            => '系列',
                'book_name'                         => '书名',
                'proofreading_times'                => '校次',
                'content_table_pages'               => '目录',
                'text_pages'                        => '正文',
                'text_table_chars_per_page'         => '目录+正文千字/页',
                'answer_pages'                      => '答案',
                'answer_chars_per_page'             => '答案千字/页',
                'test_pages'                        => '试卷',
                'test_chars_per_page'               => '试卷千字/页',
                'test_answer_pages'                 => '试卷答案',
                'test_answer_chars_per_page'        => '试卷答案千字/页',
                'exercise_pages'                    => '课后作业',
                'exercise_chars_per_page'           => '课后作业千字/页',
                'function_book'                     => '功能册',
                'function_book_chars_per_page'      => '功能册千字/页',
                'function_answer'                   => '功能册答案',
                'function_answer_chars_per_page'    => '功能册答案千字/页',
                'weight'                            => '难度系数',
                'total_chars'                       => '字数（合计）',
                'total_chars_without_weight'        => '字数（未乘系数）',
                'remarks'                           => '备注'
            );
            $fileName = '导出书稿-' . date('Y-m-d') . '.xls';

            // 导出书稿
            $style = array(
                'width'   => array('A'=>4, 'B'=>10, 'C'=>10, 'D'=>15,'E'=>20,'G'=>4,'H'=>4, 'Y'=>20,), // 字符数算
                'height'  => array(1 => 20),      // 按照 磅 算
                'style'   => array (
                    'A1:Y1'=> array (
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
        } else {
            $itemList  = $this->model('sinhoWorkload')->getBookList($where, 'delivery_date DESC, id DESC', $this->per_page, $_GET['page']);
        }
        foreach ($itemList as & $_itemInfo) {
            $_itemInfo['subject_code'] = $_itemInfo['category_id'];
            if ($_itemInfo['subject_code']) {
                continue;
            }
            foreach ($keywordSubjectList as $_keyword=>$_subjectCode) {
                if (strpos($_itemInfo['book_name'], $_keyword)!==false) {
                    $_itemInfo['subject_code'] = $_subjectCode;
                    break;
                }
            }
        }
        $totalRows = $this->model('sinhoWorkload')->found_rows();
        $bookIds   = array_column($itemList, 'id');
        $booksWorkload = $this->model('sinhoWorkload')->getWorkloadStatByBookIds ($bookIds, sinhoWorkloadModel::STATUS_VERIFIED);
        $booksWorkloadNotPayed = $this->model('sinhoWorkload')->getWorkloadStatByBookIds ($bookIds, array(sinhoWorkloadModel::STATUS_RECORDING, sinhoWorkloadModel::STATUS_VERIFYING) );
        $userList = $this->model('sinhoWorkload')->getUserList('forbidden != 1', 'uid DESC', PHP_INT_MAX);
        $groupList = $this->model('account')->get_user_group_list(0, 1);
        foreach ($groupList as & $_item) {
            $_item['permission'] = unserialize($_item['permission']);
        }
        $artsList = $scienceList = array();
        foreach ($this->bookSubjectList as $_item) {
            if ($_item['type'] == self::SUBJECT_CATEGORIZE_ARTS) {
                $artsList[] = $_item['type'];
            } else if ($_item['type'] == self::SUBJECT_CATEGORIZE_SCIENCE) {
                $scienceList[] = $_item['type'];
            }
        }
        $userMoreSubjects = $this->model()->fetch_all('users_attribute', 'attr_key = "sinho_more_subject"');
        $userIds = array_column($userMoreSubjects, 'uid');
        $userMoreSubjects = array_column($userMoreSubjects, 'attr_value');
        $userMoreSubjects = array_combine($userIds, $userMoreSubjects);
        foreach ($userList as & $_item) {
            if (isset($userMoreSubjects[$_item['uid']])) {
                $_item['more_subject'] = $userMoreSubjects[$_item['uid']];
            } else {
                $_item['more_subject'] = '[]';
            }
            $_item['main_subject'] = $groupList[$_item['group_id']]['permission']['sinho_subject'];
            if (in_array($_item['main_subject'], $artsList) ) {
                $_item['subject_category'] = 0;
            } else if (in_array($_item['main_subject'], $scienceList) ) {
                $_item['subject_category'] = 1;
            } else {
                $_item['subject_category'] = '';
            }
        }

        $url_param = array();
        foreach($_GET as $key => $val) {
            if (is_array($val)) { // 如果是数组参数， 将参数用逗号连接
                $val = join(',', $val);
            }
            if (!in_array($key, array('app', 'c', 'act', 'page'))) {
                $url_param[] = $key . '-' . $val;
            }
        }
        View::assign('backUrl', get_js_url('/admin/'.CONTROLLER.'/index/page-'.$_GET['page']));
        View::assign('pagination', Application::pagination()->initialize(array(
            'base_url'   => get_js_url('/admin/'.CONTROLLER.'/index/') . implode('__', $url_param),
            'total_rows' => $totalRows,
            'per_page'   => $this->per_page
        ))->create_links());
        //$categoryList = $this->model('category')->getAllCategories('id');
        View::assign('itemsList',       $itemList);
        View::assign('booksWorkload',   $booksWorkload);
        View::assign('booksWorkloadNotPayed', $booksWorkloadNotPayed);
        View::assign('bookSubjectList', $bookSubjectList);
        View::assign('bookBelongYears', $bookBelongYears);

        View::assign('itemOptions',
                     buildSelectOptions(
                         $userList,
                         'user_name',
                         'uid',
                         null,
                         array(
                             'group_id'             => 'data-group_id',
                             'more_subject'         => 'data-more_subject',
                             'main_subject'         => 'data-main_subject',
                             'subject_category'     => 'data-subject_category'
                         )
                    )
                );

        View::assign('bookSubjectListOptions',
                buildSelectOptions(
                    $bookSubjectList,
                    'name',
                    'id',
                    isset($itemInfo)? $itemInfo['category_id'] : null,
                    array(
                        'remark'     => 'data-subject_keyword'
                    )
               )
           );
        //View::assign('itemOptions', $this->buildCategoryDropdownHtml('0', $selected, '--'));
        View::assign('totalRows', $totalRows);
        View::assign('amountPerPage', $this->per_page);

        View::import_js(G_STATIC_URL . '/js/bootstrap-multiselect.js');
        View::import_js('js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js');
        View::import_js('js/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js');
        View::import_js('js/icb_template_isinho.com.js');
        View::import_css('js/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css');
        View::import_css(G_STATIC_URL . '/css/bootstrap-multiselect.css');

        View::assign('menu_list', $this->filterAdminMenu($this->model('admin')->fetch_menu_list('admin/team_books','sinho_admin_menu')  ) );


        View::output('admin/books/list');
    }

    /**
     * 新建/编辑 书稿
     */
    public function book_action()
    {
        $tags = array();
        $selected = null;

        if ($_GET['id']) { // 传递书稿id，编辑书稿
            $this->crumb(Application::lang()->_t('书稿编辑'), 'admin/'.CONTROLLER.'/');

            $itemInfo = $this->model('sinhoWorkload')->getBookById($_GET['id']);

            if (!$itemInfo || (!in_array($itemInfo['category_id'], $this->user_info['sinho_manage_subject']) && $itemInfo['user_id']!=$this->user_id) ) {
                H::redirect_msg(Application::lang()->_t('书稿不存在'), '/admin/'.CONTROLLER.'/');
            }

            View::assign('itemInfo', $itemInfo);
        } else {
            // 基于指定书稿复制成新书稿. 将书稿id清空， 只用其他数据
            if (isset($_GET['from_id'])) {
                $itemInfo = $this->model('sinhoWorkload')->getBookById($_GET['from_id']);

            if (!$itemInfo || (!in_array($itemInfo['category_id'], $this->user_info['sinho_manage_subject']) && $itemInfo['user_id']!=$this->user_id) ) {
                H::redirect_msg(Application::lang()->_t('书稿不存在'), '/admin/'.CONTROLLER.'/');
            }
                if ($itemInfo) {
                    unset($itemInfo['id']);
                }
                View::assign('itemInfo', $itemInfo);
            }
            $this->crumb(Application::lang()->_t('添加书稿'), 'admin/'.CONTROLLER.'/');
        }
        // 获取书稿所属学科列表
        $bookSubjectList = $this->model()->fetch_all('sinho_book_category');
        $bookSubjectList = array_combine(array_column($bookSubjectList, 'id'), $bookSubjectList);
        View::assign('bookSubjectList', $bookSubjectList);

        View::assign('itemOptions',
                     buildSelectOptions(
                         $bookSubjectList,
                         'name',
                         'id',
                         isset($itemInfo)? $itemInfo['category_id'] : null,
                         array(
                             'remark'     => 'data-subject_keyword'
                         )
                    )
                );

        // 获取书稿的配置信息
        $bookBelongYears = $this->model('sinhoWorkload')->fetch_one('sinho_key_value', 'value', 'varname="bookBelongYear"');
        $bookBelongYears = json_decode($bookBelongYears, true);
        View::assign('bookBelongYears', $bookBelongYears);

        View::assign('menu_list', $this->filterAdminMenu($this->model('admin')->fetch_menu_list('admin/team_books','sinho_admin_menu') ) );

        View::import_js('js/functions.js');
        View::import_js(G_STATIC_URL . '/js/bootstrap-multiselect.js');
        View::import_css(G_STATIC_URL . '/css/bootstrap-multiselect.css');
        View::import_js('js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js');
        View::import_js('js/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js');
        View::import_js('js/icb_template_isinho.com.js');
        View::import_css('js/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css');
        View::output('admin/books/book');
    }

    /**
     * 导入书稿页面
     */
    public function import_action ()
    {
        // 获取书稿的配置信息
        $bookBelongYears = $this->model('sinhoWorkload')->fetch_one('sinho_key_value', 'value', 'varname="bookBelongYear"');
        $bookBelongYears = json_decode($bookBelongYears, true);
        View::assign('bookBelongYears', $bookBelongYears);

        View::assign('menu_list', $this->filterAdminMenu($this->model('admin')->fetch_menu_list('admin/books','sinho_admin_menu') ) );


        View::import_js(G_STATIC_URL . '/js/bootstrap-multiselect.js');
        View::import_css(G_STATIC_URL . '/css/bootstrap-multiselect.css');
        View::import_js('js/fileupload.js');
        View::output('admin/books/import');
    }
}

/* EOF */
