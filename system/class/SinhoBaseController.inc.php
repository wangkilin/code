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

/**
 * 前台控制器
 */
class SinhoBaseController extends BaseController
{
    CONST LEAVE_TYPE_PRIVATE                = 1  ; // '事假',
    CONST LEAVE_TYPE_SICK                   = 2  ; // '病假',
    CONST LEAVE_TYPE_ANNUAL                 = 3  ; // '年假',
    CONST LEAVE_TYPE_WEDDING                = 4  ; // '婚假',
    CONST LEAVE_TYPE_MATERNITY              = 5  ; // '产假',
    CONST LEAVE_TYPE_PERIOD                 = 6  ; // '生理',
    CONST LEAVE_TYPE_BODY_CHECK             = 8  ; // '产检',
    CONST LEAVE_TYPE_FUNERAL                = 9  ; // '丧假',
    CONST LEAVE_TYPE_NO_REASON              = 20 ; // '旷工',
    CONST LEAVE_TYPE_OVERTIME_REST          = 40 ; // '调休',
    CONST LEAVE_TYPE_OVERTIME               = 50 ; // '++ 加班',
    CONST LEAVE_TYPE_WEEKEND_WORKLOAD       = 51 ; // 周末带稿
    CONST LEAVE_TYPE_HOMEWORK               = 55 ; // '🏠居家办公'

    protected $leaveTypeList = array (
        self::LEAVE_TYPE_PRIVATE            => array('name' => '事假',          'icon' => 'icon-private-leave'),
        self::LEAVE_TYPE_SICK               => array('name' => '病假',          'icon' => 'icon-sick-leave'),
        self::LEAVE_TYPE_ANNUAL             => array('name' => '年假',          'icon' => 'icon-annual-leave'),
        self::LEAVE_TYPE_WEDDING            => array('name' => '婚假',          'icon' => 'icon-wedding-leave'),
        self::LEAVE_TYPE_MATERNITY          => array('name' => '产假',          'icon' => 'icon-maternity-leave'),
        self::LEAVE_TYPE_PERIOD             => array('name' => '生理假',        'icon' => 'icon-period-leave'),
        self::LEAVE_TYPE_BODY_CHECK         => array('name' => '产检',          'icon' => 'icon-body-check'),
        self::LEAVE_TYPE_FUNERAL            => array('name' => '丧假',          'icon' => 'icon-funeral-leave'),
        self::LEAVE_TYPE_NO_REASON          => array('name' => '旷工',          'icon' => 'icon-leave'),
        self::LEAVE_TYPE_OVERTIME_REST      => array('name' => '调休',          'icon' => 'icon-transfer'),
        self::LEAVE_TYPE_OVERTIME           => array('name' => '++ 加班',       'icon' => 'icon-add-to-list'),
        self::LEAVE_TYPE_WEEKEND_WORKLOAD   => array('name' => '周末带稿',       'icon' => 'icon-attach'),
        self::LEAVE_TYPE_HOMEWORK           => array('name' => '🏠居家办公',     'icon' => 'icon-home'),
    );

    const SUBJECT_LIST = array(
        1 => array('name'=>"语文", 'keyword'=>array('语文','7语','8语','9语','七语','八语','九语','语',)),
        2 => array('name'=>"数学", 'keyword'=>array('数学','7数','8数','9数','七数','八数','九数','数',)),
        3 => array('name'=>"英语", 'keyword'=>array('英语','7英','8英','9英','七英','八英','九英','英',)),
        4 => array('name'=>"物理", 'keyword'=>array('物理','7物','8物','9物','七物','八物','九物','物',)),
        5 => array('name'=>"化学", 'keyword'=>array('化学','7化','8化','9化','七化','八化','九化','化',)),
        6 => array('name'=>"地理", 'keyword'=>array('地理','7地','8地','9地','七地','八地','九地','地',)),
        7 => array('name'=>"政治", 'keyword'=>array('政治','7政','8政','9政','七政','八政','九政','政','7道','8道','9道','七道','八道','九道','道法')),
        8 => array('name'=>"历史", 'keyword'=>array('历史','7历','8历','9历','七历','八历','九历','历',)),
        9 => array('name'=>"生物", 'keyword'=>array('生物','7生','8生','9生','七生','八生','九生','生',)),
    );
    const SUBJECT_CATEGORIZE = array (
        array(1,3,6,7,8), array(2,4,5,9)

    );

    const SUBJECT_CATEGORIZE_ARTS = 1;    // 文科
    const SUBJECT_CATEGORIZE_SCIENCE = 2; // 理科
    const SUBJECT_CATEGORIZE_LIST = array (
        self::SUBJECT_CATEGORIZE_ARTS        => '文科',
        self::SUBJECT_CATEGORIZE_SCIENCE     => '理科',
    );

    const PERMISSION_VERIFY_WORKLOAD    = 'sinho_verify_workload';
    const PERMISSION_FILL_WORKLOAD      = 'sinho_fill_workload';
    const PERMISSION_BOOKLIST           = 'sinho_modify_manuscript_param';
    const PERMISSION_CHECK_WORKLOAD     = 'sinho_check_workload';
    const PERMISSION_ADMINISTRATION     = 'sinho_administration';
    const PERMISSION_SUBJECT            = 'sinho_subject';
    const PERMISSION_TEAM_LEADER        = 'sinho_permission_team_leader';
    const PERMISSION_PAGE_ADMIN         = 'sinho_permission_page_admin';  // 页面内容管理权限

    const SINHO_PERMISSION_LIST = array(
        self::PERMISSION_FILL_WORKLOAD,
        self::PERMISSION_BOOKLIST,
        self::PERMISSION_VERIFY_WORKLOAD,
        self::PERMISSION_CHECK_WORKLOAD,
        self::PERMISSION_ADMINISTRATION,
        self::PERMISSION_SUBJECT,
        self::PERMISSION_TEAM_LEADER,
        self::PERMISSION_PAGE_ADMIN,
    );

    /**
     * 书稿管理权限键值
     */
    const SINHO_PERMISSION_BOOKLIST_ALL             = 1;
    const SINHO_PERMISSION_BOOKLIST_ADD             = 2;
    const SINHO_PERMISSION_BOOKLIST_EDIT            = 3;
    const SINHO_PERMISSION_BOOKLIST_DELETE          = 4;
    const SINHO_PERMISSION_BOOKLIST_SET_LEVEL       = 5;
    const SINHO_PERMISSION_BOOKLIST_RETURN_DATE     = 6;
    const SINHO_PERMISSION_BOOKLIST_CHECK_WORKLOAD  = 7;
    const SINHO_PERMISSION_BOOKLIST_SET_PAY         = 8;
    const SINHO_PERMISSION_BOOKLIST_SET_PREPAY      = 9;
    const SINHO_PERMISSION_BOOKLIST_ASSIGN          = 10;
    const SINHO_PERMISSION_BOOKLIST_ADMIN_REMARKS   = 11;
    const SINHO_PERMISSION_BOOKLIST_SET_SUBJECT     = 12;
    /**
     * 书稿管理权限列表
     */
    const SINHO_PERMISSION_BOOKLIST_LIST = array(
        self::SINHO_PERMISSION_BOOKLIST_ALL             => '全部权限',
        self::SINHO_PERMISSION_BOOKLIST_ADD             => '新建',
        self::SINHO_PERMISSION_BOOKLIST_EDIT            => '编辑',
        self::SINHO_PERMISSION_BOOKLIST_DELETE          => '删除',
        self::SINHO_PERMISSION_BOOKLIST_SET_LEVEL       => '设置阶段',
        self::SINHO_PERMISSION_BOOKLIST_RETURN_DATE     => '设置返稿日期',
        self::SINHO_PERMISSION_BOOKLIST_CHECK_WORKLOAD  => '查看工作量',
        self::SINHO_PERMISSION_BOOKLIST_SET_PAY         => '设置支付状态',
        self::SINHO_PERMISSION_BOOKLIST_SET_PREPAY      => '标记对账',
        self::SINHO_PERMISSION_BOOKLIST_ASSIGN          => '分配任务',
        self::SINHO_PERMISSION_BOOKLIST_ADMIN_REMARKS   => '管理员备注',
        self::SINHO_PERMISSION_BOOKLIST_SET_SUBJECT     => '设置学科',
    );

    // const PERMISSION_BOOKLIST        = SinhoBaseController::PERMISSION_MODIFY_MANUSCRIPT_PARAM;
    // const PERMISSION_VERIFY_WORKLOAD = SinhoBaseController::PERMISSION_VERIFY_WORKLOAD;
    // const PERMISSION_FILL_WORKLOAD   = SinhoBaseController::PERMISSION_FILL_WORKLOAD;
    // const PERMISSION_CHECK_WORKLOAD  = SinhoBaseController::PERMISSION_CHECK_WORKLOAD;

    const IS_SINHO_BOOK_ADMIN = 0x04; // 乐读图书编辑
    const IS_SINHO_FILL_WORKLOAD = 0x08; // 填充工作量
    const IS_SINHO_VERIFY_WORKLOAD = 0x10; // 核对工作量
    const IS_SINHO_CHECK_WORKLOAD  = 0x20; // 查看工作量
    const IS_SINHO_ADMIN           = 0x40; // 行政管理
    const IS_SINHO_TEAM_LEADER     = 0x80; // 是否是组长
    const IS_SINHO_PAGE_ADMIN      = 0x100; // 是否是页面内容管理人员

    const PERMISSION_MAP = array (
        self::IS_ROLE_ADMIN              => parent::PERMISSION_MAP[parent::IS_ROLE_ADMIN],
        self::IS_ROLE_MODERATOR          => parent::PERMISSION_MAP[parent::IS_ROLE_MODERATOR],
        self::IS_SINHO_BOOK_ADMIN        => self::PERMISSION_BOOKLIST,
        self::IS_SINHO_VERIFY_WORKLOAD   => self::PERMISSION_VERIFY_WORKLOAD,
        self::IS_SINHO_FILL_WORKLOAD     => self::PERMISSION_FILL_WORKLOAD,
        self::IS_SINHO_CHECK_WORKLOAD    => self::PERMISSION_CHECK_WORKLOAD,
        self::IS_SINHO_ADMIN             => self::PERMISSION_ADMINISTRATION,
        self::IS_SINHO_TEAM_LEADER       => self::PERMISSION_TEAM_LEADER,
        self::IS_SINHO_PAGE_ADMIN        => self::PERMISSION_PAGE_ADMIN,
    );

    public $user_id;
    public $user_info;

    public $per_page = 20; // 每页显示多少条

    protected $hostConfig = null;

    public function __construct($process_setup=true)
    {
        parent::__construct(false); // 不在父级构造方法里执行 setup 方法。  后面会执行

        try {
            $this->hostConfig = Application::config()->get('__HOST__');
        } catch (Exception $e) {
            $this->hostConfig = new stdClass();
        }

        if ($this->user_info['uid']) {
            $userAttributes = $this->model()->fetch_all('users_attribute', 'uid = ' . $this->user_info['uid']);
            foreach($userAttributes as $_tmpInfo) {
                if (strpos($_tmpInfo['attr_key'], 'sinho_permission') === 0) {
                    $this->user_info['permission'][$_tmpInfo['attr_key']] = $_tmpInfo['attr_value'];
                } else {
                    $this->user_info[$_tmpInfo['attr_key']] = $_tmpInfo['attr_value'];
                }
            }
        }

        if ($_GET['app'] != 'admin' && $_GET['app'] != 'admin/ajax') {
            return false;
        }

        View::import_clean();

        if (defined('SYSTEM_LANG'))
        {
            View::import_js(base_url() . '/language/' . SYSTEM_LANG . '.js');
        }

        if (HTTP::is_browser('ie', 8)) {
            View::import_js('js/jquery.js');
        }  else {
            View::import_js('js/jquery.2.js');
        }

        View::import_js(array(
            'admin/js/aws_admin.js',
            'admin/js/aws_admin_template.js',
            'js/jquery.form.js',
            'js/framework.js',
            'admin/js/global.js',
            'js/global.js',
            'js/icb_template.js',
            'js/app.js',
        ));

        View::import_css(array(
            'admin/css/common.css'
        ));

        if (in_array($_GET['act'], array(
            'login',
            'login_process',
        )))
        {
            return true;
        }

        $hasSinhoPermission = false;
        foreach ((array)$this->user_info['permission'] as $_key=>$_value) {
            if (strpos($_key, 'sinho_') ===0) {
                $hasSinhoPermission = true;
                break;
            }
        }

        $this->model('admin')->set_admin_login($this->user_info['uid']);
        $admin_info = json_decode(Application::crypt()->decode(Application::session()->admin_login), true);

        if ($admin_info['uid'])
        {
            if ($admin_info['uid'] != $this->user_id OR $admin_info['UA'] != $_SERVER['HTTP_USER_AGENT'])
            {
                // 将旧登录信息失效
                $this->model('account')->logout();

                if ($_POST['_post_type'] == 'ajax') {
                    H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('会话超时, 请重新登录')));
                } else {
                    H::redirect_msg(Application::lang()->_t('会话超时, 请重新登录'), '/account/login/url-' . base64_current_path());
                }
            }
        }
        else
        {
            if ($_POST['_post_type'] == 'ajax') {
                H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('会话超时, 请重新登录')));

            } else {

                // 将旧信息失效
                $this->model('account')->logout();
                HTTP::redirect('/account/login/return_url-' . base64_current_path());
            }
        }

        View::assign('user_info', $this->user_info);
        //View::assign('menu_list', $this->model('admin')->fetch_menu_list(null, 'sinho_admin_menu'));

        $this->setup();

    }

    /**
     * 根据菜单中的权限， 过滤掉无权限访问的菜单
     */
    public function filterAdminMenu ($adminMenuList)
    {
        try {
            $hostConfig = Application::config()->get('__HOST__');
        } catch (Exception $e) {
            $hostConfig = new stdClass();
        }

        $newAdminMenu = array();
        foreach ($adminMenuList as $_key => $_menuInfo) {
            if (isset($_menuInfo['config'])) {
                foreach ($_menuInfo['config'] as $_configKey=>$_configValue) {
                    if (is_array($_configValue)) {
                        foreach ($_configValue as $_configKey2 => $_configValue2) {
                            if ($hostConfig->$_configKey[$_configKey2] !== $_configValue2) {
                                $hasPermission = false;
                                continue 3;
                            }
                        }
                    } else {
                        if ($hostConfig->$_configKey !== $_configValue) {
                            $hasPermission = false;
                            continue 2;
                        }
                    }
                }
            }
            if ($_menuInfo['children']) {
                $_children = array();
                foreach ($_menuInfo['children'] as $_key2=>$_menuInfo2) {

                    if (isset($_menuInfo2['config'])) {
                        foreach ($_menuInfo['config'] as $_configKey=>$_configValue) {
                            if (is_array($_configValue)) {
                                foreach ($_configValue as $_configKey2 => $_configValue2) {
                                    if ($hostConfig->$_configKey[$_configKey2] !== $_configValue2) {
                                        $hasPermission = false;
                                        continue 3;
                                    }
                                }
                            } else {
                                if ($hostConfig->$_configKey !== $_configValue) {
                                    $hasPermission = false;
                                    continue 2;
                                }
                            }
                        }
                    }
                    empty($_menuInfo2['permission']) OR settype($_menuInfo2['permission'], 'array');
                    $hasPermission = false;

                    foreach ($_menuInfo2['permission'] as $_tmpPermissionName) {
                        if ($this->user_info['permission'][$_tmpPermissionName]
                          && (!property_exists($hostConfig, 'sinho_permission') || !isset($hostConfig->sinho_permission[$_tmpPermissionName]) || $hostConfig->sinho_permission[$_tmpPermissionName])) {
                            $hasPermission = true;
                            break;
                        }
                    }
                    /*
                     * 判断是否检测用户属性设置。如果设置了用户属性检查，需要进行对比 */
                    empty($_menuInfo2['user_attribute']) OR settype($_menuInfo2['user_attribute'], 'array');
                    foreach ($_menuInfo2['user_attribute'] as $_tmpAttributeName=>$_tmpAttributeValue) {
                        if ($this->user_info[$_tmpAttributeName]==$_tmpAttributeValue) {
                            $hasPermission = true;
                            break;
                        }
                    }

                    if ((!$_menuInfo2['permission']&&!$_menuInfo2['user_attribute']) || $hasPermission)  {

                        $_children[] = $_menuInfo2;
                    }
                }

                if ($_children) {
                    $_menuInfo['children'] = $_children;
                    $newAdminMenu[$_key] = $_menuInfo;
                }

                continue;

            }


            empty($_menuInfo['permission']) OR settype($_menuInfo['permission'], 'array');
            $hasPermission = false;
            foreach ($_menuInfo['permission'] as $_tmpPermissionName) {
                if ($this->user_info['permission'][$_tmpPermissionName]
                  && (!property_exists($hostConfig, 'sinho_permission')
                    || !isset($hostConfig->sinho_permission[$_tmpPermissionName])
                    || $hostConfig->sinho_permission[$_tmpPermissionName])) {
                    $hasPermission = true;
                    break;
                }
            }
            empty($_menuInfo['user_attribute']) OR settype($_menuInfo['user_attribute'], 'array');
            foreach ($_menuInfo['user_attribute'] as $_tmpAttributeName=>$_tmpAttributeValue) {
                if ($this->user_info[$_tmpAttributeName]==$_tmpAttributeValue) {
                    $hasPermission = true;
                    break;
                }
            }
            if ($_menuInfo['permission'] && !$hasPermission) {
            } else {
                $newAdminMenu[$_key] = $_menuInfo;
            }
        }

        foreach ($newAdminMenu as & $_itemInfo) {
            if (isset($_itemInfo['children']) && count($_itemInfo['children'])==1 && empty($_itemInfo['url']) ) {
                $_itemInfo = $_itemInfo['children'][0];
            }
        }

        return $newAdminMenu;
    }

    /**
     * 将书稿分配给编辑。 可以重新分配已经分配过的书稿。
     * 需要检查书稿是否已经记录了工作量
     */
    public function assignBookToEditor ($bookId, $userIds=array())
    {
        // 将已分配的数据移除；
        $assigned = (array) $this->model('sinhoWorkload')->fetch_all(sinhoWorkloadModel::WORKLOAD_TABLE, 'book_id = ' . intval($bookId) .' AND status <> ' . sinhoWorkloadModel::STATUS_DELETE );
        $assignedUserIds = array_column($assigned, 'user_id');
        $toBeRemoved = array();
        foreach ($assigned as $_itemInfo) {
            if (in_array($_itemInfo['user_id'], $userIds)) {
                continue;
            }
            // 已经有工作量填充的记录， 不能移除。
            if ($_itemInfo['content_table_pages']!=0 || $_itemInfo['text_pages']!=0 || $_itemInfo['answer_pages']!=0
              || $_itemInfo['test_pages']!=0 || $_itemInfo['test_answer_pages']!=0 || $_itemInfo['exercise_pages']!=0
              || $_itemInfo['function_book']!=0 || $_itemInfo['function_answer']!=0  ) {
                $userInfo = $this->model('account')->getUserById($_itemInfo['user_id']);
                H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('*'.$userInfo['user_name'] . '* 已经在书稿上开始了工作，不能取消分配！')));
            }

            $toBeRemoved[] = $_itemInfo['id'];
        }
        if ($toBeRemoved) { // 取消绑定， 设置成删除状态
            // $this->model('sinhoWorkload')
            //      ->update(sinhoWorkloadModel::WORKLOAD_TABLE,
            //                 array('status' => sinhoWorkloadModel::STATUS_DELETE),
            //                 'id IN(' . join(',', $toBeRemoved). ')' // AND status = ' . sinhoWorkloadModel::STATUS_RECORDING
            //         );
            $this->model('sinhoWorkload')->deleteByIds ($toBeRemoved, sinhoWorkloadModel::WORKLOAD_TABLE);
        }
        // 获取兼职用户id列表
        $parttimeUsers = array();
        if($userIds) {
            $parttimeUsers = $this->model()->fetch_all('users_attribute', 'attr_key = "sinho_is_parttime" AND attr_value="1"');
            $parttimeUsers = array_combine(array_column($parttimeUsers,'uid'), array_column($parttimeUsers,'attr_value'));
        }

        foreach ($userIds as $_userId) {
            if (! in_array($_userId, $assignedUserIds)) {
                $set = array(
                    'book_id'       => $bookId,
                    'user_id'       => $_userId,
                    'status'        => sinhoWorkloadModel::STATUS_RECORDING,
                    'add_time'      => time(),
                    // 将兼职用户的工作量，标记
                    'is_parttime'   => intval($parttimeUsers[$_userId])
                );
                $this->model('sinhoWorkload')->insert(sinhoWorkloadModel::WORKLOAD_TABLE, $set);
            }
        }

    }

    /**
     * 保存书稿
     */
    public function saveBook($bookInfo)
    {
        if (!$bookInfo['serial'] && !$bookInfo['book_name'] && !$bookInfo['proofreading_times']) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('请输入参数')));
        }
        // 查找是否已存在相同书稿。 已存在相同书稿， 提示
        $itemInfo = Application::model('sinhoWorkload')->fetch_row(
            sinhoWorkloadModel::BOOK_TABLE,
            'delivery_date         = "' . $this->model('sinhoWorkload')->quote($bookInfo['delivery_date']) . '"
            AND category           = "' . $this->model('sinhoWorkload')->quote($bookInfo['category']) . '"
            AND serial             = "' . $this->model('sinhoWorkload')->quote($bookInfo['serial']) . '"
            AND book_name          = "' . $this->model('sinhoWorkload')->quote($bookInfo['book_name'] ) .'"
            AND proofreading_times = "' . $this->model('sinhoWorkload')->quote($bookInfo['proofreading_times']) .'"'

        ) ;
        if ($itemInfo && $itemInfo['id']!=$bookInfo['id']) {
            H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('已存在系列、书名、校次完成相同的书稿')));
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

        if ($bookInfo['id']) { // 更新
            $_checkKeys = array (
                'content_table_pages',		// varchar(255) DEFAULT NULL COMMENT '目录',
                'text_pages',		// varchar(255) DEFAULT NULL COMMENT '正文',
                'text_table_chars_per_page',		// varchar(255) DEFAULT NULL COMMENT '目录+正文千字/页',
                'answer_pages',		// varchar(255) DEFAULT NULL COMMENT '答案',
                'answer_chars_per_page',		// varchar(255) DEFAULT NULL COMMENT '答案千字/页',
                'test_pages',		// varchar(255) DEFAULT NULL COMMENT '试卷',
                'test_chars_per_page',		// varchar(255) DEFAULT NULL COMMENT '试卷千字/页',
                'test_answer_pages',		// varchar(255) DEFAULT NULL COMMENT '试卷答案',
                'test_answer_chars_per_page',		// varchar(255) DEFAULT NULL COMMENT '试卷答案千字/页',
                'exercise_pages',		// varchar(255) DEFAULT NULL COMMENT '课后作业',
                'exercise_chars_per_page',		// varchar(255) DEFAULT NULL COMMENT '课后作业千字/页',
                'function_book',		// varchar(255) DEFAULT NULL COMMENT '功能册',
                'function_book_chars_per_page',		// varchar(255) DEFAULT NULL COMMENT '功能册千字/页',
                'function_answer',		// varchar(255) DEFAULT NULL COMMENT '功能册答案',
                'function_answer_chars_per_page',		// varchar(255) DEFAULT NULL COMMENT '功能册答案千字/页',
                'weight',		// varchar(255) DEFAULT NULL COMMENT '难度系数',

            );
            // 检查关键数据是否存在变更， 如果存在变更， 就认为稿件不是导入的， 以后不能修改;
            foreach ($_checkKeys as $_checkKey) {
                if ($itemInfo[$_checkKey]!=$bookInfo[$_checkKey]) {
                    $bookInfo['is_import'] = 0; // 书稿设置为手动录入， 非导入

                    break;
                }
            }
            Application::model('sinhoWorkload')->updateBook(intval($bookInfo['id']), $bookInfo);

        } else { // 添加
            $bookInfo['is_import'] = 0; // 书稿设置为手动录入， 非导入

            $bookInfo['user_id']       = $this->user_id;
            $bookInfo['delivery_date'] = strtotime($bookInfo['delivery_date'])>0 ? date('Y-m-d', strtotime($bookInfo['delivery_date'])) : date('Y-m-d');


            // 获取书稿所属学科id
            if (! $bookInfo['category_id']) {
                $bookInfo['category_id'] = null;
                foreach ($keywordSubjectList as $_keyword=>$_subjectCode) {
                    if (strpos($bookInfo['book_name'], $_keyword)!==false) {
                        $bookInfo['category_id'] = $_subjectCode;
                        break;
                    }
                }
            }

            $bookInfo['id'] = Application::model('sinhoWorkload')->addBook($bookInfo);
        }

        return $bookInfo['id'];
    }
}



/* EOF */
