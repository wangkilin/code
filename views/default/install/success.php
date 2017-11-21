<?php View::output('install/header.php'); ?>

<div class="icb-mod-body">
	<dl>
		<dt>• <?php _e('安装成功'); ?></dt>
		<dd><?php _e('欢迎使用 WeCenter 问答交流平台'); ?>, <?php _e('为了增强安全性, 请将 install/index.php 文件删除'); ?></dd>
	</dl>
	
	<a href="../" class="btn btn-success pull-right"><?php _e('访问网站首页'); ?></a>
</div>

<!-- Analytics --><img src="http://www.wecenter.com/analytics/?build=<?php echo G_VERSION_BUILD; ?>&amp;php=<?php echo PHP_VERSION; ?>" alt="" width="1" height="1" /><!-- / Analytics -->

<?php View::output('install/footer.php'); ?>