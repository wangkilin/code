<?php View::output('m/header.php'); ?>

<!-- top-nav -->
<div class="top-nav">
	<ul>
		<li>
			<a href="m/home/"><?php _e('最新动态'); ?> <!-- <i class="icon-tips"></i> --></a>
		</li>
		<li>
			<a href="m/notifications" class="active"><?php _e('消息通知'); ?> <?php if ($this->user_info['notification_unread']) { ?><span class="badge badge-danger"><?php echo $this->user_info['notification_unread']; ?></span><?php } ?></a>
		</li>
	</ul>
</div>
<!-- end top-nav -->

<!-- 内容 -->
<div class="container">
	<!-- 消息列表 -->
	<div class="icb-feed-list icb-notifications-list active">
		<div class="mod-head text-center">
			<a href="" class="btn btn-success btn-large" onclick="AWS.Message.read_notification(0, false, false);"><?php _e('全部已读'); ?></a>
			<a href="m/settings/" class="btn btn-gray btn-large"><?php _e('通知设置'); ?></a>
		</div>
		<div class="mod-body">
			<ul id="notifications_listview"></ul>
		</div>
		<div class="mod-footer">
			<a id="load_notifications" class="icb-load-more"><i class="icon icon-loading icon-spin"></i> <span><?php _e('更多'); ?></span></a>
		</div>
	</div>
	<!-- end 消息列表 -->

	<?php View::output('m/nav_menu.php'); ?>

</div>
<!-- end 内容 -->

<script type="text/javascript">
	$(document).ready(function () {
		AWS.load_list_view(G_BASE_URL + '/notifications/ajax/list/', $('#load_notifications'), $('#notifications_listview'));
	});
</script>

<?php View::output('m/footer.php'); ?>