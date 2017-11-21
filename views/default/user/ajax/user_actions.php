<?php if (is_array($this->list)) { ?>
<?php foreach ($this->list AS $key => $val) { ?>
<?php if ($val['article_info'] AND $val['associate_action'] != ACTION_LOG::ADD_COMMENT_ARTICLE) { ?>
<div class="icb-item">
	<div class="icb-mod">
		<div class="mod-head">
			<h4 class="icb-hide-txt">
			<a href="<?php echo $val['link']; ?>"><?php echo $val['title']; ?></a>
			</h4>
		</div>
		<div class="mod-body">
			<span class="icb-border-radius-5 count pull-left"><i class="icon icon-agree"></i><?php echo $val['article_info']['votes']; ?></span>
			<p class="text-color-999"><?php _e('%s 个评论', $val['article_info']['comments']); ?> • <?php _e('%s 次浏览', $val['article_info']['views']); ?> • <?php echo date_friendly($val['add_time'], 604800, 'Y-m-d'); ?></p>
		</div>
	</div>
</div>
<?php } else { ?>
<div class="icb-item">
	<p>
		<span class="pull-right text-color-999"><?php echo date_friendly($val['add_time'], 604800, 'Y-m-d'); ?></span>
		<em class="pull-left"><?php echo $val['last_action_str']; ?>:</em>
		
		<a class="icb-hide-txt" href="<?php echo $val['link']; ?>"><?php echo cjk_substr($val['title'], 0, 40, 'UTF-8', '...'); ?></a>

	</p>
</div>
<?php } ?>
<?php } ?>
<?php } ?>
