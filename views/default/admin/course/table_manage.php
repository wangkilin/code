<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
            <h3>
            <?php View::output('admin/course/nav_inc.php');?>
            </h3>
        </div>
        <div class="tab-content mod-body">
            <div class="alert alert-success collapse error_message"></div>
<!--
            <div class="row">
                <form action="admin/course/content_table/" method="post" class="form-horizontal">
                    <label class="pull-left control-label col-sm-2">所属分类:</label>
                    <div class="form-group col-sm-9">
                            <select name="category_id" id="category_id" class="form-control">
                                <option value="0" data-module="0"><?php _e('分类'); ?></option>
                                <?php echo $this->itemOptions; ?>
                            </select>
                    </div>
                </form>
            </div>
-->
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th><?php _e('标题'); ?></th>
                        <th><?php _e('分类'); ?></th>
                        <th><?php _e('排序'); ?></th>
                        <th><?php _e('操作'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <form id="table_form" action="admin/ajax/course/save_table_sort" method="post" onsubmit="return false">
                    <?php foreach ($this->list AS $key => $val) { ?>
                    <tr>
                        <td>
                            <?php echo $val['title']; ?>
                        </td>
                        <td>
                            <div class="col-sm-12 clo-xs-12 col-lg-offset-1">
                            <?php echo $this->categoryList[$val['category_id']]['title']; ?>
                            </div>
                        </td>
                        <td class="">
                            <div class="col-sm-10 clo-xs-10 col-lg-offset-1">
                                <input type="text" class="col-sm-10 clo-xs-10 form-control sort-action" name="category[<?php echo $val['id']; ?>][sort]" value="<?php echo $val['sort']; ?>" />
                            </div>
                        </td>
                        <td class="col-sm-3 clo-xs-3">
                            <div class="col-sm-12 clo-xs-12">
                            <a href="admin/course/edit_table/id-<?php echo $val['id']; ?>" data-toggle="tooltip" class="icon icon-edit md-tip" title="<?php _e('编辑'); ?>"></a>
                            <a href="admin/course/publish_table/id-<?php echo $val['id']; ?>" data-toggle="tooltip" class="icon <?php echo $val['is_publish'] ? 'icon-unlock' : 'icon-lock';?> md-tip" title="<?php $val['is_publish'] ?  _e('取消发布') : _e('发布'); ?>"></a>
                            <a onclick="AWS.ajax_request(G_BASE_URL + '/admin/ajax/course/remove_table/', 'id=<?php echo $val['id'];?>');" data-toggle="tooltip" class="icon icon-trash md-tip btn-danger" title="<?php _e('删除'); ?>"></a>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                    </form>
                    </tbody>
                    <tfoot class="mod-foot-center">
                    <tr>
                        <td colspan="5">
                        <form id="add_form" action="admin/ajax/course/save_table/" method="post" onsubmit="return false">

                            <div class="form-group col-sm-4">
                                <span  class="col-sm-3 col-xs-12 mod-category-foot"><?php _e('标题'); ?></span>
                                <div class="col-sm-9 col-xs-12">
                                    <input type="text" class="form-control" name="title" />
                                </div>
                            </div>

                            <div class="form-group col-sm-4">
                                    <select name="category_id" id="category_id" class="form-control">
                                        <option value="0" data-module="0"><?php _e('选择分类'); ?></option>
                                        <?php echo $this->itemOptions; ?>
                                    </select>
                            </div>
                            <div class="col-sm-4 col-xs-12">
                             <a onclick="ICB.ajax.postForm($('#add_form'));" class="btn-primary btn"><?php _e('添加'); ?></a>
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


<script type="text/javascript">
    $(document).ready(function () {
        // 排序输入框，输入数字， 提交内容
        $('input.sort-action').keyup(function () {
            if ($(this).val() != '') {
                AWS.ajax_post($('#add_form'), AWS.ajax_processer, 'error_message');
            }
        });
    });
</script>

<?php View::output('admin/global/footer.php'); ?>
