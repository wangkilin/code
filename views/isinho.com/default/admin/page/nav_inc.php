<ul class="nav nav-tabs">
    <li<?php echo ACTION=='index'?' class="active"':''?>>
      <a href="<?php
          echo ACTION=='index'?'#list" data-toggle="tab':'admin/page/index/'
        ?>"><?php _e('页面管理'); ?></a>
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
    <li><a href="#search" data-toggle="tab"><?php _e('搜索'); ?></a></li>
</ul>