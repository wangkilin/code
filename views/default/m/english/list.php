<?php View::output('m/english/header.php'); ?>

	<?php if ($this->list) { ?>
	<!-- 分类 -->
	<div class="container">
	    <div class="search_wrap">
	      <form class="navbar-search" action="m/english/list/category-<?php echo $_GET['category'];?>" id="course_search_form" method="post">
              <input class="form-control search-query" type="text" placeholder="<?php _e('搜索问题、话题或人'); ?>" autocomplete="off" name="q" id="icb-search-query" data-dropdown-type="tip"/>
              <span class="search-icon" title="<?php _e('搜索'); ?>" id="global_search_btns" onClick="$('#course_search_form').submit();"><i class="icon icon-search"></i></span>
          </form>
	    </div>
		<ul class="list-content">
		  <?php foreach ($this->list as $val) {?>
			<?php if ($val['title']) { ?>
			<li class="col-sm-12">
				<a href="m/english/show/<?php echo $val['id'];?>">
				  <span><?php echo $val['title']; ?></span>
				  <img src="<?php echo getMudulePicUrlBySize('course', null, $val['pic']);?>"/>
				</a>
				<div class="list-info">
                    <div class="list-info-left">
                        <i class="teacher_icon"></i>
                    </div>
                    <div class="list-info-right">
                        <p class="teacher_brief">Maggie：美国本土小学教师</p>
                        <p>
                            <span class="course_views">
                                <?php echo _t('收听人数'), ':', $val['views'];?>
                            </span>
                            <span class="course_time"><?php echo date('Y/m/d', $val['add_time']);?></span>
                        </p>

                    </div>
				</div>
			</li>
			<?php } ?>
		  <?php } ?>
		</ul>
	</div>
	<!-- end 分类 -->
	<?php } ?>
<?php View::output('m/english/foot_nav.php'); ?>
