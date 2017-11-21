<div class="icb-mod icb-text-align-justify">
	<div class="mod-head">
		<a href="user/" class="pull-right"><?php _e('更多'); ?> &gt;</a>
		<h3><?php _e('热门用户'); ?></h3>
	</div>
	<div class="mod-body">
		<?php if (is_array($this->sidebar_hot_users)) { ?>
		<?php foreach($this->sidebar_hot_users AS $key => $val) {?>	
		<dl>
			<dt class="pull-left icb-border-radius-5">
				<a href="user/<?php echo $val['url_token']; ?>"><img alt="" src="<?php echo get_avatar_url($val['uid'], 'mid'); ?>" /></a>
			</dt>
			<dd class="pull-left">
				<a href="user/<?php echo $val['url_token']; ?>" data-id="<?php echo $val['uid']; ?>" class="icb-user-name"><?php echo $val['user_name']; ?><?php if ($val['verified']) { ?><i class="icon-v<?php if ($val['verified'] == 'enterprise') { ?> i-ve<?php } ?>" title="<?php if ($val['verified'] == 'enterprise') { ?>企业认证<?php } else { ?>个人认证<?php } ?>"></i><?php } ?>
						<?php echo $val['signature']; ?></a>
				<p class="signature"><?php echo $val['signature']; ?></p>
				<p><?php _e('%s 个问题', '<b>' . $val['answer_count'] . '</b>'); ?>, <?php _e('%s 次赞同', '<b>' . $val['agree_count'] . '</b>'); ?></p>
			</dd>
		</dl>
		<?php } ?>
		<?php } ?>
	</div>
</div>