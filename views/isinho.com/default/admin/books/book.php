<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
            <label>
            <?php View::output('admin/books/nav_inc.php');?>
            </label>
        </div>


        <!-- Theme switcher -->
        <div class="theme-switch" style="width:200px;right:-210px;top:55%;">
            <div class="icon inOut" style="z-index:100"><i class="rotate icon-setting"></i></div>
            <div class="tab-pane" id="search">

                <form method="get" action="" class="form-horizontal" role="form" onsubmit="return false;">
                    <input name="action" type="hidden" value="search" />

                    <div class="form-group">
                        <label class="col-sm-6 col-xs-6 control-label text-right no-padding"><?php _e('修订比例 %'); ?>:</label>

                        <div class="col-sm-6 col-xs-6">
                            <input id="rateNumber" class="form-control" type="text" value="" name="number" placeholder="数值"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-4 col-xs-8">
                            <input type="button" class="btn btn-primary js-change-rate" value="<?php _e('修订计算'); ?>"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="mod-body tab-content">
            <div class="tab-pane active" id="book">
                <div class="table-responsive">

					<form action="admin/ajax/books/save/" method="post" id="item_form" onsubmit="return false;">
						<input type="hidden" name="post_hash" value="<?php echo new_post_hash(); ?>" />
						<input type="hidden" name="batchKey" value="<?php echo $this->batchKey; ?>" />
                        <input type="hidden" name="id" id="item_id" value="<?php echo $this->itemInfo['id']; ?>" />
                        <input type="hidden" name="backUrl" value="<?php echo isset($_GET['url'])? $_GET['url']:'';?>"/>

						<div class="icb-mod icb-book-infos">
                            <div class="row">
                                <!-- 年份 -->
                                <div class="col-sm-1">
                                    <label class="icb-label"><?php _e('隶属学科'); ?>:</label>
                                </div>

                                <div class="col-sm-5 mod-double icon-date-container">
                                <?php if ($this->itemOptions) { ?>
							<select name="category_id" class="form-control  " id="category_id">
								<option value="0">- <?php _e('请选择学科'); ?> -</option>
								<?php echo $this->itemOptions; ?>
							</select>
							<?php } ?>
                                </div>

                                <!-- 年份 -->
                                <div class="col-sm-1">
                                    <label class="icb-label"><?php _e('发稿日期'); ?>:</label>
                                </div>

                                <div class="col-sm-5 mod-double icon-date-container">
                                    <input type="text" class="form-control icon-indent js-date-input js-monthpicker" value="<?php echo isset($this->itemInfo['delivery_date']) ? $this->itemInfo['delivery_date'] : date('Y-m-d'); ?>" name="delivery_date" autocomplete="off" placeholder="发稿日期"/>
                                    <i class="icon icon-date"></i>
                                    <i class="icon icon-date-delete icon-delete"></i>
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
                                    <input type="text" name="text_pages" value="<?php echo $this->itemInfo['text_pages']; ?>" class="form-control js-monitor-compute js-monitor-change-rate" />
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
                                    <input type="text" name="answer_pages" value="<?php echo $this->itemInfo['answer_pages']; ?>" class="form-control js-monitor-compute js-monitor-change-rate" />
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
                                    <input type="text" name="test_pages" value="<?php echo $this->itemInfo['test_pages']; ?>" class="form-control js-monitor-compute js-monitor-change-rate" />
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
                                    <input type="text" name="test_answer_pages" value="<?php echo $this->itemInfo['test_answer_pages']; ?>" class="form-control js-monitor-compute js-monitor-change-rate" />
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
                                    <input type="text" name="exercise_pages" value="<?php echo $this->itemInfo['exercise_pages']; ?>" class="form-control js-monitor-compute js-monitor-change-rate" />
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
                                    <input type="text" name="function_book" value="<?php echo $this->itemInfo['function_book']; ?>" class="form-control js-monitor-compute js-monitor-change-rate" />
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
                                    <input type="text" name="function_answer" value="<?php echo $this->itemInfo['function_answer']; ?>" class="form-control js-monitor-compute js-monitor-change-rate" />
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

                            <div class="row">
                                <!-- 管理员备注信息 -->
                                <div class="col-sm-1">
                                    <label class="icb-label"><?php _e('管理员备注'); ?>:</label>
                                </div>
                                <div class="col-sm-11 icb-item-title">
                                    <input type="text" name="admin_remarks" value="<?php echo $this->itemInfo['admin_remarks']; ?>" class="form-control" placeholder="<?php _e('管理员备注信息，只对管理员可见');?>"/>
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
     * 点击修订率计算按钮，将除目录外的页码按照修订率计算；
     */
    $('body').on('click', '.js-change-rate', function () {
        var rateNumber = parseFloat($('#rateNumber').val());
        // 对每个设置样式的输入框计算修订页码
        $('.js-monitor-change-rate').each(function () {
            if ($.trim($(this).val()) =='') {
                return;
            }
            // 展示原值
            $(this).tooltip({title:$(this).val()});
            $(this).tooltip('show');
            // 计算结果
            $(this).val(float($(this).val() * rateNumber / 100) );
            // 页码变更， 字总数要重新计算
            compute_chars_ammount();
        });
    });

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

    // 日期输入框
    $( ".js-monthpicker" ).datetimepicker({
                format  : 'yyyy-mm-dd',
                language:  'zh-CN',
                weekStart: 1, // 星期一 为一周开始
                todayBtn:  1, // 显示今日按钮
                autoclose: 1,
                todayHighlight: 1,
                startView: 2, // 显示的日期级别： 0:到分钟， 1：到小时， 2：到天
                forceParse: 0,
                minView : 2, // 0:选择到分钟， 1：选择到小时， 2：选择到天
            });


    // 点击侧边栏图标，将侧边栏工具内容显示/隐藏
    $('.theme-switch').width(200);
    $('.theme-switch .icon').click (function(event){
            event.preventDefault();
            if( $ (this).hasClass('inOut')  ){
                $('.theme-switch').stop().animate({right:'0px'},1000 );
            } else{
                $('.theme-switch').stop().animate({right:'-210px'},1000 );
            }
            $(this).toggleClass('inOut');
            return false;

        }  );

});


</script>
<?php View::output('admin/global/footer.php'); ?>
