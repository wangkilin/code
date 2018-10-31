<?php View::output('global/header.php'); ?>

<div class="icb-container">
	<?php View::output('block/content_nav_menu.php'); ?>

	<div class="container">
	  <div class="clearfix">
		  <div class="col-sm-4 clearfix"><?php _e('教程');?></div>

		  <div class="col-sm-4 clearfix"><?php _e('问答');?></div>
		  <div class="col-sm-4 clearfix"><?php _e('文章');?></div>
	  </div>
	  <div class="clearfix">
		  <div class="col-sm-4 clearfix"><?php echo $this->courseList; ?></div>
		  <div class="col-sm-4 clearfix"><?php echo $this->mannualList; ?></div>
		  <div class="col-sm-4 clearfix"><?php echo $this->articleList; ?></div>
	  </div>
    </div>

	<div class="container">
		<div class="row">
			<div class="icb-content-wrap clearfix">
				<div class="col-sm-12 col-md-9 icb-main-content">
					<!-- 新消息通知 -->
					<div class="icb-mod icb-notification-box collapse" id="index_notification">
						<div class="mod-head common-head">
							<h2>
								<span class="pull-right"><a href="account/setting/privacy/#notifications" class="text-color-999"><i class="icon icon-setting"></i> <?php _e('通知设置'); ?></a></span>
								<i class="icon icon-bell"></i><?php _e('新通知'); ?><em class="badge badge-important" name="notification_unread_num"><?php echo $this->user_info['notification_unread']; ?></em>
							</h2>
						</div>
						<div class="mod-body">
							<ul id="notification_list"></ul>
						</div>
						<div class="mod-footer clearfix">
							<a href="javascript:;" onclick="AWS.Message.read_notification(false, 0, false);" class="pull-left btn btn-mini btn-gray"><?php _e('我知道了'); ?></a>
							<a href="notifications/" class="pull-right btn btn-mini btn-success"><?php _e('查看所有'); ?></a>
						</div>
					</div>
					<!-- end 新消息通知 -->
					<!-- tab切换 -->
					<ul class="nav nav-tabs icb-nav-tabs right hidden-xs">
						<li<?php if ($_GET['sort_type'] == 'unresponsive') { ?> class="active"<?php } ?>><a href="<?php if ($this->feature_info) { ?>feature_id-<?php echo $this->feature_info['id']; ?>__<?php } ?>category-<?php echo $this->category_info['id']; ?>__sort_type-unresponsive"><?php _e('等待回复'); ?></a></li>
						<li<?php if ($_GET['sort_type'] == 'hot') { ?> class="active"<?php } ?>><a href="<?php if ($this->feature_info) { ?>feature_id-<?php echo $this->feature_info['id']; ?>__<?php } ?>category-<?php echo $this->category_info['id']; ?>__sort_type-hot__day-7" id="sort_control_hot"><?php _e('热门'); ?></a></li>
						<li<?php if ($_GET['is_recommend']) { ?> class="active"<?php } ?>><a href="<?php if ($this->feature_info) { ?>feature_id-<?php echo $this->feature_info['id']; ?>__<?php } ?>category-<?php echo $this->category_info['id']; ?>__is_recommend-1"><?php _e('推荐'); ?></a></li>
						<li<?php if ((!$_GET['sort_type'] OR $_GET['sort_type'] == 'new') AND !$_GET['is_recommend']) { ?> class="active"<?php } ?>><a href="<?php if ($this->feature_info) { ?>feature_id-<?php echo $this->feature_info['id']; ?>__<?php } ?><?php if ($this->category_info['id']) { ?>category-<?php echo $this->category_info['id']; ?><?php } ?>"><?php _e('最新'); ?></a></li>

						<h2 class="hidden-xs"><?php if ($this->category_info) { ?><?php echo $this->category_info['title']; ?><?php } else if ($this->feature_info) { ?><?php echo $this->feature_info['title']; ?><?php } else { ?><i class="icon icon-list"></i> <?php _e('发现'); ?><?php } ?></h2>
					</ul>
					<!-- end tab切换 -->

					<?php if ($_GET['sort_type'] == 'hot') { ?>
					<!-- 自定义tab切换 -->
					<div class="icb-tabs">
						<ul>
							<li<?php if ($_GET['day'] == 30) { ?> class="active"<?php } ?>><a href="<?php if ($this->feature_info) { ?>feature_id-<?php echo $this->feature_info['id']; ?>__<?php } ?>sort_type-hot__<?php if ($this->category_info['id']) { ?>__category-<?php echo $this->category_info['id']; ?><?php } ?>__day-30" day="30"><?php _e('30天'); ?></a></li>
						  	<li<?php if ($_GET['day'] == 7) { ?> class="active"<?php } ?>><a href="<?php if ($this->feature_info) { ?>feature_id-<?php echo $this->feature_info['id']; ?>__<?php } ?>sort_type-hot__<?php if ($this->category_info['id']) { ?>__category-<?php echo $this->category_info['id']; ?><?php } ?>__day-7" day="7"><?php _e('7天'); ?></a></li>
						  	<li<?php if ($_GET['day'] == 1) { ?> class="active"<?php } ?>><a href="<?php if ($this->feature_info) { ?>feature_id-<?php echo $this->feature_info['id']; ?>__<?php } ?>sort_type-hot__<?php if ($this->category_info['id']) { ?>__category-<?php echo $this->category_info['id']; ?><?php } ?>__day-1" day="1"><?php _e('当天'); ?></a></li>
						</ul>
					</div>
					<!-- end 自定义tab切换 -->
					<?php } ?>

					<div class="icb-mod icb-explore-list">
						<div class="mod-body">
							<div class="icb-common-list">
								<?php echo $this->posts_list_bit; ?>
							</div>
						</div>
						<div class="mod-footer">
							<?php echo $this->pagination; ?>
						</div>
					</div>
				</div>

				<!-- 侧边栏 -->
				<div class="col-sm-12 col-md-3 icb-side-bar hidden-xs hidden-sm">
					<?php View::output('block/sidebar_feature.php'); ?>
					<?php View::output('block/sidebar_hot_topics.php'); ?>
					<?php View::output('block/sidebar_hot_users.php'); ?>
				</div>
				<!-- end 侧边栏 -->
			</div>
		</div>
	</div>
</div>

<?php View::output('global/footer.php'); ?>