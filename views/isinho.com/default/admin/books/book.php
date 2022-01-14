<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
            <label>
            <?php View::output('admin/books/nav_inc.php');?>
            </label>
        </div>

        <div class="mod-body tab-content">
            <div class="tab-pane active" id="book">
                <div class="table-responsive">

					<form action="admin/ajax/books/save/" method="post" id="item_form" onsubmit="return false;">
						<input type="hidden" name="post_hash" value="<?php echo new_post_hash(); ?>" />
						<input type="hidden" name="batchKey" value="<?php echo $this->batchKey; ?>" />
                        <input type="hidden" name="id" id="item_id" value="<?php echo $this->itemInfo['id']; ?>" />
                        <input type="hidden" name="backUrl" value="<?php echo isset($_GET['url'])? $_GET['url']:'';?>"/>
						<?php if ($this->itemOptions) { ?>
							<select name="category_id" class="collapse js_select_transform" id="category_id">
								<option value="0">- <?php _e('请选择分类'); ?> -</option>
								<?php echo $this->itemOptions; ?>
							</select>
							<?php } ?>
						<div class="icb-mod icb-book-infos">
                            <div class="row">
                                <!-- 年份 -->
                                <div class="col-sm-1">
                                    <label class="icb-label"><?php _e('年份'); ?>:</label>
                                </div>
                                <div class="col-sm-5 icb-item-title">
                                    <select name="book_belong_year" class="form-control" id="book_belong_year">
                                        <option value="0">- <?php _e('请选择年份'); ?> -</option>
                                        <?php foreach ($this->bookBelongYears as $_key => $_valueInfo) {?>
                                        <option value="<?php echo $_key;?>" <?php
                                          echo  $this->itemInfo['book_belong_year']==$_key ? 'selected':'' ?>><?php echo $_valueInfo['long'];?></option>
                                        <?php }?>
                                    </select>
                                </div>

                            </div>
                            <div class="row">
                                <!-- 系列 -->
                                <div class="col-sm-1">
                                    <label class="icb-label"><?php _e('类别'); ?>:</label>
                                </div>
                                <div class="col-sm-5 icb-item-title">
                                    <input type="text" name="category" value="<?php echo $this->itemInfo['category']; ?>" class="form-control" />
                                </div>
                                <!-- 系列 -->
                                <div class="col-sm-1">
                                    <label class="icb-label"><?php _e('系列'); ?>:</label>
                                </div>
                                <div class="col-sm-5 icb-item-title">
                                    <input type="text" name="serial" value="<?php echo $this->itemInfo['serial']; ?>" class="form-control" />
                                </div>


                            </div>
                            <div class="row">
                                <!-- 书名 -->
                                <div class="col-sm-1">
                                    <label class="icb-label"><?php _e('书名'); ?>:</label>
                                </div>
                                <div class="col-sm-5 icb-item-title">
                                    <input type="text" name="book_name" value="<?php echo $this->itemInfo['book_name']; ?>" class="form-control" />
                                </div>
                                <!-- 校次 -->
                                <div class="col-sm-1">
                                    <label class="icb-label"><?php _e('校次'); ?>:</label>
                                </div>
                                <div class="col-sm-5 icb-item-title">
                                    <input type="text" name="proofreading_times" value="<?php echo $this->itemInfo['proofreading_times']; ?>" class="form-control" />
                                </div>
                            </div>

                            <div class="row">
                                <!--  -->
                                <div class="col-sm-1">
                                    <label class="icb-label"><?php _e('目录'); ?>:</label>
                                </div>
                                <div class="col-sm-2 icb-item-title">
                                    <input type="text" name="content_table_pages" value="<?php echo $this->itemInfo['content_table_pages']; ?>" class="form-control js-monitor-compute" />
                                </div>
                                <div class="col-sm-2">
                                    <label class="icb-label"><?php _e('正文'); ?>:</label>
                                </div>
                                <div class="col-sm-2 icb-item-title">
                                    <input type="text" name="text_pages" value="<?php echo $this->itemInfo['text_pages']; ?>" class="form-control js-monitor-compute" />
                                </div>
                                <div class="col-sm-2">
                                    <label class="icb-label"><?php _e('目录+正文千字/页'); ?>:</label>
                                </div>
                                <div class="col-sm-3 icb-item-title">
                                    <input type="text" name="text_table_chars_per_page" value="<?php echo $this->itemInfo['text_table_chars_per_page']; ?>" class="form-control js-monitor-compute" />
                                </div>

                            </div>

                            <div class="row">
                                <!-- 答案 -->
                                <div class="col-sm-1">
                                    <label class="icb-label"><?php _e('答案'); ?>:</label>
                                </div>
                                <div class="col-sm-2 icb-item-title">
                                    <input type="text" name="answer_pages" value="<?php echo $this->itemInfo['answer_pages']; ?>" class="form-control js-monitor-compute" />
                                </div>
                                <div class="col-sm-2">
                                    <label class="icb-label"><?php _e('答案千字/页'); ?>:</label>
                                </div>
                                <div class="col-sm-2 icb-item-title">
                                    <input type="text" name="answer_chars_per_page" value="<?php echo $this->itemInfo['answer_chars_per_page']; ?>" class="form-control js-monitor-compute" />
                                </div>

                            </div>

                            <div class="row">
                                <!-- 试卷 -->
                                <div class="col-sm-1">
                                    <label class="icb-label"><?php _e('试卷'); ?>:</label>
                                </div>
                                <div class="col-sm-2 icb-item-title">
                                    <input type="text" name="test_pages" value="<?php echo $this->itemInfo['test_pages']; ?>" class="form-control js-monitor-compute" />
                                </div>
                                <div class="col-sm-2">
                                    <label class="icb-label"><?php _e('试卷千字/页'); ?>:</label>
                                </div>
                                <div class="col-sm-2 icb-item-title">
                                    <input type="text" name="test_chars_per_page" value="<?php echo $this->itemInfo['test_chars_per_page']; ?>" class="form-control js-monitor-compute" />
                                </div>

                            </div>

                            <div class="row">
                                <!-- 试卷答案 -->
                                <div class="col-sm-1">
                                    <label class="icb-label"><?php _e('试卷答案'); ?>:</label>
                                </div>
                                <div class="col-sm-2 icb-item-title">
                                    <input type="text" name="test_answer_pages" value="<?php echo $this->itemInfo['test_answer_pages']; ?>" class="form-control js-monitor-compute" />
                                </div>
                                <div class="col-sm-2">
                                    <label class="icb-label"><?php _e('试卷答案千字/页'); ?>:</label>
                                </div>
                                <div class="col-sm-2 icb-item-title">
                                    <input type="text" name="test_answer_chars_per_page" value="<?php echo $this->itemInfo['test_answer_chars_per_page']; ?>" class="form-control js-monitor-compute" />
                                </div>

                            </div>


                            <div class="row">
                                <!-- 课后作业 -->
                                <div class="col-sm-1">
                                    <label class="icb-label"><?php _e('课后作业'); ?>:</label>
                                </div>
                                <div class="col-sm-2 icb-item-title">
                                    <input type="text" name="exercise_pages" value="<?php echo $this->itemInfo['exercise_pages']; ?>" class="form-control js-monitor-compute" />
                                </div>
                                <div class="col-sm-2">
                                    <label class="icb-label"><?php _e('课后作业千字/页'); ?>:</label>
                                </div>
                                <div class="col-sm-2 icb-item-title">
                                    <input type="text" name="exercise_chars_per_page" value="<?php echo $this->itemInfo['exercise_chars_per_page']; ?>" class="form-control js-monitor-compute" />
                                </div>

                            </div>

                            <div class="row">
                                <!-- 功能册 -->
                                <div class="col-sm-1">
                                    <label class="icb-label"><?php _e('功能册'); ?>:</label>
                                </div>
                                <div class="col-sm-2 icb-item-title">
                                    <input type="text" name="function_book" value="<?php echo $this->itemInfo['function_book']; ?>" class="form-control js-monitor-compute" />
                                </div>
                                <div class="col-sm-2">
                                    <label class="icb-label"><?php _e('功能册千字/页'); ?>:</label>
                                </div>
                                <div class="col-sm-2 icb-item-title">
                                    <input type="text" name="function_book_chars_per_page" value="<?php echo $this->itemInfo['function_book_chars_per_page']; ?>" class="form-control js-monitor-compute" />
                                </div>

                            </div>

                            <div class="row">
                                <!-- 功能册答案 -->
                                <div class="col-sm-1">
                                    <label class="icb-label"><?php _e('功能册答案'); ?>:</label>
                                </div>
                                <div class="col-sm-2 icb-item-title">
                                    <input type="text" name="function_answer" value="<?php echo $this->itemInfo['function_answer']; ?>" class="form-control js-monitor-compute" />
                                </div>
                                <div class="col-sm-2">
                                    <label class="icb-label"><?php _e('功能册答案千字/页'); ?>:</label>
                                </div>
                                <div class="col-sm-2 icb-item-title">
                                    <input type="text" name="function_answer_chars_per_page" value="<?php echo $this->itemInfo['function_answer_chars_per_page']; ?>" class="form-control js-monitor-compute" />
                                </div>

                            </div>

                            <div class="row">
                                <!-- 系数， 总字数 -->
                                <div class="col-sm-1">
                                    <label class="icb-label"><?php _e('难度系数'); ?>:</label>
                                </div>
                                <div class="col-sm-2 icb-item-title">
                                    <input type="text" name="weight" value="<?php echo $this->itemInfo['weight']; ?>" class="form-control js-monitor-compute" />
                                </div>
                                <div class="col-sm-2">
                                    <label class="icb-label"><?php _e('字数（合计）'); ?>:</label>
                                </div>
                                <div class="col-sm-2 icb-item-title">
                                    <input type="text" name="total_chars" value="<?php echo $this->itemInfo['total_chars']; ?>" class="form-control" />
                                </div>

                            </div>

                            <div class="row">
                                <!-- 备注信息 -->
                                <div class="col-sm-1">
                                    <label class="icb-label"><?php _e('备注'); ?>:</label>
                                </div>
                                <div class="col-sm-11 icb-item-title">
                                    <input type="text" name="remarks" value="<?php echo $this->itemInfo['remarks']; ?>" class="form-control" />
                                </div>

                            </div>

							<div class="row mod-footer clearfix">
								<?php if ($this->itemInfo['id'] ) { ?>
								<a class="btn btn-large btn-danger" id="deleteBatchBtn" onclick="deleteItem(<?php echo $this->itemInfo['id'];?>); return false;"><?php _e('删除书稿'); ?></a>
								<?php } ?>
								<a class="btn btn-large btn-success btn-publish-submit" id="publish_submit" onclick="ICB.ajax.postForm($('#item_form')); return false;"><?php _e('保存书稿'); ?></a>
							</div>
						</div>
					</form>

                </div>
            </div>
            <?php View::output('admin/books/search_inc.php');?>
        </div>
    </div>
</div>
<script>
function compute_chars_ammount ()
{
    // =((H5+I5)*J5+K5*L5+M5*N5+O5*P5+Q5*R5+S5*T5+U5*V5)*W5
    var totalChars =
        ( (float($('#item_form input[name="content_table_pages"]').val())
                    + float($('#item_form input[name="text_pages"]').val() ) )
                * float($('#item_form input[name="text_table_chars_per_page"]').val() )
            + (float($('#item_form input[name="answer_pages"]').val() )
                * float($('#item_form input[name="answer_chars_per_page"]').val() ) )
            + (float($('#item_form input[name="test_pages"]').val())
                * float($('#item_form input[name="test_chars_per_page"]').val())  )
            + (float($('#item_form input[name="test_answer_pages"]').val())
                * float($('#item_form input[name="test_answer_chars_per_page"]').val())  )
            + (float($('#item_form input[name="exercise_pages"]').val())
                * float($('#item_form input[name="exercise_chars_per_page"]').val())  )
            + (float($('#item_form input[name="function_book"]').val())
                * float($('#item_form input[name="function_book_chars_per_page"]').val())  )
            + (float($('#item_form input[name="function_answer"]').val())
                * float($('#item_form input[name="function_answer_chars_per_page"]').val())  )
        )
        * float($('#item_form input[name="weight"]').val() ) ;
    totalChars = float(totalChars, 4);

    console && console.info( float($('#item_form input[name="content_table_pages"]').val())
                    , float($('#item_form input[name="text_pages"]').val() )
                , float($('#item_form input[name="text_table_chars_per_page"]').val() )
            , (float($('#item_form input[name="answer_pages"]').val() )
                * float($('#item_form input[name="answer_chars_per_page"]').val() ) )
            , (float($('#item_form input[name="test_pages"]').val())
                * float($('#item_form input[name="test_chars_per_page"]').val())  )
            , (float($('#item_form input[name="test_answer_pages"]').val())
                * float($('#item_form input[name="test_answer_chars_per_page"]').val())  )
            , (float($('#item_form input[name="exercise_pages"]').val())
                * float($('#item_form input[name="exercise_chars_per_page"]').val())  )
            , (float($('#item_form input[name="function_book"]').val())
                * float($('#item_form input[name="function_book_chars_per_page"]').val())  )
            , (float($('#item_form input[name="function_answer"]').val())
                * float($('#item_form input[name="function_answer_chars_per_page"]').val())  )

        , float($('#item_form input[name="weight"]').val() )
    );
   $('#item_form input[name="total_chars"]').val(totalChars);
   //return {'chars':totalChars, 'amount':amount};
}
function deleteItem(id)
{
    ICB.domEvents.deleteShowConfirmModal(
  	   _t('确认删除书稿？书稿工作量也会一起删除。 请通知责编！'),
  	   function(){
      	   var url = G_BASE_URL + '/admin/ajax/books/remove/',
      	       params = {'ids[]':id, 'action':'remove', '_post_type':'ajax'};
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
	      	      	    ICB.modal.alert(_t('书稿成功删除'), {'hidden.bs.modal': function () {
		      	      		    window.location.href = G_BASE_URL + '/admin/books/';
		      	      	    }
	      	      	    });
	      	      	} else {
	      	      	    ICB.modal.alert(_t('请求发生错误'));
	      	      	}
  	      	   }
  	       );
	       }
    );
}

$(function () {

    /**
     * 监听输入框输入按键, 计算总字数
     */
    $('#item_form input').blur(function () {
        if($(this).hasClass('js-monitor-compute')) {
            compute_chars_ammount();
        }
    });
    $('#item_form input').keyup(function (event) {
        if($(this).hasClass('js-monitor-compute')) {
            compute_chars_ammount();
        }
    });
});
</script>
<?php View::output('admin/global/footer.php'); ?>
