<ul class="nav nav-tabs">
    <li<?php echo ACTION=='list'?' class="active"':''?>><a href="<?php echo ACTION=='list'?'#list"  data-toggle="tab':'admin/tag/list/'?>"><?php _e('标签管理'); ?></a></li>
    <li<?php echo ACTION=='list_category'?' class="active"':''?>><a href="<?php echo ACTION=='list_category'?'#list_category"  data-toggle="tab':'admin/tag/list_category/'?>"><?php _e('标签分类'); ?></a></li>
    <li<?php echo ACTION=='tag'?' class="active"':''?>><a href="<?php echo ACTION=='tag'?'#tag"  data-toggle="tab':'admin/tag/tag/'?>"><?php _e('新建标签'); ?></a></li>
    <li<?php echo ACTION=='category'?' class="active"':''?>><a href="<?php echo ACTION=='category'?'#category"  data-toggle="tab':'admin/tag/category/'?>"><?php _e('新建分类'); ?></a></li>
    <li><a href="#search" data-toggle="tab"><?php _e('搜索'); ?></a></li>
</ul>