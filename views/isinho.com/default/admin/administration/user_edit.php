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
                                    <label class="icb-label"><?php _e('密码'); ?>:</label>
                                </div>
                                <div class="col-sm-4 icb-item-title">
                                    <input class="form-control" name="password" type="password" value="" />

                                    <span class="help-block"><?php _e('不更改请留空'); ?></span>
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
                                    <label class="icb-label"><?php _e('允许跨学科'); ?>:</label>
                                </div>
                                <div class="col-sm-4 icb-item-title text-left">
                                    <select id="more_subject" name="attributes[sinho_more_subject][]" class="form-control js_select" multiple>
                                        <?php foreach ($this->bookSubjectList as $_subjectKey => $_subjectInfo) {?>
                                            <option value="<?php echo $_subjectKey;?>" <?php if ( in_array($_subjectKey, $this->moreSubjects) ) { ?> selected<?php } ?>><?php echo $_subjectInfo['name'];?></option>
                                        <?php }?>
                                    </select>
                                    <input type="hidden" name="remark[sinho_more_subject]" value="设置的责编的副科。在稿子分配时，根据主副科优先选择对应的责编"/>
                                </div>
                            </div>
                            <div class="row">
                                <!-- 是否组长 -->
                                <div class="col-sm-2">
                                    <label class="icb-label"><?php _e('是否组管理员'); ?>:</label>
                                    <input type="hidden" name="remark[sinho_permission_team_leader]" value="是否为组管理员"/>
                                </div>
                                <div class="col-sm-4 icb-item-title">
								  <div class="btn-group mod-btn col-sm-4 nopadding">
									<label type="button" class="btn mod-btn-color js-input-radio">
										<input type="radio" value="1" name="attributes[sinho_permission_team_leader]"<?php if ($this->userAttributes['sinho_permission_team_leader']) { ?> checked="checked"<?php } ?>> <?php _e('是'); ?>
									</label>

                                    </div>
                                    <div class="btn-group mod-btn  col-sm-offset-4 col-sm-4 nopadding">
									<label type="button" class="btn mod-btn-color js-input-radio">
										<input type="radio" value="0" name="attributes[sinho_permission_team_leader]"<?php if (! $this->userAttributes['sinho_permission_team_leader']) { ?> checked="checked"<?php } ?>> <?php _e('否'); ?>
									</label>
								  </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-2">
                                    <label class="icb-label"><?php _e('允许管理学科'); ?>:</label>
                                </div>

                                <div class="col-sm-4 icb-item-title text-left">
                                    <select id="sinho_manage_subject" name="attributes[sinho_manage_subject][]" class="form-control" multiple>
                                        <?php foreach ($this->bookSubjectList as $_subjectKey => $_subjectInfo) {?>
                                            <option value="<?php echo $_subjectKey;?>" <?php if ( is_array($this->userAttributes['sinho_manage_subject']) && in_array($_subjectKey, $this->userAttributes['sinho_manage_subject'])) { ?> selected<?php } ?>><?php echo $_subjectInfo['name'];?></option>
                                        <?php }?>
                                    </select>
                                    <span class=" help-block"><?php _e('管理对应学科下的图书，及分配对应学科下的工作量'); ?></span>
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


    $("#sinho_manage_subject, .js_select").multiselect({
                nonSelectedText : '<?php _e('---- 选择学科 ----');?>',
                maxHeight       : 200,
                buttonWidth     : '100%',
                allSelectedText : '<?php _e('已选择所有学科');?>',
                numberDisplayed : 7, // 选择框最多提示选择多少个人名
            });


    // 是否是组管理员 开关 决定可以管理哪些组
    $('input[type=radio]').on('ifChecked', function(obj){
        if ($(this).attr('name') == 'attributes[sinho_permission_team_leader]') {
            if($(this).val()==1 ) {
                $("#sinho_manage_subject").multiselect('enable');
            } else {
                $("#sinho_manage_subject").siblings('.open').removeClass('open');
                $("#sinho_manage_subject").multiselect('clearSelection');
                $("#sinho_manage_subject").multiselect('rebuild');
                $("#sinho_manage_subject").multiselect('disable');
            }
        }
    });
    $('input[type=radio]').filter(':checked[name="attributes[sinho_permission_team_leader]"]').trigger('ifChecked');

});
</script>
<?php View::output('admin/global/footer.php'); ?>
