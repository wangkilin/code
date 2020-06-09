		<div class="row">
            <div class="col-sm-12 form-group icb-item-title js-submit-choose" data-active="toggle">
                <form action="admin/course/content_table/" method="post" class="form-horizontal">
		            <label class="pull-left control-label"><?php _e('所属分类');?>:</label>
                    <div class="col-sm-7">
                            <input type="hidden" name="load_category" value="1"/>
                            <select id="category_id" name="category_id" class="hidden js_select_transform js_category_id">
                                <option value="0"><?php _e('选择分类'); ?></option>
                                <?php echo $this->itemOptions; ?>
                            </select>
                            <div class="dropdown">
								<div class="dropdown-toggle" data-toggle="dropdown">
									<span id="icb-selected-tag-show"><?php _e('选择分类'); ?></span>
									<a><i class="icon icon-down"></i></a>
								</div>
							</div>
                    </div>
                 </form>
            </div>
        </div>
