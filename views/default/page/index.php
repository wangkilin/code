<?php View::output('global/header.php'); ?>

<div class="icb-container">
	<div class="container icb-custom-page">
		<div class="row">
			<div class="col-sm-12">
				<?php echo $this->page_info['contents']; ?>
			</div>
		</div>
	</div>
</div>

<?php View::output('global/footer.php'); ?>