<div class="icb-footer-wrap">
	<div class="icb-footer">
		<?php _e("版权所有");?> © <?php echo date('Y'); ?> <?php echo get_setting('site_name');?><?php if(get_setting('icp_beian')){ ?><span class="hidden-xs"> - <?php echo get_setting('icp_beian'); ?><?php } ?></span>

		<span class="hidden-xs">Powered By <a href="#" target="blank">iCodebang.com <?php echo G_VERSION; ?></a></span>

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


<!-- DO NOT REMOVE -->
<div id="icb-modal-window" class="icb-modal-window"></div>

<?php echo get_setting('statistic_code'); ?>
<div style="display:none;" id="__crond">
	<script type="text/javascript">
		$(document).ready(function () {
			$('#__crond').html(unescape('%3Cimg%20src%3D%22' + G_BASE_URL + '/crond/run/<?php echo TIMESTAMP; ?>%22%20width%3D%221%22%20height%3D%221%22%20/%3E'));
		});
	</script>
</div>

<?php View::output('global/debuger.php'); ?>
<!-- / DO NOT REMOVE -->
<script type="text/javascript" src="static/js/editor/ckeditor.4.11/plugins/codesnippet/lib/highlight/highlight.pack.js"></script>
<script type="text/javascript" src="static/js/editor/ckeditor.4.11/plugins/codesnippet/lib/highlight/highlightjs-line-numbers.min.js"></script>
<script type="text/javascript">
$(function () {
hljs.initHighlightingOnLoad();
console.info($('code.hljs').length);
$('code.hljs').each(function(i, block) {
        hljs.lineNumbersBlock(block);
    });
});
</script>
</body>
</html>
