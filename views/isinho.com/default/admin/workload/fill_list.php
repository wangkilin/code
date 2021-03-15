<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
            <h3>
                <ul class="nav nav-tabs">
                    <li class="active">
                    <a href="<?php
                        echo ACTION=='index'?'#index" data-toggle="tab':'admin/books/index/'
                        ?>"><?php _e('我的工作量'); ?></a>
                    </li>
                    <li class="bg-warning sinho-stat-total-chars">
                        <a><?php
                        echo _e('核算中：');
                        echo isset($this->totalChars[$this->thisUserId]) ? $this->totalChars[$this->thisUserId] : 0;
                        echo _e('千字');
                        echo ' &nbsp; &nbsp; ';
                        echo _e(' 金额：');
                        echo isset($this->totalChars[$this->thisUserId]) ? round($this->totalChars[$this->thisUserId]*2,2) : 0;
                        ?></a>
                    </li>
                </ul>
            </h3>
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
                         <td class="success">工作量记录中，没有提交到核算</td>
                         <td class="info">工作量结算完成，绩效已支付</td>
                         <td class="warning">工作量正在核算，绩效正在核算</td>
                         <!-- <td class="sinho-red-background">有疑问数据，需确认重新提交</td> -->
                        </tr>
                    </table>
                </div>
                <br/>

                <div class="table-responsive">
                <?php if ($this->itemsList) { ?>

                    <table class="table table-striped px10 no-padding no-margin workload-list workload-fill-list">
                        <thead>
                            <tr>
                                <th class="text-left"><?php _e('日期'); ?></th>
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
                                if ($itemInfo['status']==sinhoWorkloadModel::STATUS_VERIFIED) echo ' verified-line';
                                if ($itemInfo['status']==sinhoWorkloadModel::STATUS_VERIFYING) echo ' verifying-line';
                                if ($itemInfo['status']==sinhoWorkloadModel::STATUS_RECORDING) echo ' recording-line'; ?>" data-verify-remark='<?php echo $itemInfo['verify_remark'];?>'>
                                <td class="text-left">

                                    <a class="md-tip"  title="<?php _e('发稿日期'); echo $itemInfo['delivery_date'];?> <?php _e('回稿日期'); echo $itemInfo['return_date'];?>" data-toggle="tooltip"><?php echo substr($itemInfo['delivery_date'], 5),'~',substr($itemInfo['return_date'], 5); ?></a>
                                </td>
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
                                  <a href="admin/ajax/workload/fill_more/" onclick="fillMore(<?php echo $itemInfo['id']; ?>);" class="icon icon-add-to-list md-tip js-fill-more" title="<?php _e('继续补充工作量'); ?>" data-toggle="tooltip"></a>
                                  <?php }?>
                                  <?php if ($itemInfo['status']==sinhoWorkloadModel::STATUS_RECORDING ) {// 加入核算队列 ?>
                                  <a href="admin/ajax/workload/queue/" onclick="addQueue(<?php echo $itemInfo['id']; ?>); return false;" class="icon icon-coin-yen md-tip" title="<?php _e('加入核算'); ?>" data-toggle="tooltip"></a>
                                  <?php } ?>
                                  <?php if (($itemInfo['status']==sinhoWorkloadModel::STATUS_RECORDING) && $itemInfo['is_branch']) {// 工作量没有核算过，而且是分支处理，允许删除 ?>
                                  <a href="admin/ajax/workload/remove/" onclick="deleteItem(<?php echo $itemInfo['id']; ?>); return false;" class="icon icon-delete md-tip" title="<?php _e('删除'); ?>" data-toggle="tooltip"></a>
                                  <?php } ?>
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

<script type="text/javascript">
function compute_chars_ammount ()
{
    // =((H5+I5)*J5+K5*L5+M5*N5+O5*P5+Q5*R5+S5*T5+U5*V5)*W5
    var totalChars =
        ( (float($('#workload-edit-form-container input[name="content_table_pages"]').val())
                    + float($('#workload-edit-form-container input[name="text_pages"]').val() ) )
                * float($('#workload-edit-form-container input[name="text_table_chars_per_page"]').val() )
            + (float($('#workload-edit-form-container input[name="answer_pages"]').val() )
                * float($('#workload-edit-form-container input[name="answer_chars_per_page"]').val() ) )
            + (float($('#workload-edit-form-container input[name="test_pages"]').val())
                * float($('#workload-edit-form-container input[name="test_chars_per_page"]').val())  )
            + (float($('#workload-edit-form-container input[name="test_answer_pages"]').val())
                * float($('#workload-edit-form-container input[name="test_answer_chars_per_page"]').val())  )
            + (float($('#workload-edit-form-container input[name="exercise_pages"]').val())
                * float($('#workload-edit-form-container input[name="exercise_chars_per_page"]').val())  )
            + (float($('#workload-edit-form-container input[name="function_book"]').val())
                * float($('#workload-edit-form-container input[name="function_book_chars_per_page"]').val())  )
            + (float($('#workload-edit-form-container input[name="function_answer"]').val())
                * float($('#workload-edit-form-container input[name="function_answer_chars_per_page"]').val())  )
        )
        * float($('#workload-edit-form-container input[name="weight"]').val() ) ;
    totalChars = float(totalChars, 4);
    var amount = float(totalChars * 2, 2);

    console && console.info( float($('#workload-edit-form-container input[name="content_table_pages"]').val())
                    , float($('#workload-edit-form-container input[name="text_pages"]').val() )
                , float($('#workload-edit-form-container input[name="text_table_chars_per_page"]').val() )
            , (float($('#workload-edit-form-container input[name="answer_pages"]').val() )
                * float($('#workload-edit-form-container input[name="answer_chars_per_page"]').val() ) )
            , (float($('#workload-edit-form-container input[name="test_pages"]').val())
                * float($('#workload-edit-form-container input[name="test_chars_per_page"]').val())  )
            , (float($('#workload-edit-form-container input[name="test_answer_pages"]').val())
                * float($('#workload-edit-form-container input[name="test_answer_chars_per_page"]').val())  )
            , (float($('#workload-edit-form-container input[name="exercise_pages"]').val())
                * float($('#workload-edit-form-container input[name="exercise_chars_per_page"]').val())  )
            , (float($('#workload-edit-form-container input[name="function_book"]').val())
                * float($('#workload-edit-form-container input[name="function_book_chars_per_page"]').val())  )
            , (float($('#workload-edit-form-container input[name="function_answer"]').val())
                * float($('#workload-edit-form-container input[name="function_answer_chars_per_page"]').val())  )

        , float($('#workload-edit-form-container input[name="weight"]').val() )
    );
   $('#workload-edit-form-container input[name="total_chars"]').val(totalChars);
   $('#workload-edit-form-container input[name="payable_amount"]').val(amount);
   //return {'chars':totalChars, 'amount':amount};
}
/**
 * 取消提交工作量， 将工作量填充表单移除
 */
function concel_form ()
{
    $('#workload-edit-form-container').remove();
    return false;
}

function submit_workload_edit_form ()
{
    // 检查输入参数， 是否比基准参数大。 如果比进准参数大，判断出有错误
    $inputs = $('#workload-edit-form input');
    $hasWarning = false;
    for (var i=0; i<$inputs.length; i++) {
        if ($inputs.eq(i).data('default')===undefined) { // 没有基准参数， 不需要比较
            continue;
        }
        // 比基准参数大， 标识错误。
        if (float($inputs.eq(i).val()) > float($inputs.eq(i).data('default'))) {
            $hasWarning = true;
            $inputs.eq(i).parent().addClass('has-error');
        }
    }
    // 标识了参数错误， 提醒是否确认提交
    if ($hasWarning) {
        var onYesCallback = function () {
            compute_chars_ammount();
            ICB.ajax.postForm($('#workload-edit-form'));
            $('#workload-edit-form-container').remove();
        };

        ICB.modal.confirm('存在不一致的参数。 是否继续提交？', onYesCallback);
    } else { // 没有错误， 直接提交
        compute_chars_ammount();
        ICB.ajax.postForm($('#workload-edit-form'));
        $('#workload-edit-form-container').remove();
    }


    return false;
}
/**
 * 编辑工作量
 */
function editWorkload (id)
{
    ICB.modal.loading(true);
    var url = G_BASE_URL + '/admin/fill_workload/';
    $.get(url, {'id':id}, function (response) {
        ICB.modal.loading(false);


        $('#workload-edit-form-container').remove();
        var $refTr = $('tr[data-db-id="'+id+'"]');
        $refTr.after('<tr id="workload-edit-form-container"><td colspan="'+$refTr.find('td').length+'">'+$(response).find('#workload-edit-form').parent().html()+'</td></tr>');
        $('#workload-edit-form-container input').tooltip();
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
            if (parseFloat($(this).val()) > parseFloat($(this).data('default')) ) {
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
</script>

<?php View::output('admin/global/footer.php'); ?>
