<?php View::output('global/header.php'); ?>

<div class="icb-container">
	<?php View::output('block/content_nav_menu.php'); ?>

	<div class="container">
		<div class="row">
		<form id="verify_form" method="post" action="test/index-square/" enctype="multipart/form-data" class="form-horizontal">
			<dl class="verify-attach">
				<dt><?php _e('请选择PDF文件上传'); ?>:</dt>
				<dd>
					<input type="file" class="" name="attach" />
				</dd>
			</dl>
			<dl>
				<dd><input type="submit" class="btn btn-primary btn-large" value="上传" /></dd>
			</dl>

		</form>

		</div>
	</div>
</div>

<?php View::output('global/footer.php'); ?>
