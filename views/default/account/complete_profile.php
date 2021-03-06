<?php View::output('global/header_meta.php'); ?>

<div class="icb-register-box">
	<div class="mod-head">
		<img src="<?php echo G_STATIC_URL; ?>/css/<?php echo $this->template_name; ?>/img/login_logo.png" alt="" />
		<h1><?php _e('完善资料'); ?></h1>
	</div>
	<div class="mod-body">
		<form class="icb-register-form" id="profile_form" method="post" action="account/ajax/complete_profile/">
			<ul>
				<li class="alert alert-danger collapse error_message text-left">
					<i class="icon icon-delete"></i><em></em>
				</li>
				<li>
					<input class="form-control" name="user_name" type="text" placeholder="<?php _e('真实姓名'); ?>" value="<?php echo $this->user_info['user_name']; ?>" />
				</li>
				<li>
					<input class="form-control" name="email" type="text" placeholder="<?php _e('邮箱'); ?>" />
				</li>
				<li>
					<input class="form-control" name="password" type="password" placeholder="<?php _e('设置密码'); ?>" />
				</li>
				
				<li class="clearfix">
					<button class="btn btn-large btn-blue btn-block" onclick="AWS.ajax_post($('#profile_form'), AWS.ajax_processer, 'error_message'); return false;"><?php _e('完善资料'); ?></button>
				</li>
			</ul>
	</form>
	</div>
</div>

<?php View::output('global/footer.php'); ?>