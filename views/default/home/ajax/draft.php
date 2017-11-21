<?php if ($this->drafts) { ?>
<?php foreach ($this->drafts AS $key => $val) { ?>
<div class="icb-item">
	<h4><a href="question/<?php echo $val['item_id']; ?>#!answer_form"><?php echo $val['question_info']['question_content']; ?></a></h4>
	<div class="markitup-box"><?php echo nl2br(htmlspecialchars($val['data']['message'])); ?></div>
	<div class="meta">
		<span class="pull-right">
			<a class="text-color-999" onclick="AWS.User.delete_draft(<?php echo $val['item_id']; ?>, 'answer'); $(this).parents('.icb-item').fadeOut();"><?php _e('删除草稿'); ?></a> • 
			<a class="text-color-999" href="question/<?php echo $val['item_id']; ?>#!answer_form"><?php _e('编辑草稿'); ?></a>
		</span>
		<?php echo date_friendly($val['time']); ?>
	</div>
</div>
<?php } ?>
<?php } ?>