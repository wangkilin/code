<?php View::output('global/header_meta.php'); ?>

<div class="icb-register-box">
    <div class="mod-head">
        <a href=""><img src="<?php echo G_STATIC_URL; ?>/css/<?php echo $this->template_name; ?>/img/login_logo.png" alt="" /></a>
        <h1><?php _e('注册新用户'); ?></h1>
    </div>
    <div class="mod-body">
        <form class="icb-register-form" action="account/ajax/register_process/" method="post" id="register_form">
            <?php if ($this->icode) { ?><input type="hidden" name="icode" id="icode" value="<?php echo $this->icode; ?>" /><?php } ?>
            <?php if ($this->return_url) { ?><input type="hidden" name="return_url" value="<?php echo $this->return_url; ?>" /><?php } ?>

            <ul>
                <li class="alert alert-danger collapse error_message text-left">
                    <i class="icon icon-delete"></i> <em></em>
                </li>
                <li>
                    <input class="icb-register-name form-control" type="text" name="user_name" placeholder="<?php _e('用户名'); ?>" tips="<?php _e('请输入一个 2-14 位的用户名');?>" errortips="<?php _e('用户名长度不符合');?>" value="" />
                </li>
                <li>
                    <input class="icb-register-email form-control" type="text" placeholder="<?php _e('邮箱'); ?>" name="email" tips="<?php _e('请输入你常用的电子邮箱作为你的账号'); ?>" value="<?php echo htmlspecialchars($_GET['email']); ?>" errortips="<?php _e('邮箱格式不正确'); ?>" />
                </li>
                <li>
                    <input class="icb-register-pwd form-control" type="password" name="password" placeholder="密码" tips="<?php _e('请输入 6-16 个字符,区分大小写'); ?>" errortips="<?php _e('密码不符合规则'); ?>" />
                </li>
                <li class="more-information collapse">
                    <ul>
                        <li>
                            <?php _e('性别'); ?>:
                            <label>
                                <input name="sex" id="sex" value="1" type="radio" /> <?php _e('男'); ?>
                            </label>&nbsp;
                            <label>
                                <input name="sex" id="sex" value="2" type="radio" /> <?php _e('女'); ?> </label>&nbsp;
                            <label>
                                <input name="sex" id="sex" value="3" type="radio" checked="checked" /> <?php _e('保密'); ?>
                            </label>
                        </li>
                        <li>
                            <?php _e('职业'); ?>:
                            <select name="job_id">
                                <option value="">--</option>
                                <?php echo H::display_options($this->job_list); ?>
                            </select>
                        </li>
                        <li>
                            <?php _e('所在城市'); ?>:
                            <select name="province" class="select_area" style="display:inline-block"></select>

                            <select name="city" class="select_area"></select>
                        </li>
                        <li>
                            <input type="text" class="form-control" placeholder="<?php _e('一句话介绍'); ?>" id="welcome_signature" value="<?php if ($this->user_info['signature']) { echo $this->user_info['signature']; } ?>" name="signature" />
                        </li>
                    </ul>
                </li>
                <li>
                    <hr />
                    <a class="more-information-btn"><?php _e('更多资料'); ?></a>
                </li>
                <?php if (get_setting('register_seccode') == 'Y') { ?>
                <li class="icb-register-verify">
                    <img class="pull-right" id="captcha" onclick="this.src = G_BASE_URL + '/account/captcha/' + Math.floor(Math.random() * 10000);" src="">

                    <input type="text" class="form-control" name="seccode_verify" placeholder="<?php _e('验证码'); ?>" />
                </li>
                <?php } ?>
                <li class="last">
                    <label><input type="checkbox" checked="checked" value="agree" name="agreement_chk" /> <?php _e('我同意'); ?></label> <a href="javascript:;" class="icb-agreement-btn"><?php _e('用户协议'); ?></a>
                    <a href="account/login/" class="pull-right">已有账号?</a>
                    <div class="icb-register-agreement collapse">
                        <div class="icb-register-agreement-txt" id="register_agreement"></div>
                    </div>

                </li>
                <li class="clearfix">
                    <button class="btn btn-large btn-blue btn-block" onclick="AWS.ajax_post($('#register_form'), AWS.ajax_processer, 'error_message'); return false;"><?php _e('注册'); ?></button>
                </li>
            </ul>
        </form>
    </div>
    <div class="mod-footer"></div>
</div>

<script type="text/javascript">
$(document).ready(function ()
{
    $.get(G_BASE_URL + '/account/ajax/register_agreement/', function (result) { $('#register_agreement').html(result.err); }, 'json');

    $('.icb-agreement-btn').click(function()
    {
        if ($('.icb-register-agreement').is(':visible'))
        {
            $('.icb-register-agreement').hide();
        }
        else
        {
            $('.icb-register-agreement').show();
        }
    });

    $('.more-information-btn').click(function()
    {
        $('.more-information').fadeIn();
        $(this).parent().hide();
    });

    verify_register_form('#register_form');

    /* 注册页面验证 */
    function verify_register_form(element)
    {
        $(element).find('[type=text], [type=password]').on({
            focus : function()
            {
                if (typeof $(this).attr('tips') != 'undefined' && $(this).attr('tips') != '')
                {
                    $(this).parent().append('<span class="icb-reg-tips">' + $(this).attr('tips') + '</span>');
                }
            },
            blur : function()
            {
                if ($(this).attr('tips') != '')
                {
                    switch ($(this).attr('name'))
                    {
                        case 'user_name' :
                            var _this = $(this);
                            $(this).parent().find('.icb-reg-tips').detach();
                            if ($(this).val().length >= 0 && $(this).val().length < 2)
                            {
                                $(this).parent().find('.icb-reg-tips').detach();
                                $(this).parent().append('<span class="icb-reg-tips icb-reg-err"><i class="icb-icon i-err"></i>' + $(this).attr('errortips') + '</span>');
                                return;
                            }
                            if ($(this).val().length > 17)
                            {
                                $(this).parent().find('.icb-reg-tips').detach();
                                $(this).parent().append('<span class="icb-reg-tips icb-reg-err"><i class="icb-icon i-err"></i>' + $(this).attr('errortips') + '</span>');
                                return;
                            }
                            else
                            {
                                $.post(G_BASE_URL + '/account/ajax/check_username/', 
                                    {
                                        username: $(this).val()
                                    }, function (result)
                                {
                                    if (result.errno == -1)
                                    {
                                        _this.parent().find('.icb-reg-tips').detach();
                                        _this.parent().append('<span class="icb-reg-tips icb-reg-err"><i class="icb-icon i-err"></i>' + result.err + '</span>');
                                    }
                                    else
                                    {
                                        _this.parent().find('.icb-reg-tips').detach();
                                        _this.parent().append('<span class="icb-reg-tips icb-reg-right"><i class="icb-icon i-followed"></i></span>');
                                    }
                                }, 'json');
                            }
                            return;

                        case 'email' :
                            $(this).parent().find('.icb-reg-tips').detach();
                            var emailreg = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/;
                            if (!emailreg.test($(this).val()))
                            {
                                $(this).parent().find('.icb-reg-tips').detach();
                                $(this).parent().append('<span class="icb-reg-tips icb-reg-err"><i class="icb-icon i-err"></i>' + $(this).attr('errortips') + '</span>');
                                return;
                            }
                            else
                            {
                                $(this).parent().find('.icb-reg-tips').detach();
                                $(this).parent().append('<span class="icb-reg-tips icb-reg-right"><i class="icb-icon i-followed"></i></span>');
                            }
                            return;

                        case 'password' :
                            $(this).parent().find('.icb-reg-tips').detach();
                            if ($(this).val().length >= 0 && $(this).val().length < 6)
                            {
                                $(this).parent().find('.icb-reg-tips').detach();
                                $(this).parent().append('<span class="icb-reg-tips icb-reg-err"><i class="icb-icon i-err"></i>' + $(this).attr('errortips') + '</span>');
                                return;
                            }
                            if ($(this).val().length > 17)
                            {
                                $(this).parent().find('.icb-reg-tips').detach();
                                $(this).parent().append('<span class="icb-reg-tips icb-reg-err"><i class="icb-icon i-err"></i>' + $(this).attr('errortips') + '</span>');
                                return;
                            }
                            else
                            {
                                $(this).parent().find('.icb-reg-tips').detach();
                                $(this).parent().append('<span class="icb-reg-tips icb-reg-right"><i class="icb-icon i-followed"></i></span>');
                            }
                            return;

                    }
                }

            }
        });
    }

    $('.select_area').LocationSelect({
        labels: ["<?php _e('请选择省份或直辖市'); ?>", "<?php _e('请选择城市'); ?>"],
        elements: document.getElementsByTagName("select"),
        detector: function () {
            this.select(["<?php echo $this->user_info['province']; ?>", "<?php echo $this->user_info['city']; ?>"]);
        },
        dataUrl: G_BASE_URL.replace('/?', '') + '/static/js/areas.js'
    });
});
</script>

<?php View::output('global/footer.php'); ?>
