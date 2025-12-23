<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12 form-group">
                    <div class="mod">
                        <div class="form-group nomargin mod-head">
                                <div class="col-sm-12 col-xs-12 ">
                                <h3>
                                <span class="pull-left nopadding nomargin"><?php _e('我的工作量和质量考核'); ?></span>
                                </h3>
                                </div>
                        </div>
                        <div class="tab-content nopadding mod-content">
                            <div id="statistic_chart" class="echart_stat"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<style>
.intranet-news-list .icon-new {
background: none;
color: #d33;
line-height: normal;
}
</style>
<script type="text/javascript">
/**
 * 获取工作量列表数据
 *
 * param sort string 工作量排序的方式
 */
function loadTotalWorkload(sort) {

    var $form = $('#statistic_total_chars_form');
    $form.find('input[name="sort"]').val(sort);

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
    $('#total-chars-list').find('.js-icon').removeClass('icon-down');

    $('#total-chars-list').find('.js-icon.js' + '-'+sort).addClass('icon-down');

    ICB.ajax.requestJson($form.attr('action'), $form.serialize(), successCallback);
}

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
    var quarlityList = JSON.parse('<?php echo json_encode(array_values($this->personalQuarlityList));?>');
    var quarlityMaxLeft = parseInt(Math.max(<?php echo join(',', array_values($this->personalQuarlityList));?>) +10);
    var quarlityMinLeft = parseInt(Math.min(<?php echo join(',', array_values($this->personalQuarlityList));?>) -10);
    minLeft = minLeft < 0 ? 0 : minLeft;
    //console && console.info(shares, minLeft, maxLeft);
    var echartOptions1 = Object.assign({}, echartOptions); // 克隆对象
    echartOptions1.legend.x = 'center';
    echartOptions1.legend.left = 'center';
    echartOptions1.title.text = '';// '我的工作量和质量考核';
    echartOptions1.series[1] = Object.assign({}, echartOptions1.series[0]);
    //echartOptions1.xAxis[1] = Object.assign({}, echartOptions1.xAxis[0]);
    echartOptions1.yAxis[1] = Object.assign({}, echartOptions1.yAxis[0]);

    echartOptions1.series[0].data = shares;
    echartOptions1.series[0].name = '工作量';
    echartOptions1.xAxis[0].data =  dates;
    echartOptions1.yAxis[0].max = maxLeft;
    echartOptions1.yAxis[0].min = minLeft;
    echartOptions1.yAxis[0].name = '工作量';

    echartOptions1.series[1].data = quarlityList;
    //echartOptions1.xAxis[1].data =  dates;
    echartOptions1.yAxis[1].max = quarlityMaxLeft;
    echartOptions1.yAxis[1].min = quarlityMinLeft;
    echartOptions1.series[1].name = '质量考核';
    echartOptions1.yAxis[1].position = 'right';
    echartOptions1.series[1].yAxisIndex = 1; // 使用另外的y轴。
    echartOptions1.yAxis[1].name = '质量考核';

    //console.info(echartOptions1);

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


});

</script>
<script type="text/javascript" src="<?php echo G_STATIC_URL; ?>/js/echarts.4_8.min.js"></script>
<?php View::output('admin/global/footer.php'); ?>
