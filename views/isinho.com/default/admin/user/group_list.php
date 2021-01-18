<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
	<div class="mod">
		<div class="mod-head">
			<h3>
				<ul class="nav nav-tabs">
					<li class="active"><a href="#_members" data-toggle="tab" id="members"><?php _e('会员组'); ?></a></li>
					<li><a href="#_system" data-toggle="tab" id="system"><?php _e('系统组'); ?></a></li>
					<li><a href="#_custom" data-toggle="tab" id="custom"><?php _e('特殊组'); ?></a></li>
					<?php if (check_extension_package('ticket')) { ?><li><a href="admin/ticket/service_group_list/"><?php _e('客服组'); ?></a></li><?php } ?>
				</ul>
			</h3>
		</div>
		<div class="mod-body tab-content">
			<div class="tab-pane active" id="_members">
				<div class="table-responsive">
					<form id="batchs_form" action="admin/ajax/save_user_group/" method="post" onsubmit="return false;">
						<table class="table table-striped" id="members_table">
							<thead>
								<tr>
									<th><input type="checkbox" class="check-all"></th>
									<th>ID</th>
									<th><?php _e('会员组名称'); ?></th>
									<th><?php _e('威望介于'); ?></th>
									<th><?php _e('威望系数'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php if ($this->mem_group) { foreach ($this->mem_group AS $key => $val) { ?>
								<tr>
									<td><input type="checkbox" value="<?php echo $val['group_id']; ?>" name="group_ids[]"></td>
									<td><?php echo $val['group_id']; ?></td>
									<td>
										<input class="form-control" type="text" name="group[<?php echo $val['group_id']; ?>][group_name]" value="<?php echo $val['group_name']; ?>" />
									</td>
									<td class="form-inline">
										<div class="form-group">
											<input type="text" name="group[<?php echo $val['group_id']; ?>][reputation_lower]" value="<?php echo $val['reputation_lower']; ?>" class="form-control control-value" />
											~
											<input type="text" name="group[<?php echo $val['group_id']; ?>][reputation_higer]" class="form-control max-value" value="<?php echo $val['reputation_higer']; ?>" />
										</div>
									</td>
									<td><input type="text" name="group[<?php echo $val['group_id']; ?>][reputation_factor]" class="form-control" value="<?php echo $val['reputation_factor']; ?>" /></td>
									<td><a href="admin/user/group_edit/group_id-<?php echo $val['group_id']; ?>" title="<?php _e('权限编辑'); ?>" class="icon icon-edit md-tip"></a></td>
								</tr>
								<?php } } ?>
								<tr id="members_add_form" class="collapse">
									<td></td>
									<td></td>
									<td>
										<input type="text" class="form-control" name="group_new[group_name][]" placeholder="<?php _e('用户组名称'); ?>" />
									</td>
									<td class="form-inline">
										<div class="form-group">
											<input class="new_input" type="text" name="group_new[reputation_lower][]" />
											~ <input type="text" class="input-small max-value form-control" name="group_new[reputation_higer][]" />
										</div>
									</td>
									<td><input type="text" class="input-small form-control" name="group_new[reputation_factor][]" /></td>
									<td></td>
								</tr>
							</tbody>
						</table>
						<div class="mod-table-foot">
							<a class="btn btn-primary add"><?php _e('新增'); ?></a>
							<a class="btn btn-primary" onclick="AWS.ajax_post($('#batchs_form'));"><?php _e('保存'); ?></a>
							<a class="btn btn-danger" onclick="AWS.ajax_post($('#batchs_form'));"><?php _e('删除'); ?></a>
						</div>
					</form>
				</div>
			</div>

			<div class="tab-pane" id="_system">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>ID</th>
							<th><?php _e('系统组名称'); ?></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($this->system_group AS $key => $val) { ?>
						<tr>
							<td><?php echo $val['group_id']; ?></td>
							<td><?php echo $val['group_name']; ?></td>
							<td><a href="admin/user/group_edit/group_id-<?php echo $val['group_id']; ?>" title="<?php _e('权限编辑'); ?>" class="icon icon-edit md-tip"></a></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>

			<div class="tab-pane" id="_custom">
				<form method="post" action="admin/ajax/save_custom_user_group/" id="custom_form">
				<table class="table table-striped" id="custom_table">
					<thead>
						<tr>
							<th><input type="checkbox" class="check-all"></th>
							<th>ID</th>
							<th><?php _e('名称'); ?></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php if ($this->custom_group) { ?>
						<?php foreach ($this->custom_group AS $key => $val) { ?>
						<tr>
							<td><input type="checkbox" value="<?php echo $val['group_id']; ?>" name="group_ids[]"></td>
							<td><?php echo $val['group_id']; ?></td>
							<td><input type="text" class="form-control" name="group[<?php echo $val['group_id']; ?>][group_name]" value="<?php echo $val['group_name']; ?>" /></td>
							<td><a href="admin/user/group_edit/group_id-<?php echo $val['group_id']; ?>" title="<?php _e('权限编辑'); ?>" class="icon icon-edit md-tip"></a></td>
						</tr>
						<?php } ?>
						<?php } ?>
						<tr id="custom_add_form" class="collapse">
							<td></td>
							<td></td>
							<td><input type="text" class="form-control" name="group_new[group_name][]" placeholder="<?php _e('用户组名称'); ?>" /></td>
							<td></td>
						</tr>
					</tbody>
				</table>
				</form>
				<div class="mod-table-foot">
					<a class="btn btn-primary" onclick="$('#custom_table').append('<tr>' + $('#custom_add_form').html() + '</tr>')"><?php _e('新增'); ?></a>
					<a class="btn btn-primary" onclick="AWS.ajax_post($('#custom_form'));"><?php _e('保存'); ?></a>
					<a class="btn btn-danger" onclick="AWS.ajax_post($('#custom_form'));"><?php _e('删除'); ?></a>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function () {

		// 遍历生成select的option
		var select_arr = get_select_arr(), input_arr = [], template = get_select_template(select_arr);

		// 替换原来的input为select
		$.each($('#members_table .control-value'), function (i, e)
		{
			if (i > 0)
			{
				$(this).parents('.form-group').prepend('<select class="form-control min-value" name="' + $(this).attr('name') + '">' + template + '</select>');
				if ($.inArray($(this).val(), select_arr) != -1)
				{
					var val = $(this).parents('.form-group').find('option').eq($.inArray($(this).val(), select_arr)).val();
					$(this).parents('.form-group').find('select').val(val);
				}
				$(this).detach();
			}
		});

		var _index = $('#members_table .max-value').length - 1;
		$('#members_table .max-value:lt(' + _index + ')').change(function()
		{
			var select_arr = get_select_arr(), input_arr = [], template = get_select_template(select_arr);
			$.each($('#members_table select'), function (i, e)
			{
				input_arr.push($(this).val());
			});
			$('#members_table select').html(template);
			$.each($('#members_table select'), function (i, e)
			{
				$(this).val(input_arr[i]);
			});
		});

		//新加用户组
		$('.add').click(function()
		{
			$('#members_table').append('<tr class="new">' + $('#members_add_form').html() + '</tr>');

			if ($('#members_table tbody tr').length != 2)
			{
				var select_arr = get_select_arr(), template = get_select_template(select_arr);
					$('#members_table .new_input').parents('.form-group').prepend('<select class="form-control min-value" name="group_new[reputation_lower][]"></select>');
					$('#members_table .new_input').detach();
					$('#members_table select').last().append(template);
			}
		});

		function get_select_arr ()
		{
			var arr = [];
			$.each($('#members_table .max-value'), function (i, e)
			{
				if ($('#members_table .max-value').length == 1 || i != $('#members_table .max-value').length - 1)
				{
					if ($(this).val() != '')
					{
						arr.push($(this).val());
					}
				}

			});
			return arr;
		}

		function get_select_template (arr)
		{
			var template = '';
			$.each(arr, function (i, e)
			{
				template = template +  '<option value="' + e + '">' + e + '</option>';
			});
			return template
		}
	});
</script>

<?php View::output('admin/global/footer.php'); ?>