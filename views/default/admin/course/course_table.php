<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
            <h3>
            <?php View::output('admin/course/nav_inc.php');?>
            </h3>
        </div>
	    <div class="tab-content mod-content mod-body">
	      <div class="tab-pane active" id="course_table">
	        <?php //View::output('admin/course/tag_list_inc.php');?>
            <?php View::output('admin/course/category_list_inc.php');?>
            <div class="row">
                <div class="col-sm-12 form-group icb-item-title" data-active="toggle">
                    <form action="admin/course/content_table/" method="post" class="form-horizontal">
                        <input type="hidden" name="load_table" value="1"/>
                        <label class="pull-left control-label"><?php _e('所属教程');?>:</label>
                        <div class="col-sm-7">
                                <select id="table_id" name="table_id" class="hidden js_select_transform">
                                    <option value="0"><?php _e('所属教程'); ?></option>
                                    <?php echo $this->tableOptions; ?>
                                </select>
                                <div class="dropdown js-submit-choose">
                                    <div class="dropdown-toggle" data-toggle="dropdown">
                                        <span id="icb-selected-tag-show"><?php _e('所属教程'); ?></span>
                                        <a><i class="icon icon-down"></i></a>
                                    </div>
                                </div>
                        </div>
                    </form>
                </div>
            </div>
	        <?php if ($this->parent_id) { ?>
	        <div class="row" id="js_course_table_list">
	            <div class="col-md-12">
		            <div class="col-md-7 nopadding">
		                <form action="admin/ajax/save_content_table/" method="post" id="item_list" onsubmit="return false">
		                    <div class="mod icb-message-box ">
		                    <div class="mod-set-head">
		                        <a class="btn btn-primary btn-xs pull-right" onclick="ICB.ajax.postForm($('#item_list'));"><?php _e('保存设置'); ?></a>
		                        <h3>
		                            <span><?php _e('拖动可调整章节的排序'); ?></span>
		                        </h3>
		                    </div>
		                    <div class="mod-set-body">
		                    <input type="hidden" name="item_sort" id="item_sort">

		                    <div class="icb-item-info">
		                    <ul data-listidx="0">
		                        <?php if ($this->contentTable) { ?>
		                        <?php foreach($this->contentTable as $key => $val) { ?>
		                        <?php if ($val['title']) { ?>
		                        <li data-sort="<?php echo $val['id']; ?>" data-cursor="pointer" class="<?php
		                                 echo 'icb-course-' . $val['from_type'];
		                                 echo $val['parent_id'] ? ' item-intent' : ' item-no-intent'; ?>">
		                            <div class="mod-set-head">
		                                <span class="pull-right">
		                                  <a href="javascript:;"  class="js-change-level">
		                                    <i class="icon-up">升级</i>
		                                    <i class="icon-down">降级</i>
		                                    <input class="form-control js-is-child" type="hidden" name="item[<?php echo $val['id']; ?>][is_child]" value="<?php echo intval($val['parent_id']>0); ?>" />
		                                    <input class="form-control" type="hidden" name="item[<?php echo $val['id']; ?>][from_type]" value="<?php echo $val['from_type']; ?>" />
		                                  </a>
		                                  <a href="javascript:;" onclick="window.event && window.event.stopPropagation(); ICB.domEvents.deleteShowConfirmModal('<?php _e('确认删除?'); ?>', function(){ICB.ajax.requestJson(G_BASE_URL + '/admin/ajax/remove_content_table/', 'id=<?php echo $val['id']; ?>');}); return false;"><?php _e('删除'); ?></a>
		                                </span>
		                                <h4><?php
		                                switch ($val['from_type']) {
                                            case 'course':
                                                echo '<a target="_blank" href="'.base_url().'/course/',$this->parentItemsList[$val['category_id']]['url_token'], '/', $this->list[$val['article_id']]['url_token'], '">', $val['title'], '</a> (';
		                                		_e('教程');
		                                		break;
		                                	case 'link':
		                                		echo '<a target="_blank" href="',$val['link'],'">',$val['title'],'</a> (';
		                                		_e('教程链接');
		                                		break;
                                            case 'chapter':
                                                echo $val['title'];
                                                echo ' (';
		                                		_e('章节列表');
		                                		break;
                                        }
                                        echo ')';
		                                ?></h4>
		                            </div>
		                            <div class="mod-set-body clearfix">
		                                <div class="clearfix">
		                                    <label class="pull-left col-sm-12 nopadding">
		                                        <?php _e('标题'); ?><br />
		                                        <input class="form-control" type="text" name="item[<?php echo $val['id']; ?>][title]" value="<?php echo $val['title']; ?>" />
		                                    </label>
		                                </div>

		                                <?php if ($val['from_type'] != 'course') { ?>
		                                <div class="">
		                                    <label class="pull-left col-sm-12 nopadding">
		                                        <?php _e('链接'); ?><br />
		                                        <input class="form-control" type="text" name="item[<?php echo $val['id']; ?>][link]" value="<?php echo $val['link']; ?>" />
		                                    </label>
		                                </div>
		                                <?php } ?>

		                                <div class="">
		                                    <label class="pull-left col-sm-12 nopadding">
		                                        <?php _e('描述'); ?><br />
		                                        <input class="form-control" type="text" name="item[<?php echo $val['id']; ?>][description]" value="<?php echo $val['description']; ?>" />
		                                    </label>
		                                </div>

		                            </div>
		                        </li>
		                        <?php } ?>
		                        <?php } ?>
		                        <?php } ?>
		                    </ul>
		                    </div>
		                    </div>
		                </div>
		                </form>
		            </div>

		            <div class="col-md-5">
			            <div class="mod">
			                <div class="icb-message-box topic">
			                    <div class="mod-set-head">
			                        <h3>教程文章</h3>
			                    </div>
			                    <div class="mod-set-body">
			                        <form action="admin/ajax/add_content_table/" method="post">
			                            <input type="hidden" name="from_type" value="course"/>
			                            <!-- <input type="hidden" name="topic_id" value="<?php echo $this->parent_id;?>"/> -->
			                            <input type="hidden" name="category_id" value="<?php echo $this->categoryId;?>"/>
			                            <input type="hidden" name="table_id" value="<?php echo $this->table_id;?>"/>
			                            <input type="hidden" name="title" value=""/>
			                            <select name="course_id" class="form-control pull-left input-small js-course-list">
			                                <option value="0"><?php _e('无'); ?></option>
			                                <?php foreach ($this->list as $_item) {?>
			                                <option value="<?php echo $_item['id'];?>"><?php echo $_item['title']?></option>
			                                <?php }?>
			                            </select>
			                            <a onclick="ICB.ajax.postForm($(this).closest('form'));" class="btn btn-primary"><?php _e('添加至教程目录'); ?></a>
			                        </form>
			                    </div>
			                </div>
			            </div>

			            <div class="mod">
			                <div class="icb-message-box define-link">
			                    <div class="mod-set-head">
			                        <h3><?php _e('自定义'); ?></h3>
			                    </div>
			                    <div class="mod-set-body">
			                        <form action="admin/ajax/add_content_table/" method="post">
			                            <input type="hidden" name="from_type" value="custom">
			                            <!-- <input type="hidden" name="topic_id" value="<?php echo $this->parent_id;?>"/> -->
			                            <input type="hidden" name="category_id" value="<?php echo $this->categoryId;?>"/>
			                            <input type="hidden" name="table_id" value="<?php echo $this->table_id;?>"/>

			                            <p class="clearfix">
			                                <label type="button" class="btn mod-btn-color col-sm-offset-0">
		                                        <input type="radio" name="custom_type" value="link" checked="checked" /> <?php _e('教程链接'); ?>
		                                    </label>
		                                    <label type="button" class="btn mod-btn-color">
		                                        <input type="radio" name="custom_type" value="chapter" /> <?php _e('章节列表'); ?>
		                                    </label>
		                                </p>
		                                 <p><?php _e('标题')?><br />
			                                <input class="form-control" type="text" name="title">
			                            </p>
			                            <p><?php _e('描述')?><br />
			                                <input class="form-control" type="text" name="description">
			                            </p>
			                            <p><?php _e('链接')?><br />
			                                <input class="form-control" type="text" name="link" value="http://">
			                            </p>

			                            <a onclick="ICB.ajax.postForm($(this).closest('form'));" class="btn btn-primary"><?php _e('添加至教程目录'); ?></a>
			                        </form>
			                    </div>
			                </div>
			            </div>


		        	</div>
	        	</div>
	        </div>
	        <?php } ?>
	      </div>
          <?php View::output('admin/course/search_inc.php');?>
	    </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        /*
        // 选择分类后， 将对应模块选定。 如果是根分类， 需要选择所属的模块
        $('#parent_id').change(function () {
            var moduleId = $(this).find('option:selected').attr('data-module');
            $('#module_id').val(moduleId);
            if (moduleId!='0') {
                $('#module_id').attr('disabled', 'disabled');
            } else {
                $('#module_id').removeAttr('disabled');
            }
        });
         */
        // $('#parent_id').attr('disabled', 'disabled');
        // $('#module_id').change(function () {
        //     var moduleId = $(this).val();
        //     if (moduleId>0) {
        //         $('#parent_id').find('option[data-module="'+moduleId+'"]').show();
        //         $('#parent_id').find('option[data-module!="'+moduleId+'"]').hide();
        //         $('#parent_id').find('option[data-module="0"]').show();
        //         $('#parent_id').removeAttr('disabled');
        //     } else {
        //         $('#parent_id').attr('disabled', 'disabled');
        //     }
        //     var moduleToken = $(this).find('option[value="'+moduleId+'"]').attr('data-token');
        //     if (! moduleToken) {
        //         moduleToken = 'index';
        //     }
        //     $('#js_url_module_name').text(moduleToken);
        // });
        // $('#module_id').trigger('change');
    });
</script>
<?php View::output('admin/global/footer.php'); ?>
