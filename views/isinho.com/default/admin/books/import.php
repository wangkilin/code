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
            <div class="tab-pane active">
                <div class="table-responsive">

						<div class="icb-mod">
                            <div class="row js-import-upload">
                                <div  class="col-sm-12">
                                    <a class="btn btn-default js-upload-btn">上传附件</a>
                                    <span class="text-color-999 icb-upload-tips hidden-xs bg-danger"><?php _e('允许的附件文件类型'); ?>: xls, xlsx; </span>
                                    <div class="upload-container"></div>
                                </div>

                            </div>
                            <div class="row hide hidden js-import-result">
                                <div  class="col-sm-12">
                                    <?php _e('导入工作表：');?><span id="import-result-total-sheet" class="bg-danger"></span>
                                &nbsp; &nbsp;
                                    <?php _e('导入书稿：');?><span id="import-result-total-book" class="bg-danger"></span>
                                </div>
                            </div>
                            <div class="row"></div>
                            <div class="row hide hidden js-import-result">
                                <div  class="col-sm-12">
                                    <form action="admin/ajax/books/set_payed/" method="post" id="item_form" onsubmit="return false;">
                                        <input type="hidden" id="js-import-result-batchkey" name="batch_key" value="" />
                                        <input type="hidden" name="backUrl" value="<?php echo isset($_GET['url'])? $_GET['url']:'';?>"/>
                                        <div class="">
                                            <select id="payed_sheets" name="payed_sheets[]" multiple ></select>
                                            <input id="js-submit" class="btn btn-primary" type="submit" value="<?php _e('确 定');?>"/>
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
				$('.js-upload-btn'),
				$('.upload-containerrr'),
				G_BASE_URL + '/admin/ajax/books/import/id-sinho',
				{
					//'uploadingModalSelector' : '#avatar_uploading_status',
					'showUploadImage': false,
					fileName:'upload_file'
				},
				function (result) {
                    console && console.info(result);
                    $('#import-result-total-sheet').text(result.sheet_names.length);
                    $('#import-result-total-book').text(result.total_import);
                    $('#js-import-result-batchkey').val(result.batch_key);

                    for(var i=0; i<result.sheet_names.length; i++) {
                        $('#payed_sheets').append($('<option></option>').val(result.sheet_names[i]).text(result.sheet_names[i]));
                    }

                    $('#payed_sheets').multiselect({
                        nonSelectedText : '<?php _e('---- 选择已支付的工作表 ----');?>',
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
