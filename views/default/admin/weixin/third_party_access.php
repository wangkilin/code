<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
    <div class="mod">
        <div class="mod-head">
            <h3>
                <span class="pull-left"><?php _e('第三方接入'); ?></span>
            </h3>

            <?php if (count($this->accounts_list) > 1) { ?>
            <div class="dropdown pull-right">
                <a class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
                    <?php if ($this->account_id == 0) { _e('主账号'); } else { _e('子账号 %s', $this->account_id); } ?>
                    <span class="caret"></span>
                </a>

                <ul class="dropdown-menu weixin-dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                    <?php foreach ($this->accounts_list AS $account_info) {
                        if ($account_info['id'] == $this->account_id) { continue; } ?>
                    <li role="presentation">
                        <a role="menuitem" tabindex="-1" href="admin/weixin/third_party_access/id-<?php echo $account_info['id']; ?>"><?php if ($account_info['id'] == 0) { _e('主账号'); } else { _e('子账号 %s', $account_info['id']); } ?></a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
            <?php } ?>
        </div>

        <div class="tab-content mod-body">
            <div class="alert alert-success collapse error_message"></div>

            <div class="table-responsive">
                <form action="admin/ajax/weixin/save_third_party_access_rule_status/" method="post" id="rule_list_form" onsubmit="return false">
                <table class="table table-striped">
                    <?php if ($this->rule_list) { ?>
                    <thead>
                    <tr>
                        <th><?php _e('启用'); ?></th>
                        <th>URL</th>
                        <th>Token</th>
                        <th><?php _e('排序'); ?></th>
                        <th><?php _e('操作'); ?></th>
                    </tr>
                    </thead>

                    <tbody>
                    <input type="hidden" name="account_id" value="<?php echo $this->account_id; ?>" />

                    <?php foreach ($this->rule_list AS $rule_info) { ?>
                    <input type="hidden" name="rule_ids[]" value="<?php echo $rule_info['id']; ?>" />
                    <tr>
                        <td><input type="checkbox" class="enabled-status" name="enabled[<?php echo $rule_info['id']; ?>]" value="1"<?php if ($rule_info['enabled']) { ?> checked="checked"<?php } ?> /></td>
                        <td><?php echo $rule_info['url']; ?></td>
                        <td><?php echo $rule_info['token']; ?></td>
                        <td>
                            <input type="text" class="form-control sort-action sort-status input-mini" name="rank[<?php echo $rule_info['id']; ?>]" value="<?php echo $rule_info['rank']; ?>" />
                        </td>
                        <td class="nowrap">
                            <a href="admin/weixin/edit_third_party_access_rule/id-<?php echo $rule_info['id']; ?>" data-toggle="tooltip" class="icon icon-edit md-tip" title="<?php _e('编辑'); ?>"></a>
                            <a onclick="AWS.ajax_request(G_BASE_URL + '/admin/ajax/weixin/remove_third_party_access_rule/', 'id=<?php echo $rule_info['id']; ?>');" data-toggle="tooltip" class="icon icon-trash md-tip" title="<?php _e('删除'); ?>"></a>
                        </td>
                    </tr>
                    <?php } ?>
                    </tbody>
                    <?php } ?>
                </table>
                </form>
                <div class="mod-table-foot mod-one-btn">
                    <a class="btn btn-primary" href="admin/weixin/edit_third_party_access_rule/account_id-<?php echo $this->account_id; ?>" id="batch_approval"><?php _e('添加规则'); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('input.enabled-status, input.flag-action').on('ifChanged', function () {
            if ($(this).val() != '')
            {
                AWS.ajax_post($('#rule_list_form'), AWS.ajax_processer, 'error_message');
            }
        });

        $('input.sort-action').keyup(function () {
            if ($(this).val() != '')
            {
                AWS.ajax_post($('#rule_list_form'), AWS.ajax_processer, 'error_message');
            }
        });
    });
</script>

<?php View::output('admin/global/footer.php'); ?>