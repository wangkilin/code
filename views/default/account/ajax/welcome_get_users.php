<?php if ($this->users_list) { ?>
<?php foreach ($this->users_list AS $key => $val) { ?>
<li>
	<a class="img"><img src="<?php echo get_avatar_url($val['uid'], 'mid'); ?>" alt="" /></a>
	<a class="btn btn-success follow btn-mini pull-right<?php if ($val['follow_check']) { ?> active<?php } ?>" href="javascript:;" onclick="AWS.User.follow($(this), 'user', <?php echo $val['uid']; ?>);"><span><?php if ($val['follow_check']) { ?><?php _e('取消'); ?><?php } ?><?php _e('关注'); ?> <em>|</em> <b><?php echo $val['fans_count']; ?></b></span></a>
	<p>
		<a class="icb-user-name" href="user/<?php echo $val['url_token']; ?>"><?php echo $val['user_name']; ?> <?php if ($val['verified']) { ?><i class="icon-v <?php if ($val['verified'] == 'enterprise') { ?> i-ve<?php } ?>" title="<?php if ($val['verified'] == 'enterprise') { ?>企业认证<?php } else { ?>个人认证<?php } ?>"></i><?php } ?></a>
	</p>
	<p>
		<span><i class="icb-icon i-user-prestige"></i><?php _e('威望'); ?>: <em class="icb-text-color-green"><?php echo $val['reputation']; ?></em></span>
	</p>
	<p class="signature icb-hide-txt">
		<?php echo $val['signature']; ?>
	</p>
</li>
<?php } ?>
<?php } ?>