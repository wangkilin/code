<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
<div class="mod">
    <div class="mod-head">
        <h3>
            <ul class="nav nav-tabs">
                <li class="active"><a href="#list" data-toggle="tab"><?php _e('二维码列表'); ?></a></li>
                <li><a href="#create" data-toggle="tab"><?php _e('生成二维码'); ?></a></li>
            </ul>
        </h3>
    </div>

    <div class="mod-body tab-content">
        <div class="tab-pane active" id="list">
            <div class="table-responsive">
                <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th><?php _e('描述'); ?></th>
                        <th><?php _e('关注数'); ?></th>
                        <th><?php _e('操作'); ?></th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($this->qr_code_list) { foreach ($this->qr_code_list AS $qr_code_info) { ?>
                    <tr>
                        <td><?php echo $qr_code_info['scene_id']; ?></td>
                        <td><?php echo $qr_code_info['description']; ?></td>
                        <td><?php echo $qr_code_info['subscribe_num']; ?></td>
                        <td>
                            <?php $img_file = get_setting('upload_url') . '/weixin_qr_code/' . $qr_code_info['scene_id'] . '.jpg'; ?>
                            <a onclick="AWS.dialog('imagePreview', {'image' : '<?php echo $img_file; ?>','title':'微信二维码'});" href="javascript:;"  data-toggle="tooltip" title="<?php _e('查看二维码'); ?>" class="icon icon-search md-tip"></a>
                            <a href="javascript:;" onclick="AWS.ajax_request(G_BASE_URL + '/admin/ajax/weixin/remove_qr_code/', 'scene_id=<?php echo $qr_code_info['scene_id'] ?>');" data-toggle="tooltip" title="<?php _e('删除'); ?>" class="icon icon-trash md-tip"></a>
                        </td>
                    </tr>
                    <?php } } ?>
                </tbody>
                </table>
            </div>

            <div class="mod-table-foot">
                <span class="pull-right mod-page"><?php echo $this->pagination; ?></span>
            </div>
        </div>

        <div class="tab-pane" id="create">
        <form method="post" action="admin/ajax/weixin/create_qr_code/" onsubmit="return false;" id="create_form" class="form-horizontal" role="form">
            <div class="form-group">
                <label class="col-sm-2 col-xs-3 control-label"><?php _e('描述'); ?>:</label>

                <div class="col-sm-5 col-xs-8">
                    <input class="form-control" type="text" value="" name="description" />
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-5 col-xs-8">
                    <button type="button" onclick="AWS.ajax_post($('#create_form'));" class="btn btn-primary"><?php _e('生成'); ?></button>
                </div>
            </div>
        </form>
        </div>
    </div>
</div>
</div>

<?php View::output('admin/global/footer.php'); ?>