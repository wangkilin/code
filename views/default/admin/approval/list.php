<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head table-striped" id="approval">
            <h3>
                <ul class="nav nav-tabs">
                    <li class="<?php if ($_GET['type'] == 'question') { ?> active<?php } ?>"><a href="admin/approval/list/"><?php _e('问题'); ?> (<?php echo $this->question_count; ?>)</a></li>

                    <li class="<?php if ($_GET['type'] == 'unverified_modify') { ?> active<?php } ?>"><a href="admin/approval/list/type-unverified_modify"><?php _e('问题修改'); ?> (<?php echo $this->unverified_modifies_count; ?>)</a></li>

                    <li class="<?php if ($_GET['type'] == 'answer') { ?> active<?php } ?>"><a href="admin/approval/list/type-answer"><?php _e('回答'); ?> (<?php echo $this->answer_count; ?>)</a></li>

                    <li class="<?php if ($_GET['type'] == 'article') { ?> active<?php } ?>"><a href="admin/approval/list/type-article"><?php _e('文章'); ?> (<?php echo $this->article_count; ?>)</a></li>

                    <li class="<?php if ($_GET['type'] == 'article_comment') { ?> active<?php } ?>"><a href="admin/approval/list/type-article_comment"><?php _e('文章评论'); ?> (<?php echo $this->article_comment_count; ?>)</a></li>

                    <?php if (get_setting('weibo_msg_enabled') == 'question') { ?><li class="<?php if ($_GET['type'] == 'weibo_msg') { ?> active<?php } ?>"><a href="admin/approval/list/type-weibo_msg"><?php _e('微博消息'); ?> (<?php echo $this->weibo_msg_count; ?>)</a></li><?php } ?>

                    <?php $receiving_email_global_config = get_setting('receiving_email_global_config'); if ($receiving_email_global_config['enabled'] == 'question') { ?><li class="<?php if ($_GET['type'] == 'received_email') { ?> active<?php } ?>"><a href="admin/approval/list/type-received_email"><?php _e('邮件咨询'); ?> (<?php echo $this->received_email_count; ?>)</a></li><?php } ?>
                </ul>
            </h3>
        </div>
        <div class="mod-body tab-content">
            <form id="batchs_form" action="admin/ajax/approval_manage/" method="post">
            <input type="hidden" id="batch_type" name="batch_type" value="approval" />

            <input type="hidden" name="type" value="<?php echo htmlspecialchars($_GET['type']); ?>" />

            <div class="table-responsive">
            <?php if ($this->approval_list) { ?>
                <table class="table">
                    <thead>
                        <tr>
                            <?php if ($_GET['type'] != 'unverified_modify') { ?>
                            <th><input type="checkbox" class="check-all"></th>
                            <?php } ?>
                            
                            <th><?php _e('用户'); ?></th>
                            
                            <?php if (in_array($_GET['type'], array('question', 'unverified_modify', 'article', 'received_email'))) { ?>
                            <th><?php _e('标题'); ?></th>
                            <?php } ?>
                            <th width="50%"><?php if ($_GET['type'] == 'unverified_modify') { _e('待确认修改数'); } else { _e('内容'); } ?></th>
                            <th><?php _e('操作')?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->approval_list AS $approval_info) { ?>
                        <tr>
                            <?php if ($_GET['type'] != 'unverified_modify') { ?>
                            <td>
                                <input type="checkbox" name="approval_ids[]" value="<?php echo $approval_info['id']; ?>">
                            </td>
                            <?php } ?>

                            <td>
                                <a href="user/<?php echo $this->users_info[$approval_info['uid']]['url_token']; ?>" target="_blank"><?php echo $this->users_info[$approval_info['uid']]['user_name']; ?></a>
                            </td>

                            <?php if (in_array($_GET['type'], array('question', 'unverified_modify', 'article', 'received_email'))) { ?>
                            <td><?php
                            switch ($_GET['type']) {
                                case 'question':
                                    echo htmlspecialchars($approval_info['data']['question_content']);
                                    break;

                                case 'unverified_modify':
                                    echo htmlspecialchars($approval_info['question_content']);
                                    break;

                                case 'article':
                                    echo htmlspecialchars($approval_info['data']['title']);
                                    break;

                                case 'received_email':
                                    echo htmlspecialchars($approval_info['subject']);
                                    break;
                            }
                            ?></td>
                            <?php } ?>

                            <td>
                                <?php if ($_GET['type'] == 'unverified_modify') { echo $approval_info['unverified_modify_count']; } else { ?>
                                <a onclick="AWS.dialog('ajaxData', {'title':'<?php _e('内容审核'); ?>', 'url':G_BASE_URL + '/admin/approval/preview/<?php if (in_array($_GET['type'], array('weibo_msg', 'received_email'))) { ?>type-<?php echo $_GET['type']; ?>__id-<?php } echo $approval_info['id']; ?>'});"><?php
                                switch ($_GET['type']) {
                                    case 'question':
                                        echo cjk_substr(htmlspecialchars($approval_info['data']['question_detail']), 0, 128, 'UTF-8', '...');
                                        break;

                                    case 'answer':
                                        echo cjk_substr(htmlspecialchars($approval_info['data']['answer_content']), 0, 128, 'UTF-8', '...');
                                        break;

                                    case 'article':
                                    case 'article_comment':
                                        echo cjk_substr(htmlspecialchars($approval_info['data']['message']), 0, 128, 'UTF-8', '...');
                                        break;

                                    case 'weibo_msg':
                                        echo cjk_substr(htmlspecialchars($approval_info['text']), 0, 128, 'UTF-8', '...');
                                        break;

                                    case 'received_email':
                                        echo cjk_substr(htmlspecialchars($approval_info['content']), 0, 128, 'UTF-8', '...');
                                        break;
                                }
                                ?></a>
                                <?php } ?>
                            </td>

                            <td class="nowrap">
                                <?php if ($_GET['type'] == 'unverified_modify') { ?>
                                <a href="question/id-<?php echo $approval_info['question_id']; ?>__column-log__rf-false" target="_blank" class="icon icon-search md-tip" data-original-title="<?php _e('查看修改日志'); ?>"></a>
                                <?php } else { ?>
                                <a class="icon icon-search md-tip" onclick="AWS.dialog('ajaxData', {'title':'<?php _e('内容审核'); ?>', 'url':G_BASE_URL + '/admin/approval/preview/<?php
                                if (in_array($_GET['type'], array('weibo_msg', 'received_email'))) {
                                ?>type-<?php echo $_GET['type']; ?>__id-<?php
                                }
                                echo $approval_info['id'];
                                ?>'});" data-original-title="<?php _e('查看内容'); ?>"></a>

                                <a href="admin/approval/preview/action-edit<?php
                                if (in_array($_GET['type'], array('weibo_msg', 'received_email'))) {
                                ?>__type-<?php echo $_GET['type'];
                                } ?>__id-<?php
                                echo $approval_info['id'];
                                ?>" class="icon icon-edit md-tip" data-original-title="<?php _e('修改内容'); ?>"></a>
                                <?php } ?>
                                
                                <?php if ($approval_info['uid'] != $this->user_id) { ?><a href="javascript:;" onclick="AWS.ajax_request(G_BASE_URL + '/admin/ajax/forbidden_user/' , 'uid=<?php echo $approval_info['uid']; ?>&status=<?php echo intval($this->users_info[$approval_info['uid']]['forbidden']) ? 0 : 1; ?>');" title="<?php if ($this->users_info[$approval_info['uid']]['forbidden']) { ?><?php _e('解除封禁'); ?><?php } else { ?><?php _e('封禁用户'); ?><?php } ?>" class="icon <?php if ($this->users_info[$approval_info['uid']]['forbidden']) { ?>icon-plus<?php } else { ?>icon-forbid<?php } ?> md-tip"><?php } ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
            </div>
            </form>
            <div class="mod-table-foot">
                <span class="pull-right mod-page"><?php echo $this->pagination; ?></span>

                <?php if ($_GET['type'] != 'unverified_modify') { ?>
                <a class="btn btn-primary" onclick="$('#batch_type').val('approval'); AWS.ajax_post($('#batchs_form'));" id="batch_approval"><?php _e('通过审核'); ?></a>
                <a class="btn btn-danger" onclick="$('#batch_type').val('decline'); AWS.ajax_post($('#batchs_form'));" id="batch_decline"><?php _e('拒绝审核'); ?></a>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?php View::output('admin/global/footer.php'); ?>