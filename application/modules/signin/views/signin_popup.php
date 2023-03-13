<!-- Modal Header -->
<div class="modal-header popup_header popup_header_without_text">
	<h4 class="modal-title popup_header_title"></span></h4>
	<button type="button" class="close login_popup_close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
</div>
<!-- Modal body -->
<form id="login_popup_form" onsubmit="return false;" method="POST" accept-charset="utf-8" novalidate>
	<input type="hidden" name="page_id" id="page_id" value="<?php echo $page_id; ?>" />
	<input type="hidden" name="page_type" id="page_type" value="<?php echo $page_type; ?>" />
	
	<div class="modal-body">
		<div class="popup_body_bold_title"><h1 class="default_black_regular_xxl signinSignup_borderGap"><?php echo $this->config->item('signin_page_signin_to_account_heading_txt'); ?><!--Sign in to your Travai account--></h1></div>
		<label class="errAlert">
		<?php 
				if($this->session->userdata('linkedin_signin_error')) {
		?>
		<span id="wrong_email_password_error" class="error-msg5 error alert-error alert text-center login_error" ><span class="error-msg"><?php echo $this->session->userdata('linkedin_signin_error'); ?></span></span>
		<?php
				} else {
		?>
		<span id="wrong_email_password_error" class="error-msg5 error alert-error alert text-center" style="display:none"></span>
		<?php
				}
		?>
			
		</label>
		<div class="row signinModalRow">
			<div class="col-md-6 col-sm-6 col-12 signinModalColumn">
				<div class="form-group default_login_input">
					<label for="usr" class="default_black_bold_medium "><?php echo $this->config->item('signin_page_email_address_label_txt'); ?></label>
					<div class="input-group">
						<input type="text" tabindex="1" class="form-control avoid_space login_register_input_field" name="email" id="email">
						<!-- <span id="email_error" class="error-msg13 errMesg"></span> -->
					</div>
					<div class="error_div_sectn clearfix" id="email_error"></div>
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-12 signinModalColumn">
				<div class="form-group default_login_input">
					<label for="usr" class="default_black_bold_medium "><?php echo $this->config->item('signin_page_password_label_txt'); ?></label>
					<div class="input-group">
						<input type="password" tabindex="2" class="form-control avoid_space login_register_input_field" name="password" id="password">
						<div class="input-group-append">
							<span class="input-group-text"><i class="fa fa-eye" aria-hidden="true"  id="passwordReset"></i></span>
						</div>
					</div>
					<div class="error_div_sectn clearfix" id="password_error"></div>
				</div>
			</div>
		</div>
		
		<div class="subButton">
			<button type="submit" tabindex="3" class="btn login_button input_button blue_btn default_btn"><?php echo $this->config->item('signin_btn_txt'); ?></button>
		</div>
		<!-- <div class="nebo">
			<small><?php echo $this->config->item('or'); ?></small>
			<span></span>
		</div> -->
		<div class="row loginModal_nebo">
			<div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-xs-5 neboLeft">
				<hr>
			</div>
			<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-xs-2 neboMiddle">
				<span><?php echo $this->config->item('or'); ?><!--or--></span>
			</div>
			<div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-xs-5 neboRight">
				<hr>
			</div>
		</div>
		<div class="row loginModalOnly">
			<div class="col-md-6 col-sm-6 col-12 signinModalColumn <?php echo (in_array($page_type, ['post_publish_project', 'post_draft_project']) ? 'logged_off_post_project_fb_btn' : '') ?>">
				<button type="button" class="btn input_button default_btn default_facebook_btn facebook_button" tabindex="4" onclick="loginWithFacebook()">
					<i class="fab fa-facebook-f" aria-hidden="true"></i>
					<?php echo $this->config->item('signin_page_signin_with_facebook_account_btn_txt'); ?>
				</button>
			</div>
			<div class="col-md-6 col-sm-6 col-12 signinModalColumn <?php echo (in_array($page_type, ['post_publish_project', 'post_draft_project']) ? 'd-none' : '') ?>">
				<button type="button" class="btn input_button default_linkedin_btn default_btn linkdin_button" tabindex="5" onclick="loginWithLinkedIn()">
					<i class="fab fa-linkedin-in" aria-hidden="true"></i>
					<?php echo $this->config->item('signin_page_signin_with_linkedin_account_btn_txt'); ?>
				</button>
			</div>
		</div>
		<div class="row signinModalRow" id="forgot-password-link">
			<div class="form-group col-md-12 privacy-consent-agreement default_terms_text signinModalColumn">
				<div class="default_checkbox default_small_checkbox">
						<input class="checked_input" value="1" name=""  type="checkbox" checked>
						<small class="checkmark"></small>
				</div><?php echo str_replace(array('{terms_and_conditions_page_url}','{privacy_policy_page_url}'),array(site_url($this->config->item('terms_and_conditions_page_url')),site_url($this->config->item('privacy_policy_page_url'))),$this->config->item('signin_page_disclaimer_txt')); ?>
			</div>
			<div class="col-md-12 forgot_password_sectn signinModalColumn">
				<p class="default_black_regular_large"><!--Forgot your password?--><?php echo $this->config->item('signin_page_forget_password_label_txt'); ?> <a href="<?php echo base_url().$this->config->item('reset_login_password_page_url'); ?>"><?php echo $this->config->item('signin_page_reset_password_txt'); ?><!--Reset now--></a></p>
			</div>
		</div>
		<div class="namSup">
			<h3 class="default_black_regular_xl"><?php echo $this->config->item('signin_page_user_do_not_have_account_heading_txt'); ?></h3>
			<!-- <a href="#">Zaregistrovat se</a> -->
			<button type="button" class="btn default_btn blue_btn register_button"><?php echo $this->config->item('signup_btn_txt'); ?></button>
		</div>
	</div>
</form>	
<script>
	<?php 
			if($this->session->userdata('linkedin_signin_error')) {
	?>
	setTimeout(function () {
			$("#wrong_email_password_error").removeClass('login_error');
	}, 5000);
	<?php
					$this->session->unset_userdata('linkedin_signin_error');
			}
	?>

	
</script>