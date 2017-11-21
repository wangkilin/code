<?php if ($this->topics_list) { ?>
<?php foreach ($this->topics_list AS $key => $val) { ?>
<div class="icb-item">
    <!-- 话题图片 -->
    <a class="img icb-border-radius-5" href="topic/<?php echo $val['url_token']; ?>" data-id="<?php echo $val['topic_id']; ?>">
        <img src="<?php echo getMudulePicUrlBySize('topic', 'mid', $val['topic_pic']); ?>" alt="" />
    </a>
    <!-- end 话题图片 -->
    <p class="clearfix">
        <!-- 话题内容 -->
        <span class="article-tag">
            <a class="text" href="topic/<?php echo $val['url_token']; ?>" data-id="<?php echo $val['topic_id']; ?>"><?php echo $val['topic_title']; ?></a>
        </span>
        <!-- end 话题内容 -->
    </p>
    <p class="text-color-999">
        <span><?php _e('%s 个讨论', $val['discuss_count']); ?></span>
        <span><?php _e('%s 个关注', $val['focus_count']); ?></span>
    </p>
    <p class="text-color-999">
        <?php _e('7 天新增 %s 个讨论', $val['discuss_count_last_week']); ?>, <?php _e('30 天新增 %s 个讨论', $val['discuss_count_last_month']); ?>
    </p>
</div>
<?php } ?>
<?php } ?>