<ul class="nav nav-tabs">
    <li<?php echo ACTION=='list'?' class="active"':''?>>
      <a href="<?php
          echo ACTION=='list'?'#list" data-toggle="tab':'admin/course/list/'
        ?>"><?php _e('教程管理'); ?></a>
    </li>
    <li<?php echo ACTION=='course'?' class="active"':''?>>
      <a href="<?php
          echo ACTION=='course'?'#course" data-toggle="tab':'admin/course/course/'
        ?>"><?php isset($_GET['id']) ? _e('编辑教程'):_e('新建教程'); ?></a>
    </li>
    <?php if (ACTION=='course' && isset($_GET['id'])) {?>
    <li>
      <a href="admin/course/course/"><?php _e('新建教程'); ?></a>
    </li>
    <?php }?>
    <li<?php echo ACTION=='content_table'?' class="active"':''?>>
      <a href="<?php
          echo ACTION=='content_table'?'#course_table" data-toggle="tab':'admin/course/content_table/'
        ?>"><?php _e('教程目录'); ?></a>
    </li>
    <li><a href="#search" data-toggle="tab"><?php _e('搜索教程'); ?></a></li>
</ul>