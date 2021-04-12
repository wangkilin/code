
    <div class="tab-pane" id="add">
        <div class="table-responsive">
            <form action="admin/ajax/administration/save_user/" id="settings_form" method="post">
            <table class="table table-striped">
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('用户名'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input class="form-control" name="user_name" type="text" value="" />
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('邮箱'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input class="form-control" name="email" type="text" value="" />
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('密码'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <input class="form-control" name="password" type="password" value="" />
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('用户组'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <select class="form-control" name="group_id">
                                <?php foreach ($this->groupList AS $group) { ?>
                                <?php if ($group == 1 AND !$this->user_info['permission']['is_administortar']) { continue; } ?>
                                <option value="<?php echo $group['group_id']; ?>"<?php if ($group['group_id'] == 4) { ?> selected="selected"<?php } ?>><?php echo $group['group_name']; ?></option>
                                <?php } ?>
                                </select>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('是否组长'); ?>:</span>
                            <input type="hidden" name="remark[sinho_permission_team_leader]" value="是否为组长"/>
                            <div class="col-sm-5 col-xs-8 btn-group mod-btn">
									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="1" name="attributes[sinho_permission_team_leader]"<?php if ($this->userAttributes['sinho_permission_team_leader']) { ?> checked="checked"<?php } ?>> <?php _e('是'); ?>
									</label>

									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="0" name="attributes[sinho_permission_team_leader]"<?php if (! $this->userAttributes['sinho_permission_team_leader']) { ?> checked="checked"<?php } ?>> <?php _e('否'); ?>
									</label>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('副科'); ?>:</span>
                            <div class="col-sm-5 col-xs-8">
                                <select id="more_subject" name="attributes[sinho_more_subject][]" class="form-control" multiple>
                                    <?php foreach (SinhoBaseController::SUBJECT_LIST as $_subjectKey => $_subjectInfo) {?>
                                        <option value="<?php echo $_subjectKey;?>" <?php if ( in_array($_subjectKey, $this->moreSubjects) ) { ?> selected<?php } ?>><?php echo $_subjectInfo['name'];?></option>
                                    <?php }?>
                                </select>
                                <input type="hidden" name="remark[sinho_more_subject]" value="设置的责编的副科。在稿子分配时，根据主副科优先选择对应的责编"/>
                            </div>
                        </div>
                    </td>
                </tr>

                <tfoot>
                <tr>
                    <td>
                        <input type="button" value="<?php _e('添加用户'); ?>" class="btn btn-primary center-block" onclick="AWS.ajax_post($('#settings_form'));" />
                    </td>
                </tr>
                </tfoot>
            </table>
            </form>
        </div>
    </div>

<script>
$(function(){

    $("#more_subject").multiselect({
        			nonSelectedText : '<?php _e('---- 选择学科 ----');?>',
                    maxHeight       : 200,
                    buttonWidth     : '100%',
                    allSelectedText : '<?php _e('已选择所有学科');?>',
                    numberDisplayed : 7, // 选择框最多提示选择多少个人名
        		});
});
</script>
