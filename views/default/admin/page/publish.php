<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
	<form action="admin/ajax/<?php if ($this->page_info['id']) { ?>edit<?php } else { ?>add<?php } ?>_page/" id="settings_form" method="post">
	<?php if ($this->page_info['id']) { ?>
	<input type="hidden" name="page_id" value="<?php echo $this->page_info['id']; ?>" />
	<?php } ?>
	<div class="mod">
		<div class="mod-head">
			<h3>
				<?php View::output('admin/page/nav_inc.php');?>
			</h3>
		</div>
		<div class="tab-content mod-content">
			<table class="table table-striped" id="list">
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-2 col-xs-3 control-label"><?php _e('页面 URL'); ?>:</span>
							<div class="col-sm-10 col-xs-9">
	                            <span class="col-sm-1 col-xs-3 mod-text-inline">/page/</span>
	                            <div class="col-sm-11 col-xs-9 pull-right nopadding">
									<input type="text" name="url_token" class="form-control" value="<?php echo $this->page_info['url_token']; ?>" />
								</div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-2 col-xs-3 control-label"><?php _e('页面标题'); ?>:</span>
							<div class="col-sm-10 col-xs-9">
								<input type="text" name="title" class="form-control" value="<?php echo $this->page_info['title']; ?>" />
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-2 col-xs-3 control-label"><?php _e('页面描述'); ?>:</span>
							<div class="col-sm-10 col-xs-9">
								<input type="text" name="description" class="form-control" value="<?php echo $this->page_info['description']; ?>" />
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-2 col-xs-3 control-label"><?php _e('页面关键词'); ?>:</span>
							<div class="col-sm-10 col-xs-9">
								<input type="text" name="keywords" class="form-control" value="<?php echo $this->page_info['keywords']; ?>" />
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-2 col-xs-3 control-label"><?php _e('页面内容'); ?>:</span>
							<div class="col-sm-10 col-xs-9 icb-mod icb-editor-box">
									<div class="mod-head">
										<div class="wmd-panel">
								<textarea id="article-content" class="form-control" name="contents"  rows="15"><?php echo $this->page_info['contents']; ?></textarea>
								</div>
								</div>


									<div class="mod-body">
										<p class="text-color-999"><span class="pull-right" id="question_detail_message">&nbsp;</span></p>
										<?php if (get_setting('upload_enable') == 'Y' AND get_setting('advanced_editor_enable' == 'Y')) { ?>
										<div class="icb-upload-wrap">
											<a class="btn btn-default">上传附件</a>
											<div class="upload-container"></div>
											<span class="text-color-999 icb-upload-tips hidden-xs"><?php _e('允许的附件文件类型'); ?>: <?php echo get_setting('allowed_upload_types'); ?></span>
										</div>
										<?php } ?>
									</div>
							</div>
						</div>
					</td>
				</tr>
				<tfoot>
				<tr>
					<td>
						<input type="button" value="<?php _e('保存设置'); ?>" class="btn btn-primary center-block" onclick="AWS.ajax_post($('#settings_form'));" />
					</td>
				</tr>
				</tfoot>
			</table>

            <?php View::output('admin/page/search_inc.php');?>
		</div>
	</div>
	</form>
</div>
<script type="text/javascript">
$(function () {
	// 初始化编辑器
	var editor = CKEDITOR.replace( 'article-content' );

	// 绑定上传
	if ($('.icb-upload-wrap').length) {
		if ("<?php echo get_setting('advanced_editor_enable');?>" == 'Y') {
			var editorSetting = {'editor' : editor };
		} else {
			var editorSetting = {'editor' : $('.article-content')};
		}
		var fileupload = new FileUpload(
		    	'file',
		    	'.icb-editor-box .icb-upload-wrap .btn',
		    	'.icb-editor-box .icb-upload-wrap .upload-container',
		    	G_BASE_URL + '/publish/ajax/attach_upload/id-page',
		    	editorSetting
		    );
	}


    //初始化分类
	if ($('#parent_id').length) {
		var dropdownData = '', selectedId = '';
		// 组装下拉列表需要的数据， 获取默认选择
		$.each($('#parent_id option').toArray(), function (i, field) {
			if ($(field).attr('selected') == 'selected') {
				selectedId = $(this).attr('value');
				$('#parent_id').val(selectedId);// 设置选定的父级分类id
			}
			if (i > 0) {
				if (i > 1) {
					dropdownData += ',';
				}

				dropdownData += "{'title':'" + $(field).text() + "', 'id':'" + $(field).val() + "'}";
			}
		});
		// 实现下拉列表数据内容
		ICB.dropdown.setList('.icb-item-title .dropdown', eval('[' + dropdownData + ']'), selectedId);
		// 监听下拉列表点击事件
		$('.icb-item-title .dropdown li a').click(function() {
			$('#parent_id').val($(this).attr('data-value'));
		});
		// 设置默认选中值内容
		$('.icb-item-title .dropdown .icb-dropdown-list li a[data-value="' +$('#parent_id').val()+'"]').click();

	}
	// 添加更多标签
	ICB.domEvents.editArticleTagButtonClick('#icb-article-tag-box .icb-edit-tag');
});
</script>
<?php View::output('admin/global/footer.php'); ?>