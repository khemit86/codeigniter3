<div class="container-fluid" id="register-page">
    <div class="row signupMargin">
        <!-- <div class="col-xl-6 col-lg-6"> -->
        <div class="col-xl-12 col-lg-12 signupPadding">
			<div id="register-side">
				<header>
					<a class="navLogo" href="
						<?php 
							if(validate_session ()) 
							{ 
								echo base_url().$this->config->item('dashboard_page_url'); 
							} else {
								echo base_url();
							} 
						?>">

						  <img src="<?=ASSETS?>images/site-inner-logo.png" alt="logo">
					</a>
				</header>	
				<div class="content signinSignupContent">
						<h1 class="default_black_regular_xxl signinSignup_borderGap"><?php echo $this->config->item('signup_page_heading'); ?></h1>
						<?php
						$attributes = [
							'id' => 'register',
							'class' => 'reply',
							'role' => 'form',
							'name' => 'register',
							'onsubmit' => "return false;",
							'novalidate' => "true",
						];
						echo form_open ('', $attributes);
						?>
						<div class="row" id="account-type-choose">
							<div class="col-lg-6 col-md-6 col-sm-6 col-12 chose-gender-sectn">
								<div class="form-check form-group">
									<input class="form-check-input account-type-picker" onclick="displayType(this.id)" type="radio" name="account_type" id="individual" checked value="<?php echo USER_PERSONAL_ACCOUNT_TYPE; ?>">
									<label class="form-check-label" for="individual">
										<?php echo $this->config->item('signup_page_account_type_tab_as_personal_account_txt'); ?><!--Personal-->
									</label>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-12 chose-gender-sectn">
								<div class="form-check form-group">
									<input class="form-check-input account-type-picker" onclick="displayType(this.id)" type="radio" name="account_type" id="company" value="<?php echo USER_COMPANY_ACCOUNT_TYPE; ?>">
									<label class="form-check-label" for="company">
										<?php echo $this->config->item('signup_page_account_type_tab_as_company_or_authorized_physical_person_account_txt'); ?><!--Company-->
									</label>
								</div>
							</div>
						</div>
						<div class="row" id="company-sub-account-type-choose" style="display:none">
							<div class="col-lg-6 col-md-6 col-sm-6 col-12 chose-gender-sectn">
								<div class="form-check form-group">
									<input class="form-check-input account-type-picker company_sub_account_type"  type="radio" name="company_sub_account_type" id="comp" value="2">
									<label class="form-check-label" for="comp">
										<?php echo $this->config->item('signup_page_company_sub_account_type_tab_as_company_account_txt'); ?>
									</label>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-12 chose-gender-sectn">
								<div class="form-check form-group">
									<input class="form-check-input account-type-picker company_sub_account_type" type="radio" name="company_sub_account_type" id="app" value="1">
									<label class="form-check-label" for="app">
										<?php echo $this->config->item('signup_page_company_sub_account_type_tab_as_authorized_physical_person_account_txt'); ?>
									</label>
								</div>
							</div>
						</div>
						<div id="user_names_div">
							<div class="row" >
								<div class="col-md-6 col-sm-6 col-12 userNameDiv">
									<div class="form-group default_login_input">
										<label class="default_black_bold_medium "><?php echo $this->config->item('signup_page_signup_form_first_name_txt'); ?><!--Firstname--></label>
										 <input class="form-control avoid_space login_register_input_field" id="first_name" name="first_name" type="text" autofocus maxlength="<?php echo $this->config->item('pa_first_name_maximum_length_character_limit_signup_page'); ?>">
									</div>
								</div>
								<div class="col-md-6 col-sm-6 col-12 userNameDiv">
									<div class="form-group default_login_input">
										<label class="default_black_bold_medium "><?php echo $this->config->item('signup_page_signup_form_last_name_txt'); ?><!--Lastname--></label>
										<input type="text" class="form-control avoid_space login_register_input_field" id="last_name" name="last_name" maxlength="<?php echo $this->config->item('pa_last_name_maximum_length_character_limit_signup_page'); ?>">
									</div>
								</div>
							</div>
						</div>
						<div id="company_div">
							<div class="row" >
								<div class="col-md-12">
									<div class="form-group default_login_input">
										<label for="companyname" class="default_black_bold_medium "><?php echo $this->config->item('signup_page_signup_form_company_name_txt'); ?><!--Company name--></label>
										<input type="text" class="form-control avoid_space login_register_input_field" name="company_name" id="companyname" maxlength="<?php echo $this->config->item('ca_company_name_maximum_length_character_limit_signup_page'); ?>">
									</div>
								</div>
							</div>
						</div>
						<div id="choose-sex-div">
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 col-6 chose-gender-sectn">
									<div class="form-check form-group">
										<input class="form-check-input gender" type="radio" name="current_user" id="male" value="M">
										<label class="form-check-label" for="male">
											<?php echo $this->config->item('signup_page_signup_form_male_txt'); ?><!--Male-->
										</label>
									</div>
								</div>						
								<div class="col-lg-6 col-md-6 col-sm-6 col-6 chose-gender-sectn">
									<div class="form-check form-group">
										<input class="form-check-input gender" type="radio" name="current_user" id="female" value="F">
										<label class="form-check-label" for="female">
											<?php echo $this->config->item('signup_page_signup_form_female_txt'); ?><!--Female-->
										</label>								
									</div>
								</div>	
									<!--<input type="text" name="gender" id="gender">-->
								<div class="col-md-12 gender_box"><input type="text" name="gender" id="gender"></div>
							</div>
						</div>
						<div id="email_div">
							<div class="row">
								<div class="col-md-6 col-sm-6 col-12 emailDivOnly">
									<div class="form-group email_address_sectn default_login_input">
										<label class="default_black_bold_medium "><?php echo $this->config->item('signup_page_signup_form_email_address_txt'); ?><!--E-mail address--></label>
										<input type="text" class="form-control avoid_space login_register_input_field" name="email" id="email">
									</div>
								</div>
								<div class="col-md-6 col-sm-6 col-12 emailDivOnly">
									<div class="form-group default_login_input">
										<div class="password_box">
											<label><span class="default_black_bold_medium"><?php echo $this->config->item('signup_page_signup_form_password_txt'); ?></span><!--Password--><i class="fa fa-question-circle tooltipAuto" data-toggle="tooltip" data-placement="top" title="<?php echo $this->config->item('password_signup_message_tooltip'); ?>"></i></label>
											<div class="input-group">
												<input type="password" class="form-control avoid_space login_register_input_field" name="password" id="password">
												<div class="input-group-append">
													<span class="input-group-text"><i class="fa fa-eye" aria-hidden="true"  id="passwordReset"></i></span>
												</div>
											</div>
											<div class="error_div_sectn clearfix" id="password_error"></div>
											<!-- <input type="password" class="form-control avoid_space login_register_input_field" name="password" id="password"> -->
											<!--<i class="fa fa-eye" onclick="passwordEyeReset()" id="passwordReset"></i>-->
											<!-- <i class="fa fa-eye" id="passwordReset"></i> -->
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
						<div id="profile_div">
							<div class="row">
								<div class="col-md-6 col-sm-6 col-12">
									<div class="form-group default_login_input">
										<label><span class="default_black_bold_medium "><?php echo $this->config->item('signup_page_signup_form_profile_name_txt'); ?></span><!--Profile name--><i class="fa fa-question-circle tooltipAuto" data-toggle="tooltip" data-placement="top" title="<?php echo $this->config->item('profile_name_signup_message_tooltip'); ?>"> </i></label>
										<input onkeyup="checkProfileName(this.id);" onkeypress="return avoidSpace(event)" type="text" class="form-control login_register_input_field" name="profile_name" id="profile_name">	
										<!--<span class="url_profile"></span>-->
									</div>
								</div>
								<div class="col-md-6 col-sm-6 col-12"></div>
							</div>
							<div class="row">
								<div class="col-md-12 col-sm-12 col-12">
									<div id="profile_name_msg"></div>
								</div>
							</div>					
						</div>					
						<div id="referal-div" class="referal_code_box">
							<div class="row">
								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-12">
									<div class="default_black_bold_medium "><?php echo $this->config->item('signup_page_signup_form_i_have_referal_code_txt'); ?></div><!--I have a referal code-->
								</div>

								<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-xs-1 referal-parts referal_partsLeft" style="display:block;">
									<div class="form-group default_login_input markasRead">
										<input maxlength="3" data-stape="1"  type="text" class="form-control avoid_space login_register_input_field referal_box" name="referral_part_1" id="referral_part_1">
									</div>
								</div>
								<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-xs-1 referal-parts"  style="display:block;">
									<div class="form-group default_login_input markasRead">
										<input maxlength="3" data-stape="2" type="text" class="form-control avoid_space login_register_input_field referal_box" name="referral_part_2" id="referral_part_2">
									</div>
								</div>
								<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-xs-1 referal-parts referal_partsRight"  style="display:block;">
									<div class="form-group default_login_input markasRead">
										<input maxlength="3" data-stape="3" type="text" class="form-control avoid_space login_register_input_field referal_box" name="referral_part_3" id="referral_part_3">
									</div>
								</div>
								<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-xs-1"></div>
							</div>	
						</div>	
						<div class="clearfix"></div>
						<div class="terms_checkboxes">
							<div id="marketing_info">
								<label class="default_checkbox default_small_checkbox">
									<input class="marketing_info" value="1" name=""  type="checkbox">
									<small class="checkmark"></small>
									<span class="default_terms_text"><?php echo $this->config->item('signup_page_signup_form_marketing_agreement_disclaimer_txt'); ?>
									</span>
								</label>
							</div>
							<div id="privacy-info">
								<label class="default_checkbox default_small_checkbox">
									<input class="checked_input" value="1" name=""  type="checkbox">
									<small class="checkmark checkbox_shadow"></small>
									<span class="default_terms_text"><?php echo str_replace(array('{terms_and_conditions_page_url}','{privacy_policy_page_url}'),array(site_url($this->config->item('terms_and_conditions_page_url')),site_url($this->config->item('privacy_policy_page_url'))),$this->config->item('signup_page_signup_form_disclaimer_txt')); ?>
									<!--By registering you confirm that you accept the Terms and Conditions and Privacy Policy.-->
									</span>
								</label>
							</div>
						</div>
						<div id="button_div">	
							<button type="submit" id="submit-check" class="btn blue_btn default_btn"><?php echo $this->config->item('signup_btn_txt'); ?><!--Register--></button>
						</div>	
					 <?php echo form_close (); ?>

					<!--<p id="privacy-info">
						<label class="default_checkbox default_small_checkbox">
							<input class="checked_input" value="1" name=""  type="checkbox">
							<small class="checkmark"></small>
							<span class="default_terms_text"><?php //echo str_replace(array('{terms_and_conditions_page_url}','{privacy_policy_page_url}'),array(site_url($this->config->item('terms_and_conditions_page_url')),site_url($this->config->item('privacy_policy_page_url'))),$this->config->item('signup_page_signup_form_disclaimer_txt')); ?>
							</span>
						</label>
					</p>-->

					<!--<div id="login-area" class="login_top_margin">-->
					<div id="login-area">
						<h3 class="default_black_regular_xl"><?php echo $this->config->item('signup_page_signup_form_already_have_an_account_txt'); ?><!--Already have an account?--></h3>
						<a href="<?php echo site_url ($this->config->item('signin_page_url')) ?>" class="btn blue_btn default_btn"><?php echo $this->config->item('signin_btn_txt'); ?><!--Log In--></a>
					</div>
				</div>		
			</div>		
        </div>

        <!-- <div class="col-xl-6 col-lg-6">
			<div id="bussines-side">
				<div class="content"></div>
			</div>
        </div> -->
    </div>
</div>

<script>
	var only_letters_validation_signup_message = "<?php echo $this->config->item('only_letters_validation_signup_message'); ?>";
	var first_name_validation_signup_message = "<?php echo $this->config->item('first_name_validation_signup_message'); ?>";
	var last_name_validation_signup_message = "<?php echo $this->config->item('last_name_validation_signup_message'); ?>";
	var gender_validation_signup_message = "<?php echo $this->config->item('gender_validation_signup_message'); ?>";
	var email_validation_signup_message = "<?php echo $this->config->item('email_validation_signup_message'); ?>";
	var company_name_validation_signup_message = "<?php echo $this->config->item('company_name_validation_signup_message'); ?>";
	var profile_name_validation_signup_message = "<?php echo $this->config->item('profile_name_validation_signup_message'); ?>";
	var password_validation_signup_message = "<?php echo $this->config->item('password_validation_signup_message'); ?>";
	var email_address_already_exists_signup_message = "<?php echo $this->config->item('email_address_already_exists_signup_message'); ?>";
	var valid_email_validation_signup_message = "<?php echo $this->config->item('valid_email_validation_signup_message'); ?>";
	var password_characters_min_length_validation_signup_message = "<?php echo $this->config->item('password_characters_min_length_validation_signup_message'); ?>";
	var password_min_length_character_limit_validation_signup = "<?php echo $this->config->item('password_min_length_character_limit_validation_signup'); ?>";
	
	
</script>
<script src="<?php echo JS; ?>cdn/xregexp.js" ></script>
<script src="<?php echo JS; ?>modules/signup.js"></script>