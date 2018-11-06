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
                        <th><?php _e('分类标题'); ?></th>
                        <th><?php _e('别名'); ?></th>
                        <th><?php _e('排序'); ?></th>
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
                            <div class="col-sm-12 clo-xs-12 col-lg-offset-1">
                            <?php echo $val['url_token']; ?>
                            </div>
                        </td>
                        <td class="">
                            <div class="col-sm-10 clo-xs-10 col-lg-offset-1">
                                <input type="text" class="col-sm-10 clo-xs-10 form-control sort-action" name="category[<?php echo $val['id']; ?>][sort]" value="<?php echo $val['sort']; ?>" />
                            </div>
                        </td>
                        <td class="col-sm-3 clo-xs-3">
                            <div class="col-sm-12 clo-xs-12">
                            <a href="admin/category/edit/category_id-<?php echo $val['id']; ?>" data-toggle="tooltip" class="icon icon-edit md-tip" title="<?php _e('编辑'); ?>"></a>
                            <a onclick="AWS.ajax_request(G_BASE_URL + '/admin/ajax/remove_category/', 'category_id=<?php echo $val['id'];?>');" data-toggle="tooltip" class="icon icon-trash md-tip" title="<?php _e('删除'); ?>"></a>
                            <a data-id="<?php echo $val['id']; ?>" data-name="<?php echo $val['title']?>" data-url="admin/category/move_contents/category_id-" data-toggle="tooltip" class="icon icon-transfer md-tip move" title="<?php _e('批量移动'); ?>"></a>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                    </form>
                    </tbody>
                    <tfoot class="mod-foot-center">
                    <tr>
                        <td colspan="4">
                        <form id="add_category_form" action="admin/ajax/save_category/" method="post" onsubmit="return false">
                            <div class="form-group col-sm-3">
                                <span  class="col-sm-3 col-xs-12 mod-category-foot"><?php _e('标题'); ?></span>
                                <div class="col-sm-9 col-xs-12">
                                    <input type="text" class="form-control" name="title" />
                                </div>
                            </div>
                            <div class="form-group col-sm-3">
                                <span  class="col-sm-3 col-xs-12 mod-category-foot"><?php _e('别名'); ?></span>
                                <div class="col-sm-9 col-xs-12">
                                    <input type="text" class="form-control" name="url_token" />
                                </div>
                            </div>

                            <div class="form-group col-sm-2">
                                    <select name="parent_id" id="parent_id" class="form-control">
                                        <option value="0" data-module="0"><?php _e('父级分类'); ?></option>
                                        <?php echo $this->category_option; ?>
                                    </select>
                            </div>

                            <div class="form-group col-sm-2">
                                    <select name="module" id="module_id" class="form-control">
                                        <option value="0"><?php _e('所属模块'); ?></option>
                                        <?php echo $this->module_option; ?>
                                    </select>
                            </div>
                            <div class="col-sm-2 col-xs-12">
                             <a onclick="AWS.ajax_post($('#add_category_form'));" class="btn-primary btn"><?php _e('添加分类'); ?></a>
                            </div>
                        </form>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div id="target-category" class="collapse">
        <?php echo $this->target_category; ?>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        $('input.sort-action').keyup(function () {
            if ($(this).val() != '')
            {
                AWS.ajax_post($('#category_form'), AWS.ajax_processer, 'error_message');
            }
        });

        var json = '';

        $.each($('#target-category option').toArray(), function (i, e)
        {
            if (i >= 1)
            {
                json += ',';
            }

            json += "{'title':'" + $(e).text() + "', 'id':'" + $(e).val() + "'}";
        });


        $('.move').click(function()
        {
            AWS.dialog('adminCategoryMove', {'option':eval('[' + json + ']'), 'name':'分类移动','from_id':'#settings_form'});
            $('.from-category').val($(this).attr('data-id'));
            $('.icb-category-move-box .col-md-12').prepend('<p>将 <b>' + $(this).attr('data-name') + '</b> 的内容批量移动到</p>');
        });
        // 选择分类后， 将对应模块选定。 如果是根分类， 需要选择所属的模块
        $('#parent_id').change(function () {
            var moduleId = $(this).find('option:selected').attr('data-module');
            $('#module_id').val(moduleId);
            if (moduleId!='0') {
                $('#module_id').attr('disabled', 'disabled');
            } else {
                $('#module_id').removeAttr('disabled');
            }
        });
    });
</script>

<?php View::output('admin/global/footer.php'); ?>
