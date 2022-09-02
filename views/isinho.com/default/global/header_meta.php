<!DOCTYPE html>
<html lang="zh-CN">
<head>
<link rel="dns-prefetch" href="//www.isinho.com">
<!-- <link rel="canonical" href="" /> -->
<meta content="text/html;charset=utf-8" http-equiv="Content-Type" />
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
<meta http-equiv="X-UA-Compatible" content="<?php echo X_UA_COMPATIBLE; ?>" />
<meta name="renderer" content="webkit" />
<title><?php echo $this->page_title; ?></title>
<meta name="keywords" content="<?php echo $this->_meta_keywords; ?>" />
<meta name="description" content="<?php echo $this->_meta_description; ?>"  />
<link rel="icon" type="image/png" href="<?php echo G_STATIC_URL; ?>/isinho.com/favicon.png?v=<?php echo G_VERSION_BUILD; ?>">
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="<?php echo G_STATIC_URL; ?>/isinho.com/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo G_STATIC_URL; ?>/css/icomoon/style.css" />
<!-- Sinhe Style CSS -->
<link rel="stylesheet" href="<?php echo G_STATIC_URL; ?>/isinho.com/style.css">
<link rel="stylesheet" href="<?php echo G_STATIC_URL; ?>/isinho.com/default.css">
<link rel="stylesheet" href="<?php echo G_STATIC_URL; ?>/isinho.com/responsive.css">
<?php if (is_array($this->_import_css_files)) { ?>
<?php foreach ($this->_import_css_files AS $import_css) { ?>
<link href="<?php echo $import_css; ?>?v=<?php echo G_VERSION_BUILD; ?>" rel="stylesheet" type="text/css" />
<?php } ?>
<?php } ?>
<link rel="stylesheet" href="<?php echo G_STATIC_URL; ?>/isinho.com/blue.css" id="theme-switch">
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->



<script type="text/javascript">
var G_STATIC_URL = '<?php echo G_STATIC_URL; ?>/';

	var iScale = 1;
	iScale = iScale / window.devicePixelRatio;

	document.write('<meta name="viewport" content="height=device-height,width=device-width,initial-scale='+iScale+',minimum-scale='+iScale+',maximum-scale='+iScale+',user-scalable=no" />')

	var iWidth = document.documentElement.clientWidth;
	document.getElementsByTagName('html')[0].style.fontSize = iWidth / 1200 + 'px';
	function Wonresize(){
		setTimeout(function(){
			var iWidth = document.documentElement.clientWidth;
			document.getElementsByTagName('html')[0].style.fontSize = iWidth / 1200 + 'px';
		},200);
	 }
	window.addEventListener("onorientationchange" in window ? "orientationchange" : "resize", Wonresize, false);
</script>
<script type="text/javascript">
	var _<?php echo $post_hash_var_name; ?>="<?php echo new_post_hash(); ?>";
	var G_POST_HASH=_<?php echo $post_hash_var_name; ?>;
	var G_INDEX_SCRIPT = "<?php if (get_setting('url_rewrite_enable') != 'Y') echo G_INDEX_SCRIPT; ?>";
	var G_SITE_NAME = "<?php echo addcslashes(get_setting('site_name'), '\"'); ?>";
	var G_BASE_URL = "<?php echo base_url(); if (get_setting('url_rewrite_enable') != 'Y') { ?>/<?php echo rtrim(G_INDEX_SCRIPT, '/'); } ?>";
	var G_STATIC_URL = "<?php echo G_STATIC_URL; ?>";
	var G_UPLOAD_URL = "<?php echo get_setting('upload_url'); ?>";
	var G_USER_ID = "<?php echo $this->user_id; ?>";
	var G_USER_NAME = "<?php echo addcslashes($this->user_info['user_name'], '\"'); ?>";
	var G_UPLOAD_ENABLE = "<?php echo get_setting('upload_enable'); ?>";
	var G_UNREAD_NOTIFICATION = 0;
	var G_NOTIFICATION_INTERVAL = <?php echo intval(get_setting('unread_flush_interval')) * 1000; ?>;
	var G_CAN_CREATE_TOPIC = "<?php echo $this->user_info['permission']['create_topic']; ?>";
	var G_ADVANCED_EDITOR_ENABLE = "<?php echo get_setting('advanced_editor_enable'); ?>";
	var FILE_TYPES = "<?php echo strtolower(get_setting('allowed_upload_types')); ?>";


	<?php if (human_valid('question_valid_hour')) { ?>
	var G_QUICK_PUBLISH_HUMAN_VALID = true;
	<?php } ?>

	var G_FLAG_CAPTCHA = true,
	G_FLAG_AUTOSIZE = true,
	G_FLAG_AUTO_NAV = true,
	G_FLAG_SHOW_AVATAR = true,
	G_FLAG_ADD_COMMENT = true,
	G_FLAG_ENABLE_AT   = true;
</script>
<?php if (is_array($this->_import_js_files)) { ?>
<?php foreach ($this->_import_js_files AS $import_js) { ?>
<script src="<?php echo $import_js; ?>?v=<?php echo G_VERSION_BUILD; ?>" type="text/javascript"></script>
<?php } ?>
<?php } ?>
<script type="text/javascript" src="<?php echo G_STATIC_URL; ?>/js/functions.js"></script>
<script type="text/javascript" src="<?php echo G_STATIC_URL; ?>/js/compatibility.js"></script>
<!--[if lte IE 8]>
	<script type="text/javascript" src="<?php echo G_STATIC_URL; ?>/js/respond.js"></script>
<![endif]-->

<?php $post_hash_var_name = strtoupper(md5(TIMESTAMP . $this->userinfo['salt'])); ?>
</head>
<noscript unselectable="on" id="noscript">
    <div class="icb-404 icb-404-wrap container">
        <img src="<?php echo G_STATIC_URL; ?>/common/no-js.jpg">
        <p>你的浏览器禁用了JavaScript, 请开启后刷新浏览器获得更好的体验!</p>
    </div>
</noscript>
