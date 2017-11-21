<li uninterested_count="<?php echo $this->answer_info['uninterested_count']; ?>" force_fold="<?php if ($this->answer_info['user_rated_uninterested']) { ?>1<?php } else { ?><?php echo $this->answer_info['force_fold']; ?><?php } ?>" id="answer_list_<?php echo $this->answer_info['answer_id']; ?>">
	<div class="mod-head">
		<a class="anchor" name="answer_<?php echo $this->answer_info['answer_id']; ?>"></a>
		<?php if ($this->question_info['best_answer'] == $this->answer_info['answer_id']) { ?>
		<!-- 最佳回答 -->
		<p class="icb-best-replay"><i class="icon icon-best"></i><i class="icon icon-flag"></i></p>
		<!-- end 最佳回答 -->
		<?php } ?>
		<a href="<?php if ($this->answer_info['anonymous']) { ?>javascript:;<?php } else { ?>m/user/<?php echo $this->answer_info['uid']; ?><?php } ?>"><img class="img" width="20" src="<?php if ($this->answer_info['anonymous']) { ?><?php echo G_STATIC_URL; ?>/common/avatar-mid-img.png<?php } else { ?><?php echo get_avatar_url($this->answer_info['uid'], 'mid'); ?><?php } ?>" alt="" /></a>
		<a href="<?php if ($this->answer_info['anonymous']) { ?>javascript:;<?php } else { ?>m/user/<?php echo $this->answer_info['uid']; ?><?php } ?>"><?php if ($this->answer_info['anonymous']) { ?><?php _e('匿名用户'); ?><?php } else { ?><?php echo $this->answer_info['user_info']['user_name']; ?><?php } ?></a><?php if (!$this->answer_info['anonymous']) { ?>
			<?php if ($this->answer_info['user_info']['verified']) { ?> <i class="icon-v<?php if ($this->answer_info['user_info']['verified'] == 'enterprise') { ?> i-ve<?php } ?>" title="<?php if ($this->user['verified'] == 'enterprise') { ?>个人认证<?php } else { ?>企业认证<?php } ?>"></i><?php } ?>
			<?php echo $this->answer_info['user_info']['signature']; ?><?php } ?>
	</div>
	<div class="mod-body">
		<div class="markitup-box clearfix">
			<?php echo nl2br($this->answer_info['answer_content']); ?>

			<?php if ($this->answer_info['attachs']) {  ?>
			<div class="icb-upload-img-list">
			<?php foreach ($this->answer_info['attachs'] AS $attach) { ?>
			<?php if ($attach['is_image'] AND !($this->answer_info['insert_attach_ids'] AND in_array($attach['id'], $this->answer_info['insert_attach_ids']))) { ?>
			<a href="<?php echo $attach['attachment']; ?>" target="_blank" data-fancybox-group="thumb" rel="lightbox"><img src="<?php echo $attach['attachment']; ?>" class="img-polaroid" alt="<?php echo $attach['attach_name']; ?>" /></a>
			<?php } ?>
			<?php } ?>
			</div>
			<?php } ?>
		</div>
	</div>
	<div class="mod-footer">
		<div class="meta">
			<span class="operate">
				<a class="answer_vote agree <?php if ($this->answer_info['agree_status'] == 1) { ?> active<?php } ?>" <?php if ($this->user_id) { ?>onclick="AWS.User.agree_vote($(this), <?php echo $this->answer_info['answer_id']; ?>)"<?php } ?>><i class="icon icon-agree"></i> <b><?php echo $this->answer_info['agree_count']; ?></b></a>
				<?php if ($this->user_id != $this->answer_info['uid']) { ?>
				<a class="answer_vote disagree <?php if ($this->answer_info['agree_status'] == -1) { ?> active<?php } ?>" <?php if ($this->user_id) { ?>onclick="AWS.User.disagree_vote($(this), <?php echo $this->answer_info['answer_id']; ?>)"<?php }?>><i class="icon icon-disagree"></i></a>
				<?php } ?>
			</span>
			<?php if ($this->user_id) { ?>
			<span class="operate">
				<a class="icb-add-comment" data-id="<?php echo $this->answer_info['answer_id']; ?>" data-type="answer" <?php if ($this->question_info['lock'] OR !$this->user_info['permission']['publish_comment']) { ?> data-close="true"<?php } ?>><i class="icon icon-comment"></i> <?php echo $this->answer_info['comment_count']; ?></a>
			</span>
			<span class="operate">
				<a onclick="AWS.User.favorite('answer', <?php echo $this->answer_info['answer_id']; ?>)"><i class="icon icon-favor"></i></a>
			</span>
			<?php } ?>

			<?php if (($this->user_info['permission']['is_administortar'] OR $this->user_info['permission']['is_moderator']) OR ($this->answer_info['uid'] == $this->user_id AND ((TIMESTAMP - $this->answer_info['add_time']) < (get_setting('answer_edit_time') * 60) OR (!get_setting('answer_edit_time'))))) { ?>
			<span class="operate">
				<a class="text-color-999" href="javascript:;" onclick="AWS.dialog('commentEdit', {answer_id:<?php echo $this->answer_info['answer_id']; ?>,attach_access_key:'<?php echo $this->attach_access_key; ?>'});"><i class="icon icon-edit"></i></a>
			</span>
			<?php } ?>

			<span class="pull-right"><?php echo date_friendly($this->answer_info['add_time']); ?></span>
		</div>
	</div>
</li>