<?php if ($this->list) { ?>
<?php if ($_GET['page'] == 0 AND count($this->list) > 0) { ?>
<a href="javascript:;" class="icb-get-more warmming collapse" onclick="reload_list(0);" id="new_actions_tip"><span><?php _e('%s 条新动态, 点击查看', '<em id="new_action_num"></em>'); ?></span></a>
<?php } ?>
<?php foreach ($this->list AS $key => $val) { ?>
<div class="icb-item" data-history-id="<?php echo $val['history_id']; ?>">
	<div class="mod-head">
		<?php if ($val['question_info']['anonymous'] == 0 OR !$val['question_info']) { ?><a data-id="<?php echo $val['user_info']['uid']; ?>" class="icb-user-img icb-border-radius-5" href="user/<?php echo $val['user_info']['url_token']; ?>"><img src="<?php echo get_avatar_url($val['user_info']['uid'], 'mid'); ?>" alt="<?php echo $val['user_info']['user_name']; ?>" /><?php if ($val['user_info']['verified']) { ?><?php if ($val['user_info']['verified'] == 'personal') { ?><i class="icon icon-v"></i><?php } else { ?><i class="icon icon-v i-ve"></i><?php } ?><?php } ?></a><?php } else { ?><a href="javascript:;" class="icb-user-img icb-border-radius-5"><img src="<?php echo G_STATIC_URL; ?>/common/avatar-mid-img.png" alt="<?php _e('匿名用户'); ?>" /></a><?php } ?>
		<p class="text-color-999">
			<?php if ($val['last_action_str']) { ?><?php echo $val['last_action_str']; ?> • <?php echo date_friendly($val['add_time']); ?> • <?php } ?>

			<?php if (isset($val['article_info']['comments'])) { ?>
				<a href="<?php echo $val['link']; ?>" class="text-color-999"><?php _e('%s 个评论', $val['article_info']['comments']); ?></a>
			<?php } else { ?>
				<a href="<?php echo $val['link']; ?>" class="text-color-999"><?php _e('%s 个回复', $val['question_info']['answer_count']); ?></a>
					<?php if ($this->user_id AND !$_GET['filter']) { ?>
				</a>
				<?php } ?>
				<?php if ($_GET['filter'] == 'focus') { ?>
					 • <?php if ($val['topics']) { ?><?php _e('已添加到'); ?>
					<?php foreach($val['topics'] as $t_key => $t_val) { if ($t_key > 2) { break; } ?><a href="topic/<?php echo $t_val['url_token']; ?>" class="icb-topic-name" data-id="<?php echo $t_val['topic_id']; ?>"><?php echo $t_val['topic_title']; ?></a> <?php } ?><?php if (sizeof($val['topics']) > 3) { ?> <?php _e('等'); ?> <?php _e('%s 个话题', sizeof($val['topics'])); ?><?php } else { ?><?php _e('话题'); ?><?php } ?>
					<?php } else { ?><a href="question/<?php echo $val['question_info']['question_id']; ?>" class="text-color-999"><?php _e('添加话题'); ?></a><?php } ?>
				<?php } else if (!$_GET['filter']) { ?><span class="pull-right collapse"><a class="text-color-999" href="javascript:;" onClick="AWS.User.question_uninterested($(this).parents('div.icb-item'), <?php echo $val['question_info']['question_id']; ?>);"><?php _e('不感兴趣'); ?></a></span><?php } ?>
			<?php } ?>
		</p>
		<h4><a href="<?php echo $val['link']; ?>"><?php echo $val['title']; ?></a></h4>
		<p class="text-color-999 icb-agree-by<?php if (count($val['answer_info']['agree_users']) == 0) { ?> collapse<?php } ?>">
			<?php _e('赞同来自'); ?>:
			<?php if ($val['answer_info']['agree_users']) { $count = 0; ?>
			<?php foreach ($val['answer_info']['agree_users'] as $uid => $user) { ?>
				<?php if ($count > 0) { ?><em<?php if ($count >= 5) { ?> class="collapse"<?php } ?>>、</em><?php } ?><a href="user/<?php echo $user['url_token']; ?>" data-id="<?php echo $user['uid']; ?>" class="icb-user-name<?php if ($count >= 5) { ?> collapse<?php } ?>"><?php echo $user['user_name']; ?></a>
			<?php $count++; } ?>
			<?php if (count($val['answer_info']['agree_users']) >= 5) { ?><a href="javascript:;" class="icb-agree-by-show" onclick="$(this).parents('.icb-agree-by').find('em,a').removeClass('collapse'); $(this).remove();"><?php _e('更多'); ?> »</a><?php } ?>
			<?php } ?>
		</p>
	</div>
	<?php if ($val['comment_info']) { ?>
	<div class="mod-body clearfix">
		<!-- 文章评论内容 -->
		<div id="detail_<?php echo $val['history_id']; ?>" class="markitup-box">
      		<?php echo nl2br(cjk_substr(strip_ubb($val['comment_info']['message']), 0, 130, 'UTF-8', '...')); ?>
      		<?php if (cjk_strlen($val['answer_info']['answer_content']) > 130) { ?>
        	<a href="javascript:;" class="showMore" onclick="AWS.content_switcher($('#detail_<?php echo $val['history_id']; ?>'), $('#detail_more_<?php echo $val['history_id']; ?>'));"><?php _e('显示全部'); ?> »</a>
        	<?php } ?>
		</div>
		<div id="detail_more_<?php echo $val['history_id']; ?>" class="collapse markitup-box">
      		<?php echo nl2br(strip_ubb($val['comment_info']['message'])); ?>
		</div>
		<!-- end 文章评论内容 -->
	</div>
	<?php } else if ($val['answer_info']) { ?>
	<div class="mod-body clearfix">
		<!-- 评论内容 -->
		<div id="detail_<?php echo $val['history_id']; ?>" class="markitup-box">
      		<?php echo nl2br(cjk_substr(strip_ubb($val['answer_info']['answer_content']), 0, 130, 'UTF-8', '...')); ?>
      		<?php if (cjk_strlen($val['answer_info']['answer_content']) > 130) { ?>
        	<a href="javascript:;" class="showMore" onclick="AWS.content_switcher($('#detail_<?php echo $val['history_id']; ?>'), $('#detail_more_<?php echo $val['history_id']; ?>'));"><?php _e('显示全部'); ?> »</a>
        	<?php } ?>

			<?php if ($val['answer_info']['attachs']) { ?>
				<div class="icb-upload-img-list width-auto">
					<?php foreach($val['answer_info']['attachs'] AS $t_key => $t_val) { ?>
					<?php if ($t_val['is_image']): ?>
						<a href="javascript:;" onclick="AWS.content_switcher($('#detail_<?php echo $val['history_id']; ?>'), $('#detail_more_<?php echo $val['history_id']; ?>'));">
							<img class="img-thumbnail" src="<?php echo $t_val[thumb]; ?>" alt="<?php echo $t_val['file_name']; ?>" />
						</a>
					<?php endif; ?>
				  <?php } ?>
				</div>

				<div class="icb-upload-file-list">
					<?php foreach($val['answer_info']['attachs'] AS $t_key => $t_val) { ?>
					<?php if (!$t_val['is_image']): ?>
						<li><a href="<?php echo download_url($t_val['file_name'], $t_val['attachment']); ?>"><em class="icb-icon i-upload-file"></em><?php echo $t_val['file_name']; ?></a></li>
					<?php endif; ?>
				  <?php } ?>
				</div>
			<?php } ?>
		</div>
		<div id="detail_more_<?php echo $val['history_id']; ?>" class="collapse markitup-box">
      		<?php echo nl2br(strip_ubb($val['answer_info']['answer_content'])); ?>

			<?php if ($val['answer_info']['attachs']) { ?>
				<div class="icb-upload-img-list active">
					<?php foreach($val['answer_info']['attachs'] AS $t_key => $t_val) { ?>
					<?php if ($t_val['is_image']): ?>
						<a href="<?php echo $t_val['attachment']; ?>" target="lightbox" data-fancybox-group="thumb" rel="lightbox"><img class="img-thumbnail" src="<?php echo $t_val['attachment']; ?>" alt="" /></a>
					<?php endif; ?>
				  <?php } ?>
				</div>

				<div class="icb-upload-file-list">
					<?php foreach($val['answer_info']['attachs'] AS $t_key => $t_val) { ?>
					<?php if (!$t_val['is_image']): ?>
						<li><a href="<?php echo download_url($t_val['file_name'], $t_val['attachment']); ?>"><em class="icb-icon i-upload-file"></em><?php echo $t_val['file_name']; ?></a></li>
					<?php endif; ?>
				  <?php } ?>
				</div>
			<?php } ?>
		</div>
		<!-- end 评论内容 -->
	</div>
	<div class="mod-footer">
		<!-- 社交操作 -->
		<div class="meta clearfix">
			<!-- 投票栏 -->
			<span class="operate">
				<a class="agree <?php if ($this->user_id == $val['answer_info']['uid']) { ?> disabled<?php } ?> <?php if ($val['answer_info']['agree_status'] == 1) { ?>active<?php } ?>" <?php if ($this->user_id != $val['answer_info']['uid']) { ?>onclick="AWS.User.agree_vote(this, '<?php echo $this->user_info['user_name']; ?>', <?php echo $val['answer_info']['answer_id']; ?>);"<?php } ?>><i data-original-title="赞同回复" class="icon icon-agree" data-toggle="tooltip" title="" data-placement="right"></i> <b class="count"><?php echo $val['answer_info']['agree_count']; ?></b></a>
				<?php if ($this->user_id != $val['answer_info']['uid']) { ?>
				<a class="disagree <?php if ($val['answer_info']['agree_status'] == -1) { ?>active<?php } ?>" onclick="AWS.User.disagree_vote(this, '<?php echo $this->user_info['user_name']; ?>', <?php echo $val['answer_info']['answer_id']; ?>)"><i data-original-title="对回复持反对意见" class="icon icon-disagree" data-toggle="tooltip" title="" data-placement="right"></i></a>
				<?php } ?>
			</span>
			<!-- end 投票栏 -->
			<span class="operate">
				<a class="icb-add-comment text-color-999" href="javascript:;" data-id="<?php echo $val['answer_info']['answer_id']; ?>" data-type="answer"><i class="icon icon-comment"></i><?php echo $val['answer_info']['comment_count']; ?></a>
			</span>
			<span class="pull-right more-operate">
				<?php if ($this->user_id != $val['uid']) { ?>
				<a href="javascript:;"<?php if (!$val['answer_info']['user_rated_thanks']) { ?> onclick="AWS.User.answer_user_rate($(this), 'thanks', <?php echo $val['answer_info']['answer_id']; ?>);"<?php } ?> class="icb-icon-thank-tips text-color-999" data-original-title="<?php _e('感谢热心的回复者'); ?>" data-toggle="tooltip" title="" data-placement="bottom"><i class="icon icon-thank"></i><?php if ($val['answer_info']['user_rated_thanks']) { ?><?php _e('已感谢'); ?><?php } else { ?><?php _e('感谢'); ?><?php } ?></a>
				<?php } ?>
				<a href="javascript:;" onclick="AWS.dialog('favorite', {item_id:<?php echo $val['answer_info']['answer_id']; ?>, item_type:'answer'});" class="text-color-999"><i class="icon icon-favor"></i><?php _e('收藏'); ?></a>
				<a class="text-color-999 dropdown-toggle" data-toggle="dropdown">
					<i class="icon icon-share"></i><?php _e('分享'); ?>
				</a>
				<div aria-labelledby="dropdownMenu" role="menu" class="icb-dropdown shareout pull-right">
					<ul class="icb-dropdown-list">
                    	<li><a onclick="AWS.User.share_out({webid: 'tsina', title: $(this).parents('.icb-item').find('.markitup-box').eq(0).text(), url: '<?php echo $val['link']; ?>', content: $(this).parents('.icb-question-detail').find('.markitup-box')});"><i class="icon icon-weibo"></i> <?php _e('微博'); ?></a></li>
						<li><a onclick="AWS.User.share_out({webid: 'qzone', title:$(this).parents('.icb-item').find('.markitup-box').eq(0).text(), url: '<?php echo $val['link']; ?>',  content: $(this).parents('.icb-question-detail')});"><i class="icon icon-qzone"></i> <?php _e('QZONE'); ?></a></li>
						<li><a onclick="AWS.User.share_out({webid: 'weixin', title:$(this).parents('.icb-item').find('.markitup-box').eq(0).text(), url: '<?php echo $val['link']; ?>', content: $(this).parents('.icb-question-detail')});"><i class="icon icon-wechat"></i> <?php _e('微信'); ?></a></li>
					</ul>
				</div>
			</span>
		</div>
		<!-- end 社交操作 -->
	</div>
	<?php } ?>
</div>
<?php } ?>
<?php } ?>