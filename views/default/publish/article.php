<?php View::output('global/header.php'); ?>

<div class="icb-container">
	<div class="container icb-publish icb-publish-article">
		<div class="row">
			<div class="icb-content-wrap clearfix">
				<div class="col-sm-12 col-md-9 icb-main-content">
					<!-- tab 切换 -->
					<ul class="nav nav-tabs icb-nav-tabs right">
						<li class="nav-tabs-title hidden-xs"><i class="icon icon-ask"></i> <?php _e('发起'); ?></li>
						<li class="active"><a href="publish/article/"><?php _e('文章'); ?></a></li>
						<?php if ($this->user_info['permission']['publish_question']) { ?>
						<li><a href="publish/"><?php _e('问题'); ?></a></li>
						<?php } ?>
					</ul>
					<!-- end tab 切换 -->
					<form action="publish/ajax/<?php if ($this->article_info['id']) { ?>modify<?php } else { ?>publish<?php } ?>_article/" method="post" id="question_form" onsubmit="return false;">
						<input type="hidden" name="post_hash" value="<?php echo new_post_hash(); ?>" />
						<input type="hidden" name="attach_access_key" value="<?php echo $this->attach_access_key; ?>" />
						<input type="hidden" name="article_id" id="article_id" value="<?php echo $this->article_info['id']; ?>" />
						<?php if ($this->article_category_list) { ?>
							<select name="category_id" class="collapse" id="category_id">
								<option value="0">- <?php _e('请选择分类'); ?> -</option>
								<?php echo $this->article_category_list; ?>
							</select>
							<?php } ?>
						<div class="icb-mod icb-mod-publish">
							<div class="mod-body">
								<h3><?php _e('文章标题'); ?>:</h3>
								<!-- 文章标题 -->
								<div class="icb-publish-title<?php if (!$this->article_category_list) { ?> active<?php } ?>">
									<input type="text" name="title" value="<?php echo $this->article_info['title']; ?>" class="form-control" />
									<?php if ($this->article_category_list) { ?>
									<div class="icb-dropdown icb-question-dropdown">
										<i class="icb-icon i-dropdown-triangle active"></i>
										<p class="title"><?php _e('没有找到相关结果'); ?></p>
										<ul class="icb-question-dropdown-list"></ul>
									</div>
									<div class="dropdown">
										<div class="dropdown-toggle" data-toggle="dropdown">
											<span id="icb-selected-tag-show"><?php _e('选择分类'); ?></span>
											<a href="javascript:;"><i class="icon icon-down"></i></a></a>
										</div>
									</div>
									<?php } ?>
								</div>
								<!-- end 文章标题 -->

								<h3><?php _e('文章内容'); ?>:</h3>
								<div class="icb-mod icb-editor-box">
									<div class="mod-head">
										<div class="wmd-panel">
											<textarea class="article-content form-control autosize editor" id="article-content" rows="15" name="message"><?php echo $this->article_info['message']; ?></textarea>
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

								<h3><?php _e('添加话题'); ?>:</h3>
								<div class="icb-article-title-box" data-type="publish">
									<div class="tag-queue-box clearfix">
										<?php if ($_GET['topic_title']) { ?>
										<span class="article-tag">
											<a class="text"><?php echo urldecode($_GET['topic_title']); ?></a>
											<a class="close" onclick="$(this).parents('.article-tag').remove();">
												<i class="icon icon-delete"></i>
											</a>
											<button class="close icb-close" onclick="">×</button></span><input type="hidden" value="<?php echo urldecode($_GET['topic_title']); ?>" name="topics[]" />
										</span>
										<?php } ?>

										<?php if ($_POST['topics']) { ?>
										<?php foreach ($_POST['topics'] AS $key => $val) { ?>
										<span class="article-tag">
											<a class="text"><?php echo $val; ?></a>
											<input type="hidden" value="<?php echo $val; ?>" name="topics[]" />
										</span>
										<?php } ?>
										<?php } else if ($this->article_topics) { ?>
										<?php foreach ($this->article_topics AS $key => $val) { ?>
										<span class="article-tag">
											<a class="text"><?php echo $val['topic_title']; ?></a>
											<input type="hidden" value="<?php echo $val['topic_title']; ?>" name="topics[]" />
										</span>
										<?php } ?>
										<?php } ?>

										<span class="icb-edit-topic icon-inverse"><i class="icon icon-edit"></i> <?php _e('编辑话题'); ?></span>
									</div>
								</div>

								<?php if ($this->recent_topics) { ?>
								<h3><?php _e('最近话题'); ?>:</h3>
								<div class="icb-article-tag-box">
									<div class="topic-bar clearfix">
										<?php foreach($this->recent_topics as $key => $val) { ?>
										<span class="article-tag" onclick="$('#icb-tag-category-keyword').val('<?php echo $val; ?>');$('.add').click();$(this).hide();">
											<a class="text">
												<?php echo $val; ?>
											</a>
										</span>
										<?php } ?>
									</div>
								</div>
								<?php } ?>

								<?php if ($this->human_valid) { ?>
								<div class="icb-auth-img clearfix">
									<em class="auth-img pull-right"><img src="" onclick="this.src = G_BASE_URL + '/account/captcha/' + Math.floor(Math.random() * 10000);" id="captcha"  /></em>
									<input class="pull-right form-control" type="text" name="seccode_verify" placeholder="验证码" />
								</div>
								<?php } ?>
							</div>
							<div class="mod-footer clearfix">
								<?php if (get_setting('integral_system_enabled') == 'Y') { ?><a href="integral/rule/" target="_blank">[<?php _e('积分规则'); ?>]</a><?php } ?>
								<span class="icb-anonymity">
									<?php if ($this->article_info['id'] AND ($this->user_info['permission']['is_administortar'] OR $this->user_info['permission']['is_moderator'])) { ?>
									<label><input type="checkbox" class="pull-left" value="1" name="do_delete" />
										<?php _e('删除文章'); ?></label>
									<?php } ?>
								</span>
								<a class="btn btn-large btn-success btn-publish-submit" id="publish_submit" onclick="AWS.ajax_post($('#question_form')); return false;"><?php _e('确认发起'); ?></a>
							</div>
						</div>
					</form>
				</div>
				<!-- 侧边栏 -->
				<div class="col-sm-12 col-md-3 icb-side-bar hidden-xs">
					<!-- 文章发起指南 -->
					<div class="icb-mod publish-help">
						<div class="mod-head">
							<h3><?php _e('文章发起指南'); ?></h3>
						</div>
						<div class="mod-body">
							<p><b>• <?php _e('文章标题'); ?>:</b> <?php _e('请用准确的语言描述您发布的文章思想'); ?></p>
							<p><b>• <?php _e('文章补充'); ?>:</b> <?php _e('详细补充您的文章内容, 并提供一些相关的素材以供参与者更多的了解您所要文章的主题思想'); ?></p>
							<p><b>• <?php _e('选择话题'); ?>:</b> <?php _e('选择一个或者多个合适的话题, 让您发布的文章得到更多有相同兴趣的人参与. 所有人可以在您发布文章之后添加和编辑该文章所属的话题'); ?></p>
						</div>
					</div>
					<!-- end 文章发起指南 -->
				</div>
				<!-- end 侧边栏 -->
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	var ATTACH_ACCESS_KEY = '<?php echo $this->attach_access_key; ?>';
	var CATEGORY_ID = <?php echo $this->article_info['category_id']; ?>;
	var PUBLISH_TYPE = 'article';
</script>

<?php View::output('global/footer.php'); ?>