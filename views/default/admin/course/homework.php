<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="tab-content mod-content">
        <div class="row">
            <div class="col-md-8">
                <form action="admin/ajax/save_homework_question/id-<?php echo $this->item['id']; ?>" method="post" id="item_list_form" onsubmit="return false">
                    <div class="mod icb-message-box ">
                    <div class="mod-set-head">
                        <a class="btn btn-primary btn-xs pull-right" onclick="ICB.ajax.postForm($('#item_list_form'));"><?php _e('保存设置'); ?></a>
                        <a class="btn btn-default btn-xs pull-right" onclick="addHomeworkItem();"><?php _e('添加作业'); ?></a>
                        <h3>
                            <?php _e('课后作业'); ?>
                            <span><?php _e('拖动可调整排序； 点击展开详细内容'); ?></span>
                        </h3>
                    </div>
                    <div class="mod-set-body">
                    <input type="hidden" name="item_sort" id="item_sort">

                    <div class="icb-item-info">
                    <ul data-listidx="0" id="homework_question_list">
                        <?php if ($this->itemList) { ?>
                        <?php foreach($this->itemList as $val) { ?>
                        <li data-sort="<?php echo $val['id']; ?>" data-item-id="<?php echo $val['id']; ?>" data-cursor="pointer">
                            <div class="mod-set-head <?php echo $val['parent_id'] ? 'item-intent' : 'item-no-intent' ?>">
                                <span class="pull-right">
                                  <a href="javascript:;" onclick="ICB.modal.confirm('<?php _e('确认删除?'); ?>', function(){ICB.ajax.requestJson(G_BASE_URL + '/admin/ajax/remove_homework_question/', 'id=<?php echo $val['id']; ?>');}); event ? event.stopPropagation():null;return false;"><?php _e('删除'); ?></a>
                                </span>
                                <h4><?php _e('课后作业习题') ?></h4>
                            </div>
                            <div class="mod-set-body clearfix">
                                <div class="icb-item-info-tag clearfix">
                                    <label class="pull-left">
                                        <?php _e('语音'); ?><br />
                                    </label>
                                    <div class="pull-left icb-item-info-img">
                                        <a id="icb-item-info-img-upload-<?php echo $val['id']; ?>" class="js-upload">
                                        <input class="js-attach_id" name="homework[<?php echo $val['id']; ?>][attach_id]" type="hidden" value="<?php echo $val['attach_id']; ?>"/>
                                        <img src="<?php
                                        echo G_STATIC_URL; ?>/css/default/img/default_class_imgs.png" alt="" class="js-show"/>
                                        </a>
                                    </div>
                                </div>

                                <div class="icb-item-info-descrip">
                                    <label class="pull-left">
                                        <?php _e('文字'); ?><br />
                                    </label>
                                    <textarea class="form-control col-sm-12" type="text" name="homework[<?php echo $val['id']; ?>][content]" ><?php echo $val['content']; ?></textarea>
                                </div>
                            </div>
                        </li>
                        <?php } ?>
                        <?php } ?>
                    </ul>
                    </div>
                    </div>
                </div>
                </form>

        </div>

       <div class="col-md-4">
            <div class="mod">
                <div class="icb-message-box topic">
                    <div class="mod-set-head">
                        <h3><?php _e('文章'); ?></h3>
                    </div>
                    <div class="mod-set-body">
                        <h3><?php echo $this->item['title']; ?></h3>
                    </div>
                </div>
            </div>
        </div>
      </div>
   </div>
</div>

<script type="text/javascript">

/**
 * 点击添加课后作业条目
 */
function addHomeworkItem () {
    var indexMax = 0;
    $(".icb-item-info ul:first li.js-new-homework").each(function(){
        indexMax = indexMax > +$(this).attr('data-index') ? indexMax : +$(this).attr('data-index');
    });
    var itemHtml = Hogan.compile(ICB.template.homeworkItemBox).render({
        'index' : indexMax + 1
    });

    $(".icb-item-info").find('ul:first').append(itemHtml);
    // 绑定上传文件插件
    bindUpload(0, $(".icb-item-info").find('ul:first li:last'));
    // 将最后一个条目激活
    $(".icb-item-info").find('ul:first li:last .mod-set-head').trigger('click');
}
var uploadUrl = 	G_BASE_URL + '/course/ajax/upload_attach/id-<?php echo $this->item['id']; ?>__type-homework__batchKey-<?php echo $this->batchKey;?>';
/**
 * 上传文件后的回调函数， 将上传的音频显示出来
 */
function showAudio (info) {
    if (typeof info != 'object' || info.class_name != 'audio') {
        return;
    }
    var $domAudio = $('<audio/>').attr( {
        'controls'  : 'controls',
        'attach-id' : info.attach_id,
        'src'       : info.url
    });
    var $domContainer = $('<div/>').attr({
        'class' : '_item_container'
    });
    $(this.element).closest('li').find('.js-attach_id').val(info.attach_id);
    $(this.element).parent().hide().after($domContainer.append($domAudio));
}
/**
 * 绑定上传插件
 */
function bindUpload (index, dom) {
	var $bindElement = $(dom).find('.js-upload');
	var $showElement = $(dom).find('.js-show');
    var fileupload = new FileUploader(
    $bindElement,
    $showElement,
	    	uploadUrl,
	    	{},
	    	showAudio);
}
$(document).ready(function () {
    $('.icb-item-info li .mod-set-head:first').trigger('click');

    $('#homework_question_list li').each (bindUpload);
});
</script>

<?php View::output('admin/global/footer.php'); ?>