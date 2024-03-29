<?php View::output('global/header_meta.php'); ?>

<div class="icb-register-box">
	<div class="mod-head">
		<a href=""><img src="<?php echo G_STATIC_URL; ?>/css/<?php echo $this->template_name; ?>/img/login_logo.png" alt="" /></a>
		<h1><?php _e('找回密码'); ?></h1>
	</div>
	<div class="mod-body">
		<form class="icb-register-form" id="fpw_form" method="post" action="account/ajax/find_password_modify/">
			<input type="hidden" name="active_code" value="<?php echo htmlspecialchars($_GET['key']); ?>"/>
			<ul>
				<li class="error alert-danger collapse error_message">
					<p><i class="icon-remove"></i><em></em></p>
				</li>
				<li>
					<input class="icb-register-email form-control" name="password" type="password" placeholder="<?php _e('密码'); ?>" />
				</li>
				<li>
					<input class="icb-register-email form-control" name="re_password" type="password" placeholder="<?php _e('再次输入密码'); ?>" />
				</li>
				
				<li class="icb-register-verify">
					<img id="captcha" onclick="this.src = G_BASE_URL + '/account/captcha/' + Math.floor(Math.random() * 10000);" src="" class="auth-img pull-right"/>
					<input class="form-control" type="text" name="seccode_verify" placeholder="<?php _e('验证码'); ?>" />
				</li>
				<li class="clearfix">
					<button class="btn btn-large btn-blue btn-block" onclick="AWS.ajax_post($('#fpw_form'), AWS.ajax_processer, 'error_message'); return false;"><?php _e('重置密码'); ?></button>
				</li>
			</ul>
		</form>
	</div>
</div>

<?php View::output('global/footer.php'); ?>