<div class="icb-item" uninterested_count="<?php echo $this->answer_info['uninterested_count']; ?>" force_fold="<?php if ($this->answer_info['user_rated_uninterested']) { ?>1<?php } else { ?><?php echo $this->answer_info['force_fold']; ?><?php } ?>" id="answer_list_<?php echo $this->answer_info['answer_id']; ?>">
	<div class="mod-head">
		<?php if ($this->question_info['best_answer'] == $this->answer_info['answer_id']) { ?>
		<!-- 最佳回答 -->
		<div class="icb-best-answer">
			<i class="icon icon-bestbg"></i>
			<?php _e('最佳回复'); ?>
		</div>
		<!-- end 最佳回答 -->
		<?php } ?>
		<a class="anchor" name="answer_<?php echo $this->answer_info['answer_id']; ?>"></a>
		<!-- 用户头像 -->
		<?php if ($this->answer_info['anonymous'] == 0) { ?><a class="icb-user-img icb-border-radius-5 pull-right" href="user/<?php echo $this->answer_info['user_info']['url_token']; ?>" data-id="<?php echo $this->answer_info['uid']; ?>"><img src="<?php echo get_avatar_url($this->answer_info['uid'], 'mid'); ?>" alt="" /></a><?php } else { ?><a class="icb-user-img icb-border-radius-5 pull-right" href="javascript:;"><img src="<?php echo G_STATIC_URL; ?>/common/avatar-mid-img.png" alt="<?php _e('匿名用户'); ?>" /></a><?php } ?>
		<!-- end 用户头像 -->
		<div class="title">
			<p>
				<?php if ($this->answer_info['anonymous'] == 0) { ?>
					<a class="icb-user-name" href="user/<?php echo $this->answer_info['user_info']['url_token']; ?>" data-id="<?php echo $this->answer_info['uid']; ?>"><?php echo $this->answer_info['user_info']['user_name']; ?></a>
				<?php } else { ?>
					<a class="icb-user-name" href="javascript:;"><?php _e('匿名用户'); ?></a>
				<?php } ?>
				<?php if ($this->answer_info['anonymous'] == 0) { ?>
					<?php if ($this->answer_info['user_info']['verified']) { ?>
						<i class="icon-v<?php if ($this->answer_info['user_info']['verified'] == 'enterprise') { ?> i-ve<?php } ?>" title="<?php if ($this->answer_info['user_info']['verified'] == 'enterprise') { ?>企业认证<?php } else { ?>个人认证<?php } ?>"></i>
					<?php } ?>
					<?php if ($this->answer_info['user_info']['signature']) { ?> - <span class="text-color-999"><?php echo $this->answer_info['user_info']['signature']; ?></span><?php } ?>
				<?php } ?>
				<?php if ($this->answer_info['publish_source'] == 'mobile') { ?>
					<i class="icon icon-phone"></i>
				<?php } else if ($this->answer_info['publish_source'] == 'weixin') { ?>
					<i class="icon icon-wechat"></i>
				<?php } ?>
			</p>
			<p class="text-color-999 icb-agree-by<?php if (sizeof($this->answer_info['agree_users']) == 0) { ?> collapse<?php } ?>">
				<?php _e('赞同来自'); ?>:

				<?php if ($this->answer_info['agree_users']) { ?>
				<?php $count = 0; foreach($this->answer_info['agree_users'] AS $uid => $user) { ?>
				<?php if ($count > 0) { ?><em<?php if ($count >= 5) { ?> class="collapse"<?php } ?>>、</em><?php } ?><a href="user/<?php echo $user['url_token']; ?>" data-id="<?php echo $user['uid']; ?>" class="icb-user-name<?php if ($count >= 5) { ?> collapse<?php } ?>"><?php echo $user['user_name']; ?></a><?php $count++; } ?><?php } ?><?php if (count($this->answer_info['agree_users']) > 5) { ?><a href="javascript:;" class="icb-agree-by-show" onclick="$(this).parents('.icb-agree-by').find('em,a').removeClass('collapse'); $(this).remove();"><?php _e('更多'); ?> »</a>
				<?php } ?>
			</p>
		</div>
	</div>
	<div class="mod-body clearfix">
		<!-- 评论内容 -->
		<div class="markitup-box">
			<?php echo $this->answer_info['answer_content']; ?>
		</div>

		<?php if ($this->answer_info['attachs']) {  ?>
		<div class="icb-upload-img-list">
		<?php foreach ($this->answer_info['attachs'] AS $attach) { ?>
		<?php if ($attach['is_image'] AND (!$this->answer_info['insert_attach_ids'] OR !in_array($attach['id'], $this->answer_info['insert_attach_ids']))) { ?>
			<a href="<?php echo $attach['attachment']; ?>" target="_blank" data-fancybox-group="thumb" rel="lightbox"><img src="<?php echo $attach['attachment']; ?>" class="img-thumbnail" alt="<?php echo $attach['attach_name']; ?>" /></a>
		<?php } ?>
		<?php } ?>
		</div>
		<?php } ?>

		<?php if ($this->answer_info['attachs']) {  ?>
		<ul class="icb-upload-file-list">
			<?php foreach ($this->answer_info['attachs'] AS $attach) { ?>
			<?php if (!$attach['is_image'] AND (!$this->answer_info['insert_attach_ids'] OR !in_array($attach['id'], $this->answer_info['insert_attach_ids']))) { ?>
				<li><a href="<?php echo download_url($attach['file_name'], $attach['attachment']); ?>"><i class="icon icon-attach"></i><?php echo $attach['file_name']; ?></a></li>
			<?php } ?>
			<?php } ?>
		</ul>
		<?php } ?>
		<!-- end 评论内容 -->
	</div>
	<div class="mod-footer">
		<!-- 社交操作 -->
		<div class="meta clearfix">
			<span class="text-color-999 pull-right"><?php echo date_friendly($this->answer_info['add_time'], 604800, 'Y-m-d'); ?></span>
			<!-- 投票栏 -->
			<span class="operate">
				<a class="agree<?php if ($this->user_info['user_name'] == $this->answer_info['user_info']['user_name']) { ?> disabled<?php } ?> <?php if ($this->answer_info['agree_status'] == 1) { ?>active<?php } ?>" <?php if ($this->user_info['user_name'] != $this->answer_info['user_info']['user_name']) { ?>onclick="AWS.User.agree_vote(this, '<?php echo $this->user_info['user_name']; ?>', <?php echo $this->answer_info['answer_id']; ?>);"<?php } ?>><i data-placement="right" title="" data-toggle="tooltip" class="icon icon-agree" data-original-title="赞同回复"></i> <b class="count"><?php echo $this->answer_info['agree_count']; ?></b></a>
				<?php if ($this->user_id AND $this->user_info['user_name'] != $this->answer_info['user_info']['user_name']) { ?>
				<a class="disagree <?php if ($this->answer_info['agree_status'] == -1) { ?>active<?php } ?>" onclick="AWS.User.disagree_vote(this, '<?php echo $this->user_info['user_name']; ?>', <?php echo $this->answer_info['answer_id']; ?>)"><i data-placement="right" title="" data-toggle="tooltip" class="icon icon-disagree" data-original-title="对回复持反对意见"></i></a>
				<?php } ?>
			</span>
			<!-- end 投票栏 -->
			<span class="operate">
				<a class="icb-add-comment" data-id="<?php echo $this->answer_info['answer_id']; ?>" data-type="answer" data-comment-count="<?php echo $this->answer_info['comment_count']; ?>" data-first-click="<?php if ($this->answer_info['comment_count'] == 0) {?>hide<?php }?>" href="javascript:;"><i class="icon icon-comment"></i> <?php if ($this->answer_info['comment_count']) { ?><?php echo $this->answer_info['comment_count']; ?><?php } else { ?>0<?php } ?></a>
			</span>
			<!-- 可显示/隐藏的操作box -->
			<div class="more-operate">
				<?php if ($this->user_id) { ?>
					<?php if (($this->user_info['permission']['is_administortar'] OR $this->user_info['permission']['is_moderator']) OR ($this->answer_info['uid'] == $this->user_id AND ((TIMESTAMP - $this->answer_info['add_time']) < (get_setting('answer_edit_time') * 60) OR (!get_setting('answer_edit_time'))))) { ?>
					<a class="text-color-999" href="javascript:;" onclick="AWS.dialog('commentEdit', {answer_id:<?php echo $this->answer_info['answer_id']; ?>,attach_access_key:'<?php echo $this->attach_access_key; ?>'});"><i class="icon icon-edit"></i> <?php _e('编辑'); ?></a>
					<?php } ?>
					<?php if ($this->user_info['permission']['is_administortar'] OR $this->user_info['permission']['is_moderator']) { ?>
					<a href="javascript:;" onclick="AWS.User.answer_force_fold($(this), <?php echo $this->answer_info['answer_id']; ?>);" class="text-color-999"><i class="icon icon-fold"></i> <?php if ($this->answer_info['force_fold']) { ?><?php _e('撤消折叠'); ?><?php } else { ?><?php _e('折叠'); ?><?php } ?></a>
					<?php } else { ?>
					<a class="icb-icon-thank-tips text-color-999" data-original-title="<?php _e('这是一个没有价值的回复'); ?>" data-toggle="tooltip" title="" data-placement="bottom" onclick="AWS.User.answer_user_rate($(this), 'uninterested', <?php echo $this->answer_info['answer_id']; ?>);"><i class="icon icon-fold"></i><?php if ($this->answer_info['user_rated_uninterested']) { ?><?php _e('撤消没有帮助'); ?><?php } else { ?><?php _e('没有帮助'); ?><?php } ?></a>
					<?php } ?>

					<a href="javascript:;" onclick="AWS.dialog('favorite', {item_id:<?php echo $this->answer_info['answer_id']; ?>, item_type:'answer'});" class="text-color-999"><i class="icon icon-favor"></i> <?php _e('收藏'); ?></a>
				<?php } ?>

				<?php if ($this->user_id != $this->answer_info['uid'] AND $this->user_id) { ?>
				<a href="javascript:;"<?php if (!$this->answer_info['user_rated_thanks']) { ?> onclick="AWS.User.answer_user_rate($(this), 'thanks', <?php echo $this->answer_info['answer_id']; ?>);"<?php } ?> class="icb-icon-thank-tips text-color-999" data-original-title="<?php _e('感谢热心的回复者'); ?>" data-toggle="tooltip" title="" data-placement="bottom"><i class="icon icon-thank"></i> <?php if ($this->answer_info['user_rated_thanks']) { ?><?php _e('已感谢'); ?><?php } else { ?><?php _e('感谢'); ?><?php } ?></a>
				<?php } ?>
				<div class="btn-group pull-left">
					<a class="text-color-999 dropdown-toggle" data-toggle="dropdown">
						<i class="icon icon-share"></i> <?php _e('分享'); ?>
					</a>
					<div aria-labelledby="dropdownMenu" role="menu" class="icb-dropdown shareout pull-right">
						<ul class="icb-dropdown-list">
							<li><a onclick="AWS.User.share_out({webid: 'tsina', content: $(this).parents('.icb-item').find('.markitup-box').text()});"><i class="icon icon-weibo"></i> <?php _e('微博'); ?></a></li>
							<li><a onclick="AWS.User.share_out({webid: 'qzone', content: $(this).parents('.icb-item').find('.markitup-box').text()});"><i class="icon icon-qzone"></i> <?php _e('QZONE'); ?></a></li>
							<li><a onclick="AWS.User.share_out({webid: 'weixin', content: $(this).parents('.icb-item').find('.markitup-box').text()});"><i class="icon icon-wechat"></i> <?php _e('微信'); ?></a></li>
						</ul>
					</div>
				</div>
				<?php if (($this->user_info['permission']['is_moderator'] OR $this->user_info['permission']['is_administortar']) AND !$this->question_info['best_answer']) { ?>
				<a href="javascript:;" onclick="AWS.ajax_request(G_BASE_URL + '/question/ajax/set_best_answer/', 'answer_id=<?php echo $this->answer_info['answer_id']; ?>');" class="text-color-999"><i class="icon icon-best"></i><?php _e('最佳回复'); ?></a>
				<?php } ?>
			</div>
			<!-- end 可显示/隐藏的操作box -->

		</div>
		<!-- end 社交操作 -->
	</div>
</div>