<div class="icb-mod side-nav">
	<div class="mod-body">
		<ul>
			<li><a href="home/#all" rel="all"><i class="icon icon-home"></i><?php _e('最新动态'); ?></a></li>
			<li><a href="home/#draft_list__draft" rel="draft_list__draft"><i class="icon icon-draft"></i><?php _e('我的草稿'); ?><?php if ($this->user_info['draft_count']) { ?><span class="badge"><?php echo $this->user_info['draft_count']; ?></span><?php } ?></a></li>
			<li><a href="favorite/"<?php if ($_GET['app'] == 'favorite') { ?> class="active"<?php } ?>><i class="icon icon-favor"></i><?php _e('我的收藏'); ?></a></li>
			<li><a href="home/#all__focus" rel="all__focus"><i class="icon icon-check"></i><?php _e('我关注的问题'); ?></a></li>
			<li><a href="home/#focus_topic__focus" rel="focus_topic__focus"><i class="icon icon-mytopic"></i><?php _e('我关注的话题'); ?></a></li>
			<li><a href="home/#invite_list__invite" rel="invite_list__invite"><i class="icon icon-invite"></i><?php _e('邀请我回复的问题'); ?><?php if ($this->user_info['invite_count']) { ?><span class="badge"><?php echo $this->user_info['invite_count']; ?></span><?php } ?></a></li>
		</ul>
	</div>
</div>

<div class="icb-mod side-nav">
	<div class="mod-body">
		<ul>
			<li><a href="topic/"><i class="icon icon-topic"></i><?php _e('所有话题'); ?></a></li>
			<li><a href="user/"><i class="icon icon-user"></i><?php _e('所有用户'); ?></a></li>
			<li><a href="invitation/"<?php if ($_GET['app'] == 'invite') { ?> class="active"<?php } ?>><i class="icon icon-inviteask"></i><?php _e('邀请好友加入'); ?> <?php if ($this->user_info['invitation_available']) { ?><em class="badge"><?php echo $this->user_info['invitation_available']; ?></em><?php } ?></a></li>
		</ul>
	</div>
</div>