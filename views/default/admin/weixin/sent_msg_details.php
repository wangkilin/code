<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap" id="msg_details">
    <div class="mod">
        <div class="mod-head">
            <h3>
                <span class="pull-left"><?php _e('查看群发消息'); ?></span>
            </h3>
        </div>

        <div class="tab-content mod-content">
        <table class="table table-striped">
            <tr>
                <td>
                    <div class="form-group">
                        <span class="col-sm-4 col-xs-3 control-label"><?php _e('ID'); ?>:</span>
                        <div class="col-sm-5 col-xs-8">
                            <?php echo $this->msg_details['id']; ?>
                        </div>
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="form-group">
                        <span class="col-sm-4 col-xs-3 control-label"><?php _e('消息 ID'); ?>:</span>
                        <div class="col-sm-5 col-xs-8">
                            <?php echo $this->msg_details['msg_id']; ?>
                        </div>
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="form-group">
                        <span class="col-sm-4 col-xs-3 control-label"><?php _e('目标分组'); ?>:</span>
                        <div class="col-sm-5 col-xs-8">
                            <?php echo $this->msg_details['group_name']; ?>
                        </div>
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="form-group">
                        <span class="col-sm-4 col-xs-3 control-label"><?php _e('状态'); ?>:</span>
                        <div class="col-sm-5 col-xs-8">
                            <?php switch ($this->msg_details['status']) {
                                  case 'unsent':
                                      _e('未发送');
                                      break;

                                  case 'pending':
                                      _e('提交成功');
                                      break;

                                  case 'error':
                                      _e('提交失败');
                                      break;

                                  case 'success':
                                      _e('发送成功');
                                      break;

                                  case 'fail':
                                      _e('发送失败');
                                      break;

                                  case 'wrong':
                                      _e('审核失败');
                                      break;

                                  default:
                                      _e('未知');
                                      break;
                              } ?>
                        </div>
                    </div>
                </td>
            </tr>

            <?php if ($this->msg_details['status'] == 'wrong') { ?>
            <tr>
                <td>
                    <div class="form-group">
                        <span class="col-sm-4 col-xs-3 control-label"><?php _e('审核失败的原因'); ?>:</span>
                        <div class="col-sm-5 col-xs-8">
                            <?php switch ($this->msg_details['error_num']) {
                                 case '10001':
                                     _e('涉嫌广告');
                                     break;

                                 case '20001':
                                     _e('涉嫌政治');
                                     break;

                                 case '20004':
                                     _e('涉嫌社会');
                                     break;

                                 case '20002':
                                     _e('涉嫌色情');
                                     break;

                                 case '20006':
                                     _e('涉嫌违法犯罪');
                                     break;

                                 case '20008':
                                     _e('涉嫌欺诈');
                                     break;

                                 case '20013':
                                     _e('涉嫌版权');
                                     break;

                                 case '22000':
                                     _e('涉嫌互推(互相宣传)');
                                     break;

                                 case '21000':
                                     _e('涉嫌其他');
                                     break;

                                 default:
                                     _e('未知原因');
                                     break;
                             } ?>
                        </div>
                    </div>
                </td>
            </tr>
            <?php } ?>

            <?php if ($this->msg_details['main_msg']) { ?>
            <tr>
                <td>
                    <div class="form-group">
                        <span class="col-sm-4 col-xs-3 control-label"><?php _e('群发封面'); ?>:</span>
                        <div class="col-sm-5 col-xs-8">
                            <a href="<?php echo $this->msg_details['main_msg']['url']; ?>" target="_blank"><?php echo $this->msg_details['main_msg']['title']; ?></a>
                        </div>
                    </div>
                </td>
            </tr>
            <?php } ?>

            <?php if ($this->msg_details['articles_info']) { ?>
            <tr>
                <td>
                    <div class="form-group msg_arc">
                        <span class="col-sm-4 col-xs-3 control-label"><?php _e('群发的文章'); ?>:</span>
                        <div class="col-sm-5 col-xs-8">
                          <ul>
                            <?php foreach ($this->msg_details['articles_info'] AS $article_info) { ?>
                            <li>
                              <i class="icon icon-file"></i>
                              <a href="article/<?php echo $article_info['id']; ?>" target="_blank"><?php echo $article_info['title']; ?></a>
                              <?php } ?>
                            </li>
                          </ul>
                        </div>
                    </div>
                </td>
            </tr>
            <?php } ?>

            <?php if ($this->msg_details['questions_info']) { ?>
            <tr>
                <td>
                    <div class="form-group msg-que">
                        <span class="col-sm-4 col-xs-3 control-label"><?php _e('群发的问题'); ?>:</span>
                        <div class="col-sm-6 col-xs-8">
                          <ul>
                            <?php foreach ($this->msg_details['questions_info'] AS $question_info) { ?>
                              <li>
                                <i class="icon icon-help"></i>
                                <a href="question/<?php echo $question_info['id']; ?>" target="_blank"><?php echo $question_info['title']; ?></a>
                              </li>
                            <?php } ?>
                          </ul>
                        </div>
                    </div>
                </td>
            </tr>
            <?php } ?>

            <tr>
                <td>
                    <div class="form-group">
                        <span class="col-sm-4 col-xs-3 control-label"><?php _e('创建时间'); ?>:</span>
                        <div class="col-sm-6 col-xs-8">
                            <?php echo date_friendly($this->msg_details['create_time']); ?>
                        </div>
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="form-group">
                        <span class="col-sm-4 col-xs-3 control-label"><?php _e('用户数'); ?>:</span>
                        <div class="col-sm-6 col-xs-8">
                            <?php echo $this->msg_details['filter_count']; ?>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
        </div>
    </div>
</div>

<?php View::output('admin/global/footer.php'); ?>