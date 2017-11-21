<?php View::output('global/header.php'); ?>
<div class="icb-container">
	<?php View::output('block/content_nav_menu.php'); ?>

	<div class="container">
		<div class="row">
			<div class="icb-content-wrap clearfix">
				<div class="col-sm-12 col-md-9 icb-main-content">
					<!-- tab切换 -->
					<ul class="nav nav-tabs icb-nav-tabs hidden-xs active">
						<li class="nav-tabs-title hidden-xs"><i class="icon icon-topic"></i> <?php if ($this->category_info) { ?><?php echo $this->category_info['title']; ?><?php } else if ($this->feature_info) { ?><?php echo $this->feature_info['title']; ?><?php } else { ?><?php _e('全部问题'); ?><?php } ?></li>
						<li<?php if ((!$_GET['sort_type'] OR $_GET['sort_type'] == 'new') AND !$_GET['is_recommend']) { ?> class="active"<?php } ?>><a href="question/<?php if ($this->feature_info) { ?>feature_id-<?php echo $this->feature_info['id']; ?>__<?php } ?><?php if ($this->category_info['id']) { ?>category-<?php echo $this->category_info['id']; ?><?php } ?>"><?php _e('最新'); ?></a></li>
						<li<?php if ($_GET['sort_type'] == 'unresponsive') { ?> class="active"<?php } ?>><a href="question/<?php if ($this->feature_info) { ?>feature_id-<?php echo $this->feature_info['id']; ?>__<?php } ?>category-<?php echo $this->category_info['id']; ?>__sort_type-unresponsive"><?php _e('等待回复'); ?></a></li>
						<li<?php if ($_GET['sort_type'] == 'hot') { ?> class="active"<?php } ?>><a href="question/<?php if ($this->feature_info) { ?>feature_id-<?php echo $this->feature_info['id']; ?>__<?php } ?>category-<?php echo $this->category_info['id']; ?>__sort_type-hot__day-30" id="sort_control_hot"><?php _e('热门'); ?></a></li>
						<li<?php if ($_GET['is_recommend']) { ?> class="active"<?php } ?>><a href="question/<?php if ($this->feature_info) { ?>feature_id-<?php echo $this->feature_info['id']; ?>__<?php } ?>category-<?php echo $this->category_info['id']; ?>__is_recommend-1"><?php _e('推荐'); ?></a></li>

					</ul>
					<!-- end tab切换 -->

					<?php if ($_GET['sort_type'] == 'hot') { ?>
					<!-- 自定义tab切换 -->
					<div class="icb-tabs">
						<ul>
							<li<?php if ($_GET['sort_type'] == 'hot' AND $_GET['day'] == 30) { ?> class="active"<?php } ?>><a href="question/<?php if ($this->feature_info) { ?>feature_id-<?php echo $this->feature_info['id']; ?>__<?php } ?>sort_type-hot__<?php if ($this->category_info['id']) { ?>__category-<?php echo $this->category_info['id']; ?><?php } ?>__day-30" day="30"><?php _e('30天'); ?></a></li>
						  	<li<?php if ($_GET['sort_type'] == 'hot' AND $_GET['day'] == 7) { ?> class="active"<?php } ?>><a href="question/<?php if ($this->feature_info) { ?>feature_id-<?php echo $this->feature_info['id']; ?>__<?php } ?>sort_type-hot__<?php if ($this->category_info['id']) { ?>__category-<?php echo $this->category_info['id']; ?><?php } ?>__day-7" day="7"><?php _e('7天'); ?></a></li>
						  	<li<?php if ($_GET['sort_type'] == 'hot' AND $_GET['day'] == 1) { ?> class="active"<?php } ?>><a href="question/<?php if ($this->feature_info) { ?>feature_id-<?php echo $this->feature_info['id']; ?>__<?php } ?>sort_type-hot__<?php if ($this->category_info['id']) { ?>__category-<?php echo $this->category_info['id']; ?><?php } ?>__day-1" day="1"><?php _e('当天'); ?></a></li>
						</ul>
					</div>
					<!-- end 自定义tab切换 -->
					<?php } ?>

					<!-- 问题列表 -->
					<div class="icb-mod icb-explore-list">
						<div class="mod-body">
							<div class="icb-common-list">
								<?php echo $this->question_list_bit; ?>
							</div>
						</div>
					</div>
					<!-- end 问题列表 -->

					<?php echo $this->pagination; ?>
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