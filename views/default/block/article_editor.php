<!-- 文章内容 -->
<h3 class="icb-label"><?php _e('内容'); ?>:</h3>
<div class="icb-mod icb-editor-box">
	<div class="mod-head">
		<div class="wmd-panel">
			<textarea class="article-content form-control autosize editor" id="article-content" rows="15" name="content"><?php echo $this->article_info['content']; ?></textarea>
        </div>
	</div>
	<div class="mod-body">
		<p class="text-color-999"><span class="pull-right" id="question_detail_message">&nbsp;</span></p>
		<?php if (get_setting('upload_enable') == 'Y' AND get_setting('advanced_editor_enable' == 'Y')) { ?>
		<div class="icb-upload-wrap">
			<a class="btn btn-default">上传附件</a>
			<div class="upload-container"></div>
			<span class="text-color-999 icb-upload-tips hidden-xs"><?php _e('允许的附件文件类型'); ?>: <?php echo get_setting('allowed_upload_types'); ?></span>
		</div>
		<?php } ?>
	</div>
</div>
<!-- end 文章内容 -->
<!-- 文章标签 -->
<h3 class="icb-label"><?php _e('加入标签'); ?>:</h3>
<div id="icb-article-tag-box" data-type="publish">
	<div class="tag-queue-box clearfix">
		<?php if ($_GET['topic_title']) { ?>
		<span class="article-tag">
			<a class="text"><?php echo urldecode($_GET['topic_title']); ?></a>
			<a class="close" onclick="$(this).parents('.article-tag').remove();">
				<i class="icon icon-delete"></i>
			</a>
			<button class="close icb-close" onclick="">×</button></span><input type="hidden" value="<?php echo urldecode($_GET['topic_title']); ?>" name="topics[]" />
		</span>
		<?php } ?>
		<?php if ($_POST['tag_names']) { ?>
		<?php foreach ($_POST['tag_names'] AS $val) { ?>
		<span class="article-tag">
			<a class="text"><?php echo $val; ?></a>
			<i class="icon icon-delete close" onclick="$(this).closest('.article-tag').remove();"></i>
			<input type="hidden" value="<?php echo $val; ?>" name="tag_names[]" />
		</span>
		<?php } ?>
		<?php } else if ($this->bindTopics) { ?>
		<?php foreach ($this->bindTopics AS $val) { ?>
		<span class="article-tag">
			<a class="text"><?php echo $val['topic_title']; ?></a>
			<i class="icon icon-delete close" onclick="$(this).closest('.article-tag').remove();"></i>
			<input type="hidden" value="<?php echo $val['topic_title']; ?>" name="tag_names[]" />
		</span>
		<?php } ?>
		<?php } ?>
		<span class="icb-edit-tag">
		  <i class="icon icon-edit"></i> <?php _e('编辑标签');?>
		</span>

	</div>
	<div class="icb-search-tag-box form-inline">
	  <input class="form-control width-half" id="icb-tag-category-keyword"
	      autocomplete="off" placeholder="创建或搜索添加新话题..." type="text"
	      data-dropdown-url="<?php echo base_url() .'/'.rtrim(G_INDEX_SCRIPT, '/'); ?>/topic/ajax/tag_list/?key={{search}}&limit=10"/>
	  <a class="btn btn-normal btn-success add">添加</a>
	  <a class="btn btn-normal btn-gray close-add-tag">取消</a>
	  <div class="icb-dropdown col-sm-6">
		  <p class="title">没有找到相关结果</p>
		  <ul class="icb-dropdown-list"></ul>
	  </div>
	</div>
</div>
<!-- end 文章标签 -->

<?php if ($this->recent_topics) { ?>
<h3><?php _e('最近标签'); ?>:</h3>
<div class="icb-article-tag-box">
	<div class="topic-bar clearfix">
		<?php foreach($this->recent_topics as $key => $val) { ?>
		<span class="article-tag" onclick="$('#icb-tag-category-keyword').val('<?php echo $val; ?>');$('.add').click();$(this).hide();">
			<a class="text">
				<?php echo $val; ?>
			</a>
		</span>
		<?php } ?>
	</div>
</div>
<?php } ?>

<?php if ($this->human_valid) { ?>
<div class="icb-auth-img clearfix">
	<em class="auth-img pull-right"><img src="" onclick="this.src = G_BASE_URL + '/account/captcha/' + Math.floor(Math.random() * 10000);" id="captcha"  /></em>
	<input class="pull-right form-control" type="text" name="seccode_verify" placeholder="验证码" />
</div>
<?php } ?>
<script>
$(function () {
	// 添加更多标签
	ICB.domEvents.editArticleTagButtonClick('#icb-article-tag-box .icb-edit-tag');
});
</script>