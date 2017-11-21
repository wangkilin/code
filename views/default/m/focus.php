<?php View::output('m/header.php'); ?>

<!-- 标题 -->
<div class="icb-title">
	<?php _e('我关注的问题'); ?> <span class="badge"><?php echo $this->user_info['focus_count']; ?></span>
</div>
<!-- end 标题 -->

<!-- 内容 -->
<div class="container">
	<!-- 动态列表 -->
	<div class="icb-feed-list">
		<div class="mod-body">
			<ul id="index_listview"></ul>
		</div>
		<div class="mod-footer">
			<a id="load_index_actions" class="icb-load-more"><i class="icon icon-loading icon-spin"></i> <span><?php _e('更多'); ?></span></a>
		</div>
	</div>
	<!-- end 动态列表 -->

	<?php View::output('m/nav_menu.php'); ?>

</div>
<!-- end 内容 -->

<script type="text/javascript">
	$(document).ready(function () {
		AWS.load_list_view(G_BASE_URL + '/home/ajax/index_actions/filter-focus', $('#load_index_actions'), $('#index_listview'));
	});
</script>

<?php View::output('m/footer.php'); ?>