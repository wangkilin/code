<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="tab-content mod-content">
        <div class="row">
            <div class="col-md-12">
                <form action="admin/ajax/save_nav_menu/" method="post" id="nav_menu_list" onsubmit="return false">
                    <div class="mod icb-message-box ">
                    <div class="mod-set-head">
                        <a class="btn btn-primary btn-xs pull-right" onclick="AWS.ajax_post($('#nav_menu_list'));"><?php _e('保存设置'); ?></a>
                        <h3>
                            <?php _e('课后作业'); ?>
                            <span><?php echo $this->item['title']; ?></span>
                        </h3>
                        <h3>
                            <span><?php _e('拖动菜单可调整导航菜单排序'); ?></span>
                        </h3>
                    </div>
                    <div class="mod-set-body">
                    <input type="hidden" name="item_sort" id="item_sort">

                    <div class="icb-item-info">
                    <ul data-listidx="0" id="homework_question_list">
                        <?php //if ($this->nav_menu_list) { ?>
                        <?php //foreach($this->nav_menu_list as $key => $val) { ?>
                        <?php //if ($val['title']) { ?>
                        <li data-sort="<?php echo $val['id']; ?>" data-cursor="pointer">
                            <div class="mod-set-head <?php echo $val['parent_id'] ? 'item-intent' : 'item-no-intent' ?>">
                                <span class="pull-right">
                                  <a href="javascript:;" onclick="ICB.modal.confirm('<?php _e('确认删除?'); ?>', function(){ICB.ajax.request(G_BASE_URL + '/admin/ajax/remove_nav_menu/', 'id=<?php echo $val['id']; ?>');}); return false;"><?php _e('删除'); ?></a>
                                </span>
                                <h4><?php echo $val['title']; ?></h4>
                            </div>
                            <div class="mod-set-body clearfix">
                                <div class="icb-item-info-tag clearfix">
                                    <label class="pull-left">
                                        <?php _e('语音'); ?><br />
                                    </label>
                                    <div class="pull-left icb-item-info-img">
                                        <a id="icb-item-info-img-upload-<?php echo $val['id']; ?>" class="js-upload">
                                        <input name="homework[<?php echo $val['id']; ?>][attach_id]" type="hidden" value="<?php echo $val['attach_id']; ?>"/>
                                        <img src="<?php
                                        if ($val['icon']) {
                                           echo get_setting('upload_url');?>/nav_menu/<?php echo $val['icon'];
                                        } else {
                                           echo G_STATIC_URL; ?>/css/default/img/default_class_imgs.png<?php
                                        } ?>" alt="" id="icb-item-info-img-<?php echo $val['id']; ?>" class="js-show"/>
                                        </a>
                                    </div>
                                </div>

                                <div class="icb-item-info-descrip">
                                    <label class="pull-left">
                                        <?php _e('文字'); ?><br />
                                    </label>
                                    <textarea class="form-control col-sm-12" type="text" name="homework[<?php echo $val['id']; ?>][content]" ><?php echo $val['content']; ?></textarea>
                                    <input name="homework[<?php echo $val['id']; ?>][id]" type="hidden" value="<?php echo $val['id']; ?>"/>
                                </div>
                            </div>
                        </li>


                        <li data-sort="<?php echo $val['id']; ?>" data-cursor="pointer">
                            <div class="mod-set-head <?php echo $val['parent_id'] ? 'item-intent' : 'item-no-intent' ?>">
                                <span class="pull-right">
                                  <a href="javascript:;" onclick="ICB.modal.dialog('confirm', {'message': '<?php _e('确认删除?'); ?>'}, function(){ICB.ajax.request(G_BASE_URL + '/admin/ajax/remove_nav_menu/', 'id=<?php echo $val['id']; ?>');}); return false;"><?php _e('删除'); ?></a>
                                </span>
                                <h4><?php echo $val['title']; ?></h4>
                            </div>
                            <div class="mod-set-body clearfix">
                                <div class="icb-item-info-tag clearfix">
                                    <label class="pull-left">
                                        <?php _e('语音'); ?><br />
                                    </label>
                                    <div class="pull-left icb-item-info-img">
                                        <a id="icb-item-info-img-upload-<?php echo $val['id']; ?>" class="js-upload">
                                        <input name="homework[<?php echo $val['id']; ?>][attach_id]" type="hidden" value="<?php echo $val['attach_id']; ?>"/>
                                        <img src="<?php
                                        if ($val['icon']) {
                                           echo get_setting('upload_url');?>/nav_menu/<?php echo $val['icon'];
                                        } else {
                                           echo G_STATIC_URL; ?>/css/default/img/default_class_imgs.png<?php
                                        } ?>" alt="" id="icb-item-info-img-<?php echo $val['id']; ?>" class="js-show"/>
                                        </a>
                                    </div>
                                </div>

                                <div class="icb-item-info-descrip">
                                    <label class="pull-left">
                                        <?php _e('文字'); ?><br />
                                    </label>
                                    <textarea class="form-control col-sm-12" type="text" name="homework[<?php echo $val['id']; ?>][content]" ><?php echo $val['content']; ?></textarea>
                                    <input name="homework[<?php echo $val['id']; ?>][id]" type="hidden" value="<?php echo $val['id']; ?>"/>
                                </div>
                            </div>
                        </li>
                        <?php //} ?>
                        <?php //} ?>
                        <?php //} ?>
                    </ul>
                    </div>
                    </div>
                </div>
                </form>

        </div>
        </div>
        </div>
    </div>

<script type="text/javascript">
$(document).ready(function () {
    var uploadUrl = 	G_BASE_URL + '/course/ajax/upload_attach/id-<?php echo $this->item['id']; ?>__type-homework__batchKey-<?php echo $this->batchKey;?>';
    function callback (info) {
        if (typeof info != 'object' || info.class_name != 'audio') {
            return;
        }
        var $domAudio = $('<audio/>').attr( {
            'controls'  : 'controls',
            'attach-id' : info.attach_id,
            'src'       : info.url
        });
        var $domHidden = $('<input/>').attr({
            'type'  : 'hidden',
            'name'  : '',
            'value' : info.attach_id
        });
        var $domContainer = $('<div/>').attr({
            'class' : '_item_container'
        });
        $(this.element).parent().hide().after($domContainer.append($domAudio).append($domHidden));
    }

    $('#homework_question_list li').each (function () {
		var $bindElement = $(this).find('.js-upload');
		var $showElement = $(this).find('.js-show');
        var fileupload = new FileUploader(
        $bindElement,
        $showElement,
    	    	uploadUrl,
    	    	{},
    	    	callback);
    });
    <?php if ($this->nav_menu_list) { ?>
    <?php foreach($this->nav_menu_list as $key => $val) { ?>
        <?php if ($val['title']) { ?>
        var fileUpload_<?php echo $val['id']; ?> = new FileUpload('avatar', $('#icb-item-info-img-upload-<?php echo $val['id']; ?>'), $('#icb-item-info-img-<?php echo $val['id']; ?>'), G_BASE_URL + '/admin/ajax/nav_menu_upload/<?php echo $val['id']; ?>', {'multiple' : false});

        <?php } ?>
    <?php } ?>
    <?php } ?>
});
</script>

<?php View::output('admin/global/footer.php'); ?>