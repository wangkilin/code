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
    }


    /**
     * 页面列表
     */
    public function index_action()
    {
        $where = is_null(self::$domainId) ? null : 'belong_domain = ' . self::$domainId;
        $categoryList = $this->model()->fetch_all('page_category', $where);
        $categoryIds = array_column($categoryList, 'id');
        $categoryList= array_combine($categoryIds, $categoryList);
        View::assign('categoryList', $categoryList);
        View::assign('page_list', $this->model('page')->fetch_page_list($_GET['page'], $this->per_page));

        View::assign('pagination', Application::pagination()->initialize(array(
            'base_url'   => get_js_url('/admin/page/'),
            'total_rows' => $this->model('page')->found_rows(),
            'per_page'   => $this->per_page
        ))->create_links());

        View::output('admin/page/list');
    }

    /**
     * 添加新页面表单
     */
    public function add_action()
    {
        $this->crumb(Application::lang()->_t('添加页面'), "admin/page/add/");

        $where = is_null(self::$domainId) ? null : 'belong_domain = ' . self::$domainId;
        $categoryList = $this->model()->fetch_all('page_category', $where);
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
}
