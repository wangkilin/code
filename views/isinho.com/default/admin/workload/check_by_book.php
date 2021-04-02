

        <div class="mod-body tab-content padding5px">
            <div class="tab-pane active" id="index">
                <?php if ($_GET['action'] == 'search') { ?>
                <div class="alert alert-info"><?php _e('找到 %s 条符合条件的内容', intval($this->totalRows)); ?></div>
                <?php } ?>

                <div class="table-responsive">
                    <table class="table table-bordered workload-list">
                        <tr>
                         <td>列表颜色说明：</td>
                         <!-- <td class="success">工作量记录中，没有提交到核算</td> -->
                         <td class="info">工作量结算完成，绩效已支付</td>
                         <td class="warning">工作量正在核算，绩效正在核算</td>
                         <td class="success">记录中的工作量，待提交核算</td>
                        </tr>
                    </table>
                </div>
                <br/>

                <div class="table-responsive">
                <form id="workload_verify_form" action="admin/ajax/workload/confirm/" method="post">
                    <input type="hidden" id="action" name="action" value="" />
                <?php if ($this->itemsList) { ?>

                    <table class="table table-striped px10 no-padding no-margin workload-list">
                        <thead>
                            <tr>
                                <th class="text-left"><?php _e('日期'); ?></th>
                                <th><?php _e('责编'); ?></th>
                                <th><?php _e('系列'); ?></th>
                                <th><?php _e('书名'); ?></th>
                                <th><?php _e('校次'); ?></th>
                                <th><?php _e('类别'); ?></th>
                                <th><?php _e('遍次'); ?></th>
                                <th><?php _e('目录'); ?></th>
                                <th><?php _e('正文'); ?></th>
                                <th class="red-right-border"><?php _e('千字/页'); ?></th>
                                <th><?php _e('答案'); ?></th>
                                <!-- <th class="red-right-border"><?php _e('千字/页'); ?></th> -->
                                <th><?php _e('试卷'); ?></th>
                                <!-- <th class="red-right-border"><?php _e('千字/页'); ?></th> -->
                                <th><?php _e('试卷<br/>答案'); ?></th>
                                <!-- <th class="red-right-border"><?php _e('千字/页'); ?></th> -->
                                <th><?php _e('课后<br/>作业'); ?></th>
                                <!-- <th class="red-right-border"><?php _e('千字/页'); ?></th> -->
                                <th><?php _e('功能册'); ?></th>
                                <!-- <th class="red-right-border"><?php _e('千字/页'); ?></th> -->
                                <th><?php _e('功能册<br/>答案'); ?></th>
                                <!-- <th class="red-right-border"><?php _e('千字/页'); ?></th> -->
                                <th><?php _e('系数'); ?></th>
                                <th><?php _e('核算总<br/>字数(千)'); ?></th>
                                <th><?php _e('应发<br/>金额'); ?></th>
                                <th><?php _e('备注'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($this->itemsList AS $itemInfo) { $bookInfo = $itemInfo; ?>
                            <tr data-book-id="<?php echo $itemInfo['id'];?>" class="book-line">
                                <td class="text-left">

                                    <a class="md-tip"  title="<?php _e('发稿日期'); echo $itemInfo['delivery_date'];?> <?php _e('回稿日期'); echo $itemInfo['return_date'];?>" data-toggle="tooltip"><?php echo substr($itemInfo['delivery_date'], 5),'~',substr($itemInfo['return_date'], 5); ?></a>
                                </td>
                                <td><?php echo $this->userList[$itemInfo['user_id']]['user_name']; ?></td>
                                <td class="js-serial"><?php echo $itemInfo['serial']; ?></td>
                                <td class="js-bookname"><?php echo $itemInfo['book_name']; ?></td>
                                <td class="js-proofreading-times"><?php echo $itemInfo['proofreading_times']; ?></td>
                                <td><?php echo $itemInfo['category']; ?></td>
                                <td><?php echo $itemInfo['working_times']; ?></td>
                                <td><?php echo $itemInfo['content_table_pages']; ?></td>
                                <td><?php echo $itemInfo['text_pages']; ?></td>
                                <td class="red-right-border"><?php echo $itemInfo['text_table_chars_per_page']; ?></td>
                                <td><?php echo $itemInfo['answer_pages']; ?></td>
                                <!-- <td class="red-right-border"><?php echo $itemInfo['answer_chars_per_page']; ?></td> -->
                                <td><?php echo $itemInfo['test_pages']; ?></td>
                                <!-- <td class="red-right-border"><?php echo $itemInfo['test_chars_per_page']; ?></td> -->
                                <td><?php echo $itemInfo['test_answer_pages']; ?></td>
                                <!-- <td class="red-right-border"><?php echo $itemInfo['test_answer_chars_per_page']; ?></td> -->
                                <td><?php echo $itemInfo['exercise_pages']; ?></td>
                                <!-- <td class="red-right-border"><?php echo $itemInfo['exercise_chars_per_page']; ?></td> -->
                                <td><?php echo $itemInfo['function_book']; ?></td>
                                <!-- <td class="red-right-border"><?php echo $itemInfo['function_book_chars_per_page']; ?></td> -->
                                <td><?php echo $itemInfo['function_answer']; ?></td>
                                <!-- <td class="red-right-border"><?php echo $itemInfo['function_answer_chars_per_page']; ?></td> -->
                                <td><?php echo $itemInfo['weight']; ?></td>
                                <td><?php echo $itemInfo['total_chars']; ?></td>
                                <td>&nbsp;</td>
                                <!-- <td><?php echo $itemInfo['remarks']; ?></td> -->

                                <td>
                                    <a target="_blank" href="admin/books/book/from_id-<?php echo $itemInfo['id']; ?>" class="icon icon-cogs md-tip" title="<?php _e('书稿照抄'); ?>" data-toggle="tooltip"></a>
                                    <a target="_blank" href="admin/books/book/id-<?php echo $itemInfo['id']; ?>" class="icon icon-edit md-tip" title="<?php _e('编辑'); ?>" data-toggle="tooltip"></a>
                                </td>
                            </tr>
                            <?php if (isset($this->workloadList[$itemInfo['id']])) { ?>

                                <?php foreach ($this->workloadList[$itemInfo['id']] AS $workloadInfo) { ?>
                            <tr data-db-id="<?php echo $workloadInfo['id']; ?>" data-book-id="<?php echo $itemInfo['id'];?>" class="workload-line<?php echo $workloadInfo['status']==1 ? ' verified-line': ($workloadInfo['status']==3 ? ' recording-line' : ' verifying-line'); ?>" data-verify-remark='<?php echo $workloadInfo['verify_remark'];?>'>
                                <td class="text-left">
                                    <input type="hidden" name="id[]" value="<?php echo $workloadInfo['id']; ?>"/>
                                    <?php
                                    if ($workloadInfo['add_time']) {
                                        echo date('m-d', $workloadInfo['add_time']);
                                    } else {
                                        echo substr($itemInfo['delivery_date'], 5);
                                    }
                                    echo '~';
                                    if ($workloadInfo['fill_time']) {
                                        echo date('m-d', $workloadInfo['fill_time']);
                                    } else {
                                        substr($itemInfo['return_date'], 5);
                                    }
                                    ?>
                                </td>
                                <td class="no-word-break"><?php echo $this->userList[$workloadInfo['user_id']]['user_name']; ?></td>
                                <td class="js-serial"><?php echo $itemInfo['serial']; ?></td>
                                <td class="js-bookname"><?php echo $itemInfo['book_name']; ?></td>
                                <td class="js-proofreading-times"><?php echo $itemInfo['proofreading_times']; ?></td>
                                <td data-td-name="category" class="js-allow-mark"><a><?php echo $workloadInfo['category']; ?></a></td>
                                <td data-td-name="working_times" class="js-allow-mark"><a><?php echo $workloadInfo['working_times']; ?></a></td>
                                <td data-td-name="content_table_pages" class="js-allow-mark"><a><?php echo $workloadInfo['content_table_pages']; ?></a></td>
                                <td data-td-name="text_pages" class="js-allow-mark"><a><?php echo $workloadInfo['text_pages']; ?></a></td>
                                <td data-td-name="text_table_chars_per_page" class="js-allow-mark red-right-border"><a><?php echo $workloadInfo['text_table_chars_per_page']; ?></a></td>
                                <td data-td-name="answer_pages" class="js-allow-mark"><a><?php echo $workloadInfo['answer_pages']; ?></a></td>
                                <!-- <td data-td-name="answer_chars_per_page" class="js-allow-mark red-right-border"><a><?php echo $workloadInfo['answer_chars_per_page']; ?></a></td> -->
                                <td data-td-name="test_pages" class="js-allow-mark"><a><?php echo $workloadInfo['test_pages']; ?></a></td>
                                <!-- <td data-td-name="test_chars_per_page" class="js-allow-mark red-right-border"><a><?php echo $workloadInfo['test_chars_per_page']; ?></a></td> -->
                                <td data-td-name="test_answer_pages" class="js-allow-mark"><a><?php echo $workloadInfo['test_answer_pages']; ?></a></td>
                                <!-- <td data-td-name="test_answer_chars_per_page" class="js-allow-mark red-right-border"><a><?php echo $workloadInfo['test_answer_chars_per_page']; ?></a></td> -->
                                <td data-td-name="exercise_pages" class="js-allow-mark"><a><?php echo $workloadInfo['exercise_pages']; ?></a></td>
                                <!-- <td data-td-name="exercise_chars_per_page" class="js-allow-mark red-right-border"><a><?php echo $workloadInfo['exercise_chars_per_page']; ?></a></td> -->
                                <td data-td-name="function_book" class="js-allow-mark"><a><?php echo $workloadInfo['function_book']; ?></a></td>
                                <!-- <td data-td-name="function_book_chars_per_page" class="js-allow-mark red-right-border"><a><?php echo $workloadInfo['function_book_chars_per_page']; ?></a></td> -->
                                <td data-td-name="function_answer" class="js-allow-mark"><a><?php echo $workloadInfo['function_answer']; ?></a></td>
                                <!-- <td data-td-name="function_answer_chars_per_page" class="js-allow-mark red-right-border"><a><?php echo $workloadInfo['function_answer_chars_per_page']; ?></a></td> -->
                                <td data-td-name="weight" class="js-allow-mark"><a><?php echo $workloadInfo['weight']; ?></a></td>
                                <td data-td-name="total_chars" class=""><a><?php echo $workloadInfo['total_chars']; ?></a></td>
                                <td data-td-name="payable_amount" class=""><a><?php echo $workloadInfo['payable_amount']; ?></a></td>
                                <!-- 存在js-allow-diff-book-mark, 允许跨书稿间计算单元格；js-can-not-compute表示单元格不可以参与计算 -->
                                <td data-td-name="remarks" class="js-allow-mark js-allow-diff-book-mark js-can-not-compute"><a><?php echo $workloadInfo['remarks']; ?></a></td>
                            </tr>
                                <?php } ?>
                            <?php } //end if ?>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>
                </form>
                </div>

                <div class="mod-table-foot">
                <?php if (isset($_GET['id'])) {
                        if ($bookInfo) {
                ?>
                    <div class="text-right"><span class="bg-warning">当前书稿：<?php echo $bookInfo['serial'], ' ', $bookInfo['book_name'], ' ', $bookInfo['proofreading_times']; ?> </span></div>
                <?php
                        }
                } else { ?>
                    <div class="text-right">每页<?php echo $this->amountPerPage; ?> &nbsp; 共<?php echo $this->totalRows;?>本</div>
                    <?php echo $this->pagination; ?>
                <?php } ?>
                </div>
            </div>

        </div>

<script type="text/javascript">
$(function(){
});
</script>

