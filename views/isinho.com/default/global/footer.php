
    <!-- Start Footer -->
    <footer id="footer" class="wow fadeIn" style="visibility: visible; animation-name: fadeIn;">
        <!-- Footer Top -->
        <div class="footer-top">
            <div class="container">
                <div class="row">
                    <!-- Single Widget -->
                    <div class="col-md-5 col-sm-5 col-xs-12 ">
                        <div class="single-widget about">
                            <div class="footer-logo">
                                <img src="<?php echo G_STATIC_URL; ?>/isinho.com/logo-stamp.png" alt="#"><h4 class="slogan">怀匠心，造精品；立诚信，谋共赢</h4>
                            </div>
                            <p>新禾怀揣匠心，努力将每个经手项目打造成精品。新禾期待与每个客户谋求共同发展，共创佳绩！</p>
                        </div>
                    </div>
                    <!--/ End Single Widget -->
                    <!-- Single Widget -->
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="single-widget contact">
                            <h4>联系方式</h4>
                            <ul class="list">
                                <li><i class="fa fa-qq"></i>Q Q: 994640800 </li>
                                <li><i class="fa fa-wechat"></i>微信: isinho_com </li>
                                <li><i class="fa fa-envelope"></i>邮箱: isinho@126.com</li>
                                <!-- <li><i class="fa fa-map-marker"></i>地址: 沈阳市浑南区招商局大厦A座12层</li> -->
                            </ul>
                        </div>
                    </div>
                    <!--/ End Single Widget -->
                    <!-- Single Widget -->
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <div class="single-widget">
                            <h4 class="wow fadeIn">快速导航</h4>
                            <ul class="social-icon">
                                <li class="active"><a href="/#slider"><i class="fa fa-home"></i>首页</a></li>
                                <li class="active"><a href="/#BookPublish"><i class="fa fa-book"></i>图书出版</a></li>
                                <li class="active"><a href="/#newmedia"><i class="fa fa-link"></i>新媒体</a></li>
                                <li class="active"><a href="/#software"><i class="fa fa-mobile"></i>软件开发</a></li>
                                <li class="active"><a href="/#website"><i class="fa fa-link"></i>网站建设</a></li>
                                <li class="active"><a href="/account/login/"><i class="fa fa-sign-in"></i>后台登录</a></li>
                            </ul>
                        </div>
                    </div>
                    <!--/ End Single Widget -->
                </div>
            </div>
        </div>
        <!--/ End Footer Top -->

        <!-- Copyright -->
        <div class="copyright">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="text">
                            <p>版权所有 &copy; 2020~<?php echo date('Y'); ?> 新禾
                            <span class="hidden-xs">备案号：<a href="https://beian.miit.gov.cn/" target="blank">京ICP备10215645号</a></span>
                            <span class="hidden">新禾&reg;,新禾文化&trade;,沈阳新禾,图书出版,编辑校对,软件开发,沈阳新禾文化传媒有限公司[官网]</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ End Copyright -->
    </footer>
    <!--/ End Footer -->

<a id="icb-goto-top" class="icb-goto-top hidden-xs" href="javascript:;" onclick="$.scrollTo(1, 600, {queue:true});" title="<?php _e('返回顶部'); ?>" data-toggle="tooltip"><i class="icon icon-up"></i></a>


<!-- DO NOT REMOVE -->
<div id="icb-modal-window" class="icb-modal-window"></div>

<?php echo get_setting('statistic_code'); ?>
<!--把button放在右下角，这个是可以随意修改的，你可以改成一个链接一个图片之类的-->
<div id="google_translate_element" style="position:absolute;top:10px;right:10px;z-index:2000;opacity:0.7"></div>
<?php
if (Application::config()->get('system')->debug && Application::config()->get('system')->sites[$_SERVER['HTTP_HOST']]['debug']) {
     View::output('global/debuger.php');
}
?>

<!-- / DO NOT REMOVE -->
<!-- <script type="text/javascript" src="/static/js/editor/ckeditor.4.11/plugins/codesnippet/lib/highlight/highlight.pack.js"></script> -->

    <!-- Modernizr JS -->
    <script type="text/javascript" src="<?php echo G_STATIC_URL; ?>/isinho.com/modernizr.min.js"></script>

    <!-- 页面滚动到指定元素时， 触发事件 Appear JS-->
    <script type="text/javascript" src="<?php echo G_STATIC_URL; ?>/isinho.com/jquery.appear.js"></script>

    <!-- 页面局部效果，需要配合animate.css Animate JS https://www.delac.io/wow/ -->
    <script type="text/javascript" src="<?php echo G_STATIC_URL; ?>/isinho.com/wow.min.js"></script>

    <!-- Onepage Nav JS -->
    <script type="text/javascript" src="<?php echo G_STATIC_URL; ?>/isinho.com/jquery.nav.js"></script>

    <!-- Yt Player -->
    <script type="text/javascript" src="<?php echo G_STATIC_URL; ?>/isinho.com/ytplayer.min.js"></script>

    <!-- Popup JS -->
    <script type="text/javascript" src="<?php echo G_STATIC_URL; ?>/isinho.com/jquery.magnific-popup.min.js"></script>

    <!-- 打字机效果 Typed JS -->
    <script type="text/javascript" src="<?php echo G_STATIC_URL; ?>/isinho.com/typed.min.js"></script>

    <!-- 点击返回页面顶部 Scroll Up JS -->
    <script type="text/javascript" src="<?php echo G_STATIC_URL; ?>/isinho.com/jquery.scrollUp.min.js"></script>

    <!-- 移动端自适应导航菜单 Slick Nav JS -->
    <script type="text/javascript" src="<?php echo G_STATIC_URL; ?>/isinho.com/jquery.slicknav.min.js"></script>

    <!-- 背景图效果，滚动时背景图移动效果 Jquery Steller JS -->
    <script type="text/javascript" src="<?php echo G_STATIC_URL; ?>/isinho.com/jquery.stellar.min.js"></script>

    <!-- 美化下拉框 NICE select JS -->
    <script type="text/javascript" src="<?php echo G_STATIC_URL; ?>/isinho.com/niceselect.js"></script>

    <!-- 动态文字效果 -->
    <script type="text/javascript" src="<?php echo G_STATIC_URL; ?>/isinho.com/morphext.min.js"></script>

    <!-- 动态粒子 Particles JS https://github.com/VincentGarreau/particles.js/ -->
    <!-- <script src="<?php echo G_STATIC_URL; ?>/isinho.com/particles.min.js"></script>
    <script src="<?php echo G_STATIC_URL; ?>/isinho.com/particle-active.js"></script> -->
    <!-- 砖石结构 Masonry JS https://github.com/desandro/masonry -->
	<!-- Isotop JS  https://github.com/metafizzy/isotope -->
	<script type="text/javascript" src="<?php echo G_STATIC_URL; ?>/isinho.com/isotope.pkgd.min.js"></script>
    <!-- Masonry JS https://github.com/desandro/masonry -->
	<script type="text/javascript" src="<?php echo G_STATIC_URL; ?>/isinho.com/masonry.pkgd.min.js"></script>

    <!-- 指定标签滚动到用户视角，提示 Counterup JS -->
    <script type="text/javascript" src="<?php echo G_STATIC_URL; ?>/isinho.com/waypoints.min.js"></script>
    <!-- 计数效果 Counterup JS -->
    <script type="text/javascript" src="<?php echo G_STATIC_URL; ?>/isinho.com/jquery.counterup.min.js"></script>

    <!-- 幻灯片切换 Owl Carousel JS -->
    <script type="text/javascript" src="<?php echo G_STATIC_URL; ?>/isinho.com/owl.carousel.min.js"></script>

    <!-- Bootstrap JS -->
    <script type="text/javascript" src="<?php echo G_STATIC_URL; ?>/isinho.com/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/10.4.1/highlight.min.js"></script>
<script type="text/javascript" src="<?php echo G_STATIC_URL; ?>/js/editor/ckeditor.4.11/plugins/codesnippet/lib/highlight/highlightjs-line-numbers.min.js"></script>
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

    <!-- Google Map JS -->
    <!-- <script type="text/javascript" src="<?php echo G_STATIC_URL; ?>/isinho.com/gmap.js"></script> -->
    <!-- Baidu Map JS -->
    <script type="text/javascript" src="//api.map.baidu.com/getscript?v=2.0&ak=GNMfmaHWOLrt5HMqz4ofS1t1"></script>

<!-- 百度统计 -->
<?php View::output('global/baidu_stat.php'); ?>

<a id="scrollUp" href="./#top" style="position: fixed; z-index: 2147483647; display: none;"><i class="fa fa-angle-up"></i></a>

<!-- 防止手机浏览器在页面后面加入垃圾广告, 放置一个标志性的元素。 所有必须的元素， 都要放到这个标识元素前面  -->
<div id="last-one-flag"></div>
</body>

</html>
