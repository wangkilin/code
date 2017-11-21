

            <div class="tab-pane" id="search">
                <form method="post" action="admin/tag/list/" onsubmit="return false;" id="search_form" class="form-horizontal" role="form">

                    <input name="action" type="hidden" value="search" />

                    <div class="form-group">
                        <label class="col-sm-2 col-xs-3 control-label"><?php _e('关键词'); ?>:</label>

                        <div class="col-sm-5 col-xs-8">
                            <input class="form-control" type="text" value="<?php echo rawurldecode($_GET['keyword']); ?>" name="keyword" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 col-xs-3 control-label"><?php _e('添加时间范围'); ?>:</label>

                        <div class="col-sm-6 col-xs-9">
                            <div class="row">
                                <div class="col-xs-11  col-sm-5 mod-double">
                                    <input type="text" class="form-control mod-data" value="<?php echo base64_decode($_GET['start_date']); ?>" name="start_date" />
                                    <i class="icon icon-date"></i>
                                </div>
                                <span class="mod-symbol col-xs-1 col-sm-1">
                                -
                                </span>
                                <div class="col-xs-11 col-sm-5">
                                    <input type="text" class="form-control mod-data" value="<?php echo base64_decode($_GET['end_date']); ?>" name="end_date" />
                                    <i class="icon icon-date"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 col-xs-3 control-label"><?php _e('作者'); ?>:</label>

                        <div class="col-sm-5 col-xs-8">
                            <input class="form-control" type="text" value="<?php echo $_GET['user_name']; ?>" name="user_name" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 col-xs-3 control-label"><?php _e('讨论数'); ?>:</label>

                        <div class="col-sm-6 col-xs-9">
                            <div class="row">
                                <div class="col-xs-11  col-sm-5 mod-double">
                                    <input type="text" class="form-control" name="discuss_count_min" value="<?php echo $_GET['discuss_count_min']; ?>" />
                                </div>
                                <span class="mod-symbol col-xs-1 col-sm-1">
                                -
                                </span>
                                <div class="col-xs-11 col-sm-5">
                                    <input type="text" class="form-control" name="discuss_count_max" value="<?php echo $_GET['discuss_count_max']; ?>" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-5 col-xs-8">
                            <button type="button" onclick="AWS.ajax_post($('#search_form'));" class="btn btn-primary"><?php _e('搜索'); ?></button>
                        </div>
                    </div>
                </form>
            </div>