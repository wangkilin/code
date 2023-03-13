<?php
$config[] = array(
    'id'        => 'admin/index',
    'title'     => _t('主页面板'), // 概述
    'cname'     => 'home',
    'url'       => 'admin/',
    'children'  => array(),
    'permission'=> '',
);

$config[] = array(
    'id'        => 'admin/book_manage',
    'title'     => _t('书稿管理'), // 概述
    'cname'     => 'folder-open',
    'children'  => array(

        array(
            'id'        => 'admin/books',
            'title'     => _t('书稿总管理'),
            'cname'     => 'reader',
            'url'       => 'admin/books/index/',
            'children'  => array(),
            'permission'=> SinhoBaseController::PERMISSION_BOOKLIST,
        ),

        array(
            'id'        => 'admin/team_books',
            'title'     => _t('书稿分管理'),
            'cname'     => 'reader',
            'url'       => 'admin/team_books/',
            'children'  => array(),
            'permission'=> SinhoBaseController::PERMISSION_TEAM_LEADER,
        ),
    ),
    'permission'=> '',
);

// $config[] = array(
//     'id'        => 'admin/books',
//     'title'     => _t('书稿总管理'),
//     'cname'     => 'reader',
//     'url'       => 'admin/books/index/',
//     'children'  => array(),
//     'permission'=> SinhoBaseController::PERMISSION_BOOKLIST,
// );

// $config[] = array(
//     'id'        => 'admin/team_books',
//     'title'     => _t('书稿分管理'),
//     'cname'     => 'reader',
//     'url'       => 'admin/team_books/',
//     'children'  => array(),
//     'permission'=> SinhoBaseController::PERMISSION_TEAM_LEADER,
// );

$config[] = array(
    'id'    => 'admin/workload',
    'title' => '工作量',
    'cname' => 'log',
    'children' => array(
        array (
            'id'        => 'admin/fill_list',
            'title'     => _t('填报工作量'),
            'cname'     => 'edit',
            'url'       => 'admin/fill_list/',
            'permission'=> SinhoBaseController::PERMISSION_FILL_WORKLOAD,
        ),
        array (
            'id'        => 'admin/workload/quarlity_list',
            'title'     => _t('质量考核记录'),
            'cname'     => 'order',
            'url'       => 'admin/workload/quarlity_list/',
            'permission'=> array(SinhoBaseController::PERMISSION_FILL_WORKLOAD,
                                 SinhoBaseController::PERMISSION_CHECK_WORKLOAD
                            ),
        ),
        array (
            'id'        => 'admin/team_workload/quarlity_list',
            'title'     => _t('成员质量考核记录'),
            'cname'     => 'ol',
            'url'       => 'admin/team_workload/quarlity_list/',
            'permission'=> SinhoBaseController::PERMISSION_TEAM_LEADER,
        ),
        array(
            'id'        => 'admin/verify_list',
            'title'     => _t('核算工作量'),
            'cname'     => 'verify',
            'url'       => 'admin/verify_list/',
            'permission'=> SinhoBaseController::PERMISSION_VERIFY_WORKLOAD,
        ),
        array(
            'id'        => 'admin/check_list',
            'title'     => _t('查看总工作量'),
            'cname'     => 'search',
            'url'       => 'admin/check_list/',
            'permission'=> SinhoBaseController::PERMISSION_CHECK_WORKLOAD,
        ),
        array( // 组长查看工作量
            'id'        => 'admin/team_workload',
            'title'     => _t('查看分工作量'),
            'cname'     => 'search',
            'url'       => 'admin/team_workload/check_list/',
            'permission'=> SinhoBaseController::PERMISSION_TEAM_LEADER,
        ),
    )
);
$config[] = array(
    'id'    => 'admin/administration',
    'title' => '行政&人事',
    'cname' => 'users',
    'children' => array(
        array (
            'id'        => 'admin/administration/ask_leave',
            'title'     => _t('考勤管理'),
            'cname'     => 'order',
            'url'       => 'admin/administration/ask_leave/',
            'permission'=> SinhoBaseController::PERMISSION_ADMINISTRATION,
        ),
        array(
            'id'        => 'admin/administration/holiday',
            'title'     => _t('作息安排'),
            'cname'     => 'ol',
            'url'       => 'admin/administration/holiday/',
            'permission'=> SinhoBaseController::PERMISSION_ADMINISTRATION,
        ),
        array(
            'id'        => 'admin/administration/editor',
            'title'     => _t('编辑设置'),
            'cname'     => 'user',
            'url'       => 'admin/administration/editor/',
            'permission'=> SinhoBaseController::PERMISSION_ADMINISTRATION,
        ),
        array(
            'id'        => 'admin/administration/group_list',
            'title'     => _t('组管理'),
            'cname'     => 'users',
            'url'       => 'admin/administration/group_list/',
            'permission'=> SinhoBaseController::PERMISSION_ADMINISTRATION,
        ),
        array(
            'id'        => 'admin/page/',
            'title'     => _t('页面管理'),
            'cname'     => 'log',
            'url'       => 'admin/page/',
            'permission'=> array(
                BaseController::PERMISSION_MAP[SinhoBaseController::IS_ROLE_ADMIN],            // 超级管理员
                SinhoBaseController::PERMISSION_PAGE_ADMIN,                                    // 动态页面管理员
            )
        ),
    )
);
$config[] = array(
    'id'    => 'admin/finance',
    'title' => '财务数据',
    'cname' => 'coin-yen',
    // 系统配置检查。 至多支持到二维数组
    'config' => array('sinho_feature_list'=>array('enable_module_finance'=>true)),
    'children' => array(
        array (
            'id'        => 'admin/finance/salary',
            'title'     => _t('工资表'),
            'cname'     => 'log',
            'url'       => 'admin/finance/salary/',
            'permission'=> SinhoBaseController::PERMISSION_ADMINISTRATION,
        ),
        array(
            'id'        => 'admin/finance/monthly_pay',
            'title'     => _t('收入支出'),
            'cname'     => 'transfer',
            'url'       => 'admin/finance/monthly_pay/',
            'permission'=> SinhoBaseController::PERMISSION_ADMINISTRATION,
        ),
        // array(
        //     'id'        => 'admin/finance/show_costing',
        //     'title'     => _t('成本核算'),
        //     'cname'     => 'check',
        //     'url'       => 'admin/finance/show_costing/',
        //     'permission'=> SinhoBaseController::PERMISSION_ADMINISTRATION,
        // ),
    )
);

/* EOF */
