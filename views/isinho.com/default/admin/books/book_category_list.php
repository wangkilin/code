<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
	<div class="mod">
        <div class="mod-head">
            <label>
            <?php View::output('admin/books/nav_inc.php');?>
            </label>
        </div>
		<div class="mod-body tab-content">
			<div class="tab-pane active" id="category">
                <div class="text-warning text-right"> ※ 备注信息里填写用来识别图书分类的关键字。关键字之间用逗号( , )分割</div>
                <?php View::output('admin/block/simple_admin_category_list.php'); ?>
			</div>
            <?php View::output('admin/books/search_inc.php');?>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function () {
	});
</script>

<?php View::output('admin/global/footer.php'); ?>
