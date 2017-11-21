<?php View::output('global/header.php'); ?>

<div class="icb-container">
	<div class="container">
		<div class="row">
			<div class="icb-content-wrap clearfix">
				<div class="col-sm-12 col-md-9 icb-main-content">
					<div class="mod mod-favorite">
						<div class="mod-head common-head">
							<h2><?php _e('我的收藏'); ?> <span class="badge"><?php echo count($this->list); ?></span></h2>
						</div>
						<div class="mod-body">
							<?php if (sizeof($this->favorite_tags) > 0) { ?>
							<div class="icb-tag-list clearfix">
								<span class="pull-left"><?php _e('标签'); ?>&nbsp;:&nbsp;</span>
								<div class="icb-article-title-box">
									<div class="topic-bar clearfix">
										<?php foreach ($this->favorite_tags AS $key => $val) { ?>
										<span class="article-tag">
											<a href="favorite/tag-<?php echo urlencode($val['title']); ?>" class="text"><?php echo $val['title']; ?></a>
										</span>
										<?php } ?>
									</div>
								</div>
							</div>
							<?php } ?>
							<div class="icb-feed-list">
								<?php if (sizeof ($this->list) > 0) { ?>
								<?php foreach ($this->list AS $key => $val) { ?>
								<div class="icb-item">
									<div class="mod-head">
										<?php if ($val['answer_info']['anonymous'] == 0) { ?><a data-id="<?php echo $val['user_info']['uid']; ?>" class="icb-user-img icb-border-radius-5" data-id="<?php echo $val['user_info']['uid']; ?>" href="user/<?php echo $val['user_info']['url_token']; ?>"><img src="<?php echo get_avatar_url($val['user_info']['uid'], 'mid'); ?>" alt="<?php echo $val['user_info']['user_name']; ?>" /></a><?php } else { ?><a href="javascript:;" class="icb-user-img icb-border-radius-5"><img src="<?php echo G_STATIC_URL; ?>/common/avatar-mid-img.png" alt="<?php _e('匿名用户'); ?>" /></a><?php } ?>
										<p class="text-color-999">
											<?php if ($val['last_action_str']) { ?><?php echo $val['last_action_str']; ?> • <?php echo date_friendly($val['add_time']); ?><?php } ?>
										</p>
										<h4><a href="<?php echo $val['link']; ?>"><?php echo $val['title']; ?></a></h4>
									</div>
									<div class="mod-body">
										<?php if ($val['answer_info']) { ?>
										<?php if ($val['answer_info']['anonymous'] == 0) { ?>
										<p class="text-color-999">
											<a class="icb-user-name" data-id="<?php echo $val['user_info']['uid']; ?>" href="user/<?php echo $val['user_info']['url_token']; ?>"><?php echo $val['user_info']['user_name']; ?></a><?php if ($val['user_info']['signature']) { ?> - <?php echo $val['user_info']['signature']; ?><?php } ?>
										</p>
										<?php } ?>

										<!-- 评论内容 -->
										<div class="content">
											<div id="detail_<?php echo md5($val['item_id'] . $val['item_type']); ?>">
									      		<?php echo cjk_substr(strip_ubb($val['answer_info']['answer_content']), 0, 130, 'UTF-8', '...'); ?>
									      		<?php if (cjk_strlen($val['answer_info']['answer_content']) > 130) { ?>
									        	<a href="javascript:;" class="showMore" onclick="AWS.content_switcher($('#detail_<?php echo md5($val['item_id'] . $val['item_type']); ?>'), $('#detail_more_<?php echo md5($val['item_id'] . $val['item_type']); ?>'));"><?php _e('显示全部'); ?> »</a>
									        	<?php } ?>

												<?php if ($val['answer_info']['attachs']) { ?>
													<div class="icb-upload-img-list width-auto">
														<?php foreach($val['answer_info']['attachs'] AS $t_key => $v) { ?>
														<?php if ($v['is_image']): ?>
															<a href="javascript:;" onclick="AWS.content_switcher($('#detail_<?php echo md5($val['item_id'] . $val['item_type']); ?>'), $('#detail_more_<?php echo md5($val['item_id'] . $val['item_type']); ?>'));">
																<img class="img-polaroid" src="<?php echo $v[thumb]; ?>" alt="<?php echo $v['file_name']; ?>" />
															</a>
														<?php endif; ?>
													  <?php } ?>
													</div>

													<div class="icb-upload-file-list">
														<?php foreach($val['answer_info']['attachs'] AS $t_key => $v) { ?>
														<?php if (!$v['is_image']): ?>
															<li><a href="<?php echo download_url($v['file_name'], $v['attachment']); ?>"><em class="icb-icon i-upload-file"></em><?php echo $v['file_name']; ?></a></li>
														<?php endif; ?>
													  <?php } ?>
													</div>
												<?php } ?>
											</div>

											<div id="detail_more_<?php echo md5($val['item_id'] . $val['item_type']); ?>" class="collapse">
									      		<?php echo nl2br(strip_ubb($val['answer_info']['answer_content'])); ?>

												<?php if ($val['answer_info']['attachs']) { ?>
													<div class="icb-upload-img-list active">
														<?php foreach($val['answer_info']['attachs'] AS $k => $v) { ?>
														<?php if ($v['is_image']): ?>
															<a href="<?php echo $v['attachment']; ?>" target="lightbox" data-fancybox-group="thumb" rel="lightbox"><img class="img-polaroid" src="<?php echo $v['attachment']; ?>" alt="" /></a>
														<?php endif; ?>
													  <?php } ?>
													</div>

													<div class="icb-upload-file-list">
														<?php foreach($val['answer_info']['attachs'] AS $k => $v) { ?>
														<?php if (!$v['is_image']): ?>
															<li><a href="<?php echo download_url($v['file_name'], $v['attachment']); ?>"><em class="icb-icon i-upload-file"></em><?php echo $v['file_name']; ?></a></li>
														<?php endif; ?>
													  <?php } ?>
													</div>
												<?php } ?>
											</div>
										</div>
										<!-- end 评论内容 -->
										<?php } ?>
									</div>
									<div class="mod-footer">
										<a class="pull-right text-color-999" onclick="AWS.ajax_request(G_BASE_URL + '/favorite/ajax/remove_favorite_item/', 'item_type=<?php echo $val['item_type']; ?>&item_id=<?php echo $val['item_id']; ?>');"><?php _e('取消收藏'); ?></a>

										<div class="icb-article-title-box" data-type="favorite" data-item-type="<?php echo $val['item_type']; ?>" data-id="<?php echo $val['item_id']; ?>">
											<div class="tag-queue-box clearfix">
												<?php if ($this->favorite_items_tags[$val['item_id']]) { ?>
												<?php foreach ($this->favorite_items_tags[$val['item_id']] AS $k => $v) { ?>
												<span class="article-tag">
													<a class="text"><?php echo $v['title']; ?></a>
												</span>
												<?php } ?>
												<?php } ?>

												<span class="icon-inverse icb-edit-topic"><i class="icon icon-edit"></i> <?php if (!$this->favorite_items_tags[$val['item_id']]) { ?><?php _e('添加标签'); ?><?php } else { ?><?php _e('编辑'); ?><?php } ?></span>
											</div>
										</div>
									</div>
								</div>
								<?php } ?>
								<?php } ?>
							</div>
						</div>
						<div class="mod-footer">
							<?php echo $this->pagination; ?>
						</div>
					</div>
				</div>
				<!-- 侧边栏 -->
				<div class="col-sm-12 col-md-3 icb-side-bar">
					<?php View::output('block/sidebar_announce.php'); ?>

					<?php View::output('block/sidebar_menu.php'); ?>

					<!-- 可能感兴趣的人/或话题 -->
					<?php View::output('block/sidebar_recommend_users_topics.php'); ?>
				</div>
				<!-- end 侧边栏 -->
			</div>
		</div>
	</div>
</div>
<?php View::output('global/footer.php'); ?>
