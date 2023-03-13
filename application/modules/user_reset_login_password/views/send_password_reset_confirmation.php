<div class="container-fluid passwordResetConfirm" id="send-reset-confimation-page">
    <div class="headerTop row">
        <div class="col-xl-12 col-lg-12" id="send-reset-confimation-inner">
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

					<img src="<?php echo site_url ('assets/images/site-inner-logo.png'); ?>" />
				</a>
            </header>
		</div>
	</div>
	<div class="content">
		<div class="content-inner">
			<!--
			<div id="global-error">
				<div role="alert" class="alert error error-msg" id="control_gen_1" style="visibility: hidden;;min-height: 45px;">
				</div>
			</div>-->
			<div class="row">
				<div class="col-md-12" id="reset_content">
					<div id="reset" class="reset text-center">
						<!--<h2>--><?php //echo $name ?><!--, a reset password link was successfully sent <span id="password_request_time_counter"></span> ago, to the email address <strong>--><?php //echo $user_email; ?><!--</strong>.</h2>-->
						<?php echo str_replace(array('{user_first_name_or_company_name}','{user_email}'),array($name,$user_email),$this->config->item('password_reset_confirmation_page_heading_txt'))?>
						<!--<h3>Please check your email and follow the instructions. If you don’t see our email, check your Spam folder.</h3>-->
						
						
						<?php
						
						/* <h3>Zkontrolujte email a postupujte podle pokynů. Pokud náš email nedorazil, podívejte se do složky Spam.</h3>
						<h4>Žádost pro obnovení hesla může být provedena každé<!--There can be one single password reset request done every-->
						<?php
						$forgot_password_token_request_time_set_interval = $this->config->item('forgot_password_token_request_time_set_interval');
						$forgot_password_token_request_time_set_interval = $forgot_password_token_request_time_set_interval/60;
						if($forgot_password_token_request_time_set_interval > 1){
							echo $forgot_password_token_request_time_set_interval."&nbsp;hodiny";
						}else{
							echo $forgot_password_token_request_time_set_interval."&nbsp;hodina";
						}
						?>
							Zbývající čas <span id="next_password_request_countdown"></span> do další možné žádosti.
						</h4> */
						$forgot_password_token_request_time_set_interval = $this->config->item('forgot_password_token_request_time_set_interval');
						$forgot_password_token_request_time_set_interval = $forgot_password_token_request_time_set_interval*60;
						 $sub_heading = $this->config->item('password_reset_confirmation_page_sub_heading_txt');
						 echo str_replace(array('{next_password_reset_request_interval}'),array(secondsToWordsResponsive($forgot_password_token_request_time_set_interval)), $sub_heading );
						?>
						<p><?php echo str_replace(array('{signin_page_url}'),array($this->config->item('signin_page_url')),$this->config->item('password_reset_confirmation_page_continue_click_here_txt'))?></p>
						
						<!--<div class="split-disclaimer">
							<p style="padding-top: 20px;"></p>
							<p></p>
						</div>
						<div class="form-actions text-center">
							<div class="error_body_sectn">
								<h3 style="margin-top: -39px !important;"><?php //echo $this->config->item('continue_click_here_txt'); ?></h3>
							</div>
							
						</div>-->
					</div>
				</div>
			</div>
		</div> 
	</div>
</div>
<script>
	var passwordRequestNow = <?php echo time(); ?> * 1000;
	var countDownPasswordRequestDate =  <?php echo $password_reset_token_request_time; ?> * 1000;
	var now = <?php echo time(); ?> * 1000;
	var countDownNextPasswordRequestDate = <?php echo $next_available_password_reset_token_request_time; ?> * 1000;
	var forgot_password_page_url = '<?php echo $forgot_password_page_url; ?>';
	
	// for hours
	var one_hour = '<?php echo $this->config->item('1_hour') ?>';
	var two_four_hours = '<?php echo $this->config->item('2_4_hours') ?>';
	var more_than_or_equal_five_hours = '<?php echo $this->config->item('more_than_or_equal_5_hours') ?>';
	
	// for minutes
	var one_minute = '<?php echo $this->config->item('1_minute') ?>';
	var two_four_minutes = '<?php echo $this->config->item('2_4_minutes') ?>';
	var more_than_or_equal_five_minutes = '<?php echo $this->config->item('more_than_or_equal_5_minutes') ?>';
	
	// for seconds
	
	var one_second = '<?php echo $this->config->item('1_second') ?>';
	var two_four_seconds = '<?php echo $this->config->item('2_4_seconds') ?>';
	var more_than_or_equal_five_seconds = '<?php echo $this->config->item('more_than_or_equal_5_seconds') ?>';
	
	// for days
	
	var one_day = '<?php echo $this->config->item('1_day') ?>';
	var two_four_days = '<?php echo $this->config->item('2_4_days') ?>';
	var more_than_or_equal_five_days = '<?php echo $this->config->item('more_than_or_equal_5_days') ?>';
	
</script>	
<script src="<?php echo JS; ?>modules/reset_password_confirmation.js"></script>