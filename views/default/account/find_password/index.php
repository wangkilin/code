<?php View::output('global/header_meta.php'); ?>

<div class="icb-register-box">
	<div class="mod-head">
		<a href=""><img src="<?php echo G_STATIC_URL; ?>/css/<?php echo $this->template_name; ?>/img/login_logo.png" alt="" /></a>
		<h1><?php _e('找回密码'); ?></h1>
	</div>
	<div class="mod-body">
		<form class="icb-register-form" id="fpw_form" method="post" action="account/ajax/request_find_password/">
			<ul>
				<li class="alert alert-danger collapse error_message">
					<i class="icon icon-delete"></i><em></em>
				</li>
				<li>
					<input class="icb-register-email form-control" type="text" placeholder="<?php _e('邮箱'); ?>" name="email" />
				</li>
				<li class="icb-register-verify">
					<img  class="auth-img pull-right" id="captcha" onclick="this.src = G_BASE_URL + '/account/captcha/' + Math.floor(Math.random() * 10000);" src="">
					<input class="form-control" type="text" name="seccode_verify" placeholder="<?php _e('验证码'); ?>"/>
				</li>
				<li class="clearfix">
					<button class="btn btn-large btn-blue btn-block" onclick="AWS.ajax_post($('#fpw_form'), AWS.ajax_processer, 'error_message'); return false;"><?php _e('下一步'); ?></button>
				</li>
			</ul>
		</form>
	</div>
</div>

<?php View::output('global/footer.php'); ?>