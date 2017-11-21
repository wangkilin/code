<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
            <h3>
				<ul class="nav nav-tabs">
					<li class="<?php if ($_GET['status'] == 0) { ?>active<?php } ?>"><a href="admin/question/report_list/"><?php _e('新举报'); ?></a></li>
					<li class="<?php if ($_GET['status'] == 1) { ?>active<?php } ?>"><a href="admin/question/report_list/status-1"><?php _e('已处理'); ?></a></li>
				</ul>
            </h3>
        </div>

		<div class="mod-body tab-content">
			<form action="admin/ajax/report_manage/" id="batchs_form" onsubmit="return false;" method="post">
			<input name="action_type" id="action_type" type="hidden" value="" />
			<div class="table-responsive">
			<?php if ($this->list) { ?>
				<table class="table table-striped">
					<thead>
						<tr>
							<th><input type="checkbox" class="check-all"></th>
							<th><?php _e('地址'); ?></th>
							<th><?php _e('理由'); ?></th>
							<th><?php _e('时间'); ?></th>
							<th><?php _e('举报人'); ?></th>
							<th><?php _e('操作'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($this->list AS $key => $val) { ?>
						<tr>
							<tr>
								<td><input name="report_ids[]" value="<?php echo $val['id']; ?>" type="checkbox" /></td>
								<td><a href="<?php echo $val['url']; ?>" target="_blank"><?php echo $val['url']; ?></a></td>
								<td><?php echo nl2br($val['reason']); ?></td>
								<td><?php echo date_friendly($val['add_time']); ?></td>
								<td><a href="user/<?php echo $val['user']['url_token']; ?>" target="_blank"><?php echo $val['user']['user_name']; ?></a></td>
								<td align="center"><?php if ($_GET['status'] == 1) { ?><i title="<?php _e('已处理'); ?>" class="icon icon-followed md-tip"></i><?php } else { ?><a onclick="AWS.ajax_request(G_BASE_URL + '/admin/ajax/report_manage/', 'action_type=handle&report_ids[]=<?php echo $val['id']; ?>')" data-toggle="tooltip" title="<?php _e('标记为已处理'); ?>" class="icon icon-check md-tip"></a><?php } ?></td>
							</tr>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			<?php } ?>
			</div>
			</form>
			<div class="mod-table-foot">
				<span class="pull-right mod-page"><?php echo $this->pagination; ?></span>

				<a onclick="$('#action_type').val('delete'); AWS.ajax_post($('#batchs_form'));" class="btn-danger btn"><?php _e('删除'); ?></a>
				<?php if ($_GET['status'] == 0) { ?><a onclick="$('#action_type').val('handle'); AWS.ajax_post($('#batchs_form'));" class="btn-primary btn"><?php _e('标记'); ?></a><?php } ?>
			</div>
		</div>
	</div>
</div>

<?php View::output('admin/global/footer.php'); ?>