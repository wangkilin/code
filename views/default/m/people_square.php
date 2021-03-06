<?php View::output('m/header.php'); ?>

<!-- 标题 -->
<div class="icb-title">
	<?php _e('用户推荐'); ?>
</div>
<!-- end 标题 -->

<!-- 分类 -->
<?php if ($this->custom_group) { ?>
<div class="top-category">
	<ul>
		<li><a <?php if (!$_GET['group_id']) { ?> class="active"<?php } ?> href="m/user/"><?php _e('全部'); ?></a></li>
			<?php foreach ($this->custom_group AS $key => $val) { ?>
			<li><a <?php if ($_GET['group_id'] == $val['group_id']) { ?> class="active"<?php } ?> href="m/user/group_id-<?php echo $val['group_id']; ?>"><?php echo $val['group_name']; ?></a></li>
			<?php } ?>
	</ul>
</div>
<?php } ?>
<!-- end 分类 -->

<!-- 内容 -->
<div class="container">
	<!-- 用户列表 -->
	<div class="icb-feed-list icb-people-list active">
		<div class="mod-body">
			<ul>
				<?php if ($this->users_list) { ?>
				<?php foreach($this->users_list as $key => $val) { ?>
				<li>
					<div class="mod-head">
						<?php if ($val['uid'] != $this->user_id AND $this->user_id) { ?>
							<a onclick="AWS.User.follow($(this), 'user', <?php echo $val['uid'];?>);" class="btn btn-success btn-mini pull-right<?php if ($val['focus']) { ?> active<?php } ?>"><?php if ($val['focus']) { ?><?php _e('取消关注'); ?><?php } else { ?><?php _e('关注'); ?><?php } ?></a>
						<?php } ?>
						<img alt="" width="50" class="img" src="<?php echo get_avatar_url($val['uid'], 'mid'); ?>" />
						<a href="user/<?php echo $val['url_token']; ?>"><?php echo $val['user_name']; ?></a>
						<?php if ($val['verified']) { ?><i class="icon-v<?php if ($val['verified'] == 'enterprise') { ?> i-ve<?php } ?>" title="<?php if ($val['verified'] == 'enterprise') { ?><?php _e('企业认证'); ?><?php } else { ?><?php _e('个人认证'); ?><?php } ?>"></i><?php } ?>
						<p class="color-999">
							<?php echo $val['signature']; ?>
						</p>
						<span class="flag"><i class="icon icon-flag <?php if (($key + 1 + ((intval($_GET['page']) - 1) * get_setting('contents_per_page'))) <= 3) { ?> active<?php } ?>"></i> <b><?php if (($key + 1 + ((intval($_GET['page']) - 1) * get_setting('contents_per_page'))) < 99) { echo ($key + 1 + ((intval($_GET['page']) - 1) * get_setting('contents_per_page'))); } else { echo '*'; }; ?></b></span>
					</div>
					<div class="mod-body clearfix">
						<ol>
							<li>
								<b><?php echo $val['reputation']; ?></b>
								<?php _e('威望'); ?>
							</li>
							<?php if (get_setting('integral_system_enabled') == 'Y') { ?>
							<li>
								<b><?php echo $val['integral']; ?></b>
								<?php _e('积分'); ?>
							</li>
							<?php } ?>
							<li>
								<b><?php echo $val['agree_count']; ?></b>
								<?php _e('赞同'); ?>
							</li>
							<li>
								<b><?php echo $val['thanks_count']; ?></b>
								<?php _e('感谢'); ?>
							</li>
							<li>
								<?php _e('擅长'); ?><br/><?php _e('话题'); ?>
							</li>
						</ol>
						<i></i>
						<i class="active"></i>
					</div>
					<?php if ($val['reputation_topics']) { ?>
					<div class="mod-footer active">
						<?php foreach($val['reputation_topics'] as $t_key => $t_val) { ?>
						<span class="article-tag">
							<a href="topic/<?php echo $t_val['url_token']; ?>" data-id="<?php echo $t_val['topic_id']; ?>" class="text"><?php echo $t_val['topic_title']; ?></a>
						</span>
						<?php } ?>
					</div>
					<?php } ?>
				</li>
				<?php } ?>
				<?php } ?>
			</ul>
		</div>
		<div class="mod-footer">
			<?php echo $this->pagination; ?>
		</div>
	</div>
	<!-- end 用户列表 -->
</div>
<!-- end 内容 -->

<?php View::output('m/footer.php'); ?>