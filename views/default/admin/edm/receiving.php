<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
            <h3>
                <?php if (empty($this->receiving_email_config)) { ?>
                <ul class="nav nav-tabs">
                    <li><a href="admin/edm/receiving_list/"><?php _e('账号列表'); ?></a></li>
                    <li><a href="admin/edm/receiving_config/"><?php _e('全局设置'); ?></a></li>
                    <li class="active"><a href="admin/edm/receiving/"><?php _e('新增账号'); ?></a></li>
                </ul>
                <?php } else { ?>
                <span class="pull-left"><?php _e('编辑账号'); ?></span>
                <?php } ?>
            </h3>
        </div>

        <div class="tab-content mod-content">
            <form method="post" action="admin/ajax/save_receiving_email_config/" onsubmit="return false;" id="receiving_email_global_config_form" class="form-horizontal" role="form">
                <table class="table table-striped">
                    <?php if ($this->receiving_email_config) { ?>
                    <input name="id" type="hidden" value="<?php echo $this->receiving_email_config['id']; ?>"/>
                    <?php } ?>

                    <tr><td>
                    <div class="form-group">
                        <span class="col-sm-4 col-xs-3 control-label"><?php _e('服务器'); ?>:</span>

                        <div class="col-sm-5 col-xs-8">
                            <input name="server" type="text" class="form-control" value="<?php echo $this->receiving_email_config['server']; ?>" />
                        </div>
                    </div>
                    </td></tr>

                    <tr><td>
                    <div class="form-group">
                        <span class="col-sm-4 col-xs-3 control-label"><?php _e('协议'); ?>:</span>

                        <div class="col-sm-6 col-xs-8 email-protocol">
                            <label>
                                <input type="radio" name="protocol" value="pop3"<?php if (!$this->receiving_email_config['protocol'] OR $this->receiving_email_config['protocol'] == 'pop3') { ?> checked="checked"<?php } ?> /> POP3
                            </label>

                            <label class="col-md-offset-1">
                                <input type="radio" name="protocol" value="imap"<?php if ($this->receiving_email_config['protocol'] == 'imap') { ?> checked="checked"<?php } ?> /> IMAP
                            </label>

                            <div class="help-block"><?php _e('使用 POP3 协议时，请在服务商处设置“允许收信软件删信”，否则已读邮件会重复收取。'); ?></div>
                        </div>
                    </div>
                    </td></tr>

                    <tr><td>
                    <div class="form-group">
                        <span class="col-sm-4 col-xs-3 control-label"><?php _e('使用安全链接(SSL)连接服务器'); ?>:</span>

                        <div class="col-sm-6 col-xs-8">
                            <div class="btn-group mod-btn">
                                <label type="button" class="btn mod-btn-color">
                                    <input type="radio" name="ssl" value="1"<?php if ($this->receiving_email_config['ssl'] == 1) { ?> checked="checked"<?php } ?> /> <?php _e('是'); ?>
                                </label>

                                <label type="button" class="btn mod-btn-color">
                                    <input type="radio" name="ssl" value="0"<?php if ($this->receiving_email_config['ssl'] != 1) { ?> checked="checked"<?php } ?> /> <?php _e('否'); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    </td></tr>

                    <tr><td>
                    <div class="form-group">
                        <span class="col-sm-4 col-xs-3 control-label"><?php _e('端口'); ?>:</span>

                        <div class="col-sm-5 col-xs-8">
                            <input name="port" type="text" class="form-control" value="<?php echo $this->receiving_email_config['port']; ?>" />

                            <div class="help-block"><?php _e('POP3 默认端口为 110，使用 SSL 协议时默认端口为 995；IMAP 默认端口为 143，使用 SSL 协议时默认端口为 993。留空时使用默认端口。'); ?></div>
                        </div>
                    </div>
                    </td></tr>

                    <tr><td>
                    <div class="form-group">
                        <span class="col-sm-4 col-xs-3 control-label"><?php _e('帐户'); ?>:</span>
                        <div class="col-sm-5 col-xs-8">
                            <input name="username" type="text" class="form-control" value="<?php echo $this->receiving_email_config['username']; ?>"/>
                        </div>
                    </td></tr>

                    <tr>
                        <td>
                            <div class="form-group">
                                <span class="col-sm-4 col-xs-3 control-label"><?php _e('密码'); ?>:</span>
                                <div class="col-sm-5 col-xs-8">
                                    <input name="password" type="password" class="form-control" value="<?php echo $this->receiving_email_config['password']; ?>"/>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <div class="form-group">
                                <span class="col-sm-4 col-xs-3 control-label"><?php _e('设置此账号对应用户'); ?>:</span>

                                <div class="col-sm-5 col-xs-8 icb-admin-email">
                                    <input name="uid" type="hidden" id="setEmail" value="<?php echo $this->receiving_email_user_info['uid']; ?>"/>

                                    <?php if (!$this->receiving_email_user_info['uid']) { ?>
                                    <input type="text" class="form-control search-input" placeholder="<?php _e('设置此账号对应用户'); ?>">
                                    <?php } ?>
                                    <div class="icb-dropdown">
                                        <p class="title"><?php _e('没有找到相关结果'); ?></p>
                                        <ul class="icb-dropdown-list">
                                            <li></li>
                                        </ul>
                                    </div>

                                    <?php if ($this->receiving_email_user_info['uid']) { ?>
                                    <ul>
                                        <li>
                                            <a class="push-name" href="user/<?php echo $this->receiving_email_user_info['url_token']; ?>" target="_blank"><img class="img" src="<?php echo get_avatar_url($this->receiving_email_user_info['uid'], 'min'); ?>" alt="" /><?php echo $this->receiving_email_user_info['user_name']; ?></a>
                                            <a class="delete btn btn-danger btn-sm set" href="javascript:;"><?php _e('删除用户'); ?></a>
                                        </li>
                                    </ul>
                                    <?php } ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </form>

            <div class="mod-table-foot mod-one-btn">
                <div class="center-block">
                 <?php if ($this->receiving_email_user_info['uid']) { ?>
                    <input type="button" value="<?php _e('保存设置'); ?>" class="btn btn-primary"/>
                 <?php } else { ?>
                    <input type="button" value="<?php _e('保存设置'); ?>" class="btn btn-primary" />
                 <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function(){

        <?php if ($this->receiving_email_user_info['uid']) { ?>

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


        $('.center-block .btn-primary').click(function(){

            if($('.icb-admin-email ul:eq(1)').find("li").length <=0){

                AWS.alert('请设置此账号对应用户！')

                return false;
            }else{

                AWS.ajax_post($('#receiving_email_global_config_form'));
            }
        });


        $('.icb-dropdown-list:eq(0)').delegate('li a','click',function()
        {
            _this = $(this)
            addEmail(_this,'#setEmail');
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