<?php
defined('iCodeBang_Com') OR die('Access denied!');
/**
 * 自定义页面管理维护控制器
 * @author zhoumingxia
 *
 */
class page extends SinhoBaseController
{
    /**
     * 控制器 初始化
     * @see Controller::setup()
     */
    public function setup()
    {
        // 检查管理员 或者 页面管理 权限
        $this->checkPermission(AdminController::IS_ROLE_ADMIN | self::IS_SINHO_PAGE_ADMIN);

        $this->crumb(Application::lang()->_t('页面管理'), 'admin/page/');

        View::assign('menu_list', $this->filterAdminMenu($this->model('admin')->fetch_menu_list('admin/page/','sinho_admin_menu') ) );



        View::import_js(G_STATIC_URL . '/js/bootstrap-multiselect.js');
        View::import_css(G_STATIC_URL . '/css/bootstrap-multiselect.css');
    }


    /**
     * 页面列表
     */
    public function index_action()
    {
        $where = is_null(self::$domainId) ? null : 'belong_domain = ' . self::$domainId;
        $categoryList = $this->model('page')->fetch_all('page_category', $where);

        $categoryIds = array_column($categoryList, 'id');
        $categoryList= array_combine($categoryIds, $categoryList);
        View::assign('categoryList', $categoryList);
        // 根据ACTION的不同， 确定返回数据是内网还是外网
        $where = ACTION == 'index' ? 'publish_area <=1' : 'publish_area>=1';
        View::assign('page_list', $this->model('page')->fetch_page_list($_GET['page'], $this->per_page, $where));

        View::assign('pagination', Application::pagination()->initialize(array(
            'base_url'   => get_js_url('/admin/page/' . ACTION . '/'),
            'total_rows' => $this->model('page')->found_rows(),
            'per_page'   => $this->per_page
        ))->create_links());

        View::output('admin/page/list');
    }

    /**
     * 外网页面列表，和内网共用数据逻辑
     */
    public function outside_action ()
    {
        $this->index_action();
    }

    /**
     * 添加新页面表单
     */
    public function add_action()
    {
        $this->crumb(Application::lang()->_t('添加页面'), "admin/page/add/");

        $where = is_null(self::$domainId) ? null : 'belong_domain = ' . self::$domainId;
        $categoryList = $this->model('page')->fetch_all('page_category', $where);
        View::assign('categoryList', $categoryList);
        View::import_js('js/fileupload.js');
        if (get_setting('advanced_editor_enable') == 'Y') {
            import_editor_static_files();
        }
        View::assign('batchKey', $this->getBatchUploadAccessKey());

        View::output('admin/page/publish');
    }

    /**
     * 编辑页面表单
     */
    public function edit_action()
    {
        $this->crumb(Application::lang()->_t('编辑页面'), "admin/page/edit/");

        if (!$page_info = $this->model('page')->getPageById($_GET['id'])) {
            H::redirect_msg(Application::lang()->_t('页面不存在'), '/admin/page/');
        }
        $where = is_null(self::$domainId) ? null : 'belong_domain = ' . self::$domainId;
        $categoryList = $this->model()->fetch_all('page_category', $where);
        View::assign('categoryList', $categoryList);

        View::assign('page_info', $page_info);

        View::import_js('js/fileupload.js');
        if (get_setting('advanced_editor_enable') == 'Y') {
            import_editor_static_files();
        }

        View::output('admin/page/publish');
    }

    /**
     * 显示页面阅读记录
     */
    public function show_read_log_action ()
    {
        // 页面不存在，或者不是所属域，跳转到首页
        if (empty($_GET['id']) || !($pageInfo = $this->model('page')->getById($_GET['id']))
          || self::$domainId != $pageInfo['belong_domain'] ) {
            H::redirect_msg(Application::lang()->_t('页面不存在'), '/admin/');
        }

        $categoryInfo = $this->model()->fetch_one('page_category', '*', 'id = ' . $pageInfo['category_id']);
        $readList = $this->model()->fetch_page('page_read_record', 'page_id = ' . $_GET['id'], 'read_time', $_GET['page'], $this->per_page);
        $userList = array();
        if ($readList) {
            $userList = $this->model()->fetch_all('users', 'uid IN(' . join(',', array_column($readList, 'user_id')) . ')');
            $userList = array_combine(array_column($userList, 'uid'), array_column($userList, 'user_name') );
        }
        View::assign('read_list', $readList);
        View::assign('user_list', $userList);
        View::assign('page_info', $pageInfo);

        View::assign('pagination', Application::pagination()->initialize(array(
            'base_url'   => get_js_url('/admin/page/show_read_log/'),
            'total_rows' => $this->model('page')->found_rows(),
            'per_page'   => $this->per_page
        ))->create_links());

        View::output('admin/page/read_record_list');
    }
}
