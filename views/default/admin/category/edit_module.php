<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
	<form action="admin/ajax/save_module/" id="module_form" method="post" onsubmit="return false">
	<input type="hidden" name="category_id" value="<?php echo $this->category['id']; ?>" />
	<div class="mod">
		<div class="mod-head">
			<h3>
            <?php View::output('admin/category/nav_inc.php');?>
			</h3>
		</div>
		<div class="tab-content mod-content">
			<table class="table table-striped">
				<?php if ($this->category) { ?>
				<?php } ?>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-2 col-xs-3 control-label"><?php _e('模块标题'); ?>:</span>
							<div class="col-sm-9 col-xs-8">
								<input class="form-control" type="text" name="title" value="<?php echo $this->module['title']; ?>" />
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-2 col-xs-3 control-label"><?php _e('模块别名'); ?>:</span>
							<div class="col-sm-9 col-xs-8">
								<span class="col-sm-1 mod-text-inline">/index/module-</span>
								<div class="col-xs-11 col-sm-9 pull-right nopadding">
									<input type="text" name="url_token" class="form-control" value="<?php echo $this->module['url_token']; ?>" />
								</div>
							</div>
						</div>
					</td>
				</tr>
				<tfoot>
				<tr>
					<td>
						<input type="button" value="<?php _e('保存设置'); ?>" class="btn btn-primary center-block" onclick="ICB.ajax.postForm($('#module_form'));" />
					</td>
				</tr>
				</tfoot>
			</table>
		</div>
		</div>
	</form>
</div>
<?php View::output('admin/global/footer.php'); ?>
