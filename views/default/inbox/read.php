<?php View::output('global/header.php'); ?>

<div class="icb-container">
	<div class="container">
		<div class="row">
			<div class="icb-content-wrap clearfix">
				<div class="col-sm-12 col-md-9 icb-main-content">
					<div class="icb-mod icb-inbox-read">
						<div class="mod-head common-head">
							<h2>
								<a href="inbox/" class="pull-right"><?php _e('返回私信列表'); ?> »</a>
								<?php _e('私信对话'); ?>：<?php echo $this->recipient_user['user_name']; ?>
							</h2>
						</div>
						<div class="mod-body">
							<!-- 私信内容输入框　-->
							<form action="inbox/ajax/send/" method="post" id="recipient_form">
				        		<input type="hidden" name="post_hash" value="<?php echo new_post_hash(); ?>" />
				        		<input type="hidden" name="recipient" value="<?php echo $this->recipient_user['user_name']; ?>" />
								<a href="user/<?php echo $this->user_info['url_token']; ?>" data-id="<?php echo $this->user_info['uid']; ?>" class="icb-user-img icb-border-radius-5"><img src="<?php echo get_avatar_url($this->user_info['uid'], 'mid'); ?>" alt="" /></a>
								<textarea rows="3" class="form-control message" placeholder="<?php _e('想要对ta说点什么'); ?>?" type="text" name="message" /></textarea>
								<p>
									<a class="btn btn-mini btn-success" href="javascript:;" onclick="AWS.ajax_post($('#recipient_form'));"><?php _e('发送'); ?></a>
								</p>
							</form>
							<!-- end 私信内容输入框 -->
						</div>
						<div class="mod-footer">
							<!-- 私信内容列表 -->
							<a name="contents"></a>
							<ul>
								<?php if ($this->list) { ?>
								<?php foreach($this->list AS $key => $val) { ?>
								<li<?php if ($val['uid'] == $this->user_id) { ?> class="active"<?php } ?>>
									<a href="user/<?php if ($val['uid'] == $this->user_id) { ?><?php echo $this->user_info['url_token']; ?><?php } else { ?><?php echo $val['url_token']; ?><?php } ?>" data-id="<?php echo $val['uid']; ?>" class="icb-user-img icb-border-radius-5"><img src="<?php echo get_avatar_url($val['uid'], 'mid'); ?>" alt="" /></a>
									<div class="icb-item">
										<p><a href="user/<?php if ($val['uid'] == $this->user_id) { ?><?php echo $this->user_info['url_token']; ?><?php } else { ?><?php echo $val['url_token']; ?><?php } ?>"><?php if ($val['uid'] == $this->user_id) { ?><?php _e('我'); ?><?php } else { ?><?php echo $val['user_name']; ?><?php } ?></a>: <?php echo nl2br($val['message']); ?></p>
										<p class="text-color-999">
											<?php if ($val['uid'] != $this->user_id) { ?><span class="pull-right icb-replay"><a href="javascript:;" onclick="$.scrollTo(($('#recipient_form').offset()['top']) - 20, 600, {queue:true});$('.message').focus();"><?php _e('回复'); ?></a></span><?php } ?>
											<?php echo date_friendly($val['add_time']); ?><?php if ($val['receipt'] AND $val['uid'] == $this->user_id) { ?> (<?php _e('对方于 %s 已读', date_friendly($val['receipt'])); ?>)<?php } ?>
										</p>
										<i class="i-private-replay-triangle"></i>
									</div>
								</li>
								<?php } ?>
								<?php } ?>
							</ul>
							<!-- end 私信内容列表 -->
						</div>
					</div>
				</div>
				<!-- 侧边栏 -->
				<div class="col-sm-12 col-md-3 icb-side-bar hidden-xs hidden-sm">
					<?php View::output('block/sidebar_menu.php'); ?>
				</div>
				<!-- end 侧边栏 -->
			</div>
		</div>
	</div>
</div>

<?php View::output('global/footer.php'); ?>
