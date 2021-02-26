<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
            <h3>
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#index"><?php _e('假期设置'); ?></a></li>
                </ul>
            </h3>
        </div>


        <div class="mod-body tab-content padding5px">
            <div class="tab-pane active" id="index">

                <table  class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th><span class="col-sm-12 no-padding text-right">&nbsp; 日期</span><span class="col-sm-12 no-padding cell-rotate-separator"></span><span class="col-sm-12 no-padding">月份 &nbsp;</span></th>
                            <?php $totalDaysInMonth = date('t');
                            for($i=0; $i<$totalDaysInMonth; $i++) {?>
                            <th style="width:<?php echo 1/32*100;?>%"><?php echo $i+1; ?></th>
                            <?php }
                            for($i=$totalDaysInMonth; $i<31; $i++) {?>
                                <th style="width:<?php echo 1/32*100;?>%"><?php echo $i+1; ?></th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($i=1; $i<=12; $i++) { ?>
                        <tr>
                            <td><?php echo $i, '月';?></td>
                            <?php
                            for($j=0; $j<31; $j++) {?>
                            <td style="width:<?php echo 1/32*100;?>%">&nbsp;</td>
                            <?php }?>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(function () {

});

</script>

<?php View::output('admin/global/footer.php'); ?>
