<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
	<form action="admin/ajax/save_topic/" id="settings_form" method="post" onsubmit="return false">
	<input type="hidden" name="topic_id" value="<?php echo $this->topic_info['topic_id']; ?>" />
	<div class="mod">
		<div class="mod-head">
			<h3>
				<?php if ($this->topic_info) { ?>
				<span class="pull-left"><?php _e('话题编辑'); ?></span>
				<?php } else { ?>
				<ul class="nav nav-tabs">
					<li><a href="admin/topic/list/"><?php _e('话题管理'); ?></a></li>
					<li><a href="admin/topic/parent/"><?php _e('根话题'); ?></a></li>
					<li class="active"><a href="admin/topic/edit/"><?php _e('新建话题'); ?></a></li>
				</ul>
				<?php } ?>
			</h3>
		</div>

		<div class="tab-content mod-content">
			<table class="table table-striped">
				<?php if ($this->topic_info) { ?>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-2 col-xs-3 control-label"><?php _e('缩略图'); ?>:</span>
							<div class="col-sm-9 col-xs-8">
								<a id="topic_pic_uploader"><img src="<?php echo getMudulePicUrlBySize('topic', 'mid', $this->topic_info['topic_pic']); ?>" alt="" id="topic_pic" class="img-polaroid" name="topic_pic" /></a>
							</div>
						</div>
					</td>
				</tr>
				<?php } ?>

				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-2 col-xs-3 control-label"><?php _e('话题标题'); ?>:</span>
							<div class="col-sm-9 col-xs-8">
								<input type="text" name="topic_title" value="<?php echo $this->topic_info['topic_title']; ?>" class="form-control" />
							</div>
						</div>
					</td>
				</tr>

				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-2 col-xs-3 control-label"><?php _e('话题别名'); ?>:</span>
							<div class="col-sm-9 col-xs-8">
								<input type="text" name="url_token" value="<?php echo $this->topic_info['url_token']; ?>" class="form-control" />
							</div>
						</div>
					</td>
				</tr>

				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-2 col-xs-3 control-label"><?php _e('话题描述'); ?>:</span>
							<div class="col-sm-9 col-xs-8">
								<textarea class="form-control" name="topic_description"  ><?php echo $this->topic_info['topic_description']; ?></textarea>
							</div>
						</div>
					</td>
				</tr>

				<?php if ($this->topic_info) { ?>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-2 col-xs-3 control-label"><?php _e('锁定状态'); ?>:</span>
							<div class="col-sm-9 col-xs-8">
								<div class="btn-group mod-btn">
									<label type="button" class="btn mod-btn-color">
										<input type="radio" name="topic_lock" value="1"<?php if ($this->topic_info['topic_lock']) { ?> checked="checked"<?php } ?> /> <?php _e('是'); ?>
									</label>

									<label type="button" class="btn mod-btn-color">
										<input type="radio" name="topic_lock" value="0"<?php if (!$this->topic_info['topic_lock']) { ?> checked="checked"<?php } ?> /> <?php _e('否'); ?>
									</label>
								</div>
							</div>
						</div>
					</td>
				</tr>
				<?php } ?>

				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-2 col-xs-3 control-label"><?php _e('根话题'); ?>:</span>
							<div class="col-sm-9 col-xs-8">
								<div class="btn-group mod-btn">
									<label type="button" class="btn mod-btn-color">
										<input type="radio" name="is_parent" value="1"<?php if ($this->topic_info['is_parent']) { ?> checked="checked"<?php } ?> /> <?php _e('是'); ?>
									</label>

									<label type="button" class="btn mod-btn-color">
										<input type="radio" name="is_parent" value="0"<?php if (!$this->topic_info['is_parent']) { ?> checked="checked"<?php } ?> /> <?php _e('否'); ?>
									</label>
								</div>
							</div>
						</div>
					</td>
				</tr>

				<tr class="parent_topic_tr<?php if ($this->topic_info['is_parent']) { ?> hide<?php } ?>">
					<td>
						<div class="form-group">
							<span class="col-sm-2 col-xs-3 control-label"><?php _e('选择根话题'); ?>:</span>
							<div class="col-sm-9 col-xs-8">
								<select id="parent_ids" class="" name="parent_ids[]" multiple="multiple">
								<?php if ($this->parent_topics) { foreach ($this->parent_topics AS $val) { ?>
								<option value="<?php echo $val['topic_id']; ?>"<?php
								if (in_array($val['topic_id'], $this->topic_info['parent_ids'])) {
								    ?> selected="selected"<?php
                                } ?>><?php echo $val['topic_title']; ?></option>
								<?php } } ?>
								</select>
							</div>
						</div>
					</td>
				</tr>
                <!--
				<tr class="parent_topic_tr"<?php if ($this->topic_info['is_parent']) { ?> style="display: none;"<?php } ?>>
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('选择根话题'); ?>:</span>
							<div class="col-sm-4 col-xs-8">
								<select id="parent_topic_select" class="collapse" name="parent_id">
								<option value="0"> --- </option>
								<?php if ($this->parent_topics) { foreach ($this->parent_topics AS $val) { ?>
								<option value="<?php echo $val['topic_id']; ?>"<?php if ($val['topic_id'] == $this->topic_info['parent_id']) { ?> selected="selected"<?php } ?>><?php echo $val['topic_title']; ?></option>
								<?php } } ?>
								</select>

								<div class="icb-publish-title">
									<div class="dropdown">
										<div class="dropdown-toggle">
											<input class="icb-hide-txt form-control" id="icb-selected-tag-show" placeholder="<?php _e(根话题); ?>" />
											<a class="triangle"><i class="icon icon-down"></i></a>
										</div>
										<div class="icb-dropdown">
											<ul class="icb-dropdown-list">
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
					</td>
				</tr>
                -->
				<tfoot>
				<tr>
					<td class="text-center">
						<input type="button" value="<?php _e('保存设置'); ?>" class="btn btn-primary" onclick="ICB.ajax.postForm($('#settings_form'));" />
						&nbsp;
						<a href="admin/topic/list/" class="btn btn-default"><?php _e('返回列表'); ?></a>
					</td>
				</tr>
				</tfoot>
			</table>
		</div>
	</div>
	</form>
</div>

<script type="text/javascript">
	$(document).ready(function () {
		<?php if ($this->topic_info) { ?>
		if ($('#topic_pic_uploader').length)
		{
			var fileupload = new FileUploader($('#topic_pic_uploader'), $('#topic_pic'), G_BASE_URL + '/topic/ajax/upload_topic_pic/topic_id-<?php echo $this->topic_info['topic_id']; ?>', {'uploadingModalSelector' : '#avatar_uploading_status', 'showUploadImage': true, fileName:'upload_file'});
		}
		<?php } ?>

		// 根话题选择
		//$.each($('#parent_topic_select option'), function (i, e)
		//{
		//	$('.icb-publish-title .icb-dropdown-list').append('<li data-value="' + $(this).attr('value') + '"><a href="javascript:;" >' + $(this).html() + '</a></li>');
		//});

		// 父级分类选择
		$('#parent_ids').multiselect({
			nonSelectedText: '<?php _e('选择父级');?>',
			maxHeight: 200,
			});
		/*
		// 初始化给input赋值
		if ($('#parent_topic_select option[selected=selected]').text())
		{
			$('.icb-publish-title #icb-selected-tag-show').val($('#parent_topic_select option[selected=selected]').text());
		}
		else
		{
			$('.icb-publish-title #icb-selected-tag-show').val($('#parent_topic_select option').eq(0).text());
		}

		$('.icb-publish-title .icb-dropdown-list li').click(function()
		{
			$('#parent_topic_select').val($(this).data('value'));
			$('#icb-selected-tag-show').val($(this).text());
			$('.icb-publish-title .dropdown').removeClass('open');
			$('.icb-publish-title .icb-dropdown-list li').removeClass('collapse');
			//AWS.ajax_request(G_BASE_URL + '/topic/ajax/set_parent_id/', 'topic_id=<?php echo $this->topic_info['topic_id']; ?>&parent_id=' + $(this).data('value'));
		});

		$('.icb-publish-title .triangle').click(function()
		{
			if ($(this).parents('.dropdown').hasClass('open'))
			{
				$(this).parents('.dropdown').removeClass('open');
			}
			else
			{
				$(this).parents('.dropdown').addClass('open');
			}
		});

		$('.icb-publish-title #icb-selected-tag-show').bind({
			focus : function()
			{
				$(this).parents('.dropdown').addClass('open');
			},

			keyup : function()
			{
				var value = $(this).val();
				if (value != '')
				{
					$.each($('.icb-publish-title .icb-dropdown-list li'), function (i, e)
					{
						if ($(this).text().match(value) == null)
						{
							$(this).addClass('collapse');
						}
						else
						{
							$(this).removeClass('collapse');
						}
					});
				}
				else
				{
					$('.icb-publish-title .icb-dropdown-list li').removeClass('collapse');
				}
			}
		});

		$(document).click(function(e)
		{
			var target = $(e.target);
			if (target.parents('.icb-publish-title').length)
			{
				//return false;
			}
			else
			{
				var _this = $('#icb-selected-tag-show');
				if (_this.val() == '')
				{
					$('#parent_topic_select').val('');
					$('.icb-publish-title .icb-dropdown-list li').eq(0).click();
					$('.icb-publish-title .dropdown').removeClass('open');
				}
				else
				{
					if ($('#parent_topic_select option[selected=selected]').text() != '')
					{
						var val = $('#parent_topic_select option[selected=selected]').text();
					}
					else
					{
						var val = ' --- ';
					}

					if (_this.val() != val)
					{
						if ($('.icb-publish-title .icb-dropdown-list li:not(".collapse")').eq(0).text().match(_this.val()))
						{
							$('.icb-publish-title .icb-dropdown-list li:not(".collapse")').eq(0).click();
						}
						else
						{
							var flag = false;
							$.each($('.icb-publish-title .icb-dropdown-list li:not(".collapse")'), function (i, e)
							{
								if ($(this).text().match(_this.val()))
								{
									flag = true;
									$(this).click();
								}
							});

							if (flag == false)
							{
								$('.icb-publish-title #icb-selected-tag-show').val($('#parent_topic_select option[selected=selected]').text());
							}
						}

						$('.icb-publish-title .dropdown').removeClass('open');
					}
					else
					{
						$('.icb-publish-title .dropdown').removeClass('open');
					}
				}

				$(this).parents('.dropdown').removeClass('open');
			}
		});
		// */

		$('input[name="is_parent"]').on('ifClicked', function() {
			this.value == 0 ? $('.parent_topic_tr').show() : $('.parent_topic_tr').hide();
		});
	});
</script>

<?php View::output('admin/global/footer.php'); ?>