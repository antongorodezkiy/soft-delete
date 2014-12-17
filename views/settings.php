<?php if (!defined('WPINC')) die(); ?>

<div class="soft-delete-admin-settings js-soft-delete-admin-settings">
	
		<div class="pure-g">
			<div class="pure-u-lg-2-3 pure-u-md-1-1 pure-u-sm-1-1 pure-u-xs-1-1">
				
				<div class="soft-delete-bl row">
					<div class="row hdr">
						<h3>
							<span class="fa fa-info"></span>
							<?php _e('About', SOFT_DELETE_PLUGIN)?>
						</h3>
					</div>
					<div class="row container">
						<p class="center">
							<a class="logo" href="http://soft-delete.wp.teamlead.pw" target="_blank">
								WordPress Soft Delete Plugin
							</a>
						</p>
						
						<div class="in">
							<p><?php _e('If you ever need to be sure that your data is safe, even if it could be deleted by someone, this plugin is for you.', SOFT_DELETE_PLUGIN)?></p>
							<p><?php _e('The concept of soft deletion is when the data not erased from the system, but just marked as deleted and stays in the database as long as you need it... or just forever.', SOFT_DELETE_PLUGIN)?></p>
							
							<blockquote>
								WordPress Soft Delete Plugin &copy; <a target="_blank" href="http://teamlead.pw">Teamlead Power&nbsp;<span class="fa fa-external-link-square"></span></a>
							</blockquote>
							
							<blockquote>
								<p>
									Icons &copy; <a href="http://fortawesome.github.io/Font-Awesome/" target="_blank">Font Awesome&nbsp;<span class="fa fa-external-link-square"></span></a>
								</p>
								<p>
									Logo Icon &copy;
									<a href="http://thenounproject.com/term/close/15425/" target="_blank">Alex S. Lakas&nbsp;<span class="fa fa-external-link-square"></span></a> &amp;
									<a href="http://thenounproject.com/term/pillow/1473/" target="_blank">Megan Strickland&nbsp;<span class="fa fa-external-link-square"></span></a>
								</p>
								<p>
									CSS Framework &copy; <a href="http://purecss.io/" target="_blank">Pure.css&nbsp;<span class="fa fa-external-link-square"></span></a>
								</p>
								<p>
									Color theme &copy; <a href="http://www.colourlovers.com/palette/3590058/1" target="_blank">cdelorey&nbsp;<span class="fa fa-external-link-square"></span></a>
								</p>
							</blockquote>
						</div>
					</div>
				</div>

				<div class="soft-delete-bl row">
					<div class="row hdr">
						<h3>
							<span class="fa fa-exclamation-triangle"></span>
							<?php _e('Plugin Requirements', SOFT_DELETE_PLUGIN)?>
						</h3>
					</div>
					<div class="row in container">
						<ul>
							<?php
								foreach($requirements as $requirement) {
									
									if ($requirement['status']) {
										?>
											<li>
												<span class="fa-stack soft-delete-requirement-success">
													<i class="fa fa-circle fa-stack-2x"></i>
													<i class="fa fa-check fa-stack-1x fa-inverse"></i>
												</span>
												<?php echo $requirement['name'];?> <?php echo $requirement['success'];?>
											</li>
										<?php
									}
									else {
										?>
											<li>
												<span class="fa-stack soft-delete-requirement-fail">
													<i class="fa fa-circle fa-stack-2x"></i>
													<i class="fa fa-exclamation fa-stack-1x fa-inverse"></i>
												</span>
												<?php echo $requirement['name'];?> <?php echo $requirement['fail'];?>
											</li>
										<?php
									}
								}
							?>
						</ul>
					</div>
				</div>
			
				<form class="soft-delete-content soft-delete-bl settings-pure-form pure-form-aligned pure-form" method="post" action="options.php">
					
					<?php settings_fields(SOFT_DELETE_PLUGIN);?>
					
					<div class="row hdr">
						<h3>
							<span class="fa fa-sliders"></span>
							<?php _e('Settings', SOFT_DELETE_PLUGIN)?>
						</h3>
					</div>
			
					<div class="row in">
						<div class="row">
							<legend><span class="fa fa-cogs"></span><?php _e('Main settings', SOFT_DELETE_PLUGIN)?></legend>
							
							<p class="pure-control-group">
								<label for="<?php echo SOFT_DELETE_PLUGIN?>[enable_logging]">
									<span class="fa-stack">
										<i class="fa fa-circle fa-stack-2x"></i>
										<i class="fa fa-bell-slash fa-stack-1x fa-inverse"></i>
									</span>
									<?php _e('Enable debug logging', SOFT_DELETE_PLUGIN)?>
									<a href="#!" class="js-tip tip" title="<?php _e('Enable logging debug information to logfie. If turned off, then only errors will be logged.', SOFT_DELETE_PLUGIN)?>"><span class="fa fa-question-circle"></span></a>
								</label>
								<input
									type="hidden"
									name="<?php echo SOFT_DELETE_PLUGIN?>[enable_logging]"
									value="0"
									/>
								<input
									type="checkbox"
									name="<?php echo SOFT_DELETE_PLUGIN?>[enable_logging]"
									value="1"
									<?php echo ( SoftDelete_AdminController::getSetting('enable_logging') ? 'checked="checked"' : '' )?>
									/>
							</p>
							
							<p class="pure-control-group">
								<label for="<?php echo SOFT_DELETE_PLUGIN?>[soft_deleted_status]">
									<span class="fa-stack">
										<i class="fa fa-circle fa-stack-2x"></i>
										<i class="fa fa-align-left fa-stack-1x fa-inverse"></i>
									</span>
									<?php _e('Status name for soft "deleted" posts', SOFT_DELETE_PLUGIN)?>
									<a href="#!" class="js-tip tip" title="<?php _e('', SOFT_DELETE_PLUGIN)?>"><span class="fa fa-question-circle"></span></a>
								</label>
								<input
									type="text"
									required="required"
									name="<?php echo SOFT_DELETE_PLUGIN?>[soft_deleted_status]"
									value="<?php echo SoftDelete_AdminController::getSetting('soft_deleted_status');?>"
									/>
							</p>
							
						</div>
						
						
						
						<div class="row">
							<legend><span class="fa fa-user"></span><?php _e('Post Types', SOFT_DELETE_PLUGIN)?></legend>
							
							<div class="pure-control-group">
								<label>
									<span class="fa fa-trash"></span>
									<?php _e('Post Types allowed for soft delete', SOFT_DELETE_PLUGIN)?>
									<a href="#!" class="js-tip tip" title="<?php _e('The following post types are allowed to be soft deleted', SOFT_DELETE_PLUGIN)?>"><span class="fa fa-question-circle"></span></a>
								</label>
								<ul>
									<?php
										$selected_post_types = SoftDelete_AdminController::getSetting('types_allowed_for_soft_delete');
										foreach($post_types as $post_type) {
											?>
												<li>
													<label>
														<input
															type="checkbox"
															name="<?php echo SOFT_DELETE_PLUGIN?>[types_allowed_for_soft_delete][]"
															value="<?php echo $post_type; ?>"
															<?php echo (in_array($post_type,$selected_post_types) ? 'checked="checked"' : '' )?>
															/>
														<?php echo $post_type;?>
													</label>
												</li>
											<?php
										}
									?>
								</ul>
							</div>
							
							<hr />
							
							<div class="pure-control-group">
								<label>
									<span class="fa fa-remove"></span>
									<?php _e('Post Types allowed for hard (permanent) delete', SOFT_DELETE_PLUGIN)?>
									<a href="#!" class="js-tip tip" title="<?php _e('The following post types are allowed to be deleted permanently', SOFT_DELETE_PLUGIN)?>"><span class="fa fa-question-circle"></span></a>
								</label>
								<ul>
									<?php
										$selected_post_types = SoftDelete_AdminController::getSetting('types_allowed_for_hard_delete');
										foreach($post_types as $post_type) {
											?>
												<li>
													<label>
														<input
															type="checkbox"
															name="<?php echo SOFT_DELETE_PLUGIN?>[types_allowed_for_hard_delete][]"
															value="<?php echo $post_type; ?>"
															<?php echo (in_array($post_type,$selected_post_types) ? 'checked="checked"' : '' )?>
															/>
														<?php echo $post_type;?>
													</label>
												</li>
											<?php
										}
									?>
								</ul>
							</div>

						</div>
					
					
						<div class="row">
							<legend><span class="fa fa-users"></span><?php _e('User Roles', SOFT_DELETE_PLUGIN)?></legend>
							
							<div class="pure-control-group">
								<label>
									<span class="fa fa-trash"></span>
									<?php _e('User Roles allowed to soft delete', SOFT_DELETE_PLUGIN)?>
									<a href="#!" class="js-tip tip" title="<?php _e('The following user roles are allowed to soft delete', SOFT_DELETE_PLUGIN)?>"><span class="fa fa-question-circle"></span></a>
								</label>
								<ul>
									<?php
										$selected_user_roles = SoftDelete_AdminController::getSetting('roles_allowed_to_soft_delete');
										foreach($roles as $role => $name) {
											?>
												<li>
													<label>
														<input
															type="checkbox"
															name="<?php echo SOFT_DELETE_PLUGIN?>[roles_allowed_to_soft_delete][]"
															value="<?php echo $role; ?>"
															<?php echo (in_array($role,$selected_user_roles) ? 'checked="checked"' : '' )?>
															/>
														<?php echo $name;?>
													</label>
												</li>
											<?php
										}
									?>
								</ul>
							</div>
							
							<hr />
							
							<div class="pure-control-group">
								<label>
									<span class="fa fa-trash"></span>
									<?php _e('User Roles allowed to soft delete', SOFT_DELETE_PLUGIN)?>
									<a href="#!" class="js-tip tip" title="<?php _e('The following user roles are allowed to soft delete', SOFT_DELETE_PLUGIN)?>"><span class="fa fa-question-circle"></span></a>
								</label>
								<ul>
									<?php
										$selected_user_roles = SoftDelete_AdminController::getSetting('roles_allowed_to_hard_delete');
										foreach($roles as $role => $name) {
											?>
												<li>
													<label>
														<input
															type="checkbox"
															name="<?php echo SOFT_DELETE_PLUGIN?>[roles_allowed_to_hard_delete][]"
															value="<?php echo $role; ?>"
															<?php echo (in_array($role,$selected_user_roles) ? 'checked="checked"' : '' )?>
															/>
														<?php echo $name;?>
													</label>
												</li>
											<?php
										}
									?>
								</ul>
							</div>

						</div>
						
						
						<p>
							<blockquote>
								<?php echo sprintf(__('More information about settings you could find below in %s "Documentation" section, "Where to start?" subsection.',LINKED_ARTICLES_PLUGIN),'<span class="fa fa-file-code-o"></span>')?>
							</blockquote>
						</p>

						<hr />
						
						<div class="row">
							<button class="button-primary" type="submit">
								<span class="fa fa-save"></span>
								<?php _e('Save', SOFT_DELETE_PLUGIN)?>
							</button>
						</div>
						
					</div>
					
				</form>
			
			</div>
			
			
			
			<div class="pure-u-lg-1-3 pure-u-md-1-1 pure-u-sm-1-1 pure-u-xs-1-1">
			
				<div class="soft-delete-bl row">
					<div class="row hdr">
						<h3>
							<span class="fa fa-envelope-o"></span>
							<?php _e('Personal Support', SOFT_DELETE_PLUGIN)?>
						</h3>
					</div>
					<div class="row in container">
			
						<div class="row">
							<blockquote>
								<p>
									<?php
			
									$subject = sprintf(__('Support request, plugin: %s (time: %s)', SOFT_DELETE_PLUGIN),
										SOFT_DELETE_PLUGIN,
										date('d.m.Y H:i:s')
									);
									
									echo sprintf(__('To get support please contact us on address <a target="_blank" href="%s">%s</a>. Please also attach information below to let us know more about your server and site environment - this could be helpful to solve the issue.', SOFT_DELETE_PLUGIN),
										'mailto:support@teamlead.pw?subject='.$subject,
										'support@teamlead.pw&nbsp;<span class="fa fa-external-link-square"></span>'
									);?>
								</p>
							</blockquote>
							<p>
								Email: <a target="_blank" href="mailto:support@teamlead.pw?subject=<?php echo $subject;?>">support@teamlead.pw&nbsp;<span class="fa fa-external-link-square"></span></a>
							</p>
							<p>
								<?php _e('Subject', SOFT_DELETE_PLUGIN)?>: <?php echo $subject;?>
							</p>
						</div>
			
						<div class="row">
							<h5 class="row">
								<?php _e('Server Info', SOFT_DELETE_PLUGIN)?>
							</h5>
							<ul>
								<?php
									foreach(SoftDelete_Plugin::serverInfo() as $option => $val) {
										$info = $option.' -> '.$val;
										?>
											<li>
												<?php echo $info; ?>
											</li>
										<?php
									}
								?>
							</ul>
						</div>
						
						<hr />
						
						<div class="row">
							<h5 class="row">
								<?php _e('Theme', SOFT_DELETE_PLUGIN)?>
							</h5>
							<?php $current_theme = wp_get_theme(); ?>
							<p>
								<?php echo $current_theme->get('Name');?>,
								<?php echo $current_theme->get('Version');?>,
								<?php echo $current_theme->get('ThemeURI');?>
							</p>
							<p>
								<?php _e('from', SOFT_DELETE_PLUGIN)?> <?php echo $current_theme->get('Author');?>,
								<?php echo $current_theme->get('AuthorURI');?>
							</p>
							
						</div>
						
						<hr />
						
						<div class="row">
							<h5 class="row">
								<?php _e('Plugins', SOFT_DELETE_PLUGIN)?>
							</h5>
							<ul>
								<?php
									foreach(SoftDelete_Plugin::getActivePlugins() as $pl) {
										$plugin = $pl['Name'].', '.$pl['Version'].', '.$pl['PluginURI'];
										?>
											<li>
												<?php echo $plugin; ?>
											</li>
										<?php
									}
								?>
							</ul>
						</div>
				
					</div>
				</div>
				
			</div>
		</div>
		
		<div class="pure-u-1">
			<div class="soft-delete-bl">

				<div class="row hdr">
					<h3>
						<span class="fa fa-tasks"></span>
						<?php _e("Today's log file", SOFT_DELETE_PLUGIN)?>
					</h3>
				</div>
		
				<div class="row in soft-delete-logfile-preview">
					<code><pre><?php
						if (file_exists(SoftDelete_Plugin::getLogsPath().date('Y-m-d').'.php')) {
							include_once(SoftDelete_Plugin::getLogsPath().date('Y-m-d').'.php');
						}
						else {
							_e("Today's log file doesn't exist", SOFT_DELETE_PLUGIN); 
						}
						?></pre></code>
				</div>
				
			</div>
		</div>
		
	<div class="pure-u-1">
		<?php
			$documentation_url = SoftDelete_Plugin::getDocsUrl();
		?>
		<div class="soft-delete-bl">
			<div class="row hdr">
				<h3>
					<span class="fa fa-file-code-o"></span>
					<?php _e('Documentation', SOFT_DELETE_PLUGIN)?>
					<a class="right" target="_blank" href="<?php echo $documentation_url?>" title="<?php _e('open in the separate tab', SOFT_DELETE_PLUGIN)?>"><span class="fa fa-external-link"></span></a>
				</h3>
			</div>
			<div class="row in container">
				<iframe class="soft-delete-iframe" src="<?php echo $documentation_url?>" frameborder="0"></iframe>
			</div>
		</div>
	</div>
	
</div>
