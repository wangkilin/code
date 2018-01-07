<?php View::output('m/english/header.php'); ?>

	<!-- 内容 -->
	<div class="container">
	    <!--<div class="index-intro-img"></div>-->
		<!-- 今日课程 -->
		<?php foreach ($this->courseList as $_item) {;?>
			<div class="mod-body" >
			  	<a href="m/english/show/id-<?php echo $_item['id'];?>">
					<div class="mod-body-box"><?php echo $_item['title']?></div>
					<div>
						<img src="<?php echo getModulePicUrlBySize('course', null, $_item['pic']);?>"/>
					</div>
			  	</a>
			</div>
		<?php } ?>
		<!-- end 热门话题 -->

	</div>
	<!-- end 内容 -->


<?php View::output('m/english/foot_nav.php'); ?>
