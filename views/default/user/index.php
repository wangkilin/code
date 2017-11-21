<?php View::output('global/header.php'); ?>

<div class="icb-container">
    <div class="container">
        <div class="row">
            <div class="icb-content-wrap clearfix">
                <div class="col-sm-12 col-md-9 icb-main-content">
                    <!-- 用户数据内容 -->
                    <div class="icb-mod icb-user-detail-box">
                        <div class="mod-head">
                            <img src="<?php echo get_avatar_url($this->user['uid'], 'max'); ?>" alt="<?php echo $this->user['user_name']; ?>" />
                            <span class="pull-right operate">
                                <?php if ($this->user['uid'] != $this->user_id AND $this->user_id) { ?>
                                <a class="text-color-999" onclick="AWS.dialog('inbox', '<?php echo $this->user['user_name']; ?>');"><i class="icon icon-inbox"></i> 私信</a><a onclick="AWS.dialog('publish', {category_enable:<?php echo (get_setting('category_enable') == 'Y') ? '1' : '0'; ?>, ask_user_id:<?php echo $this->user['uid']; ?>, ask_user_name:'<?php echo $this->user['user_name']; ?>'});" class="text-color-999 hidden-xs"><i class="icon icon-at"></i> <?php _e('问Ta'); ?></a><a href="javascript:;" class="follow btn btn-normal btn-success<?php if ($this->user_follow_check) { ?> active<?php } ?>" onclick="AWS.User.follow($(this), 'user', <?php echo $this->user['uid']; ?>);"><span><?php if ($this->user_follow_check) { ?><?php _e('取消关注'); ?><?php } else { ?><?php _e('关注'); ?><?php } ?></span> <em>|</em> <b><?php echo $this->user['fans_count']; ?></b></a>
                                <?php } else if ($this->user_id == $this->user['uid']) { ?>
                                <a href="account/setting/profile/" class="btn btn-mini btn-success"><?php _e('编辑'); ?></a>
                                <?php } ?>
                            </span>
                            <h1><?php echo $this->user['user_name']; ?> <?php if ($this->user['verified']) { ?><i class="icon-v <?php if ($this->user['verified'] == 'enterprise') { ?> i-ve<?php } ?>" title="<?php if ($this->user['verified'] == 'enterprise') { ?>企业认证<?php } else { ?>个人认证<?php } ?>"></i><?php } ?></h1>
                            <p class="text-color-999"><?php echo $this->user['signature']; ?></p>
                            <p class="icb-user-flag">
                                <?php if ($this->user['province']) { ?>
                                <span>
                                    <i class="icon icon-location"></i>
                                    <?php echo $this->user['province']; ?>
                                    <?php echo $this->user['city']; ?>
                                </span>
                                <?php } ?>
                                <?php if ($this->job_name) { ?>
                                <span>
                                    <i class="icon icon-job"></i>
                                    <?php echo $this->job_name; ?>
                                </span>
                                <?php } ?>
                                <?php if (get_setting('sina_weibo_enabled') == 'Y' AND $this->sina_weibo_url) { ?>
                                <span>
                                    <?php _e('绑定认证'); ?>
                                    <?php if ($this->sina_weibo_url AND get_setting('sina_weibo_enabled') == 'Y') { ?><a href="<?php if ($this->sina_weibo_url) { ?><?php echo $this->sina_weibo_url; ?><?php } else { ?>javascript:;<?php } ?>" title="<?php _e('微博'); ?>"><i class="icon icon-weibo"></i></a><?php } ?>
                                </span>
                                <?php } ?>
                            </p>
                        </div>
                        <div class="mod-body">
                            <div class="meta">
                                <span><i class="icon icon-prestige"></i> <?php _e('威望'); ?> : <em class="icb-text-color-green"><?php echo $this->user['reputation']; ?></em></span>
                                <?php if (get_setting('integral_system_enabled') == 'Y') { ?><span><i class="icon icon-score"></i> <?php _e('积分'); ?> : <em class="icb-text-color-orange"><?php echo $this->user['integral']; ?></em></span><?php } ?>
                                <span><i class="icon icon-agree"></i> <?php _e('赞同'); ?> : <em class="icb-text-color-orange"><?php echo $this->user['agree_count']; ?></em></span>
                                <span><i class="icon icon-thank"></i> <?php _e('感谢'); ?> : <em class="icb-text-color-orange"><?php echo $this->user['thanks_count']; ?></em></span>
                            </div>
                            <?php if ($this->reputation_topics) { ?>
                            <!-- 擅长话题 -->
                            <div class="good-at-topics clearfix">
                                <h2><?php _e('擅长话题'); ?></h2>
                                <ul>
                                    <?php foreach ($this->reputation_topics AS $key => $val) { ?>
                                    <li>
                                        <span class="article-tag">
                                            <a href="topic/<?php echo $val['url_token']; ?>" class="text" data-id="<?php echo $val['topic_id']; ?>"><?php echo $val['topic_title']; ?></a>
                                        </span>
                                        <span>
                                            <i class="icon icon-agree"></i> <?php echo $val['agree_count']; ?>&nbsp;&nbsp;
                                            <i class="icon icon-thank"></i> <?php echo $val['thanks_count']; ?>
                                        </span>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <!-- end 擅长话题 -->
                            <?php } ?>

                        </div>
                        <div class="mod-footer">
                            <ul class="nav nav-tabs icb-nav-tabs">
                                <li class="active"><a href="#overview" id="page_overview" data-toggle="tab"><?php _e('概述'); ?></a></li>
                                <li><a href="#questions" id="page_questions" data-toggle="tab"><?php _e('发问'); ?><span class="badge"><?php echo $this->user['question_count']; ?></span></a></li>
                                <li><a href="#answers" id="page_answers" data-toggle="tab"><?php _e('回复'); ?><span class="badge"><?php echo $this->user['answer_count']; ?></span></a></li>
                                <li><a href="#articles" id="page_articles" data-toggle="tab"><?php _e('文章'); ?><span class="badge"><?php echo $this->user['article_count']; ?></span></a></li>
                                <li><a href="#focus" id="page_focus" data-toggle="tab"><?php _e('关注'); ?></a></li>
                                <li><a href="#actions" id="page_actions" data-toggle="tab"><?php _e('动态'); ?></a></li>
                                <li><a href="#detail" id="page_detail" data-toggle="tab"><?php _e('详细资料'); ?></a></li>
                                <?php if ($this->user_id == $this->user['uid'] AND get_setting('integral_system_enabled') == 'Y') { ?>
                                <li><a href="#integral" id="page_integral" data-toggle="tab"><?php _e('我的积分'); ?></a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                    <!-- end 用户数据内容 -->

                    <div class="icb-user-center-tab">
                        <div class="tab-content">
                            <div class="tab-pane active" id="overview">
                                <!-- 回复 -->
                                <div class="icb-mod">
                                    <div class="mod-head">
                                        <h3><a class="pull-right icb-more-content" href="javascript:;" onclick="$('#page_answers').click();"><?php _e('更多'); ?> »</a><?php _e('回复'); ?></h3>
                                    </div>
                                    <div class="mod-body">
                                        <div class="icb-profile-answer-list">
                                            <?php if ($this->user_actions_answers) { ?>
                                                <?php foreach ($this->user_actions_answers AS $key => $val) { ?>
                                                <div class="icb-item">
                                                    <div class="mod-head">
                                                        <h4><a href="<?php echo $val['link']; ?>"><?php echo $val['title']; ?></a></h4>
                                                    </div>
                                                    <div class="mod-body">
                                                        <span class="icb-border-radius-5 count pull-left"><i class="icon icon-agree"></i><?php echo $val['answer_info']['agree_count']; ?></span>
                                                        <p class="icb-hide-txt"><?php echo cjk_substr($val['answer_info']['answer_content'], 0, 130, 'UTF-8', '...'); ?></p>
                                                    </div>
                                                </div>
                                                <?php } ?>
                                            <?php } else { ?>
                                            <p class="padding10 text-center"><?php _e('没有内容'); ?></p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <!-- end 回复 -->

                                <!-- 发问 -->
                                <div class="icb-mod">
                                    <div class="mod-head">
                                        <h3><a class="pull-right icb-more-content" href="javascript:;" onclick="$('#page_questions').click();"><?php _e('更多'); ?> »</a><?php _e('发问'); ?></h3>
                                    </div>
                                    <div class="mod-body">
                                        <div class="icb-profile-publish-list">
                                            <?php if ($this->user_actions_questions) { ?>
                                                <?php foreach ($this->user_actions_questions AS $key => $val) { ?>
                                                <div class="icb-item">
                                                    <div class="mod-head">
                                                        <h4><a href="question/<?php echo $val['question_info']['question_id']; ?>"><?php echo $val['question_info']['question_content']; ?></a></h4>
                                                    </div>
                                                    <div class="mod-body">
                                                        <span class="icb-border-radius-5 count pull-left"><i class="icon icon-reply"></i><?php echo $val['question_info']['answer_count']; ?></span>
                                                        <p class="icb-hide-txt"><?php _e('%s 次浏览', $val['question_info']['view_count']); ?> &nbsp;• <?php _e('%s 个关注', $val['question_info']['focus_count']); ?> &nbsp; • <?php echo date_friendly($val['add_time'], 604800, 'Y-m-d'); ?></p>
                                                    </div>
                                                </div>
                                                <?php } ?>
                                            <?php } else { ?>
                                            <p class="padding10 text-center"><?php _e('没有内容'); ?></p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <!-- end 发问 -->

                                <!-- 最新动态 -->
                                <div class="icb-mod">
                                    <div class="mod-head">
                                        <h3><a class="pull-right icb-more-content" href="javascript:;" onclick="$('#page_actions').click();"><?php _e('更多'); ?> »</a><?php _e('动态'); ?></h3>
                                    </div>
                                    <div class="mod-body">
                                        <ul>
                                            <?php if ($this->user_actions_answers) { ?>
                                                <?php foreach ($this->user_actions_answers AS $key => $val) { ?>
                                                <li>
                                                    <p>
                                                        <span class="pull-right text-color-999"><?php echo date_friendly($val['add_time'], 604800, 'Y-m-d'); ?></span>
                                                        <em class="pull-left"><?php echo $val['last_action_str']; ?>,</em>
                                                        <a class="icb-hide-txt" href="question/<?php echo $val['question_info']['question_id']; ?>"><?php echo $val['question_info']['question_content']; ?></a>
                                                    </p>
                                                </li>
                                                <?php } ?>
                                            <?php } else { ?>
                                            <p class="padding10 text-center"><?php _e('没有内容'); ?></p>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="questions">
                                <div class="icb-mod">
                                    <div class="mod-head">
                                        <h3><?php _e('发问'); ?></h3>
                                    </div>
                                    <div class="mod-body">
                                        <div class="icb-profile-publish-list" id="contents_user_actions_questions"></div>
                                    </div>
                                    <div class="mod-footer">
                                        <!-- 加载更多内容 -->
                                        <a class="icb-get-more" id="bp_user_actions_questions_more">
                                            <span><?php _e('更多'); ?></span>
                                        </a>
                                        <!-- end 加载更多内容 -->
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="answers">
                                <div class="icb-mod">
                                    <div class="mod-head">
                                        <h3><?php _e('回复'); ?></h3>
                                    </div>
                                    <div class="mod-body">
                                        <div class="icb-profile-answer-list" id="contents_user_actions_answers"></div>
                                    </div>
                                    <div class="mod-footer">
                                        <!-- 加载更多内容 -->
                                        <a class="icb-get-more" id="bp_user_actions_answers_more">
                                            <span><?php _e('更多'); ?></span>
                                        </a>
                                        <!-- end 加载更多内容 -->
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="articles">
                                <div class="icb-mod">
                                    <div class="mod-head">
                                        <h3><?php _e('文章'); ?></h3>
                                    </div>
                                    <div class="mod-body">
                                        <div class="icb-profile-publish-list" id="contents_user_actions_articles"></div>
                                    </div>
                                    <div class="mod-footer">
                                        <!-- 加载更多内容 -->
                                        <a class="icb-get-more" id="bp_user_actions_articles_more">
                                            <span><?php _e('更多'); ?></span>
                                        </a>
                                        <!-- end 加载更多内容 -->
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="focus">
                                <!-- 自定义切换 -->
                                <div class="icb-mod">
                                    <div class="icb-tabs text-center">
                                        <ul>
                                            <li class="active"><a><?php _e('关注的人'); ?></a></li>
                                            <li><a><?php _e('关注者'); ?></a></li>
                                            <li><a><?php _e('关注的话题'); ?></a></li>
                                        </ul>
                                    </div>
                                    <div class="mod-body">
                                        <div class="icb-tab-content">
                                            <div class="icb-mod icb-user-center-follow-mod">
                                                <div class="mod-body">
                                                    <ul id="contents_user_follows" class="clearfix"></ul>
                                                </div>
                                                <div class="mod-footer">
                                                    <!-- 加载更多内容 -->
                                                    <a class="icb-get-more" id="bp_user_follows_more">
                                                        <span><?php _e('更多'); ?></span>
                                                    </a>
                                                    <!-- end 加载更多内容 -->
                                                </div>
                                            </div>
                                            <div class="icb-mod icb-user-center-follow-mod collapse">
                                                <div class="mod-body">
                                                    <ul class="clearfix" id="contents_user_fans"></ul>
                                                </div>
                                                <div class="mod-footer">
                                                    <!-- 加载更多内容 -->
                                                    <a class="icb-get-more" id="bp_user_fans_more">
                                                        <span><?php _e('更多'); ?></span>
                                                    </a>
                                                    <!-- end 加载更多内容 -->
                                                </div>
                                            </div>
                                            <div class="icb-mod icb-user-center-follow-mod collapse">
                                                <div class="mod-body">
                                                    <ul id="contents_user_topics" class="clearfix"></ul>
                                                </div>
                                                <div class="mod-footer">
                                                    <!-- 加载更多内容 -->
                                                    <a class="icb-get-more" id="bp_user_topics_more">
                                                        <span><?php _e('更多'); ?></span>
                                                    </a>
                                                    <!-- end 加载更多内容 -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end 自定义切换 -->
                            </div>
                            <div class="tab-pane" id="actions">
                                <div class="icb-mod">
                                    <div class="mod-head">
                                        <h3><?php _e('最新动态'); ?></h3>
                                    </div>
                                    <div class="mod-body">
                                        <div id="contents_user_actions"></div>
                                    </div>
                                    <div class="mod-footer">
                                        <!-- 加载更多内容 -->
                                        <a class="icb-get-more" id="bp_user_actions_more">
                                            <span><?php _e('更多'); ?></span>
                                        </a>
                                        <!-- end 加载更多内容 -->
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="detail">
                                <div class="icb-mod">
                                    <div class="mod-head">
                                        <h3><?php _e('详细资料'); ?></h3>
                                    </div>
                                    <div class="mod-body icb-user-center-details">
                                        <?php if (get_setting('sina_weibo_enabled') == 'Y' AND $this->sina_weibo_url) { ?>
                                        <dl>
                                            <dt><span><?php _e('绑定认证'); ?>:</span></dt>
                                            <?php if ($this->sina_weibo_url AND get_setting('sina_weibo_enabled') == 'Y') { ?><dd><a href="<?php if ($this->sina_weibo_url) { ?><?php echo $this->sina_weibo_url; ?><?php } else { ?>javascript:;<?php } ?>" class="text-color-999"><i class="icon icon-weibo"></i><?php _e('微博'); ?></a></dd><?php } ?>
                                        </dl>
                                        <?php } ?>
                                    <dl>
                                        <dt><span><?php _e('个人成就'); ?>:</span></dt>
                                        <dd>
                                            <p class="meta">
                                                <span><i class="icon icon-prestige"></i><?php _e('威望'); ?>: <em class="icb-text-color-green"><?php echo $this->user['reputation']; ?></em></span>
                                                <?php if (get_setting('integral_system_enabled') == 'Y') { ?>
                                                <span><i class="icon icon-score"></i><?php _e('积分'); ?>: <em class="icb-text-color-orange"><?php echo $this->user['integral']; ?></em></span>
                                                <?php } ?>
                                                <span><i class="icon icon-agree"></i><?php _e('赞同'); ?>: <em class="icb-text-color-orange"><?php echo $this->user['agree_count']; ?></em></span>
                                                <span><i class="icon icon-thank"></i><?php _e('感谢'); ?>: <em class="icb-text-color-orange"><?php echo $this->user['thanks_count']; ?></em></span>
                                            </p>
                                        </dd>
                                    </dl>
                                    <?php if ($this->user['area_name']) { ?>
                                    <dl>
                                        <dt><span><?php _e('现居'); ?>:</span></dt>
                                        <dd>
                                            <?php echo $this->user['area_name']; ?>
                                        </dd>
                                    </dl>
                                    <?php } ?>

                                    <?php if ($this->education_experience_list) { ?>
                                    <dl>
                                        <dt><span><?php _e('教育经历'); ?>:</span></dt>
                                        <dd>
                                            <ul>
                                                <?php foreach($this->education_experience_list as $k => $v) { ?>
                                                <li>
                                                    <?php echo $v['education_years']; ?> <?php _e('年'); ?> <?php _e('就读于%s', $v['school_name']); ?> <?php echo $v['departments']; ?>
                                                </li>
                                                <?php } ?>
                                            </ul>
                                        </dd>
                                    </dl>
                                    <?php } ?>

                                    <?php if ($this->work_experience_list) { ?>
                                    <dl>
                                        <dt><span><?php _e('职业经历'); ?>:</span></dt>
                                        <dd>
                                            <ul>
                                            <?php foreach($this->work_experience_list as $k => $v) { ?>                     <li>
                                                    <?php echo $v['start_year']; ?> - <?php if ($v['end_year'] == -1) { ?><?php _e('至今'); ?><?php } else { ?><?php echo $v['end_year']; ?><?php } ?> <?php _e('就职于%s', $v['company_name']); ?> <?php if ($v['job_name']) { ?> <?php _e('担任 %s', $v['job_name']); ?> <?php } ?>
                                                </li>
                                            <?php } ?>
                                            </ul>
                                        </dd>
                                    </dl>
                                    <?php } ?>

                                    <?php if ($this->user['last_active']) { ?>
                                    <dl>
                                        <dt><span><?php _e('最后活跃'); ?>:</span></dt>
                                        <dd><?php echo date_friendly($this->user['last_active']); ?></dd>
                                    </dl>
                                    <?php } ?>

                                    <?php if ($this->reputation_topics) { ?>
                                    <dl>
                                        <dt><span><?php _e('擅长话题'); ?>:</span></dt>
                                        <dd class="icb-user-center-details-good-topic">
                                            <?php foreach ($this->reputation_topics AS $key => $val) { ?>
                                            <div>
                                                <span class="article-tag">
                                                    <a href="topic/<?php echo $val['url_token']; ?>" class="text" data-id="<?php echo $val['topic_id']; ?>"><?php echo $val['topic_title']; ?></a>
                                                </span>
                                                <span>
                                                    <i class="icon icon-agree"></i> <?php echo $val['agree_count']; ?>&nbsp;&nbsp;
                                                    <i class="icon icon-thank"></i> <?php echo $val['thanks_count']; ?>
                                                </span>
                                            </div>
                                            <?php } ?>
                                        </dd>
                                    </dl>
                                    <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <?php if ($this->user_id == $this->user['uid'] AND get_setting('integral_system_enabled') == 'Y') { ?>
                            <div class="tab-pane" id="integral">
                                <div class="icb-mod">
                                    <div class="mod-head">
                                        <h3><i class="icon icon-point"></i><?php _e('我的积分'); ?></h3>
                                    </div>
                                    <div class="mod-body">
                                        <table class="table table-hover icb-table">
                                            <thead>
                                                <tr class="info">
                                                    <th width="14%"><?php _e('时间'); ?></th>
                                                    <th width="8%"><?php _e('数额'); ?></th>
                                                    <th width="8%"><?php _e('余额'); ?></th>
                                                    <th width="17%"><?php _e('描述'); ?></th>
                                                    <th width="40%"><?php _e('相关信息'); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody id="contents_user_integral">
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mod-footer">
                                        <!-- 加载更多内容 -->
                                        <a class="icb-get-more" id="bp_user_integral">
                                            <span><?php _e('更多'); ?></span>
                                        </a>
                                        <!-- end 加载更多内容 -->
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <!-- 侧边栏 -->
                <div class="col-sm-12 col-md-3 icb-side-bar">
                    <div class="icb-mod people-following">
                        <div class="mod-body">
                            <a onclick="$('#page_focus').click();$('#focus .icb-tabs ul li').eq(0).click();$.scrollTo($('#focus').offset()['top'], 600, {queue:true})" class="pull-right font-size-12"><?php _e('更多'); ?> »</a>
                            <span>
                                <?php _e('关注 %s 人', '<em class="icb-text-color-blue">' . $this->user['friend_count'] . '</em>'); ?>
                            </span>
                            <?php if ($this->friends_list) { ?>
                            <p>
                                <?php foreach ($this->friends_list AS $key => $val) { ?>
                                    <a class="icb-user-name" data-id="<?php echo $val['uid']; ?>" href="user/<?php echo $val['url_token']; ?>"><img src="<?php echo get_avatar_url($val['uid'], 'mid'); ?>" alt="<?php echo $val['user_name']; ?>" /></a>
                                <?php } ?>
                            </p>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="icb-mod people-following">
                        <div class="mod-body">
                            <a onclick="$('#page_focus').click();$('#focus .icb-tabs ul li').eq(1).click();$.scrollTo($('#focus').offset()['top'], 600, {queue:true})" class="pull-right font-size-12"><?php _e('更多'); ?> »</a>
                            <span>
                                <?php _e('被 %s 人关注', '<em class="icb-text-color-blue">' . $this->user['fans_count'] . '</em>'); ?>
                            </span>

                            <?php if ($this->fans_list) { ?>
                            <p>
                                <?php foreach ($this->fans_list AS $key => $val) { ?>
                                    <a class="icb-user-name" data-id="<?php echo $val['uid']; ?>" href="user/<?php echo $val['url_token']; ?>"><img src="<?php echo get_avatar_url($val['uid'], 'mid'); ?>" alt="<?php echo $val['user_name']; ?>"></a>
                                <?php } ?>
                            </p>
                            <?php } ?>
                        </div>

                    </div>
                    <div class="icb-mod people-following">
                        <div class="mod-body">
                            <?php _e('关注 %s 话题', '<em class="icb-text-color-blue">' . $this->user['topic_focus_count'] . '</em>'); ?>
                            <?php if ($this->focus_topics) { ?>
                            <div class="icb-article-title-box">
                                <div class="tag-queue-box clearfix">
                                    <?php foreach ($this->focus_topics AS $key => $val) { ?>
                                        <span class="article-tag">
                                            <a href="topic/<?php echo $val['url_token']; ?>" class="text" data-id="<?php echo $val['topic_id']; ?>"><?php echo $val['topic_title']; ?></a>
                                        </span>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="icb-mod">
                        <div class="mod-body">
                            <span class="icb-text-color-666">
                                <?php _e('主页访问量'); ?> : <?php _e('%s 次访问', $this->user['views_count']); ?>
                            </span>
                        </div>
                    </div>
                </div>
                <!-- end 侧边栏 -->
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var PEOPLE_USER_ID = '<?php echo $this->user['uid']; ?>';

    var ACTIVITY_ACTIONS = '<?php echo implode(',', array(
        ACTION_LOG::ADD_QUESTION,
        ACTION_LOG::ANSWER_QUESTION,
        ACTION_LOG::ADD_REQUESTION_FOCUS,
        ACTION_LOG::ADD_AGREE,
        ACTION_LOG::ADD_TOPIC,
        ACTION_LOG::ADD_TOPIC_FOCUS,
        ACTION_LOG::ADD_ARTICLE,
        ACTION_LOG::ADD_AGREE_ARTICLE,
        ACTION_LOG::ADD_COMMENT_ARTICLE
    )); ?>';
</script>

<script type="text/javascript" src="<?php echo G_STATIC_URL; ?>/js/app/user.js"></script>

<?php View::output('global/footer.php'); ?>