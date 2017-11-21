<?php View::output('m/header.php'); ?>

	<?php if ($this->content_nav_menu) { ?>
	<!-- 分类 -->
	<div class="top-category">
		<ul>
			<li><a <?php if (!$_GET['category'] AND !$this->feature_info) { ?> class="active"<?php } ?> href="<?php echo $this->content_nav_menu['base']['link']; ?>"><?php _e('全部'); ?></a></li>
			<?php foreach ($this->content_nav_menu as $key => $val) { ?>
			<?php if ($val['title']) { ?>
			<li>
				<a <?php if (($val['type'] == 'category' AND $_GET['category'] AND ($val['type_id'] == $this->category_info['id'])) OR ($val['type'] == 'feature' AND $this->feature_info['id'] == $val['type_id'])) { ?> class="active"<?php } ?> href="<?php echo $val['link']; ?>"<?php if ($val['type'] == 'custom') { ?> target="_blank"<?php } ?>><?php echo $val['title']; ?></a>
			</li>
			<?php } ?>
			<?php } ?>
		</ul>
	</div>
	<!-- end 分类 -->
	<?php } ?>

	<!-- 内容 -->
	<div class="container">
		<!-- 问题列表 -->
		<div class="icb-question-list">
			<div class="mod-body">
				<ul id="load_explore_list">
					<?php if ($this->posts_list) { ?>
					<?php foreach($this->posts_list as $key => $val) { ?>
					<li>
						<div class="mod-body">
							<?php if ($val['question_id']) { ?>
								<h2><a href="m/question/<?php echo $val['question_id']; ?>"><?php echo $val['question_content']; ?></a></h2>
							<?php } else { ?>
								<h2><a href="m/article/<?php echo $val['id']; ?>"><?php echo $val['title']; ?></a></h2>
							<?php } ?>
						</div>
						<div class="mod-footer">
							<?php if ($val['question_id']) { ?>
							<?php if ($val['answer_count'] > 0) { ?>
								<?php if ($val['answer_info']['anonymous']) { ?>
									<a href="javascript:;"><img class="img" src="<?php echo G_STATIC_URL; ?>/common/avatar-max-img.png" alt="" width="20" /></a>
								<?php } else { ?>
									<a data-id="<?php echo $val['answer_info']['user_info']['uid']; ?>" href="user/<?php echo $val['answer_info']['user_info']['url_token']; ?>" rel="nofollow"><img class="img" src="<?php echo get_avatar_url($val['answer_info']['user_info']['uid'], 'max'); ?>" alt="" width="20" /></a>
								<?php } ?>
							<?php } else { ?>
								<?php if ($val['anonymous'] == 0) { ?>
									<a data-id="<?php echo $val['user_info']['uid']; ?>" href="user/<?php echo $val['user_info']['url_token']; ?>" rel="nofollow"><img class="img" src="<?php echo get_avatar_url($val['user_info']['uid'], 'max'); ?>" alt="" width="20" /></a>
								<?php } else { ?>
									<a><img width="20" src="<?php echo G_STATIC_URL; ?>/common/avatar-max-img.png" alt="<?php _e('匿名用户'); ?>" title="<?php _e('匿名用户'); ?>" /></a>
								<?php } ?>
							<?php } ?>
							<?php } ?>
							<?php if ($val['question_id']) { ?>
							<?php if ($val['answer_count'] > 0) { ?>
								<?php if ($val['answer_info']['anonymous']) { ?><a href="javascript:;"><?php _e('匿名用户'); ?></a><?php } else { ?><a href="m/user/<?php echo $val['answer_info']['user_info']['url_token']; ?>"><?php echo $val['answer_info']['user_info']['user_name']; ?></a><?php } ?> <?php _e('回复了问题'); ?>
									<span class="pull-right color-999"><?php echo date_friendly($val['update_time'], 604800, 'Y-m-d'); ?></span>
							<?php } else { ?>
								<?php if ($val['anonymous'] == 0) { ?><a href="m/user/<?php echo $val['user_info']['url_token']; ?>"><?php echo $val['user_info']['user_name']; ?></a><?php } else { ?><a href="javascript:;"><?php _e('匿名用户'); ?></a><?php } ?> <?php _e('发起了问题'); ?>
									<span class="pull-right color-999"><?php echo date_friendly($val['add_time'], 604800, 'Y-m-d'); ?></span>
							<?php } ?>
							<?php } else { ?>
								<a href="m/user/<?php echo $val['user_info']['url_token']; ?>"><?php echo $val['user_info']['user_name']; ?></a>
								<?php _e('发起了文章'); ?>
								<span class="pull-right color-999"><?php echo date_friendly($val['add_time'], 604800, 'Y-m-d'); ?></span>
							<?php } ?>
						</div>

					</li>
					<?php } ?>
					<?php } ?>
				</ul>
			</div>
			<div class="mod-footer">
				<a id="load_explore" class="icb-load-more" auto-load="false"><i class="icon icon-loading icon-spin"></i> <span><?php _e('更多'); ?></span></a>
			</div>
		</div>
		<!-- end 问题列表 -->

		<!-- 热门话题 -->
		<?php if ($this->sidebar_hot_topics) { ?>
		<div class="icb-explore-hot-topic">
			<div class="mod-head">
				<h3><?php _e('热门话题'); ?></h3>
			</div>
			<div class="mod-body">
				<ul>
					<?php foreach($this->sidebar_hot_topics AS $key => $val) {?>
					<li>
						<a class="pull-left" href="m/topic/<?php echo $val['topic_title'];?>"><img src="<?php echo getMudulePicUrlBySize('topic', 'mid', $val['topic_pic']); ?>" alt="" width="50" /></a>
						<div class="content pull-left">
							<span class="article-tag clearfix">
								<a class="text" href="m/topic/<?php echo $val['topic_title'];?>"><?php echo $val['topic_title'];?></a>
							</span>
							<p class="color-999">
								<span class="text-color-999"><?php _e('%s 个讨论', '<b>' . $val['discuss_count'] . '</b>'); ?> • <?php _e('%s 个关注', '<b>' . $val['focus_count'] . '</b>'); ?></span>
							</p>
						</div>
					</li>
					<?php } ?>
				</ul>
			</div>
		</div>
		<?php } ?>
		<!-- end 热门话题 -->

	</div>
	<!-- end 内容 -->


<script type="text/javascript">
	$(document).ready(function ()
	{
		<?php if ($_GET['category']) { ?>
		AWS.load_list_view(G_BASE_URL + '/index/ajax/list/sort_type-new__category' + '-' + <?php echo $_GET['category']; ?> + '__day-0__is_recommend-0', $('#load_explore'), $('#load_explore_list'), 2);
		<?php } else { ?>
		AWS.load_list_view(G_BASE_URL + '/index/ajax/list/sort_type-new__day-0__is_recommend-0', $('#load_explore'), $('#load_explore_list'), 2);
		<?php } ?>

		if ($('.top-category').length)
		{
			var length = 0;

			$.each($('.top-category li'), function (i, e)
			{
				length += $(this).innerWidth();
			});

			$('.top-category ul').css('width', length + 10)

			var myScroll = new IScroll('.top-category', { eventPassthrough: true, scrollX: true, scrollY: false, preventDefault: false });
		}
	});


</script>

<?php View::output('m/footer.php'); ?>
