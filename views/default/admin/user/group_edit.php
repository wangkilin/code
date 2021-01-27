<?php View::output('admin/global/header.php'); ?>
<?php View::output('admin/global/nav_menu.php'); ?>

<div class="icb-content-wrap">
	<form action="admin/ajax/edit_user_group_permission/" id="settings_form" method="post" onsubmit="return false">
	<input type="hidden" name="group_id" value="<?php echo $this->group['group_id']; ?>" />
	<div class="mod">
		<div class="mod-head">
			<h3>
				<span class="pull-left"><?php _e('用户组权限'); ?>: <?php echo $this->group['group_name']; ?></span>
			</h3>
		</div>
		<div class="tab-content mod-content">
			<?php if ($this->group['group_id'] == 4) { ?>
				<div class="alert alert-danger"><?php _e('注意: 这个用户组的权限会被会员组覆盖, 除非用户的威望不在会员组范围内才会使用此用户组权限'); ?></div>
			<?php } ?>

			<table class="table table-striped">
				<?php if ($this->group['group_id'] != 99) { ?>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('系统管理员'); ?>:</span>
							<div class="col-sm-6 col-xs-8">
								<div class="btn-group mod-btn">
									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="1" name="is_administortar"<?php if ($this->group_pms['is_administortar']) { ?> checked="checked"<?php } ?>> <?php _e('是'); ?>
									</label>

									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="0" name="is_administortar"<?php if (!$this->group_pms['is_administortar']) { ?> checked="checked"<?php } ?>> <?php _e('否'); ?>
									</label>
								</div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('前台管理员'); ?>:</span>
							<div class="col-sm-6 col-xs-8">
								<div class="btn-group mod-btn">
									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="1" name="is_moderator"<?php if ($this->group_pms['is_moderator']) { ?> checked="checked"<?php } ?>> <?php _e('是'); ?>
									</label>

									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="0" name="is_moderator"<?php if (!$this->group_pms['is_moderator']) { ?> checked="checked"<?php } ?>> <?php _e('否'); ?>
									</label>
								</div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('允许发布问题'); ?>:</span>
							<div class="col-sm-6 col-xs-8">
								<div class="btn-group mod-btn">
									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="1" name="publish_question"<?php if ($this->group_pms['publish_question']) { ?> checked="checked"<?php } ?>> <?php _e('是'); ?>
									</label>

									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="0" name="publish_question"<?php if (!$this->group_pms['publish_question']) { ?> checked="checked"<?php } ?>> <?php _e('否'); ?>
									</label>
								</div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('允许发布文章'); ?>:</span>
							<div class="col-sm-6 col-xs-8">
								<div class="btn-group mod-btn">
									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="1" name="publish_article"<?php if ($this->group_pms['publish_article']) { ?> checked="checked"<?php } ?>> <?php _e('是'); ?>
									</label>

									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="0" name="publish_article"<?php if (!$this->group_pms['publish_article']) { ?> checked="checked"<?php } ?>> <?php _e('否'); ?>
									</label>
								</div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('允许发布评论'); ?>:</span>
							<div class="col-sm-6 col-xs-8">
								<div class="btn-group mod-btn">
									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="1" name="publish_comment"<?php if ($this->group_pms['publish_comment']) { ?> checked="checked"<?php } ?>> <?php _e('是'); ?>
									</label>

									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="0" name="publish_comment"<?php if (!$this->group_pms['publish_comment']) { ?> checked="checked"<?php } ?>> <?php _e('否'); ?>
									</label>
								</div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('发布内容需要审核'); ?>:</span>
							<div class="col-sm-6 col-xs-8">
								<div class="btn-group mod-btn">
									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="1" name="publish_approval"<?php if ($this->group_pms['publish_approval']) { ?> checked="checked"<?php } ?>> <?php _e('是'); ?>
									</label>

									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="0" name="publish_approval"<?php if (!$this->group_pms['publish_approval']) { ?> checked="checked"<?php } ?>> <?php _e('否'); ?>
									</label>
								</div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('需要审核内容的时间段'); ?>:</span>
							<div class="col-sm-7">
								<div class="row">
									<div class="col-sm-4">
										<input type="text" class="form-control" value="<?php echo $this->group_pms['publish_approval_time']['start']; ?>" name="publish_approval_time[start]" />
									</div>
									<span class="col-sm-1 mod-text-inline"> -</span>
									<div class="col-sm-4">
										<input type="text" class="form-control" value="<?php echo $this->group_pms['publish_approval_time']['end']; ?>" name="publish_approval_time[end]" />
									</div>

									<span class="help-block"><?php _e('对系统管理员和前台管理员无效 <br /> 不设置审核请留空，24 小时制全部审核请设置 0 - 23'); ?></span>
								</div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('允许编辑所有问题'); ?>:</span>
							<div class="col-sm-6 col-xs-8">
								<div class="btn-group mod-btn">
									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="1" name="edit_question"<?php if ($this->group_pms['edit_question']) { ?> checked="checked"<?php } ?>> <?php _e('是'); ?>
									</label>

									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="0" name="edit_question"<?php if (!$this->group_pms['edit_question']) { ?> checked="checked"<?php } ?>> <?php _e('否'); ?>
									</label>
								</div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('允许编辑所有文章'); ?>:</span>
							<div class="col-sm-6 col-xs-8">
								<div class="btn-group mod-btn">
									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="1" name="edit_article"<?php if ($this->group_pms['edit_article']) { ?> checked="checked"<?php } ?>> <?php _e('是'); ?>
									</label>

									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="0" name="edit_article"<?php if (!$this->group_pms['edit_article']) { ?> checked="checked"<?php } ?>> <?php _e('否'); ?>
									</label>
								</div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('允许编辑所有话题'); ?>:</span>
							<div class="col-sm-6 col-xs-8">
								<div class="btn-group mod-btn">
									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="1" name="edit_topic"<?php if ($this->group_pms['edit_topic']) { ?> checked="checked"<?php } ?>> <?php _e('是'); ?>
									</label>

									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="0" name="edit_topic"<?php if (!$this->group_pms['edit_topic']) { ?> checked="checked"<?php } ?>> <?php _e('否'); ?>
									</label>
								</div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('允许管理所有话题'); ?>:</span>
							<div class="col-sm-6 col-xs-8">
								<div class="btn-group mod-btn">
									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="1" name="manage_topic"<?php if ($this->group_pms['manage_topic']) { ?> checked="checked"<?php } ?>> <?php _e('是'); ?>
									</label>

									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="0" name="manage_topic"<?php if (!$this->group_pms['manage_topic']) { ?> checked="checked"<?php } ?>> <?php _e('否'); ?>
									</label>
								</div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('允许创建话题'); ?>:</span>
							<div class="col-sm-6 col-xs-8">
								<div class="btn-group mod-btn">
									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="1" name="create_topic"<?php if ($this->group_pms['create_topic']) { ?> checked="checked"<?php } ?>> <?php _e('是'); ?>
									</label>

									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="0" name="create_topic"<?php if (!$this->group_pms['create_topic']) { ?> checked="checked"<?php } ?>> <?php _e('否'); ?>
									</label>
								</div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('允许使用问题重定向'); ?>:</span>
							<div class="col-sm-6 col-xs-8">
								<div class="btn-group mod-btn">
									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="1" name="redirect_question"<?php if ($this->group_pms['redirect_question']) { ?> checked="checked"<?php } ?>> <?php _e('是'); ?>
									</label>

									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="0" name="redirect_question"<?php if (!$this->group_pms['redirect_question']) { ?> checked="checked"<?php } ?>> <?php _e('否'); ?>
									</label>
								</div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('允许使用附件功能'); ?>:</span>
							<div class="col-sm-6 col-xs-8">
								<div class="btn-group mod-btn">
									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="1" name="upload_attach"<?php if ($this->group_pms['upload_attach']) { ?> checked="checked"<?php } ?>> <?php _e('是'); ?>
									</label>

									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="0" name="upload_attach"<?php if (!$this->group_pms['upload_attach']) { ?> checked="checked"<?php } ?>> <?php _e('否'); ?>
									</label>
								</div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('允许发布站外链接'); ?>:</span>
							<div class="col-sm-6 col-xs-8">
								<div class="btn-group mod-btn">
									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="1" name="publish_url"<?php if ($this->group_pms['publish_url']) { ?> checked="checked"<?php } ?>> <?php _e('是'); ?>
									</label>

									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="0" name="publish_url"<?php if (!$this->group_pms['publish_url']) { ?> checked="checked"<?php } ?>> <?php _e('否'); ?>
									</label>
								</div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('开启发文验证码限制'); ?>:</span>
							<div class="col-sm-6 col-xs-8">
								<div class="btn-group mod-btn">
									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="1" name="human_valid"<?php if ($this->group_pms['human_valid']) { ?> checked="checked"<?php } ?>> <?php _e('是'); ?>
									</label>

									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="0" name="human_valid"<?php if (!$this->group_pms['human_valid']) { ?> checked="checked"<?php } ?>> <?php _e('否'); ?>
									</label>
								</div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('发问验证码'); ?>:</span>
							<div class="col-sm-5 col-xs-8">
								<input type="text" name="question_valid_hour" class="form-control" value="<?php echo intval($this->group_pms['question_valid_hour']); ?>" />

								<span class="help-block"><?php _e('一小时内发布问题超过规定条数需要验证码'); ?>, <?php _e('设置 0 为不限制'); ?></span>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('回答验证码'); ?>:</span>
							<div class="col-sm-5 col-xs-8">
								<input type="text" name="answer_valid_hour" class="form-control" value="<?php echo intval($this->group_pms['answer_valid_hour']); ?>" />

								<span class="help-block"><?php _e('一小时内发表回复超过规定条数需要验证码'); ?>, <?php _e('设置 0 为不限制'); ?></span>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('频率限制'); ?>:</span>
							<div class="col-sm-5 col-xs-8">
								<input type="text" name="function_interval" class="form-control" value="<?php echo intval($this->group_pms['function_interval']); ?>" />

								<span class="help-block"><?php _e('编辑话题 / 添加话题 / 话题重定向功能使用间隔限制 (单位: 秒, 设置 0 为不限制, 对管理员无效)'); ?></span>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('允许编辑问题话题'); ?>:</span>
							<div class="col-sm-6 col-xs-8">
								<div class="btn-group mod-btn">
									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="1" name="edit_question_topic"<?php if ($this->group_pms['edit_question_topic']) { ?> checked="checked"<?php } ?>> <?php _e('是'); ?>
									</label>

									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="0" name="edit_question_topic"<?php if (!$this->group_pms['edit_question_topic']) { ?> checked="checked"<?php } ?>> <?php _e('否'); ?>
									</label>
								</div>
							</div>
						</div>
					</td>
				</tr>

				<?php if (check_extension_package('ticket')) { ?>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('允许管理工单'); ?>:</span>
							<div class="col-sm-6 col-xs-8">
								<div class="btn-group mod-btn">
									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="1" name="is_service"<?php if ($this->group_pms['is_service']) { ?> checked="checked"<?php } ?>> <?php _e('是'); ?>
									</label>

									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="0" name="is_service"<?php if (!$this->group_pms['is_service']) { ?> checked="checked"<?php } ?>> <?php _e('否'); ?>
									</label>
								</div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('允许发布工单'); ?>:</span>
							<div class="col-sm-6 col-xs-8">
								<div class="btn-group mod-btn">
									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="1" name="publish_ticket"<?php if ($this->group_pms['publish_ticket']) { ?> checked="checked"<?php } ?>> <?php _e('是'); ?>
									</label>

									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="0" name="publish_ticket"<?php if (!$this->group_pms['publish_ticket']) { ?> checked="checked"<?php } ?>> <?php _e('否'); ?>
									</label>
								</div>
							</div>
						</div>
					</td>
				</tr>
				<?php } ?>

				<?php if (check_extension_package('project')) { ?>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('允许发布活动'); ?>:</span>
							<div class="col-sm-6 col-xs-8">
								<div class="btn-group mod-btn">
									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="1" name="publish_project"<?php if ($this->group_pms['publish_project']) { ?> checked="checked"<?php } ?>> <?php _e('是'); ?>
									</label>

									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="0" name="publish_project"<?php if (!$this->group_pms['publish_project']) { ?> checked="checked"<?php } ?>> <?php _e('否'); ?>
									</label>
								</div>
							</div>
						</div>
					</td>
				</tr>
				<?php } ?>
                <!-- 新禾网站权限  -->
                <?php if (check_extension_package('sinhoWorkload')) { ?>
                <tr class="js-sinho">
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('允许管理稿件'); ?>:</span>
                            <div class="col-sm-6 col-xs-8">
                                <div class="btn-group mod-btn">
                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" value="1" name="<?php echo sinhoWorkloadModel::PERMISSION_MODIFY_MANUSCRIPT_PARAM;?>"<?php if ($this->group_pms[sinhoWorkloadModel::PERMISSION_MODIFY_MANUSCRIPT_PARAM]) { ?> checked="checked"<?php } ?>> <?php _e('是'); ?>
                                    </label>

                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" value="0" name="<?php echo sinhoWorkloadModel::PERMISSION_MODIFY_MANUSCRIPT_PARAM;?>"<?php if (!$this->group_pms[sinhoWorkloadModel::PERMISSION_MODIFY_MANUSCRIPT_PARAM]) { ?> checked="checked"<?php } ?>> <?php _e('否'); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr class="js-sinho">
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('允许添加个人工作量'); ?>:</span>
                            <div class="col-sm-6 col-xs-8">
                                <div class="btn-group mod-btn">
                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" value="1" name="<?php echo sinhoWorkloadModel::PERMISSION_FILL_WORKLOAD;?>"<?php if ($this->group_pms[sinhoWorkloadModel::PERMISSION_FILL_WORKLOAD]) { ?> checked="checked"<?php } ?>> <?php _e('是'); ?>
                                    </label>

                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" value="0" name="<?php echo sinhoWorkloadModel::PERMISSION_FILL_WORKLOAD;?>"<?php if (!$this->group_pms[sinhoWorkloadModel::PERMISSION_FILL_WORKLOAD]) { ?> checked="checked"<?php } ?>> <?php _e('否'); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr class="js-sinho">
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('允许核算工作量'); ?>:</span>
                            <div class="col-sm-6 col-xs-8">
                                <div class="btn-group mod-btn">
                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" value="1" name="<?php echo sinhoWorkloadModel::PERMISSION_VERIFY_WORKLOAD;?>"<?php if ($this->group_pms[sinhoWorkloadModel::PERMISSION_VERIFY_WORKLOAD]) { ?> checked="checked"<?php } ?>> <?php _e('是'); ?>
                                    </label>

                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" value="0" name="<?php echo sinhoWorkloadModel::PERMISSION_VERIFY_WORKLOAD;?>"<?php if (!$this->group_pms[sinhoWorkloadModel::PERMISSION_VERIFY_WORKLOAD]) { ?> checked="checked"<?php } ?>> <?php _e('否'); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr class="js-sinho">
                    <td>
                        <div class="form-group">
                            <span class="col-sm-4 col-xs-3 control-label"><?php _e('允许查阅工作量'); ?>:</span>
                            <div class="col-sm-6 col-xs-8">
                                <div class="btn-group mod-btn">
                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" value="1" name="<?php echo sinhoWorkloadModel::PERMISSION_CHECK_WORKLOAD;?>"<?php if ($this->group_pms[sinhoWorkloadModel::PERMISSION_CHECK_WORKLOAD]) { ?> checked="checked"<?php } ?>> <?php _e('是'); ?>
                                    </label>

                                    <label type="button" class="btn mod-btn-color">
                                        <input type="radio" value="0" name="<?php echo sinhoWorkloadModel::PERMISSION_CHECK_WORKLOAD;?>"<?php if (!$this->group_pms[sinhoWorkloadModel::PERMISSION_CHECK_WORKLOAD]) { ?> checked="checked"<?php } ?>> <?php _e('否'); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php } ?>

				<?php } else { ?>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('允许浏览网站'); ?>:</span>
							<div class="col-sm-6 col-xs-8">
								<div class="btn-group mod-btn">
									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="1" name="visit_site"<?php if ($this->group_pms['visit_site']) { ?> checked="checked"<?php } ?>> <?php _e('是'); ?>
									</label>

									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="0" name="visit_site"<?php if (!$this->group_pms['visit_site']) { ?> checked="checked"<?php } ?>> <?php _e('否'); ?>
									</label>
								</div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('允许浏览问题列表'); ?>:</span>
							<div class="col-sm-6 col-xs-8">
								<div class="btn-group mod-btn">
									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="1" name="visit_explore"<?php if ($this->group_pms['visit_explore']) { ?> checked="checked"<?php } ?>> <?php _e('是'); ?>
									</label>

									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="0" name="visit_explore"<?php if (!$this->group_pms['visit_explore']) { ?> checked="checked"<?php } ?>> <?php _e('否'); ?>
									</label>
								</div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('允许浏览问题内容'); ?>:</span>
							<div class="col-sm-6 col-xs-8">
								<div class="btn-group mod-btn">
									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="1" name="visit_question"<?php if ($this->group_pms['visit_question']) { ?> checked="checked"<?php } ?>> <?php _e('是'); ?>
									</label>

									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="0" name="visit_question"<?php if (!$this->group_pms['visit_question']) { ?> checked="checked"<?php } ?>> <?php _e('否'); ?>
									</label>
								</div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('访问回复权限'); ?>:</span>
							<div class="col-sm-6 col-xs-8">
								<label class="checkbox-inline"><input type="radio" value="0" name="answer_show"<?php if (!$this->group_pms['answer_show']) { ?> checked="checked"<?php } ?>> <?php _e('显示一个回复 (最佳回复或赞同最多的回复)'); ?></label>
								<label class="checkbox-inline"><input type="radio" value="1" name="answer_show"<?php if ($this->group_pms['answer_show']) { ?> checked="checked"<?php } ?>> <?php _e('显示所有回复'); ?></label>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('允许浏览话题'); ?>:</span>
							<div class="col-sm-6 col-xs-8">
								<div class="btn-group mod-btn">
									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="1" name="visit_topic"<?php if ($this->group_pms['visit_topic']) { ?> checked="checked"<?php } ?>> <?php _e('是'); ?>
									</label>

									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="0" name="visit_topic"<?php if (!$this->group_pms['visit_topic']) { ?> checked="checked"<?php } ?>> <?php _e('否'); ?>
									</label>
								</div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('允许浏览专题'); ?>:</span>
							<div class="col-sm-6 col-xs-8">
								<div class="btn-group mod-btn">
									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="1" name="visit_feature"<?php if ($this->group_pms['visit_feature']) { ?> checked="checked"<?php } ?>> <?php _e('是'); ?>
									</label>

									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="0" name="visit_feature"<?php if (!$this->group_pms['visit_feature']) { ?> checked="checked"<?php } ?>> <?php _e('否'); ?>
									</label>
								</div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('允许浏览用户'); ?>:</span>
							<div class="col-sm-6 col-xs-8">
								<div class="btn-group mod-btn">
									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="1" name="visit_people"<?php if ($this->group_pms['visit_people']) { ?> checked="checked"<?php } ?>> <?php _e('是'); ?>
									</label>

									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="0" name="visit_people"<?php if (!$this->group_pms['visit_people']) { ?> checked="checked"<?php } ?>> <?php _e('否'); ?>
									</label>
								</div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="form-group">
							<span class="col-sm-4 col-xs-3 control-label"><?php _e('允许使用搜索'); ?>:</span>
							<div class="col-sm-6 col-xs-8">
								<div class="btn-group mod-btn">
									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="1" name="search_avail"<?php if ($this->group_pms['search_avail']) { ?> checked="checked"<?php } ?>> <?php _e('是'); ?>
									</label>

									<label type="button" class="btn mod-btn-color">
										<input type="radio" value="0" name="search_avail"<?php if (!$this->group_pms['search_avail']) { ?> checked="checked"<?php } ?>> <?php _e('否'); ?>
									</label>
								</div>
							</div>
						</div>
					</td>
				</tr>
				<?php } ?>
				<tfoot>
				<tr>
					<td>
						<div class="form-group bg-warning">页面增加参数后， 需要修改 app/admin/ajax.php中的权限参数设置。 将新参数名称加入到对应方法中</div>
					</td>
				</tr>
				<tr>
					<td>
						<input type="button" value="<?php _e('保存设置'); ?>" class="btn btn-primary center-block" onclick="AWS.ajax_post($('#settings_form'));" />
					</td>
				</tr>
				</tfoot>
			</table>
		</div>
		</div>
	</form>
</div>

<?php View::output('admin/global/footer.php'); ?>
