<?php
// 系统根路径
defined('ROOT_PATH') OR define('ROOT_PATH', realpath(dirname(__DIR__)) . DIRECTORY_SEPARATOR);
// 配置文件路径
define('CONF_PATH', ROOT_PATH . 'config' . DIRECTORY_SEPARATOR);


isset($config) OR $config = array();
// 金属解析
$config['metal'] = array(
    'url_prefix'    => 'https://hq.smm.cn',
    'host'          => 'hq.smm.cn',
);

// 文章解析
$config['51jb']  = array (
    'more_page'     => array (
                        'css' => array(
                            'max' => 3,
                            'm_url' => 'https://m.jb51.net/css/list591_%d.html',
                            'url' => 'https://www.jb51.net/css/list591_%d.htm',
                            'category_id' => 34,
                        ),
                        'html5' => array(
                            'max' => 38,
                            'm_url' => 'https://m.jb51.net/html5/list551_%d.html',
                            'url' => 'https://www.jb51.net/html5/list551_%d.htm',
                            'category_id' => 35,
                        ),
                        'html' => array(
                            // last https://m.jb51.net/web/697161.html
                            'max' => 81,
                            'm_url' => 'https://m.jb51.net/web/list220_%d.html',
                            'url' => 'https://www.jb51.net/web/list220_%d.htm',
                            'category_id' => 19,
                        ),
                      ),
    'test_code'     => <<<EOF

EOF
,
    'list_dir'      => '/Users/zhoumingxia/git/sites/www.jb51.net/list',
    'article_dir'   => '/Users/zhoumingxia/git/sites/www.jb51.net/article',
    'user_id'       => 1, // 抓取到的文章， 绑定到的作者id

    'categoryList'   => array (
        'ASP编程' =>
        array (
        '_id' => '20',
        '小偷/采集' =>
        array (
            '_id' => '',
        ),
        '木马相关' =>
        array (
            '_id' => '',
        ),
        '存储过程' =>
        array (
            '_id' => '',
        ),
        '应用技巧' =>
        array (
            '_id' => '',
        ),
        'ASP CLASS类' =>
        array (
            '_id' => '',
        ),
        '数据库相关' =>
        array (
            '_id' => '',
        ),
        'ASP基础' =>
        array (
            '_id' => '',
        ),
        'FSO专题' =>
        array (
            '_id' => '',
        ),
        ),
        'JavaScript' =>
        array (
        '_id' => '21',
        '广告代码' =>
        array (
            '_id' => '',
        ),
        '表单特效' =>
        array (
            '_id' => '',
        ),
        'javascript类库' =>
        array (
            '_id' => '22',
            'YUI.Ext相关' =>
            array (
            '_id' => '23',
            ),
            'prototype' =>
            array (
            '_id' => '24',
            ),
            'jquery' =>
            array (
            '_id' => '25',
            ),
            'dojo' =>
            array (
            '_id' => '26',
            ),
            'json' =>
            array (
            '_id' => '27',
            ),
            'lib_js' =>
            array (
            '_id' => '',
            ),
            'js面向对象' =>
            array (
            '_id' => '',
            ),
            'extjs' =>
            array (
            '_id' => '28',
            ),
            'Mootools' =>
            array (
            '_id' => '29',
            ),
            '其它' =>
            array (
            '_id' => '',
            ),
            'Seajs' =>
            array (
            '_id' => '31',
            ),
            'AngularJS' =>
            array (
            '_id' => '30',
            ),
            'vue.js' =>
            array (
            '_id' => '32',
            ),
            'backbone.js' =>
            array (
            '_id' => '33',
            ),
        ),
        '黑客性质' =>
        array (
            '_id' => '',
        ),
        '网页特效' =>
        array (
            '_id' => '36',
            '典型特效' =>
            array (
            '_id' => '',
            ),
            '按钮特效' =>
            array (
            '_id' => '',
            ),
            '页面背景' =>
            array (
            '_id' => '',
            ),
            '鼠标特效' =>
            array (
            '_id' => '',
            ),
            '状态特效' =>
            array (
            '_id' => '',
            ),
            '图象特效' =>
            array (
            '_id' => '',
            ),
            '下拉菜单' =>
            array (
            '_id' => '',
            ),
            '链接特效' =>
            array (
            '_id' => '',
            ),
            '导航菜单' =>
            array (
            '_id' => '',
            ),
            '文字特效' =>
            array (
            '_id' => '',
            ),
            '布局与层' =>
            array (
            '_id' => '',
            ),
            '其他特效' =>
            array (
            '_id' => '',
            ),
            '游戏娱乐' =>
            array (
            '_id' => '',
            ),
        ),
        '基础知识' =>
        array (
            '_id' => '',
        ),
        'javascript技巧' =>
        array (
            '_id' => '21',
        ),
        'DOM' =>
        array (
            '_id' => '37',
        ),
        'node.js' =>
        array (
            '_id' => '38',
        ),
        'js其它' =>
        array (
            '_id' => '21',
        ),
        ),
        'CSS/HTML' =>
        array (
        '_id' => '19',
        '经验交流' =>
        array (
            '_id' => '',
        ),
        '基础教程' =>
        array (
            '_id' => '',
        ),
        'VML相关' =>
        array (
            '_id' => '',
        ),
        ),
        'AJAX相关' =>
        array (
        '_id' => '39',
        ),
        '正则表达式' =>
        array (
        '_id' => '40',
        ),
        'photoshop' =>
        array (
        '_id' => '',
        ),
        'fireworks' =>
        array (
        '_id' => '',
        ),
        '安全设置' =>
        array (
        '_id' => '',
        ),
        'PHP编程' =>
        array (
        '_id' => '41',
        'php基础' =>
        array (
            '_id' => '',
        ),
        'php技巧' =>
        array (
            '_id' => '',
        ),
        'php实例' =>
        array (
            '_id' => '',
        ),
        'php文摘' =>
        array (
            '_id' => '',
        ),
        'php模板' =>
        array (
            '_id' => '',
        ),
        ),
        '网页播放器' =>
        array (
        '_id' => '',
        ),
        'ASP.NET' =>
        array (
        '_id' => '20',
        '基础应用' =>
        array (
            '_id' => '',
        ),
        '实用技巧' =>
        array (
            '_id' => '',
        ),
        '自学过程' =>
        array (
            '_id' => '',
        ),
        ),
        '应用技巧' =>
        array (
        '_id' => '',
        ),
        '网站应用' =>
        array (
        '_id' => '',
        ),
        'javascript' =>
        array (
        '_id' => '21',
        '网页特效' =>
        array (
            '_id' => '36',
            '时间日期' =>
            array (
            '_id' => '',
            ),
            'cookie' =>
            array (
            '_id' => '',
            ),
        ),
        ),
        '经典网摘' =>
        array (
        '_id' => '',
        ),
        '网站运营' =>
        array (
        '_id' => '',
        ),
        '我的作品' =>
        array (
        '_id' => '',
        ),
        '本站宗旨' =>
        array (
        '_id' => '',
        ),
        '脚本加解密' =>
        array (
        '_id' => '',
        ),
        'web2.0' =>
        array (
        '_id' => '42',
        ),
        '视频相关' =>
        array (
        '_id' => '',
        ),
        'XML/RSS' =>
        array (
        '_id' => '43',
        'XML基础' =>
        array (
            '_id' => '',
        ),
        'XML示例' =>
        array (
            '_id' => '',
        ),
        'WML教程' =>
        array (
            '_id' => '',
        ),
        ),
        '安全相关' =>
        array (
        '_id' => '',
        ),
        '常用工具' =>
        array (
        '_id' => '',
        ),
        '本站公告' =>
        array (
        '_id' => '',
        ),
        '生活健康' =>
        array (
        '_id' => '',
        ),
        '服务器' =>
        array (
        '_id' => '44',
        '星外虚拟主机' =>
        array (
            '_id' => '',
        ),
        '华众虚拟主机' =>
        array (
            '_id' => '',
        ),
        'Linux' =>
        array (
            '_id' => '45',
        ),
        'win服务器' =>
        array (
            '_id' => '46',
        ),
        'FTP服务器' =>
        array (
            '_id' => '47',
        ),
        'DNS服务器' =>
        array (
            '_id' => '48',
        ),
        '服务器其它' =>
        array (
            '_id' => '44',
        ),
        'Tomcat' =>
        array (
            '_id' => '49',
        ),
        'nginx' =>
        array (
            '_id' => '50',
        ),
        'zabbix' =>
        array (
            '_id' => '',
        ),
        '云和虚拟化' =>
        array (
            '_id' => '51',
            'docker' =>
            array (
            '_id' => '52',
            ),
            'Hyper-V' =>
            array (
            '_id' => '53',
            ),
            'VMware' =>
            array (
            '_id' => '54',
            ),
            'VirtualBox' =>
            array (
            '_id' => '55',
            ),
            'XenServer' =>
            array (
            '_id' => '56',
            ),
            'Kvm' =>
            array (
            '_id' => '57',
            ),
            'Qemu' =>
            array (
            '_id' => '58',
            ),
            'OpenVZ' =>
            array (
            '_id' => '59',
            ),
            'Xen' =>
            array (
            '_id' => '60',
            ),
            'CloudStack' =>
            array (
            '_id' => '61',
            ),
            'OpenStack' =>
            array (
            '_id' => '62',
            ),
            '云计算技术' =>
            array (
            '_id' => '',
            ),
            '云其它' =>
            array (
            '_id' => '',
            ),
        ),
        ),
        'JSP编程' =>
        array (
        '_id' => '64',
        ),
        'C#教程' =>
        array (
        '_id' => '65',
        ),
        'Windows2003' =>
        array (
        '_id' => '',
        ),
        'WindowsXP' =>
        array (
        '_id' => '',
        ),
        '注册表' =>
        array (
        '_id' => '',
        ),
        'unix linux' =>
        array (
        '_id' => '45',
        ),
        '其它相关' =>
        array (
        '_id' => '',
        ),
        'vb' =>
        array (
        '_id' => '66',
        ),
        '安装教程' =>
        array (
        '_id' => '',
        ),
        'python' =>
        array (
        '_id' => '67',
        ),
        '网络冲浪' =>
        array (
        '_id' => '',
        ),
        '其它免费' =>
        array (
        '_id' => '',
        ),
        'Access' =>
        array (
        '_id' => '69',
        ),
        'DOS/BAT' =>
        array (
        '_id' => '',
        ),
        'Flash' =>
        array (
        '_id' => '',
        'Flash As' =>
        array (
            '_id' => '',
        ),
        'Flash教程' =>
        array (
            '_id' => '',
        ),
        'Flex' =>
        array (
            '_id' => '',
        ),
        'Flash as3' =>
        array (
            '_id' => '',
        ),
        ),
        'hta' =>
        array (
        '_id' => '',
        ),
        'htc' =>
        array (
        '_id' => '',
        ),
        'Mysql' =>
        array (
        '_id' => '70',
        ),
        'MsSql' =>
        array (
        '_id' => '71',
        ),
        'vbs' =>
        array (
        '_id' => '',
        'vbs相关软件' =>
        array (
            '_id' => '',
        ),
        ),
        '普通空间' =>
        array (
        '_id' => '',
        ),
        '免费ASP空间' =>
        array (
        '_id' => '',
        ),
        '免费PHP空间' =>
        array (
        '_id' => '',
        ),
        '免费全能空间' =>
        array (
        '_id' => '',
        ),
        '免费FTP空间' =>
        array (
        '_id' => '',
        ),
        '编程10000问' =>
        array (
        '_id' => '',
        ),
        '病毒查杀' =>
        array (
        '_id' => '',
        ),
        'perl' =>
        array (
        '_id' => '72',
        '基础教程' =>
        array (
            '_id' => '',
        ),
        '应用技巧' =>
        array (
            '_id' => '',
        ),
        ),
        'Vista' =>
        array (
        '_id' => '',
        ),
        'IT 业界' =>
        array (
        '_id' => '',
        ),
        '娱乐动态' =>
        array (
        '_id' => '',
        '电影下载' =>
        array (
            '_id' => '',
        ),
        '演员资料' =>
        array (
            '_id' => '',
        ),
        '音乐收集' =>
        array (
            '_id' => '',
        ),
        ),
        '其它' =>
        array (
        '_id' => '',
        ),
        '与客户沟通' =>
        array (
        '_id' => '',
        ),
        '数据库文摘' =>
        array (
        '_id' => '68',
        ),
        '数据库其它' =>
        array (
        '_id' => '68',
        ),
        'Java编程' =>
        array (
        '_id' => '63',
        ),
        'Dreamweaver' =>
        array (
        '_id' => '',
        ),
        '游戏相关' =>
        array (
        '_id' => '',
        ),
        '游戏脚本' =>
        array (
        '_id' => '',
        '按键精灵' =>
        array (
            '_id' => '',
            '热血江湖' =>
            array (
            '_id' => '',
            ),
            '使用教程' =>
            array (
            '_id' => '',
            ),
        ),
        '传家宝脚本' =>
        array (
            '_id' => '',
        ),
        '其它相关' =>
        array (
            '_id' => '',
        ),
        'CS脚本' =>
        array (
            '_id' => '',
        ),
        ),
        '硬件维护' =>
        array (
        '_id' => '',
        ),
        '存储空间' =>
        array (
        '_id' => '',
        ),
        '网络安全' =>
        array (
        '_id' => '',
        ),
        '安全教程' =>
        array (
        '_id' => '',
        ),
        '漏洞研究' =>
        array (
        '_id' => '',
        ),
        'oracle' =>
        array (
        '_id' => '73',
        'Oracle应用' =>
        array (
            '_id' => '',
        ),
        ),
        'DB2' =>
        array (
        '_id' => '74',
        ),
        '整站程序' =>
        array (
        '_id' => '',
        '科讯相关' =>
        array (
            '_id' => '',
        ),
        '新云3.0' =>
        array (
            '_id' => '',
        ),
        'lbs_blog' =>
        array (
            '_id' => '',
        ),
        'dedecms' =>
        array (
            '_id' => '',
        ),
        '杰奇cms' =>
        array (
            '_id' => '',
        ),
        '其它CMS' =>
        array (
            '_id' => '',
        ),
        ),
        'C 语言' =>
        array (
        '_id' => '75',
        ),
        '代理服务器' =>
        array (
        '_id' => '',
        ),
        'VBA' =>
        array (
        '_id' => '',
        ),
        '注册码' =>
        array (
        '_id' => '',
        ),
        '远程脚本' =>
        array (
        '_id' => '',
        ),
        '中英文对照' =>
        array (
        '_id' => '',
        ),
        '组网教程' =>
        array (
        '_id' => '',
        ),
        '网页编辑器' =>
        array (
        '_id' => '',
        ),
        '相关技巧' =>
        array (
        '_id' => '',
        ),
        'ColdFusion' =>
        array (
        '_id' => '',
        ),
        '密码恢复攻略' =>
        array (
        '_id' => '',
        ),
        '毕业论文' =>
        array (
        '_id' => '',
        ),
        '路由器、交换机' =>
        array (
        '_id' => '',
        ),
        '其它综合' =>
        array (
        '_id' => '',
        ),
        '励志篇' =>
        array (
        '_id' => '',
        ),
        '民俗传统' =>
        array (
        '_id' => '',
        ),
        '系统维护' =>
        array (
        '_id' => '',
        ),
        'ruby专题' =>
        array (
        '_id' => '76',
        ),
        'windows2008' =>
        array (
        '_id' => '',
        ),
        'vb.net' =>
        array (
        '_id' => '66',
        ),
        'Delphi' =>
        array (
        '_id' => '77',
        ),
        'java' =>
        array (
        '_id' => '63',
        ),
        'mssql2005' =>
        array (
        '_id' => '71',
        ),
        '办公自动化' =>
        array (
        '_id' => '',
        ),
        'autoit' =>
        array (
        '_id' => '',
        ),
        'seraphzone' =>
        array (
        '_id' => '',
        ),
        'SQLite' =>
        array (
        '_id' => '78',
        ),
        'PostgreSQL' =>
        array (
        '_id' => '79',
        ),
        'Android' =>
        array (
        '_id' => '80',
        ),
        'PowerShell' =>
        array (
        '_id' => '',
        ),
        'linux shell' =>
        array (
        '_id' => '',
        ),
        'mssql2008' =>
        array (
        '_id' => '71',
        ),
        'Flex' =>
        array (
        '_id' => '',
        ),
        'IOS' =>
        array (
        '_id' => '81',
        ),
        'MongoDB' =>
        array (
        '_id' => '82',
        ),
        'Redis' =>
        array (
        '_id' => '83',
        ),
        'Swift' =>
        array (
        '_id' => '84',
        ),
        'Lua' =>
        array (
        '_id' => '85',
        ),
        'Golang' =>
        array (
        '_id' => '86',
        ),
        'Erlang' =>
        array (
        '_id' => '87',
        ),
        'Scala' =>
        array (
        '_id' => '88',
        ),
        'Dart' =>
        array (
        '_id' => '89',
        ),
        'mariadb' =>
        array (
        '_id' => '70',
        ),
        '易语言' =>
        array (
        '_id' => '90',
        ),
        '汇编语言' =>
        array (
        '_id' => '91',
        )
    )
);
$config['w3c'] = array (
    'list_dir'     => '/Users/zhoumingxia/git/sites/www.jb51.net/list',
);

// 陆股通解析
$config['lugutong'] = array(
    'lugutong_sz_url'       => 'https://sc.hkexnews.hk/TuniS/www.hkexnews.hk/sdw/search/mutualmarket_c.aspx?t=sz',
    'lugutong_sh_url'       => 'https://sc.hkexnews.hk/TuniS/www.hkexnews.hk/sdw/search/mutualmarket_c.aspx?t=sh',
);

return $config;
