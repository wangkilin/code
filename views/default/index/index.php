<?php View::output('global/header.php'); ?>

<div class="icb-container">
<style>
.lunhuan {
    position: absolute;
    width: 100%;
    height: 442px;
    background-color: #555;
}
.lunhuan #lunhuanback {
    position: absolute;
    top: 0;
    left: 0;
    overflow: hidden;
    width: 100%;
    height: 442px;
}
.lunhuan #lunhuanback p {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 442px;
    opacity: 0;
}
.lunhuan .lunhuan_main {
    position: relative;
    margin: 0 auto;
    width: 100%;
    height: 440px;
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
    <?php
    $loopPlayList = array(
        '<p style="background: url('.getIcodebangCdnUrl().'/static/css/default/img/background/sky_star.jpg) 0 0 no-repeat scroll; opacity: 1;">
        <canvas id="J_dotLine" style="background-color: transparent;width: 100%;position: absolute;height: 300px;"></canvas>
        </p>',
        '<p style="background: url('.getIcodebangCdnUrl().'/static/css/default/img/background/codeonce.jpg) center center no-repeat scroll; opacity: 0;">
            <a href="'.getIcodebangCdnUrl().'" target="_blank" rel="nofollow"></a>
        </p>',
        '<p style="background: url('.getIcodebangCdnUrl().'/static/css/default/img/background/goodsoftware_1.jpg) center center no-repeat scroll; opacity: 0;">
        </p>',
        '<p style="background: url('.getIcodebangCdnUrl().'/static/css/default/img/background/truth.jpg) center center no-repeat scroll; opacity: 0;">
        </p>',
        '<p style="background: url('.getIcodebangCdnUrl().'/static/css/default/img/background/showcode.jpg) center center no-repeat scroll; opacity: 0;">
        </p>',
        '<p style="background: url('.getIcodebangCdnUrl().'/static/css/default/img/background/poweredworld.jpg) center center no-repeat scroll; opacity: 0;">
        </p>',
        '<p style="background: url('.getIcodebangCdnUrl().'/static/css/default/img/background/teamwork_1.jpg) center center no-repeat scroll; opacity: 0;">
        </p>',
        '<p style="background: url('.getIcodebangCdnUrl().'/static/css/default/img/background/teamwork_2.jpg) center center no-repeat scroll; opacity: 0;">
        </p>',
    );
    $_rand = rand(0, count($loopPlayList)-1);
    //var_dump($loopPlayList[$_rand] );
    echo $loopPlayList[$_rand];
    ?>
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
                <li class=""></li>
                <li class=""></li>
            </ul>
            <!-- 轮播的页码  结束 -->
        </div>
        <!-- 轮换中间区域结束 -->
    </div>
</div>
<script type="text/javascript">
/**
 * 轮播功能
 * @param containerSelector 包含待轮播内容的容器选择
 * @param slideSelector
 */
function loopPlay (containerSelector, slideSelector, controlSelector) {
    var ali=$(controlSelector);
    var aPage=$(containerSelector);
    var aslide_img=$('.lunhuancenter > div');
    var iNow=0;
    //首屏banner效果图
    var totalShow = $(containerSelector).length;
    var totalControl = $(controlSelector).length;
    if (totalShow==0 || (totalShow==1 && totalControl==0)) {
        return;
    }
    if (totalShow > totalControl) {
        for(var index=totalShow-1; index>=totalControl; index--) {
            $($(containerSelector)[index]).remove();
        }
    } else if (totalShow < totalControl) {
        for(var index=totalControl-1; index>=totalShow; index--) {
            $($(controlSelector)[index]).remove();
        }
    }
    totalShow = $(containerSelector).length;
    // 只有一个待播放，直接拿掉控制单元
    if (totalShow == 1 && $(controlSelector).length>0) {
        $($(controlSelector)[0]).remove();
    }
    // 将第一个可见
    aPage.eq(0).css({opacity:1});

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
    // 鼠标滑过控制单元，切换
    ali.hover(
                function(){clearInterval(timer);},
                function(){timer=setInterval(autoRun,5000);}
            );

};
$(function (containerSelector, slideSelector, controlSelector) {
    loopPlay ('#lunhuanback p', slideSelector, '#lunbonum li');
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
if ($('#J_dotLine').length) {
	var dotline = new Dotline({
		dom: 'J_dotLine', //画布id
		cw: 1500, //画布宽
		ch: 300, //画布高
		ds: 30, //点的个数
		r: 2, //圆点半径
		cl: '#FFFFFF', //粒子线颜色
		dis: 100 //触发连线的距离
    }).start();
}
//}

</script>

	<?php View::output('block/content_nav_menu.php'); ?>

	<div class="container">
		<div class="row">
			<div class="icb-content-wrap clearfix">
                <!-- 暂停侧边栏。 开启时，需要将col-md-12改成 col-md-9 -->
				<div class="col-sm-12 col-md-12 icb-main-content">
                    <?php foreach ($this->content_nav_menu as $_itemInfo) {
                        if (! $_itemInfo['category_ids']) { continue;}
                    ?>
                    <!-- start :: 前端 -->
					<!-- tab切换 -->
					<ul class="nav nav-tabs icb-nav-tabs right padding-10">
					    <li class="nav-tabs-title"><h2 class=""><i class="icon icon-list"></i><?php
                        echo $_itemInfo['title']
                         ?></h2></li><span class=" "><?php
                        foreach ($_itemInfo['category_ids'] as $_categoryId) { ?>
                        <a class="text-color-999 icb-padding10" href="/index/category-<?php echo $this->categoryList[$_categoryId]['url_token']=='' ? $this->categoryList[$_categoryId]['id'] :$this->categoryList[$_categoryId]['url_token'];?>"><?php echo $this->categoryList[$_categoryId]['title']; ?></a>
                        <?php } ?></span>
					</ul>
					<!-- end tab切换 -->
					<div class="icb-mod icb-article-list  clearfix margin10">
						<!-- <div class="mod-body">
							<div class="icb-common-list">
							</div>
						</div> -->
                        <div class="col-sm-9 nopadding">
						    <?php echo $_itemInfo['posts_list']; ?>
                        </div>
                        <div class="col-sm-3 homepage-course-table-list nopadding">
                          <h3 class="padding-10"><i class="icon-reader"></i> <?php echo $_itemInfo['title'];?> 教程</h3>
                          <div class="js-scrollup ">
                            <ul class="">
                            <?php
                            $_tmpIndex = 0;
                            foreach ($_itemInfo['course_table_list'] as $_courseTableInfo) {
                                if (($_tmpIndex % 4 == 0) && $_tmpIndex>0 ) { echo '</ul><ul class="">';}
                                $_tmpIndex++;
                            ?>
                                <li class="nopadding col-sm-12 col-xs-12 col-md-12 col-lg-12">
                                    <a class="clearfix-item" href="/course/<?php echo $this->categoryList[$_courseTableInfo['category_id']]['title']?>/id-<?php echo $this->courseFirstPageList[$_courseTableInfo['id']]['article_id'];?>__table_id-<?php echo $_courseTableInfo['id']?>.html" title="<?php echo $_courseTableInfo['title'];?>">
                                        <div class="course_img  padding5 col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                            <img src="//www.icodebang.cn/uploads/nav_menu/<?php echo $_courseTableInfo['icon'];?>" alt="<?php echo $_courseTableInfo['title'];?>教程">
                                        </div>
                                        <div class="course_info padding-5 col-xs-10 col-sm-10 col-md-10 col-lg-10">
                                            <h4 class="table-title nomargin"><?php echo $_courseTableInfo['title'];?></h4>
                                            <p class="table-desc text-color-999 nomargin"><?php echo $_courseTableInfo['description'];?></p>
                                        </div>
                                    </a>
                                </li>
                            <?php

                            } ?>
                            </ul>
                          </div>
                        </div>
					</div>
                    <!-- end :: 前端 -->
                    <?php } // end foreach ?>

				</div>

                <!-- 侧边栏 -->
                <!-- 暂停侧边栏
				<div class="col-sm-12 col-md-3 icb-side-bar hidden-xs hidden-sm">
					<?php //View::output('block/sidebar_feature.php'); ?>
					<?php //View::output('block/sidebar_hot_topics.php'); ?>
					<?php //View::output('block/sidebar_hot_users.php'); ?>
                </div>
                -->
				<!-- end 侧边栏 -->
			</div>
		</div>
	</div>
</div>


<!-- 幻灯片切换 Owl Carousel JS -->
<script type="text/javascript">

	/*======================================
        // Main Slider
        // 参数   类型   默认值   说明
        // items   整数5幻灯片每页可见个数
        // itemsDesktop 数组[1199,4]设置浏览器宽度和幻灯片可见个数，格式为[X,Y]，X 为浏览器宽度，Y 为可见个数，如[1199,4]就是如果浏览器宽度小于1199，每页显示 4 张，此参数主要用于响应式设计。也可以使用 false
        // itemsDesktopSmall  数组[979,3]同上
        // itemsTablet  数组[768,2]同上
        // itemsTabletSmall  数组false同上，默认为 false
        // itemsMobile  数组[479,1]同上
        // itemsCustom  数组false
        // singleItem  布尔值false是否只显示一张
        // itemsScaleUp  布尔值false
        // slideSpeed  整数200幻灯片切换速度，以毫秒为单位
        // paginationSpeed  整数800分页切换速度，以毫秒为单位
        // rewindSpeed  整数1000重回速度，以毫秒为单位
        // autoPlay  布尔值/整数false自动播放，可选布尔值或整数，若使用整数，如 3000，表示 3 秒切换一次；若设置为 true，默认 5 秒切换一次
        // stopOnHover  布尔值false鼠标悬停停止自动播放
        // navigation  布尔值false显示“上一个”、“下一个”
        // navigationText  数组[“prev”,”next”]设置“上一个”、“下一个”文字，默认是[“prev”,”next”]
        // rewindNav  布尔值true滑动到第一个
        // scrollPerPage  布尔值false每页滚动而不是每个项目滚动
        // pagination  布尔值true显示分页
        // paginationNumbers  布尔值false分页按钮显示数字
        // responsive  布尔值true
        // responsiveRefreshRate  整数200每 200 毫秒检测窗口宽度并做相应的调整，主要用于响应式
        // responsiveBaseWidthjQuery 选择器window
        // baseClass  字符串owl-carousel添加 CSS，如果不需要，最好不要使用
        // theme  字符串owl-theme主题样式，可以自行添加以符合你的要求
        // lazyLoad  布尔值false延迟加载
        // lazyFollow  布尔值true当使用分页时，如果跨页浏览，将不加载跳过页面的图片，只加载所要显示页面的图片，如果设置为 false，则会加载跳过页面的图片。这是 lazyLoad 的子选项
        // lazyEffect  布尔值/字符串fade延迟加载图片的显示效果，默认以 400 毫秒淡入，若为 false 则不使用效果
        // autoHeight  布尔值false自动使用高度
        // jsonPath  字符串falseJSON 文件路径
        // jsonSuccess  函数false处理自定义 JSON 格式的函数
        // dragBeforeAnimFinish  布尔值true忽略过度是否完成（只限拖动）
        // mouseDrag  布尔值true关闭/开启鼠标事件
        // touchDrag  布尔值true关闭/开启触摸事件
        // addClassActive  布尔值false给可见的项目加入 “active” 类
        // transitionStyle  字符串false添加 CSS3 过度效果
        // 回调函数

        // 变量 类型 默认值 说明
        // beforeUpdate 函数 false 响应之后的回调函数
        // afterUpdate 函数 false 响应之前的回调函数
        // beforeInit 函数 false 初始化之前的回调函数
        // afterInit 函数 false 初始化之后的回调函数
        // beforeMove 函数 false 移动之前的回调函数
        // afterMove 函数 false 移动之后的回调函数
        // afterAction 函数 false 初始化之后的回调函数
        // startDragging 函数 false 拖动的回调函数
        // afterLazyLoad 函数 false 延迟加载之后的回调函数

        // 自定义事件
        // 事件 说明
        // owl.prev 到上一个
        // owl.next 到下一个
        // owl.play 自动播放，可传递一个参数作为播放速度
        // owl.stop 停止自动播放
        // owl.goTo 跳到第几个
        // owl.jumpTo 不使用动画跳到第几个
	======================================*/
$(".js-scrollup").each(function () {
    if ($(this).children().length <2) {
        return;
    }
    var timeScroll = parseInt(Math.random() * 10000);
        $(this).owlCarousel({
            itemsScaleUp : true,// 布尔值false
            loop:true,
            autoplay:true,
            autoplayHoverPause:true,
            smartSpeed: 10000 + timeScroll,
            autoplayTimeout:10000 + timeScroll,
            mouseDrag: true,
            items:1,
            animateIn: 'fadeIn',
            animateOut: 'fadeOut',
            nav:false,
            dots:true,
            navText:['',''],
            //navText: ['<i class="fa fa-angle-left icon icon-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right icon icon-left" aria-hidden="true"></i>'],
            responsive:{
                300: {
                    items:1,
                },
                480: {
                    items:1,
                },
                768: {
                    items:1,
                },
                1170: {
                    nav:false,
                    items:1,
                },
            }
        });
});
</script>
<?php View::output('global/footer.php'); ?>
