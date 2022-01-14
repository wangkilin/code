
            <div class="tab-pane" id="search">

                <form method="post" action="admin/books/index/" onsubmit="return false;" id="search_form" class="form-horizontal" role="form">
                    <input name="action" id="input_action_name" type="hidden" value="search" />

                    <div class="form-group">
                        <label class="col-sm-2 col-xs-3 control-label"><?php _e('年份'); ?>:</label>

                        <div class="col-sm-5 col-xs-8">
                            <select name="book_belong_year" class="form-control" id="book_belong_year">
                                <option value="0">- <?php _e('请选择年份'); ?> -</option>
                                <?php foreach ($this->bookBelongYears as $_key => $_valueInfo) {?>
                                <option value="<?php echo $_key;?>" <?php
                                    echo  $_GET['book_belong_year']==$_key ? 'selected':'' ?>><?php echo $_valueInfo['long'];?></option>
                                <?php }?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-xs-3 control-label"><?php _e('类别'); ?>:</label>

                        <div class="col-sm-5 col-xs-8">
                            <input class="form-control" type="text" value="<?php echo rawurldecode($_GET['category']); ?>" name="category" placeholder="类别关键字"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-xs-3 control-label"><?php _e('系列'); ?>:</label>

                        <div class="col-sm-5 col-xs-8">
                            <input class="form-control" type="text" value="<?php echo rawurldecode($_GET['serial']); ?>" name="serial" placeholder="系列关键字"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-xs-3 control-label"><?php _e('书名'); ?>:</label>

                        <div class="col-sm-5 col-xs-8">
                            <input class="form-control" type="text" value="<?php echo rawurldecode($_GET['book_name']); ?>" name="book_name"  placeholder="书名关键字"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-xs-3 control-label"><?php _e('校次'); ?>:</label>

                        <div class="col-sm-5 col-xs-8">
                            <input class="form-control" type="text" value="<?php echo rawurldecode($_GET['proofreading_times']); ?>" name="proofreading_times"  placeholder="校次关键字"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 col-xs-3 control-label"><?php _e('发稿时间'); ?>:</label>

                        <div class="col-sm-6 col-xs-9">
                            <div class="row">
                                <div class="col-xs-11  col-sm-5 mod-double icon-date-container">
                                    <input type="text" class="form-control mod-data js-date-input" value="<?php echo base64_decode($_GET['start_date']); ?>" name="start_date" autocomplete="off" placeholder="开始日期"/>
                                    <i class="icon icon-date"></i>
                                    <i class="icon icon-date-delete icon-delete"></i>
                                </div>
                                <span class="mod-symbol col-xs-1 col-sm-1">
                                -
                                </span>
                                <div class="col-xs-11 col-sm-5 icon-date-container">
                                    <input type="text" class="form-control mod-data js-date-input" value="<?php echo base64_decode($_GET['end_date']); ?>" name="end_date"  autocomplete="off" placeholder="结束日期"/>
                                    <i class="icon icon-date"></i>
                                    <i class="icon icon-date-delete icon-delete"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-xs-3 control-label"><?php _e('所属阶段'); ?>:</label>

                        <div class="col-sm-5 col-xs-8">
                            <select id="sinho_grade_level" multiple name="grade_level[]">
                               <option value="1" <?php echo in_array(1, $_GET['grade_level']) ? 'selected' : ''; ?>>小学</option>
                               <option value="2" <?php echo in_array(2, $_GET['grade_level']) ? 'selected' : ''; ?>>初中</option>
                               <option value="3" <?php echo in_array(3, $_GET['grade_level']) ? 'selected' : ''; ?>>高中</option>
                               <option value="4" <?php echo in_array(4, $_GET['grade_level']) ? 'selected' : ''; ?>>外社</option>
                               <option value="5" <?php echo in_array(5, $_GET['grade_level']) ? 'selected' : ''; ?>>综合</option>

                               <option value="0" <?php echo in_array(0, $_GET['grade_level']) ? 'selected' : ''; ?>>其他</option>
                            </select>
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-5 col-xs-8">
                            <button type="button" onclick="$('#input_action_name').val('export');ICB.ajax.postForm($('#search_form'));" class="btn btn-info"><?php _e('导出'); ?></button>
                            <button type="button" onclick="$('#input_action_name').val('search');ICB.ajax.postForm($('#search_form'));" class="btn btn-primary col-sm-offset-1"><?php _e('搜索'); ?></button>
                        </div>
                    </div>
                </form>
            </div>

<script type="text/javascript">
$(function () {

    /**
     * 日期输入框， 点击清除图标，将输入框内容清除
     */
    $('.icon-delete.icon-date-delete').click (function () {
        $(this).siblings('.js-date-input').val('');
    });


    $("#sinho_grade_level").multiselect({
        			nonSelectedText : '<?php _e('---- 选择书稿所属阶段 ----');?>',
                    maxHeight       : 200,
                    buttonWidth     : 400,
                    allSelectedText : '<?php _e('已选择全部');?>',
                    numberDisplayed : 7, // 选择框最多提示选择多少个人名
    });
});
</script>
