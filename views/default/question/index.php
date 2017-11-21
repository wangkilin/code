<?php View::output('global/header.php'); ?>

<div class="icb-container">
	<?php if ($this->redirect_message) { ?>
	<div class="container">
		<div class="row">
			<div class="col-sm-12 icb-global-tips">
				<?php foreach ($this->redirect_message AS $key => $message) { ?>
				<div class="alert alert-warning fade in">
					<?php echo $message; ?>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php } ?>
	<div class="container">
		<div class="row">
			<div class="icb-content-wrap clearfix">
				<div class="col-sm-12 col-md-9 icb-main-content">
					<!-- 话题推荐bar -->
					<?php if (sizeof($this->question_topics) == 0 AND $this->user_id AND !$this->question_info['lock'] && $this->user_info['permission']['edit_topic']) { ?>
					<div class="icb-notopic-sort">
						<?php if ($this->related_topics) { ?>
						<span class="pull-left"><?php _e('可能属于这些话题'); ?>&nbsp;:&nbsp;</span>
						<?php foreach ($this->related_topics AS $key => $topic_title) { ?>
						<span class="article-tag">
							<a onclick="one_click_add_topic($(this), '<?php echo $topic_title; ?>', <?php echo $this->question_info['question_id'];?>);" class="text"><?php echo $topic_title; ?></a>
						</span>
						<?php } ?>, <?php _e('都不是'); ?>? <a href="javascript:;" onclick="$('#question_topic_editor .icb-edit-topic').click();$(this).parents('.icb-notopic-sort').hide();"> <?php _e('点此添加话题'); ?></a>
						<?php } else { ?>
						<?php _e('没有归属话题, 请帮问题添加话题'); ?>, <a href="javascript:;" onclick="$('#question_topic_editor .icb-edit-topic').click(); $(this).parents('.icb-notopic-sort').hide();"><?php _e('点此添加话题'); ?></a>
						<?php } ?>
					</div>
					<?php } ?>
					<!-- 话题推荐bar -->
					<!-- 话题bar -->
					<div class="icb-mod icb-article-title-box" id="question_topic_editor" data-type="question" data-id="<?php echo $this->question_info['question_id']; ?>">
						<div class="tag-queue-box clearfix">
							<?php foreach($this->question_topics as $key => $val) { ?>
							<span class="article-tag" data-id="<?php echo $val['topic_id']; ?>">
								<a href="topic/<?php echo $val['url_token']; ?>" class="text"><?php echo $val['topic_title']; ?></a>
							</span>
							<?php } ?>

							<?php if ($this->user_id AND ((!$this->question_info['lock'] AND $this->user_info['permission']['edit_topic']) OR $this->user_id == $this->question_info['published_uid'])) { ?><span class="icon-inverse icb-edit-topic"<?php if (sizeof($this->question_topics) == 0) { ?> style="display:none"<?php } ?>><i class="icon icon-edit"></i></span><?php } ?>
						</div>
					</div>
					<!-- end 话题bar -->
					<div class="icb-mod icb-question-detail icb-item">
						<div class="mod-head">
							<h1>
								<?php echo $this->question_info['question_content']; ?>
							</h1>

							<?php if ($this->user_id) { ?>
							<div class="operate clearfix">
								<a href="javascript:;" onclick="AWS.User.follow($(this), 'question', <?php echo $this->question_info['question_id']; ?>);" class="follow btn btn-normal btn-success pull-left <?php if ($this->question_focus) { ?> active<?php } ?>"><span><?php if ($this->question_focus) { ?><?php _e('取消关注'); ?><?php } else { ?><?php _e('关注'); ?><?php } ?></span> <em>|</em> <b><?php echo $this->question_info['focus_count']?></b></a>
								<!-- 下拉菜单 -->
								<div class="btn-group pull-left">
									<a class="btn btn-gray dropdown-toggle" data-toggle="dropdown" href="javascript:;">...</a>
									<div class="icb-dropdown pull-right" role="menu" aria-labelledby="dropdownMenu">
										<ul class="icb-dropdown-list">
											<li>
												<?php if ($_GET['column'] == 'log') { ?>
													<a href="question/<?php echo $this->question_info['question_id']; ?>"><?php _e('问题讨论'); ?></a>
												<?php } else { ?>
													<a href="question/<?php echo $this->question_info['question_id']; ?>?column=log&rf=false" rel="nofollow"><?php _e('修改记录'); ?></a>
												<?php } ?>
											</li>
											<?php if ($this->user_info['permission']['is_administortar'] OR $this->user_info['permission']['is_moderator']) { ?>
											<li>
												<a href="javascript:;" onclick="AWS.ajax_request(G_BASE_URL + '/question/ajax/lock/', 'question_id=<?php echo $this->question_info['question_id']; ?>');"><?php if ($this->question_info['lock']) { ?><?php _e('解除锁定'); ?><?php } else { ?><?php _e('锁定问题'); ?><?php } ?></a>
											</li>
											<li>
												<a href="javascript:;" onclick="AWS.dialog('confirm', {'message' : '<?php _e('确认删除?'); ?>'}, function(){AWS.ajax_request(G_BASE_URL + '/question/ajax/remove_question/', 'question_id=<?php echo $this->question_info['question_id']; ?>');});"><?php _e('删除问题'); ?></a>
											</li>
											<?php } ?>
											<?php if (($this->user_info['permission']['is_administortar'] OR $this->user_info['permission']['is_moderator'] OR (!$this->question_info['lock'] AND $this->user_info['permission']['redirect_question'])) AND $this->user_id) { ?>
											<li class="hidden-xs">
											<?php if ($this->question_info['redirect']) { ?>
												<a href="javascript:;" onclick="AWS.ajax_request(G_BASE_URL + '/question/ajax/redirect/', 'item_id=<?php echo $this->question_info['question_id']; ?>');"><?php _e('撤消重定向'); ?></a>
											<?php } else { ?>
												<a href="javascript:;" onclick="AWS.dialog('redirect', <?php echo $this->question_info['question_id']; ?>);"><?php _e('问题重定向'); ?></a>
											<?php } ?>
											</li>
											<?php } ?>
											<?php if (($this->user_info['permission']['is_administortar'] OR $this->user_info['permission']['is_moderator']) AND $this->question_info['ip']) { ?>
												<li>
													<a href="javascript:;" title="<?php echo long2ip($this->question_info['ip']); ?>" onclick="AWS.alert('<?php _e('系统记录的 IP 地址为'); ?>: <?php echo long2ip($this->question_info['ip']); ?>');"><?php _e('IP 地址'); ?></a>
												</li>
											<?php } ?>
											<?php if ($this->user_info['permission']['is_administortar'] OR $this->user_info['permission']['is_moderator']) { ?>
												<li><a href="javascript:;" onclick="AWS.ajax_request(G_BASE_URL + '/question/ajax/set_recommend/', 'action=<?php if ($this->question_info['is_recommend']) { ?>un<?php } ?>set&question_id=<?php echo $this->question_info['question_id']; ?>');"><?php if ($this->question_info['is_recommend']) { ?><?php _e('取消推荐'); ?><?php } else { ?><?php _e('推荐问题'); ?><?php } ?></a>
												</li>
												<li>
													<a href="javascript:;" onclick="AWS.dialog('recommend', {'type': 'question', 'item_id': <?php echo $this->question_info['question_id']?>, 'focus_id': <?php if ($this->question_info['chapter_id']) { echo $this->question_info['chapter_id']; } else { ?>''<?php } ?>});"><?php _e('添加到帮助中心'); ?></a>
												</li>
											<?php } ?>
										</ul>
									</div>
								</div>
								<!-- end 下拉菜单 -->
							</div>
							<?php } ?>
						</div>
						<div class="mod-body">
							<div class="content markitup-box">
								<?php echo $this->question_info['question_detail']; ?>

								<?php if ($this->question_info['attachs']) {  ?>
								<div class="icb-upload-img-list">
								<?php foreach ($this->question_info['attachs'] AS $attach) { ?>
								<?php if ($attach['is_image'] AND (!$this->question_info['attachs_ids'] OR !in_array($attach['id'], $this->question_info['attachs_ids']))) { ?>
									<a href="<?php echo $attach['attachment']; ?>" target="_blank" data-fancybox-group="thumb" rel="lightbox"><img src="<?php echo $attach['attachment']; ?>" class="img-thumbnail" alt="<?php echo $attach['file_name']; ?>" /></a>
								<?php } ?>
								<?php } ?>
								</div>
								<?php } ?>
							</div>
							<?php if ($this->question_info['attachs']) {  ?>
							<div class="icb-mod icb-upload-file-list">
								<!-- <div class="mod-head">
									<h3><i class="icon icon-attach"></i> <?php _e('附件'); ?> :</h3>
								</div> -->
								<div class="mod-body">
									<ul>
										<?php foreach ($this->question_info['attachs'] AS $attach) { ?>
										<?php if (!$attach['is_image'] AND (!$this->question_info['attachs_ids'] OR !in_array($attach['id'], $this->question_info['attachs_ids']))) { ?>
											<li><a href="<?php echo download_url($attach['file_name'], $attach['attachment']); ?>"><i class="icon icon-attach"></i> <?php echo $attach['file_name']; ?></a></li>
										<?php } ?>
										<?php } ?>
									</ul>
								</div>
							</div>
							<?php } ?>
							<?php if ($this->question_related_links) { ?>
							<div class="icb-mod icb-question-related-list">
								<div class="mod-head">
									<h3><i class="icon icon-bind"></i> <?php _e('相关链接'); ?> : </h3>
								</div>
								<div class="mod-body">
									<ul>
										<?php foreach ($this->question_related_links AS $key => $val) { ?>
										<li><a href="<?php echo $val['link']; ?>" rel="nofollow" target="_blank"><?php echo $val['link']; ?></a> &nbsp; <?php if ($this->user_info['permission']['is_administortar'] OR $this->user_info['permission']['is_moderator'] OR $this->user_id == $this->question_info['published_uid']) { ?><a class="text-color-999" onclick="AWS.ajax_request(G_BASE_URL + '/publish/ajax/remove_related_link/', 'item_id=<?php echo $this->question_info['question_id']; ?>&id=<?php echo $val['id']; ?>');">删除</a><?php } ?></li>
										<?php } ?>
									</ul>
								</div>
							</div>
							<?php } ?>
						</div>
						<div class="mod-footer">
							<div class="meta">
								<span class="text-color-999"><?php echo date_friendly($this->question_info['add_time'], 604800, 'Y-m-d'); ?></span>

								<a data-id="<?php echo $this->question_info['question_id']; ?>" data-type="question" class="icb-add-comment text-color-999 <?php if ($this->question_info['comment_count'] > 0) {?>active<?php }?>" data-comment-count="<?php echo $this->question_info['comment_count']; ?>" data-first-click="<?php if ($this->question_info['comment_count'] == 0) {?>hide<?php }?>"><i class="icon icon-comment"></i><?php if ($this->question_info['comment_count']) { ?><?php _e('%s 条评论', $this->question_info['comment_count']); ?><?php } else { ?><?php _e('添加评论'); ?><?php } ?></a>

								<?php if ($this->user_id) { ?>
								<a class="text-color-999 icb-invite-replay"><i class="icon icon-invite"></i><?php _e('邀请'); ?> <?php if (sizeof($this->invite_users) > 0){ ?><em class="badge badge-info"><?php echo count($this->invite_users); ?></em><?php } ?></a>
								<?php } ?>

								<?php if ((!$this->question_info['lock'] AND ($this->question_info['published_uid'] == $this->user_id OR $this->user_info['permission']['edit_question'])) OR $this->user_info['permission']['is_administortar'] OR $this->user_info['permission']['is_moderator']) { ?><a class="text-color-999" href="publish/<?php echo $this->question_info['question_id']; ?>"><i class="icon icon-edit"></i><?php _e('编辑'); ?></a><?php } ?>
								<?php if ($this->user_id AND $this->question_info['unverified_modify_count']) { ?><a href="question/<?php echo $this->question_info['question_id']; ?>?column=log&rf=false" class="text-color-999 icb-question-wait-edit"><i class="icon-check"></i><?php _e('待确认'); ?> <em class="badge badge-info"><?php echo $this->question_info['unverified_modify_count']; ?></em></a><?php } ?>

								<?php if ($this->user_info['permission']['is_administortar'] OR $this->user_info['permission']['is_moderator'] OR $this->user_id == $this->question_info['published_uid']) { ?>
								<a class="text-color-999 icb-add-question-related hidden-xs"><i class="icon icon-bind"></i> <?php _e('相关链接'); ?></a>
								<?php } ?>
								<div class="pull-right more-operate">
									<?php if ($this->user_id != $this->question_info['published_uid'] AND $this->user_id) { ?><a <?php if (!$this->question_thanks) { ?>data-placement="bottom" title="" data-toggle="tooltip" data-original-title="<?php _e('感谢提问者'); ?>" onclick="AWS.User.question_thanks($(this), <?php echo $this->question_info['question_id']; ?>);"<?php } ?> class="icb-icon-thank-tips text-color-999"><i class="icon icon-thank"></i><?php if ($this->question_thanks) { ?><?php _e('已感谢'); ?><?php } else { ?><?php _e('感谢'); ?><?php } ?></a>
									<?php } ?>

									<!-- <a class="text-color-999"  onclick="AWS.dialog('shareOut', {item_type:'question', item_id:<?php echo $this->question_info['question_id']; ?>});"> -->
									<a class="text-color-999 dropdown-toggle" data-toggle="dropdown">
										<i class="icon icon-share"></i><?php _e('分享'); ?>
									</a>
									<div aria-labelledby="dropdownMenu" role="menu" class="icb-dropdown shareout pull-right">
										<ul class="icb-dropdown-list">
											<li><a onclick="AWS.User.share_out({webid: 'tsina', content: $(this).parents('.icb-question-detail').find('.markitup-box')});"><i class="icon icon-weibo"></i> <?php _e('微博'); ?></a></li>
											<li><a onclick="AWS.User.share_out({webid: 'qzone', content: $(this).parents('.icb-question-detail')});"><i class="icon icon-qzone"></i> <?php _e('QZONE'); ?></a></li>
											<li><a onclick="AWS.User.share_out({webid: 'weixin', content: $(this).parents('.icb-question-detail')});"><i class="icon icon-wechat"></i> <?php _e('微信'); ?></a></li>
										</ul>
									</div>

									<?php if ($this->user_id) { ?><a href="javascript:;" class="text-color-999" onclick="AWS.dialog('report', {item_type:'question', item_id:<?php echo $this->question_info['question_id']; ?>})"><i class="icon icon-report"></i><?php _e('举报'); ?></a><?php } ?>
								</div>
							</div>
						</div>
						<!-- 站内邀请 -->
						<div class="icb-invite-box collapse">
							<div class="mod-head clearfix">
								<div class="search-box pull-left">
									<input id="invite-input" class="form-control" type="text"  placeholder="<?php _e('搜索你想邀请的人...'); ?>"/>
									<div class="icb-dropdown">
										<p class="title"><?php _e('没有找到相关结果'); ?></p>
										<ul class="icb-dropdown-list"></ul>
									</div>
									<i class="icon icon-search"></i>
								</div>
								<div class="invite-list pull-left<?php if (!$this->invite_users) { ?> collapse<?php } ?>">
									<?php _e('已邀请'); ?>:
									<?php if ($this->invite_users) { ?>
									<?php foreach($this->invite_users as $key => $val) { ?>
										<a class="text-color-999 invite-list-user" data-id="<?php echo $val['uid']; ?>" href="user/<?php echo $val['url_token']; ?>" data-id="<?php echo $val['uid']; ?>" data-original-title="<?php echo $val['user_name']; ?>" data-placement="bottom" data-toggle="tooltip"><img src="<?php echo get_avatar_url($val['uid'], 'mid'); ?>" /></a>
									<?php } ?>
									<?php } ?>
								</div>
							</div>
							<?php if ($this->helpful_users) { ?>
							<div class="mod-body clearfix">
								<ul>
									<?php foreach ($this->helpful_users AS $key => $val) { ?>
										<li>
											<a class="icb-user-img pull-left" data-id="<?php echo $val['user_info']['uid']; ?>" href="user/<?php echo $val['user_info']['url_token']; ?>"><img class="img" alt="" src="<?php echo get_avatar_url($val['user_info']['uid'], 'mid'); ?>" /></a>
											<div class="main">
												<?php if ($val['has_invite']) { ?>
													<a class="pull-right btn btn-mini btn-success active" data-value="<?php echo $val['user_info']['user_name']; ?>" data-id="<?php echo $val['user_info']['uid']; ?>" onclick="AWS.User.disinvite_user($(this))"><?php _e('取消邀请'); ?></a>
												<?php } else { ?>
													<a class="pull-right btn btn-mini btn-success" data-value="<?php echo $val['user_info']['user_name']; ?>" data-id="<?php echo $val['user_info']['uid']; ?>" onclick="AWS.User.invite_user($(this),$(this).parents('li').find('img').attr('src'));"><?php _e('邀请'); ?></a>
												<?php } ?>

												<a class="icb-user-name" data-id="<?php echo $val['user_info']['uid']; ?>" href="user/<?php echo $val['user_info']['url_token']; ?>"><?php echo $val['user_info']['user_name']; ?><?php if ($val['user_info']['verified']) { ?><i class="icon-v<?php if ($val['user_info']['verified'] == 'enterprise') { ?> i-ve<?php } ?>" title="<?php if ($val['user_info']['verified'] == 'enterprise') { ?>企业认证<?php } else { ?>个人认证<?php } ?>"></i><?php } ?></a>

												<p>

													<?php _e('在 %s 话题下', '<span class="article-tag"><a class="text" data-id="' . $val['experience']['topic_info']['topic_id'] . '" href="topic/' . $val['experience']['topic_info']['url_token'] . '">' . $val['experience']['topic_info']['topic_title'] . '</a></span>'); ?> <?php _e('获得 %s 个赞同', $val['experience']['agree_count']); ?>
												</p>
											</div>
										</li>
									<?php } ?>
								</ul>
							</div>
							<div class="mod-footer">
								<a class="next pull-right">&gt;</a> <a class="prev active pull-right">&lt;</a>
							</div>
							<?php } ?>
						</div>
						<!-- end 站内邀请 -->
						<!-- 相关链接 -->
						<div class="icb-question-related-box collapse">
							<form action="publish/ajax/save_related_link/" method="post" onsubmit="return false" id="related_link_form">
								<div class="mod-head">
									<h2><?php _e('与内容相关的链接'); ?></h2>
								</div>
								<div class="mod-body clearfix">
									<input type="hidden" name="item_id" value="<?php echo $this->question_info['question_id']; ?>" />
									<input type="text" class="form-control pull-left" name="link" value="" />

									<a onclick="AWS.ajax_post($('#related_link_form'));" class="pull-left btn btn-success"><?php _e('提交'); ?></a>
								</div>
							</form>
						</div>
						<!-- end 相关链接 -->
					</div>

					<?php if ($_GET['column'] == 'log') { ?>
					<div class="icb-mod icb-question-edit">
						<div class="mod-head common-head">
							<h2><span class="pull-right"><a class="text-color-999" href="question/<?php echo $this->question_info['question_id']; ?>"><?php _e('返回问题'); ?> »</a></span><?php _e('问题修改日志'); ?></h2>
						</div>
						<div class="mod-body">
							<ul id="c_log_list"></ul>
						</div>
						<div class="mod-footer">
							<!-- 加载更多内容 -->
							<a class="icb-get-more" id="bp_log_more">
								<span><?php _e('更多'); ?>...</span>
							</a>
							<!-- end 加载更多内容 -->
						</div>
					</div>
					<?php } else { ?>
					<div class="icb-mod icb-question-comment">
						<div class="mod-head">
							<ul class="nav nav-tabs icb-nav-tabs right">
								<li class="nav-tabs-title hidden-xs"><?php if ($_GET['single']) { ?><?php _e('查看单个回答'); ?><?php } else { ?><?php _e('%s 个回复', $this->answer_count); ?><?php } ?></li>
								<?php if ($_GET['single']) { ?>
								<!--<li><a href="question/<?php echo $this->question_info['question_id']; ?>"><?php _e('全部回答'); ?></a></li>-->
								<?php } else if (($this->answer_count OR $_GET['uid']) AND $this->user_id) { ?>
								<li<?php if ((!$_GET['uid'] && !$_GET['sort_key']) || $_GET['sort_key'] == 'agree_count') { ?> class="active"<?php } ?>><a href="question/<?php echo $this->question_info['question_id']; ?>&sort_key=agree_count&sort=DESC"><?php _e('票数'); ?></a></li>
								<li<?php if ($_GET['sort_key'] == 'add_time') { ?> class="active"<?php } ?>><a href="question/<?php echo $this->question_info['question_id']; ?>?sort_key=add_time&sort=<?php if (($_GET['sort_key'] == 'add_time') && $_GET['sort'] == 'ASC') { ?>DESC<?php } else { ?>ASC<?php } ?>"><?php _e('时间'); ?><?php if (($_GET['sort_key'] == 'add_time') && $_GET['sort'] == 'DESC') { ?> <i class="icon icon-down"></i><?php } else { ?> <i class="icon icon-up"></i><?php } ?></a></li>
								<li<?php if ($_GET['uid'] == 'focus') { ?> class="active"<?php } ?>><a href="question/<?php echo $this->question_info['question_id']; ?>?uid=focus"><?php _e('关注的人'); ?></a></li>
								<?php } ?>

							</ul>
						</div>
						<div class="mod-body icb-feed-list">
							<?php if ($this->answers) { foreach ($this->answers AS $key => $val) { ?>
								<div class="icb-item" uninterested_count="<?php echo $val['uninterested_count']; ?>" force_fold="<?php if ($val['user_rated_uninterested']) { ?>1<?php } else { ?><?php echo $val['force_fold']; ?><?php } ?>" id="answer_list_<?php echo $val['answer_id']; ?>">
									<div class="mod-head">
										<?php if ($this->question_info['best_answer'] == $val['answer_id']) { ?>
										<!-- 最佳回答 -->
										<div class="icb-best-answer">
											<i class="icon icon-bestbg"></i>
										</div>
										<!-- end 最佳回答 -->
										<?php } ?>
										<a class="anchor" name="answer_<?php echo $val['answer_id']; ?>"></a>
										<!-- 用户头像 -->
										<?php if ($val['anonymous'] == 0) { ?><a class="icb-user-img icb-border-radius-5" href="user/<?php echo $val['user_info']['url_token']; ?>" data-id="<?php echo $val['uid']; ?>"><img src="<?php echo get_avatar_url($val['uid'], 'mid'); ?>" alt="" /></a><?php } else { ?><a class="icb-user-img icb-border-radius-5" href="javascript:;"><img src="<?php echo G_STATIC_URL; ?>/common/avatar-mid-img.png" alt="<?php _e('匿名用户'); ?>" /></a><?php } ?>
										<!-- end 用户头像 -->
										<div class="title">
											<p>
												<?php if ($val['anonymous'] == 0) { ?>
													<a class="icb-user-name" href="user/<?php echo $val['user_info']['url_token']; ?>" data-id="<?php echo $val['uid']; ?>"><?php echo $val['user_info']['user_name']; ?></a>
												<?php } else { ?>
													<a class="icb-user-name" href="javascript:;"><?php _e('匿名用户'); ?></a>
												<?php } ?>
												<?php if ($val['anonymous'] == 0) { ?>
													<?php if ($val['user_info']['verified']) { ?>
														<i class="icon-v<?php if ($val['user_info']['verified'] == 'enterprise') { ?> i-ve<?php } ?>" title="<?php if ($val['user_info']['verified'] == 'enterprise') { ?>企业认证<?php } else { ?>个人认证<?php } ?>"></i>
													<?php } ?>
													<?php if ($val['user_info']['signature']) { ?> - <span class="text-color-999"><?php echo $val['user_info']['signature']; ?></span><?php } ?>
												<?php } ?>
												<?php if ($val['publish_source'] == 'mobile') { ?>
													<i class="icon icon-phone"></i>
												<?php } else if ($val['publish_source'] == 'weixin') { ?>
													<i class="icon icon-wechat"></i>
												<?php } ?>
											</p>
											<p class="text-color-999 icb-agree-by<?php if (sizeof($val['agree_users']) == 0) { ?> collapse<?php } ?>">
												<?php _e('赞同来自'); ?>:

												<?php if ($val['agree_users']) { ?>
												<?php $count = 0; foreach($val['agree_users'] AS $uid => $user) { ?>
												<?php if ($count > 0) { ?><em<?php if ($count >= 5) { ?> class="collapse"<?php } ?>>、</em><?php } ?><a href="user/<?php echo $user['url_token']; ?>" data-id="<?php echo $user['uid']; ?>" class="icb-user-name<?php if ($count >= 5) { ?> collapse<?php } ?>"><?php echo $user['user_name']; ?></a><?php $count++; } ?><?php } ?><?php if (count($val['agree_users']) > 5) { ?><a href="javascript:;" class="icb-agree-by-show" onclick="$(this).parents('.icb-agree-by').find('em,a').removeClass('collapse'); $(this).remove();"><?php _e('更多'); ?> »</a>
												<?php } ?>
											</p>
										</div>
									</div>
									<div class="mod-body clearfix">
										<!-- 评论内容 -->
										<div class="markitup-box">
											<?php echo $val['answer_content']; ?>
										</div>

										<?php if ($val['attachs']) {  ?>
										<div class="icb-upload-img-list">
										<?php foreach ($val['attachs'] AS $attach) { ?>
										<?php if ($attach['is_image'] AND (!$val['insert_attach_ids'] OR !in_array($attach['id'], $val['insert_attach_ids']))) { ?>
											<a href="<?php echo $attach['attachment']; ?>" target="_blank" data-fancybox-group="thumb" rel="lightbox"><img src="<?php echo $attach['attachment']; ?>" class="img-thumbnail" alt="<?php echo $attach['attach_name']; ?>" /></a>
										<?php } ?>
										<?php } ?>
										</div>
										<?php } ?>

										<?php if ($val['attachs']) {  ?>
										<ul class="icb-upload-file-list">
											<?php foreach ($val['attachs'] AS $attach) { ?>
											<?php if (!$attach['is_image'] AND (!$val['insert_attach_ids'] OR !in_array($attach['id'], $val['insert_attach_ids']))) { ?>
												<li><a href="<?php echo download_url($attach['file_name'], $attach['attachment']); ?>"><i class="icon icon-attach"></i><?php echo $attach['file_name']; ?></a></li>
											<?php } ?>
											<?php } ?>
										</ul>
										<?php } ?>
										<!-- end 评论内容 -->
									</div>
									<div class="mod-footer">
										<!-- 社交操作 -->
										<div class="meta clearfix">
											<span class="text-color-999 pull-right"><?php echo date_friendly($val['add_time'], 604800, 'Y-m-d'); ?></span>
											<!-- 投票栏 -->
											<span class="operate">
												<a class="agree<?php if ($this->user_info['user_name'] == $val['user_info']['user_name']) { ?> disabled<?php } ?> <?php if ($val['agree_status'] == 1) { ?>active<?php } ?> <?php if (!$this->user_id) { ?>disabled<?php } ?>" <?php if ($this->user_id && $this->user_info['user_name'] != $val['user_info']['user_name']) { ?>onclick="AWS.User.agree_vote(this, '<?php echo $this->user_info['user_name']; ?>', <?php echo $val['answer_id']; ?>);"<?php } ?>><i data-placement="right" title="" data-toggle="tooltip" class="icon icon-agree" data-original-title="赞同回复"></i> <b class="count"><?php echo $val['agree_count']; ?></b></a>
												<?php if ($this->user_id AND $this->user_info['user_name'] != $val['user_info']['user_name']) { ?>
												<a class="disagree <?php if ($val['agree_status'] == -1) { ?>active<?php } ?>" onclick="AWS.User.disagree_vote(this, '<?php echo $this->user_info['user_name']; ?>', <?php echo $val['answer_id']; ?>)"><i data-placement="right" title="" data-toggle="tooltip" class="icon icon-disagree" data-original-title="对回复持反对意见"></i></a>
												<?php } ?>
											</span>
											<!-- end 投票栏 -->
											<span class="operate">
												<a class="icb-add-comment" data-id="<?php echo $val['answer_id']; ?>" data-type="answer" data-comment-count="<?php echo $val['comment_count']; ?>" data-first-click="<?php if ($val['comment_count'] == 0) {?>collapse<?php }?>" href="javascript:;"><i class="icon icon-comment"></i> <?php if ($val['comment_count']) { ?><?php echo $val['comment_count']; ?><?php } else { ?>0<?php } ?></a>
											</span>
											<!-- 可显示/隐藏的操作box -->
											<div class="more-operate">
												<?php if ($this->user_id) { ?>
													<?php if (($this->user_info['permission']['is_administortar'] OR $this->user_info['permission']['is_moderator']) OR ($val['uid'] == $this->user_id AND ((TIMESTAMP - $val['add_time']) < (get_setting('answer_edit_time') * 60) OR (!get_setting('answer_edit_time'))))) { ?>
													<a class="text-color-999" href="javascript:;" onclick="AWS.dialog('commentEdit', {answer_id:<?php echo $val['answer_id']; ?>,attach_access_key:'<?php echo $this->attach_access_key; ?>'});"><i class="icon icon-edit"></i> <?php _e('编辑'); ?></a>
													<?php } ?>
													<?php if ($this->user_info['permission']['is_administortar'] OR $this->user_info['permission']['is_moderator']) { ?>
													<a href="javascript:;" onclick="AWS.User.answer_force_fold($(this), <?php echo $val['answer_id']; ?>);" class="text-color-999"><i class="icon icon-fold"></i> <?php if ($val['force_fold']) { ?><?php _e('撤消折叠'); ?><?php } else { ?><?php _e('折叠'); ?><?php } ?></a>
													<?php } else { ?>
													<a class="icb-icon-thank-tips text-color-999" data-original-title="<?php _e('这是一个没有价值的回复'); ?>" data-toggle="tooltip" title="" data-placement="bottom" onclick="AWS.User.answer_user_rate($(this), 'uninterested', <?php echo $val['answer_id']; ?>);"><i class="icon icon-fold"></i><?php if ($val['user_rated_uninterested']) { ?><?php _e('撤消没有帮助'); ?><?php } else { ?><?php _e('没有帮助'); ?><?php } ?></a>
													<?php } ?>

													<a href="javascript:;" onclick="AWS.dialog('favorite', {item_id:<?php echo $val['answer_id']; ?>, item_type:'answer'});" class="text-color-999"><i class="icon icon-favor"></i> <?php _e('收藏'); ?></a>
												<?php } ?>

												<?php if ($this->user_id != $val['uid'] AND $this->user_id) { ?>
												<a href="javascript:;"<?php if (!$val['user_rated_thanks']) { ?> onclick="AWS.User.answer_user_rate($(this), 'thanks', <?php echo $val['answer_id']; ?>);"<?php } ?> class="icb-icon-thank-tips text-color-999" data-original-title="<?php _e('感谢热心的回复者'); ?>" data-toggle="tooltip" title="" data-placement="bottom"><i class="icon icon-thank"></i> <?php if ($val['user_rated_thanks']) { ?><?php _e('已感谢'); ?><?php } else { ?><?php _e('感谢'); ?><?php } ?></a>
												<?php } ?>
												<div class="btn-group pull-left">
													<a class="text-color-999 dropdown-toggle" data-toggle="dropdown">
														<i class="icon icon-share"></i> <?php _e('分享'); ?>
													</a>
													<div aria-labelledby="dropdownMenu" role="menu" class="icb-dropdown shareout pull-right">
														<ul class="icb-dropdown-list">
															<li><a onclick="AWS.User.share_out({webid: 'tsina', title: $(this).parents('.icb-item').find('.markitup-box').text()});"><i class="icon icon-weibo"></i> <?php _e('微博'); ?></a></li>
															<li><a onclick="AWS.User.share_out({webid: 'qzone', title: $(this).parents('.icb-item').find('.markitup-box').text()});"><i class="icon icon-qzone"></i> <?php _e('QZONE'); ?></a></li>
															<li><a onclick="AWS.User.share_out({webid: 'weixin', title: $(this).parents('.icb-item').find('.markitup-box').text()});"><i class="icon icon-wechat"></i> <?php _e('微信'); ?></a></li>
														</ul>
													</div>
												</div>
												<?php if (($this->user_info['permission']['is_moderator'] OR $this->user_info['permission']['is_administortar']) AND !$this->question_info['best_answer']) { ?>
												<a href="javascript:;" onclick="AWS.ajax_request(G_BASE_URL + '/question/ajax/set_best_answer/', 'answer_id=<?php echo $val['answer_id']; ?>');" class="text-color-999"><i class="icon icon-best"></i><?php _e('最佳回复'); ?></a>
												<?php } ?>
											</div>
											<!-- end 可显示/隐藏的操作box -->

										</div>
										<!-- end 社交操作 -->
									</div>
								</div>
								<?php } ?>
							<?php } ?>
						</div>
						<div class="mod-footer">
							<?php if ($_GET['single']) { ?>
								<a href="question/<?php echo $this->question_info['question_id']; ?>" class="icb-get-more">
									<span><?php _e('查看全部回答'); ?></span>
								</a>
							<?php } else { ?>
								<div class="icb-get-more collapse" id="load_uninterested_answers">
									<span class="text-color-999 icb-alert-tip" href="javascript:;" tabindex="-1" onclick="AWS.alert('<?php _e('被折叠的回复是被你或者被大多数用户认为没有帮助的回复'); ?>');"><?php _e('为什么被折叠?'); ?></span>
									<a href="javascript:;" class="icb-get-more"><?php _e('%s 个回复被折叠', '<span class="hide_answers_count">0</span>'); ?></a>
								</div>

								<div class="collapse icb-feed-list" id="uninterested_answers_list"></div>
							<?php } ?>
						</div>

						<?php if ($this->pagination) { ?>
							<div class="clearfix"><?php echo $this->pagination; ?></div>
						<?php } ?>
					</div>
					<?php } ?>
					<!-- end 问题详细模块 -->

					<?php if ($_GET['column'] != 'log') { ?>
					<!-- 回复编辑器 -->
					<div class="icb-mod icb-replay-box question">
						<a name="answer_form"></a>
						<?php if ($this->question_info['lock']) { ?>
						<p align="center"><?php _e('该问题目前已经被锁定, 无法添加新回复'); ?></p>
						<?php } else if (!$this->user_id) { ?>
						<p align="center"><?php _e('要回复问题请先<a href="account/login/">登录</a>或<a href="account/register/">注册</a>'); ?></p>
						<?php } else if ($this->user_answered) { ?>
						<p align="center"><?php _e('一个问题只能回复一次'); ?><?php if (get_setting('answer_edit_time')) { ?>, <?php _e('你可以在发言后 %s 分钟内编辑回复过的内容', get_setting('answer_edit_time')); ?><?php } ?></p>
						<?php } else if ((get_setting('answer_self_question') == 'N') && ($this->user_id == $this->question_info['published_uid'])) { ?>
						<p align="center"><?php _e('不能回复自己发布的问题, 你可以修改问题内容'); ?></p>
						<?php } else { ?>
						<form action="question/ajax/save_answer/" onsubmit="return false;" method="post" id="answer_form" class="question_answer_form">
			        	<input type="hidden" name="post_hash" value="<?php echo new_post_hash(); ?>" />
			        	<input type="hidden" name="question_id" value="<?php echo $this->question_info['question_id']; ?>" />
			        	<input type="hidden" name="attach_access_key" value="<?php echo $this->attach_access_key; ?>" />
						<div class="mod-head">
							<a href="user/" class="icb-user-name"><img alt="<?php echo $this->user_info['user_name']; ?>" src="<?php echo get_avatar_url($this->user_info['uid'], 'mid'); ?>" /></a>
							<p>
								<?php if (get_setting('anonymous_enable') == 'Y') { ?>
								<label class="pull-right">
									<input type="checkbox" value="1" name="anonymous" /> <?php _e('匿名回复'); ?>
								</label>
								<?php } ?>
								<?php if (!$this->question_focus) { ?>
								<label class="pull-right">
									<input type="checkbox" checked="checked" value="1" name="auto_focus" /> <?php _e('关注问题'); ?>
								</label>
								<?php } ?>
								<label class="pull-right">
									<?php if (get_setting('integral_system_enabled') == 'Y') { ?><a href="integral/rule/" target="_blank"><?php _e('积分规则'); ?></a><?php } ?>
								</label>
								<?php echo $this->user_info['user_name']; ?>
							</p>
						</div>
						<div class="mod-body">
							<div class="icb-mod icb-editor-box">
								<div class="mod-head">
									<div class="wmd-panel">
							           <textarea class="article-content form-control autosize editor" id="article-content" rows="15" name="answer_content"><?php echo htmlspecialchars($this->draft_content['message']); ?></textarea>
							        </div>
								</div>
								<div class="mod-body clearfix">
									<?php if ($this->human_valid) { ?>
									<div class="icb-auth-img clearfix">
											<input class="pull-right form-control" type="text" name="seccode_verify" placeholder="<?php _e('验证码'); ?>" />
											<em class="auth-img pull-right"><img src="" onclick="this.src = G_BASE_URL + '/account/captcha/' + Math.floor(Math.random() * 10000);" id="captcha" /></em>
									</div>
									<?php } ?>
									<a href="javascript:;" onclick="AWS.ajax_post($('#answer_form'), AWS.ajax_processer, 'reply_question');" class="btn btn-normal btn-success pull-right btn-reply"><?php _e('回复'); ?></a>
									<span class="pull-right text-color-999" id="answer_content_message">&nbsp;</span>
									<?php if (get_setting('upload_enable') == 'Y') { ?>
									<div class="icb-upload-wrap">
										<a class="btn btn-default">上传附件</a>
										<div class="upload-container"></div>
										<span class="text-color-999 icb-upload-tips hidden-xs"><?php _e('允许'); ?> : <?php echo get_setting('allowed_upload_types'); ?></span>
									</div>
									<?php } ?>
								</div>
							</div>

						</div>
						</form>
						<?php } ?>
					</div>
					<!-- end 回复编辑器 -->
					<?php } ?>
				</div>
				<!-- 侧边栏 -->
				<div class="col-md-3 icb-side-bar hidden-xs hidden-sm">
					<!-- 发起人 -->
					<?php if ($this->question_info['anonymous'] == 0) { ?>
					<div class="icb-mod">
						<div class="mod-head">
							<h3><?php _e('发起人'); ?></h3>
						</div>
						<div class="mod-body">
							<dl>
								<dt class="pull-left icb-border-radius-5">
									<a href="user/<?php echo $this->question_info['user_info']['url_token']; ?>"><img alt="<?php echo $this->question_info['user_info']['user_name']; ?>" src="<?php echo get_avatar_url($this->question_info['published_uid'], 'mid'); ?>" /></a>
								</dt>
								<dd class="pull-left">
									<a class="icb-user-name" href="user/<?php echo $this->question_info['user_info']['url_token']; ?>" data-id="<?php echo $this->question_info['user_info']['uid']; ?>"><?php echo $this->question_info['user_info']['user_name'];?></a>
									<?php if ($this->question_info['user_info']['verified']) { ?>
										<i class="icon-v<?php if ($this->question_info['user_info']['verified'] == 'enterprise') { ?> i-ve<?php } ?>" title="<?php if ($this->question_info['user_info']['verified'] == 'enterprise') { ?>企业认证<?php } else { ?>个人认证<?php } ?>"></i>
									<?php } ?>
									<?php if ($this->question_info['user_info']['uid'] != $this->user_id AND $this->user_id) { ?>
									<a class="icon-inverse follow tooltips icon icon-plus <?php if ($this->user_follow_check) { ?> active<?php } ?>" onclick="AWS.User.follow($(this), 'user', <?php echo $this->question_info['user_info']['uid']; ?>);" data-original-title="<?php if ($this->user_follow_check) { ?><?php _e('取消关注'); ?><?php } else { ?><?php _e('关注'); ?><?php } ?>"></a>
									<?php } ?>
									<p><?php echo $this->question_info['user_info']['signature']; ?></p>
								</dd>
							</dl>
						</div>
					</div>
					<?php } ?>
					<!-- end 发起人 -->

					<?php if ($this->user_id) { ?>
					<!-- 邀请别人回复 -->
					<div class="icb-mod email-invite-replay">
						<div class="mod-head">
							<h3><?php _e('邮件邀请别人回复'); ?></h3>
						</div>
						<div class="mod-body clearfix">
							<!-- 侧边栏邀请box -->
							<form method="post" action="question/ajax/email_invite/question_id-<?php echo $this->question_info['question_id']; ?>" onsubmit="return false;" id="email_invite_form">
								<input class="form-control" type="text" name="email" placeholder="<?php _e('邮件邀请回复...'); ?>"/>
								<a class="pull-right btn btn-mini btn-success" onclick="AWS.ajax_post($('#email_invite_form'));"><?php _e('邀请'); ?></a>
							</form>
							<!-- end 侧边栏邀请box -->
						</div>
					</div>
					<!-- end 邀请别人回复 -->
					<?php } ?>

					<?php if ($this->recommend_posts) { ?>
					<!-- 推荐内容 -->
					<div class="icb-mod">
						<div class="mod-head">
							<h3><?php _e('推荐内容'); ?></h3>
						</div>
						<div class="mod-body font-size-12">
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
					<div class="icb-mod">
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

					<!-- 问题状态 -->
					<div class="icb-mod question-status">
						<div class="mod-head">
							<h3><?php _e('问题状态'); ?></h3>
						</div>
						<div class="mod-body">
							<ul>
								<li><?php _e('最新活动'); ?>: <span class="icb-text-color-blue"><?php echo date_friendly($this->question_info['update_time']); ?></span></li>
								<li><?php _e('浏览'); ?>: <span class="icb-text-color-blue"><?php echo $this->question_info['view_count']; ?></span></li>
								<li><?php _e('关注'); ?>: <span class="icb-text-color-blue"><?php echo $this->question_info['focus_count']; ?></span> <?php _e('人'); ?></li>

								<li class="icb-border-radius-5" id="focus_users"></li>
							</ul>
						</div>
					</div>
					<!-- end 问题状态 -->
				</div>
				<!-- end 侧边栏 -->
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	var ATTACH_ACCESS_KEY = '<?php echo $this->attach_access_key; ?>';
	var ITEM_IDS = '<?php echo addslashes($_GET['item_id']); ?>';
	var COMMENT_UNFOLD = '<?php echo addslashes($_GET['comment_unfold']); ?>';
	var QUESTION_ID = <?php echo $this->question_info['question_id'];?>;
	var UNINTERESTED_COUNT = <?php echo get_setting('uninterested_fold'); ?>;
	var ANSWER_EDIT_TIME = <?php echo get_setting('answer_edit_time'); ?>;
	var USER_ANSWERED = '<?php echo $this->user_answered; ?>';
	var UPLOAD_ENABLE = '<?php echo get_setting('upload_enable') ?>';
	var ANSWER_TYPE = 'answer';
</script>

<script type="text/javascript" src="<?php echo G_STATIC_URL; ?>/js/app/question_detail.js"></script>

<?php View::output('global/footer.php'); ?>
