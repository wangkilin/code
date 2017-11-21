<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
            <h3>
                <ul class="nav nav-tabs">
                    <li><a href="admin/edm/receiving_list/"><?php _e('账号列表'); ?></a></li>
                    <li class="active"><a href="admin/edm/receiving_config/"><?php _e('全局设置'); ?></a></li>
                    <li><a href="admin/edm/receiving/"><?php _e('新增账号'); ?></a></li>
                </ul>
            </h3>
        </div>

        <div class="tab-content mod-content">
            <form method="post" action="admin/ajax/save_receiving_email_global_config/" onsubmit="return false;" id="receiving_email_global_config_form" class="form-horizontal" role="form">
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <td>
                            <div class="form-group">
                                <span class="col-sm-4 col-xs-3 control-label"><?php _e('导入邮件消息至'); ?>:</span>

                                <div class="col-sm-6 col-xs-8">
                                    <label class="checkbox-inline">
                                        <input type="radio" name="enabled" value="question"<?php if ($this->receiving_email_global_config['enabled'] == 'question') { ?> checked="checked"<?php } ?> />
                                        <?php _e('问题'); ?>
                                    </label>

                                    <?php if (check_extension_package('ticket')) { ?>
                                    <label class="checkbox-inline">
                                        <input type="radio" name="enabled" value="ticket"<?php if ($this->receiving_email_global_config['enabled'] == 'ticket') { ?> checked="checked"<?php } ?> />
                                        <?php _e('工单'); ?>
                                    </label>
                                    <?php } ?>

                                    <label class="checkbox-inline">
                                        <input type="radio" name="enabled" value="N"<?php if ($this->receiving_email_global_config['enabled'] == 'N') { ?> checked="checked"<?php } ?> />
                                        <?php _e('关闭'); ?>
                                    </label>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <div class="form-group">
                                <span class="col-sm-4 col-xs-3 control-label"><?php _e('设置邮件内容对应提问用户'); ?>:</span>

                                <div class="col-sm-5 col-xs-8 icb-admin-email">
                                    <input name="uid" id="addEmail" type="hidden" value="<?php echo $this->receiving_email_global_config['publish_user']['uid']; ?>"/>

                                    <?php if (!$this->receiving_email_global_config['publish_user']['uid']) { ?>
                                    <input type="text" class="form-control search-input" placeholder="<?php _e('添加邮件内容对应提问用户'); ?>">
                                    <?php } ?>
                                    <div class="icb-dropdown">
                                        <p class="title"><?php _e('没有找到相关结果'); ?></p>
                                        <ul class="icb-dropdown-list">
                                            <li></li>
                                        </ul>
                                    </div>

                                    <ul>
                                        <?php if ($this->receiving_email_global_config['publish_user']['uid']) { ?>
                                        <li>
                                            <a class="push-name" href="user/<?php echo $this->receiving_email_global_config['publish_user']['url_token']; ?>" target="_blank"><img class="img" src="<?php echo get_avatar_url($this->receiving_email_global_config['publish_user']['uid'], 'min'); ?>" alt="" /><?php echo $this->receiving_email_global_config['publish_user']['user_name']; ?></a>
                                            <a class="delete btn btn-danger btn-sm" href="javascript:;"><?php _e('删除用户'); ?></a>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            </form>

            <div class="mod-table-foot mod-one-btn">
                <div class="center-block">
                <?php if ($this->receiving_email_global_config['publish_user']['uid']) { ?>
                     <input type="button" value="<?php _e('保存设置'); ?>" class="btn btn-primary"/>
                <?php } else { ?>
                     <input type="button" value="<?php _e('保存设置'); ?>" class="btn btn-primary" onclick="AWS.ajax_post($('#receiving_email_global_config_form'));" />
                <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){

        <?php if ($this->receiving_email_global_config['publish_user']['uid']) { ?>
        $('.center-block .btn-primary').click(function(){

            if($('.icb-admin-email ul:eq(1)').find("li").length <=0){

                AWS.alert('请设置邮件内容对应提问用户！')

                return false;
            }else{

                AWS.ajax_post($('#receiving_email_global_config_form'));
            }
        });

        function delEmail(_this)
        {
            var tempDom = $('<input type="text" class="form-control search-input" placeholder="设置此账号对应用户">');


            tempDom.insertBefore($('.icb-dropdown'));

            _this.parents('ul').detach();

            AWS.Dropdown.bind_dropdown_list($('.search-input'), 'adminEmailUser');
        }

        <?php } else { ?>

        AWS.Dropdown.bind_dropdown_list($('.search-input:eq(0)'), 'adminEmailUser');

        function delEmail(_this)
        {
            _this.parents('.icb-admin-email').find('.search-input').val('').show('0');

            _this.parents('ul').detach();
        }
        <?php } ?>

        $('.icb-dropdown-list:eq(0)').delegate('li a','click',function()
        {
            _this = $(this)
            addEmail(_this,'#addEmail');
        });


        $(document).on('click', '.delete:eq(0)', function()
        {
            _this = $(this)
            delEmail(_this);
        });

        function addEmail (_this,inputName)
        {
            _this.parents('.icb-admin-email').find('.search-input').css({display:'none'});

            _this.parents('.icb-admin-email').append('<ul><li><a class="push-name" href="' + _this.attr('data-url') +'">' + _this.html() + '</a> <a class="delete btn btn-danger btn-sm">删除用户</a></li></ul>');

            $(inputName).val(_this.attr("data-id"));
        }


    })
</script>
<?php View::output('admin/global/footer.php'); ?>