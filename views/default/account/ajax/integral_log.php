<?php if ($this->log) { ?>
<?php foreach ($this->log AS $key => $val) { ?>
<tr>
	<td><?php echo date_friendly($val['time'], 604800, 'Y-m-d'); ?></td>
	<td><span class="<?php if ($val['integral'] > 0) { ?>icb-text-color-orange<?php } ?>"><?php echo $val['integral']; ?></span></td>
	<td><?php echo $val['balance']; ?></td>
	<td><?php echo $val['note'] ;?></td>
	<td><p><?php if ($this->log_detail[$val['id']]) { ?><a href="<?php echo $this->log_detail[$val['id']]['url']; ?>"><?php echo $this->log_detail[$val['id']]['title']; ?></a><?php } else { ?> - <?php } ?></p></td>
</tr>
<?php } ?>
<?php } ?>