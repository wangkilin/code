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
    var stockName = "<?php echo $val['name'];?>";
    var shOrSztong  = "<?php echo $val['code'][0]=='6' ? "沪股通" : "深股通";?>";
    var dates = JSON.parse('<?php echo json_encode(array_keys($this->personalWorkloadList));?>');
    var shares = JSON.parse('<?php echo json_encode(array_values($this->personalWorkloadList));?>');
    var minLeft = parseInt(Math.max(<?php echo join(',', array_values($this->personalWorkloadList));?>) +50);
    var maxLeft = parseInt(Math.min(<?php echo join(',', array_values($this->personalWorkloadList));?>) -50);
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
            legend: {
                data: [shOrSztong + ':' + stockName,'b','c','d','e'],//[],
                padding: 8,
                x: 'right',
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
    var stockName = "<?php echo $val['name'];?>";
    var shOrSztong  = "<?php echo $val['code'][0]=='6' ? "沪股通" : "深股通";?>";
    //var dates = JSON.parse('<?php echo json_encode(array_keys($this->employeeWorkloadList));?>');
    //var shares = JSON.parse('<?php echo json_encode(array_values($this->employeeWorkloadList));?>');
    var minLeft = parseInt(Math.max(<?php echo join(',', $this->allTotalChars);?>) +50);
    var maxLeft = parseInt(Math.min(<?php echo join(',', $this->allTotalChars);?>) -50);
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
