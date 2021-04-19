<?php View::output('m/header_meta.php'); ?>
<!-- header -->
<div class="header">
	<a class="logo" href="m/"><img src="<?php echo G_STATIC_URL; ?>/mobile/img/icodebang_white_face_logo@2x.png" alt="" width="48" /></a>
	<span class="pull-right">
		<?php if ($this->user_id) { ?>
			<a href="m/user/<?php echo $this->user_info['url_token']; ?>">
				<?php echo $this->user_info['user_name']; ?>
				<img src="<?php echo get_avatar_url($this->user_id, 'mid'); ?>" alt="" class="img" width="25" />
			</a>
		<?php } else {?>
			<a href="m/login/" class="btn btn-mini btn-primary btn-normal"><?php _e('登录'); ?></a>
			<?php if (get_setting('register_type') == 'open') { ?><a href="m/register/" class="btn btn-mini btn-success btn-normal"><?php _e('注册'); ?></a><?php } ?>
		<?php } ?>
	</span>
</div>
<!-- end header -->
