<?php View::output('stock/block/header.php'); ?>
<style>
.icb-nav-tabs>li {
    line-height: 28px;
}
</style>
<div class="container icb-container">
    <div class="icb-content-wrap clearfix">
        <div id="statistic_chart" style="height:400px"></div>
        <ul class="nav nav-tabs icb-nav-tabs" style="padding-left:8px">
                <li class="bg-danger">
                <i class="icon icon-list"></i>持股日期截至: <?php echo $this->lastDate; ?>

                </li>
        </ul>
        <table class="table table-striped">
            <thead>
                <tr class="">
                    <th class="col-sm-2">持股日期</th>
                    <th class="col-sm-2">陆股通持股占比</th>
                    <th class="col-sm-2">陆股通持股数</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $dateShareList = array();
            $minLeft = $maxLeft = -1;
            foreach ($this->list as $_code => $val) {
                $minLeft = $minLeft >=0 && $minLeft < $val['share'] ? $minLeft : $val['share'];
                $maxLeft = $maxLeft > $val['share'] ? $maxLeft : $val['share'];
                $date = date('Y/m/d', strtotime($val['belong_date']));
                $dateShareList[$date] = $val['share'];
            ?>
                <tr >
                    <td class="col-sm-2"><?php echo $val['belong_date'] ?></td>
                    <td class="col-sm-2"><?php echo $val['percent']; ?>%</td>
                    <td class="col-sm-2"><?php echo number_format($val['share']); ?></td>
                </tr>
            <?php
            ksort($dateShareList);
            } ?>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript" src="<?php echo G_STATIC_URL; ?>/js/echarts.4_8.min.js"></script>
<script type="text/javascript">
var stockName = "<?php echo $val['name'];?>";
var shOrSztong  = "<?php echo $val['code'][0]=='6' ? "沪股通" : "深股通";?>";
var dates = JSON.parse('<?php echo json_encode(array_keys($dateShareList));?>');
var shares = JSON.parse('<?php echo json_encode(array_values($dateShareList));?>');
var minLeft = <?php echo intval($minLeft - ($maxLeft-$minLeft)/20); ?>;
var maxLeft = <?php echo intval($maxLeft + ($maxLeft-$minLeft)/20); ?>;
var echartOptions = {
        animation: false,
        addDataAnimation: false,
        grid: {
            //backgroundColor: '#c5c5c5', // 背景色， 默认无颜色 ‘transparent’
            borderColor: '#fff', //  borderColor = '#ccc'注意：此配置项生效的前提是，设置了 show: true
            show : true, // 网格的边框颜色。
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        title: {
            text:  stockName + ' - ' + shOrSztong + '近1个月持股变化'
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
                name: shOrSztong + ':' + stockName,
                type: 'line',
                //stack: '总量',
                data: shares
            }
            // ,
            // {
            //     name: 'b',
            //     type: 'line',
            //     //stack: '总量',
            //     data: [220, 182, 191, 234, 290, 330, 310]
            // },
            // {
            //     name: 'c',
            //     type: 'line',
            //     //stack: '总量',
            //     data: [150, 232, 201, 154, 190, 330, 410]
            // },
            // {
            //     name: 'd',
            //     type: 'line',
            //     //stack: '总量',
            //     data: [320, 332, 301, 334, 390, 330, 320]
            // },
            // {
            //     name: 'e',
            //     type: 'bar',
            //     yAxisIndex: 1, // 使用哪个Y轴坐标。 默认 0
            //     //stack: '总量',
            //     data: [820, 932, 901, 934, 290, 330, 320]
            // }
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
        yAxis: [{
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
        },
        {
            type: 'value',
            max: 1200,
            min: 0,
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
        }],
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
                opacity: 0.4
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
</script>
