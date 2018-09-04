<?php View::output('global/header.php'); ?>

<div class="icb-container">
	<?php View::output('block/content_nav_menu.php'); ?>

	<div class="container">
		<div class="row">
		<?php if (isset($this->chapterList)) { ?>
		<form id="chapter_form" method="post" action="test/download_excel/" enctype="multipart/form-data" class="form-horizontal">
			<dl class="verify-attach">
				<dt><?php _e('从PDF中识别出来的目录'); ?>:</dt>
			</dl>
			<dl >
				<dt class="col-sm-5 clearfix"><?php _e('章节'); ?>:</dt>
				<dt class="col-sm-5 clearfix"><?php _e('页码'); ?>:</dt>
			</dl>
			<?php foreach ($this->chapterList as $row) { ?>
			<dl >
				<dd class="col-sm-5 clearfix">
					<?php echo $row[0]; ?>
				</dd>
				<dd class="col-sm-5 clearfix">
					<?php echo $row[1]; ?>
				</dd>
			<?php } ?>
			</dl>
			<dl class="col-sm-12 clearfix">
				<dd>
				<input type="hidden" name="excelData" value="<?php echo json_encode($this->chapterList, JSON_UNESCAPED_UNICODE); ?>"/>
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

<?php View::output('global/footer.php'); ?>
