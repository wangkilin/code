<?php if ($this->users_list) { ?>
<?php foreach ($this->users_list AS $key => $val) { ?>
<li>
	<div class="mod-head">
		<a class="icb-user-img pull-left icb-border-radius-5" data-id="<?php echo $val['uid']; ?>" href="user/<?php echo $val['url_token']; ?>">
			<img src="<?php echo get_avatar_url($val['uid'],'mid',$val['avatar_file']); ?>" alt="<?php echo $val['user_name']; ?>" />
		</a>
		<p><a href="user/<?php echo $val['url_token']; ?>"><?php echo $val['user_name']; ?></a></p>
	</div>
	<div class="mod-body">
		<p class="text-color-999 icb-hide-txt"><?php echo $val['signature']; ?></p>
	</div>
	<div class="mod-footer meta">
		<span><i class="icon icon-prestige"></i><?php _e('威望'); ?> <em class="icb-text-color-green"><?php echo $val['reputation']; ?></em></span>
		<span><i class="icon icon-agree"></i><?php _e('赞同'); ?> <em class="icb-text-color-orange"><?php echo $val['agree_count']; ?></em></span>
		<!-- <span><i class="icon icon-thank"></i><?php _e('感谢'); ?> <em class="icb-text-color-orange"><?php echo $val['thanks_count']; ?></em></span> -->
	</div>
</li>
<?php } ?>
<?php } ?>