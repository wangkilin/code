<?php if ($this->attach) {  ?>
<?php if ($this->attach['is_image']) { ?>
<div class="icb-upload-img-list active">
<?php } else { ?>
<ul class="icb-upload-file-list">
<?php } ?>
<?php if ($this->attach['is_image']) { ?>
	<a href="<?php echo $this->attach['attachment']; ?>" target="_blank" data-fancybox-group="thumb" rel="lightbox"><img src="<?php echo $this->attach['attachment']; ?>" class="img-polaroid" title="<?php echo $this->attach['file_name']; ?>" alt="<?php echo $this->attach['file_name']; ?>" /></a>
<?php } else { ?>
	<li><a href="<?php echo download_url($this->attach['file_name'], $this->attach['attachment']); ?>"><em class="icb-icon i-upload-file"></em><?php echo $this->attach['file_name']; ?></a></li>
<?php } ?>
<?php if ($this->attach['is_image']) { ?>
</div>
<?php } else { ?>
</ul>
<?php } ?>
<?php } ?>
