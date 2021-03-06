<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
	<form action="admin/ajax/save_verify_approval/" id="settings_form" method="post" onsubmit="return false">
	<input type="hidden" name="uid" value="<?php echo $this->user['uid']; ?>" />
	<div class="mod">
		<div class="mod-head">
			<h3>
				<span class="pull-left"><?php _e('认证审核编辑'); ?> - <?php echo $this->user['user_name']; ?></span>
			</h3>
		</div>
		<div class="tab-content mod-content">
			<table class="table table-striped">
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('认证类型'); ?>:</span>
							<div class="col-sm-5 col-xs-8">
								<?php if ($this->user['verified'] == 'personal') { ?><?php _e('个人认证'); ?><?php } else if ($this->user['verified'] == 'enterprise') { ?><?php _e('机构认证'); ?><?php } ?>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('真实姓名'); ?>:</span>
							<div class="col-sm-5 col-xs-8">
								<input class="form-control" name="name" type="text" value="<?php echo $this->verify_apply['name']; ?>" />
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('身份证号码'); ?>:</span>
							<div class="col-sm-5 col-xs-8">
								<input class="form-control" name="id_code" type="text" value="<?php echo $this->verify_apply['data']['id_code']; ?>" />
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('联系方式'); ?>:</span>
							<div class="col-sm-5 col-xs-8">
								<input class="form-control" name="contact" type="text" value="<?php echo $this->verify_apply['data']['contact']; ?>" />
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('认证说明'); ?>:</span>
							<div class="col-sm-5 col-xs-8">
								<input class="form-control" name="reason" type="text" value="<?php echo $this->verify_apply['reason']; ?>" />
							</div>
						</div>
					</td>
				</tr>
				<tfoot>
				<tr>
					<td>
						<input type="button" value="<?php _e('保存设置'); ?>" class="btn btn-primary center-block" onclick="AWS.ajax_post($('#settings_form'));" />
					</td>
				</tr>
				</tfoot>
			</table>
		</div>
	</div>
	</form>
</div>

<?php View::output('admin/global/footer.php'); ?>