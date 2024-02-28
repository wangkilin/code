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
            <div class="tab-pane active" id="import">
                <div class="table-responsive">

						<div class="icb-mod">
                            <div class="row js-import-upload">
                                <div  class="col-sm-12">
                                    <a class="btn btn-default js-upload-btn">上传附件</a>
                                    <span class="text-color-999 icb-upload-tips hidden-xs bg-danger"><?php _e('允许的附件文件类型'); ?>: xls, xlsx; </span>
                                    &nbsp;
                                    <span class="icb-upload-tips hidden-xs bg-success"> <a href="/static/isinho.com/template_book_import.xls" target="_blank">导入模板下载</a></span>
                                    <div class="upload-container"></div>
                                </div>

                            </div>
                            <div class="row hide hidden js-import-result">
                                <div  class="col-sm-12">
                                    <?php _e('识别出工作表：');?><span id="import-result-total-sheet" class="bg-danger"></span>
                                &nbsp; &nbsp;
                                    <?php _e('导入书稿：');?><span id="import-result-total-book" class="bg-danger"></span>
                                </div>
                            </div>
                            <div class="row"></div>
                            <div class="row hide hidden js-import-result">
                                <div  class="col-sm-12">
                                    <form action="admin/ajax/<?php echo CONTROLLER;?>/do_import/" method="post" id="item_form" onsubmit="return false;">
                                        <input type="hidden" id="js-import-result-batchkey" name="batch_key" value="" />
                                        <input type="hidden" name="backUrl" value="<?php echo isset($_GET['url'])? $_GET['url']:'';?>"/>
                                        <input type="hidden" id="js-import-filename" name="filename" value=""/>
                                        <div class="row">
                                            <div class="col-sm-5">
                                            <select id="payed_sheets" name="payed_sheets[]" multiple ></select>
                                            </div>
                                        <?php if ($this->hostConfig && $this->hostConfig->sinho_feature_list['enable_set_book_pay_status']) { ?>
                                            <div class="col-sm-5">
                                                <label class="icb-label"><?php _e('已支付'); ?></label>
                                                <input name="is_payed" type="radio" value="1"/>
                                                &nbsp;
                                                <label class="icb-label"><?php _e('未支付'); ?></label>
                                                <input name="is_payed" type="radio" value="0" checked="checked"/>
                                            </div>
                                        <?php }?>
                                        </div>
                                        <br/>
                                        <br/>
                                        <div class="row">
                                            <div class="col-sm-6">
                                            <input id="js-submit" class="btn btn-primary" type="submit" value="<?php _e('确 定');?>"/>
                                            </div>
                                        </div>
					                </form>
                                </div>

                            </div>
						</div>

                </div>
            </div>
            <?php View::output('admin/books/search_inc.php');?>
        </div>
    </div>
</div>
<script>
$(function () {


    $('#js-submit').click(function() {
        ICB.ajax.requestJson(
            $(this).closest('form').attr('action'),
            $(this).closest('form').serialize(),
            function (response) {
                if (response.errno==1) {
                    if (response.err) {
                        ICB.modal.alert(response.err);
                        window.setTimeout(function () {
                            window.location.href = response.rsm.url;
                        }, 3000);
                    } else {
                        window.location.href = response.rsm.url;
                    }

                } else {
                    ICB.modal.alert('操作出现错误， 请联系管理员！');
                }
            }
        );
    });
		new FileUploader(
				$('.js-upload-btn'), // 绑定上传文件按钮
				$('.upload-container'),
				G_BASE_URL + '/admin/ajax/<?php echo CONTROLLER;?>/import/id-sinho',
				{
					//'uploadingModalSelector' : '#avatar_uploading_status',
					'showUploadImage': false,
					fileName:'upload_file'
				},
				function (result) {
                    console && console.info(result);
                    $('#import-result-total-sheet').text('   ' + result.sheet_names.length + '   ');
                    //$('#import-result-total-book').text(result.total_import);
                    $('#js-import-result-batchkey').val(result.batch_key);
                    $('#js-import-filename').val(result.newFilePath);
                    // 将Excel表中的子表做列表处理， 供选择导入。 只将选择的子表导入
                    for(var i=0; i<result.sheet_names.length; i++) {
                        $('#payed_sheets').append($('<option></option>').val(result.sheet_names[i]).text(result.sheet_names[i]));
                    }
                    // 生成复选列表。 只有选中的子表才做导入处理
                    $('#payed_sheets').multiselect({
                        nonSelectedText : '<?php _e('---- 选择待导入的工作表 ----');?>',
                        maxHeight       : 200,
                        buttonWidth     : 400,
                        allSelectedText : '<?php _e('已选择全部工作表');?>',
                        numberDisplayed : 4, // 选择框最多提示选择多少个人名
                    });
                    $('.js-import-upload').addClass('hidden');
                    $('.js-import-result').removeClass('hide').removeClass('hidden');

                    return;
				}
		);

});
</script>
<?php View::output('admin/global/footer.php'); ?>
