<div class="icb-mod icb-text-align-justify">
	<div class="mod-head">
		<a href="topic/channel-hot" class="pull-right"><?php _e('更多'); ?> &gt;</a>
		<h3><?php if ($this->feature_info){ ?><?php _e('该专题包含'); ?> <?php _e('%s 个话题', $this->feature_info['topic_count']); ?><?php } else { ?><?php _e('热门话题'); ?><?php } ?></h3>
	</div>
	<div class="mod-body">
		<?php if (is_array($this->sidebar_hot_topics)) { ?>
		<?php foreach($this->sidebar_hot_topics AS $key => $val) {?>
			<dl>
				<dt class="pull-left icb-border-radius-5">
					<a href="topic/<?php echo $val['topic_title'];?>"><img alt="" src="<?php echo getModulePicUrlBySize('topic', 'mid', $val['topic_pic']); ?>" /></a>
				</dt>
				<dd class="pull-left">
					<p class="clearfix">
						<span class="article-tag">
							<a href="topic/<?php echo $val['topic_title'];?>" class="text" data-id="<?php echo $val['topic_id']; ?>"><?php echo $val['topic_title'];?></a>
						</span>
					</p>
					<p><?php _e('%s 个问题', '<b>' . $val['discuss_count'] . '</b>'); ?>, <?php _e('%s 人关注', '<b>' . $val['focus_count'] . '</b>'); ?></p>
				</dd>
			</dl>
		<?php } ?>
		<?php } ?>
	</div>
</div>
