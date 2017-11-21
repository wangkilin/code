<!-- 底部 -->
<footer class="text-center text-color-999">
	Copyright © <?php echo date('Y'); ?> - <?php echo get_setting('site_name'); ?>
</footer>
<!-- end 底部 -->
<!-- nav -->
<div class="nav">
	<ul>
		<li class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
			<a href="m/english/category/" <?php if ($_GET['act'] == 'category') { ?>class="active"<?php } ?>><i class="icon icon-home"></i><?php _e('全部课程'); ?></a>
		</li>

		<li class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
			<a href="m/english/homework/" <?php if ($_GET['act'] == 'homework') { ?>class="active"<?php } ?>><i class="icon icon-topic"></i><?php _e('交作业'); ?></a>
		</li>
		<li class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
			<a href="m/english/home/" <?php if ($_GET['act'] == 'home') { ?>class="active"<?php } ?>><i class="icon icon-user"></i><?php _e('我'); ?></a>
		</li>
	</ul>
</div>
<!-- end nav -->
<?php View::output('m/english/footer.php'); ?>
