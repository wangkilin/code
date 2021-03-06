<?php foreach ($this->hot_topics_list AS $key => $val) { ?>
<li>
	<div class="mod-head clearfix">
		<a href="m/topic/<?php echo $val['url_token']; ?>"  class="img">
			<img src="<?php echo getModulePicUrlBySize('topic', 'mid', $val['topic_pic']); ?>" alt="" width="50" />
		</a>
		<div class="content">
			<span class="article-tag clearfix">
				<a href="m/topic/<?php echo $val['url_token']; ?>" class="text"><?php echo $val['topic_title']; ?></a>
			</span>
			<p class="color-999">
				<?php echo cjk_substr($val['topic_description'], 0, 32, 'UTF-8', '...'); ?>
			</p>
		</div>
	</div>
	<div class="mod-footer active">
		<a><?php _e('%s 个讨论', $val['discuss_count']); ?></a>
		<span class="pull-left"> • </span>
		<a><?php _e('%s 个关注', $val['focus_count']); ?></a>
	</div>
</li>
<?php } ?>