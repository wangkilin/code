<?php View::output('m/header_meta.php'); ?>

<!-- 内容 -->
<div class="container">
	<div class="icb-login-box">
		<div class="mod-head">
			<img src="<?php echo G_STATIC_URL; ?>/mobile/img/login-logo.png" alt="" width="198" />
		</div>
		<div class="mod-body">
			<div class="bind-form">
				<p class="text-center color-999"><?php _e('请填写下列表单完成与微信账号的绑定'); ?></p>
				<form id="login_form" method="post" onsubmit="return false;" action="account/ajax/login_process/">
					<input type="hidden" name="return_url" value="<?php echo get_js_url('/m/weixin/binding/redirect-' . urlencode($_GET['redirect'])); ?>" />
					<ul>
						<li>
							<input type="text" class="form-control" id="user_name" name="user_name" placeholder="<?php _e('邮箱'); ?>/<?php _e('用户名'); ?>" />
						</li>
						<li>
							<input type="password" class="form-control" id="password" name="password" placeholder="<?php _e('密码'); ?>" onkeydown="if (event.keyCode == 13) { ajax_post($('#login_form')); }" />
						</li>
						<li>
							<a class="btn btn-primary btn-block" onclick="AWS.ajax_post($('#login_form'));"><?php _e('绑定微信'); ?></a>
						</li>
						<?php if ($this->register_url) { ?>
						<li>
							<p class="text-center color-999"><?php _e('或者点击下面的按钮生成帐号'); ?></p>
							<a href="<?php echo $this->register_url; ?>" class="btn-submit btn-success btn btn-block"><?php _e('生成帐号')?></a>
						</li>
						<li>
							<p class="text-center color-999"><?php _e('如果您还未注册, 请先注册问答账号'); ?></p>
							<a class="btn btn-success btn-block" onclick="$('.bind-confirm, .bind-form').hide();$('.register-confirm, .register-form').show();"><?php _e('注册')?></a>
						</li>
						<?php } ?>
					</ul>
				</form>
			</div>
			<div class="register-form collapse">
				<p class="text-center color-999"><?php _e('请填写下列表单完成账号注册'); ?></p>
				<form id="register_form" method="post" onsubmit="return false;" action="account/ajax/register_process/">
					<input type="hidden" name="agreement_chk" value="agree" />
					<input type="hidden" name="return_url" value="<?php echo get_js_url('/m/weixin/binding/redirect-' . urlencode($_GET['redirect'])); ?>" />
					<ul>
						<li>
							<input type="text" class="form-control" id="user_name" name="user_name" placeholder="<?php _e('用户名'); ?>" value="<?php echo $this->access_user['nickname']; ?>" />
						</li>
						<li>
							<input type="text" class="form-control" id="email" name="email" placeholder="<?php _e('邮箱'); ?>" />
						</li>
						<li>
							<input type="password" class="form-control" id="password" name="password" placeholder="<?php _e('密码'); ?>" onkeydown="if (event.keyCode == 13) { ajax_post($('#register_form')); }" />
						</li>
						<li>
							<a class="btn btn-primary btn-block" onclick="AWS.ajax_post($('#register_form')); return false;"><?php _e('注册')?></a>
						</li>
						<?php if ($this->register_url) { ?>
						<li>
							<p class="text-center color-999"><?php _e('或者点击下面的按钮生成帐号'); ?></p>
							<a href="<?php echo $this->register_url; ?>" class="btn-submit btn-success btn btn-block"><?php _e('生成帐号')?></a>
						</li>
						<?php } ?>
						<li>
							<p class="text-center color-999"><?php _e('已有账号'); ?></p>
							<a class="btn btn-submit btn-success btn-block" onclick="$('.register-confirm, .register-form').hide();$('.bind-confirm, .bind-form').show();"><?php _e('绑定微信'); ?></a>
						</li>
					</ul>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- DO NOT REMOVE -->
<div id="icb-modal-window" class="icb-modal-window"></div>

<div style="display:none">
	<?php echo get_setting('statistic_code'); ?>
</div>
<!-- / DO NOT REMOVE -->

</body>
</html>