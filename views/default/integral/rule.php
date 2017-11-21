<?php View::output('global/header.php'); ?>

<div class="icb-container">
    <div class="container icb-custom-page">
        <div class="row">
            <div class="col-sm-12">
                <div class="icb-point-rule">
                    <h1><?php _e('%s 积分规则', get_setting('site_name')); ?></h1>

                    <dl>
                        <dt><?php _e('新用户注册默认拥有积分'); ?></dt>
                        <dd><span class="<?php if (get_setting('integral_system_config_register') >= 0) { ?>green<?php } else { ?>red<?php } ?>"><?php _e('%s 分', get_setting('integral_system_config_register')); ?></span></dd>
                    </dl>
                    <dl>
                        <dt><?php _e('用户完善资料获得积分（包括头像，一句话介绍，履历等资料）'); ?></dt>
                        <dd><span class="<?php if (get_setting('integral_system_config_profile') >= 0) { ?>green<?php } else { ?>red<?php } ?>"><?php _e('%s 分', get_setting('integral_system_config_profile')); ?></span></dd>
                    </dl>
                    <dl>
                        <dt><?php _e('用户邀请他人注册且被邀请人成功注册'); ?></dt>
                        <dd><span class="<?php if (get_setting('integral_system_config_invite') >= 0) { ?>green<?php } else { ?>red<?php } ?>"><?php _e('%s 分', get_setting('integral_system_config_invite')); ?></span></dd>
                    </dl>
                    <dl>
                        <dt><?php _e('发起问题'); ?></dt>
                        <dd><span class="<?php if (get_setting('integral_system_config_new_question') >= 0) { ?>green<?php } else { ?>red<?php } ?>"><?php _e('%s 分', get_setting('integral_system_config_new_question')); ?></span></dd>
                    </dl>
                    <dl>
                        <dt><?php _e('回复问题'); ?></dt>
                        <dd><span class="<?php if (get_setting('integral_system_config_new_answer') >= 0) { ?>green<?php } else { ?>red<?php } ?>"><?php _e('%s 分', get_setting('integral_system_config_new_answer')); ?></span></dd>
                    </dl>
                    <?php if (get_setting('integral_system_config_answer_change_source') == 'Y' AND get_setting('integral_system_config_new_answer') <= 0) { ?>
                    <dl>
                        <dt><?php _e('问题被回复'); ?></dt>
                        <dd><span class="green"><?php _e('%s 分', -get_setting('integral_system_config_new_answer')); ?></span></dd>
                    </dl>
                    <?php } ?>
                    <dl>
                        <dt><?php _e('回复被评为最佳回复'); ?></dt>
                        <dd><span class="<?php if (get_setting('integral_system_config_best_answer') >= 0) { ?>green<?php } else { ?>red<?php } ?>"><?php _e('%s 分', get_setting('integral_system_config_best_answer')); ?></span></dd>
                    </dl>
                    <dl>
                        <dt><?php _e('感谢回复'); ?></dt>
                        <dd><span class="<?php if (get_setting('integral_system_config_thanks') >= 0) { ?>green<?php } else { ?>red<?php } ?>"><?php _e('%s 分', get_setting('integral_system_config_thanks')); ?></span></dd>
                    </dl>
                    <dl>
                        <dt><?php _e('回复被感谢'); ?></dt>
                        <dd><span class="<?php if (-get_setting('integral_system_config_thanks') >= 0) { ?>green<?php } else { ?>red<?php } ?>"><?php _e('%s 分', -get_setting('integral_system_config_thanks')); ?></span></dd>
                    </dl>
                    <dl>
                        <dt><?php _e('回复被折叠'); ?></dt>
                        <dd><span class="<?php if (get_setting('integral_system_config_answer_fold') >= 0) { ?>green<?php } else { ?>red<?php } ?>"><?php _e('%s 分', get_setting('integral_system_config_answer_fold')); ?></span></dd>
                    </dl>
                    <dl>
                        <dt><?php _e('发起者邀请用户回答问题且收到答案'); ?></dt>
                        <dd><span class="<?php if (get_setting('integral_system_config_invite_answer') >= 0) { ?>green<?php } else { ?>red<?php } ?>"><?php _e('%s 分', get_setting('integral_system_config_invite_answer')); ?></span></dd>
                    </dl>
                    <dl>
                        <dt><?php _e('回复问题发起者的邀请'); ?></dt>
                        <dd><span class="<?php if (-get_setting('integral_system_config_invite_answer') >= 0) { ?>green<?php } else { ?>red<?php } ?>"><?php _e('%s 分', -get_setting('integral_system_config_invite_answer')); ?></span></dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<?php View::output('global/footer.php'); ?>