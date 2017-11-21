<?php View::output('m/english/header.php'); ?>

	<?php if ($this->content_nav_menu) { ?>
	<!-- 分类 -->
	<div class="container">
		<ul>
			<?php foreach ($this->content_nav_menu as $val) { ?>
			<?php if ($val['title']) { ?>
			<li class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
				<a <?php if (($val['type'] == 'category' AND $_GET['category'] AND ($val['type_id'] == $this->category_info['id'])) OR ($val['type'] == 'feature' AND $this->feature_info['id'] == $val['type_id'])) { ?> class="active"<?php } ?> href="<?php echo $val['link']; ?>"<?php if ($val['type'] == 'custom') { ?> target="_blank"<?php } ?>><?php echo $val['title']; ?></a>
			</li>
			<?php } ?>
			<?php } ?>
		</ul>
	</div>
	<!-- end 分类 -->
	<?php } ?>

	<?php if ($this->categoryList) { ?>
	<!-- 分类 -->
	<div class="container">
		<ul>
		  <?php foreach ($this->categoryList as $val) {?>
			<?php if ($val['title']) { ?>
			<li class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
				<a href="m/english/list/category-<?php echo $val['id'];?>">
				  <span><?php echo $val['title']; ?></span>
				  <span><?php echo _t('收听人数'), ':', $val['views'];?></span>
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
