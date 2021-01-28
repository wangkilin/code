<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">

    <div class="mod-head">
                    <h3>
                        <ul class="nav nav-tabs">
                            <li class="active">
                            <a href=""><?php _e('修改密码'); ?></a>
                            </li>
                        </ul>
                    </h3>
                </div>
        <div class="mod-body tab-content">
                <form class="" action="account/ajax/modify_password/" method="post" id="post_form">
                        <div class="form-group">
                            <label class="control-label" for="input-password-old"><?php _e('当前密码'); ?></label>
                            <div class="row">
                                <div class="col-lg-4">
                                    <input type="password" class="form-control" id="input-password-old" name="old_password" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="input-password-new"><?php _e('新的密码'); ?></label>
                            <div class="row">
                                <div class="col-lg-4">
                                    <input type="password" class="form-control" id="input-password-new" name="password" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="input-password-re-new"><?php _e('确认密码'); ?></label>
                            <div class="row">
                                <div class="col-lg-4">
                                    <input type="password" class="form-control" id="input-password-re-new" name="re_password" />
                                </div>
                            </div>
                        </div>


                        <div class="clearfix">
                            <a href="javascript:;" class="btn btn-large btn-success" onclick="ICB.ajax.postForm($('#post_form'));"><?php _e('保 存'); ?></a>
                        </div>
                </form>
        </div>
	</div>
</div>


<?php View::output('admin/global/footer.php'); ?>
