<?php
// 系统根路径
defined('ROOT_PATH') OR define('ROOT_PATH', realpath(dirname(__DIR__)) . DIRECTORY_SEPARATOR);
// 配置文件路径
defined('CONF_PATH') OR define('CONF_PATH', ROOT_PATH . 'config' . DIRECTORY_SEPARATOR);


isset($config) OR $config = array();

// 金属json解析
$config['stock_rank'] = array(
    'header' => array(
        'Accept'                => 'application/json, text/javascript, */*; q=0.01',
        'Accept-Encoding'       => 'gzip, deflate, br',
        'Accept-Language'       => 'zh-CN,zh;q=0.9',
        'Cache-Control'         => 'no-cache',
        'Connection'            => 'keep-alive',
        'Content-Type'          => 'application/x-www-form-urlencoded; charset=UTF-8',
        'Pragma'                => 'no-cache',
        'Sec-Fetch-Dest'        => 'empty',
        'Sec-Fetch-Mode'        => 'cors',
        'Sec-Fetch-Site'        => 'same-site',
        'User-Agent'            => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.150 Safari/537.36',
    ),
    'param'  => array(
        "param"  => "code=%s",
        "path"   => "webarticlelist/api/guba/gubainfo",
        "env"    => "2"
    ),
);
// 金属解析
$config['metal'] = array(
);
// 金属json解析
$config['metal_json'] = array(
    'header' => array(
        'Accept'                => 'application/json, text/javascript, */*; q=0.01',
        'Accept-Encoding'       => 'gzip, deflate, br',
        'Accept-Language'       => 'zh-CN,zh;q=0.9',
        'Cache-Control'         => 'no-cache',
        'Connection'            => 'keep-alive',
        'Content-Type'          => 'application/x-www-form-urlencoded; charset=UTF-8',
        'Pragma'                => 'no-cache',
        'Sec-Fetch-Dest'        => 'empty',
        'Sec-Fetch-Mode'        => 'cors',
        'Sec-Fetch-Site'        => 'same-site',
        'User-Agent'            => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.150 Safari/537.36',
    ),
    'list' => array (
        array(
            'name' => '铜',
            'params' => array (
                'colName'   => 'cu',
                'pageSize'  => 20,
                'pageNo'    => 1
            )
        ),
        array(
            'name' => '铝',
            'params' => array (
                'colName'   => 'al',
                'pageSize'  => 20,
                'pageNo'    => 1
            )
        ),
        array(
            'name' => '锌',
            'params' => array (
                'colName'   => 'zn',
                'pageSize'  => 20,
                'pageNo'    => 1
            )
        ),
        array(
            'name' => '铅',
            'params' => array (
                'colName'   => 'pb',
                'pageSize'  => 20,
                'pageNo'    => 1
            )
        ),
        array(
            'name' => '锡',
            'params' => array (
                'colName'   => 'sn',
                'pageSize'  => 20,
                'pageNo'    => 1
            )
        ),
        array(
            'name' => '镍',
            'params' => array (
                'colName'   => 'ni',
                'pageSize'  => 20,
                'pageNo'    => 1
            )
        ),
        array(
            'name' => '小金属',
            'params' => array (
                'colName'   => 'xjs',
                'pageSize'  => 20,
                'pageNo'    => 1
            )
        ),
        array(
            'name' => '贵金属',
            'params' => array (
                'colName'   => 'gjs',
                'pageSize'  => 20,
                'pageNo'    => 1
            )
        ),
        array(
            'name' => '稀土金属',
            'params' => array (
                'colName'   => 'xtjs',
                'pageSize'  => 20,
                'pageNo'    => 1
            )
        ),
        array(
            'name' => '金属矿产',
            'params' => array (
                'colName'   => 'jskc',
                'pageSize'  => 20,
                'pageNo'    => 1
            )
        ),
        array(
            'name' => '金属化合物',
            'params' => array (
                'colName'   => 'jshhw',
                'pageSize'  => 20,
                'pageNo'    => 1
            )
        ),
        array(
            'name' => '废旧金属',
            'params' => array (
                'colName'   => 'fjjs',
                'pageSize'  => 20,
                'pageNo'    => 1
            )
        ),
        // array(
        //     'name' => '不锈钢',
        //     'params' => array (
        //         'productSortName'   => '不锈钢',
        //         'cityCode'          => 520200,
        //         'pageSize'  => 20,
        //         'pageNo'    => 1
        //     )
        // ),
    ),
);
$config['parse'] = array(

    'categoryInfo' => array (
        'php'    => array(
            'detect' =>array(
                'laravel' => '121',
                'symfony' => '141',
                'zend-framework' => '129',
                'zendframework' => '129',
                'zf2' => '129',
            ),
        ),
        'js'    => array(
            'detect' =>array(
                'node.js' => '38',
                'nodejs' => '38',
                'angular.js' => '30',
                'angularjs' => '30',
                'angularjs2' => '30',
                'react' => '99',
                'react.js' => '99',
                'vue' => '32',
                'vue.js' => '32',
                'vue2' => '32',
                'vuex' => '32',
                'jeasyui' => '25',
                'jquery' => '25',
                'jquerymobile' => '25',
                'jqueryui' => '25',
                'ES6' => '110',
                'ecmascript' => '110',
            ),
        ),
        'javascript'    => array(
            'detect' =>array(
                'node.js' => '38',
                'nodejs' => '38',
                'angular.js' => '30',
                'angularjs' => '30',
                'angularjs2' => '30',
                'react' => '99',
                'react.js' => '99',
                'vue' => '32',
                'vue.js' => '32',
                'vue2' => '32',
                'vuex' => '32',
                'jeasyui' => '25',
                'jquery' => '25',
                'jquerymobile' => '25',
                'jqueryui' => '25',
                'ES6' => '110',
                'ecmascript' => '110',
            ),
        ),
        'html/css'    => array(
            'detect' =>array(
                'node.js' => '38',
                'nodejs' => '38',
                'angular.js' => '30',
                'angularjs' => '30',
                'angularjs2' => '30',
                'react' => '99',
                'react.js' => '99',
                'vue' => '32',
                'vue.js' => '32',
                'vue2' => '32',
                'vuex' => '32',
                'jeasyui' => '25',
                'jquery' => '25',
                'jquerymobile' => '25',
                'jqueryui' => '25',
                'ES6' => '110',
                'ecmascript' => '110',
                'css' => '34',
                'html5' => '35',
            ),
        ),
        'java'  => array(
            'detect' =>array(
                'Spring Boot'=>142,
                'SpringBoot'=>142,
                'Spring Cloud'  => '142',
                'SpringCloud'  => '142',
                'Spring'    => '142',
                'jdk'    => '63',
                'tomcat'    => '63',
                'jvm'    => '63',
            ),
        ),
        'python'    => array(
            'detect' =>array(
                'TensorFlow' => '111',
                'django' => '95',
            ),
        ),
        '人工智能'   => array(
            'detect' =>array(
                '人工智能'  => '133',
                '机器学习'   => '137',
                '深度学习'   => '133',
                '算法'   => '133',
                '神经网络'  => '135',
                'TensorFlow' => '111',
                'django' => '95',
                'python' => 67,
                'Go' => 86,
                'Spring Boot'=>142,
                'SpringBoot'=>142,
                'Spring Cloud'  => '142',
                'SpringCloud'  => '142',
                'Spring'    => '142',
                'java' => 63,
                'MySQL' => '70',
                'Redis' => '83',
                'Linux' => '45',
            ),
        ),
        '前端' => array(
            'detect' =>array(
                'TypeScript'    => '103',
                'Flutter'   => '117',
                'JavaScript'    => '21',
                'Vue.js'    => '32',
                'Vue'    => '32',
                'React.js'  => '99',
                'React'  => '99',
                'ECMAScript 6'=> '110',
                'ECMAScript6'=> '110',
                'ECMAScript'=> '110',
                'es6'=> '110',
                'CSS'   => '34',
                'html5'   => '35',
                'Node.js'   => '38',
                'Node'   => '38',
                'Nodejs'   => '38',
                'Webpack'   => '21',
                '前端面试'  => '144',
                'web'   => '144',
                '前端'  => '144',
                '网页'  => '144',
            ),
        ),
        '架构' => array(
            'detect' => array(
                'Spring Boot'=>142,
                'SpringBoot'=>142,
                'Spring Cloud'  => '142',
                'SpringCloud'  => '142',
                'Spring'    => '142',
                'java' => 63,
                'MySQL' => '70',
                'Redis' => '83',
                'Linux' => '45',
                'Node.js'   => '38',
                'Node'   => '38',
                'Nodejs'   => '38',
                'nginx' => 50,
                'Docker'    => '52',
                'Kubernetes' => '145',
                'K8s' => '145',
            ),
        ),
        '数据库' => array(
            'detect' => array(
                'sql server' => 71,
                'sqlserver' => 71,
                'PostgreSQL' => '79',
                'Oracle' => '73',
                'mariadb' => '70',
                'MySQL' => '70',
                'Redis' => '83',
                'mongodb' => '82',
                'Memcached' => '98',
                '数据库'    => '68',
            ),
        ),
        '移动开发' => array(
            'detect' => array(
                'Android'   => '80',
                'Kotlin'    => '92',
                'iOS'   => '81',
                'Swift' => '84',
                'Objective-C'   => '124',
                'Flutter'   => '117',
            ),
        ),
        '运维' => array(
            'detect' => array(
                'MySQL' => '70',
                'Redis' => '83',
                'Linux' => '45',
                'Node.js'   => '38',
                'Node'   => '38',
                'Nodejs'   => '38',
                'nginx' => 50,
                'apache' => '100',
                'Docker'    => '52',
                'Kubernetes' => '145',
                'K8s' => '145',
                'hadoop' => 118,
                'Spark' => 126
            ),
        ),
    ),
    'categoryKeyIdMap' => array (
        '.net' => '65',
        '.net core' => '65',
        '.net新手区' => '65',
        'ES6' => '110',
        'List' => '',
        'Memcached' => '98',
        'actionscript' => '',
        'ado' => '',
        'ajax' => '39',
        'alauda' => '',
        'amazon-web-services' => '',
        'ambari' => '',
        'analyzer' => '',
        'android' => '80',
        'android-studio' => '80',
        'android开发' => '80',
        'angular.js' => '30',
        'angularjs' => '30',
        'angularjs2' => '30',
        'apache' => '100',
        'api' => '',
        'appml' => '',
        'asp' => '20',
        'asp.net' => '20',
        'asp.net mvc' => '20',
        'aspnet' => '20',
        'bash' => '112',
        'bootstrap' => '96',
        'bootstrap4' => '96',
        'bpm' => '',
        'browsers' => '',
        'bundle' => '',
        'c#' => '65',
        'c++' => '105',
        'centos' => '113',
        'charsets' => '',
        'chrome' => '',
        'ci' => '',
        'cloudera' => '',
        'clr' => '',
        'cocoa' => '',
        'composer' => '114',
        'cplusplus' => '105',
        'cprogramming' => '104',
        'csharp' => '65',
        'css' => '34',
        'css3' => '34',
        'cssref' => '34',
        'c语言' => '104',
        'dart' => '117',
        'deepflow' => '',
        'delphi' => '',
        'design-pattern' => '94',
        'django' => '95',
        'docker' => '52',
        'dom' => '37',
        'dtd' => '',
        'dynamics' => '',
        'eclipse' => '',
        'ecmascript' => '110',
        'elastic' => '115',
        'elasticsearch' => '115',
        'emacs' => '',
        'entity framework' => '',
        'erlang' => '87',
        'es6' => '110',
        'facebook' => '',
        'firebug' => '',
        'firefox' => '',
        'flask' => '',
        'flex' => '',
        'flink' => '',
        'flutter' => '117',
        'font-awesome' => '97',
        'foundation' => '96',
        'gis技术' => '',
        'git' => '102',
        'github' => '',
        'go' => '86',
        'golang' => '86',
        'googleAPI' => '',
        'googleapi' => '',
        'hadoop' => '118',
        'hdfs' => '118',
        'hg' => '',
        'hibernate' => '',
        'highcharts' => '',
        'hive' => '',
        'hosting' => '',
        'html' => '35',
        'html/css' => '144',
        'html5' => '35',
        'htmldom' => '37',
        'http' => '',
        'ico' => '138',
        'ide' => '',
        'intellij-idea' => '',
        'internet-explorer' => '',
        'ionic' => '109',
        'ios' => '81',
        'ios开发' => '81',
        'ipad' => '',
        'iphone' => '',
        'jar' => '119',
        'java' => '63',
        'java-ee' => '63',
        'javascript' => '21',
        'jeasyui' => '25',
        'jquery' => '25',
        'jquerymobile' => '25',
        'jqueryui' => '25',
        'js' => '21',
        'json' => '27',
        'jsp' => '64',
        'jsref' => '21',
        'kafka' => '120',
        'kotlin' => '92',
        'kylin' => '45',
        'laravel' => '121',
        'linux' => '45',
        'lua' => '85',
        'lucene' => '122',
        'macos' => '81',
        'macosx' => '81',
        'manual' => '',
        'mapreduce' => '',
        'maven' => '63',
        'media' => '',
        'memcached' => '98',
        'microsoft' => '',
        'mongodb' => '82',
        'mpvue' => '123',
        'mpx' => '',
        'mvc' => '',
        'mysql' => '70',
        'nginx' => '50',
        'nhibernate' => '',
        'node.js' => '38',
        'nodejs' => '38',
        'nosql' => '143',
        'note' => '',
        'numpy' => '67',
        'objective-c' => '124',
        'oracle' => '73',
        'oracle erp' => '73',
        'os x' => '',
        'paddle' => '',
        'perl' => '72',
        'phonegap' => '',
        'php' => '41',
        'postgresql' => '79',
        'python' => '67',
        'python3' => '67',
        'quality' => '',
        'quiz' => '',
        'rdf' => '',
        'react' => '99',
        'react.js' => '99',
        'redis' => '83',
        'regexp' => '40',
        'rss' => '43',
        'ruby' => '76',
        'ruby-on-rails' => '76',
        'rubygems' => '76',
        'rvm' => '',
        'r语言' => '107',
        'safari' => '',
        'sap' => '',
        'scala' => '88',
        'schema' => '43',
        'servlet' => '63',
        'sharepoint' => '',
        'silverlight' => '',
        'soap' => '43',
        'solr' => '125',
        'spark' => '126',
        'sphinx' => '127',
        'spring' => '142',
        'sql' => '68',
        'sql server' => '71',
        'sqlalchemy' => '',
        'sqlite' => '78',
        'sqoop' => '',
        'struts' => '',
        'sublime-text' => '',
        'svg' => '35',
        'svn' => '102',
        'swift' => '84',
        'symfony' => '141',
        'tags' => '35',
        'talkingdata' => '',
        'taro' => '',
        'tcpip' => '',
        'tdengine' => '128',
        'tensorflow' => '111',
        'textmate' => '',
        'tomcat' => '49',
        'tornado' => '',
        'twitter' => '',
        'typescript' => '103',
        'ubuntu' => '45',
        'ucloud' => '',
        'uni-app' => '',
        'unix' => '45',
        'uwp' => '',
        'vbscript' => '',
        'verilog' => '',
        'video' => '',
        'vim' => '',
        'virtualenv' => '',
        'visual studio' => '',
        'visual-studio' => '',
        'vue' => '32',
        'vue.js' => '32',
        'vue2' => '32',
        'w3c' => '',
        'w3cnote' => '',
        'w3cnote_genre' => '',
        'wcf' => '',
        'web' => '',
        'web.py' => '',
        'webservices' => '',
        'webview' => '2',
        'wepy' => '',
        'windows' => '',
        'windows ce' => '',
        'windows mobile' => '',
        'windows phone' => '',
        'windows server' => '46',
        'windows-server' => '46',
        'winform' => '',
        'winrt/metro' => '',
        'wpf' => '',
        'wsdl' => '',
        'xcode' => '',
        'xlink' => '',
        'xml' => '43',
        'xna' => '',
        'xpath' => '43',
        'xquery' => '43',
        'xsl' => '43',
        'xslfo' => '43',
        'zend-framework' => '129',
        'zookeeper' => '',
        '七牛云存储' => '',
        '中文分词' => '',
        '云杉网络' => '',
        '云计算' => '',
        '人工智能' => '133',
        '代码与软件发布' => '',
        '以太坊' => '138',
        '企业信息化其他' => '',
        '信息安全' => '',
        '全文检索' => '',
        '其他手机开发' => '',
        '其他技术区' => '',
        '其他数据库' => '',
        '其他语言' => '',
        '前端' => '144',
        '区块链' => '138',
        '又拍云存储' => '',
        '大数据' => '',
        '头条小程序' => '',
        '小程序' => '130',
        '小程序云开发' => '130',
        '嵌入式' => '',
        '开源研究' => '',
        '微信' => '',
        '微信公众平台' => '',
        '微信小程序' => '132',
        '微信开发者工具' => '',
        '微信开放平台' => '',
        '控件开发' => '',
        '搜索引擎' => '',
        '支付宝小程序' => '131',
        '敏捷开发' => '',
        '数字化货币' => '139',
        '数据库' => '68',
        '数据挖掘' => '134',
        '智能合约' => '138',
        '机器学习' => '137',
        '架构设计' => '',
        '正则表达式' => '40',
        '比特币' => '138',
        '求职面试' => '',
        '涛思数据' => '',
        '深度学习' => '137',
        '游戏开发' => '',
        '灵雀云' => '',
        '百度' => '',
        '百度云' => '',
        '百度智能小程序' => '140',
        '神经网络' => '135',
        '程序人生' => '',
        '算法与数据结构' => '',
        '缓存' => '',
        '网络安全' => '',
        '美团云' => '',
        '翻译区' => '',
        '腾讯云' => '',
        '自动驾驶' => '136',
        '自然语言处理' => '',
        '计算机图形学' => '',
        '设计模式' => '94',
        '读书区' => '',
        '负载均衡' => '',
        '转载区' => '',
        '软件工程其他' => '',
        '软件测试' => '',
        '金山云' => '',
        '非技术区' => '',
        '面向对象' => '',
        '项目与团队管理' => '',
        '领域驱动设计' => '',
      )
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
        ),
        'R语言' =>
        array (
        '_id' => '107',
        ),
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



$commonConfig = $config;
$privateConfig = require_once(__DIR__ . '/parseConfig.inc.php');

$config = array_merge_recursive($commonConfig, $privateConfig);

return $config;
