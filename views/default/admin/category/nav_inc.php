<ul class="nav nav-tabs">
    <li<?php echo ACTION=='list'?' class="active"':''?>>
      <a href="<?php
          echo ACTION=='list'?'#list" data-toggle="tab':'admin/category/list/'
        ?>"><?php _e('分类管理'); ?></a>
    </li>
    <?php if (ACTION=='edit') { ?>
    <li class="active">
      <a href="#edit" data-toggle="tab"><?php _e('分类编辑'); ?></a>
    </li>
    <?php } ?>
    <li<?php echo ACTION=='module'?' class="active"':''?>>
      <a href="<?php
          echo ACTION=='module'?'#module" data-toggle="tab':'admin/category/module/'
        ?>"><?php _e('模块设置'); ?></a>
    </li>
    <?php if (ACTION=='edit_post_module') { ?>
    <li class="active">
      <a href="#edit" data-toggle="tab"><?php _e('模块编辑'); ?></a>
    </li>
    <?php } ?>
</ul>
