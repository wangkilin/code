<?php View::output('m/header.php'); ?>

<!-- 标题 -->
<div class="icb-title">
	<?php _e('认证'); ?>
</div>
<!-- end 标题 -->

<div class="container">
	<div class="icb-setting">

		<form id="verify_form" method="post" action="account/ajax/verify/" enctype="multipart/form-data" class="form-horizontal">
			<dl>
				<dt><?php _e('当前状态'); ?> : </dt>
				<dd>
					<?php if ($this->user_info['verified']) { ?>
						<?php _e('已认证'); ?>
						<?php } else if (!isset($this->verify_apply['status'])) { ?>
						<?php _e('未认证'); ?>
						<?php } else if ($this->verify_apply['status'] == 0) { ?>
						<?php _e('正在审核'); ?>
						<?php } else if ($this->verify_apply['status'] == 1) { ?>
						<?php _e('已认证'); ?>
						<?php } else if ($this->verify_apply['status'] == -1) { ?>
						<?php _e('认证被拒绝'); ?>
						<?php } ?>
				</dd>
			</dl>

			<dl>
				<dt><?php _e('认证类型'); ?>:</dt>
				<dd><input type="radio" name="type" value="personal" id="type_personal" <?php if (!$this->verify_apply['type'] OR $this->verify_apply['type'] == 'personal') { ?> checked="checked"<?php } ?> /> <?php _e('个人认证'); ?> <input type="radio" name="type" id="type_enterprise" <?php if ($this->verify_apply['type'] == 'enterprise') { ?> checked="checked"<?php } ?> /> <?php _e('企业认证'); ?></dd>
			</dl>

			<dl>
				<dt class="verify-name"><span><?php if (!$this->verify_apply['type'] OR $this->verify_apply['type'] == 'personal') { ?><?php _e('真实姓名'); ?><?php } else { ?><?php _e('企业名称'); ?><?php } ?>:</span></dt>
				<dd><input type="text" class="form-control" name="name" value="<?php echo $this->verify_apply['name']; ?>" /></dd>
			</dl>

			<dl>
				<dt class="verify-code"><span><?php if (!$this->verify_apply['type'] OR $this->verify_apply['type'] == 'personal') { ?><?php _e('身份证号码'); ?><?php } else { ?><?php _e('组织机构代码'); ?><?php } ?>:</span></dt>
				<dd><input type="text" class="form-control" name="id_code" value="<?php echo $this->verify_apply['data']['id_code']; ?>" /></dd>
			</dl>

			<dl>
				<dt><?php _e('联系方式'); ?>:</dt>
				<dd><input type="text" class="form-control" name="contact" value="<?php echo $this->verify_apply['data']['contact']; ?>" /></dd>
			</dl>

			<dl>
				<dt><?php _e('认证说明'); ?>:</dt>
				<dd><input type="text" class="form-control" name="reason" value="<?php echo $this->verify_apply['reason']; ?>" /></dd>
			</dl>

			<dl class="verify-attach">
				<dt><?php _e('附件'); ?>:</dt>
				<dd>
					<input type="file" name="attach" />
					<p>请提交对应的身份证或者组织机构代码证件扫描</p>
				</dd>
			</dl>
		</form>
	
		<div class="icb-setting-save clearfix">
			<a class="btn btn-primary pull-right" id="submit-form" onclick="AWS.ajax_post($('#verify_form'))"><?php _e('提交'); ?></a>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function()
	{
		$('#type_personal').click(function()
		{
			$('.verify-name span').html(_t('真实姓名') + ':');
			$('.verify-code span').html(_t('身份证号码') + ':');
		});
		
		$('#type_enterprise').click(function()
		{
			$('.verify-name span').html(_t('企业名称') + ':');
			$('.verify-code span').html(_t('组织机构代码') + ':');
		});

		<?php if ((isset($this->verify_apply['status']) AND ($this->verify_apply['status'] == 1 OR $this->verify_apply['status'] == 0)) OR $this->user_info['verified']) { ?>
		$('#verify_form input').attr('disabled', true);
		$('.verify-attach, #submit-form').hide();
		<?php } ?>
	})
</script>

<?php View::output('m/footer.php'); ?>