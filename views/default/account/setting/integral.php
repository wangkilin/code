<?php View::output('global/header.php'); ?>
<?php View::output('account/setting/setting_header.php'); ?>

<div class="icb-mod icb-user-setting-points">
	<div class="icb-mod-head">
		<h3><?php _e('当前积分'); ?>:<span class="icb-text-color-orange"><?php echo $this->user_info['integral']; ?></span></h3>
	</div>
	<div class="icb-mod-body">
		<table class="table table-hover">
			<thead>
				<tr class="info">
					<th width="19%"><?php _e('时间'); ?></th> 
                    <th width="11%"><?php _e('数额'); ?></th>
                    <th width="13%"><?php _e('余额'); ?></th>
                    <th width="17%"><?php _e('描述'); ?></th>
                    <th width="40%"><?php _e('相关信息'); ?></th>
				</tr>
			</thead>
            <tbody id="contents_integral_log">
            </tbody>
	    </table>
	    <!-- 加载更多内容 -->
		<a class="icb-get-more" id="integral_log_more">
			<span><?php _e('更多'); ?>...</span>
		</a>
		<!-- end 加载更多内容 -->
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function () {
		AWS.load_list_view(G_BASE_URL + '/account/ajax/integral_log/', $('#integral_log_more'), $('#contents_integral_log'));
	});
</script>

<?php View::output('account/setting/setting_footer.php'); ?>
<?php View::output('global/footer.php'); ?>