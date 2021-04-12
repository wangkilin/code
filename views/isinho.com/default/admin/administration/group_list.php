<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
	<div class="mod">
		<div class="mod-head">
			<h3>
				<ul class="nav nav-tabs">
					<li class="active"><a href="#_members" data-toggle="tab" id="members"><?php _e('组列表'); ?></a></li>
				</ul>
			</h3>
		</div>
		<div class="mod-body tab-content">

			<div class="tab-pane active" id="_custom">
				<form method="post" action="admin/ajax/administration/save_group/" id="custom_form">
                <input type="hidden" name="action" value="" id="js-ajax-action"/>
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
							<td><a href="admin/administration/group_edit/group_id-<?php echo $val['group_id']; ?>" title="<?php _e('权限编辑'); ?>" class="icon icon-edit md-tip"></a></td>
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
					<a class="btn btn-primary" onclick="$('#js-ajax-action').val('save');ICB.ajax.postForm($('#custom_form'));"><?php _e('保存'); ?></a>
					<a class="btn btn-danger" onclick="$('#js-ajax-action').val('delete');ICB.ajax.postForm($('#custom_form'));"><?php _e('删除'); ?></a>
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
