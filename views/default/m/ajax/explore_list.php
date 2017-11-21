<?php if ($this->posts_list) { ?>
<?php foreach($this->posts_list as $key => $val) { ?>
<li>
	<div class="mod-body">
		<?php if ($val['question_id']) { ?>
			<h2><a href="m/question/<?php echo $val['question_id']; ?>"><?php echo $val['question_content']; ?></a></h2>
		<?php } else { ?>
			<h2><a href="m/article/<?php echo $val['id']; ?>"><?php echo $val['title']; ?></a></h2>
		<?php } ?>
	</div>
	<div class="mod-footer">
		<?php if ($val['anonymous'] == 0) { ?>
			<?php if ($val['question_id']) { ?>
				<?php if ($val['answer_count'] > 0) { ?>
					<a data-id="<?php echo $val['answer_info']['user_info']['uid']; ?>" href="user/<?php echo $val['answer_info']['user_info']['url_token']; ?>" rel="nofollow">
						<img class="img" src="<?php echo get_avatar_url($val['answer_info']['user_info']['uid'], 'max'); ?>" alt="" width="20" />
					</a>
				<?php } else { ?>
					<a data-id="<?php echo $val['user_info']['uid']; ?>" href="user/<?php echo $val['user_info']['url_token']; ?>" rel="nofollow">
						<img class="img" src="<?php echo get_avatar_url($val['user_info']['uid'], 'max'); ?>" alt="" width="20" />
					</a>
				<?php } ?>
			<?php } ?>
		<?php } else { ?>
		<a><img width="20" src="<?php echo G_STATIC_URL; ?>/common/avatar-max-img.png" alt="<?php _e('匿名用户'); ?>" title="<?php _e('匿名用户'); ?>" /></a>
		<?php } ?>
		<?php if ($val['question_id']) { ?>
		<?php if ($val['answer_count'] > 0) { ?>
			<?php if ($val['answer_info']['anonymous']) { ?><a href="javascript:;"><?php _e('匿名用户'); ?></a><?php } else { ?><a href="m/user/<?php echo $val['answer_info']['user_info']['url_token']; ?>"><?php echo $val['answer_info']['user_info']['user_name']; ?></a><?php } ?> <?php _e('回复了问题'); ?>
				<span class="pull-right color-999"><?php echo date_friendly($val['update_time'], 604800, 'Y-m-d'); ?></span>
		<?php } else { ?>
			<?php if ($val['anonymous'] == 0) { ?><a href="m/user/<?php echo $val['user_info']['url_token']; ?>"><?php echo $val['user_info']['user_name']; ?></a><?php } else { ?><a href="javascript:;"><?php _e('匿名用户'); ?></a><?php } ?> <?php _e('发起了问题'); ?>
				<span class="pull-right color-999"><?php echo date_friendly($val['add_time'], 604800, 'Y-m-d'); ?></span>
		<?php } ?>
		<?php } else { ?>
			<a href="m/user/<?php echo $val['user_info']['url_token']; ?>"><?php echo $val['user_info']['user_name']; ?></a> 
			<?php _e('发起了文章'); ?>
			<span class="pull-right color-999"><?php echo date_friendly($val['add_time'], 604800, 'Y-m-d'); ?></span>
		<?php } ?>
	</div>

</li>
<?php } ?>
<?php } ?>