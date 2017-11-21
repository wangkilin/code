<?php View::output('global/header_landing_blank.tpl.htm'); ?>

<script type="text/javascript">
	$(document).ready(function () {
		$('#header_action').append('<?php _e('邮箱验证'); ?>');
	});
</script>

<div class="aw-register-box">
	<div class="aw-mod aw-find-pwd">
		<div class="aw-mod-body">
			<h3><?php _e('感谢您的注册!'); ?></h3>
			<p>
				<?php _e('感谢您的注册, 请点击下一步继续激活你的账户'); ?>
			</p>
			<p class="aw-padding10">
				<form action="account/ajax/valid_email_active/" method="post" id="active_form">
					<input type="hidden" name="active_code" value="<?php echo $this->active_code; ?>" />
					<button class="btn btn-large btn-success" onclick="ajax_post($('#active_form')); return false;"><?php _e('下一步'); ?></button>
				</form>	
			</p>
		</div>
	</div>
</div>


