<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
            <h3>
				<?php View::output('admin/page/nav_inc.php');?>
            </h3>
        </div>

		<div class="mod-body tab-content">

            <div class="tab-pane active" id="read_log">
                <blockquote class=""><p><?php echo $this->page_info['title'];?></p></blockquote>
                <div class="table-responsive">
                <?php if ($this->read_list) { ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><?php _e('用户'); ?></th>
                                <th><?php _e('阅读时间'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($this->read_list AS $_item) { ?>
                            <tr>
                                <td><?php echo $this->user_list[$_item['user_id']];?></td>
                                <td><?php echo $_item['read_time'];?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <div class="text-center">
                        <?php _e('还没有阅读记录');?>
                    </div>
                <?php } ?>
                </div>
                <div class="mod-table-foot">
                    <?php echo $this->pagination; ?>
                </div>
            </div>


            <?php View::output('admin/books/search_inc.php');?>
        </div>
	</div>
</div>

<script type="text/javascript">
</script>

<?php View::output('admin/global/footer.php'); ?>
