<?php View::output('global/header.php'); ?>

<div class="icb-container">
	<div class="container icb-feature-title">
		<div class="row">
			<div class="col-xs-12 col-sm-12">
				<h2><?php _e('专题'); ?>: <?php echo $this->feature_info['title']; ?></h2>

				<?php if ($this->feature_info['icon']) { ?>
				<div class="img" style="background:url(<?php echo get_feature_pic_url('max', $this->feature_info['icon']); ?>) no-repeat;"></div>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="icb-content-wrap clearfix">
				<div class="col-xs-12 col-sm-9 icb-main-content icb-feature-content">
					<!-- 热门解答 -->
					<div class="icb-mod clearfix">
						<div class="mod-head">
							<!-- tab切换 -->
							<ul class="nav nav-tabs icb-nav-tabs right" id="hot_question_control">
								<li><a href="#day_30" onclick="get_hot_posts(30);" data-toggle="tab"><?php _e('30天'); ?></a></li>
								<li><a href="#day_7" onclick="get_hot_posts(7);" data-toggle="tab"><?php _e('7天'); ?></a></li>
								<li class="active"><a href="#day_all" onclick="get_hot_posts(0);" data-toggle="tab"><?php _e('全部'); ?></a></li>
								<h2><?php _e('热门内容'); ?></h2>
							</ul>
							<!-- end tab切换 -->
						</div>
						<div class="mod-body">
							<div class="icb-common-list" id="hot_question_list"></div>
						</div>
					</div>
					<!-- end 热门解答 -->

					<!-- 最佳回复 -->
					<div class="icb-mod clearfix">
						<div class="mod-head">
							<ul class="nav nav-tabs icb-nav-tabs right">
								<h2><?php _e('最佳回复'); ?></h2>
							</ul>
						</div>
						<div id="c_best_list" class="mod-body icb-feed-list">
						</div>
					</div>
					<!-- end 最佳回复 -->

					<!-- 专题动态 -->
					<div class="icb-mod clearfix">
						<div class="mod-head">
							<!-- tab切换 -->
							<ul class="nav nav-tabs icb-nav-tabs right" id="feature_dynamic">
								<li><a href="#unresponsive" data-toggle="tab"><?php _e('等待回复'); ?></a></li>
								<li class="active"><a href="#all" data-toggle="tab"><?php _e('全部'); ?></a></li>
								<h2><?php _e('专题动态'); ?></h2>
							</ul>
							<!-- end tab切换 -->
						</div>
						<div class="mod-body">
							<div class="icb-common-list" id="c_all_list"></div>
						</div>
						<div class="mod-footer">
							<!-- 加载更多 -->
							<a class="icb-get-more" id="bp_all_more">
								<span><?php _e('更多'); ?>...</span>
							</a>
							<!-- end 加载更多 -->
						</div>
					</div>
					<!-- end 专题动态 -->
				</div>
				<!-- 侧边栏 -->
				<div class="col-xs-12 col-sm-3 icb-side-bar">
					<!-- 热门话题 -->
					<div class="icb-mod">
						<div class="mod-head">
							<h2><?php _e('该专题包含'); ?> <?php _e('%s 个话题', '<span class="i_blue">' . $this->feature_info['topic_count'] . '</span>'); ?></h2>
						</div>
						<?php if (is_array($this->sidebar_hot_topics)) { ?>
						<div class="mod-body">
							<?php foreach($this->sidebar_hot_topics AS $key => $val) {?>
							<dl>
								<dt class="pull-left icb-border-radius-5">
									<a href="topic/<?php echo $val['url_token']; ?>"><img alt="" src="<?php echo getMudulePicUrlBySize('topic', 'mid', $val['topic_pic']); ?>" /></a>
								</dt>
								<dd class="pull-left">
									<a href="topic/<?php echo $val['url_token']; ?>" class="icb-topic-name" data-id="<?php echo $val['topic_id']; ?>"><span><?php echo $val['topic_title']; ?></span></a>
									<p><?php _e('该话题下有'); ?> <?php _e('%s 个讨论', $val['discuss_count']); ?></p>
								</dd>
							</dl>
							<?php } ?>
						</div>
						<?php } ?>
					</div>
					<!-- end 热门话题 -->
				</div>
				<!-- end 侧边栏 -->
				</div>
		</div>
	</div>
</div>

<?php if ($this->feature_info['css']) { ?>
<style type="text/css">
<?php echo $this->feature_info['css']; ?>
</style>
<?php } ?>

<script type="text/javascript">
	var FEATURE_ID = <?php echo $this->feature_info['id']; ?>;
</script>

<?php View::output('global/footer.php'); ?>