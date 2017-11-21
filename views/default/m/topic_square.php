<?php View::output('m/header.php'); ?>

<!-- top-nav -->
<div class="top-nav">
	<ul>
		<?php if ($this->user_id) { ?>
		<li class="active">
			<a href="#my_topic" role="tab" data-toggle="tab"><?php _e('我关注的话题'); ?></a>
		</li>
		<?php } ?>
		<li <?php if (!$this->user_id) { ?>class="active" <?php } ?>>
			<a href="#hot_topic" role="tab" data-toggle="tab"><?php _e('热门话题'); ?></a>
		</li>
	</ul>
</div>
<!-- end top-nav -->

<!-- 内容 -->
<div class="container">
	<div class="tab-content">
		<!-- 我关注的话题 -->
		<?php if ($this->user_id) { ?>
		<div class="tab-pane active" id="my_topic">
			<div class="icb-feed-list icb-topic-list active">
				<div class="mod-body">
					<ul id="focus_topics_listview"></ul>
				</div>
				<div class="mod-footer">
					<a class="icb-load-more" id="focus_topics_list"><i class="icon icon-loading icon-spin"></i> <span><?php _e('更多'); ?></span></a>
				</div>
			</div>
		</div>
		<?php } ?>
		<!-- end 我关注的话题 -->
		<!-- 热门话题 -->
		<div class="tab-pane <?php if (!$this->user_id) { ?>active<?php } ?>" id="hot_topic">
			<div class="icb-feed-list icb-topic-list active">
				<div class="mod-body">
					<ul id="hot_topics_list"></ul>
				</div>
				<div class="mod-footer">
					<a class="icb-load-more" id="load_hot_topics"><i class="icon icon-loading icon-spin"></i> <span><?php _e('更多'); ?></span></a>
				</div>
			</div>
		</div>
		<!-- 热门话题 -->
	</div>
</div>
<!-- end 内容 -->

<script type="text/javascript">
	$(document).ready(function () {
		if ($('#focus_topics_listview').length)
		{
			AWS.load_list_view(G_BASE_URL + '/topic/ajax/focus_topics_list/', $('#focus_topics_list'), $('#focus_topics_listview'));
		}
		
		if ($('#hot_topics_list').length)
		{
			AWS.load_list_view(G_BASE_URL + '/m/ajax/hot_topics_list/', $('#load_hot_topics'), $('#hot_topics_list'), 1);
		}
	});
</script>

<?php View::output('m/footer.php'); ?>