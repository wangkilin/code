<?php View::output('m/header.php'); ?>

<!-- 标题 -->
<div class="icb-title">
	<?php _e('附近的人'); ?>
</div>
<!-- end 标题 -->

<!-- 内容 -->
<div class="container">
	<!-- 用户列表 -->
	<div class="icb-feed-list icb-inbox-list active">
		<div class="mod-body">
			<ul>
				<?php foreach ($this->near_by_users AS $key => $val) { ?>
				<li>
					<div class="mod-head clearfix" style="min-height:70px;">
						<a href="m/user/<?php echo $val['url_token']; ?>"><img class="img" width="50" src="<?php echo get_avatar_url($val['uid'], 'max'); ?>" /></a>
						<a href="m/user/<?php echo $val['url_token']; ?>"><?php echo $val['user_name']; ?></a>
						<p class="color-999">
							<?php echo $val['signature']; ?>
						</p>
					</div>
					<div class="mod-footer active">
						<a><i class="glyphicon glyphicon-map-marker"></i> <?php _e('%s 公里以内', $val['distance']); ?></a>
						<span class="color-999 pull-right"><?php echo date_friendly($val['location_update']); ?></span>
					</div>
				</li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<!-- end 用户列表 -->
</div>
<!-- end 内容 -->


</body>
</html>