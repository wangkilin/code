<?php View::output('global/header.php'); ?>

<div class="icb-container">

	<div class="container">
		<div class="row">
			<div class="icb-content-wrap clearfix">
                <!-- 暂停侧边栏。 开启时，需要将col-md-12改成 col-md-9 -->
				<div class="col-sm-12 col-md-12 icb-main-content">
                    <?php foreach ($this->itemList as $_itemInfo) {
                        if (! $_itemInfo['category_ids']) { continue;}
                    ?>
                    <!-- start :: 前端 -->
					<!-- tab切换 -->
					<ul class="nav nav-tabs icb-nav-tabs right padding-10">
					    <li class="nav-tabs-title"><h2 class=""><i class="icon icon-list"></i><?php
                        echo $_itemInfo['title']
                         ?></h2></li><?php
                        foreach ($_itemInfo['category_ids'] as $_categoryId) {
                            if ($_categoryId == $_itemInfo['id']) continue;
                        ?>
                        <a class="text-color-999 icb-padding10" href="/index/category-<?php echo $this->categoryList[$_categoryId]['url_token']=='' ? $this->categoryList[$_categoryId]['id'] :$this->categoryList[$_categoryId]['url_token'];?>"><?php echo $this->categoryList[$_categoryId]['title']; ?></a>
                        <?php } ?>
					</ul>
					<!-- end tab切换 -->
					<div class="icb-mod icb-article-list  clearfix margin10">
                        <div class="col-sm-9 nopadding">
						    <?php echo $_itemInfo['posts_list']; ?>
                            <div class="col-sm-12">
                            <?php echo $this->pagination; ?>
                            </div>
                        </div>
                        <div class="col-sm-3 homepage-course-table-list js-scrollup nopadding">
                          <h3 class="padding-10"><i class="icon-reader"></i> <?php echo $_itemInfo['title'];?> 教程</h3>
                          <div>
                            <ul class="">
                            <?php
                            $_tmpIndex = 0;
                            foreach ($_itemInfo['course_table_list'] as $_courseTableInfo) {
                                if (($_tmpIndex % 4 == 0) && $_tmpIndex>0 ) { echo '</ul><ul class="">';}
                                $_tmpIndex++;
                            ?>
                                <li class="nopadding col-sm-12 col-xs-12 col-md-12 col-lg-12">
                                    <a class="clearfix-item" href="/course/html5/id-html_tutorial__table_id-25.html" title="<?php echo $_courseTableInfo['title'];?>">
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
    if ($(".js-scrollup > div > ul").length > 1) {
	$(".js-scrollup > div").owlCarousel({
        itemsScaleUp : true,// 布尔值false
		loop:true,
		autoplay:true,
		autoplayHoverPause:true,
		smartSpeed: 10000,
		autoplayTimeout:10000,
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
    }
</script>
<?php View::output('global/footer.php'); ?>
