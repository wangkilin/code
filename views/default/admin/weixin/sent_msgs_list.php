<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
            <h3>
                <ul class="nav nav-tabs">
                    <li class="active"><a href="admin/weixin/sent_msgs_list/"><?php _e('群发列表'); ?></a></li>
                    <li><a href="admin/weixin/send_msg_batch/"><?php _e('群发消息'); ?></a></li>
                </ul>
            </h3>
        </div>

        <div class="tab-content mod-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <?php if ($this->msgs_total > 0) { ?>
                    <thead>
                    <tr>
                        <th><?php _e('ID'); ?></th>
                        <th><?php _e('消息 ID'); ?></th>
                        <th><?php _e('目标分组'); ?></th>
                        <th><?php _e('状态'); ?></th>
                        <th><?php _e('创建时间'); ?></th>
                        <th><?php _e('用户数'); ?></th>
                        <th><?php _e('操作'); ?></th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($this->msgs_list AS $msg_info) { ?>
                    <tr>
                        <td><?php echo $msg_info['id']; ?></td>
                        <td><?php echo $msg_info['msg_id']; ?></td>
                        <td><?php echo $msg_info['group_name']; ?></td>
                        <td><?php switch ($msg_info['status']) {
                            case 'unsent':
                                _e('未发送');
                                break;

                            case 'pending':
                                _e('提交成功');
                                break;

                            case 'error':
                                _e('提交失败');
                                break;

                            case 'success':
                                _e('发送成功');
                                break;

                            case 'fail':
                                _e('发送失败');
                                break;

                            case 'wrong':
                                _e('审核失败');
                                break;

                            default:
                                _e('未知');
                                break;
                        } ?></td>
                        <td><?php echo date_friendly($msg_info['create_time']); ?></td>
                        <td><?php echo $msg_info['filter_count']; ?></td>
                        <td><a href="admin/weixin/sent_msg_details/id-<?php echo $msg_info['id']; ?>" data-toggle="tooltip" title="<?php _e('查看'); ?>" class="icon icon-search md-tip"></a></td>
                    </tr>
                    <?php } ?>
                    </tbody>
                    <?php } ?>
                </table>
            </div>

            <div class="mod-table-foot">
                <span class="pull-right mod-page"><?php echo $this->pagination; ?></span>
            </div>
        </div>
    </div>
</div>

<?php View::output('admin/global/footer.php'); ?>