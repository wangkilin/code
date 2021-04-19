<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="tab-content mod-content">
        <div class="row">
            <div class="col-md-12">
            <div class="col-md-7 nopadding">
                <form action="admin/ajax/save_nav_menu/" method="post" id="nav_menu_list" onsubmit="return false">
                    <div class="mod icb-message-box ">
                    <div class="mod-set-head">
                        <a class="btn btn-primary btn-xs pull-right" onclick="AWS.ajax_post($('#nav_menu_list'));"><?php _e('保存设置'); ?></a>
                        <h3>
                            <?php _e('导航菜单'); ?>
                            <span><?php _e('拖动菜单可调整导航菜单排序'); ?></span>
                        </h3>
                    </div>
                    <div class="mod-set-body">
                    <input type="hidden" name="item_sort" id="item_sort">

                    <p>
                        <select name="category_display_mode" class="pull-left form-control">
                            <option value="list"<?php if ($this->setting['category_display_mode'] == 'list') { ?> selected="selected"<?php } ?>><?php _e('文字列表模式'); ?></option>
                            <option value="icon"<?php if ($this->setting['category_display_mode'] == 'icon') { ?> selected="selected"<?php } ?>><?php _e('图标模式'); ?></option>
                        </select>

                        <label class="pull-right"><input type="checkbox" value="Y" name="nav_menu_show_child"<?php if ($this->setting['nav_menu_show_child'] == 'Y') { ?> checked="checked"<?php } ?>> <?php _e('显示分类下的子分类'); ?></label>
                    </p>
                    <div class="icb-item-info">
                    <ul data-listidx="0">
                        <?php if ($this->nav_menu_list) { ?>
                        <?php foreach($this->nav_menu_list as $key => $val) { ?>
                        <?php if ($val['title']) { ?>
                        <li data-sort="<?php echo $val['id']; ?>" data-cursor="pointer">
                            <div class="mod-set-head <?php echo $val['parent_id'] ? 'item-intent' : 'item-no-intent' ?>">
                                <span class="pull-right">
                                  <a href="javascript:;"  onclick="$(this).closest('.item-intent, .item-no-intent').toggleClass('item-intent').toggleClass('item-no-intent');$(this).find('.js-is-child').val(1-$(this).find('.js-is-child').val());event.stopPropagation();">
                                    <i class="icon-up">升级</i>
                                    <i class="icon-down">降级</i>
                                    <input class="form-control js-is-child" type="hidden" name="nav_menu[<?php echo $val['id']; ?>][is_child]" value="<?php echo intval($val['parent_id']>0); ?>" />
                                   </a>
                                  <a href="javascript:;" onclick="AWS.dialog('confirm', {'message': '<?php _e('确认删除?'); ?>'}, function(){AWS.ajax_request(G_BASE_URL + '/admin/ajax/remove_nav_menu/', 'id=<?php echo $val['id']; ?>');}); return false;"><?php _e('删除'); ?></a>
                                </span>
                                <h4><?php echo $val['title']; ?> (<?php
                                if ($val['type'] == 'category') { ?><?php _e('分类'); ?><?php
                                } else if ($val['type'] == 'feature') { ?><?php _e('专题'); ?><?php
                                } else if ($val['type'] == 'topic') { ?><?php _e('话题'); ?><?php
                                } else { ?><?php _e('自定义链接'); ?><?php } ?>)</h4>
                            </div>
                            <div class="mod-set-body clearfix">
                                <div class="icb-item-info-tag clearfix">
                                    <label class="pull-left">
                                        <?php _e('导航标签'); ?><br />
                                        <input class="form-control" type="text" name="nav_menu[<?php echo $val['id']; ?>][title]" value="<?php echo $val['title']; ?>" />
                                    </label>
                                    <div class="pull-left icb-item-info-img">
                                        <a id="icb-item-info-img-upload-<?php echo $val['id']; ?>"><img src="<?php if ($val['icon']) { ?><?php echo get_setting('upload_url');?>/nav_menu/<?php echo $val['icon']; ?><?php } else { ?><?php echo G_STATIC_URL; ?>/css/default/img/default_class_imgs.png<?php } ?>" alt="" id="icb-item-info-img-<?php echo $val['id']; ?>" /></a>
                                    </div>
                                </div>

                                <?php if ($val['type'] == 'custom') { ?>
                                <div class="icb-item-info-descrip">
                                    <label class="pull-left">
                                        <?php _e('链接'); ?><br />
                                        <input class="form-control" type="text" name="nav_menu[<?php echo $val['id']; ?>][link]" value="<?php echo $val['link']; ?>" />
                                    </label>
                                </div>
                                <?php } ?>

                                <div class="icb-item-info-descrip">
                                    <label class="pull-left">
                                        <?php _e('描述'); ?><br />
                                        <input class="form-control" type="text" name="nav_menu[<?php echo $val['id']; ?>][description]" value="<?php echo $val['description']; ?>" />
                                    </label>
                                </div>
                            </div>
                        </li>
                        <?php } ?>
                        <?php } ?>
                        <?php } ?>
                    </ul>
                    </div>
                    </div>
                </div>
                </form>
            </div>

            <div class="col-md-5">
            <div class="mod">
                <div class="icb-message-box topic">
                    <div class="mod-set-head">
                        <h3>分类</h3>
                    </div>
                    <div class="mod-set-body">
                        <form action="admin/ajax/add_nav_menu/" method="post">
                            <input type="hidden" name="type" value="category">
                            <select name="module_id" id="module_id" class="form-control pull-left input-small">
                                <option value="0"><?php _e('选择模块'); ?></option>
                                <?php echo $this->module_option; ?>
                            </select>
                            <br/><br/>
                            <select name="type_id" id="type_id" class="form-control pull-left input-small">
                                <option value="0"><?php _e('模块分类'); ?></option>
                                <?php echo $this->category_list; ?>
                            </select>
                            <a onclick="AWS.ajax_post($(this).parents('form'));" class="btn btn-primary"><?php _e('添加至导航菜单'); ?></a>
                        </form>
                    </div>
                </div>
            </div>
            <div class="mod">
                <div class="icb-message-box topic">
                    <div class="mod-set-head">
                        <h3>标签</h3>
                    </div>
                    <div class="mod-set-body">
                        <form action="admin/ajax/add_nav_menu/" method="post">
                            <input type="hidden" name="type" value="topic">
                            <select name="type_id" class="form-control pull-left input-small">
                                <option value="0"><?php _e('无'); ?></option>
                                <?php echo $this->tag_list; ?>
                            </select>
                            <a onclick="AWS.ajax_post($(this).parents('form'));" class="btn btn-primary"><?php _e('添加至导航菜单'); ?></a>
                        </form>
                    </div>
                </div>
            </div>

            <div class="mod">
                <div class="icb-message-box define-link">
                    <div class="mod-set-head">
                        <h3><?php _e('自定义链接'); ?></h3>
                    </div>
                    <div class="mod-set-body">
                        <form action="admin/ajax/add_nav_menu/" method="post">
                            <input type="hidden" name="type" value="custom">
                            <p>
                                导航标签
                                <br />
                                <input class="form-control" type="text" name="title"></p>
                            <p>
                                描述
                                <br />
                                <input class="form-control" type="text" name="description"></p>
                            <p>
                                链接
                                <br />
                                <input class="form-control" type="text" name="link" value="http://"></p>
                            <a onclick="AWS.ajax_post($(this).parents('form'));" class="btn btn-primary"><?php _e('添加至导航菜单'); ?></a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>
        </div>
    </div>

<script type="text/javascript">
$(document).ready(function () {
    <?php if ($this->nav_menu_list) { ?>
    <?php foreach($this->nav_menu_list as $key => $val) { ?>
        <?php if ($val['title']) { ?>
        var fileUpload_<?php echo $val['id']; ?> = new FileUpload('avatar', $('#icb-item-info-img-upload-<?php echo $val['id']; ?>'), $('#icb-item-info-img-<?php echo $val['id']; ?>'), G_BASE_URL + '/admin/ajax/nav_menu_upload/<?php echo $val['id']; ?>', {'multiple' : false});

        <?php } ?>
    <?php } ?>
    <?php } ?>
    //$('#type_id').attr('disabled', 'disabled');
    $('#module_id').change(function () {
        // var moduleId = $(this).val();
        // if (moduleId>0) {
        //     $('#type_id').find('option[data-module="'+moduleId+'"]').show();
        //     $('#type_id').find('option[data-module!="'+moduleId+'"]').hide();
        //     $('#type_id').removeAttr('disabled');
        // } else {
        //     $('#type_id').attr('disabled', 'disabled');
        // }
    });
});
</script>

<?php View::output('admin/global/footer.php'); ?>
