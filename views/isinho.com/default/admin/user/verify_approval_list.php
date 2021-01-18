<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
	<div class="mod">
		<div class="mod-head">
			<h3>
				<ul class="nav nav-tabs">
					<li class="<?php if (!$_GET['status']) { ?> active<?php } ?>"><a href="admin/user/verify_approval_list/"><?php _e('待审核'); ?></a></li>
					<li class="<?php if ($_GET['status'] == 1) { ?> active<?php } ?>"><a href="admin/user/verify_approval_list/status-1"><?php _e('已审核'); ?></a></li>
				</ul>
			</h3>
		</div>
		<div class="mod-body tab-content">
			<form id="batchs_form" action="admin/ajax/verify_approval_manage/" method="post">
			<input type="hidden" id="batch_type" name="batch_type" value="approval" />
			<div class="table-responsive">
			<?php if ($this->approval_list) { ?>
				<table class="table table-striped">
					<thead>
						<tr>
							<?php if ($_GET['status'] != 1) { ?><th width="10%"><input type="checkbox" class="check-all"></th><?php } ?>
							<th width="10%"><?php _e('会员'); ?></th>
							<th width="20%"><?php _e('企业名称/真实姓名'); ?></th>
							<th width="10%"><?php _e('认证类型'); ?></th>
							<th width="20%"><?php _e('详细资料'); ?></th>
							<th><?php _e('认证说明'); ?></th>
							<th><?php _e('操作'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($this->approval_list AS $key => $val) { ?>
						<tr>
							<?php if ($_GET['status'] != 1) { ?><td><input type="checkbox" class="approval_ids" name="approval_ids[]" value="<?php echo $val['id']; ?>"></td><?php } ?>
							<td><a href="user/<?php echo $this->users_info[$val['uid']]['url_token']; ?>" target="_blank"><?php echo $this->users_info[$val['uid']]['user_name']; ?></a></td>
							<td><?php echo $val['name']; ?></td>
							<td><?php if ($val['type'] == 'enterprise') { ?><?php _e('机构认证'); ?><?php } else { ?><?php _e('个人认证'); ?><?php } ?></td>
							<td>
								<?php if ($val['type'] == 'enterprise') { ?><?php _e('组织机构代码'); ?><?php } else { ?><?php _e('身份证号码'); ?><?php } ?>: <?php echo $val['data']['id_code']; ?><br />
								<?php _e('联系方式'); ?>: <?php echo $val['data']['contact']; ?>
							</td>
							<td><?php echo $val['reason']; ?></td>
							<td class="nowrap">
								<?php if ($val['attach']) { ?><a href="<?php echo get_setting('upload_url'); ?>/verify/<?php echo $val['attach']; ?>" target="_blank" data-toggle="tooltip" class="icon icon-log md-tip" title="<?php _e('附件'); ?>"></a><?php } ?>

								<a href="admin/user/verify_approval_edit/<?php echo $val['uid']; ?>" data-toggle="tooltip" class="icon icon-edit md-tip" title="<?php _e('编辑'); ?>"></a>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			<?php } ?>
			</div>
			</form>
			<div class="mod-table-foot">
				<span class="pull-right mod-page"><?php echo $this->pagination; ?></span>

				<?php if ($_GET['status'] != 1) { ?>
				<a class="btn btn-primary" onclick="$('#batch_type').val('approval'); AWS.ajax_post($('#batchs_form'));" id="batch_approval"><?php _e('通过审核'); ?></a>
				<a class="btn btn-danger" onclick="$('#batch_type').val('decline'); AWS.ajax_post($('#batchs_form'));" id="batch_decline"><?php _e('拒绝审核'); ?></a>
				<?php } ?>
			</div>
		</div>
	</div>
</div>

<?php View::output('admin/global/footer.php'); ?>