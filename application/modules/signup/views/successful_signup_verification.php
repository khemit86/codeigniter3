<div class="container-fluid successfulSignupVerification" id="successful-signup-verification-confimation-page">
   <div class="row">
      <div class="col-xl-12 col-lg-12" id="successful-signup-verification-inner">
         <header>
            <a class="navLogo redirection">
            <img src="<?php echo site_url ('assets/images/site-inner-logo.png'); ?>" />
            </a>
         </header>
      </div>
	</div>
	<div class="content">
		<div class="content-inner">
		   <div id="reset" class="reset text-center">
			  <div class="col-md-12 middile-div">
				 <!-- <i class="far fa-check-circle"></i> -->
				 <h1 class="default_bigger_heading_blue"><?php echo $this->config->item('successful_signup_verification_page_heading'); ?></h1>
				 <?php echo str_replace(array("{user_first_name_or_company_name}","{newly_registered_account_user_email}"),array($name,$email,$this->config->item('successful_signup_verification_page_continue_click_dasboard_txt')),$this->config->item('successful_signup_verification_page_message_txt')); ?>
				 
				 <!--<form>
					<div class="col-md-12 another-field">
					   <div class="cstm-tp">
							<p>--><?php //echo str_replace("{redirection_url_to_dashboard}",site_url ('signup/set_session_after_verification').'?'.$this->config->item('signup_page_success_parameter')."=".$last_insert_id,$this->config->item('successful_signup_verification_page_continue_click_dasboard_txt')); ?><!--</p>-->
							<?php
						
						 /*  <a class="btn blue_btn default_btn" href="<?php echo site_url ('signup/set_session_after_verification').'?'.$this->config->item('signup_page_success_parameter')."=".$last_insert_id ?>">
							  <!--Dashboard--><?php echo $this->config->item('successful_signup_verification_page_dashboard_button_txt'); ?>
						  </a> */
						  ?>
						 <!-- 
					   </div>
					</div>
				 </form>--> 
			  </div>
		   </div>
		</div>
	</div>
</div>
<script>
$(document).on('click', '.redirection', function () {
	var redirection = '<?php echo site_url ('signup/set_session_after_verification').'?'.$this->config->item('signup_page_success_parameter')."=".$last_insert_id ?>';
	window.location.replace(redirection);

});
</script>
