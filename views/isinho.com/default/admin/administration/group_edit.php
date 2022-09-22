<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
	<form action="admin/ajax/administration/save_group_permission/" id="settings_form" method="post" onsubmit="return false">
	<input type="hidden" name="group_id" value="<?php echo $this->group['group_id']; ?>" />
	<div class="mod">
		<div class="mod-head">
			<h3>
				<span class="pull-left"><?php _e('用户组权限'); ?>: <?php echo $this->group['group_name']; ?></span>
			</h3>
			<?php if ($this->group['group_id'] != 99) {?>
            <h3>
                <ul class="nav nav-tabs">
                    <li class="js-toggle-class" data-toggle-class="js-sinho"><a><?php _e('权限管理'); ?></a></li>
                </ul>
            </h3>
        </div>
        <?php } ?>
		<div class="tab-content mod-content">

			<table class="table table-striped">
                <!-- 新禾网站权限  -->
                <?php if (check_extension_package('sinhoWorkload')) {
                foreach ($this->booleanParamList['sinho'] as $_varName => $_varText) {?>
				<tr class="js-sinho hide">
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php echo $_varText; ?>:</span>
							<div class="col-sm-6 col-xs-8">
								<div class="btn-group mod-btn">
									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="1" name="permission[<?php echo $_varName; ?>]"<?php if ($this->group_pms[$_varName]) { ?> checked="checked"<?php } ?>> <?php _e('是'); ?>
									</label>

									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="0" name="permission[<?php echo $_varName; ?>]"<?php if (!$this->group_pms[$_varName]) { ?> checked="checked"<?php } ?>> <?php _e('否'); ?>
									</label>
								</div>
							</div>
						</div>
					</td>
				</tr>
                <?php } // end foreach ?>
				<tr class="js-sinho hide">
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('首选学科'); ?>:</span>
							<div class="col-sm-2 col-xs-4">
                                <select name="permission[sinho_subject]" class="js_select form-control">
                                     <option value="0">-- 选择学科 --</option>
                                     <?php foreach ($this->bookSubjectList as $_subjectKey => $_subjectInfo) {?>
                                        <option value="<?php echo $_subjectKey;?>" <?php if ( $_subjectKey == $this->group_pms['sinho_subject']) { ?> selected<?php } ?>><?php echo $_subjectInfo['name'];?></option>
                                     <?php }?>
                                </select>
							</div>
						</div>
					</td>
				</tr>
                <?php } // end if ?>
				<tfoot>
				<tr>
					<td>
						<div class="form-group bg-warning">页面增加参数后， 需要修改 app/admin/ajax.php中的权限参数设置。 将新参数名称加入到对应方法中</div>
					</td>
				</tr>
				<tr>
					<td>
						<input type="button" value="<?php _e('保存设置'); ?>" class="btn btn-primary center-block" onclick="ICB.ajax.postForm($('#settings_form'));" />
					</td>
				</tr>
				</tfoot>
			</table>
		</div>
		</div>
	</form>
</div>
<script type="text/javascript">
$(function () {
    $('.js-toggle-class').click (function () {
        $(this).addClass('active');
        $(this).siblings().each(function () {
            $(this).removeClass('active');
            $('.' + $(this).data('toggle-class')).hide();
        });
        $('.' + $(this).data('toggle-class')).removeClass('hide').show();
    });
    $('.js-toggle-class:first').trigger('click');


    $(".js_select").multiselect({
        			nonSelectedText : '<?php _e('-- 选择学科 --');?>',
                    maxHeight       : 200,
                    buttonWidth     : '100%',
                    allSelectedText : '<?php _e('已选择所有学科');?>',
                    numberDisplayed : 7, // 选择框最多提示选择多少个人名
        		});

});
</script>

<?php View::output('admin/global/footer.php'); ?>
