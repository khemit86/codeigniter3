<div class="container-fluid signupVerifyPage" id="successful-registration-confimation-page">
<div class="row">
  <div class="col-xl-12 col-lg-12" id="successful-registration-inner">
	 <header>
		<a class="navLogo" href="
		   <?php echo base_url(); ?>">
			<img src="<?php echo site_url ('assets/images/site-inner-logo.png'); ?>" />
		</a>
	 </header>
  </div>
</div>
		
<div class="content">
	<div class="content-inner">
		<div class="col-md-12 middile-div text-center">
				<h1 class="verify-head default_black_regular_xxl"><?php echo str_replace("{user_first_name_last_name_or_company_name}",$name,$this->config->item("signup_verify_page_heading_txt")); ?></h1>
				<?php
				$account_expiration_time = $this->config->item('signup_unverified_user_remove_set_interval');
				$account_expiration_time = $account_expiration_time/60;
				
				if($result[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE && $result[0]->is_authorized_physical_person == 'N'){
					if($result[0]->gender == 'M')
					{
						$signup_sub_heading = $this->config->item('signup_verify_page_sub_heading_txt_male');
					
					}else{
						$signup_sub_heading = $this->config->item('signup_verify_page_sub_heading_txt_female');
					}
				
				}else if($result[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $result[0]->is_authorized_physical_person == 'Y'){
					if($result[0]->gender == 'M')
					{
						$signup_sub_heading = $this->config->item('signup_verify_page_sub_heading_txt_app_male');
					
					}else{
						$signup_sub_heading = $this->config->item('signup_verify_page_sub_heading_txt_app_female');
					}
				}else{
					$signup_sub_heading = $this->config->item('signup_verify_page_sub_heading_txt_company');
				}
				echo str_replace(array("{newly_registered_account_registration_date}","{newly_registered_account_user_email}","{newly_registered_account_confirmation_expiration_time}"),array(date(DATE_TIME_FORMAT,strtotime($result[0]->account_registration_date)),$result[0]->email,$account_expiration_time),$signup_sub_heading); ?>
			<div id="signup_verified_update_section">
				<?php
					$automatic_request_date = $result[0]->automatic_verification_code_generate_time!= NULL ? strtotime ($result[0]-> 	automatic_verification_code_generate_time) : 0;

					$next_automatic_request_date = $result[0]->next_automatic_verification_code_generate_time != NULL ? strtotime ($result[0]->next_automatic_verification_code_generate_time) : 0;

					$request_date =  $result[0]->verification_code_manual_request_time 	 != NULL ? strtotime ($result[0]->verification_code_manual_request_time) : 0;

					$next_request_date =  $result[0]->next_available_verification_code_manual_request_time 	 != NULL ? strtotime ($result[0]->next_available_verification_code_manual_request_time) : 0;
					
					$account_registration_date = $result[0]->account_registration_date 	 != NULL ? strtotime ($result[0]->account_registration_date) : 0;
					
					 
					
					if(($account_registration_date == $automatic_request_date) && $request_date == 0  && $expired == 'no')
					//if(($account_registration_date == $automatic_request_date) && $is_manual_requested == 'yes' && $expired == 'no' )
					{
					
						if($result[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE)
						{
							if($result[0]->gender == 'M')
							{
								$registartion_email_subject = $this->config->item('welcome_email_subject_signup_personal_male');
							}else{
								$registartion_email_subject =  $this->config->item('welcome_email_subject_signup_personal_female');
							}
						
						
						}else{
							$registartion_email_subject = $this->config->item('welcome_email_subject_signup_company');
						}
						echo str_replace("{registartion_email_subject}",$registartion_email_subject,$this->config->item('signup_verify_page_newly_registered_user_valid_verification_code_and_send_manual_request_code_option_available_message_txt'));
					
					}elseif($is_manual_requested == 'yes' && $expired == 'yes'){
						
						if($result[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE)
						{
							if($result[0]->gender == 'M')
							{
								$send_mannual_verification_code_email_subject = $this->config->item('email_subject_unverified_account_manual_request_verification_code_personal_male');
							}else{
								$send_mannual_verification_code_email_subject =  $this->config->item('email_subject_unverified_account_manual_request_verification_code_personal_female');
							}
						
						
						}else
						{
							$send_mannual_verification_code_email_subject = $this->config->item('email_subject_unverified_account_manual_request_verification_code_company');
						}
						$signup_automatic_send_reminder_interval = $this->config->item('signup_automatic_send_reminder_to_unverified_user_set_interval');
						$signup_automatic_send_reminder_interval = $signup_automatic_send_reminder_interval/60;
						
						echo str_replace(array("{newly_registered_account_user_email}","{send_manual_verification_code_email_subject}"),array($result[0]->email,$send_mannual_verification_code_email_subject),$this->config->item('signup_verify_page_newly_registered_user_verification_code_expired_and_send_manual_request_code_option_available_message_txt'));
						if($next_automatic_request_date < strtotime ($result[0]->account_expiration_date)){
							echo str_replace(array("{signup_automatic_send_reminder_interval}"),array($signup_automatic_send_reminder_interval),$this->config->item('signup_verify_page_newly_registered_user_verification_code_expired_and_send_manual_request_code_option_available_reminder_message_txt'));
						}
						
					
					}elseif($is_manual_requested == 'yes' && $expired == 'no' && $automatic_request_date < $request_date){ 
					
					
						if($result[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE){
							if($result[0]->gender == 'M'){
								$send_mannual_verification_code_email_subject = $this->config->item('email_subject_unverified_account_manual_request_verification_code_personal_male');
							}else{
								$send_mannual_verification_code_email_subject =  $this->config->item('email_subject_unverified_account_manual_request_verification_code_personal_female');
							}
						
						
						}else{
							$send_mannual_verification_code_email_subject = $this->config->item('email_subject_unverified_account_manual_request_verification_code_company');
						}
						
						echo str_replace(array("{send_manual_verification_code_email_subject}"),array($send_mannual_verification_code_email_subject),$this->config->item('signup_verify_page_newly_registered_user_generate_verification_code_by_manualy_request_valid_verification_code_and_send_manual_request_code_option_available_message_txt'));
					}elseif($is_manual_requested == 'yes' && $expired == 'no' && $automatic_request_date > $request_date){
						
						if($result[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE){
							if($result[0]->gender == 'M')
							{
								$send_reminder_verification_code_email_subject = $this->config->item('email_subject_unverified_account_reminder_verification_code_personal_male');
							}else{
								$send_reminder_verification_code_email_subject = $this->config->item('email_subject_unverified_account_manual_request_verification_code_personal_female');;
							}
						}else{
							$send_reminder_verification_code_email_subject = $this->config->item('email_subject_unverified_account_reminder_verification_code_company');
						}
				
						echo str_replace("{send_reminder_verification_code_email_subject}",$send_reminder_verification_code_email_subject,$this->config->item('signup_verify_page_newly_registered_user_received_verification_code_by_automatically_cron_valid_verification_code_and_send_manual_request_code_option_available_message_txt'));	

					}
					elseif($is_manual_requested == 'no' && $expired == 'no' &&  $automatic_request_date > $request_date){
					
						if($result[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE){
							if($result[0]->gender == 'M'){
								$send_reminder_verification_code_email_subject = $this->config->item('email_subject_unverified_account_reminder_verification_code_personal_male');
							}else{
								$send_reminder_verification_code_email_subject = $this->config->item('email_subject_unverified_account_manual_request_verification_code_personal_female');;
							}
						}else{
							$send_reminder_verification_code_email_subject = $this->config->item('email_subject_unverified_account_reminder_verification_code_company');
						}
						echo str_replace("{send_reminder_verification_code_email_subject}",$send_reminder_verification_code_email_subject,$this->config->item('signup_verify_page_newly_registered_user_received_verification_code_by_automatically_cron_valid_verification_code_and_send_manual_request_code_option_available_message_txt'));	
					
					}
					elseif($is_manual_requested == 'no' && $expired == 'no' &&  $automatic_request_date < $request_date)
					{
						
						
						if($result[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE){
							if($result[0]->gender == 'M'){
								$send_mannual_verification_code_email_subject = $this->config->item('email_subject_unverified_account_manual_request_verification_code_personal_male');
							}else{
								$send_mannual_verification_code_email_subject =  $this->config->item('email_subject_unverified_account_manual_request_verification_code_personal_female');
							}
						}else{
							$send_mannual_verification_code_email_subject = $this->config->item('email_subject_unverified_account_manual_request_verification_code_company');
						}
						
						echo str_replace("{send_manual_verification_code_email_subject}",$send_mannual_verification_code_email_subject,$this->config->item('signup_verify_page_newly_registered_user_generate_verification_code_by_manualy_request_valid_verification_code_and_send_manual_request_code_option_not_available_message_txt'));
				
					}elseif($is_manual_requested == 'no' && $expired == 'yes'){
						
						if($result[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE){
							if($result[0]->gender == 'M'){
								$send_reminder_verification_code_email_subject = $this->config->item('email_subject_unverified_account_reminder_verification_code_personal_male');
							}else{
								$send_reminder_verification_code_email_subject = $this->config->item('email_subject_unverified_account_manual_request_verification_code_personal_female');;
							}
						}else{
							$send_reminder_verification_code_email_subject = $this->config->item('email_subject_unverified_account_reminder_verification_code_company');
						}
					$signup_automatic_send_reminder_to_unverified_user_set_interval = $this->config->item('signup_automatic_send_reminder_to_unverified_user_set_interval');
					$signup_automatic_send_reminder_to_unverified_user_set_interval = $signup_automatic_send_reminder_to_unverified_user_set_interval/60;
					if($next_request_date < strtotime ($result[0]->account_expiration_date) || $next_automatic_request_date < strtotime ($result[0]->account_expiration_date)){
						echo '<div>';
						
						if($next_request_date < strtotime ($result[0]->account_expiration_date)){
							
							echo str_replace(array("{verification_code_manual_request_date}","{verification_code_manual_request_time}","{next_request_generated_time}"),array(date(DATE_FORMAT,strtotime($result[0]->verification_code_manual_request_time)),date(TIME_FORMAT,strtotime($result[0]->verification_code_manual_request_time)),$next_request),$this->config->item('signup_verify_page_newly_registered_user_verification_code_expired_not_received_verification_code_by_automatically_cron_and_send_manual_request_code_option_not_available_manual_request_code_option_reminder_message_txt'));
						}
						
						
						
						if($next_automatic_request_date < strtotime ($result[0]->account_expiration_date)){
						
							echo str_replace(array("{signup_automatic_send_reminder_to_unverified_user_set_interval}","{send_reminder_verification_code_email_subject}"),array($signup_automatic_send_reminder_to_unverified_user_set_interval,$send_reminder_verification_code_email_subject),$this->config->item('signup_verify_page_newly_registered_user_verification_code_expired_not_received_verification_code_by_automatically_cron_and_send_manual_request_code_option_not_available_message_txt'));
							echo str_replace(array("{automatic_request}"),array($automatic_request),$this->config->item('signup_verify_page_newly_registered_user_verification_code_expired_not_received_verification_code_by_automatically_cron_and_send_manual_request_code_option_not_available_automatically_cron_reminder_message_txt'));
						}
						echo '</div>';
					}	
				}?><!--  <p>Chcete-li zajistit doručování e-mailů (případně spadajících emailů do složky Spam) od Travai.cz,
					 přidejte si doménu *@travai.cz* do adresáře kontaktů Vaší e-mailové schránky.</p>--><?php /* <form action="<?php echo VPATH.$uri; ?>"> */ ?><div class="col-md-12 another-field">
						<div class="col-md-4 input-group">
							<div class="input-group-prepend" <?php echo $expired == 'no' ? '' : 'style="display:none"'; ?>>
								<span class="input-group-text" id="basic-addon1"><?php echo $this->config->item('signup_verify_page_code_txt'); ?></span>
							</div>
						   <input id="input_verification_code" class="form-control" type="<?php echo $expired == 'no' ? 'text' : 'hidden'; ?>" name="verify" value="" onfocus="validator(this);" onblur="validator(this);" onkeyup="validator(this);" onchange="validator(this);">
						</div>
						<div class="custom_message" style="display:<?php echo ($expired == 'no')?'flex':'none'; ?>"></div> 
						<?php //if ($expired == 'no'): ?>						
						<a class="btn default_btn red_btn resend-btn-acti" href="<?php echo VPATH.$this->config->item('logout_page_url'); ?>"><!--Log Out--><?php echo $this->config->item('signout_btn_txt'); ?></a>
					   <?php //endif; ?>
					   <?php
						  $manual_request_button_status = '';
						  if($is_manual_requested == 'no')
						  {
						  $manual_request_button_status = 'disabled="disabled"';
						  }
						  ?>
					   <button class="btn default_btn blue_btn custom_resend_code" <?php echo $manual_request_button_status; ?>><!--Generate Verification Code--><?php echo $this->config->item('signup_verify_page_generate_verification_code_button_txt'); ?></button>
					   <?php if ($expired == 'no'): ?>
					   <button id="validate" class="btn default_btn green_btn" type="button"  disabled="disabled"><!--Validate Code--><?php echo $this->config->item('signup_verify_page_validate_code_button_txt'); ?></button>
					   <?php endif; ?>
					</div>
					
					<?php /* </form> */ ?>
					<?php
					$uid = $_GET[$this->config->item('signup_page_success_parameter')];

				   $signup_verification_code_expiration_set_interval = $this->config->item('signup_verified_code_expire_set_interval')*60;	
				
					$automatic_code_generate_time = $result[0]->automatic_verification_code_generate_time != NULL ? strtotime ($result[0]->automatic_verification_code_generate_time)+$signup_verification_code_expiration_set_interval : 0;  
			
					$code_manual_expire_time = $result[0]->verification_code_manual_request_time != NULL ? strtotime ($result[0]->verification_code_manual_request_time)+$signup_verification_code_expiration_set_interval : 0; 
					
					$code_expiration_time = $result[0]->current_verification_code_expiration_date != NULL ? strtotime ($result[0]->current_verification_code_expiration_date): 0; // 1
					
					
					$code_manual_next_request_time = $result[0]->next_available_verification_code_manual_request_time != NULL ? strtotime ($result[0]->next_available_verification_code_manual_request_time) : 0; //2

					$next_automatic_code_generate_time = $result[0]->next_automatic_verification_code_generate_time != NULL ? strtotime ($result[0]->next_automatic_verification_code_generate_time) : 0; //3 */
					
					$page_auto_refresh_time_array =array($code_expiration_time, $code_manual_next_request_time,$next_automatic_code_generate_time);
					
					$nextintervel = array();
					foreach( $page_auto_refresh_time_array as $single_time ){
						if($single_time >= time()){
							$nextintervel[] = $single_time;
						}
					}
					//$page_refresh_interval = '-1';
					if(!empty($nextintervel)) {
						$page_refresh_interval = strtotime(date('Y-m-d H:i:s'));
						$expiry_interval = min($nextintervel);
						$page_refresh_interval = $expiry_interval;
						$page_refresh_interval = $page_refresh_interval -time();
						$page_refresh_interval += 5 ; 
					}
					?>
					<script type="text/javascript">
					   
					
						var now = <?php echo time(); ?> * 1000;
						var nextManualNow = <?php echo time(); ?> * 1000;
						var nextAutomaticNow = <?php echo time(); ?> * 1000;
						var nextAutoRequestNow = <?php echo time(); ?> * 1000;
						var AutoRequestNow = <?php echo time(); ?> * 1000;
						var manualAutoRequestNow = <?php echo time(); ?> * 1000;
						var page_refresh_interval = <?php echo $page_refresh_interval; ?>;
						var logout_page_url = '<?php echo VPATH.$this->config->item('logout_page_url'); ?>';
						var countDownDate = <?php echo strtotime($result[0]->account_expiration_date); ?> * 1000;
						var countDownAutomaticRequestDate =  <?php echo $automatic_request_date; ?> * 1000;
						var countDownManualRequestDate =  <?php echo $request_date; ?> * 1000;
					   
						<?php if (!empty($result[0]->verification_code_manual_request_time) ){ ?>

						var countDownNextManualRequestDate = <?php echo $next_request_date; ?> * 1000;

						<?php
						   }
						   ?>
						var countDownNextAutomaticRequestDate = <?php echo $next_automatic_request_date; ?> * 1000;

						SITE_URL = '<?php echo site_url (); ?>';
						var uid = '<?php echo $uid;  ?>';
					</script>
					<script src="<?php echo JS; ?>modules/signup_verification.js"></script>
				</div>
				<script>
				 /* coundown script for user expiration account start here */
					if($("#account_expiration_countdown").length > 0)
					{

						// Update the count down every 1 second
						var x = setInterval(function() {
							if($("#account_expiration_countdown").length)
							{
								now = now + 1000;
								// Get todays date and time
								// 1. JavaScript
								// var now = new Date().getTime();
								// 2. PHP

								// Find the distance between now an the count down date
								var distance = countDownDate - now;


								// Time calculations for days, hours, minutes and seconds
								var days = Math.floor(distance / (1000 * 60 * 60 * 24));
								var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
								var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
								var seconds = Math.floor((distance % (1000 * 60)) / 1000);
								hours = (days*24)+hours;
								
								// For hours
								var hourly_txt = '';
								var minutes_txt = '';
								var seconds_txt = '';
								if(hours ==1){
									hourly_txt = '<?php echo $this->config->item('1_hour'); ?>';
								}else if(hours >=2 && hours <= 4){
									hourly_txt = '<?php echo $this->config->item('2_4_hours'); ?>';
								}
								else if(hours > 4){
									hourly_txt = '<?php echo $this->config->item('more_than_or_equal_5_hours'); ?>';
								}
								
								// For minutes
								if(minutes ==1){
									minutes_txt = '<?php echo $this->config->item('1_minute'); ?>';
								}else if(minutes >=2 && minutes <= 4){
									minutes_txt = '<?php echo $this->config->item('2_4_minutes'); ?>';
								}
								else if(minutes > 4){
									minutes_txt = '<?php echo $this->config->item('more_than_or_equal_5_minutes'); ?>';
								}
								
								// For seconds
								if(seconds ==1){
									seconds_txt = '<?php echo $this->config->item('1_second'); ?>';
								}else if(seconds >=2 && seconds <= 4){
									seconds_txt = '<?php echo $this->config->item('2_4_seconds'); ?>';
								}
								else if(seconds > 4){
									seconds_txt = '<?php echo $this->config->item('more_than_or_equal_5_seconds'); ?>';
								}
								
								
								if(days != 0){
									
									if (hours < 10)	hours = "0" + hours;
									if (minutes < 10) minutes = "0" + minutes;
									if (seconds < 10) seconds = "0" + seconds;
									
									document.getElementById("account_expiration_countdown").innerHTML = hours + ":" + minutes + ":" + seconds+ ' '+hourly_txt;
								}
								else if(days == 0 &&  hours > 0 )
								{
									if (hours < 10)	hours = "0" + hours;
									if (minutes < 10) minutes = "0" + minutes;
									if (seconds < 10) seconds = "0" + seconds;
									document.getElementById("account_expiration_countdown").innerHTML = hours + ":" + minutes + ":" + seconds+ ' '+hourly_txt;
								}
								else if(hours == 0 && minutes > 0)
								{
									if (hours < 10)	hours = "0" + hours;
									if (minutes < 10) minutes = "0" + minutes;
									if (seconds < 10) seconds = "0" + seconds;
									document.getElementById("account_expiration_countdown").innerHTML = hours + ":" + minutes + ":" + seconds+ ' '+minutes_txt;
								}
								else if(minutes == 0 && seconds > 0)
								{
									if (hours < 10)	hours = "0" + hours;
									if (minutes < 10) minutes = "0" + minutes;
									if (seconds < 10) seconds = "0" + seconds;
									document.getElementById("account_expiration_countdown").innerHTML = hours + ":" + minutes + ":" + seconds+ ' '+seconds_txt;
								}
								else{
									if (hours < 10)	hours = "0" + hours;
									if (minutes < 10) minutes = "0" + minutes;
									if (seconds < 10) seconds = "0" + seconds;
									//document.getElementById("account_expiration_countdown").innerHTML = days + "d " + hours + " :" + minutes + ":" + seconds+ ' days';
									$("#account_expiration_text").html('');
								}
								//alert(distance);	
								// If the count down is over, write some text
								if (distance <= 0) {
									/* alert(distance);
									alert(logout_page_url); */
								clearInterval(x);
								//document.getElementById("account_expiration_countdown").innerHTML = "EXPIRED";
								$("#account_expiration_text").html('');
								window.location.href = logout_page_url;
								
								}
							}else{
								 clearInterval(x);
							}
								
								
								
								
						}, 1000);

					}
					/* coundown script for user expiration account end here */

				</script>
			</div>
		</div>
	</div>
</div>
<script>
	// for hours
	var one_hour_txt = '<?php echo $this->config->item('1_hour') ?>';
	var two_four_hours_txt = '<?php echo $this->config->item('2_4_hours') ?>';
	var more_than_or_equal_five_hours_txt = '<?php echo $this->config->item('more_than_or_equal_5_hours') ?>';
	
	// for minutes
	var one_minute_txt = '<?php echo $this->config->item('1_minute') ?>';
	var two_four_minutes_txt = '<?php echo $this->config->item('2_4_minutes') ?>';
	var more_than_or_equal_five_minutes_txt = '<?php echo $this->config->item('more_than_or_equal_5_minutes') ?>';
	
	// for seconds
	var one_second_txt = '<?php echo $this->config->item('1_second') ?>';
	var two_four_seconds_txt = '<?php echo $this->config->item('2_4_seconds') ?>';
	var more_than_or_equal_five_seconds_txt = '<?php echo $this->config->item('more_than_or_equal_5_seconds') ?>';
	
</script>	