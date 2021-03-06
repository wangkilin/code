<?php View::output('global/header.php'); ?>

<div class="icb-container">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div class="icb-global-tips">
					<?php if ($this->redirect_message) { ?>
					<div class="alert alert-warning fade in">
						<?php foreach ($this->redirect_message AS $key => $message) { ?>
						<?php echo $message; ?>
						<?php } ?>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="row">
			<div class="icb-content-wrap clearfix">
				<div class="col-sm-12 col-md-9 icb-main-content">
					<div class="icb-mod icb-topic-detail-title">
						<div class="mod-body clearfix">
							<img src="<?php echo getModulePicUrlBySize('topic', 'mid', $this->topic_info['topic_pic']); ?>" alt="<?php echo $this->topic_info['topic_title']; ?>" />
							<h2 class="pull-left"><?php echo $this->topic_info['topic_title']; ?> <?php if ($this->topic_info['topic_lock']) { ?><i class="icb-icon i-lock" title="<?php _e('已锁定'); ?>"></i><?php } ?></h2>

							<div class="icb-topic-operate">
							 <?php if ($this->user_info['permission']['is_administortar'] OR $this->user_info['permission']['is_moderator'] OR ($this->topic_info['topic_lock'] == 0 && $this->user_info['permission']['edit_topic']) OR ($this->topic_info['topic_lock'] == 0 && $this->user_info['permission']['manage_topic'])) { ?>

								<?php if ($this->topic_info['topic_lock'] == 0 && $this->user_info['permission']['edit_topic']) { ?>
								<a class="text-color-999" href="topic/edit/<?php echo $this->topic_info['topic_id']; ?>"><i class="icon icon-edit"></i><?php _e('编辑'); ?></a>
								<?php } ?>
								<?php if ($this->topic_info['topic_lock'] == 0 && $this->user_info['permission']['manage_topic']) { ?>
								<a class="text-color-999" href="topic/manage/<?php echo $this->topic_info['topic_id']; ?>"><i class="icon icon-setting"></i><?php _e('管理'); ?></a>
								<?php } ?>

								<?php if ($this->user_info['permission']['is_administortar'] OR $this->user_info['permission']['is_moderator']) { ?>
								<a class="text-color-999" href="javascript:;" onclick="AWS.ajax_request(G_BASE_URL + '/topic/ajax/lock/', 'topic_id=<?php echo $this->topic_info['topic_id']; ?>');"><?php if ($this->topic_info['topic_lock']) { ?><i class="icon icon-unlock"></i><?php _e('解锁'); ?><?php } else { ?><i class="icon icon-lock"></i><?php _e('锁定'); ?><?php } ?></a>

								<a class="text-color-999" href="javascript:;" onclick="AWS.dialog('confirm', {'message' : '<?php _e('确认删除?'); ?>'}, function(){AWS.ajax_request(G_BASE_URL + '/topic/ajax/remove/', 'topic_id=<?php echo $this->topic_info['topic_id']; ?>');});"><i class="icon icon-delete"></i><?php _e('删除'); ?></a>
								<?php } ?>
							<?php } ?>
								<a class="text-color-999 dropdown-toggle" data-toggle="dropdown"><i class="icon icon-share2"></i>分享</a>
								<div aria-labelledby="dropdownMenu" role="menu" class="icb-dropdown shareout pull-right">
									<ul class="icb-dropdown-list">
										<li><a onclick="AWS.User.share_out({webid: 'tsina', content: $(this).parents('.icb-question-detail').find('.markitup-box')});"><i class="icon icon-weibo"></i> 微博</a></li>
										<li><a onclick="AWS.User.share_out({webid: 'qzone', content: $(this).parents('.icb-question-detail')});"><i class="icon icon-qzone"></i> QZONE</a></li>
										<li><a onclick="AWS.User.share_out({webid: 'weixin', content: $(this).parents('.icb-question-detail')});"><i class="icon icon-wechat"></i> 微信</a></li>
									</ul>
								</div>
                            </div>
							<div class="pull-right text-color-999">
								<?php if ($this->user_id) { ?>
								<a href="javascript:;" onclick="AWS.User.follow($(this), 'topic', <?php echo $this->topic_info['topic_id']; ?>);"
								   class="follow btn btn-normal btn-success<?php if ($this->topic_info['isFollowed']) { ?> active<?php } ?>">
								     <span class="hover-hide"><?php if ($this->topic_info['isFollowed']) { ?><?php _e('已关注'); ?><?php } else { ?><?php _e('关注'); ?><?php } ?></span><span class="hover-show"><?php if ($this->topic_info['isFollowed']) { ?><?php _e('取消关注'); ?><?php } else { ?><?php _e('关注'); ?><?php } ?>
								     </span><em>|</em> <b><?php echo $this->topic_info['focus_count']; ?></b>
								</a>
								<?php } ?>
							</div>
						</div>
					</div>

					<div class="icb-mod icb-topic-list-mod">
						<div class="mod-head">
							<div class="tabbable">
								<!-- tab 切换 -->
								<ul class="nav nav-tabs icb-nav-tabs hidden-xs">
									<li class="active"><a href="#all" data-toggle="tab"><?php _e('全部内容'); ?></a></li>
									<li><a href="#best_answers" data-toggle="tab"><?php _e('精华'); ?></a></li>
									<?php if ($this->all_list_bit) { ?>
									<li><a href="#recommend" data-toggle="tab"><?php _e('推荐'); ?></a></li>
									<?php } ?>
									<?php if ($this->all_questions_list_bit) { ?>
									<li><a href="#questions" data-toggle="tab"><?php _e('问题'); ?></a></li>
									<?php } ?>
									<?php if ($this->recommend_list_bit) { ?>
									<li><a href="#articles" data-toggle="tab"><?php _e('文章'); ?></a></li>
									<?php } ?>
									<li><a href="#favorite" id="i_favorite" data-toggle="tab" style="display:none"><?php _e('我的收藏'); ?></a></li>
									<li><a href="#about" id="i_about" data-toggle="tab"><?php _e('关于话题'); ?></a></li>
									<div class="icb-search-bar pull-right hidden-xs">
										<i class="icon icon-search"></i>
										<input type="text" id="question-input" class="search-query form-control" placeholder="搜索...">
										<div class="icb-dropdown">
											<p class="title"><?php _e('没有找到相关结果'); ?></p>
											<ul class="icb-dropdown-list"></ul>
										</div>
									</div>
								</ul>
								<!-- end tab 切换 -->
							</div>
						</div>

						<div class="mod-body">
							<!-- tab 切换内容 -->
							<div class="tab-content">
								<div class="tab-pane active" id="all">
									<!-- 推荐问题 -->
									<?php if ($this->recommend_list) { ?>
									<div class="icb-mod icb-topic-recommend-list">
										<div class="mod-body">
											<div class="icb-common-list" id="c_recommend_list">
												<ul>
													<?php foreach ($this->topic_recommend_list AS $key => $val) { ?>
													<li>
														<?php if ($val['question_id']) { ?>
														<a href="question/<?php echo $val['question_id']; ?>"><?php echo $val['question_content']; ?></a>
														<span class="pull-right text-color-999"><?php _e('% 个回复', $val['comment_count']); ?></span>
														<?php } else { ?>
														<a href="article/<?php echo $val['id']; ?>"><?php echo $val['title']; ?></a>
														<span class="pull-right text-color-999"><?php _e('% 评论', $val['comments']); ?></span>
														<?php } ?>
													</li>
													<?php } ?>
												</ul>
											</div>
										</div>
										<div class="mod-footer">
											<a class="pull-right" id="icb-more-recommend"><?php _e('更多推荐内容'); ?> &gt;</a>
										</div>
									</div>
									<?php } ?>
									<!-- end 推荐问题 -->
									<div class="icb-mod">
										<div class="mod-body">
											<div class="icb-common-list" id="c_all_list">
												<?php echo $this->all_list_bit; ?>
											</div>
										</div>
										<div class="mod-footer">
											<a class="icb-get-more" id="c_all_more" auto-load="false">
												<span><?php _e('更多'); ?>...</span>
											</a>
										</div>
									</div>
								</div>

								<div class="tab-pane" id="best_answers">
									<div class="icb-feed-list" id="c_best_question_list">
										<div class="mod-body">
											<?php echo $this->best_questions_list_bit; ?>
										</div>
										<div class="mod-footer">
											<a class="icb-get-more" id="bp_best_question_more" auto-load="false">
												<span><?php _e('更多'); ?>...</span>
											</a>
										</div>
									</div>
								</div>

								<div class="tab-pane" id="recommend">
									<div class="icb-mod">
										<div class="mod-body">
											<div class="icb-common-list" id="c_recommend_list">
												<?php echo $this->recommend_list_bit; ?>
											</div>
										</div>
										<div class="mod-footer">
											<a class="icb-get-more" id="c_recommend_more" auto-load="false">
												<span><?php _e('更多'); ?>...</span>
											</a>
										</div>
									</div>
								</div>

								<div class="tab-pane" id="questions">
									<div class="icb-mod">
										<div class="mod-body">
											<div class="icb-common-list" id="c_question_list">
												<?php echo $this->all_questions_list_bit; ?>
											</div>
										</div>
										<div class="mod-footer">
											<a class="icb-get-more" id="c_question_more" auto-load="false">
												<span><?php _e('更多'); ?>...</span>
											</a>
										</div>
									</div>
								</div>

								<div class="tab-pane" id="articles">
									<!-- 动态首页&话题精华模块 -->
									<div class="icb-mod">
										<div class="mod-body">
											<div class="icb-common-list" id="c_articles_list">
												<?php echo $this->articles_list_bit; ?>
											</div>
										</div>
										<div class="mod-footer">
											<a class="icb-get-more" id="bp_articles_more" auto-load="false">
												<span><?php _e('更多'); ?>...</span>
											</a>
										</div>
									</div>
									<!-- end 动态首页&话题精华模块 -->
								</div>

								<div class="tab-pane" id="favorite">
									<!-- 动态首页&话题精华模块 -->
									<div class="icb-mod icb-feed-list" id="c_favorite_list"></div>
									<!-- end 动态首页&话题精华模块 -->

									<!-- 加载更多内容 -->
									<a class="icb-get-more" id="bp_favorite_more">
										<span><?php _e('更多'); ?>...</span>
									</a>
									<!-- end 加载更多内容 -->
								</div>

								<div class="tab-pane" id="about">
									<div class="icb-topic-detail-about text-color-666 markitup-box">
										<?php echo $this->topic_info['topic_description']; ?>
									</div>
								</div>
							</div>
							<!-- end tab 切换内容 -->
						</div>
					</div>
				</div>

				<!-- 侧边栏 -->
				<div class="col-sm-12 col-md-3 icb-side-bar hidden-xs">
					<!-- 话题描述 -->
					<div class="icb-mod icb-text-align-justify">
						<div class="mod-head">
							<h3><?php _e('话题描述'); ?></h3>
						</div>
						<div class="mod-body">
							<?php if ($this->topic_info['topic_description']) { ?>
							<p><?php echo cjk_substr(strip_tags($this->topic_info['topic_description']), 0, 128, 'UTF-8', '... &nbsp; <a href="javascript:;" onclick="$(\'#i_about\').click()">查看全部</a>'); ?></p>
							<?php } else if ($this->topic_info['topic_lock'] == 0 && $this->user_info['permission']['edit_topic']) { ?>
							<a href="topic/edit/<?php echo $this->topic_info['topic_id']; ?>" class="icon-inverse" class=><i class="icon icon-edit"></i> <?php _e('添加描述'); ?></a>
							<?php } ?>
						</div>
					</div>
					<!-- end 话题描述 -->

					<?php View::output('topic/related_topics.php'); ?>

					<?php if ($this->parent_topic_info) { ?>
					<div class="icb-mod">
						<div class="mod-head">
							<h3><?php _e('根话题'); ?></h3>
						</div>

						<a class="icb-topic-name" href="topic/<?php echo $this->parent_topic_info['url_token']; ?>" data-id="<?php echo $this->parent_topic_info['topic_id']; ?>"><span><?php echo $this->parent_topic_info['topic_title']; ?></span></a>
					</div>
					<?php } ?>

					<!-- 最佳回复者 -->
					<?php if ($this->best_answer_users) { ?>
					<div class="icb-mod">
						<div class="mod-head">
							<h3><?php _e('最佳回复者'); ?></h3>
						</div>

						<div class="mod-body">
							<?php foreach ($this->best_answer_users AS $key => $val) { ?>
							<dl>
								<dt class="pull-left icb-border-radius-5">
									<a href="user/<?php echo $val['user_info']['url_token']; ?>"><img src="<?php echo get_avatar_url($val['user_info']['uid'], 'mid'); ?>" alt="" /></a>
								</dt>

								<dd class="pull-left">
									<a class="icb-user-name" href="user/<?php echo $val['user_info']['url_token']; ?>" data-id="<?php echo $val['user_info']['uid']; ?>"><?php echo $val['user_info']['user_name']; ?><?php if ($val['user_info']['verified']) { ?><i class="icon-v<?php if ($val['user_info']['verified'] == 'enterprise') { ?> i-ve<?php } ?>" title="<?php if ($val['user_info']['verified'] == 'enterprise') { ?>企业认证<?php } else { ?>个人认证<?php } ?>"></i><?php } ?>
									<?php echo $val['signature']; ?></a>
									<p><?php _e('获得'); ?> <?php _e('%s 次赞同', '<b>' . $val['agree_count'] . '</b>'); ?>, <?php _e('%s 次感谢', '<b>' . $val['thanks_count'] . '</b>'); ?></p>
								</dd>
							</dl>
							<?php } ?>
						</div>
					</div>
					<?php } ?>
					<!-- end 最佳回复者 -->

					<!-- xx人关注该话题 -->
					<div class="icb-mod topic-status">
						<div class="mod-head">
							<h3><?php _e('%s 人关注该话题', $this->topic_info['focus_count']); ?></h3>
						</div>
						<div class="mod-body">
							<div id="focus_users" class="icb-border-radius-5"></div>
						</div>
					</div>
					<!-- end xx人关注该话题 -->

					<!-- 话题修改记录 -->
					<?php if ($this->user_id) {?>
					<div class="icb-mod topic-edit-notes">
						<div class="mod-head">
							<h3><i class="icon icon-down pull-right"></i><?php _e('话题修改记录'); ?></h3>
						</div>
						<div class="mod-body collapse">
							<ul>
								<?php if ($this->log_list) { ?>
								<?php foreach ($this->log_list as $key => $val) { ?>
								<li onclick="AWS.dialog('topicEditHistory', decodeURIComponent('<?php echo rawurlencode($val['add_time'] . ': ' . $val['title']); ?>'));">
									<span class="pull-right text-color-999"><?php echo $val['add_time']; ?></span>
									<a href="javascript:;" data-id="<?php echo $val['uid']; ?>" class="icb-user-name"><?php echo $val['user_name']; ?></a>
								</li>
								<?php } ?>
								<?php } ?>
							</ul>
						</div>
					</div>
					<?php } ?>
					<!-- end 话题修改记录 -->
				</div>
				<!-- end 侧边栏 -->
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	var TOPIC_ID = '<?php echo $this->topic_info['topic_id']; ?>';

	var CONTENTS_TOPIC_ID = '<?php echo $this->contents_topic_id; ?>';
	var CONTENTS_RELATED_TOPIC_IDS = '<?php echo $this->contents_related_topic_ids; ?>';
	var CONTENTS_TOPIC_TITLE = '<?php echo $this->contents_topic_title; ?>';
</script>

<script type="text/javascript" src="<?php echo G_STATIC_URL; ?>/js/app/topic.js"></script>

<?php View::output('global/footer.php'); ?>