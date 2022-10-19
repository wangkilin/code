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
/**
 *
 * select c.`serial`,
 *       p.*
 *   from icb_sinho_employee_workload p
 *   left join icb_sinho_company_workload c
 *   on  p.`serial` = c.`serial`
 *   and p.`book_name` = c.`book_name`
 *   #and p.`proofreading_times` = c.`proofreading_times`
 *   where belong_month = '202101'
 *   ; ## 匹配公司稿件和个人分配的稿件
 *
 *   select * from (
 *   select
 *   '公司' AS `数据源`,
 *   c.`id` ,
 *   c.`序号` ,
 *   '' AS `责编`,
 *   c.`发稿日期`,
 *   c.`回稿日期` AS `日期`,
 *   c.`系列` ,
 *   c.`书名` ,
 *   c.`校次` ,
 *   '' AS `类别` ,
 *   '' AS `遍次` ,
 *   c.`目录` ,
 *   c.`正文` ,
 *   c.`目+正千字/页` ,
 *   c.`答案` ,
 *   c.`千字/页` ,
 *   c.`试卷` ,
 *   c.`试卷千字/页` ,
 *   c.`试卷答案` ,
 *   c.`试卷答案千字/页` ,
 *   c.`课后作业` ,
 *   c.`课后作业千字/页` ,
 *   c.`功能册` ,
 *   c.`功能册千字/页` ,
 *   c.`功能册答案` ,
 *   c.`功能册答案千字/页` ,
 *   c.`难度系数` AS `系数`,
 *   c.`字数（合计）` ,
 *   c.`字数（未乘系数）` ,
 *   2 AS `单价`,
 *   '' AS `应发金额` ,
 *   '' AS `考核奖罚比例` ,
 *   '' AS `考核奖罚金额` ,
 *   '' AS `实发金额` ,
 *
 *   c.`备注`
 *   from company_workload c
 *   inner join personal_workload p
 *   on p.`系列` = c.`系列`
 *   and p.`书名` = c.`书名`
 *   and p.`校次` = c.`校次`
 *   group by c.id                     ## 获取分配出去的 公司稿件内容，作为基准，供校验; ## 获取分配出去的 公司稿件内容，作为基准，供校验
 *
 *   union
 *
 *   select
 *   '员工' AS `数据源`,
 *   p.`id` ,
 *   '' AS `序号`,
 *   p.`责编`,
 *   '' AS `发稿日期`,
 *   p.`结算日期` AS `日期`,
 *   p.`系列` ,
 *   p.`书名` ,
 *   p.`校次` ,
 *   p.`类别` ,
 *   p.`遍次` ,
 *   p.`目录` ,
 *   p.`正文` ,
 *   p.`千字/页` ,
 *   p.`答案` ,
 *   p.`答案千字/页` ,
 *   p.`试卷` ,
 *   p.`试卷千字/页` ,
 *   p.`试卷答案` ,
 *   p.`试卷答案千字/页` ,
 *   p.`课后作业` ,
 *   p.`课后作业千字/页` ,
 *   p.`功能册` ,
 *   p.`功能册千字/页` ,
 *   p.`功能册答案` ,
 *   p.`功能册答案千字/页` ,
 *   p.`系数` ,
 *   p.`核算总字数（千）` ,
 *   p.`核算总字数（千）`/ p.`系数`  AS`字数（未乘系数）` ,
 *   p.`单价` ,
 *   p.`应发金额` ,
 *   p.`考核奖罚比例` ,
 *   p.`考核奖罚金额` ,
 *   p.`实发金额` ,
 *   p.`备注（具体校稿页码）`
 *   from `personal_workload` p
 *   ) as union_all
 *   order by
 *   `系列` ,
 *   `书名` ,
 *   `校次` ,
 *   `类别` ,
 *   `遍次`
 *   ;
 *
 *
 *   insert INto personal_workload select * from personal_workload_copy where `责编` = '史旭' or  `责编` = '黄惠莹' ;
 *
 *   update personal_workload set `对应月份` = 202011, `状态` = 1, `核算日期` = UNIX_TIMESTAMP()
 *
 *
 *
 *
 *   show variables like 'table_open_cache'
 *
 *   show variables like 'max_connections'
 *
 *   insert into icb_sinho_employee_workload (
 *   `user_id` , # varchar(255) DEFAULT NULL COMMENT '编辑员工id， 对应到 user.id',
 *   `settlement_date` , #  varchar(255) DEFAULT NULL COMMENT '结算日期',
 *   `serial` , #  varchar(255) DEFAULT NULL,
 *   `book_name` , #  varchar(255) DEFAULT NULL COMMENT '书名',
 *   `proofreading_times` , #  varchar(255) DEFAULT NULL,
 *   `category` , #  varchar(255) DEFAULT NULL COMMENT '类别',
 *   `working_times` , #  varchar(255) DEFAULT NULL COMMENT '遍次',
 *   `content_table_pages` , #  varchar(255) DEFAULT NULL COMMENT '目录',
 *   `text_pages` , #  varchar(255) DEFAULT NULL COMMENT '正文',
 *   `text_table_chars_per_page` , #  varchar(255) DEFAULT NULL COMMENT '目录+正文千字/页',
 *   `answer_pages` , #  varchar(255) DEFAULT NULL COMMENT '答案',
 *   `answer_chars_per_page` , #  varchar(255) DEFAULT NULL COMMENT '答案千字/页',
 *   `test_pages` , #  varchar(255) DEFAULT NULL COMMENT '试卷',
 *   `test_chars_per_page` , #  varchar(255) DEFAULT NULL COMMENT '试卷千字/页',
 *   `test_answer_pages` , #  varchar(255) DEFAULT NULL COMMENT '试卷答案',
 *   `test_answer_chars_per_page` , #  varchar(255) DEFAULT NULL COMMENT '试卷答案千字/页',
 *   `exercise_pages` , #  varchar(255) DEFAULT NULL COMMENT '课后作业',
 *   `exercise_chars_per_page` , #  varchar(255) DEFAULT NULL COMMENT '课后作业千字/页',
 *   `function_book` , #  varchar(255) DEFAULT NULL COMMENT '功能册',
 *   `function_book_chars_per_page` , #  varchar(255) DEFAULT NULL COMMENT '功能册千字/页',
 *   `function_answer` , #  varchar(255) DEFAULT NULL COMMENT '功能册答案',
 *   `function_answer_chars_per_page` , #  varchar(255) DEFAULT NULL COMMENT '功能册答案千字/页',
 *   `weight` , #  varchar(255) DEFAULT NULL COMMENT '难度系数',
 *   `total_chars` , #  varchar(255) DEFAULT NULL COMMENT '核算总字数（千）',
 *   `price` , #  varchar(10) DEFAULT NULL COMMENT '单价',
 *   `payable_amount` , #  varchar(10) DEFAULT NULL COMMENT '应发金额',
 *   `assessment_rate` , #  varchar(11) DEFAULT NULL COMMENT '考核奖罚比例',
 *   `assessment_amount` , #  varchar(11) DEFAULT NULL COMMENT '考核奖罚金额',
 *   `actual_amount` , #  varchar(11) DEFAULT NULL COMMENT '实发金额',
 *   `remarks` , #  mediumtext COMMENT '备注',
 *   `belong_month` , #  int(6) DEFAULT NULL COMMENT '对应月份',
 *   `accounting_date` , #  int(11) DEFAULT NULL COMMENT '核算日期',
 *   `status`  #  tinyint(1) DEFAULT NULL COMMENT '状态： 1-已验证， 0-未验证',
 *   )
 *   select
 *   `责编` , #varchar(255) DEFAULT NULL,
 *   `结算日期`, # varchar(255) DEFAULT NULL,
 *   `系列` , #varchar(255) DEFAULT NULL,
 *   `书名` , #varchar(255) DEFAULT NULL,
 *   `校次` , #varchar(255) DEFAULT NULL,
 *   `类别` , #varchar(255) DEFAULT NULL,
 *   `遍次` , #varchar(255) DEFAULT NULL,
 *   `目录` , #varchar(255) DEFAULT NULL,
 *   `正文` , #varchar(255) DEFAULT NULL,
 *   `千字/页` , #varchar(255) DEFAULT NULL,
 *   `答案` , #varchar(255) DEFAULT NULL,
 *   `答案千字/页` , #varchar(255) DEFAULT NULL,
 *   `试卷` , #varchar(255) DEFAULT NULL,
 *   `试卷千字/页` , #varchar(255) DEFAULT NULL,
 *   `试卷答案` , #varchar(255) DEFAULT NULL,
 *   `试卷答案千字/页` , #varchar(255) DEFAULT NULL,
 *   `课后作业` , #varchar(255) DEFAULT NULL,
 *   `课后作业千字/页`, # varchar(255) DEFAULT NULL,
 *   `功能册` , #varchar(255) DEFAULT NULL,
 *   `功能册千字/页` , #varchar(255) DEFAULT NULL,
 *   `功能册答案` , #varchar(255) DEFAULT NULL,
 *   `功能册答案千字/页`, # varchar(255) DEFAULT NULL,
 *   `系数`, # varchar(255) DEFAULT NULL,
 *   `核算总字数（千）`, # varchar(255) DEFAULT NULL,
 *   `单价`, # varchar(255) DEFAULT NULL,
 *   `应发金额`, # varchar(255) DEFAULT NULL,
 *   `考核奖罚比例`, # varchar(255) DEFAULT NULL,
 *   `考核奖罚金额`, # varchar(255) DEFAULT NULL,
 *   `实发金额`, # varchar(255) DEFAULT NULL,
 *   `备注（具体校稿页码）`, # varchar(255) DEFAULT NULL,
 *   `对应月份`, # mediumint(6) NOT NULL DEFAULT '0',
 *   `核算日期`, # int(11) NOT NULL DEFAULT '0',
 *   `状态` # tinyint(1) NOT NULL DEFAULT '0'
 *   from icb_sinho_personal_workload ;
 *
 *   update icb_sinho_employee_workload e, icb_sinho_company_workload c
 *   set e.book_id = c.id
 *   where e.`serial` = c.`serial` AND e.`book_name`=c.`book_name` AND e.`proofreading_times` = c.`proofreading_times`;
 *
 *
 *   SELECT `icb_sinho_company_workload`.* FROM `icb_sinho_company_workload` WHERE (id = 85) LIMIT 1
 *
 */

defined('iCodeBang_Com') OR die('Access denied!');

class books extends SinhoBaseController
{
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
                'url' => get_js_url('/admin/books/index/' . implode('__', $param))
            ), 1, null));
        }

        $this->per_page = 30;
        $this->crumb(Application::lang()->_t('书稿管理'), 'admin/books/');

        if (!$this->user_info['permission'][self::PERMISSION_BOOKLIST]) {
            H::redirect_msg(Application::lang()->_t('你没有访问权限'), 'admin/');
        }

        $where = array();
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

        $orderBy = 'delivery_date DESC, id DESC'; // 默认按照日期排序
        switch ($_GET['orderby']) {
            case 'book': // 按照书名排序
                $orderBy = 'book_name,proofreading_times,text_pages,answer_pages,test_pages,test_answer_pages,exercise_pages,function_book,function_answer';
                break;

            case 'page': // 按照页码排序
                $orderBy = 'text_pages,answer_pages,test_pages,test_answer_pages,exercise_pages,function_book,function_answer,book_name,proofreading_times';
                break;
            case 'date':
                $orderBy ='delivery_date DESC,book_name,proofreading_times,text_pages,answer_pages,test_pages,test_answer_pages,exercise_pages,function_book,function_answer';
                break;
            default:  // 按照日期排序
                $_GET['orderby'] = '';
                break;
        }
        if ($_GET['action']=='export') {
            $itemList  = $this->model('sinhoWorkload')->getBookList($where, $orderBy, PHP_INT_MAX, $_GET['page']);
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
            $itemList  = $this->model('sinhoWorkload')->getBookList($where, $orderBy, $this->per_page, $_GET['page']);
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
            if (in_array($_item['main_subject'], SinhoBaseController::SUBJECT_CATEGORIZE[0]) ) {
                $_item['subject_category'] = 0;
            } else if (in_array($_item['main_subject'], SinhoBaseController::SUBJECT_CATEGORIZE[1]) ) {
                $_item['subject_category'] = 1;
            } else {
                $_item['subject_category'] = '';
            }
        }

        $this->crumb(Application::lang()->_t('书稿列表'), 'admin/books/index/');

        $url_param = array();
        foreach($_GET as $key => $val) {
            if (is_array($val)) { // 如果是数组参数， 将参数用逗号连接
                $val = join(',', $val);
            }
            if (!in_array($key, array('app', 'c', 'act', 'page'))) {
                $url_param[] = $key . '-' . $val;
            }
        }


        View::assign('hostConfig', $this->hostConfig);
        View::assign('urlQuery', implode('__', $url_param));
        View::assign('backUrl', get_js_url('/admin/books/index/page-'.$_GET['page']));
        View::assign('pagination', Application::pagination()->initialize(array(
            'base_url'   => get_js_url('/admin/books/index/') . implode('__', $url_param),
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

        View::assign('menu_list', $this->filterAdminMenu($this->model('admin')->fetch_menu_list('admin/books','sinho_admin_menu')  ) );


        View::output('admin/books/list');
    }

    /**
     * 管理书稿分类
     */
    public function category_action ()
    {
        $list = array();
        foreach (self::SUBJECT_CATEGORIZE_LIST as $_k => $_v) {
            $list[] = array('id' => $_k, 'name'=> $_v);
        }

        $table = 'sinho_book_category';
        $bookCategoryList = $this->model()->fetch_all($table);
        foreach ($bookCategoryList as & $_itemInfo) {
            $_itemInfo['type'] = buildSelectOptions(
                $list,
                'name',
                'id',
                $_itemInfo['type'],
                array()
            );
        }

        View::assign('itemOptions',
                     buildSelectOptions(
                         $list,
                         'name',
                         'id',
                         null,
                         array()
                    )
                );
        View::assign('itemList', $bookCategoryList);
        View::assign('menu_list', $this->filterAdminMenu($this->model('admin')->fetch_menu_list('admin/books','sinho_admin_menu') ) );
        View::assign('formAction', 'admin/ajax/books/save_subject/'); // 设置表单提交的链接
        View::output('admin/books/book_category_list');
    }

    /**
     * 新建/编辑 书稿
     */
    public function book_action()
    {
        $tags = array();
        $selected = null;

        if ($_GET['id']) { // 传递书稿id，编辑书稿
            $this->crumb(Application::lang()->_t('书稿编辑'), 'admin/books/');

            $itemInfo = $this->model('sinhoWorkload')->getBookById($_GET['id']);

            if (!$itemInfo) {
                H::redirect_msg(Application::lang()->_t('书稿不存在'), '/admin/books/');
            }

            View::assign('itemInfo', $itemInfo);
        } else {
            // 基于指定书稿复制成新书稿. 将书稿id清空， 只用其他数据
            if (isset($_GET['from_id'])) {
                $itemInfo = $this->model('sinhoWorkload')->getBookById($_GET['from_id']);
                if ($itemInfo) {
                    unset($itemInfo['id']);
                }
                View::assign('itemInfo', $itemInfo);
            }
            $this->crumb(Application::lang()->_t('添加书稿'), 'admin/books/');
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

        View::assign('menu_list', $this->filterAdminMenu($this->model('admin')->fetch_menu_list('admin/books','sinho_admin_menu') ) );

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

    /**
     * 组长分配书稿
     */
    public function leader_assign_book_action ()
    {
        if (!$this->user_info['permission'][self::PERMISSION_TEAM_LEADER]) {
            H::redirect_msg(Application::lang()->_t('你没有访问权限'), 'admin/');
        }

        if ($this->is_post()) {
            foreach ($_POST as $key => $val) {
                if ($key == 'start_date' OR $key == 'end_date') {
                    $val = base64_encode($val);
                } else  {
                    $val = rawurlencode($val);
                }

                $param[] = $key . '-' . $val;
            }

            H::ajax_json_output(Application::RSM(array(
                'url' => get_js_url('/admin/books/leader_assign_book/' . implode('__', $param))
            ), 1, null));
        }

        $this->per_page = 30;
        $this->crumb(Application::lang()->_t('书稿分配'), 'admin/books/leader_assign_book');
        //$this->user_info['uid'] = 10017;
        $bookIdList  = $this->model('sinhoWorkload')->fetch_page(sinhoWorkloadModel::WORKLOAD_TABLE, 'user_id = '.$this->user_info['uid'], 'id DESC', $_GET['page'], $this->per_page, true, 'book_id', true);
        $totalRows   = $this->model('sinhoWorkload')->found_rows();
        $itemList = array();
        $booksWorkload = array();
        $_tmpList = array();
        $bookIds   = array_column($bookIdList,'book_id');
        if ($bookIdList) {
            $itemList  = $this->model('sinhoWorkload')->getBookList('id IN (' . join(',', $bookIds) . ')', 'delivery_date DESC, id DESC', $this->per_page);
            $booksWorkload = $this->model('sinhoWorkload')->getWorkloadStatByBookIds ($bookIds, sinhoWorkloadModel::STATUS_VERIFIED);
            $_tmpList = $this->model('sinhoWorkload')->fetch_all (sinhoWorkloadModel::WORKLOAD_TABLE, 'book_id IN (' . join(',', $bookIds) . ') AND `status` <>' . sinhoWorkloadModel::STATUS_DELETE  );
        }
        $booksWorkloadAll = array();
        foreach ($_tmpList as $_info) {
            isset($booksWorkloadAll[$_info['book_id']]) OR $booksWorkloadAll[$_info['book_id']] = array();
            $booksWorkloadAll[$_info['book_id']][$_info['user_id']] = $_info['user_id'];
        }
        $userList = $this->model('sinhoWorkload')->getUserList('group_id = ' . $this->user_info['group_id'], 'uid DESC', PHP_INT_MAX);

        $url_param = array();
        foreach($_GET as $key => $val) {
            if (!in_array($key, array('app', 'c', 'act', 'page'))) {
                $url_param[] = $key . '-' . $val;
            }
        }

        View::assign('pagination', Application::pagination()->initialize(array(
            'base_url'   => get_js_url('/admin/books/leader_assign_book/') . implode('__', $url_param),
            'total_rows' => $totalRows,
            'per_page'   => $this->per_page
        ))->create_links());
        //$categoryList = $this->model('category')->getAllCategories('id');
        View::assign('itemsList', $itemList);
        View::assign('booksWorkload', $booksWorkload);
        View::assign('booksWorkloadAll', $booksWorkloadAll);

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
        //View::assign('itemOptions', $this->buildCategoryDropdownHtml('0', $selected, '--'));
        View::assign('totalRows', $totalRows);
        View::assign('amountPerPage', $this->per_page);

        View::import_js(G_STATIC_URL . '/js/bootstrap-multiselect.js');
        View::import_js('js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js');
        View::import_js('js/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js');
        View::import_js('js/icb_template_isinho.com.js');
        View::import_css('js/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css');
        View::import_css(G_STATIC_URL . '/css/bootstrap-multiselect.css');

        View::assign('menu_list', $this->filterAdminMenu($this->model('admin')->fetch_menu_list('admin/leader_assign_book','sinho_admin_menu')  ) );


        View::output('admin/books/leader_assign');
    }
}

/* EOF */
