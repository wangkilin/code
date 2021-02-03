<?php
/*
+-------------------------------------------+
|   iCodeBang CMS [#RELEASE_VERSION#]       |
|   by iCodeBang.com Team                   |
|   © iCodeBang.com. All Rights Reserved    |
|   ------------------------------------    |
|   Support: icodebang@126.com              |
|   WebSite: http://www.icodebang.com       |
+-------------------------------------------+
*/
class sinhoWorkloadModel extends Model
{
    /**
     * 书稿数据表
     */
    const BOOK_TABLE = 'sinho_company_workload';

    /**
     * 工作量表
     */
    const WORKLOAD_TABLE = 'sinho_employee_workload';

    /**
     * 新禾各种权限常量
     */
    const PERMISSION_VERIFY_WORKLOAD = 'sinho_verify_workload';
    const PERMISSION_FILL_WORKLOAD   = 'sinho_fill_workload';
    const PERMISSION_MODIFY_MANUSCRIPT_PARAM = 'sinho_modify_manuscript_param';
    const PERMISSION_CHECK_WORKLOAD  = 'sinho_check_workload';
    const SINHO_PERMISSION_LIST = array(
        self::PERMISSION_FILL_WORKLOAD,
        self::PERMISSION_MODIFY_MANUSCRIPT_PARAM,
        self::PERMISSION_VERIFY_WORKLOAD,
        self::PERMISSION_CHECK_WORKLOAD,
    );

    const STATUS_DELETE    = 0; // 已删除
    const STATUS_VERIFIED  = 1; // 已核算
    const STATUS_VERIFYING = 2; // 正在核算
    const STATUS_RECORDING = 3; // 正在录入中

    /**
     * 更新书稿
     * @param int $id 文章id
     * @param array $data 文章信息
     * @return bool
     */
    public function updateBook ($id, $data)
    {
        $result = false;
        if ($set = $this->processBookData($data)) {
            $set['modify_time'] = time();
            $result = $this->update(self::BOOK_TABLE, $set, 'id = ' . intval($id));
        }

        return $result;
    }

    /**
     * 处理标签数据， 供更新和添加使用
     * @param array $data
     * @return multitype:string NULL
     */
    protected function processBookData ($data)
    {
        // 转换成浮点型的数据
        $doubleVars = array (
            'content_table_pages', // varchar(255) DEFAULT NULL COMMENT '目录',
            'text_pages', // varchar(255) DEFAULT NULL COMMENT '正文',
            'text_table_chars_per_page', // varchar(255) DEFAULT NULL COMMENT '目录+正文千字/页',
            'answer_pages', // varchar(255) DEFAULT NULL COMMENT '答案',
            'answer_chars_per_page', // varchar(255) DEFAULT NULL COMMENT '答案千字/页',
            'test_pages', // varchar(255) DEFAULT NULL COMMENT '试卷',
            'test_chars_per_page', // varchar(255) DEFAULT NULL COMMENT '试卷千字/页',
            'test_answer_pages', // varchar(255) DEFAULT NULL COMMENT '试卷答案',
            'test_answer_chars_per_page', // varchar(255) DEFAULT NULL COMMENT '试卷答案千字/页',
            'exercise_pages', // varchar(255) DEFAULT NULL COMMENT '课后作业',
            'exercise_chars_per_page', // varchar(255) DEFAULT NULL COMMENT '课后作业千字/页',
            'function_book', // varchar(255) DEFAULT NULL COMMENT '功能册',
            'function_book_chars_per_page', // varchar(255) DEFAULT NULL COMMENT '功能册千字/页',
            'function_answer', // varchar(255) DEFAULT NULL COMMENT '功能册答案',
            'function_answer_chars_per_page', // varchar(255) DEFAULT NULL COMMENT '功能册答案千字/页',
            'weight', // varchar(255) DEFAULT NULL COMMENT '难度系数',
            'total_chars', // varchar(255) DEFAULT NULL COMMENT '字数（合计）',
            'total_chars_without_weight', // varchar(255) DEFAULT NULL COMMENT '字数（未乘系数）',
        );
        // 转换成整数的数据
        $intVars   = array(
            'id_number', // int(11) DEFAULT NULL COMMENT '序号',
        );
        $allowVars = array(
            'id_number', // int(11) DEFAULT NULL COMMENT '序号',
            'delivery_date', // varchar(255) DEFAULT NULL COMMENT '发稿日期',
            'return_date', // varchar(255) DEFAULT NULL COMMENT '回稿日期',
            'serial', // varchar(255) DEFAULT NULL COMMENT '系列',
            'book_name', // varchar(255) DEFAULT NULL COMMENT '书名',
            'proofreading_times', // varchar(255) DEFAULT NULL COMMENT '校次',
            'content_table_pages', // varchar(255) DEFAULT NULL COMMENT '目录',
            'text_pages', // varchar(255) DEFAULT NULL COMMENT '正文',
            'text_table_chars_per_page', // varchar(255) DEFAULT NULL COMMENT '目录+正文千字/页',
            'answer_pages', // varchar(255) DEFAULT NULL COMMENT '答案',
            'answer_chars_per_page', // varchar(255) DEFAULT NULL COMMENT '答案千字/页',
            'test_pages', // varchar(255) DEFAULT NULL COMMENT '试卷',
            'test_chars_per_page', // varchar(255) DEFAULT NULL COMMENT '试卷千字/页',
            'test_answer_pages', // varchar(255) DEFAULT NULL COMMENT '试卷答案',
            'test_answer_chars_per_page', // varchar(255) DEFAULT NULL COMMENT '试卷答案千字/页',
            'exercise_pages', // varchar(255) DEFAULT NULL COMMENT '课后作业',
            'exercise_chars_per_page', // varchar(255) DEFAULT NULL COMMENT '课后作业千字/页',
            'function_book', // varchar(255) DEFAULT NULL COMMENT '功能册',
            'function_book_chars_per_page', // varchar(255) DEFAULT NULL COMMENT '功能册千字/页',
            'function_answer', // varchar(255) DEFAULT NULL COMMENT '功能册答案',
            'function_answer_chars_per_page', // varchar(255) DEFAULT NULL COMMENT '功能册答案千字/页',
            'weight', // varchar(255) DEFAULT NULL COMMENT '难度系数',
            'total_chars', // varchar(255) DEFAULT NULL COMMENT '字数（合计）',
            'total_chars_without_weight', // varchar(255) DEFAULT NULL COMMENT '字数（未乘系数）',
            'remarks', // mediumtext COMMENT '备注',); // 转换成浮点型的数据
        ); // 允许存在的数据

        $set = array();
        foreach ($doubleVars as $varName) {
            if (! isset($data[$varName])) {
                continue;
            }

            //$data[$varName] = trim($data[$varName], " \t\n\r\0\x0B");
            $data[$varName] = doubleval($data[$varName]);
            if (0==$data[$varName]) {
                $data[$varName] = '';
            }

            // 移除末尾的 0，以及小数点
            $data[$varName] = strval($data[$varName]);
            if (strpos($data[$varName], '.')) {
                $data[$varName] = rtrim($data[$varName], '0');
            }
            $set[$varName] = rtrim($data[$varName], ".");
        }
        foreach ($intVars as $varName) {
            if (! isset($data[$varName])) {
                continue;
            }
            $data[$varName] = intval($data[$varName]);
            if (0!=$data[$varName]) {
                $set[$varName] = $data[$varName];
            }
        }

        foreach ($allowVars as $varName) {
            if (isset($data[$varName]) && !isset($set[$varName])) {
                // 去空格， 替换全角空格
                $set[$varName] = trim(str_replace(' ', ' ', $data[$varName]));
            }
        }

        return $set;
    }

    /**
     * 添加新教程
     * @param array $data 教程信息数组
     * @return number
     */
    public function addBook ($data)
    {
        $id = 0;
        if ($set = $this->processBookData($data)) {
            $set['add_time'] = $set['modify_time'] = time();
            $id = $this->insert(self::BOOK_TABLE, $set);
        }

        return $id;
    }

    public function getUserList ($where, $order='uid DESC', $perPage, $pageNum=0)
    {
        $itemList = array();
        $customGroupList = $this->model('account')->get_user_group_list(0, 1);
        $requiredGroupdIds = array();
        foreach ($customGroupList as $_item) {
            $_item['permission'] = unserialize($_item['permission']);
            foreach ($_item['permission'] as $_key=>$_v) {
                if ($_v == 1 && in_array($_key, self::SINHO_PERMISSION_LIST)) {
                    $requiredGroupdIds[] = $_item['group_id'];
                    break;
                }
            }
        }

        if (! $requiredGroupdIds)
            return $itemList;

        if ($where) {
            $where = '(' . $where . ') AND group_id IN ('.join(',', $requiredGroupdIds).')';
        } else {
            $where = 'group_id IN ('.join(',', $requiredGroupdIds).')';
        }

        return $this->fetch_page('users', $where, $order, $pageNum, $perPage);

        //return $this->fetch_all('users', 'group_id IN ('.join(',', $requiredGroupdIds).')');
    }

    /**
     * 根据条件获取课程列表
     * @param string $where
     * @param string $order
     * @param number $perPage
     * @param number $pageNum
     * @return multitype:unknown
     */
    public function getBookList ($where=null, $order='id DESC', $perPage=10, $pageNum=0)
    {
        $itemList = array();
        if ($list = $this->fetch_page('sinho_company_workload', $where, $order, $pageNum, $perPage)) {
            foreach ($list as $key => $val) {
                $val['url_token']=='' AND $val['url_token'] = $val['id'];
                $itemList[$val['id']] = $val;
            }
        }

        return $itemList;
    }

    /**
     * 根据id获取书稿信息
     * @param int $id 书稿id
     * @return array
     */
    public function getBookById ($id)
    {
        return $this->getById($id, self::BOOK_TABLE);
    }

    /**
     * 根据ids删除
     * @param array | int $ids 数据条目id列表
     * @return boolean
     */
    public function removeBookById ($id)
    {
        return $this->removeBooksByIds(array($id));
    }
    /**
     * 根据ids删除
     * @param array | int $ids 数据条目id列表
     * @return boolean
     */
    public function removeBooksByIds ($ids)
    {
        return $this->deleteByIds($ids, self::BOOK_TABLE);
    }

    /**
     * 填充工作量
     */
    public function fillWorkload($id, $data)
    {
        // 转换成浮点型的数据
        $doubleVars = array (
            'content_table_pages', // varchar(255) DEFAULT NULL COMMENT '目录',
            'text_pages', // varchar(255) DEFAULT NULL COMMENT '正文',
            'text_table_chars_per_page', // varchar(255) DEFAULT NULL COMMENT '目录+正文千字/页',
            'answer_pages', // varchar(255) DEFAULT NULL COMMENT '答案',
            'answer_chars_per_page', // varchar(255) DEFAULT NULL COMMENT '答案千字/页',
            'test_pages', // varchar(255) DEFAULT NULL COMMENT '试卷',
            'test_chars_per_page', // varchar(255) DEFAULT NULL COMMENT '试卷千字/页',
            'test_answer_pages', // varchar(255) DEFAULT NULL COMMENT '试卷答案',
            'test_answer_chars_per_page', // varchar(255) DEFAULT NULL COMMENT '试卷答案千字/页',
            'exercise_pages', // varchar(255) DEFAULT NULL COMMENT '课后作业',
            'exercise_chars_per_page', // varchar(255) DEFAULT NULL COMMENT '课后作业千字/页',
            'function_book', // varchar(255) DEFAULT NULL COMMENT '功能册',
            'function_book_chars_per_page', // varchar(255) DEFAULT NULL COMMENT '功能册千字/页',
            'function_answer', // varchar(255) DEFAULT NULL COMMENT '功能册答案',
            'function_answer_chars_per_page', // varchar(255) DEFAULT NULL COMMENT '功能册答案千字/页',
            'weight', // varchar(255) DEFAULT NULL COMMENT '难度系数',
            'total_chars', // varchar(255) DEFAULT NULL COMMENT '字数（合计）',
            'total_chars_without_weight', // varchar(255) DEFAULT NULL COMMENT '字数（未乘系数）',
            'payable_amount',
        );
        // 转换成整数的数据
        $intVars   = array(
            'id_number', // int(11) DEFAULT NULL COMMENT '序号',
        );
        $allowVars = array(
            // 'id_number', // int(11) DEFAULT NULL COMMENT '序号',
            // 'delivery_date', // varchar(255) DEFAULT NULL COMMENT '发稿日期',
            // 'return_date', // varchar(255) DEFAULT NULL COMMENT '回稿日期',
            // 'serial', // varchar(255) DEFAULT NULL COMMENT '系列',
            // 'book_name', // varchar(255) DEFAULT NULL COMMENT '书名',
            // 'proofreading_times', // varchar(255) DEFAULT NULL COMMENT '校次',
            'content_table_pages', // varchar(255) DEFAULT NULL COMMENT '目录',
            'text_pages', // varchar(255) DEFAULT NULL COMMENT '正文',
            'text_table_chars_per_page', // varchar(255) DEFAULT NULL COMMENT '目录+正文千字/页',
            'answer_pages', // varchar(255) DEFAULT NULL COMMENT '答案',
            'answer_chars_per_page', // varchar(255) DEFAULT NULL COMMENT '答案千字/页',
            'test_pages', // varchar(255) DEFAULT NULL COMMENT '试卷',
            'test_chars_per_page', // varchar(255) DEFAULT NULL COMMENT '试卷千字/页',
            'test_answer_pages', // varchar(255) DEFAULT NULL COMMENT '试卷答案',
            'test_answer_chars_per_page', // varchar(255) DEFAULT NULL COMMENT '试卷答案千字/页',
            'exercise_pages', // varchar(255) DEFAULT NULL COMMENT '课后作业',
            'exercise_chars_per_page', // varchar(255) DEFAULT NULL COMMENT '课后作业千字/页',
            'function_book', // varchar(255) DEFAULT NULL COMMENT '功能册',
            'function_book_chars_per_page', // varchar(255) DEFAULT NULL COMMENT '功能册千字/页',
            'function_answer', // varchar(255) DEFAULT NULL COMMENT '功能册答案',
            'function_answer_chars_per_page', // varchar(255) DEFAULT NULL COMMENT '功能册答案千字/页',
            'weight', // varchar(255) DEFAULT NULL COMMENT '难度系数',
            'total_chars', // varchar(255) DEFAULT NULL COMMENT '字数（合计）',
            'total_chars_without_weight', // varchar(255) DEFAULT NULL COMMENT '字数（未乘系数）',
            'remarks', // mediumtext COMMENT '备注',); // 转换成浮点型的数据
            'category',
            'working_times',
            'payable_amount',
        ); // 允许存在的数据

        $set = array();
        foreach ($doubleVars as $varName) {
            if (! isset($data[$varName])) {
                continue;
            }

            //$data[$varName] = trim($data[$varName], " \t\n\r\0\x0B");
            $data[$varName] = doubleval(trim($data[$varName]));
            // $_dataInfo = explode('.', $data[$varName]);
            // isset($_dataInfo[1]) AND $_dataInfo[1] =rtrim($_dataInfo[1], '0');
            // // 移除末尾的 0，以及小数点
            // if (empty($_dataInfo[1])) {
            //     $set[$varName] = ltrim($_dataInfo[0], '0');
            // } else {
            //     $set[$varName] = $_dataInfo[0] . '.' . $_dataInfo[1];
            // }
            if (''==$data[$varName]) {
                $set[$varName] = '';
            } else {
                $set[$varName] = $data[$varName];
            }
        }
        foreach ($intVars as $varName) {
            if (! isset($data[$varName])) {
                continue;
            }
            $data[$varName] = intval($data[$varName]);
            if (0!=$data[$varName]) {
                $set[$varName] = $data[$varName];
            }
        }

        foreach ($allowVars as $varName) {
            if (isset($data[$varName]) && !isset($set[$varName])) {
                $set[$varName] = trim($data[$varName]);
            }
        }
        if ($set) {
            $set['verify_remark'] = '';
        }

        return $this->update(self::WORKLOAD_TABLE, $set, 'id = ' . intval($id) );
    }

    /**
     * 基于用户统计核算总字数
     * @param array $userIds 编辑ids
     * @param int   $status  状态
     *
     * @return float
     */
    public function getTotalCharsByUserIds ($userIds = array(), $status = null)
    {
        // ( (目录+正文)*目录正文字数+答案*答案字数...) * 系数
        $sql = 'SELECT
                    ROUND (
                        SUM(
                            (
                                    ( CASE `content_table_pages` IS NULL OR `content_table_pages`="" WHEN 1 THEN 0 ELSE `content_table_pages` END
                                    + CASE `text_pages` IS NULL OR `text_pages`="" WHEN 1 THEN 0 ELSE `text_pages` END
                                    )
                                    *
                                    CASE `text_table_chars_per_page` IS NULL OR `text_table_chars_per_page`="" WHEN 1 THEN 0 ELSE `text_table_chars_per_page` END
                                +
                                    CASE `answer_pages` IS NULL OR `answer_pages`="" WHEN 1 THEN 0 ELSE `answer_pages` END
                                    *
                                    CASE `answer_chars_per_page` IS NULL OR `answer_chars_per_page`="" WHEN 1 THEN 0 ELSE `answer_chars_per_page` END
                                +
                                    CASE `test_pages` IS NULL OR `test_pages`="" WHEN 1 THEN 0 ELSE `test_pages` END
                                    *
                                    CASE `test_chars_per_page` IS NULL OR `test_chars_per_page`="" WHEN 1 THEN 0 ELSE `test_chars_per_page` END
                                +
                                    CASE `test_answer_pages` IS NULL OR `test_answer_pages`="" WHEN 1 THEN 0 ELSE `test_answer_pages` END
                                    *
                                    CASE `test_answer_chars_per_page` IS NULL OR `test_answer_chars_per_page`="" WHEN 1 THEN 0 ELSE `test_answer_chars_per_page` END
                                +
                                    CASE `exercise_pages` IS NULL OR `exercise_pages`="" WHEN 1 THEN 0 ELSE `exercise_pages` END
                                    *
                                    CASE `exercise_chars_per_page` IS NULL OR `exercise_chars_per_page`="" WHEN 1 THEN 0 ELSE `exercise_chars_per_page` END
                                +
                                    CASE `function_book` IS NULL OR `function_book`="" WHEN 1 THEN 0 ELSE `function_book` END
                                    *
                                    CASE `function_book_chars_per_page` IS NULL OR `function_book_chars_per_page`="" WHEN 1 THEN 0 ELSE `function_book_chars_per_page` END
                                +
                                    CASE `function_answer` IS NULL OR `function_answer`="" WHEN 1 THEN 0 ELSE `function_answer` END
                                    *
                                    CASE `function_answer_chars_per_page` IS NULL OR `function_answer_chars_per_page`="" WHEN 1 THEN 0 ELSE `function_answer_chars_per_page` END
                            )
                            *
                            CASE `weight` IS NULL OR `weight`="" WHEN 1 THEN 0 ELSE `weight` END
                        ), 4
                    ) AS total_chars, user_id
                FROM ' . $this->get_table(self::WORKLOAD_TABLE);
        $whereList = array();
        if ($userIds) {
            $whereList[] = 'user_id IN (' . join(',', $userIds) . ')';
        }
        if ($status) {
            $whereList[] = 'status = ' . intval($status);
        }

        $where = null;
        if ($whereList) {
            $where = join(' AND ', $whereList);
        }

        $list = $this->query_all($sql, PHP_INT_MAX, 0, $where, 'user_id');
        $keys = array_column($list, 'user_id');
        $values = array_column($list, 'total_chars');

        return array_combine($keys, $values);
    }

    /**
     * 基于用户做工作量数据统计
     * @param array $userIds 编辑ids
     * @param int   $status  状态
     *
     * @return float
     */
    public function getWorkloadStatByUserIds ($userIds = array(), $status = null, $belongMonth = null)
    {
        if (isset($belongMonth) && ! is_array($belongMonth)) {
            $belongMonth = array($belongMonth);
        }
        // ( (目录+正文)*目录正文字数+答案*答案字数...) * 系数
        $sql = 'SELECT
                    ROUND (
                        SUM(
                            (
                                    ( CASE `content_table_pages` IS NULL OR `content_table_pages`="" WHEN 1 THEN 0 ELSE `content_table_pages` END
                                    + CASE `text_pages` IS NULL OR `text_pages`="" WHEN 1 THEN 0 ELSE `text_pages` END
                                    )
                                    *
                                    CASE `text_table_chars_per_page` IS NULL OR `text_table_chars_per_page`="" WHEN 1 THEN 0 ELSE `text_table_chars_per_page` END
                                +
                                    CASE `answer_pages` IS NULL OR `answer_pages`="" WHEN 1 THEN 0 ELSE `answer_pages` END
                                    *
                                    CASE `answer_chars_per_page` IS NULL OR `answer_chars_per_page`="" WHEN 1 THEN 0 ELSE `answer_chars_per_page` END
                                +
                                    CASE `test_pages` IS NULL OR `test_pages`="" WHEN 1 THEN 0 ELSE `test_pages` END
                                    *
                                    CASE `test_chars_per_page` IS NULL OR `test_chars_per_page`="" WHEN 1 THEN 0 ELSE `test_chars_per_page` END
                                +
                                    CASE `test_answer_pages` IS NULL OR `test_answer_pages`="" WHEN 1 THEN 0 ELSE `test_answer_pages` END
                                    *
                                    CASE `test_answer_chars_per_page` IS NULL OR `test_answer_chars_per_page`="" WHEN 1 THEN 0 ELSE `test_answer_chars_per_page` END
                                +
                                    CASE `exercise_pages` IS NULL OR `exercise_pages`="" WHEN 1 THEN 0 ELSE `exercise_pages` END
                                    *
                                    CASE `exercise_chars_per_page` IS NULL OR `exercise_chars_per_page`="" WHEN 1 THEN 0 ELSE `exercise_chars_per_page` END
                                +
                                    CASE `function_book` IS NULL OR `function_book`="" WHEN 1 THEN 0 ELSE `function_book` END
                                    *
                                    CASE `function_book_chars_per_page` IS NULL OR `function_book_chars_per_page`="" WHEN 1 THEN 0 ELSE `function_book_chars_per_page` END
                                +
                                    CASE `function_answer` IS NULL OR `function_answer`="" WHEN 1 THEN 0 ELSE `function_answer` END
                                    *
                                    CASE `function_answer_chars_per_page` IS NULL OR `function_answer_chars_per_page`="" WHEN 1 THEN 0 ELSE `function_answer_chars_per_page` END
                            )
                            *
                            CASE `weight` IS NULL OR `weight`="" WHEN 1 THEN 0 ELSE `weight` END
                        ), 4
                    ) AS total_chars,
                    ROUND (
                        SUM(
                            (
                                    ( CASE `content_table_pages` IS NULL OR `content_table_pages`="" WHEN 1 THEN 0 ELSE `content_table_pages` END
                                    + CASE `text_pages` IS NULL OR `text_pages`="" WHEN 1 THEN 0 ELSE `text_pages` END
                                    )
                                    *
                                    CASE `text_table_chars_per_page` IS NULL OR `text_table_chars_per_page`="" WHEN 1 THEN 0 ELSE `text_table_chars_per_page` END
                                +
                                    CASE `answer_pages` IS NULL OR `answer_pages`="" WHEN 1 THEN 0 ELSE `answer_pages` END
                                    *
                                    CASE `answer_chars_per_page` IS NULL OR `answer_chars_per_page`="" WHEN 1 THEN 0 ELSE `answer_chars_per_page` END
                                +
                                    CASE `test_pages` IS NULL OR `test_pages`="" WHEN 1 THEN 0 ELSE `test_pages` END
                                    *
                                    CASE `test_chars_per_page` IS NULL OR `test_chars_per_page`="" WHEN 1 THEN 0 ELSE `test_chars_per_page` END
                                +
                                    CASE `test_answer_pages` IS NULL OR `test_answer_pages`="" WHEN 1 THEN 0 ELSE `test_answer_pages` END
                                    *
                                    CASE `test_answer_chars_per_page` IS NULL OR `test_answer_chars_per_page`="" WHEN 1 THEN 0 ELSE `test_answer_chars_per_page` END
                                +
                                    CASE `exercise_pages` IS NULL OR `exercise_pages`="" WHEN 1 THEN 0 ELSE `exercise_pages` END
                                    *
                                    CASE `exercise_chars_per_page` IS NULL OR `exercise_chars_per_page`="" WHEN 1 THEN 0 ELSE `exercise_chars_per_page` END
                                +
                                    CASE `function_book` IS NULL OR `function_book`="" WHEN 1 THEN 0 ELSE `function_book` END
                                    *
                                    CASE `function_book_chars_per_page` IS NULL OR `function_book_chars_per_page`="" WHEN 1 THEN 0 ELSE `function_book_chars_per_page` END
                                +
                                    CASE `function_answer` IS NULL OR `function_answer`="" WHEN 1 THEN 0 ELSE `function_answer` END
                                    *
                                    CASE `function_answer_chars_per_page` IS NULL OR `function_answer_chars_per_page`="" WHEN 1 THEN 0 ELSE `function_answer_chars_per_page` END
                            )
                        ), 4
                    ) AS total_chars_without_weight,
                    SUM(content_table_pages             ) AS content_table_pages           ,
                    SUM(text_pages                      ) AS text_pages                    ,
                    SUM(answer_pages                    ) AS answer_pages                  ,
                    SUM(test_pages                      ) AS test_pages                    ,
                    SUM(test_answer_pages               ) AS test_answer_pages             ,
                    SUM(exercise_pages                  ) AS exercise_pages                ,
                    SUM(function_book                   ) AS function_book                 ,
                    SUM(function_answer                 ) AS function_answer               ,
                    `status`,
                    belong_month,
                    user_id
                FROM ' . $this->get_table(self::WORKLOAD_TABLE);
        $whereList = array();
        if ($userIds) {
            $whereList[] = 'user_id IN (' . join(',', $userIds) . ')';
        }
        if ($status) {
            $whereList[] = 'status = ' . intval($status);
        }
        if ($belongMonth) {
            $whereList[] = 'belong_month IN (' . join(',', $belongMonth)  . ')';
        }

        $where = null;
        if ($whereList) {
            $where = join(' AND ', $whereList);
        }

        $list = $this->query_all($sql, PHP_INT_MAX, 0, $where, 'user_id');
        $keys = array_column($list, 'user_id');

        return array_combine($keys, $list);
    }


}

/* EOF */
