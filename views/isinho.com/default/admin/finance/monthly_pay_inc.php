
            <h3>
                <ul class="nav nav-tabs">
                    <li<?php echo ACTION=='monthly_pay'?' class="active"':''?>>
                    <a href="<?php
                        echo ACTION=='monthly_pay'?'#monthly_pay" data-toggle="tab':'admin/finance/monthly_pay/'
                        ?>"><?php _e('月度收支'); ?></a>
                    </li>
                    <li<?php echo ACTION==''?' class="active"':''?>>
                    <a href="<?php
                        echo ACTION=='pay'?'#pay" data-toggle="tab':'admin/finance/pay_statistic/'
                        ?>"><?php _e('统计报表'); ?></a>
                    </li>
                    <li<?php echo ACTION=='monthly_pay_import'?' class="active"':''?>>
                    <a href="<?php
                        echo ACTION=='monthly_pay_import'?'#monthly_pay_import" data-toggle="tab':'admin/finance/monthly_pay_import/'
                        ?>" ><?php _e('数据导入'); ?></a>
                    </li>
                </ul>
            </h3>
