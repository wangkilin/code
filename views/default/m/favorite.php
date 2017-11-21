<?php View::output('m/header.php'); ?>

<!-- 标题 -->
<div class="icb-title">
	<?php _e('我的收藏'); ?> 
</div>
<!-- end 标题 -->

<!-- 内容 -->
<div class="container">
	<!-- 草稿列表 -->
	<div class="icb-feed-list active">
		<div class="mod-body">
			<ul id="favorite_listview">
			</ul>
		</div>
		<div class="mod-footer">
			<a id="load_favorite" class="icb-load-more"><i class="icon icon-loading icon-spin"></i> <span><?php _e('更多'); ?></span></a>
		</div>
	</div>
	<!-- end 草稿列表 -->

	<?php View::output('m/nav_menu.php'); ?>

</div>
<!-- end 内容 -->

<script type="text/javascript">
	$(document).ready(function () {
		AWS.load_list_view(G_BASE_URL + '/m/ajax/favorite_list/', $('#load_favorite'), $('#favorite_listview'));
	});
</script>

<?php View::output('m/footer.php'); ?>
