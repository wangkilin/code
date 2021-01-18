<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
	<form action="admin/ajax/save_category/" id="category_form" method="post" onsubmit="return false">
	<input type="hidden" name="category_id" value="<?php echo $this->category['id']; ?>" />
	<div class="mod">
		<div class="mod-head">
			<h3>
            <?php View::output('admin/category/nav_inc.php');?>
			</h3>
		</div>
		<div class="tab-content mod-content">
			<table class="table table-striped">
				<?php if ($this->category) { ?>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-2 col-xs-3 control-label"><?php _e('所属模块'); ?>:</span>
							<div class="col-sm-9 col-xs-8">
								<select name="module_id" id="module_id" class="form-control">
									<option value="0"><?php _e('无'); ?></option>
									<?php echo $this->module_option; ?>
								</select>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-2 col-xs-3 control-label"><?php _e('缩略图'); ?>:</span>
							<div class="col-sm-9 col-xs-8">
								<a id="thumb_pic_uploader"><img src="<?php echo getModulePicUrlBySize('category', 'mid', $this->category['pic']); ?>" alt="" id="thumb_pic" class="img-polaroid" name="thumb_pic" /></a>
							</div>
						</div>
					</td>
				</tr>
				<?php } ?>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-2 col-xs-3 control-label"><?php _e('分类标题'); ?>:</span>
							<div class="col-sm-9 col-xs-8">
								<input class="form-control" type="text" name="title" value="<?php echo $this->category['title']; ?>" />
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-2 col-xs-3 control-label"><?php _e('分类别名'); ?>:</span>
							<div class="col-sm-9 col-xs-8">
								<span class="col-sm-1 mod-text-inline">/<span id="js_url_module_name">index</span>/category-</span>
								<div class="col-xs-11 col-sm-9 pull-right nopadding">
									<input type="text" name="url_token" class="form-control" value="<?php echo $this->category['url_token']; ?>" />
								</div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-2 col-xs-3 control-label"><?php _e('父级分类'); ?>:</span>
							<div class="col-sm-9 col-xs-8">
								<select name="parent_id" id="parent_id" class="form-control">
									<option value="0" data-module="0"><?php _e('无'); ?></option>
									<?php echo $this->category_option; ?>
								</select>
							</div>
						</div>
					</td>
				</tr>
				<tfoot>
				<tr>
					<td>
						<input type="button" value="<?php _e('保存设置'); ?>" class="btn btn-primary center-block" onclick="ICB.ajax.postForm($('#category_form'));" />
					</td>
				</tr>
				</tfoot>
			</table>
		</div>
		</div>
	</form>
</div>
<<script type="text/javascript">
$(function() {
    <?php if ($this->category) { ?>
	if ($('#thumb_pic_uploader').length){
		var url = G_BASE_URL + '/admin/ajax/upload_category_pic/id-<?php echo $this->category['id']; ?>';
		var fileupload = new FileUploader(
				$('#thumb_pic_uploader'),
				$('#thumb_pic'),
				url,
				{
					'uploadingModalSelector' : '#avatar_uploading_status',
					'showUploadImage': true,
					fileName:'upload_file'
				}
		);
	}
	<?php } ?>

    $('#parent_id').attr('disabled', 'disabled');
    $('#module_id').change(function () {
        var moduleId = $(this).val();
        if (moduleId>0) {
            $('#parent_id').find('option[data-module="'+moduleId+'"]').show();
            $('#parent_id').find('option[data-module!="'+moduleId+'"]').hide();
            $('#parent_id').find('option[data-module="0"]').show();
            $('#parent_id').removeAttr('disabled');
        } else {
            $('#parent_id').attr('disabled', 'disabled');
        }
        var moduleToken = $(this).find('option[value="'+moduleId+'"]').attr('data-token');
        if (! moduleToken) {
            moduleToken = 'index';
        }
        $('#js_url_module_name').text(moduleToken);
    });
    $('#module_id').trigger('change');
    /*
	// 选择分类后， 将对应模块选定。 如果是根分类， 需要选择所属的模块
	$('#parent_id').change(function () {
		var moduleId = $(this).find('option:selected').attr('data-module');
		$('#module_id').val(moduleId);
		if (moduleId!='0') {
            $('#module_id').find('option[value="'+moduleId+'"]').attr('selected', 'selected');
			$('#module_id').attr('readonly', 'readonly');
		} else {
			$('#module_id').removeAttr('readonly');
		}
        var moduleToken = $('#module_id').find('option[value="'+moduleId+'"]').attr('data-token');
        if (! moduleToken) {
            moduleToken = 'index';
        }
        $('#js_url_module_name').text(moduleToken);
    });
    $('#parent_id').trigger('change');
    */
});
</script>
<?php View::output('admin/global/footer.php'); ?>
