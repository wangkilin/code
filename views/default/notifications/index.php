<?php View::output('global/header.php'); ?>

<div class="icb-container">
	<div class="container">
		<div class="row">
			<div class="icb-content-wrap clearfix">
				<div class="col-sm-12 col-md-9 icb-main-content">
					<div class="icb-mod clearfix">
						<div class="mod-head common-head clearfix">
							<h2>
								<span class="pull-right hidden-xs">
									<a class="text-color-999" href="account/setting/privacy/#!notifications"><i class="icon icon-setting"></i> <?php _e('通知设置'); ?></a>
									<a onclick="AWS.Message.read_notification(true, 0, true);" class="btn btn-mini btn-success"><?php _e('全部已读'); ?></a>
								</span>

								<?php _e('通知'); ?>
							</h2>
						</div>
						<div class="mod-body">
							<div class="icb-notifications-list">
								<ul id="notifications_list"></ul>
							</div>
						</div>
						<div class="mod-footer">
							<a class="icb-get-more" id="notifications_more">
								<span><?php _e('更多'); ?>...</span>
							</a>
						</div>
					</div>
				</div>
				<!-- 侧边栏 -->
				<div class="col-md-3 icb-side-bar hidden-xs hidden-sm">
					<?php View::output('block/sidebar_menu.php'); ?>
				</div>
				<!-- end 侧边栏 -->
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function () {
		AWS.load_list_view(G_BASE_URL + '/notifications/ajax/list/', $('#notifications_more'), $('#notifications_list'));
	});
</script>

<?php View::output('global/footer.php'); ?>