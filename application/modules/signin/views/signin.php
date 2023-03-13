<div class="container-fluid" id="login-page">
    <div class="row signinMargin">
        <!-- <div class="col-xl-6 col-lg-6" id="login-side"> -->
        <div class="col-xl-12 col-lg-12 signinPadding" id="login-side">
            <header>
				<a class="navLogo" href="
					<?php 
					if(validate_session ()) 
					{ 
					echo base_url().'dashboard'; 
					} else {
					echo base_url();
					} 
					?>">

					<img src="<?=ASSETS?>images/site-inner-logo.png" alt="logo">
				</a>
            </header>

            <div class="content signinSignupContent">
            	<h1 class="default_black_regular_xxl signinSignup_borderGap"><?php echo $this->config->item('signin_page_signin_to_account_heading_txt'); ?><!--Sign in to your Travai account--></h1>
                <form id="logform" onsubmit="return false;" method="POST" accept-charset="utf-8" novalidate>
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
                    <div class="row logFormRow">
                        <div class="col-md-6 col-sm-6 col-12">
                            <div class="form-group default_login_input">
                                <label class="default_black_bold_medium "><?php echo $this->config->item('signin_page_email_address_label_txt'); ?><!--Email address--></label>
								<?php if ($this->session->userdata ('switch_account_email')){ ?>
                                <input type="email" value="<?php echo $this->session->userdata ('switch_account_email') ?>" class="form-control avoid_space login_register_input_field" name="email" id="email" autofocus>
								<?php 
									$this->session->unset_userdata ('switch_account_email');
									
								}else{?>
								 <input type="email" class="form-control avoid_space login_register_input_field" name="email" id="email">
								<?php
									
								}
								?>
                                <!-- <span id="email_error" class="error-msg13"></span> -->
								<div class="error_div_sectn clearfix" id="email_error"></div>
								<?php
								if($this->session->userdata('last_redirect_url')) {
									$last_redirect_url  =  $this->session->userdata('last_redirect_url');
									$this->session->unset_userdata('last_redirect_url');
								}else{
									$last_redirect_url  =  '';
								}
								?>
								<input type="hidden" name="last_redirect_url" value="<?php echo $last_redirect_url; ?>">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-12">
                            <div class="form-group default_login_input">
                                <label class="default_black_bold_medium "><?php echo $this->config->item('signin_page_password_label_txt'); ?><!--Password--></label>
								<div class="input-group">
									<input type="password" class="form-control avoid_space login_register_input_field" name="password" id="password">
									<div class="input-group-append">
										<span class="input-group-text"><i class="fa fa-eye" aria-hidden="true" id="passwordReset"></i></span>
									</div>
								</div>
								<div class="error_div_sectn clearfix" id="password_error"></div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" onclick="login();" class="btn blue_btn default_btn login_button"><?php echo $this->config->item('signin_btn_txt'); ?><!--Login--></button>
                </form>

                <div class="row" id="login-type-separator">
                    <div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-xs-5">
                        <hr>
                    </div>

                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-xs-2">
                        <span><?php echo $this->config->item('or'); ?><!--or--></span>
                    </div>

                    <div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-xs-5">
                        <hr>
                    </div>
                </div>
				
                <div class="row" id="social-media-login-area">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12" id="facebook">
                        <button onclick="loginWithFacebook()" class="btn facebook_button default_facebook_btn default_btn">
                            <i class="fab fa-facebook-f"></i>
                            <?php echo $this->config->item('signin_page_signin_with_facebook_account_btn_txt'); ?><!--Login with Facebook-->
                        </button>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12" id="linkedin">
						<button class="btn linkdin_button default_linkedin_btn default_btn"  onclick="loginWithLinkedIn()">
                            <i class="fab fa-linkedin-in"></i>
                            <?php echo $this->config->item('signin_page_signin_with_linkedin_account_btn_txt'); ?><!--Login with LinkedIn-->
                        </button>
                        
                    </div>
                </div>
                <div class="row" id="forgot-password-link">
                            <div class="form-group col-md-12 privacy-consent-agreement default_terms_text">
                                <div class="default_checkbox default_small_checkbox">
                                    <input class="checked_input" value="1" name=""  type="checkbox" checked>
                                    <small class="checkmark"></small>
                                </div><?php echo str_replace(array('{terms_and_conditions_page_url}','{privacy_policy_page_url}'),array(site_url($this->config->item('terms_and_conditions_page_url')),site_url($this->config->item('privacy_policy_page_url'))),$this->config->item('signin_page_disclaimer_txt')); ?>
                            </div>
                            <div class="col-md-12 forgot_password_sectn">
                                <p class="default_black_regular_large"><!--Forgot your password?--><?php echo $this->config->item('signin_page_forget_password_label_txt'); ?> <a href="<?php echo base_url().$this->config->item('reset_login_password_page_url'); ?>"><?php echo $this->config->item('signin_page_reset_password_txt'); ?><!--Reset now--></a></p>
                            </div>
                        </div>
                        <div id="register-area">
                            <h3 class="default_black_regular_xl"><?php echo $this->config->item('signin_page_user_do_not_have_account_heading_txt'); ?><!--Don't have an account yet?--></h3>
                            <a class="btn blue_btn default_btn"    href="<?php echo site_url ($this->config->item('signup_page_url')) ?>"><?php echo $this->config->item('signup_btn_txt'); ?><!--Register now--></a>
                        </div>
                    </div>
                </div>

        <!-- <div class="col-xl-6 col-lg-6" id="bussines-side"> -->
            <!--<div class="content">
            </div>-->
        <!-- </div> -->
    </div>
</div>
<script>
	$(document).ready(function(){ 
        $('#email').focus();
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
	});
</script>	