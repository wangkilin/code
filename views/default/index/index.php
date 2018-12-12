<?php View::output('global/header.php'); ?>

<div class="icb-container">
<style>
.lunhuan {
    position: absolute;
    width: 100%;
    height: 438px;
    background-color: #555;
}
.lunhuan #lunhuanback {
    position: absolute;
    top: 0;
    left: 0;
    overflow: hidden;
    width: 100%;
    height: 438px;
}
.lunhuan #lunhuanback p {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 438px;
    opacity: 0;
}
.lunhuan .lunhuan_main {
    position: relative;
    margin: 0 auto;
    width: 1180px;
    height: 438px;
}
#lunbonum {
    position: absolute;
    right: 20px;
    bottom: 27px;
    z-index: 1000;
    height: 14px;
    line-height: 23px;
}
#lunbonum li {
    float: left;
    margin-left: 5px;
    width: 12px;
    height: 12px;
    cursor: pointer;
    border: 2px solid #fafeff;
    border-radius:50%;
}
#lunbonum li.lunboone {
    width: 50px;
    background-color: #fafeff;
    border-radius:6px;
}
</style>
<div class="lunhuan">
    <div id="lunhuanback">
        <p style="background: url(static/css/default/img/background/sky_star.jpg) 0 0 no-repeat scroll; opacity: 1;">
        <canvas id="J_dotLine" style="background-color: transparent;width: 100%;position: absolute;height: 300px;"></canvas>
        </p>
        <p style="background: url(http://kfw-special.oss-cn-beijing.aliyuncs.com/banner/summit.jpg) center center no-repeat scroll; opacity: 0;">
            <a href="/activity/index/summit" target="_blank" rel="nofollow"></a>
			Truth can only be found in one place: the code.
        </p>

        <p style="background: url(http://kfw-special.oss-cn-beijing.aliyuncs.com/banner/new-shuishou.jpg) center center no-repeat scroll; opacity: 0;">
            <a href="/event/wadi.html?utm_term=180607852862" target="_blank" rel="nofollow"></a>
			Life is like a penis. It is short, but seems long when it gets hard... but you can have fun only when it gets hard.
        </p>
        <p style="background: url(http://kfw-special.oss-cn-beijing.aliyuncs.com/banner/new-transfer.jpg) center center no-repeat scroll; opacity: 0;">
            <a href="/event/company_transfer.html?utm_term=180607852863" target="_blank" rel="nofollow"></a>
        </p>
        <p style="background: url(http://kfw-special.oss-cn-beijing.aliyuncs.com/banner/banner_zc.jpg) center center no-repeat scroll; opacity: 0;">
            <a href="/product/item/info/id/967_3302_0_5.html" target="_blank" rel="nofollow"></a>
        </p>
    </div>
    <div class="lunhuan_main">
        <!-- 轮换中间区域 -->
        <div class="lunhuancenter">
            <ul id="lunbonum">
                <li class="lunboone"></li>
                <li class=""></li>
                <li class=""></li>
                <li class=""></li>
                <li class=""></li>
            </ul>
            <!-- 轮播的页码  结束 -->
        </div>
        <!-- 轮换中间区域结束 -->
    </div>
</div>
<script type="text/javascript">
$(function (containerSelector, slideSelector, controlSelector) {
    //首屏banner效果图
    var ali=$('#lunbonum li');
    var aPage=$('#lunhuanback p');
    var aslide_img=$('.lunhuancenter > div');
    var iNow=0;

    ali.each(function(index){
    $(this).mouseenter(function(){
        slide(index);
    })
    });

    function slide(index){
    iNow=index;
    ali.eq(index).addClass('lunboone').siblings().removeClass();
    aPage.eq(index).siblings().stop().animate({opacity:0},1000);
    aPage.eq(index).stop().animate({opacity:1},1000);
    aslide_img.eq(index).stop().animate({opacity:1,top:0},1000).siblings('div').stop().animate({opacity:0,top:0},1000);
    }

    function autoRun(){
    iNow++;
    if(iNow==ali.length){
        iNow=0;
    }
    slide(iNow);
    }

    var timer=setInterval(autoRun,5000);

    ali.hover(function(){
    clearInterval(timer);
    },function(){
    timer=setInterval(autoRun,5000);
    });

});

(function(window) {
	function Dotline(option) {
		this.opt = this.extend({
			dom: 'J_dotLine', //画布id
			cw: 1000, //画布宽
			ch: 500, //画布高
			ds: 100, //点的个数
			r: 0.5, //圆点半径
			cl: '#000', //颜色
			dis: 100 //触发连线的距离
		}, option);
		this.c = document.getElementById(this.opt.dom); //canvas元素id
		this.ctx = this.c.getContext('2d');
		this.c.width = this.opt.cw; //canvas宽
		this.c.height = this.opt.ch; //canvas高
		this.dotSum = this.opt.ds; //点的数量
		this.radius = this.opt.r; //圆点的半径
		this.disMax = this.opt.dis * this.opt.dis; //点与点触发连线的间距
		this.color = this.color2rgb(this.opt.cl); //设置粒子线颜色
		this.dots = [];
		//requestAnimationFrame控制canvas动画
		var RAF = window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimationFrame || function(callback) {
			window.setTimeout(callback, 1000 / 60);
		};
		var _self = this;
		//增加鼠标效果
		var mousedot = {
			x: null,
			y: null,
			label: 'mouse'
		};
		this.c.onmousemove = function(e) {
			var e = e || window.event;
			mousedot.x = e.clientX - _self.c.offsetLeft;
			mousedot.y = e.clientY - _self.c.offsetTop;
		};
		this.c.onmouseout = function(e) {
			mousedot.x = null;
			mousedot.y = null;
		}
		//控制动画
		this.animate = function() {
			_self.ctx.clearRect(0, 0, _self.c.width, _self.c.height);
			_self.drawLine([mousedot].concat(_self.dots));
			RAF(_self.animate);
		};
	}
	//合并配置项，es6直接使用obj.assign();
	Dotline.prototype.extend = function(o, e) {
		for(var key in e) {
			if(e[key]) {
				o[key] = e[key]
			}
		}
		return o;
	};
	//设置线条颜色
	Dotline.prototype.color2rgb = function(colorStr) {
		var red = null,
			green = null,
			blue = null;
		var cstr = colorStr.toLowerCase(); //变小写
		var cReg = /^#[0-9a-fA-F]{3,6}$/; //确定是16进制颜色码
		if(cstr && cReg.test(cstr)) {
			if(cstr.length == 4) {
				var cstrnew = '#';
				for(var i = 1; i < 4; i++) {
					cstrnew += cstr.slice(i, i + 1).concat(cstr.slice(i, i + 1));
				}
				cstr = cstrnew;
			}
			red = parseInt('0x' + cstr.slice(1, 3));
			green = parseInt('0x' + cstr.slice(3, 5));
			blue = parseInt('0x' + cstr.slice(5, 7));
		}
		return red + ',' + green + ',' + blue;
	}
	//画点
	Dotline.prototype.addDots = function() {
		var dot;
		for(var i = 0; i < this.dotSum; i++) { //参数
			dot = {
				x: Math.floor(Math.random() * this.c.width) - this.radius,
				y: Math.floor(Math.random() * this.c.height) - this.radius,
				ax: (Math.random() * 2 - 1) / 1.5,
				ay: (Math.random() * 2 - 1) / 1.5
			}
			this.dots.push(dot);
		}
	};
	//点运动
	Dotline.prototype.move = function(dot) {
		dot.x += dot.ax;
		dot.y += dot.ay;
		//点碰到边缘返回
		dot.ax *= (dot.x > (this.c.width - this.radius) || dot.x < this.radius) ? -1 : 1;
		dot.ay *= (dot.y > (this.c.height - this.radius) || dot.y < this.radius) ? -1 : 1;
		//绘制点
		this.ctx.beginPath();
		this.ctx.arc(dot.x, dot.y, this.radius, 0, Math.PI * 2, true);
		this.ctx.stroke();
	};
	//点之间画线
	Dotline.prototype.drawLine = function(dots) {
		var nowDot;
		var _that = this;
		//自己的思路：遍历两次所有的点，比较点之间的距离，函数的触发放在animate里
		this.dots.forEach(function(dot) {

			_that.move(dot);
			for(var j = 0; j < dots.length; j++) {
				nowDot = dots[j];
				if(nowDot === dot || nowDot.x === null || nowDot.y === null) continue; //continue跳出当前循环开始新的循环
				var dx = dot.x - nowDot.x, //别的点坐标减当前点坐标
					dy = dot.y - nowDot.y;
				var dc = dx * dx + dy * dy;
				if(Math.sqrt(dc) > Math.sqrt(_that.disMax)) continue;
				// 如果是鼠标，则让粒子向鼠标的位置移动
				if(nowDot.label && Math.sqrt(dc) > Math.sqrt(_that.disMax) / 2) {
					dot.x -= dx * 0.02;
					dot.y -= dy * 0.02;
				}
				var ratio;
				ratio = (_that.disMax - dc) / _that.disMax;
				_that.ctx.beginPath();
				_that.ctx.lineWidth = ratio / 2;
				_that.ctx.strokeStyle = 'rgba(' + _that.color + ',' + parseFloat(ratio + 0.1).toFixed(1) + ')';
				_that.ctx.moveTo(dot.x, dot.y);
				_that.ctx.lineTo(nowDot.x, nowDot.y);
				_that.ctx.stroke(); //不描边看不出效果

				//dots.splice(dots.indexOf(dot), 1);
			}
		});
	};
	//开始动画
	Dotline.prototype.start = function() {
		var _that = this;
		this.addDots();
		setTimeout(function() {
			_that.animate();
		}, 100);
	}
	window.Dotline = Dotline;
}(window));
//调用
//window.onload = function() {
	var dotline = new Dotline({
		dom: 'J_dotLine', //画布id
		cw: 1500, //画布宽
		ch: 300, //画布高
		ds: 30, //点的个数
		r: 2, //圆点半径
		cl: '#FFFFFF', //粒子线颜色
		dis: 100 //触发连线的距离
	}).start();
//}

</script>

	<?php View::output('block/content_nav_menu.php'); ?>
<br/>
	<div class="container">
	  <div class="clearfix">
		  <div class="col-sm-4 clearfix"><?php _e('教程');?></div>

		  <div class="col-sm-4 clearfix"><?php _e('问答');?></div>
		  <div class="col-sm-4 clearfix"><?php _e('文章');?></div>
	  </div>
	  <div class="clearfix">
		  <div class="col-sm-4 clearfix"><?php echo $this->courseList; ?></div>
		  <div class="col-sm-4 clearfix"><?php echo $this->mannualList; ?></div>
		  <div class="col-sm-4 clearfix"><?php echo $this->articleList; ?></div>
	  </div>
    </div>

	<div class="container">
		<div class="row">
			<div class="icb-content-wrap clearfix">
				<div class="col-sm-12 col-md-9 icb-main-content">
					<!-- 新消息通知 -->
					<div class="icb-mod icb-notification-box collapse" id="index_notification">
						<div class="mod-head common-head">
							<h2>
								<span class="pull-right"><a href="account/setting/privacy/#notifications" class="text-color-999"><i class="icon icon-setting"></i> <?php _e('通知设置'); ?></a></span>
								<i class="icon icon-bell"></i><?php _e('新通知'); ?><em class="badge badge-important" name="notification_unread_num"><?php echo $this->user_info['notification_unread']; ?></em>
							</h2>
						</div>
						<div class="mod-body">
							<ul id="notification_list"></ul>
						</div>
						<div class="mod-footer clearfix">
							<a href="javascript:;" onclick="AWS.Message.read_notification(false, 0, false);" class="pull-left btn btn-mini btn-gray"><?php _e('我知道了'); ?></a>
							<a href="notifications/" class="pull-right btn btn-mini btn-success"><?php _e('查看所有'); ?></a>
						</div>
					</div>
					<!-- end 新消息通知 -->
					<!-- tab切换 -->
					<ul class="nav nav-tabs icb-nav-tabs right hidden-xs">
					    <li class="nav-tabs-title"><h2 class="hidden-xs"><?php if ($this->category_info) { ?><?php echo $this->category_info['title']; ?><?php } else if ($this->feature_info) { ?><?php echo $this->feature_info['title']; ?><?php } else { ?><i class="icon icon-list"></i> <?php _e('发现'); ?><?php } ?></h2></li>
						<li<?php if ((!$_GET['sort_type'] OR $_GET['sort_type'] == 'new') AND !$_GET['is_recommend']) { ?> class="active"<?php } ?>><a href="<?php if ($this->feature_info) { ?>feature_id-<?php echo $this->feature_info['id']; ?>__<?php } ?><?php if ($this->category_info['id']) { ?>category-<?php echo $this->category_info['id']; ?><?php } ?>"><?php _e('最新'); ?></a></li>
						<li<?php if ($_GET['sort_type'] == 'unresponsive') { ?> class="active"<?php } ?>><a href="<?php if ($this->feature_info) { ?>feature_id-<?php echo $this->feature_info['id']; ?>__<?php } ?>category-<?php echo $this->category_info['id']; ?>__sort_type-unresponsive"><?php _e('等待回复'); ?></a></li>
						<li<?php if ($_GET['sort_type'] == 'hot') { ?> class="active"<?php } ?>><a href="<?php if ($this->feature_info) { ?>feature_id-<?php echo $this->feature_info['id']; ?>__<?php } ?>category-<?php echo $this->category_info['id']; ?>__sort_type-hot__day-7" id="sort_control_hot"><?php _e('热门'); ?></a></li>
						<li<?php if ($_GET['is_recommend']) { ?> class="active"<?php } ?>><a href="<?php if ($this->feature_info) { ?>feature_id-<?php echo $this->feature_info['id']; ?>__<?php } ?>category-<?php echo $this->category_info['id']; ?>__is_recommend-1"><?php _e('推荐'); ?></a></li>

					</ul>
					<!-- end tab切换 -->

					<?php if ($_GET['sort_type'] == 'hot') { ?>
					<!-- 自定义tab切换 -->
					<div class="icb-tabs">
						<ul>
							<li<?php if ($_GET['day'] == 30) { ?> class="active"<?php } ?>><a href="<?php if ($this->feature_info) { ?>feature_id-<?php echo $this->feature_info['id']; ?>__<?php } ?>sort_type-hot__<?php if ($this->category_info['id']) { ?>__category-<?php echo $this->category_info['id']; ?><?php } ?>__day-30" day="30"><?php _e('30天'); ?></a></li>
						  	<li<?php if ($_GET['day'] == 7) { ?> class="active"<?php } ?>><a href="<?php if ($this->feature_info) { ?>feature_id-<?php echo $this->feature_info['id']; ?>__<?php } ?>sort_type-hot__<?php if ($this->category_info['id']) { ?>__category-<?php echo $this->category_info['id']; ?><?php } ?>__day-7" day="7"><?php _e('7天'); ?></a></li>
						  	<li<?php if ($_GET['day'] == 1) { ?> class="active"<?php } ?>><a href="<?php if ($this->feature_info) { ?>feature_id-<?php echo $this->feature_info['id']; ?>__<?php } ?>sort_type-hot__<?php if ($this->category_info['id']) { ?>__category-<?php echo $this->category_info['id']; ?><?php } ?>__day-1" day="1"><?php _e('当天'); ?></a></li>
						</ul>
					</div>
					<!-- end 自定义tab切换 -->
					<?php } ?>

					<div class="icb-mod icb-explore-list">
						<div class="mod-body">
							<div class="icb-common-list">
								<?php echo $this->posts_list_bit; ?>
							</div>
						</div>
						<div class="mod-footer">
							<?php echo $this->pagination; ?>
						</div>
					</div>
				</div>

				<!-- 侧边栏 -->
				<div class="col-sm-12 col-md-3 icb-side-bar hidden-xs hidden-sm">
					<?php View::output('block/sidebar_feature.php'); ?>
					<?php View::output('block/sidebar_hot_topics.php'); ?>
					<?php View::output('block/sidebar_hot_users.php'); ?>
				</div>
				<!-- end 侧边栏 -->
			</div>
		</div>
	</div>
</div>

<?php View::output('global/footer.php'); ?>
