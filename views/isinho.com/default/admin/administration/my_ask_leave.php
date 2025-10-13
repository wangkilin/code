<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
            <h3>
                <ul class="nav nav-tabs">
                    <li class="<?php echo $_GET['tab']=='report'? '':'active';?>"><a href="#index"  data-toggle="tab"><?php _e('我的休假&加班'); ?></a></li>
                </ul>
            </h3>
        </div>


        <div class="mod-body tab-content padding5px">
            <div class="tab-pane <?php echo $_GET['tab']=='report'? '':'active';?>" id="index">

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                         <td>图标说明：</td>
                         <?php foreach ($this->leaveTypeList as $_itemInfo) { ?>
                         <td><i class="icon <?php echo $_itemInfo['icon'];?>"></i><?php echo $_itemInfo['name'];?></td>
                         <?php } ?>
                        </tr>
                    </table>
                </div>
                <br/>
                <div class="row">
                    <div class="col-sm-2"><a href="/admin/administration/my_ask_leave/year_month-<?php echo date('Ym', strtotime($this->leaveYear.$this->leaveMonth.'01 -1month'));?>">上一月(<?php echo date('Y-m', strtotime($this->leaveYear.$this->leaveMonth.'01 -1month'));?>)</a></div>
                    <div class="col-sm-8 text-center"><strong><?php echo date('Y-m', strtotime($this->leaveYear.$this->leaveMonth.'01'));?></strong></div>
                    <div class="col-sm-2 text-right"><a href="/admin/administration/my_ask_leave/year_month-<?php echo date('Ym', strtotime($this->leaveYear.$this->leaveMonth.'01 +1month'));?>">(<?php echo date('Y-m', strtotime($this->leaveYear.$this->leaveMonth.'01 +1month'));?>)下一月</a></div>
                </div>
                <div class="table-responsive">
                    <?php $year = $this->leaveYear; $month = $this->leaveMonth;?>
                        <table  class="table table-striped table-bordered" id="js-sinho-leave-table" data-year-month="<?php echo $year,'-',$month;?>">
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
                                    echo '<span>', $i, '</span><br/>';
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


                <div class="row padding20">
                    <div class="col-sm-12">&nbsp;<br/><br/><br/></div>
                    <div class="col-sm-12 text-center">我的休假&加班统计</div>
                </div>

                <div class="table-responsive">
                    <?php $year = $this->leaveYear; $month = $this->leaveMonth;?>
                        <table  class="table table-striped table-bordered" >
                            <thead>
                                <tr>
                                    <th rowspan=2 class="col-sm-1"><span class="col-sm-12 no-padding">姓名</span></th>
                                    <th rowspan=2>当月统计:<?php if ($_GET['year_month']!=$_GET['end_year_month']) { echo date('Y-m', strtotime($_GET['year_month'].'01')), ' ~ ', date('Y-m', strtotime($_GET['end_year_month'].'01')); } else {echo date('Y-m', strtotime($_GET['year_month'].'01')) ;}?></th>
                                    <th colspan=3 class="col-sm-2">上年度</th>
                                    <th colspan=4 class="col-sm-2">本年度</th>
                                </tr>
                                <tr>
                                    <!-- 上年度 -->
                                    <th>事假</th>
                                    <th>病假&生理假</th>
                                    <th>年假</th>
                                    <!-- 本年度 -->
                                    <th>事假</th>
                                    <th>病假&生理假</th>
                                    <th>年假</th>
                                    <th>剩余年假</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=1;
                                $nowDateObj = date_create();
                                $startMonthTime = strtotime($this->leaveYear.$this->leaveMonth.'01');
                                $endMonthTime = strtotime($_GET['end_year_month'].'01 +1month');
                                foreach ($this->userList as $_userInfo) {
                                ?>
                                <tr data-user-id="<?php echo $_userInfo['uid'];?>" data-user-name="<?php echo $_userInfo['user_name'];?>">
                                    <td class="col-sm-1"><?php echo $_userInfo['user_name'];?></td>

                                    <td><?php
                                    $_leaveInfo = array(
                                        'event'     => array('m'=>0, 'd'=>0, 'h'=>0), // 事假信息
                                        'sick'      => array('m'=>0, 'd'=>0, 'h'=>0), // 病假信息
                                        'weekend'   => array('m'=>0, 'd'=>0, 'h'=>0), // 周末带稿
                                        'overtime'  => array('m'=>0, 'd'=>0, 'h'=>0), // 加班
                                        'annual'    => array('m'=>0, 'd'=>0, 'h'=>0), // 年假
                                        'total'     => array('m'=>0, 'd'=>0, 'h'=>0), // 全部请假信息
                                    );
                                    foreach ($this->userLeaveList[$_userInfo['uid']] as $_itemInfo) {
                                        $_leaveMonth = 0;
                                        $_leaveDay   = 0;
                                        $_leaveHour  = 0;
                                        if ($_itemInfo['leave_start_time'] >= $startMonthTime && $_itemInfo['leave_end_time'] <= $endMonthTime) {
                                            $_leaveHour = $_itemInfo['leave_period'];
                                        } else if ($_itemInfo['leave_start_time'] < $startMonthTime && $_itemInfo['leave_end_time'] > $endMonthTime) {
                                            $_dateInterval = date_diff(
                                                               new DateTime(date('Y-m-d 08:30:00', $startMonthTime)),
                                                               new DateTime(date('Y-m-d 17:30:00', $endMonthTime - 3600 * 24))
                                            );
                                            $_leaveDay   = $_dateInterval->d;
                                            $_leaveHour  = 0;
                                            $_leaveMonth = $_dateInterval->m;
                                        } else if ($_itemInfo['leave_end_time'] >= $endMonthTime) {

                                            $_dateInterval = date_diff(
                                                new DateTime(date('Y-m-d H:i:00', $_itemInfo['leave_start_time'])),
                                                new DateTime(date('Y-m-d 17:30:00', $endMonthTime - 3600 * 24))
                                            );
                                            $_leaveMonth = $_dateInterval->m;
                                            $_leaveDay   = $_dateInterval->d;
                                            $_leaveHour  = $_dateInterval->h;
                                            $_leaveHour > 7.5 AND $_leaveHour = 8;
                                            $_leaveDay > 0 AND $_leaveHour==8 AND $_leaveHour = 0;
                                        } else {

                                            $_dateInterval = date_diff(
                                                new DateTime(date('Y-m-d 08:30:00', $startMonthTime)),
                                                new DateTime(date('Y-m-d 17:30:00', $endMonthTime - 3600 * 24))
                                            );
                                            $_leaveDay   = $_dateInterval->d;
                                            $_leaveHour  = $_dateInterval->h;
                                            $_leaveMonth = $_dateInterval->m;
                                        }

                                        if ($_itemInfo['leave_start_time'] < $startMonthTime) {
                                            echo date('m-d', $startMonthTime);
                                        } else {
                                            echo date('m/d H:i', $_itemInfo['leave_start_time']);
                                        }
                                        echo '~';
                                        if ($_itemInfo['leave_end_time'] >= $endMonthTime) {
                                            echo date('m-d', $endMonthTime);
                                        } else {
                                            if (date('Y-m-d', $_itemInfo['leave_start_time']) == date('Y-m-d', $_itemInfo['leave_end_time'])) {
                                                $_dateFormat = 'H:i';
                                            } else {
                                                $_dateFormat = 'm/d H:i';
                                            }
                                            echo date($_dateFormat, $_itemInfo['leave_end_time']);
                                        }
                                        echo ' ';
                                        switch($_itemInfo['leave_type']) {
                                            case administration::LEAVE_TYPE_SICK: // 病假
                                                _e('病假');
                                                $_leaveInfo['sick']['m'] += $_leaveMonth;
                                                $_leaveInfo['sick']['d'] += $_leaveDay;
                                                $_leaveInfo['sick']['h'] += $_leaveHour;
                                                break;
                                            case administration::LEAVE_TYPE_ANNUAL: // 年假
                                                _e('年假');
                                                $_leaveInfo['annual']['m'] += $_leaveMonth;
                                                $_leaveInfo['annual']['d'] += $_leaveDay;
                                                $_leaveInfo['annual']['h'] += $_leaveHour;
                                                break;
                                            case administration::LEAVE_TYPE_WEDDING: // 婚假
                                                _e('婚假');
                                                break;
                                            case administration::LEAVE_TYPE_MATERNITY: // 产假
                                                _e('产假');
                                                break;
                                            case administration::LEAVE_TYPE_PERIOD: // 生理假
                                                _e('生理假');
                                                break;
                                            case administration::LEAVE_TYPE_FUNERAL: // 丧假
                                                _e('丧假');
                                                break;
                                            case administration::LEAVE_TYPE_BODY_CHECK: // 产检
                                                _e('产检');
                                                break;
                                            case administration::LEAVE_TYPE_PRIVATE: // 事假
                                                _e('事假');

                                                $_leaveInfo['event']['m'] += $_leaveMonth;
                                                $_leaveInfo['event']['d'] += $_leaveDay;
                                                $_leaveInfo['event']['h'] += $_leaveHour;
                                                break;
                                            case administration::LEAVE_TYPE_OVERTIME_REST: // 调休
                                                _e('调休');
                                                $_leaveInfo['overtime_rest']['m'] += $_leaveMonth;
                                                $_leaveInfo['overtime_rest']['d'] += $_leaveDay;
                                                $_leaveInfo['overtime_rest']['h'] += $_leaveHour;
                                                break;
                                            case administration::LEAVE_TYPE_OVERTIME: // 加班
                                                _e('加班');
                                                $_leaveInfo['overtime']['m'] += $_leaveMonth;
                                                $_leaveInfo['overtime']['d'] += $_leaveDay;
                                                $_leaveInfo['overtime']['h'] += $_leaveHour;
                                                break;
                                            case administration::LEAVE_TYPE_WEEKEND_WORKLOAD: // 周末带稿
                                                _e('周末带稿量');
                                                $_leaveInfo['weekend']['m'] += $_leaveMonth;
                                                $_leaveInfo['weekend']['d'] += $_leaveDay;
                                                $_leaveInfo['weekend']['h'] += $_leaveHour;
                                                break;
                                            case administration::LEAVE_TYPE_HOMEWORK: // 居家办公
                                                _e('居家办公');
                                                break;
                                            default:
                                                _e('旷工');
                                                break;
                                        }

                                        if ($_itemInfo['leave_type'] <= 20) { // 20以下的是请假
                                            $_leaveInfo['total']['m'] += $_leaveMonth;
                                            $_leaveInfo['total']['d'] += $_leaveDay;
                                            $_leaveInfo['total']['h'] += $_leaveHour;
                                        }

                                        if ($_itemInfo['leave_start_time'] >= $startMonthTime && $_itemInfo['leave_end_time'] < $endMonthTime) {
                                            echo ' ', $_itemInfo['leave_period'], '小时';
                                        }
                                        echo '; &nbsp; ';
                                    }
                                    ?></td>
                                    <!-- 上年度 -->
                                    <td><?php
                                    $lastYearPrivateLeaveHours = 0;
                                    if (isset($this->userRecentLeaveList[$_userInfo['uid']], $this->userRecentLeaveList[$_userInfo['uid']]['lastYear'][administration::LEAVE_TYPE_PRIVATE])) {
                                        $lastYearPrivateLeaveHours = array_sum(array_column($this->userRecentLeaveList[$_userInfo['uid']]['lastYear'][administration::LEAVE_TYPE_PRIVATE], 'leave_period'));
                                        //var_dump($this->userRecentLeaveList[$_userInfo['uid']]['thisYear'][administration::LEAVE_TYPE_PRIVATE]);
                                        echo $lastYearPrivateLeaveHours;
                                    }
                                    ?></td>
                                    <td><?php
                                    $lastYearSickLeaveHours = 0;
                                    if (isset($this->userRecentLeaveList[$_userInfo['uid']], $this->userRecentLeaveList[$_userInfo['uid']]['lastYear'][administration::LEAVE_TYPE_SICK])) {
                                        isset($this->userRecentLeaveList[$_userInfo['uid']]['lastYear'][administration::LEAVE_TYPE_PERIOD]) OR $this->userRecentLeaveList[$_userInfo['uid']]['lastYear'][administration::LEAVE_TYPE_PERIOD] = array();
                                        $lastYearSickLeaveHours = array_sum(
                                                array_merge(
                                                    array_column($this->userRecentLeaveList[$_userInfo['uid']]['lastYear'][administration::LEAVE_TYPE_SICK], 'leave_period'),
                                                    array_column($this->userRecentLeaveList[$_userInfo['uid']]['lastYear'][administration::LEAVE_TYPE_PERIOD], 'leave_period')
                                                )
                                            );
                                        echo $lastYearSickLeaveHours;
                                    }
                                    ?></td>
                                    <td><?php
                                    $lastYearAnnualLeaveHours = 0;
                                    if (isset($this->userRecentLeaveList[$_userInfo['uid']], $this->userRecentLeaveList[$_userInfo['uid']]['lastYear'][administration::LEAVE_TYPE_ANNUAL])) {
                                        $lastYearAnnualLeaveHours = array_sum(array_column($this->userRecentLeaveList[$_userInfo['uid']]['lastYear'][administration::LEAVE_TYPE_ANNUAL], 'leave_period'));
                                        echo $lastYearAnnualLeaveHours;
                                    }
                                    ?></td>
                                    <!-- 本年度 -->
                                    <td><?php
                                    $thisYearPrivateLeaveHours = 0;
                                    if (isset($this->userRecentLeaveList[$_userInfo['uid']], $this->userRecentLeaveList[$_userInfo['uid']]['thisYear'][administration::LEAVE_TYPE_PRIVATE])) {
                                        $thisYearPrivateLeaveHours = array_sum(array_column($this->userRecentLeaveList[$_userInfo['uid']]['thisYear'][administration::LEAVE_TYPE_PRIVATE], 'leave_period'));
                                        echo $thisYearPrivateLeaveHours;
                                        //var_dump($this->userRecentLeaveList[$_userInfo['uid']]['thisYear'][administration::LEAVE_TYPE_PRIVATE]);
                                    }
                                    ?></td>
                                    <td><?php
                                    $thisYearSickLeaveHours = 0;
                                    if (isset($this->userRecentLeaveList[$_userInfo['uid']], $this->userRecentLeaveList[$_userInfo['uid']]['thisYear'][administration::LEAVE_TYPE_SICK])) {
                                        isset($this->userRecentLeaveList[$_userInfo['uid']]['thisYear'][administration::LEAVE_TYPE_PERIOD]) OR $this->userRecentLeaveList[$_userInfo['uid']]['thisYear'][administration::LEAVE_TYPE_PERIOD] = array();
                                        $thisYearSickLeaveHours =  array_sum(
                                            array_merge(
                                               array_column($this->userRecentLeaveList[$_userInfo['uid']]['thisYear'][administration::LEAVE_TYPE_SICK], 'leave_period'),
                                               array_column($this->userRecentLeaveList[$_userInfo['uid']]['thisYear'][administration::LEAVE_TYPE_PERIOD], 'leave_period')
                                            )
                                        );
                                        echo $thisYearSickLeaveHours;
                                    }
                                    ?></td>
                                    <td><?php
                                    $thisYearAnnualLeaveHours = 0;
                                    if (isset($this->userRecentLeaveList[$_userInfo['uid']], $this->userRecentLeaveList[$_userInfo['uid']]['thisYear'][administration::LEAVE_TYPE_ANNUAL])) {
                                        $thisYearAnnualLeaveHours = array_sum(array_column($this->userRecentLeaveList[$_userInfo['uid']]['thisYear'][administration::LEAVE_TYPE_ANNUAL], 'leave_period'));
                                        echo $thisYearAnnualLeaveHours;
                                    }
                                    ?></td>
                                    <td><?php
                                    $_annualDays = empty($this->userAttributes[$_userInfo['uid']]['sinho_annual_leave_days']) ? $this->hostConfig->sinho_feature_list['default_annual_leave_days'] : $this->userAttributes[$_userInfo['uid']]['sinho_annual_leave_days'];
                                    $_userJoinDateObj = empty($this->userAttributes[$_userInfo['uid']]['sinho_join_date']) ? date_create(date('Y-m-d', $_userInfo['reg_time'])) : date_create($this->userAttributes[$_userInfo['uid']]['sinho_join_date']);
                                    $_dateDiffObj = date_diff($nowDateObj, $_userJoinDateObj);
                                    //echo $_annualDays, '/' . $_dateDiffObj->y, '/' . $_userJoinDateObj->format('Y-m-d') ;
                                    if ($_dateDiffObj->y == 0) {
                                        _e('入职不满一年');
                                    } else if ($_userInfo['forbidden'] == 1) {
                                        echo '---';
                                    } else {
                                        if ($thisYearAnnualLeaveHours >=$_annualDays * 8) {
                                            _e('今年年假余额不足');
                                            //echo "$thisYearAnnualLeaveHours >=$_annualDays";
                                        } else if ( ($thisYearPrivateLeaveHours + $thisYearSickLeaveHours) >= 21.75 * 8) {
                                            _e('当年事假病假过多，禁止请年假');
                                        } else if ( ($lastYearPrivateLeaveHours + $lastYearSickLeaveHours) >=21.75 * 8 && $lastYearAnnualLeaveHours>0) {
                                            _e('去年年假后的事假病假过多,禁止今年请年假');
                                        } else {
                                            echo $_annualDays * 8 - $thisYearAnnualLeaveHours;
                                        }
                                    }
                                    //echo '(' . $_userJoinDateObj->format('Y-m-d') . ')';
                                    ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
.icb-content-wrap .icon:not(.icon-attach){
background: #cc0d0d;
}
</style>
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
        console.info(leaveList[i], tmpStartTime, tmpEndTime);
        while(tmpStartTime < tmpEndTime) {
            tmpDate = new Date(tmpStartTime * 1000);
            console.info(tmpDate.toLocaleDateString());
            // if (tmpDate.getDay() % 6 == 0) { // 周六日不显示请假状态
            //     tmpStartTime += 24 * 60 * 60;
            //     continue;
            // }
            tmpTdId = '#td_' + userId + '_' + tmpDate.getDate();
            switch(leaveList[i].leave_type) {
                <?php foreach ($this->leaveTypeList as $_key=>$_leaveItemInfo) { ?>
                case <?php echo $_key;?>: // <?php echo $_leaveItemInfo['name']."\r\n";?>
                    $(tmpTdId).html($(tmpTdId).html() + '<i class="icon <?php echo $_leaveItemInfo['icon'];?>"/>');
                    break;
                <?php }?>
                default:
                    $(tmpTdId).html($(tmpTdId).html() + '<i class="icon icon-leave"/>');
                    break;
            }
            // 获取第二天的时间。 如果请假截止时间 大于  第二天上班时间，那么第二天也是请假
            tmpStartTime += 24 * 60 * 60;
            tmpDateObj   = new Date(tmpStartTime*1000);
            // 获取第二天8点钟的时间。 此处的上班时间，应该是从配置文件中读取，不应该写固定的时间点
            tmpDateObj   = new Date(tmpDateObj.getFullYear(), tmpDateObj.getMonth(), tmpDateObj.getDate(), 8,0,0);
            //console.info(tmpDateObj.getFullYear(), tmpDateObj.getMonth(), tmpDateObj.getDate(), tmpDateObj.getHours());
            tmpStartTime = tmpDateObj.getTime()/1000;
        }
    }
}
$(function () {
    var leaveList = JSON.parse('<?php echo json_encode($this->leaveList, JSON_UNESCAPED_UNICODE)?>');
    loadLeaveDataIntoTable(leaveList);
});

</script>
<?php View::output('admin/global/footer.php'); ?>
