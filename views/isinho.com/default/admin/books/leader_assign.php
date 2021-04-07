<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
            <h3>
                <ul class="nav nav-tabs">
                    <li class="active"><a  data-toggle="tab"><?php _e('书稿列表'); ?></a></li>
                </ul>
            </h3>
        </div>

        <div class="mod-body tab-content">
            <div class="tab-pane active" id="index">

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                         <td>列表颜色说明：</td>
                         <td class="success">已分配责编</td>
                         <td>未分配责编</td>
                         <td class="info">结算完成</td>
                        </tr>
                    </table>
                </div>
                <br/>

                <?php if ($_GET['action'] == 'search') { ?>
                <div class="alert alert-info"><?php _e('找到 %s 条符合条件的内容', intval($this->totalRows)); ?></div>
                <?php } ?>

                <div class="table-responsive">
                <form id="batchs_form" action="admin/ajax/books/remove/" method="post">
                    <input type="hidden" id="action" name="action" value="" />
                <?php if ($this->itemsList) { ?>

                    <table class="table table-hover book-list">
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
                                <th><?php _e('备注'); ?></th>
                                <th style="white-space: nowrap;"><?php _e('操作'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($this->itemsList AS $itemInfo) { ?>
                            <tr class="<?php
                                    if($this->booksWorkload[$itemInfo['id']]['content_table_pages']>=$itemInfo['content_table_pages']
                                       &&       $this->booksWorkload[$itemInfo['id']]['text_pages']>=$itemInfo['text_pages']
                                       &&       $this->booksWorkload[$itemInfo['id']]['answer_pages']>=$itemInfo['answer_pages']
                                       &&       $this->booksWorkload[$itemInfo['id']]['test_pages']>=$itemInfo['test_pages']
                                       &&       $this->booksWorkload[$itemInfo['id']]['test_answer_pages']>=$itemInfo['test_answer_pages']
                                       &&       $this->booksWorkload[$itemInfo['id']]['exercise_pages']>=$itemInfo['exercise_pages']
                                       &&       $this->booksWorkload[$itemInfo['id']]['function_book']>=$itemInfo['function_book']
                                       &&       $this->booksWorkload[$itemInfo['id']]['function_answer']>=$itemInfo['function_answer']
                                       ) {
                                          echo 'book_is_payed info';
                                    } else if (count($this->booksWorkloadAll[$itemInfo['id']])==1 ) {
                                        echo '';
                                    } else {
                                        echo 'success';
                                    }
                            ?>">
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
                                <td><?php echo $itemInfo['remarks']; ?></td>

                                <td style="white-space: nowrap;">
                                  <a href="admin/books/book/#id-<?php echo $itemInfo['id']; ?>" data-subject-code="<?php echo $itemInfo['subject_code'];?>" data-book-id="<?php echo $itemInfo['id']; ?>" class="icon icon-users md-tip jsAssign" title="<?php _e('分派'); ?>" data-toggle="tooltip"></a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>
                </form>
                </div>

                <div class="mod-table-foot">
                    <div class="text-right">每页<?php echo $this->amountPerPage; ?> &nbsp; 共<?php echo $this->totalRows;?>本</div>
                    <?php echo $this->pagination; ?>

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
                // 复选框变形
                $("#sinho_editor").multiselect({
        			nonSelectedText : '<?php _e('---- 选择责编 ----');?>',
                    maxHeight       : 200,
                    buttonWidth     : 300,
                    allSelectedText : '<?php _e('已选择所有人');?>',
                    numberDisplayed : 7, // 选择框最多提示选择多少个人名
        		});
            });
            // 分配编辑
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

});
</script>

<?php View::output('admin/global/footer.php'); ?>
