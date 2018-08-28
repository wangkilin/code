<?php
$config[] = array(
    'title' => _t('概述'),
    'cname' => 'home',
    'url' => 'admin/',
    'children' => array()
);

$config[] = array(
    'title' => _t('全局设置'),
    'cname' => 'setting',
    'children' => array(
        array(
            'id' => 'SETTINGS_SITE',
            'title' => _t('站点信息'),
            'url' => 'admin/settings/category-site'
        ),

        array(
            'id' => 'SETTINGS_REGISTER',
            'title' => _t('注册访问'),
            'url' => 'admin/settings/category-register'
        ),

        array(
            'id' => 'SETTINGS_FUNCTIONS',
            'title' => _t('站点功能'),
            'url' => 'admin/settings/category-functions'
        ),

        array(
            'id' => 'SETTINGS_CONTENTS',
            'title' => _t('内容设置'),
            'url' => 'admin/settings/category-contents'
        ),

        array(
            'id' => 'SETTINGS_INTEGRAL',
            'title' => _t('威望积分'),
            'url' => 'admin/settings/category-integral'
        ),

        array(
            'id' => 'SETTINGS_PERMISSIONS',
            'title' => _t('用户权限'),
            'url' => 'admin/settings/category-permissions'
        ),

        array(
            'id' => 'SETTINGS_MAIL',
            'title' => _t('邮件设置'),
            'url' => 'admin/settings/category-mail'
        ),

        array(
            'id' => 'SETTINGS_OPENID',
            'title' => _t('开放平台'),
            'url' => 'admin/settings/category-openid'
        ),

        array(
            'id' => 'SETTINGS_CACHE',
            'title' => _t('性能优化'),
            'url' => 'admin/settings/category-cache'
        ),

        array(
            'id' => 'SETTINGS_INTERFACE',
            'title' => _t('界面设置'),
            'url' => 'admin/settings/category-interface'
        )
    )
);

$config[] = array(
    'title' => _t('内容管理'),
    'cname' => 'reply',
    'children' => array(
//         array(
//             'id' => 'question/question_list',
//             'title' => _t('问题管理'),
//             'url' => 'admin/question/question_list/'
//         ),

//         array(
//             'id' => 'article/list',
//             'title' => _t('文章管理'),
//             'url' => 'admin/article/list/'
//         ),

        array(
            'id' => 'course',
            'title' => _t('教程管理'),
            'url' => 'admin/course/list/'
        ),

        array(
            'id' => 'category/list',
            'title' => _t('分类管理'),
            'url' => 'admin/category/list/'
        ),
//         array(
//             'id' => 'page',
//             'title' => _t('页面管理'),
//             'url' => 'admin/page/'
//         ),

//         array(
//             'id' => 'tag',
//             'title' => _t('标签管理'),
//             'url' => 'admin/tag/list/'
//         )
    )
);

// $config[] = array(
//     'title' => _t('审核管理'),
//     'cname' => 'report',
//     'children' => array(
//         array(
//             'id' => 'approval/list',
//             'title' => _t('内容审核'),
//             'url' => 'admin/approval/list/'
//         ),

//         array(
//             'id' => 'user/verify_approval_list',
//             'title' => _t('认证审核'),
//             'url' => 'admin/user/verify_approval_list/'
//         ),

//         array(
//             'id' => 'user/register_approval_list',
//             'title' => _t('注册审核'),
//             'url' => 'admin/user/register_approval_list/'
//         ),

//         array(
//             'id' => 'question/report_list',
//             'title' => _t('用户举报'),
//             'url' => 'admin/question/report_list/'
//         )
//     )
// );

// if (check_extension_package('project'))
// {
//     $config[] = array(
//         'title' => '活动管理',
//         'cname' => 'reply',
//         'children' => array(
//             array(
//                 'id' => 'project/project_list',
//                 'title' => '活动管理',
//                 'url' => 'admin/project/project_list/'
//             ),

//             array(
//                 'id' => 'project/approval_list',
//                 'title' => '活动审核',
//                 'url' => 'admin/project/approval_list/'
//             ),

//             array(
//                 'id' => 'project/order_list',
//                 'title' => '订单管理',
//                 'url' => 'admin/project/order_list/'
//             )
//         )
//     );
// }

$config[] = array(
    'title' => _t('内容设置'),
    'cname' => 'signup',
    'children' => array(
        array(
            'id' => 'nav_menu',
            'title' => _t('导航设置'),
            'url' => 'admin/nav_menu/'
        ),

        array(
            'id' => 'category/list',
            'title' => _t('分类管理'),
            'url' => 'admin/category/list/'
        ),

        array(
            'id' => 'topic/list',
            'title' => _t('话题(标签)管理'),
            'url' => 'admin/topic/list/'
        ),

        array(
            'id' => 'feature/list',
            'title' => _t('专题管理'),
            'url' => 'admin/feature/list/'
        ),

        array(
            'id' => 'help/list',
            'title' => _t('帮助中心'),
            'url' => 'admin/help/list/'
        )
    )
);

$config[] = array(
    'title' => _t('微信微博'),
    'cname' => 'share',
    'children' => array(
        array(
            'id' => 'weixin/accounts',
            'title' => _t('微信多账号管理'),
            'url' => 'admin/weixin/accounts/'
        ),

        array(
            'id' => 'weixin/mp_menu',
            'title' => _t('微信菜单管理'),
            'url' => 'admin/weixin/mp_menu/'
        ),

        array(
            'id' => 'weixin/reply',
            'title' => _t('微信自定义回复'),
            'url' => 'admin/weixin/reply/'
        ),

        array(
            'id' => 'weixin/third_party_access',
            'title' => _t('微信第三方接入'),
            'url' => 'admin/weixin/third_party_access/'
        ),

        array(
            'id' => 'weixin/qr_code',
            'title' => _t('微信二维码管理'),
            'url' => 'admin/weixin/qr_code/'
        ),

        array(
            'id' => 'weixin/sent_msgs_list',
            'title' => _t('微信消息群发'),
            'url' => 'admin/weixin/sent_msgs_list/'
        ),

        array(
            'id' => 'weibo/msg',
            'title' => _t('微博消息接收'),
            'url' => 'admin/weibo/msg/'
        ),

        array(
            'id' => 'edm/receiving_list',
            'title' => _t('邮件导入'),
            'url' => 'admin/edm/receiving_list/'
        )
    )
);

$config[] = array(
    'title' => _t('用户管理'),
    'cname' => 'user',
    'children' => array(
        array(
            'id' => 'user/list',
            'title' => _t('用户列表'),
            'url' => 'admin/user/list/'
        ),

        array(
            'id' => 'user/group_list',
            'title' => _t('用户组'),
            'url' => 'admin/user/group_list/'
        ),

        array(
            'id' => 'user/invites',
            'title' => _t('批量邀请'),
            'url' => 'admin/user/invites/'
        ),

        array(
            'id' => 'user/job_list',
            'title' => _t('职位设置'),
            'url' => 'admin/user/job_list/'
        )
    )
);

// $config[] = array(
//     'title' => _t('邮件群发'),
//     'cname' => 'inbox',
//     'children' => array(
//         array(
//             'id' => 'edm/tasks',
//             'title' => _t('任务管理'),
//             'url' => 'admin/edm/tasks/'
//         ),

//         array(
//             'id' => 'edm/groups',
//             'title' => _t('用户群管理'),
//             'url' => 'admin/edm/groups/'
//         )
//     )
// );

// $config[] = array(
//     'title' => _t('工具'),
//     'cname' => 'job',
//     'children' => array(
//         array(
//             'id' => 'tools',
//             'title' => _t('系统维护'),
//             'url' => 'admin/tools/',
//         )
//     )
// );
