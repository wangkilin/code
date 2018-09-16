<?php if ($this->content_nav_menu) { ?>
<div class="container content-nav-menu category">
    <?php if (get_setting('category_display_mode') == 'list') { ?>
	<div class="row">
	  <div class="col-sm-12">
		<ul class="list">
			<li<?php if (!$_GET['category'] AND !$this->feature_info) { ?> class="active"<?php } ?>><a href="<?php echo $this->content_nav_menu['base']['link']; ?>"><?php _e('全部'); ?></a></li>

			<?php foreach ($this->content_nav_menu as $key => $val) { ?>
			<?php if ($val['title']) { ?>
			<li<?php if (($val['type'] == 'category' AND $_GET['category'] AND ($val['type_id'] == $this->category_info['id'] OR $this->category_info['parent_id'] == $val['type_id'])) OR ($val['type'] == 'feature' AND $this->feature_info['id'] == $val['type_id'])) { ?> class="active"<?php } ?>>
				<a href="<?php echo $val['link']; ?>"<?php if ($val['type'] == 'custom') { ?> target="_blank"<?php } ?>><?php echo $val['title']; ?></a>
				<?php if ($val['child'] && get_setting('nav_menu_show_child') == 'Y') { ?>
				<div class="icb-dropdown" role="menu" aria-labelledby="dropdownMenu">
					<span></span>
					<ul class="icb-dropdown-list">
						<?php foreach ($val['child'] AS $_key => $_val) { ?>
						<li>
						   <a href="<?php echo $_val['link']; ?>"<?php if ($_val['id'] == $this->category_info['id']) { ?> class="active"<?php } ?>><?php echo $_val['title']; ?></a></li>
						<?php } ?>
					</ul>
				</div>
				<?php } ?>
			</li>
			<?php } ?>
			<?php } ?>
		</ul>
      </div>
    </div>
	<?php } else { // 图文形式显示
        $_numChildPerRow = 4;
	?>
	  <?php foreach ($this->content_nav_menu as $key => $val) {
	            $_tmpStep = 0;
                $_tmpTotalChild = count($val['child']);
                $_tmpRows = ceil($_tmpTotalChild / $_numChildPerRow); ?>
		<?php if ($val['title']) { ?>
		 <!-- <div class="row">
		  <div class="nav-row-title">
		   <span class="title col-sm-12">
              <?php echo $val['title'];?>
            </span>
          </div>
		 </div> -->
		 <div class="row icb-content-nav-menu my-category-<?php echo $val['url_token'] ? $val['url_token'] : $val['id']; ?>-and-sub-item">

		  <div class="icb-content-wrap nav-block-row clearfix">
           <div class="col-sm-3 icb-content-nav-top-menu  my-category-<?php echo $val['url_token'] ? $val['url_token'] : $val['id']; ?>">
            <a <?php if ($val['link']) { echo 'href="'. $val['link'].'"';} if ($val['type'] == 'custom') { ?> target="_blank"<?php } ?>>
            <!-- <span class="col-sm-3"><img src="<?php if ($val['icon']) { ?><?php echo get_setting('upload_url'); ?>/nav_menu/<?php echo $val['icon']; ?><?php } else { ?><?php echo G_STATIC_URL; ?>/css/<?php echo $this->template_name; ?>/img/default_class_imgs.png<?php } ?>" alt="<?php echo $val['title']; ?>" /></span> -->
            <span class="title col-sm-12">
              <?php echo $val['title'];?>
            </span>
            </a>
            <span class="col-sm-9"><?php echo $val['description']; ?></span>
		   </div>
           <!--  start sub-category -->
		   <div class="col-sm-9 icb-content-nav-sub-menu">
		   <div class="row">
		   <?php foreach ($val['child'] AS $_key => $_val) { ?>
		     <div class="col-sm-3 category-sub-item">
               <a href="<?php echo $_val['link']; ?>"<?php if ($_val['type'] == 'custom') { ?> target="_blank"<?php } ?>>
	            <span><img src="<?php if ($_val['icon']) {
	              echo get_setting('upload_url'); ?>/nav_menu/<?php echo $_val['icon'];
	             } else {  echo G_STATIC_URL;
	             ?>/css/<?php echo $this->template_name; ?>/img/default_class_imgs.png<?php
	             } ?>" alt="<?php echo $_val['title']; ?>" /></span>
	            <span class="title"><?php echo $_val['title']; ?></span>
                </a>
	            <span><?php echo $_val['description']; ?></span>
		     </div>
            <?php if (++$_tmpStep % $_numChildPerRow == 0) { ?>
            </div>
            <div class="row">
		    <?php } ?>
           <?php } ?>
            </div>
	       </div>
           <!-- end sub-category -->
          </div>
	    </div>
		<?php } /* end if ($val['title']) */ ?>
      <?php } /* end foreach */ ?>


        <div class="row icb-loop-play-pic">
          <div class="icb-content-wrap nav-block-row clearfix">
           <div class="col-sm-3 icb-content-nav-top-menu">
            <span class="col-sm-9"></span>
		   </div>
           <!--  start sub-category -->
		   <div class="col-sm-9  scroll-pic">
		   <div class="row">
		               </div>
	       </div>
           <!-- end sub-category -->
          </div>
        </div>

	<?php } ?>

</div>
<?php } ?>
