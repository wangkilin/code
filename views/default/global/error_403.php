<!doctype html>
<html>
<head>
	<meta charset="utf-8" />
	<title><?php _e('文件未找到'); ?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo G_STATIC_URL; ?>/css/bootstrap.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo G_STATIC_URL; ?>/css/icon.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo G_STATIC_URL; ?>/css/default/common.css" />
	<script type="text/javascript">
	var _<?php echo $post_hash_var_name; ?>="<?php echo new_post_hash(); ?>";
	var G_POST_HASH=_<?php echo $post_hash_var_name; ?>;
	var G_INDEX_SCRIPT = "<?php if (get_setting('url_rewrite_enable') != 'Y') echo G_INDEX_SCRIPT; ?>";
	var G_SITE_NAME = "<?php echo addcslashes(get_setting('site_name'), '\"'); ?>";
	var G_BASE_URL = "<?php echo base_url(); ?>/<?php echo rtrim(G_INDEX_SCRIPT, '/'); ?>";
	var G_STATIC_URL = "<?php echo G_STATIC_URL; ?>";
	var G_UPLOAD_URL = "<?php echo get_setting('upload_url'); ?>";
	var G_USER_ID = "<?php echo $this->user_id; ?>";
	var G_USER_NAME = "<?php echo addcslashes($this->user_info['user_name'], '\"'); ?>";
	var G_UPLOAD_ENABLE = "<?php echo get_setting('upload_enable'); ?>";
	var G_UNREAD_NOTIFICATION = 0;
	var G_NOTIFICATION_INTERVAL = <?php echo intval(get_setting('unread_flush_interval')) * 1000; ?>;
	var G_CAN_CREATE_TOPIC = "<?php echo $this->user_info['permission']['create_topic']; ?>";

	<?php if (human_valid('question_valid_hour')) { ?>
	var G_QUICK_PUBLISH_HUMAN_VALID = true;
	<?php } ?>
</script>
	<script src="<?php echo G_STATIC_URL; ?>/js/jquery.2.js" type="text/javascript"></script>
<!--[if lte IE 8]>
	<script type="text/javascript" src="<?php echo G_STATIC_URL; ?>/js/respond.js"></script>
	<![endif]-->
</head>
<body class="icb-404">
	<div class="icb-404-wrap container">
		<div class="row">
			<img src="<?php echo G_STATIC_URL; ?>/common/403.png" alt="403">
			<p><?php _e('禁止访问'); ?>，<?php _e('请检查是否存在恶意访问行为！'); ?></p>
            <!--
            <?php
            $keys = array('HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR', 'HTTP_HOST', 'REQUEST_URI', 'HTTP_USER_AGENT');
            foreach ($keys as $_key) {
                if (isset($_SERVER[$_key])) {
                    echo $_key, ': ', $_SERVER[$_key], "\r\n";
                }
            }
            echo "\r\n\r\n";
            $properties = get_object_vars($this);
            foreach ($properties as $_key => $_value) {
                if ($_key[0]=='_') {
                    continue;
                }
                echo $_key, ': ', var_export($_value), "\r\n";
            }
            ?>
            -->
		</div>
	</div>
	<?php View::output('global/footer.php'); ?>
