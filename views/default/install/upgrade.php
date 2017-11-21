<?php View::output('install/header.php'); ?>

<div class="icb-mod-body">
	<dl>
		<dt>• <?php _e('版本升级'); ?></dt>
		<dd><?php _e('欢迎使用 WeCenter 升级程序, 本程序仅适用于 1.0.2 及以上版本升级.'); ?><br /><strong style="color:orange"><?php _e('升级过程可能比较缓慢, 在未显示升级成功之前不要关闭浏览器!'); ?></strong></dd>
	</dl>
	<ul>
		<li>
			<b><?php _e('数据版本'); ?></b><span><?php echo $this->db_version; ?></span>
		</li>
		<li>
			<b><?php _e('程序版本'); ?></b><span><?php echo G_VERSION; ?> Build <?php echo G_VERSION_BUILD; ?></span>
		</li>
	</ul>

	<a class="btn btn-success pull-right" onclick="window.location = '<?php echo base_url(); ?>/<?php echo G_INDEX_SCRIPT; ?>upgrade/run/<?php echo TIMESTAMP; ?>'; this.className='btn btn-success disabled pull-right'; this.onclick=''; return false;"><?php _e('下一步'); ?></a>
</div>

<?php View::output('install/footer.php'); ?>