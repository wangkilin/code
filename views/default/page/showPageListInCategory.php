<?php View::output('global/header.php'); ?>

<div class="icb-container">
	<div class="container icb-custom-page">
		<div class="row">
            <?php if ($this->categoryList) {?>
            <div class="col-sm-2">
                <ul class="text-left">
                <?php foreach ($this->categoryList as $_itemInfo) {
                        $_itemInfo['url_token'] == '' AND $_itemInfo['url_token'] = $_itemInfo['id'];
                ?>
                    <li class="btn <?php echo $_GET['category'] == $_itemInfo['url_token'] ? 'active' : '';?> btn-default btn-block"><a class="" href="/page/category-<?php echo $_itemInfo['url_token'];?>.html"><?php echo $_itemInfo['title'];?></a></li>
                <?php }?>
                </ul>
            </div>
			<div class="col-sm-10">
                <ul class="nav nav-tabs icb-nav-tabs right padding-10">
					<li class="nav-tabs-title"><h4 class=""><i class="icon icon-list"></i> <?php echo $this->categoryInfo['title'];?></h4></li>
                </ul>
                <div class="padding10">
                <ul class="clearfix prefix-dot list-inline">
				<?php foreach ($this->pageList as $_itemInfo) {?>
                    <li class="col-sm-12">
                        <a class="col-sm-9 nooverflow title" href="/page/id-<?php echo $_itemInfo['url_token'];?>.html"><?php echo $_itemInfo['title'];?></a>
                        <span class="col-sm-3 ft12 text-right"><?php echo $_itemInfo['add_time'];?></span>
                    </li>
                <?php }?>
                </ul>
                <?php echo $this->pagination; ?>
                </div>
			</div>

            <?php } ?>
		</div>
	</div>
</div>

<?php View::output('global/footer.php'); ?>
