<?php View::output('stock/block/header_meta.php'); ?>
<body>
	<div class="icb-top-menu-wrap">
		<div class="container">
			<!-- logo -->
			<div class="icb-logo hidden-xs">
				<a href="<?php echo base_url(); ?>"></a>
			</div>
            <!-- end logo -->

			<!-- 导航 -->
			<div class="icb-top-nav navbar">
				<nav role="navigation" class="collapse navbar-collapse bs-navbar-collapse">
				  <ul class="nav navbar-nav">
					<li><a href="<?php echo base_url(); ?>" class="<?php if (!$_GET['app'] OR $_GET['app'] == 'index') { ?>active<?php } ?>"><i class="icon icon-home"></i><?php _e('首页'); ?></a></li>
                    <li><a href="about/" class="<?php if ($_GET['app'] == 'about') { ?>active<?php } ?>"><?php _e('免责声明'); ?></a></li>
                  </ul>
                </nav>
			</div>
		</div>
	</div>

