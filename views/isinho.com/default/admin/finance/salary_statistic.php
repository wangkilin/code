<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
        <?php View::output('admin/finance/salary_inc.php');?>
        </div>

        <div class="mod-body tab-content">
                <div class="row" style="height:30px;">

                    <div style="width:900px;">
                        <form action="/admin/finance/salary_statistic/" method="get" id="item_form">
                            <div class="col-sm-5 col-xs-5 mod-double">
                                <div class="col-sm-6 col-xs-6 mod-double icon-date-container">
                                        <input type="text" name="start_month" class="form-control icon-indent js-date-input js-monthpicker" value="<?php echo substr($this->startMonth, 0, 4) . '-' . substr($this->startMonth, -2);?>">
                                        <i class="icon icon-date"></i>
                                        <i class="icon icon-date-delete icon-delete"></i>
                                    </div>
                                <span class="mod-symbol col-xs-1 col-sm-1">
                                    -
                                </span>
                                    <div class="col-sm-6 col-xs-6 mod-double icon-date-container">
                                        <input type="text" name="end_month" class="form-control icon-indent js-date-input js-monthpicker" value="<?php echo substr($this->endMonth, 0, 4) . '-' . substr($this->endMonth, -2);?>">
                                        <i class="icon icon-date"></i>
                                        <i class="icon icon-date-delete icon-delete"></i>
                                </div>
                            </div>

                            <div class="col-sm-6 col-xs-6">
                                <select id="sinho_editor" name="user_ids[]" multiple><?php echo $this->itemOptions;?></select>
                            </div>
                            <div class="col-sm-1 col-xs-1">
                                <input id="js-submit" class="btn btn-primary" type="submit" value="<?php _e('确 定');?>"/>
                            </div>
					    </form>
                    </div>
                </div>
            <div class="row">
                <div class="col-md-12 form-group">
                    <table  class="table table-striped" id="total-chars-list">
                        <thead>
                            <tr>
                                <th>月份    </th>
                                <th>人数    </th>
                                <th>公司全部</th>
                                <th>实发工资</th>
                                <th>个人五险</th>
                                <th>公司五险</th>
                                <th>平均工资</th>
                                <th>工会经费</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i=1; foreach ($this->statData as $_yearMonth => $_itemInfo) { ?>
                            <tr>
                                <td><?php echo $_yearMonth;?></td>
                                <td><?php echo $_itemInfo['staff_total']; ?></td>
                                <td><?php echo round($_itemInfo['gongsi_quanbu'], 2); ?></td>
                                <td><?php echo round($_itemInfo['shifa_gongzi'], 2); ?></td>
                                <td><?php echo round($_itemInfo['geren_heji'], 2);?></td>
                                <td><?php echo round($_itemInfo['gongsi_heji'], 2);?></td>
                                <td><?php echo round($_itemInfo['pingjun_gongzi'], 2);?></td>
                                <td><?php echo round($_itemInfo['gonghuijingfei'], 2);?></td>
                            </tr>
                            <?php }?>
                        </tbody>
                        <tfoot>
                            <tr class="bg-primary text-center ">
                                <td>合计</td>
                                <td><?php echo       array_sum(array_column($this->statData, 'staff_total')); ?></td>
                                <td><?php echo round(array_sum(array_column($this->statData, 'gongsi_quanbu')), 2); ?></td>
                                <td><?php echo round(array_sum(array_column($this->statData, 'shifa_gongzi')), 2); ?></td>
                                <td><?php echo round(array_sum(array_column($this->statData, 'geren_heji')), 2);?></td>
                                <td><?php echo round(array_sum(array_column($this->statData, 'gongsi_heji')), 2);?></td>
                                <td><?php echo round(array_sum(array_column($this->statData, 'pingjun_gongzi')), 2);?></td>
                                <td><?php echo round(array_sum(array_column($this->statData, 'gonghuijingfei')), 2);?></td>
                            </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>



    </div>
</div>
<script type="text/javascript">
$(function () {


    /**
     * 日期输入框， 点击清除图标，将输入框内容清除
     */
    $('.icon-delete.icon-date-delete').click (function () {
        $(this).siblings('.js-date-input').val('');
    });

    // 月份输入框
    $( ".js-monthpicker" ).datetimepicker({
                format  : 'yyyy-mm',
                language:  'zh-CN',
                weekStart: 1, // 星期一 为一周开始
                todayBtn:  1, // 显示今日按钮
                autoclose: 1,
                todayHighlight: 1,
                startView: 3, // 显示的日期级别： 0:到分钟， 1：到小时， 2：到天
                forceParse: 0,
                minView : 3, // 0:选择到分钟， 1：选择到小时， 2：选择到天
            });



    // 如果 select 标签有 multiple属性，就会生成多选。
        $('#sinho_editor').multiselect({
            nonSelectedText : '<?php _e('---- 选择人员 ----');?>',
            maxHeight       : 200,
            buttonWidth     : 400,
            allSelectedText : '<?php _e('已选择全部人员');?>',
            numberDisplayed : 4, // 选择框最多提示选择多少个选项
        });


});

</script>
<?php View::output('admin/global/footer.php'); ?>
