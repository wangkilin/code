<?php View::output('global/header.php'); ?>

<?php View::output('global/nav.php'); ?>
	<div class="container icb-custom-page">
		<div class="row">
            <?php if ($this->categoryList) {?>
            <?php if (count($this->categoryList) > 1 ) { //只有一个分类，不用显示了 ?>
            <div class="col-sm-2">
                <ul class="text-left">
                <?php foreach ($this->categoryList as $_itemInfo) {
                        $_itemInfo['url_token'] == '' AND $_itemInfo['url_token'] = $_itemInfo['id'];
                ?>
                    <li class="btn <?php echo $_GET['category'] == $_itemInfo['url_token'] ? 'active' : '';?> btn-default btn-block"><a class="" href="/page/category-<?php echo $_itemInfo['url_token'];?>.html"><?php echo $_itemInfo['title'];?></a></li>
                <?php }?>
                </ul>
            </div>
            <?php }?>
			<div class="<?php echo count($this->categoryList) > 1 ? 'col-sm-10' : 'col-sm-12'?>">
                <ul class="nav nav-tabs icb-nav-tabs right padding-10">
					<li class="nav-tabs-title"><h4 class=""><i class="icon icon-reader"></i> <?php echo $this->page_info['title'];?></h4></li>
                </ul>
                <?php if ($this->user_info['permission']['is_administortar'] ) { ?>
                <a class="text-color-999" href="/admin/page/edit/id-<?php echo $this->page_info['id']; ?>"><i class="icon icon-edit"></i> <?php _e('编辑'); ?></a>
                <a class="text-color-999" onclick="ICB.domEvents.deleteShowConfirmModal( _t('确认删除？'), function(){ ICB.ajax.requestJson(G_BASE_URL + '/admin/ajax/remove_page/', 'id=<?php echo $this->page_info['id'];?>;',function(){window.location.href='/';} ); });"><i class="icon icon-trash"></i> <?php _e('删除'); ?></a>
                <?php } ?>
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
<div class="container margin20"></div>
<?php View::output('global/footer.php'); ?>
