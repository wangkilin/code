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
            <div class="tab-pane active" id="list">
                <?php if ($_GET['action'] == 'search') { ?>
                <div class="alert alert-info">
                 <?php if ($_GET['category'] && isset($this->categoryList[$_GET['category']])) {
                 	 echo $this->categoryList[$_GET['category']]['title'];
                 	 echo _e(' 分类中');
                    }
                 ?>
                 <?php _e('找到 %s 条符合条件的内容', intval($this->tags_count)); ?>
                </div>
                <?php } ?>

                <div class="table-responsive">
                <form id="batchs_form" action="admin/ajax/tag_manage/" method="post">
                    <input type="hidden" id="action" name="action" value="" />
                    <input type="hidden" id="category_ids" name="category_ids" value="" />
                <?php if ($this->list) { ?>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><input type="checkbox" class="check-all"></th>
                                <th><?php _e('标签'); ?></th>
                                <th><?php _e('讨论'); ?></th>
                                <th><?php _e('关注'); ?></th>
                                <th><?php _e('父级'); ?></th>
                                <th><?php _e('操作'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($this->list AS $tag_info) { ?>
                            <tr>
                                <td><input type="checkbox" name="ids[]" value="<?php echo $tag_info['id']; ?>"></td>

                                <td><a href="tag/<?php echo $tag_info['url_token']; ?>" target="_blank"><?php echo $tag_info['title']; ?></a></td>

                                <td><?php echo $tag_info['discuss_count']; ?></td>

                                <td><?php echo $tag_info['focus_count']; ?></td>

                                <td>
                                    <?php if (isset($this->relationList[$tag_info['id']])) {
                                    foreach ($this->relationList[$tag_info['id']] as $_categoryId) {
                                        if (isset($this->categoryList[$_categoryId])) {
                                    ?>
                                    <a href="admin/tag/list/category-<?php echo $_categoryId; ?>__action-search"><?php echo $this->categoryList[$_categoryId]['title']; ?></a>
                                    <br/>
                                    <?php }
                                    }
                                    }?>
                                </td>

                                <td><a href="admin/tag/tag/id-<?php echo $tag_info['id']; ?>" class="icon icon-edit md-tip" title="<?php _e('编辑'); ?>" data-toggle="tooltip"></a></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>
                </form>
                </div>

                <div class="mod-table-foot">
                    <?php echo $this->pagination; ?>

                    <a class="btn btn-danger" onclick="$('#action').val('remove'); AWS.ajax_post($('#batchs_form'));"><?php _e('删除标签'); ?></a>
                    <a class="btn btn-primary" id="batchs_post"><?php _e('标签归类'); ?></a>
                </div>
            </div>
            <?php View::output('admin/tag/search.php');?>
        </div>
    </div>
</div>

<?php if ($this->categoryList) { ?>
<div id="target-category" class="collapse">
    <?php foreach ($this->categoryList AS $categoryInfo) { ?>
    <option value="<?php echo $categoryInfo['id']; ?>"><?php echo $categoryInfo['title']; ?></option>
    <?php } ?>
</div>
<?php } ?>
<script>
    $(function(){

        var json = '';

        $.each($('#target-category option').toArray(), function (i, e)
        {
            if (i >= 1)
            {
                json += ',';
            }

            json += "{'title':'" + $(e).text() + "', 'id':'" + $(e).val() + "'}";
        });

        $('#batchs_post').click(function()
        {
            if($('.icheckbox_square-blue').hasClass("checked")){
                AWS.dialog('adminCategoryMove', {'option':eval('[' + json + ']'), 'name':'选择分类','from_id':'#batchs_form'});

                $('#action').val('set_category');
                $("#icb-modal-window").find('.form-control').eq(1).attr('multiple','multiple')
                .val('')
                .multiselect({
        			nonSelectedText: '<?php _e('选择标签分类');?>',
        			maxHeight: 200,
        			});
            }else{
                AWS.alert('请勾选标签');
            }

        });

        $("#icb-modal-window").delegate('.form-control:eq(1)','change',function() {

            $('#category_ids').val($("#icb-modal-window .form-control:eq(1)").val());
        });

    })
</script>

<?php View::output('admin/global/footer.php'); ?>