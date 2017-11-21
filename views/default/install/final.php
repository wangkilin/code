<?php View::output('install/header.php'); ?>

<div class="icb-mod-body">
	<form action="" method="post" id="installer">
	<input type="hidden" name="step" value="4" />
	<input type="hidden" name="upload_url" value="<?php echo $_POST['upload_url']; ?>" />
	<dl>
		<dt>• <?php _e('添加管理员'); ?></dt>
		<dd><?php _e('数据库导入成功, 创建管理员账户'); ?></dd>
	</dl>
	<ul>
		<li>
			<b><?php _e('用户名'); ?></b><input type="text" name="user_name" value="" id="user_name" class="default_text"/>
		</li>
		<li>
			<b><?php _e('密码'); ?></b><input type="password" name="password" id="_password" maxlength="16" class="default_text" />
		</li>
		<li>
			<b>E-mail</b><input type="text" name="email" id="email" class="default_text" />
		</li>
	</ul>
	<a href="javascript:;" onclick="if (document.getElementById('user_name').value == '' || document.getElementById('_password').value == '' || document.getElementById('email').value == '') { alert('<?php _e('请填写管理员账户信息'); ?>'); } if (document.getElementById('_password').length < 6) { alert('<?php _e('密码长度必需大于 6 位'); ?>'); } else { document.getElementById('installer').submit(); this.className='btn btn-success disabled pull-right'; this.onclick=''; }" class="btn btn-success pull-right"><?php _e('完成'); ?></a>
	</form>
</div>

<?php View::output('install/footer.php'); ?>