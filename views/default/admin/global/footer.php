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
		$(document).ready(function () {
			$('#__crond').html(unescape('%3Cimg%20src%3D%22' + G_BASE_URL + '/crond/run/<?php echo TIMESTAMP; ?>%22%20width%3D%221%22%20height%3D%221%22%20/%3E'));
		});

	</script>
</div>

<?php View::output('global/debuger.php'); ?>
<!-- / DO NOT REMOVE -->

</body>
</html>
