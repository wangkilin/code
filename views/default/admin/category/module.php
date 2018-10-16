<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
            <h3>
            <?php View::output('admin/category/nav_inc.php');?>
            </h3>
        </div>

        <div class="tab-content mod-body">
            <div class="alert alert-success collapse error_message"></div>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th><?php _e('模块标题'); ?></th>
                        <th><?php _e('别名'); ?></th>
                        <th><?php _e('操作'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <form id="category_form" action="admin/ajax/save_category_sort/" method="post" onsubmit="return false">
                    <?php foreach ($this->list AS $key => $val) { ?>
                    <tr>
                        <td>
                            <a href="index/category-<?php echo ($val['url_token']) ? $val['url_token'] : $val['id']; ?>"><?php echo $val['title']; ?></a>
                        </td>
                        <td>
                            <div class="col-sm-6 clo-xs-12 col-lg-offset-3">
                                <input type="text" class="form-control sort-action" name="category[<?php echo $val['id']; ?>][sort]" value="<?php echo $val['url_token']; ?>" />
                            </div>
                        </td>
                        <td >
                            <a href="admin/category/edit/category_id-<?php echo $val['id']; ?>" data-toggle="tooltip" class="icon icon-edit md-tip" title="<?php _e('编辑'); ?>"></a>
                            <a onclick="AWS.ajax_request(G_BASE_URL + '/admin/ajax/remove_category/', 'category_id=<?php echo $val['id'];?>');" data-toggle="tooltip" class="icon icon-trash md-tip" title="<?php _e('删除'); ?>"></a>
                            <a data-id="<?php echo $val['id']; ?>" data-name="<?php echo $val['title']?>" data-url="admin/category/move_contents/category_id-" data-toggle="tooltip" class="icon icon-transfer md-tip move" title="<?php _e('批量移动'); ?>"></a>
                        </td>
                    </tr>
                    <?php } ?>
                    </form>
                    </tbody>
                    <tfoot class="mod-foot-center">
                    <tr>
                        <td colspan="3">
                        <form id="add_module_form" action="admin/ajax/post_module_save/" method="post" onsubmit="return false">
                            <div class="form-group col-sm-5">
                                <span  class="col-sm-3 col-xs-12 mod-category-foot"><?php _e('模块标题'); ?></span>
                                <div class="col-sm-6 col-xs-12">
                                    <input type="text" class="form-control" name="title" />
                                </div>
                            </div><div class="form-group col-sm-5">
                                <span  class="col-sm-3 col-xs-12 mod-category-foot"><?php _e('别名'); ?></span>
                                <div class="col-sm-6 col-xs-12">
                                    <input type="text" class="form-control" name="url_token" />
                                </div>
                            </div>
                            <div class="col-sm-2 col-xs-12">
                             <a onclick="ICB.ajax.postForm($('#add_module_form'));" class="btn-primary btn"><?php _e('添加模块'); ?></a>
                            </div>
                        </form>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<?php View::output('admin/global/footer.php'); ?>
