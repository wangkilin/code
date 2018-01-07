<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
	<div class="mod">
		<div class="mod-head">
			<h3>
				<?php if ($this->tagInfo) { ?>
				<span class="pull-left"><?php _e('标签编辑'); ?></span>
				<?php } else {
				View::output('admin/tag/nav.php');
				} ?>
			</h3>
		</div>

		<div class="tab-content mod-content">
		  <div class="tab-pane  active" id="category">
		  <form action="admin/ajax/category_save/" id="settings_form" method="post" onsubmit="return false">
	       <input type="hidden" name="id" value="<?php echo $this->categoryInfo['id']; ?>" />

			<table class="table table-striped">
				<?php if ($this->categoryInfo) { ?>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('缩略图'); ?>:</span>
							<div class="col-sm-5 col-xs-8">
								<a id="topic_pic_uploader"><img src="<?php echo getModulePicUrlBySize('topic', 'mid', $this->tagInfo['topic_pic']); ?>" alt="" id="topic_pic" class="img-polaroid" name="pic" /></a>
							</div>
						</div>
					</td>
				</tr>
				<?php } ?>

				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('分类标题'); ?>:</span>
							<div class="col-sm-5 col-xs-8">
								<input type="text" name="title" value="<?php echo $this->categoryInfo['title']; ?>" class="form-control" />
							</div>
						</div>
					</td>
				</tr>

				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('分类别名'); ?>:</span>
							<div class="col-sm-5 col-xs-8">
								<span class="col-sm-1 mod-text-inline">/index/category-</span>
								<div class="col-xs-11 col-sm-8 pull-right nopadding">
									<input name="url_token" class="form-control" value="<?php echo $this->categoryInfo['url_token'];?>" type="text">
								</div>
							</div>
						</div>
					</td>
				</tr>

				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('分类描述'); ?>:</span>
							<div class="col-sm-5 col-xs-8">
								<textarea class="form-control" name="description"  ><?php echo $this->categoryInfo['description']; ?></textarea>
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

	       </form>
           </div>
           <?php View::output('admin/tag/search.php');?>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function () {
		<?php if ($this->tagInfo) { ?>
		if ($('#topic_pic_uploader').length)
		{
			var fileupload = new FileUpload('avatar', $('#topic_pic_uploader'), $('#topic_pic'), G_BASE_URL + '/topic/ajax/upload_topic_pic/topic_id-<?php echo $this->tagInfo['topic_id']; ?>', 'topic_pic');
		}
		<?php } ?>

		// 根话题选择
		$.each($('#parent_topic_select option'), function (i, e)
		{
			$('.icb-publish-title .icb-dropdown-list').append('<li data-value="' + $(this).attr('value') + '"><a href="javascript:;" >' + $(this).html() + '</a></li>');
		});

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
			//AWS.ajax_request(G_BASE_URL + '/topic/ajax/set_parent_id/', 'topic_id=<?php echo $this->tagInfo['topic_id']; ?>&parent_id=' + $(this).data('value'));
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

		$('input[name="is_parent"]').on('ifClicked', function()
		{
			if (this.value == 0)
			{
				$('.parent_topic_tr').removeAttr('style');
			}
			else
			{
				$('.parent_topic_tr').css('display', 'none');
			}
		});
	});
</script>

<?php View::output('admin/global/footer.php'); ?>