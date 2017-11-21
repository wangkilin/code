<?php View::output('global/header.php'); ?>

<div class="icb-container">
	<div class="container">
		<div class="row">
			<div class="icb-content-wrap clearfix">
				<div class="col-sm-12 col-md-9 icb-main-content">
					<div class="icb-mod icb-mod-invite-friend">
						<div class="mod-head common-head">
							<h2><?php _e('邀请好友加入社区'); ?></h2>
						</div>
						<div class="mod-body">
							<form id="invitation_form" method="post" action="invitation/ajax/invite/" onsubmit="return false">
								<div class="content">
									<input class="form-control" type="text" name="email" placeholder="<?php _e('请输入好友邮箱'); ?>..." />
									<p>
										<a class="pull-right btn btn-mini btn-success" onclick="AWS.ajax_post($('#invitation_form')); return false;"><?php _e('发送邀请'); ?></a>
										<?php _e('你还有'); ?> <?php _e('%s 个邀请名额', '<span class="icb-text-color-blue">' . $this->user_info['invitation_available'] . '</span>'); ?>
									</p>
								</div>
							</form>
						</div>
					</div>
					<div class="icb-mod icb-invite-list">
						<div class="mod-head common-head">
							<h2><?php _e('已邀请好友'); ?></h2>
						</div>
						<div class="mod-body">
							<ul id="invitation_list"></ul>
						</div>
						<div class="mod-footer">
							<!-- 加载更多内容 -->
							<a class="icb-get-more" id="invitation_more">
								<span><?php _e('更多'); ?>...</span>
							</a>
							<!-- end 加载更多内容 -->
						</div>
					</div>
				</div>
				<!-- 侧边栏 -->
				<div class="col-sm-12 col-md-3 icb-side-bar">
					<?php View::output('block/sidebar_menu.php'); ?>
				</div>
				<!-- end 侧边栏 -->
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	AWS.load_list_view(G_BASE_URL + '/invitation/ajax/invitation_list/', $('#invitation_more'),  $('#invitation_list'));
});
</script>

<?php View::output('global/footer.php'); ?>