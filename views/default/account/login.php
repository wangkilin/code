<?php View::output('global/header_meta.php'); ?>

<div id="wrapper">
	<div class="icb-login-box">
		<div class="mod-body clearfix">
			<div class="content pull-left">
				<h1 class="logo"><a href=""></a></h1>
				<h2><?php echo get_setting('site_name'); ?></h2>
				<form id="login_form" method="post" onsubmit="return false" action="account/ajax/login_process/">
					<input type="hidden" name="return_url" value="<?php echo $this->return_url; ?>" />
					<ul>
						<li>
							<input type="text" id="icb-login-user-name" class="form-control" placeholder="<?php _e('邮箱'); ?> / <?php _e('用户名'); ?>" name="user_name" />
						</li>
						<li>
							<input type="password" id="icb-login-user-password" class="form-control" placeholder="<?php _e('密码'); ?>" name="password" />
						</li>
						<li class="alert alert-danger collapse error_message">
							<i class="icon icon-delete"></i> <em></em>
						</li>
						<li class="last">
							<a href="javascript:;" onclick="AWS.ajax_post($('#login_form'), AWS.ajax_processer, 'error_message');" id="login_submit" class="pull-right btn btn-large btn-primary"><?php _e('登录'); ?></a>
							<label>
								<input type="checkbox" value="1" name="net_auto_login" />
								<?php _e('记住我'); ?>
							</label>
							<a href="account/find_password/">&nbsp;&nbsp;<?php _e('忘记密码'); ?></a>
						</li>
					</ul>
				</form>
			</div>
			<div class="side-bar pull-left">
				<?php if (get_setting('site_close') != 'Y' && (get_setting('sina_weibo_enabled') == 'Y' || get_setting('qq_login_enabled') == 'Y' || get_setting('google_login_enabled') == 'Y' || get_setting('facebook_login_enabled') == 'Y' || get_setting('twitter_login_enabled') == 'Y' || get_setting('weixin_app_id') && get_setting('weixin_app_secret') && get_setting('weixin_account_role') == 'service')) { ?>

					<?php if ($this->return_url) $return_url = 'return_url-' . base64_encode($this->return_url); ?>

					<h3><?php _e('第三方账号登录'); ?></h3>
					<?php if (get_setting('sina_weibo_enabled') == 'Y') { ?>
						<a href="account/openid/weibo/bind/<?php echo $return_url; ?>" class="btn btn-block btn-weibo"><i class="icon icon-weibo"></i> 微博登录</a>
					<?php } ?>

					<?php if (get_setting('qq_login_enabled') == 'Y') { ?>
						<a href="account/openid/qq/bind/<?php echo $return_url; ?>" class="btn btn-block btn-qq"><i class="icon icon-qq"></i> QQ 登录</a>
					<?php } ?>

					<?php if (get_setting('weixin_app_id') && get_setting('weixin_app_secret') && get_setting('weixin_account_role') == 'service') { ?>
						<a href="account/weixin_login/<?php echo $return_url; ?>" class="btn btn-block btn-wechat">
							<i class="icon icon-wechat"></i> 微信扫一扫登录
							<div class="img">
								<img src="<?php echo get_js_url('/weixin/login_qr_code/'); ?>" />
							</div>
						</a>
					<?php } ?>

					<?php if (get_setting('google_login_enabled') == 'Y') { ?>
						<a href="account/openid/google/bind/<?php echo $return_url; ?>" class="btn btn-block btn-google"> <i class="icon icon-google"></i> Google 登录</a>
					<?php } ?>

					<?php if (get_setting('facebook_login_enabled') == 'Y') { ?>
						<a href="account/openid/facebook/bind/<?php echo $return_url; ?>" class="btn btn-block btn-facebook"> <i class="icon icon-facebook"></i> Facebook 登录</a>
					<?php } ?>

					<?php if (get_setting('twitter_login_enabled') == 'Y') { ?>
						<a href="account/openid/twitter/bind/<?php echo $return_url; ?>" class="btn btn-block btn-twitter"> <i class="icon icon-twitter"></i> Twitter 登录</a>
					<?php } ?>
				<?php } ?>
			</div>
		</div>
		<div class="mod-footer">
			<span>还没有账号?</span>&nbsp;&nbsp;
			<a href="account/register/"><?php _e('立即注册'); ?></a>&nbsp;&nbsp;•&nbsp;&nbsp;
			<a href="">游客访问</a>&nbsp;&nbsp;•&nbsp;&nbsp;
			<a href="reader/"><?php _e('问答阅读'); ?></a>
		</div>
	</div>
</div>

<script type="text/javascript" src="<?php echo G_STATIC_URL; ?>/js/app/login.js"></script>

<?php View::output('global/footer.php'); ?>