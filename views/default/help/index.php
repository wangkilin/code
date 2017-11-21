<?php View::output('global/header.php'); ?>

<div class="icb-container">
	<div class="container">
		<div class="row">
			<div class="icb-content-wrap clearfix">
				<div class="col-sm-12 col-md-9 icb-main-content">
					<div class="common-head">
						<h2><?php _e('帮助中心'); ?></h2>
					</div>
					<div class="icb-mod icb-help-list">
						<div class="mod-head">
							<img class="icb-border-radius-5" src="<?php echo get_chapter_icon_url($this->chapter_info['id']); ?>" alt="<?php echo $this->chapter_info['title']; ?>">
							<h3><?php echo $this->chapter_info['title']; ?></h3>
						</div>

						<?php if ($this->data_list) { ?>
						<div class="mod-body">
							<ul>
								<?php foreach ($this->data_list AS $data_info) { ?>
								<li><a href="<?php echo $data_info['type']; ?>/<?php echo $data_info['id']; ?>"><?php echo $data_info['title']; ?></a></li>
								<?php } ?>
							</ul>
						</div>
						<?php } ?>
					</div>
				</div>

				<!-- 侧边栏 -->
				<div class="col-sm-12 col-md-3 icb-side-bar icb-help-side hidden-xs hidden-sm">
					<?php if ($this->chapter_list) { ?>
					<div class="icb-mod">
						<div class="mod-head">
							<h4><?php _e('所有章节'); ?></h4>
						</div>

						<div class="mod-body">
							<?php foreach ($this->chapter_list AS $chapter_info) { ?>
							<dl>
								<dt class="pull-left">
									<a href="help/<?php echo ($chapter_info['url_token']) ? $chapter_info['url_token'] : $chapter_info['id']; ?>">
										<img class="icb-border-radius-5" src="<?php echo get_chapter_icon_url($chapter_info['id'], 'min'); ?>" alt="<?php echo $chapter_info['title']; ?>">
									</a>
								</dt>
								<dd>
									<a href="help/<?php echo ($chapter_info['url_token']) ? $chapter_info['url_token'] : $chapter_info['id']; ?>"><?php echo $chapter_info['title']; ?></a>
								</dd>
							</dl>
							<?php } ?>
						</div>
					</div>
					<?php } ?>
				</div>
				<!-- end 侧边栏 -->
			</div>
		</div>
	</div>
</div>

<?php View::output('global/footer.php'); ?>
