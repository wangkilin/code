<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
            <h3>
				<ul class="nav nav-tabs">
					<li class="<?php if ($_GET['act'] == 'list') { ?> active<?php } ?>"><a href="admin/feature/list/"><?php _e('专题列表'); ?></a></li>
				    <li class="<?php if ($_GET['act'] == 'add') { ?> active<?php } ?>"><a href="admin/feature/add/"><?php _e('添加专题'); ?></a></li>
				</ul>
            </h3>
        </div>

		<div class="mod-body tab-content">
			<div class="alert alert-success collapse error_message"></div>
			
			<form action="admin/ajax/save_feature_status/" method="post" id="feature_form">
			<div class="table-responsive">
			<?php if ($this->list) { ?>
				<table class="table table-striped">
					<thead>
						<tr>
							<th><?php _e('启用'); ?></th>
							<th><?php _e('专题标题'); ?></th>
							<th><?php _e('描述'); ?></th>
							<th><?php _e('话题'); ?></th>
							<th><?php _e('操作'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($this->list AS $key => $val) { ?>
						<tr>
							<td>
								<input type="hidden" name="feature_ids[<?php echo $val['id']; ?>]" value="<?php echo $val['id']; ?>" />
								
								<input type="checkbox" class="enabled-status" name="enabled_status[<?php echo $val['id']; ?>]" value="1"<?php if ($val['enabled']) { ?> checked="checked"<?php } ?> />
							</td>
							<td><a href="feature/<?php echo $val['id']; ?>" target="_blank"><?php echo $val['title']; ?></a></td>
							<td><?php echo $val['description']; ?></td>
							<td><?php if ($val['topic_count']) { ?><?php _e('%s 个话题', $val['topic_count']); ?><?php } else { ?> - <?php } ?></td>
							<td><a href="admin/feature/edit/feature_id-<?php echo $val['id']; ?>" title="<?php _e('编辑'); ?>" class="icon icon-edit md-tip"></a>
							<a onclick="AWS.ajax_request(G_BASE_URL + '/admin/ajax/remove_feature/', 'feature_id=<?php echo $val['id']; ?>');" title="<?php _e('删除'); ?>" class="icon icon-trash md-tip"></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			<?php } ?>
			</div>
			</form>
			<div class="mod-table-foot">
				<?php echo $this->pagination; ?>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function () {
		$('input.enabled-status').on('ifChanged', function () {
			AWS.ajax_post($('#feature_form'),  AWS.ajax_processer, 'error_message');
		});
	});
</script>

<?php View::output('admin/global/footer.php'); ?>