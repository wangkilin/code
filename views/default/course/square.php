<?php View::output('global/header.php'); ?>

<div class="icb-container">
	<?php View::output('block/content_nav_menu.php'); ?>

	<div class="container">
		<div class="row">
			<div class="icb-content-wrap clearfix">
				<div class="col-sm-12 col-md-9 icb-main-content">

					<!-- tab切换 -->
					<ul class="nav nav-tabs icb-nav-tabs right hidden-xs">
						<li class="nav-tabs-title"><?php if ($this->category_info) { ?><?php echo $this->category_info['title']; ?><?php } else if ($this->feature_info) { ?><?php echo $this->feature_info['title']; ?><?php } else { ?><i class="icon icon-list"></i> <?php _e('教程'); ?><?php } ?></li>
						<li<?php if ((!$_GET['sort_type'] OR $_GET['sort_type'] == 'new') AND !$_GET['is_recommend']) { ?> class="active"<?php } ?>><a href="course/<?php if ($this->feature_info) { ?>feature_id-<?php echo $this->feature_info['id']; ?>__<?php } ?><?php if ($this->category_info['id']) { ?>category-<?php echo $this->category_info['id']; ?><?php } ?>"><?php _e('最新'); ?></a></li>
						<li<?php if ($_GET['is_recommend']) { ?> class="active"<?php } ?>><a href="course/<?php if ($this->feature_info) { ?>feature_id-<?php echo $this->feature_info['id']; ?>__<?php } ?>category-<?php echo $this->category_info['id']; ?>__is_recommend-1"><?php _e('推荐'); ?></a></li>
						<li<?php if ($_GET['sort_type'] == 'hot') { ?> class="active"<?php } ?>><a href="course/<?php if ($this->feature_info) { ?>feature_id-<?php echo $this->feature_info['id']; ?>__<?php } ?>category-<?php echo $this->category_info['id']; ?>__sort_type-hot__day-7" id="sort_control_hot"><?php _e('热门'); ?></a></li>
						<li<?php if ($_GET['sort_type'] == 'unresponsive') { ?> class="active"<?php } ?>><a href="course/<?php if ($this->feature_info) { ?>feature_id-<?php echo $this->feature_info['id']; ?>__<?php } ?>category-<?php echo $this->category_info['id']; ?>__sort_type-unresponsive"><?php _e('视频'); ?></a></li>
					</ul>
					<!-- end tab切换 -->

					<?php if ($_GET['sort_type'] == 'hot') { ?>
					<!-- 自定义tab切换 -->
					<div class="icb-tabs">
						<ul>
						    <li><?php _e("按时间排序");?>:</li>
							<li<?php if ($_GET['day'] == 30) { ?> class="active"<?php } ?>><a href="course/<?php if ($this->feature_info) { ?>feature_id-<?php echo $this->feature_info['id']; ?>__<?php } ?>sort_type-hot__<?php if ($this->category_info['id']) { ?>__category-<?php echo $this->category_info['id']; ?><?php } ?>__day-30" day="30"><?php _e('30天'); ?></a></li>
						  	<li<?php if ($_GET['day'] == 7) { ?> class="active"<?php } ?>><a href="course/<?php if ($this->feature_info) { ?>feature_id-<?php echo $this->feature_info['id']; ?>__<?php } ?>sort_type-hot__<?php if ($this->category_info['id']) { ?>__category-<?php echo $this->category_info['id']; ?><?php } ?>__day-7" day="7"><?php _e('7天'); ?></a></li>
						  	<li<?php if ($_GET['day'] == 1) { ?> class="active"<?php } ?>><a href="course/<?php if ($this->feature_info) { ?>feature_id-<?php echo $this->feature_info['id']; ?>__<?php } ?>sort_type-hot__<?php if ($this->category_info['id']) { ?>__category-<?php echo $this->category_info['id']; ?><?php } ?>__day-1" day="1"><?php _e('当天'); ?></a></li>
						</ul>
					</div>
					<!-- end 自定义tab切换 -->
					<?php } ?>

					<div class="icb-mod icb-article-list">
						<?php if ($this->article_list) { ?>
						<?php foreach ($this->article_list AS $key => $val) { ?>
						<div class="icb-item clearfix">
						    <div class="icb-rank col-sm-1">
					            <span class="views hidden-xs viewsword100to999">
					                122 <em>浏览</em>
					            </span>
					            <span class="votes hidden-xs">
					                0 <em>得票</em>
					            </span>
		                    </div>

							<div class="icb-content col-sm-11">
								<div class="mod-body">
									<div class="icb-article-title-box clearfix">
									    <span class="icb-article-title"><a href="course/<?php echo $val['id']; ?>"><?php echo $val['title']; ?></a></span>
											<?php foreach($this->article_topics[$val['id']] as $topic_key => $topic_val) { ?>
											<span class="article-tag">
												<a href="tag/<?php echo $topic_val['url_token']; ?>" class="text" data-id="<?php echo $topic_val['topic_id']; ?>"><?php echo $topic_val['topic_title']; ?></a>
											</span>
											<?php } ?>
									</div>
									<div class="content-wrap">
										<div class="content" id="detail_<?php echo $val['id']; ?>">
											<div class="article-brief">
												<?php echo FORMAT::parse_attachs(nl2br(FORMAT::parse_bbcode($val['message']))); ?>

											</div>
								        </div>
									</div>
								</div>
								<div class="mod-footer clearfix">
									<span class="pull-right more-operate text-color-999">
										<a class="" href="user/<?php echo $val['user_info']['url_token']; ?>">
											<?php echo $val['user_info']['user_name']; ?>
										</a>
										<?php _e('发表于');?> : <?php echo date_friendly($val['add_time']); ?>

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
