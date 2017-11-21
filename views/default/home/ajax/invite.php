<?php if ($this->list) { ?>
<?php foreach($this->list as $key => $val) { ?>
<div class="icb-item">
	<h4>
		<a href="question/<?php echo $val['question_info']['question_id']; ?>"><?php echo $val['question_info']['question_content']; ?></a>
	</h4>
	<p class="text-color-999">
		<span class="pull-right">
			<a class="text-color-999" onclick="AWS.User.question_invite_delete($(this).parents('div.icb-item'), <?php echo $val['question_invite_id']; ?>)">忽略</a>
		</span>
		<a class="icb-user-name" href="user/<?php echo $val['user_info']['url_token']; ?>" data-id="<?php echo $val['user_info']['uid']; ?>"><?php echo $val['user_info']['user_name']; ?></a> <?php _e('邀请你回复这个问题'); ?>
	</p>
</div>
<?php } ?>
<?php } ?>

