<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
            <h3>
                <ul class="nav nav-tabs">
                    <li class="active js-onload-click"><a href="#index"data-toggle="tab"><?php _e('责编列表'); ?></a></li>
                    <li><a href="#add" data-toggle="tab"><?php _e('添加用户'); ?></a></li>
                </ul>
            </h3>
        </div>

        <div class="mod-body tab-content">
            <div class="tab-pane active" id="index">

                <?php if ($_GET['action'] == 'search') { ?>
                <div class="alert alert-info"><?php _e('找到 %s 条符合条件的内容', intval($this->totalRows)); ?></div>
                <?php } ?>

                <div class="table-responsive">
                <form id="batchs_form" action="admin/ajax/books/remove/" method="post">
                    <input type="hidden" id="action" name="action" value="" />
                <?php if ($this->itemsList) { ?>

                    <table class="table table-striped table-hover book-list">
                        <thead>
                            <tr>
                                <th><!--<input type="checkbox" class="check-all">--></th>
                                <th class="text-left"><?php _e('责编'); ?></th>
                                <th><?php _e('属组'); ?></th>
                                <th><?php _e('组长?'); ?></th>
                                <th><?php _e('主科'); ?></th>
                                <th><?php _e('副科/跨学科'); ?></th>
                                <th><?php _e('学科管理员'); ?></th>
                                <th><?php _e('上次登录'); ?></th>
                                <th><?php _e('注册时间'); ?></th>
                                <th><?php _e('操作'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php  foreach ($this->itemsList AS $itemInfo) { ?>
                            <tr class="<?php if ($itemInfo['forbidden']){ echo 'text-danger';} ?>">
                                <td><!--<input type="checkbox" name="ids[]" value="<?php echo $itemInfo['uid']; ?>">--></td>
                                <td class="text-left">

                                    <a class="md-tip <?php if ($itemInfo['forbidden']){ echo 'text-danger';} ?>"  title="" data-toggle="tooltip"><?php echo $itemInfo['user_name']; ?></a>
                                </td>
                                <td><?php echo $this->groupList[$itemInfo['group_id']]['group_name']; ?></td>
                                <td><?php if($this->userAttributes[$itemInfo['uid']]['sinho_permission_team_leader']) echo '是'; else echo '-'; ?></td>
                                <td><?php echo SinhoBaseController::SUBJECT_LIST[$this->groupList[$itemInfo['group_id']]['permission']['sinho_subject']]['name']; ?></td>
                                <td><?php if ($this->userAttributes[$itemInfo['uid']]['sinho_more_subject']) echo join('、', $this->userAttributes[$itemInfo['uid']]['sinho_more_subject']); else echo '-'; ?></td>
                                <td><?php if ($this->userAttributes[$itemInfo['uid']]['sinho_manage_subject']) echo join('、', $this->userAttributes[$itemInfo['uid']]['sinho_manage_subject']); else echo '-'; ?></td>
                                <td><?php echo date_friendly($itemInfo['last_login']); ?></td>
                                <td><?php echo date_friendly($itemInfo['reg_time']); ?></td>

                                <td>
                                  <a href="admin/administration/editor_edit/id-<?php echo $itemInfo['uid']; ?>.html" class="icon icon-edit md-tip" title="<?php _e('编辑'); ?>" data-toggle="tooltip"></a>
                                  <?php if ($itemInfo['uid'] != $this->user_id) {
                                  ?><a href="javascript:;" onclick="ICB.ajax.requestJson(G_BASE_URL + '/admin/ajax/administration/forbidden_user/' , 'uid=<?php echo $itemInfo['uid']; ?>&status=<?php echo intval($itemInfo['forbidden']) ? 0 : 1; ?>');" title="<?php if ($itemInfo['forbidden']) { ?><?php _e('解除封禁，恢复登录'); ?><?php } else { ?><?php _e('封禁用户，禁止登录'); ?><?php } ?>" class="icon <?php if ($itemInfo['forbidden']) { ?>icon-unlock <?php } else { ?>icon-lock<?php } ?> md-tip"><?php
                                  } ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>
                </form>
                </div>

                <div class="mod-table-foot">
                    <?php echo $this->pagination; ?>
                </div>

            </div>

            <?php View::output('admin/administration/add_user.php'); ?>
        </div>
    </div>
</div>

<script>
$(function(){
    //$('.js-onload-click').trigger('click');
});
</script>

<?php View::output('admin/global/footer.php'); ?>
