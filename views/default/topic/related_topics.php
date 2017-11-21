<div class="icb-mod topic-about">
	<div class="mod-head">
		<h3><?php _e('相关话题'); ?></h3>
	</div>
	<div class="mod-body" data-type="topic">
		<div class="icb-article-title-box" data-type="topic" data-id="<?php echo $this->topic_info['topic_id']; ?>">
			<div class="tag-queue-box clearfix">
				<?php if ($this->related_topics) { ?>
					<?php foreach($this->related_topics as $key => $val){ ?>
					<span class="article-tag" data-id="<?php echo $val['topic_id']; ?>">
						<a class="text" href="topic/<?php echo $val['url_token']; ?>"><?php echo $val['topic_title']; ?></a>
					</span>
					<?php } ?>
				<?php } ?>
				<?php if ($this->user_info['permission']['is_administortar'] OR $this->user_info['permission']['is_moderator'] OR ($this->topic_info['topic_lock'] == 0 && $this->user_info['permission']['edit_topic'])) { ?>
					<?php if ($this->related_topics) { ?>
					<span class="article-tag icb-edit-topic icon-inverse"><i class="icon icon-edit"></i><?php _e('管理话题'); ?></span>
					<?php } else { ?>
					<span class="article-tag icb-edit-topic icon-inverse"><i class="icon icon-edit"></i><?php _e('添加话题'); ?></span>
					<?php } ?>
				<?php } ?>
			</div>
		</div>
	</div>
</div>