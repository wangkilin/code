<?php if ($this->posts_list) { ?>
<?php
foreach($this->posts_list as $key => $val) { ?>
<div class="icb-item <?php
    if ($val['question_id']) {
         if ($val['answer_count'] == 0) {
    ?>active<?php
         }
    } else {
        echo $val['post_type']; // 条目类型
    } ?>" data-topic-id="<?php
    foreach ($val['topics'] AS $k => $v) {
        echo $v['topic_id']; ?>,<?php
    } ?>">
    <!--
    <?php

    if ($val['anonymous'] == 0) {
    ?><a class="icb-user-name hidden-xs" data-id="<?php
      echo $val['user_info']['uid']; ?>" href="user/<?php
      echo $val['user_info']['url_token']; ?>" rel="nofollow"><img src="<?php echo
      get_avatar_url($val['user_info']['uid'], 'max'); ?>" alt="" /><?php
      if ($val['user_info']['verified']) {
          if ($val['user_info']['verified'] == 'personal') {
           ?><i class="icon icon-v"></i><?php
          } else {
           ?><i class="icon icon-v i-ve"></i><?php
          } ?><?php
      } ?></a><?php
    } else {
    ?><a class="icb-user-name hidden-xs" href="javascript:;"><img src="<?php
      echo G_STATIC_URL; ?>/common/avatar-max-img.png" alt="<?php
      _e('匿名用户'); ?>" title="<?php
       _e('匿名用户'); ?>" /></a><?php
    } ?>
    -->
	<div class="icb-question-content">
		<h4>
        <?php
        if ($val['question_id']) { ?>
            <a href="question/<?php
            echo $val['question_id']; ?>"><?php
            echo $val['question_content']; ?></a>
        <?php
        } else { ?>
            <a href="<?php echo $val['post_type']; ?>/<?php
            echo $val['id']; ?>"><?php
            echo $val['title']; ?></a>
        <?php
        } ?>
		</h4>
        <?php
        if (is_array($val['answer_users'])) { ?>
		<div class="pull-right hidden-xs contribute">
			<span class="pull-right text-color-999"><?php _e('贡献'); ?></span>
		<?php foreach ($val['answer_users'] AS $answer_user) { ?>
		    <a class="icb-user-name" data-id="<?php echo $answer_user['uid']; ?>" href="user/<?php echo $answer_user['url_token']; ?>" rel="nofollow"><img src="<?php echo get_avatar_url($answer_user['uid'], 'mid'); ?>" alt="" /></a>
		<?php } ?>
		</div>
        <?php
        } else if ($val['question_id']) { ?>
		<a href="question/<?php echo $val['question_id']; ?>#!answer_form" class="pull-right text-color-999"><?php _e('回复'); ?></a>
        <?php
        } ?>

		<p>
        <?php if ($_GET['category'] != $val['category_id'] AND $val['category_info']['title']) { ?>
            <a class="icb-question-tags" href="<?php
                echo $val['post_type'];?>/category-<?php
                echo $val['category_info']['url_token']; ?>"><?php
                echo $val['category_info']['title']; ?></a>
            <?php } ?>
        <?php
        if ($val['question_id']) { ?>
          <?php
          if ($val['answer_count'] > 0) { ?>
            <?php
            if ($val['answer_info']['anonymous']) {
            ?><a href="javascript:;" class="icb-user-name"><?php _e('匿名用户'); ?></a><?php
            } else {
                ?><a href="user/<?php echo $val['answer_info']['user_info']['url_token']; ?>" class="icb-user-name" data-id="<?php echo $val['answer_info']['user_info']['uid']; ?>"><?php echo $val['answer_info']['user_info']['user_name']; ?></a><?php
            } ?>
            <span class="text-color-999"><?php
                _e('回复了问题',null, array('tag'=>'i','class'=>'answered-item') );
            _e('%s 人关注', $val['focus_count'], array('tag'=>'i','class'=>'focused-item') );
            _e('%s 个回复', $val['answer_count'], array('tag'=>'i','class'=>'replied-item') );
            _e('%s 次浏览', $val['view_count'], array('tag'=>'i','class'=>'viewed-item') );
            echo '<i class="add-item-time">' . date_friendly($val['update_time']) . '</i>';
            ?></span>
          <?php
          } else {
            if ($val['anonymous'] == 0) { ?>
              <a href="user/<?php
              echo $val['user_info']['url_token']; ?>" class="icb-user-name"><?php
              echo $val['user_info']['user_name']; ?></a><?php
            } else {
            ?><a href="javascript:;" class="icb-user-name" data-id="<?php
              echo $val['uid']; ?>"><?php
              _e('匿名用户'); ?></a><?php
            } ?>
            <span class="text-color-999"><?php
            _e('发起了问题',null, array('tag'=>'i','class'=>'answered-item') );
            _e('%s 人关注', $val['focus_count'], array('tag'=>'i','class'=>'focused-item') );
            _e('%s 个回复', $val['answer_count'], array('tag'=>'i','class'=>'replied-item') );
            _e('%s 次浏览', $val['view_count'], array('tag'=>'i','class'=>'viewed-item') );
            echo '<i class="add-item-time">' . date_friendly($val['add_time']) . '</i>';
            ?>
			</span>
            <?php
          } ?>
        <?php
        } else {
        ?><a href="user/<?php
            echo $val['user_info']['url_token']; ?>" class="icb-user-name"><?php
            echo $val['user_info']['user_name']; ?></a> <span class="text-color-999"><?php
            _e('发表了文章',null, array('tag'=>'i','class'=>'answered-item') );
            _e('%s 个评论', $val['comments'], array('tag'=>'i','class'=>'comments-item') );
            _e('%s 次浏览', $val['views'], array('tag'=>'i','class'=>'viewed-item') );
            echo '<i class="add-item-time">' . date_friendly($val['add_time']) . '</i>';
        } ?><span class="text-color-999 related-topic collapse"> • 来自相关话题</span>
		</p>

		<?php if (!$val['question_id']) { ?>
		<!-- 文章内容调用 -->
		<div class="markitup-box">
			<div class="img pull-right"></div>
			<?php echo nl2br(trim(strip_tags(FORMAT::parse_attachs(FORMAT::parse_bbcode($val['message']))))); ?> <?php if (cjk_strlen($val['message']) > 130) { ?>
			<a class="more" href="article/<?php echo $val['id']; ?>">查看全部</a>
			<?php } ?>
		</div>

		<div class="collapse all-content">
			<?php echo FORMAT::parse_attachs(nl2br(FORMAT::parse_bbcode($val['message']))); ?>
		</div>
		<!-- end 文章内容调用 -->
		<?php } ?>
	</div>
</div>
<?php
    } // end foreach

}
 ?>
