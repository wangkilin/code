<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <form action="admin/ajax/save_settings/" id="settings_form" method="post" onsubmit="return false">
    <div class="mod">
        <?php if ($_GET['category'] == 'site') { ?>
        <div class="mod-head">
            <h3>
                <span class="pull-left"><?php _e('站点信息'); ?></span>

                <span class="pull-right"><a href="javascript:;" onclick="AWS.ajax_post($('#settings_form'));" class="btn btn-xs btn-primary mod-site-save"><?php _e('快速保存'); ?></a></span>
            </h3>
        </div>
        <div class="tab-content mod-content">
            <table class="table table-striped">
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('网站名称'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input type="text" class="form-control" name="site_name" value="<?php echo $this->setting['site_name']; ?>">
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('网站简介'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <textarea class="form-control textarea" rows="4" name="description"  ><?php echo $this->setting['description']; ?></textarea>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('网站关键词'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <textarea class="form-control textarea" rows="4" name="keywords"  ><?php echo $this->setting['keywords']; ?></textarea>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('网站 ICP 备案号'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="icp_beian" class="form-control" type="text" value="<?php echo $this->setting['icp_beian']; ?>"/>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('上传目录外部访问 URL 地址'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="upload_url" class="form-control" type="text" value="<?php echo $this->setting['upload_url']; ?>"/>

                                <span class="help-block"><?php _e('末尾不带 / 或 \\'); ?></span>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('上传文件存放绝对路径'); ?></span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="upload_dir" class="form-control" type="text" value="<?php echo $this->setting['upload_dir']; ?>"/>

                                <span class="help-block"><?php _e('末尾不带 / 或 \，目前网站根目录绝对路径：'); echo ROOT_PATH; ?></span>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('static 目录资源 URL 地址'); ?></span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="img_url" class="form-control" type="text" value="<?php echo $this->setting['img_url']; ?>"/>

                                <span class="help-block"><?php _e('末尾不带 / 或 \，留空使用根目录下的 static 资源'); ?></span>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <?php } else if ($_GET['category'] == 'register') { ?>
        <div class="mod-head">
            <h3>
                <span class="pull-left"><?php _e('注册与访问'); ?></span>

                <span class="pull-right"><a href="javascript:;" onclick="AWS.ajax_post($('#settings_form'));" class="btn btn-xs btn-primary mod-site-save"><?php _e('快速保存'); ?></a></span>
            </h3>
        </div>
        <div class="tab-content mod-content">
            <table class="table table-striped">
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-3 col-xs-3 control-label"><?php _e('默认时区'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <select name="default_timezone" class="form-control">
                                    <option value='Etc/GMT+12'<?php if ($this->setting['default_timezone'] == 'Etc/GMT+12') { ?> selected="selected"<?php } ?>><?php _e('(GMT - 12:00 小时) 安尼威托克岛，卡瓦加兰'); ?></option>
                                    <option value='Etc/GMT+11'<?php if ($this->setting['default_timezone'] == 'Etc/GMT+11') { ?> selected="selected"<?php } ?>><?php _e('(GMT - 11:00 小时) 中途岛，萨摩亚'); ?></option>
                                    <option value='Etc/GMT+10'<?php if ($this->setting['default_timezone'] == 'Etc/GMT+10') { ?> selected="selected"<?php } ?>><?php _e('(GMT - 10:00 小时) 夏威夷'); ?></option>
                                    <option value='Etc/GMT+9'<?php if ($this->setting['default_timezone'] == 'Etc/GMT+9') { ?> selected="selected"<?php } ?>><?php _e('(GMT - 9:00 小时) 阿拉斯加'); ?></option>
                                    <option value='Etc/GMT+8'<?php if ($this->setting['default_timezone'] == 'Etc/GMT+8') { ?> selected="selected"<?php } ?>><?php _e('(GMT - 8:00 小时) 太平洋时间'); ?></option>
                                    <option value='Etc/GMT+7'<?php if ($this->setting['default_timezone'] == 'Etc/GMT+7') { ?> selected="selected"<?php } ?>><?php _e('(GMT - 7:00 小时) 美国山区时间'); ?></option>
                                    <option value='Etc/GMT+6'<?php if ($this->setting['default_timezone'] == 'Etc/GMT+6') { ?> selected="selected"<?php } ?>><?php _e('(GMT - 6:00 小时) 美国中部时间，墨西哥市'); ?></option>
                                    <option value='Etc/GMT+5'<?php if ($this->setting['default_timezone'] == 'Etc/GMT+5') { ?> selected="selected"<?php } ?>><?php _e('(GMT - 5:00 小时) 美国东部时间，波哥大，利马'); ?></option>
                                    <option value='Etc/GMT+4'<?php if ($this->setting['default_timezone'] == 'Etc/GMT+4') { ?> selected="selected"<?php } ?>><?php _e('(GMT - 4:00 小时) 大西洋时间（加拿大），加拉加斯，拉巴斯'); ?></option>
                                    <option value='Canada/Newfoundland'<?php if ($this->setting['default_timezone'] == 'Canada/Newfoundland') { ?> selected="selected"<?php } ?>><?php _e('(GMT - 3:30 小时) 纽芬兰'); ?></option>
                                    <option value='Etc/GMT+3'<?php if ($this->setting['default_timezone'] == 'Etc/GMT+3') { ?> selected="selected"<?php } ?>><?php _e('(GMT - 3:00 小时) 巴西，布宜诺斯艾利斯，福克兰群岛'); ?></option>
                                    <option value='Etc/GMT+2'<?php if ($this->setting['default_timezone'] == 'Etc/GMT+2') { ?> selected="selected"<?php } ?>><?php _e('(GMT - 2:00 小时) 大西洋中部，亚森欣，圣赫勒拿岛'); ?></option>
                                    <option value='Etc/GMT+1'<?php if ($this->setting['default_timezone'] == 'Etc/GMT+1') { ?> selected="selected"<?php } ?>><?php _e('(GMT - 1:00 小时) 亚速群岛，佛得角群岛'); ?></option>
                                    <option value='Etc/GMT'<?php if ($this->setting['default_timezone'] == 'Etc/GMT') { ?> selected="selected"<?php } ?>><?php _e('(GMT) 卡萨布兰卡，都柏林，伦敦，里斯本，蒙罗维亚'); ?></option>
                                    <option value='Etc/GMT-1'<?php if ($this->setting['default_timezone'] == 'Etc/GMT-1') { ?> selected="selected"<?php } ?>><?php _e('(GMT + 1:00 小时) 布鲁塞尔，哥本哈根，马德里，巴黎'); ?></option>
                                    <option value='Etc/GMT-2'<?php if ($this->setting['default_timezone'] == 'Etc/GMT-2') { ?> selected="selected"<?php } ?>><?php _e('(GMT + 2:00 小时) 加里宁格勒，南非'); ?></option>
                                    <option value='Etc/GMT-3'<?php if ($this->setting['default_timezone'] == 'Etc/GMT-3') { ?> selected="selected"<?php } ?>><?php _e('(GMT + 3:00 小时) 巴格达，利雅德，莫斯科，奈洛比'); ?></option>
                                    <option value='Iran'<?php if ($this->setting['default_timezone'] == 'Iran') { ?> selected="selected"<?php } ?>><?php _e('(GMT + 3:30 小时) 德黑兰'); ?></option>
                                    <option value='Etc/GMT-4'<?php if ($this->setting['default_timezone'] == 'Etc/GMT-4') { ?> selected="selected"<?php } ?>><?php _e('(GMT + 4:00 小时) 阿布达比，巴库，马斯喀特，第比利斯'); ?></option>
                                    <option value='Asia/Kabul'<?php if ($this->setting['default_timezone'] == 'Asia/Kabul') { ?> selected="selected"<?php } ?>><?php _e('(GMT + 4:30 小时) 喀布尔'); ?></option>
                                    <option value='Etc/GMT-5'<?php if ($this->setting['default_timezone'] == 'Etc/GMT-5') { ?> selected="selected"<?php } ?>><?php _e('(GMT + 5:00 小时) 凯萨琳堡，克拉嗤，塔什干'); ?></option>
                                    <option value='Asia/Kolkata'<?php if ($this->setting['default_timezone'] == 'Asia/Kolkata') { ?> selected="selected"<?php } ?>><?php _e('(GMT + 5:30 小时) 孟买，加尔各答，马德拉斯，新德里'); ?></option>
                                    <option value='Etc/GMT-6'<?php if ($this->setting['default_timezone'] == 'Etc/GMT-6') { ?> selected="selected"<?php } ?>><?php _e('(GMT + 6:00 小时) 阿拉木图，科隆巴，达卡'); ?></option>
                                    <option value='Etc/GMT-7'<?php if ($this->setting['default_timezone'] == 'Etc/GMT-7') { ?> selected="selected"<?php } ?>><?php _e('(GMT + 7:00 小时) 曼谷，河内，雅加达'); ?></option>
                                    <option value='Etc/GMT-8'<?php if (!$this->setting['default_timezone'] OR $this->setting['default_timezone'] == 'Etc/GMT-8') { ?> selected="selected"<?php } ?>><?php _e('(GMT + 8:00 小时) 北京，香港，澳洲伯斯，新加坡，台北'); ?></option>
                                    <option value='Etc/GMT-9'<?php if ($this->setting['default_timezone'] == 'Etc/GMT-9') { ?> selected="selected"<?php } ?>><?php _e('(GMT + 9:00 小时) 大阪，札幌，首尔，东京，亚库次克'); ?></option>
                                    <option value='Etc/GMT-10'<?php if ($this->setting['default_timezone'] == 'Etc/GMT-10') { ?> selected="selected"<?php } ?>><?php _e('(GMT + 10:00 小时) 墨尔本，巴布亚新几内亚，雪梨'); ?></option>
                                    <option value='Etc/GMT-11'<?php if ($this->setting['default_timezone'] == 'Etc/GMT-11') { ?> selected="selected"<?php } ?>><?php _e('(GMT + 11:00 小时) 马加丹，新喀里多尼亚，所罗门群岛'); ?></option>
                                    <option value='Etc/GMT-12'<?php if ($this->setting['default_timezone'] == 'Etc/GMT-12') { ?> selected="selected"<?php } ?>><?php _e('(GMT + 12:00 小时) 新西兰，斐济，马绍尔群岛'); ?></option>
                                    <option value='Etc/GMT-13'<?php if ($this->setting['default_timezone'] == 'Etc/GMT-13') { ?> selected="selected"<?php } ?>><?php _e('(GMT + 13:00 小时) 堪察加半岛，阿那底河'); ?></option>
                                    <option value='Etc/GMT-14'<?php if ($this->setting['default_timezone'] == 'Etc/GMT-14') { ?> selected="selected"<?php } ?>><?php _e('(GMT + 14:00 小时) 圣诞岛'); ?></option>
                                </select>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-3 col-xs-3 control-label"><?php _e('站点关闭'); ?>:</span>
                            <div class="col-sm-6 col-xs-8">
                                <div class="btn-group mod-btn">
                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="site_close" value="Y"<?php if ($this->setting['site_close'] == 'Y') { ?> checked="checked"<?php } ?> /> <?php _e('是'); ?>
                                    </label>

                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="site_close" value="N"<?php if ($this->setting['site_close'] != 'Y') { ?> checked="checked"<?php } ?> /> <?php _e('否'); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-3 col-xs-3 control-label"><?php _e('站点关闭的提示'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input type="text" class="form-control" name="close_notice" value="<?php echo $this->setting['close_notice']; ?>" />
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-3 col-xs-3 control-label"><?php _e('新用户注册显示验证码'); ?>:</span>
                            <div class="col-sm-6 col-xs-8">
                                <div class="btn-group mod-btn">
                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="register_seccode" value="Y"<?php if ($this->setting['register_seccode'] == 'Y') { ?> checked="checked"<?php } ?> /> <?php _e('是'); ?>
                                    </label>

                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="register_seccode" value="N"<?php if ($this->setting['register_seccode'] != 'Y') { ?> checked="checked"<?php } ?> /> <?php _e('否'); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-3 col-xs-3 control-label"><?php _e('新用户注册验证类型'); ?>:</span>
                            <div class="col-sm-8 col-xs-8">
                                <label class="checkbox-inline"><input type="radio" name="register_valid_type" value="email"<?php if ($this->setting['register_valid_type'] == 'email') { ?> checked="checked"<?php } ?> /> <?php _e('邮件验证'); ?></label>

                                <label class="checkbox-inline"><input type="radio" name="register_valid_type" value="approval"<?php if ($this->setting['register_valid_type'] == 'approval') { ?> checked="checked"<?php } ?> /> <?php _e('邮件验证 + 后台审核'); ?></label>

                                <label class="checkbox-inline"><input type="radio" name="register_valid_type" value="N"<?php if ($this->setting['register_valid_type'] == 'N') { ?> checked="checked"<?php } ?> /> <?php _e('不验证'); ?></label>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-3 col-xs-3 control-label"><?php _e('注册类型'); ?>:</span>
                            <div class="col-sm-8 col-xs-8">
                                <label class="checkbox-inline"><input type="radio" name="register_type" value="open"<?php if ($this->setting['register_type'] == 'open') { ?> checked="checked"<?php } ?> /> <?php _e('开放注册'); ?></label>

                                <label class="checkbox-inline"><input type="radio" name="register_type" value="invite"<?php if ($this->setting['register_type'] == 'invite') { ?> checked="checked"<?php } ?> /> <?php _e('邀请注册'); ?></label>

                                <?php if (get_setting('weixin_app_id')) { ?>
                                <label class="checkbox-inline"><input type="radio" name="register_type" value="weixin"<?php if ($this->setting['register_type'] == 'weixin') { ?> checked="checked"<?php } ?> /> <?php _e('微信一站式注册 (不支持 UCenter)'); ?></label>
                                <?php } ?>

                                <label class="checkbox-inline"><input type="radio" name="register_type" value="close"<?php if ($this->setting['register_type'] == 'close') { ?> checked="checked"<?php } ?> /> <?php _e('关闭注册'); ?></label>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-3 col-xs-3 control-label"><?php _e('用户名规则'); ?>:</span>
                            <div class="col-sm-8 col-xs-8">
                                <label class="checkbox-inline"><input type="radio" value="0" name="username_rule"<?php if ($this->setting['username_rule'] == 0) { ?> checked="checked"<?php } ?> /> <?php _e('不限制'); ?></label>
                                <label class="checkbox-inline"><input type="radio" value="1" name="username_rule"<?php if ($this->setting['username_rule'] == 1) { ?> checked="checked"<?php } ?> /> <?php _e('汉字/字母/数字/下划线'); ?></label>
                                <label class="checkbox-inline"><input type="radio" value="2" name="username_rule"<?php if ($this->setting['username_rule'] == 2) { ?> checked="checked"<?php } ?> /> <?php _e('字母/数字/下划线'); ?></label>
                                <label class="checkbox-inline"><input type="radio" value="3" name="username_rule"<?php if ($this->setting['username_rule'] == 3) { ?> checked="checked"<?php } ?> /> <?php _e('汉字'); ?></label>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-3 col-xs-3 control-label"><?php _e('用户名最少字符数'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="username_length_min" class="form-control" type="text" value="<?php echo $this->setting['username_length_min']; ?>"/>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-3 col-xs-3 control-label"><?php _e('用户名最多字符数'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="username_length_max" class="form-control" type="text" value="<?php echo $this->setting['username_length_max']; ?>"/>

                                <span class="help-block"><?php _e('注: 一个汉字等于 2 个字符'); ?></span>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-3 col-xs-3 control-label"><?php _e('用户注册协议'); ?>:</span>
                            <div class="col-sm-7 col-xs-8">
                                <textarea class="form-control textarea" rows="8" name="register_agreement"  ><?php echo $this->setting['register_agreement']; ?></textarea>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-3 col-xs-3 control-label"><?php _e('用户注册名不允许出现以下关键字'); ?>:</span>
                            <div class="col-sm-7 col-xs-8">
                                <textarea class="form-control textarea" rows="5" name="censoruser"  ><?php echo $this->setting['censoruser']; ?></textarea>

                                <span class="help-block"><?php _e('每行填写一个关键字, 如: admin, 管理员等'); ?></span>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-3 col-xs-3 control-label"><?php _e('用户注册后默认关注的用户 ID'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="def_focus_uids" class="form-control" type="text" value="<?php echo $this->setting['def_focus_uids']; ?>"/>

                                <span class="help-block"><?php _e('多个用户 ID 请用 , 隔开'); ?></span>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-3 col-xs-3 control-label"><?php _e('首次登录推荐用户列表'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="welcome_recommend_users" class="form-control" type="text" value="<?php echo $this->setting['welcome_recommend_users']; ?>"/>

                                <span class="help-block"><?php _e('填写用户名, 若有多个用户名请用 , 隔开, 留空系统自动推荐'); ?></span>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-3 col-xs-3 control-label"><?php _e('新用户注册获得邀请数量'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="newer_invitation_num" type="text" class="form-control" value="<?php echo $this->setting['newer_invitation_num']; ?>"/>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-3 col-xs-3 control-label"><?php _e('新用户注册系统发送的欢迎内容'); ?>:</span>
                            <div class="col-sm-7 col-xs-8">
                                <textarea class="form-control textarea" rows="4" name="welcome_message_pm"  ><?php echo $this->setting['welcome_message_pm']; ?></textarea>

                                <span class="help-block"><?php _e('新用户注册欢迎内容, 以下变量可作为内容替换: <br />{username}: 用户名<br />{time}: 发送时间<br />{sitename}: 网站名称'); ?></span>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group mod-width">
                            <span class="col-sm-2 col-xs-3 control-label"><?php _e('新用户默认邮件提醒设置'); ?>:</span>
                            <div class="col-sm-10 col-xs-8">
                                <input type="hidden" name="set_email_settings" value="1" />

                                <label>
                                    <input type="checkbox" name="new_user_email_setting[]" value="FOLLOW_ME"<?php if ($this->setting['new_user_email_setting']['FOLLOW_ME'] != 'N') { ?> checked="checked"<?php } ?> /> <?php _e('当有人关注我'); ?>
                                </label>
                                <label>
                                    <input type="checkbox" name="new_user_email_setting[]" value="QUESTION_INVITE"<?php if ($this->setting['new_user_email_setting']['QUESTION_INVITE'] != 'N') { ?> checked="checked"<?php } ?> /> <?php _e('有人邀请我回答问题'); ?>
                                </label>
                                <label>
                                    <input type="checkbox" name="new_user_email_setting[]" value="NEW_ANSWER"<?php if ($this->setting['new_user_email_setting']['NEW_ANSWER'] != 'N') { ?> checked="checked"<?php } ?> /> <?php _e('我关注的问题有了新回复'); ?>
                                </label>
                                <label>
                                    <input type="checkbox" name="new_user_email_setting[]" value="NEW_MESSAGE"<?php if ($this->setting['new_user_email_setting']['NEW_MESSAGE'] != 'N') { ?> checked="checked"<?php } ?> /> <?php _e('有人向我发送私信'); ?>
                                </label>
                                <label>
                                    <input type="checkbox" name="new_user_email_setting[]" value="QUESTION_MOD"<?php if ($this->setting['new_user_email_setting']['QUESTION_MOD'] != 'N') { ?> checked="checked"<?php } ?> /> <?php _e('我的问题被编辑'); ?>
                                </label>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group mod-width">
                            <span class="col-sm-2 col-xs-3 control-label"><?php _e('新用户默认通知设置'); ?>:</span>
                            <div class="col-sm-10 col-xs-8">
                                <input type="hidden" name="set_notification_settings" value="1" />

                                <?php foreach($this->notify_actions as $key => $val) { ?>
                                <?php if ($val['user_setting']) { ?>
                                <label><input name="new_user_notification_setting[<?php echo $key;?>]" type="checkbox" value="1"<?php if (!in_array($key, $this->notification_settings)) { ?> checked="checked"<?php } ?> /> <?php _e($val['desc']); ?></label>
                                <?php } ?>
                                <?php } ?>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <?php } else if ($_GET['category'] == 'functions') { ?>
        <div class="mod-head">
            <h3>
                <span class="pull-left"><?php _e('站点功能'); ?></span>

                <span class="pull-right"><a href="javascript:;" onclick="AWS.ajax_post($('#settings_form'));" class="btn btn-xs btn-primary mod-site-save"><?php _e('快速保存'); ?></a></span>
            </h3>
        </div>
        <div class="tab-content mod-content">
            <table class="table table-striped">
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('网站公告'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <textarea class="form-control textarea" name="site_announce"  ><?php echo htmlspecialchars($this->setting['site_announce']); ?></textarea>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('开启 Rewrite 伪静态'); ?>:</span>
                            <div class="col-sm-6 col-xs-8">
                                <div class="btn-group mod-btn">
                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="url_rewrite_enable" value="Y"<?php if ($this->setting['url_rewrite_enable'] == 'Y') { ?> checked="checked"<?php } ?> /> <?php _e('是'); ?>
                                    </label>

                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="url_rewrite_enable" value="N"<?php if ($this->setting['url_rewrite_enable'] != 'Y') { ?> checked="checked"<?php } ?> /> <?php _e('否'); ?>
                                    </label>

                                    <span class="help-block"><?php _e('Rewrite 开启方法请见 ReadMe 说明文件'); ?></span>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-2 col-xs-3 control-label"><?php _e('URL 链接显示样式'); ?>:</span>
                            <div class="col-sm-10 col-xs-12">
                                <ul class="mod-site-url">
                                    <li>
                                        <span class="pull-left"><a href="javascript:;" onclick='$("#request_route_custom").val("/account/login/===/login/\n/account/logout/===/logout/\n/account/setting/(:any)/===/setting/(:any)/")'>[ <?php _e('获取规则'); ?> ]</a></span>
                                        <p class="pull-left">
                                            <?php echo base_url(); ?>/question/123<br /><?php echo base_url(); ?>/topic/123<br /><?php echo base_url(); ?>/user/anwsion<br /><?php echo base_url(); ?>/category/123
                                        </p>
                                    </li>
                                    <li>
                                        <span class="pull-left"><a href="javascript:;" onclick='$("#request_route_custom").val("/question/(:any)===/q_(:any)\n/topic/(:any)===/t_(:any).html\n/user/(:any)===/p_(:any).html\n/account/login/===/login/\n/account/logout/===/logout/\n/account/setting/(:any)/===/setting/(:any)/");'>[ <?php _e('获取规则'); ?> ]</a></span>
                                        <p class="pull-left">
                                            <?php echo base_url(); ?>/q_123.html
                                            <br />
                                            <?php echo base_url(); ?>/t_话题.html
                                            <br />
                                            <?php echo base_url(); ?>/p_admin.html
                                            <br />
                                            <?php echo base_url(); ?>/c_123.html
                                        </p>
                                    </li>
                                    <li class="mod-site-rule">
                                        <span class="pull-left"><?php _e('自定义路由'); ?></span>
                                        <div class="pull-left col-sm-8">
                                            <textarea class="form-control textarea" name="request_route_custom" id="request_route_custom" rows="4" cols="63"><?php echo $this->setting['request_route_custom']; ?></textarea>
                                        </div>
                                        <div class="help-block">
                                            <?php _e('此模式只有开启 Rewrite 后有效, 请填写简略正则表达式, 每行一条规则, 中间使用 === 隔开, 左边为站点默认 URL 模式, 右边为替换后的 URL 模式, 链接以 / 开头, (:num) 代表数字, (:any) 代表任意字符<br />如替换问题规则：<br />/question/(:any)===/q_(:any)<br />(!) 警告: 使用此功能之前请确定你对替换有所把握, 错误的规则将导致站点不能运行'); ?>
                                        </div>

                                    </li>
                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php if (is_dir(ROOT_PATH . 'uc_client/')) { ?>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('UCenter 系统字符编码'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="ucenter_charset" class="form-control" type="text" value="<?php echo $this->setting['ucenter_charset']; ?>" />
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('开启 Ucenter 用户对接'); ?>:</span>
                            <div class="col-sm-6 col-xs-8">
                                <div class="btn-group mod-btn">
                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="ucenter_enabled" value="Y"<?php if ($this->setting['ucenter_enabled'] == 'Y') { ?> checked="checked"<?php } ?> /> <?php _e('是'); ?>
                                    </label>

                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="ucenter_enabled" value="N"<?php if ($this->setting['ucenter_enabled'] != 'Y') { ?> checked="checked"<?php } ?> /> <?php _e('否'); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('Ucenter 绝对路径'); ?></span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="ucenter_path" class="form-control" type="text" value="<?php echo $this->setting['ucenter_path']; ?>"/>

                                <span class="help-block"><?php _e('末尾不带 / 或 \\，留空无法同步头像'); ?></span>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php } ?>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('通知未读数刷新间隔时间'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="unread_flush_interval" class="form-control" type="text" value="<?php echo $this->setting['unread_flush_interval']; ?>"/>

                                <div class="help-block"><?php _e('单位'); ?>: <?php _e('秒'); ?></div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('问题自动锁定时间'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="auto_question_lock_day" class="form-control" type="text" value="<?php echo $this->setting['auto_question_lock_day']; ?>"/>

                                <div class="help-block"><?php _e('单位'); ?>: <?php _e('天'); ?>, <?php _e('问题超过设定天数没有动作,系统则会自动锁定该问题, 设置为 0 则关闭自动锁定'); ?></div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('网站统计代码'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <textarea class="form-control textarea" name="statistic_code" rows="9"><?php echo $this->setting['statistic_code']; ?></textarea>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('问题举报理由选项'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <textarea class="form-control textarea" name="report_reason" rows="5" ><?php echo get_setting('report_reason'); ?></textarea>

                                <div class="help-block"><?php _e('每行填写一个举报理由'); ?></div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('有新举报与认证申请私信提醒用户 ID'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="report_message_uid" class="form-control" type="text" value="<?php echo $this->setting['report_message_uid']; ?>"/>

                                <div class="help-block"><?php _e('留空则发送到 ID 为 1 的用户'); ?></div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('系统时间显示格式'); ?>:</span>
                            <div class="col-sm-6 col-xs-8">
                                <label><input type="radio" name="time_style" value="Y"<?php if ($this->setting['time_style'] == 'Y') { ?> checked="checked"<?php } ?> /> <?php _e('xx 分钟前形式'); ?></label>
                                <label class="col-md-offset-1"><input type="radio" name="time_style" value="N"<?php if ($this->setting['time_style'] != 'Y') { ?> checked="checked"<?php } ?> /> <?php _e('完整日期形式'); ?></label>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('管理员后台登录是否需要验证码'); ?>:</span>
                            <div class="col-sm-6 col-xs-8">
                                <div class="btn-group mod-btn">
                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="admin_login_seccode" value="Y"<?php if ($this->setting['admin_login_seccode'] == 'Y') { ?> checked="checked"<?php } ?> /> <?php _e('是'); ?>
                                    </label>

                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="admin_login_seccode" value="N"<?php if ($this->setting['admin_login_seccode'] != 'Y') { ?> checked="checked"<?php } ?> /> <?php _e('否'); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('发送诊断数据帮助改善产品'); ?>:</span>
                            <div class="col-sm-6 col-xs-8">
                                <div class="btn-group mod-btn">
                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="report_diagnostics" value="Y"<?php if ($this->setting['report_diagnostics'] == 'Y') { ?> checked="checked"<?php } ?> /> <?php _e('是'); ?>
                                    </label>

                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="report_diagnostics" value="N"<?php if ($this->setting['report_diagnostics'] != 'Y') { ?> checked="checked"<?php } ?> /> <?php _e('否'); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <?php } else if ($_GET['category'] == 'contents') { ?>
        <div class="mod-head">
            <h3>
                <span class="pull-left"><?php _e('内容设置'); ?></span>

                <span class="pull-right"><a href="javascript:;" onclick="AWS.ajax_post($('#settings_form'));" class="btn btn-xs btn-primary mod-site-save"><?php _e('快速保存'); ?></a></span>
            </h3>
        </div>
        <div class="tab-content mod-content">
            <table class="table table-striped">
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('发起问题模式'); ?>:</span>
                            <div class="col-sm-6 col-xs-8">
                                <label><input type="radio" name="quick_publish" value="Y"<?php if ($this->setting['quick_publish'] == 'Y') { ?> checked="checked"<?php } ?> /> <?php _e('快捷模式'); ?></label>
                                <label class="col-md-offset-1"><input type="radio" name="quick_publish" value="N"<?php if ($this->setting['quick_publish'] != 'Y') { ?> checked="checked"<?php } ?> /> <?php _e('高级模式'); ?></label>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('编辑器设置'); ?>:</span>
                            <div class="col-sm-6 col-xs-8">
                                <label><input type="radio" name="advanced_editor_enable" value="Y"<?php if ($this->setting['advanced_editor_enable'] == 'Y') { ?> checked="checked"<?php } ?> /> <?php _e('CKEditor 编辑器'); ?></label>
                                <label class="col-md-offset-1"><input type="radio" name="advanced_editor_enable" value="N"<?php if ($this->setting['advanced_editor_enable'] != 'Y') { ?> checked="checked"<?php } ?> /> <?php _e('纯文本编辑器'); ?></label>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('启用分类功能'); ?>:</span>
                            <div class="col-sm-6 col-xs-8">
                                <div class="btn-group mod-btn">
                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="category_enable" value="Y"<?php if ($this->setting['category_enable'] == 'Y') { ?> checked="checked"<?php } ?> /> <?php _e('是'); ?>
                                    </label>

                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="category_enable" value="N"<?php if ($this->setting['category_enable'] != 'Y') { ?> checked="checked"<?php } ?> /> <?php _e('否'); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('允许上传附件'); ?>:</span>
                            <div class="col-sm-6 col-xs-8">
                                <div class="btn-group mod-btn">
                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="upload_enable" value="Y"<?php if ($this->setting['upload_enable'] == 'Y') { ?> checked="checked"<?php } ?> /> <?php _e('是'); ?>
                                    </label>

                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="upload_enable" value="N"<?php if ($this->setting['upload_enable'] != 'Y') { ?> checked="checked"<?php } ?> /> <?php _e('否'); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('允许的附件文件类型'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="allowed_upload_types" class="form-control" type="text" value="<?php echo $this->setting['allowed_upload_types']; ?>"/>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('允许上传最大附件大小'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="upload_size_limit" class="form-control" type="text" value="<?php echo $this->setting['upload_size_limit']; ?>"/>

                                <div class="help-block"><?php _e('单位'); ?>: KB</div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('回复内容最小字符数限制'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="answer_length_lower" class="form-control" type="text" value="<?php echo $this->setting['answer_length_lower']; ?>"/>

                                <div class="help-block"><?php _e('填写 0 则不限制'); ?></div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('问题标题最大字符数限制'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="question_title_limit" class="form-control" type="text" value="<?php echo $this->setting['question_title_limit']; ?>"/>

                                <div class="help-block"><?php _e('填写 0 则不限制'); ?></div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('新问题强制要求添加话题'); ?>:</span>
                            <div class="col-sm-6 col-xs-8">
                                <div class="btn-group mod-btn">
                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="new_question_force_add_topic" value="Y"<?php if ($this->setting['new_question_force_add_topic'] == 'Y') { ?> checked="checked"<?php } ?> /> <?php _e('是'); ?>
                                    </label>

                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="new_question_force_add_topic" value="N"<?php if ($this->setting['new_question_force_add_topic'] != 'Y') { ?> checked="checked"<?php } ?> /> <?php _e('否'); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('问题话题数量限制'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="question_topics_limit" class="form-control" type="text" value="<?php echo $this->setting['question_topics_limit']; ?>"/>

                                <div class="help-block"><?php _e('填写 0 则不限制'); ?></div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('自动展开评论'); ?>:</span>
                            <div class="col-sm-6 col-xs-8">
                                <div class="btn-group mod-btn">
                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="unfold_question_comments" value="Y"<?php if ($this->setting['unfold_question_comments'] == 'Y') { ?> checked="checked"<?php } ?> /> <?php _e('是'); ?>
                                    </label>

                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="unfold_question_comments" value="N"<?php if ($this->setting['unfold_question_comments'] != 'Y') { ?> checked="checked"<?php } ?> /> <?php _e('否'); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('评论内容最大字符数限制'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="comment_limit" type="text" class="form-control" value="<?php echo $this->setting['comment_limit']; ?>"/>

                                <div class="help-block"><?php _e('填写 0 则不限制'); ?></div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('话题标题最大字符数限制'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="topic_title_limit" type="text" class="form-control" value="<?php echo $this->setting['topic_title_limit']; ?>"/>

                                <div class="help-block"><?php _e('填写 0 则不限制'); ?></div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('允许上传最大头像/话题图片大小'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="upload_avatar_size_limit" type="text" class="form-control" value="<?php echo $this->setting['upload_avatar_size_limit']; ?>"/>

                                <div class="help-block"><?php _e('单位'); ?>: KB</div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('回复编辑/删除有效时间'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="answer_edit_time" type="text" class="form-control" value="<?php echo $this->setting['answer_edit_time']; ?>"/>

                                <div class="help-block"><?php _e('单位'); ?>: <?php _e('分钟'); ?></div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('“没有帮助”数量达到多少个时折叠回复');?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="uninterested_fold" type="text" class="form-control" value="<?php echo $this->setting['uninterested_fold']; ?>"/>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('系统自动选出最佳回复时间'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="best_answer_day" type="text" class="form-control" value="<?php echo $this->setting['best_answer_day']; ?>"/>

                                <div class="help-block"><?php _e('单位'); ?>: 天, <?php _e('填写 0 则不启用'); ?></div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('参与自动选出最佳回复的问题最小回复数'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="best_answer_min_count" type="text" class="form-control" value="<?php echo $this->setting['best_answer_min_count']; ?>"/>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('参与自动选出最佳回复的最小赞同数'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="best_agree_min_count" type="text" class="form-control" value="<?php echo $this->setting['best_agree_min_count']; ?>"/>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('阅读器获取最近多少天的热门问题'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="reader_questions_last_days" type="text" class="form-control" value="<?php echo $this->setting['reader_questions_last_days']; ?>"/>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('阅读器热门问题赞同数需大于或等于'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="reader_questions_agree_count" type="text" class="form-control" value="<?php echo $this->setting['reader_questions_agree_count']; ?>"/>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('自动建立地区/职业/公司话题'); ?>:</span>
                            <div class="col-sm-6 col-xs-8">
                                <div class="btn-group mod-btn">
                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="auto_create_social_topics" value="Y"<?php if ($this->setting['auto_create_social_topics'] == 'Y') { ?> checked="checked"<?php } ?> /> <?php _e('是'); ?>
                                    </label>

                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="auto_create_social_topics" value="N"<?php if ($this->setting['auto_create_social_topics'] != 'Y') { ?> checked="checked"<?php } ?> /> <?php _e('否'); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('敏感词列表'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <textarea class="form-control textarea" rows="5" name="sensitive_words"><?php echo $this->setting['sensitive_words']; ?></textarea>
                                <span class="help-block"><?php _e('内容中如出现敏感词将进入审核，支持普通字符串和正则表达式，每行一个。正则表达式请以 <code>{</code> 开始、以 <code>}</code> 结束，且需符合 <a href="http://cn2.php.net/manual/zh/pcre.pattern.php" target="_blank">PCRE 模式</a>，如 <code>{/敏\s*感\s*词/i}</code>。'); ?></span>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('启用帮助中心'); ?>:</span>
                            <div class="col-sm-6 col-xs-8">
                                <div class="btn-group mod-btn">
                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="enable_help_center" value="Y"<?php if ($this->setting['enable_help_center'] == 'Y') { ?> checked="checked"<?php } ?> /> <?php _e('是'); ?>
                                    </label>

                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="enable_help_center" value="N"<?php if ($this->setting['enable_help_center'] != 'Y') { ?> checked="checked"<?php } ?> /> <?php _e('否'); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>

                <?php if (check_extension_package('ticket')) { ?>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('启用工单系统'); ?>:</span>
                            <div class="col-sm-6 col-xs-8">
                                <div class="btn-group mod-btn">
                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="ticket_enabled" value="Y"<?php if ($this->setting['ticket_enabled'] == 'Y') { ?> checked="checked"<?php } ?> /> <?php _e('是'); ?>
                                    </label>

                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="ticket_enabled" value="N"<?php if ($this->setting['ticket_enabled'] != 'Y') { ?> checked="checked"<?php } ?> /> <?php _e('否'); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php } ?>

                <?php if (check_extension_package('project')) { ?>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('启用活动系统'); ?>:</span>
                            <div class="col-sm-6 col-xs-8">
                                <div class="btn-group mod-btn">
                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="project_enabled" value="Y"<?php if ($this->setting['project_enabled'] == 'Y') { ?> checked="checked"<?php } ?> /> <?php _e('是'); ?>
                                    </label>

                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="project_enabled" value="N"<?php if ($this->setting['project_enabled'] != 'Y') { ?> checked="checked"<?php } ?> /> <?php _e('否'); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php } ?>
            </table>
        </div>

        <?php } else if ($_GET['category'] == 'integral') { ?>
        <div class="mod-head">
            <h3>
                <span class="pull-left"><?php _e('积分与威望'); ?></span>

                <span class="pull-right"><a href="javascript:;" onclick="AWS.ajax_post($('#settings_form'));" class="btn btn-xs btn-primary mod-site-save"><?php _e('快速保存'); ?></a></span>
            </h3>
        </div>
        <div class="tab-content mod-content">
            <table class="table table-striped">
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('使用积分系统'); ?>:</span>
                            <div class="col-sm-6 col-xs-8">
                                <div class="btn-group mod-btn">
                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="integral_system_enabled" value="Y"<?php if ($this->setting['integral_system_enabled'] == 'Y') { ?> checked="checked"<?php } ?> /> <?php _e('是'); ?>
                                    </label>

                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="integral_system_enabled" value="N"<?php if ($this->setting['integral_system_enabled'] != 'Y') { ?> checked="checked"<?php } ?> /> <?php _e('否'); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('积分单位'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="integral_unit" type="text" class="form-control" value="<?php echo $this->setting['integral_unit']; ?>"/>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('新用户注册默认拥有积分'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="integral_system_config_register" type="text" class="form-control" value="<?php echo $this->setting['integral_system_config_register']; ?>"/>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('用户完善资料获得积分（包括头像，一句话介绍，履历等资料）'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="integral_system_config_profile" type="text" class="form-control" value="<?php echo $this->setting['integral_system_config_profile']; ?>"/>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('用户邀请他人注册且被邀请人成功注册'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="integral_system_config_invite" type="text" class="form-control" value="<?php echo $this->setting['integral_system_config_invite']; ?>"/>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('发起问题'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="integral_system_config_new_question" type="text" class="form-control" value="<?php echo $this->setting['integral_system_config_new_question']; ?>"/>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('回复问题'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="integral_system_config_new_answer" type="text" class="form-control" value="<?php echo $this->setting['integral_system_config_new_answer']; ?>"/>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('问题被回复时增加发起者积分'); ?>:</span>
                            <div class="col-sm-6 col-xs-8">
                                <div class="btn-group mod-btn">
                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="integral_system_config_answer_change_source" value="Y"<?php if ($this->setting['integral_system_config_answer_change_source'] == 'Y') { ?> checked="checked"<?php } ?> /> <?php _e('是'); ?>
                                    </label>

                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="integral_system_config_answer_change_source" value="N"<?php if ($this->setting['integral_system_config_answer_change_source'] != 'Y') { ?> checked="checked"<?php } ?> /> <?php _e('否'); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('回复被评为最佳回复'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="integral_system_config_best_answer" type="text" class="form-control" value="<?php echo $this->setting['integral_system_config_best_answer']; ?>"/>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('感谢回复'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="integral_system_config_thanks" type="text" class="form-control" value="<?php echo $this->setting['integral_system_config_thanks']; ?>"/>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('回复被折叠'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="integral_system_config_answer_fold" type="text" class="form-control" value="<?php echo $this->setting['integral_system_config_answer_fold']; ?>"/>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('发起者邀请用户回答问题且收到答案'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="integral_system_config_invite_answer" type="text" class="form-control" value="<?php echo $this->setting['integral_system_config_invite_answer']; ?>"/>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('发起者赞同反对威望系数'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="publisher_reputation_factor" type="text" class="form-control" value="<?php echo get_setting('publisher_reputation_factor'); ?>"/>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('最佳回复威望系数'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="best_answer_reput" type="text" class="form-control" value="<?php echo get_setting('best_answer_reput'); ?>"/>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('对数底值'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="reputation_log_factor" type="text" class="form-control" value="<?php echo get_setting('reputation_log_factor'); ?>"/>

                                <div class="help-block"><?php _e('控制显示的威望值大小，底数越小威望值越大，底数越大威望值越小，系统默认数值是 3'); ?><br /><?php _e('威望公式：log((((用户组威望系数 x 赞同数 - 用户组威望系数 x 反对数) + 发起者赞同反对威望系数 + 最佳回复威望系数) + 0.5), 对数底值)'); ?></div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <?php } else if ($_GET['category'] == 'permissions') { ?>
        <div class="mod-head">
            <h3>
                <span class="pull-left"><?php _e('用户权限'); ?></span>

                <span class="pull-right"><a href="javascript:;" onclick="AWS.ajax_post($('#settings_form'));" class="btn btn-xs btn-primary mod-site-save"><?php _e('快速保存'); ?></a></span>
            </h3>
        </div>
        <div class="tab-content mod-content">
            <table class="table table-striped">
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('用户对每个问题的回复限制'); ?>:</span>
                            <div class="col-sm-6 col-xs-8">
                                <label><input type="radio" name="answer_unique" value="Y"<?php if ($this->setting['answer_unique'] == 'Y') { ?> checked="checked"<?php } ?> /><?php _e('只允许回复一次'); ?></label>
                                <label class="col-md-offset-1"><input type="radio" name="answer_unique" value="N"<?php if ($this->setting['answer_unique'] != 'Y') { ?> checked="checked"<?php } ?> /><?php _e('不限制'); ?></label>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('允许用户回复自己发起的问题'); ?>:</span>
                            <div class="col-sm-6 col-xs-8">
                                <div class="btn-group mod-btn">
                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="answer_self_question" value="Y"<?php if ($this->setting['answer_self_question'] == 'Y') { ?> checked="checked"<?php } ?> /> <?php _e('是'); ?>
                                    </label>

                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="answer_self_question" value="N"<?php if ($this->setting['answer_self_question'] != 'Y') { ?> checked="checked"<?php } ?> /> <?php _e('否'); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('允许匿名发起或回复'); ?>:</span>
                            <div class="col-sm-6 col-xs-8">
                                <div class="btn-group mod-btn">
                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="anonymous_enable" value="Y"<?php if ($this->setting['anonymous_enable'] == 'Y') { ?> checked="checked"<?php } ?> /> <?php _e('是'); ?>
                                    </label>

                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="anonymous_enable" value="N"<?php if ($this->setting['anonymous_enable'] != 'Y') { ?> checked="checked"<?php } ?> /> <?php _e('否'); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <?php } else if ($_GET['category'] == 'mail') { ?>
        <div class="mod-head">
            <h3>
                <span class="pull-left"><?php _e('邮件设置'); ?></span>

                <span class="pull-right"><a href="javascript:;" onclick="AWS.ajax_post($('#settings_form'));" class="btn btn-xs btn-primary mod-site-save"><?php _e('快速保存'); ?></a></span>
            </h3>
        </div>
        <div class="tab-content mod-content">
            <table class="table table-striped">
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('系统邮件发送方式'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <select class="form-control" name="mail_config[transport]">
                                    <option value="sendmail"<?php if ($this->setting['mail_config']['transport'] == 'sendmail') { ?> selected="selected"<?php } ?>><?php _e('通过 PHP 的 Sendmail 发送'); ?></option>
                                    <option value="smtp"<?php if ($this->setting['mail_config']['transport'] == 'smtp') { ?> selected="selected"<?php } ?>><?php _e('通过 SOCKET 连接 SMTP 服务器发送'); ?></option>
                                </select>

                                <div class="help-block"><?php _e('若选择 SendMail 方式, 下面的 SMTP 选项无效'); ?></div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('邮件编码'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="mail_config[charset]" type="text" class="form-control" value="<?php echo $this->setting['mail_config']['charset']; ?>" />

                                <div class="help-block"><?php _e('非 UTF-8 编码会被自动转码'); ?></div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('主 SMTP 服务器'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="mail_config[server]" type="text" class="form-control" value="<?php echo $this->setting['mail_config']['server']; ?>" />
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('使用安全链接(SSL)连接主服务器'); ?>:</span>
                            <div class="col-sm-6 col-xs-8">
                                <div class="btn-group mod-btn">
                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="mail_config[ssl]" value="1"<?php if ($this->setting['mail_config']['ssl'] == 1) { ?> checked="checked"<?php } ?> /> <?php _e('是'); ?>
                                    </label>

                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="mail_config[ssl]" value="0"<?php if ($this->setting['mail_config']['ssl'] == 0) { ?> checked="checked"<?php } ?> /> <?php _e('否'); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('主 SMTP 端口'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="mail_config[port]" type="text" class="form-control" value="<?php echo $this->setting['mail_config']['port']; ?>" />

                                <div class="help-block"><?php _e('留空时默认服务器端口为 25，使用 SSL 协议默认端口为 465，详细参数请询问邮箱服务商'); ?></div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('主 SMTP 帐户'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="mail_config[username]" type="text" class="form-control" value="<?php echo $this->setting['mail_config']['username']; ?>"/>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('主 SMTP 密码'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="mail_config[password]" type="password" class="form-control" value="<?php echo $this->setting['mail_config']['password']; ?>"/>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php if (!defined('IN_SAE')) { ?>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('次 SMTP 服务器'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="slave_mail_config[server]" type="text" class="form-control" value="<?php echo $this->setting['slave_mail_config']['server']; ?>" />
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('使用安全链接(SSL)连接次服务器'); ?>:</span>
                            <div class="col-sm-6 col-xs-8">
                                <div class="btn-group mod-btn">
                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="slave_mail_config[ssl]" value="1"<?php if ($this->setting['slave_mail_config']['ssl'] == 1) { ?> checked="checked"<?php } ?> /> <?php _e('是'); ?>
                                    </label>

                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="slave_mail_config[ssl]" value="0"<?php if ($this->setting['slave_mail_config']['ssl'] == 0) { ?> checked="checked"<?php } ?> /> <?php _e('否'); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('次 SMTP 端口'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="slave_mail_config[port]" type="text" class="form-control" value="<?php echo $this->setting['slave_mail_config']['port']; ?>" />

                                <div class="help-block"><?php _e('留空时默认服务器端口为 25，使用 SSL 协议默认端口为 465，详细参数请询问邮箱服务商'); ?></div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('次 SMTP 帐户'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="slave_mail_config[username]" type="text" class="form-control" value="<?php echo $this->setting['slave_mail_config']['username']; ?>"/>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('次 SMTP 密码'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="slave_mail_config[password]" type="password" class="form-control" value="<?php echo $this->setting['slave_mail_config']['password']; ?>"/>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php } ?>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('系统邮件来源邮箱地址'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="from_email" type="text" class="form-control" value="<?php echo $this->setting['from_email']; ?>"/>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <?php } else if ($_GET['category'] == 'openid') { ?>
        <div class="mod-head">
            <h3>
                <span class="pull-left"><?php _e('开放平台'); ?></span>

                <span class="pull-right"><a href="javascript:;" onclick="AWS.ajax_post($('#settings_form'));" class="btn btn-xs btn-primary mod-site-save"><?php _e('快速保存'); ?></a></span>
            </h3>
        </div>
        <div class="tab-content mod-content">
            <table class="table table-striped">
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('开启 QQ 登录功能'); ?>:</span>
                            <div class="col-sm-6 col-xs-8">
                                <div class="btn-group mod-btn">
                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="qq_login_enabled" value="Y"<?php if ($this->setting['qq_login_enabled'] == 'Y') { ?> checked="checked"<?php } ?>/> <?php _e('是'); ?>
                                    </label>

                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="qq_login_enabled" value="N"<?php if ($this->setting['qq_login_enabled'] != 'Y') { ?> checked="checked"<?php } ?>/> <?php _e('否'); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('QQ 登录 AppID'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="qq_login_app_id" type="text" class="form-control" value="<?php echo $this->setting['qq_login_app_id']; ?>"/>

                                <span class="help-block"><?php _e('AppID 与 APPKey 需要到 <a href="http://connect.qq.com/" target="_blank">QQ 互联开放平台</a> 申请 (注意: 请申请网站不要申请应用)'); ?></span>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('QQ 登录 AppKey'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="qq_login_app_key" type="text" class="form-control" value="<?php echo $this->setting['qq_login_app_key']; ?>"/>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('开启微博登录功能'); ?>:</span>
                            <div class="col-sm-6 col-xs-8">
                                <div class="btn-group mod-btn">
                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="sina_weibo_enabled" value="Y"<?php if ($this->setting['sina_weibo_enabled'] == 'Y') { ?> checked="checked"<?php } ?>/> <?php _e('是'); ?>
                                    </label>

                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="sina_weibo_enabled" value="N"<?php if ($this->setting['sina_weibo_enabled'] != 'Y') { ?> checked="checked"<?php } ?>/> <?php _e('否'); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label textwrap"><?php _e('微博 App Key'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="sina_akey" type="text" class="form-control" value="<?php echo $this->setting['sina_akey']; ?>"/>

                                <span class="help-block"><?php _e('AppKey 需要到 <a href="http://open.weibo.com" target="_blank">微博开放平台</a> 申请 (注意: 请申请网站不要申请应用)'); ?></span>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label textwrap"><?php _e('微博 App Secret'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="sina_skey" type="text" class="form-control" value="<?php echo $this->setting['sina_skey']; ?>"/>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('微信公众平台接口 Token'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="weixin_mp_token" type="text" class="form-control" value="<?php echo $this->setting['weixin_mp_token']; ?>"/>

                                <span class="help-block"><?php _e('请与微信公众平台后台设置一致，URL 为 <code>%s</code>', get_js_url('/weixin/api/')); ?></span>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('微信公众平台接口 EncodingAESKey'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input type="text" class="form-control" name="weixin_encoding_aes_key" value="<?php echo $this->setting['weixin_encoding_aes_key']; ?>">
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('微信公众平台帐号角色'); ?>:</span>
                            <div class="col-sm-8 col-xs-8">
                                <label class="checkbox-inline"><input type="radio" name="weixin_account_role" value="base"<?php if (!$this->setting['weixin_account_role'] OR $this->setting['weixin_account_role'] == 'base') { ?> checked="checked"<?php } ?> /> <?php _e('普通订阅号'); ?></label>
                            <label class="checkbox-inline"><input type="radio" name="weixin_account_role" value="subscription"<?php if ($this->setting['weixin_account_role'] == 'subscription') { ?> checked="checked"<?php } ?> /> <?php _e('微信认证订阅号'); ?></label>
                            <label class="checkbox-inline"><input type="radio" name="weixin_account_role" value="general"<?php if ($this->setting['weixin_account_role'] == 'general') { ?> checked="checked"<?php } ?> /> <?php _e('普通服务号'); ?></label>
                            <label class="checkbox-inline"><input type="radio" name="weixin_account_role" value="service"<?php if ($this->setting['weixin_account_role'] == 'service') { ?> checked="checked"<?php } ?> /> <?php _e('微信认证服务号'); ?></label>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('微信公众平台 AppID'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="weixin_app_id" type="text" class="form-control" value="<?php echo $this->setting['weixin_app_id']; ?>"/>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('微信公众平台 AppSecret'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="weixin_app_secret" type="text" class="form-control" value="<?php echo $this->setting['weixin_app_secret']; ?>"/>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('开启 Google 登录功能'); ?>:</span>
                            <div class="col-sm-6 col-xs-8">
                                <div class="btn-group mod-btn">
                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="google_login_enabled" value="Y"<?php if ($this->setting['google_login_enabled'] == 'Y') { ?> checked="checked"<?php } ?>/> <?php _e('是'); ?>
                                    </label>

                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="google_login_enabled" value="N"<?php if ($this->setting['google_login_enabled'] != 'Y') { ?> checked="checked"<?php } ?>/> <?php _e('否'); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label textwrap">Google Client ID:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="google_client_id" type="text" class="form-control" value="<?php echo $this->setting['google_client_id']; ?>"/>

                                <span class="help-block"><?php _e('请到 <a href="https://console.developers.google.com" target="_blank">Google Developers Console</a> 申请。Redirect URI 必填，值为 <code>%s</code>', get_js_url('/account/openid/google/bind/')); ?></span>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label textwrap">Google Client Secret:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="google_client_secret" type="text" class="form-control" value="<?php echo $this->setting['google_client_secret']; ?>"/>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('开启 Facebook 登录功能'); ?>:</span>
                            <div class="col-sm-6 col-xs-8">
                                <div class="btn-group mod-btn">
                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="facebook_login_enabled" value="Y"<?php if ($this->setting['facebook_login_enabled'] == 'Y') { ?> checked="checked"<?php } ?>/> <?php _e('是'); ?>
                                    </label>

                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="facebook_login_enabled" value="N"<?php if ($this->setting['facebook_login_enabled'] != 'Y') { ?> checked="checked"<?php } ?>/> <?php _e('否'); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label textwrap">Facebook APP ID:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="facebook_app_id" type="text" class="form-control" value="<?php echo $this->setting['facebook_app_id']; ?>"/>

                                <span class="help-block"><?php _e('请到 <a href="https://developers.facebook.com" target="_blank">Facebook Developers</a> 申请'); ?></span>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label textwrap">Facebook APP Secret:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="facebook_app_secret" type="text" class="form-control" value="<?php echo $this->setting['facebook_app_secret']; ?>"/>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('开启 Twitter 登录功能'); ?>:</span>
                            <div class="col-sm-6 col-xs-8">
                                <div class="btn-group mod-btn">
                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="twitter_login_enabled" value="Y"<?php if ($this->setting['twitter_login_enabled'] == 'Y') { ?> checked="checked"<?php } ?>/> <?php _e('是'); ?>
                                    </label>

                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" name="twitter_login_enabled" value="N"<?php if ($this->setting['twitter_login_enabled'] != 'Y') { ?> checked="checked"<?php } ?>/> <?php _e('否'); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label textwrap">Twitter Consumer Key:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="twitter_consumer_key" type="text" class="form-control" value="<?php echo $this->setting['twitter_consumer_key']; ?>"/>

                                <span class="help-block"><?php _e('请到 <a href="https://apps.twitter.com" target="_blank">Twitter Application Management</a> 申请。Callback URL 必须设置，值可为任意 URL。'); ?></span>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label textwrap">Twitter Consumer Secret:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="twitter_consumer_secret" type="text" class="form-control" value="<?php echo $this->setting['twitter_consumer_secret']; ?>"/>
                            </div>
                        </div>
                    </td>
                </tr>

                <?php if (check_extension_package('payment')) { ?>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label textwrap"><?php _e('支付宝账号'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="alipay_seller_email" type="text" class="form-control" value="<?php echo $this->setting['alipay_seller_email']; ?>"/>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label textwrap"><?php _e('支付宝合作身份者 ID'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="alipay_partner" type="text" class="form-control" value="<?php echo $this->setting['alipay_partner']; ?>"/>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label textwrap"><?php _e('支付宝安全检验码'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="alipay_key" type="text" class="form-control" value="<?php echo $this->setting['alipay_key']; ?>"/>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('支付宝签名方式'); ?>:</span>
                            <div class="col-sm-8 col-xs-8">
                                <label class="checkbox-inline"><input type="radio" name="alipay_sign_type" value="MD5"<?php if (!$this->setting['alipay_sign_type'] OR $this->setting['alipay_sign_type'] == 'MD5') { ?> checked="checked"<?php } ?> /> MD5</label>
                                <label class="checkbox-inline"><input type="radio" name="alipay_sign_type" value="RSA"<?php if ($this->setting['alipay_sign_type'] == 'RSA') { ?> checked="checked"<?php } ?> /> RSA</label>
                                <label class="checkbox-inline"><input type="radio" name="alipay_sign_type" value="0001"<?php if ($this->setting['alipay_sign_type'] == '0001') { ?> checked="checked"<?php } ?> /> 0001</label>

                                <span class="help-block"><?php _e('如不确定请勿更改'); ?></span>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('支付宝字符编码格式'); ?>:</span>
                            <div class="col-sm-8 col-xs-8">
                                <label class="checkbox-inline"><input type="radio" name="alipay_input_charset" value="utf-8"<?php if (!$this->setting['alipay_input_charset'] OR $this->setting['alipay_input_charset'] == 'utf-8') { ?> checked="checked"<?php } ?> /> UTF-8</label>
                                <label class="checkbox-inline"><input type="radio" name="alipay_input_charset" value="gbk"<?php if ($this->setting['alipay_input_charset'] == 'gbk') { ?> checked="checked"<?php } ?> /> GBK</label>

                                <span class="help-block"><?php _e('不建议更改'); ?></span>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('支付宝访问模式'); ?>:</span>
                            <div class="col-sm-8 col-xs-8">
                                <label class="checkbox-inline"><input type="radio" name="alipay_transport" value="https"<?php if (!$this->setting['alipay_transport'] OR $this->setting['alipay_transport'] == 'https') { ?> checked="checked"<?php } ?> /> HTTPS</label>
                                <label class="checkbox-inline"><input type="radio" name="alipay_transport" value="http"<?php if ($this->setting['alipay_transport'] == 'http') { ?> checked="checked"<?php } ?> /> HTTP</label>

                                <span class="help-block"><?php _e('如服务器不支持 SSL 请改为 HTTP'); ?></span>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('支付宝商户私钥'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <textarea class="form-control textarea" rows="6" name="alipay_private_key"><?php echo $this->setting['alipay_private_key']; ?></textarea>

                                <span class="help-block"><?php _e('支付宝签名方式为 RSA 或 0001 时必须设置'); ?></span>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('支付宝公钥'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <textarea class="form-control textarea" rows="6" name="alipay_ali_public_key"><?php echo $this->setting['alipay_ali_public_key']; ?></textarea>

                                <span class="help-block"><?php _e('支付宝签名方式为 RSA 或 0001 时必须设置'); ?></span>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php } ?>
            </table>
        </div>
        <?php } else if ($_GET['category'] == 'cache') { ?>
        <div class="mod-head">
            <h3>
                <span class="pull-left"><?php _e('性能优化'); ?></span>

                <span class="pull-right"><a href="javascript:;" onclick="AWS.ajax_post($('#settings_form'));" class="btn btn-xs btn-primary mod-site-save"><?php _e('快速保存'); ?></a></span>
            </h3>
        </div>
        <div class="tab-content mod-content">
            <table class="table table-striped">
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('高级别缓存时间'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="cache_level_high" type="text" class="form-control" value="<?php echo $this->setting['cache_level_high']; ?>"/>

                                <span class="help-block"><?php _e('单位:秒'); ?></span>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('普通级别缓存时间'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="cache_level_normal" type="text" class="form-control" value="<?php echo $this->setting['cache_level_normal']; ?>"/>

                                <span class="help-block"><?php _e('单位:秒'); ?></span>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('低级别缓存时间'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="cache_level_low" type="text" class="form-control" value="<?php echo $this->setting['cache_level_low']; ?>"/>

                                <span class="help-block"><?php _e('单位:秒'); ?></span>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <?php } else if ($_GET['category'] == 'interface') { ?>
        <div class="mod-head">
            <h3>
                <span class="pull-left"><?php _e('界面设置'); ?></span>

                <span class="pull-right"><a href="javascript:;" onclick="AWS.ajax_post($('#settings_form'));" class="btn btn-xs btn-primary mod-site-save"><?php _e('快速保存'); ?></a></span>
            </h3>
        </div>
        <div class="tab-content mod-content">
            <table class="table table-striped">
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('用户界面风格'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <select class="form-control" name="ui_style">
                                <?php foreach($this->styles as $skey => $val) { ?>
                                    <option value="<?php echo $val['id']; ?>"<?php if ($this->setting['ui_style'] == $val['id']) { ?> selected="selected"<?php } ?>><?php echo $val['title']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('最新动态显示条数'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="index_per_page" class="form-control" type="text" value="<?php echo $this->setting['index_per_page']; ?>"/>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('通知显示条数'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="notifications_per_page" class="form-control" type="text" value="<?php echo $this->setting['notifications_per_page']; ?>"/>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('内容列表页显示条数'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="contents_per_page" class="form-control" type="text" value="<?php echo $this->setting['contents_per_page']; ?>"/>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('首页推荐用户和话题数量'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input name="recommend_users_number" class="form-control" type="text" value="<?php echo $this->setting['recommend_users_number']; ?>"/>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <?php } ?>
        <div class="tab-content mod-content mod-one-btn">
            <div class="center-block">
                <input type="button" value="<?php _e('保存设置'); ?>" class="btn btn-primary" onclick="AWS.ajax_post($('#settings_form'));" />
            </div>
        </div>
    </div>
    </form>
</div>

<?php View::output('admin/global/footer.php'); ?>