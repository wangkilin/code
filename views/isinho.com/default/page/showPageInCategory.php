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
			<div class="<?php echo count($this->navCategoryList) > 1 ? 'col-sm-10' : 'col-sm-12'?>">
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
                <?php if ($this->user_info['uid'] ) { ?>
                <div class="row">
                    <span class="col-sm-2"></span>
                    <span class="col-sm-8">
                        <button data-page-id="<?php echo $this->page_info['id']?>"  onclick="setItemRead($(this));return false;" class="btn btn-lg <?php echo $this->page_info['is_receipt_required']==1?'btn-primary':'';?> col-sm-12"><?php _e('阅读完成');?></button>
                    </span>
                </div>
                <?php } ?>

                <div class="clearfix">
                    <span class="col-sm-6 padding10">上一篇: <?php if($this->prevPageInfo) echo '<a href="/page/'. ($this->_isInside ? 'inside_index/' : '').$this->prevPageInfo['url_token'].'.html">', $this->prevPageInfo['title'],'</a>'; else echo '没有了';?></span>
                    <span class="col-sm-6 padding10 text-right">下一篇: <?php if($this->nextPageInfo) echo '<a href="/page/'.( $this->_isInside ? 'inside_index/' : '').$this->nextPageInfo['url_token'].'.html">', $this->nextPageInfo['title'],'</a>'; else echo '没有了';?></span>
                </div>
			</div>

            <?php } ?>
		</div>
	</div>
<div class="container margin20"></div>
<script>
function setItemRead($obj) {
    var pageId = $obj.data('page-id');
    // 没有阅读过， 点击阅读完成按钮， 设置阅读状态；
    if ($obj.hasClass('btn-primary')) {
        $obj.removeClass('btn-primary'); // 移除按钮可以点击的状态
        // 发送阅读完成请求
        ICB.ajax.requestJson("/admin/ajax/page/set_read/", {page_id:pageId}, function(){});
    }

    return false;
}
</script>
<?php View::output('global/footer.php'); ?>
