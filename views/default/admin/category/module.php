<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
            <h3>
            <?php View::output('admin/category/nav_inc.php');?>
            </h3>
        </div>

        <div class="tab-content mod-body">
            <div class="alert alert-success collapse error_message"></div>

            <div class="table-responsive">
            待开发： 分类属于模块； 模块绑定到控制器名称； 在控制器中调用分类时， 自动加载本模块分类；
            <br/>
            <br/>
            另外， 模板页面是否需要引入layout概念？ 当前是每个页面都将头尾信息引入。
            <br/>
            引入layout概念后，每个页面只关注本页面自己的内容。 公共内容在layout中定义。

            </div>
        </div>
    </div>
    <div id="target-category" class="collapse">
        <?php echo $this->target_category; ?>
    </div>
</div>

<?php View::output('admin/global/footer.php'); ?>