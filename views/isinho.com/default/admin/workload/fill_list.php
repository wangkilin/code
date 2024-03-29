<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
            <h3><?php View::output('admin/workload/nav_inc'); ?></h3>
        </div>

        <div class="mod-body tab-content">
            <div class="tab-pane active" id="index">
                <?php if ($_GET['action'] == 'search') { ?>
                <div class="alert alert-info"><?php _e('找到 %s 条符合条件的内容', intval($this->totalRows)); ?></div>
                <?php } ?>


                <div class="table-responsive">
                    <table class="table table-bordered workload-list">
                        <tr>
                         <td>列表颜色说明：</td>
                         <td class="warning">工作量正在核算，绩效正在核算：<?php
                        echo isset($this->totalChars[$this->thisUserId]) ? $this->totalChars[$this->thisUserId] : 0;
                        echo _e('千字');
                        echo '， ￥';
                        echo isset($this->totalChars[$this->thisUserId]) ? round($this->totalChars[$this->thisUserId]*2,2) : 0;
                        ?></td>
                        <td class="danger">工作量审核中，审核通过允许核算</td>
                         <td class="success">工作量记录中，没有提交到核算</td>
                         <td class="info">工作量结算完成，绩效已支付</td>
                         <!-- <td class="sinho-red-background">有疑问数据，需确认重新提交</td> -->
                        </tr>
                    </table>
                </div>

                <!-- start::搜索工作量 -->

                <!-- Theme switcher -->
                <div class="theme-switch" style="width:410px;right:-410px;top:35%;">
                    <div class="icon inOut" style="z-index:100"><i class="rotate icon-setting"></i></div>
                    <div class="tab-pane" id="search">

                        <form method="get" action="/admin/fill_list/" id="search_form" method="GET" class="form-horizontal" role="form" target="">
                            <input name="action" type="hidden" value="search" />

                            <div class="form-group">
                                <label class="col-sm-3 col-xs-3 control-label"><?php _e('系列'); ?>:</label>

                                <div class="col-sm-8 col-xs-7">
                                    <input class="form-control" type="text" value="<?php echo rawurldecode($_GET['serial']); ?>" name="serial" placeholder="系列关键字"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-xs-3 control-label"><?php _e('书名'); ?>:</label>

                                <div class="col-sm-8 col-xs-7">
                                    <input class="form-control" type="text" value="<?php echo rawurldecode($_GET['book_name']); ?>" name="book_name"  placeholder="书名关键字"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-xs-3 control-label"><?php _e('校次'); ?>:</label>

                                <div class="col-sm-8 col-xs-7">
                                    <input class="form-control" type="text" value="<?php echo rawurldecode($_GET['proofreading_times']); ?>" name="proofreading_times"  placeholder="校次关键字"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 col-xs-3 control-label"><?php _e('月份'); ?>:</label>

                                <div class="col-sm-8 col-xs-8">
                                    <div class="row">
                                        <div class="col-xs-12  col-sm-12 mod-double icon-date-container">
                                            <input type="text" class="form-control js-date-input js-monthpicker" style="text-indent:14px;" value="<?php echo $_GET['start_date']; ?>" name="start_date" autocomplete="off" placeholder="开始日期"/>
                                            <i class="icon- icon-date"></i>
                                            <i class="icon- icon-date-delete icon-delete"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-8 col-xs-8">
                                    <input type="submit" class="btn btn-primary" value="<?php _e('搜 索'); ?>"/>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- end::搜索工作量 -->

                <br/>

                <div class="table-responsive">
                <?php if ($this->itemsList) { ?>

                    <table class="table table-striped px10 no-padding no-margin workload-list workload-fill-list">
                        <thead>
                            <tr>
                                <th class=""><?php _e('日期'); ?></th>
                                <th><?php _e('责编'); ?></th>
                                <!-- <th><?php _e('书稿<br/>类别'); ?></th> -->
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
                                <th><?php _e('月份'); ?></th>
                                <th style="width:85px;"><?php _e('操作'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($this->itemsList AS $itemInfo) { ?>
                            <tr data-db-id="<?php echo $itemInfo['id']; ?>" data-book-id="<?php echo $itemInfo['book_id'];?>" class="workload-line<?php
                                if ($this->booksList[$itemInfo['book_id']]['verify_status'] > 0) echo ' danger';
                                else if ($itemInfo['status']==sinhoWorkloadModel::STATUS_VERIFIED) echo ' verified-line';
                                else if ($itemInfo['status']==sinhoWorkloadModel::STATUS_VERIFYING) echo ' verifying-line';
                                else if ($itemInfo['status']==sinhoWorkloadModel::STATUS_RECORDING) echo ' recording-line'; ?>" data-verify-remark='<?php echo $itemInfo['verify_remark'];?>'>
                                <td class="text-left">

                                    <a class="md-tip"  title="<?php _e('发稿日期'); echo date('m-d', $itemInfo['add_time']);?> <?php _e('回稿日期'); echo $itemInfo['fill_time']>0 ? date('m-d', $itemInfo['fill_time']):'';?>" data-toggle="tooltip"><?php echo date('m-d', $itemInfo['add_time']),'~';echo $itemInfo['fill_time']>0 ? date('m-d', $itemInfo['fill_time']):''; ?></a>
                                </td>
                                <td class="no-word-break"><?php echo $this->user_info['user_name']; ?></td>
                                <!-- <td class="js-category"><?php //echo $this->booksList[$itemInfo['book_id']]['category']; ?></td> -->
                                <td class="js-serial"><?php echo $this->booksList[$itemInfo['book_id']]['serial']; ?></td>
                                <td class="js-bookname"><?php echo $this->booksList[$itemInfo['book_id']]['book_name']; ?></td>
                                <td class="js-proofreading-times"><?php echo $this->booksList[$itemInfo['book_id']]['proofreading_times']; ?></td>


                                <td data-td-name="category" class="js-allow-mark"><a><?php echo $itemInfo['category']; ?></a></td>
                                <td data-td-name="working_times" class="js-allow-mark"><a><?php echo $itemInfo['working_times']; ?></a></td>
                                <td data-td-name="content_table_pages" class="js-allow-mark"><a><?php echo $itemInfo['content_table_pages']; ?></a></td>
                                <td data-td-name="text_pages" class="js-allow-mark"><a><?php echo $itemInfo['text_pages']; ?></a></td>
                                <td data-td-name="text_table_chars_per_page" class="js-allow-mark red-right-border"><a><?php echo $itemInfo['text_table_chars_per_page']; ?></a></td>
                                <td data-td-name="answer_pages" class="js-allow-mark"><a><?php echo $itemInfo['answer_pages']; ?></a></td>
                                <td data-td-name="answer_chars_per_page" class="js-allow-mark red-right-border"><a><?php echo $itemInfo['answer_chars_per_page']; ?></a></td>
                                <td data-td-name="test_pages" class="js-allow-mark"><a><?php echo $itemInfo['test_pages']; ?></a></td>
                                <td data-td-name="test_chars_per_page" class="js-allow-mark red-right-border"><a><?php echo $itemInfo['test_chars_per_page']; ?></a></td>
                                <td data-td-name="test_answer_pages" class="js-allow-mark"><a><?php echo $itemInfo['test_answer_pages']; ?></a></td>
                                <td data-td-name="test_answer_chars_per_page" class="js-allow-mark red-right-border"><a><?php echo $itemInfo['test_answer_chars_per_page']; ?></a></td>
                                <td data-td-name="exercise_pages" class="js-allow-mark"><a><?php echo $itemInfo['exercise_pages']; ?></a></td>
                                <td data-td-name="exercise_chars_per_page" class="js-allow-mark red-right-border"><a><?php echo $itemInfo['exercise_chars_per_page']; ?></a></td>
                                <td data-td-name="function_book" class="js-allow-mark"><a><?php echo $itemInfo['function_book']; ?></a></td>
                                <td data-td-name="function_book_chars_per_page" class="js-allow-mark red-right-border"><a><?php echo $itemInfo['function_book_chars_per_page']; ?></a></td>
                                <td data-td-name="function_answer" class="js-allow-mark"><a><?php echo $itemInfo['function_answer']; ?></a></td>
                                <td data-td-name="function_answer_chars_per_page" class="js-allow-mark red-right-border"><a><?php echo $itemInfo['function_answer_chars_per_page']; ?></a></td>
                                <td data-td-name="weight" class="js-allow-mark"><a><?php echo $itemInfo['weight']; ?></a></td>
                                <td data-td-name="total_chars" class=""><a><?php echo $itemInfo['total_chars']; ?></a></td>
                                <td data-td-name="payable_amount" class=""><a><?php echo round($itemInfo['total_chars'] * 2, 2); ?><?php //echo $itemInfo['payable_amount']; ?></a></td>
                                <!-- 存在js-allow-diff-book-mark, 允许跨书稿间计算单元格；js-can-not-compute表示单元格不可以参与计算 -->
                                <td data-td-name="remarks" class="js-allow-mark js-allow-diff-book-mark js-can-not-compute "><a><?php echo $itemInfo['remarks']; ?></a></td>
                                <td data-td-name="belong_month" class="js-can-not-compute "><a><?php echo $itemInfo['belong_month']; ?></a></td>

                                <td>
                                  <?php if ($itemInfo['status']==sinhoWorkloadModel::STATUS_RECORDING || $itemInfo['status']==sinhoWorkloadModel::STATUS_VERIFYING) {// 没有核算过，允许修改 ?>
                                  <a href="admin/fill_workload/id-<?php echo $itemInfo['id']; ?>" onclick="editWorkload(<?php echo $itemInfo['id']; ?>); return false;" class="icon icon-edit md-tip" title="<?php _e('填写工作量'); ?>" data-toggle="tooltip"></a>
                                  <?php } ?>
                                  <?php if (! $itemInfo['is_branch']) {// 可以从分配过来的任务量，做分支处理 ?>
                                  <a href="admin/ajax/workload/fill_more/" onclick="fillMore(<?php echo $itemInfo['id']; ?>);" class="icon icon-add-to-list md-tip js-fill-more" title="<?php _e('拆分任务，对工作量进行分叉处理'); ?>" data-toggle="tooltip"></a>
                                  <?php }?>
                                  <?php if ($itemInfo['status']==sinhoWorkloadModel::STATUS_RECORDING &&$this->booksList[$itemInfo['book_id']]['verify_status'] == 0) {// 加入核算队列 ?>
                                  <a href="admin/ajax/workload/queue/" onclick="addQueue(<?php echo $itemInfo['id']; ?>); return false;" class="icon icon-coin-yen md-tip" title="<?php _e('加入核算'); ?>" data-toggle="tooltip"></a>
                                  <?php } ?>
                                  <?php if (($itemInfo['status']==sinhoWorkloadModel::STATUS_RECORDING) && $itemInfo['is_branch']) {// 工作量没有核算过，而且是分支处理，允许删除 ?>
                                  <a href="admin/ajax/workload/remove/" onclick="deleteItem(<?php echo $itemInfo['id']; ?>); return false;" class="icon icon-delete md-tip" title="<?php _e('删除'); ?>" data-toggle="tooltip"></a>
                                  <?php }
                                  ?>
                                  <?php if ($itemInfo['status'] == sinhoWorkloadModel::STATUS_VERIFYING) { ?><a target="_blank" onclick="rollback(<?php echo $itemInfo['id']; ?>)" class="icon icon-undo2 md-tip" title="<?php _e('撤回核算'); ?>" data-toggle="tooltip"></a><?php }?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>
                </div>

                <div class="mod-table-foot">
                    <?php echo $this->pagination; ?>
                </div>
            </div>
            <?php View::output('admin/books/search_inc.php');?>

        </div>
    </div>
</div>
<div id="template-assign-options" style="display:none;">
<?php echo $this->itemOptions;?>
</div>
<?php View::output('admin/workload/fill_workload_js'); ?>
<script type="text/javascript">
/**
 * 编辑工作量
 */
function editWorkload (id)
{
    ICB.modal.loading(true);
    var url = G_BASE_URL + '/admin/fill_workload/';
    $.get(url, {'id':id}, function (response) {
        ICB.modal.loading(false);

        var refBookWorkload = $(response).find('#workload_by_book tr').length > 2 ? $(response).find('#workload_by_book').parent().html() : '';
        $('#workload-edit-form-container').remove();
        var $refTr = $('tr[data-db-id="'+id+'"]');
        var workloadLineNumber = $(response).find('.workload-line').length; // 获取书稿工作量条数
        $refTr.after('<tr id="workload-edit-form-container"><td colspan="'+$refTr.find('td').length+'">'
        +refBookWorkload
        +$(response).find('#workload-edit-form').parent().html()
        +'</td></tr>');
        $('#workload_by_book .js-workload-ref').remove();
        $('#workload-edit-form-container input').tooltip();
        $('#workload-edit-form-container .btn').tooltip();
        // 如果书稿工作量只有一条，属于独立完成情形， 允许快速填写书稿工作量。
        workloadLineNumber > 1 ? $('#quick_fill_button').hide() : $('#quick_fill_button').show();
        compute_chars_ammount();

        /**
         * 监听输入框输入按键为回车时， 如果输入框为空值， 使用默认值填充
         */
        $('#workload-edit-form input').blur(function () {
            compute_chars_ammount();
        });
        $('#workload-edit-form input').keydown(function (event) {
            compute_chars_ammount();
            if (13 != event.keyCode || $(this).val()!=='') {
                return;
            }

            if (13 == event.keyCode || $(this).val()==='') {
                $(this).val($(this).data('default'));
                if ($(this).parent().nextAll().find('input[type="text"]').length) {
                    $(this).parent().nextAll().find('input[type="text"]').eq(0).focus();
                } else if ($(this).closest('.row').nextAll().find('input[type="text"]').length) {
                    $(this).closest('.row').nextAll().find('input[type="text"]').eq(0).focus();
                }
            }
        });

        $('#workload-edit-form input').change(function () {
            if ($(this).data('default')===undefined) {
                return;
            }
            compute_chars_ammount();
            if (parseFloat($(this).val()) > parseFloat(+$(this).data('default')) ) {
                $(this).parent().addClass('has-error');
            } else {
                $(this).parent().removeClass('has-error');
            }
        });
        //ICB.ajax._onSuccess(response);
    }).error(function (error) {
        ICB.modal.loading(false);
        ICB.ajax._onError(error);
    });

    return false;
}
function deleteItem(id)
{
    ICB.modal.confirm (
  	   _t('确认删除吗？'),
  	   function(){
      	   var url = G_BASE_URL + '/admin/ajax/workload/remove/',
      	       params = {'id':id, '_post_type':'ajax'};
  		   ICB.ajax.requestJson(
  	      	   url,
  	      	   params,
  	      	   function (response) {
      	      		if (!response) {
      	      		    return false;
	      	      	}

	      	      	if (response.err) {
	      	      		ICB.modal.alert(response.err);
	      	      	} else if (response.errno == 1) {
	      	      	    ICB.modal.alert(_t('已删除工作量条目'), {'hidden.bs.modal': function () {
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
 * 将工作量加入到绩效审核中
 * @param int id 工作量记录id
 */
function addQueue (id)
{
    // 弹框询问是否确认加入工作量审核
    ICB.modal.confirm(
  	   _t('确认将本条加入绩效核算中么？'),
  	   function(){
      	   var url = G_BASE_URL + '/admin/ajax/workload/queue/', // 提交url
      	       params = {'id':id, '_post_type':'ajax'};
  		   ICB.ajax.requestJson( // 发送请求
  	      	   url,
  	      	   params,
  	      	   function (response) {
      	      		if (!response) {
      	      		    return false;
	      	      	}

	      	      	if (response.err) {// 响应错误数据， 错误提示
	      	      		ICB.modal.alert(response.err);
	      	      	} else if (response.errno == 1) { // 成功加入到绩效核算， 页面刷新
	      	      	    ICB.modal.alert(_t('工作量已加入到绩效核算中'), {'hidden.bs.modal': function () {
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
/**
 * 将书稿工作量拆分进行填充
 * @param int id 工作量记录id。 将基于此工作量对应的书稿，增加一条工作量记录
 */
function fillMore(id)
{
    ICB.modal.confirm(
  	   _t('确认拆分工作量？'),
  	   function(){
      	   var url = G_BASE_URL + '/admin/ajax/workload/fill_more/',
      	       params = {'id':id, '_post_type':'ajax'};
  		   ICB.ajax.requestJson(
  	      	   url,
  	      	   params,
  	      	   function (response) {
      	      		if (!response) {
      	      		    return false;
	      	      	}

	      	      	if (response.err) {
	      	      		ICB.modal.alert(response.err);
	      	      	} else if (response.errno == 1) {
	      	      	    ICB.modal.alert(_t('工作量条目已拆分'), {'hidden.bs.modal': function () {
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
    /**
     * 撤回书稿工作量核算
     */
    function rollback(id) {
        ICB.modal.confirm(
            _t('确认撤回核算？'),
            function() {
                var url = G_BASE_URL + '/admin/ajax/workload/rollback/',
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
                            ICB.modal.alert(_t('核算已撤回'), {
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

$(function(){
    $('.js-fill-more').click(function () {

        return false;
    });



    /**
     * 将错误信息标识回显
     */
    $('.verifying-line').each(function () {
        var verifyRemark = $(this).data('verify-remark');
        if(! verifyRemark.length) {
            return;
        }
        //console.info(verifyRemark);
        //verifyRemark = $.parseJSON(verifyRemark);
        for(var i=0; i<verifyRemark.length; i++) {
            console.info('.js-allow-mark[data-td-name="'+verifyRemark[i]+'"]');
            $(this).find('.js-allow-mark[data-td-name="'+verifyRemark[i]+'"]').addClass('sinho-red-background');
        }
        //console.info(verifyRemark);
    });
});


$(function(){

    $('.theme-switch').width(400);
    $('.theme-switch .icon').click (function(event){
            event.preventDefault();
            if( $ (this).hasClass('inOut')  ){
                $('.theme-switch').stop().animate({right:'0px'},1000 );
            } else{
                $('.theme-switch').stop().animate({right:'-410px'},1000 );
            }
            $(this).toggleClass('inOut');
            return false;

        }  );

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
</script>

<?php View::output('admin/global/footer.php'); ?>
