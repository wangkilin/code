<?php View::output('m/header_meta.php'); ?><!-- 内容 --><div class="container">	<div class="icb-login-box">		<div class="mod-head">			<img src="<?php echo G_STATIC_URL; ?>/mobile/img/login-logo.png" alt="" width="198" />			<h1><?php echo get_setting('site_name'); ?></h1>		</div>		<div class="mod-body">			<div class="verify">				<div class="content">					<?php _e('密码重置链接已经发到您邮箱'); ?>					<span><?php echo $this->email; ?></span>					<?php _e('请登录您的邮箱并点击密码重置链接进行密码更改');?>。				</div>			</div>		</div>	</div></div><!-- end 内容 --><?php View::output('m/footer.php'); ?>