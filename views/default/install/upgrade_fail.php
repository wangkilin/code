<?php View::output('install/header.php'); ?>

<div class="icb-mod-body">
	<dl>
		<dt>• <?php _e('升级失败'); ?></dt>
		<dd><?php _e('升级过程中可能出了一些问题'); ?></dd>
	</dl>
	<div class="error">
		<p><?php echo $this->sql_error; ?></p>
	</div>
	<ul>
		<li>
			<b><?php _e('如何解决'); ?></b><span><?php _e('可以手工执行 SQL, 如果遇到错误跳过那句 SQL 继续执行, 操作完成后继续刷新本页面'); ?>, <a href="<?php echo base_url(); ?>/<?php echo G_INDEX_SCRIPT; ?>upgrade/sql/<?php echo $this->version; ?>" target="_blank"><?php _e('从这里下载 SQL 文件'); ?></a></span>
		</li>
	</ul>
</div>

<!-- Analytics --><img src="http://www.wecenter.com/analytics/?build=<?php echo $this->version; ?>&amp;site_name=<?php echo urlencode(get_setting('site_name')); ?>&amp;base_url=<?php echo urlencode(base_url()); ?>&amp;sql_error=1&amp;php=<?php echo PHP_VERSION; ?>" alt="" width="1" height="1" /><!-- / Analytics -->

<?php View::output('install/footer.php'); ?>