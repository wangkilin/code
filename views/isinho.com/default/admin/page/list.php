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
							<th><?php _e('发布范围'); ?></th>
							<th><?php _e('发布时间'); ?></th>
							<th><?php _e('阅读回执'); ?></th>
							<th width="40%"><?php _e('页面描述'); ?></th>
							<th><?php _e('操作'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($this->page_list AS $key => $val) { ?>
						<tr>
							<td>
								<input type="hidden" name="page_ids[<?php echo $val['id']; ?>]" value="<?php echo $val['id']; ?>" />
								<input type="checkbox" class="enabled-status" name="enabled_status[<?php echo $val['id']; ?>]" value="1"<?php if ($val['enabled']) { ?> checked="checked"<?php } ?> />
							</td>
                            <td>
                            <?php
                            if($val['category_id']) {
                                echo $this->categoryList[$val['category_id']]['title'];
                                echo '<br/>';
                                if ($this->categoryList[$val['category_id']]['publish_area']!=pageModel::PUBLIC_AREA_INSIDE
                                 || $this->categoryList[$val['category_id']]['publish_area']==pageModel::PUBLIC_AREA_NO_LIMIT) {
                                ?>
                                <a class="bg-info" href="page/category-<?php echo empty($this->categoryList[$val['category_id']]['url_token']) ? $val['category_id'] : $this->categoryList[$val['category_id']]['url_token']; ?>" target="_blank"><?php _e('外网');?></a>
                                <?php }
                                if ($this->categoryList[$val['category_id']]['publish_area']==pageModel::PUBLIC_AREA_NO_LIMIT) echo ' / ';
                                if ($this->categoryList[$val['category_id']]['publish_area']!=pageModel::PUBLIC_AREA_OUTSIDE) { ?>
                                <a class="bg-info" href="page/inside_square/category-<?php echo empty($this->categoryList[$val['category_id']]['url_token']) ? $val['category_id'] : $this->categoryList[$val['category_id']]['url_token']; ?>" target="_blank"><?php _e('内网');?></a>
                                <?php
                                }
                            }
                            ?>
                            </td>

							<td>
                            <?php
                            if ($val['publish_area']==pageModel::PUBLIC_AREA_NO_LIMIT) { ?>
                                <?php echo $val['title']; ?>
                                <br/>
                                <a class="bg-primary" href="page/index/<?php echo $val['url_token']; ?>" target="_blank"><?php _e('外网');?></a>
                                /
                                <a class="bg-primary" href="page/inside_index/<?php echo $val['url_token']; ?>" target="_blank"><?php _e('内网');?></a>
                            <?php } else if ($val['publish_area']==pageModel::PUBLIC_AREA_INSIDE) { ?>
                                <a href="page/inside_index/<?php echo $val['url_token']; ?>" target="_blank"><?php echo $val['title']; ?></a>
                            <?php } else { ?>
                                <a href="page/index/<?php echo $val['url_token']; ?>" target="_blank"><?php echo $val['title']; ?></a>
                            <?php } ?>

                            </td>
                            <td><?php  echo pageModel::PUBLIC_AREA_LIST[$val['publish_area']];?></td>
                            <td><?php  echo $val['publish_time']==0? _t('立即') : date('Y/m/d',$val['publish_time'] );?></td>
                            <td><?php $val['is_receipt_required'] == '1' ? _e('是') : _e('否'); ?></td>
							<td><?php echo $val['description']; ?></td>
							<td>
								<a href="admin/page/edit/id-<?php echo $val['id']; ?>" title="<?php _e('编辑'); ?>" data-toggle="tooltip" class="icon icon-edit md-tip"></a>
								<a onclick="ICB.modal.confirm('<?php $val['is_top'] ? _e('取消置顶？'):_e('设置置顶');?>', function(){ ICB.ajax.requestJson(G_BASE_URL + '/admin/ajax/page/set_top/', {page_id:<?php echo $val['id']?>,top:<?php echo $val['is_top'] ? 0:1?>}) });"  title="<?php $val['is_top'] ? _e('取消置顶'):_e('设置置顶'); ?>" data-toggle="tooltip" class="icon <?php echo $val['is_top'] ? 'icon-down':'icon-up'; ?> md-tip"></a>
								<a onclick="ICB.domEvents.deleteShowConfirmModal( _t('确认删除？'), function(){ ICB.ajax.requestJson(G_BASE_URL + '/admin/ajax/remove_page/', 'id=<?php echo $val['id']; ?>') });" title="<?php _e('删除'); ?>" data-toggle="tooltip" class="icon icon-trash md-tip"></a>
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
