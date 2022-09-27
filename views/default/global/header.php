<?php View::output('global/header_meta.php'); ?>

<body>
	<div class="icb-top-menu-wrap">
            <?php if (strpos($_SERVER['HTTP_HOST'], 'icodebang.cn') === false &&
                      strpos($_SERVER['HTTP_HOST'], 'icodebang.com') === false &&
                      strpos($_SERVER['HTTP_HOST'], 'devboy.cn')     === false ) {
           ?>
		<div class="hidden-xs bg-primary">
        &nbsp;  &nbsp; Interested in this domain ? Email: icodebang#126.com (#->@)&nbsp;  &nbsp; 本站寻求合作
        </div>
        <?php } ?>
		<div class="container">
			<!-- logo -->
			<div class="icb-logo hidden-xs">
				<a href="<?php echo base_url(); ?>"><?php
                  if (strpos($_SERVER['HTTP_HOST'], 'devboy.cn')===false) {
                    echo '爱码帮';
                  } else {
                    echo '开发者';
                  }
                ?></a>
			</div>
			<!-- end logo -->
			<!-- 导航 -->
			<div class="icb-top-nav navbar">
				<div class="navbar-header">
				  <button  class="navbar-toggle pull-left">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				  </button>
				</div>
				<nav role="navigation" class="collapse navbar-collapse bs-navbar-collapse">
				  <ul class="nav navbar-nav">
					<li><a href="<?php echo base_url(); ?>" class="<?php if (!$_GET['app'] OR $_GET['app'] == 'index') { ?>active<?php } ?>"><i class="icon icon-home"></i><?php _e('首页'); ?></a></li>
                    <?php if(Application::config()->get('system')->model['article']['status']) {
                    ?><li><a href="article/" class="<?php if ($_GET['app'] == 'article') { ?>active<?php } ?>"><?php _e('文章'); ?></a></li><?php } ?>
                    <?php if(Application::config()->get('system')->model['course']['status']) {
                    ?><li><a href="course/" class="<?php if ($_GET['app'] == 'course') { ?>active<?php } ?>"><?php _e('教程'); ?></a></li><?php } ?>
                    <?php if(Application::config()->get('system')->model['asked']['status']) {
                    ?><li><a href="asked/" class="<?php if ($_GET['app'] == 'asked') { ?>active<?php } ?>"><?php _e('面试题'); ?></a></li><?php } ?>
                    <?php if(Application::config()->get('system')->model['manual']['status']) {
                    ?><li><a href="manual/" class="<?php if ($_GET['app'] == 'manual') { ?>active<?php } ?>"><?php _e('手册'); ?></a></li><?php } ?>
                    <?php if(Application::config()->get('system')->model['job']['status']) {
                    ?><li><a href="job/" class="<?php if ($_GET['app'] == 'job') { ?>active<?php } ?>"><?php _e('工作'); ?></a></li><?php } ?>
                    <!-- <li><a href="project/" class="<?php if ($_GET['app'] == 'project') { ?>active<?php } ?>"><?php _e('项目'); ?></a></li> -->

                    <?php if ($this->user_id) { ?>
                        <?php if(Application::config()->get('system')->model['home']['status']) {
                    ?><li><a href="home/"<?php if ($_GET['app'] == 'home') { ?> class="active"<?php } ?>><?php _e('动态'); ?></a></li><?php } ?>
					<?php } ?>

					<?php if(Application::config()->get('system')->model['question']['status']) {
                    ?><li><a href="question/" class="<?php if ($_GET['app'] == 'question') { ?>active<?php } ?>"><?php _e('问题'); ?></a></li><?php } ?>

                    <?php if(Application::config()->get('system')->model['topic']['status']) {
                    ?><li><a href="topic" <?php if ($_GET['app'] == 'topic') { ?>class="active"<?php } ?>><?php _e('话题'); ?></a></li><?php } ?>
					<?php if ($this->user_id) { ?>
                    <?php if(Application::config()->get('system')->model['notifications']['status']) {
                    ?><li>
						<a href="notifications/" class="<?php if ($_GET['app'] == 'notifications') { ?>active<?php } ?>"><i class="icon icon-bell"></i> <?php _e('通知'); ?></a>
						<span class="badge badge-important" style="display:none" id="notifications_unread"><?php echo $this->user_info['notification_unread']; ?></span>
						<div class="icb-dropdown pull-right hidden-xs">
							<div class="mod-body">
								<ul id="header_notification_list"></ul>
							</div>
							<div class="mod-footer">
								<a href="notifications/"><?php _e('查看全部'); ?></a>
							</div>
						</div>
					</li><?php } ?>
					<?php } ?>
					<?php if (check_extension_package('ticket') && get_setting('ticket_enabled') == 'Y' && ($this->user_info['permission']['publish_ticket'] || $this->user_info['permission']['is_administortar'] || $this->user_info['permission']['is_service'])) { ?>
                    <!-- <li><a href="ticket/"<?php if ($_GET['app'] == 'ticket') { ?> class="active"<?php } ?><?php _e('工单'); ?></a></li>-->
                    <?php } ?>
                    <?php if (check_extension_package('project') && get_setting('project_enabled') == 'Y') { ?>
                    <li><a href="project/"<?php if ($_GET['app'] == 'project') { ?> class="active"<?php } ?><?php _e('活动'); ?></a></li>
                    <?php } ?>

					<?php if (get_setting('enable_help_center') == 'Y') { ?><!--<li><a href="help/"<?php if ($_GET['app'] == 'help') { ?> class="active"<?php } ?>><i class="icon icon-bulb"></i> <?php _e('帮助'); ?></a></li>--><?php } ?>

                    <!-- <li><a href="test/" class="<?php if ($_GET['app'] == 'test') { ?>active<?php } ?>"><?php _e('测试'); ?></a></li> -->
                  </ul>
				</nav>
			</div>
			<!-- end 导航 -->
            <!-- 搜索框 -->
            <div class="icb-search-box  hidden-xs hidden-sm">
                <form class="navbar-search" action="search/" id="global_search_form" method="post">
                    <input class="form-control search-query" type="text" placeholder="<?php _e('输入关键字搜索文章，教程'); ?>" autocomplete="off" name="q" id="icb-search-query" data-dropdown-type="tip"/>
                    <span title="<?php _e('搜索'); ?>" id="global_search_btns" onClick="$('#global_search_form').submit();"><i class="icon icon-search"></i></span>
                    <div class="icb-dropdown">
                        <div class="mod-body">
                            <p class="title"><?php _e('输入关键字进行搜索'); ?></p>
                            <ul class="icb-dropdown-list collapse"></ul>
                            <p class="tip"><span><?php _e('搜索'); ?>:</span><a onClick="$('#global_search_form').submit();"></a></p>
                        </div>
                        <div class="mod-footer">
                            <a href="<?php if (get_setting('quick_publish') == 'Y' && $this->user_id) { ?>javascript:;<?php } else { ?>publish<?php } ?>" onClick="$('#header_publish').click();" class="pull-right btn btn-mini btn-success publish"><?php _e('发起问题'); ?></a>
                        </div>
                    </div>
                </form>
            </div>
            <!-- end 搜索框 -->
			<!-- 用户栏 -->
			<div class="icb-user-nav">
				<!-- 登陆&注册栏 -->
				<?php if ($this->user_id) { ?>
					<a href="user/<?php echo $this->user_info['url_token']; ?>" class="icb-user-nav-dropdown">
						<img id="user_avatar" alt="<?php echo $this->user_info['user_name']; ?>" src="<?php echo get_avatar_url($this->user_info['uid'], 'mid'); ?>" />
						<?php if ($this->user_info['inbox_unread'] != 0) {?>
							<span class="badge badge-important"><?php echo $this->user_info['inbox_unread']?></span>
						<?php } ?>

					</a>
					<div class="icb-dropdown dropdown-list pull-right">
						<ul class="icb-dropdown-list">
							<li><a href="inbox/"><i class="icon icon-inbox"></i> <?php _e('私信'); ?><span class="badge badge-important collapse" id="inbox_unread">0</span></a></li>
							<li class="hidden-xs"><a href="account/setting/profile/"><i class="icon icon-setting"></i> <?php _e('设置'); ?></a></li>
							<?php if ($this->user_info['permission']['is_administortar'] OR $this->user_info['permission']['is_moderator']) { ?>
							<li class="hidden-xs"><a href="admin/"><i class="icon icon-job"></i> <?php _e('管理'); ?></a></li>
							<?php } ?>
							<li><a href="account/logout/"><i class="icon icon-logout"></i> <?php _e('退出'); ?></a></li>
						</ul>
					</div>
				<?php } else { ?>
					<a class="login btn btn-normal btn-secondary" href="account/login/"><?php _e('登录'); ?></a>
					<?php if (get_setting('register_type') == 'open') { ?><a class="register btn btn-normal  btn-primary" href="account/register/"><?php _e('注册'); ?></a><?php } ?>
				<?php } ?>
				<!-- end 登陆&注册栏 -->
			</div>
			<!-- end 用户栏 -->
			<!-- 发起 -->
			<?php if ($this->user_id) { ?>
			<div class="icb-publish-btn">

				<a id="header_publish" class="btn-primary" <?php if (get_setting('quick_publish') == 'Y' AND $this->user_id) { ?>href="publish/"<?php } else { ?>href="publish/<?php if ($_GET['category']) { ?>category_id-<?php echo intval($_GET['category']); ?>__<?php } ?><?php if ($this->topic_info) { ?>topic_title-<?php echo urlencode($this->topic_info['topic_title']); ?>__<?php } ?>"<?php } ?><?php if (get_setting('quick_publish') == 'Y' && $this->user_id) { ?> onclick="AWS.dialog('publish', {'category_enable':'<?php echo (get_setting('category_enable') == 'Y') ? '1' : '0'; ?>', 'category_id':'<?php echo intval($_GET['category']); ?>', 'topic_title':'<?php echo $this->topic_info['topic_title']; ?>'}); return false;"<?php } ?>><i class="icon icon-ask"></i><?php _e('发起'); ?></a>
				<div class="dropdown-list pull-right">
					<ul>
						<li>
							<form method="post" action="publish/">
								<?php if ($this->topic_info['topic_title']) { ?>
								<input type="hidden" value="<?php echo $this->topic_info['topic_title']; ?>" name="topics[]" />
								<?php } ?>
								<a onclick="$(this).parents('form').submit();"><?php _e('问题'); ?></a>
							</form>

						</li>
						<?php if ($this->user_info['permission']['publish_article']) { ?>
						<li>
							<form method="post" action="publish/article/">
								<?php if ($this->topic_info['topic_title']) { ?>
								<input type="hidden" value="<?php echo $this->topic_info['topic_title']; ?>" name="topics[]" />
								<?php } ?>
								<a onclick="$(this).parents('form').submit();"><?php _e('文章'); ?></a>
							</form>
						</li>
						<?php } ?>
						<?php if (check_extension_package('ticket') && get_setting('ticket_enabled') == 'Y' && $this->user_info['permission']['publish_ticket']) { ?>
						<li><a href="ticket/publish/"><?php _e('工单'); ?></a></li>
						<?php } ?>
						<?php if (check_extension_package('project') && get_setting('project_enabled') == 'Y' && $this->user_info['permission']['publish_project']) { ?>
						<li><a href="project/publish/"><?php _e('活动'); ?></a></li>
						<?php } ?>
					</ul>
				</div>
			</div>
			<?php }?>
			<!-- end 发起 -->
		</div>
	</div>
	<?php if ($this->user_id AND $this->user_info['email'] AND !$this->user_info['valid_email'] AND get_setting('register_valid_type') != 'N') { ?>
	<div class="icb-email-verify">
		<div class="container text-center">
			<a onclick="AWS.ajax_request(G_BASE_URL + '/account/ajax/send_valid_mail/');"><?php _e('你的邮箱 %s 还未验证, 点击这里重新发送验证邮件', $this->user_info['email']); ?></a>
		</div>
	</div>
	<?php } ?>

