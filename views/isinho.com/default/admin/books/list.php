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

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                         <td>列表颜色说明：</td>
                         <td class="success">已分配责编</td>
                         <td>未分配责编</td>
                         <td class="info">结算完成</td>
                         <td class="warning">部分结算</td>
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
                                <th><?php _e('书稿<br/>类别'); ?></th>
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
                                <th><?php _e('阶段'); ?></th>
                                <th style="white-space: nowrap;"><?php _e('操作'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($this->itemsList AS $itemInfo) { ?>
                            <tr class="<?php
                                      // 书稿未结算过
                                      if(! $this->booksWorkload[$itemInfo['id']]) {
                                          if ($this->booksWorkloadNotPayed[$itemInfo['id']]) { // 分配人员了
                                              echo 'success';
                                          } else { // 没有分配人员

                                          }

                                       // 书稿分配人， 没有填充数据的结算。 理论不应该有这样的数据
                                      } else if ($this->booksWorkload[$itemInfo['id']]['content_table_pages']==0 &&
                                            $this->booksWorkload[$itemInfo['id']]['text_pages']==0 &&
                                            $this->booksWorkload[$itemInfo['id']]['answer_pages']==0 &&
                                            $this->booksWorkload[$itemInfo['id']]['test_pages']==0 &&
                                            $this->booksWorkload[$itemInfo['id']]['test_answer_pages']==0 &&
                                            $this->booksWorkload[$itemInfo['id']]['exercise_pages']==0 &&
                                            $this->booksWorkload[$itemInfo['id']]['function_book']==0 &&
                                            $this->booksWorkload[$itemInfo['id']]['function_answer']==0) {
                                        echo 'book_is_payed danger';

                                      } else if($this->booksWorkload[$itemInfo['id']]['content_table_pages']>=$itemInfo['content_table_pages']
                                       &&       $this->booksWorkload[$itemInfo['id']]['text_pages']>=$itemInfo['text_pages']
                                       &&       $this->booksWorkload[$itemInfo['id']]['answer_pages']>=$itemInfo['answer_pages']
                                       &&       $this->booksWorkload[$itemInfo['id']]['test_pages']>=$itemInfo['test_pages']
                                       &&       $this->booksWorkload[$itemInfo['id']]['test_answer_pages']>=$itemInfo['test_answer_pages']
                                       &&       $this->booksWorkload[$itemInfo['id']]['exercise_pages']>=$itemInfo['exercise_pages']
                                       &&       $this->booksWorkload[$itemInfo['id']]['function_book']>=$itemInfo['function_book']
                                       &&       $this->booksWorkload[$itemInfo['id']]['function_answer']>=$itemInfo['function_answer']
                                       ) {
                                          echo 'book_is_payed info';
                                      } else {
                                          echo 'warning';
                                      }
                            ?>">
                                <td><input type="checkbox" name="ids[]" value="<?php echo $itemInfo['id']; ?>"></td>
                                <td class="text-left">

                                    <a class="md-tip"  title="<?php _e('发稿日期'); echo $itemInfo['delivery_date'];?> <?php _e('回稿日期'); echo $itemInfo['return_date'];?>" data-toggle="tooltip"><?php echo $itemInfo['delivery_date']; ?></a>
                                </td>
                                <td class="js-category"><?php echo $itemInfo['category']; ?></td>
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
                                <td><?php $_list=array('-','小学','初中','高中','外社','综合');echo $_list[$itemInfo['grade_level'] ]; ?></td>

                                <td style="white-space: nowrap;">
                                  <span href="admin/books/book/#id-<?php echo $itemInfo['id']; ?>" data-book-id="<?php echo $itemInfo['id']; ?>" class="icon icon-score jsToggleSubIcon">
                                    <a data-grade-level="1" title="<?php _e('设置书稿所属阶段：小学'); ?>" data-toggle="tooltip"  class="md-tip jsSinhoSetGradeLevel ft12">小学</a>
                                    <a data-grade-level="2" title="<?php _e('设置书稿所属阶段：初中'); ?>" data-toggle="tooltip"  class="md-tip jsSinhoSetGradeLevel ft12">初中</a>
                                    <a data-grade-level="3" title="<?php _e('设置书稿所属阶段：高中'); ?>" data-toggle="tooltip"  class="md-tip jsSinhoSetGradeLevel ft12">高中</a>
                                    <a data-grade-level="4" title="<?php _e('设置书稿所属阶段：外社'); ?>" data-toggle="tooltip"  class="md-tip jsSinhoSetGradeLevel ft12">外社</a>
                                    <a data-grade-level="5" title="<?php _e('设置书稿所属阶段：综合'); ?>" data-toggle="tooltip"  class="md-tip jsSinhoSetGradeLevel ft12">综合</a>
                                    <a data-grade-level="0" title="<?php _e('设置书稿所属阶段：其他'); ?>" data-toggle="tooltip"  class="md-tip jsSinhoSetGradeLevel ft12">其他</a>
                                  </span>
                                  <!-- <a href="admin/books/book/#id-<?php echo $itemInfo['id']; ?>" data-book-id="<?php echo $itemInfo['id']; ?>" class="icon icon-date md-tip jsSinhoSetBookDate" title="<?php _e('设置日期'); ?>" data-toggle="tooltip" data-delivery-date="<?php echo $itemInfo['delivery_date']; ?>" data-return-date="<?php echo $itemInfo['return_date']; ?>"></a> -->
                                  <a href="admin/books/book/from_id-<?php echo $itemInfo['id']; ?>" class="icon icon-cogs md-tip" title="<?php _e('书稿照抄'); ?>" data-toggle="tooltip"></a>
                                  <a href="admin/check_list/by-book__id-<?php echo $itemInfo['id']; ?>" class="icon icon-job md-tip" title="<?php _e('查看工作量'); ?>" data-toggle="tooltip"></a>
                                  <a href="admin/books/book/id-<?php echo $itemInfo['id']; ?>__url-<?php echo base64_encode($this->backUrl);?>" class="icon icon-edit md-tip" title="<?php _e('编辑'); ?>" data-toggle="tooltip"></a>
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

<style>
.jsToggleSubIcon{
    position: relative;
}
.jsToggleSubIcon .jsSinhoSetGradeLevel{
    position: absolute;
    left:-35px;
    display: none;
    background-color: #8bbf61;
    font-size: 11px !important;
    border-radius: 5px;
}
.jsToggleSubIcon.on .jsSinhoSetGradeLevel{
    display: inline-block;
}
.jsToggleSubIcon a[data-grade-level="1"] {
    top: -35px;
}
.jsToggleSubIcon a[data-grade-level="2"] {
    top: -15px;
}
.jsToggleSubIcon a[data-grade-level="3"] {
    top: 5px;
}
.jsToggleSubIcon a[data-grade-level="4"] {
    top: 25px;
}
.jsToggleSubIcon a[data-grade-level="5"] {
    top: 45px;
}
.jsToggleSubIcon a[data-grade-level="0"] {
    top: 65px;
}
</style>
<script>
$(function(){
    $('body').click(function (event) {
        if (! $(event.target).hasClass('jsToggleSubIcon') && ! $(event.target).hasClass('jsSinhoSetGradeLevel')) {
            $('.jsToggleSubIcon').removeClass('on');
        }
    });
    $('body').on('click', '.jsToggleSubIcon', function () {
        $('.jsToggleSubIcon').not(this).removeClass('on');
        $(this).toggleClass('on');
    });
    $('body').on('click', '.jsSinhoSetGradeLevel', function (event) {
        event.preventDefault();
        var bookId = $(this).parent().data('book-id');
        var gradeLevel = $(this).data('grade-level');

        ICB.modal.loading(true);
        var url = G_BASE_URL + '/admin/ajax/books/set_grade/';
        var params = {book_id : bookId, grade_level : gradeLevel};

        ICB.ajax.requestJson(
            url,
            params,
            function (response) {
                ICB.modal.loading(false);

                if (!response) {
                    ICB.modal.alert(_t('请求发生错误'));
                    return false;
                }

                if (response.errno === 0) {
                    window.location.reload();
                } else if (response.err) {
                    ICB.modal.alert(response.err);
                } else {
                    ICB.modal.alert(_t('请求发生错误'));
                }
            }
        );

    });

    /**
     * 点击批量删除按钮
     */
    $('#deleteBatchBtn').click(function () {
        if($('.icheckbox_square-blue.checked').length){
    	    ICB.domEvents.deleteShowConfirmModal(
            	   _t('确认删除？ 书稿工作量也会一起删除。 请通知责编！'),
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
        var subjectCode = '' + $(this).data('subject-code');
        var url = "admin/ajax/books/assigned/id"+"-"+bookId;
        var onshowCallback = function () {
            // 组装下拉列表需要的数据， 获取默认选择.
            $.each($('.modal-dialog .js_select_transform'), function () {
                // 根据科目代码，将编辑按照科目代码顺序。 让相同组的编辑，显示在一块
                var maxSubjectCode = <?php echo max(array_keys(SinhoBaseController::SUBJECT_LIST));?>;
                var minSubjectCode = <?php echo min(array_keys(SinhoBaseController::SUBJECT_LIST));?>;
                for (var i=minSubjectCode; i<=maxSubjectCode; i++) {
                    $('#template-assign-options').prepend($('#template-assign-options option[data-main_subject="'+i+'"]'));
                }
                // 按照文理科排序
                $('#template-assign-options').prepend($('#template-assign-options option[data-subject_category="1"]'));
                $('#template-assign-options').prepend($('#template-assign-options option[data-subject_category="0"]'));
                // 书稿能够识别出来科目，将具有对应科目的编辑，排在列表上面
                if (subjectCode) {
                    var $options = $('#template-assign-options option');
                    var moreSubjects;
                    // 先将具有副科能力的编辑， 排前面
                    for(var i=0; i<$options.length; i++) {
                        //console.info($options.eq(i).data('more_subject'),subjectCode,$options.eq(i).data('more_subject').indexOf(subjectCode));
                        if ($options.eq(i).data('more_subject').indexOf(subjectCode)>-1) {
                            //console.info($options.eq(i));
                            $('#template-assign-options').prepend($options.eq(i));
                        }
                    }
                    // 最后将主科编辑排在前面
                    $('#template-assign-options').prepend($('#template-assign-options option[data-main_subject="'+subjectCode+'"]'));
                }
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
