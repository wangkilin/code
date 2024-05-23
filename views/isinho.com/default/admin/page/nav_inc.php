<ul class="nav nav-tabs">
    <li<?php echo ACTION=='index'?' class="active"':''?>>
      <a href="<?php
          echo ACTION=='index'?'#list" data-toggle="tab':'admin/page/index/'
        ?>"><?php _e('内网页面'); ?></a>
    </li>
    <li<?php echo ACTION=='outside'?' class="active"':''?>>
      <a href="<?php
          echo ACTION=='outside'?'#outside_list" data-toggle="tab':'admin/page/outside/'
        ?>"><?php _e('外网页面'); ?></a>
    </li>
    <?php if (ACTION=='edit') { ?>
    <li>
      <a href="admin/page/add/"><?php _e('新建页面'); ?></a>
    </li>
    <?php }?>
    <li<?php echo ACTION=='add' || ACTION=='edit' ?' class="active"':''?>>
      <a href="<?php
          echo ACTION=='publish'?'#publish" data-toggle="tab':'admin/page/add/'
        ?>"><?php ACTION!='edit' ? _e('新建页面') : _e('编辑页面'); ?></a>
    </li>
    <?php if (ACTION=='show_read_log') { ?>
    <li class="active">
      <a href="#read_log" data-toggle="tab"><?php _e('阅读记录'); ?></a>
    </li>
    <?php }?>
    <li><a href="#search" data-toggle="tab"><?php _e('搜索'); ?></a></li>
</ul>
