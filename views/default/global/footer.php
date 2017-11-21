<div class="icb-footer-wrap">
	<div class="icb-footer">
		Copyright © <?php echo date('Y'); ?><?php if(get_setting('icp_beian')){ ?><span class="hidden-xs"> - <?php echo get_setting('icp_beian'); ?><?php } ?>, All Rights Reserved</span>

		<span class="hidden-xs">Powered By <a href="http://www.wecenter.com/?copyright" target="blank">WeCenter <?php echo G_VERSION; ?></a></span>

		<?php if (is_mobile(true)) { ?>
			<div class="container">
				<div class="row">
					<p align="center"><?php _e('版本切换'); ?>: <b><?php _e('电脑版'); ?></b> | <a href="m/ignore_ua_check-FALSE"><?php _e('手机版'); ?></a></p>
				</div>
			</div>
		<?php } ?>
	</div>
</div>

<a id="icb-goto-top" class="icb-goto-top hidden-xs" href="javascript:;" onclick="$.scrollTo(1, 600, {queue:true});" title="<?php _e('返回顶部'); ?>" data-toggle="tooltip"><i class="icon icon-up"></i></a>

<?php echo get_setting('statistic_code'); ?>

<!-- DO NOT REMOVE -->
<div id="icb-modal-window" class="icb-modal-window"></div>

<div style="display:none;" id="__crond">
	<script type="text/javascript">
		$(document).ready(function () {
			$('#__crond').html(unescape('%3Cimg%20src%3D%22' + G_BASE_URL + '/crond/run/<?php echo TIMESTAMP; ?>%22%20width%3D%221%22%20height%3D%221%22%20/%3E'));
		});
	</script>
</div>

<?php View::output('global/debuger.php'); ?>
<!-- / DO NOT REMOVE -->

</body>
</html>
