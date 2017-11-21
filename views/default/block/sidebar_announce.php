<?php if (get_setting('site_announce')) { ?>
	<?php if ($_COOKIE['announce_close'] != substr(md5(get_setting('site_announce')), 0, 10)) { ?>
		<script type="text/javascript">
		var ANNOUNCE_CLOSE = '<?php echo substr(md5(get_setting('site_announce')), 0, 10); ?>';

		$(document).ready(function()
		{
			if (ANNOUNCE_CLOSE != $.cookie('announce_close'))
			{
				$('#icb-site-announce').show();
			}
		});
		</script>

		<div class="icb-mod new-announce collapse" id="icb-site-announce">
			<div class="mod-head">
				<h3>
					<a class="pull-right" href="javascript:;" onclick="$('#icb-site-announce').fadeOut(); $.cookie('announce_close', ANNOUNCE_CLOSE, { expires: 30 });"><i class="icon icon-delete text-color-999"></i></a>
					<?php _e('公告'); ?>
				</h3>
			</div>
			<div class="mod-body">
				<?php echo get_setting('site_announce'); ?>
			</div>
		</div>
	<?php } ?>
<?php } ?>