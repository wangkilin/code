<?php View::output('global/header.php'); ?>

<div class="icb-container">
	<div class="container icb-topic-edit">
		<div class="row">
			<div class="icb-content-wrap clearfix">
				<div class="col-sm-9 icb-main-content">
					<div class="icb-mod">
						<div class="mod-head common-head">
							<h2><span class="pull-right"><a class="text-color-999" href="topic/<?php echo $this->topic_info['url_token']; ?>"><?php _e('返回话题'); ?> »</a></span><?php _e('管理话题'); ?> - <?php echo $this->topic_info['topic_title']; ?></h2>
						</div>
					</div>
					<div class="icb-mod-topic-manage">
						<dl class="dl-horizontal">
							<form class="form-inline" action="topic/ajax/save_url_token/" method="post" id="url_token_form">
								<input type="hidden" name="topic_id" value="<?php echo $this->topic_info['topic_id']; ?>" />
								<dt><?php _e('话题别名'); ?>:</dt>
								<dd>
									<a class="btn btn-normal btn-success pull-right" href="javascript:;" onclick="AWS.ajax_post($('#url_token_form'));" /><?php _e('保存'); ?></a>
									<script type="text/javascript">document.write(G_BASE_URL);</script>/topic/
									<input type="text" class="form-control" name="url_token" value="<?php echo $this->topic_info['url_token']; ?>" />
								</dd>
							</form>
						</dl>
						<dl class="dl-horizontal">
							<form action="topic/ajax/save_seo_title/" method="post" id="seo_title_form">
								<input type="hidden" name="topic_id" value="<?php echo $this->topic_info['topic_id']; ?>" />
								<dt><?php _e('页面标题'); ?>:</dt>
								<dd>
									<a class="btn btn-normal btn-success pull-right" href="javascript:;" onclick="AWS.ajax_post($('#seo_title_form'));"><?php _e('保存'); ?></a>
									<input type="text" class="form-control" name="seo_title" value="<?php echo $this->topic_info['seo_title']; ?>" />
								</dd>
							</form>
						</dl>
						<dl class="dl-horizontal">
							<form action="topic/ajax/merge_topic/" method="post" id="merge_topic_form">
								<input type="hidden" name="target_id" value="<?php echo $this->topic_info['topic_id']; ?>" />
								<dt><?php _e('话题合并'); ?>:</dt>
								<dd>
									<a class="btn btn-normal btn-success pull-right" onclick="AWS.ajax_post($('#merge_topic_form'));"><?php _e('合并'); ?></a>
									<input type="text" class="form-control" name="topic_title" placeholder="<?php _e('在此输入要与该话题合并的话题'); ?>..."/>
								</dd>
							</form>
						</dl>
						<?php if ($this->merged_topics_info) { ?>
						<dl class="dl-horizontal">
							<dt><?php _e('被合并的话题'); ?>:</dt>
							<dd>
								<div class="icb-article-title-box active">
									<div class="topic-bar clearfix">
										<?php foreach ($this->merged_topics_info AS $key => $val) { ?>
										<span class="article-tag">
											<a href="" class="text"><?php echo $val['topic_title']; ?></a>
											<a class="close" onclick="AWS.ajax_request(G_BASE_URL + '/topic/ajax/remove_merge_topic/', 'source_id=<?php echo $val['topic_id']; ?>&target_id=<?php echo $this->topic_info['topic_id']; ?>');"><i class="icon icon-delete"></i></a>
										</span>
										<?php } ?>
									</div>
								</div>
							</dd>
						</dl>
						<?php } ?>

						<?php if ($this->parent_topics) { ?>
						<dl class="dl-horizontal">
							<dt><?php _e('根话题'); ?>:</dt>
							<dd>
								<select id="parent_topic_select" class="collapse" name="parent_id">
								<option value=""> --- </option>
								<?php foreach ($this->parent_topics AS $key => $val) { ?>
								<option value="<?php echo $val['topic_id']; ?>"<?php if ($val['topic_id'] == $this->topic_info['parent_id']) { ?> selected="selected"<?php } ?>><?php echo $val['topic_title']; ?></option>
								<?php } ?>
								</select>

								<div class="icb-publish-title">
									<div class="dropdown">
										<div class="dropdown-toggle">
											<input class="icb-hide-txt" id="icb-selected-tag-show" placeholder="<?php _e(根话题); ?>" />
											<a class="triangle"><i class="icon icon-down"></i></a>
										</div>
										<div class="icb-dropdown">
											<i class="i-dropdown-triangle"></i>
											<ul class="icb-dropdown-list">
											</ul>
										</div>
									</div>
								</div>

							</dd>
						</dl>
						<?php } ?>
					</div>
				</div>

				<!-- 侧边栏 -->
				<div class="col-sm-3 icb-side-bar">
					<div class="icb-mod topic-edit-help">
						<div class="mod-head">
							<h3><?php _e('管理话题指南'); ?></h3>
						</div>
						<div class="mod-body">
							<p><b>• <?php _e('话题别名'); ?>:</b> <?php _e('如果该话题还有其他的表达方式, 您可以为其创建别名以便其他人能更好的找到该话题'); ?></p>
							<p><b>• <?php _e('话题合并'); ?>:</b> <?php _e('如果该话题跟另一个话题意义相近, 您可以将此话题合并至其他话题'); ?></p>
						</div>
						<div class="mod-footer">
							<?php if ($this->user_info['permission']['is_administortar'] OR $this->user_info['permission']['is_moderator']) { ?>
							<a onclick="AWS.ajax_request(G_BASE_URL + '/topic/ajax/lock/', 'topic_id=<?php echo $this->topic_info['topic_id']; ?>');" class="btn btn-success <?php if ($this->topic_info['topic_lock']) { ?>active<?php } ?>"><?php if ($this->topic_info['topic_lock']) { ?><?php _e('解除锁定'); ?><?php } else { ?><?php _e('锁定话题'); ?><?php } ?></a>&nbsp;
							<a onclick="AWS.dialog('confirm', {'message' : '<?php _e('确认删除?'); ?>'}, function(){AWS.ajax_request(G_BASE_URL + '/topic/ajax/remove/', 'topic_id=<?php echo $this->topic_info['topic_id']; ?>');});" class="btn btn-gray"><?php _e('删除话题'); ?></a>
							<?php } ?>
						</div>
					</div>
				</div>
				<!-- end 侧边栏 -->
			</div>
		</div>
	</div>
</div>

<script>
	$(function()
	{
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
			AWS.ajax_request(G_BASE_URL + '/topic/ajax/set_parent_id/', 'topic_id=<?php echo $this->topic_info['topic_id']; ?>&parent_id=' + $(this).data('value'));
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

	});
</script>

<?php View::output('global/footer.php'); ?>