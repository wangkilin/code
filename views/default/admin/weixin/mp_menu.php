<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
            <h3>
                <span class="pull-left"><?php _e('菜单管理'); ?></span>
            </h3>

            <?php if (count($this->accounts_list) > 1) { ?>
            <div class="dropdown pull-right weixin_dropdown">
                <a class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
                    <?php if ($this->account_id == 0) { _e('主账号'); } else { _e('子账号 %s', $this->account_id); } ?>
                    <span class="caret"></span>
                </a>

                <ul class="dropdown-menu weixin-dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                    <?php foreach ($this->accounts_list AS $account_info) {
                        if ($account_info['id'] == $this->account_id OR $account_info['weixin_account_role'] == 'base') { continue; } ?>
                    <li role="presentation">
                        <a role="menuitem" tabindex="-1" href="admin/weixin/mp_menu/id-<?php echo $account_info['id']; ?>"><?php if ($account_info['id'] == 0) { _e('主账号'); } else { _e('子账号 %s', $account_info['id']); } ?></a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
            <?php } ?>
        </div>

        <div class="tab-content mod-body">
            <div class="alert alert-success collapse error_message"></div>

            <!-- 最新问题 -->
            <script type="text/x-template" id="drop_menu_template_new_posts">
                <div class="dropdown-menu pull-right<?php if ($this->category_data) { ?> subtract-two<?php } ?>">
                    <div class="mod-head clearfix">
                        <?php if ($this->category_data) { ?>
                        <div class="mod">
                            <div class="mod-head"><?php _e('分类最新内容'); ?></div>
                            <div class="divider"></div>
                            <div class="mod-footer">
                                <ul class="control-height">

                                    <?php foreach ($this->category_data AS $val) { ?>
                                    <li data-value="COMMAND_NEW_POSTS__CATEGORY_<?php echo $val['id']; ?>"><a><?php echo $val['title']; ?></a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                        <?php } ?>

                        <div class="mod">
                            <div class="mod-head"><?php _e('专题最新内容'); ?></div>
                            <div class="divider"></div>
                            <div class="mod-footer">
                                <ul class="control-height">
                                    <?php if ($this->feature_list) { ?>
                                    <?php foreach ($this->feature_list AS $val) { ?>
                                    <li data-value="COMMAND_NEW_POSTS__FEATURE_<?php echo $val['id']; ?>"><a><?php echo $val['title']; ?></a></li>
                                    <?php } ?>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="mod-footer clearfix">
                        <ul>
                            <li data-value="COMMAND_NEW_POSTS"><a><?php _e('全部最新内容'); ?></a></li>
                        </ul>
                    </div>

                </div>
            </script>
            <!-- end 最新问题 -->

            <!-- 最新问题 -->
            <script type="text/x-template" id="drop_menu_template_new_question">
                <div class="dropdown-menu pull-right<?php if ($this->category_data) { ?> subtract-two<?php } ?>">
                    <div class="mod-head clearfix">
                        <?php if ($this->category_data) { ?>
                        <div class="mod">
                            <div class="mod-head"><?php _e('分类最新问题'); ?></div>
                            <div class="divider"></div>
                            <div class="mod-footer">
                                <ul class="control-height">
                                    <?php foreach ($this->category_data AS $val) { ?>
                                    <li data-value="COMMAND_NEW_QUESTION__CATEGORY_<?php echo $val['id']; ?>"><a><?php echo $val['title']; ?></a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                        <?php } ?>

                        <div class="mod">
                            <div class="mod-head"><?php _e('专题最新问题'); ?></div>
                            <div class="divider"></div>
                            <div class="mod-footer">
                                <ul class="control-height">
                                    <?php if ($this->feature_list) { ?>
                                    <?php foreach ($this->feature_list AS $val) { ?>
                                    <li data-value="COMMAND_NEW_QUESTION__FEATURE_<?php echo $val['id']; ?>"><a><?php echo $val['title']; ?></a></li>
                                    <?php } ?>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="mod-footer clearfix">
                        <ul>
                            <li data-value="COMMAND_NEW_QUESTION"><a><?php _e('全部最新问题'); ?></a></li>
                        </ul>
                    </div>
                </div>
            </script>
            <!-- end 最新问题 -->

            <!-- 文章专题 -->
            <script type="text/x-template" id="drop_menu_template_new_article">
                <div class="dropdown-menu pull-right">
                    <div class="mod-head clearfix">
                        <div class="mod">
                            <div class="mod-head"><?php _e('文章专题'); ?></div>
                            <div class="divider"></div>
                            <div class="mod-footer">
                                <ul class="control-height">
                                    <li data-value="COMMAND_NEW_ARTICLE"><a><?php _e('全部文章'); ?></a></li>
                                    <?php if ($this->feature_list) { ?>
                                    <?php foreach ($this->feature_list AS $val) { ?>
                                    <li data-value="COMMAND_NEW_ARTICLE__FEATURE_<?php echo $val['id']; ?>"><a><?php echo $val['title']; ?></a></li>
                                    <?php } ?>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </script>
            <!-- end 文章专题 -->

            <!-- 热门问题 -->
            <script type="text/x-template" id="drop_menu_template_hot_question">
                <div class="dropdown-menu pull-right<?php if ($this->category_data) { ?> subtract-two<?php } ?>">
                    <div class="mod-head clearfix">
                        <?php if ($this->category_data) { ?>
                        <div class="mod">
                            <div class="mod-head"><?php _e('分类热门问题'); ?></div>
                            <div class="divider"></div>
                            <div class="mod-footer">
                                <ul class="control-height">
                                    <?php foreach ($this->category_data AS $val) { ?>
                                    <li data-value="COMMAND_HOT_QUESTION__CATEGORY_<?php echo $val['id']; ?>"><a><?php echo $val['title']; ?></a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                        <?php } ?>


                        <div class="mod">
                            <div class="mod-head"><?php _e('专题热门问题'); ?></div>
                            <div class="divider"></div>
                            <div class="mod-footer">
                                <ul class="control-height">
                                    <?php if ($this->feature_list) { ?>
                                    <?php foreach ($this->feature_list AS $val) { ?>
                                    <li data-value="COMMAND_HOT_QUESTION__FEATURE_<?php echo $val['id']; ?>"><a><?php echo $val['title']; ?></a></li>
                                    <?php } ?>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="mod-footer clearfix">
                        <ul>
                            <li data-value="COMMAND_HOT_QUESTION"><a><?php _e('全部热门问题'); ?></a></li>
                        </ul>
                    </div>
                </div>
            </script>
            <!-- end热门问题 -->

            <!-- 推荐问题 -->
            <script type="text/x-template" id="drop_menu_template_suggest_question">
                <div class="dropdown-menu pull-right subtract-two">
                    <div class="mod-head clearfix">
                        <?php if ($this->category_data) { ?>
                        <div class="mod">
                            <div class="mod-head"><?php _e('分类推荐问题'); ?></div>
                            <div class="divider"></div>
                            <div class="mod-footer">
                                <ul class="control-height">
                                    <?php foreach ($this->category_data AS $val) { ?>
                                    <li data-value="COMMAND_RECOMMEND_QUESTION__CATEGORY_<?php echo $val['id']; ?>"><a><?php echo $val['title']; ?></a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                        <?php } ?>

                        <div class="mod">
                            <div class="mod-head"><?php _e('专题推荐问题'); ?></div>
                            <div class="divider"></div>
                            <div class="mod-footer">
                                <ul class="control-height">
                                    <?php if ($this->feature_list) { ?>
                                    <?php foreach ($this->feature_list AS $val) { ?>
                                    <li data-value="COMMAND_RECOMMEND_QUESTION__FEATURE_<?php echo $val['id']; ?>"><a><?php echo $val['title']; ?></a></li>
                                    <?php } ?>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="mod-footer">
                        <ul class="control-height">
                            <li data-value="COMMAND_RECOMMEND_QUESTION"><a><?php _e('全部推荐问题'); ?></a></li>
                        </ul>
                    </div>
                </div>
            </script>
            <!-- end 推荐问题 -->

            <!-- 等待回复 -->
            <script type="text/x-template" id="drop_menu_template_wait_replay">
                <div class="dropdown-menu pull-right<?php if ($this->category_data) { ?> subtract-two<?php } ?>">
                    <div class="mod-head clearfix">
                        <?php if ($this->category_data) { ?>
                        <div class="mod">
                            <div class="mod-head"><?php _e('分类待回复问题'); ?></div>
                            <div class="divider"></div>
                            <div class="mod-footer">
                                <ul class="control-height">
                                    <?php foreach ($this->category_data AS $val) { ?>
                                    <li data-value="COMMAND_NO_ANSWER_QUESTION__CATEGORY_<?php echo $val['id']; ?>"><a><?php echo $val['title']; ?></a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                        <?php } ?>

                        <div class="mod">
                            <div class="mod-head"><?php _e('专题待回复问题'); ?></div>
                            <div class="divider"></div>
                            <div class="mod-footer">
                                <ul class="control-height">
                                    <?php if ($this->feature_list) { ?>
                                    <?php foreach ($this->feature_list AS $val) { ?>
                                    <li data-value="COMMAND_NO_ANSWER_QUESTION__FEATURE_<?php echo $val['id']; ?>"><a><?php echo $val['title']; ?></a></li>
                                    <?php } ?>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="mod-footer clearfix">
                        <ul>
                            <li data-value="COMMAND_NO_ANSWER_QUESTION"><a><?php _e('全部待回复问题'); ?></a></li>
                        </ul>
                    </div>
                </div>
            </script>
            <!-- end 等待回复 -->

            <!-- 自定义回复 -->
            <script type="text/x-template" id="drop_menu_template_auto_replay">
                <div class="dropdown-menu pull-right">
                    <div class="mod-head clearfix">
                        <div class="mod">
                            <div class="mod-head"><?php _e('自定义回复'); ?></div>
                            <div class="divider"></div>
                            <div class="mod-footer">
                                <ul class="control-height">
                                    <?php if ($this->reply_rule_list) { ?>
                                    <?php foreach ($this->reply_rule_list AS $val) { ?>
                                    <li data-value="REPLY_RULE_<?php echo $val['id']; ?>" data-upload="false"><a><?php echo $val['keyword']; ?></a></li>
                                    <?php } ?>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </script>
            <!-- end 自定义回复 -->

            <?php if ($this->account_id == 0) { ?>
            <!-- 全局命令 -->
            <script type="text/x-template" id="drop_menu_template_global">
                <div class="dropdown-menu pull-right">
                    <div class="mod-head clearfix">
                        <div class="mod">
                            <div class="mod-head"><?php _e('全局命令'); ?></div>
                            <div class="divider"></div>
                            <div class="mod-footer">
                                <ul class="control-height">
                                    <?php if (get_setting('weixin_account_role') == 'service') { ?>
                                    <li data-value="COMMAND_HOME_ACTIONS" data-upload="false"><a><?php _e('最新动态'); ?></a></li>
                                    <li data-value="COMMAND_NOTIFICATIONS" data-upload="false"><a><?php _e('最新通知'); ?></a></li>

                                    <li data-value="COMMAND_MY_QUESTION" data-upload="false"><a><?php _e('我的提问'); ?></a></li>
                                    <?php } ?>
                                    <li data-value="COMMAND_MORE" data-upload="false"><a><?php _e('载入更多'); ?></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </script>
            <!-- end 全局命令 -->
            <?php } ?>

            <!-- 自定义url -->
            <script type="text/x-template" id="drop_menu_template_defined_url">
                <div class="dropdown-menu pull-right">
                    <div class="mod-head clearfix">
                        <div class="mod">
                            <div class="mod-head"><?php _e('常用功能'); ?></div>
                            <div class="divider"></div>
                            <div class="mod-footer">
                                <ul class="control-height">
                                    <?php if (get_setting('weixin_account_role') == 'service') { ?>
                                    <li data-value="<?php echo get_js_url('/m/nearby_user/'); ?>" data-upload="false"><a><?php _e('附近的人'); ?></a></li>
                                    <li data-value="<?php echo get_js_url('/m/nearby_question/'); ?>" data-upload="false"><a><?php _e('附近的问题'); ?></a></li>
                                    <?php } ?>
                                    <li data-value="<?php echo get_js_url('/m/register/'); ?>" data-upload="false"><a><?php _e('注册用户'); ?></a></li>
                                    <li data-value="<?php echo get_js_url('/m/explore/'); ?>" data-upload="false"><a><?php _e('发现'); ?></a></li>
                                    <li data-value="<?php echo get_js_url('/m/topic/'); ?>" data-upload="false"><a><?php _e('话题广场'); ?></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </script>
            <!-- end 自定义url -->

            <script type="text/x-template" id="select_option">
                <option></option>
                <option data-name="全局命令"><?php _e('全局命令'); ?></option>
                <option data-value="COMMAND_NEW_POSTS" data-name="最新问题"><?php _e('最新内容'); ?></option>
                <option data-value="COMMAND_NEW_QUESTION" data-name="最新问题"><?php _e('最新问题'); ?></option>
                <option data-value="COMMAND_NEW_ARTICLE" data-name="文章专题"><?php _e('文章专题'); ?></option>
                <option data-value="COMMAND_HOT_QUESTION" data-name="热门问题"><?php _e('热门问题'); ?></option>
                <option data-value="COMMAND_RECOMMEND_QUESTION" data-name="推荐问题"><?php _e('推荐问题'); ?></option>
                <option data-value="COMMAND_HOME_ACTIONS" data-name="等待回复"><?php _e('等待回复'); ?></option>
                <option data-name="自定义回复"><?php _e('自定义回复'); ?></option>
                <option data-name="自定义 URL"><?php _e('自定义 URL'); ?></option>
            </script>

            <script type="text/x-template" id="top_menu_template">
                <tr data-id="{MENU_ID}" class="top_menu" id="menu_id_{MENU_ID}">
                    <td><input class="form-control input-xlarge" type="text" value="" name="button[{MENU_ID}][name]" /></td>
                    <td align="center"><input type="text" value="" class="form-control sort-action" name="button[{MENU_ID}][sort]" /></td>
                    <td aligin="center">
                        <select class="icb-wechat-select form-control" name="button[{MENU_ID}][command_type]">
                        </select>
                    </td>
                    <td align="center">
                        <div class="icb-wechat-dropdown-box">
                            <span data-toggle="dropdown">&nbsp;</span>
                            <input type="button" value="" class="input-bg"  data-toggle="dropdown" />
                            <input class="form-control input-code collapse" readonly="readonly" type="text" name="button[{MENU_ID}][key]" placeholder="<?php _e('点击右侧按钮选择命令'); ?>" />
                            <a class="icon icon-down" data-toggle="dropdown"></a>

                            <input type="hidden" class="attach_key" name="button[{MENU_ID}][attach_key]" value="" />
                            <a class="ajax-upload"><i class="icon icon-image"></i></a>
                            <a class="ajax-look" onclick="if ($(this).parent().find('input.attach_key').val() != '') { AWS.dialog('imagePreview', {'title':'图片预览', 'image':'<?php echo get_setting('upload_url'); ?>/weixin/list_image/' + $(this).parent().find('input.attach_key').val() + '.jpg?'+ Math.random() }) } else { AWS.alert('<?php _e('当前菜单没有上传图片'); ?>'); }"><i class="icon icon-search disabled"></i></a>
                        </div>
                    </td>
                    <td align="center">
                        <a href="javascript:;" onclick="if ($('#mp_menu_table tbody tr.sub_button_{MENU_ID}').length > 4) { AWS.alert('<?php _e('最多只能创建 5 个子菜单'); ?>') } else { $('tr#menu_id_{MENU_ID}').after($('#sub_button_template').html().replace(/{MENU_TOP_ID}/g, '{MENU_ID}').replace(/{MENU_SUB_ID}/g, hex_md5(new Date().getTime() + '|' + Math.random()))); ajax_upload_reload();$('tr#menu_id_{MENU_ID}').next().find('.icb-wechat-select').append($('#select_option').html()); }"><?php _e('添加子菜单'); ?></a> |
                        <a href="javascript:;" onclick="$(this).parents('tr').remove(); $('.sub_button_{MENU_ID}').remove();"><?php _e('删除'); ?></a>
                    </td>
                </tr>
            </script>

            <script type="text/x-template" id="sub_button_template">
                <tr class="sub_button_{MENU_TOP_ID}" data-id="{MENU_SUB_ID}">
                    <td>
                        <span class="col-sm-1 chat-symbol">--</span>
                        <div class="col-sm-11">
                            <input class="form-control input-xlarge inline-block" type="text" value="" name="button[{MENU_TOP_ID}][sub_button][{MENU_SUB_ID}][name]" />
                        </div>
                    </td>
                    <td align="center"><input type="text" value="" class="form-control sort-action" name="button[{MENU_TOP_ID}][sub_button][{MENU_SUB_ID}][sort]" /></td>
                    <td aligin="center">
                        <select class="icb-wechat-select form-control" name="button[{MENU_TOP_ID}][sub_button][{MENU_SUB_ID}][command_type]">
                        </select>
                    </td>
                    <td align="center">
                        <div class="icb-wechat-dropdown-box">
                            <span data-toggle="dropdown">&nbsp;</span>
                            <input type="button" value="" class="input-bg"  data-toggle="dropdown" />
                            <input class="form-control input-code collapse" readonly="readonly" type="text" name="button[{MENU_TOP_ID}][sub_button][{MENU_SUB_ID}][key]" placeholder="点击右侧按钮选择命令" />
                            <a class="icon icon-down" data-toggle="dropdown"></a>

                            <input type="hidden" class="attach_key" name="button[{MENU_TOP_ID}][sub_button][{MENU_SUB_ID}][attach_key]" value="" />
                            <a class="ajax-upload collapse"><i class="icon icon-image"></i></a>
                            <a class="ajax-look collapse" onclick="if ($(this).parent().find('input.attach_key').val() != '') { AWS.dialog('imagePreview', {'title':'图片预览', 'image':'<?php echo get_setting('upload_url'); ?>/weixin/list_image/' + $(this).parent().find('input.attach_key').val() + '.jpg?'+ Math.random()}) } else { AWS.alert('<?php _e('当前菜单没有上传图片'); ?>'); }"><i class="icon icon-search disabled"></i></a>
                        </div>
                    </td>
                    <td align="center">
                        <a href="javascript:;" onclick="$(this).parents('tr').remove();"><?php _e('删除'); ?></a>
                    </td>
                </tr>
            </script>

            <form id="mp_menu_form" action="admin/weixin/save_mp_menu/" method="post">
                <input type="hidden" name="account_id" value="<?php echo $this->account_id; ?>" />

                <table class="table table-striped" id="mp_menu_table">
                    <thead>
                        <tr>
                            <th style="width:25%;"><?php _e('菜单标题'); ?></th>
                            <th align="center" style="width:10%"><?php _e('排序'); ?></th>
                            <th align="center" style="width:15%"><?php _e('菜单选择'); ?></th>
                            <th align="center" style="width:35%;"><?php _e('菜单命令'); ?></th>
                            <th align="center" style="width:15%;"><?php _e('操作'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($this->mp_menu) { foreach ($this->mp_menu AS $key => $val) { ?>
                            <tr data-id="<?php echo $key; ?>" class="top_menu" id="menu_id_<?php echo $key; ?>">
                                <td><input type="text" class="input-xlarge form-control" value="<?php echo $val['name']; ?>" name="button[<?php echo $key; ?>][name]" /></td>
                                <td align="center"><input type="text" value="<?php echo $val['sort']; ?>" class="form-control sort-action" name="button[<?php echo $key; ?>][sort]" /></td>
                                <td aligin="center">
                                    <select class="icb-wechat-select form-control" name="button[<?php echo $key?>][command_type]">
                                    </select>
                                </td>
                                <td align="center">
                                    <div class="icb-wechat-dropdown-box">
                                        <span data-toggle="dropdown">&nbsp;</span>
                                        <input type="button" value="" class="input-bg"  data-toggle="dropdown" />
                                        <input class="form-control input-code collapse" readonly type="text" name="button[<?php echo $key; ?>][key]" value="<?php echo $val['key']; ?>" placeholder="点击右侧按钮选择命令" />
                                        <a class="icon icon-down" data-toggle="dropdown"></a>
                                        <input type="hidden" class="attach_key" name="button[<?php echo $key; ?>][attach_key]" value="<?php echo $val['attach_key']; ?>" />
                                        <a class="ajax-upload"><i class="icon icon-image"></i></a>
                                        <a class="ajax-look" onclick="if ($(this).parent().find('input.attach_key').val() != '') { AWS.dialog('imagePreview', {'title':'图片预览', 'image':'<?php echo get_setting('upload_url'); ?>/weixin/list_image/' + $(this).parent().find('input.attach_key').val() + '.jpg?'+ Math.random()}) } else { AWS.alert('<?php _e('当前菜单没有上传图片'); ?>'); }"><i class="icon icon-search <?php if (!$sub_val['attach_key']) { ?>disabled<?php } ?>"></i></a>
                                    </div>

                                </td>
                                <td align="center">
                                    <a href="javascript:;" onclick="if ($('#mp_menu_table tbody tr.sub_button_<?php echo $key; ?>').length > 4) { AWS.alert('<?php _e('最多只能创建 5 个子菜单'); ?>') } else { $('tr#menu_id_<?php echo $key; ?>').after($('#sub_button_template').html().replace(/{MENU_TOP_ID}/g, '<?php echo $key; ?>').replace(/{MENU_SUB_ID}/g, hex_md5(new Date().getTime() + ' ' + Math.random()))); ajax_upload_reload();$('tr#menu_id_<?php echo $key; ?>').next().find('.icb-wechat-select').append($('#select_option').html()); }"><?php _e('添加子菜单'); ?></a> |
                                    <a href="javascript:;" onclick="$(this).parents('tr').remove(); $('.sub_button_<?php echo $key; ?>').remove();"><?php _e('删除'); ?></a>
                                </td>
                            </tr>

                            <?php if ($val['sub_button']) { foreach ($val['sub_button'] AS $sub_key => $sub_val) { ?>
                            <tr class="sub_button_<?php echo $key; ?>" data-id="<?php echo $sub_key; ?>">
                                    <td>
                                        <span class="col-sm-1 chat-symbol">--</span>
                                        <div class="col-sm-11">
                                            <input class="form-control input-xlarge inline-block" type="text" value="<?php echo $sub_val['name']; ?>" name="button[<?php echo $key; ?>][sub_button][<?php echo $sub_key; ?>][name]" />
                                        </div>
                                    </td>
                                    <td align="center"><input type="text" value="<?php echo $sub_val['sort']; ?>" class="form-control sort-action" name="button[<?php echo $key; ?>][sub_button][<?php echo $sub_key; ?>][sort]" /></td>
                                    <td align="center">
                                        <select class="icb-wechat-select form-control" name="button[<?php echo $key; ?>][sub_button][<?php echo $sub_key; ?>][command_type]">
                                        </select>
                                    </td>
                                    <td align="center">
                                        <div class="icb-wechat-dropdown-box">
                                            <span data-toggle="dropdown">&nbsp;</span>
                                            <input type="button" value="" class="input-bg"  data-toggle="dropdown" />
                                            <input class="form-control input-code collapse" readonly type="text" name="button[<?php echo $key; ?>][sub_button][<?php echo $sub_key; ?>][key]" value="<?php echo $sub_val['key']; ?>" placeholder="点击右侧按钮选择命令" />
                                            <a class="icon icon-down" data-toggle="dropdown"></a>

                                            <input type="hidden" class="attach_key" name="button[<?php echo $key; ?>][sub_button][<?php echo $sub_key; ?>][attach_key]" value="<?php echo $sub_val['attach_key']; ?>" />
                                            <a class="ajax-upload"><i class="icon icon-image"></i></a>
                                            <a class="ajax-look" onclick="if ($(this).parent().find('input.attach_key').val() != '') { AWS.dialog('imagePreview', {'title':'图片预览', 'image':'<?php echo get_setting('upload_url'); ?>/weixin/list_image/' + $(this).parent().find('input.attach_key').val() + '.jpg?'+ Math.random()}) } else { AWS.alert('<?php _e('当前菜单没有上传图片'); ?>'); }"><i class="icon icon-search <?php if (!$sub_val['attach_key']) { ?>disabled<?php } ?>"></i></a>
                                        </div>
                                    </td>
                                    <td align="center">
                                        <a href="javascript:;" onclick="$(this).parents('tr').remove();"><?php _e('删除'); ?></a>
                                    </td>
                            </tr>
                            <?php } } ?>
                        <?php } } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5">
                                <a class="btn btn-primary inline-block pull-right" href="javascript:;" onclick="AWS.ajax_post($('#mp_menu_form'), function(){$('#form_update_weixin_menu_submit').click();});"><?php _e('提交菜单至微信'); ?></a>

                                <a class="btn btn-primary inline-block" href="javascript:;" onclick="if ($('#mp_menu_table tbody tr.top_menu').length > 2) { AWS.alert('<?php _e('最多只能创建 3 个菜单'); ?>') } else { $('#mp_menu_table tbody').append($('#top_menu_template').html().replace(/{MENU_ID}/g, hex_md5(new Date().getTime() + '|' + Math.random())));$('#mp_menu_table tbody tr:last').find('.icb-wechat-select').append($('#select_option').html()); }"><?php _e('添加菜单'); ?></a>

                                <a class="btn btn-primary inline-block" href="javascript:;" onclick="AWS.ajax_post($('#mp_menu_form'));"><?php _e('保存设置'); ?></a>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </form>
            <form action="admin/tools/init/" id="form_update_weixin_menu" method="post">
                <input type="hidden" name="action" value="update_weixin_menu" />
                <input type="submit" id="form_update_weixin_menu_submit" class="collapse"/>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    var mp_menu_list = eval('[<?php echo json_encode($this->mp_menu); ?>]');

    $(function()
    {
        // 初始化上传按钮
        ajax_upload_reload();

        // 遍历给ajaxbox插入模板
        $.each($("script[type='text/x-template']"), function (i, e) {
            $('#icb-modal-window').append($(e).html());
        });

        //动态插入select内的option
        $('#mp_menu_table tr').find('.icb-wechat-select').append($('#select_option').html());

        //select初始化
        $.each(mp_menu_list, function(i, e)
        {
            if (e)
            {
                $.each(e, function(i, e)
                {
                    var _i = i, _e = e, sub_command_type = [];
                    // 判断是否有子菜单
                    if (e.sub_button)
                    {
                        // arr_code span内容数组, arr_select select内容
                        var arr_code = [], arr_select = [];
                        // 遍历初始化子菜单
                        $.each(e.sub_button, function(i, e)
                        {
                            var _e = e;
                            arr_select.push(e.command_type);
                            // 判断如果是自定义url给arr_code插入空数组
                            if (_e.key.match('http'))
                            {
                                arr_code.push('');
                            }
                            else
                            {
                                // 遍历模板中li,找到相应的中文内容插入到arr_code内
                                $.each($('#icb-modal-window ul li'), function (i, e)
                                {
                                    if ($(this).attr('data-value') == _e.key)
                                    {
                                        arr_code.push($(this).text());
                                    }
                                });
                            }
                        });

                        // 遍历select菜单数组,设置select菜单默认值,动态插入下拉菜单
                        $.each(arr_select, function (i, e)
                        {
                            //插入菜单
                            addDropdownList(e, $('.sub_button_' + _i).eq(i), false);

                            if (e == '自定义 URL')
                            {
                                $('.sub_button_' + _i).eq(i).find('input.input-bg, span').hide();
                                $('.sub_button_' + _i).eq(i).find('.input-code').removeAttr('readonly').show();
                                $('.sub_button_' + _i).eq(i).find('.icb-wechat-select').val(e);
                            }
                            else
                            {
                                $('.sub_button_' + _i).eq(i).find('.icb-wechat-select').val(e);
                            }
                        });

                        // 遍历arr_code数组,设置span默认值
                        $.each(arr_code, function (i, e)
                        {
                            $('.sub_button_' + _i).eq(i).find('.icb-wechat-dropdown-box span').html(e);
                            $.each($('.sub_button_' + _i).eq(i).find('.icb-wechat-dropdown-box ul li'), function (i, e)
                            {

                                if ($(this).attr('data-value') == $(this).parents('.icb-wechat-dropdown-box').find('input.input-code').val())
                                {
                                    if ($(this).attr('data-value').match('http://'))
                                    {
                                        $(this).parents('.icb-wechat-dropdown-box').find('.ajax-upload, .ajax-look ').hide();
                                    }
                                    else if ($(this).attr('data-upload') == 'false')
                                    {
                                        $(this).parents('.icb-wechat-dropdown-box').find('.ajax-upload, .ajax-look ').hide();
                                    }
                                    else
                                    {
                                        $(this).parents('.icb-wechat-dropdown-box').find('.ajax-upload, .ajax-look ').show();
                                    }
                                }
                                else if ($(this).parents('.icb-wechat-dropdown-box').find('input.input-code').val().match('http://'))
                                {
                                    $(this).parents('.icb-wechat-dropdown-box').find('.ajax-upload, .ajax-look ').hide();
                                }
                            });
                        });
                        //隐藏父节点的上传图片按钮
                        $('.sub_button_' + _i).eq(0).prev().find('.ajax-upload, .ajax-look').hide();
                    }
                    else
                    {
                        var _e = e;
                        // 插入下拉菜单
                        addDropdownList(e.command_type, $('#menu_id_' + _i), false);

                        //判断当前是自定义url
                        if (e.command_type == '自定义 URL')
                        {
                            $('#menu_id_' + i).find('.icb-wechat-dropdown-box input.input-code').removeAttr('readonly').val(e.key).show();
                            $('#menu_id_' + i).find('.input-bg, span').hide();
                        }

                        // 遍历下拉模板中的li,根据key找中文
                        $.each($('#icb-modal-window ul li'), function (i, e)
                        {
                            if ($(this).attr('data-value') == _e.key)
                            {
                                $('#menu_id_' + _i).find('.icb-wechat-dropdown-box span').html($(this).text());
                            }

                        });

                        // 给select选中初始化
                        $('#menu_id_' + i).find('.icb-wechat-select').val(e.command_type);

                        // 判断当前下拉菜单是否有上传图片功能
                        if ($('#menu_id_' + _i).find('.icb-wechat-dropdown-box ul').length > 0)
                        {
                            $.each($('#menu_id_' + _i).find('.icb-wechat-dropdown-box ul li'), function (i, e)
                            {
                                if ($(this).attr('data-value') == _e.key)
                                {
                                    if ($(this).attr('data-upload') == 'false')
                                    {
                                        $('#menu_id_' + _i).find('.ajax-upload, .ajax-look ').hide();
                                    }
                                }
                            });
                        }
                        else
                        {
                            $('#menu_id_' + _i).find('.ajax-upload, .ajax-look ').hide();
                        }

                    }
                });
            }

        });

        $('#icb-modal-window').html('');


        // select菜单事件绑定
        $(document).on('change', 'select.icb-wechat-select', function()
        {
            addDropdownList($(this).val(), $(this).parents('tr'), true);
        });

        // 点击显示下拉菜单时,将菜单默认选中的选项添加hover状态
        $(document).on('click', '.icb-wechat-dropdown-box span, .icb-wechat-dropdown-box input.input-bg, .icb-wechat-dropdown-box .icon-down', function ()
        {
            $.each($(this).parents('.icb-wechat-dropdown-box').find('.dropdown-menu ul li'), function (i, e)
            {
                if ($(this).attr('data-value') == $(this).parents('.icb-wechat-dropdown-box').find('input.input-code').val())
                {
                    $(this).addClass('active');
                    if ($(this).index() > 5)
                    {
                        $(this).parents('ul').scrollTop((parseInt($(this).index() - 5)) * parseInt($(this).css('height')));
                    }
                }
            });
        });

        // 展开菜单内列表点击事件
        $(document).on('click', '.icb-wechat-dropdown-box .dropdown-menu ul li', function ()
        {
            if ($(this).attr('data-upload') == 'false')
            {
                $(this).parents('.icb-wechat-dropdown-box').find('.ajax-upload, .ajax-look').hide();
            }
            else
            {
                $(this).parents('.icb-wechat-dropdown-box').find('.ajax-upload, .ajax-look').show();
            }
            $(this).addClass('active');
            $(this).parents('.icb-wechat-dropdown-box').find('li').removeClass('active');
            if (!$(this).attr('data-value').match('http://'))
            {
                $(this).parents('.icb-wechat-dropdown-box').find('span').html($(this).text()).show();
            }
            $(this).parents('.icb-wechat-dropdown-box').find('input.input-code').val($(this).attr('data-value'));
            $(this).parents('.icb-wechat-dropdown-box').find('input.attach_key').val('');
        });

        /**
         * 微信菜单管理，帐号切换
         * 7月29日
         */

        if($('.weixin_dropdown .dropdown-menu').find("li").length <= 0){
            $('.weixin_dropdown').detach();
         }
    });

    /*
    *   插入下拉菜单
    *   type:类型
    *   element:容器
    *   defined_url: 为true时给自定义url输入框添加绑定, false时不做任何处理
    */
    function addDropdownList (type, element, defined_url)
    {
        switch (type)
        {
            case '最新问题' :
                var template = $('#drop_menu_template_new_question').html();
            break;
            case '最新内容' :
                var template = $('#drop_menu_template_new_posts').html();
            break;
            case '文章专题' :
                var template = $('#drop_menu_template_new_article').html();
            break;
            case '热门问题' :
                var template = $('#drop_menu_template_hot_question').html();
            break;
            case '推荐问题' :
                var template = $('#drop_menu_template_suggest_question').html();
            break;
            case '等待回复' :
                var template = $('#drop_menu_template_wait_replay').html();
            break;
            case '自定义回复' :
                var template = $('#drop_menu_template_auto_replay').html();
            break;
            case '全局命令' :
                var template = $('#drop_menu_template_global').html();
            break;
            case '自定义 URL' :
                var template = $('#drop_menu_template_defined_url').html();
            break;
        }

        if (template)
        {
            // 判断是否存在下拉菜单
            if (element.find('.icb-wechat-dropdown-box .dropdown-menu').length > 0)
            {
                element.find('.icb-wechat-dropdown-box .dropdown-menu').detach();
            }
            // 判断上一次选择自定义 URL选项
            if (element.find('.icb-wechat-dropdown-box input.input-code').is(':visible'))
            {
                element.find('.icb-wechat-dropdown-box .icon-down, .icb-wechat-dropdown-box input.input-bg').show();
                element.find('.icb-wechat-dropdown-box input.input-code').val('').attr('readonly','readonly').hide();
            }
            if (type == '自定义 URL' && defined_url == true)
            {
                element.find('.icb-wechat-dropdown-box span, .icb-wechat-dropdown-box .ajax-upload, .icb-wechat-dropdown-box .ajax-look').hide();
                element.find('.icb-wechat-dropdown-box input.input-bg').hide();
                element.find('.icb-wechat-dropdown-box input.input-code').val('http://').removeAttr('readonly').show();
            }
            element.find('.icb-wechat-dropdown-box').append(template);
        }
    }

    function ajax_upload_reload()
    {
        $.each($('.ajax-upload'), function (i, e) 
        {
            var _this = this, attach_key = hex_md5(new Date().getTime() + ' ' + Math.random());

            var fileupload = new FileUpload('avatar', $(this), '', G_BASE_URL + '/admin/ajax/weixin/mp_menu_list_image_upload/?attach_access_key=' + attach_key, '', function()
                {
                    $(_this).prev().val(attach_key);
                    $(_this).next().find('i').removeClass('disabled');
                });
        });
    }
</script>

<?php View::output('admin/global/footer.php'); ?>