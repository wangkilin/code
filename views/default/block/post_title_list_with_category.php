<?php
if ($this->posts_list) {
?><ul class="clearfix"><?php
    $_titleColSpan = 12;
    if ($this->showArticleCategory) $_titleColSpan -= 2;
    if ($this->showArticleTime) $_titleColSpan -= 2;
    foreach ($this->posts_list AS $key => $val) { ?>
        <li class="clearfix <?php echo $this->listColClass;
        if ($val['question_id']) {
            if ($val['answer_count'] == 0) {
                ?> active<?php
            } ?><?php
        } else {
            ?> article<?php
        } ?>">


                        <?php
                        if ($_GET['category'] != $val['category_id'] AND $_GET['category'] != $val['category_info']['title']) { ?>
                        <a class="col-sm-2 col-xs-2 <?php echo $this->showArticleCategory==true ? '' : 'hidden';?> text-color-999 nooverflow nobg nopadding text-center <?php echo $this->categoryCssClass;?>" href="<?php echo MODULE; ?>/category-<?php
                        echo $val['category_info']['url_token']; ?>"><?php
                        echo $val['category_info']['title']; ?></a>
                        <?php
                        }
                        foreach ($val['category_list'] as $_categoryInfo) { break; ?>
                            <a class="icb-question-tags text-left" href="<?php echo MODULE; ?>/category-<?php
                            echo $_categoryInfo['url_token']; ?>"><?php
                            echo $_categoryInfo['title']; ?></a>
                        <?php

                        }
                        ?>
                            <h4 class="col-sm-<?php echo $_titleColSpan;?> col-xs-<?php echo $_titleColSpan;?> nooverflow title">
                                <?php if ($val['question_id']) { ?>
                                <a href="question/<?php echo empty($val['url_token']) ? $val['question_id']: $val['url_token']; ?>" target="blank"><?php echo $val['question_content']; ?></a>
                                <?php } else { ?>
                                <a href="<?php echo $val['post_type']?>/<?php echo empty($val['url_token']) ? $val['id']: $val['url_token']; ?>" target="blank"><?php echo $val['title']; ?></a>
                                <?php } ?>
                            </h4>

                    <span class="pull-right <?php echo $this->showArticleTime==true ? '' : 'hidden';?> col-sm-2 text-color-999 text-right <?php echo $this->timeCssClass;?>">
                    <?php
                    if ($val['question_id']) { ?>
                        <?php
                        if ($val['answer_count'] > 0) { ?>
                            <?php
                            if ($val['answer_info']['anonymous']) {
                                ?> <a href="javascript:;" class=""><?php _e('匿名用户'); ?></a><?php
                            } else {
                                ?><a href="user/<?php
                                echo $val['answer_info']['user_info']['url_token']; ?>" class="" data-id="<?php
                                echo $val['answer_info']['user_info']['uid']; ?>"><?php
                                echo $val['answer_info']['user_info']['user_name']; ?></a><?php
                            } ?>
                            <span class="text-color-999"><?php
                            _e('回复'); ?> • <?php
                            echo date_friendly($val['update_time'], null, 'Y-m-d'); ?>
                                </span>
                            <?php
                        } else { ?>
                            <span class="text-color-999"> • <?php
                            echo date_friendly($val['add_time'], null, 'Y-m-d'); ?>
                            </span>
                            <?php
                        } ?>
                    <?php
                    } else { ?> • <?php
                        echo date_friendly($val['add_time'], 1, 'm-d'); ?>
                    <?php
                    } ?>
                    </span>
        </li>
<?php } ?>
</ul>
<?php echo $this->pagination; ?>

<?php } ?>
