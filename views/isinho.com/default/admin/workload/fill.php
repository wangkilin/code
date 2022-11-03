<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
            <h3><?php View::output('admin/workload/nav_inc'); ?></h3>
        </div>
        <div><?php  View::output('admin/workload/check_by_book.php'); ?></div>
        <div class="mod-body tab-content">
            <div class="tab-pane active" id="book">
                <div class="table-responsive" id="workload-edit-form-container">

					<form action="<?php echo $this->formAction=='' ? 'admin/ajax/workload/fill/' : $this->formAction;?>" method="post" id="workload-edit-form" onsubmit="return false;">

						<div class="icb-mod icb-book-infos">
						<input type="hidden" name="post_hash" value="<?php echo new_post_hash(); ?>" />
						<input type="hidden" name="batchKey" value="<?php echo $this->batchKey; ?>" />
                        <input type="hidden" name="backUrl" value="<?php echo isset($_GET['url'])? $_GET['url']:'';?>"/>
                        <?php if ($this->flagIsEditorReporting) { ?>
                        <input type="hidden" name="delivery_date" value="<?php echo date('Y-m-d'); ?>" />
                        <input type="hidden" name="category" value="" />

                        <div class="row">
                            <div class="col-sm-1">
                                <label class="icb-label"><?php _e('协同'); ?>:</label>
                            </div>
                            <div class="col-sm-5 text-left">
                                <select id="sinho_editor" name="user_ids[]" multiple><?php echo $this->userOptions;?></select>
                            </div>

                            <!-- 隶属学科 -->
                            <div class="col-sm-1">
                                <label class="icb-label"><?php _e('隶属学科'); ?>:</label>
                            </div>

                            <div class="col-sm-5 mod-double icon-date-container">
                                <?php if ($this->bookSubjectOptions) { ?>
                                <select name="category_id" class="form-control  " id="category_id">
                                    <option value="0">- <?php _e('请选择学科'); ?> -</option>
                                    <?php echo $this->bookSubjectOptions; ?>
                                </select>
							    <?php } ?>
                            </div>
                        </div>
                        <?php } else { ?>
                        <input type="hidden" name="id" id="item_id" value="<?php echo $this->itemInfo['id']; ?>" />
                        <input type="hidden" name="book_id" value="<?php echo $this->itemInfo['book_id']; ?>" />
                        <?php } ?>

                            <div class="row">
                                <!-- 系列 -->
                                <div class="col-sm-1">
                                    <label class="icb-label"><?php _e('系列'); ?>:</label>
                                </div>
                                <div class="col-sm-5 icb-item-title">
                                    <input type="text" name="serial" value="<?php echo $this->bookInfo['serial']; ?>" class="form-control" <?php if (! $this->flagIsEditorReporting) { ?>readonly<?php }?>/>
                                </div>
                                <!-- 书名 -->
                                <div class="col-sm-1">
                                    <label class="icb-label"><?php _e('书名'); ?>:</label>
                                </div>
                                <div class="col-sm-5 icb-item-title">
                                    <input type="text" name="book_name" value="<?php echo $this->bookInfo['book_name']; ?>" class="form-control"  <?php if (! $this->flagIsEditorReporting) { ?>readonly<?php }?>/>
                                </div>

                            </div>
                            <div class="row">
                                <!-- 校次 -->
                                <div class="col-sm-1">
                                    <label class="icb-label"><?php _e('校次'); ?>:</label>
                                </div>
                                <div class="col-sm-5 icb-item-title">
                                    <input type="text" name="proofreading_times" value="<?php echo $this->bookInfo['proofreading_times']; ?>" class="form-control"  <?php if (! $this->flagIsEditorReporting) { ?>readonly<?php }?>/>
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
                                <!-- 书名 -->
                                <div class="col-sm-1">
                                    <label class="icb-label"><?php _e('遍次'); ?>:</label>
                                </div>
                                <div class="col-sm-5 icb-item-title">
                                    <input type="text" name="working_times" value="<?php echo $this->itemInfo['working_times']; ?>" class="form-control" />
                                </div>

                            </div>

                            <div class="row">
                                <!--  -->
                                <div class="col-sm-1">
                                    <label class="icb-label"><?php _e('目录'); ?>:</label>
                                </div>
                                <div class="col-sm-1 icb-item-title">
                                    <input type="text" name="content_table_pages" value="<?php echo $this->itemInfo['content_table_pages']; ?>" class="form-control md-tip" data-default="<?php echo $this->bookInfo['content_table_pages']; ?>" title="<?php echo $this->bookInfo['content_table_pages']; ?>" data-toggle="tooltip" />
                                </div>
                                <div class="col-sm-2">
                                    <label class="icb-label"><?php _e('正文'); ?>:</label>
                                </div>
                                <div class="col-sm-1 icb-item-title">
                                    <input type="text" name="text_pages" value="<?php echo $this->itemInfo['text_pages']; ?>" class="form-control md-tip" data-default="<?php echo $this->bookInfo['text_pages']; ?>" title="<?php echo $this->bookInfo['text_pages']; ?>" data-toggle="tooltip" />
                                </div>
                                <div class="col-sm-2">
                                    <label class="icb-label"><?php _e('目录+正文千字/页'); ?>:</label>
                                </div>
                                <div class="col-sm-3 icb-item-title">
                                    <input type="text" name="text_table_chars_per_page" value="<?php echo empty($this->itemInfo['text_table_chars_per_page']) ? $this->bookInfo['text_table_chars_per_page'] : $this->itemInfo['text_table_chars_per_page']; ?>" class="form-control md-tip" data-default="<?php echo $this->bookInfo['text_table_chars_per_page']; ?>" title="<?php echo $this->bookInfo['text_table_chars_per_page']; ?>" data-toggle="tooltip" />
                                </div>

                            </div>

                            <div class="row">
                                <!-- 答案 -->
                                <div class="col-sm-1">
                                    <label class="icb-label"><?php _e('答案'); ?>:</label>
                                </div>
                                <div class="col-sm-1 icb-item-title">
                                    <input type="text" name="answer_pages" value="<?php echo $this->itemInfo['answer_pages']; ?>" class="form-control md-tip" data-default="<?php echo $this->bookInfo['answer_pages']; ?>" title="<?php echo $this->bookInfo['answer_pages']; ?>" data-toggle="tooltip" />
                                </div>
                                <div class="col-sm-2">
                                    <label class="icb-label"><?php _e('答案千字/页'); ?>:</label>
                                </div>
                                <div class="col-sm-1 icb-item-title">
                                    <input type="text" name="answer_chars_per_page" value="<?php echo empty($this->itemInfo['answer_chars_per_page']) ? $this->bookInfo['answer_chars_per_page'] : $this->itemInfo['answer_chars_per_page']; ?>" class="form-control md-tip" data-default="<?php echo $this->bookInfo['answer_chars_per_page']; ?>" title="<?php echo $this->bookInfo['answer_chars_per_page']; ?>" data-toggle="tooltip" />
                                </div>

                            </div>

                            <div class="row">
                                <!-- 试卷 -->
                                <div class="col-sm-1">
                                    <label class="icb-label"><?php _e('试卷'); ?>:</label>
                                </div>
                                <div class="col-sm-1 icb-item-title">
                                    <input type="text" name="test_pages" value="<?php echo $this->itemInfo['test_pages']; ?>" class="form-control md-tip" data-default="<?php echo $this->bookInfo['test_pages']; ?>" title="<?php echo $this->bookInfo['test_pages']; ?>" data-toggle="tooltip" />
                                </div>
                                <div class="col-sm-2">
                                    <label class="icb-label"><?php _e('试卷千字/页'); ?>:</label>
                                </div>
                                <div class="col-sm-1 icb-item-title">
                                    <input type="text" name="test_chars_per_page" value="<?php echo empty($this->itemInfo['test_chars_per_page']) ? $this->bookInfo['test_chars_per_page'] : $this->itemInfo['test_chars_per_page']; ?>" class="form-control md-tip" data-default="<?php echo $this->bookInfo['test_chars_per_page']; ?>" title="<?php echo $this->bookInfo['test_chars_per_page']; ?>" data-toggle="tooltip" />
                                </div>

                            <!-- </div>

                            <div class="row"> -->
                                <div class="col-sm-1"></div>
                                <!-- 试卷答案 -->
                                <div class="col-sm-1">
                                    <label class="icb-label"><?php _e('试卷答案'); ?>:</label>
                                </div>
                                <div class="col-sm-1 icb-item-title">
                                    <input type="text" name="test_answer_pages" value="<?php echo $this->itemInfo['test_answer_pages']; ?>" class="form-control md-tip" data-default="<?php echo $this->bookInfo['test_answer_pages']; ?>" title="<?php echo $this->bookInfo['test_answer_pages']; ?>" data-toggle="tooltip" />
                                </div>
                                <div class="col-sm-2">
                                    <label class="icb-label"><?php _e('试卷答案千字/页'); ?>:</label>
                                </div>
                                <div class="col-sm-1 icb-item-title">
                                    <input type="text" name="test_answer_chars_per_page" value="<?php echo empty($this->itemInfo['test_answer_chars_per_page']) ? $this->bookInfo['test_answer_chars_per_page'] : $this->itemInfo['test_answer_chars_per_page']; ?>" class="form-control md-tip" data-default="<?php echo $this->bookInfo['test_answer_chars_per_page']; ?>" title="<?php echo $this->bookInfo['test_answer_chars_per_page']; ?>" data-toggle="tooltip" />
                                </div>

                            </div>


                            <div class="row">
                                <!-- 课后作业 -->
                                <div class="col-sm-1">
                                    <label class="icb-label"><?php _e('课后作业'); ?>:</label>
                                </div>
                                <div class="col-sm-1 icb-item-title">
                                    <input type="text" name="exercise_pages" value="<?php echo $this->itemInfo['exercise_pages']; ?>" class="form-control md-tip" data-default="<?php echo $this->bookInfo['exercise_pages']; ?>" title="<?php echo $this->bookInfo['exercise_pages']; ?>" data-toggle="tooltip" />
                                </div>
                                <div class="col-sm-2">
                                    <label class="icb-label"><?php _e('课后作业千字/页'); ?>:</label>
                                </div>
                                <div class="col-sm-1 icb-item-title">
                                    <input type="text" name="exercise_chars_per_page" value="<?php echo empty($this->itemInfo['exercise_chars_per_page']) ? $this->bookInfo['exercise_chars_per_page'] : $this->itemInfo['exercise_chars_per_page']; ?>" class="form-control md-tip" data-default="<?php echo $this->bookInfo['exercise_chars_per_page']; ?>" title="<?php echo $this->bookInfo['exercise_chars_per_page']; ?>" data-toggle="tooltip" />
                                </div>

                            </div>

                            <div class="row">
                                <!-- 功能册 -->
                                <div class="col-sm-1">
                                    <label class="icb-label"><?php _e('功能册'); ?>:</label>
                                </div>
                                <div class="col-sm-1 icb-item-title">
                                    <input type="text" name="function_book" value="<?php echo $this->itemInfo['function_book']; ?>" class="form-control md-tip" data-default="<?php echo $this->bookInfo['function_book']; ?>" title="<?php echo $this->bookInfo['function_book']; ?>" data-toggle="tooltip" />
                                </div>
                                <div class="col-sm-2">
                                    <label class="icb-label"><?php _e('功能册千字/页'); ?>:</label>
                                </div>
                                <div class="col-sm-1 icb-item-title">
                                    <input type="text" name="function_book_chars_per_page" value="<?php echo empty($this->itemInfo['function_book_chars_per_page']) ? $this->bookInfo['function_book_chars_per_page'] : $this->itemInfo['function_book_chars_per_page']; ?>" class="form-control md-tip" data-default="<?php echo $this->bookInfo['function_book_chars_per_page']; ?>" title="<?php echo $this->bookInfo['function_book_chars_per_page']; ?>" data-toggle="tooltip" />
                                </div>

                            <!-- </div>

                            <div class="row"> -->
                                <div class="col-sm-1"></div>
                                <!-- 功能册答案 -->
                                <div class="col-sm-1">
                                    <label class="icb-label"><?php _e('功能册答案'); ?>:</label>
                                </div>
                                <div class="col-sm-1 icb-item-title">
                                    <input type="text" name="function_answer" value="<?php echo $this->itemInfo['function_answer']; ?>" class="form-control md-tip" data-default="<?php echo $this->bookInfo['function_answer']; ?>" title="<?php echo $this->bookInfo['function_answer']; ?>" data-toggle="tooltip" />
                                </div>
                                <div class="col-sm-2">
                                    <label class="icb-label"><?php _e('功能册答案千字/页'); ?>:</label>
                                </div>
                                <div class="col-sm-1 icb-item-title">
                                    <input type="text" name="function_answer_chars_per_page" value="<?php echo empty($this->itemInfo['function_answer_chars_per_page']) ? $this->bookInfo['function_answer_chars_per_page'] : $this->itemInfo['function_answer_chars_per_page']; ?>" class="form-control md-tip" data-default="<?php echo $this->bookInfo['function_answer_chars_per_page']; ?>" title="<?php echo $this->bookInfo['function_answer_chars_per_page']; ?>" data-toggle="tooltip" />
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-sm-1">
                                    <label class="icb-label"><?php _e('系数'); ?>:</label>
                                </div>
                                <div class="col-sm-1 icb-item-title">
                                    <input type="text" name="weight" value="<?php echo $this->itemInfo['weight']; ?>" class="form-control md-tip" data-default="<?php echo $this->bookInfo['weight']; ?>" title="<?php echo $this->bookInfo['weight']; ?>" data-toggle="tooltip" />
                                </div>
                                <div class="col-sm-3"></div>
                                <div class="col-sm-2">
                                    <label class="icb-label"><?php _e('核算总字数（千）'); ?>:</label>
                                </div>
                                <div class="col-sm-1 icb-item-title">
                                    <input type="text" name="total_chars" value="<?php echo $this->itemInfo['total_chars']; ?>" class="form-control md-tip" readonly/>
                                </div>
                                <div class="col-sm-2">
                                    <label class="icb-label"><?php _e('应发金额'); ?>:</label>
                                </div>
                                <div class="col-sm-1 icb-item-title">
                                    <input type="text" name="payable_amount" value="<?php echo $this->itemInfo['payable_amount']; ?>" class="form-control md-tip" readonly/>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-sm-1">
                                    <label class="icb-label"><?php _e('备注'); ?>:</label>
                                </div>
                                <div class="col-sm-11 icb-item-title">
                                    <input type="text" name="remarks" value="<?php echo $this->itemInfo['remarks']; ?>" class="form-control md-tip" data-default="<?php echo $this->bookInfo['remarks']; ?>" title="" data-toggle="tooltip" />
                                </div>
                                <div class="col-sm-11 icb-item-title text-left">
                                    <?php echo $this->bookInfo['remarks']; ?>
                                </div>

                            </div>


                            <div class="row">
                                <div class="col-sm-1"></div>
                                <div class="col-sm-4 mod-footer clearfix">
                                    <a class="btn btn-large btn-success col-sm-8" id="publish_submit" onclick="submit_workload_edit_form();"><?php _e('保 存'); ?></a>
                                </div>
                                <div class="col-sm-2"></div>
                                <div class="col-sm-4 mod-footer clearfix">
                                    <a class="btn btn-large btn-warning col-sm-8" onclick="concel_form();"><?php _e('取 消'); ?></a>
                                </div>
                                <div class="col-sm-1"></div>
                            </div>
						</div>
					</form>

                </div>
            </div>
            <?php View::output('admin/course/search_inc.php');?>
        </div>
    </div>
</div>

<?php View::output('admin/workload/fill_workload_js'); ?>
<script type="text/javascript">
function concel_form ()
{
    window.location.href = G_BASE_URL + '/admin/fill_list/';
}

function submit_workload_edit_form ()
{
    $inputs = $('#workload-edit-form input');
    $hasWarning = false;
    for (var i=0; i<$inputs.length; i++) {
        if ($inputs.eq(i).data('default')===undefined) {
            continue;
        }

        if (parseFloat($inputs.eq(i).val()) > parseFloat($inputs.eq(i).data('default'))) {
            $hasWarning = true;
            break;
        }
    }

    if ($hasWarning) {
        var onYesCallback = function () { ICB.ajax.postForm($('#workload-edit-form')); };

        ICB.modal.confirm('存在不一致的参数。 是否继续提交？', onYesCallback);
    } else {
        ICB.ajax.postForm($('#workload-edit-form'));
    }


    return false;
}

$(function () {

    $("#sinho_editor").multiselect({
        			nonSelectedText : '<?php _e('---- 选择责编 ----');?>',
                    maxHeight       : 200,
                    buttonWidth     : '100%',
                    allSelectedText : '<?php _e('已选择所有人');?>',
                    numberDisplayed : 7, // 选择框最多提示选择多少个人名
    });
    /**
     * 监听输入框输入按键为回车时， 如果输入框为空值， 使用默认值填充
     */
    $('#workload-edit-form input').keydown(function (event) {
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
        compute_chars_ammount();
        console.info($(this).data('default'));
        if ($(this).data('default')===undefined) {
            return;
        }

        if (parseFloat($(this).val()) > parseFloat($(this).data('default')) ) {
            $(this).parent().addClass('has-error');
        } else {
            $(this).parent().removeClass('has-error');
        }
        console.info(parseFloat($(this).val()) , parseFloat($(this).data('default')));
    });


});
</script>
<?php View::output('admin/global/footer.php'); ?>
