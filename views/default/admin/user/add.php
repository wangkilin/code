<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
            <h3>
                <ul class="nav nav-tabs">
                    <li><a href="admin/user/list/"><?php _e('会员列表'); ?></a></li>
                    <li><a href="admin/user/list/type-forbidden"><?php _e('封禁用户'); ?></a></li>
                    <li><a href="#search" data-toggle="tab"><?php _e('搜索'); ?></a></li>
                    <li class="active"><a href="#add" data-toggle="tab"><?php _e('添加用户'); ?></a></li>
                </ul>
            </h3>
        </div>

        <div class="tab-content mod-content">
            <div class="tab-pane active" id="add">
            <div class="table-responsive">
            <form action="admin/ajax/save_user/" id="settings_form" method="post">
            <table class="table table-striped">
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('用户名'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input class="form-control" name="user_name" type="text" value="" />
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('邮箱'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input class="form-control" name="email" type="text" value="" />
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('密码'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input class="form-control" name="password" type="password" value="" />
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('用户组'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <select class="form-control" name="group_id">
                                <?php foreach ($this->system_group AS $group) { ?>
                                <?php if ($group == 1 AND !$this->user_info['permission']['is_administortar']) { continue; } ?>
                                <option value="<?php echo $group['group_id']; ?>"<?php if ($group['group_id'] == 4) { ?> selected="selected"<?php } ?>><?php echo $group['group_name']; ?></option>
                                <?php } ?>
                                </select>
                            </div>
                        </div>
                    </td>
                </tr>

                <tfoot>
                <tr>
                    <td>
                        <input type="button" value="<?php _e('添加用户'); ?>" class="btn btn-primary center-block" onclick="AWS.ajax_post($('#settings_form'));" />
                    </td>
                </tr>
                </tfoot>
            </table>
            </form>
            </div>
            </div>

            <div class="tab-pane" id="search">
                <form method="post" action="admin/user/list/" onsubmit="return false;" id="search_form" class="form-horizontal" role="form">

                    <input name="action" type="hidden" value="search" />

                    <div class="form-group">
                        <label class="col-sm-2 col-xs-3 control-label"><?php _e('用户名'); ?>:</label>

                        <div class="col-sm-5 col-xs-8">
                            <input class="form-control" type="text" value="<?php echo rawurldecode($_GET['user_name']); ?>" name="user_name" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 col-xs-3 control-label"><?php _e('邮箱'); ?>:</label>

                        <div class="col-sm-5 col-xs-8">
                            <input class="form-control" type="text" value="<?php echo rawurldecode($_GET['email']); ?>" name="email" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 col-xs-3 control-label"><?php _e('用户组'); ?>:</label>

                        <div class="col-sm-5 col-xs-8">
                            <select name="group_id" class="form-control">
                                <option value=""></option>
                                <?php foreach($this->system_group as $skey => $sval) { ?>
                                <option value="<?php echo $sval['group_id']; ?>"<?php if ($_GET['group_id'] == $sval['group_id']) { ?> selected="selected"<?php } ?>><?php echo $sval['group_name']; ?></option>
                            <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 col-xs-3 control-label"><?php _e('最后登录 IP 段'); ?>:</label>

                        <div class="col-sm-5 col-xs-8">
                            <input class="form-control" type="text" value="<?php echo $_GET['ip']; ?>" name="ip" />

                            <span class="help-block"><?php _e('限 C 段, 如: 203.31.42.*'); ?></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 col-xs-3 control-label"><?php _e('积分'); ?>:</label>

                        <div class="col-sm-6 col-xs-9">
                            <div class="row">
                                <div class="col-xs-11  col-sm-5 mod-double">
                                    <input type="text" class="form-control" name="integral_min" value="<?php echo $_GET['integral_min']; ?>" />
                                </div>
                                <span class="mod-symbol col-xs-1 col-sm-1">
                                -
                                </span>
                                <div class="col-xs-11 col-sm-5">
                                    <input type="text" class="form-control" name="integral_max" value="<?php echo $_GET['integral_max']; ?>" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 col-xs-3 control-label"><?php _e('威望'); ?>:</label>

                        <div class="col-sm-6 col-xs-9">
                            <div class="row">
                                <div class="col-xs-11  col-sm-5 mod-double">
                                    <input type="text" class="form-control" name="reputation_min" value="<?php echo $_GET['reputation_min']; ?>" />
                                </div>
                                <span class="mod-symbol col-xs-1 col-sm-1">
                                -
                                </span>
                                <div class="col-xs-11 col-sm-5">
                                    <input type="text" class="form-control" name="reputation_max" value="<?php echo $_GET['reputation_max']; ?>" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 col-xs-3 control-label"><?php _e('职业'); ?>:</label>

                        <div class="col-sm-5 col-xs-8">
                            <select name="category_id" class="form-control">
                                <option value="0"></option>
                                <?php if ($this->job_list) { foreach ($this->job_list as $job_id => $job_name) { ?>
                                    <option value="<?php echo $job_id; ?>"<?php if ($_GET['job_id'] == $job_id) { ?> selected="selected"<?php } ?>><?php echo $job_name; ?></option>
                                <?php } } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 col-xs-3 control-label"><?php _e('居住地'); ?>:</label>

                        <div class="col-sm-3 col-xs-3">
                            <select name="province" class="select_area form-control"></select>
                        </div>
                        <div class="col-sm-3 col-xs-3">
                            <select name="city" class="select_area form-control collapse"></select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-5 col-xs-8">
                            <button type="button" onclick="AWS.ajax_post($('#search_form'));" class="btn btn-primary"><?php _e('搜索'); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php View::output('admin/global/footer.php'); ?>