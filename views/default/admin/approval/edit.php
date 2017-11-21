<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
            <h3>
                <span class="pull-left"><?php _e('编辑内容'); ?></span>
            </h3>
        </div>

        <div class="tab-content mod-content">
            <form action="admin/ajax/save_approval_item/" id="approval_form" method="post" onsubmit="return false">
                <input name="id" type="hidden" value="<?php echo $this->approval_item['id']; ?>" />
                <input type="hidden" name="type" value="<?php echo $this->approval_item['type']; ?>" />

                <table class="table table-striped">
                    <?php if (in_array($this->approval_item['type'], array('question', 'article', 'received_email'))) { ?>
                    <tr>
                        <td>
                            <div class="form-group">
                                <span class="col-sm-4 col-xs-3 control-label"><?php _e('标题'); ?>:</span>
                                <div class="col-sm-5 col-xs-8">
                                    <input class="form-control" name="title" type="text" value="<?php echo $this->approval_item['title']; ?>" />
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>

                    <tr>
                        <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('内容'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <textarea class="form-control textarea" name="content" rows="10"><?php echo $this->approval_item['content']; ?></textarea>
                            </div>
                        </div>
                        </td>
                    </tr>

                    <?php if ($this->approval_item['type'] == 'question') { ?>
                    <tr>
                        <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('话题'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input class="form-control" name="topics" type="text" value="<?php echo $this->approval_item['topics']; ?>" />

                                <span class="help-block">多个话题请用 , 分隔</span>
                            </div>
                        </div>
                        </td>
                    </tr>
                    <?php } ?>

                    <?php if ($this->approval_item['attachs']) { ?>
                    <tr>
                        <td>
                            <p><strong><?php _e('附件'); ?></strong></p>

                            <blockquote>
                                <ul class="icb-upload-img-list">
                                    <?php foreach ($this->approval_item['attachs'] AS $attach) { ?>
                                        <?php if ($attach['is_image']) { ?>
                                        <li>
                                            <input name="attachs[]" type="hidden" value="<?php echo $attach['id']; ?>" />

                                            <a href="<?php echo $attach['attachment']; ?>" target="_blank" data-fancybox-group="thumb" rel="lightbox"><img src="<?php echo $attach['attachment']; ?>" class="img-polaroid" alt="<?php echo $attach['attach_name']; ?>" /></a>

                                            <a href="javascript:;" class="btn btn-danger" ><?php _e('删除'); ?></a>
                                        </li>
                                        <?php } ?>
                                    <?php } ?>
                                </ul>
                            </blockquote>

                            <blockquote>
                                <ul class="icb-upload-file-list">
                                    <?php foreach ($this->approval_item['attachs'] AS $attach) { ?>
                                        <?php if (!$attach['is_image']) { ?>
                                        <li>
                                            <input name="attachs[]" type="hidden" value="<?php echo $attach['id']; ?>" />

                                            <a href="<?php echo download_url($attach['file_name'], $attach['attachment']); ?>"><em class="icb-icon i-upload-file"></em><?php echo $attach['file_name']; ?></a>

                                            <a class="btn btn-danger btn-sm" href="javascript:;" ><?php _e('删除'); ?></a>
                                        </li>
                                        <?php } ?>
                                    <?php } ?>
                                </ul>
                            </blockquote>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </form>
        </div>

        <div class="tab-content mod-content mod-one-btn">
            <div class="center-block">
                <input type="button" value="<?php _e('保存设置'); ?>" class="btn btn-primary" onclick="AWS.ajax_post($('#approval_form'));" />
            </div>
        </div>
    </div>
</div>

<script>
    $(function(){

        // 附件删除功能
        $('.btn-danger').click(function()
        {
            $(this).parents('li').hide();

            $(this).parents('li').find('input').attr('name', 'remove_' + $(this).parents('li').find('input').attr('name'));
        });

    });
</script>
<?php View::output('admin/global/footer.php'); ?>