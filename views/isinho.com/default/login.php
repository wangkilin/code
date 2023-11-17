<!DOCTYPE HTML>
<html>

<head>
<meta charset="utf-8" />
<meta name="renderer" content="webkit" />
<meta http-equiv="X-UA-Compatible" content="<?php echo X_UA_COMPATIBLE; ?>" />
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="blank" />
<meta name="format-detection" content="telephone=no" />
<title><?php echo $this->page_title; ?></title>
<link href="<?php echo G_STATIC_URL; ?>/isinho.com/favicon.png?v=<?php echo G_VERSION_BUILD; ?>" rel="shortcut icon" type="image/png" />
<link type="text/css" rel="stylesheet" href="<?php echo G_STATIC_URL; ?>/css/bootstrap.css?v=<?php echo G_VERSION_BUILD; ?>" />
<link type="text/css" rel="stylesheet" href="<?php echo G_STATIC_URL; ?>/css/icon.css?v=<?php echo G_VERSION_BUILD; ?>" />
<link type="text/css" rel="stylesheet" href="<?php echo G_STATIC_URL; ?>/admin/css/login.css?v=<?php echo G_VERSION_BUILD; ?>" />
<script type="text/javascript">
    var G_INDEX_SCRIPT = "<?php echo G_INDEX_SCRIPT; ?>";
    var G_BASE_URL = "<?php echo base_url(); ?>/<?php echo rtrim(G_INDEX_SCRIPT, '/'); ?>";
    var G_USER_ID = "<?php echo $this->user_id; ?>";
    var G_POST_HASH = "";
</script>
<?php if (is_array($this->_import_css_files)) { ?>
<?php foreach ($this->_import_css_files AS $import_css) { ?>
<link type="text/css" rel="stylesheet" href="<?php echo $import_css; ?>?v=<?php echo G_VERSION_BUILD; ?>" />
<?php } ?>
<?php } ?>
<?php if (is_array($this->_import_js_files)) { ?>
<?php foreach ($this->_import_js_files AS $import_js) { ?>
<script type="text/javascript" src="<?php echo $import_js; ?>?v=<?php echo G_VERSION_BUILD; ?>" ></script>
<?php } ?>
<?php } ?>
</head>

<body>
<div class="icb-login">
    <div class="mod center-block">
        <h1><img src="<?php echo G_STATIC_URL; ?>/isinho.com/logo-blue-fat-small.png" alt="" /></h1>

        <form role="form" id="login_form"  onsubmit="return false" action="account/ajax/login_process/" method="post">
            <?php if ($_GET['return_url']){ ?>
            <input type="hidden" name="return_url" value="<?php echo htmlspecialchars($_GET['return_url']); ?>">
            <?php } ?>

            <div class="alert alert-danger collapse error_message"></div>

            <div class="form-group">
                <label><?php _e('用户名'); ?></label>
                <input type="text" class="form-control" placeholder="<?php _e('用户名'); ?>" name="user_name" value="<?php echo $this->user_info['email']; ?>"  autofocus/>
                <i class="icon icon-user"></i>
            </div>
            <div class="form-group">
                <label><?php _e('密码'); ?></label>
                <input type="password" class="form-control" placeholder="<?php _e('密码'); ?>" type="password" name="password" onkeydown="if (event.keyCode == 13) { document.getElementById('login_submit').click(); };"/>
                <i class="icon icon-lock"></i>
            </div>
            <div class="form-group">
                <label><?php _e('验证码'); ?></label>
                <div class="row">
                  <div class="col-xs-7">
                    <input type="text" class="form-control" placeholder="<?php _e('验证码'); ?>"  name="seccode_verify" onkeydown="if (event.keyCode == 13) { document.getElementById('login_submit').click(); };" maxlength="4" />
                  </div>
                  <div class="col-xs-4">
                    <img src="" class="verification" id="captcha" onclick="this.src = G_BASE_URL + '/account/captcha/' + Math.floor(Math.random() * 10000);" />
                  </div>
                </div>  <i class="icon icon-verify"></i>

            </div>
            <button type="submit" class="btn btn-primary" id="login_submit" onclick="AWS.ajax_post($('#login_form'), AWS.ajax_processer, 'error_message');"><?php _e('登录'); ?></button>
        </form>

        <h2 class="text-center text-color-999">&copy;iSinho.com 沈阳新禾文化传媒有限公司</h2>
    </div>
</div>
