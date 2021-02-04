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
    'permission'=> sinhoWorkloadModel::PERMISSION_MODIFY_MANUSCRIPT_PARAM,
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
            'permission'=> sinhoWorkloadModel::PERMISSION_FILL_WORKLOAD,
        ),
        array(
            'id'        => 'admin/verify_list',
            'title'     => _t('核算工作量'),
            'cname'     => 'verify',
            'url'       => 'admin/verify_list/',
            'permission'=> sinhoWorkloadModel::PERMISSION_VERIFY_WORKLOAD,
        ),
        array(
            'id'        => 'admin/check_list',
            'title'     => _t('查看工作量'),
            'cname'     => 'search',
            'url'       => 'admin/check_list/',
            'permission'=> sinhoWorkloadModel::PERMISSION_CHECK_WORKLOAD,
        ),
    )
);

/* EOF */
