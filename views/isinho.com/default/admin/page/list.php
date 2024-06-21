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

            <div class="tab-pane active" id="<?php echo ACTION=='outside' ? 'outside_list' : 'list';?>">
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
                                <td style="white-space: nowrap;">
                                <?php
                                if($val['category_id']) {
                                    echo $this->categoryList[$val['category_id']]['title'];
                                    echo '<br/>';
                                    if ($this->categoryList[$val['category_id']]['publish_area']!=pageModel::PUBLIC_AREA_INSIDE
                                    || $this->categoryList[$val['category_id']]['publish_area']==pageModel::PUBLIC_AREA_NO_LIMIT) {
                                    ?>
                                    <a class="bg-info icon-volume-high" href="page/category-<?php echo empty($this->categoryList[$val['category_id']]['url_token']) ? $val['category_id'] : $this->categoryList[$val['category_id']]['url_token']; ?>" target="_blank" title="<?php _e('外网');?>"></a>
                                    <?php }
                                    if ($this->categoryList[$val['category_id']]['publish_area']==pageModel::PUBLIC_AREA_NO_LIMIT) echo ' / ';
                                    if ($this->categoryList[$val['category_id']]['publish_area']!=pageModel::PUBLIC_AREA_OUTSIDE) { ?>
                                    <a class="bg-info icon-volume-low" href="page/inside_square/category-<?php echo empty($this->categoryList[$val['category_id']]['url_token']) ? $val['category_id'] : $this->categoryList[$val['category_id']]['url_token']; ?>" target="_blank" title="<?php _e('内网');?>"></a>
                                    <?php
                                    }
                                }
                                ?>
                                </td>

                                <td>
                                <?php
                                // 条目不限制显示范围，分类限制显示范围， 这种情况，显示范围由分类来决定
                                if ($val['publish_area']==pageModel::PUBLIC_AREA_NO_LIMIT && $this->categoryList[$val['category_id']]['publish_area']!==pageModel::PUBLIC_AREA_NO_LIMIT) {
                                    $val['publish_area']= $this->categoryList[$val['category_id']]['publish_area'];
                                }
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
                                <td><?php echo $val['is_receipt_required'] == '1' ? ('<a href="admin/page/show_read_log/id-' .$val['id'].'" class="icon-preview" title="'._t('阅读记录').'"></a>') : '-'; ?></td>
                                <td><?php echo $val['description']; ?></td>
                                <td style="white-space: nowrap;">
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
            </div>

            <?php View::output('admin/books/search_inc.php');?>
			<div class="mod-table-foot">
				<?php echo $this->pagination; ?>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function () {
		$('input.enabled-status').on('ifClicked', function () {});
		$('input.enabled-status').on('ifChanged', function () {

            // ICB.ajax.requestJson(
            //     $('#page_list_form').attr('action'),
            //     $('#page_list_form').serialize()
            //     //function (response) {}, // 成功回调
            //     //function (response) {}  // 失败回调
            // );
            // var confirmFlag = true;
            // ICB.modal.confirm('确认变更发布状态吗？', function () {
			//     ICB.ajax.postForm ($('#page_list_form'),  ICB.ajax.processer, 'error_message');
            // });

            // console.info('Changed');
            // $(this).iCheck('toggle');

            return false;
		});

		$('input.enabled-status').on('ifChecked', function () {
            console.info($(this).attr('name'), 'checked');
            paramPageName = $(this).closest('td').find('input[type="hidden"]').attr('name');
            paramPageValue = $(this).closest('td').find('input[type="hidden"]').val();
            paramStatusValue = $(this).val();
            paramStatusName  = $(this).attr('name');
            var param = {};
            param[paramPageName] = paramPageValue;
            param[paramStatusName] = paramStatusValue;
            ICB.ajax.requestJson(
                $('#page_list_form').attr('action'),
                param
                //function (response) {}, // 成功回调
                //function (response) {}  // 失败回调
            );

            return false;
        });

        $('input.enabled-status').on('ifUnchecked', function () {
            console.info($(this).attr('name'), 'checked');
            paramPageName = $(this).closest('td').find('input[type="hidden"]').attr('name');
            paramPageValue = $(this).closest('td').find('input[type="hidden"]').val();
            paramStatusValue = $(this).val();
            paramStatusName  = $(this).attr('name');
            var param = {};
            param[paramPageName] = paramPageValue; // 取消显示， 只需要传递页面id
            ICB.ajax.requestJson(
                $('#page_list_form').attr('action'),
                param
                // function (response) {
                //     ICB.modal.loading(false);

                //     console.info(response);
                // } // 成功回调
                //function (response) {}  // 失败回调
            );

            return false;
        });
	});
</script>

<?php View::output('admin/global/footer.php'); ?>
