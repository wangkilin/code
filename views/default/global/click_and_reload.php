<!doctype html><html><head><meta charset="utf-8" /><title><?php _e('异常访问'); ?></title>
<script src="<?php echo G_STATIC_URL; ?>/js/jquery.2.js" type="text/javascript"></script>
</head>
<body class="icb-404">
<p><?php _e('监测到异常行为！'); ?></p><br/>
<p id="reload_link_pool" style="text-align:center;"><a><?php _e('继续访问'); ?></a><a><?php _e('继续访问'); ?></a><a><?php _e('继续访问'); ?></a></p>
<script type="text/javascript">
function clickReload(){window.location.reload();}
$(function(){
var randomI = new Date().getMilliseconds() % 3;
$('#reload_link_pool>a').hide();
$('#reload_link_pool>a').eq(randomI).show().click(function () {window.location.reload();});
});
$(function () {/* 防止手机浏览器在页面后面加入垃圾广告 */
$(document).scroll(function () {$("#last-one-flag").nextAll(':not(#scrollUp,.datetimepicker)').remove();});
});
</script>
<div id="last-one-flag"></div></body></html>
