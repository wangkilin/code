<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
            <h3>
                <ul class="nav nav-tabs">
                    <li><a href="admin/topic/list/"><?php _e('话题管理'); ?></a></li>
                    <li class="active"><a href="#list" data-toggle="tab"><?php _e('根话题'); ?></a></li>
                    <li><a href="admin/topic/edit/"><?php _e('新建话题'); ?></a></li>
                    <li><a href="#search" data-toggle="tab"><?php _e('搜索'); ?></a></li>
                    <li><a href="#today-topics" data-toggle="tab"><?php _e('今日话题'); ?></a></li>
                </ul>
            </h3>
        </div>

        <div class="mod-body tab-content">
            <div class="tab-pane active" id="list">
                <div class="table-responsive">
                <form id="batchs_form" action="admin/ajax/topic_manage/" method="post">
                    <input type="hidden" id="action" name="action" value="" />

                <?php if ($this->list) { ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><input type="checkbox" class="check-all"></th>
                                <th><?php _e('话题标题'); ?></th>
                                <th><?php _e('讨论'); ?></th>
                                <th><?php _e('关注'); ?></th>
                                <th><?php _e('合并'); ?></th>
                                <th><?php _e('状态'); ?></th>
                                <th><?php _e('最后编辑用户'); ?></th>
                                <th><?php _e('最后编辑时间'); ?></th>
                                <th><?php _e('操作'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($this->list AS $topic_info) { ?>
                            <tr>
                                <td><input type="checkbox" name="topic_ids[]" value="<?php echo $topic_info['topic_id']; ?>"></td>

                                <td><a href="topic/<?php echo $topic_info['url_token']; ?>" target="_blank"><?php echo $topic_info['topic_title']; ?></a></td>

                                <td><?php echo $topic_info['discuss_count']; ?></td>

                                <td><?php echo $topic_info['focus_count']; ?></td>

                                <td><?php if ($topic_info['merged_id']) { ?><?php _e('是'); ?><?php } else { ?><?php _e('否'); ?><?php } ?></td>

                                <td>
                                    <?php if ($topic_info['topic_lock']) { ?>
                                    <a onclick="AWS.ajax_request(G_BASE_URL + '/admin/ajax/lock_topic/', 'topic_id=<?php echo $topic_info['topic_id']; ?>&status=0');" title="<?php _e('解除锁定'); ?>" data-toggle="tooltip" class="icon icon-lock md-tip"></a><?php } else { ?><a href="javascript:;" onclick="AWS.ajax_request(G_BASE_URL + '/admin/ajax/lock_topic/', 'topic_id=<?php echo $topic_info['topic_id']; ?>&status=1');" data-toggle="tooltip" title="<?php _e('锁定话题'); ?>" class="icon icon-lock md-tip"></a>
                                    <?php } ?>
                                </td>

                                <td><a href="user/<?php echo $this->users_info[$topic_info['last_edited_uid']]['url_token']; ?>" target="_blank"><?php echo $this->users_info[$topic_info['last_edited_uid']]['user_name'] ?></a></td>

                                <td><?php echo date_friendly($topic_info['last_edited_time']); ?></td>

                                <td><a href="admin/topic/edit/topic_id-<?php echo $topic_info['topic_id']; ?>" class="icon icon-edit md-tip" title="<?php _e('编辑'); ?>" data-toggle="tooltip"></a></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>
                </form>
                </div>

                <div class="mod-table-foot">
                    <?php echo $this->pagination; ?>

                    <a class="btn btn-danger" onclick="$('#action').val('remove'); AWS.ajax_post($('#batchs_form'));"><?php _e('删除话题'); ?></a>
                    <a class="btn btn-primary" onclick="$('#action').val('lock'); AWS.ajax_post($('#batchs_form'));"><?php _e('锁定话题'); ?></a>
                </div>
            </div>

            <div class="tab-pane" id="search">
                <form method="post" action="admin/topic/list/" onsubmit="return false;" id="search_form" class="form-horizontal" role="form">

                    <input name="action" type="hidden" value="search" />

                    <div class="form-group">
                        <label class="col-sm-2 col-xs-3 control-label"><?php _e('关键词'); ?>:</label>

                        <div class="col-sm-5 col-xs-8">
                            <input class="form-control" type="text" value="<?php echo rawurldecode($_GET['keyword']); ?>" name="keyword" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 col-xs-3 control-label"><?php _e('添加时间范围'); ?>:</label>

                        <div class="col-sm-6 col-xs-9">
                            <div class="row">
                                <div class="col-xs-11  col-sm-5 mod-double">
                                    <input type="text" class="form-control mod-data" value="<?php echo base64_decode($_GET['start_date']); ?>" name="start_date" />
                                    <i class="icon icon-date"></i>
                                </div>
                                <span class="mod-symbol col-xs-1 col-sm-1">
                                -
                                </span>
                                <div class="col-xs-11 col-sm-5">
                                    <input type="text" class="form-control mod-data" value="<?php echo base64_decode($_GET['end_date']); ?>" name="end_date" />
                                    <i class="icon icon-date"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 col-xs-3 control-label"><?php _e('作者'); ?>:</label>

                        <div class="col-sm-5 col-xs-8">
                            <input class="form-control" type="text" value="<?php echo $_GET['user_name']; ?>" name="user_name" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 col-xs-3 control-label"><?php _e('讨论数'); ?>:</label>

                        <div class="col-sm-6 col-xs-9">
                            <div class="row">
                                <div class="col-xs-11  col-sm-5 mod-double">
                                    <input type="text" class="form-control" name="discuss_count_min" value="<?php echo $_GET['discuss_count_min']; ?>" />
                                </div>
                                <span class="mod-symbol col-xs-1 col-sm-1">
                                -
                                </span>
                                <div class="col-xs-11 col-sm-5">
                                    <input type="text" class="form-control" name="discuss_count_max" value="<?php echo $_GET['discuss_count_max']; ?>" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-5 col-xs-8">
                            <button type="button" onclick="AWS.ajax_post($('#search_form'));" class="btn btn-primary"><?php _e('搜索'); ?></button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="tab-pane" id="today-topics">
                <form method="post" action="admin/ajax/save_today_topics/" onsubmit="return false;" id="today_topics_form" class="form-horizontal" role="form">
                    <div class="form-group">
                        <span class="col-sm-2 col-xs-3 control-label"><?php _e('今日话题'); ?>:</span>
                        <div class="col-sm-5 col-xs-8">
                            <input type="text" name="today_topics" class="form-control" value="<?php echo get_setting('today_topics'); ?>" />

                            <span class="help-block"><?php _e('填写话题名称, 系统每天随机选出一个作为今日话题, 留空则不显示今日话题, 可填写一个或多个话题, 多个话题请用 , 分隔'); ?></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-5 col-xs-8">
                            <button type="button" onclick="AWS.ajax_post($('#today_topics_form'));" class="btn btn-primary"><?php _e('保存设置'); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php View::output('admin/global/footer.php'); ?>