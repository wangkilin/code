<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
            <h3>
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#index"><?php _e('假期设置'); ?></a></li>
                </ul>
            </h3>
        </div>


        <div class="mod-body tab-content padding5px">
            <div class="tab-pane active" id="index">


                <div class="table-responsive">
                    <table class="table table-bordered calendar-container">
                        <tr>
                         <td><strong>表格颜色说明：</strong></td>
                         <td class="selday">休息日</td>
                         <td>工作日</td>
                        </tr>
                    </table>
                </div>
                <br/>
                <table  class="table table-bordered calendar-container">
                    <tbody>
                        <tr>
                            <td class="calTit" colspan="7" >

                                <a href="" title="上一年" id="nianjian" class="ymNaviBtn lsArrow"></a>
                                <a href="" title="上一月" id="yuejian" class="ymNaviBtn icon icon-left pull-left md-tip" data-toggle="tooltip"></a>

                                <div class="text-center  calendar-yearmonth-selection col-sm-10 col-sm-offset-1">
                                    <div id="dateSelectionRili" class="dateSelectionRili" onclick="dateSelection.show()">
                                        <span id="nian" class="topDateFont">2021</span>
                                        <span class="topDateFont">年</span>
                                        <span id="yue" class="topDateFont">2</span>
                                        <span class="topDateFont">月</span>
                                        <span class="dateSelectionBtn cal_next" onclick="dateSelection.show()">▼</span>
                                        &nbsp;&nbsp;<span id="GZ" class="topDateFont">  农历辛丑年&nbsp;【牛年】</span>

                                    </div>


                                    <!--新加导航功能-->
                                    <div id="dateSelectionDiv" class="bg-warning">
                                        <div id="dateSelectionHeader"></div>
                                        <div id="dateSelectionBody">
                                            <div id="yearList" class="row nomargin bg-primary">
                                                <div id="yearListPrev" class="col-sm-1 nopadding" onclick="dateSelection.prevYearPage()">&lt;</div>
                                                <div id="yearListContent"class="col-sm-10 nopadding" >
                                                    <span id="SY2019" class="year" onclick="dateSelection.setYear(2019)">2019</span>
                                                    <span id="SY2020" class="year" onclick="dateSelection.setYear(2020)">2020</span>
                                                    <span id="SY2021" class="year curr" onclick="dateSelection.setYear(2021)">2021</span>
                                                    <span id="SY2022" class="year" onclick="dateSelection.setYear(2022)">2022</span></div>
                                                <div id="yearListNext"class="col-sm-1 nopadding"  onclick="dateSelection.nextYearPage()">&gt;</div>
                                            </div>
                                            <div id="dateSeparator"></div>
                                            <div id="monthList" class="col-sm-12">
                                                <div id="monthListContent"><span id="SM0" class="month" onclick="dateSelection.setMonth(0)">1</span><span id="SM1" class="month curr" onclick="dateSelection.setMonth(1)">2</span><span id="SM2" class="month" onclick="dateSelection.setMonth(2)">3</span><span id="SM3" class="month" onclick="dateSelection.setMonth(3)">4</span><span id="SM4" class="month" onclick="dateSelection.setMonth(4)">5</span><span id="SM5" class="month" onclick="dateSelection.setMonth(5)">6</span><span id="SM6" class="month" onclick="dateSelection.setMonth(6)">7</span><span id="SM7" class="month" onclick="dateSelection.setMonth(7)">8</span><span id="SM8" class="month" onclick="dateSelection.setMonth(8)">9</span><span id="SM9" class="month" onclick="dateSelection.setMonth(9)">10</span><span id="SM10" class="month" onclick="dateSelection.setMonth(10)">11</span><span id="SM11" class="month" onclick="dateSelection.setMonth(11)">12</span></div>
                                                <div style="clear: both;"></div>
                                            </div>
                                            <div id="dateSelectionBtn">
                                                <div id="dateSelectionTodayBtn" class="btn btn-sm btn-primary" onclick="dateSelection.goToday()">今天</div>
                                                <div id="dateSelectionCancelBtn" class="btn btn-sm btn-default" onclick="dateSelection.hide()">取消</div>
                                                <div id="dateSelectionOkBtn" class="btn btn-sm btn-success" onclick="dateSelection.go()">确定</div>
                                            </div>
                                        </div>
                                        <div id="dateSelectionFooter"></div>
                                    </div>
                                </div>
                                <a id="nianjia" title="下一年" class="ymNaviBtn rsArrow" style="float:right;"></a>
                                <a id="yuejia" title="下一月" class="ymNaviBtn icon icon-right pull-right md-tip" data-toggle="tooltip"></a>
                                <!--	<a id="jintian" href="#" title="今天" class="btn" style="float:right; margin-top:-2px; font-size:12px; text-align:center;">今天</a>-->

                            </td>
                        </tr>
                        <tr class="bg-info">
                            <td width="100" class="weekend"><strong>星期日</strong></td>
                            <td width="100"><strong>星期一</strong></td>
                            <td width="100"><strong>星期二</strong></td>
                            <td width="100"><strong>星期三</strong></td>
                            <td width="100"><strong>星期四</strong></td>
                            <td width="100"><strong>星期五</strong></td>
                            <td width="100" class="weekend"><strong>星期六</strong></td>
                        </tr>
                        <script language="JavaScript">

                            var gNum;
                            var tdString, sunTdString;
                            for (var i = 0; i < 6; i++) {
                                sunTdString = '';
                                document.write('<tr align=center height="50" id="tt">');
                                for (var j = 0; j < 7; j++) {
                                    gNum = i * 7 + j ;
                                    tdString = '<td  id="GD' + gNum + '" on="0"';
                                    if (j % 6 == 0)  tdString += ' class="weekend"';
                                    //if (j==0) tdString += ' class="pull-right"';
                                    tdString += '>';
                                    tdString += '<font  id="SD' + gNum + '" ';
                                    //tdString += ' onMouseOver="mOvr(this,' + gNum + ');"  onMouseOut="mOut(this);"';
                                    tdString += '  TITLE="">  </font><br><font  id="LD' + gNum + '"  size=2  style="white-space:nowrap;overflow:hidden;cursor:default;">  </font></td>';

                                    document.write(tdString);
                                }
                                document.write('</tr>');
                            }
                        </script>
                    </tbody>
                </table>

                <table  class="table table-bordered bg-info">
                  <tbody>
                        <tr>
                            <td><input type="button" value="元 旦" class="btn btn-sm btn-primary" onclick="dateSelection.goHoliday(0)"></td>
                            <td><input type="button" value="春 节" class="btn btn-sm btn-primary" onclick="dateSelection.goHoliday(1)"></td>
                            <td><input type="button" value="清 明" class="btn btn-sm btn-primary" onclick="dateSelection.goHoliday(3)"></td>
                            <td><input type="button" value="五 一" class="btn btn-sm btn-primary" onclick="dateSelection.goHoliday(4)"></td>
                            <td><input type="button" value="端 午" class="btn btn-sm btn-primary" onclick="dateSelection.goHoliday(5)"></td>
                            <td><input type="button" value="中 秋" class="btn btn-sm btn-primary" onclick="dateSelection.goHoliday(8)"></td>
                            <td><input type="button" value="国 庆" class="btn btn-sm btn-primary" onclick="dateSelection.goHoliday(9)"></td>
                        </tr>
                    </tbody>
                </table>

            </div>
            <div class="row"><div class="col-sm-12">&nbsp;</div></div>
            <div class="row">
                <div class="col-sm-6 text-right"><input type="button" class="btn btn-success" onclick="setSchedule(hDays, dateSelection.currYear, dateSelection.currMonth+1);" value="保存当月设置"/></div>
                <div class="col-sm-6 text-left"><input type="button" class="btn btn-primary" onclick="setSchedule(hDays, dateSelection.currYear);" value="保存全年设置"/></div>
            </div>
        </div>
    </div>
</div>

<script language="JavaScript">
	//提交
function h_submit(){
	alert(hDays);
}
  //重置
 function rebuild(){
	hDays=[];
}
 </script>
<div id="details" style="margin-top:-1px;"></div>
<script type="text/javascript">
/**
 * 设置作息时间
 * @param array daysList 日期列表YYYY-mm-dd格式
 * @param int   year     年YYYY
 * @param int   month    月 1-12
 */
function setSchedule (daysList, year, month) {
    //console.info(hDays, dateSelection);
    // 传递后后台的参数格式
    var params = {
        year : year,
        days : []
    };
    // 判断是否有月份
    var prefix = '' + year;
    if (undefined !== month) {
        month = month > 9 ? month : ('0'+month);
        prefix += month;
        params.month = month;
    }
    // 获取有效的日期数据
    for(var i in daysList) {
        if (daysList[i].indexOf(prefix)===0) {
            params.days.push(daysList[i]);
        }
    }
    // 如果日期为空， 传递一个0。 否则空数组传递不过去
    if (params.days.length==0) {
        params.days.push(0);
    }
    // 发送请求
    ICB.ajax.requestJson(G_BASE_URL + '/admin/ajax/administration/set_holiday/', params);

    return false;
}
$(function () {
    // 去除多余的节日
    for(var i=sFtv.length-1; i>=0; i--) {
        if (sFtv[i].indexOf('*')<0) {
            sFtv.splice(i, 1);
        }
    }
    for(var i=lFtv.length-1; i>=0; i--) {
        if (lFtv[i].indexOf('*')<0) {
            lFtv.splice(i, 1);
        }
    }
    for(var i=wFtv.length-1; i>=0; i--) {
        if (wFtv[i].indexOf('*')<0) {
            wFtv.splice(i, 1);
        }
    }

    // 载入默认的数据
    var schedule = JSON.parse('<?php echo json_encode($this->scheduleList);?>');
    // 初始化日历
    loadCalendarData(schedule);
});

</script>

<?php View::output('admin/global/footer.php'); ?>
