<div class="icb-footer-wrap">
	<div class="icb-footer">
        <div class="row">
		<?php _e("版权所有");?> © <?php echo date('Y'); ?> </span>
		<span class="hidden-xs">Powered By <a href="#" target="blank">大涨吧 dazhang8.com <?php echo G_VERSION; ?></a></span>
        <span class="hidden-xs">备案号：<a href="http://www.beian.miit.gov.cn" target="blank">京ICP备10215645号</a></span>
        </div>
        <div class="row">
		<?php _e("友情链接");?> ：</span>
		<span class="hidden-xs"><a href="//www.icodebang.com" target="blank">爱码帮</a></span>
        <span> | </span>
        <span class="hidden-xs"><a href="//www.isinho.com" target="blank">新禾文化</a></span>
        <span> | </span>
        <span class="hidden-xs"><a href="//www.kinful.com" target="blank">Kinful</a></span>
        <span> | </span>
        <span class="hidden-xs"><a href="//www.devboy.cn" target="blank">开发者</a></span>
        <span> | </span>
        <span class="hidden-xs"><a href="//www.dazhang8.com" target="blank">大涨吧</a></span>
        <span> | </span>
        <span class="hidden-xs"><a href="//www.mysqlworkbench.cn" target="blank">MySQL Workbench</a></span>
        </div>
	</div>
</div>

<a id="icb-goto-top" class="icb-goto-top hidden-xs" href="javascript:;" onclick="$.scrollTo(1, 600, {queue:true});" title="<?php _e('返回顶部'); ?>" data-toggle="tooltip"><i class="icon icon-up"></i></a>
<script type="text/javascript">
<?php if (strpos($_SERVER['HTTP_HOST'], 'dazhang8.com')) {
    $baiduStatCode = "4f1556e9ba8cb0ab9522277102269cb5";
}

if ($baiduStatCode) {
?>
// 百度统计
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "https://hm.baidu.com/hm.js?<?php echo $baiduStatCode;?>";
  var s = document.getElementsByTagName("script")[0];
  s.parentNode.insertBefore(hm, s);
})();
<?php }?>
// 百度自动推送
(function(){
    var bp = document.createElement('script');
    var curProtocol = window.location.protocol.split(':')[0];
    if (curProtocol === 'https') {
        bp.src = 'https://zz.bdstatic.com/linksubmit/push.js';
    }
    else {
        bp.src = 'http://push.zhanzhang.baidu.com/push.js';
    }
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(bp, s);
})();
</script>

<!--
<script src="https://translate.google.cn/translate_a/element.js?cb=googleTranslateCallback"></script> -->

</body>
</html>
