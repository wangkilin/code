<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
        <?php View::output('admin/finance/salary_inc.php');?>
        </div>

        <div class="mod-body tab-content">
            <div class="tab-pane active" id="salary_import">
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
                            <div class="row"></div>
                            <div class="row hide hidden  js-import-result">
                                <div  class="col-sm-12">
                                    <form action="admin/ajax/finance/salary_do_import/" method="post" id="item_form" onsubmit="return false;">
                                        <input type="hidden" id="js-import-result-batchkey" name="batch_key" value="" />
                                        <input type="hidden" id="js-import-filename" name="filename" value=""/>
                                        <input type="hidden" name="backUrl" value="<?php echo isset($_GET['url'])? $_GET['url']:'';?>"/>

                                        <div class="row">
                                            <div class="col-sm-2 col-xs-6">
                                                <?php _e('选择要导入的工作表：');?>
                                                <!-- <span id="import-result-total-sheet" class="bg-danger"></span> -->
                                            </div>
                                            <div class="col-sm-6 col-xs-6">
                                                <select id="salary_sheets" name="salary_sheets"></select>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <div class="col-sm-2 col-xs-6">
                                                <?php _e('工资所属月份：');?>
                                            </div>


                                            <div class="col-sm-6 col-xs-6 mod-double icon-date-container">
                                                <input type="text" name="start_month" class="form-control icon-indent js-date-input js-monthpicker" value="<?php echo date('Y-m');?>">
                                                <i class="icon icon-date"></i>
                                                <i class="icon icon-date-delete icon-delete"></i>
                                            </div>
                                        </div>
                                        <div class="">
                                            <input id="js-submit" class="btn btn-primary" type="submit" value="<?php _e('确 定');?>"/>
                                        </div>
					                </form>
                                </div>

                            </div>
						</div>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(function () {


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
    $('#js-submit').click(function() {
        ICB.ajax.requestJson(
            $(this).closest('form').attr('action'),
            $(this).closest('form').serialize(),
            function (response) {
                console && console.info(response);
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
    // 点击上传， 显示操作进行中的状态
    $('.js-upload-btn').click(function () {
        ICB.modal.loading(true);
    });

    new FileUploader(
            $('.js-upload-btn'),
            $('.upload-containerrr'),
            G_BASE_URL + '/admin/ajax/finance/finance_import/id-sinho',
            {
                //'uploadingModalSelector' : '#avatar_uploading_status',
                'showUploadImage': false,
                fileName:'upload_file'
            },
            function (result) {
                ICB.modal.loading(false);
                console && console.info(result);
                $('#import-result-total-sheet').text(result.sheet_names.length);
                $('#js-import-result-batchkey').val(result.batch_key);

                for(var i=0; i<result.sheet_names.length; i++) {
                    $('#salary_sheets').append($('<option></option>').val(result.sheet_names[i]).text(result.sheet_names[i]));
                }

                // 如果 select 标签有 multiple属性，就会生成多选。
                $('#salary_sheets').multiselect({
                    nonSelectedText : '<?php _e('---- 选择已支付的工作表 ----');?>',
                    maxHeight       : 200,
                    buttonWidth     : 400,
                    allSelectedText : '<?php _e('已选择全部工作表');?>',
                    numberDisplayed : 4, // 选择框最多提示选择多少个选项
                });
                $('#js-import-filename').val(result.newFilePath);
                $('.js-import-upload').addClass('hidden');
                $('.js-import-result').removeClass('hide').removeClass('hidden');

                return;
            }
    );

});
</script>
<?php View::output('admin/global/footer.php'); ?>
