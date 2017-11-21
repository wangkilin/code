<?php View::output('m/header.php'); ?>

<!-- 标题 -->
<div class="icb-title">
	<?php _e('我的私信'); ?>
	<!-- <a class="pull-right btn btn-success btn-mini" onclick="AWS.dialog('message')">发私信</a> -->
</div>
<!-- end 标题 -->

<!-- 内容 -->
<div class="container">
	<!-- 私信列表 -->
	<div class="icb-feed-list icb-inbox-list active">
		<div class="mod-body">
			<ul id="inbox_listview">
			</ul>
		</div>
		<div class="mod-footer">
			<a id="load_inbox" class="icb-load-more"><i class="icon icon-loading icon-spin"></i> <span>更多</span></a>
		</div>
	</div>
	<!-- end 私信列表 -->
</div>
<!-- end 内容 -->

<script type="text/javascript">
	$(document).ready(function () {
		AWS.load_list_view(G_BASE_URL + '/m/ajax/inbox_list/', $('#load_inbox'), $('#inbox_listview'), 1);
	});
</script>

<?php View::output('m/footer.php'); ?>