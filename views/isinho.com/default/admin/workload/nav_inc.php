
                <ul class="nav nav-tabs">
                    <li class="<?php echo ACTION=='fill_list'?'active':'';?>">
                        <a href="<?php
                        echo ACTION=='fill_list'?'#fill_list" data-toggle="tab':'admin/fill_list/'
                        ?>"><?php _e('我的工作量'); ?></a>
                    </li>
                    <?php if ($this->hostConfig && $this->hostConfig->sinho_feature_list['allow_editor_add_book']) { ?>
                    <li class="<?php echo ACTION=='report'?'active':'';?>">
                        <a href="<?php
                        echo ACTION=='report'?'#my_new" data-toggle="tab':'admin/workload/report/'
                        ?>"><?php _e('上报工作量'); ?></a>
                    </li>
                    <?php }?>
                </ul>
