<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
            <h3>
                <span class="pull-left"><?php _e('微博消息接收'); ?></span>
            </h3>
        </div>

        <div class="tab-content mod-content">
            <table class="table table-striped">
                <tr><td>
                <div class="form-group">
                    <span class="col-sm-4 col-xs-3 control-label"><?php _e('导入微博消息至'); ?>:</span>
                    <div class="col-sm-6 col-xs-8">
                        <label class="checkbox-inline">
                            <input type="radio" name="weibo_msg_enabled" value="question"<?php if (get_setting('weibo_msg_enabled') == 'question') { ?> checked="checked"<?php } ?> />
                            <?php _e('问题'); ?>
                        </label>

                        <?php if (check_extension_package('ticket')) { ?>
                        <label class="checkbox-inline">
                            <input type="radio" name="weibo_msg_enabled" value="ticket"<?php if (get_setting('weibo_msg_enabled') == 'ticket') { ?> checked="checked"<?php } ?> />
                            <?php _e('工单'); ?>
                        </label>
                        <?php } ?>

                        <label class="checkbox-inline">
                            <input type="radio" name="weibo_msg_enabled" value="N"<?php if (get_setting('weibo_msg_enabled') == 'N') { ?> checked="checked"<?php } ?> />
                            <?php _e('关闭'); ?>
                        </label>
                    </div>
                </div>
                </td></tr>

                <tr><td>
                <div class="form-group">
                    <span class="col-sm-4 col-xs-3 control-label"><?php _e('从指定微博帐号获取 @ta 的内容并导入 WeCenter'); ?>:</span>
                    <div class="col-sm-8 col-xs-8 icb-admin-weibo-answer" >
                        <input type="text" class="form-control search-input" placeholder="<?php _e('添加用户并绑定指定微博帐号'); ?>">
                        <div class="icb-dropdown">
                            <p class="title"><?php _e('没有找到相关结果'); ?></p>
                            <ul class="icb-dropdown-list">
                                <li></li>
                            </ul>
                        </div>

                        <ul class="mod-weibo-reply">
                            <?php if ($this->tmp_service_users_info) { foreach ($this->tmp_service_users_info AS $tmp_service_user_info) { ?>
                            <li>
                                <a class="reply-name" href="user/<?php echo $tmp_service_user_info['url_token']; ?>" target="_blank"><img src="<?php echo get_avatar_url($tmp_service_user_info['uid'], 'min'); ?>" alt="" /><?php echo $tmp_service_user_info['user_name']; ?></a>
                                <a class="btn btn-primary btn-sm" href="account/sina/binding/uid-<?php echo $tmp_service_user_info['uid']; ?>" target="_blank"><?php _e('绑定微博'); ?></a>
                                <a class="btn btn-danger btn-sm delete" data-id="<?php echo $tmp_service_user_info['uid']; ?>" data-actions="del_service_user"><?php _e('删除用户'); ?></a>
                            </li>
                            <?php } } ?>

                            <?php if ($this->service_users_info) { foreach ($this->service_users_info AS $service_user_info) { ?>
                            <li>

                                <a class="reply-name" href="user/<?php echo $service_user_info['url_token']; ?>" target="_blank"><img src="<?php echo get_avatar_url($service_user_info['uid'], 'min'); ?>" alt="" /><?php echo $service_user_info['user_name']; ?></a>
                                <a class="btn btn-primary btn-sm" href="account/sina/binding/uid-<?php echo $service_user_info['uid']; ?>" target="_blank"><?php _e('更新 Access Token'); ?></a>
                                <a class="btn btn-danger btn-sm delete" data-actions="del_service_user" data-id="<?php echo $service_user_info['uid']; ?>"><?php _e('删除用户'); ?></a>
                            </li>
                            <?php } } ?>
                        </ul>
                    </div>
                </div>
                </td></tr>

                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('设置微博内容对应提问用户'); ?>:</span>
                            <div class="col-sm-8 col-xs-8 icb-admin-weibo-publish">
                                <input type="text" class="form-control search-input" placeholder="<?php _e('添加微博内容对应提问用户'); ?>">
                                <div class="icb-dropdown">
                                    <p class="title"><?php _e('没有找到相关结果'); ?></p>
                                    <ul class="icb-dropdown-list">
                                        <li></li>
                                    </ul>
                                </div>

                                <?php if ($this->published_user['uid']) { ?>
                                <ul>
                                    <li>
                                        <a class="push-name" href="user/<?php echo $this->published_user['url_token']; ?>" target="_blank"><img class="img" src="<?php echo get_avatar_url($this->published_user['uid'], 'min'); ?>" alt="" /><?php echo $this->published_user['user_name']; ?></a>
                                        <a class="delete btn btn-danger btn-sm" href="javascript:;"><?php _e('删除用户'); ?></a>
                                    </li>
                                </ul>
                                <?php } ?>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

<script>
$(function(){
    $('input[name="weibo_msg_enabled"]').on('ifClicked', function()
    {
        AWS.ajax_request(G_BASE_URL + '/admin/ajax/weibo_batch/', 'action=weibo_msg_enabled&uid=' + this.value);
    });

    AWS.Dropdown.bind_dropdown_list($('.icb-admin-weibo-publish input'), 'adminPublishUser');
    AWS.Dropdown.bind_dropdown_list($('.icb-admin-weibo-answer input'), 'adminAnswerUser');

    if ($('.icb-admin-weibo-publish').find('.btn-danger').length > 0)
    {
        $('.icb-admin-weibo-publish').find('.search-input').hide();
    }
    else
    {
        $('.icb-admin-weibo-publish').find('.search-input').show();
    }
});
</script>

<?php View::output('admin/global/footer.php'); ?>