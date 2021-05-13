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
					<li class="nav-tabs-title"><h4 class=""><i class="icon icon-reader"></i> <?php echo $this->page_info['title'];?></h4></li>
                </ul>
                <div class="padding10">
				    <?php echo $this->page_info['contents']; ?>
                </div>

                <div class="clearfix">
                    <span class="col-sm-6 padding10">上一篇: <?php if($this->prevPageInfo) echo '<a href="/page/'.$this->prevPageInfo['url_token'].'.html">', $this->prevPageInfo['title'],'</a>'; else echo '没有了';?></span>
                    <span class="col-sm-6 padding10 text-right">下一篇: <?php if($this->nextPageInfo) echo '<a href="/page/'.$this->nextPageInfo['url_token'].'.html">', $this->nextPageInfo['title'],'</a>'; else echo '没有了';?></span>
                </div>
			</div>

            <?php } ?>
		</div>
	</div>
</div>

<?php View::output('global/footer.php'); ?>
