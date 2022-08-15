<div class="container margin20"></div>
<div class="icb-footer-wrap">
    <div class="container text-center">
            <div class="margin20">
                <a href="/page/website_aboutus.html">关于我们</a>
                •
                <a href="/page/website_donate.html">赞助与捐赠</a>
                •
                <a href="">站点地图</a>
                •
                <a href="/page/website_contact_us.html">联系我们</a>
                •
                <a href="/page/website_cooperation.html">网站合作</a>
                •
                <a href="/page/website_disclaimer.html">免责声明</a>
            </div>
            <div>
                友情链接：
                <a href="http://www.isinho.com">新禾文化</a>
                •
                <a href="//www.devboy.cn">开发者</a>
                •
                <a href="//www.mysqlworkbench.cn">MySQL Workbench</a>
                •
                <a href="//www.icodebang.cn">爱码帮</a>
                •
                <a href="mailto:爱码帮<icodebang@126.com>?subject=交换链接">交换链接</a>
            </div>
            <div class="margin10">
                <div style="color:#fff">本站为个人网站，部分内容来自于互联网，版权归原作者所有。如本站内容有侵权，敬请来信联系我们，我们将及时删除清理。</div>
                <div class="margin5">
                © <?php echo date('Y'); ?> <?php echo get_setting('site_name');?><?php if(get_setting('icp_beian')){ ?><span class="hidden-xs"> - <?php echo get_setting('icp_beian'); ?><?php } ?></span>

                <span class="hidden-xs">Powered By <a href="//www.icodebang.com" target="blank">iCodebang.com <?php echo G_VERSION; ?></a></span>
                <span class="hidden-xs">备案号：<a href="https://beian.miit.gov.cn/" target="blank">京ICP备10215645号</a></span>
                </div>
            </div>
		<?php if (is_mobile(true)) { ?>
			<div class="container">
				<div class="row">
					<p align="center"><?php _e('版本切换'); ?>: <b><?php _e('电脑版'); ?></b> | <a href="m/ignore_ua_check-FALSE"><?php _e('手机版'); ?></a></p>
				</div>
			</div>
		<?php } ?>
	</div>
</div>

<a id="icb-goto-top" class="icb-goto-top hidden-xs" href="javascript:;" onclick="$.scrollTo(1, 600, {queue:true});" title="<?php _e('返回顶部'); ?>" data-toggle="tooltip"><i class="icon icon-up"></i></a>


<!-- DO NOT REMOVE -->
<div id="icb-modal-window" class="icb-modal-window"></div>

<?php echo get_setting('statistic_code'); ?>
<div style="display:none;" id="__crond">
	<script type="text/javascript">
		$(document).ready(function () {
			$('#__crond').html(unescape('%3Cimg%20src%3D%22' + G_BASE_URL + '/crond/run/<?php echo TIMESTAMP; ?>.html%22%20width%3D%221%22%20height%3D%221%22%20/%3E'));
		});
	</script>
</div>
<!--把button放在右下角，这个是可以随意修改的，你可以改成一个链接一个图片之类的-->
<div id="google_translate_element" style="position:absolute;top:10px;right:10px;z-index:2000;opacity:0.7"></div>

<?php View::output('global/debuger.php'); ?>
<!-- / DO NOT REMOVE -->
<!-- <script type="text/javascript" src="/static/js/editor/ckeditor.4.11/plugins/codesnippet/lib/highlight/highlight.pack.js"></script> -->
<script src="//cdn.icodebang.com/static/js/highlight/highlight.pack.js"></script>
<script type="text/javascript" src="//cdn.icodebang.com/static/js/editor/ckeditor.4.11/plugins/codesnippet/lib/highlight/highlightjs-line-numbers.min.js"></script>
<script type="text/javascript">
$(function () {
$('pre>code').each(function(index, element) {
    $(this).data('data-code', $(this).html());
    $(this).parent().append('<div class="code_tools"><!--<a href="javascript:;" class="select_all icon-insert-template-" title="全选">全选</a>--><a href="javascript:;" class="copy_all icon-files-empty-" title="复制">复制</a><textarea class="code__for__copy"></textarea></div>');
    if ($(this).attr("class")) {
        var highlightClass = $(this).attr("class").replace(/brush:([^;]*);?/i, '$1');
        var classMap = {py:'python', js:'javascript','cpp':'c++','plain':'文本','xhtml':'html'};
        var lang = typeof classMap[highlightClass] == 'undefined' ? '':classMap[highlightClass];
    }

	$(this).parent().find('.select_all').attr("title","全选当前"+lang+"代码");
	$(this).parent().find('.copy_all').attr("title","复制当前"+lang+"代码");
	$(this).parent().find('.save_code').attr("title","保存当前代码");
    $(this).parent().find('.code__for__copy').val(this.innerText);
    $(this).parent().find('.code__for__copy')[0].innerHTML = this.innerText;

    $(this).parent().find('.select_all:last').click(function(event) {
        selectAllCode($(event.target).closest('pre').find('code')[0]);
    });
    $(this).parent().find('.copy_all:last').click(function(event) {
        copyCode(event);
	});
});



//hljs.initHighlightingOnLoad(); // 启用代码高亮
//console.info($('code.hljs').length);
//$('pre>code, .content .codebody').each(function(i, block) { // 设置代码行号
$('pre>code').each(function(i, block) { // 设置代码行号
        $(this).removeAttr('class');// 移除原来的样式， 重新自动添加样式
        hljs.configure({useBR: $(this).find('br').length>0});
        hljs.highlightBlock(block);
        hljs.lineNumbersBlock(block, {singleLine:true});
    });
});


//
$('div.code>div,div.content div.sample-code-container').each(function(i, block) {
    hljs.configure({useBR: $(this).find('br').length>0});
    hljs.highlightBlock(block);
});
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
<!-- 百度统计 -->
<?php View::output('global/baidu_stat.php'); ?>
<!--
<script src="https://translate.google.cn/translate_a/element.js?cb=googleTranslateCallback"></script> -->

<!-- 防止手机浏览器在页面后面加入垃圾广告, 放置一个标志性的元素。 所有必须的元素， 都要放到这个标识元素前面  -->
<div id="last-one-flag"></div>
</body>
</html>
