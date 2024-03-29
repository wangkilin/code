<?php View::output('global/header_meta.php'); ?>

<div class="icb-register-box icb-register-open-box">
	<div class="mod-head">
		<img src="<?php echo G_STATIC_URL; ?>/css/<?php echo $this->template_name; ?>/img/login_logo.png" alt="" />
		<h1><?php _e('第三方登录'); ?></h1>
	</div>
	<div class="mod-body">
		<p><?php _e('已有账号'); ?>? <a class="icb-register-open-tabs"><?php _e('点此绑定'); ?><i class="icb-icon i-triangle-down"></i></a></p>
		<form id="login_form" method="post" class="icb-register-form collapse" onsubmit="return false" action="account/ajax/login_process/">
			<input type="hidden" name="post_hash" value="<?php echo new_post_hash(); ?>" />
			<input type="hidden" name="return_url" id="login_return_url" value="" />
			<ul>
				<li>
					<input class="icb-register-email form-control" name="user_name" type="text" placeholder="<?php _e('邮箱'); ?>/<?php _e('用户名'); ?>" value="<?php echo $this->user_name; ?>" />
				</li>
				<li>
					<input class="icb-register-pwd form-control" type="password" placeholder="<?php _e('密码'); ?>" name="password" />
				</li>
				<li>
					<button class="btn btn-large btn-blue btn-block" onclick="AWS.ajax_post($('#login_form'));return false;"><?php _e('绑定帐号'); ?></button>
				</li>
			</ul>
		</form>

		<form id="register_form" method="post" class="icb-register-form" onsubmit="return false" action="<?php echo $this->register_url; ?>">
			<ul>
				<li>
					<input name="user_name" class="icb-register-name form-control" type="text" placeholder="<?php _e('用户名'); ?>" tips="<?php _e('请输入一个 2-14 位的用户名');?>" errortips="<?php _e('用户名长度不符合');?>" value="<?php echo $this->user_name; ?>" />
				</li>
				<li>
					<input name="email" class="icb-register-email form-control" type="text" placeholder="<?php _e('邮箱'); ?>" value="<?php echo $this->email; ?>" tips="<?php _e('请输入你常用的电子邮箱作为你的账号'); ?>" value="<?php echo htmlspecialchars($_GET['email']); ?>" errortips="<?php _e('邮箱格式不正确'); ?>" />
				</li>
				<li>
					<input name="password" class="icb-register-pwd form-control" type="password" placeholder="<?php _e('密码'); ?>" tips="<?php _e('请输入 6-16 个字符,区分大小写'); ?>" errortips="<?php _e('密码不符合规则'); ?>" />
				</li>
				<li class="last">
					<label><input type="checkbox" checked="checked" value="agree" name="agreement_chk" /> <?php _e('我同意'); ?></label> <a href="javascript:;" onclick="$('.icb-regiter-agreement').show();"><?php _e('用户协议'); ?></a>
					<div class="icb-regiter-agreement collapse">
						<i></i>
						<div class="icb-register-agreement-txt" id="register_agreement"></div>
					</div>
				</li>
				<li class="alert alert-danger collapse error_message">
					<i class="icon icon-delete"></i>
					<em></em>
				</li>
				<li>
					<button class="btn btn-large btn-blue btn-block" onclick="AWS.ajax_post($('#register_form'), AWS.ajax_processer, 'error_message'); return false;"><?php _e('确认注册'); ?></button>
				</li>
			</ul>
		</form>
	</div>
</div>

<script type="text/javascript" src="<?php echo G_STATIC_URL; ?>/js/app/login.js"></script>
<script type="text/javascript">
	$(document).ready(function () {
		$('#login_return_url').val(window.location.href);

		$.get(G_BASE_URL + '/account/ajax/register_agreement/', function (result) { $('#register_agreement').html(result.err); }, 'json');

		$('.icb-register-open-tabs').click(function() {
			if ($('#login_form').is(':visible'))
			{
				$('#login_form').hide();
				$('#register_form').show();

				$(this).removeClass('active');
			}
			else
			{
				$('#login_form').show();
				$('#register_form').hide();

				$(this).addClass('active');
			}
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
											$('.error_message').hide();
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
									$('.error_message').hide();
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
									$('.error_message').hide();
	                                $(this).parent().find('.icb-reg-tips').detach();
	                                $(this).parent().append('<span class="icb-reg-tips icb-reg-right"><i class="icb-icon i-followed"></i></span>');
	                            }
	                            return;

	                    }
						
	                }

	            }
	        });
	    }
	});
</script>

<?php View::output('global/footer.php'); ?>
