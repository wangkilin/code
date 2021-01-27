<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="robots" content="noarchive" />
	<meta content="user-scalable=no, width=device-width, initial-scale=1.0, maximum-scale=1.0" name="viewport">
	<!--<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<link rel="apple-touch-startup-image" href="/startup.png" />
	<link rel="apple-touch-startup-image" sizes="640x1096" href="/startup_5.png" />-->
	<meta name="keywords" content="<?php echo $this->_meta_keywords; ?>" />
	<meta name="description" content="<?php echo $this->_meta_description; ?>" />
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo G_STATIC_URL; ?>/common/apple_touch_icon.png" />
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo G_STATIC_URL; ?>/common/apple_touch_icon_114x114.png" />
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo G_STATIC_URL; ?>/common/apple_touch_icon_72x72.png" />
	<link rel="apple-touch-icon-precomposed" href="<?php echo G_STATIC_URL; ?>/common/apple_touch_icon_57x57.png" />

	<link href="<?php echo G_STATIC_URL; ?>/css/bootstrap.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo G_STATIC_URL; ?>/css/icon.css" rel="stylesheet" type="text/css" />

	<?php if (is_array($this->_import_css_files)) { ?>
	<?php foreach ($this->_import_css_files AS $import_css) { ?>
	<link href="<?php echo $import_css; ?>?v=<?php echo G_VERSION_BUILD; ?>" rel="stylesheet" type="text/css" />
	<?php } ?>
	<?php } ?>

	<?php $post_hash_var_name = strtoupper(md5(TIMESTAMP . $this->userinfo['salt'])); ?>
	<script type="text/javascript">
		var _<?php echo $post_hash_var_name; ?>="<?php echo new_post_hash(); ?>";
		var G_POST_HASH=_<?php echo $post_hash_var_name; ?>;
		var G_INDEX_SCRIPT = "<?php if (get_setting('url_rewrite_enable') != 'Y') echo G_INDEX_SCRIPT; ?>";
		var G_SITE_NAME = "<?php echo addcslashes(get_setting('site_name'), '\"'); ?>";
		var G_BASE_URL = "<?php echo base_url(); ?>/<?php echo rtrim(G_INDEX_SCRIPT, '/'); ?>";
		var G_STATIC_URL = "<?php echo G_STATIC_URL; ?>";
		var G_UPLOAD_ENABLE = "<?php echo get_setting('upload_enable'); ?>";
		var G_UPLOAD_URL = "<?php echo get_setting('upload_url'); ?>";
		var G_USER_ID = "<?php echo $this->user_id; ?>";
		var G_USER_NAME = "<?php echo addcslashes($this->user_info['user_name'], '\"'); ?>";
		var G_UNREAD_NOTIFICATION = 0;
		var G_NOTIFICATION_INTERVAL = <?php echo intval(get_setting('unread_flush_interval')) * 1000; ?>;
		var G_CAN_CREATE_TOPIC = "<?php echo $this->user_info['permission']['create_topic']; ?>";
		var G_TIMESTAMP = <?php echo TIMESTAMP; ?>;

		<?php if (human_valid('question_valid_hour')) { ?>
		var G_QUICK_PUBLISH_HUMAN_VALID = true;
		<?php } ?>
	</script>

	<?php if (is_array($this->_import_js_files)) { ?>
	<?php foreach ($this->_import_js_files AS $import_js) { ?>
	<script src="<?php echo $import_js; ?>?v=<?php echo G_VERSION_BUILD; ?>" type="text/javascript"></script>
	<?php } ?>
	<?php } ?>

	<title><?php echo $this->page_title; ?></title>
</head>
<body class="<?php echo $this->body_class; ?>">
