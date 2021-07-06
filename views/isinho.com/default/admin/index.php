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
                <?php if (property_exists($this, 'personalWorkloadList')) { // 有个人工作量数据展示个人数据统计图表 ?>
                <div class="col-md-12 form-group">
                    <div id="statistic_chart" class="echart_stat"></div>
                </div>
                <?php } ?>
                <?php if (property_exists($this, 'employeeWorkloadList')) {// 展示员工统计图表 ?>
                <div class="col-md-12 form-group">
                    <div id="workload_chart" class="echart_stat"></div>
                    <div class="form-group echart-date col-xs-offset-1">
                        <form action="admin/ajax/workload/statistic_monthly_chars/" method="post" onsubmit="return false">
                            <label class="col-sm-2 col-xs-2 control-label nopadding">统计时间:</label>
                            <div class="col-sm-4 col-xs-8 nopadding">
                                <div class="row">
                                    <div class="col-sm-6 col-xs-6 mod-double icon-date-container">
                                        <input type="text" name="start_month" class="form-control icon-indent js-date-input js-monthpicker" value="<?php echo date('Y-m', strtotime($this->belongMinMonth.'01'));?>">
                                        <i class="icon icon-date"></i>
                                        <i class="icon icon-date-delete icon-delete"></i>
                                    </div>
                                <span class="mod-symbol col-xs-1 col-sm-1">
                                    -
                                </span>
                                    <div class="col-sm-6 col-xs-6 mod-double icon-date-container">
                                        <input type="text" name="end_month" class="form-control icon-indent js-date-input js-monthpicker" value="<?php echo date('Y-m', strtotime($this->belongMonth.'01'));?>">
                                        <i class="icon icon-date"></i>
                                        <i class="icon icon-date-delete icon-delete"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 col-xs-2  text-right">
                                <a href="javascript:;" class="btn btn-primary btn-sm date-seach js-load-employee-workload">确认查询</a>
                            </div>
                        </form>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>

        <?php if ($this->totalCharsListLastMonth) {// 展示工作量榜单 ?>
        <div class="col-md-6">
            <div class="mod">

                <div class="form-group echart-date mod-head">
                    <form action="admin/ajax/workload/statistic_total_chars/" method="post" onsubmit="return false">
                        <div class="col-sm-3 col-xs-2 nopadding">
                        <h3>
                        <span class="pull-left nopadding nomargin"><?php _e('工作榜单'); ?></span>
                        </h3>
                        </div>
                        <div class="col-sm-7 col-xs-8 nopadding">
                            <div class="row">
                                <div class="col-sm-6 col-xs-6 mod-double icon-date-container">
                                    <input type="text" name="start_month" class="form-control icon-indent js-date-input js-monthpicker" value="<?php echo date('Y-m', strtotime($this->belongMonth.'01'));?>">
                                    <i class="icon icon-date"></i>
                                    <i class="icon icon-date-delete icon-delete"></i>
                                </div>
                               <span class="mod-symbol col-xs-1 col-sm-1">
                                   -
                               </span>
                                <div class="col-sm-6 col-xs-6 mod-double icon-date-container">
                                    <input type="text" name="end_month" class="form-control icon-indent js-date-input js-monthpicker" value="<?php echo date('Y-m', strtotime($this->belongMonth.'01'));?>">
                                    <i class="icon icon-date"></i>
                                    <i class="icon icon-date-delete icon-delete"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2 col-xs-12 nopadding nomargin">
                            <a href="javascript:;" class="btn btn-primary  btn-sm js-load-workload-top-list pull-right">确认查询</a>
                        </div>
                    </form>
                </div>
                <div class="tab-content mod-content">
                    <table  class="table table-striped" id="total-chars-list">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?php _e('责编');?></th>
                                <th ><b class="js-tooltip" data-toggle="tooltip" title="大于1的系数转换成系数为1"><?php _e('字数X转换系数?');?></b></th>
                                <th><?php _e('字数X系数');?></th>
                                <th><?php _e('绩效');?></th>
                                <th><?php _e('考核奖惩');?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i=1; foreach ($this->totalCharsListLastMonth as $_userId => $_totalChars) { ?>
                            <tr>
                                <td><?php echo $i++;?></td>
                                <td><?php echo $this->userList[$_userId]['user_name']; ?></td>
                                <td><?php echo $this->totalCharsWeightLt1ListLastMonth[$_userId]; ?></td>
                                <td><?php echo $_totalChars; ?></td>
                                <td><?php echo round($_totalChars*2,2);?></td>
                                <td><?php echo round($this->quarlityStat[$_userId],2);?></td>
                            </tr>
                            <?php }?>
                        </tbody>
                        <tfoot>
                            <tr class="info">
                                <td colspan="2">合计</td>
                                <td><?php echo array_sum($this->totalCharsWeightLt1ListLastMonth);?></td>
                                <td><?php echo array_sum($this->totalCharsListLastMonth);?></td>
                                <td><?php echo round(array_sum($this->totalCharsListLastMonth)*2, 2);?></td>
                                <td><?php echo array_sum($this->quarlityStat);?></td>
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
    </div>
</div>
<script type="text/javascript">
$(function () {

    $('.js-tooltip').tooltip();

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
        legend: { // 是否在图标中显示每个员工名称。 点击员工名称，可以显示/隐藏对应员工数据
                //data: employeeList,//[],
                padding: 8,
                x: 'right',
        },
        series: [
            {
                name: '',
                type: 'line',
                //stack: '总量',
                data: []
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
            data: []
        }],
        yAxis: [{// Y轴左侧坐标
            type: 'value',
            max: null,
            min: null,
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


<?php if (property_exists($this, 'personalWorkloadList')) { ?>
    var dates = JSON.parse('<?php echo json_encode(array_keys($this->personalWorkloadList));?>');
    var shares = JSON.parse('<?php echo json_encode(array_values($this->personalWorkloadList));?>');
    var maxLeft = parseInt(Math.max(<?php echo join(',', array_values($this->personalWorkloadList));?>) +50);
    var minLeft = parseInt(Math.min(<?php echo join(',', array_values($this->personalWorkloadList));?>) -50);
    minLeft = minLeft < 0 ? 0 : minLeft;
    //console && console.info(shares, minLeft, maxLeft);
    var echartOptions1 = Object.assign({}, echartOptions); // 克隆对象
    echartOptions1.title.text =  '我的工作量';
    echartOptions1.series[0].data = shares;
    echartOptions1.xAxis[0].data =  dates;
    echartOptions1.yAxis[0].max = maxLeft;
    echartOptions1.yAxis[0].min = minLeft;

    var chart = echarts.init($('#statistic_chart')[0]);
    chart.setOption(echartOptions1);

    window.addEventListener("orientationchange", function ()
    {
        var chart = echarts.init($('#statistic_chart'));
        chart.setOption(echartOptions1);
    }, false);

    // 左侧菜单收缩重新渲染图表
    $('.icb-header .mod-head-btn').click(function () {
        //chart = echarts.init($('#statistic_chart')[0]);
        //chart.setOption(echartOptions);
    });


<?php } ?>

<?php if (property_exists($this, 'employeeWorkloadList')) { ?>
    //var dates = JSON.parse('<?php echo json_encode(array_keys($this->employeeWorkloadList));?>');
    //var shares = JSON.parse('<?php echo json_encode(array_values($this->employeeWorkloadList));?>');
    var maxLeft2 = parseInt(Math.max(<?php echo join(',', $this->allTotalChars);?>) +50);
    var minLeft2 = parseInt(Math.min(<?php echo join(',', $this->allTotalChars);?>) -50);
    minLeft2 = minLeft2 < 0 ? 0 : minLeft2;
    //console && console.info(shares, minLeft2, maxLeft2);

    var echartOptions2 = Object.assign({}, echartOptions);
    echartOptions2.title.text =  '近期工作量';
    echartOptions2.series = [ // 显示的数据。
                <?php
                $length = count($this->employeeWorkloadList);
                $i = 1;
                foreach ($this->employeeWorkloadList as $_userId=>$_stats) {
                    if (! $_userId) {
                        continue;
                    }
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
            ];
    echartOptions2.xAxis[0].data =  JSON.parse('<?php echo json_encode(array_values($this->monthList));?>');
    echartOptions2.yAxis[0].max = null;
    echartOptions2.yAxis[0].min = null;
    var chart2 = echarts.init($('#workload_chart')[0]);
    chart2.setOption(echartOptions2);

    window.addEventListener("orientationchange", function ()
    {
        var chart2 = echarts.init($('#workload_chart'));
        chart2.setOption(echartOptions2);
    }, false);

    // 左侧菜单收缩重新渲染图表
    $('.icb-header .mod-head-btn').click(function () {
        chart2.setOption(echartOptions2);
    });


<?php } ?>
    /**
     * 工作量榜单。 根据起止月份，获取员工工作量统计榜。
     */
    $('.js-load-workload-top-list').click(function () {
        var $form = $(this).closest('form');
        var successCallback = function (response) {
            $('#total-chars-list > tbody > tr').remove();
            $('#total-chars-list > tfoot > tr').remove();
            var html = '';
            var total = 0;
            var totalCharsWeightLt1 = 0;
            var totalQuarlity = 0;
            for(var i = 0; i<response.rsm.length; i++) {
                if (! response.rsm[i].name) {
                    continue;
                }
                total += response.rsm[i].total;
                totalCharsWeightLt1 += response.rsm[i].totalCharsWeightLt1;
                totalQuarlity += response.rsm[i].quarlityStat;

                html = '<tr><td>' + (i+1)
                      + '</td><td>' + response.rsm[i].name
                      + '</td><td>' + response.rsm[i].totalCharsWeightLt1
                      + '</td><td>' + response.rsm[i].total
                      + '</td><td>' + float(response.rsm[i].total*2, 2)
                      + '</td><td>' + float(response.rsm[i].quarlityStat, 2)
                      + '</td></tr>';
                $('#total-chars-list > tbody').append(html);
            }
            html = '<tr class="info">' +
                        '<td colspan="2">合计</td>' +
                        '<td>' + float(totalCharsWeightLt1, 4) + '</td>' +
                        '<td>' + float(total, 4) + '</td>' +
                        '<td>'+ float(total*2, 2) +'</td>' +
                        '<td>'+ float(totalQuarlity, 2) +'</td>' +
                    '</tr>';
            $('#total-chars-list > tfoot').append(html);
        };
        ICB.ajax.requestJson($form.attr('action'), $form.serialize(), successCallback);
    });

    /**
     * 根据起止月份，获取员工每月的工作量， 刷新工作量图表
     */
    $('.js-load-employee-workload').click(function () {
        var $form = $(this).closest('form');
        var successCallback = function (response) {
            //console.info(response);
            echartOptions2.title.text =  '近期工作量';
            echartOptions2.series = []; // 显示的数据。
            var xAxis = [];

            for(var key in response.rsm.stat) {
                if (! key) {
                    continue;
                }
                if (xAxis.length==0) {
                    for (var xAxisKey in response.rsm.stat[key]) {
                        xAxis.push(xAxisKey);
                    }
                }
                echartOptions2.series.push(
                    {
                        name: response.rsm.employee[key],
                        type: 'line',
                        data: []
                    }
                );
                for(var _tmpKey in response.rsm.stat[key]) {
                    echartOptions2.series[echartOptions2.series.length-1].data.push(response.rsm.stat[key][_tmpKey]);
                }
            }
            //console.info(echartOptions2.series);
            echartOptions2.xAxis[0].data =  xAxis;
            echartOptions2.yAxis[0].max = null;
            echartOptions2.yAxis[0].min = null;
            var chart2 = echarts.init($('#workload_chart')[0]);
            chart2.setOption(echartOptions2);
        };
        ICB.ajax.requestJson($form.attr('action'), $form.serialize(), successCallback);

    });

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

});

</script>
<script type="text/javascript" src="<?php echo G_STATIC_URL; ?>/js/echarts.4_8.min.js"></script>
<?php View::output('admin/global/footer.php'); ?>
