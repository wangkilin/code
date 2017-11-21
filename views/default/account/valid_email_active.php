<?php View::output('global/header_meta.php'); ?>

<div class="icb-register-box">
	<div class="mod-head">
		<a href=""><img src="<?php echo G_STATIC_URL; ?>/css/<?php echo $this->template_name; ?>/img/login_logo.png" alt="" /></a>
		<h1><?php _e('邮箱验证'); ?></h1>
	</div>
	<div class="mod-body">
		<p>
			<?php _e('感谢您的注册, 请点击继续激活你的账户'); ?>
		</p>
		<p class="icb-padding10">
			<form action="account/ajax/valid_email_active/" method="post" id="active_form">
				<input type="hidden" name="active_code" value="<?php echo $this->active_code; ?>" />
				<button class="btn btn-large btn-blue btn-block" onclick="AWS.ajax_post($('#active_form')); return false;"><?php _e('下一步'); ?></button>
			</form>	
		</p>
	</div>
</div>

<?php View::output('global/footer.php'); ?>
