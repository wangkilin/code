<?php View::output('global/header.php'); ?>
<style>
.parentNode{
    display:block;
    cursor:pointer;
}
.childNode{
    display:none;
}
.childNode dd:first-child{
    text-indent:20px;
}
.dataItem {
    border-width: 1px 0 0px 0;
    border-color: #ccc;
    border-style: dotted;
}
</style>
<div class="icb-container">
	<?php View::output('block/content_nav_menu.php'); ?>

	<div class="container">
		<div class="row">
		<?php if (isset($this->chapterList)) { ?>
		<form id="chapter_form" method="post" action="test/download_excel/" enctype="multipart/form-data" class="form-horizontal">
			<dl class="verify-attach clearfix">
				<dt><?php _e('从PDF中识别出来的目录'); ?>:</dt>
			</dl>
			<dl class="dataItem">
				<dt class="col-sm-4"><?php _e('章节'); ?>:</dt>
				<dt class="col-sm-4"><?php _e('页码'); ?>:</dt>
				<dt class="col-sm-3">&nbsp;</dt>
			</dl>
            <?php
            $i = 0;
            foreach ($this->chapterList as $row) {
                if (''===$row[1]) {
                    $i++;
                    $class='parentNode';
                } else {
                    $class='childNode';
                }
                $dataSet = 'block' . $i;
            ?>
			<dl class="<?php echo $class;?> dataItem" data-set="<?php echo $dataSet;?>">
				<dd class="col-sm-4">
					<?php echo ''===$row[1]? '<b>'.$row[0].'</b>':$row[0]; ?>&nbsp;
				</dd>
				<dd class="col-sm-4">
					<?php echo $row[1]; ?>&nbsp;
				</dd>
				<dd class="col-sm-3">
					&nbsp;
				</dd>
			</dl>
			<?php } ?>
			<dl class="col-sm-12 clearfix">
                <dt>&nbsp;</dt>
				<dd>
				<input type="hidden" name="excelData" value='<?php echo json_encode($this->chapterList, JSON_UNESCAPED_UNICODE); ?>'/>
				<input type="hidden" name="filename" value="<?php echo $this->filename;?>"/>
				<input type="submit" class="btn btn-primary btn-large" value="下载表格" />
			    </dd>
			</dl>

		</form>
		<?php } ?>
		<form id="verify_form" method="post" action="test/index-square/" enctype="multipart/form-data" class="form-horizontal">
			<dl class="verify-attach col-sm-12">
				<dt><?php _e('请选择PDF文件上传'); ?>:</dt>
				<dd>
					<input type="file" class="" name="attach" />
				</dd>
			</dl>
			<dl>
				<dd><input type="submit" class="btn btn-primary btn-large" value="上传" /></dd>
			</dl>

		</form>

		</div>
	</div>
</div>
<script type="text/javascript">
$('.parentNode').click(function(){
    var dataSet = $(this).attr('data-set');
    $('.childNode').filter('[data-set='+dataSet+']').toggle();
});
</script>

<?php View::output('global/footer.php'); ?>
