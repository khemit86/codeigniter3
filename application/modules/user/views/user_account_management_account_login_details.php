<?php 
	$user = $this->session->userdata('user'); 
?>
<div class="dashTop">		
    <!-- Menu Icon on Responsive View Start -->		
    <?php echo $this->load->view('user_left_menu_mobile.php'); ?>
    <!-- Menu Icon on Responsive View End -->		
    <!-- Middle Section Start -->
    <div class="wrapper wrapper1">
        <!-- Left Menu Start -->
        <?php echo $this->load->view('user_left_nav.php'); ?>
        <!-- Left Menu End -->
        <!-- Right Section Start -->
        <div id="content" class="account_management_login_details_page body_distance_adjust">
			<div class="etSecond_step" id="work_experience_listing_data" style="display:block;">
				<!-- Profile Management Text Start -->
				<div class="default_page_heading">
					<h4><div><?php echo $this->config->item('account_management_headline_title_account_login_details'); ?></div></h4>
					<span><sup>__________</sup><sub><i class="fas fa-check"></i></sub><sup>__________</sup></span>
				</div>
				<!-- Profile Management Text End -->
				
				<!-- Checkbox Start -->
				<div class="default_checkbox_button accManagement_logindetails_chkbox three_checkbox"><span class="singleLine_chkBtn"><input type="checkbox" id="login_email_tab" name="contacts_management_checkbox" value="login_email_tab" class="chk-btn account_login_details" data-target="email_container"><label class="singleLine_radioBtn" for="login_email_tab"><span><?php echo $this->config->item('account_management_account_login_details_page_login_email_tab_txt');?></span></label></span><span class="singleLine_chkBtn"><input type="checkbox" id="login_password_tab" name="contacts_management_checkbox" value="login_password_tab" class="chk-btn account_login_details" data-target="password_container"><label class="singleLine_radioBtn" for="login_password_tab"><span><?php echo $this->config->item('account_management_account_login_details_page_login_password_tab_txt');?></span></label></span><span class="singleLine_chkBtn"><input type="checkbox" id="verification_tab" name="contacts_management_checkbox" value="verification_tab" class="chk-btn account_login_details" data-target="verification_container"><label class="singleLine_radioBtn" for="verification_tab"><span><?php echo $this->config->item('account_management_account_login_details_page_verfication_tab_txt');?></span></label></span></div>
				<!-- Checkbox End -->
				
				<!-- Content Start -->
				<div class="cmFieldText">
					<div id="email_container" class="cmField d-none">
						<div class="emailSection">
							<!-- Step 1st Start -->
							<div class="pmFirstStep">
								<div id="initialViewEditEmail" class="default_hover_section_iconText emailNew mrgBtm0 closeSkills">
									<div class="row">
										<div class="col-md-12 col-sm-12 col-12 fontSize0 default_bottom_border">
											<i class="fas fa-envelope"></i>
											<h6><?php echo $this->config->item('account_management_email_title_update_email'); ?></h6>
										</div>
										<div class="col-md-12 col-sm-12 col-12">
											<p><?php echo $this->config->item('account_management_email_initial_view_content'); ?></p>
										</div>
									</div>
								</div>
							</div>
							<!-- Step 1st End -->
							
							<!-- Step 2nd Start -->
							<div class="pmdonotSection pmAllStep" id="editEmailSection" style="display:block;">
								<!-- Step 1st Start -->
								<div class="row" id="currentEmailSection">
									<div class="col-md-6 col-sm-6 col-12 emailStep">
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text">
													<i class="fas fa-envelope" aria-hidden="true"></i>
												</span>
											</div>
											<input type="text" id="current_email" class="form-control default_input_field avoid_space_input" placeholder="<?php echo $this->config->item('account_management_email_current_email_placeholder'); ?>">
										</div>
										<div class="error_div_sectn clearfix">
										<span class="error_msg" id="current_email_error"></span>
										</div>
									</div>
									<div class="col-md-6 col-sm-6 col-12 emailStep">
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text">
													<i class="fas fa-lock" aria-hidden="true"></i>
												</span>
											</div>
											<input type="password" id="current_password" class="form-control avoid_space_input default_input_field" placeholder="<?php echo $this->config->item('account_management_email_current_password_placeholder'); ?>" >
										</div>
										<div class="error_div_sectn clearfix">
											<span class="error_msg" id="current_password_error"></span>
										</div>
									</div>
								</div>
								<!-- Step 1st End -->
								<!-- Step 2nd Start -->
								<!--
								<div class="row">
									<div class="col-md-6 col-sm-6 col-12 emailStep1">
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text">
													<i class="fas fa-envelope" aria-hidden="true"></i>
												</span>
											</div>
											<input type="text" id="current_email" class="form-control default_input_field avoid_space_input" placeholder="Current Email" value="exaltedsol03@gmail.com" disabled>
										</div>
										<div class="error_div_sectn clearfix">
											<span class="error_msg"></span>
										</div>
									</div>
									<div class="col-md-6 col-sm-6 col-12 emailStep1">
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text">
													<i class="fas fa-lock" aria-hidden="true"></i>
												</span>
											</div>
											<input type="password" id="current_password" class="form-control avoid_space_input default_input_field" placeholder="Current Password" value="12345" disabled>
										</div>
										<div class="error_div_sectn clearfix">
											<span class="error_msg"></span>
										</div>
									</div>
								</div>
								-->
								<!-- Step 2nd End -->
								<!-- Step 3rd Start -->
								<div class="row" id="newEmailSection" style="display:block">
									<div class="col-md-6 col-sm-6 col-12 emailStep2">
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text">
													<i class="fas fa-envelope" aria-hidden="true"></i>
												</span>
											</div>
											<input type="text" id="new_email" class="form-control default_input_field avoid_space_input" placeholder="<?php echo $this->config->item('account_management_email_new_email_placeholder'); ?>">
										</div>
										<div class="error_div_sectn clearfix">
											<span class="error_msg" id="new_email_error"></span>
										</div>
									</div>
									<div class="col-md-6 col-sm-6 col-12 emailStep2">
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text">
													<i class="fas fa-envelope" aria-hidden="true"></i>
												</span>
											</div>
											<input type="text" id="confirm_new_email" class="form-control default_input_field avoid_space_input" placeholder="<?php echo $this->config->item('account_management_email_confirm_new_email_placeholder'); ?>">
										</div>
										<div class="error_div_sectn clearfix">
											<span class="error_msg" id="confirm_new_email_error"></span>
										</div>
									</div>
								</div>
								<!-- Step 3rd End -->
								<!-- Step 4th Start -->
								<!--
								<div class="row">
									<div class="col-md-6 col-sm-6 col-12 emailStep3">
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text">
													<i class="fas fa-envelope" aria-hidden="true"></i>
												</span>
											</div>
											<input type="text" id="new_email" class="form-control default_input_field avoid_space_input" placeholder="New E-Mail-" value="exaltedsol03@gmail" disabled>
										</div>
										<div class="error_div_sectn clearfix">
											<span class="error_msg" id="new_email_error"></span>
										</div>
									</div>
									<div class="col-md-6 col-sm-6 col-12 emailStep3">
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text">
													<i class="fas fa-envelope" aria-hidden="true"></i>
												</span>
											</div>
											<input type="text" id="confirm_new_email" class="form-control default_input_field avoid_space_input" placeholder="Confirm New E-Mail-" value="exaltedsol03@gmail" disabled>
										</div>
										<div class="error_div_sectn clearfix">
											<span class="error_msg" id="confirm_new_email_error"></span>
										</div>
									</div>
								</div>-->
								<!-- Step 4th End -->
								<!-- Step 5th Start -->
								<div class="row">
									<div class="col-md-12 col-sm-12 col-12">
										<div class="amBtn"> 
											<!--<button type="button" class="btn default_btn green_btn address_details_edit" id="">Upravit - Edit</button>--><button type="button" id="cancel_update_email" class="btn red_btn default_btn"><?php echo $this->config->item('cancel_btn_txt'); ?></button><button type="button" id="update_email" class="btn blue_btn default_btn update_email_step1"><?php echo $this->config->item('next_btn_txt'); ?>
											<i style="pointer-events: unset; opacity: 1; cursor: pointer; display: none;" class="fa fa-spinner fa-spin spin_loader"></i></button>
										</div>
									</div>
								</div>
								<!-- Step 5th End -->
							</div>						
							<!-- Step 6th Start -->							
							<div class="pmFirstStep" id="editEmailConfirmation" style="display:none">							
								<div class="row margin_top15">
									<div class="col-md-12 col-sm-12 col-12">
										<div  class="btn default_btn green_btn btn-block font-weight-bold" style="cursor:auto"><i class="fas fa-check mr-1"></i><?php echo $this->config->item('account_management_verifications_title_email_successfully_update'); ?></div>
									</div>
								</div>
							</div>
							<!-- Step 6th End -->
						</div>
					</div>
					<div id="password_container" class="cmField d-none">
						<div class="emailSection">
							<!-- Step 1st Start -->
							<div class="pmFirstStep">
								<div id="initialViewPwd" class="default_hover_section_iconText emailNew mrgBtm0 closeSkills">
									<div class="row">
										<div class="col-md-12 col-sm-12 col-12 fontSize0 default_bottom_border">
											<i class="fas fa-lock"></i>
											<h6><?php echo $this->config->item('account_management_password_title_update_password'); ?></h6>
										</div>
										<div class="col-md-12 col-sm-12 col-12">
											<p><?php echo $this->config->item('account_management_password_initial_view_content'); ?></p>
										</div>
									</div>
								</div>
							</div>
							<!-- Step 1st End -->
							
							<!-- Step 2nd Start -->
							<div class="pmdonotSection pmAllStep" id="editPwdSection" style="display:none;" >
							
							
								<div class="row" id="currentPwdSection">
									<div class="col-md-6 col-sm-6 col-12 emailStep">
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text">
													<i class="fas fa-envelope" aria-hidden="true"></i>
												</span>
											</div>
											<input type="text" id="current_email_pwd_section" class="form-control default_input_field avoid_space_input" placeholder="<?php echo $this->config->item('account_management_password_current_email_placeholder'); ?>" >
										</div>
										<div class="error_div_sectn clearfix">
										<span class="error_msg" id="current_email_pwd_section_error"></span>
										</div>
									</div>
									<div class="col-md-6 col-sm-6 col-12 emailStep">
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text">
													<i class="fas fa-lock" aria-hidden="true"></i>
												</span>
											</div>
											<input type="password" id="current_password_pwd_section" class="form-control avoid_space_input default_input_field" placeholder="<?php echo $this->config->item('account_management_password_current_password_placeholder'); ?>">
										</div>
										<div class="error_div_sectn clearfix">
											<span class="error_msg" id="current_password_pwd_section_error"></span>
										</div>
									</div>
								</div>
							
							
								<div class="row" id="newPwdSection" style="display:none">
									<div class="col-md-6 col-sm-6 col-12 emailStep">
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text">
													<i class="fas fa-lock" aria-hidden="true"></i>
												</span>
											</div>
											<input type="password" id="user_new_password"  class="form-control default_input_field avoid_space_input" placeholder="<?php echo $this->config->item('account_management_password_new_password_placeholder'); ?>">
										</div>
										<div class="error_div_sectn clearfix">
										<span class="error_msg" id="user_new_password_error"></span>
										</div>
									</div>
									<div class="col-md-6 col-sm-6 col-12 emailStep">
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text">
													<i class="fas fa-lock" aria-hidden="true"></i>
												</span>
											</div>
											<input type="password" id="user_confirm_new_password" class="form-control avoid_space_input default_input_field" placeholder="<?php echo $this->config->item('account_management_password_confirm_new_password_placeholder'); ?>" >
										</div>
										<div class="error_div_sectn clearfix">
											<span class="error_msg" id="user_confirm_new_password_error"></span>
										</div>
									</div>
								</div>
								<!--
								<div class="row">
									<div class="col-md-6 col-sm-6 col-12 emailStep">
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text">
													<i class="fas fa-lock" aria-hidden="true"></i>
												</span>
											</div>
											<input type="password" id="current_password" class="form-control avoid_space_input default_input_field" placeholder="Confirm New Password" value="">
										</div>
										<div class="error_div_sectn clearfix">
											<span class="error_msg">Confirm New Password is required.</span>
										</div>
									</div>
									<div class="col-md-6 col-sm-6 col-12"></div>
								</div>-->
								<div class="row">
									<div class="col-md-12 col-sm-12 col-12">
										<div class="amBtn">
											<button type="button" id="cancel_update_password" class="btn red_btn default_btn"><?php echo $this->config->item('cancel_btn_txt'); ?></button><button type="button" id="update_password" class="btn blue_btn default_btn update_password_step1"><?php echo $this->config->item('next_btn_txt'); ?>
											<i style="pointer-events: unset; opacity: 1; cursor: pointer; display: none;" class="fa fa-spinner fa-spin spin_loader"></i></button>
										</div>
									</div>
								</div>
							</div>
							<!-- Step 2nd End -->
							
							<!-- Step 3rd Start -->							
							<div class="pmFirstStep" id="editPwdConfirmation" style="display:none">							
								<div class="row margin_top15">
									<div class="col-md-12 col-sm-12 col-12">
										<div class="btn green_btn btn-block font-weight-bold" style="cursor:auto"><i class="fas fa-check mr-1"></i><?php echo $this->config->item('account_management_password_title_password_successfully_update'); ?></div>
										<!-- <div class="mailUpdate"><i class="fas fa-check"></i><?php //echo $this->config->item('account_management_password_title_password_successfully_update'); ?></div> -->
									</div>
								</div>
							</div>
							<!-- Step 3rd End -->						
						</div>
					</div>
					<div id="verification_container" class="cmField d-none">
						<div class="pmdonotSection loginSection">
							<!-- Step 1st Start -->
							<div class="default_bottom_border">
								<div class="amEAdd">
									<p class="default_black_bold_medium"><i class="fas fa-envelope"></i><?php echo $this->config->item('account_management_account_login_details_page_social_media_verification_section_user_current_email_address_txt'); ?><span class="default_black_regular_medium" id="user_account_email"><?php echo $account_email; ?></span>
									</p>
								</div>
							</div>
							<div class="row loginPart">
								<div class="col-md-5 col-sm-5 col-12 fbContent">
									<div class="amEVerify fb_not_connected <?php echo ($user_data['sync_facebook'] == 'y' ? 'd-none' : '') ?>">
										<div class="fbDetails"><small class="default_black_bold_medium"><i class="fab fa-facebook-square"></i><?php echo $this->config->item('account_management_account_login_details_page_social_media_verification_section_facebook_label_txt'); ?></small><span class="default_black_regular_medium"><i class="fas fa-exclamation-circle"></i><?php echo $this->config->item('account_management_account_login_details_page_social_media_verification_section_social_media_account_not_connected_label_txt'); ?></span></div>
										<p class="default_black_bold_medium fbText"><?php echo $this->config->item('account_management_account_login_details_page_social_media_verification_section_connect_facebook_account_label_txt'); ?></p>
										<p class="default_black_regular"><?php echo $this->config->item('account_management_account_login_details_page_social_media_verification_section_no_information_publicaly_displayed_disclaimer_label_txt'); ?></p>
										<div class="fbLoginBtn">
											<button class="btn facebook_button default_facebook_btn default_btn connect_with_fb"><i class="fab fa-facebook-f"></i>
												<?php echo $this->config->item('account_management_account_login_details_page_social_media_verification_section_connect_facebook_account_btn_txt'); ?> <i id="spin_loader" style="display:none;" class="fa fa-spinner fa-spin"></i>
											</button>
										</div>
										<div class="default_error_red_message fb_connect_err d-none"></div>
									</div>
									<div class="amEVerify verifyFb fb_connected <?php echo ($user_data['sync_facebook'] == 'n' ? 'd-none' : '') ?>" >
										<div class="fbDetails"><small class="default_black_bold_medium"><i class="fab fa-facebook-square"></i><?php echo $this->config->item('account_management_account_login_details_page_social_media_verification_section_facebook_label_txt'); ?></small><span class="default_black_regular_medium"><i class="fas fa-check-circle"></i><?php echo $this->config->item('account_management_account_login_details_page_social_media_verification_section_social_media_account_connected_label_txt'); ?></span></div>
										<p class="default_black_bold_medium" id="fb_connected_txt">
											<?php
												$msg = $this->config->item('account_management_account_login_details_page_social_media_verification_section_user_connected_facebook_account_confirmation_label_txt');
												$msg = str_replace('{user_facebook_email_id}', $user_data['user_facebook_associated_email'], $msg); 
												echo $msg; 
											?>
										</p>
										<p class="default_black_regular"><?php echo $this->config->item('account_management_account_login_details_page_social_media_verification_section_no_information_publicaly_displayed_disclaimer_label_txt'); ?></p>
										<div class="fbLoginBtn">
											<button class="btn default_btn red_btn revoke_fb"><?php echo $this->config->item('account_management_account_login_details_page_social_media_verification_section_revoke_btn_txt'); ?></button>
										</div>
									</div>
								</div>
								<div class="col-md-2 col-sm-2 col-12 borderContent">
									<div class="middleBdr"></div>
								</div>
								<div class="col-md-5 col-sm-5 col-12 linkedinContent">
									<div class="amLinkedin linkedin_not_connected <?php echo ($user_data['sync_linkedin'] == 'y' ? 'd-none' : '') ?>">
										<div class="fbDetails"><small class="default_black_bold_medium"><i class="fa fa-linkedin-square"></i><?php echo $this->config->item('account_management_account_login_details_page_social_media_verification_section_linkedin_label_txt'); ?></small><span class="default_black_regular_medium"><i class="fas fa-exclamation-circle"></i><?php echo $this->config->item('account_management_account_login_details_page_social_media_verification_section_social_media_account_not_connected_label_txt'); ?></span></div>
										<p class="default_black_bold_medium fbText"><?php echo $this->config->item('account_management_account_login_details_page_social_media_verification_section_connect_linkedin_account_label_txt'); ?></p>
										<p class="default_black_regular"><?php echo $this->config->item('account_management_account_login_details_page_social_media_verification_section_no_information_publicaly_displayed_disclaimer_label_txt'); ?></p>
										<div class="fbLoginBtn"><button class="btn linkdin_button default_linkedin_btn default_btn" onclick="loginWithLinkedIn('connect_linkedin')"><i class="fab fa-linkedin-in"></i>
											<?php echo $this->config->item('account_management_account_login_details_page_social_media_verification_section_connect_linkedin_account_btn_txt'); ?></button>
										</div>
										<?php 
											if($this->session->userdata('linkedin_error')) {
										?>	
											<div class="default_error_red_message ln_connect_err "><?php echo $this->session->userdata('linkedin_error'); ?></div>
										<?php
											} else {
										?>
											<div class="default_error_red_message ln_connect_err d-none"></div>
										<?php
											}
										?>
									</div>
									<div class="amLinkedin verifyLi linkedin_connected <?php echo ($user_data['sync_linkedin'] == 'n' ? 'd-none' : '') ?>">
										<div class="fbDetails"><small class="default_black_bold_medium"><i class="fa fa-linkedin-square"></i><?php echo $this->config->item('account_management_account_login_details_page_social_media_verification_section_linkedin_label_txt'); ?></small><span class="default_black_regular_medium"><i class="fas fa-check-circle"></i><?php echo $this->config->item('account_management_account_login_details_page_social_media_verification_section_social_media_account_connected_label_txt'); ?></span></div>
										<p class="default_black_bold_medium" id="ln_connected_txt">
											<?php 
												$msg = $this->config->item('account_management_account_login_details_page_social_media_verification_section_user_connected_linkedin_account_confirmation_label_txt');
												$msg = str_replace('{user_linkedin_email_id}', $user_data['user_linkedin_associated_email'], $msg); 
												echo $msg; 
											?>
										</p>
										<p class="default_black_regular"><?php echo $this->config->item('account_management_account_login_details_page_social_media_verification_section_no_information_publicaly_displayed_disclaimer_label_txt'); ?></p>
										<div class="fbLoginBtn">
											<button class="btn default_btn red_btn revoke_ln"><?php echo $this->config->item('account_management_account_login_details_page_social_media_verification_section_revoke_btn_txt'); ?></button>
										</div>
									</div>
								</div>
							</div>
							<div class="row accmVerifyMesg">
								<div class="col-md-12 col-sm-12 col-12">
									<div class="verifyMesg default_project_description">
										<p><?php echo $this->config->item('account_management_account_login_details_page_social_media_verification_section_bottom_disclaimer_txt'); ?></p>
									</div>
								</div>
							</div>
							<!-- Step 1st End -->								
						</div>
					</div>
				</div>
				<!-- Content End -->
			
			</div>
			<!-- Right Section End -->
		</div>
	<!-- Middle Section End -->
	</div>
<script>
var uid = '<?php echo Cryptor::doEncrypt($user[0]->user_id); ?>';
</script>	
<script src="<?= ASSETS ?>js/modules/user_account_management_account_login_details.js"></script>
<script>
	<?php 
		if($this->session->userdata('linkedin_error')) {
	?>
			setTimeout(function() {
				$('.ln_connect_err').addClass('d-none');
			}, 3000);
	<?php
			$this->session->unset_userdata('linkedin_error');
		}
	?>

	<?php 
		if($this->session->userdata('open_verfication_tab')) {
	?>
		$('.account_login_details[data-target="verification_container"]').trigger('click');
	<?php
			$this->session->unset_userdata('open_verfication_tab');
		}
	?>
</script>