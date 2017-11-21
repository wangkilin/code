<?php View::output('m/header.php'); ?>

<!-- 标题 -->
<div class="icb-title">
	<?php _e('发起问题'); ?>
</div>
<!-- end 标题 -->

<!-- 内容 -->
<div class="container active">
	<form action="publish/ajax/<?php if ($this->question_info['question_id']) { ?>modify<?php } else { ?>publish<?php } ?>_question/" method="post" id="question_form" onsubmit="return false;">
		<input type="hidden" name="post_hash" value="<?php echo new_post_hash(); ?>" />
		<input type="hidden" name="question_id" id="question_id" value="<?php echo $this->question_info['question_id']; ?>" />
		<input type="hidden" name="ask_user_id" value="<?php echo $_POST['ask_user_id']; ?>" />
		<input type="hidden" name="attach_access_key" value="<?php echo $this->attach_access_key; ?>" />

		<?php if ($this->weixin_media_id) { ?>
		<input type="hidden" name="weixin_media_id" value="<?php echo $this->weixin_media_id; ?>" />
		<input type="hidden" name="weixin_pic_url" value="<?php echo $this->weixin_pic_url; ?>" />
		<?php } ?>
		<div class="icb-publish-box">
			<!-- 问题标题 -->
			<div class="title">
				<input type="text" class="form-control" placeholder="<?php _e('输入问题'); ?>" name="question_content" value="<?php echo $this->question_info['question_content']; ?>" />
			</div>
			<!-- end 问题标题 -->

			<!-- 问题补充 -->
			<div class="content">
				<textarea name="question_detail" class="form-control autosize" placeholder="<?php _e('问题详细补充（选填）'); ?>" id="publish_detail" cols="30" rows="4"><?php echo $this->question_info['question_detail']; ?></textarea>
			</div>
			<!-- end 问题补充 -->

			<!-- 上传控件 -->
			<div class="icb-upload-wrap">
				<a class="icon icon-pic">
					<span class="tips">上传图片</span>
				</a>
				<div class="upload-container"></div>
			</div>

			<!-- end 上传控件 -->

			<!-- 分类 -->
			<?php if ($this->question_category_list) { ?>
			<div class="category clearfix">
				<select name="category_id" id="category_id">
					<option value="0">- <?php _e('请选择分类'); ?> -</option>
					<?php echo $this->question_category_list; ?>
				</select>
				<i class="icon icon-down"></i>
			</div>
			<?php } ?>
			<!-- end 分类 -->

			<!-- 话题bar -->
			<?php if ($this->user_id AND !$this->question_info['question_id']) { ?>
			<div class="icb-article-title-box clearfix" data-type="publish">
				<div class="tag-queue-box">
					<a class="icon icon-inverse icb-add-topic-box"><i class="icon icon-edit"></i></a>
				</div>
			</div>
			<?php } ?>
			<!-- end 话题bar -->

			<?php if ($this->human_valid) { ?>
			<div class="icb-auth-img clearfix">
				<input class="form-control pull-left" type="text" name="seccode_verify" placeholder="验证码" />
				<em class="auth-img pull-left"><img src="" onclick="this.src = G_BASE_URL + '/account/captcha/' + Math.floor(Math.random() * 10000);" id="captcha"  /></em>
			</div>
			<?php } ?>

			<div class="command">
				<?php if (get_setting('anonymous_enable') == 'Y') { ?>
				<label class="pull-left"><input type="checkbox"  value="1" name="anonymous" /> <?php _e('匿名'); ?></label>
				<?php } ?>
				<?php if ($this->question_info['question_id'] AND ($this->user_info['permission']['is_administortar'] OR $this->user_info['permission']['is_moderator'])) { ?>
				<!-- <label class="pull-left"><input type="checkbox"  value="1" name="do_delete" /> <?php _e('删除问题'); ?></label> -->
				<?php } ?>

				<a class="btn btn-success btn-slarge" onclick="AWS.ajax_post($('#question_form')); return false;"><?php _e('发起问题'); ?></a>
			</div>

		</div>
	</form>

</div>
<!-- end 内容 -->

<script type="text/javascript">
	var CATEGORY_ID = <?php echo $this->question_info['category_id']; ?>;
	var ATTACH_ACCESS_KEY = '<?php echo $this->attach_access_key; ?>';
	var PUBLISH_TYPE = 'question';

	$(document).ready(function () {

		$('.icb-add-topic-box').click();

		// 判断是否微信打开
		if (typeof window.WeixinJSBridge != 'undefined')
		{
			$('header, nav, footer').hide();
		}

		if (ATTACH_ACCESS_KEY != '')
		{
			var fileupload = new FileUpload('file' ,'.icb-upload-wrap .icon-pic', '.icb-upload-wrap .upload-container', G_BASE_URL + '/publish/ajax/attach_upload/id-question__attach_access_key' + '-' + ATTACH_ACCESS_KEY, {'insertTextarea' : '#publish_detail', 'deleteBtnTemplate' : '<a class="delete-file"><i class="icon icon-delete"></i></a>', 'insertBtnTemplate' : '<a class="insert-file"><i class="icon icon-insert"></i></a>', 'editor': $('#publish_detail')});
		}

		if ($('#category_id').length)
		{
			var category_data = '';

			$.each($('#category_id option').toArray(), function (i, field) {
				if (i > 0)
				{
					if (i > 1)
					{
						category_data += ',';
					}

					category_data += "{'title':'" + $(field).text() + "', 'id':'" + $(field).val() + "'}";
				}
			});

			//AWS.Dropdown.add_dropdown_list('.icb-publish-dropdown', eval('[' + category_data + ']'), CATEGORY_ID);

			$('.icb-publish-dropdown .dropdown-menu li a').click(function() {
				$('#category_id').val($(this).attr('data-value'));
			});
		}

		if ($('.icb-publish-dropdown').length)
		{
			$.each($('.icb-publish-dropdown .dropdown-menu li a'),function(i, e)
			{
				if ($(e).attr('data-value') == $('#category_id').val())
				{
					$('.icb-publish-dropdown span').html($(e).html());

					return;
				}
			});
		}

		if ($('#quick_publish_topic_chooser').length)
		{
			$('#quick_publish_topic_chooser').click();
		}

		if ($('#question_id').length)
		{
			ITEM_ID = $('#question_id').val();
		}
		else if ($('#article_id').length)
		{
			ITEM_ID = $('#article_id').val();
		}

		// 微信图片发起
		if ($('input[name="weixin_pic_url"]').val() != undefined)
		{
			$('.upload-list').append(
				'<li>'+
					'<div class="img" style="background-image: url(' + $('input[name="weixin_pic_url"]').val() + ');"></div>'+
					'<div class="content">'+
						'<p class="meta">'+
							'<span class="color-999">图片已插入</span>'+
							'<a class="insert-file disabled"><i class="icon icon-insert"></i></a>'+
						'</p>'+
					'</div>'+
				'</li>');
		}

		if (ITEM_ID && G_UPLOAD_ENABLE == 'Y' && ATTACH_ACCESS_KEY != '')
		{
			if ($(".icb-upload-wrap .upload-list").length) {
				$.post(G_BASE_URL + '/publish/ajax/' + PUBLISH_TYPE + '_attach_edit_list/', PUBLISH_TYPE + '_id=' + ITEM_ID, function (data) {
					if (data['err']) {
						return false;
					} else {
						$.each(data['rsm']['attachs'], function (i, v) {
						   fileupload.setFileList(v);
						});
					}
				}, 'json');
			}
		}

	});
</script>

<?php View::output('m/footer.php'); ?>
