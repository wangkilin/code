<?php if ($this->posts_list) { ?>
<?php foreach ($this->posts_list AS $key => $val) { ?>
<div class="icb-item clearfix nomargin <?php
if ($val['question_id']) {
     ?><?php
     if ($val['answer_count'] == 0) {
          ?>active<?php
     } ?><?php
} else {
    ?>article<?php
} ?>" data-topic-id="<?php
foreach ($val['topics'] AS $k => $v) {
     ?><?php echo $v['topic_id']; ?>,<?php
} ?>">
    <div class="icb-content col-sm-12">
        <div class="mod-body">
            <div class="icb-article-title-box clearfix">
                <span class="icb-article-title">
				 <h4>
					<?php if ($val['question_id']) { ?>
					<a href="question/<?php echo empty($val['url_token']) ? $val['question_id']: $val['url_token']; ?>" target="blank"><?php echo $val['question_content']; ?></a>
					<?php } else { ?>
					<a href="<?php echo $val['post_type']?>/<?php echo empty($val['url_token']) ? $val['id']: $val['url_token']; ?>" target="blank"><?php echo $val['title']; ?></a>
					<?php } ?>
				</h4>
                </span>
                <?php
                switch ($val['post_type']) {
                    case 'article':
                        $topics = $this->article_topics;
                        break;
                    case 'mannual':
                        $topics = $this->mannual_topics;
                        break;
                    case 'course':
                        $topics = $this->course_topics;
                        break;
                    default:
                        $topics = array();
                        break;
                }
                foreach($topics[$val['id']] as $topic_key => $topic_val) { ?>
                <span class="article-tag">
                    <a href="tag/<?php echo $topic_val['url_token']; ?>" class="text" data-id="<?php echo $topic_val['topic_id']; ?>"><?php echo $topic_val['topic_title']; ?></a>
                </span>
                <?php
                }?>
            </div>

            <div class="content-wrap">
                <div class="content" id="detail_<?php echo $val['id']; ?>">
                <div class="markitup-box"><?php
                if ($this->show_image && isset($this->attach_list[$val['post_type']][$val['id']])) {
                ?><img src="<?php echo $this->attach_list[$val['post_type']][$val['id']]['attachment']; ?>" class="pull-left inline-img"><?php
                }
                ?>
			<div class="img pull-right"></div>
            <?php
            echo nl2br(cjk_substr(trim(strip_tags(FORMAT::parse_attachs(FORMAT::parse_bbcode($val['message'])))), 0, 200) ); ?> <?php
            if (cjk_strlen($val['message']) > 130) {
                ?> ...  <a class="more" href="article/<?php echo empty($val['url_token']) ? $val['id']:$val['url_token']; ?>" target="blank">查看全部</a>
            <?php
            } ?>
		</div>
                    <div class="collapse article-brief all-content">
                        <?php //echo FORMAT::parse_attachs(nl2br(FORMAT::parse_bbcode($val['message']))); ?>

                    </div>
                </div>
            </div>
        </div>
        <div class="mod-footer clearfix">

            <?php
            if (true OR $_GET['category'] != $val['category_id'] AND $val['category_info']['title']) { ?>
               <a class="icb-question-tags text-left" href="index/category-<?php
               echo $val['category_info']['url_token']; ?>"><?php
               echo $val['category_info']['title']; ?></a>
            <?php
            }
            foreach ($val['category_list'] as $_categoryInfo) { ?>
                <a class="icb-question-tags text-left" href="index/category-<?php
                echo $_categoryInfo['url_token']; ?>"><?php
                echo $_categoryInfo['title']; ?></a>
             <?php

            }
            ?>
            <span class="pull-right more-operate text-color-999">
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
                <?php
                    if ($val['anonymous'] == 0) { ?>
                        <a href="user/<?php
                        echo $val['user_info']['url_token']; ?>" class=""><?php
                        echo $val['user_info']['user_name']; ?></a><?php
                    } else {
                        ?><a href="javascript:;" class="" data-id="<?php
                        echo $val['uid']; ?>"><?php _e('匿名用户'); ?></a><?php
                    } ?>
                    <span class="text-color-999"> • <?php
                    echo date_friendly($val['add_time'], null, 'Y-m-d'); ?>
                    </span>
                    <?php
                } ?>
            <?php
            } ?>

            </span>
        </div>
    </div>
</div>
<?php } ?>

<?php echo $this->pagination; ?>

<?php } ?>
