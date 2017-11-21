<div class="icb-container">
	<div class="container">
		<div class="row">
			<div class="icb-content-wrap clearfix">
				<div class="icb-user-setting">
					<div class="tabbable">
						<ul class="nav nav-tabs icb-nav-tabs">
							<li class="nav-tabs-title hidden-xs"><i class="icon icon-setting"></i> <?php _e('用户设置'); ?></li>
							<li<?php if (!$_GET['act'] OR $_GET['act'] == 'profile') { ?> class="active"<?php } ?>><a href="account/setting/profile/"><?php _e('基本资料'); ?></a></li>
							<li<?php if ($_GET['act'] == 'verify') { ?> class="active"<?php } ?>><a href="account/setting/verify/"><?php _e('申请认证'); ?></a></li>
							<li<?php if ($_GET['act'] == 'security') { ?> class="active"<?php } ?>><a href="account/setting/security/"><?php _e('安全设置'); ?></a></li>
							<li<?php if ($_GET['act'] == 'openid') { ?> class="active"<?php } ?>><a href="account/setting/openid/"><?php _e('账号绑定'); ?></a></li>
							<li<?php if ($_GET['act'] == 'privacy') { ?> class="active"<?php } ?>><a href="account/setting/privacy/"><?php _e('隐私/提醒'); ?></a></li>

						</ul>
					</div>

					<div class="tab-content clearfix">