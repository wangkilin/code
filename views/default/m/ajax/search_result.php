<?php if ($this->search_result) { ?>
<?php foreach ($this->search_result AS $key => $val) { ?>
	<?php if ($val['type'] == 'users') { ?>
		<li class="user">
			<a href="<?php echo $val['url']; ?>">
				<img src="<?php echo get_avatar_url($val['search_id'], 'mid'); ?>" alt="" class="img" width="25" />
				<?php echo $val['name']; ?>
			</a>
		</li>
	<?php } else if ($val['type'] == 'topics') { ?>
		<li class="topic">
			<span class="article-tag">
				<a href="<?php echo $val['url']; ?>" class="text"><?php echo $val['name']; ?></a>
			</span>
			 <span class="color-999"><?php echo cjk_substr($val['topic_description'], 0, 64, 'UTF-8', '...'); ?></span>
		</li>
	<?php } else if ($val['type'] == 'questions') { ?>
		<li class="question">
			<a href="<?php echo $val['url']; ?>"><?php echo $val['name']; ?><span class="pull-right color-999"><?php _e('%s 个回复', $val['detail']['answer_count']); ?></span></a>
		</li>
	<?php } ?>
<?php } ?>
<?php } ?>