<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
            <h3>
                <ul class="nav nav-tabs">
                    <li class="active"><a><?php _e('责编列表'); ?></a>
                    </li>
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
                                <th><?php _e('主科'); ?></th>
                                <th><?php _e('副科'); ?></th>
                                <th><?php _e('操作'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php  foreach ($this->itemsList AS $itemInfo) { ?>
                            <tr >
                                <td><!--<input type="checkbox" name="ids[]" value="<?php echo $itemInfo['uid']; ?>">--></td>
                                <td class="text-left">

                                    <a class="md-tip"  title="" data-toggle="tooltip"><?php echo $itemInfo['user_name']; ?></a>
                                </td>
                                <td><?php echo $this->groupList[$itemInfo['group_id']]['group_name']; ?></td>
                                <td><?php echo SinhoBaseController::SUBJECT_LIST[$this->groupList[$itemInfo['group_id']]['permission']['sinho_subject']]['name']; ?></td>
                                <td><?php if ($this->moreSubjects[$itemInfo['uid']]) echo join('、', $this->moreSubjects[$itemInfo['uid']]); else echo '-'; ?></td>

                                <td>
                                  <a href="admin/administration/editor_edit/id-<?php echo $itemInfo['uid']; ?>.html" class="icon icon-edit md-tip" title="<?php _e('编辑'); ?>" data-toggle="tooltip"></a>
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

        </div>
    </div>
</div>

<script>
$(function(){
});
</script>

<?php View::output('admin/global/footer.php'); ?>
