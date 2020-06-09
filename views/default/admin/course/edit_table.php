<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
	<form action="admin/ajax/course/save_table/" id="info_form" method="post" onsubmit="return false">
	<input type="hidden" name="id" value="<?php echo $this->itemInfo['id']; ?>" />
	<div class="mod">
		<div class="mod-head">
			<h3>
            <?php View::output('admin/course/nav_inc.php');?>
			</h3>
		</div>
		<div class="tab-content mod-content">
			<table class="table table-striped">
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-2 col-xs-3 control-label"><?php _e('标题'); ?>:</span>
							<div class="col-sm-9 col-xs-8">
								<input class="form-control" type="text" name="title" value="<?php echo $this->itemInfo['title']; ?>" />
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-2 col-xs-3 control-label"><?php _e('父级分类'); ?>:</span>
							<div class="col-sm-9 col-xs-8">
								<select name="category_id" id="category_id" class="form-control">
									<option value="0" data-module="0"><?php _e('无'); ?></option>
									<?php echo $this->itemOptions; ?>
								</select>
							</div>
						</div>
					</td>
				</tr>
				<tfoot>
				<tr>
					<td>
						<input type="button" value="<?php _e('保存设置'); ?>" class="btn btn-primary center-block" onclick="ICB.ajax.postForm($('#info_form'));" />
					</td>
				</tr>
				</tfoot>
			</table>
		</div>
		</div>
	</form>
</div>
<?php View::output('admin/global/footer.php'); ?>
