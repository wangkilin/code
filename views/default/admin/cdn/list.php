<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
            <h3>
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#list" data-toggle="tab"><?php _e('文件列表'); ?></a></li>
                </ul>
            </h3>
        </div>
        <div class="mod-body tab-content">
            <div class="tab-pane active" id="list">

                <div class="row">
                    <div class="col-sm-5 text-right">
                        <!-- <label class="line-height-25">责编:</label> -->
                        <select class="form-control" id="sinho_editor">
                            <?php echo $this->itemOptions;?>
                        </select>
                    </div>
                    <div class="col-sm-5 text-right">
                        <select class="form-control" id="sinho_editor">
                            <?php echo $this->itemOptions;?>
                        </select>
                    </div>
                    <div class="col-sm-2 text-right">
                        <a href="javascript:query_workload();" class="btn btn-primary btn-sm date-seach">确认查询</a>
                    </div>
                </div>
                <form id="batchs_form" action="admin/ajax/file_manager/" method="post">
                <input type="hidden" id="action" name="action" value="del" />
                <div class="table-responsive">
                <?php if ($this->list) { ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><input type="checkbox" class="check-all"></th>
                                <th><?php _e('文件名'); ?></th>
                                <th><?php _e('修改时间'); ?></th>
                                <th><?php _e('上传时间'); ?></th>
                                <th><?php _e('服务商'); ?></th>
                                <th><?php _e('空间'); ?></th>
                                <th><?php _e('操作'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($this->list AS $key => $val) {  //echo count($this->list); break; ?>
                            <tr>
                                <td><input type="checkbox" name="file_path[]" value="<?php  ?>"></td>
                                <td class="text-left"><i class="icon <?php
                                if ($val['type']=='dir') echo "icon-folder-open"; else echo "icon-draft";
                                ?>"></i><?php echo $val['name']; ?></td>
                                <td><?php echo date('Y-m-d H:i', $val['stat']['mtime']); ?></td>
                                <td><?php echo $val['views']; ?></td>
                                <td><a href="user/<?php echo $val['user_info']['url_token']; ?>" target="_blank"><?php echo $val['user_info']['user_name']; ?></a></td>
                                <td><?php echo date_friendly($val['add_time']); ?></td>
                                <td><a href="publish/article/<?php echo $val['id']; ?>" target="_blank" class="icon icon-edit md-tip" title="编辑"></a></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>
                </div>
                </form>
                <div class="mod-table-foot">
                    <?php echo $this->pagination; ?>

                    <a class="btn btn-danger" onclick="$('#action').val('del'); AWS.ajax_post($('#batchs_form'));" href="javascript:;"><?php _e('删除'); ?></a>
                </div>
            </div>

        </div>
    </div>
</div>

<?php View::output('admin/global/footer.php'); ?>
