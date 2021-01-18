<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
	<form action="admin/ajax/send_invites/" id="settings_form" method="post" onsubmit="return false">
	<div class="mod">
		<div class="mod-head">
			<h3>
				<span class="pull-left"><?php _e('批量邀请'); ?></span>
			</h3>
		</div>
		<div class="tab-content mod-content">
			<table class="table table-striped">
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('邮箱地址'); ?>:</span>
							<div class="col-sm-5 col-xs-8">
								<textarea class="form-control textarea" name="email_list" rows="5"></textarea>

								<span class="help-block"><?php _e('一行一个邮箱地址'); ?></span>
							</div>
						</div>
					</td>
				</tr>
				<tfoot>
				<tr>
					<td>
						<input type="button" value="<?php _e('发送邀请'); ?>" class="btn btn-primary center-block" onclick="AWS.ajax_post($('#settings_form'));" />
					</td>
				</tr>
				</tfoot>
			</table>
		</div>
	</div>
	</form>
</div>

<?php View::output('admin/global/footer.php'); ?>