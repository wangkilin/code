<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
	<div class="mod">
		<div class="mod-head">
			<h3>
				<ul class="nav nav-tabs">
					<li class="active"><a href="#list" data-toggle="tab"><?php _e('问题列表'); ?></a></li>
					<li><a href="#search" data-toggle="tab"><?php _e('搜索'); ?></a></li>
				</ul>
			</h3>
		</div>
		<div class="mod-body tab-content">
			<div class="tab-pane active" id="list">
				<?php if ($_GET['action'] == 'search') { ?>
				<div class="alert alert-info"><?php _e('找到 %s 条符合条件的内容', intval($this->question_count)); ?></div>
				<?php } ?>

				<form id="batchs_form" action="admin/ajax/question_manage/" method="post">
				<input type="hidden" id="action" name="action" value="" />
				<div class="table-responsive">
				<?php if ($this->list) { ?>
					<table class="table table-striped">
						<thead>
							<tr>
								<th><input type="checkbox" class="check-all"></th>
								<th><?php _e('问题标题'); ?></th>
								<th><?php _e('回答'); ?></th>
								<th><?php _e('关注'); ?></th>
								<th><?php _e('浏览'); ?></th>
								<th><?php _e('作者'); ?></th>
								<th><?php _e('发布时间'); ?></th>
								<th><?php _e('最后更新'); ?></th>
								<th><?php _e('操作'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($this->list AS $key => $val) { ?>
							<tr>
								<td><input type="checkbox" name="question_ids[]" value="<?php echo $val['question_id']; ?>"></td>
								<td><a href="question/<?php echo $val['question_id']; ?>" target="_blank"><?php echo $val['question_content']; ?></a></td>
								<td><?php if ($val['answer_count']) { ?><?php echo $val['answer_count']; ?><?php } else { ?>0<?php } ?></td>
								<td><?php echo $val['focus_count']; ?></td>
								<td><?php echo $val['view_count']; ?></td>
								<td><a href="user/<?php echo $val['user_info']['url_token']; ?>" target="_blank"><?php echo $val['user_info']['user_name']; ?></a></td>
								<td><?php echo date_friendly($val['add_time']); ?></td>
								<td><?php echo date_friendly($val['update_time']); ?></td>
								<td><a href="publish/<?php echo $val['question_id']; ?>" target="_blank" class="icon icon-edit md-tip" title="<?php _e('编辑'); ?>"></a></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				<?php } ?>
				</div>
				</form>
				<div class="mod-table-foot">
					<?php echo $this->pagination; ?>

					<a class="btn btn-danger" onclick="$('#action').val('remove'); AWS.ajax_post($('#batchs_form'));"><?php _e('删除'); ?></a>
				</div>
			</div>

			<div class="tab-pane" id="search">
				<form method="post" action="admin/question/question_list/" onsubmit="return false;" id="search_form" class="form-horizontal" role="form">

					<input name="action" type="hidden" value="search" />

					<div class="form-group">
						<label class="col-sm-2 col-xs-3 control-label"><?php _e('关键词'); ?>:</label>

						<div class="col-sm-5 col-xs-8">
							<input class="form-control" type="text" value="<?php echo rawurldecode($_GET['keyword']); ?>" name="keyword" />
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 col-xs-3 control-label"><?php _e('分类'); ?>:</label>

						<div class="col-sm-5 col-xs-8">
							<select name="category_id" class="form-control">
								<option value="0"></option>
								<?php echo $this->category_list; ?>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 col-xs-3 control-label"><?php _e('发起时间范围'); ?>:</label>

						<div class="col-sm-6 col-xs-9">
							<div class="row">
								<div class="col-xs-11  col-sm-5 mod-double">
									<input type="text" class="form-control mod-data" value="<?php echo base64_decode($_GET['start_date']); ?>" name="start_date" />
									<i class="icon icon-date"></i>
								</div>
								<span class="mod-symbol col-xs-1 col-sm-1">
								-
								</span>
								<div class="col-xs-11 col-sm-5">
									<input type="text" class="form-control mod-data" value="<?php echo base64_decode($_GET['end_date']); ?>" name="end_date" />
									<i class="icon icon-date"></i>
								</div>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 col-xs-3 control-label"><?php _e('作者'); ?>:</label>

						<div class="col-sm-5 col-xs-8">
							<input class="form-control" type="text" value="<?php echo $_GET['user_name']; ?>" name="user_name" />
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 col-xs-3 control-label"><?php _e('回复数'); ?>:</label>

						<div class="col-sm-6 col-xs-9">
							<div class="row">
								<div class="col-xs-11  col-sm-5 mod-double">
									<input type="text" class="form-control" name="answer_count_min" value="<?php echo $_GET['answer_count_min']; ?>" />
								</div>
								<span class="mod-symbol col-xs-1 col-sm-1">
								-
								</span>
								<div class="col-xs-11 col-sm-5">
									<input type="text" class="form-control" name="answer_count_max" value="<?php echo $_GET['answer_count_max']; ?>" />
								</div>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 col-xs-3 control-label"><?php _e('是否有最佳回复'); ?>:</label>

						<div class="col-sm-5 col-xs-8">
							<div class="checkbox mod-padding">
								<label><input type="checkbox" value="1" name="best_answer"<?php if ($_GET['best_answer'] == '1') { ?> checked="checked"<?php } ?>> <?php _e('有最佳回复'); ?></label>
							</div>
						</div>
					</div>

					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-5 col-xs-8">
							<button type="button" onclick="AWS.ajax_post($('#search_form'));" class="btn btn-primary"><?php _e('搜索'); ?></button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?php View::output('admin/global/footer.php'); ?>