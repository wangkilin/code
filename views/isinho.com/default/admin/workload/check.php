<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
            <h3>
                <ul class="nav nav-tabs">
                    <li class="<?php if($_GET['by']=='book') echo 'active'; ?>">
                    <a href="<?php
                        echo $_GET['by']=='book'?'#index" data-toggle="tab':'admin/check_list/by-book'
                        ?>"><?php _e('书稿工作量'); ?></a>
                    </li>
                    <li class="<?php if($_GET['by']=='user') echo 'active'; ?>">
                    <a href="<?php
                        echo $_GET['by']=='user'?'#index" data-toggle="tab':'admin/check_list/by-user'
                        ?>"><?php _e('编辑工作量'); ?></a>
                    </li>
                </ul>
            </h3>
        </div>

        <?php if($_GET['by']=='book') { 
            // 查看书稿的工作量
            View::output('admin/workload/check_by_book.php');
            } else if ($_GET['by']=='user') {
            // 查看员工的工作量
            View::output('admin/workload/check_by_user.php');
            }
        ?>
                  
    </div>
</div>

<script type="text/javascript">
$(function(){
});
</script>

<?php View::output('admin/global/footer.php'); ?>
