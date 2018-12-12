<?php if ($this->posts_list) { ?>
<?php foreach($this->posts_list as $key => $val) { ?>
<div class="icb-item <?php
    if ($val['question_id']) {
    ?><?php
        if ($val['answer_count'] == 0) {
        ?>active<?php
        } ?><?php
    } else {
        ?>article<?php
    } ?>" data-topic-id="<?php
    foreach ($val['topics'] AS $k => $v) {
        echo $v['topic_id']; ?>,<?php
    } ?>">
	<?php if ($val['anonymous'] == 0) {
    // 注册用户头像
    ?><a class="icb-user-name hidden-xs" data-id="<?php
        echo $val['user_info']['uid']; ?>" href="user/<?php
        echo $val['user_info']['url_token']; ?>" rel="nofollow"><img src="<?php
        echo get_avatar_url($val['user_info']['uid'], 'max'); ?>" alt="" /><?php
        if ($val['user_info']['verified']) {
         ?><?php
           if ($val['user_info']['verified'] == 'personal') {
        ?><i class="icon icon-v"></i><?php
           } else {
            ?><i class="icon icon-v i-ve"></i><?php
           } ?><?php
        } ?></a><?php
    } else {
    // 匿名用户头像
    ?><a class="icb-user-name hidden-xs" href="javascript:;"><img src="<?php
        echo G_STATIC_URL; ?>/common/avatar-max-img.png" alt="<?php
        _e('匿名用户'); ?>" title="<?php _e('匿名用户'); ?>" /></a><?php
    } ?>
	<div class="icb-question-content">
		<h4>
            <?php
            if ($val['question_id']) {
             ?><a href="question/<?php
                echo $val['question_id']; ?>"><?php echo $val['question_content']; ?></a>
            <?php
            } else { ?>
			<a href="article/<?php echo $val['id']; ?>"><?php echo $val['title']; ?></a>
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
        <?php
        if ($_GET['category'] != $val['category_id'] AND $val['category_info']['title']) { ?>
				<a class="icb-question-tags" href="index/category-<?php echo $val['category_info']['url_token']; ?>"><?php echo $val['category_info']['title']; ?></a>
                • <?php
        } ?>
        <?php
        if ($val['question_id']) { ?>
            <?php
            if ($val['answer_count'] > 0) { ?>
               <?php
               if ($val['answer_info']['anonymous']) {
                   ?> <a href="javascript:;" class="icb-user-name"><?php _e('匿名用户'); ?></a><?php
               } else {
                   ?><a href="user/<?php
                   echo $val['answer_info']['user_info']['url_token']; ?>" class="icb-user-name" data-id="<?php
                   echo $val['answer_info']['user_info']['uid']; ?>"><?php
                   echo $val['answer_info']['user_info']['user_name']; ?></a><?php
                } ?>
				<span class="text-color-999"><?php _e('回复了问题'); ?> • <?php _e('%s 人关注', $val['focus_count']); ?> • <?php _e('%s 个回复', $val['answer_count']); ?> • <?php _e('%s 次浏览', $val['view_count']); ?> • <?php echo date_friendly($val['update_time']); ?>
				</span>
            <?php
            } else { ?>
              <?php
              if ($val['anonymous'] == 0) { ?>
                <a href="user/<?php echo $val['user_info']['url_token']; ?>" class="icb-user-name"><?php echo $val['user_info']['user_name']; ?></a><?php
              } else {
                  ?><a href="javascript:;" class="icb-user-name" data-id="<?php
                  echo $val['uid']; ?>"><?php _e('匿名用户'); ?></a><?php
              } ?>
				<span class="text-color-999"><?php _e('发起了问题'); ?> • <?php _e('%s 人关注', $val['focus_count']); ?> • <?php _e('%s 个回复', $val['answer_count']); ?> • <?php _e('%s 次浏览', $val['view_count']); ?> • <?php echo date_friendly($val['add_time']); ?>
				</span>
            <?php
            } ?>
        <?php
        } else { ?>
            <a href="user/<?php
            echo $val['user_info']['url_token']; ?>" class="icb-user-name"><?php
            echo $val['user_info']['user_name']; ?></a> <span class="text-color-999"><?php
            _e('发表了文章'); ?> • <?php
            _e('%s 个评论', $val['comments']); ?> • <?php
            _e('%s 次浏览', $val['views']); ?> • <?php
            echo date_friendly($val['add_time']); ?></span>
        <?php
        } ?>
			<span class="text-color-999 related-topic collapse"> • 来自相关话题</span>
		</p>

        <?php
        if (! $val['question_id']) { ?>
		<!-- 文章内容调用 -->
		<div class="markitup-box">
			<div class="img pull-right"></div>
            <?php
            echo nl2br(trim(strip_tags(FORMAT::parse_attachs(FORMAT::parse_bbcode($val['message']))))); ?> <?php
            if (cjk_strlen($val['message']) > 130) {
                ?><a class="more" href="article/<?php echo $val['id']; ?>">查看全部</a>
            <?php
            } ?>
		</div>

		<div class="collapse all-content">
			<?php echo FORMAT::parse_attachs(nl2br(FORMAT::parse_bbcode($val['message']))); ?>
		</div>
		<!-- end 文章内容调用 -->
        <?php
        } ?>
	</div>
</div>
<?php } ?>
<?php } ?>


<?php if ($this->posts_list) { ?>
<?php foreach ($this->posts_list AS $key => $val) { ?>
<div class="icb-item clearfix <?php
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
    <div class="icb-rank col-sm-1">
    <?php
    if ($val['question_id']) {
        ?><span class="views hidden-xs viewsword100to999">
            <?php echo $val['view_count']; ?> <em><?php _e('浏览'); ?></em>
        </span>
        <span class="votes hidden-xs">
        <?php echo $val['focus_count']; ?><em><?php _e('关注'); ?></em>
        </span>
        <span class="answer hidden-xs">
        <?php echo $val['answer_count']; ?><em><?php _e('回复'); ?></em>
        </span>
    <?php
    } else { ?> <span class="votes hidden-xs">
            <?php echo $val['views']; ?> <em><?php _e('浏览'); ?></em>
        </span>
        <span class="votes hidden-xs">
            0 <em>得票</em>
            <?php echo $val['comments']; ?> <em><?php _e('评论'); ?></em>
        </span><?php
    }?>

    </div>

    <div class="icb-content col-sm-11">
        <div class="mod-body">
            <div class="icb-article-title-box clearfix">
                <span class="icb-article-title">
				 <a href="course/<?php echo $val['id']; ?>"><?php echo $val['title']; ?></a>
				 <h4>
					<?php if ($val['question_id']) { ?>
					<a href="question/<?php echo $val['question_id']; ?>"><?php echo $val['question_content']; ?></a>
					<?php } else { ?>
					<a href="<?php echo $val['post_type']?>/<?php echo $val['id']; ?>"><?php echo $val['title']; ?></a>
					<?php } ?>
				</h4>
				</span>
				<?php foreach($this->article_topics[$val['id']] as $topic_key => $topic_val) { ?>
				<span class="article-tag">
					<a href="tag/<?php echo $topic_val['url_token']; ?>" class="text" data-id="<?php echo $topic_val['topic_id']; ?>"><?php echo $topic_val['topic_title']; ?></a>
				</span>
				<?php } ?>
            </div>

            <div class="content-wrap">
                <div class="content" id="detail_<?php echo $val['id']; ?>">
                <div class="markitup-box">
			<div class="img pull-right"></div>
            <?php
            echo nl2br(trim(strip_tags(FORMAT::parse_attachs(FORMAT::parse_bbcode($val['message']))))); ?> <?php
            if (cjk_strlen($val['message']) > 130) {
                ?><a class="more" href="article/<?php echo $val['id']; ?>">查看全部</a>
            <?php
            } ?>
		</div>
                    <div class="article-brief">
                        <?php echo FORMAT::parse_attachs(nl2br(FORMAT::parse_bbcode($val['message']))); ?>

                    </div>
                </div>
            </div>
        </div>
        <div class="mod-footer clearfix">
            <span class="pull-right more-operate text-color-999">

            <?php
            if ($val['question_id']) { ?>
                <?php
                if ($val['answer_count'] > 0) { ?>
                    <?php
                    if ($val['answer_info']['anonymous']) {
                        ?> <a href="javascript:;" class="icb-user-name"><?php _e('匿名用户'); ?></a><?php
                    } else {
                        ?><a href="user/<?php
                        echo $val['answer_info']['user_info']['url_token']; ?>" class="icb-user-name" data-id="<?php
                        echo $val['answer_info']['user_info']['uid']; ?>"><?php
                        echo $val['answer_info']['user_info']['user_name']; ?></a><?php
                    } ?>
                     <span class="text-color-999"><?php
                    _e('回复了问题'); ?><?php
                     echo date_friendly($val['update_time']); ?>
                        </span>
                    <?php
                } else { ?>
                <?php
                    if ($val['anonymous'] == 0) { ?>
                        <a href="user/<?php
                        echo $val['user_info']['url_token']; ?>" class="icb-user-name"><?php
                        echo $val['user_info']['user_name']; ?></a><?php
                    } else {
                        ?><a href="javascript:;" class="icb-user-name" data-id="<?php
                        echo $val['uid']; ?>"><?php _e('匿名用户'); ?></a><?php
                    } ?>
                    <span class="text-color-999"><?php
                    _e('发起了问题'); ?><?php
                    echo date_friendly($val['add_time']); ?>
                    </span>
                    <?php
                } ?>
            <?php
            } else { ?>
                <a href="user/<?php
                echo $val['user_info']['url_token']; ?>" class="icb-user-name"><?php
                echo $val['user_info']['user_name']; ?></a> <span class="text-color-999"><?php
                _e('发表了文章'); ?><?php
                echo date_friendly($val['add_time']); ?></span>
            <?php
            } ?>

                <a class="text-color-999" href="article/<?php echo $val['id']; ?>"><i class="icon icon-comment"></i> <?php _e('评论'); ?> (<?php echo $val['comments']; ?>)</a>
                <a class="text-color-999 dropdown-toggle" data-toggle="dropdown">
                    <i class="icon icon-share"></i> <?php _e('分享'); ?>
                </a>
                <div aria-labelledby="dropdownMenu" role="menu" class="icb-dropdown shareout pull-right">
                    <ul class="icb-dropdown-list">
                        <li><a onclick="AWS.User.share_out({webid: 'tsina', content: $(this).parents('.icb-item').find('.markitup-box')});"><i class="icon icon-weibo"></i> <?php _e('微博'); ?></a></li>
                    <li><a onclick="AWS.User.share_out({webid: 'qzone', content: $(this).parents('.icb-item')});"><i class="icon icon-qzone"></i> <?php _e('QZONE'); ?></a></li>
                    <li><a onclick="AWS.User.share_out({webid: 'weixin', content: $(this).parents('.icb-item')});"><i class="icon icon-wechat"></i> <?php _e('微信'); ?></a></li>
                    </ul>
                </div>
            </span>
        </div>
    </div>
</div>
<?php } ?>

<?php echo $this->pagination; ?>

<?php } ?>
