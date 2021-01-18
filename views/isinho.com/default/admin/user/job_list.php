<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
            <ul class="nav nav-tabs">
                <li class="active"><a href="admin/user/job_list/"><?php _e('职位列表'); ?></a></li>
                <li><a href="#add" data-toggle="tab"><?php _e('添加职位'); ?></a></li>
            </ul>
        </div>

        <div class="tab-content mod-body">
            <div class="tab-pane active" id="list">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th><?php _e('职位名称'); ?></th>
                        <th><?php _e('操作'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <form id="jobs_form" action="admin/ajax/save_job/" method="post" onsubmit="return false">
                    <?php if ($this->job_list) { ?>
                    <?php foreach ($this->job_list AS $key => $val) { ?>
                    <tr>
                        <td><?php echo $key; ?></td>
                        <td>
                            <div class="row">
                                <div class="col-sm-12">
                                    <input class="job-title form-control" type="text"  name="job_list[<?php echo $key; ?>]" value="<?php echo $val; ?>" />
                                </div>
                            </div>
                        </td>
                        <td><a onclick="AWS.dialog('confirm', {'message': '<?php _e('确认删除?'); ?>'}, function(){AWS.ajax_request(G_BASE_URL + '/admin/ajax/remove_job/', 'id=<?php echo $key; ?>');}); " class="icon icon-trash md-tip" data-toggle="tooltip" title="<?php _e('删除'); ?>"></a></td>
                    </tr>
                    <?php } ?>
                    <?php } ?>
                    </form>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="3">
                            <input type="button" value="<?php _e('保存设置'); ?>" class="btn btn-primary center-block" onclick="AWS.ajax_post($('#jobs_form'));" />
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
            </div>

            <div class="tab-pane" id="add">
            <div class="table-responsive">
                <form method="post" action="admin/ajax/add_job/" onsubmit="return false;" id="new_job_form" class="form-horizontal" role="form">
                    <div class="form-group">
                        <span class="col-sm-2 col-xs-3 control-label"><?php _e('添加新职位'); ?>:</span>
                        <div class="col-sm-5 col-xs-8">
                            <textarea class="form-control textarea" name="jobs" rows="5" ></textarea>

                            <span class="help-block"><?php _e('一行一个职位名称'); ?></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-5 col-xs-8">
                            <button type="button" onclick="AWS.ajax_post($('#new_job_form'));" class="btn btn-primary"><?php _e('保存设置'); ?></button>
                        </div>
                    </div>
                </form>
            </div>
            </div>
        </div>
    </div>
</div>
<?php View::output('admin/global/footer.php'); ?>
