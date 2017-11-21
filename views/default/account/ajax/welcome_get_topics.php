<?php if ($this->topics_list) { ?>
<?php foreach ($this->topics_list AS $key => $val) { ?>
<li>
	<a class="img" ><img src="<?php echo getMudulePicUrlBySize('topic', 'mid', $val['topic_pic']); ?>" alt="" /></a>
	<a href="javascript:;" class="btn btn-success follow btn-mini pull-right<?php if ($val['isFollowed']) { ?> active<?php } ?>" onclick="AWS.User.follow($(this), 'topic', <?php echo $val['topic_id']; ?>);"><span><?php if ($val['isFollowed']) { ?><?php _e('取消关注'); ?><?php } else { ?><?php _e('关注'); ?><?php } ?></span>
			 <em>|</em> <b><?php echo $val['focus_count']; ?></b>
		</a>
	<p class="clearfix">
		<span class="article-tag"><a class="text"><?php echo $val['topic_title']; ?></a></span>
	</p>
	<p>
		<span class="text-color-999"><?php _e('%s 个讨论', $val['discuss_count']); ?></span>
	</p>
</li>
<?php } ?>
<?php } ?>