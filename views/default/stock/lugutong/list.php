<?php View::output('stock/block/header.php'); ?>
<style>
    /*日期选择控件*/
.date_selector,.date_selector *{margin:0;padding:0;width:auto;height:auto;border:none;background:none;text-align:left;text-decoration:none;cursor:pointer;}
.date_selector{position:absolute;z-index:100000;display:none;margin:-1px 0 0;padding:10px;border:1px solid #ccc;background:#fbfbfb;-webkit-box-shadow:0 0 5px #aaa;-moz-box-shadow:0 0 5px #aaa;box-shadow:0 0 5px #aaa;}
.date_selector_ieframe{position:absolute;z-index:99999;display:none;}
.date_selector .nav{width:17.5em;}
.date_selector .nav p{clear:none;}
.date_selector .month_nav,.date_selector .year_nav{position:relative;display:block;margin:0 0 3px;padding:0;text-align:center;}
.date_selector .month_nav{float:left;width:55%;}
.date_selector .year_nav{float:right;margin-right:-8px;width:42%;}
.date_selector .month_name,.date_selector .year_name{font-weight:700;line-height:20px;}
.date_selector .button{position:absolute;top:0;display:block;overflow:hidden;width:18px;height:18px;border:1px solid #ccc;-webkit-border-radius:2px;-moz-border-radius:2px;border-radius:2px;color:#008ee8;text-align:center;font-weight:700;font-size:12px;line-height:16px;}
.date_selector .button:hover,.date_selector .button.hover{border-color:#d77011;background:#ed9c35;color:#fff;cursor:pointer;}
.date_selector .prev{left:0;}
.date_selector .next{right:0;}
.date_selector table#table1{clear:both;margin:0;border-spacing:0;border-collapse:collapse;}
.date_selector th,.date_selector td{padding:0!important;width:2.5em;height:2em;color:#666;text-align:center!important;font-weight:400;}
.date_selector th{font-size:11px;}
.date_selector td{border:1px solid #D7E6F0;background:#fff;color:#666;text-align:center;white-space:nowrap;line-height:2em;}
.date_selector td.today{background:#eee;}
.date_selector td.unselected_month{color:#ccc;}
.date_selector td.selectable_day{cursor:pointer;}
.date_selector td.selected{background:#4B85D2;color:#fff;font-weight:700;}
.date_selector td.selectable_day:hover,.date_selector td.selectable_day.hover{background:#F2F8FF;color:#246594;}
.icb-content-wrap .icon-date {
    position: absolute;
    top: 8px;
    left: 8px;
}
.icb-content-wrap .date-input {
    padding: 3px 0 0 20px;
    text-align: center;
    width: 150px;
    height: 30px;
}
.icb-nav-tabs>li {
    line-height: 28px;
}
</style>
<div class="container icb-container">
    <div class="icb-content-wrap clearfix">
        <ul class="nav nav-tabs icb-nav-tabs" style="padding-left:8px">
            <li class="bg-danger">
            <i class="icon icon-list"></i>持股日期: <?php echo $this->selectDate; ?>

            </li><li class="pull-left">
                                <div class="mod-double">
                                    <input type="text" class="form-control date-input date-start">
                                    <i class="icon icon-date"></i>
                                </div></li>
            <li class="pull-right js-show-fixed-market" data-market="sz"><a href="#">深股通</a></li>
            <li class="active pull-right js-show-fixed-market" data-market="sh"><a href="#">沪股通</a></li>

        </ul>
        <table class="table table-striped">
            <thead>
                <tr class="">
                    <th class="col-sm-2">股票代码</th>
                    <th class="col-sm-2">股票名字</th>
                    <th class="col-sm-2" data-market="sh">沪股通持股占比</th>
                    <th class="col-sm-2" data-market="sh">沪股通持股数</th>
                    <th class="col-sm-2 hide" data-market="sz">深股通持股占比</th>
                    <th class="col-sm-2 hide" data-market="sz">深股通持股数</th>
                    <th class="col-sm-2">持股数较前一日变化</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($this->list as $_code => $val) { ?>
                <tr class="hide" data-market="<?php echo $val['sh_or_sz'] ?>">
                    <td class="col-sm-2"><a target="_blank" href="/lugutong/statistic/id-<?php echo $val['code']; ?>.html"><?php echo $val['code']; ?></a></td>
                    <td class="col-sm-2"><a target="_blank" href="/lugutong/statistic/id-<?php echo $val['code']; ?>.html"><?php echo $val['name']; ?></a></td>
                    <td class="col-sm-2"><?php echo $val['percent']; ?>%</td>
                    <td class="col-sm-2"><?php echo number_format($val['share']); ?></td>
                    <td class="col-sm-2"><?php echo number_format($val['share'] - $this->prevDateList[$_code]['share']); ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript">
$('.js-show-fixed-market').click (function () {
    $('.js-show-fixed-market').removeClass('active');
    $(this).addClass('active');
    var market = $(this).attr('data-market');
    $('tr[data-market],th[data-market]').hide();
    $('tr[data-market="'+market+'"],th[data-market="'+market+'"]').removeClass('hide').show();
});
$('.js-show-fixed-market.active').trigger('click');

$('input.date-input').date_input();
</script>
