<?php View::output('m/header_meta.php'); ?><!-- 内容 --><div class="container">	<div class="icb-login-box">		<div class="mod-head">			<img src="<?php echo G_STATIC_URL; ?>/mobile/img/login-logo.png" alt="" width="198" />			<h1><?php echo get_setting('site_name'); ?></h1>		</div>		<div class="mod-body">			<form class="icb-register-form" id="fpw_form" method="post" action="account/ajax/find_password_modify/">				<input type="hidden" name="active_code" value="<?php echo htmlspecialchars($_GET['key']); ?>"/>				<ul>					<li>						<input type="password" class="form-control" name="password" placeholder="<?php _e('新密码'); ?>" />					</li>					<li>						<input type="password" class="form-control" name="re_password" placeholder="<?php _e('确认密码'); ?>" />					</li>					<li class="captcha">						<input type="text" class="form-control" name="seccode_verify" placeholder="<?php _e('验证码'); ?>" />						<img class="pull-right" id="captcha" onclick="this.src = G_BASE_URL + '/account/captcha/' + Math.floor(Math.random() * 10000);" src="" width="96" />					</li>					<li>						<a href="" class="btn btn-primary btn-block" onclick="AWS.ajax_post($('#fpw_form'), AWS.ajax_processer, 'error_message'); return false;"><?php _e('重置密码'); ?></a>					</li>				</ul>			</form>		</div>	</div></div><!-- end 内容 --><?php View::output('m/footer.php'); ?>