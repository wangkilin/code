<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
            <h3>
				<?php View::output('admin/page/nav_inc.php');?>
            </h3>
        </div>

		<div class="mod-body tab-content">
			<div class="alert alert-success collapse error_message"></div>

			<form action="admin/ajax/save_page_status/" method="post" id="page_list_form">
			<div class="table-responsive">
			<?php if ($this->page_list) { ?>
				<table class="table table-striped">
					<thead>
						<tr>
							<th><?php _e('启用'); ?></th>
							<th><?php _e('页面分类'); ?></th>
							<th><?php _e('页面标题'); ?></th>
							<th width="50%"><?php _e('页面描述'); ?></th>
							<th width="80px"><?php _e('操作'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($this->page_list AS $key => $val) { ?>
						<tr>
							<td>
								<input type="hidden" name="page_ids[<?php echo $val['id']; ?>]" value="<?php echo $val['id']; ?>" />
								<input type="checkbox" class="enabled-status" name="enabled_status[<?php echo $val['id']; ?>]" value="1"<?php if ($val['enabled']) { ?> checked="checked"<?php } ?> />
							</td>
                            <td><?php if($val['category_id']) echo $this->categoryList[$val['category_id']]['title'];?></td>
							<td><a href="page/<?php echo $val['url_token']; ?>" target="_blank"><?php echo $val['title']; ?></a></td>
							<td width="50%"><?php echo $val['description']; ?></td>
							<td>
								<a href="admin/page/edit/id-<?php echo $val['id']; ?>" title="<?php _e('编辑'); ?>" data-toggle="tooltip" class="icon icon-edit md-tip"></a>
								<a onclick="AWS.ajax_request(G_BASE_URL + '/admin/ajax/remove_page/', 'id=<?php echo $val['id']; ?>');" title="<?php _e('删除'); ?>" data-toggle="tooltip" class="icon icon-trash md-tip"></a>
							</td>
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
			AWS.ajax_post($('#page_list_form'),  AWS.ajax_processer, 'error_message');
		});
	});
</script>

<?php View::output('admin/global/footer.php'); ?>
