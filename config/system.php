<?php

$config['debug'] = false;    // 网站 Debug 模式
$config['sites'] = array(
    'www.isinho.com'    => array(
        'debug'=>false
    ),
    'www1.isinho.com'    => array(
        'debug'=>true,
        'workday_holiday' => array ( // 工作日和假期设置。
            2021 => array (
                'holiday' => array (
                    1 => array (1,2,3),
                    2 => array (11, 12, 13, 14, 15, 16, 17),
                    4 => array (3, 4, 5),
                    5 => array (1, 2, 3, 4, 5),
                    6 => array (12, 13, 14),
                    9 => array (19,20,21),
                    10 => array ( 1, 2, 3, 4, 5, 6, 7, 8)

                ),
                'workday' => array (
                    2 => array (7, 20),
                    4 => array (25),
                    5 => array (8),
                    9 => array (18, 26),
                    10 => array (9)
                ),
            ),
        ),
    ),
);

$config['model'] = array(
    'article'   => array( // 文章
                    'status' => 1,  // 1:启用， 0:开发中
                ),
    'course'    => array( // 教程
                    'status' => 1,  // 1:启用， 0:开发中
                ),
    'manual'    => array( // 手册
                    'status' => 0,  // 1:启用， 0:开发中
                ),
);

/* EOF */
