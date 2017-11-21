<div class="modal icb-first-login first show">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body clearfix">
				<div class="icb-mod">
					<div class="mod-head">
						<h2>Hi , <?php echo $this->user_info['user_name']; ?><br/><?php _e('欢迎来到'); ?> <?php echo get_setting('site_name'); ?></h2>
						<ul>
							<li class="active">
								1. <?php _e('完善基本资料'); ?>
								<i></i>
							</li>
							<li>
								2. <?php _e('关注热门话题'); ?>
								<i></i>
							</li>
							<li>
								3. <?php _e('关注热门用户'); ?>
							</li>
						</ul>
					</div>
					<div class="mod-body">
						<form id="welcome_step_1_form" action="account/ajax/profile_setting/" method="post" onsubmit="return false">
						<input type="hidden" name="first_login" value="1" />
						<div class="icb-complete-data">
							<div class="pull-left side-bar">
								<img id="icb-upload-img" src="<?php echo get_avatar_url($this->user_id, 'max'); ?>" title="" alt=""/>
								<p class="clearfix text-center"><a class="btn btn-success clearfix" id="welcome_avatar_uploader"><?php _e('上传头像'); ?></a> </p>
								<p id="icb-img-uploading" class="collapse"><i class="icb-loading"></i><?php _e('文件上传中...'); ?></p>

							</div>
							<div class="pull-left form-horizontal">
								<div class="form-group">
								    <label class="col-sm-2 control-label"><?php _e('性别'); ?>:</label>
								    <div class="col-sm-10">
								    	<label>
											<input type="radio" name="sex" value="1"<?php if ($this->user_info['sex'] == 1) { ?> checked="checked"<?php } ?> />
											<?php _e('男'); ?>
										</label> &nbsp;
										<label>
											<input type="radio" name="sex" value="2"<?php if ($this->user_info['sex'] == 2) { ?> checked="checked"<?php } ?> />
											<?php _e('女'); ?>
										</label> &nbsp;
										<label>
											<input type="radio" name="sex" value="3"<?php if (!$this->user_info['sex']) { ?> checked="checked"<?php } ?> />
											<?php _e('保密'); ?>
										</label>
								    </div>
  								</div>
  								<div class="form-group">
								    <label class="col-sm-2 control-label"><?php _e('介绍'); ?>:</label>
								    <div class="col-sm-10">
								    	<input type="text" class="form-control" placeholder="<?php _e('如：80后IT男..'); ?>" id="welcome_signature" value="<?php if ($this->user_info['signature']) { echo $this->user_info['signature']; } ?>" name="signature" />
								    </div>
								</div>
							</div>
						</div>
						</form>
					</div>
					<div class="mod-footer">
						<a class="pull-left" onclick="welcome_step('finish');">跳过资料填写</a>
						<a class="btn btn-large btn-success pull-right" onclick="AWS.ajax_post($('#welcome_step_1_form'), _welcome_step_1_form_processer);"><?php _e('下一步'); ?></a>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal icb-first-login second collapse">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body clearfix">
				<div class="icb-mod">
					<div class="mod-head">
						<h2>Hi，<?php echo $this->user_info['user_name']; ?>, <?php _e('欢迎来到'); ?> <?php echo get_setting('site_name'); ?></h2>
						<ul>
							<li class="active">
								1. <?php _e('完善基本资料'); ?>
								<i></i>
							</li>
							<li class="active">
								2. <?php _e('关注热门话题'); ?>
								<i></i>
							</li>
							<li>
								3. <?php _e('关注热门用户'); ?>
							</li>
						</ul>
					</div>
					<div class="mod-body">
						<div class="icb-first-login-suggest-list clearfix">
							<p class="clearfix title"><a class="pull-right " onclick="welcome_step('2');">换一批</a></p>
							<ul id="welcome_topics_list" class="clearfix"></ul>
						</div>
					</div>
					<div class="mod-footer">
						<a class="pull-left" onclick="welcome_step('finish');">跳过资料填写</a>
						<a class="btn btn-large btn-success pull-right" onclick="welcome_step('3');"><?php _e('下一步'); ?></a>
						<a class="btn btn-large btn-success pull-right" onclick="$('.icb-first-login.first').show();$('.icb-first-login.second').hide();"><?php _e('上一步'); ?></a>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal icb-first-login third collapse">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body clearfix">
				<div class="icb-mod">
					<div class="mod-head">
						<h2>Hi，<?php echo $this->user_info['user_name']; ?>, <?php _e('欢迎来到'); ?> <?php echo get_setting('site_name'); ?></h2>
						<ul>
							<li class="active">
								1. <?php _e('完善基本资料'); ?>
							</li>
							<li class="active">
								2. <?php _e('关注热门话题'); ?>
							</li>
							<li class="active">
								3. <?php _e('关注热门用户'); ?>
							</li>
						</ul>
					</div>
					<div class="mod-body">
						<div class="icb-first-login-suggest-list clearfix">
							<p class="clearfix title"><a class="pull-right " onclick="welcome_step('3');">换一批</a></p>
							<ul id="welcome_users_list" class="clearfix"></ul>
						</div>
					</div>
					<div class="mod-footer">
						<a class="pull-left" onclick="welcome_step('finish');">跳过资料填写</a>
						<a class="btn btn-large btn-success pull-right" onclick="welcome_step('finish');"><?php _e('完成'); ?></a>
						<a class="btn btn-large btn-success pull-right" onclick="$('.icb-first-login.second').show();$('.icb-first-login.third').hide();"><?php _e('上一步'); ?></a>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal-backdrop fade in"></div>

