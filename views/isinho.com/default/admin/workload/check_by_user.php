

        <div class="mod-body tab-content padding5px">
            <div class="tab-pane active" id="user-workload">

                <div class="table-responsive">
                    <table class="table table-bordered workload-list">
                        <tr>
                         <td>列表颜色说明：</td>
                         <!-- <td class="success">工作量记录中，没有提交到核算</td> -->
                         <td class="info">工作量结算完成，绩效已支付</td>
                         <td class="warning">工作量正在核算，绩效正在核算</td>
                         <td class="success">记录中的工作量，待提交核算</td>
                        </tr>
                    </table>
                </div>
                <br/>
                <div class="">
                 <form id="search_workload_form" target="_blank" action="" method="post" >
                    <div class="col-sm-5 text-right">
                        <!-- <label class="line-height-25">责编:</label> -->
                        <select id="sinho_editor" multiple><?php echo $this->itemOptions;?></select>
                    </div>
                    <div class="col-sm-1 text-right">
                        <label class="line-height-25">月份:</label>
                    </div>
                    <div class="col-sm-2 text-right icon-date-container">
                        <input id="start_month" type="text" class="form-control icon-indent js-date-input js-monthpicker" placeholder="开始月份" value="<?php echo $_GET['start_month'] > 0 ? date('Y-m', strtotime($_GET['start_month'].'01')) : ''; ?>" readonly>
                        <i class="icon icon-date"></i>
                        <i class="icon icon-date-delete icon-delete"></i>
                    </div>
                    <span class="mod-symbol pull-left">-</span>
                    <div class="col-sm-2 text-right icon-date-container">
                        <input id="end_month" type="text" class="form-control icon-indent js-date-input js-monthpicker" placeholder="结束月份" value="<?php echo $_GET['end_month']>0 ? date('Y-m', strtotime($_GET['end_month'].'01')) : ''; ?>" readonly>
                        <i class="icon icon-date"></i>
                        <i class="icon icon-date-delete icon-delete"></i>
                    </div>
                    <div class="col-sm-2 text-right">
                        <a href="javascript:query_workload(0);" class="btn btn-primary btn-sm date-seach">确认查询</a>
                        &nbsp;
                        <a href="javascript:query_workload(1);" class="btn btn-info btn-sm date-seach">导 出</a>
                    </div>
                    <input type="hidden" name="export_user_workload" value="1"/>
                 </form>
                </div>
                <div class="table-responsive">
                <form id="workload_verify_form" action="admin/ajax/workload/confirm/" method="post">
                    <input type="hidden" id="action" name="action" value="" />
                <?php if ($this->itemsList) { ?>

                    <table class="table table-striped px10 no-padding no-margin workload-list">
                        <thead>
                            <tr>
                                <th class="text-left"><?php _e('日期'); ?></th>
                                <th><?php _e('责编'); ?></th>
                                <th><?php _e('书稿<br/>类别'); ?></th>
                                <th><?php _e('系列'); ?></th>
                                <th><?php _e('书名'); ?></th>
                                <th><?php _e('校次'); ?></th>
                                <th><?php _e('类别'); ?></th>
                                <th><?php _e('遍次'); ?></th>
                                <th><?php _e('目录'); ?></th>
                                <th><?php _e('正文'); ?></th>
                                <!-- <th class="red-right-border"><?php _e('千字/页'); ?></th> -->
                                <th><?php _e('答案'); ?></th>
                                <!-- <th class="red-right-border"><?php _e('千字/页'); ?></th> -->
                                <th><?php _e('试卷'); ?></th>
                                <!-- <th class="red-right-border"><?php _e('千字/页'); ?></th> -->
                                <th><?php _e('试卷<br/>答案'); ?></th>
                                <!-- <th class="red-right-border"><?php _e('千字/页'); ?></th> -->
                                <th><?php _e('课后<br/>作业'); ?></th>
                                <!-- <th class="red-right-border"><?php _e('千字/页'); ?></th> -->
                                <th><?php _e('功能册'); ?></th>
                                <!-- <th class="red-right-border"><?php _e('千字/页'); ?></th> -->
                                <th><?php _e('功能册<br/>答案'); ?></th>
                                <!-- <th class="red-right-border"><?php _e('千字/页'); ?></th> -->
                                <th><?php _e('系数'); ?></th>
                                <th><?php _e('核算总<br/>字数(千)'); ?></th>
                                <th><?php _e('应发<br/>金额'); ?></th>
                                <th><?php _e('备注'); ?></th>
                                <th><?php _e('月份'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($this->workloadList) { ?>

                                <?php foreach ($this->workloadList AS $workloadInfo) { ?>
                            <tr data-db-id="<?php echo $workloadInfo['id']; ?>" data-book-id="<?php echo $itemInfo['id'];?>" class="workload-line<?php echo $workloadInfo['status']==1 ? ' verified-line':($workloadInfo['status']==3 ? ' recording-line' : ' verifying-line'); ?>" data-verify-remark='<?php echo $workloadInfo['verify_remark'];?>'>
                                <td class="text-left">
                                    <input type="hidden" name="id[]" value="<?php echo $workloadInfo['id']; ?>"/>
                                    <a class="md-tip" title="<?php _e('发稿日期');
                                                            echo $workloadInfo['add_time']>0 ? date('Y-m-d', $workloadInfo['add_time']) : $this->bookList[$workloadInfo['book_id']]['delivery_date']; ?> <?php _e('回稿日期');
                                                            echo $workloadInfo['fill_time']>0 ? date('Y-m-d', $workloadInfo['fill_time']) : $this->bookList[$workloadInfo['book_id']]['return_date'];
                                                        ?>" data-toggle="tooltip"><?php
                                                            echo $workloadInfo['add_time']>0 ? date('m-d', $workloadInfo['add_time']) : substr($this->bookList[$workloadInfo['book_id']]['delivery_date'], 5);
                                                            echo  '~';
                                                            echo $workloadInfo['fill_time']>0 ? date('m-d', $workloadInfo['fill_time']) : substr($this->bookList[$workloadInfo['book_id']]['return_date'], 5);
                                                        ?></a>
                                </td>
                                <td class="no-word-break"><?php echo $this->userList[$workloadInfo['user_id']]['user_name']; ?></td>
                                <td class="js-category"><?php echo $this->bookList[$workloadInfo['book_id']]['category']; ?></td>
                                <td class="js-serial"><a target="_blank" href="admin/<?php echo CONTROLLER=='team_workload'?'team_workload/':'';?>check_list/by-book__id-<?php echo $workloadInfo['book_id'];?>"><?php echo $this->bookList[$workloadInfo['book_id']]['serial']; ?></a></td>
                                <td class="js-bookname"><a target="_blank" href="admin/<?php echo CONTROLLER=='team_workload'?'team_workload/':'';?>check_list/by-book__id-<?php echo $workloadInfo['book_id'];?>"><?php echo $this->bookList[$workloadInfo['book_id']]['book_name']; ?></a></td>
                                <td class="js-proofreading-times"><a target="_blank" href="admin/<?php echo CONTROLLER=='team_workload'?'team_workload/':'';?>check_list/by-book__id-<?php echo $workloadInfo['book_id'];?>"><?php echo $this->bookList[$workloadInfo['book_id']]['proofreading_times']; ?></a></td>
                                <td data-td-name="category" class="js-allow-mark"><a><?php echo $workloadInfo['category']; ?></a></td>
                                <td data-td-name="working_times" class="js-allow-mark"><a><?php echo $workloadInfo['working_times']; ?></a></td>
                                <td data-td-name="content_table_pages" class="js-allow-mark"><a><?php echo $workloadInfo['content_table_pages']; ?></a></td>
                                <td data-td-name="text_pages" class="js-allow-mark"><a><?php echo $workloadInfo['text_pages']; ?></a></td>
                                <!-- <td data-td-name="text_table_chars_per_page" class="js-allow-mark red-right-border"><a><?php echo $workloadInfo['text_table_chars_per_page']; ?></a></td> -->
                                <td data-td-name="answer_pages" class="js-allow-mark"><a><?php echo $workloadInfo['answer_pages']; ?></a></td>
                                <!-- <td data-td-name="answer_chars_per_page" class="js-allow-mark red-right-border"><a><?php echo $workloadInfo['answer_chars_per_page']; ?></a></td> -->
                                <td data-td-name="test_pages" class="js-allow-mark"><a><?php echo $workloadInfo['test_pages']; ?></a></td>
                                <!-- <td data-td-name="test_chars_per_page" class="js-allow-mark red-right-border"><a><?php echo $workloadInfo['test_chars_per_page']; ?></a></td> -->
                                <td data-td-name="test_answer_pages" class="js-allow-mark"><a><?php echo $workloadInfo['test_answer_pages']; ?></a></td>
                                <!-- <td data-td-name="test_answer_chars_per_page" class="js-allow-mark red-right-border"><a><?php echo $workloadInfo['test_answer_chars_per_page']; ?></a></td> -->
                                <td data-td-name="exercise_pages" class="js-allow-mark"><a><?php echo $workloadInfo['exercise_pages']; ?></a></td>
                                <!-- <td data-td-name="exercise_chars_per_page" class="js-allow-mark red-right-border"><a><?php echo $workloadInfo['exercise_chars_per_page']; ?></a></td> -->
                                <td data-td-name="function_book" class="js-allow-mark"><a><?php echo $workloadInfo['function_book']; ?></a></td>
                                <!-- <td data-td-name="function_book_chars_per_page" class="js-allow-mark red-right-border"><a><?php echo $workloadInfo['function_book_chars_per_page']; ?></a></td> -->
                                <td data-td-name="function_answer" class="js-allow-mark"><a><?php echo $workloadInfo['function_answer']; ?></a></td>
                                <!-- <td data-td-name="function_answer_chars_per_page" class="js-allow-mark red-right-border"><a><?php echo $workloadInfo['function_answer_chars_per_page']; ?></a></td> -->
                                <td data-td-name="weight" class="js-allow-mark"><a><?php echo $workloadInfo['weight']; ?></a></td>
                                <td data-td-name="total_chars" class=""><a><?php echo $workloadInfo['total_chars']; ?></a></td>
                                <td data-td-name="payable_amount" class=""><a><?php echo $workloadInfo['payable_amount']; ?></a></td>
                                <!-- 存在js-allow-diff-book-mark, 允许跨书稿间计算单元格；js-can-not-compute表示单元格不可以参与计算 -->
                                <td data-td-name="remarks" class="js-allow-mark js-allow-diff-book-mark js-can-not-compute"><a><?php echo $workloadInfo['remarks']; ?></a></td>
                                <td data-td-name="belong_month" class=""><a><?php echo $workloadInfo['belong_month']; ?></a></td>
                            </tr>
                                <?php }
                                if ($this->totalCharsList) {
                                ?>

                            <tr class="active" >
                                <td class="text-left">合计</td>
                                <td class="no-word-break"></td>
                                <td class="js-category"></td>
                                <td class="js-serial"></td>
                                <td class="js-bookname"></td>
                                <td class="js-proofreading-times"></td>
                                <td data-td-name="category" class="js-allow-mark"></td>
                                <td data-td-name="working_times" class="js-allow-mark"></td>
                                <td data-td-name="content_table_pages" class="js-allow-mark"></td>
                                <td data-td-name="text_pages" class="js-allow-mark"><a><?php echo $this->totalCharsList['text_pages']; ?></a></td>
                                <td data-td-name="answer_pages" class="js-allow-mark"><a><?php echo $this->totalCharsList['answer_pages']; ?></a></td>
                                <td data-td-name="test_pages" class="js-allow-mark"><a><?php echo $this->totalCharsList['test_pages']; ?></a></td>
                                <td data-td-name="test_answer_pages" class="js-allow-mark"><a><?php echo $this->totalCharsList['test_answer_pages']; ?></a></td>
                                <td data-td-name="exercise_pages" class="js-allow-mark"><a><?php echo $this->totalCharsList['exercise_pages']; ?></a></td>
                                <td data-td-name="function_book" class="js-allow-mark"><a><?php echo $this->totalCharsList['function_book']; ?></a></td>
                                <td data-td-name="function_answer" class="js-allow-mark"><a><?php echo $this->totalCharsList['function_answer']; ?></a></td>
                                <td data-td-name="weight" class="js-allow-mark"></td>
                                <td data-td-name="total_chars" class=""><a><?php echo $this->totalCharsList['total_chars']; ?></a></td>
                                <td data-td-name="payable_amount" class=""><a><?php echo number_format(round($this->totalCharsList['total_chars']*2,2), 2); ?></a></td>
                                <td data-td-name="remarks" class="js-allow-mark js-allow-diff-book-mark js-can-not-compute"></td>
                                <td data-td-name="belong_month" class=""><a></td>
                            </tr>
                                <?php
                                }
                                ?>
                            <?php } //end if ?>
                        </tbody>
                    </table>
                <?php } else {?>
                    <table class="table table-striped ">
                        <tr><td>
                    没有数据记录</td></tr>
                    </table>
                    </div>
                <?php }?>
                </form>
                </div>

                <div class="mod-table-foot">
                    <div class="text-right">每页显示<?php echo $this->amountPerPage; ?>条 &nbsp; 共<?php echo $this->totalRows;?>条</div>
                    <?php echo $this->pagination; ?>
                </div>
            </div>

        </div>

<script type="text/javascript">
function query_workload (isDownload) {
    var userIds = [];
    var $selectUsers = $('#sinho_editor>option:selected');
    for(var i = 0; i<$selectUsers.length; i++) {
        userIds.push($selectUsers.eq(i).val());
    }
    var startMonth = $('#start_month').val().replace('-','');
    var endMonth = $('#end_month').val().replace('-','');
    var url = '/admin/<?php echo CONTROLLER=='team_workload'?'team_workload/':'';?>check_list/by-user'+'__'+'id'+'-' + userIds.join(',') + '__'+'start_month'+'-' + startMonth +'__'+'end_month'+'-'+endMonth;


    if (isDownload==1) {
        console.info('here');
        $('#search_workload_form').attr('action', url);
        $('#search_workload_form').submit();
    } else {
        window.location.href = url;
    }

    return false;
}
$(function(){

    $("#sinho_editor").multiselect({
        			nonSelectedText : '<?php _e('---- 选择责编 ----');?>',
                    maxHeight       : 200,
                    buttonWidth     : 400,
                    allSelectedText : '<?php _e('已选择所有人');?>',
                    numberDisplayed : 7, // 选择框最多提示选择多少个人名
    });


    // 月份输入框
    $( ".js-monthpicker" ).datetimepicker({
                format  : 'yyyy-mm',
                language:  'zh-CN',
                weekStart: 1, // 星期一 为一周开始
                todayBtn:  1, // 显示今日按钮
                autoclose: 1,
                todayHighlight: 1,
                startView: 3, // 显示的日期级别： 0:到分钟， 1：到小时， 2：到天
                forceParse: 0,
                minView : 3, // 0:选择到分钟， 1：选择到小时， 2：选择到天
            });

    $('.icon-delete.icon-date-delete').click (function () {
        $(this).siblings('.js-date-input').val('');
    });
});
</script>

