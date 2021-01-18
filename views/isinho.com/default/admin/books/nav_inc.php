<ul class="nav nav-tabs">
    <li<?php echo ACTION=='index'?' class="active"':''?>>
      <a href="<?php
          echo ACTION=='index'?'#index" data-toggle="tab':'admin/books/index/'
        ?>"><?php _e('书稿管理'); ?></a>
    </li>
    <li<?php echo ACTION=='book'?' class="active"':''?>>
      <a href="<?php
          echo ACTION=='book'?'#book" data-toggle="tab':'admin/books/book/'
        ?>"><?php isset($_GET['id']) ? _e('编辑书稿'):_e('新建书稿'); ?></a>
    </li>
    <li<?php echo ACTION=='import'?' class="active"':''?>>
      <a href="<?php
          echo ACTION=='import'?'#import" data-toggle="tab':'admin/books/import/'
        ?>"><?php _e('书稿导入'); ?></a>
    </li>
    <li><a href="#search" data-toggle="tab"><?php _e('搜索书稿'); ?></a></li>
</ul>
