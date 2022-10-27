<?php View::output('global/header.php'); ?>
<?php View::output('global/nav.php'); ?>
	<div class="container icb-custom-page">
		<div class="row">
            <?php if ($this->navCategoryList) {?>
            <?php if (count($this->navCategoryList) > 1 ) { //只有一个分类，不用显示了 ?>
            <div class="col-sm-2">
                <ul class="text-left">
                <?php foreach ($this->navCategoryList as $_itemInfo) {
                        $_itemInfo['url_token'] == '' AND $_itemInfo['url_token'] = $_itemInfo['id'];
                ?>
                    <li class="btn <?php echo $_GET['category'] == $_itemInfo['url_token'] ? 'active' : '';?> btn-default btn-block"><a class="" href="/page/<?php echo $this->_isInside ? 'inside_square/' : ''; ?>category-<?php echo $_itemInfo['url_token'];?>.html"><?php echo $_itemInfo['title'];?></a></li>
                <?php }?>
                </ul>
            </div>
            <?php }?>
			<div class="icb-article-list <?php echo count($this->navCategoryList) > 1 ? 'col-sm-10' : 'col-sm-12'?>">
                <ul class="nav nav-tabs icb-nav-tabs right padding-10">
					<li class="nav-tabs-title"><h4 class=""><i class="icon icon-list"></i> <?php echo is_array($this->categoryInfo)?$this->categoryInfo['title'] : _t('动态');?></h4></li>
                </ul>
                <div class="padding10">
                <div class="clearfix prefix-dot list-inline">
                <?php
                $this->itemList = $this->pageList;
                if ($this->itemList) {
                View::output('block/page_list_with_thumb.php');
                } else {?>
                    <div class="text-info text-center"><?php _e('暂无内容');?></div>
                <?php }
                ?>
                </div>
                <?php echo $this->pagination; ?>
                </div>
			</div>

            <?php }?>
		</div>
	</div>
<div class="container margin20"></div>
<?php View::output('global/footer.php'); ?>
