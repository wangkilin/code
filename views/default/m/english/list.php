<?php View::output('m/english/header.php'); ?>

	<?php if ($this->list) { ?>
	<!-- 分类 -->
	<div class="container">
		<ul>
		  <?php foreach ($this->list as $val) {?>
			<?php if ($val['title']) { ?>
			<li class="col-sm-12">
				<a href="m/english/show/<?php echo $val['id'];?>">
				  <span><?php echo $val['title']; ?></span>
				  <span><?php echo _t('收听人数'), ':', $val['views'];?></span>
				  <span><?php echo _t('收藏人数'), ':', $val['favorites'];?></span>
				  <span><?php echo _t('评论人数'), ':', $val['comments'];?></span>
				  <img src="<?php echo getMudulePicUrlBySize('category', 'max', $val['pic']);?>"/>
				</a>
			</li>
			<?php } ?>
		  <?php } ?>
		</ul>
	</div>
	<!-- end 分类 -->
	<?php } ?>
<?php View::output('m/english/foot_nav.php'); ?>
