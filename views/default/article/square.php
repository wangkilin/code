<?php View::output('global/header.php'); ?>

<div class="icb-container">
	<?php View::output('block/content_nav_menu.php'); ?>

	<div class="container">
		<div class="row">
			<div class="icb-content-wrap clearfix">
				<div class="col-sm-12 col-md-9 icb-main-content">
					<div class="icb-mod icb-article-list">
						<?php if ($this->article_list) { ?>
						<?php foreach ($this->article_list AS $key => $val) { ?>
						<div class="icb-item">
							<a class="icb-user-name hidden-xs" href="user/<?php echo $val['user_info']['url_token']; ?>">
								<img alt="" src="<?php echo get_avatar_url($val['uid'], 'mid'); ?>" />
							</a>
							<div class="icb-content">
								<div class="mod-body">
									<h2><a href="article/<?php echo $val['id']; ?>"><?php echo $val['title']; ?></a></h2>
									<div class="icb-article-title-box">
										<div class="tag-queue-box clearfix">
											<?php foreach($this->article_topics[$val['id']] as $topic_key => $topic_val) { ?>
											<span class="article-tag">
												<a href="topic/<?php echo $topic_val['url_token']; ?>" class="text" data-id="<?php echo $topic_val['topic_id']; ?>"><?php echo $topic_val['topic_title']; ?></a>
											</span>
											<?php } ?>
										</div>
									</div>
									<div class="content-wrap">
										<div class="content" id="detail_<?php echo $val['id']; ?>">
											<div class="hide-content markitup-box">
												<?php echo FORMAT::parse_attachs(nl2br(FORMAT::parse_bbcode($val['message']))); ?>

											</div>

											<?php if (cjk_strlen($val['message']) > 130) { ?>
								        	<a class="more" href="#" onclick="AWS.content_switcher($('#detail_<?php echo $val['id']; ?>'), $('#detail_more_<?php echo $val['id']; ?>')); return false;"><?php _e('继续阅读'); ?> »</a>
								        	<?php } ?>
										</div>

										<div class="content markitup-box collapse" id="detail_more_<?php echo $val['id']; ?>">
											<?php echo FORMAT::parse_attachs(nl2br(FORMAT::parse_bbcode($val['message']))); ?>
											<?php if (cjk_strlen($val['message']) > 130) { ?>
								        	<a class="more" href="#" onclick="AWS.content_switcher($('#detail_more_<?php echo $val['id']; ?>'), $('#detail_<?php echo $val['id']; ?>')); return false;"><?php _e('收起阅读'); ?> »</a>
								        	<?php } ?>
										</div>
									</div>
								</div>
								<div class="mod-footer clearfix">
									<span class="pull-right more-operate text-color-999">
										<?php echo $val['user_info']['user_name']; ?> 发表于 : <?php echo date_friendly($val['add_time']); ?>

										<a class="text-color-999" href="article/<?php echo $val['id']; ?>"><i class="icon icon-comment"></i> <?php _e('评论'); ?> (<?php echo $val['comments']; ?>)</a>
										<a class="text-color-999 dropdown-toggle" data-toggle="dropdown">
											<i class="icon icon-share"></i> <?php _e('分享'); ?>
										</a>
										<div aria-labelledby="dropdownMenu" role="menu" class="icb-dropdown shareout pull-right">
											<ul class="icb-dropdown-list">
												<li><a onclick="AWS.User.share_out({webid: 'tsina', content: $(this).parents('.icb-item').find('.markitup-box')});"><i class="icon icon-weibo"></i> <?php _e('微博'); ?></a></li>
											<li><a onclick="AWS.User.share_out({webid: 'qzone', content: $(this).parents('.icb-item')});"><i class="icon icon-qzone"></i> <?php _e('QZONE'); ?></a></li>
											<li><a onclick="AWS.User.share_out({webid: 'weixin', content: $(this).parents('.icb-item')});"><i class="icon icon-wechat"></i> <?php _e('微信'); ?></a></li>
											</ul>
										</div>
									</span>
								</div>
							</div>
						</div>
						<?php } ?>

						<?php echo $this->pagination; ?>

						<?php } ?>
					</div>
					<!-- end 文章列表 -->
				</div>
				<!-- 侧边栏 -->
				<div class="col-sm-3 col-md-3 icb-side-bar hidden-xs hidden-sm">
					<!-- 热门文章 -->
					<div class="icb-mod icb-text-align-justify">
						<div class="mod-head">
							<h3><?php _e('热门文章'); ?></h3>
						</div>
						<div class="mod-body">
							<ul>
								<?php foreach($this->hot_articles AS $key => $val) { ?>
								<li><a href="article/<?php echo $val['id']; ?>"><?php echo $val['title']; ?></a></li>
								<?php } ?>
							</ul>
						</div>
					</div>
					<!-- end 热门文章 -->
					<?php View::output('block/sidebar_hot_topics.php'); ?>
				</div>
				<!-- end 侧边栏 -->
			</div>
		</div>
	</div>
</div>

<?php View::output('global/footer.php'); ?>
