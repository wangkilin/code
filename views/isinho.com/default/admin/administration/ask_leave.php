<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
            <h3>
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#index"><?php _e('请假管理'); ?></a></li>
                </ul>
            </h3>
        </div>


        <div class="mod-body tab-content padding5px">
            <div class="tab-pane active" id="index">
                <div class="row">
                    <div class="col-sm-2"><a href="/admin/administration/ask_leave/year_month-<?php echo date('Ym', strtotime($this->leaveYear.$this->leaveMonth.'01 -1month'));?>">上一月(<?php echo date('Y-m', strtotime($this->leaveYear.$this->leaveMonth.'01 -1month'));?>)</a></div>
                    <div class="col-sm-8"></div>
                    <div class="col-sm-2 text-right"><a href="/admin/administration/ask_leave/year_month-<?php echo date('Ym', strtotime($this->leaveYear.$this->leaveMonth.'01 +1month'));?>">(<?php echo date('Y-m', strtotime($this->leaveYear.$this->leaveMonth.'01 +1month'));?>)下一月</a></div>
                </div>
                <div class="table-responsive">
                    <?php $year = $this->leaveYear; $month = $this->leaveMonth;?>
                        <table  class="table table-striped table-bordered ask-leave-table" id="js-sinho-leave-table" data-year-month="<?php echo $year,'-',$month;?>">
                            <thead>
                                <tr>
                                    <th><span class="col-sm-12 no-padding text-right"><?php echo $year,'-',$month;?></span><span class="col-sm-12 no-padding cell-rotate-separator"></span><span class="col-sm-12 no-padding">姓名</span></th>
                                    <?php $totalDaysInMonth = date('t', strtotime($year . $month . '01'));
                                    $weekNameList = array('日','一','二','三','四','五','六');
                                    for($i=1; $i<=$totalDaysInMonth; $i++) {
                                        $_weekIndex = date('w', strtotime($year.$month.sprintf('%02d', $i)));
                                        $class = $_weekIndex%6==0 ? 'bg-warning' : '';
                                        $class = date('Ymd', strtotime($year.$month.sprintf('%02d', $i)))==date('Ymd') ? 'bg-danger' : $class;
                                    ?>
                                    <th style="width:<?php echo round(1/($totalDaysInMonth+2), 5)*100;?>%" class="<?php echo $class;?>"><?php
                                    echo '<span>', $i, '</span><br/》';
                                    echo '<span>', $weekNameList[$_weekIndex%7], '</span>';
                                    ?></th>
                                    <?php }?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($this->userList as $_userInfo) { ?>
                                <tr data-user-id="<?php echo $_userInfo['uid'];?>" data-user-name="<?php echo $_userInfo['user_name'];?>">
                                    <td><?php echo $_userInfo['user_name'];?></td>
                                    <?php
                                    for($i=1; $i<=$totalDaysInMonth; $i++) {
                                        $_weekIndex = date('w', strtotime($year.$month.sprintf('%02d', $i)));
                                        $class = $_weekIndex%6==0 ? 'bg-warning weekend' : '';
                                        $class = date('Ymd', strtotime($year.$month.sprintf('%02d', $i)))==date('Ymd') ? 'bg-danger' : $class;
                                    ?>
                                    <td id="td_<?php echo $_userInfo['uid'],'_',$i;?>" style="width:<?php echo round(1/($totalDaysInMonth+2), 5)*100;?>%" class="<?php echo $class;?>" title="<?php echo intval($month) , '月', $i, '日/星期', $weekNameList[$_weekIndex%7];?>" data-toggle="tooltip" data-date="<?php echo $year.$month.sprintf('%02d', $i);?>"></td>
                                    <?php
                                    }?>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
function loadLeaveDataIntoTable (leaveList) {
    var startTime, endTime, startDateObj,endDateObj;
    var year = $('#js-sinho-leave-table').data('year-month').substr(0,4);
    var month = parseInt($('#js-sinho-leave-table').data('year-month').substr(5));
    startDateObj = new Date(year, month, 0);
    endDateObj   = new Date(year, month-1, startDateObj.getDate(), 23, 59, 59);
    startDateObj = new Date(year, month-1, 1);
    //console.info(startDateObj.toLocaleDateString(), endDateObj.toLocaleDateString());
    startTime = Math.floor(startDateObj.getTime() / 1000, 0);
    endTime   = Math.floor(endDateObj.getTime() / 1000, 0);
    var tmpStartTime, tmpEndTime, userId, tmpDate, tmpTdIdA;
    for (var i=0; i<leaveList.length; i++) {
        userId = leaveList[i].user_id;
        tmpStartTime = Math.max(startTime, leaveList[i].leave_start_time);
        tmpEndTime   = Math.min(endTime, leaveList[i].leave_end_time);
        //console.info(tmpStartTime, tmpEndTime);
        while(tmpStartTime < tmpEndTime) {
            tmpDate = new Date(tmpStartTime * 1000);
            if (tmpDate.getDay() % 6 == 0) { // 周六日不显示请假状态
                tmpStartTime += 24 * 60 * 60;
                continue;
            }
            tmpTdId = '#td_' + userId + '_' + tmpDate.getDate();
            switch(leaveList[i].leave_type) {
                case 2:
                    $(tmpTdId).html($(tmpTdId).html() + '<i class="icon icon-bulb"/>');
                    break;
                case 3:
                    $(tmpTdId).html($(tmpTdId).html() + '<i class="icon icon-log"/>');
                    break;
                case 1:
                default:
                    $(tmpTdId).html($(tmpTdId).html() + '<i class="icon icon-report"/>');
                    break;
            }
            tmpStartTime += 24 * 60 * 60;
        }
    }
}
$(function () {
    var leaveList = JSON.parse('<?php echo json_encode($this->leaveList, JSON_UNESCAPED_UNICODE)?>');
    loadLeaveDataIntoTable(leaveList);

    $('body').on('click', '.ask-leave-single-item .icon-plus', function () {
        $('.js-ajax-feedback').removeClass('fade in bg-warning text-danger').text(''); // 移除错误提醒信息
        var $item = $(this).closest('.ask-leave-single-item').clone();
        $item.find('input').val('');
        $item.find(".js-datepicker" ).datetimepicker({
                format  : 'yyyy-mm-dd h:ii',
                language:  'zh-CN',
                weekStart: 1, // 星期一 为一周开始
                todayBtn:  1, // 显示今日按钮
                autoclose: 1,
                todayHighlight: 1,
                startView: 1,
                forceParse: 0,
                minView : 0, // 0:选择到分钟， 1：选择到小时， 2：选择到天
                minuteStep:30,
                initialDate : $('#ask-leave-date').text(),
        }) ;
        $(this).closest('.ask-leave-single-item').after($item);
    });
    $('body').on('click', '.ask-leave-single-item .icon-delete', function () {
        $('.js-ajax-feedback').removeClass('fade in bg-warning text-danger').text(''); // 移除错误提醒信息
        $(this).closest('.ask-leave-single-item').remove();
    });
    $('body').on('change', '.ask-leave-single-item input', function () {

        $('.js-ajax-feedback').removeClass('fade in bg-warning text-danger').text(''); // 移除错误提醒信息
    });

    /**
     * 设置书稿日期
     */
    $('body').on('click', '#js-sinho-leave-table > tbody > tr > td:not(.weekend)', function() {
        var userId    = $(this).parent().data('user-id');
        var userName  = $(this).parent().data('user-name');
        var leaveDate = $(this).data('date').toString();
        var leaveDateDisplay = leaveDate.substr(0,4) + '-' + leaveDate.substr(4,2) + '-' + leaveDate.substr(6,2);
        var callback = function (response) {
            //console.info(response);
            var onshowCallback = function () {
                //$('.js-datepicker').date_input(); // 已有日期输入。 后台管理首页，有示例
                var startDate, endDate;
                for (var i=0; i<response.rsm.length; i++) {
                    if (0==i) {
                        $('.ask-leave-single-item').eq(i).append($('<input/>').attr({type:'hidden',name:'id[]'}));
                    } else {
                        $('.ask-leave-single-item').eq(i-1).after($('.ask-leave-single-item').eq(0).clone());
                    }
                    startDate = new Date(response.rsm[i].leave_start_time*1000);
                    endDate = new Date(response.rsm[i].leave_end_time*1000);
                    $('.ask-leave-single-item').eq(i).find('select[name="leave_type[]"]').val(response.rsm[i].leave_type);
                    $('.ask-leave-single-item').eq(i)
                          .find('input[name="leave_start_time[]"]')
                          .val(startDate.getFullYear() + '-'
                               + (startDate.getMonth() > 8 ? (startDate.getMonth()+1) : ('0'+(startDate.getMonth() +1)) ) + '-'
                               + (startDate.getDate() > 9 ?  startDate.getDate() : ('0'+startDate.getDate()) ) + ' '
                               + (startDate.getHours() > 9 ? startDate.getHours() : ('0'+startDate.getHours()) ) + ':'
                               + (startDate.getMinutes() >9 ?  startDate.getMinutes() : ('0'+startDate.getMinutes()) )
                          );
                    $('.ask-leave-single-item').eq(i)
                         .find('input[name="leave_end_time[]"]')
                         .val(endDate.getFullYear() + '-'
                               + (endDate.getMonth()>8 ? (endDate.getMonth()+1) : ('0'+(endDate.getMonth()+1)) ) + '-'
                               + (endDate.getDate() > 9 ?  endDate.getDate() : ('0'+endDate.getDate()) ) + ' '
                               + (endDate.getHours() > 9 ? endDate.getHours() : ('0'+endDate.getHours()) ) + ':'
                               + (endDate.getMinutes() >9 ?  endDate.getMinutes() : ('0'+endDate.getMinutes()) )
                          );
                    $('.ask-leave-single-item').eq(i).find('input[name="leave_period[]"]').val(response.rsm[i].leave_period);
                    $('.ask-leave-single-item').eq(i).find('input[name="id[]"]').val(response.rsm[i].id);
                }

                $( ".js-datepicker" ).datetimepicker({
                    format  : 'yyyy-mm-dd h:ii',
                    language:  'zh-CN',
                    weekStart: 1, // 星期一 为一周开始
                    todayBtn:  1, // 显示今日按钮
                    autoclose: 1,
                    todayHighlight: 1,
                    startView: 1,
                    forceParse: 0,
                    minView : 0, // 0:选择到分钟， 1：选择到小时， 2：选择到天
                    minuteStep:30,
                    initialDate : leaveDateDisplay,
                }) ;

                $('#js-submit-form').click(function() {
                    $('.js-ajax-feedback').removeClass('fade in bg-warning text-danger').text('');
                    var successCallback = function (response) {
                        if (response.errno==-1) {
                            $('.js-ajax-feedback').text(response.err).addClass('fade in bg-warning text-danger');
                        } else {
                            $('.icb-alert-box').modal('hide');
                        }
                    };
                    var errorCallback   = function (response) {
                        console.info(response);
                    };
                    ICB.ajax.requestJson(
                        $(this).closest('form').attr('action'),
                        $(this).closest('form').serialize(),
                        successCallback,
                        errorCallback
                    );
                });
            };

            var currentDate = new Date();
            var html = Hogan.compile(ICB.template.sinhoAskLeave).render(
                {
                    user_id             : userId,
                    user_name           : userName,
                    leave_date          : leaveDate,
                    leave_date_display  : leaveDateDisplay,
                    //leave_date_start    : leaveDateDisplay.substr(5) +' ' + currentDate.getHours() + ':00',
                    //leave_date_end      : leaveDateDisplay.substr(5) +' ' + currentDate.getHours() + ':00',
                });
            ICB.modal.dialog(html, onshowCallback);
        };
        ICB.ajax.requestJson(
                G_BASE_URL + '/admin/ajax/administration/get_ask_leave/',
                {start_date: leaveDate, end_date:leaveDate, user_id:userId},
                callback
            );

        return false;
    });
});

</script>
<?php View::output('admin/global/footer.php'); ?>
