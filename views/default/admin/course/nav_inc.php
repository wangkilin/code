<ul class="nav nav-tabs">
    <li<?php echo ACTION=='table'?' class="active"':''?>>
      <a href="<?php
          echo ACTION=='table'?'#table" data-toggle="tab':'admin/course/table/'
        ?>"><?php _e('教程归类'); ?></a>
    </li><?php if (ACTION=='edit_table') {?>
    <li class="active">
      <a href="#edit_table" data-toggle="tab"><?php _e('编辑教程'); ?></a>
    </li><?php } ?>
    <li<?php echo ACTION=='content_table'?' class="active"':''?>>
      <a href="<?php
          echo ACTION=='content_table'?'#course_table" data-toggle="tab':'admin/course/content_table/'
        ?>"><?php _e('教程课节'); ?></a>
    </li>
    <li<?php echo ACTION=='list'?' class="active"':''?>>
      <a href="<?php
          echo ACTION=='list'?'#list" data-toggle="tab':'admin/course/list/'
        ?>"><?php _e('课节管理'); ?></a>
    </li>
    <li<?php echo ACTION=='course'?' class="active"':''?>>
      <a href="<?php
          echo ACTION=='course'?'#course" data-toggle="tab':'admin/course/course/'
        ?>"><?php isset($_GET['id']) ? _e('编辑课节'):_e('新建课节'); ?></a>
    </li>
    <?php if (ACTION=='course' && isset($_GET['id'])) {?>
    <li>
      <a href="admin/course/course/"><?php _e('新建课节'); ?></a>
    </li>
    <?php }?>
    <li><a href="#search" data-toggle="tab"><?php _e('搜索内容'); ?></a></li>
</ul>
