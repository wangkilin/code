<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
            <h3>
                <ul class="nav nav-tabs">
                    <li class=""><a href="admin/administration/editor/"><?php _e('责编列表'); ?></a>
                    <li class="active"><a><?php _e('修改设置'); ?></a>
                    </li>
                </ul>
            </h3>
        </div>

        <div class="mod-body tab-content">
            <div class="tab-pane active" id="book">
                <div class="">

					<form action="admin/ajax/administration/editor_edit/" method="post" id="item_form" onsubmit="return false;">
						<input type="hidden" name="post_hash" value="<?php echo new_post_hash(); ?>" />
                        <input type="hidden" name="id" value="<?php echo $this->userInfo['uid']; ?>" />
						<div class="icb-mod icb-book-infos">
                            <div class="row">
                                <!-- 系列 -->
                                <div class="col-sm-2 text-right">
                                    <h4 class=""><?php echo $this->userInfo['user_name'];?></h4>
                                </div>

                            </div>
                            <div class="row">
                                <!-- 校次 -->
                                <div class="col-sm-2">
                                    <label class="icb-label"><?php _e('属组/主科'); ?>:</label>
                                </div>
                                <div class="col-sm-4 icb-item-title">
                                    <select name="group_id" class="form-control">
                                        <?php echo $this->groupOptions;?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <!-- 校次 -->
                                <div class="col-sm-2">
                                    <label class="icb-label"><?php _e('副科'); ?>:</label>
                                </div>
                                <div class="col-sm-4 icb-item-title text-left">
                                    <select id="more_subject" name="more_subject[]" class="form-control" multiple>
                                        <?php foreach (SinhoBaseController::SUBJECT_LIST as $_subjectKey => $_subjectInfo) {?>
                                            <option value="<?php echo $_subjectKey;?>" <?php if ( in_array($_subjectKey, $this->moreSubjects) ) { ?> selected<?php } ?>><?php echo $_subjectInfo['name'];?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>

							<div class="row mod-footer clearfix">
                                <div class="col-sm-6 col-sm-offset-1">
								    <a class="btn btn-large btn-success" onclick="ICB.ajax.postForm($('#item_form')); return false;"><?php _e('保存设置'); ?></a>
                                </div>
							</div>
						</div>
					</form>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(function () {

    $("#more_subject").multiselect({
        			nonSelectedText : '<?php _e('---- 选择学科 ----');?>',
                    maxHeight       : 200,
                    buttonWidth     : '100%',
                    allSelectedText : '<?php _e('已选择所有学科');?>',
                    numberDisplayed : 7, // 选择框最多提示选择多少个人名
        		});

});
</script>
<?php View::output('admin/global/footer.php'); ?>
