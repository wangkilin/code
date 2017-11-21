<?php View::output('global/header_meta.php'); ?>

<div class="icb-register-box icb-find-pwd">
	<div class="mod-head">
		<a href=""><img src="<?php echo G_STATIC_URL; ?>/css/<?php echo $this->template_name; ?>/img/login_logo.png" alt="" /></a>
		<h1><?php _e('找回密码'); ?></h1>
	</div>
	<div class="mod-body">
		<p>
			<?php _e('密码重置链接已经发到您邮箱'); ?> <a><b><?php echo $this->email; ?></b></a><br/>
			<?php _e('请登录您的邮箱并点击密码重置链接进行密码更改'); ?>
		</p>
	</div>
	<div class="mod-footer">
		<p><b><?php _e('还没收到确认邮件') ?></b>? <?php _e('尝试到广告邮件、垃圾邮件目录里找找看'); ?></p>
	</div>
</div>

<?php View::output('global/footer.php'); ?>