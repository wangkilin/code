
            <div class="tab-pane" id="search">

                <form method="post" action="admin/books/index/" onsubmit="return false;" id="search_form" class="form-horizontal" role="form">
                    <input name="action" type="hidden" value="search" />

                    <div class="form-group">
                        <label class="col-sm-2 col-xs-3 control-label"><?php _e('系列'); ?>:</label>

                        <div class="col-sm-5 col-xs-8">
                            <input class="form-control" type="text" value="<?php echo rawurldecode($_GET['serial']); ?>" name="serial" placeholder="系列关键字"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-xs-3 control-label"><?php _e('书名'); ?>:</label>

                        <div class="col-sm-5 col-xs-8">
                            <input class="form-control" type="text" value="<?php echo rawurldecode($_GET['book_name']); ?>" name="book_name"  placeholder="书名关键字"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-xs-3 control-label"><?php _e('校次'); ?>:</label>

                        <div class="col-sm-5 col-xs-8">
                            <input class="form-control" type="text" value="<?php echo rawurldecode($_GET['proofreading_times']); ?>" name="proofreading_times"  placeholder="校次关键字"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 col-xs-3 control-label"><?php _e('发稿时间'); ?>:</label>

                        <div class="col-sm-6 col-xs-9">
                            <div class="row">
                                <div class="col-xs-11  col-sm-5 mod-double icon-date-container">
                                    <input type="text" class="form-control mod-data js-date-input" value="<?php echo base64_decode($_GET['start_date']); ?>" name="start_date" autocomplete="off" placeholder="开始日期"/>
                                    <i class="icon icon-date"></i>
                                    <i class="icon icon-date-delete icon-delete"></i>
                                </div>
                                <span class="mod-symbol col-xs-1 col-sm-1">
                                -
                                </span>
                                <div class="col-xs-11 col-sm-5 icon-date-container">
                                    <input type="text" class="form-control mod-data js-date-input" value="<?php echo base64_decode($_GET['end_date']); ?>" name="end_date"  autocomplete="off" placeholder="结束日期"/>
                                    <i class="icon icon-date"></i>
                                    <i class="icon icon-date-delete icon-delete"></i>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-5 col-xs-8">
                            <button type="button" onclick="ICB.ajax.postForm($('#search_form'));" class="btn btn-primary"><?php _e('搜索'); ?></button>
                        </div>
                    </div>
                </form>
            </div>

<script type="text/javascript">
$(function () {

    /**
     * 日期输入框， 点击清除图标，将输入框内容清除
     */
    $('.icon-delete.icon-date-delete').click (function () {
        $(this).siblings('.js-date-input').val('');
    });
});
</script>
