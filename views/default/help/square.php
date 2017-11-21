<?php View::output('global/header.php'); ?>

<div class="icb-container">
	<div class="container">
		<div class="row">
			<div class="icb-content-wrap clearfix">
				<div class="col-sm-12 col-md-12 icb-main-content">
					<div class="icb-mod icb-help-center">
						<div class="mod-head">
							<h2><i class="icon icon-bulb"></i><?php _e('帮助中心'); ?></h2>
							<div class="icb-search-box icb-border-radius-5 hidden-xs hidden-sm">
								<form class="navbar-search icb-border-radius-5" action="search/is_recommend-1" id="help_search_form" method="post">
									<input class="form-control search-query icb-border-radius-5" type="text" placeholder="<?php _e('搜索问题'); ?>" autocomplete="off" name="q" id="icb-search-query" />
									<span title="<?php _e('搜索'); ?>" id="global_search_btns" onClick="$('#help_search_form').submit();"><i class="icon icon-search"></i></span>
									<div class="icb-dropdown">
										<div class="mod-body">
											<p class="title"><?php _e('输入关键字进行搜索'); ?></p>
											<ul class="icb-dropdown-list collapse"></ul>
											<p class="search"><span><?php _e('搜索'); ?>:</span><a onClick="$('#global_search_form').submit();"></a></p>
										</div>
										<div class="mod-footer">
											<a href="<?php if (get_setting('quick_publish') == 'Y' && $this->user_id) { ?>javascript:;<?php } else { ?>publish<?php } ?>" onClick="$('#header_publish').click();" class="pull-right btn btn-mini btn-success publish"><?php _e('发起问题'); ?></a>
										</div>
									</div>
								</form>
							</div>
						</div>

						<?php if ($this->chapter_list) { ?>
						<div class="mod-body clearfix">
							<?php foreach ($this->chapter_list AS $chapter_info) { ?>
							<div class="col-sm-4 col-md-4">
								<div class="icb-item">
									<h3>
										<a href="help/<?php echo ($chapter_info['url_token']) ? $chapter_info['url_token'] : $chapter_info['id']; ?>">
											<img class="icb-border-radius-5" src="<?php echo get_chapter_icon_url($chapter_info['id'], 'min'); ?>" alt="<?php echo $chapter_info['title']; ?>">
											<?php echo $chapter_info['title']; ?>
										</a>
									</h3>
									<?php if ($this->data_list[$chapter_info['id']]) { ?>
									<ul>
										<?php foreach ($this->data_list[$chapter_info['id']] AS $data_info) { ?>
										<li class="icb-hide-txt"><a href="<?php echo $data_info['type']; ?>/<?php echo $data_info['id']; ?>"><?php echo $data_info['title']; ?></a></li>
										<?php } ?>
									</ul>
									<?php } ?>
								</div>
							</div>
							<?php } ?>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php View::output('global/footer.php'); ?>
