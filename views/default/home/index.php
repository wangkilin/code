<?php View::output('global/header.php'); ?>

<div class="icb-container">
	<div class="container">
		<div class="row">
			<div class="icb-content-wrap clearfix">
				<div class="col-sm-12 col-md-9 icb-main-content">
					<!-- 新消息通知 -->
					<div class="icb-mod icb-notification-box collapse" id="index_notification">
						<div class="mod-head common-head">
							<h2>
								<span class="pull-right"><a href="account/setting/privacy/#notifications" class="text-color-999"><i class="icon icon-setting"></i> <?php _e('通知设置'); ?></a></span>
								<i class="icon icon-bell"></i><?php _e('新通知'); ?><em class="badge badge-important" name="notification_unread_num"><?php echo $this->user_info['notification_unread']; ?></em>
							</h2>
						</div>
						<div class="mod-body">
							<ul id="notification_list"></ul>
						</div>
						<div class="mod-footer clearfix">
							<a href="javascript:;" onclick="AWS.Message.read_notification(false, 0, false);" class="pull-left btn btn-mini btn-default"><?php _e('我知道了'); ?></a>
							<a href="notifications/" class="pull-right btn btn-mini btn-success"><?php _e('查看所有'); ?></a>
						</div>
					</div>
					<!-- end 新消息通知 -->

					<a name="c_contents"></a>
					<div class="icb-mod clearfix">
						<div class="mod-head common-head">
							<h2 id="main_title"><?php _e('最新动态'); ?></h2>
						</div>

						<div class="mod-body icb-feed-list clearfix" id="main_contents"></div>

						<div class="mod-footer">
							<!-- 加载更多内容 -->
							<a id="bp_more" class="icb-get-more" data-page="0">
								<span><?php _e('更多'); ?></span>
							</a>
							<!-- end 加载更多内容 -->
						</div>
					</div>
				</div>
				<!-- 侧边栏 -->
				<div class="col-sm-12 col-md-3 icb-side-bar hidden-xs hidden-sm">
					<?php View::output('block/sidebar_announce.php'); ?>

					<?php View::output('block/sidebar_menu.php'); ?>

					<!-- 可能感兴趣的人/或话题 -->
					<?php View::output('block/sidebar_recommend_users_topics.php'); ?>
				</div>
				<!-- end 侧边栏 -->
			</div>
		</div>
	</div>
</div>

<?php if ($_GET['first_login'] && $this->user_info['is_first_login'] == 1) { ?>
<script type="text/javascript" src="<?php echo G_STATIC_URL; ?>/js/fileupload.js"></script>
<script type="text/javascript">
$(document).ready(function () {
	$.get(G_BASE_URL+'/account/ajax/welcome_message_template/', function (template) {
		$('#icb-modal-window').html(template);
		$('body').addClass('modal-open');
		welcome_step('1');
	});
});
</script>
<?php } ?>

<?php View::output('global/footer.php'); ?>