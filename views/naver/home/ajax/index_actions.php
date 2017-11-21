<?php if ($this->list) { ?>
<?php if ($_GET['page'] == 0 AND count($this->list) > 0) { ?>
<a href="javascript:;" class="aw-load-more-content warmming" style="display:none" onclick="reload_list(0);" id="new_actions_tip"><span><?php _e('%s 条新动态, 点击查看', '<span id="new_action_num"></span>'); ?></span></a>

<script type="text/javascript">
	if (typeof(check_actions_new) == 'function')
	{
		if (typeof checkactionsnew_handle != 'undefined')
		{
			clearInterval(checkactionsnew_handle);
		}
		
		checkactionsnew_handle = setInterval(function () {
			check_actions_new('0', '<?php echo time(); ?>');
		}, 60000);
	}
</script>
<?php } ?>
<?php foreach ($this->list AS $key => $val) { ?>
<div class="aw-item aw-two-cloum" data-history-id="<?php echo $val['history_id']; ?>">
	<div class="aw-mod-head">
		<?php if ($val['question_info']['anonymous'] == 0 OR !$val['question_info']) { ?><a data-id="<?php echo $val['user_info']['uid']; ?>" class="aw-user-img aw-border-radius-5 pull-right" href="user/<?php echo $val['user_info']['url_token']; ?>"><img src="<?php echo get_avatar_url($val['user_info']['uid'], 'mid'); ?>" alt="<?php echo $val['user_info']['user_name']; ?>" /></a><?php } else { ?><a href="javascript:;" class="aw-user-img aw-border-radius-5 pull-right"><img src="<?php echo G_STATIC_URL; ?>/common/avatar-mid-img.png" alt="<?php _e('匿名用户'); ?>" /></a><?php } ?>
		
		<h4><a href="<?php echo $val['link']; ?>"><?php echo $val['title']; ?></a></h4>
		
		<p class="aw-text-color-999">
			<?php if ($val['last_action_str']) { ?><?php echo $val['last_action_str']; ?> • <?php echo date_friendly($val['add_time']); ?> • <?php } ?>
			
			<?php if (isset($val['article_info']['comments'])) { ?>
				<a href="<?php echo $val['link']; ?>" class="aw-text-color-999"><?php _e('%s 个评论', $val['article_info']['comments']); ?></a>
			<?php } else { ?>
				<a href="<?php echo $val['link']; ?>" class="aw-text-color-999"><?php _e('%s 个回复', $val['question_info']['answer_count']); ?></a>
					<?php if ($this->user_id AND !$_GET['filter']) { ?>
				</a>
				<?php } ?>
				<?php if ($_GET['filter'] == 'focus') { ?>
					 • <?php if ($val['topics']) { ?><?php _e('已添加到'); ?> 
					<?php foreach($val['topics'] as $t_key => $t_val) { if ($t_key > 2) { break; } ?><a href="topic/<?php echo $t_val['url_token']; ?>" class="aw-topic-name" data-id="<?php echo $t_val['topic_id']; ?>"><?php echo $t_val['topic_title']; ?></a> <?php } ?><?php if (sizeof($val['topics']) > 3) { ?> <?php _e('等'); ?> <?php _e('%s 个话题', sizeof($val['topics'])); ?><?php } else { ?><?php _e('话题'); ?><?php } ?>
					<?php } else { ?><a href="question/<?php echo $val['question_info']['question_id']; ?>" class="aw-text-color-999"><?php _e('添加话题'); ?></a><?php } ?>
				<?php } else if (!$_GET['filter']) { ?> • <a class="aw-text-color-999" href="javascript:;" onClick="question_uninterested($(this).parents('div.aw-item'), <?php echo $val['question_info']['question_id']; ?>);"><?php _e('不感兴趣'); ?></a><?php } ?>
			<?php } ?>
		</p>
	
	</div>
	
</div>
<?php } ?>
<?php } ?>