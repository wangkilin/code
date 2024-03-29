<!-- 底部 -->
<footer class="text-center text-color-999">
	Copyright © <?php echo date('Y'); ?> - <?php echo get_setting('site_name'); ?>
	<p><a href="<?php echo base_url(); ?>/<?php if (get_setting('url_rewrite_enable') != 'Y') { ?><?php echo G_INDEX_SCRIPT; ?><?php } ?>ignore_ua_check-TRUE"><?php _e('访问桌面版网站'); ?></a></p>
</footer>
<!-- end 底部 -->

<!-- DO NOT REMOVE -->
<div id="icb-modal-window" class="icb-modal-window"></div>

<!-- nav -->
<div class="nav">
	<ul>
		<?php if ($this->user_id) { ?>
		<li>
			<a href="m/home/" <?php if ($_GET['act'] == 'home') { ?>class="active"<?php } ?>><i class="icon icon-list"></i><?php _e('动态'); ?> <i class="icon-tips new-action" <?php if ($this->user_info['notification_unread'] <= 0 ) { ?>style="display:none;"<?php } ?>></i> </a>
		</li>
		<?php } ?>

		<li>
			<a href="m/" <?php if ($_GET['act'] == 'index') { ?>class="active"<?php } ?>><i class="icon icon-home"></i><?php _e('发现'); ?></a>
		</li>

		<?php if (!$this->user_id) { ?>
		<li>
			<a href="m/topic/" <?php if ($_GET['act'] == 'topic') { ?>class="active"<?php } ?>><i class="icon icon-topic"></i><?php _e('话题'); ?></a>
		</li>
		<?php } ?>

		<li>
			<a href="m/publish/"<?php if ($_GET['act'] == 'publish') { ?>class="active"<?php } ?>><i class="icon icon-ask"></i><?php _e('发起'); ?></a>
		</li>

		<?php if (!$this->user_id) { ?>
		<li>
			<a href="m/search/"<?php if ($_GET['act'] == 'search') { ?>class="active"<?php } ?>><i class="icon icon-search"></i><?php _e('搜索'); ?></a>
		</li>
		<?php }?>
		<li>
			<a class="user"><span class="triangle"></span><i class="icon icon-user"></i><?php _e('我'); ?><?php if (!$this->user_id OR $this->user_info['inbox_unread'] != 0) { ?><i class="icon-tips"></i><?php } ?></a>
		</li>

		<?php if ($this->user_id) { ?>
		<li>
			<a class="more"><span class="triangle"></span><i class="icon icon-more"></i><?php _e('更多'); ?></a>
		</li>
		<?php } ?>
	</ul>
	<div class="icb-popover user active">
		<ul>
			<?php if ($this->user_id) { ?>
			<li>
				<a href="user/<?php echo $this->user_info['url_token']; ?>">
					<span class="label">
						<img class="img" alt="<?php echo $this->user_info['user_name']; ?>" src="<?php echo get_avatar_url($this->user_info['uid'], 'mid'); ?>" />
					</span>
					<?php echo $this->user_info['user_name']; ?>
				</a>
			</li>
			<li>
				<a href="m/inbox/">
					<span class="label">
						<i class="icon icon-inbox"></i>
					</span>
					<?php _e('私信'); ?>
					<?php if ($this->user_info['inbox_unread'] != 0) {?>
						<span class="badge badge-danger"><?php echo $this->user_info['inbox_unread']?></span>
					<?php } ?>
				</a>
			</li>
			<li>
				<a href="m/settings/">
					<span class="label">
						<i class="icon icon-setting"></i>
					</span>
					<?php _e('设置'); ?>
				</a>
			</li>
			<li>
				<a href="account/logout/">
					<span class="label">
						<i class="icon icon-logout"></i>
					</span>
					<?php _e('退出'); ?>
				</a>
			</li>
			<?php } else { ?>
			<li>
				<a href="m/login/">
					<span class="label">
						<i class="icon icon-login"></i>
					</span>
					<?php _e('登录'); ?>
				</a>
			</li>
			<?php if (get_setting('sina_weibo_enabled') == 'Y') { ?>
			<li>
				<a href="account/openid/weibo/bind/<?php echo $return_url; ?>">
					<span class="label">
						<i class="icon icon-weibo"></i>
					</span>
					<?php _e('微博登录'); ?>
				</a>
			</li>
			<?php } ?>
			<?php if (get_setting('qq_login_enabled') == 'Y') { ?>
			<li>
				<a href="account/openid/qq/bind/<?php echo $return_url; ?>">
					<span class="label">
						<i class="icon icon-qq"></i>
					</span>
					<?php _e('QQ登录'); ?>
				</a>
			</li>
			<?php } ?>
			<?php if (get_setting('register_type') == 'open') { ?>
			<li>
				<a href="m/register/">
					<span class="label">
						<i class="icon icon-signup"></i>
					</span>
					<?php _e('注册'); ?>
				</a>
			</li>
			<?php } ?>
			<?php } ?>
		</ul>
	</div>
	<div class="icb-popover more">
		<ul>
			<li>
				<a href="m/search/">
					<span class="label">
						<i class="icon icon-search"></i>
					</span>
					<?php _e('搜索'); ?>
				</a>
			</li>
			<li>
				<a href="m/user/">
					<span class="label">
						<i class="icon icon-user"></i>
					</span>
					<?php _e('用户'); ?>
				</a>
			</li>
			<li>
				<a href="m/topic/">
					<span class="label">
						<i class="icon icon-topic"></i>
					</span>
					<?php _e('话题'); ?>
				</a>
			</li>
		</ul>
	</div>
</div>
<!-- end nav -->

<?php View::output('global/debuger.php'); ?>
<div style="display:none">
	<?php echo get_setting('statistic_code'); ?>
</div>
<!-- / DO NOT REMOVE -->
<?php if (in_weixin()) { ?>
<script src="//res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('.header').hide();
});

wx.config({
	debug: false,
	appId: '<?php echo get_setting("weixin_app_id"); ?>',
	timestamp: '<?php echo TIMESTAMP; ?>',
	nonceStr: '<?php echo $this->weixin_noncestr; ?>',
	signature: '<?php echo $this->weixin_signature; ?>',
	jsApiList: [
		'checkJsApi',
		'onMenuShareTimeline',
		'onMenuShareAppMessage',
		'onMenuShareQQ',
		'onMenuShareWeibo'
		]
});

var WEIXIN_IMG_COVER;

if ($('.icb-question-detail .markitup-box img').length)
{
	WEIXIN_IMG_COVER = $('.icb-question-detail .markitup-box img').first().attr('src');
}
else
{
	WEIXIN_IMG_COVER = '<?php echo G_STATIC_URL; ?>/common/weixin_default_cover.png';
}

wx.ready(function () {
	wx.checkJsApi({
		jsApiList: ["onMenuShareTimeline", "onMenuShareAppMessage", "onMenuShareQQ", "onMenuShareWeibo"],
		success: function() {
			// 朋友圈
			wx.onMenuShareTimeline({
				title: document.title,
				desc: ($('#weixin-desc').text() == '') ? $('meta[name="description"]').attr('content') : $('#weixin-desc').text(),
				link: location.href,
				imgUrl: WEIXIN_IMG_COVER
			});

			// 朋友
			wx.onMenuShareAppMessage({
				title: document.title,
				desc: ($('#weixin-desc').text() == '') ? $('meta[name="description"]').attr('content') : $('#weixin-desc').text(),
				link: location.href,
				imgUrl: WEIXIN_IMG_COVER
			});

			// QQ
			wx.onMenuShareQQ({
				title: document.title,
				desc: ($('#weixin-desc').text() == '') ? $('meta[name="description"]').attr('content') : $('#weixin-desc').text(),
				link: location.href,
				imgUrl: WEIXIN_IMG_COVER
			});

			// 腾讯微博
			wx.onMenuShareWeibo({
				title: document.title,
				desc: ($('#weixin-desc').text() == '') ? $('meta[name="description"]').attr('content') : $('#weixin-desc').text(),
				link: location.href,
				imgUrl: WEIXIN_IMG_COVER
			});
		}
	})
});
</script>
<?php } ?>

<script type="text/javascript" src="/static/js/editor/ckeditor.4.11/plugins/codesnippet/lib/highlight/highlight.pack.js"></script>
<script type="text/javascript" src="/static/js/editor/ckeditor.4.11/plugins/codesnippet/lib/highlight/highlightjs-line-numbers.min.js"></script>
<script type="text/javascript">
$(function () {
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

// 百度统计
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "https://hm.baidu.com/hm.js?681fc9b1c75c25b5868d6bfdea94f7df";
  var s = document.getElementsByTagName("script")[0];
  s.parentNode.insertBefore(hm, s);
})();

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

  window.bughd = window.bughd || function(){};
  bughd("create",{key:"d96d072cefc5ab3c6256af43ec8859bc"})
</script>

<!-- 防止手机浏览器在页面后面加入垃圾广告, 放置一个标志性的元素。 所有必须的元素， 都要放到这个标识元素前面  -->
<div id="last-one-flag"></div>
</body>
</html>
