		<div class="row">
            <div class="col-sm-12 form-group icb-item-title js-submit-choose" data-active="toggle">
                <form action="admin/course/content_table/" method="post" class="form-horizontal">
		            <label class="pull-left control-label"><?php _e('目录所属标签');?>:</label>
		            <!--
		            <div class="col-sm-10 active-hide-only"><?php echo $this->parentTitle; ?>
		                <span class="js-toggle-active">
						  <a><i class="icon icon-edit"></i> <?php _e('切换目录');?></a>
						</span>
		            </div>
		             -->
                    <div class="col-sm-7">
                            <select id="parent_id" name="parent_id" class="hidden js_parent_id">
                                <option value="0"><?php _e('选择标签'); ?></option>
                                <?php echo $this->topicOptions; ?>
                            </select>
                            <div class="dropdown">
								<div class="dropdown-toggle" data-toggle="dropdown">
									<span id="icb-selected-tag-show"><?php _e('选择标签'); ?></span>
									<a><i class="icon icon-down"></i></a>
								</div>
							</div>
                    </div>
                    <!--
                    <div class="col-sm-3 active-show-only">
                            <a class="btn btn-normal btn-gray close-add-tag js-toggle-active">取消</a>
                    </div>
                     -->
                 </form>
            </div>
        </div>