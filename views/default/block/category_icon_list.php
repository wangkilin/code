<div class="col-sm-12 col-md-12">

    <!-- tab切换 -->
    <ul class="nav nav-tabs icb-nav-tabs right padding-10">
        <li class="nav-tabs-title"></li>
    </ul>
    <div class="col-sm-1 padding20"> <span class="btn bg-primary">全部分类</span></div>

    <!-- 全部分类icon列表 -->
    <div class="col-sm-11">
        <ul class="col-sm-12">
            <?php
            foreach($this->categoryList as $_categoryInfo) {?>
            <li class="pull-left margin-5 padding5">
            <a class="text-color-999" href="<?php echo MODULE;?>/index/category-<?php echo $_categoryInfo['url_token']; ?>"><?php
            if ($_categoryInfo['icon']) {
            ?><img style="max-width:30px; max-height:18px; width:auto;display:inline-block;" src="//www.icodebang.cn/uploads/nav_menu/<?php echo $_categoryInfo['icon'] ;?>" alt="<?php echo $_categoryInfo['title'];?>"> <?php
                }?><?php echo $_categoryInfo['title']; ?></a>
            </li>
            <?php }?>
        </ul>
    </div>
    <!-- end 全部分类icon列表 -->
</div>
