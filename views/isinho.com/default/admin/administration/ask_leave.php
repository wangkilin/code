<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
            <h3>
                <ul class="nav nav-tabs">
                    <li class="<?php echo $_GET['tab']=='report'? '':'active';?>"><a href="#index"  data-toggle="tab"><?php _e('请假管理'); ?></a></li>
                    <li class="<?php echo $_GET['tab']=='report'? 'active':'';?>"><a href="#monthly-report" data-toggle="tab"><?php _e('统计报告'); ?></a></li>
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
                    <div class="col-sm-2"><a href="/admin/administration/ask_leave/year_month-<?php echo date('Ym', strtotime($this->leaveYear.$this->leaveMonth.'01 -1month'));?>">上一月(<?php echo date('Y-m', strtotime($this->leaveYear.$this->leaveMonth.'01 -1month'));?>)</a></div>
                    <div class="col-sm-8 text-center"><strong><?php echo date('Y-m', strtotime($this->leaveYear.$this->leaveMonth.'01'));?></strong></div>
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
            </div>

            <div class="tab-pane<?php echo $_GET['tab']=='report'? 'active':'';?>" id="monthly-report">
                <div class="clearfix padding20">
                    <div class="col-sm-4"></div>
                    <div class="col-sm-1 text-right">
                        <label class="line-height-25">报告月份:</label>
                    </div>
                    <div class="col-sm-2 text-right icon-date-container">
                        <input id="year_month" type="text" class="form-control icon-indent js-date-input js-monthpicker" placeholder="开始月份" value="<?php echo preg_replace('/^(\d{4})/', '\1-', $_GET['year_month']) ; ?>" readonly>
                        <i class="icon icon-date"></i>
                        <i class="icon icon-date-delete icon-delete"></i>
                    </div>
                    <span class="mod-symbol col-xs-1 col-sm-1">-</span>
                    <div class="col-sm-2 text-right icon-date-container">
                        <input id="end_year_month" type="text" class="form-control icon-indent js-date-input js-monthpicker" placeholder="结束月份" value="<?php echo preg_replace('/^(\d{4})/', '\1-', $_GET['end_year_month']) ; ?>" readonly>
                        <i class="icon icon-date"></i>
                        <i class="icon icon-date-delete icon-delete"></i>
                    </div>
                    <div class="col-sm-2 text-right">
                        <a href="javascript:window.location.href='/admin/administration/ask_leave/tab-report__year'+'_'+'month'+'-'+$('#year_month').val().replace('-','')+'__end_year'+'_'+'month'+'-'+$('#end_year_month').val().replace('-','');" class="btn btn-primary btn-sm date-seach">确认查询</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2"><a href="/admin/administration/ask_leave/tab-report__year_month-<?php echo date('Ym', strtotime($this->leaveYear.$this->leaveMonth.'01 -1month'));?>">上一月(<?php echo date('Y-m', strtotime($this->leaveYear.$this->leaveMonth.'01 -1month'));?>)</a></div>
                    <div class="col-sm-8 text-center"><strong><?php echo date('Y-m', strtotime($this->leaveYear.$this->leaveMonth.'01'));?></strong></div>
                    <div class="col-sm-2 text-right"><a href="/admin/administration/ask_leave/tab-report__year_month-<?php echo date('Ym', strtotime($this->leaveYear.$this->leaveMonth.'01 +1month'));?>">(<?php echo date('Y-m', strtotime($this->leaveYear.$this->leaveMonth.'01 +1month'));?>)下一月</a></div>
                </div>
                <div class="table-responsive">
                    <?php $year = $this->leaveYear; $month = $this->leaveMonth;?>
                        <table  class="table table-striped table-bordered" >
                            <thead>
                                <tr>
                                    <th rowspan=2 style="width:20px">#</th>
                                    <th rowspan=2 class="col-sm-1"><span class="col-sm-12 no-padding">姓名&年假起始</span></th>
                                    <th rowspan=2>请假统计信息:<?php if ($_GET['year_month']!=$_GET['end_year_month']) { echo date('Y-m', strtotime($_GET['year_month'].'01')), ' ~ ', date('Y-m', strtotime($_GET['end_year_month'].'01')); } else {echo date('Y-m', strtotime($_GET['year_month'].'01')) ;}?></th>
                                    <th colspan=5 class="col-sm-2">报告周期</th>
                                    <th colspan=3 class="col-sm-2">上年度</th>
                                    <th colspan=4 class="col-sm-2">本年度</th>
                                </tr>
                                <tr>
                                    <th>事假</th>
                                    <th>病假</th>
                                    <th>年假</th>
                                    <th>全部请假</th>
                                    <!-- <th class="col-sm-1">加班</th> -->
                                    <th>周末带稿量</th>
                                    <!-- 上年度 -->
                                    <th>事假</th>
                                    <th>病假</th>
                                    <th>年假</th>
                                    <!-- 本年度 -->
                                    <th>事假</th>
                                    <th>病假</th>
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
                                    <td style="width:20px"><?php echo $i++;?></td>
                                    <td class="col-sm-1"><?php echo $_userInfo['user_name'], '<br/>',empty($this->userAttributes[$_userInfo['uid']]['sinho_join_date']) ? date('Y-m-d', $_userInfo['reg_time']) : $this->userAttributes[$_userInfo['uid']]['sinho_join_date'];?></td>

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
                                    <td><?php
                                    echo $_leaveInfo['event']['m']>0 ?$_leaveInfo['event']['m'] .'月' : '';
                                    echo $_leaveInfo['event']['d']>0 ?$_leaveInfo['event']['d'] .'天' : '';
                                    echo $_leaveInfo['event']['h']>0 ?$_leaveInfo['event']['h'] .'小时' : '';
                                    ?></td>
                                    <td><?php
                                    echo $_leaveInfo['sick']['m']>0 ?$_leaveInfo['sick']['m'] .'月' : '';
                                    echo $_leaveInfo['sick']['d']>0 ?$_leaveInfo['sick']['d'] .'天' : '';
                                    echo $_leaveInfo['sick']['h']>0 ?$_leaveInfo['sick']['h'] .'小时' : '';
                                    ?></td>
                                    <td><?php
                                    echo $_leaveInfo['annual']['m']>0 ?$_leaveInfo['annual']['m'] .'月' : '';
                                    echo $_leaveInfo['annual']['d']>0 ?$_leaveInfo['annual']['d'] .'天' : '';
                                    echo $_leaveInfo['annual']['h']>0 ?$_leaveInfo['annual']['h'] .'小时' : '';
                                    ?></td>
                                    <td><?php
                                    echo $_leaveInfo['total']['m']>0 ?$_leaveInfo['total']['m'] .'月' : '';
                                    echo $_leaveInfo['total']['d']>0 ?$_leaveInfo['total']['d'] .'天' : '';
                                    echo $_leaveInfo['total']['h']>0 ?$_leaveInfo['total']['h'] .'小时' : '';
                                    ?></td>
                                    <!--<td><?php
                                    echo $_leaveInfo['overtime']['m']>0 ?$_leaveInfo['overtime']['m'] .'月' : '';
                                    echo $_leaveInfo['overtime']['d']>0 ?$_leaveInfo['overtime']['d'] .'天' : '';
                                    echo $_leaveInfo['overtime']['h']>0 ?$_leaveInfo['overtime']['h'] .'小时' : '';
                                    ?></td>-->
                                    <td><?php
                                    echo $_leaveInfo['weekend']['m']>0 ?$_leaveInfo['weekend']['m'] .'月' : '';
                                    echo $_leaveInfo['weekend']['d']>0 ?$_leaveInfo['weekend']['d'] .'天' : '';
                                    echo $_leaveInfo['weekend']['h']>0 ?$_leaveInfo['weekend']['h'] .'小时' : '';
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
                                    //var_dump( $this->userRecentLeaveList[$_userInfo['uid']]['thisYear'][administration::LEAVE_TYPE_ANNUAL]);
                                    //echo '(' . $_userJoinDateObj->format('Y-m-d') . ')';
                                    //echo $this->userAttributes[$_userInfo['uid']]['sinho_recent_one_year_date_end'];
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
                hoursDisabled : '0,1,2,3,4,5,6,7,18,19,20,21,22,23'
        }) ;
        $(this).closest('.ask-leave-single-item').after($item);
    });
    /**
     * 移除请假条目
     */
    $('body').on('click', '.ask-leave-single-item .icon-delete', function () {
        if ($(this).closest('.ask-leave-single-item').siblings('.ask-leave-single-item').length == 0) {
            var $item = $(this).closest('.ask-leave-single-item').clone();
            $item.find('input[name="id[]"]').remove();

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
                    hoursDisabled : '0,1,2,3,4,5,6,7,18,19,20,21,22,23'
            }) ;
            $(this).closest('.ask-leave-single-item').before($item);
        }
        if ($(this).closest('.ask-leave-single-item').find('input[name="id[]"]').length ) {
            $(this).closest('form').append($('<input type="hidden" name="delete_ids[]" value="'+$(this).closest('.ask-leave-single-item').find('input[name="id[]"]').val()+'"/>'));
        }
        $('.js-ajax-feedback').removeClass('fade in bg-warning text-danger').text(''); // 移除错误提醒信息
        $(this).closest('.ask-leave-single-item').remove();
    });

    $('body').on('change', '.ask-leave-single-item input', function () {

        $('.js-ajax-feedback').removeClass('fade in bg-warning text-danger').text(''); // 移除错误提醒信息
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
                minView : 3, // 0:选择到分钟， 1：选择到小时， 2：选择到天,
                hoursDisabled : '0,1,2,3,4,5,6,7,18,19,20,21,22,23'
            });

    $('.icon-delete.icon-date-delete').click (function () {
        $(this).siblings('.js-date-input').val('');
    });

    $('body').on('click', '#js-remove-ask-leave', function () {

        $('.js-ajax-feedback').removeClass('fade in bg-warning text-danger').text('');
        var successCallback = function (response) {
            if (response.errno==-1) {
                $('.js-ajax-feedback').text(response.err).addClass('fade in bg-warning text-danger');
            } else {
                $('#js-sinho-leave-table tbody tr').find('td:not(:first)').html('');
                loadLeaveDataIntoTable(response.rsm);
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

    /**
     * 请假时长如果为8小时， 请假时间为空， 将请假时间填充默认值
     */
    $('body').on('change', '.js-change-input-date', function () {
        if ($(this).val()==8 && $(this).closest('.ask-leave-single-item').find('input[name="leave_start_time[]"]').val()==''
          && $(this).closest('.ask-leave-single-item').find('input[name="leave_end_time[]"]').val()=='') {
            $(this).closest('.ask-leave-single-item').find('input[name="leave_start_time[]"]').val($('#ask-leave-date').text()+' 08:30');

            $(this).closest('.ask-leave-single-item').find('input[name="leave_end_time[]"]').val($('#ask-leave-date').text()+' 08:30');

        }
    });

    /**
     * 点击单元格， 设置考勤
     */
    //$('body').on('click', '#js-sinho-leave-table > tbody > tr > td:not(.weekend)', function() {
    $('body').on('click', '#js-sinho-leave-table > tbody > tr > td', function() {
        var userId    = $(this).parent().data('user-id');
        var userName  = $(this).parent().data('user-name');
        var leaveDate = $(this).data('date').toString();
        var leaveDateDisplay = leaveDate.substr(0,4) + '-' + leaveDate.substr(4,2) + '-' + leaveDate.substr(6,2);
        var callback = function (response) {
            //console.info(response);
            var onshowCallback = function () {
                $("#sinho_user_ids").find('option[value='+userId+']').attr('selected','selected').removeAttr('disabled');
                if (response.rsm.length) {
                    $("#sinho_user_ids").find('option[value!='+userId+']').attr('disabled','disabled');
                }

                $("#sinho_user_ids").multiselect({
                        nonSelectedText : '<?php _e('---- 选择责编 ----');?>',
                        maxHeight       : 200,
                        buttonWidth     : '100%',
                        allSelectedText : '<?php _e('已选择所有人');?>',
                        numberDisplayed : 7, // 选择框最多提示选择多少个人名
                });
                // 设置下拉框选项
                var options = '<?php foreach ($this->leaveTypeList as $_key => $_optionItemInfo) { echo '<option value="'.$_key.'">'.$_optionItemInfo['name'].'</option>';} ?>';
                $('#leave_type').append(options);

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
                    hoursDisabled : '0,1,2,3,4,5,6,7,18,19,20,21,22,23'
                }) ;

                $('#js-submit-form').click(function() {
                    $('.js-ajax-feedback').removeClass('fade in bg-warning text-danger').text('');
                    var successCallback = function (response) {
                        if (response.errno==-1) {
                            $('.js-ajax-feedback').text(response.err).addClass('fade in bg-warning text-danger');
                        } else {
                            $('#js-sinho-leave-table tbody tr').find('td:not(:first)').html('');
                            loadLeaveDataIntoTable(response.rsm);
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
                    user_option_list     : '<?php echo $this->itemOptions;?>',
                    user_id             : userId,
                    user_name           : userName,
                    leave_date          : leaveDate,
                    leave_date_display  : leaveDateDisplay,
                    //leave_date_start    : leaveDateDisplay.substr(5) +' ' + currentDate.getHours() + ':00',
                    //leave_date_end      : leaveDateDisplay.substr(5) +' ' + currentDate.getHours() + ':00',
                    //options             : options
                });

            ICB.modal.dialog(html, onshowCallback);

            // 如果是编辑请假， 显示删除按钮， 允许删除请假
            if (response.rsm.length) {
                $('#js-remove-ask-leave').removeClass('hidden');
            }
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
