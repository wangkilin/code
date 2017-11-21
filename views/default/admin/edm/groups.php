<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
            <h3>
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#list" data-toggle="tab"><?php _e('用户群'); ?></a></li>
                    <li><a href="#import" data-toggle="tab"><?php _e('新建'); ?></a></li>
                </ul>
            </h3>
        </div>
        <div class="mod-body tab-content">
            <div class="tab-pane active" id="list">
                <div class="table-responsive">
                <?php if ($this->groups_list) { ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><?php _e('用户群名称'); ?></th>
                                <th><?php _e('用户数量'); ?></th>
                                <th><?php _e('建立时间'); ?></th>
                                <th><?php _e('操作'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($this->groups_list AS $key => $val) { ?>
                            <tr>
                                <td><?php echo $val['title']; ?></td>
                                <td><?php echo $val['users']; ?></td>
                                <td><?php echo date_friendly($val['time']); ?></a>
                                <td>
                                    <a onclick="AWS.dialog('confirm', {'message': '<?php _e('确认删除这个用户群?'); ?>'}, function(){window.location = G_BASE_URL + '/admin/edm/remove_group/<?php echo $val['id']; ?>'}); return false;" class="icon icon-trash md-tip" title="<?php _e('删除'); ?>"></a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>
                </div>
                <div class="mod-table-foot">
                    <span class="pull-right mod-page"><?php echo $this->pagination; ?></span>
                </div>
            </div>

            <div class="tab-pane" id="import">
                <form method="post" action="admin/ajax/edm_add_group/" onsubmit="return false;" id="import_form" class="form-horizontal" role="form">

                    <div class="form-group">
                        <label class="col-sm-2 col-xs-3 control-label"><?php _e('用户群名称'); ?>:</label>

                        <div class="col-sm-5 col-xs-8">
                            <input class="form-control" type="text" name="title" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 col-xs-3 control-label"><?php _e('导入方式'); ?>:</label>

                        <div class="col-sm-5 col-xs-8">
                            <select name="import_type" id="import_type" class="form-control" onchange="set_import_type(this.value)">
                                <option value="text"><?php _e('文本'); ?></option>
                                <option value="system_group"><?php _e('系统用户组'); ?></option>
                                <option value="reputation_group"><?php _e('声望用户组'); ?></option>
                                <option value="last_active"><?php _e('最后活跃时间'); ?></option>
                                <!--<option value="last_login"><?php _e('最后登录时间'); ?></option>-->
                            </select>
                        </div>
                    </div>

                    <div class="form-group hidden-option collapse" id="option_text">
                        <label class="col-sm-2 col-xs-3 control-label"><?php _e('邮件列表'); ?>:</label>

                        <div class="col-sm-5 col-xs-8">
                            <textarea class="form-control textarea" name="email" rows="10"></textarea>

                            <span class="help-block"><?php _e('一行一个邮件地址'); ?></span>
                        </div>
                    </div>

                    <div class="form-group hidden-option collapse" id="option_system_group">
                        <span class="col-sm-3 col-xs-3 control-label"><?php _e('系统用户组'); ?>:</span>

                        <div class="col-sm-8 col-xs-8">
                            <?php foreach ($this->system_user_group AS $key => $val) { ?>
                            <label>
                                <input type="checkbox" name="user_groups[]" value="<?php echo $val['group_id']; ?>" /> <?php echo $val['group_name']; ?>
                            </label>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="form-group hidden-option collapse" id="option_reputation_group">
                        <span class="col-sm-3 col-xs-3 control-label"><?php _e('声望用户组'); ?>:</span>

                        <div class="col-sm-8 col-xs-8">
                            <?php foreach ($this->reputation_user_group AS $key => $val) { ?>
                            <label>
                                <input type="checkbox" name="user_groups[]" value="<?php echo $val['group_id']; ?>" /> <?php echo $val['group_name']; ?>
                            </label>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="form-group hidden-option collapse" id="option_last_active">
                        <span class="col-sm-3 col-xs-3 control-label"><?php _e('最后活跃时间'); ?>:</span>

                        <div class="col-sm-8 col-xs-8">
                            <label class="checkbox-inline"><input type="radio" name="last_active" value="7776000" checked="checked" /> <?php _e('最近三个月'); ?></label>
                            <label class="checkbox-inline"><input type="radio" name="last_active" value="15552000" /> <?php _e('最近半年'); ?></label>
                            <label class="checkbox-inline"><input type="radio" name="last_active" value="31104000" /> <?php _e('最近一年'); ?></label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-5 col-xs-8">
                            <button type="button" onclick="AWS.ajax_post($('#import_form'));" class="btn btn-primary"><?php _e('创建'); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function set_import_type(item_name)
    {
        $('.hidden-option').hide();
        $('#option_' + item_name).show();
    }

    $(document).ready(function () {
        set_import_type($('#import_type').val());
    });
</script>

<?php View::output('admin/global/footer.php'); ?>