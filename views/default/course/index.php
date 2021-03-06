<?php View::output('global/header.php'); ?>

<div class="icb-container">
    <div class="container">
        <div class="row">
            <div class="icb-content-wrap clearfix">
                <!-- 左侧边栏 -->
                <div class="col-sm-12 col-md-2 icb-side-bar icb-left-side-bar course hidden-sm hidden-xs">
                    <div class="icb-mod">
                        <?php foreach ($this->tableList as $_tableInfo) {?>
                        <div class="mod-head">
                            <h3 class="course-title"><i class="icon-reader"> </i><?php echo $_tableInfo['title']; ?></h3>
                        </div>
                        <div class="mod-body">
                            <ul class="course-content-table" id="course-content-table"><?php
                            foreach ($this->itemList[$_tableInfo['id']] as $_item) {
                                switch ($_item['from_type']) {
                                    case 'course':
                            ?><li class="course<?php
                                echo $_GET['id']==$this->courseList[$_item['article_id']]['id'] || $_GET['url_token']==$this->courseList[$_item['url_token']]['id'] ? ' on':'';
                                echo $_item['parent_id'] > 0 ? ' hidden' : '';
                            ?>" data-parent-id="<?php echo $_item['parent_id'];?>" data-id="<?php echo $_item['id'];?>"><i class="icon-log<?php
                                 echo $_item['parent_id'] > 0 ? ' sub-level' : ' icon-add-to-list';
                                 ?>"></i><a href="./id-<?php echo empty($this->courseList[$_item['article_id']]['url_token']) ? $_item['article_id']:$this->courseList[$_item['article_id']]['url_token'];?>__table_id-<?php echo $_item['table_id'];?>.html"><?php echo $_item['title'];?></a></li>
                            <?php
                                        break;
                                    case 'link':
                            ?><li class="link"><i class="icon-log<?php
                                    echo $_item['parent_id'] > 0 ? ' sub-level' : '';
                            ?>" data-parent-id="<?php echo $_item['parent_id'];?>" data-id="<?php echo $_item['id'];?>"></i><a target="_blank" href="<?php echo $_item['link'];?>"><?php echo $_item['title'];?></a></li>
                            <?php
                                        break;
                                    case 'chapter':
                            ?><li class="chapter" data-parent-id="<?php echo $_item['parent_id'];?>" data-id="<?php echo $_item['id'];?>"><i class="icon-file"></i> <?php echo $_item['title'];?></li>
                            <?php
                                        break;
                                    default:
                                        break;
                                }
                            }?></ul>
                        </div>
                        <?php } ?>
                    </div>

                </div>
                <!-- end 左侧边栏 -->
                <div class="col-sm-12 col-md-8 icb-main-content icb-middle-main-content icb-article-content ">
                    <div class="icb-mod icb-article-title-box" id="question_topic_editor" data-type="article" data-id="<?php echo $this->itemInfo['id']; ?>">
                        <div class="col-sm-12 clearfix nav-previous-next">
                            <div class="nav-previous-link col-sm-5"><?php if ($this->prevItem) {
                                ?><a href="<?php echo $this->prevItem['link'];?>"><i class="icon-left"></i><?php echo $this->prevItem['title']; ?></a><?php
                             }?> </div>
                            <div class="nav-next-link col-sm-5"><?php if ($this->nextItem) {
                                ?><a href="<?php echo $this->nextItem['link'];?>"><?php echo $this->nextItem['title']; ?><i class="icon-right"></i></a><?php
                            }?> </div>
                        </div>
                        <div class="tag-queue-box clearfix">
                            <?php if ($this->article_topics) { ?>
                            <?php foreach($this->article_topics as $key => $val) { ?>
                            <span class="article-tag" data-id="<?php echo $val['topic_id']; ?>">
                                <a class="text" href="topic/<?php echo $val['url_token']; ?>"><?php echo $val['topic_title']; ?></a>
                            </span>
                            <?php } ?>
                            <?php } ?>

                            <?php if ($this->user_info['permission']['is_administortar'] OR $this->user_info['permission']['is_moderator']) { ?><span class="icon-inverse icb-edit-topic"><i class="icon icon-edit"></i> <?php if (sizeof($this->article_topics) == 0) { ?><?php _e('添加话题')?><?php } ?></span><?php } ?>
                        </div>
                    </div>
                    <div class="icb-mod icb-question-detail">
                        <div class="mod-head">
                            <h1><?php echo $this->itemInfo['title']; ?></h1>
                            <?php if ($this->user_info['permission']['is_administortar']
                                 OR $this->user_info['permission']['is_moderator']) { ?>
                            <div class="operate clearfix">
                                <!-- 下拉菜单 -->
                                <div class="btn-group pull-left">
                                    <a class="btn btn-gray dropdown-toggle" data-toggle="dropdown" href="javascript:;">...</a>
                                    <div class="dropdown-menu icb-dropdown pull-right" role="menu" aria-labelledby="dropdownMenu">
                                        <ul class="icb-dropdown-list">
                                            <li>
                                                <a href="/admin/course/course/id-<?php echo $this->itemInfo['id']; ?>__url-<?php echo base64_current_path();?>.html"><i class="icon icon-edit"></i> <?php _e('编辑'); ?></a>
                                            </li>
                                            <li>
                                                <a href="javascript:;" onclick="ICB.modal.confirm('<?php _e('确认删除?'); ?>', function(){ICB.ajax.requestJson(G_BASE_URL + '/admin/ajax/course_remove/', 'ids[]=<?php echo $this->itemInfo['id']; ?>&backUrl=<?php echo base64_encode('/course/') ;?>.html');});"><?php _e('删除文章'); ?></a>
                                            </li>
                                            <li>
                                                <a href="javascript:;" onclick="AWS.ajax_request(G_BASE_URL + '/course/ajax/set_recommend/', 'action=<?php if ($this->itemInfo['is_recommend']) { ?>un<?php } ?>set&article_id=<?php echo $this->itemInfo['id']; ?>');"><?php if ($this->itemInfo['is_recommend']) { ?><?php _e('取消推荐'); ?><?php } else { ?><?php _e('推荐文章'); ?><?php } ?></a>
                                            </li>
                                            <li>
                                                <a href="javascript:;" onclick="AWS.dialog('recommend', {'type': 'course', 'item_id': <?php echo $this->itemInfo['id']; ?>, 'focus_id': <?php if ($this->itemInfo['chapter_id']) { echo $this->itemInfo['chapter_id']; } else {  } ?>});"><?php _e('添加到帮助中心'); ?></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- end 下拉菜单 -->
                            </div>
                            <?php } ?>
                        </div>
                        <div class="mod-body">
                            <div class="content course-content markitup-box">
                                <?php echo $this->itemInfo['content']; ?>

                                <?php if ($this->itemInfo['attachs']) {  ?>
                                <div class="icb-upload-img-list">
                                <?php foreach ($this->itemInfo['attachs'] AS $attach) {?>
                                <?php if ($attach['is_image']
                                          AND (
                                               ! $this->itemInfo['attachs_ids']
                                               OR !in_array($attach['id'], $this->itemInfo['attachs_ids'])
                                           )
                                          AND strpos($this->itemInfo['content'],$attach['file_location'])===false
                                ) { ?>
                                    <a href="<?php echo $attach['attachment']; ?>" target="_blank" data-fancybox-group="thumb" rel="lightbox"><img src="<?php echo $attach['attachment']; ?>" class="img-polaroid" alt="<?php echo $attach['file_name']; ?>" /></a>
                                <?php } ?>
                                <?php } ?>
                                </div>
                                <?php } ?>

                                <?php if ($this->itemInfo['attachs']) {  ?>
                                <ul class="icb-upload-file-list">
                                    <?php foreach ($this->itemInfo['attachs'] AS $attach) { ?>
                                    <?php if (!$attach['is_image'] AND (!$this->itemInfo['attachs_ids'] OR !in_array($attach['id'], $this->itemInfo['attachs_ids']))) { ?>
                                        <li><a href="<?php echo download_url($attach['file_name'], $attach['attachment']); ?>"><i class="icon icon-attach"></i> <?php echo $attach['file_name']; ?></a></li>
                                    <?php } ?>
                                    <?php } ?>
                                </ul>
                                <?php } ?>
                            </div>
                            <div class="meta clearfix">
                                <div class="icb-article-vote pull-left<?php if (!$this->user_id OR $this->user_id == $this->itemInfo['uid']) { ?> disabled<?php } ?>">
                                    <a href="javascript:;" class="agree<?php if ($this->itemInfo['vote_info']['rating'] == 1) { ?> active<?php } ?>" onclick="AWS.User.article_vote($(this), <?php echo $this->itemInfo['id']; ?>, 1);"><i class="icon icon-agree"></i> <b><?php echo $this->itemInfo['votes']; ?></b></a>
                                    <?php if ($this->user_id AND $this->user_id != $this->itemInfo['uid']) { ?>
                                    <a href="javascript:;" class="disagree<?php if ($this->itemInfo['vote_info']['rating'] == -1) { ?> active<?php } ?>" onclick="AWS.User.article_vote($(this), <?php echo $this->itemInfo['id']; ?>, -1);"><i class="icon icon-disagree"></i></a>
                                    <?php } ?>
                                </div>

                                <span class="pull-right  more-operate">
                                    <?php if ((!$this->itemInfo['lock'] AND ($this->itemInfo['uid'] == $this->user_id OR $this->user_info['permission']['edit_article'])) OR $this->user_info['permission']['is_administortar'] OR $this->user_info['permission']['is_moderator']) { ?>
                                    <a class="text-color-999" href="/admin/course/course/id-<?php echo $this->itemInfo['id']; ?>__url-<?php echo base64_current_path();?>.html"><i class="icon icon-edit"></i> <?php _e('编辑'); ?></a>
                                    <?php } ?>

                                    <?php if ($this->user_id) { ?><a href="javascript:;" onclick="AWS.dialog('favorite', {item_id:<?php echo $this->itemInfo['id']; ?>, item_type:'article'});" class="text-color-999"><i class="icon icon-favor"></i> <?php _e('收藏'); ?></a><?php } ?>

                                    <a class="text-color-999 dropdown-toggle" data-toggle="dropdown">
                                        <i class="icon icon-share"></i> <?php _e('分享'); ?>
                                    </a>
                                    <div aria-labelledby="dropdownMenu" role="menu" class="icb-dropdown shareout pull-right">
                                        <ul class="icb-dropdown-list">
                                            <li><a onclick="AWS.User.share_out({webid: 'tsina', content: $(this).parents('.icb-question-detail').find('.markitup-box')});"><i class="icon icon-weibo"></i> <?php _e('微博'); ?></a></li>
											<li><a onclick="AWS.User.share_out({webid: 'qzone', content: $(this).parents('.icb-question-detail')});"><i class="icon icon-qzone"></i> <?php _e('QZONE'); ?></a></li>
											<li><a onclick="AWS.User.share_out({webid: 'weixin', content: $(this).parents('.icb-question-detail')});"><i class="icon icon-wechat"></i> <?php _e('微信'); ?></a></li>
                                        </ul>
                                    </div>

                                    <em class="text-color-999"><?php echo date_friendly($this->itemInfo['add_time'], 604800, 'Y-m-d'); ?></em>
                                </span>
                            </div>
                        </div>
                        <div class="mod-footer">
                            <?php if ($this->itemInfo['vote_users']) { ?>
                            <div class="icb-article-voter">
                                <?php foreach ($this->itemInfo['vote_users'] AS $key => $val) { ?>
                                <a href="user/<?php echo $val['url_token']; ?>" class="voter" data-toggle="tooltip" data-placement="right" data-original-title="<?php echo $val['user_name']; ?>"><img alt="<?php echo $val['user_name']; ?>" src="<?php echo get_avatar_url($val['uid'], 'mid'); ?>" /></a>
                                <?php } ?>
                                <!--<a class="more-voters">...</a>-->
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="icb-mod icb-article-title-box" id="question_topic_editor" data-type="article" data-id="<?php echo $this->itemInfo['id']; ?>">
                        <div class="col-sm-12 clearfix nav-previous-next">
                            <div class="nav-previous-link col-sm-5"><?php if ($this->prevItem) {
                                ?><a href="<?php echo $this->prevItem['link'];?>"><i class="icon-left"></i><?php echo $this->prevItem['title']; ?></a><?php
                             }?> </div>
                            <div class="nav-next-link col-sm-5"><?php if ($this->nextItem) {
                                ?><a href="<?php echo $this->nextItem['link'];?>"><?php echo $this->nextItem['title']; ?><i class="icon-right"></i></a><?php
                            }?> </div>
                        </div>
                    </div>

                    <!-- 文章评论 -->
                    <div class="icb-mod">
                        <div class="mod-head common-head">
                            <h2><?php _e('%s 个评论', $this->comments_count); ?></h2>
                        </div>

                        <div class="mod-body icb-feed-list">
                            <?php if ($this->comments) { ?>

                                <?php foreach ($this->comments AS $key => $val) { ?>
                                <div class="icb-item" id="answer_list_<?php echo $val['id']; ?>">
                                    <div class="mod-head">
                                        <a class="icb-user-img icb-border-radius-5" href="user/<?php echo $val['user_info']['url_token']; ?>">
                                            <img src="<?php echo get_avatar_url($val['uid'], 'mid'); ?>" alt="<?php echo $val['user_info']['user_name']; ?>" />
                                        </a>
                                        <p>
                                            <a href="user/<?php echo $val['user_info']['url_token']; ?>"><?php echo $val['user_info']['user_name']; ?></a><?php if ($val['at_user_info']) { ?> <?php _e('回复'); ?> <a href="user/<?php echo $val['at_user_info']['url_token']; ?>"><?php echo $val['at_user_info']['user_name']; ?></a><?php } ?>
                                        </p>
                                    </div>
                                    <div class="mod-body">
                                        <div class="markitup-box">
                                            <?php echo nl2br($val['message']); ?>
                                        </div>
                                    </div>
                                    <div class="mod-footer">
                                        <div class="meta">
                                            <span class="pull-right text-color-999"><?php echo date_friendly($val['add_time']); ?></span>
                                            <?php if ($this->user_id) { ?>
                                                <a class="text-color-999 <?php
                                                if ($val['vote_info']['rating'] == 1) { ?> active<?php
                                                } ?>" onclick="AWS.User.article_comment_vote($(this), <?php
                                                echo $val['id'];
                                                ?>, 1)"><i class="icon icon-agree"></i> <?php
                                                echo $val['votes']; ?> <?php
                                                if ($val['vote_info']['rating'] == 1) { ?><?php
                                                _e('我已赞'); ?><?php } else { ?><?php _e('赞'); ?><?php
                                                } ?></a>
                                                <a class="icb-article-comment text-color-999" data-id="<?php echo $val['user_info']['uid']; ?>"><i class="icon icon-comment"></i> <?php _e('回复'); ?></a>
                                                <?php if ($this->user_info['permission']['is_administortar'] OR $this->user_info['permission']['is_moderator']) { ?>
                                                <a class="text-color-999" onclick="AWS.dialog('confirm', {'message' : '<?php _e('确认删除?'); ?>'}, function(){AWS.ajax_request(G_BASE_URL + '/article/ajax/remove_comment/', 'comment_id=<?php echo $val['id']; ?>');});"><i class="icon icon-trash"></i> <?php _e('删除'); ?></a>
                                                <?php } ?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            <?php } ?>
                        </div>

                        <?php if ($_GET['item_id']) { ?>
                        <div class="mod-footer">
                                <a href="article/<?php echo $this->itemInfo['id']; ?>" class="icb-get-more">
                                    <span><?php _e('查看全部评论'); ?></span>
                                </a>
                        </div>
                        <?php } ?>

                        <?php if ($this->pagination) { ?>
                            <div class="clearfix"><?php echo $this->pagination; ?></div>
                        <?php } ?>
                    </div>
                    <!-- end 文章评论 -->

                    <!-- 回复编辑器 -->
                    <div class="icb-mod icb-article-replay-box">
                        <a name="answer_form"></a>
                        <?php if ($this->itemInfo['lock']) { ?>
                        <p align="center"><?php _e('该文章目前已经被锁定, 无法添加新评论'); ?></p>
                        <?php } else if (!$this->user_id) { ?>
                        <p align="center"><?php _e('要回复文章请先<a href="account/login/">登录</a>或<a href="account/register/">注册</a>'); ?></p>
                        <?php } else { ?>
                        <form action="article/ajax/save_comment/" onsubmit="return false;" method="post" id="answer_form">
                        <input type="hidden" name="post_hash" value="<?php echo new_post_hash(); ?>" />
                        <input type="hidden" name="article_id" value="<?php echo $this->itemInfo['id']; ?>" />
                        <div class="mod-head">
                            <a href="user/" class="icb-user-name"><img alt="<?php echo $this->user_info['user_name']; ?>" src="<?php echo get_avatar_url($this->user_info['uid'], 'mid'); ?>" /></a>
                        </div>
                        <div class="mod-body">
                            <textarea rows="3" name="message" id="comment_editor" class="form-control autosize" placeholder="写下你的评论..."  /></textarea>
                        </div>
                        <div class="mod-footer clearfix">
                            <a href="javascript:;" onclick="AWS.ajax_post($('#answer_form'), AWS.ajax_processer, 'reply');" class="btn btn-normal btn-success pull-right btn-submit btn-reply"><?php _e('回复'); ?></a>
                            <?php if ($this->human_valid) { ?>
                            <em class="auth-img pull-right"><img src="" onclick="this.src = G_BASE_URL + '/account/captcha/' + Math.floor(Math.random() * 10000);" id="captcha" /></em>
                            <input class="pull-right form-control" type="text" name="seccode_verify" placeholder="<?php _e('验证码'); ?>" />
                            <?php } ?>
                        </div>
                        </form>
                        <?php } ?>
                    </div>
                    <!-- end 回复编辑器 -->
                </div>
                <!-- 右侧边栏 -->
                <div class="col-sm-12 col-md-2 icb-side-bar  hidden-sm hidden-xs">
                    <!-- 发起人 -->
                    <?php if ($this->itemInfo['anonymous'] == 0) { ?>
                    <div class="icb-mod user-detail">
                        <div class="mod-head">
                            <h3><?php _e('发起人'); ?></h3>
                        </div>
                        <div class="mod-body">
                            <dl>
                                <dt class="pull-left icb-border-radius-5">
                                    <a href="user/<?php echo $this->itemInfo['user_info']['url_token']; ?>"><img alt="<?php echo $this->itemInfo['user_info']['user_name']; ?>" src="<?php echo get_avatar_url($this->itemInfo['uid'], 'mid'); ?>" /></a>
                                </dt>
                                <dd class="pull-left">
                                    <a class="icb-user-name" href="user/<?php echo $this->itemInfo['user_info']['url_token']; ?>" data-id="<?php echo $this->itemInfo['user_info']['uid']; ?>"><?php echo $this->itemInfo['user_info']['user_name'];?></a>
                                    <?php if ($this->itemInfo['user_info']['verified']) { ?>
                                        <i class="icon-v<?php if ($this->itemInfo['user_info']['verified'] == 'enterprise') { ?> i-ve<?php } ?>" title="<?php if ($this->itemInfo['user_info']['verified'] == 'enterprise') { ?>企业认证<?php } else { ?>个人认证<?php } ?>"></i>
                                    <?php } ?>

                                    <?php if ($this->itemInfo['user_info']['uid'] != $this->user_id AND $this->user_id) { ?>
                                    <a class="icon-inverse follow tooltips icon icon-plus <?php if ($this->user_follow_check) { ?> active<?php } ?>" onclick="AWS.User.follow($(this), 'user', <?php echo $this->itemInfo['user_info']['uid']; ?>);"></a>
                                    <?php } ?>
                                    <p><?php echo $this->itemInfo['user_info']['signature']; ?></p>
                                </dd>
                            </dl>
                        </div>
                        <div class="mod-footer clearfix">
                            <?php if ($this->reputation_topics) { ?>
                            <div class="icb-article-title-box">
                                <div class="topic-bar clearfix">
                                    <span class="pull-left text-color-999">
                                        <?php _e('擅长话题'); ?> : &nbsp;
                                    </span>
                                    <?php foreach ($this->reputation_topics AS $key => $val) { ?>
                                    <span class="article-tag">
                                        <a href="topic/<?php echo $val['url_token']; ?>" class="text" data-id="<?php echo $val['topic_id']; ?>"><?php echo $val['topic_title']; ?></a>
                                    </span>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>
                    <!-- end 发起人 -->

                    <?php if ($this->recommend_posts) { ?>
                    <!-- 推荐内容 -->
                    <div class="icb-mod">
                        <div class="mod-head">
                            <h3><?php _e('推荐内容'); ?></h3>
                        </div>
                        <div class="mod-body">
                            <ul>
                                <?php foreach($this->recommend_posts AS $key => $val) { ?>
                                <li>
                                    <?php if ($val['question_id']) { ?>
                                    <a href="question/<?php echo $val['question_id']; ?>"><?php echo $val['question_content']; ?></a>
                                    <?php } else { ?>
                                    <a href="article/<?php echo $val['id']; ?>"><?php echo $val['title']; ?></a>
                                    <?php } ?>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                    <!-- end 推荐内容 -->
                    <?php } ?>

                    <?php if ($this->question_related_list) { ?>
                    <!-- 相关问题 -->
                    <div class="icb-mod icb-text-align-justify question-related-list">
                        <div class="mod-head">
                            <h3><?php _e('相关问题'); ?></h3>
                        </div>
                        <div class="mod-body font-size-12">
                            <ul>
                                <?php foreach($this->question_related_list AS $key => $val) { ?>
                                <li><a href="question/<?php echo $val['question_id']; ?>"><?php echo $val['question_content']; ?></a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                    <!-- end 相关问题 -->
                    <?php } ?>
                </div>
                <!-- end 右侧边栏 -->
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(function () {
    /**
     * 左侧菜单， 点击展开/收起子菜单
     */
    $('#course-content-table li>i').click(function () {
        if ($(this).parent().attr('data-parent-id')=='0') {
            $('#course-content-table li[data-parent-id="'+$(this).parent().attr('data-id')+'"]').toggleClass('hidden');
        }
    });
    /**
     * 页面载入， 将对应菜单的子菜单自动展开
     */
    if ($('#course-content-table li.on').length) {
        if ($('#course-content-table li.on').attr('data-parent-id')=='0') {
            $('#course-content-table li.on>i').trigger('click');
        } else {
            $('#course-content-table li[data-id="'+$('#course-content-table li.on').attr('data-parent-id')+'"]>i').trigger('click');
        }
    }
});
</script>

<?php View::output('global/footer.php'); ?>
