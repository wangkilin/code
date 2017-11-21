<?php View::output('global/header.php'); ?>

<div class="icb-container">
	<div class="container">
		<div class="row">
			<div class="icb-content-wrap clearfix">
				<div class="col-sm-12 icb-main-content">
					<div class="icb-mod icb-mod-search-result">
						<div class="mod-head">
							<div class="tabbable">
								<ul class="nav nav-tabs icb-nav-tabs right" id="list_nav">
									<?php if (!$_GET['is_recommend']) { ?>
									<li><a href="#users" data-toggle="tab"><?php _e('用户'); ?></a></li>
									<li><a href="#topics" data-toggle="tab"><?php _e('话题'); ?></a></li>
									<?php } ?>
									<li><a href="#questions" data-toggle="tab"><?php _e('问题'); ?></a></li>
									<li><a href="#articles" data-toggle="tab"><?php _e('文章'); ?></a></li>
									<li class="active"><a href="#all" data-toggle="tab"><?php _e('全部'); ?></a></li>
									<h2 class="hidden-xs">
										<p><?php _e('搜索'); ?> - <span id="icb-search-type"></span>
										</p>
									</h2>
								</ul>
							</div>
						</div>
						<div class="mod-body">
							<div class="tab-content">
								<div class="tab-pane active">
									<div id="search_result"></div>

									<!-- 加载更多内容 -->
									<a class="icb-get-more" id="search_result_more" data-page="1">
										<span><?php _e('更多'); ?>...</span>
									</a>
									<!-- end 加载更多内容 -->
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="<?php echo G_STATIC_URL; ?>/js/app/search.js"></script>

<script type="text/javascript">
	search_query = '<?php echo addslashes($this->keyword); ?>';
	split_query = '<?php echo addslashes($this->split_keyword); ?>';
	<?php if ($_GET['is_recommend']) { ?>search_recommend = true;<?php } ?>
</script>

<?php View::output('global/footer.php'); ?>