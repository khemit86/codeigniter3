<?php
$automatic_request_date = $result[0]->automatic_verification_code_generate_time!= NULL ? strtotime ($result[0]-> automatic_verification_code_generate_time) : 0;

$next_automatic_request_date = $result[0]->next_automatic_verification_code_generate_time != NULL ? strtotime ($result[0]->next_automatic_verification_code_generate_time) : 0;

$request_date =  $result[0]->verification_code_manual_request_time 	 != NULL ? strtotime ($result[0]->verification_code_manual_request_time) : 0;

$next_request_date =  $result[0]->next_available_verification_code_manual_request_time 	 != NULL ? strtotime ($result[0]->next_available_verification_code_manual_request_time) : 0;

$account_registration_date = $result[0]->account_registration_date 	 != NULL ? strtotime ($result[0]->account_registration_date) : 0;

/* if(($account_registration_date == $automatic_request_date) && $is_manual_requested == 'yes' && $expired == 'no' )
{ */
if(($account_registration_date == $automatic_request_date) && $request_date == 0  && $expired == 'no')
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
}  else if($is_manual_requested == 'yes' && $expired == 'yes'){	


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


}elseif($is_manual_requested == 'no' && $expired == 'no' &&  $automatic_request_date > $request_date){ 

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
	
}elseif($is_manual_requested == 'no' && $expired == 'no' &&  $automatic_request_date < $request_date)
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
		echo '<div class="message-div">';
		if($next_request_date < strtotime ($result[0]->account_expiration_date)){
			
			echo str_replace(array("{verification_code_manual_request_date}","{verification_code_manual_request_time}","{next_request_generated_time}"),array(date(DATE_FORMAT,strtotime($result[0]->verification_code_manual_request_time)),date(TIME_FORMAT,strtotime($result[0]->verification_code_manual_request_time)),$next_request),$this->config->item('signup_verify_page_newly_registered_user_verification_code_expired_not_received_verification_code_by_automatically_cron_and_send_manual_request_code_option_not_available_manual_request_code_option_reminder_message_txt'));
		}
		
		if($next_automatic_request_date < strtotime ($result[0]->account_expiration_date)){
			echo str_replace(array("{signup_automatic_send_reminder_to_unverified_user_set_interval}","{send_reminder_verification_code_email_subject}"),array($signup_automatic_send_reminder_to_unverified_user_set_interval,$send_reminder_verification_code_email_subject),$this->config->item('signup_verify_page_newly_registered_user_verification_code_expired_not_received_verification_code_by_automatically_cron_and_send_manual_request_code_option_not_available_message_txt'));
			echo str_replace(array("{automatic_request}"),array($automatic_request),$this->config->item('signup_verify_page_newly_registered_user_verification_code_expired_not_received_verification_code_by_automatically_cron_and_send_manual_request_code_option_not_available_automatically_cron_reminder_message_txt'));
		}
		echo '</div>';
	}
}
?>		
                <!--  <p>Chcete-li zajistit doručování e-mailů (případně spadajících emailů do složky Spam) od Travai.cz,
                     přidejte si doménu *@travai.cz* do adresáře kontaktů Vaší e-mailové schránky.</p>-->
                 <?php /* <form action="<?php echo VPATH.$uri; ?>"> */ ?>
                    <div class="col-md-12 another-field">
                        <div class="input-group col-md-4">
                            <div class="input-group-prepend" <?php echo $expired == 'no' ? '' : 'style="display:none"'; ?>>
                                <span class="input-group-text" id="basic-addon1"><?php echo $this->config->item('signup_verify_page_code_txt'); ?></span>
                            </div>
                           <input id="input_verification_code" class="form-control" type="<?php echo $expired == 'no' ? 'text' : 'hidden'; ?>" name="verify" value="" onfocus="validator(this);" onblur="validator(this);" onkeyup="validator(this);" onchange="validator(this);">
                        </div>
						<?php //if(!empty($msg)){ ?>
						<div class="custom_message default_success_green_message" style="display:<?php echo ($expired == 'no')?'flex':'none'; ?>"><?php echo ($msg != '1')?$msg:''; ?></div>
						<?php //} ?>
                        <a class="btn default_btn red_btn resend-btn-acti" href="<?php echo VPATH.$this->config->item('logout_page_url'); ?>"><!--Log Out--><?php echo $this->config->item('signout_btn_txt'); ?></a>
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
<?php 
	$uid = $user_id;

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
	if(!empty($nextintervel)){
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
