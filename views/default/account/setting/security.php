<?php View::output('global/header.php'); ?>
<?php View::output('account/setting/setting_header.php'); ?>

<div class="icb-mod">
	<div class="mod-body">
		<div class="icb-mod icb-user-setting-bind">
			<div class="mod-head">
				<h3><?php _e('修改密码'); ?></h3>
			</div>
			<form class="form-horizontal" action="account/ajax/modify_password/" method="post" id="setting_form">
				<div class="mod-body">
					<div class="form-group">
						<label class="control-label" for="input-password-old"><?php _e('当前密码'); ?></label>
						<div class="row">
							<div class="col-lg-4">
								<input type="password" class="form-control" id="input-password-old" name="old_password" />
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label" for="input-password-new"><?php _e('新的密码'); ?></label>
						<div class="row">
							<div class="col-lg-4">
							    <input type="password" class="form-control" id="input-password-new" name="password" />
							</div>
						</div>
					</div> 
					<div class="form-group">
						<label class="control-label" for="input-password-re-new"><?php _e('确认密码'); ?></label>
						<div class="row">
							<div class="col-lg-4">
							    <input type="password" class="form-control" id="input-password-re-new" name="re_password" />
							</div>
						</div>
					</div>      
				</div>
			</form>
		</div>
	</div>
	<div class="mod-footer clearfix">
		<a href="javascript:;" class="btn btn-large btn-success pull-right" onclick="AWS.ajax_post($('#setting_form'));"><?php _e('保存'); ?></a>
	</div>
</div>



<?php View::output('account/setting/setting_footer.php'); ?>
<?php View::output('global/footer.php'); ?>