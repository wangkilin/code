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
        ?><span class="views hidden-xs viewsword100to999 stat-info icon-preview">
            <?php echo $val['view_count']; ?> <em class=""><?php _e('浏览'); ?></em>
        </span>
        <span class="votes hidden-xs stat-info icon-favor">
        <?php echo $val['focus_count']; ?><em><?php _e('关注'); ?></em>
        </span>
        <span class="answer hidden-xs stat-info icon-topic">
        <?php echo $val['answer_count']; ?><em><?php _e('回复'); ?></em>
        </span>
    <?php
    } else { ?> <span class="views hidden-xs stat-info icon-preview">
            <?php echo $val['views']; ?> <em><?php _e('浏览'); ?></em>
        </span>
        <span class="comments hidden-xs stat-info icon-comment">
            <?php echo $val['comments']; ?> <em><?php _e('评论'); ?></em>
        </span>
        <span class="votes hidden-xs stat-info  icon-agree">
            <?php echo $val['votes']; ?> <em><?php _e('投票'); ?></em>
        </span><?php
    }?>

    </div>

    <div class="icb-content col-sm-11">
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
                //var_dump($val['post_type'],$topics);
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
                //var_dump($val['id'],$this->show_image,$this->attach_list[$val['post_type']][$val['id']]);
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
            if ($_GET['category'] != $val['category_id'] AND $val['category_info']['title']) { ?>
               <a class="icb-question-tags text-left" href="index/category-<?php
               echo $val['category_info']['url_token']; ?>"><?php
               echo $val['category_info']['title']; ?></a>
            <?php
            } ?>
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
            } else { ?>
                <a href="user/<?php
                echo $val['user_info']['url_token']; ?>" class=""><?php
                echo $val['user_info']['user_name']; ?></a> <span class="text-color-999"> • <?php
                echo date_friendly($val['add_time'], null, 'Y-m-d'); ?></span>
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
                <div class="bdsharebuttonbox">
                    <a href="#" class="bds_more" data-cmd="more">分享</a>
                    <a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博">新浪微博</a>
                    <a href="#" class="bds_weixin" data-cmd="weixin" title="分享到微信">微信</a>
                    <a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间">QQ空间</a>
                    <a href="#" class="bds_sqq" data-cmd="sqq" title="分享到QQ好友">QQ好友</a>
                    <a href="#" class="bds_tqq" data-cmd="tqq" title="分享到腾讯微博">腾讯微博</a>
                    <a href="#" class="bds_renren" data-cmd="renren" title="分享到人人网">人人网</a>
                </div>
                <script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"分享文字内容","bdMini":"2","bdMiniList":false,"bdPic":"http://www.icodebang.cn/static/css/default/img/blue-logo.jpg","bdStyle":"0","bdSize":"16"},"share":{"bdSize":16},"image":{"viewList":["qzone","tsina","weixin","sqq","tqq","renren"],"viewText":"分享","viewSize":"16"},"selectShare":{"bdContainerClass":null,"bdSelectMiniList":["qzone","tsina","weixin","sqq","tqq","renren"]}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];</script>
            </span>
        </div>
    </div>
</div>
<?php } ?>

<?php echo $this->pagination; ?>

<?php } ?>
