<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
            <h3>
            <?php View::output('admin/course/nav_inc.php');?>
            </h3>
        </div>

        <div class="mod-body tab-content">
            <div class="tab-pane active" id="course">
                <?php if ($_GET['action'] == 'search') { ?>
                <div class="alert alert-info"><?php _e('找到 %s 条符合条件的内容', intval($this->topics_count)); ?></div>
                <?php } ?>

                <div class="table-responsive">

					<form action="admin/ajax/course_save/" method="post" id="item_form" onsubmit="return false;">
						<input type="hidden" name="post_hash" value="<?php echo new_post_hash(); ?>" />
						<input type="hidden" name="batchKey" value="<?php echo $this->batchKey; ?>" />
						<input type="hidden" name="id" id="article_id" value="<?php echo $this->article_info['id']; ?>" />
						<?php if ($this->itemOptions) { ?>
							<select name="category_id" class="collapse js_category_id" id="category_id">
								<option value="0">- <?php _e('请选择分类'); ?> -</option>
								<?php echo $this->itemOptions; ?>
							</select>
							<?php } ?>
						<div class="icb-mod icb-mod-publish">
							<!-- 缩略图 -->
							<h3 class="icb-label"><?php _e('广告图'); ?>:</h3>
							<div class="icb-item-title">
								<a id="thumb_pic_uploader"><img src="<?php echo getModulePicUrlBySize('course', null, $this->article_info['pic']); ?>" alt="" id="thumb_pic" class="img-polaroid" name="thumb_pic" /></a>
							</div>
							<!-- end 文章别名 -->
							<!-- 文章标题 -->
							<h3 class="icb-label"><?php _e('标题'); ?>:</h3>
							<div class="icb-item-title icb-title-with-select<?php if (!$this->topicsList) { ?> active<?php } ?>">
								<input type="text" name="title" value="<?php echo $this->article_info['title']; ?>" class="form-control" />
								<?php if ($this->itemOptions) { ?>
								<div class="dropdown">
									<div class="dropdown-toggle" data-toggle="dropdown">
										<span id="icb-selected-tag-show"><?php _e('选择分类'); ?></span>
										<a><i class="icon icon-down"></i></a>
									</div>
								</div>
								<?php } ?>
							</div>
							<!-- end 文章标题 -->
							<!-- 文章副标题 -->
							<h3 class="icb-label"><?php _e('副标题'); ?>:</h3>
							<div class="icb-item-title">
								<input type="text" name="title2" value="<?php echo $this->article_info['title2']; ?>" class="form-control" />
							</div>
							<!-- end 文章副标题 -->
							<!-- 文章别名 -->
							<h3 class="icb-label"><?php _e('别名'); ?>:</h3>
							<div class="icb-item-title">
								<input type="text" name="url_token" value="<?php echo $this->article_info['url_token']; ?>" class="form-control" />
							</div>
							<!-- end 文章别名 -->
							<!-- 文章Meta关键字 -->
							<h3 class="icb-label"><?php _e('Meta关键字'); ?>:</h3>
							<div class="icb-item-title">
								<input type="text" name="meta_keyword" value="<?php echo $this->article_info['url_token']; ?>" class="form-control" />
							</div>
							<!-- end 文章Meta关键字 -->
							<?php View::output('block/article_editor.php');?>
							<div class="mod-footer clearfix">
								<?php if ($this->article_info['id'] AND ($this->user_info['permission']['is_administortar'] OR $this->user_info['permission']['is_moderator'])) { ?>
								<a class="btn btn-large btn-danger" id="deleteBatchBtn" onclick="deleteItem(<?php echo $this->article_info['id'];?>); return false;"><?php _e('删除教程'); ?></a>
								<?php } ?>
								<a class="btn btn-large btn-success btn-publish-submit" id="publish_submit" onclick="ICB.ajax.postForm($('#item_form')); return false;"><?php _e('保存教程'); ?></a>
							</div>
						</div>
					</form>

                </div>
            </div>
            <?php View::output('admin/course/search_inc.php');?>
        </div>
    </div>
</div>
<script>

function deleteItem(id)
{
    ICB.domEvents.deleteShowConfirmModal(
  	   _t('确认删除教程？'),
  	   function(){
      	   var url = G_BASE_URL + '/admin/ajax/course_remove/',
      	       params = {'ids[]':id, 'action':'remove', '_post_type':'ajax'};
  		   ICB.ajax.requestJson(
  	      	   url,
  	      	   params,
  	      	   function (response) {
      	      		if (!response) {
      	      		    return false;
	      	      	}

	      	      	if (response.err) {
	      	      		ICB.modal.alert(response.err);
	      	      	} else if (response.errno == 1) {
	      	      	    ICB.modal.alert(_t('已删除'), {'hidden.bs.modal': function () {
		      	      		    window.location.href = G_BASE_URL + '/admin/course/list/';
		      	      	    }
	      	      	    });
	      	      	} else {
	      	      	    ICB.modal.alert(_t('请求发生错误'));
	      	      	}
  	      	   }
  	       );
	       }
    );
}
$(function () {

	if ($('#thumb_pic_uploader').length){
		var url = G_BASE_URL + '/course/ajax/upload_attach/id-course__batchKey-<?php echo $this->batchKey;?>__type-course_banner';
		var url = G_BASE_URL + '/admin/ajax/upload_temp/module-course';
		new FileUploader(
				$('#thumb_pic_uploader'),
				$('#thumb_pic'),
				url,
				{
					'uploadingModalSelector' : '#avatar_uploading_status',
					'showUploadImage': true,
					fileName:'upload_file'
				},
				function (result) {
				    $('#thumb_pic_uploader').find('#item_banner_id, #item_banner_path').remove();
				    /*
				    $('#thumb_pic_uploader').append($('<input/>').attr({
					    type : 'hidden',
					    name : 'banner_id',
					    id   : 'item_banner_id',
					    value: result.temp_id

				    }));
				    */
				    $('#thumb_pic_uploader').append($('<input/>').attr({
					    type : 'hidden',
					    name : 'banner_path',
					    id   : 'item_banner_path',
					    value: result.file
				    }));
				}
		);
	}
	// 初始化编辑器
	//var editor = CKEDITOR.replace( 'article-content');
	// 允许HTML
    //var editor = CKEDITOR.replace( 'article-content', { allowedContent: true});
    var editor = CKEDITOR.replace( 'article-content');

	// 绑定上传
	var editorSetting = {'editor' : editor };

	var fileupload = new FileUploader(
	    	'.icb-editor-box .icb-upload-wrap .btn',
	    	'.icb-editor-box .icb-upload-wrap .upload-container',
	    	G_BASE_URL + '/course/ajax/upload_attach/id-course__batchKey-<?php echo $this->batchKey;?>',
	    	editorSetting
	    );
    <?php if (isset($_GET['id'])) { ?>
    if ($(".icb-upload-wrap .upload-list").length) {
        ICB.utils.loadAttachListFromAjax (G_BASE_URL + '/course/ajax/get_course_attach_list/', 'id=' + <?php echo $_GET['id'];?>, fileupload);
    }
    <?php } ?>

    if ($('#icb-article-tag-box .tag-queue-box .article-tag').length == 0) {
        $('#icb-article-tag-box .icb-edit-tag').click();
    }

});
</script>
<?php View::output('admin/global/footer.php'); ?>
