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
<script type="text/javascript">
$(function () {
    /*====================================
    //	防止手机浏览器在页面后面加入垃圾广告
    ======================================*/
    $(document).scroll(function () {
        $("#last-one-flag").nextAll(':not(#scrollUp)').remove();
    });
});
</script>
<!-- 防止手机浏览器在页面后面加入垃圾广告, 放置一个标志性的元素。 所有必须的元素， 都要放到这个标识元素前面  -->
<div id="last-one-flag"></div>
</body>
</html>
