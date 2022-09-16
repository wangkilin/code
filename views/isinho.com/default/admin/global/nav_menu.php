<div class="icb-side" id="icb-side">
    <div class="mod">
        <div class="mod-logo">
            <img class="pull-left" src="<?php echo G_STATIC_URL; ?>/isinho.com/logo-blue-fat-small.png" alt="" />
            <h1>绩效管理系统</h1>
        </div>

        <div class="mod-message">
            <div class="message">
                <a class="btn btn-sm" href="<?php echo base_url(); ?>" target="_blank" title="<?php _e('首页'); ?>">
                    <i class="icon icon-home"></i>
                </a>

                <a class="btn btn-sm" href="admin/" title="<?php _e('概述'); ?>">
                    <i class="icon icon-ul"></i>
                </a>

                <a class="btn btn-sm" href="account/logout/" title="<?php _e('退出'); ?>">
                    <i class="icon icon-logout"></i>
                </a>
            </div>
        </div>

        <ul class="mod-bar" >
            <input type="hidden" id="hide_values" val="0" />
            <?php foreach ($this->menu_list as $key => $val) { ?>
            <li data-menu-id="<?php echo $val['id']?>">
                <a href="<?php if ($val['url'] AND !$val['children']) { echo $val['url']; } else { ?>javascript:;<?php } ?>" class=" icon icon-<?php echo $val['cname']; if ($val['select']) { ?> active on<?php } ?>"<?php if ($val['children']) { ?> data="icon"<?php } ?>>
                    <span><?php echo $val['title']; ?></span>
                </a>
                <?php if ($val['children']) { ?>

                <ul<?php if (!$val['select']) { ?> class="collapse"<?php } ?>>
                    <?php foreach ($val['children'] as $child) { ?>
                    <li data-menu-id="<?php echo $val['id']?>">
                        <a class=" icon icon-<?php echo $child['cname']; if ($child['select']) { echo " active"; } ?>" href="<?php echo $child['url']; ?>">
                            <span><?php echo $child['title'];?></span>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
                <?php } ?>
            </li>
            <?php } ?>
        </ul>
    </div>
</div>
