<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
	
$config['pa_first_name_maximum_length_character_limit_signup_page'] = 30;
$config['pa_last_name_maximum_length_character_limit_signup_page'] = 30;

$config['ca_company_name_maximum_length_character_limit_signup_page'] = 50;

$config['password_min_length_character_limit_validation_signup'] = 6;

$config['password_signup_message_tooltip'] = 'heslo musí obsahovat nejméně '.$config['password_min_length_character_limit_validation_signup'].' znaků';

$config['password_characters_min_length_validation_signup_message'] = 'heslo musí obsahovat nejméně '.$config['password_min_length_character_limit_validation_signup'].' znaků';
################ Defined the signup form validation regarding user signup form end here

/*
|---------------------------------------------------------------------------------------------------
| Time Config Variables for Signup Process(In minutes)
| if any parameter changed run $pm2 reload all for node to consider the changes / 0 downtime needed
|---------------------------------------------------------------------------------------------------
| 
*/
/* Filename: application\modules\signup\controllers\Signup.php Method name: signup_verification */
//all times from below are refferenced in minutes
$config['signup_verified_code_expire_set_interval'] = 5;// in minutes - on live server must be set 120 (after 2 hrs)
/* Filename: application\modules\cron\controllers\Cron.php Method name: send_reminder_to_unverified_users */
$config['signup_automatic_send_reminder_to_unverified_user_set_interval'] = 10;// in minutes - on live server must be set 1440 -  every 24 hrs
/* Filename: application\modules\cron\controllers\Signup.php Method name: signup_verification */
$config['signup_verification_code_manual_request_time_set_interval'] = 8;// in minutes - on live server must be set 360 (every 6 hrs)
/* Filename: application\modules\cron\controllers\Cron.php Method name: remove_unverified_users */
$config['signup_unverified_user_remove_set_interval'] = 7200;// in minutes - on live server must be set 7200 - after 5 days

?>