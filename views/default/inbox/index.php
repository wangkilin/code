<?php View::output('global/header.php'); ?>

<div class="icb-container">
	<div class="container">
		<div class="row">
			<div class="icb-content-wrap clearfix">
				<div class="col-sm-12 col-md-9 icb-main-content">
					<div class="icb-mod icb-inbox">
						<div class="mod-head common-head">
							<h2>
								<a href="javascript:;" onclick="AWS.dialog('inbox', '');" class="pull-right btn btn-mini btn-success"><?php _e('新私信'); ?></a>
								<span class="pull-right icb-setting-inbox hidden-xs"><a class="text-color-999" href="account/setting/privacy/#!inbox"><i class="icon icon-setting"></i> <?php _e('私信设置'); ?></a></span>
								<?php _e('私信'); ?>
							</h2>
						</div>
						<div class="mod-body icb-feed-list">
							<?php if ($this->list) { ?>
							<?php foreach($this->list AS $key => $val) { ?>
							<div class="icb-item<?php if ($val['unread'] > 0) { ?> active<?php } ?>">
								<div class="mod-head">
									<a class="icb-user-img icb-border-radius-5 hidden-xs" data-id="<?php echo $val['user_info']['uid']; ?>" href="user/<?php echo $val['url_token']; ?>"><img src="<?php echo get_avatar_url($val['uid'], 'mid'); ?>" alt="" /></a>
									<p><a class="icb-user-name" data-id="<?php echo $val['uid']; ?>" href="user/<?php echo $val['url_token']; ?>"><?php echo $val['user_name']; ?></a></p>
									<p class="content"><a href="inbox/read/<?php echo $val['id']; ?>"><?php echo $val['last_message']; ?></a></p>
									<p class="text-color-999">
										<span class="pull-right"><a href="inbox/read/<?php echo $val['id']; ?>"><?php if ($val['unread']) { ?><?php _e('有'); ?> <?php echo $val['unread']; ?> <?php _e('条新回复'); ?><?php } else { ?><?php _e('共 %s 条对话', $val['count']); ?><?php } ?></a> &nbsp; <a href="javascript:;" class="text-color-999" onclick="AWS.dialog('confirm', {'message' : '<?php _e('确认删除对话'); ?>?'}, function(){window.location = G_BASE_URL + '/inbox/delete_dialog/dialog_id-<?php echo $val['id']; ?>'});"><?php _e('删除'); ?></a></span>
										<span><?php echo date_friendly($val['update_time']); ?></span>
									</p>
								</div>
							</div>
							<?php } ?>
							<?php } ?>
						</div>
						<div class="mod-footer">
							<?php echo $this->pagination; ?>
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