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
       p.*
from icb_sinho_employee_workload p
left join icb_sinho_company_workload c
  on  p.`serial` = c.`serial`
  and p.`book_name` = c.`book_name`
  #and p.`proofreading_times` = c.`proofreading_times`
where belong_month = '202101'
; ## 匹配公司稿件和个人分配的稿件

select * from (
select
'公司' AS `数据源`,
c.`id` ,
c.`序号` ,
'' AS `责编`,
c.`发稿日期`,
c.`回稿日期` AS `日期`,
c.`系列` ,
c.`书名` ,
c.`校次` ,
'' AS `类别` ,
'' AS `遍次` ,
c.`目录` ,
c.`正文` ,
c.`目+正千字/页` ,
c.`答案` ,
c.`千字/页` ,
c.`试卷` ,
c.`试卷千字/页` ,
c.`试卷答案` ,
c.`试卷答案千字/页` ,
c.`课后作业` ,
c.`课后作业千字/页` ,
c.`功能册` ,
c.`功能册千字/页` ,
c.`功能册答案` ,
c.`功能册答案千字/页` ,
c.`难度系数` AS `系数`,
c.`字数（合计）` ,
c.`字数（未乘系数）` ,
2 AS `单价`,
'' AS `应发金额` ,
'' AS `考核奖罚比例` ,
'' AS `考核奖罚金额` ,
'' AS `实发金额` ,

c.`备注`
from company_workload c
inner join personal_workload p
   on p.`系列` = c.`系列`
   and p.`书名` = c.`书名`
   and p.`校次` = c.`校次`
group by c.id                     ## 获取分配出去的 公司稿件内容，作为基准，供校验; ## 获取分配出去的 公司稿件内容，作为基准，供校验

union

select
  '员工' AS `数据源`,
  p.`id` ,
  '' AS `序号`,
  p.`责编`,
  '' AS `发稿日期`,
  p.`结算日期` AS `日期`,
  p.`系列` ,
  p.`书名` ,
  p.`校次` ,
  p.`类别` ,
  p.`遍次` ,
  p.`目录` ,
  p.`正文` ,
  p.`千字/页` ,
  p.`答案` ,
  p.`答案千字/页` ,
  p.`试卷` ,
  p.`试卷千字/页` ,
  p.`试卷答案` ,
  p.`试卷答案千字/页` ,
  p.`课后作业` ,
  p.`课后作业千字/页` ,
  p.`功能册` ,
  p.`功能册千字/页` ,
  p.`功能册答案` ,
  p.`功能册答案千字/页` ,
  p.`系数` ,
  p.`核算总字数（千）` ,
  p.`核算总字数（千）`/ p.`系数`  AS`字数（未乘系数）` ,
  p.`单价` ,
  p.`应发金额` ,
  p.`考核奖罚比例` ,
  p.`考核奖罚金额` ,
  p.`实发金额` ,
  p.`备注（具体校稿页码）`
from `personal_workload` p
) as union_all
order by
  `系列` ,
  `书名` ,
  `校次` ,
  `类别` ,
  `遍次`
;


insert INto personal_workload select * from personal_workload_copy where `责编` = '史旭' or  `责编` = '黄惠莹' ;

update personal_workload set `对应月份` = 202011, `状态` = 1, `核算日期` = UNIX_TIMESTAMP()




show variables like 'table_open_cache'

show variables like 'max_connections'

insert into icb_sinho_employee_workload (
 `user_id` , # varchar(255) DEFAULT NULL COMMENT '编辑员工id， 对应到 user.id',
  `settlement_date` , #  varchar(255) DEFAULT NULL COMMENT '结算日期',
  `serial` , #  varchar(255) DEFAULT NULL,
  `book_name` , #  varchar(255) DEFAULT NULL COMMENT '书名',
  `proofreading_times` , #  varchar(255) DEFAULT NULL,
  `category` , #  varchar(255) DEFAULT NULL COMMENT '类别',
  `working_times` , #  varchar(255) DEFAULT NULL COMMENT '遍次',
  `content_table_pages` , #  varchar(255) DEFAULT NULL COMMENT '目录',
  `text_pages` , #  varchar(255) DEFAULT NULL COMMENT '正文',
  `text_table_chars_per_page` , #  varchar(255) DEFAULT NULL COMMENT '目录+正文千字/页',
  `answer_pages` , #  varchar(255) DEFAULT NULL COMMENT '答案',
  `answer_chars_per_page` , #  varchar(255) DEFAULT NULL COMMENT '答案千字/页',
  `test_pages` , #  varchar(255) DEFAULT NULL COMMENT '试卷',
  `test_chars_per_page` , #  varchar(255) DEFAULT NULL COMMENT '试卷千字/页',
  `test_answer_pages` , #  varchar(255) DEFAULT NULL COMMENT '试卷答案',
  `test_answer_chars_per_page` , #  varchar(255) DEFAULT NULL COMMENT '试卷答案千字/页',
  `exercise_pages` , #  varchar(255) DEFAULT NULL COMMENT '课后作业',
  `exercise_chars_per_page` , #  varchar(255) DEFAULT NULL COMMENT '课后作业千字/页',
  `function_book` , #  varchar(255) DEFAULT NULL COMMENT '功能册',
  `function_book_chars_per_page` , #  varchar(255) DEFAULT NULL COMMENT '功能册千字/页',
  `function_answer` , #  varchar(255) DEFAULT NULL COMMENT '功能册答案',
  `function_answer_chars_per_page` , #  varchar(255) DEFAULT NULL COMMENT '功能册答案千字/页',
  `weight` , #  varchar(255) DEFAULT NULL COMMENT '难度系数',
  `total_chars` , #  varchar(255) DEFAULT NULL COMMENT '核算总字数（千）',
  `price` , #  varchar(10) DEFAULT NULL COMMENT '单价',
  `payable_amount` , #  varchar(10) DEFAULT NULL COMMENT '应发金额',
  `assessment_rate` , #  varchar(11) DEFAULT NULL COMMENT '考核奖罚比例',
  `assessment_amount` , #  varchar(11) DEFAULT NULL COMMENT '考核奖罚金额',
  `actual_amount` , #  varchar(11) DEFAULT NULL COMMENT '实发金额',
  `remarks` , #  mediumtext COMMENT '备注',
  `belong_month` , #  int(6) DEFAULT NULL COMMENT '对应月份',
  `accounting_date` , #  int(11) DEFAULT NULL COMMENT '核算日期',
  `status`  #  tinyint(1) DEFAULT NULL COMMENT '状态： 1-已验证， 0-未验证',
)
select
   `责编` , #varchar(255) DEFAULT NULL,
  `结算日期`, # varchar(255) DEFAULT NULL,
  `系列` , #varchar(255) DEFAULT NULL,
  `书名` , #varchar(255) DEFAULT NULL,
  `校次` , #varchar(255) DEFAULT NULL,
  `类别` , #varchar(255) DEFAULT NULL,
  `遍次` , #varchar(255) DEFAULT NULL,
  `目录` , #varchar(255) DEFAULT NULL,
  `正文` , #varchar(255) DEFAULT NULL,
  `千字/页` , #varchar(255) DEFAULT NULL,
  `答案` , #varchar(255) DEFAULT NULL,
  `答案千字/页` , #varchar(255) DEFAULT NULL,
  `试卷` , #varchar(255) DEFAULT NULL,
  `试卷千字/页` , #varchar(255) DEFAULT NULL,
  `试卷答案` , #varchar(255) DEFAULT NULL,
  `试卷答案千字/页` , #varchar(255) DEFAULT NULL,
  `课后作业` , #varchar(255) DEFAULT NULL,
  `课后作业千字/页`, # varchar(255) DEFAULT NULL,
  `功能册` , #varchar(255) DEFAULT NULL,
  `功能册千字/页` , #varchar(255) DEFAULT NULL,
  `功能册答案` , #varchar(255) DEFAULT NULL,
  `功能册答案千字/页`, # varchar(255) DEFAULT NULL,
  `系数`, # varchar(255) DEFAULT NULL,
  `核算总字数（千）`, # varchar(255) DEFAULT NULL,
  `单价`, # varchar(255) DEFAULT NULL,
  `应发金额`, # varchar(255) DEFAULT NULL,
  `考核奖罚比例`, # varchar(255) DEFAULT NULL,
  `考核奖罚金额`, # varchar(255) DEFAULT NULL,
  `实发金额`, # varchar(255) DEFAULT NULL,
  `备注（具体校稿页码）`, # varchar(255) DEFAULT NULL,
  `对应月份`, # mediumint(6) NOT NULL DEFAULT '0',
  `核算日期`, # int(11) NOT NULL DEFAULT '0',
  `状态` # tinyint(1) NOT NULL DEFAULT '0'
from icb_sinho_personal_workload ;

update icb_sinho_employee_workload e, icb_sinho_company_workload c
set e.book_id = c.id
where e.`serial` = c.`serial` AND e.`book_name`=c.`book_name` AND e.`proofreading_times` = c.`proofreading_times`;


SELECT `icb_sinho_company_workload`.* FROM `icb_sinho_company_workload` WHERE (id = 85) LIMIT 1

 */

defined('iCodeBang_Com') OR die('Access denied!');

require_once __DIR__ . '/../SinhoBaseController.php';

class books extends SinhoBaseController
{
    /**
     * 教程文章列表
     */
    public function index_action()
    {
        $this->crumb(Application::lang()->_t('书稿管理'), 'admin/books/');

        if (!$this->user_info['permission'][self::PERMISSION_BOOKLIST]) {
            H::redirect_msg(Application::lang()->_t('你没有访问权限'), 'admin/');
        }

        View::assign('itemsList', $this->model('sinhoWorkload')->getBookList(null, 'delivery_date DESC, id DESC', $this->per_page, $_GET['page']));
        $totalRows     = $this->model('sinhoWorkload')->found_rows();

        $userList = $this->model('sinhoWorkload')->getUserList(null, 'uid DESC', PHP_INT_MAX);

        $this->crumb(Application::lang()->_t('书稿列表'), 'admin/books/index/');

        if ($_POST) {// 搜索教程
            foreach ($_POST as $key => $val) {
                if ($key == 'keyword') {
                    $val = rawurlencode($val);
                }

                $param[] = $key . '-' . $val;
            }

            H::ajax_json_output(Application::RSM(array(
                'url' => get_js_url('/admin/books/index/' . implode('__', $param))
            ), 1, null));
        }

        $selected = array();
        $where = array();

        if ($_GET['keyword']) {
            $where[] = "title LIKE '" . $this->model('course')->quote($_GET['keyword']) . "%'";
        }

        if ($_GET['views_min'] OR $_GET['views_min'] == '0') {
            $where[] = 'views >= ' . intval($_GET['views_min']);
        }

        $url_param = array();
        foreach($_GET as $key => $val) {
            if (!in_array($key, array('app', 'c', 'act', 'page'))) {
                $url_param[] = $key . '-' . $val;
            }
        }

        View::assign('pagination', Application::pagination()->initialize(array(
            'base_url'   => get_js_url('/admin/books/index/') . implode('__', $url_param),
            'total_rows' => $totalRows,
            'per_page'   => $this->per_page
        ))->create_links());
        $categoryList = $this->model('category')->getAllCategories('id');
        View::assign('itemOptions', buildSelectOptions($userList, 'user_name', 'uid' ) );
        //View::assign('itemOptions', $this->buildCategoryDropdownHtml('0', $selected, '--'));
        View::assign('totalRows', $totalRows);

        View::import_js(G_STATIC_URL . '/js/bootstrap-multiselect.js');
        View::import_js('js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js');
        View::import_js('js/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js');
        View::import_js('js/icb_template_isinho.com.js');
        View::import_css('js/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css');
        View::import_css(G_STATIC_URL . '/css/bootstrap-multiselect.css');

        View::assign('menu_list', $this->filterAdminMenu($this->model('admin')->fetch_menu_list(null,'sinho_admin_menu')  ) );


        View::output('admin/books/list');
    }

    /**
     * 新建编辑书稿
     */
    public function book_action()
    {
        $tags = array();
        $selected = null;
        if ($_GET['id']) {
            $this->crumb(Application::lang()->_t('书稿编辑'), 'admin/books/');

            $itemInfo = $this->model('sinhoWorkload')->getBookById($_GET['id']);

            if (!$itemInfo) {
                H::redirect_msg(Application::lang()->_t('书稿不存在'), '/admin/books/');
            }

            View::assign('itemInfo', $itemInfo);
        } else {
            $this->crumb(Application::lang()->_t('添加书稿'), 'admin/books/');
        }

        View::assign('menu_list', $this->filterAdminMenu($this->model('admin')->fetch_menu_list('admin/books','sinho_admin_menu') ) );

        View::output('admin/books/book');
    }

    public function import_action ()
    {
        View::assign('menu_list', $this->filterAdminMenu($this->model('admin')->fetch_menu_list('admin/books','sinho_admin_menu') ) );


        View::import_js(G_STATIC_URL . '/js/bootstrap-multiselect.js');
        View::import_css(G_STATIC_URL . '/css/bootstrap-multiselect.css');
        View::import_js('js/fileupload.js');
        View::output('admin/books/import');
    }
}

/* EOF */
