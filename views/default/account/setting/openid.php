<?php View::output('global/header.php'); ?>
<?php View::output('account/setting/setting_header.php'); ?>

<?php if (get_setting('sina_weibo_enabled') == 'Y' || get_setting('qq_login_enabled') == 'Y' || get_setting('google_login_enabled') == 'Y' || get_setting('facebook_login_enabled') == 'Y' || get_setting('twitter_login_enabled') == 'Y' || get_setting('weixin_app_id') && get_setting('weixin_app_secret') && get_setting('weixin_account_role') == 'service') { ?>
<div class="icb-mod">
	<div class="mod-body">
		<div class="icb-mod mod-setting-bind">
			<div class="mod-body">
				<ul>
					<?php if (get_setting('qq_login_enabled') == 'Y') { ?>
					<li>
						<i class="icon icon-qq"></i>
						<p>
						<?php if ($this->qq) { ?>
							<?php echo $this->qq['nickname']; ?>
						<?php }  else { ?>
							<?php _e('QQ账号') ;?>
						<?php } ?>
						</p>

						<?php if ($this->qq) { ?>
						<a href="account/openid/qq/unbind/" class="btn btn-normal btn-gray"><?php _e('取消绑定'); ?></a>
						<?php } else { ?>
						<a href="account/openid/qq/bind/" class="btn btn-normal btn-success"><?php _e('绑定'); ?></a>
						<?php } ?>
					</li>
					<?php } ?>

					<?php if (get_setting('sina_weibo_enabled') == 'Y') { ?>
					<li>
						<i class="icon icon-weibo"></i>
						<p>
							<?php if ($this->sina_weibo) { ?>
								<a href="http://weibo.com/<?php echo $this->sina_weibo['id']; ?>" target="_blank"><?php echo $this->sina_weibo['name']; ?></a>
							<?php } else { ?>
								<?php _e('微博'); ?>
							<?php } ?>
						</p>
						<?php if ($this->sina_weibo) { ?>
						<a href="account/openid/weibo/unbind/" class="btn btn-normal btn-gray"><?php _e('取消绑定'); ?></a>
						<?php } else { ?>
						<a href="account/openid/weibo/bind/" class="btn btn-normal btn-success"><?php _e('绑定'); ?></a>
						<?php } ?>
					</li>
					<?php } ?>

					<?php if (get_setting('weixin_app_id')) { ?>
					<li>
						<i class="icon icon-wechat"></i>
						<p>
							<?php if ($this->weixin) { ?>
								<?php echo $this->weixin['nickname']; ?>
							<?php } else { ?>
								<?php _e('微信'); ?>
							<?php } ?>
						</p>
						<?php if ($this->weixin) { ?>
						<a href="javascript:;" onclick="AWS.ajax_request(G_BASE_URL + '/account/ajax/unbinding_weixin/');" class="btn btn-normal btn-gray"><?php _e('取消绑定'); ?></a>
						<?php } else { ?>
						<a onclick="AWS.dialog('alertImg', {'hide' : 'show', 'url' : '<?php echo G_STATIC_URL; ?>/css/<?php echo $this->template_name; ?>/img/wechat_code.jpg', 'message' : '请关注公众帐号进行绑定操作'});" href="javascript:;" class="btn btn-normal btn-success"><?php _e('绑定'); ?></a>
						<?php } ?>
					</li>
					<?php } ?>

					<?php if (get_setting('google_login_enabled') == 'Y') { ?>
					<li>
						<i class="icon icon-google"></i>
						<p>
							<?php if ($this->google) { ?>
								<a href="<?php echo $this->google['link']; ?>" target="_blank"><?php echo $this->google['name']; ?></a>
							<?php } else { ?>
								Google
							<?php } ?>
						</p>
						<?php if ($this->google) { ?>
						<a href="account/openid/google/unbind/" class="btn btn-normal btn-gray"><?php _e('取消绑定'); ?></a>
						<?php } else { ?>
						<a href="account/openid/google/bind/" class="btn btn-normal btn-success"><?php _e('绑定'); ?></a>
						<?php } ?>
					</li>
					<?php } ?>

					<?php if (get_setting('facebook_login_enabled') == 'Y') { ?>
					<li>
						<i class="icon icon-facebook"></i>
						<p>
							<?php if ($this->facebook) { ?>
								<a href="<?php echo $this->facebook['link']; ?>" target="_blank"><?php echo $this->facebook['name']; ?></a>
							<?php } else { ?>
								Facebook
							<?php } ?>
						</p>
						<?php if ($this->facebook) { ?>
						<a href="account/openid/facebook/unbind/" class="btn btn-normal btn-gray"><?php _e('取消绑定'); ?></a>
						<?php } else { ?>
						<a href="account/openid/facebook/bind/" class="btn btn-normal btn-success"><?php _e('绑定'); ?></a>
						<?php } ?>
					</li>
					<?php } ?>

					<?php if (get_setting('twitter_login_enabled') == 'Y') { ?>
					<li>
						<i class="icon icon-twitter"></i>
						<p>
							<?php if ($this->twitter) { ?>
								<a href="https://twitter.com/<?php echo $this->twitter['screen_name']; ?>" target="_blank"><?php echo $this->twitter['name']; ?></a>
							<?php } else { ?>
								Twitter
							<?php } ?>
						</p>
						<?php if ($this->twitter) { ?>
						<a href="account/openid/twitter/unbind/" class="btn btn-normal btn-gray"><?php _e('取消绑定'); ?></a>
						<?php } else { ?>
						<a href="account/openid/twitter/bind/" class="btn btn-normal btn-success"><?php _e('绑定'); ?></a>
						<?php } ?>
					</li>
					<?php } ?>
				</ul>
			</div>
		</div>
	</div>
</div>
<?php } ?>

<?php View::output('account/setting/setting_footer.php'); ?>
<?php View::output('global/footer.php'); ?>
