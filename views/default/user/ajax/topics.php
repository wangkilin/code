<?php if ($this->topic_list) { ?>
<?php foreach($this->topic_list as $key => $val) {?>
<li>
	<div class="mod-head">
		<a class="icb-topic-img pull-left icb-border-radius-5" data-id="<?php echo $val['topic_id']; ?>" href="topic/<?php echo $val['url_token']; ?>">
			<img src="<?php echo getModulePicUrlBySize('topic', 'mid', $val['topic_pic']); ?>" alt="<?php echo $val['topic_title']; ?>" />
		</a>
		<p><a class="icb-topic-name" data-id="<?php echo $val['topic_id']; ?>" href="topic/<?php echo $val['url_token']; ?>"><span><?php echo $val['topic_title']; ?></span></a></p>
	</div>
	<div class="mod-footer">
		<p class="icb-user-center-follow-meta">
			<?php _e('%s 个讨论', $val['discuss_count']); ?>
			 • 
			<?php _e('%s 个关注', $val['focus_count']); ?>
		</p>
	</div>
</li>
<?php } ?>			
<?php } ?>