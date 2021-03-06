<?php View::output('install/header.php'); ?>

<div class="icb-mod-body">
	<form action="" method="post" id="installer">
	<input type="hidden" name="step" value="3" />
	<dl>
		<dt>• <?php _e('配置系统'); ?></dt>
		<dd><?php _e('需要您提供必要的系统配置信息'); ?></dd>
	</dl>
	<?php if ($this->error_messages) { ?>
	<div class="error">
		<h3><?php _e('请修正问题后继续'); ?></h3>
		
		<?php foreach ($this->error_messages AS $error_message) { ?>
		<p><?php echo $error_message; ?></p>
		<?php } ?>
	</div>
	<?php } ?>
	<ul>
		<?php if (!defined('IN_SAE')) { ?>
		<li class="collapse">
			<b><?php _e('数据库驱动'); ?></b> 
			
			<select name="db_driver">
			<?php if ($this->mysqi_support) { ?>
				<option value="MySQLi" selected="selected">MySQLi (<?php _e('内存占用较低'); ?>)</option>
			<?php } ?>
			<?php if ($this->pdo_support) { ?>
			  	<option value="PDO_MYSQL">PDO (<?php _e('性能更强'); ?>)</option>
			<?php } ?>
			</select>
			
			<span><?php _e('请根据服务器状态选择数据库驱动'); ?></span>
		</li>
		<li>
			<b><?php _e('数据库主机'); ?></b><input type="text" class="default_text" value="localhost" name="db_host" /><span><?php _e('通常为 localhost'); ?></span>
		</li>
		<li>
			<b><?php _e('数据库帐号'); ?></b><input type="text" class="default_text" value="" name="db_username" />
		</li>
		<li>
			<b><?php _e('数据库密码'); ?></b><input type="text" class="default_text" value="" name="db_password" />
		</li>
		<li>
			<b><?php _e('数据库端口'); ?></b><input type="text" class="default_text" value="" name="db_port" /><span><?php _e('一般情况下不需要填写'); ?></span>
		</li>
		<li>
			<b><?php _e('数据库名称'); ?></b><input type="text" class="default_text" value="" name="db_dbname" />
		</li>
		<li>
			<b><?php _e('数据表前缀'); ?></b><input type="text" class="default_text" value="aws_" name="db_prefix" /><span><?php _e('同数据库安装多个本程序时需要更改'); ?></span>
		</li>
		<?php } else { ?>
		<input type="hidden" name="db_prefix" value="aws_" />
		<li>
			<b><?php _e('上传目录地址'); ?></b><input type="text" class="default_text" value="" name="upload_url" /><span><?php _e('上传目录外部访问 URL 地址'); ?></span></li>
		</li>
		<?php } ?>
		<li>
			<b><?php _e('数据表类型'); ?></b><select name="db_engine" class="default_text"><option value="MyISAM">MyISAM</option><option value="InnoDB">InnoDB</option></select><span><?php _e('请根据服务器状态选择数据表类型'); ?></span></li>
		</li>
	</ul>
	<a href="javascript:;" onclick="document.getElementById('installer').submit(); this.className='btn btn-success disabled pull-right'; this.onclick=''; return false;" class="btn btn-success pull-right"><?php _e('开始安装'); ?></a>
	</form>
</div>

<?php View::output('install/footer.php'); ?>