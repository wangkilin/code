<?php View::output('m/english/header.php'); ?>

	<?php if ($this->list) { ?>
	<!-- 分类 -->
	<div class="container">
	    <div class="search_wrap">
	      <form class="navbar-search" action="m/english/list/category-<?php echo $_GET['category'];?>" id="course_search_form" method="post">
              <input class="form-control search-query" type="text" placeholder="<?php _e('搜索问题、话题或人'); ?>" autocomplete="off" name="q" id="icb-search-query" data-dropdown-type="tip"/>
              <span title="<?php _e('搜索'); ?>" id="global_search_btns" onClick="$('#course_search_form').submit();"><i class="icon icon-search"></i></span>
          </form>
	    </div>
		<ul>
		  <?php foreach ($this->list as $val) {?>
			<?php if ($val['title']) { ?>
			<li class="col-sm-12">
				<a href="m/english/show/<?php echo $val['id'];?>">
				  <span><?php echo $val['title']; ?></span>
				  <img src="<?php echo getMudulePicUrlBySize('category', 'max', $val['pic']);?>"/>
				</a>
				<span>
				  <i class="teacher_icon">头像</i>
				  <u class="teacher_brief">Maggie：美国本土小学教师</u>
				  <u class="course_views"><?php echo _t('收听人数'), ':', $val['views'];?></u>
				  <u class="course_time"><?php echo date('Y/m/d', $val['add_time']);?></u>
				</span>
			</li>
			<?php } ?>
		  <?php } ?>
		</ul>
	</div>
	<!-- end 分类 -->
	<?php } ?>
<?php View::output('m/english/foot_nav.php'); ?>
