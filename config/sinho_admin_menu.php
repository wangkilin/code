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
    'id'        => 'admin/books',
    'title'     => _t('书稿管理'),
    'cname'     => 'reader',
    'url'       => 'admin/books/index/',
    'children'  => array(),
    'permission'=> SinhoBaseController::PERMISSION_BOOKLIST,
);

$config[] = array(
    'id'        => 'admin/leader_assign_book',
    'title'     => _t('分配书稿'),
    'cname'     => 'reader',
    'url'       => 'admin/books/leader_assign_book/',
    'children'  => array(),
    'permission'=> SinhoBaseController::PERMISSION_TEAM_LEADER,
);

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
        array(
            'id'        => 'admin/verify_list',
            'title'     => _t('核算工作量'),
            'cname'     => 'verify',
            'url'       => 'admin/verify_list/',
            'permission'=> SinhoBaseController::PERMISSION_VERIFY_WORKLOAD,
        ),
        array(
            'id'        => 'admin/check_list',
            'title'     => _t('查看工作量'),
            'cname'     => 'search',
            'url'       => 'admin/check_list/',
            'permission'=> SinhoBaseController::PERMISSION_CHECK_WORKLOAD,
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
            'title'     => _t('请假管理'),
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
    )
);

/* EOF */
