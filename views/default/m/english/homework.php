<?php View::output('m/english/header.php'); ?>
	<div class="container">
		<span>已经连续坚持xx天按时交作业，获得xxx积分,继续加油！</span>
	</div>
	<div>
	  <img src="<?php echo getMudulePicUrlBySize('course', null, $this->item['pic']);?>"/>
	</div>
	<div class="container">
		<div><?php echo $this->item['title']; ?></div>
		<div><?php echo $this->item['title2']; ?></div>
		<div><a href="m/english/show/<?php echo $_GET['id'];?>">重听课程</a></div>
		<div>问题1：语音</div>
		<div>问题1：文本</div>
		<div>回答问题</div>

	</div>
	<div class="container">
		<span>保存学习报告！</span>
	</div>


<?php View::output('m/english/footer.php'); ?>
