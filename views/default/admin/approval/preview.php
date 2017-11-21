<p><strong><?php _e('内容'); ?></strong></p>
<blockquote><?php echo $this->approval_item['content']; ?></blockquote>

<?php if ($this->approval_item['data']['topics']) {  ?>
<p><strong><?php _e('话题'); ?></strong></p>
<blockquote><?php echo htmlspecialchars(implode(', ', $this->approval_item['data']['topics'])); ?></blockquote>
<?php } ?>

<?php if ($this->approval_item['attachs']) {  ?>
<p><strong><?php _e('附件'); ?></strong></p>

<blockquote class="icb-upload-img-list">
<?php foreach ($this->approval_item['attachs'] AS $attach) { ?>
<?php if ($attach['is_image']) { ?>
    <p><a href="<?php echo $attach['attachment']; ?>" target="_blank" data-fancybox-group="thumb" rel="lightbox"><img src="<?php echo $attach['attachment']; ?>" class="img-polaroid" alt="<?php echo $attach['attach_name']; ?>" /></a></p>
<?php } ?>
<?php } ?>
</blockquote>

<blockquote>
    <ul class="icb-upload-file-list">
        <?php foreach ($this->approval_item['attachs'] AS $attach) { ?>
        <?php if (!$attach['is_image']) { ?>
            <li><a href="<?php echo download_url($attach['file_name'], $attach['attachment']); ?>"><em class="icb-icon i-upload-file"></em><?php echo $attach['file_name']; ?></a></li>
        <?php } ?>
        <?php } ?>
    </ul>
</blockquote>
<?php } ?>

<form id="approval_form" action="admin/ajax/approval_manage/" method="post">
    <input type="hidden" id="approval_type" name="batch_type" value="approval" />

    <input type="hidden" name="type" value="<?php echo htmlspecialchars($this->approval_item['type']); ?>" />

    <input type="hidden" name="approval_id" value="<?php echo $this->approval_item['id']; ?>" />
</form>

<p align="center">	<a class="btn btn-success" onclick="$('#approval_type').val('approval'); AWS.ajax_post($('#approval_form'));" href="javascript:;"><?php _e('通过审核'); ?></a> &nbsp; <a class="btn btn-danger" onclick="$('#approval_type').val('decline'); AWS.ajax_post($('#approval_form'));" href="javascript:;"><?php _e('拒绝审核'); ?></a></p>