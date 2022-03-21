<?php if (Application::config()->get('system')->debug) { ?>
<div class="well clearfix" style="margin: 20px; word-break:break-all;">
	<b style="font-weight: bold; color: red;">Debug messages:</b><br >
	<br /><p><b style="font-weight: bold;">Template:</b> <?php echo $this->template_name; ?></p>
	<br /><p><b style="font-weight: bold;">Session:</b></p>
	<p>Session type: <?php echo Application::$session_type; ?></p>
	<?php foreach ($_SESSION AS $key => $val) { ?>
		<p>[<?php echo $key; ?>] <?php echo print_r($val, true); ?></p>
	<?php } ?>
	<br /><p><b style="font-weight: bold;">Plugins:</b></p>
	<?php foreach (Application::plugins()->plugins_list() AS $key => $val) { ?>
		<p><?php echo $val; ?> (ID: <?php echo $key; ?>)</p>
	<?php } ?>
	<br /><p><b style="font-weight: bold;">Loaded Class:</b></p>
	<?php foreach (core_autoload::$loaded_class AS $key => $val) { ?>
		<p><b><?php echo $key; ?></b>: <?php echo $val; ?></p>
	<?php } ?>
	<?php foreach (Application::$_debug AS $key => $val) { ?>
	<br /><p><b style="font-weight: bold;"><?php echo ucfirst($key); ?></b></p>
		<?php foreach ($val AS $_key => $_val) { ?>
		<p>[ Log time: <?php echo $_val['log_time']; ?> ] <?php if ($_val['expend_time']) { ?>[ Expend time: <?php echo $_val['expend_time']; ?> ]<?php } ?> <?php echo $_val['message']; ?></p>
		<?php } ?>
	<?php } ?>
	<br />
	<p style="color: #666;">Escape time: <?php echo (microtime(TRUE) - START_TIME); ?>, <?php echo count(Application::$_debug['database']); ?> queries<?php if (defined('MEMORY_USAGE_START')) { ?>, PHP Memory usage: <?php echo ((memory_get_usage() - MEMORY_USAGE_START) / 1024); ?> KB<?php } ?>, Server time: <?php echo date('Y-m-d H:i:s', TIMESTAMP); ?></p>
</div>
<?php } else if (!defined('IN_AJAX')) { ?><!-- Escape time: <?php echo (microtime(TRUE) - START_TIME); ?> --><?php } ?>
