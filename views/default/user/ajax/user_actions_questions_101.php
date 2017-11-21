<?php if (is_array($this->list)) { ?>
<?php foreach ($this->list AS $key => $val) { ?>	
<div class="icb-item">
	<div class="icb-mod">
		<div class="mod-head">
			<h4 class="icb-hide-txt">
				<a href="<?php echo $val['link']; ?>"><?php echo $val['title']; ?></a>
			</h4>
		</div>
		<div class="mod-body">
			<span class="icb-border-radius-5 count pull-left"><i class="icon icon-reply"></i><?php echo $val['question_info']['answer_count']; ?></span>
			<p class="text-color-999"><?php _e('%s 次浏览', $val['question_info']['view_count']); ?> • <?php _e('%s 个关注', $val['question_info']['focus_count']); ?> • <?php echo date_friendly($val['add_time']); ?></p>
		</div>
	</div>
</div>
<?php } ?>
<?php } ?>