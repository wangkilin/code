<?php View::output('m/header.php'); ?>
<!-- 内容 -->
<div class="container padding-0">
	<!-- 个人信息 -->
	<div class="icb-people-detail">
		<div class="mod-head clearfix">
			<img src="<?php echo get_avatar_url($this->user['uid'], 'max'); ?>" alt="" class="img" width="50" />
			<div class="title clearfix">
				<h1 class="pull-left">
					<?php echo $this->user['user_name']; ?>
					<?php if ($this->user['verified']) { ?><i class="icon-v<?php if ($this->user['verified'] == 'enterprise') { ?> i-ve<?php } ?>" title="<?php if ($this->user['verified'] == 'enterprise') { ?>企业认证<?php } else { ?>个人认证<?php } ?>"></i><?php } ?>
				</h1>

				<?php if ($this->user['uid'] != $this->user_id AND $this->user_id) { ?>
				<span class="pull-right">
					<a class="btn btn-success btn-mini <?php if ($this->user_follow_check) { ?> active<?php } ?>" onclick="AWS.User.follow($(this), 'user', <?php echo $this->user['uid']; ?>);"><?php if ($this->user_follow_check) { ?><?php _e('取消关注'); ?><?php } else { ?><?php _e('关注'); ?><?php } ?></a>
					<a class="btn btn-success btn-stack" href="m/inbox_new/uid-<?php echo $this->user['uid']; ?>"><i class="icon icon-inbox"></i></a>
				</span>
				<?php } ?>
			</div>
			<?php if ($this->user['signature']) { ?>
			<p class="color-999"><?php echo $this->user['signature']; ?></p>
			<?php } ?>
		</div>
		<div class="mod-body clearfix">
			<ul>
				<li>
					<b><?php echo $this->user['reputation']; ?></b>
					<?php _e('威望')?>
				</li>
				<li>
					<b><?php echo $this->user['integral']; ?></b>
					<?php _e('积分')?>
				</li>
				<li>
					<b><?php echo $this->user['agree_count']; ?></b>
					<?php _e('赞同')?>
				</li>
				<li>
					<b><?php echo $this->user['thanks_count']; ?></b>
					<?php _e('感谢')?>
				</li>
			</ul>
		</div>
		<div class="mod-footer clearfix">
			<ul>
				<li class="active">
					<a href="#join" role="tab" data-toggle="tab"><?php _e('参与'); ?></a>
				</li>
				<li>
					<a href="#publish" role="tab" data-toggle="tab"><?php _e('发起'); ?></a>
				</li>
				<li>
					<a href="#follow" role="tab" data-toggle="tab"><?php _e('关注'); ?></a>
				</li>
				<li>
					<a href="#profile" role="tab" data-toggle="tab"><?php _e('资料'); ?></a>
				</li>
			</ul>
		</div>
	</div>
	<!-- end 个人信息 -->
	<div class="icb-box">
		<div class="tab-content">
			<!-- 参与列表 -->
			<div class="tab-pane active" id="join">
				<div class="icb-feed-list active">
					<div class="mod-body">
						<ul id="join_listview">
							<?php foreach ($this->user_actions_answers AS $key => $val) { ?>
							<li>
								<div class="mod-body">
									<h2>
										<a href="<?php echo $val['link']; ?>"><?php echo $val['title']; ?></a>
									</h2>
									<p>
										<?php echo cjk_substr($val['answer_info']['answer_content'], 0, 130, 'UTF-8', '...'); ?>
									</p>
								</div>
								<div class="mod-footer active">
									<a><?php echo $val['answer_info']['agree_count']; ?> <?php _e('个赞同'); ?></a>
									<span class="color-999 pull-right"><?php echo date_friendly($val['add_time'], 604800, 'Y-m-d'); ?></span>
								</div>
							</li>
							<?php } ?>
						</ul>
					</div>
					<div class="mod-footer">
						<a class="icb-load-more" id="load_join" auto-load="false"><i class="icon icon-loading icon-spin"></i> <span><?php _e('更多'); ?></span></a>
					</div>
				</div>
			</div>
			<!-- end 参与列表 -->

			<!-- 发起列表 -->
			<div class="tab-pane" id="publish">
				<div class="icb-feed-list active">
					<div class="mod-body">
						<ul id="publish_listview">
							<?php foreach ($this->user_actions_questions AS $key => $val) { ?>
							<li>
								<div class="mod-body">
									<h2>
										<a href="<?php echo $val['link']; ?>"><?php echo $val['title']; ?></a>
									</h2>
								</div>
								<div class="mod-footer active">
									<a><?php echo $val['question_info']['focus_count']; ?> <?php _e('个关注'); ?></a>
									<span class="color-999 pull-right"><?php echo date_friendly($val['add_time'], 604800, 'Y-m-d'); ?></span>
								</div>
							</li>
							<?php } ?>
						</ul>
					</div>
					<div class="mod-footer">
						<a class="icb-load-more" id="load_publish" auto-load="false"><i class="icon icon-loading icon-spin"></i> <span><?php _e('更多'); ?></span></a>
					</div>
				</div>
			</div>
			<!-- end 发起列表 -->

			<!-- 关注列表 -->
			<div class="tab-pane" id="follow">
				<!-- 关注 -->
				<?php if ($this->friends_list) { ?>
				<div class="mod card-mod">
					<div class="mod-head">
						<h2><?php _e('关注 %s 人', $this->user['friend_count']); ?></h2>
					</div>
					<div class="mod-body">
						<ul class="user-list">
							<?php foreach ($this->friends_list AS $key => $val) { ?>
							<li>
								<a href="m/user/<?php echo $val['url_token']; ?>"><img class="img" width="25" src="<?php echo get_avatar_url($val['uid'], 'mid'); ?>" alt="<?php echo $val['user_name']; ?>" /></a>
							</li>
							<?php } ?>
						</ul>
					</div>
				</div>
				<?php } ?>
				<!-- end 关注 -->

				<!-- 关注者 -->
				<?php if ($this->fans_list) { ?>
				<div class="mod card-mod">
					<div class="mod-head">
						<h2><?php _e('关注者 %s 人', $this->user['fans_count']); ?></h2>
					</div>
					<div class="mod-body">
						<ul class="user-list">
							<?php foreach ($this->fans_list AS $key => $val) { ?>
							<li>
								<a href="user/<?php echo $val['url_token']; ?>"><img class="img" width="25" src="<?php echo get_avatar_url($val['uid'], 'mid'); ?>" alt="<?php echo $val['user_name']; ?>"></a>
							</li>
	           				<?php } ?>
						</ul>
					</div>
				</div>
	            <?php } ?>
				<!-- end 关注者 -->

				<!-- 关注话题 -->
				<?php if ($this->focus_topics) { ?>
				<div class="mod card-mod focus-topics">
					<div class="mod-head">
						<h2><?php _e('关注 %s 个话题', $this->user['topic_focus_count']); ?></h2>
					</div>
					<div class="mod-body clearfix">
						<?php foreach ($this->focus_topics AS $key => $val) { ?>
						<span class="article-tag">
							<a class="text" href="m/topic/<?php echo $val['url_token']; ?>"><?php echo $val['topic_title']; ?></a>
						</span>
						<?php } ?>
					</div>
				</div>
				<?php } ?>
				<!-- end 关注话题 -->
			</div>
			<!-- end 关注列表 -->

			<!-- 个人资料 -->
			<div class="tab-pane" id="profile">
				<div class="mod card-mod people-profile">
					<div class="mod-head">
						<h2><?php _e('个人资料'); ?></h2>
					</div>
					<div class="mod-body">
						<ul>
							<li>
								<i class="icon icon-location"></i>
								<span class="color-666"> <?php _e('现居'); ?> :</span>
								<?php if ($this->user['province']) { ?>
									<?php echo $this->user['province']; ?>
									<?php echo $this->user['city']; ?>
								<?php } ?>
							</li>
							<li>
								<i class="icon icon-job"></i>
								<span class="color-666"> <?php _e('职业'); ?> :</span> <?php if ($this->job_name) { ?><?php echo $this->job_name; ?><?php } ?>
							</li>
							<li>
								<i class="icon icon-bind"></i>
								<span class="color-666"><?php _e('绑定'); ?>  :</span>
								<?php if (get_setting('sina_weibo_enabled') == 'Y' AND $this->sina_weibo_url) { ?>
									<?php if ($this->sina_weibo_url AND get_setting('sina_weibo_enabled') == 'Y') { ?><a href="<?php if ($this->sina_weibo_url) { ?><?php echo $this->sina_weibo_url; ?><?php } else { ?>javascript:;<?php } ?>" title="<?php _e('微博'); ?>"><?php _e('微博'); ?></a><?php } ?>
								<?php } ?>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<!-- end 个人资料 -->
		</div>
	</div>
</div>
<!-- end 内容 -->

<script type="text/javascript">
	var PEOPLE_USER_ID = '<?php echo $this->user['uid']; ?>';

    var ACTIVITY_ACTIONS = '<?php echo implode(',', array(
        ACTION_LOG::ADD_QUESTION,
        ACTION_LOG::ANSWER_QUESTION,
        ACTION_LOG::ADD_REQUESTION_FOCUS,
        ACTION_LOG::ADD_AGREE,
        ACTION_LOG::ADD_TOPIC,
        ACTION_LOG::ADD_TOPIC_FOCUS,
        ACTION_LOG::ADD_ARTICLE,
        ACTION_LOG::ADD_AGREE_ARTICLE,
        ACTION_LOG::ADD_COMMENT_ARTICLE
    )); ?>';

	$(function()
	{

		AWS.load_list_view(G_BASE_URL + '/user/ajax/user_actions/uid' + '-' + PEOPLE_USER_ID + '__actions-201', $('#load_join'), $('#join_listview'), 1);	// 参与的问题

		AWS.load_list_view(G_BASE_URL + '/user/ajax/user_actions/uid' + '-' + PEOPLE_USER_ID + '__actions-101', $('#load_publish'), $('#publish_listview'), 1);	// 发起的问题
	});
</script>

<?php View::output('m/footer.php'); ?>