<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
	<?php
    if (property_exists($this, 'currentWorkload')) {
        $hasDanger = $this->currentWorkload < $this->nowPassedDays * 50;
    ?><div class="alert <?php echo $hasDanger ? 'bg-danger' : 'bg-info'; ?>">
    <?php echo $hasDanger ? '！！！要加油啦！！！ ' : ''; ?>
    本月工作量：<?php echo $this->currentWorkload; ?>千字 /
    <?php echo $this->workingDaysAmount * 50;?>千字 ——
    本月工作日天数剩余：<?php echo $this->workingDaysAmount-$this->nowPassedDays, '天/', $this->workingDaysAmount, '天';?></div><?php
    }
    foreach ($this->warningMsgList as $_msg) { ?>
	<div class="alert alert-danger"><?php echo $_msg; ?> </div>
    <?php }
    ?>

    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <?php if (property_exists($this, 'personalWorkloadList')) { ?>
                <div class="col-md-12 form-group">
                    <div id="statistic_chart" class="echart_stat"></div>
                </div>
                <?php } ?>
                <?php if (property_exists($this, 'employeeWorkloadList')) { ?>
                <div class="col-md-12 form-group">
                    <div id="workload_chart" class="echart_stat"></div>
                    <div class="form-group echart-date col-xs-offset-1">
                        <label class="col-sm-2 col-xs-3 control-label nopadding">统计时间段:</label>
                        <div class="col-sm-8 col-xs-9">
                            <div class="row">
                                <div class="col-sm-6 mod-double">
                                    <input type="text" class="form-control mod-data date-start">
                                    <i class="icon icon-date"></i>
                                </div>
                               <span class="mod-symbol col-xs-1 col-sm-1">
                                   -
                               </span>
                                <div class="col-sm-6 mod-double">
                                    <input type="text" class="form-control mod-data date-end">
                                    <i class="icon icon-date"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <a href="javascript:;" class="btn btn-primary btn-sm date-seach">确认查询</a>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>

        <?php if ($this->totalCharsListLastMonth) { ?>
        <div class="col-md-6">
            <div class="mod">

            <div class="form-group echart-date mod-head">
                        <div class="col-sm-3 nopadding">
                        <h3>
                        <span class="pull-left"><?php _e('工作量榜单'); ?></span>
                        </h3>
                        </div>
                        <div class="col-sm-7">
                            <div class="row">
                                <div class="col-sm-6 mod-double">
                                    <input type="text" class="form-control icon-indent  js-monthpicker">
                                    <i class="icon icon-date"></i>
                                </div>
                               <span class="mod-symbol col-xs-1 col-sm-1">
                                   -
                               </span>
                                <div class="col-sm-6 mod-double">
                                    <input type="text" class="form-control icon-indent  js-monthpicker">
                                    <i class="icon icon-date"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <a href="javascript:;" class="btn btn-primary  btn-sm date-seach">确认查询</a>
                        </div>
                    </div>
                <div class="tab-content mod-content">
                    <table  class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?php _e('责编');?></th>
                                <th><?php _e('字数（乘系数）');?></th>
                                <th><?php _e('绩效');?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i=1; foreach ($this->totalCharsListLastMonth as $_userId => $_totalChars) { ?>
                            <tr>
                                <td><?php echo $i++;?></td>
                                <td><?php echo $this->userList[$_userId]['user_name']; ?></td>
                                <td><?php echo $_totalChars; ?></td>
                                <td><?php echo round($_totalChars*2,2);?></td>
                            </tr>
                            <?php }?>
                        </tbody>
                        <tfoot>
                            <tr class="info">
                                <td colspan="2">合计</td>
                                <td><?php echo array_sum($this->totalCharsListLastMonth);?></td>
                                <td><?php echo round(array_sum($this->totalCharsListLastMonth)*2, 2);?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($this->globalStatMsgList) { ?>
        <div class="col-md-6">
            <div class="mod">

            <div class="form-group echart-date mod-head">
                        <div class="col-sm-3 nopadding">
                        <h3>
                        <span class="pull-left"><?php _e('全局统计'); ?></span>
                        </h3>
                        </div>
                    </div>
                <div class="tab-content mod-content">
                    <table  class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?php _e('统计内容');?></th>
                                <th><?php _e('统计数据');?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i=1; foreach ($this->globalStatMsgList as $_statName => $_statValue) { ?>
                            <tr>
                                <td><?php echo $i++;?></td>
                                <td><?php echo $_statName; ?></td>
                                <td><?php echo $_statValue;?></td>
                            </tr>
                            <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($this->totalCharsListLastMonth) {

        ?>
        <div class="col-sm-12">开发计划:员工考勤</div>
        <?php $year = date('Y'); $month = date('m');?>
        <table  class="table table-striped table-bordered ">
            <thead>
                <tr>
                    <th><span class="col-sm-12 no-padding text-right"><?php echo $year,'-',$month;?></span><span class="col-sm-12 no-padding cell-rotate-separator"></span><span class="col-sm-12 no-padding">姓名</span></th>
                    <?php $totalDaysInMonth = date('t');
                    for($i=1; $i<=$totalDaysInMonth; $i++) {
                        $_weekIndex = date('w', strtotime($year.$month.sprintf('%02d', $i)));
                        $class = $_weekIndex%6==0 ? 'bg-warning' : '';
                    ?>
                      <th style="width:<?php echo round(1/($totalDaysInMonth+2), 5)*100;?>%" class="<?php echo $class;?>"><?php echo $i;?></th>
                    <?php }?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->userList as $_userInfo) { ?>
                <tr>
                    <td><?php echo $_userInfo['user_name'];?></td>
                    <?php
                    for($i=1; $i<=$totalDaysInMonth; $i++) {
                        $_weekIndex = date('w', strtotime($year.$month.sprintf('%02d', $i)));
                        $class = $_weekIndex%6==0 ? 'bg-warning' : '';
                    ?>
                      <td style="width:<?php echo round(1/($totalDaysInMonth+2), 5)*100;?>%" class="<?php echo $class;?>" title="<?php echo $i;?>" data-toggle="tooltip"></td>
                    <?php }?>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        开发计划:假期设置
        <!-- <table  class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th><span>月份</span><span>日期</span></th>
                    <?php $totalDaysInMonth = date('t');
                    for($i=0; $i<$totalDaysInMonth; $i++) {?>
                      <th><?php echo $i+1; ?></th>
                    <?php }
                    for($i=$totalDaysInMonth; $i<31; $i++) {?>
                        <th><?php echo $i+1; ?></th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php for ($i=0; $i<12; $i++) { ?>
                <tr>
                    <td><?php echo $i+1, '月';?></td>
                    <?php
                    for($j=0; $j<31; $j++) {?>
                      <td>&nbsp;</td>
                    <?php }?>
                </tr>
                <?php } ?>
            </tbody>
        </table> -->
        <?php } ?>
    </div>
</div>
<script type="text/javascript">
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


<?php if (property_exists($this, 'personalWorkloadList')) { ?>
    var dates = JSON.parse('<?php echo json_encode(array_keys($this->personalWorkloadList));?>');
    var shares = JSON.parse('<?php echo json_encode(array_values($this->personalWorkloadList));?>');
    var maxLeft = parseInt(Math.max(<?php echo join(',', array_values($this->personalWorkloadList));?>) +50);
    var minLeft = parseInt(Math.min(<?php echo join(',', array_values($this->personalWorkloadList));?>) -50);
    minLeft = minLeft < 0 ? 0 : minLeft;
    console && console.info(shares, minLeft, maxLeft);
    var echartOptions = {
            backgroundColor: '#fff', // 背景色， 默认无颜色 ‘transparent’
            animation: false,
            addDataAnimation: false,
            grid: {
                //backgroundColor: '#fff', // 背景色， 默认无颜色 ‘transparent’
                borderColor: '#fff', //  borderColor = '#ccc'注意：此配置项生效的前提是，设置了 show: true
                show : true, // 网格的边框颜色。
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            title: {
                text:  '我的工作量'
            },
            tooltip: {
                trigger: 'axis'
            },
            calculable: false,
            series: [
                {
                    name: '',
                    type: 'line',
                    //stack: '总量',
                    data: shares
                }
            ],
            xAxis: [{
                type: 'category',
                splitLine: {
                    show: false,
                },

                axisLine: {
                    show: true
                },
                axisTick: {
                    show: false,
                },
                data: dates //[]
            }],
            yAxis: [{// Y轴左侧坐标
                type: 'value',
                max: maxLeft,
                min: minLeft,
                splitLine: {
                    show: false,
                },

                axisLine: {
                    show: true
                },

                splitLine: {
                    show: true,
                    lineStyle: {
                        color: 'rgba(0,0,0,0.1)',
                        type: 'dashed',
                        width: 1
                    }
                }
            }
            // ,
            // {// Y轴右侧坐标
            //     type: 'value',
            //     max: 1200,
            //     min: 0,
            //     splitLine: {
            //         show: false,
            //     },

            //     axisLine: {
            //         show: true
            //     },

            //     splitLine: {
            //         show: true,
            //         lineStyle: {
            //             color: 'rgba(0,0,0,0.1)',
            //             type: 'dashed',
            //             width: 1
            //         }
            //     }
            // }
        ],
            graphic: [
            {
                type: 'image',
                id: 'logo',
                right: 20,
                top: 20,
                z: -1,
                bounding: 'raw',
                origin: [75, 75],
                style: {
                    image: 'http://www.icodebang.cn/static/css/default/img/icodebang_white_face_logo@2x.png',
                    width: 150,
                    height: 150,
                    opacity: 0
                }
            }],
        };

    var chart = echarts.init($('#statistic_chart')[0]);
    chart.setOption(echartOptions);

    window.addEventListener("orientationchange", function ()
    {
        var chart = echarts.init($('#statistic_chart'));
        chart.setOption(echartOptions);
    }, false);


<?php } ?>

<?php if (property_exists($this, 'employeeWorkloadList')) { ?>
    //var dates = JSON.parse('<?php echo json_encode(array_keys($this->employeeWorkloadList));?>');
    //var shares = JSON.parse('<?php echo json_encode(array_values($this->employeeWorkloadList));?>');
    var maxLeft = parseInt(Math.max(<?php echo join(',', $this->allTotalChars);?>) +50);
    var minLeft = parseInt(Math.min(<?php echo join(',', $this->allTotalChars);?>) -50);
    minLeft = minLeft < 0 ? 0 : minLeft;
    console && console.info(shares, minLeft, maxLeft);
    var echartOptions = {
            backgroundColor: '#fff', // 背景色， 默认无颜色 ‘transparent’
            animation: false,
            addDataAnimation: false,
            grid: {
                //backgroundColor: '#fff', // 背景色， 默认无颜色 ‘transparent’
                borderColor: '#fff', //  borderColor = '#ccc'注意：此配置项生效的前提是，设置了 show: true
                show : true, // 网格的边框颜色。
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            title: {
                text:  '近期工作量'
            },
            tooltip: {
                trigger: 'axis'
            },
            legend: {
                //data: employeeList,//[],
                padding: 8,
                x: 'right',
            },
            calculable: false,
            series: [ // 显示的数据。
                <?php
                $length = count($this->employeeWorkloadList);
                $i = 1;
                foreach ($this->employeeWorkloadList as $_userId=>$_stats) {
                ?>
                {
                    name: '<?php echo $this->userList[$_userId]['user_name'];?>',
                    type: 'line',
                    //stack: '总量',
                    data: JSON.parse('<?php echo json_encode(array_values($_stats));?>')
                }

                <?php
                if ($length>$i++) echo ',';
                } ?>
                // ,
                // {
                //     name: '',
                //     type: 'line',
                //     //stack: '总量',
                //     data: data
                // }
                // ...
            ],
            xAxis: [{
                type: 'category',
                splitLine: {
                    show: false,
                },

                axisLine: {
                    show: true
                },
                axisTick: {
                    show: false,
                },
                data: JSON.parse('<?php echo json_encode(array_values($this->monthList));?>') //[]
            }],
            yAxis: [{// Y轴左侧坐标
                type: 'value',
                //max: maxLeft,
                //min: minLeft,
                splitLine: {
                    show: false,
                },

                axisLine: {
                    show: true
                },

                splitLine: {
                    show: true,
                    lineStyle: {
                        color: 'rgba(0,0,0,0.1)',
                        type: 'dashed',
                        width: 1
                    }
                }
            }
        ],
            graphic: [
            {
                type: 'image',
                id: 'logo',
                right: 20,
                top: 20,
                z: -1,
                bounding: 'raw',
                origin: [75, 75],
                style: {
                    image: 'http://www.icodebang.cn/static/css/default/img/icodebang_white_face_logo@2x.png',
                    width: 150,
                    height: 150,
                    opacity: 0
                }
            }],
        };

    var chart = echarts.init($('#workload_chart')[0]);
    chart.setOption(echartOptions);

    window.addEventListener("orientationchange", function ()
    {
        var chart = echarts.init($('#workload_chart'));
        chart.setOption(echartOptions);
    }, false);


<?php } ?>


});

</script>
<script type="text/javascript" src="<?php echo G_STATIC_URL; ?>/js/echarts.4_8.min.js"></script>
<?php View::output('admin/global/footer.php'); ?>
