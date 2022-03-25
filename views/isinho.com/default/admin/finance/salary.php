<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
        <?php View::output('admin/finance/salary_inc.php');?>
        </div>

        <div class="mod-body tab-content">
            <div class="tab-pane active" id="salary">
                <div class="row" style="height:30px;">

                    <div style="width:900px;">
                        <form action="/admin/finance/salary/" method="get" id="item_form">
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
                            <!-- <div class="col-sm-1"><a href="/admin/administration/ask_leave/year_month-<?php echo date('Ym', strtotime($this->startMonth.'01 -1month'));?>">上一月</a></div>
                            <div class="col-sm-3 text-center"><strong><?php echo date('Y-m', strtotime($this->startMonth.'01'));?></strong></div>
                            <div class="col-sm-1 text-right"><a href="/admin/administration/ask_leave/year_month-<?php echo date('Ym', strtotime($this->endMonth.'01 +1month'));?>">下一月</a></div> -->

                            <div class="col-sm-1 col-xs-1">
                                <input id="js-submit" class="btn btn-primary" type="submit" value="<?php _e('确 定');?>"/>
                            </div>
					    </form>
                    </div>
                </div>
                <br/><br/>
                <div class="table table-responsive">

                <table class="" border="0" cellpadding="0" cellspacing="0" style='border-collapse:collapse;'>

                    <tr class="thead" >
                        <td class="xl68" >序号</td>
                        <?php if ($this->startMonth != $this->endMonth) { // 只显示一个月时， 显示当月核算内容
                            echo '<td class="" >月份</td>';
                        }
                        ?>
                        <td class="xl68" >姓名</td>
                        <!-- <td class="xl69" >手机号</td> -->
                        <td class="xl70" >实发工资</td>
                        <td class="xl97" >基本工资</td>
                        <td class="xl68" >津贴</td>
                        <td class="xl68" >质量考<br/>核奖惩</td>
                        <td class="xl68" >绩效</td>
                        <td class="xl97" >超额奖励</td>
                        <td class="xl68" >小计</td>
                        <td class="xl68" >全勤奖</td>
                        <td class="xl97" >加班补贴</td>
                        <td class="xl68" >小计</td>
                        <td class="xl68" >工资<br/>合计</td>
                        <td class="xl68 hide hidden" >税前<br/>工资</td>
                        <td class="xl68" >缺勤扣款</td>
                        <td class="xl97" >请假扣款</td>
                        <td class="xl68" >迟到扣款</td>
                        <td class="xl68" >小计</td>
                        <td class="xl68" >上年平均工资</td>
                        <td class="xl68" >养老保险<br/>（个人）</td>
                        <td class="xl68" >医疗保险<br/>（个人）</td>
                        <td class="xl68" >失业保险<br/>（个人）</td>
                        <td class="xl68" >工伤保险<br/>（个人）</td>
                        <td class="xl68" >生育保险<br/>（个人）</td>
                        <td class="xl68" >住房公积金<br/>（个人）</td>
                        <td class="xl68" >小计</td>
                        <td class="xl70" >应计个税工资</td>
                        <td class="xl68" >应扣个税</td>
                        <td class="xl130" >养老保险<br/>（公司）</td>
                        <td class="xl130" >医疗保险<br/>（公司）</td>
                        <td class="xl130" >失业保险<br/>（公司）</td>
                        <td class="xl130" >工伤保险<br/>（公司）</td>
                        <td class="xl130" >生育保险<br/>（公司）</td>
                        <td class="xl130" >住房公积金<br/>（公司）</td>
                        <td class="xl97" >公司五险<br/>一金小计</td>
                        <td class="xl139" >应出勤<br/>天数</td>
                        <td class="xl139" >实际出<br/>勤天数</td>
                        <td class="xl139" >午餐补<br/>助天数</td>
                        <td class="xl139" >病假天数</td>
                        <td class="xl139" >病假<br/>小时数</td>
                        <td class="xl139" >病假扣除</td>
                        <td class="xl139" >事假天数</td>
                        <td class="xl139" >事假<br/>小时数</td>
                        <td class="xl139" >事假扣除</td>
                        <td class="xl139" >法定假<br/>日天数</td>
                        <td class="xl138" >备注</td>
                    </tr>
                    <?php
                    if ($this->salaryList) {
                     $i = 1;
                    foreach ($this->salaryList as $_itemInfo) {
                    ?>
                    <tr height="16.80" style='height:16.80pt;'>
                        <td               > <?php echo $this->perPage * ($this->pageId -1 ) + $i++;?></td>
                        <?php if ($this->startMonth != $this->endMonth) { // 只显示一个月时， 显示当月核算内容
                            echo '<td class="" >'. $_itemInfo['belong_year_month'].'</td>';
                        }
                        ?>
                        <td               x:str><?php echo $this->userList[$_itemInfo['user_id']]['user_name'];?></td>
                        <!-- <td               ><!- 手机号 -- ><?php echo $this->userList[$_itemInfo['user_id']]['mobile']?></td> -->
                        <td               ><!-- 实发工资 --><?php echo round($_itemInfo['shifa_gongzi'],2);?></td>
                        <td               ><!-- 基本工资 --><?php echo round($_itemInfo['jiben_gongzi'],2);?></td>
                        <td               ><!-- 津贴 --><?php echo round($_itemInfo['jintie'],2);?></td>
                        <td               ><!-- 质量考核奖惩 --><?php echo round($_itemInfo['zhiliangkaohe'],2);?></td>
                        <td               ><!-- 绩效 --><?php echo round($_itemInfo['jixiao'],2);?></td>
                        <td               ><!-- 超额奖励 --><?php echo round($_itemInfo['chaoejiangli'],2);?></td>
                        <td class="xl113" ><!-- 小计 --><?php echo round($_itemInfo['jiben_heji'],2);?></td>
                        <td               ><!-- 全勤奖 --><?php echo round($_itemInfo['quanqinjiang'],2);?></td>
                        <td               ><!-- 加班补贴 --><?php echo round($_itemInfo['jiabanbutie'],2);?></td>
                        <td class="xl113" ><!-- 小计 --><?php echo round($_itemInfo['kaoqin_heji'],2);?></td>
                        <td class="xl113"><!-- 工资<br/>合计 --><?php echo round($_itemInfo['gongzi_heji'],2);?></td>
                        <td class="xl113  hide hidden"><!-- 税前<br/>工资 --><?php echo round($_itemInfo[''],2);?></td>
                        <td               ><!-- 缺勤扣款 --><?php echo round($_itemInfo['queqinkoukuan'],2);?></td>
                        <td               ><!-- 请假扣款 --><?php echo round($_itemInfo['qingjiakoukuan'],2);?></td>
                        <td               ><!-- 迟到扣款 --><?php echo round($_itemInfo['chidaokoukuan'],2);?></td>
                        <td class="xl113" ><!-- 小计 --><?php echo round($_itemInfo['koukuan_heji'],2);?></td>
                        <td class="xl122" ><!-- 上年平均工资 --><?php echo round($_itemInfo['shangnianpingjun'],2);?></td>
                        <td               ><!-- 养老保险（个人） --><?php echo round($_itemInfo['yanglao_geren'],2);?></td>
                        <td               ><!-- 医疗保险（个人） --><?php echo round($_itemInfo['yiliao_geren'],2);?></td>
                        <td               ><!-- 失业保险（个人） --><?php echo round($_itemInfo['shiye_geren'],2);?></td>
                        <td               ><!-- 工伤保险（个人） --><?php echo round($_itemInfo['gongshang_geren'],2);?></td>
                        <td               ><!-- 生育保险（个人） --><?php echo round($_itemInfo['shengyu_geren'],2);?></td>
                        <td               ><!-- 住房公积金（个人） --><?php echo round($_itemInfo['gongjijin_geren'],2);?></td>
                        <td class="xl113" ><!-- 小计 --><?php echo round($_itemInfo['geren_heji'],2);?></td>
                        <td               ><!-- 应计个税工资 --><?php echo round($_itemInfo['yingshui_gongzi'],2);?></td>
                        <td               ><!-- 应扣个税 --><?php echo round($_itemInfo['geshui'],2);?></td>
                        <td               ><!-- 养老保险（公司） --><?php echo round($_itemInfo['yanglao_gongsi'],2);?></td>
                        <td               ><!-- 医疗保险（公司） --><?php echo round($_itemInfo['yiliao_gongsi'],2);?></td>
                        <td               ><!-- 失业保险（公司） --><?php echo round($_itemInfo['shiye_gongsi'],2);?></td>
                        <td               ><!-- 工伤保险（公司） --><?php echo round($_itemInfo['gongshang_gongsi'],2);?></td>
                        <td               ><!-- 生育保险（公司） --><?php echo round($_itemInfo['shengyu_gongsi'],2);?></td>
                        <td               ><!-- 住房公积金（公司） --><?php echo round($_itemInfo['gongjijin_gongsi'],2);?></td>
                        <td class="xl113" ><!-- 公司五险一金小计 --><?php echo round($_itemInfo['gongsi_heji'],2);?></td>
                        <td               ><!-- 应出勤天数 --><?php echo $_itemInfo['yingchuqin'];?></td>
                        <td               ><!-- 实际出勤天数 --><?php echo $_itemInfo['shijichuqin'];?></td>
                        <td               ><!-- 午餐补助天数 --><?php echo $_itemInfo['canbu'];?></td>
                        <td               ><!-- 病假天数 --><?php echo $_itemInfo['bingjia_tianshu'];?></td>
                        <td               ><!-- 病假小时数 --><?php echo $_itemInfo['bingjia_xiaoshi'];?></td>
                        <td               ><!-- 病假扣除 --><?php echo round($_itemInfo['bingjia_kouchu'],2);?></td>
                        <td               ><!-- 事假天数 --><?php echo $_itemInfo['shijia_tianshu'];?></td>
                        <td               ><!-- 事假小时数 --><?php echo $_itemInfo['shijia_xiaoshi'];?></td>
                        <td               ><!-- 事假扣除 --><?php echo round($_itemInfo['shijia_kouchu'],2);?></td>
                        <td               ><!-- 法定假日天数 --><?php echo $_itemInfo['fadingjiari'];?></td>
                        <td               ><!-- 备注 --><?php echo $_itemInfo['remark'];?></td>
                    </tr>
                    <?php
                    } // end foreach
                    ?>
                    <tr  class="tfoot" height="23" style='height:23.00pt;'>
                        <td class="xl75" height="23" style='height:23.00pt;' x:str>合计</td>
                        <?php if ($this->startMonth != $this->endMonth) { // 只显示一个月时， 显示当月核算内容
                            echo '<td class="" ></td>';
                        }
                        ?>
                        <!-- <td class="xl75" ></td> -->
                        <td class="xl75" ></td>
                        <td class="xl75" ><!-- 实发工资 --><?php echo round(array_sum( array_column($this->salaryList, 'shifa_gongzi' )),2);?></td>
                        <td class="xl75" ><!-- 基本工资 --><?php echo round(array_sum( array_column($this->salaryList, 'jiben_gongzi')),2);?></td>
                        <td class="xl75" ><!-- 津贴 --><?php echo round(array_sum( array_column($this->salaryList, 'jintie')),2);?></td>
                        <td class="xl75" ><!-- 质量考核奖惩 --><?php echo round(array_sum( array_column($this->salaryList, 'zhiliangkaohe')),2);?></td>
                        <td class="xl75" ><!-- 绩效 --><?php echo round(array_sum( array_column($this->salaryList, 'jixiao')),2);?></td>
                        <td class="xl114"><!-- 超额奖励 --><?php echo round(array_sum( array_column($this->salaryList, 'chaoejiangli')),2);?></td></td>
                        <td class="xl75" ><!-- 小计 --><?php echo round(array_sum( array_column($this->salaryList, 'jiben_heji')),2);?></td>
                        <td class="xl75" ><!-- 全勤奖 --><?php echo round( array_sum( array_column($this->salaryList, 'quanqinjiang')),2);?></td>
                        <td class="xl75" ><!-- 加班补贴 --><?php echo round( array_sum( array_column($this->salaryList, 'jiabanbutie')),2);?></td>
                        <td class="xl75" ><!-- 小计 --><?php echo round( array_sum( array_column($this->salaryList, 'kaoqin_heji')),2);?></td>
                        <td class="xl75"><!-- 工资<br/>合计 --><?php echo round(array_sum( array_column($this->salaryList, 'gongzi_heji')),2);?></td>
                        <td class="xl75  hide hidden"><!-- 税前<br/>工资 --><</td>
                        <td class="xl75" ><!-- 缺勤扣款 --><?php echo round( array_sum( array_column($this->salaryList, 'queqinkoukuan')),2);?></td>
                        <td class="xl75" ><!-- 请假扣款 --><?php echo round( array_sum( array_column($this->salaryList, 'qingjiakoukuan')),2);?></td>
                        <td class="xl75" ><!-- 迟到扣款 --><?php echo round( array_sum( array_column($this->salaryList, 'chidaokoukuan')),2);?></td>
                        <td class="xl75" ><!-- 小计 --><?php echo round( array_sum( array_column($this->salaryList, 'koukuan_heji')),2);?></td>
                        <td class="xl75" ><!-- 上年平均工资 --></td>
                        <td class="xl75" ><!-- 养老保险（个人） --><?php echo round( array_sum( array_column($this->salaryList, 'yanglao_geren')),2);?></td>
                        <td class="xl75" ><!-- 医疗保险（个人） --><?php echo round( array_sum( array_column($this->salaryList, 'yiliao_geren')),2);?></td>
                        <td class="xl75" ><!-- 失业保险（个人） --><?php echo round( array_sum( array_column($this->salaryList, 'shiye_geren')),2);?></td>
                        <td class="xl75" ><!-- 工伤保险（个人） --><?php echo round( array_sum( array_column($this->salaryList, 'gongshang_geren')),2);?></td>
                        <td class="xl75" ><!-- 生育保险（个人） --><?php echo round( array_sum( array_column($this->salaryList, 'shengyu_geren')),2);?></td>
                        <td class="xl75" ><!-- 住房公积金（个人） --><?php echo round( array_sum( array_column($this->salaryList, 'gongjijin_geren')),2);?></td>
                        <td class="xl75" ><!-- 小计 --><?php echo round( array_sum( array_column($this->salaryList, 'geren_heji')),2);?></td>
                        <td class="xl75" ><!-- 应计个税工资 --></td>
                        <td class="xl75" ><!-- 应扣个税 --><?php echo round( array_sum( array_column($this->salaryList, 'geshui')),2);?></td>
                        <td class="xl75" ><!-- 养老保险（公司） -->  <?php echo round( array_sum( array_column($this->salaryList, 'yanglao_gongsi')),2);?></td>
                        <td class="xl75" ><!-- 医疗保险（公司） -->  <?php echo round( array_sum( array_column($this->salaryList, 'yiliao_gongsi')),2);?></td>
                        <td class="xl75" ><!-- 失业保险（公司） -->  <?php echo round( array_sum( array_column($this->salaryList, 'shiye_gongsi')),2);?></td>
                        <td class="xl75" ><!-- 工伤保险（公司） -->  <?php echo round( array_sum( array_column($this->salaryList, 'gongshang_gongsi')),2);?></td>
                        <td class="xl75" ><!-- 生育保险（公司） -->  <?php echo round( array_sum( array_column($this->salaryList, 'shengyu_gongsi')),2);?></td>
                        <td class="xl75" ><!-- 住房公积金（公司） --><?php echo round( array_sum( array_column($this->salaryList, 'gongjijin_gongsi')),2);?></td>
                        <td class="xl75" ><!-- 公司五险一金小计 -->  <?php echo round( array_sum( array_column($this->salaryList, 'gongsi_heji')),2);?></td>
                        <td class="xl75" ><!-- 应出勤天数 --></td>
                        <td class="xl75" ><!-- 实际出勤天数 --></td>
                        <td class="xl75" ><!-- 午餐补助天数 --></td>
                        <td class="xl75" ><!-- 病假天数 --></td>
                        <td class="xl75" ><!-- 病假小时数 --></td>
                        <td class="xl75" ><!-- 病假扣除 --><?php echo round( array_sum( array_column($this->salaryList, 'bingjia_kouchu')),2);?></td>
                        <td class="xl75" ><!-- 事假天数 --></td>
                        <td class="xl75" ><!-- 事假小时数 --></td>
                        <td class="xl75" ><!-- 事假扣除 --><?php echo round(array_sum( array_column($this->salaryList, 'shijia_kouchu')),2);?></td>
                        <td class="xl142"><!-- 法定假日天数 --></td>
                        <td class="xl142"><!-- 备注 --></td>
                    </tr>
                    <?php if ($this->startMonth == $this->endMonth) { // 只显示一个月时， 显示当月核算内容
                    ?>

                    <tr height="35" class="xl65" style='height:35.00pt;'>
                        <td class="xl76" height="123" rowspan="3" style='border-bottom:.5pt solid #ddd;'>本月核算</td>
                        <td class="xl77" x:str>个人</td>
                        <!-- <td class="xl78"></td> -->
                        <td class="xl78"></td>
                        <td class="xl98" colspan="3" >工资+个人五险一金</td>
                        <td class="xl101"><?php echo round(array_sum( array_column($this->salaryList, 'shifa_gongzi' ))+ array_sum( array_column($this->salaryList, 'geren_heji')), 2);?></td>
                        <td class="xl115"></td>
                        <td class="xl77"></td>
                        <td class="xl77"></td>
                        <td class="xl116"></td>
                        <td class="xl78"></td>
                        <td class="xl78"></td>
                        <td class="xl78"></td>
                        <td class="xl78"></td>
                        <td class="xl77"></td>
                        <td class="xl77"></td>
                        <td class="xl123" >个人保险基数</td>
                        <td class="xl77" x:num><?php echo round($this->insuranceBasis,2);?></td>
                        <td class="xl77" x:num>--</td>
                        <td class="xl77"></td>
                        <td class="xl77"></td>
                        <td class="xl77"></td>
                        <td class="xl77"></td>
                        <td class="xl77"></td>
                        <td class="xl123" colspan="2" >企业保险基数</td>
                        <td class="xl135"  >--</td>
                        <td class="xl136" >--</td>
                        <td class="xl136"></td>
                        <td class="xl136"></td>
                        <td class="xl136"></td>
                        <td class="xl136"></td>
                        <td class="xl136"></td>
                        <td class="xl136"></td>
                        <td class="xl136"></td>
                        <td class="xl136"></td>
                        <td class="xl141"></td>
                        <td class="xl141"></td>
                        <td class="xl141"></td>
                        <td class="xl141"></td>
                        <td class="xl141"></td>
                        <td class="xl141"></td>
                        <td class="xl141"></td>
                        <td class="xl141"></td>
                    </tr>
                    <tr height="35" class="xl65" style='height:35.00pt;'>
                        <td class="xl77" x:str>企业</td>
                        <td class="xl77"></td>
                        <!-- <td class="xl77"></td> -->
                        <td class="xl102" colspan="3" >工会经费</td>
                        <td class="xl105">
                            <?php
                            $gonghui_jingfei = 0;
                            $debug = '';
                            foreach ($this->salaryList as $_itemInfo) {
                                if ($_itemInfo['user_id'] == sinhoWorkloadModel::GONGHUI_FEE_IGNORE_USER_ID
                                  || $_itemInfo['yanglao_geren']<=0) {
                                    continue;
                                }
                                $debug = $debug . ' ' . ($_itemInfo['shangnianpingjun'] > $this->insuranceBasis ? $_itemInfo['shangnianpingjun'] : $this->insuranceBasis);
                                $gonghui_jingfei += $_itemInfo['shangnianpingjun'] > $this->insuranceBasis ? $_itemInfo['shangnianpingjun'] : $this->insuranceBasis;
                            }
                            $gonghui_jingfei = $gonghui_jingfei * 0.02;
                            echo round($gonghui_jingfei,2);//echo round($this->insuranceBasis;
                            ?>
                        </td>
                        <td class="xl115"></td>
                        <td class="xl77"></td>
                        <td class="xl77"></td>
                        <td class="xl116"></td>
                        <td class="xl78"></td>
                        <td class="xl78"></td>
                        <td class="xl78"></td>
                        <td class="xl78"></td>
                        <td class="xl77"></td>
                        <td class="xl116"></td>
                        <td class="xl78"></td>
                        <!-- <td class="xl77"></td>
                        <td class="xl77"></td> -->
                        <td class="xl126"><?php echo round( array_sum( array_column($this->salaryList, 'yanglao_gongsi')),2);?></td>
                        <td class="xl126"><?php echo round( array_sum( array_column($this->salaryList, 'yiliao_gongsi')),2);?></td>
                        <td class="xl126"><?php echo round( array_sum( array_column($this->salaryList, 'shiye_gongsi')),2);?></td>
                        <td class="xl126"><?php echo round( array_sum( array_column($this->salaryList, 'gongshang_gongsi')),2);?></td>
                        <td class="xl126"><?php echo round( array_sum( array_column($this->salaryList, 'shengyu_gongsi')),2);?></td>
                        <td class="xl126"><?php echo round( array_sum( array_column($this->salaryList, 'gongjijin_gongsi')),2);?></td>
                        <td class="xl74" ><?php echo round( array_sum( array_column($this->salaryList, 'gongsi_heji')),2);?></td>
                        <td class="xl77"></td>
                        <td class="xl77"></td>
                        <td class="xl77"></td>
                        <td class="xl135"></td>
                        <td class="xl136"></td>
                        <td class="xl136"></td>
                        <td class="xl136"></td>
                        <td class="xl136"></td>
                        <td class="xl136"></td>
                        <td class="xl136"></td>
                        <td class="xl141"></td>
                        <td class="xl141"></td>
                        <td class="xl141"></td>
                        <td class="xl141"></td>
                        <td class="xl141"></td>
                        <td class="xl141"></td>
                        <td class="xl141"></td>
                        <td class="xl141"></td>
                        <td class="xl141"></td>
                        <td class="xl141"></td>
                    </tr>
                    <tr height="53" style='height:53.00pt;'>
                        <td class="xl81">合计</td>
                        <!-- <td class="xl77"></td> -->
                        <td class="xl82">公司全部</td>
                        <td class="xl106" colspan="3" ><?php echo
                         round( array_sum( array_column($this->salaryList, 'shifa_gongzi'))
                         +array_sum( array_column($this->salaryList, 'geren_heji'))
                         +array_sum( array_column($this->salaryList, 'geshui'))
                         +array_sum( array_column($this->salaryList, 'gongsi_heji'))
                         +$gonghui_jingfei     ,2);   ?></td>
                        <td class="xl109"></td>
                        <td class="xl117" colspan="2" >税务缴纳五险+工会经费</td>
                        <td class="xl119" colspan="2" ><?php echo
                        round(array_sum( array_column($this->salaryList, 'yanglao_gongsi'))   +array_sum( array_column($this->salaryList, 'yanglao_geren'))
                        + array_sum( array_column($this->salaryList, 'yiliao_gongsi'))    +array_sum( array_column($this->salaryList, 'yiliao_geren'))
                        + array_sum( array_column($this->salaryList, 'shiye_gongsi'))     +array_sum( array_column($this->salaryList, 'shiye_geren'))
                        + array_sum( array_column($this->salaryList, 'gongshang_gongsi')) +array_sum( array_column($this->salaryList, 'gongshang_geren'))
                        + array_sum( array_column($this->salaryList, 'shengyu_gongsi'))   +array_sum( array_column($this->salaryList, 'shengyu_geren'))
                        +$gonghui_jingfei ,2)    ;     ?></td>
                        <td class="xl117"  ></td>
                        <td class="xl121"  ></td>
                        <td class="xl121"  ></td>
                        <td class="xl109"></td>
                        <td class="xl81"></td>
                        <td class="xl125" x:str>医保生育个人+企业</td>
                        <td class="xl125"  ><?php echo round( array_sum( array_column($this->salaryList, 'yiliao_gongsi'))    +array_sum( array_column($this->salaryList, 'yiliao_geren')) +array_sum( array_column($this->salaryList, 'shengyu_gongsi'))   +array_sum( array_column($this->salaryList, 'shengyu_geren'))      ,2);   ?></td>
                        <!-- <td class="xl81"></td>
                        <td class="xl81"></td> -->
                        <td class="xl119"><?php echo round( array_sum( array_column($this->salaryList, 'yanglao_gongsi'))   +array_sum( array_column($this->salaryList, 'yanglao_geren'))   ,2);  ?></td>
                        <td class="xl81" ><?php echo round( array_sum( array_column($this->salaryList, 'yiliao_gongsi'))    +array_sum( array_column($this->salaryList, 'yiliao_geren'))    ,2);   ?></td>
                        <td class="xl127"><?php echo round( array_sum( array_column($this->salaryList, 'shiye_gongsi'))     +array_sum( array_column($this->salaryList, 'shiye_geren'))     ,2);    ?></td>
                        <td class="xl81" ><?php echo round( array_sum( array_column($this->salaryList, 'gongshang_gongsi')) +array_sum( array_column($this->salaryList, 'gongshang_geren')) ,2);?></td>
                        <td class="xl81" ><?php echo round( array_sum( array_column($this->salaryList, 'shengyu_gongsi'))   +array_sum( array_column($this->salaryList, 'shengyu_geren'))   ,2);  ?></td>
                        <td class="xl129"><?php echo round( array_sum( array_column($this->salaryList, 'gongjijin_gongsi')) +array_sum( array_column($this->salaryList, 'gongjijin_geren')) ,2);?></td>
                        <td class="xl74"> <?php echo round( array_sum( array_column($this->salaryList, 'gongsi_heji'))      +array_sum( array_column($this->salaryList, 'geren_heji'))      ,2);     ?></td>
                        <td class="xl74"></td>
                        <td class="xl74"></td>
                        <td class="xl74"></td>
                        <td class="xl132"></td>
                        <td class="xl133"></td>
                        <td class="xl133"></td>
                        <td class="xl133"></td>
                        <td class="xl133"></td>
                        <td class="xl133"></td>
                        <td class="xl133"></td>
                        <td class="xl140"></td>
                        <td class="xl140"></td>
                        <td class="xl140"></td>
                        <td class="xl140"></td>
                        <td class="xl140"></td>
                        <td class="xl140"></td>
                        <td class="xl140"></td>
                        <td class="xl140"></td>
                        <td class="xl140"></td>
                        <td class="xl140"></td>
                    </tr>
                    <?php
                        } // end if ($this->startMonth == $this->endMonth)
                    } // end if
                    ?>
                </table>



                <div class="mod-table-foot">
                    <div class="pull-left text-left"><?php echo $this->pagination; ?></div>

                    <div class="pull-left">每页<?php echo $this->perPage; ?>条 &nbsp; 共<?php echo $this->totalRows;?>条</div>

                </div>

                <br/>
                <br/>

                <?php if ($this->salaryExcelData) {
                ?>
                <div class="bg-primary">
                    <?php
                    echo '工资表 原始Excel数据';
                    ?>
                </div>
                <div>
                    <h3 class="nomargin">
                        <ul class="nav nav-tabs">
                            <?php
                            $_tmpI = 1;
                                foreach ($this->salaryExcelData as $_dataInfo) {
                            ?>
                            <li class="<?php if ($_tmpI++ == 1) echo 'active';?>">
                            <a href="#salary_excel_<?php echo $_dataInfo['belong_year_month'];?>" data-toggle="tab"><?php echo $_dataInfo['belong_year_month'];?></a>
                            </li>
                            <?php }?>
                        </ul>
                    </h3>
                </div>
                <div class="mod-body tab-content">
                <?php
                    $_tmpI = 1;
                    foreach ($this->salaryExcelData as $_dataInfo) {
                        $_dataInfo['data'] = json_decode($_dataInfo['data_json'], true);
                ?>
                <table id="<?php echo 'salary_excel_'.$_dataInfo['belong_year_month'];?>" class="tab-pane <?php if ($_tmpI++ == 1) echo 'active';?>" border="0" cellpadding="0" cellspacing="0" style='border-collapse:collapse;'>
                    <?php foreach ($_dataInfo['data'] as $_rowData) { ?>
                        <?php
                        $_isEmpty = true;
                        foreach ($_rowData as $_cellData) {
                            if (!is_null($_cellData)&&$_cellData!=='') {
                                $_isEmpty = false;
                                break;
                            }
                        }
                        if ($_isEmpty) {
                            continue;
                        }
                        ?>
                    <tr height="35" class="xl65">
                        <?php foreach ($_rowData as $_cellData) { ?>
                        <td><?php echo $_cellData;?></td>
                        <?php } ?>
                    </tr>
                    <?php } ?>
                </table>
                <?php
                    }
                } ?>

                </div>



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
	text-align:general;
	vertical-align:middle;
	white-space:nowrap;
	color:#000000;
	font-size:10pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	border:0.5pt solid;}

.thead td, .tfoot td
	{
	text-align:center;
	background:#E7E6E6;
	font-size:11pt;
	font-weight:600;
	border:.5pt solid ;}

.xl113
	{
	text-align:center;
	background:#BDD7EE;
	font-size:10pt;
	border:.5pt solid ;}

.xl122
	{
	text-align:center;
	background:#FFFF00;
	font-size:10pt;
	border:.5pt solid windowtext;}


.icb-content-wrap .mod{display:inline-block}

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
