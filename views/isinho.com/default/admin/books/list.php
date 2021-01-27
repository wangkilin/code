<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
            <h3>
            <?php View::output('admin/books/nav_inc.php');?>
            </h3>
        </div>

        <div class="mod-body tab-content">
            <div class="tab-pane active" id="index">
                <?php if ($_GET['action'] == 'search') { ?>
                <div class="alert alert-info"><?php _e('找到 %s 条符合条件的内容', intval($this->totalRows)); ?></div>
                <?php } ?>

                <div class="table-responsive">
                <form id="batchs_form" action="admin/ajax/books/remove/" method="post">
                    <input type="hidden" id="action" name="action" value="" />
                <?php if ($this->itemsList) { ?>

                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th><input type="checkbox" class="check-all"></th>
                                <th class="text-left"><?php _e('日期'); ?></th>
                                <th><?php _e('系列'); ?></th>
                                <th><?php _e('书名'); ?></th>
                                <th><?php _e('校次'); ?></th>
                                <th><?php _e('目录'); ?></th>
                                <th><?php _e('正文'); ?></th>
                                <th><?php _e('答案'); ?></th>
                                <th><?php _e('试卷'); ?></th>
                                <th><?php _e('试卷<br/>答案'); ?></th>
                                <th><?php _e('课后<br/>作业'); ?></th>
                                <th><?php _e('功能册'); ?></th>
                                <th><?php _e('功能册<br/>答案'); ?></th>
                                <th><?php _e('系数'); ?></th>
                                <th><?php _e('字数'); ?></th>
                                <th style="width:120px;"><?php _e('操作'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($this->itemsList AS $itemInfo) { ?>
                            <tr class="<?php if($itemInfo['is_payed']==1) {echo 'book_is_payed success';} ?>">
                                <td><input type="checkbox" name="ids[]" value="<?php echo $itemInfo['id']; ?>"></td>
                                <td class="text-left">

                                    <a class="md-tip"  title="<?php _e('发稿日期'); echo $itemInfo['delivery_date'];?> <?php _e('回稿日期'); echo $itemInfo['return_date'];?>" data-toggle="tooltip"><?php echo ltrim(substr($itemInfo['delivery_date'], 5),'0'),'~',ltrim(substr($itemInfo['return_date'], 5), '0'); ?></a>
                                </td>
                                <td class="js-serial"><?php echo $itemInfo['serial']; ?></td>
                                <td class="js-bookname"><?php echo $itemInfo['book_name']; ?></td>
                                <td class="js-proofreading-times"><?php echo $itemInfo['proofreading_times']; ?></td>
                                <td><?php echo $itemInfo['content_table_pages']; ?></td>
                                <td><?php echo $itemInfo['text_pages']; ?></td>
                                <td><?php echo $itemInfo['answer_pages']; ?></td>
                                <td><?php echo $itemInfo['test_pages']; ?></td>
                                <td><?php echo $itemInfo['test_answer_pages']; ?></td>
                                <td><?php echo $itemInfo['exercise_pages']; ?></td>
                                <td><?php echo $itemInfo['function_book']; ?></td>
                                <td><?php echo $itemInfo['function_answer']; ?></td>
                                <td><?php echo $itemInfo['weight']; ?></td>
                                <td><?php echo doubleval($itemInfo['total_chars']); ?></td>

                                <td>
                                  <a href="admin/books/book/#id-<?php echo $itemInfo['id']; ?>" data-book-id="<?php echo $itemInfo['id']; ?>" class="icon icon-date md-tip jsSinhoSetBookDate" title="<?php _e('设置日期'); ?>" data-toggle="tooltip" data-delivery-date="<?php echo $itemInfo['delivery_date']; ?>" data-return-date="<?php echo $itemInfo['return_date']; ?>"></a>
                                  <a href="admin/books/book/id-<?php echo $itemInfo['id']; ?>" class="icon icon-edit md-tip" title="<?php _e('编辑'); ?>" data-toggle="tooltip"></a>
                                  <a href="admin/books/book/#id-<?php echo $itemInfo['id']; ?>" data-book-id="<?php echo $itemInfo['id']; ?>" class="icon icon-users md-tip jsAssign" title="<?php _e('分派'); ?>" data-toggle="tooltip"></a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>
                </form>
                </div>

                <div class="mod-table-foot">
                    <?php echo $this->pagination; ?>

                    <a class="btn btn-danger" id="deleteBatchBtn"><?php _e('删除书稿'); ?></a>
                </div>
            </div>
            <?php View::output('admin/books/search_inc.php');?>

        </div>
    </div>
</div>
<div id="template-assign-options" style="display:none;">
<?php echo $this->itemOptions;?>
</div>

<script>
$(function(){
    /**
     * 点击批量删除按钮
     */
    $('#deleteBatchBtn').click(function () {
        if($('.icheckbox_square-blue.checked').length){
    	    ICB.domEvents.deleteShowConfirmModal(
            	   _t('确认删除？'),
            	   function(){
        	           $('#action').val('remove');
        	           ICB.ajax.postForm($('#batchs_form'));
        	       }
            );
        } else {
            ICB.modal.alert(_t('请勾选书稿'));
        }
    	 return false;;
    });

    /**
     * 分派责编
     */
    $('.jsAssign').click(function() {
        var bookId = $(this).data('book-id');
        var url = "admin/ajax/books/assigned/id"+"-"+bookId;
        var onshowCallback = function () {
            // 组装下拉列表需要的数据， 获取默认选择.
            $.each($('.modal-dialog .js_select_transform'), function () {
                $("#sinho_editor").html($('#template-assign-options').html());
                $.ajax({
                    url:url,
                    async : false,
                    data:{id:bookId},
                    dataType : 'json',
                    success: function (data) {
                        if (! data.rsm ||  !data.rsm.data.length) {
                            if (data.err) {

                                ICB.modal.alert(data.err);
                            }
                            return;
                        }

                        for(var _i in data.rsm.data) {
                            console.info(data.rsm.data[_i].user_id);
                            $('#sinho_editor>option[value="'+data.rsm.data[_i].user_id+'"]').attr('selected', 'selected');
                        }
                    }
                });
                $("#sinho_editor").multiselect({
        			nonSelectedText : '<?php _e('---- 选择责编 ----');?>',
                    maxHeight       : 200,
                    buttonWidth     : 400,
                    allSelectedText : '<?php _e('已选择所有人');?>',
                    numberDisplayed : 7, // 选择框最多提示选择多少个人名
        		});
            });

            $('#js-submit-assign').click(function() {
                ICB.ajax.requestJson($(this).closest('form').attr('action'), $(this).closest('form').serialize());
            });
        };
        var html = Hogan.compile(ICB.template.sinhoBindBookWithEditor).render(
            {
                book_id     : $(this).data('book-id'),
                serial      : $(this).closest('tr').find('.js-serial').text(),
                book_name   : $(this).closest('tr').find('.js-bookname').text(),
                proofreading_times: $(this).closest('tr').find('.js-proofreading-times').text(),
            });
        ICB.modal.dialog(html, onshowCallback);

        return false;
    });

    /**
     * 设置书稿日期
     */
    $('.jsSinhoSetBookDate').click(function() {
        var bookId = $(this).data('book-id');
        var url = "admin/ajax/books/set_date/id"+"-"+bookId;
        var deliveryDate = $(this).data('delivery-date');
        var returnDate   = $(this).data('return-date');
        var onshowCallback = function () {
            //$('.js-datepicker').date_input(); // 已有日期输入。 后台管理首页，有示例

            $( ".js-datepicker" ).datetimepicker({
                format  : 'yyyy-mm-dd',
                language:  'zh-CN',
                weekStart: 1, // 星期一 为一周开始
                todayBtn:  1, // 显示今日按钮
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                forceParse: 0,
                minView : 2, // 0:选择到分钟， 1：选择到小时， 2：选择到天
            });

            $('#js-submit-book-date').click(function() {
                ICB.ajax.requestJson($(this).closest('form').attr('action'), $(this).closest('form').serialize());
            });
        };
        var html = Hogan.compile(ICB.template.sinhoSetBookDate).render(
            {
                book_id             : $(this).data('book-id'),
                serial              : $(this).closest('tr').find('.js-serial').text(),
                book_name           : $(this).closest('tr').find('.js-bookname').text(),
                proofreading_times  : $(this).closest('tr').find('.js-proofreading-times').text(),
                delivery_date       : deliveryDate,
                return_date         : returnDate
            });
        ICB.modal.dialog(html, onshowCallback);

        return false;
    });
});
</script>

<?php View::output('admin/global/footer.php'); ?>
