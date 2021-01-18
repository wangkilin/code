
            <div class="tab-pane" id="search">

                <form method="post" action="admin/course/list/" onsubmit="return false;" id="search_form" class="form-horizontal" role="form">
                        <div class="form-group icb-item-title" data-active="toggle">
		                    <label class="col-sm-2 col-xs-2 control-label"><?php _e('分类');?>:</label>
				            <div class="col-sm-5 active-hide-only ">
		                            <select name="category_id" class="hidden js_select_transform">
		                                <option value="0"><?php _e('选择分类'); ?></option>
		                                <?php echo $this->itemOptions; ?>
		                            </select>
		                            <div class="dropdown" id="search_category_id">
										<div class="dropdown-toggle" data-toggle="dropdown">
											<span id="icb-selected-tag-show"><?php _e('选择分类'); ?></span>
											<a><i class="icon icon-down"></i></a>
										</div>
									</div>
		                    </div>
			            </div>
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
                                    <input type="text" class="form-control mod-data" value="<?php echo $_GET['start_date']; ?>" name="start_date" />
                                    <i class="icon icon-date"></i>
                                </div>
                                <span class="mod-symbol col-xs-1 col-sm-1">
                                -
                                </span>
                                <div class="col-xs-11 col-sm-5">
                                    <input type="text" class="form-control mod-data" value="<?php echo $_GET['end_date']; ?>" name="end_date" />
                                    <i class="icon icon-date"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 col-xs-3 control-label"><?php _e('浏览次数'); ?>:</label>

                        <div class="col-sm-6 col-xs-9">
                            <div class="row">
                                <div class="col-xs-11  col-sm-5 mod-double">
                                    <input type="text" class="form-control" name="views_min" value="<?php echo $_GET['views_min']; ?>" />
                                </div>
                                <span class="mod-symbol col-xs-1 col-sm-1">
                                -
                                </span>
                                <div class="col-xs-11 col-sm-5">
                                    <input type="text" class="form-control" name="views_max" value="<?php echo $_GET['views_min']; ?>" />
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

<script type="text/javascript">
function convertSelectIntoDropdown (domSelect)
{
    var options = [], selectedId = '';
    $.each($(domSelect).find('option').toArray(), function (i, field) {
        if ($(field).attr('selected') == 'selected') {
            selectedId = $(domSelect).attr('value');
            $(domSelect).val(selectedId);// 设置选定的父级分类id
        }
        options.push("{'title':'" + $(field).text() + "', 'id':'" + $(field).val() + "', 'class':'" + $(this).attr('class') + "'}");
    });
    // 实现下拉列表数据内容
    ICB.dropdown.setList($(domSelect).closest('form').find('.dropdown'), eval('[' + options.join() + ']'), selectedId);
}
function setSelectValue (domSelect)
{
    var value = $(domSelect).val();
    $(domSelect).closest('form').find('.dropdown li a[data-value="'+value+'"]').each(function() {
        $(this).click().closest('li').addClass('active');
    });
}
$(document).ready(function () {
    var defaultCategoryId = $('#category_id option:selected').val();
    var defaultTableId = $('#table_id option:selected').val();

	//初始化分类
	if ($('.js_select_transform').length) {
		// 组装下拉列表需要的数据， 获取默认选择.
		$.each($('.js_select_transform'), function () {

            convertSelectIntoDropdown (this);
            return;

			var options = [], selectedId = '';
			var select = this;
			$.each($(select).find('option').toArray(), function (i, field) {
				if ($(field).attr('selected') == 'selected') {
					selectedId = $(this).attr('value');
					$(select).val(selectedId);// 设置选定的父级分类id
				}
				options.push("{'title':'" + $(field).text() + "', 'id':'" + $(field).val() + "', 'class':'" + $(this).attr('class') + "'}");
			});
			// 实现下拉列表数据内容
			ICB.dropdown.setList($(this).closest('form').find('.dropdown'), eval('[' + options.join() + ']'), selectedId);

		});
		// 监听下拉列表点击事件
		var $dropdownList = $('.icb-item-title .dropdown li a');
		$dropdownList.filter('[data-value="0"]').each(function(){
			if (! $(this).closest('#search_category_id').length) {
				$(this).closest('li').remove();
			}
		});
		$dropdownList.click(function() {
			var value = $(this).closest('form').find('.js_select_transform').val();
			// 只对本表单内的父级id起作用， 不能修改其他表单内的东西
            $(this).closest('form').find('.js_select_transform').val($(this).attr('data-value'));
            $(this).closest('form').find('.js_select_transform').trigger('change');

            // 如果本表单内有自动提交变更选择， 下拉列表选择发生变化， 提交表单.
            // 监测.dropdown.js-submit-choose同时存在
			if ($(this).attr('data-value')!= value && $(this).closest('.dropdown.js-submit-choose').length) {
			    ICB.ajax.postForm($(this).closest('form'));
            }

            // 选择教程后， 重新变更分类，需要把教程列表移除
            if ($(this).closest('form').find('#category_id').length && $('#table_id option:selected').length) {
                //ICB.ajax.postForm($(this).closest('form'));
                $('#js_course_table_list').remove();
            }
		});
		// 根据select的值，设置下拉框默认选中值内容
		$('.js_select_transform').each (function () {
            setSelectValue (this);return;

			var value = $(this).val();
			$(this).closest('form').find('.dropdown li a[data-value="'+value+'"]').each(function() {
				$(this).click().closest('li').addClass('active');
			});
        });


        //*
        // 选择分类后， 将对应模块选定。 如果是根分类， 需要选择所属的模块
        $('#category_id').change(function () {
            var categoryId = $(this).val();
            $('#table_id').closest('form').find('.dropdown li').hide();
            $('#table_id').find('option[data-category="' + categoryId + '"]').each(function(){
                var tableId = $(this).attr('value');
                $('#table_id').closest('form').find('.dropdown li a[data-value="' + tableId + '"]').parent().show();
            });
            // $('#table_id').closest('form').find('.dropdown li a[data-value="' + categoryId + '"]').each(function(){
            //     $(this).parent().show();
            // });
            $('#table_id').val('0').find('option:selected').removeAttr('selected');
            $('#table_id').closest('form')
                          .find('.dropdown #icb-selected-tag-show').html('所属教程');
            $('#table_id').closest('form').find('.dropdown li.active')
                          .removeClass('active');
        });

	}
	// 点击切换可见性
	//ICB.domEvents.toggleActiveClick('.js-toggle-active');
	// 点击教程 下拉列表， 将值赋给隐藏参数title
	$('.js-course-list option').click (function () {
		$(this).closest('form').find('input[name="title"]').val($(this).text());
    });



    //$('#category_id').closest('form').find('.dropdown li a[data-value="'+$('#category_id').val()+'"]').trigger('click');
    $('#category_id').closest('form').find('.dropdown li a[data-value="'+defaultCategoryId+'"]').parent().addClass('active');
    $('#category_id').val(defaultCategoryId);
    $('#category_id').trigger('change');
    $('#table_id').closest('form').find('.dropdown li a[data-value="'+defaultTableId+'"]').parent().addClass('active');
    $('#table_id').val(defaultTableId);
    $('#category_id').closest('form').find('#icb-selected-tag-show').html($('#category_id option[value="'+defaultCategoryId+'"]').eq(0).text());
    $('#table_id').closest('form').find('#icb-selected-tag-show').html($('#table_id option[value="'+defaultTableId+'"]').eq(0).text());
});
</script>
