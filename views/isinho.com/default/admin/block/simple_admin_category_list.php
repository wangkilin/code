
				<form method="post" action="<?php echo $this->formAction?>" id="item_form">
                <input type="hidden" name="action" value="" id="js-ajax-action"/>
				<table class="table table-striped" id="item_table">
					<thead>
						<tr>
							<th class="col-sm-1"><input type="checkbox" class="check-all"></th>
							<th class="col-sm-1">ID</th>
							<th class="col-sm-3"><?php _e('名称'); ?></th>
							<th class="col-sm-1">文/理</th>
							<th><?php _e('备注'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php if ($this->itemList) { ?>
						<?php foreach ($this->itemList AS $key => $val) { ?>
						<tr>
							<td><input type="checkbox" value="<?php echo $val['id']; ?>" name="item_ids[]"></td>
							<td><?php echo $val['id']; ?></td>
							<td><input type="text" class="form-control" name="item[<?php echo $val['id']; ?>][name]" value="<?php echo $val['name']; ?>" /></td>
							<td><select name="item[<?php echo $val['id']; ?>][type]" class="form-control">
                                   <?php echo $val['type']; ?>
                                </select>
                            </td>
							<td><input type="text" class="form-control" name="item[<?php echo $val['id']; ?>][remark]" value="<?php echo $val['remark']; ?>" /></td>
						</tr>
						<?php } ?>
						<?php } else { ?>
						<tr id="no_data_message">
							<td colspan="4"><?php _e('没有数据记录'); ?></td>
						</tr>
                        <?php }?>
					</tbody>
				</table>
				</form>
				<table class="collapse">
						<tr id="item_add_form">
							<td></td>
							<td></td>
							<td><input type="text" class="form-control" name="item_new[name][]" placeholder="<?php _e('名称'); ?>" /></td>
							<td>
                                <?php if ($this->itemOptions) { ?>
							    <select name="type" class="form-control  " id="type">
								    <?php echo $this->itemOptions; ?>
							    </select>
                                <?php } ?>
                            </td>
							<td><input type="text" class="form-control" name="item_new[remark][]" placeholder="<?php _e('备注'); ?>" /></td>
						</tr>
				</table>
				<div class="mod-table-foot">
					<a class="btn btn-primary" onclick="$('#no_data_message').remove();$('#item_table').append('<tr>' + $('#item_add_form').html() + '</tr>')"><?php _e('新增'); ?></a>
					<a class="btn btn-primary" onclick="$('#js-ajax-action').val('save');ICB.ajax.postForm($('#item_form'));"><?php _e('保存'); ?></a>
					<a class="btn btn-danger" onclick="ICB.modal.confirm('确认删除么？', function(){$('#js-ajax-action').val('delete');ICB.ajax.postForm($('#item_form'));});"><?php _e('删除'); ?></a>
				</div>
