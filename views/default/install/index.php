<?php View::output('install/header.php'); ?>

<div class="icb-mod-body">
<form action="" method="post" id="installer">
<input type="hidden" name="step" value="2" />
	<dl>
		<dt>• <?php _e('欢迎使用'); ?></dt>
		<dd><?php _e('欢迎使用 WeCenter 安装程序, WeCenter 是中国首个基于 PHP + MYSQL 开发的开源化社交问答社区'); ?></dd>
	</dl>
	<dl>
		<dt>• <?php _e('服务器环境检查'); ?></dt>
		<dd><?php _e('为了确保程序安装顺利, 您的服务器需要满足以下系统需求的运行环境'); ?></dd>
	</dl>
	<?php if ($this->error_messages) { ?>
	<div class="error">
		<h3><?php _e('网络限制'); ?></h3>
		
		<?php foreach ($this->error_messages AS $error_message) { ?>
		<p><?php echo $error_message; ?></p>
		<?php } ?>
	</div>
	<?php } ?>
	<ul>
		<li>
			<b><?php _e('PHP 版本'); ?></b><span><?php echo PHP_VERSION; ?></span><?php if (!$this->system_require['php']) { ?><span class="red">× <?php _e('WeCenter 需要最低 %s 版本的 PHP, 并且运行于非安全模式下, 我们推荐使用 PHP 5.3', LOWEST_PHP_VERSION); ?></span><?php } else { ?><span class="green">√</span><?php } ?>
		</li>
		<li>
			<b><?php _e('数据库模块'); ?></b><span><?php echo $this->system_require['db']; ?></span><?php if (!$this->system_require['db']) { ?><span class="red">× <?php _e('WeCenter 支持 MySQLi 与 PDO_MYSQL 两种数据库模块, 您的服务器两种都不支持'); ?></span><?php } else { ?><span class="green">√</span><?php } ?>	
		<li>
			<b><?php _e('Session 支持'); ?></b><?php if (!$this->system_require['session']) { ?><span class="red">×</span><?php } else { ?><span class="green">√</span><?php } ?>
		</li>
		<li>
			<b><?php _e('Cookie 支持'); ?></b><?php if (!$this->system_require['cookie']) { ?><span class="red">×</span><?php } else { ?><span class="green">√</span><?php } ?>
		</li>
		<li>
			<b><?php _e('CType 支持'); ?></b><?php if (!$this->system_require['ctype']) { ?><span class="red">×</span><?php } else { ?><span class="green">√</span><?php } ?>
		</li>
		<li>
			<b><?php _e('CURL 支持'); ?></b><?php if (!$this->system_require['curl']) { ?><span class="red">×</span><?php } else { ?><span class="green">√</span><?php } ?>
		</li>
		<li>
			<b><?php _e('图象处理库'); ?></b><span><?php echo $this->system_require['image_lib']; ?></span><?php if (!$this->system_require['image_lib']) { ?><span class="red">× <?php _e('WeCenter 至少需要有 GD 图象处理库才能正常运行'); ?></span><?php } else { ?><span class="green">√ <?php if ($this->system_require['image_lib'] == 'GD') { ?>(<?php _e('加装 ImageMagick 性能更佳'); ?>)<?php } ?></span><?php } ?>
		</li>
		<li>
			<b><?php _e('FreeType 支持'); ?></b><?php if (!$this->system_require['ft_font']) { ?><span class="red">× <?php _e('WeCenter 的验证码需要 FreeType 支持'); ?></span><?php } else { ?><span class="green">√</span><?php } ?>
		</li>
		<li>
			<b><?php _e('Zlib 支持'); ?></b><?php if (!$this->system_require['zlib']) { ?><span class="red">× <?php _e('WeCenter 的搜索需要 Zlib 支持'); ?></span><?php } else { ?><span class="green">√</span><?php } ?>
		</li>
		<li>
			<b><?php _e('Mcrypt 支持'); ?></b><?php if (!$this->system_require['mcrypt']) { ?><span class="red">× <?php _e('WeCenter 的加密需要 Mcrypt 支持'); ?></span><?php } else { ?><span class="green">√</span><?php } ?>
		</li>
		<li>
			<b><?php _e('编码转换'); ?></b><?php if (!$this->system_require['convert_encoding']) { ?><span class="red">× <?php _e('WeCenter 至少需要有 MB 或 ICONV 编码转换处理库才能正常运行'); ?></span><?php } else { ?><span class="green">√</span><?php } ?>
		</li>
		<li>
			<b><?php _e('上传限制'); ?></b><span><?php echo get_cfg_var('upload_max_filesize'); ?> (<?php _e('此处建议值'); ?> > 8M)</span>
		</li>
		<li>
			<b><?php _e('目录权限'); ?></b><span><?php echo INC_PATH; ?></span><?php if (!$this->system_require['config_writable_core']) { ?><span class="red">×</span><?php } else { ?><span class="green">√</span><?php } ?>
		</li>
		<li>
			<b><?php _e('目录权限'); ?></b><span><?php echo INC_PATH; ?>config/</span><?php if (!$this->system_require['config_writable_config']) { ?><span class="red">×</span><?php } else { ?><span class="green">√</span><?php } ?>
		</li>
	</ul>
	<a class="btn btn-success pull-right<?php if (sizeof($this->system_require) != 13) { ?> disabled<?php } ?>" <?php if (sizeof($this->system_require) == 13) { ?>onclick="document.getElementById('installer').submit(); this.className='btn btn-success disabled pull-right'; this.onclick='';"<?php } ?>><?php _e('下一步'); ?></a>
	 </form>
</div>

<?php View::output('install/footer.php'); ?>