<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
            <h3>
            <?php View::output('admin/course/nav_inc.php');?>
            </h3>
        </div>

        <div class="mod-body tab-content">
            <div class="tab-pane active" id="list">
                <?php if ($_GET['action'] == 'search') { ?>
                <div class="alert alert-info"><?php _e('找到 %s 条符合条件的内容', intval($this->totalRows)); ?></div>
                <?php } ?>

                <div class="table-responsive">
                <form id="batchs_form" action="admin/ajax/course_remove/" method="post">
                    <input type="hidden" id="action" name="action" value="" />
                <?php if ($this->itemsList) { ?>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><input type="checkbox" class="check-all"></th>
                                <th><?php _e('标题'); ?></th>
                                <th><?php _e('查看'); ?></th>
                                <th><?php _e('关注'); ?></th>
                                <th><?php _e('分类'); ?></th>
                                <th><?php _e('推荐'); ?></th>
                                <th><?php _e('发布时间'); ?></th>
                                <th><?php _e('操作'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($this->itemsList AS $itemInfo) { ?>
                            <tr>
                                <td><input type="checkbox" name="ids[]" value="<?php echo $itemInfo['id']; ?>"></td>

                                <td><a href="course/id-<?php echo $itemInfo['url_token']; ?>" target="_blank"><?php echo $itemInfo['title']; ?></a></td>

                                <td><?php echo $itemInfo['views']; ?></td>

                                <td><?php echo $itemInfo['focuses']; ?></td>

                                <td>
                                    <a href="category/<?php echo $this->parentItemsList[$itemInfo['parent_id']]['url_token'];?>" target="_blank"><?php
                                    echo isset($this->parentItemsList[$itemInfo['parent_id']]) ? $this->parentItemsList[$itemInfo['parent_id']]['title'] : '-'; ?></a>
                                </td>

                                <td><?php echo $itemInfo['is_recommend']==1 ?  ('<b>'. _t('是') . '</b>') : _t('否'); ?></td>

                                <td><?php echo date('Y-m-d H:i', $itemInfo['add_time']); ?></td>

                                <td>
                                  <a href="admin/course/course/id-<?php echo $itemInfo['id']; ?>" class="icon icon-edit md-tip" title="<?php _e('编辑'); ?>" data-toggle="tooltip"></a>
                                <?php if ($itemInfo['is_recommend']==1) {?>
                                  <a href="admin/ajax/course_recommend/id-<?php echo $itemInfo['id']; ?>__recommend-0" class="icon js-recommend-btn icon-fold md-tip" title="<?php _e('取消推荐'); ?>" data-toggle="tooltip"></a>
                                <?php } else { ?>
                                  <a href="admin/ajax/course_recommend/id-<?php echo $itemInfo['id']; ?>__recommend-1" class="icon js-recommend-btn icon-mytopic md-tip" title="<?php _e('推荐'); ?>" data-toggle="tooltip"></a>
                                <?php }?>
                                  <a href="admin/course/homework/id-<?php echo $itemInfo['id']; ?>" class="icon icon-order md-tip" title="<?php _e('课后作业'); ?>" data-toggle="tooltip"></a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>
                </form>
                </div>

                <div class="mod-table-foot">
                    <?php echo $this->pagination; ?>

                    <a class="btn btn-danger" id="deleteBatchBtn"><?php _e('删除教程'); ?></a>
                </div>
            </div>
            <?php View::output('admin/course/search_inc.php');?>

        </div>
    </div>
</div>

<script>
$(function(){
    /**
     * 点击批量删除按钮
     */
    $('#deleteBatchBtn').click(function () {
        if($('.icheckbox_square-blue.checked').length){
    	    ICB.domEvents.deleteShowConfirmModal(
            	   _t('确认删除教程？'),
            	   function(){
        	           $('#action').val('remove');
        	           ICB.ajax.postForm($('#batchs_form'));
        	       }
            );
        } else {
            ICB.modal.alert(_t('请勾选话题'));
        }
    	 return false;;
    });
    /**
     * 点击推荐教程按钮
     */
    $('.js-recommend-btn').click(function () {
        ICB.ajax.requestJson($(this).attr('href'));
        return false;
    });

});
</script>

<?php View::output('admin/global/footer.php'); ?>