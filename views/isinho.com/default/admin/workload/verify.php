<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<!-- Theme switcher -->
<div class="theme-switch">
    <div class="icon inOut"><i class="rotate icon-setting"></i></div>
    <a class="btn btn-large btn-danger row" onclick="highlight_issue();"><?php _e('疑义标红'); ?></a>
    <br/><br/>
                        <a class="btn btn-large btn-primary row" onclick="confirm_workload();"><?php _e('确认核算'); ?></a>
                        <br/><br/>
                        <a class="btn btn-large btn-warning row" onclick="send_warning();"><?php _e('弹回错误'); ?></a>
</div>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
            <h3>
                <ul class="nav nav-tabs">
                    <li class="<?php if (!$_GET['belong_month']) echo 'active'; ?>">
                        <a href="#index" data-toggle="tab"><?php _e('工作量核算'); ?></a>
                    </li>
                    <li class="<?php if ($_GET['belong_month']) echo 'active'; ?>">
                        <a href="#amount_stat" data-toggle="tab"><?php _e('绩效统计'); ?></a>
                    </li>
                </ul>
            </h3>
        </div>

        <div class="mod-body tab-content padding5px">
            <div class="tab-pane <?php if (!$_GET['belong_month']) echo 'active'; ?>" id="index">
                <?php if ($_GET['action'] == 'search') { ?>
                    <div class="alert alert-info"><?php _e('找到 %s 条符合条件的内容', intval($this->totalRows)); ?></div>
                <?php } ?>

                <div class="table-responsive">
                    <form id="workload_verify_form" action="admin/ajax/workload/confirm/" method="post">
                        <input type="hidden" id="action" name="action" value="" />

                        <table class="table table-striped px10 no-padding no-margin workload-list" id="workload_list_for_verify">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="text-left"><?php _e('日期'); ?></th>
                                    <th><?php _e('责编'); ?></th>
                                    <th><?php _e('类别'); ?></th>
                                    <th><?php _e('系列'); ?></th>
                                    <th><?php _e('书名'); ?></th>
                                    <th><?php _e('校次'); ?></th>
                                    <th><?php _e('类别'); ?></th>
                                    <th><?php _e('遍次'); ?></th>
                                    <th><?php _e('目录'); ?></th>
                                    <th><?php _e('正文'); ?></th>
                                    <th class="red-right-border"><?php _e('千字/页'); ?></th>
                                    <th><?php _e('答案'); ?></th>
                                    <th class="red-right-border"><?php _e('千字/页'); ?></th>
                                    <th><?php _e('试卷'); ?></th>
                                    <th class="red-right-border"><?php _e('千字/页'); ?></th>
                                    <th><?php _e('试卷<br/>答案'); ?></th>
                                    <th class="red-right-border"><?php _e('千字/页'); ?></th>
                                    <th><?php _e('课后<br/>作业'); ?></th>
                                    <th class="red-right-border"><?php _e('千字/页'); ?></th>
                                    <th><?php _e('功能册'); ?></th>
                                    <th class="red-right-border"><?php _e('千字/页'); ?></th>
                                    <th><?php _e('功能册<br/>答案'); ?></th>
                                    <th class="red-right-border"><?php _e('千字/页'); ?></th>
                                    <th><?php _e('系数'); ?></th>
                                    <th><?php _e('核算总<br/>字数(千)'); ?></th>
                                    <th><?php _e('应发<br/>金额'); ?></th>
                                    <th><?php _e('备注'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($this->itemsList) {  $totalChars = 0; $_index=0;?>
                                    <?php foreach ($this->itemsList as $itemInfo) { $_index++; ?>
                                        <tr data-book-id="<?php echo $itemInfo['id']; ?>" class="book-line">
                                            <td><?php echo $_index;?></td>
                                            <td class="text-left">
                                                <a class="md-tip" title="<?php _e('发稿日期');
                                                                            echo $itemInfo['delivery_date']; ?> <?php _e('回稿日期');
                                                                                                                                echo $itemInfo['return_date']; ?>" data-toggle="tooltip"><?php echo substr($itemInfo['delivery_date'], 5), '~', substr($itemInfo['return_date'], 5); ?></a>
                                            </td>
                                            <td><?php echo $this->userList[$itemInfo['user_id']]['user_name']; ?></td>
                                            <td class="js-category"><a href="/admin/check_list/by-book__id-<?php echo $itemInfo['id'];?>" target="_blank"><?php echo $itemInfo['category']; ?></a></td>
                                            <td class="js-serial"><a href="/admin/check_list/by-book__id-<?php echo $itemInfo['id'];?>" target="_blank"><?php echo $itemInfo['serial']; ?></a></td>
                                            <td class="js-bookname"><a href="/admin/check_list/by-book__id-<?php echo $itemInfo['id'];?>" target="_blank"><?php echo $itemInfo['book_name']; ?></a></td>
                                            <td class="js-proofreading-times"><a href="/admin/check_list/by-book__id-<?php echo $itemInfo['id'];?>" target="_blank"><?php echo $itemInfo['proofreading_times']; ?></a></td>
                                            <td data-td-name="category" ><?php echo $itemInfo['category']; ?></td>
                                            <td data-td-name="working_times" ><?php echo $itemInfo['working_times']; ?></td>
                                            <td data-td-name="content_table_pages" ><?php echo $itemInfo['content_table_pages']; ?></td>
                                            <td data-td-name="text_pages" ><?php echo $itemInfo['text_pages']; ?></td>
                                            <td data-td-name="text_table_chars_per_page"  class="red-right-border"><?php echo $itemInfo['text_table_chars_per_page']; ?></td>
                                            <td data-td-name="answer_pages" ><?php echo $itemInfo['answer_pages']; ?></td>
                                            <td data-td-name="answer_chars_per_page"  class="red-right-border"><?php echo $itemInfo['answer_chars_per_page']; ?></td>
                                            <td data-td-name="test_pages" ><?php echo $itemInfo['test_pages']; ?></td>
                                            <td data-td-name="test_chars_per_page"  class="red-right-border"><?php echo $itemInfo['test_chars_per_page']; ?></td>
                                            <td data-td-name="test_answer_pages" ><?php echo $itemInfo['test_answer_pages']; ?></td>
                                            <td data-td-name="test_answer_chars_per_page"  class="red-right-border"><?php echo $itemInfo['test_answer_chars_per_page']; ?></td>
                                            <td data-td-name="exercise_pages" ><?php echo $itemInfo['exercise_pages']; ?></td>
                                            <td data-td-name="exercise_chars_per_page"  class="red-right-border"><?php echo $itemInfo['exercise_chars_per_page']; ?></td>
                                            <td data-td-name="function_book" ><?php echo $itemInfo['function_book']; ?></td>
                                            <td data-td-name="function_book_chars_per_page"  class="red-right-border"><?php echo $itemInfo['function_book_chars_per_page']; ?></td>
                                            <td data-td-name="function_answer"><?php echo $itemInfo['function_answer']; ?></td>
                                            <td data-td-name="function_answer_chars_per_page"  class="red-right-border"><?php echo $itemInfo['function_answer_chars_per_page']; ?></td>
                                            <td data-td-name="weight" ><?php echo $itemInfo['weight']; ?></td>
                                            <td><?php echo $itemInfo['total_chars']; ?></td>
                                            <td>&nbsp;</td>
                                            <!-- <td><?php echo $itemInfo['remarks']; ?></td> -->
                                            <td>
                                                <a target="_blank" href="admin/books/book/from_id-<?php echo $itemInfo['id']; ?>" class="icon icon-cogs md-tip" title="<?php _e('书稿照抄'); ?>" data-toggle="tooltip"></a>
                                                <a target="_blank" href="admin/books/book/id-<?php echo $itemInfo['id']; ?>" class="icon icon-edit md-tip" title="<?php _e('编辑'); ?>" data-toggle="tooltip"></a>
                                            </td>
                                        </tr>
                                        <?php if (isset($this->workloadList[$itemInfo['id']])) { $_indexWorkload = 1; ?>

                                            <?php foreach ($this->workloadList[$itemInfo['id']] as $workloadInfo) { $totalChars += $workloadInfo['total_chars'] ;?>
                                                <tr data-db-id="<?php echo $workloadInfo['id']; ?>" data-book-id="<?php echo $itemInfo['id']; ?>" class="workload-line<?php echo $workloadInfo['status'] == 1 ? ' verified-line' : ($workloadInfo['status'] == 3 ? ' recording-line':' verifying-line'); ?>" data-verify-remark='<?php echo $workloadInfo['verify_remark']; ?>'>
                                                    <td><?php echo $_index,'.',$_indexWorkload++;?></td>
                                                    <td class="text-left">
                                                        <input type="hidden" name="id[]" value="<?php echo $workloadInfo['id']; ?>" />
                                                        <a class="md-tip" title="<?php _e('发稿日期');
                                                            echo $workloadInfo['add_time']>0 ? date('Y-m-d', $workloadInfo['add_time']) : $itemInfo['delivery_date']; ?> <?php _e('回稿日期');
                                                            echo $workloadInfo['fill_time']>0 ? date('Y-m-d', $workloadInfo['fill_time']) :  $itemInfo['return_date'];
                                                        ?>" data-toggle="tooltip"><?php
                                                            echo $workloadInfo['add_time']>0 ? date('m-d', $workloadInfo['add_time']) : substr($itemInfo['delivery_date'], 5);
                                                            echo  '~';
                                                            echo $workloadInfo['fill_time']>0 ? date('m-d', $workloadInfo['fill_time']) : substr($itemInfo['return_date'], 5);
                                                        ?></a>
                                                    </td>
                                                    <td class="no-word-break"><?php echo $this->userList[$workloadInfo['user_id']]['user_name']; ?></td>
                                                    <td class="js-category"><?php echo $itemInfo['category']; ?></td>
                                                    <td class="js-serial"><?php echo $itemInfo['serial']; ?></td>
                                                    <td class="js-bookname"><?php echo $itemInfo['book_name']; ?></td>
                                                    <td class="js-proofreading-times"><?php echo $itemInfo['proofreading_times']; ?></td>
                                                    <td data-td-name="category" class="js-allow-mark"><a><?php echo $workloadInfo['category']; ?></a></td>
                                                    <td data-td-name="working_times" class="js-allow-mark"><a><?php echo $workloadInfo['working_times']; ?></a></td>
                                                    <td data-td-name="content_table_pages" class="js-allow-mark"><a><?php echo $workloadInfo['content_table_pages']; ?></a></td>
                                                    <td data-td-name="text_pages" class="js-allow-mark"><a><?php echo $workloadInfo['text_pages']; ?></a></td>
                                                    <td data-td-name="text_table_chars_per_page" class="js-allow-mark red-right-border"><a><?php echo $workloadInfo['text_table_chars_per_page']; ?></a></td>
                                                    <td data-td-name="answer_pages" class="js-allow-mark"><a><?php echo $workloadInfo['answer_pages']; ?></a></td>
                                                    <td data-td-name="answer_chars_per_page" class="js-allow-mark red-right-border"><a><?php echo $workloadInfo['answer_chars_per_page']; ?></a></td>
                                                    <td data-td-name="test_pages" class="js-allow-mark"><a><?php echo $workloadInfo['test_pages']; ?></a></td>
                                                    <td data-td-name="test_chars_per_page" class="js-allow-mark red-right-border"><a><?php echo $workloadInfo['test_chars_per_page']; ?></a></td>
                                                    <td data-td-name="test_answer_pages" class="js-allow-mark"><a><?php echo $workloadInfo['test_answer_pages']; ?></a></td>
                                                    <td data-td-name="test_answer_chars_per_page" class="js-allow-mark red-right-border"><a><?php echo $workloadInfo['test_answer_chars_per_page']; ?></a></td>
                                                    <td data-td-name="exercise_pages" class="js-allow-mark"><a><?php echo $workloadInfo['exercise_pages']; ?></a></td>
                                                    <td data-td-name="exercise_chars_per_page" class="js-allow-mark red-right-border"><a><?php echo $workloadInfo['exercise_chars_per_page']; ?></a></td>
                                                    <td data-td-name="function_book" class="js-allow-mark"><a><?php echo $workloadInfo['function_book']; ?></a></td>
                                                    <td data-td-name="function_book_chars_per_page" class="js-allow-mark red-right-border"><a><?php echo $workloadInfo['function_book_chars_per_page']; ?></a></td>
                                                    <td data-td-name="function_answer" class="js-allow-mark"><a><?php echo $workloadInfo['function_answer']; ?></a></td>
                                                    <td data-td-name="function_answer_chars_per_page" class="js-allow-mark red-right-border"><a><?php echo $workloadInfo['function_answer_chars_per_page']; ?></a></td>
                                                    <td data-td-name="weight" class="js-allow-mark"><a><?php echo $workloadInfo['weight']; ?></a></td>
                                                    <td data-td-name="total_chars" class=""><a><?php echo $workloadInfo['total_chars']; ?></a></td>
                                                    <td data-td-name="payable_amount" class=""><a><?php echo round($workloadInfo['total_chars'] * 2, 2); ?><?php //echo $workloadInfo['payable_amount']; ?></a></td>
                                                    <!-- 存在js-allow-diff-book-mark, 允许跨书稿间计算单元格；js-can-not-compute表示单元格不可以参与计算 -->
                                                    <td data-td-name="remarks" class="js-allow-mark js-allow-diff-book-mark js-can-not-compute"><a><?php echo $workloadInfo['remarks']; ?></a></td>
                                                </tr>
                                            <?php } ?>
                                        <?php } //end if
                                        ?>
                                    <?php } ?>

                                                <tr class="book-line">
                                                    <td ><strong>合计</strong></td>
                                                    <td colspan="24"></td>
                                                    <td>&nbsp;<strong><?php echo $totalChars; ?></strong>&nbsp;</td>
                                                    <td>&nbsp;<strong><?php echo round($totalChars * 2, 2); ?></strong>&nbsp;</td>
                                                    <td ></td>
                                                </tr>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="28">没有待核算工作量数据</td>
                                    </tr>

                                <?php } ?>
                            </tbody>
                        </table>
                    </form>
                </div>
                <?php if ($this->itemsList) { ?>
                    <div class="mod-table-foot text-center">
                        <a class="btn btn-large btn-danger" onclick="highlight_issue();"><?php _e('疑义标红'); ?></a>
                        &nbsp;
                        <a class="btn btn-large btn-primary" onclick="confirm_workload();"><?php _e('确认核算'); ?></a>
                        &nbsp;
                        <a class="btn btn-large btn-warning" onclick="send_warning();"><?php _e('弹回错误'); ?></a>
                    </div>
                <?php } ?>
            </div>
            <div class="tab-pane <?php if ($_GET['belong_month']) echo 'active'; ?>" id="amount_stat">

            <div class="">
                    <div class="col-sm-5 text-right">
                        <!-- <label class="line-height-25">责编:</label> -->
                        <select id="sinho_editor" multiple><?php echo $this->itemOptions;?></select>
                    </div>
                    <div class="col-sm-1 text-right">
                        <label class="line-height-25">月份:</label>
                    </div>
                    <div class="col-sm-2 text-right icon-date-container">
                        <input id="belongMonth" type="text" class="form-control icon-indent js-date-input js-monthpicker" placeholder="开始月份" value="<?php echo $this->belongMonth; ?>" readonly>
                        <i class="icon icon-date"></i>
                    </div>
                    <span class="mod-symbol col-xs-1 col-sm-1">-</span>
                    <div class="col-sm-2 text-right icon-date-container">
                        <input id="endBelongMonth" type="text" class="form-control icon-indent js-date-input js-monthpicker" placeholder="结束月份" value="<?php echo $this->endBelongMonth; ?>" readonly>
                        <i class="icon icon-date"></i>
                    </div>
                    <div class="col-sm-2 text-right">
                        <a href="javascript:query_workload();" class="btn btn-primary btn-sm date-seach">确认查询</a>
                    </div>
                </div>
                <!-- <div class="">
                    <div class="col-sm-9 text-right">
                        <label class="line-height-25">选择查询月份:</label>
                    </div>
                    <div class="col-sm-2 text-right">
                        <input id="belongMonth" type="text" class="form-control icon-indent js-monthpicker" placeholder="选择查询月份" value="<?php echo $this->belongMonth; ?>">
                        <i class="icon icon-date"></i>
                    </div>
                    <div class="col-sm-1">
                        <a href="javascript:query_workload();" class="btn btn-primary btn-sm date-seach">确认查询</a>
                    </div>
                </div> -->
                <div class="table-responsive">

                    <table class="table table-striped no-padding no-margin table-bordered ">
                        <thead>
                            <tr>
                                <th><?php _e('责编'); ?></th>
                                <th><?php _e('目录'); ?></th>
                                <th><?php _e('正文'); ?></th>
                                <th><?php _e('答案'); ?></th>
                                <th><?php _e('试卷'); ?></th>
                                <th><?php _e('试卷<br/>答案'); ?></th>
                                <th><?php _e('课后<br/>作业'); ?></th>
                                <th><?php _e('功能册'); ?></th>
                                <th><?php _e('功能册<br/>答案'); ?></th>
                                <th><?php _e('核算总<br/>字数(千)'); ?></th>
                                <th><?php _e('应发<br/>金额'); ?></th>
                                <th><?php _e('月份'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($this->totalCharsList) { ?>
                                <?php foreach ($this->totalCharsList as $workloadInfo) { ?>
                                    <tr data-db-id="<?php echo $workloadInfo['id']; ?>" data-book-id="<?php echo $workloadInfo['id']; ?>" class="workload-line<?php
                                            //if ($workloadInfo['status']==sinhoWorkloadModel::STATUS_VERIFIED) echo ' verified-line';
                                            if ($workloadInfo['status'] == sinhoWorkloadModel::STATUS_VERIFYING) echo ' verifying-line';
                                            //if ($workloadInfo['status']==sinhoWorkloadModel::STATUS_RECORDING) echo ' recording-line';
                                        ?>" data-verify-remark='<?php echo $workloadInfo['verify_remark']; ?>'>
                                        <td class="no-word-break"><a href="admin/check_list/by-user__id-<?php echo $workloadInfo['user_id']; ?>__start_month-<?php echo str_replace('-','',$this->belongMonth);?>__end_month-<?php echo str_replace('-','',$this->belongMonth);?>" class="md-tip" title="<?php _e('查看');
                                            echo $this->userList[$workloadInfo['user_id']]['user_name'];
                                            _e('的工作量');
                                        ?>" data-toggle="tooltip"><?php echo $this->userList[$workloadInfo['user_id']]['user_name']; ?></a></td>
                                        <td data-td-name="content_table_pages" class="js-allow-mark"><a><?php echo $workloadInfo['content_table_pages']; ?></a></td>
                                        <td data-td-name="text_pages" class="js-allow-mark"><a><?php echo $workloadInfo['text_pages']; ?></a></td>
                                        <td data-td-name="answer_pages" class="js-allow-mark"><a><?php echo $workloadInfo['answer_pages']; ?></a></td>
                                        <td data-td-name="test_pages" class="js-allow-mark"><a><?php echo $workloadInfo['test_pages']; ?></a></td>
                                        <td data-td-name="test_answer_pages" class="js-allow-mark"><a><?php echo $workloadInfo['test_answer_pages']; ?></a></td>
                                        <td data-td-name="exercise_pages" class="js-allow-mark"><a><?php echo $workloadInfo['exercise_pages']; ?></a></td>
                                        <td data-td-name="function_book" class="js-allow-mark"><a><?php echo $workloadInfo['function_book']; ?></a></td>
                                        <td data-td-name="function_answer" class="js-allow-mark"><a><?php echo $workloadInfo['function_answer']; ?></a></td>
                                        <td data-td-name="total_chars" class=""><a><?php echo $workloadInfo['total_chars']; ?></a></td>
                                        <td data-td-name="payable_amount" class=""><a><?php echo round($workloadInfo['total_chars'] * 2, 2); ?></a></td>
                                        <td data-td-name="belong_month" class=""><a><?php echo $workloadInfo['belong_month']; ?></a></td>
                                    </tr>
                                <?php } //end foreach
                                ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    /**
     * 自动计算书稿， 将疑义部分自动标红
     */
    function highlight_issue () {
        // 获取所有待计算的行
        var $lines = $('#workload_list_for_verify >tbody >tr');
        var bookId, baseRef={}, workload={}, $tds, keyName;
        // 将书稿行参数， 存放； 将工作量的参数，根据不同列计算存放；
        for (var i=0; i<$lines.length; i++) {
            bookId = $lines.eq(i).data('book-id');
            $tds   = $lines.eq(i).find('td[data-td-name]');
            isBook = $lines.eq(i).hasClass('book-line');
            // 初始化书稿行的数据存放
            if (undefined === baseRef[bookId]) {
                baseRef[bookId] = {};
            }
            // 初始化工作量的数据存放
            if (undefined === workload[bookId]) {
                workload[bookId] = {};
            }
            // 每行中， 找到需要计算的单元格， 获取数据，处理后存放
            for(var j=0; j<$tds.length; j++) {
                keyName = $tds.eq(j).data('td-name');
                if(isBook) { // 存放书稿行数据
                    baseRef[bookId][keyName] = float($tds.eq(j).text(), 4);
                } else if (undefined === workload[bookId][keyName]) {// 存放工作量数据
                    if (keyName=='weight' || keyName.substr(-9)=='_per_page') { // 系数和每页字数， 需要每个单元格的数都存放。 后面判断每个单元格是否和基数相同
                        workload[bookId][keyName] = {};
                        workload[bookId][keyName][i] = float($tds.eq(j).text(), 4);
                    } else {// 页码数
                        workload[bookId][keyName] = float($tds.eq(j).text(), 4);
                    }
                } else {
                    if (keyName=='weight' || keyName.substr(-9)=='_per_page') {// 系数和每页字数， 全都存储
                        workload[bookId][keyName][i] = float($tds.eq(j).text(), 4);
                    } else {// 会从页码数
                        workload[bookId][keyName] += float($tds.eq(j).text(), 4);
                    }
                }
            }
        }
        // 基于存放的书稿参数， 比较工作量对应的数据。 不相同的地方，标红
        for (bookId in baseRef) {
            for(keyName in baseRef[bookId]) {
                if(baseRef[bookId][keyName] === workload[bookId][keyName]) {// 和基数相同，继续比较下一个
                    continue;
                }

                if (keyName=='weight' || keyName.substr(-9)=='_per_page') {// 系数和每页字数，比较每个单元格的数
                    for(i in workload[bookId][keyName]) {
                        if (baseRef[bookId][keyName] != workload[bookId][keyName][i] && $lines.eq(i).hasClass('verifying-line')) {

                            $lines.eq(i).find('td[data-td-name="'+keyName+'"]').addClass('sinho-red-background');
                        }
                    }
                } else {// 页码数， 汇总求和的数比较， 不一样， 要把对应的单元格都标红
                    $('tr.verifying-line[data-book-id="'+bookId+'"] td[data-td-name="'+keyName+'"]').addClass('sinho-red-background');
                }
                //for (i=0; i<$lines.length; i++) {

                //}
            }
        }

        console.info(workload);
    }
    /**
     * 按时间查询工作量, 通过URL跳转方式，传递时间参数
     */
    function query_workload() {

        var userIds = [];
        var $selectUsers = $('#sinho_editor>option:selected');
        for(var i = 0; i<$selectUsers.length; i++) {
            userIds.push($selectUsers.eq(i).val());
        }
        var startMonth = $('#belongMonth').val().replace('-','');
        var endMonth = $('#endBelongMonth').val().replace('-','');
        var url = G_BASE_URL + '/admin/verify_list/user_id'+'-' + userIds.join(',') + '__'+'belong_month'+'-' + startMonth +'__'+'end_month'+'-'+endMonth;
        window.location.href = url;

        return false;
    }
    /**
     * 确认工作量核算。
     */
    function confirm_workload() {
        // 弹框确认后，提交表单
        var onYesCallback = function() {
            ICB.ajax.postForm($('#workload_verify_form'));
        };
        // 显示确认弹框
        ICB.modal.confirm('确认保存核算么？ 保存后不能再修改', onYesCallback);

        return false;
    }
    /**
     * 标识工作量核算错误后， 将错误信息反馈回编辑
     */
    function send_warning() {
        var params = [];
        var $lines = $('.workload-line');
        var $tds, dbId, markInfo;
        // 逐行汇总标识的错误信息
        for (var i = 0; i < $lines.length; i++) {
            $tds = $lines.eq(i).find('.sinho-red-background');

            if (!$tds.length) { // 本行没有错误信息
                continue;
            }
            // 组装标识信息：对应的id，标识的单元格
            markInfo = {
                line: $lines.eq(i).data('db-id'),
                tds: []
            };
            // 将全部错误信息汇总到队列中
            for (var j = 0; j < $tds.length; j++) {
                markInfo.tds.push($tds.eq(j).data('td-name'));
            }
            params.push(markInfo);
        }

        console && console.info(params);
        // 通过ajax提交标识的数据信息
        var url = G_BASE_URL + '/admin/ajax/workload/mark_warning/',
            params = {
                'params': params,
                '_post_type': 'ajax'
            };
        ICB.ajax.requestJson(url, params);

        return false;
    }
    /**
     * 删除工作量
     */
    function deleteItem(id) {
        // 确认弹框
        ICB.modal.confirm(
            _t('确认删除吗？'),
            function() {
                var url = G_BASE_URL + '/admin/ajax/workload/remove/',
                    params = {
                        'id': id,
                        '_post_type': 'ajax'
                    };
                // ajax发送请求
                ICB.ajax.requestJson(
                    url,
                    params,
                    function(response) { // ajax请求成功后回调
                        if (!response) {
                            return false;
                        }

                        if (response.err) {
                            ICB.modal.alert(response.err);
                        } else if (response.errno == 1) {
                            ICB.modal.alert(_t('已删除工作量条目'), {
                                'hidden.bs.modal': function() {
                                    window.location.href = G_BASE_URL + '/admin/fill_list/';
                                }
                            });
                        } else {
                            ICB.modal.alert(_t('请求发生错误'));
                        }
                    }
                );
            }
        );

        return false;
    }
    /**
     * 拆分书稿工作量
     */
    function fillMore(id) {
        ICB.modal.confirm(
            _t('确认拆分工作量？'),
            function() {
                var url = G_BASE_URL + '/admin/ajax/workload/fill_more/',
                    params = {
                        'id': id,
                        '_post_type': 'ajax'
                    };
                ICB.ajax.requestJson(
                    url,
                    params,
                    function(response) {
                        if (!response) {
                            return false;
                        }

                        if (response.err) {
                            ICB.modal.alert(response.err);
                        } else if (response.errno == 1) {
                            ICB.modal.alert(_t('工作量条目已拆分'), {
                                'hidden.bs.modal': function() {
                                    window.location.reload();
                                    //window.location.href = G_BASE_URL + '/admin/fill_list/';
                                }
                            });
                        } else {
                            ICB.modal.alert(_t('请求发生错误'));
                        }
                    }
                );
            }
        );

        return false;
    }

    $(function() {

        // 月份输入框
        $(".js-monthpicker").datetimepicker({
            format: 'yyyy-mm',
            language: 'zh-CN',
            weekStart: 1, // 星期一 为一周开始
            todayBtn: 1, // 显示今日按钮
            autoclose: 1,
            todayHighlight: 1,
            startView: 3, // 显示的日期级别： 0:到分钟， 1：到小时， 2：到天
            forceParse: 0,
            minView: 3, // 0:选择到分钟， 1：选择到小时， 2：选择到天
        });

        $("#sinho_editor").multiselect({
                        nonSelectedText : '<?php _e('---- 选择责编 ----');?>',
                        maxHeight       : 200,
                        buttonWidth     : 400,
                        allSelectedText : '<?php _e('已选择所有人');?>',
                        numberDisplayed : 7, // 选择框最多提示选择多少个人名
        });
        /**
         * 双击单元格，将单元格设置成背景标识出错误
         */
        $('.verifying-line .js-allow-mark').dblclick(function() {
            console && console.info('dblclicked');
            $(this).toggleClass('sinho-red-background');
        });

        /**
         * 允许标识的单元格在点击时的操作
         */
        $('.js-allow-mark').click(function() {
            $('.sinho-compute-item a').tooltip('destroy'); // 移除全部的tooltip
            var bookId = $(this).closest('tr').data('book-id');
            if ($(this).hasClass('js-allow-diff-book-mark')) { // 点击允许跨书稿统计的单元格， 将其他书稿中不允许跨书稿统计的列排除计算
                $('.sinho-compute-item').each(function() {
                    if ($(this).closest('tr').data('book-id') != bookId && !$(this).hasClass('js-allow-diff-book-mark')) {
                        $(this).removeClass('sinho-compute-item');
                    }
                });
            } else { // 点击只允许相同书稿内计算的单元格， 移除不同书稿下的计算单元格样式
                $('.sinho-compute-item').each(function() {
                    if ($(this).closest('tr').data('book-id') != bookId) {
                        $(this).removeClass('sinho-compute-item');
                    }
                });
            }
            // 将单元格样式切换状态了
            $(this).toggleClass('sinho-compute-item');
            // 计算选定单元格结果。 如果没有选定单元格， 不需要进行后面的运算
            var $computeItems = $('.sinho-compute-item');
            if ($computeItems.length == 0) {
                return;
            }

            // 计算选定单元格的求和
            var computeResult = 0;
            var tmp;
            for (var i = 0; i < $computeItems.length; i++) {
                // 标识为不可计算的单元格， 不参与计算
                if ($computeItems.eq(i).hasClass('js-can-not-compute')) {
                    continue;
                }
                tmp = parseFloat($computeItems.eq(i).text()); // 转换成浮点数
                if (!isNaN(tmp)) { // 有转换成非数值的内容，需要过滤掉， 不能参与计算
                    computeResult += tmp;
                }
            }
            // 将计算结果转换数据类型， 防止数据有溢出
            var computeResult = new Number(computeResult);
            var computeResult = new Number(computeResult.toFixed(6)); // 换算成小数点后6位。 应该可以涵盖住
            // 将选定的计算单元格， 加入tooltip， 鼠标滑过，可以显示出来。便于和基数进行比较
            for (var i = 0; i < $computeItems.length; i++) {
                if ($computeItems.eq(i).hasClass('js-can-not-compute')) {
                    continue;
                }
                // 取得数据的实际值， 前后合并两个全角空格， 可以将tooltip显示的效果好一些
                $computeItems.eq(i).find('a').attr('title', '　' + computeResult.valueOf() + '　');
                $computeItems.eq(i).find('a').tooltip({
                    placement: 'top'
                });
            }
            // 将最后选定的计算单元格，显示tooltip
            if ($(this).hasClass('sinho-compute-item')) {
                $(this).find('a').tooltip('show');
            }

            console && console.info(computeResult.valueOf());
        });

        /**
         * 将错误信息标识回显
         */
        $('.verifying-line').each(function() {
            var verifyRemark = $(this).data('verify-remark');
            if (!verifyRemark.length) {
                return;
            }
            //console.info(verifyRemark);
            //verifyRemark = $.parseJSON(verifyRemark);
            for (var i = 0; i < verifyRemark.length; i++) {
                console && console.info('.js-allow-mark[data-td-name="' + verifyRemark[i] + '"]');
                $(this).find('.js-allow-mark[data-td-name="' + verifyRemark[i] + '"]').trigger('dblclick');
            }
            //console.info(verifyRemark);
        });


        $('.theme-switch .icon').click (function(event){
            event.preventDefault();
            if( $ (this).hasClass('inOut')  ){
                $('.theme-switch').stop().animate({right:'0px'},500 );
            } else{
                $('.theme-switch').stop().animate({right:'-120px'},500 );
            }
            $(this).toggleClass('inOut');
            return false;

        }  );
    });



    // var mouse_begin = {
    //     x: 0,
    //     y: 0
    // };
    // var mouse_end = {
    //     x: 0,
    //     y: 0
    // };
    // $(function() {
    //     init();
    //     $("body").mousedown(function() {
    //         $(".tb td").removeClass('td_bg'); //点击表格之外的部分，清空所有选中
    //     })
    // })

    // function init() {
    //     mouseDown();
    //     mouseUp();
    // }

    // function mouseDown() {
    //     $(".tb td").mousedown(function(e) {
    //         e.stopPropagation(); //阻止继承父元素document的mousedown事件
    //         mouse_begin = {
    //             x: $(this).parent().parent().find("tr").index($(this).parent()[0]),
    //             y: $(this).parent().find("td").index($(this)[0])
    //         };
    //         $(".tb td").removeClass('td_bg'); //清空所有选中
    //         $(this).addClass('td_bg');
    //         mouseMove();
    //     });
    // }

    // function mouseMove() {
    //     $(".tb td").mouseover(function() {
    //         $(".tb td").removeClass('td_bg'); //清空所有选中
    //         mouse_end = {
    //             x: $(this).parent().parent().find("tr").index($(this).parent()[0]),
    //             y: $(this).parent().find("td").index($(this)[0])
    //         };
    //         var maxX = mouse_begin.x < mouse_end.x ? mouse_end.x : mouse_begin.x;
    //         var minX = mouse_begin.x < mouse_end.x ? mouse_begin.x : mouse_end.x;
    //         var maxY = mouse_begin.y < mouse_end.y ? mouse_end.y : mouse_begin.y;
    //         var minY = mouse_begin.y < mouse_end.y ? mouse_begin.y : mouse_end.y;
    //         for (var i = minX; i <= maxX; i++) {
    //             for (var j = minY; j <= maxY; j++) {
    //                 $(".tb tr:eq(" + i + ") td:eq(" + j + ")").addClass('td_bg');
    //             }
    //         }
    //         //$(this).addClass('td_bg');
    //     });
    // }

    // function mouseUp() {
    //     $(".tb td").mouseup(function() {
    //         $(".tb td").unbind('mouseover');
    //     });
    // }
</script>

<?php View::output('admin/global/footer.php'); ?>
