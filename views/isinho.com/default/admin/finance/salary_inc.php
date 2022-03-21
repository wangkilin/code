
            <h3>
                <ul class="nav nav-tabs">
                    <li<?php echo ACTION=='salary'?' class="active"':''?>>
                    <a href="<?php
                        echo ACTION=='salary'?'#salary" data-toggle="tab':'admin/finance/salary/'
                        ?>"><?php _e('工资表'); ?></a>
                    </li>
                    <li<?php echo ACTION=='salary_statistic'?' class="active"':''?>>
                    <a href="<?php
                        echo ACTION=='salary_statistic'?'#salary_statistic" data-toggle="tab':'admin/finance/salary_statistic/'
                        ?>"><?php _e('统计报表'); ?></a>
                    </li>
                    <li<?php echo ACTION=='salary_import'?' class="active"':''?>>
                    <a href="<?php
                        echo ACTION=='salary_import'?'#salary_import" data-toggle="tab':'admin/finance/salary_import/'
                        ?>"><?php _e('工资导入'); ?></a>
                    </li>
                </ul>
            </h3>
