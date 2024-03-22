<!doctype html><html><head><meta charset="utf-8" /><title><?php _e('异常访问'); ?></title>
<script src="<?php echo G_STATIC_URL; ?>/js/jquery.2.js" type="text/javascript"></script>
<!--[if lte IE 8]>
	<script type="text/javascript" src="<?php echo G_STATIC_URL; ?>/js/respond.js"></script>
	<![endif]-->
</head>
<body class="icb-404">
	<div class="icb-404-wrap container">
		<div class="row">
			<p><?php _e('异常访问'); ?>，<?php _e('请检查是否存在恶意访问行为！'); ?></p>
            <br/>
            <p id="reload_link_pool"><a><?php _e('继续访问'); ?></a><a><?php _e('继续访问'); ?></a><a><?php _e('继续访问'); ?></a></p>
		</div>
	</div>
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
<!-- 防止手机浏览器在页面后面加入垃圾广告, 放置一个标志性的元素。 所有必须的元素， 都要放到这个标识元素前面  -->
<div id="last-one-flag"></div>
</body>
</html>
