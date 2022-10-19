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
     * 工作量质量奖惩表
     */
    const QUARLITY_TABLE = 'sinho_workload_quarlity';
    /**
     * 请假表
     */
    const ASK_LEAVE_TABLE = 'users_ask_leave';
    /**
     * 请假日期跨度表
     */
    const ASK_LEAVE_DATE_TABLE = 'users_ask_leave_date';
    /**
     * 作息时间安排表
     */
    const SCHEDULE_TABLE = 'sinho_schedule';
    /**
     * 收入支出表
     */
    const INCOME_OUTPUT_TABLE = 'sinho_income_expense';
    /**
     * 工资详细表
     */
    const SALARY_DETAIL_TABLE = 'sinho_salary_detail';
    /**
     * 完整的工资信息表
     */
    const FINANCE_DATA_TABLE = 'sinho_finance_data';
    /**
     * 工会经费排除的user id
     */
    const GONGHUI_FEE_IGNORE_USER_ID = 10003;

    // /**
    //  * 新禾各种权限常量
    //  */
    // const PERMISSION_VERIFY_WORKLOAD = 'sinho_verify_workload';
    // const PERMISSION_FILL_WORKLOAD   = 'sinho_fill_workload';
    // const PERMISSION_MODIFY_MANUSCRIPT_PARAM = 'sinho_modify_manuscript_param';
    // const PERMISSION_CHECK_WORKLOAD  = 'sinho_check_workload';
    // const SINHO_PERMISSION_LIST = array(
    //     self::PERMISSION_FILL_WORKLOAD,
    //     self::PERMISSION_MODIFY_MANUSCRIPT_PARAM,
    //     self::PERMISSION_VERIFY_WORKLOAD,
    //     self::PERMISSION_CHECK_WORKLOAD,
    // );

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
            'is_import',
            'category_id',
            'user_id',
            'verify_status', // 是否是主动上报
        );
        $allowVars = array(
            'id_number', // int(11) DEFAULT NULL COMMENT '序号',
            'delivery_date', // varchar(255) DEFAULT NULL COMMENT '发稿日期',
            'return_date', // varchar(255) DEFAULT NULL COMMENT '回稿日期',
            'category', // 类别
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
            'admin_remarks', // varchar， 管理员备注信息

            'category_id', // 书稿所属学科
            'user_id',     // 书稿创建者id
            'is_import',
            'verify_status',
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
            //if (0!=$data[$varName]) {
                $set[$varName] = $data[$varName];
            //}
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
                if ($_v == 1 && in_array($_key, SinhoBaseController::SINHO_PERMISSION_LIST)) {
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
            'add_time',
            'fill_time',
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
    public function getWorkloadStatByUserIds ($userIds = array(), $status = null, $belongMonth = null, $groupBy='user_id')
    {
        if (isset($belongMonth) && ! is_array($belongMonth)) {
            $belongMonth = array($belongMonth);
        }
        if(isset($status) && ! is_array($status)) {
            $status = array($status);
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
                            *
                            CASE `weight` IS NULL OR `weight`="" WHEN 1 THEN 0 ELSE   ( CASE `weight` > 1 WHEN 1 THEN 1 ELSE `weight` END ) END
                        ), 4
                    ) AS total_chars_weight_lt_1,
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
            $whereList[] = 'status IN ( ' . join(',', $status) . ')';
        }
        if ($belongMonth) {
            if (isset($belongMonth['start']) || isset($belongMonth['end'])) {
                if (isset($belongMonth['start'])) {
                    if ($status && in_array(sinhoWorkloadModel::STATUS_VERIFYING, $status)) {
                        $whereList[] = '(belong_month >= ' . intval($belongMonth['start'])  . ' OR belong_month IS NULL )';
                    } else {
                        $whereList[] = 'belong_month >= ' . intval($belongMonth['start']);
                    }
                }

                if (isset($belongMonth['end'])) {
                    $whereList[] = 'belong_month <= ' . intval($belongMonth['end']);
                }
            } else {
                $whereList[] = 'belong_month IN (' . join(',', $belongMonth)  . ')';
            }
        }

        $where = null;
        if ($whereList) {
            $where = join(' AND ', $whereList);
        }

        $list = $this->query_all($sql, PHP_INT_MAX, 0, $where, $groupBy);

        return $list;
        $keys = array_column($list, 'user_id');

        return array_combine($keys, $list);
    }



    /**
     * 基于用户工作量奖惩数据统计
     * @param array $userIds 编辑ids
     *
     * @return float
     */
    public function getQuarlityStatByUserIds ($userIds = array(), $belongMonth = null, $groupBy='user_id')
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
                            * 2
                            * CASE q.`good_or_bad`="-1" WHEN 1 THEN -1 ELSE 1 END
                            * q.rate_num / 100
                        ), 2
                    ) AS quarlity_num,
                    w.user_id
                FROM ' . $this->get_table(self::WORKLOAD_TABLE) . ' w
                INNER JOIN ' . $this->get_table(self::QUARLITY_TABLE) . ' q
                   ON w.id = q.workload_id'
                ;
        $whereList = array();
        if ($userIds) {
            $whereList[] = 'q.user_id IN (' . join(',', $userIds) . ')';
        }
        if ($belongMonth) {
            if (!empty($belongMonth['start']) || !empty($belongMonth['end'])) {
                if (!empty($belongMonth['start'])) {
                    $whereList[] = 'q.belong_month >= ' . intval($belongMonth['start']);
                }

                if (!empty($belongMonth['end'])) {
                    $whereList[] = 'q.belong_month <= ' . intval($belongMonth['end']);
                }
            } else {
                $whereList[] = 'q.belong_month IN (' . join(',', $belongMonth)  . ')';
            }
        }

        $where = null;
        if ($whereList) {
            $where = join(' AND ', $whereList);
        }

        $list = $this->query_all($sql, PHP_INT_MAX, 0, $where, $groupBy);

        return $list;
        $keys = array_column($list, 'user_id');

        return array_combine($keys, $list);
    }

    /**
     * 基于书稿做工作量数据统计
     * @param array $bookIds 编辑ids
     * @param int   $status  状态
     *
     * @return float
     */
    public function getWorkloadStatByBookIds ($bookIds = array(), $status = null, $belongMonth = null)
    {
        if (isset($belongMonth) && ! is_array($belongMonth)) {
            $belongMonth = array($belongMonth);
        }
        if (isset($status) && ! is_array($status)) {
            $status = array(intval($status));
        }
        // ( (目录+正文)*目录正文字数+答案*答案字数...) * 系数
        $sql = 'SELECT
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
                    book_id
                FROM ' . $this->get_table(self::WORKLOAD_TABLE);
        $whereList = array();
        if ($bookIds) {
            $whereList[] = 'book_id IN (' . join(',', $bookIds) . ')';
        }
        if ($status) {
            $whereList[] = 'status IN ( ' . join(',', $status) . ')';
        }
        if ($belongMonth) {
            $whereList[] = 'belong_month IN (' . join(',', $belongMonth)  . ')';
        }

        $where = null;
        if ($whereList) {
            $where = join(' AND ', $whereList);
        }

        $list = $this->query_all($sql, PHP_INT_MAX, 0, $where, 'book_id');
        $keys = array_column($list, 'book_id');

        return array_combine($keys, $list);
    }

    /**
     * 根据日期获取请假数据
     * @param date $startDate 开始日期YYYY-mm-dd
     * @param date $endDate   结束日期YYYY-mm-dd
     * @param array $userIds  用户id列表。 将根据用户ID列表获取数据
     *
     * @return array
     */
    public function getAskLeaveByDate($startDate, $endDate, $userIds=array())
    {
        // 转换时间戳
        $startDate = strpos($startDate, ' ')>0 ? $startDate : ($startDate . ' 00:00:00'); // 没有小时数据， 补充对应小时
        $endDate   = strpos($endDate, ' ')>0 ? $endDate : ($endDate . ' 23:59:59'); // 没有小时数据， 补充对应小时
        $startTime = strtotime($startDate);
        $endTime   = strtotime($endDate);
        // 时间范围限制
        $where = array('leave_start_time <=' . $endTime . ' AND leave_end_time >=' .$startTime );
        if ($userIds) {
            $userIds = is_array($userIds) ? $userIds : array($userIds);
            $where[] = 'user_id IN (' . join(',', $userIds) . ')';
        }

        $itemList = $this->model('sinhoWorkload')
                         ->fetch_all(sinhoWorkloadModel::ASK_LEAVE_TABLE, join(' AND ', $where) );

        return $itemList;
    }

    /**
     * 更新工资数据
     * @param int $id id
     * @param array $data 数据信息
     * @return bool
     */
    public function updateSalaryDetail ($id, $data)
    {
        $result = false;
        if ($set = $this->processSalaryDetailData($data)) {
            $result = $this->update(self::SALARY_DETAIL_TABLE, $set, 'id = ' . intval($id));
        }

        return $result;
    }

    /**
     * 处理工资数据， 供更新和添加使用
     * @param array $data 数据信息
     * @return multitype:string NULL
     */
    protected function processSalaryDetailData ($data)
    {
        // 转换成浮点型的数据
        $doubleVars = array (
            'shifa_gongzi',					// decimal(7,2) DEFAULT NULL COMMENT '实发工资',
            'jiben_gongzi',					 // decimal(7,2) DEFAULT NULL COMMENT '基本工资',
            'jintie',						 // decimal(6,2) DEFAULT NULL COMMENT '津贴',
            'zhiliangkaohe',				 // decimal(6,2) DEFAULT NULL COMMENT '质量考核奖惩',
            'jixiao',						 // decimal(7,2) DEFAULT NULL COMMENT '绩效',
            'chaoejiangli',					// decimal(7,2) DEFAULT NULL COMMENT '超额奖励',
            'jiben_heji',
            'gongzi_heji',			    	 // decimal(8,2) DEFAULT NULL COMMENT '工资合计',
            'quanqinjiang',					 // decimal(6,2) DEFAULT NULL COMMENT '全勤奖',
            'jiabanbutie',					 // decimal(7,2) DEFAULT NULL COMMENT '加班补贴',
            'kaoqin_heji',					 // decimal(6,2) DEFAULT NULL COMMENT '全勤奖+加班补贴',
            'queqinkoukuan',				 // decimal(7,2) DEFAULT NULL COMMENT '缺勤扣款',
            'qingjiakoukuan',				 // decimal(7,2) DEFAULT NULL COMMENT '请假扣款',
            'chidaokoukuan',				 // decimal(7,2) DEFAULT NULL COMMENT '迟到扣款',
            'koukuan_heji',					 // decimal(7,2) DEFAULT NULL COMMENT '扣款合计',
            'shangnianpingjun',				 // decimal(7,2) DEFAULT NULL COMMENT '上年平均工资',
            'gonghuijingfei_gongzi',
            'yanglao_geren',				 // decimal(6,2) DEFAULT NULL COMMENT '养老保险 个人',
            'yiliao_geren',					 // decimal(6,2) DEFAULT NULL COMMENT '医疗保险 个人',
            'shiye_geren',					 // decimal(6,2) DEFAULT NULL COMMENT '失业保险 个人',
            'gongshang_geren',				 // decimal(6,2) DEFAULT NULL COMMENT '工伤保险 个人',
            'shengyu_geren',				 // decimal(6,2) DEFAULT NULL COMMENT '生育保险 个人',
            'gongjijin_geren',				 // decimal(6,2) DEFAULT NULL COMMENT '公积金 个人',
            'geren_heji',				     // decimal(6,2) DEFAULT NULL COMMENT '个人五险一金合计',
            'yingshui_gongzi',				 // decimal(7,2) DEFAULT NULL COMMENT '应税工资',
            'geshui',						 // decimal(6,2) DEFAULT NULL COMMENT '个税',
            'yanglao_gongsi',				 // decimal(6,2) DEFAULT NULL COMMENT '养老保险 公司',
            'yiliao_gongsi',				 // decimal(6,2) DEFAULT NULL COMMENT '医疗保险 公司',
            'shiye_gongsi',					 // decimal(6,2) DEFAULT NULL COMMENT '失业保险 公司',
            'gongshang_gongsi',				 // decimal(6,2) DEFAULT NULL COMMENT '工伤保险 公司',
            'shengyu_gongsi',				 // decimal(6,2) DEFAULT NULL COMMENT '生育保险 公司',
            'gongjijin_gongsi',				 // decimal(6,2) DEFAULT NULL COMMENT '公积金 公司',
            'gongsi_heji',				     // decimal(6,2) DEFAULT NULL COMMENT '公司五险一金合计',
            'yingchuqin',					 // float DEFAULT NULL COMMENT '应出勤天数',
            'shijichuqin',					 // float DEFAULT NULL COMMENT '实际出勤天数',
            'bingjia_tianshu',				 // float DEFAULT NULL COMMENT '病假天数',
            'bingjia_xiaoshi',				 // float DEFAULT NULL COMMENT '病假小时数',
            'bingjia_kouchu',				 // decimal(6,2) DEFAULT NULL COMMENT '病假扣除',
            'shijia_xiaoshi',				 // float DEFAULT NULL COMMENT '事假小时数',
            'shijia_tianshu',				 // float DEFAULT NULL COMMENT '事假天数',
            'shijia_kouchu',				 // decimal(6,2) DEFAULT NULL COMMENT '事假扣除',
        );
        // 转换成整数的数据
        $intVars   = array(
            'id',						 // int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '工资表',
            'belong_year_month',		// int(6) DEFAULT NULL COMMENT '所属月份',
            'user_id',						 // int(11) DEFAULT NULL COMMENT '用户id',
            'canbu',						 // smallint(6) DEFAULT NULL COMMENT '餐补天数',
        );
        $allowVars = array(
            'id',						 // int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '工资表',
            'belong_year_month',		// int(6) DEFAULT NULL COMMENT '所属月份',
            'user_id',						 // int(11) DEFAULT NULL COMMENT '用户id',
            'shifa_gongzi',					// decimal(7,2) DEFAULT NULL COMMENT '实发工资',
            'jiben_gongzi',					 // decimal(7,2) DEFAULT NULL COMMENT '基本工资',
            'jintie',						 // decimal(6,2) DEFAULT NULL COMMENT '津贴',
            'zhiliangkaohe',				 // decimal(6,2) DEFAULT NULL COMMENT '质量考核奖惩',
            'jixiao',						 // decimal(7,2) DEFAULT NULL COMMENT '绩效',
            'chaoejiangli',					// decimal(7,2) DEFAULT NULL COMMENT '超额奖励',
            'jiben_heji',                    //decimal(8,0) DEFAULT NULL COMMENT '基本收入合计=基本工资+津贴+质量考核+绩效+超额奖励',
            'quanqinjiang',					 // decimal(6,2) DEFAULT NULL COMMENT '全勤奖',
            'jiabanbutie',					 // decimal(7,2) DEFAULT NULL COMMENT '加班补贴',
            'kaoqin_heji',					 // decimal(6,2) DEFAULT NULL COMMENT '全勤奖+加班补贴',
            'queqinkoukuan',				 // decimal(7,2) DEFAULT NULL COMMENT '缺勤扣款',
            'qingjiakoukuan',				 // decimal(7,2) DEFAULT NULL COMMENT '请假扣款',
            'chidaokoukuan',				 // decimal(7,2) DEFAULT NULL COMMENT '迟到扣款',
            'koukuan_heji',					 // decimal(7,2) DEFAULT NULL COMMENT '扣款合计',
            'gongzi_heji',			    	 // decimal(8,2) DEFAULT NULL COMMENT '工资合计',
            'shangnianpingjun',				 // decimal(7,2) DEFAULT NULL COMMENT '上年平均工资',
            'gonghuijingfei_gongzi',
            'yanglao_geren',				 // decimal(6,2) DEFAULT NULL COMMENT '养老保险 个人',
            'yiliao_geren',					 // decimal(6,2) DEFAULT NULL COMMENT '医疗保险 个人',
            'shiye_geren',					 // decimal(6,2) DEFAULT NULL COMMENT '失业保险 个人',
            'gongshang_geren',				 // decimal(6,2) DEFAULT NULL COMMENT '工伤保险 个人',
            'shengyu_geren',				 // decimal(6,2) DEFAULT NULL COMMENT '生育保险 个人',
            'gongjijin_geren',				 // decimal(6,2) DEFAULT NULL COMMENT '公积金 个人',
            'geren_heji',				     // decimal(6,2) DEFAULT NULL COMMENT '个人五险一金合计',
            'yingshui_gongzi',				 // decimal(7,2) DEFAULT NULL COMMENT '应税工资',
            'geshui',						 // decimal(6,2) DEFAULT NULL COMMENT '个税',
            'yanglao_gongsi',				 // decimal(6,2) DEFAULT NULL COMMENT '养老保险 公司',
            'yiliao_gongsi',				 // decimal(6,2) DEFAULT NULL COMMENT '医疗保险 公司',
            'shiye_gongsi',					 // decimal(6,2) DEFAULT NULL COMMENT '失业保险 公司',
            'gongshang_gongsi',				 // decimal(6,2) DEFAULT NULL COMMENT '工伤保险 公司',
            'shengyu_gongsi',				 // decimal(6,2) DEFAULT NULL COMMENT '生育保险 公司',
            'gongjijin_gongsi',				 // decimal(6,2) DEFAULT NULL COMMENT '公积金 公司',
            'gongsi_heji',				     // decimal(6,2) DEFAULT NULL COMMENT '公司五险一金合计',
            'yingchuqin',					 // float DEFAULT NULL COMMENT '应出勤天数',
            'shijichuqin',					 // float DEFAULT NULL COMMENT '实际出勤天数',
            'canbu',						 // smallint(6) DEFAULT NULL COMMENT '餐补天数',
            'bingjia_tianshu',				 // float DEFAULT NULL COMMENT '病假天数',
            'bingjia_xiaoshi',				 // float DEFAULT NULL COMMENT '病假小时数',
            'bingjia_kouchu',				 // decimal(6,2) DEFAULT NULL COMMENT '病假扣除',
            'shijia_xiaoshi',				 // float DEFAULT NULL COMMENT '事假小时数',
            'shijia_tianshu',				 // float DEFAULT NULL COMMENT '事假天数',
            'shijia_kouchu',				 // decimal(6,2) DEFAULT NULL COMMENT '事假扣除',
            'fadingjiari',					 // float DEFAULT NULL COMMENT '法定假日天数',
            'remark',						 // varchar(400) DEFAULT NULL COMMENT '备注信息',
            'batch_key',					 // varchar(32) NOT NULL DEFAULT '' COMMENT '数据上传的批次',
        ); // 允许存在的数据

        $set = array();
        foreach ($doubleVars as $varName) {
            if (! isset($data[$varName])) {
                continue;
            }

            //$data[$varName] = trim($data[$varName], " \t\n\r\0\x0B");
            $data[$varName] = doubleval($data[$varName]);
            if (in_array($varName, array('yingchuqin', 'shijichuqin', 'bingjia_tianshu', 'shijia_tianshu')) ) {
                $data[$varName] = round($data[$varName], 3); // 保留小数点后3位
            } else {
                $data[$varName] = round($data[$varName], 3); // 保留小数点后2位
            }
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
            //if (0!=$data[$varName]) {
                $set[$varName] = $data[$varName];
            //}
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
     * 添加新工资数据
     * @param array $data 数据关联数组
     * @return int
     */
    public function addSalaryDetail ($data)
    {
        $id = 0;
        if ($set = $this->processSalaryDetailData($data)) {
            $id = $this->insert(self::SALARY_DETAIL_TABLE, $set);
        }

        return $id;
    }

    /**
     * 获取工资统计信息
     */
    public function getSalaryStatistic ($startMonth, $endMonth, $userIds=array())
    {
        if (isset($belongMonth) && ! is_array($belongMonth)) {
            $belongMonth = array($belongMonth);
        }
        // ( (目录+正文)*目录正文字数+答案*答案字数...) * 系数
        $sql = 'SELECT
                    belong_year_month,
                    COUNT(1) AS staff_total,
                    (SUM(shifa_gongzi) + SUM(geren_heji)  + SUM(gongzi_heji) + SUM(gonghuijingfei_gongzi)*0.02 )
                    AS gongsi_quanbu,
                    SUM(shifa_gongzi) AS shifa_gongzi,
                    SUM(geren_heji) AS geren_heji,
                    SUM(gongsi_heji) AS gongsi_heji,
                    AVG(shifa_gongzi) AS pingjun_gongzi
                FROM ' . $this->get_table(self::SALARY_DETAIL_TABLE)
                ;
        $where = null;
        $whereList = array();
        $whereList[] = 'belong_year_month >= ' . intval($startMonth);
        $whereList[] = 'belong_year_month <= ' . intval($endMonth);
        if ($userIds) {
            $whereList[] = 'user_id IN (' . join(',', $userIds) .')';
        }
        $whereList[] = 'user_id != ' . self::GONGHUI_FEE_IGNORE_USER_ID; // 排除特殊用户

        if ($whereList) {
            $where = join(' AND ', $whereList);
        }

        $groupBy = 'belong_year_month';

        $list = $this->query_all($sql, PHP_INT_MAX, 0, $where, $groupBy);

        return $list;
    }

    /**
     * 获取工会会费
     */
    public function getGonghuiFee ($startMonth, $endMonth, $userIds=array())
    {
        if (isset($belongMonth) && ! is_array($belongMonth)) {
            $belongMonth = array($belongMonth);
        }
        // ( (目录+正文)*目录正文字数+答案*答案字数...) * 系数
        $sql = 'SELECT
                    belong_year_month,
                    SUM(gonghuijingfei_gongzi)*0.02  AS  gonghuijingfei
                FROM ' . $this->get_table(self::SALARY_DETAIL_TABLE)
                ;
        $where = null;
        $whereList = array();
        $whereList[] = 'belong_year_month >= ' . intval($startMonth);
        $whereList[] = 'belong_year_month <= ' . intval($endMonth);
        if ($userIds) {
            $whereList[] = 'user_id IN (' . join(',', $userIds) . ')';
        }

        if ($whereList) {
            $where = join(' AND ', $whereList);
        }

        $groupBy = 'belong_year_month';

        $list = $this->query_all($sql, PHP_INT_MAX, 0, $where, $groupBy);

        return $list;
    }

    /**
     * 获取收入支出期初数据
     */
    public function getBeginningValue ($yearMonth)
    {
        // ( (目录+正文)*目录正文字数+答案*答案字数...) * 系数
        $sql = 'SELECT
                    SUM(price * amount * direction) as total
                FROM ' . $this->get_table(self::INCOME_OUTPUT_TABLE);

        return $this->query_row($sql, 'belong_year_month <' . intval($yearMonth));

    }
}

/* EOF */
