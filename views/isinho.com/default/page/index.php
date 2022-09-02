<?php View::output('global/header.php'); ?>
<div class="icb-container">
	<div class="container icb-custom-page">
		<div class="row">
            <?php if ($this->page_list) {?>
            <div class="col-sm-2">
                <ul class="text-left">
                <?php foreach ($this->page_list as $_itemInfo) {?>
                    <li class="btn <?php echo $_GET['id'] == $_itemInfo['url_token'] ? 'active' : '';?> btn-default btn-block"><a class="" href="/page/<?php echo $_itemInfo['url_token'];?>.html"><?php echo $_itemInfo['title'];?></a></li>
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
			</div>

            <?php } else {?>
			<div class="col-sm-12">
                <ul class="nav nav-tabs icb-nav-tabs right padding-10">
					<li class="nav-tabs-title"><h4 class=""><i class="icon icon-list"></i> <?php echo $this->page_info['title'];?></h4></li>
                </ul>
                <div class="padding10">
				    <?php echo $this->page_info['contents']; ?>
                </div>
			</div>
			</div>
            <?php } ?>
		</div>
	</div>
</div>
<div class="container margin20"></div>

<?php View::output('global/footer.php'); ?>
