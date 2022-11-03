
<!-- Theme switcher -->
<div class="theme-switch" style="width:600px;right:-610px;top:35%;">
    <div class="icon inOut" style="z-index:100"><i class="rotate icon-setting"></i></div>
    <?php View::output('admin/workload/verify_search_inc.php');?>
</div>

        <div class="mod-body tab-content padding5px">
            <div class="tab-pane active" id="index">
                <?php if ($_GET['action'] == 'search') { ?>
                <div class="alert alert-info"><?php _e('找到 %s 条符合条件的内容', intval($this->totalRows)); ?></div>
                <?php } ?>

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

                <div class="table-responsive">
                <form id="workload_verify_form" action="admin/ajax/workload/confirm/" method="post" target="">
                    <input type="hidden" id="action" name="action" value="" />
                <?php if ($this->itemsList) { ?>

                    <table id="workload_by_book" class="table table-striped px10 no-padding no-margin workload-list">
                        <thead>
                            <tr>
                                <th class="text-left js-workload-ref"><?php _e('日期'); ?></th>
                                <th><?php _e('责编'); ?></th>
                                <th class="js-workload-ref"><?php _e('书稿<br/>类别'); ?></th>
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
                                <th class="js-workload-ref"><?php _e('核算总<br/>字数(千)'); ?></th>
                                <th class="js-workload-ref"><?php _e('应发<br/>金额'); ?></th>
                                <th><?php _e('备注'); ?></th>
                                <th class="js-workload-ref"><?php _e('操作'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($this->itemsList AS $itemInfo) { $bookInfo = $itemInfo; ?>
                            <tr data-book-id="<?php echo $itemInfo['id'];?>" class="book-line">
                                <td class="js-workload-ref text-left">

                                    <a class="md-tip"  title="<?php _e('发稿日期'); echo $itemInfo['delivery_date'];?> <?php _e('回稿日期'); echo $itemInfo['return_date'];?>" data-toggle="tooltip"><?php echo substr($itemInfo['delivery_date'], 5),'~',substr($itemInfo['return_date'], 5); ?></a>
                                </td>
                                <td></td>
                                <td class="js-workload-ref js-category"><?php echo $itemInfo['category']; ?></td>
                                <td class="js-serial"><?php echo $itemInfo['serial']; ?></td>
                                <td class="js-bookname"><?php echo $itemInfo['book_name']; ?></td>
                                <td class="js-proofreading-times"><?php echo $itemInfo['proofreading_times']; ?></td>
                                <td><?php echo $itemInfo['category']; ?></td>
                                <td><?php echo $itemInfo['working_times']; ?></td>
                                <td><?php echo $itemInfo['content_table_pages']; ?></td>
                                <td><?php echo $itemInfo['text_pages']; ?></td>
                                <td class="red-right-border"><?php echo $itemInfo['text_table_chars_per_page']; ?></td>
                                <td><?php echo $itemInfo['answer_pages']; ?></td>
                                <td class="red-right-border"><?php echo $itemInfo['answer_chars_per_page']; ?></td>
                                <td><?php echo $itemInfo['test_pages']; ?></td>
                                <td class="red-right-border"><?php echo $itemInfo['test_chars_per_page']; ?></td>
                                <td><?php echo $itemInfo['test_answer_pages']; ?></td>
                                <td class="red-right-border"><?php echo $itemInfo['test_answer_chars_per_page']; ?></td>
                                <td><?php echo $itemInfo['exercise_pages']; ?></td>
                                <td class="red-right-border"><?php echo $itemInfo['exercise_chars_per_page']; ?></td>
                                <td><?php echo $itemInfo['function_book']; ?></td>
                                <td class="red-right-border"><?php echo $itemInfo['function_book_chars_per_page']; ?></td>
                                <td><?php echo $itemInfo['function_answer']; ?></td>
                                <td class="red-right-border"><?php echo $itemInfo['function_answer_chars_per_page']; ?></td>
                                <td><?php echo $itemInfo['weight']; ?></td>
                                <td class="js-workload-ref"><?php echo $itemInfo['total_chars']; ?></td>
                                <td class="js-workload-ref">&nbsp;</td>
                                <td ><?php echo $itemInfo['remarks']; ?><span class="text-primary"><?php echo $itemInfo['admin_remarks']; ?></span></td>

                                <td class="js-workload-ref nowrap">
                                    <a target="_blank" href="admin/<?php echo CONTROLLER=='team_workload'?'team_books':'books';?>/book/from_id-<?php echo $itemInfo['id']; ?>" class="icon icon-cogs md-tip" title="<?php _e('书稿照抄'); ?>" data-toggle="tooltip"></a>
                                    <a target="_blank" href="admin/<?php echo CONTROLLER=='team_workload'?'team_books':'books';?>/book/id-<?php echo $itemInfo['id']; ?>" class="icon icon-edit md-tip" title="<?php _e('编辑'); ?>" data-toggle="tooltip"></a>
                                </td>
                            </tr>
                            <?php if (isset($this->workloadList[$itemInfo['id']])) { ?>

                                <?php foreach ($this->workloadList[$itemInfo['id']] AS $workloadInfo) { ?>
                            <tr data-db-id="<?php echo $workloadInfo['id']; ?>" data-book-id="<?php echo $itemInfo['id'];?>" class="workload-line<?php echo $workloadInfo['status']==1 ? ' verified-line': ($workloadInfo['status']==3 ? ' recording-line' : ' verifying-line'); ?>" data-verify-remark='<?php echo $workloadInfo['verify_remark'];?>'>
                                <td class="js-workload-ref text-left">
                                    <input type="hidden" name="id[]" value="<?php echo $workloadInfo['id']; ?>"/>
                                    <?php
                                    if ($workloadInfo['add_time']) {
                                        echo date('m-d', $workloadInfo['add_time']);
                                    } else {
                                        echo substr($itemInfo['delivery_date'], 5);
                                    }
                                    echo '~';
                                    if ($workloadInfo['fill_time']) {
                                        echo date('m-d', $workloadInfo['fill_time']);
                                    } else {
                                        echo substr($itemInfo['return_date'], 5);
                                    }
                                    ?>
                                </td>
                                <td class="no-word-break"><a target="_blank" href="admin/<?php echo CONTROLLER=='team_workload'?'team_workload/':'';?>check_list/by-user__id-<?php echo $workloadInfo['user_id'];?>"><?php echo $this->userList[$workloadInfo['user_id']]['user_name']; ?></a></td>
                                <td class="js-workload-ref js-category"><?php echo $itemInfo['category']; ?></td>
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
                                <td data-td-name="total_chars"  class="js-workload-ref"><a><?php echo $workloadInfo['total_chars']; ?></a></td>
                                <td data-td-name="payable_amount"  class="js-workload-ref"><a><?php echo $workloadInfo['payable_amount']; ?></a></td>
                                <!-- 存在js-allow-diff-book-mark, 允许跨书稿间计算单元格；js-can-not-compute表示单元格不可以参与计算 -->
                                <td data-td-name="remarks" class="js-allow-mark js-allow-diff-book-mark js-can-not-compute"><a><?php echo $workloadInfo['remarks']; ?></a></td>
                                <td class="js-workload-ref">
                                    <a target="_blank"  onclick="show_quarlity(<?php echo $workloadInfo['id']; ?>); return false;" class="js-fill-quarlity icon icon-verify md-tip" href="admin/ajax/workload/fill_quarylity/workload_id-<?php echo $workloadInfo['id']; ?>" class="icon icon-order md-tip" title="<?php _e('质量考核'); ?>" data-toggle="tooltip"></a>
                                </td>
                            </tr>
                            <?php if (isset($this->quarlityList[$workloadInfo['id']])) { ?>
                            <tr>
                                <td class="js-workload-ref text-left">
                                    <?php
                                    echo date('m-d', strtotime($this->quarlityList[$workloadInfo['id']]['add_date']));
                                    ?>
                                </td>
                                <td class="no-word-break"><?php echo $this->userList[$workloadInfo['user_id']]['user_name']; ?></td>
                                <td class="js-category"><?php echo $itemInfo['category']; ?></td>
                                <td class="js-serial"><?php echo $itemInfo['serial']; ?></td>
                                <td class="js-bookname"><?php echo $itemInfo['book_name']; ?></td>
                                <td class="js-proofreading-times"><?php echo $itemInfo['proofreading_times']; ?></td>
                                <td data-td-name="category" class="js-allow-mark"><a><?php echo $workloadInfo['category']; ?></a></td>
                                <td data-td-name="working_times" class="js-allow-mark"><a><?php echo $workloadInfo['working_times']; ?></a></td>

                                <td></td>
                                <td></td>
                                <td></td>
                                <td colspan="4" class="text-right">考核结果：
                                     <?php echo $this->quarlityList[$workloadInfo['id']]['good_or_bad'] == 1 ? '<span class="icon-good"></span>' : '<span class="icon-bad"></span>';?>
                                </td>
                                <td></td>
                                <td></td>
                                <td colspan="4" class="text-right">比例：<?php echo $this->quarlityList[$workloadInfo['id']]['rate_num'];?></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><a><?php echo  round($this->quarlityList[$workloadInfo['id']]['good_or_bad'] * $workloadInfo['payable_amount'] * $this->quarlityList[$workloadInfo['id']]['rate_num'] / 100, 2) ; ?></a></td>
                                <td colspan="2"><?php echo $this->quarlityList[$workloadInfo['id']]['remarks'];?></td>

                            </tr>
                            <?php  } // end quarlity if ?>
                                <?php } ?>

                            <?php } //end workload if ?>

                            <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>
                </form>
                </div>

                <div class="mod-table-foot">
                <?php if (isset($_GET['id'])) {
                        if ($bookInfo) {
                ?>
                    <div class="text-right"><span class="bg-warning">当前书稿：<?php echo $bookInfo['serial'], ' ', $bookInfo['book_name'], ' ', $bookInfo['proofreading_times']; ?> </span></div>
                <?php
                        }
                } else { ?>
                    <div class="text-right">每页<?php echo $this->amountPerPage; ?> &nbsp; 共<?php echo $this->totalRows;?>本</div>
                    <?php echo $this->pagination; ?>
                <?php } ?>
                </div>
            </div>

        </div>

        <div id="fill-quarlity-template" class="hidden">
            <form action="admin/ajax/workload/fill_quarlity/" method="post" onsubmit="return false;">
                <input type="hidden" name="workload_id" value=""/>
                <div class="row">
                    <div class="col-sm-1 text-right">
                        <label class="radio-inline padding-20">
                            <input type="radio" name="good_or_bad" value="1">奖
                        </label>
                        &nbsp;
                        <label class="radio-inline">
                            <input type="radio" name="good_or_bad" value="-1">惩
                        </label>
                    </div>
                    <div class="col-sm-1 text-right">
                        <label class="icb-label"><?php _e('考核比例'); ?>:</label>
                    </div>
                    <div class="col-sm-1 input-group" style="float:left;">
                        <input type="text" class="form-control text-right" name="rate" placeholder="">
                        <div class="input-group-addon">&nbsp;%&nbsp;</div>
                    </div>
                    <div class="col-sm-1 text-right">
                        <label class="icb-label"><?php _e('备注'); ?>:</label>
                    </div>
                    <div class="col-sm-6 icb-item-title">
                        <input type="text" name="remarks" value="" class="form-control" />
                    </div>
                    <div class="col-sm-2 ">
                       <div class="row">
                           <div class="col-sm-1"></div>
                           <div class="col-sm-3">
                                <a class="btn btn-large btn-success" id="publish_submit" onclick="fill_quarlity($(this).closest('form'));"><?php _e('保 存'); ?></a>
                           </div>
                           <div class="col-sm-1"></div>
                           <div class="col-sm-3">
                                <a class="btn btn-large btn-warning " onclick="$(this).closest('tr').remove();return false;"><?php _e('取 消'); ?></a>
                           </div>
                           <div class="col-sm-1"></div>
                           <div class="col-sm-3">
                                <a class="btn btn-large btn-danger " onclick="remove_quarlity($(this).closest('form'));return false;"><?php _e('删 除'); ?></a>
                           </div>
                       </div>
                    </div>
                </div>
            </form>
        </div>

<script type="text/javascript">
function show_quarlity (workloadId)
{
    ICB.modal.loading(true);
    var url = G_BASE_URL + '/admin/ajax/<?php echo CONTROLLER=='team_workload' ? 'team_workload':'workload'; ?>/get_quarlity/';
    var params = {'workload_id' : workloadId};
    var $refTr = $('tr[data-db-id="'+workloadId+'"]');

    ICB.ajax.requestJson(
        url,
        params,
        function (response) {
            ICB.modal.loading(false);

            if (!response) {
                return false;
            }

            if (response.err) {
                ICB.modal.alert(response.err);
            } else if (response.errno === 0) {
                var rate = (typeof response.rsm.rate_num) === 'undefined' ? '' : response.rsm.rate_num;
                var goodOrBad = (typeof response.rsm.good_or_bad) === 'undefined' ? '1' : response.rsm.good_or_bad;
                var remarks = (typeof response.rsm.remarks) === 'undefined' ? '' : response.rsm.remarks;

                $('#quarylity-edit-form-container').remove();
                var html = $('#fill-quarlity-template').html();
                $refTr.after('<tr id="quarylity-edit-form-container"><td colspan="'+$refTr.find('td').length+'">'+html + '</td></tr>');

                $('#quarylity-edit-form-container').find('input[name="workload_id"]').val(workloadId);
                $('#quarylity-edit-form-container').find('input[name="good_or_bad"][value="'+goodOrBad+'"]').attr('checked', 'checked');
                $('#quarylity-edit-form-container').find('input[name="rate"]').val(rate);
                $('#quarylity-edit-form-container').find('input[name="remarks"]').val(remarks);
                // 选择框美化
                $('.icb-content-wrap').find("input").iCheck({
                    checkboxClass : 'icheckbox_square-blue',
                    radioClass : 'iradio_square-blue',
                    increaseArea : '20%'
                });
            } else {
                ICB.modal.alert(_t('请求发生错误'));
            }
        }
    );

    return false;

}

function fill_quarlity ($form)
{
    ICB.modal.loading(true);
    var url = G_BASE_URL + '/admin/ajax/<?php echo CONTROLLER=='team_workload' ? 'team_workload':'workload'; ?>/fill_quarlity/';
    var params = $form.serialize();

    ICB.ajax.requestJson(
        url,
        params,
        function (response) {
            ICB.modal.loading(false);

            if (!response) {
                ICB.modal.alert(_t('请求发生错误'));
                return false;
            }

            if (response.err) {
                ICB.modal.alert(response.err);
            } else if (response.errno === 0) {
                window.location.reload();
            } else {
                ICB.modal.alert(_t('请求发生错误'));
            }
        }
    );
}
/**
 * 点击删除按钮
 */
function remove_quarlity ($form)
{
    ICB.domEvents.deleteShowConfirmModal(
        _t('确认删除质量考核？'),
        function(){

            var url = G_BASE_URL + '/admin/ajax/<?php echo CONTROLLER=='team_workload' ? 'team_workload':'workload'; ?>/remove_quarlity/';
            var params = $form.serialize();

            ICB.ajax.requestJson(
                url,
                params,
                function (response) {
                    if (!response) {
                        ICB.modal.alert(_t('请求发生错误'));
                        return false;
                    }

                    if (response.err) {
                        ICB.modal.alert(response.err);
                    } else if (response.errno === 0) {
                        window.location.reload();
                    } else {
                        ICB.modal.alert(_t('请求发生错误'));
                    }
                }
            );
        }
    );

    return false;
}


$(function(){

    $('.theme-switch').width(600);
    $('.theme-switch .icon').click (function(event){
            event.preventDefault();
            if( $ (this).hasClass('inOut')  ){
                $('.theme-switch').stop().animate({right:'0px'},1000 );
            } else{
                $('.theme-switch').stop().animate({right:'-610px'},1000 );
            }
            $(this).toggleClass('inOut');
            return false;

        }  );

    $('body').on('click', '.js-submit-quarlity', fill_quarlity);

});
</script>

