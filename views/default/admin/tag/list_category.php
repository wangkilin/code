<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
            <h3>
              <?php View::output('admin/tag/nav.php');?>
            </h3>
        </div>

        <div class="mod-body tab-content">
            <div class="tab-pane active" id="list_category">
                <div class="table-responsive">
                <form id="batchs_form" action="admin/ajax/category_remove/" method="post">
                    <input type="hidden" id="action" name="action" value="" />

                <?php if ($this->list) { ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><input type="checkbox" class="check-all"></th>
                                <th><?php _e('分类标题'); ?></th>
                                <th><?php _e('讨论'); ?></th>
                                <th><?php _e('关注'); ?></th>
                                <th><?php _e('操作'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($this->list AS $categoryInfo) { ?>
                            <tr>
                                <td><input type="checkbox" name="ids[]" value="<?php echo $categoryInfo['id']; ?>"></td>

                                <td><a href="category/<?php echo $categoryInfo['url_token']; ?>" target="_blank"><?php echo $categoryInfo['title']; ?></a></td>

                                <td><?php echo $categoryInfo['discuss_count']; ?></td>

                                <td><?php echo $categoryInfo['focus_count']; ?></td>

                                <td><a href="admin/tag/category/id-<?php echo $categoryInfo['id']; ?>" class="icon icon-edit md-tip" title="<?php _e('编辑'); ?>" data-toggle="tooltip"></a></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>
                </form>
                </div>

                <div class="mod-table-foot">
                    <?php echo $this->pagination; ?>

                    <a class="btn btn-danger" onclick="$('#action').val('remove'); AWS.ajax_post($('#batchs_form'));"><?php _e('删除分类'); ?></a>
                    <a class="btn btn-primary" href="admin/tag/category/"><?php _e('新建分类'); ?></a>
                </div>
            </div>
            <?php View::output('admin/tag/search.php');?>
        </div>
    </div>
</div>

<?php View::output('admin/global/footer.php'); ?>