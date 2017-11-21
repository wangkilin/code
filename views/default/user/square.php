<?php View::output('global/header.php'); ?>

<div class="icb-container">
	<div class="container">
		<div class="row">
			<div class="icb-content-wrap clearfix">
				<div class="col-sm-12 col-md-9 icb-main-content">
					<div class="icb-mod">
						<div class="mod-head common-head">
							<h2><i class="icon icon-users"></i><?php _e('用户推荐'); ?></h2>
						</div>
						<div class="mod-body icb-people-list">
							<?php if ($this->users_list) { ?>
							<?php foreach($this->users_list as $key => $val) { ?>
							<div class="icb-item">
								<span class="icb-user-sort-count icb-border-radius-5<?php if (($key + 1 + ((intval($_GET['page']) - 1) * get_setting('contents_per_page'))) <= 3) { ?> active<?php } ?>"><i class="icon icon-flag"></i> <em><?php if (($key + 1 + ((intval($_GET['page']) - 1) * get_setting('contents_per_page'))) < 99) { echo ($key + 1 + ((intval($_GET['page']) - 1) * get_setting('contents_per_page'))); } else { echo '*'; }; ?></em></span>
								<a class="icb-user-img icb-border-radius-5" href="user/<?php echo $val['url_token']; ?>">
									<img alt="" src="<?php echo get_avatar_url($val['uid'], 'mid'); ?>" />
								</a>
								<p class="text-color-999 title">
									<a href="user/<?php echo $val['url_token']; ?>" class="icb-user-name"><?php echo $val['user_name']; ?></a>
									<?php if ($val['verified']) { ?><i class="icon-v<?php if ($val['verified'] == 'enterprise') { ?> i-ve<?php } ?>" title="<?php if ($val['verified'] == 'enterprise') { ?><?php _e('企业认证'); ?><?php } else { ?><?php _e('个人认证'); ?><?php } ?>"></i><?php } ?>
								</p>
								<p class="text-color-999 signature"><?php echo $val['signature']; ?></p>
								<div class="meta">
									<span><i class="icon icon-prestige"></i><?php _e('威望'); ?> <b><?php echo $val['reputation']; ?></b></span>
									<?php if (get_setting('integral_system_enabled') == 'Y') { ?><span><i class="icon icon-score"></i><?php _e('积分'); ?> <b><?php echo $val['integral']; ?></b></span><?php } ?>
									<span><i class="icon icon-agree"></i><?php _e('赞同'); ?> <b><?php echo $val['agree_count']; ?></b></span>
									<span><i class="icon icon-thank"></i><?php _e('感谢'); ?> <b><?php echo $val['thanks_count']; ?></b></span>
								</div>
								
								<?php if ($val['uid'] != $this->user_id AND $this->user_id) { ?>
								<div class="operate">
									<a href="javascript:;" onclick="AWS.User.follow($(this), 'user', <?php echo $val['uid'];?>);" class="follow btn btn-normal btn-success<?php if ($val['focus']) { ?> active<?php } ?>"><span><?php if ($val['focus']) { ?><?php _e('取消关注'); ?><?php } else { ?><?php _e('关注'); ?><?php } ?></span> <em>|</em> <b><?php echo $val['fans_count']; ?></b></a>
									<a onclick="AWS.dialog('publish', {category_enable:<?php echo (get_setting('category_enable') == 'Y') ? '1' : '0'; ?>, ask_user_id:<?php echo $val['uid']; ?>, ask_user_name:'<?php echo $val['user_name']; ?>'});" class="text-color-999"><?php _e('问Ta'); ?></a>
									<em class="text-color-999">•</em>	 
									<a onclick="AWS.dialog('inbox', '<?php echo $val['user_name']; ?>');" class="text-color-999"><?php _e('私信'); ?></a>
								</div>
								<?php } ?>

								<?php if ($val['reputation_topics']) { ?>
								<p>
									<span class="pull-left"><?php _e('擅长话题'); ?>:</span>
									<div class="icb-article-title-box">
										<div class="topic-bar clearfix">
											<?php foreach($val['reputation_topics'] as $t_key => $t_val) { ?>
												<span class="article-tag">
													<a href="topic/<?php echo $t_val['url_token']; ?>" class="text" data-id="<?php echo $t_val['topic_id']; ?>"><?php echo $t_val['topic_title']; ?></a>
												</span>
											<?php } ?>
										</div>
									</div>
								</p>
								<?php } else if ($val['experience']) { ?>
								<p class="text-color-999">
									<?php foreach($val['experience'] as $t_key => $t_val) { ?>
										<a href="topic/<?php echo $t_val['topic_info']['url_token']; ?>" class="icb-topic-name" data-id="<?php echo $t_val['topic_id']; ?>"><span><?php echo $t_val['topic_info']['topic_title']; ?></span></a>
									<?php } ?>
									<?php _e('等话题下共获得 %s 个赞同', $val['total_agree_count']); ?>
								</p>
								<?php } ?>
							</div>
							<?php } ?>
							<?php } ?>
							
							<?php echo $this->pagination; ?>
						</div>
					</div>
				</div>
				<div class="col-sm-12 col-md-3 icb-side-bar hidden-xs hidden-sm <?php if (!$this->feature_list) { ?>active<?php } ?>">
					<div class="icb-mod side-nav">
						<div class="mod-body">
							<ul>
								<?php if ($this->custom_group) { ?>
								<li><a <?php if (!$_GET['group_id'] AND !$_GET['topic_id']) { ?> class="active"<?php } ?> href="user/"><?php _e('全部'); ?></a></li>
								<?php foreach ($this->custom_group AS $key => $val) { ?>
								<li><a <?php if ($_GET['group_id'] == $val['group_id']) { ?> class="active"<?php } ?> href="user/group_id-<?php echo $val['group_id']; ?>"><?php echo $val['group_name']; ?></a></li>
								<?php } ?>
								<?php } ?>
							</ul>
						</div>
					</div>
					
					<div class="icb-mod side-nav">
						<div class="mod-body">
							<ul>
								<?php if ($this->parent_topics) { ?>
								<?php foreach ($this->parent_topics AS $key => $val) { ?>
								<li>
									<a href="user/topic_id-<?php echo $val['topic_id']; ?>"<?php if ($_GET['topic_id'] == $val['topic_id']) { ?> class="active"<?php } ?>><?php echo $val['topic_title']; ?></a>
								</li>
								<?php } ?>
								<?php } ?>
							</ul>
						</div>
					</div>

					<!-- <div class="icb-mod people-sort">
						<div class="mod-body">
							<input type="text" name="" placeholder="按擅长话题搜索..." class="form-control" />
							<i class="icon icon-down"></i>
						</div>
					</div> -->
					
				</div>
			</div>
		</div>
	</div>
</div>
		
<?php View::output('global/footer.php'); ?>
