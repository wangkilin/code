<div class="icb-footer">
    <p>Copyright &copy; <?php echo date('Y'); ?> - Powered By <a href="http://www.icodebang.com/?copyright" target="blank">iCodeBang.com <?php echo G_VERSION; ?></a></p>
</div>

<!-- DO NOT REMOVE -->
<div id="icb-modal-window" class="icb-modal-window"></div>

<?php if (!$this->user_info['permission']['is_administortar']) { ?>
<script type="text/javascript">
$(function()
{
    $('.mod-bar .icon-setting, .mod-bar .icon-signup, .mod-bar .icon-job').hide();
})
</script>
<?php } ?>

<div style="display:none;" id="__crond">
	<script type="text/javascript">
		$(document).ready(function () {});

	</script>
</div>

<!-- 百度统计 -->
<?php View::output('global/baidu_stat.php'); ?>
<?php
if (Application::config()->get('system')->debug && Application::config()->get('system')->sites[$_SERVER['HTTP_HOST']]['debug']) {
     View::output('global/debuger.php');
}
?>
<!-- / DO NOT REMOVE -->

</body>
</html>
