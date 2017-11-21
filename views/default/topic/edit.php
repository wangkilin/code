<?php View::output('global/header.php'); ?>

<div class="icb-container">
	<div class="container icb-topic-edit">
		<div class="row">
			<div class="icb-content-wrap clearfix">
				<div class="col-sm-9 icb-main-content">
					<div class="icb-mod">
						<div class="mod-head common-head">
							<h2><a class="btn btn-mini pull-right" href="topic/<?php echo $this->topic_info['url_token']; ?>"><?php _e('返回话题'); ?> »</a><?php _e('话题编辑'); ?></h2>
						</div>
					</div>
					<div class="icb-mod icb-mod-topic-edit-box">
						<div class="mod-head clearfix">
							<a href="javascript:;" class="img" style="background:url('<?php echo getMudulePicUrlBySize('topic', 'mid', $this->topic_info['topic_pic']); ?>') no-repeat;" <?php if ($this->user_info['permission']['is_administortar'] OR $this->user_info['permission']['is_moderator'] OR ($this->topic_info['topic_lock'] == 0 && $this->user_info['permission']['edit_topic'])) { ?> id="topic_pic_uploader"<?php } ?>>
								<span class="mask"></span>
								<b>修改</b>
							</a>
							<h3><?php echo $this->topic_info['topic_title']; ?></h3>
							<span class="pull-left"><?php _e('相关话题'); ?> : </span>
							<div class="icb-article-title-box pull-left" data-type="topic" data-id="<?php echo $this->topic_info['topic_id']; ?>">
								<div class="tag-queue-box clearfix">
									<?php if ($this->related_topics) { ?>
									<?php foreach($this->related_topics as $key => $val){ ?>
									<span class="article-tag" data-id="<?php echo $val['topic_id']?>"><a class="text" href="topic/<?php echo $val['url_token']; ?>"><?php echo $val['topic_title']; ?></a></span>
									<?php } ?>
				                    <?php } ?>

				                    <?php if ($this->user_info['permission']['is_administortar'] OR $this->user_info['permission']['is_moderator'] OR ($this->topic_info['topic_lock'] == 0 && $this->user_info['permission']['edit_topic'])) { ?>
									<span class="icb-edit-topic icon-inverse"><i class="icon icon-edit"></i><?php _e('添加话题'); ?></span>
									<?php } ?>
								</div>
							</div>
						</div>
						<div class="mod-body">
							<div class="icb-mod icb-editor-box">
								<div class="mod-head">
									<form id="topic_form" action="topic/ajax/edit_topic/" method="post" onsubmit="return false">
											<div class="wmd-panel">
												<textarea class="article-content form-control autosize editor" id="article-content" rows="15" name="topic_description">&nbsp;&nbsp;<?php echo $this->topic_info['topic_description']; ?></textarea>
									        </div>
										<input type="hidden" name="topic_id" value="<?php echo $this->topic_info['topic_id']; ?>" />
									</form>
								</div>
							</div>
						</div>
						<div class="mod-footer">
							<a class="btn btn-normal btn-success pull-right" href="javascript:;" onclick="AWS.ajax_post($('#topic_form'));"><?php _e('确定'); ?></a>
						</div>
					</div>
				</div>
				<!-- 侧边栏 -->
				<div class="col-sm-3 icb-side-bar">
					<div class="icb-mod topic-edit-help">
						<div class="mod-head">
							<h3><?php _e('话题编辑指南'); ?></h3>
						</div>
						<div class="mod-body">
							 <p><b>• <?php _e('相关话题'); ?>:</b> <?php _e('添加和该话题词义相近或者相似的话题'); ?></p>
							 <p><b>• <?php _e('话题内容'); ?>:</b> <?php _e('话题是 WeCenter 系统用来组织内容的一个节点, 也是根据内容提炼出来的知识点. 话题是构成 WeCenter 系统讨论内容的基本单元, 是对各种事物、现象等概念的解释. 如果您针对本话题的定义非常清晰, 请帮忙完善该话题并帮助社区用户对该话题的理解, 谢谢您的分享和贡献'); ?></p>
						</div>
					</div>
				</div>
				<!-- end 侧边栏 -->
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function()
	{
		var editor = CKEDITOR.replace( 'article-content' );

		if ($('#topic_pic_uploader').length)
		{
			var fileUpload = new FileUpload('avatar', $('#topic_pic_uploader'), $('#topic_pic_uploader'), G_BASE_URL + '/topic/ajax/upload_topic_pic/topic_id-<?php echo $this->topic_info['topic_id']; ?>', {'loading_status' : '#uploading_status'});
		}
	});
</script>

<?php View::output('global/footer.php'); ?>