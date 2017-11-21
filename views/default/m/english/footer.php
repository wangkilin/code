
<!-- DO NOT REMOVE -->
<div id="icb-modal-window" class="icb-modal-window"></div>

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
</body>
</html>
