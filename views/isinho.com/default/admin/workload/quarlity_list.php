<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
            <h3>
                <ul class="nav nav-tabs">
                    <li class="active">
                    <a href="<?php
                        echo ACTION=='index'?'#index" data-toggle="tab':'admin/books/index/'
                        ?>"><?php _e('质量考核'); ?></a>
                    </li>
                </ul>
            </h3>
        </div>

        <div class="mod-body tab-content">
            <div class="tab-pane active" id="index">
                <?php if ($_GET['action'] == 'search') { ?>
                <div class="alert alert-info"><?php _e('找到 %s 条符合条件的内容', intval($this->totalRows)); ?></div>
                <?php } ?>


                <div class="table-responsive">
                    <table class="table table-bordered workload-list">
                        <tr>
                         <td>列表颜色说明：</td>
                         <td class="success">质量考核记录中</td>
                         <td class="info">质量考核已核算</td>
                         <!-- <td class="sinho-red-background">有疑问数据，需确认重新提交</td> -->
                        </tr>
                    </table>
                </div>
                <br/>

                <div class="table-responsive">
                <?php if ($this->itemsList) { ?>

                    <table class="table table-striped px10 no-padding no-margin workload-list workload-fill-list">
                        <thead>
                            <tr>
                                <th class="text-left"><?php _e('日期'); ?></th>
                                <?php if (property_exists($this, 'userList')) { ?>
                                <th><?php _e('编辑'); ?></th>
                                <?php }?>
                                <th><?php _e('书稿类别'); ?></th>
                                <th><?php _e('系列'); ?></th>
                                <th><?php _e('书名'); ?></th>
                                <th><?php _e('校次'); ?></th>
                                <th><?php _e('类别'); ?></th>
                                <th class="red-right-border"><?php _e('遍次'); ?></th>
                                <th><?php _e('奖惩'); ?></th>
                                <th><?php _e('考核比例'); ?></th>
                                <th><?php _e('核算金额'); ?></th>
                                <th><?php _e('备注'); ?></th>
                                <th><?php _e('核算月份'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($this->itemsList AS $itemInfo) { ?>
                            <tr data-db-id="<?php echo $itemInfo['id']; ?>" data-book-id="<?php echo $itemInfo['book_id'];?>" class="workload-line<?php
                                if ($itemInfo['belong_month']>0) echo ' verified-line';
                                else echo ' recording-line';
                                ?>" >
                                <td class="text-left">
                                    <a class="md-tip"  title="<?php _e('日期'); echo $itemInfo['add_date'];?> " data-toggle="tooltip"><?php echo substr($itemInfo['add_date'], 5, 5); ?></a>
                                </td>
                                <?php if (property_exists($this, 'userList')) { ?>
                                <td><?php echo $this->userList[$itemInfo['user_id']]['user_name']; ?></td>
                                <?php }?>
                                <td class="js-category"><?php echo $this->booksList[$itemInfo['book_id']]['category']; ?></td>
                                <td class="js-serial"><?php echo $this->booksList[$itemInfo['book_id']]['serial']; ?></td>
                                <td class="js-bookname"><?php echo $this->booksList[$itemInfo['book_id']]['book_name']; ?></td>
                                <td class="js-proofreading-times"><?php echo $this->booksList[$itemInfo['book_id']]['proofreading_times']; ?></td>


                                <td data-td-name="category" class="js-allow-mark"><a><?php echo $itemInfo['category']; ?></a></td>
                                <td data-td-name="working_times" class="js-allow-mark red-right-border"><a><?php echo $itemInfo['working_times']; ?></a></td>
                                <td class="js-allow-mark">
                                <?php echo $itemInfo['good_or_bad'] == 1 ? '<a class="icon-good"></a>' : '<a class="icon-bad"></a>';?>
                                </td>
                                <td class=""><a><?php echo $itemInfo['rate_num']; ?>%</a></td>
                                <td data-td-name="payable_amount" class=""><a><?php echo round($this->workloadList[$itemInfo['workload_id']]['payable_amount'] * $itemInfo['rate_num'] / 100 * $itemInfo['good_or_bad'], 2); ?><?php //echo $itemInfo['payable_amount']; ?></a></td>
                                <!-- 存在js-allow-diff-book-mark, 允许跨书稿间计算单元格；js-can-not-compute表示单元格不可以参与计算 -->
                                <td data-td-name="remarks" class="js-allow-mark js-allow-diff-book-mark js-can-not-compute "><a><?php echo $itemInfo['remarks']; ?></a></td>
                                <td data-td-name="belong_month" class="js-can-not-compute "><a><?php echo $itemInfo['belong_month']; ?></a></td>

                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>
                </div>

                <div class="mod-table-foot">
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

<script type="text/javascript">


$(function(){
});
</script>

<?php View::output('admin/global/footer.php'); ?>
