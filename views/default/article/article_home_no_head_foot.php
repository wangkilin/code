<div class="icb-container">
	<div class="container">
		<div class="row">
			<div class="icb-content-wrap clearfix">
				<!-- 侧边栏 -->
				<div class="col-sm-4 col-md-3 hidden-xs hidden-sm nopadding ">
					<!-- 热门文章 -->
					<div class="icb-mod icb-text-align-justify">

                        <ul class="nav nav-tabs icb-nav-tabs right nopadding margin10">
                            <li class=""><h4 class="nomargin"><i class="icon icon-favor"></i> 热门分类</h4></li>
                        </ul>
						<div class="mod-body js-scrollup">
							<ul class="col-sm-12">
								<?php
                                $_tmpI = 1;
                                foreach($this->showCategoryList AS $_categoryName=>$_categoryId) {?>
								<li class="pull-left margin-5 padding10">
                                    <img style="max-width:30px; max-height:19px; width:auto;display:inline-block;" src="//www.icodebang.cn/uploads/nav_menu/<?php echo $this->categoryList[$_categoryId]['icon'] ;?>" alt="<?php echo $this->categoryList[$_categoryId]['title'];?>"> <a href="article/index/category-<?php echo $this->categoryList[$_categoryId]['url_token']; ?>"><?php echo $this->categoryList[$_categoryId]['title']; ?></a>
                                </li>
                                <?php
                                    if ($_tmpI++ % 25 == 0) {
                                        echo '</ul><ul class="col-sm-12">';
                                    }
                                } ?>
							</ul>
						</div>
					</div>
					<!-- end 热门文章 -->
					<?php //View::output('block/sidebar_hot_topics.php'); ?>
				</div>
				<!-- end 侧边栏 -->
				<div class="col-sm-12 col-md-6 icb-side-bar nopadding icb-main-content">
					<div class="icb-mod icb-article-list  clearfix">

                        <ul class="nav nav-tabs icb-nav-tabs right nopadding margin10">
                            <li class="nav-tabs-title"><h2 class="nomargin"><i class="icon icon-reader"></i> 文章更新</h2></li>
                        </ul>
                        <div class="col-sm-12 padding-10">
                        <?php echo $this->posts_list_html; ?>
                        </div>
                    </div>
                </div>
				<!-- 侧边栏 -->
				<div class="col-sm-4 col-md-3 icb-side-bar hidden-xs hidden-sm nopadding">
					<!-- 热门文章 -->
					<div class="icb-mod icb-text-align-justify">

                        <ul class="nav nav-tabs icb-nav-tabs right nopadding margin10">
                            <li class="nopadding"><h4 class="nomargin"><i class="icon icon-agree"></i> 热门文章</h4></li>
                        </ul>
						<div class="mod-body">
							<ul class="col-sm-12 nopadding margin-10 padding-5 prefix-dot">
								<?php foreach($this->hot_articles AS $key => $val) { ?>
								<li class="nooverflow"><a class="padding-10 title" href="article/<?php echo $val['id']; ?>"><?php echo $val['title']; ?></a></li>
								<?php } ?>
							</ul>
						</div>
					</div>
					<!-- end 热门文章 -->
					<?php //View::output('block/sidebar_hot_topics.php'); ?>
				</div>
				<!-- end 侧边栏 -->


				<div class="col-sm-12 col-md-12 nopadding">
					<div class="icb-mod icb-article-list  clearfix">

                    <?php foreach ($this->itemList as $_itemInfo) {
                        if (! $_itemInfo['posts_list']) { continue;}
                        ?>
                        <div class="col-sm-4 padding10">
                        <!-- start :: 前端 -->
                        <!-- tab切换 -->
                        <ul class="nav nav-tabs icb-nav-tabs right nopadding margin10">
                            <li class="nav-tabs-title col-sm-12 nopadding"><h2 class="nomargin"><i class="icon icon-list"></i> <?php
                            echo $_itemInfo['title']
                            ?><a class="text-color-999 pull-right" href="article/index/category-<?php echo $_itemInfo['url_token']; ?>">更多<i class="icon icon-more"></i></a></h2> </li>
                        </ul>
                        <div class="col-sm-12 nopadding prefix-dot">
						    <?php echo $_itemInfo['posts_list']; ?>
                        </div>
                        </div>
                    <?php }?>
                    </div>
					<!-- end 文章列表 -->
				</div>

				<div class="col-sm-12 col-md-12 nopadding">

					<!-- tab切换 -->
					<ul class="nav nav-tabs icb-nav-tabs right padding-10">
					    <li class="nav-tabs-title"><h2 class=""><i class="icon icon-list"></i>其他开发</h2></li>
					</ul>
					<!-- end tab切换 -->
					<div class="icb-mod icb-article-list prefix-dot padding-10 clearfix">
                    <?php echo $this->more_posts_list; ?>
                    </div>
					<!-- end 文章列表 -->
				</div>

                <!-- 全部分类icon列表 -->
                <?php View::output('block/category_icon_list'); ?>
                <!-- end 全部分类icon列表 -->
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
