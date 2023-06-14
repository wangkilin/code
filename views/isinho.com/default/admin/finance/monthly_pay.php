<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
            <?php View::output('admin/finance/monthly_pay_inc.php');?>
        </div>

        <div class="table-responsive mod-body tab-content">
            <div class="tab-pane active" id="monthly_pay">
                <div class="row" style="height:30px;">

                    <div style="width:900px;">
                        <form action="/admin/finance/monthly_pay/" method="get" id="item_form">
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
                            <div class="col-sm-1 col-xs-1">
                                <input id="js-submit" class="btn btn-primary" type="submit" value="<?php _e('确 定');?>"/>
                            </div>
					    </form>
                    </div>
                </div>
                <br/><br/>

                <div class="table-responsive">
                    <table class="table" cellpadding="0" cellspacing="0" style='border-collapse:collapse;width:100%;'>
                        <tr >
                            <td class="xl66" height="33" colspan="7">沈阳编校基地<?php echo date('Y.n.1', strtotime($this->startMonth.'01'));?>-<?php echo date('Y.n.t', strtotime($this->endMonth.'01'));?>支出表</td>
                        </tr>
                        <tr >
                            <td class="xl68" >序号</td>
                            <td class="xl68" >项目</td>
                            <td class="xl68" >单价</td>
                            <td class="xl68" >数量</td>
                            <td class="xl68" >总价</td>
                            <td class="xl68" >发票日期(无发票的为交易日期)</td>
                            <td class="xl68" >备注</td>
                        </tr>
                        <?php

                        $i = 1;
                        foreach ($this->outputItemList as & $_itemInfo) {
                            $_itemInfo['total'] = $_itemInfo['price']*$_itemInfo['amount'];
                        ?>
                        <tr <?php if ($_itemInfo['has_receipt'])  echo 'class="bg-info"'; ?>>
                            <td class="xl70" ><?php echo $i++;?></td>
                            <td class="xl70" ><?php echo $_itemInfo['item_name'];?></td>
                            <td class="xl70" ><?php echo $_itemInfo['price'];?></td>
                            <td class="xl70" ><?php echo $_itemInfo['amount'];?></td>
                            <td class="xl70" ><?php echo $_itemInfo['total'];?></td>
                            <td class="xl70" ><?php echo $_itemInfo['deal_date'];?></td>
                            <td class="xl70" ><?php echo $_itemInfo['remark'];?></td>
                        </tr>
                        <?php } ?>
                        <tr >
                            <td class="xl70" >合计</td>
                            <td class="xl70"></td>
                            <td class="xl70"></td>
                            <td class="xl70"></td>
                            <td class="xl70" style="word-break: normal;"><?php echo array_sum(array_column($this->outputItemList, 'total')) ;?></td>
                            <td class="xl70"></td>
                            <td class="xl70"></td>
                        </tr>
                    </table>
                    <br/>
                    <table class="table" cellpadding="0" cellspacing="0" style='border-collapse:collapse;width:100%;'>
                        <tr height="33">
                            <td class="xl66" height="33" colspan="5">沈阳编校基地<?php echo date('Y.n.1', strtotime($this->startMonth.'01'));?>-<?php echo date('Y.n.t', strtotime($this->endMonth.'01'));?>收入表</td>
                        </tr>
                        <tr >
                            <td class="xl68" >序号</td>
                            <td class="xl68" >公司/机构</td>
                            <td class="xl68" >总价</td>
                            <td class="xl68" >日期</td>
                            <td class="xl68" >备注</td>
                        </tr>
                        <?php

                        $i = 1;
                        foreach ($this->incomeItemList as & $_itemInfo) {
                            $_itemInfo['total'] = $_itemInfo['price']*$_itemInfo['amount'];
                        ?>
                        <tr <?php if ($_itemInfo['has_receipt'])  echo 'class=""'; ?>>
                            <td class="xl70" ><?php echo $i++;?></td>
                            <td class="xl70" ><?php echo $_itemInfo['item_name'];?></td>
                            <td class="xl70" ><?php echo $_itemInfo['total'];?></td>
                            <td class="xl70" ><?php echo $_itemInfo['deal_date'];?></td>
                            <td class="xl70" ><?php echo $_itemInfo['remark'];?></td>
                        </tr>
                        <?php } ?>
                        <tr >
                            <td class="xl70" >合计</td>
                            <td class="xl70"></td>
                            <td class="xl70" style="word-break: normal;"><?php echo array_sum(array_column($this->incomeItemList, 'total')) ;?></td>
                            <td class="xl70"></td>
                            <td class="xl70"></td>
                        </tr>
                    </table>
                    </table>
                    <br/>
                    <table class="table" cellpadding="0" cellspacing="0" style='border-collapse:collapse;width:100%;'>
                        <tr height="16.80" style='height:16.80pt;'>
                            <td class="xl66" colspan="5" style='border-right:.5pt solid windowtext;border-bottom:.5pt solid windowtext;' x:str>本期账户情况</td>
                        </tr>
                        <tr height="16.80" >
                            <td class="xl68">项目</td>
                            <td class="xl68">期初结余</td>
                            <td class="xl68">本期收入</td>
                            <td class="xl68">本期支出</td>
                            <td class="xl68">账户余额</td>
                        </tr>
                        <tr height="16.80" style='height:16.80pt;'>
                            <td class="xl70" >金额</td>
                            <td class="xl70"><?php echo $this->beginningValue;?></td>
                            <td class="xl70"><?php echo array_sum(array_column($this->incomeItemList, 'total')) ;?></td>
                            <td class="xl70"><?php echo array_sum(array_column($this->outputItemList, 'total')) ;?></td>
                            <td class="xl70"><?php
                                echo floatval($this->beginningValue) + array_sum(array_column($this->incomeItemList, 'total'))
                                    - array_sum(array_column($this->outputItemList, 'total'));
                            ?></td>
                        </tr>
                        <tr height="16.80">
                            <td colspan="5"></td>
                        </tr>
                        <tr height="16.80">
                            <td colspan="5">注：账户余额=期初结余 +本期收入 －本期支出</td>
                        </tr>
                    </table>


                    <br/>
                <br/>

                <?php if ($this->excelData) {
                ?>
                <div class="bg-primary">
                    <?php
                    echo $_monthlyData['belong_year_month'] . '收入支出 原始Excel数据';
                    ?>
                </div>
                <div>
                    <h3 class="nomargin">
                        <ul class="nav nav-tabs">
                            <?php
                                $_tmpI = 1;
                                foreach ($this->excelData as $_monthlyData) {
                            ?>
                            <li class="<?php if ($_tmpI++ == 1) echo 'active';?>">
                            <a href="#tab_id_<?php echo $_monthlyData['belong_year_month'];?>" data-toggle="tab"><?php echo $_monthlyData['belong_year_month'];?></a>
                            </li>
                            <?php }?>
                        </ul>
                    </h3>
                </div>
                <div class="mod-body tab-content">
                <?php
                $_tmpI = 1;
                    foreach ($this->excelData as $_monthlyData) {
                ?>
                <div id="<?php echo 'tab_id_'.$_monthlyData['belong_year_month'];?>" class="tab-content tab-pane <?php if ($_tmpI++ == 1) echo 'active';?>" >

                    <div>
                        <h3 class="nomargin">
                            <ul class="nav nav-tabs">
                                <?php
                                    $_tmpK = 1;
                                    $_monthlyData['data'] = json_decode($_monthlyData['data_json'], true);
                                    foreach ($_monthlyData['data']['sheetDatas'] as $_key=>$_sheetDataInfo) {
                                ?>
                                <li class="bg-info <?php if ($_tmpK++ == 1) echo 'active';?>">
                                <a href="#tab_id_<?php echo $_monthlyData['belong_year_month'];?>_sheet_<?php echo $_key;?>" data-toggle="tab"><?php echo $_monthlyData['data']['sheetNames'][$_key];?></a>
                                </li>
                                <?php }?>
                            </ul>
                        </h3>
                    </div>
                <?php

                    $_tmpJ = 1;
                    $_monthlyData['data'] = json_decode($_monthlyData['data_json'], true);
                    foreach ($_monthlyData['data']['sheetDatas'] as $_key=>$_sheetDataInfo) {
                   ?>

                <table class="<?php if ($_tmpJ++ == 1) echo 'active';?> tab-pane table table-bordered table-condensed" id="tab_id_<?php echo $_monthlyData['belong_year_month'];?>_sheet_<?php echo $_key;?>">
                    <?php foreach ($_sheetDataInfo as $_rowKey=>$_rowData) { ?>
                        <?php
                        $_isEmpty = true;
                        $_trBgStyle = '';
                        foreach ($_rowData as $_colKey=>$_cellData) {

                            if (!is_null($_cellData)&&$_cellData!=='') {
                                $_isEmpty = false;
                                break;
                            }
                        }
                        if ($_isEmpty) {
                            continue;
                        }
                        ?>
                    <tr>
                        <?php
                        $_tdCount = count($_rowData);
                        foreach ($_rowData as $_colKey=>$_cellData) {
                        ?>
                        <td style="<?php
                        // 判断单元格颜色
                        echo in_array($_monthlyData['data']['sheetStyles'][$_key][$_rowKey][$_colKey], array('000000','FFFFFF')) ? '' : ('background-color:#' . $_monthlyData['data']['sheetStyles'][$_key][$_rowKey][$_colKey].';');
                        // 如果是数字， 不折行
                        echo is_numeric($_cellData) ? 'word-break: normal;':'';
                        ?>"
                        class="<?php
                        // 如果后面单元格的内容都为空， 做单元格跨列处理
                        echo join('', $_rowData)===$_cellData ? 'xl66" colspan="'.$_tdCount : '';
                        ?>"><?php
                        // 数字类型， 保留小数点后2位
                        echo is_numeric($_cellData) ? round($_cellData, 2) : $_cellData;
                        ?></td>
                        <?php
                        $_tdCount--; // 如果后面单元格的内容都为空， 已做跨列处理， 跳过当前行
                            if (join('', $_rowData)===$_cellData) {
                                break;
                            }
                        } ?>
                    </tr>
                    <?php } ?>
                </table>
                    <?php } ?>
                <br/>
                </div>
                <?php
                    }
                ?>
                </div>
                <?php
                } // end if ?>

                </div>


            </div>

        </div>
    </div>
</div>

<style>
<!--
td
	{
	padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	text-align:center;
	vertical-align:middle;
    word-break: break-all;
    white-space: normal;
	color:#000000;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:宋体;

	border:none;}
.xl66
	{
	text-align:center;
	font-size:14.0pt;
	font-weight:700;

	border:.5pt solid;}
.xl68
	{
	text-align:center;
	background:#F9F9F9;
	font-size:12.0pt;
	font-weight:700;
	border:.5pt solid;}
.xl70
	{
	border:.5pt solid;}
 -->  </style>
<script>
$(function () {
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
    /**
     * 日期输入框， 点击清除图标，将输入框内容清除
     */
    $('.icon-delete.icon-date-delete').click (function () {
        $(this).siblings('.js-date-input').val('');
    });


});
</script>

<?php View::output('admin/global/footer.php'); ?>
