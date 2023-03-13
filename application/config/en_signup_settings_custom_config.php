<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['pa_first_name_maximum_length_character_limit_signup_page'] = 30;
$config['pa_last_name_maximum_length_character_limit_signup_page'] = 30;

$config['ca_company_name_maximum_length_character_limit_signup_page'] = 50;

$config['password_min_length_character_limit_validation_signup'] = 6;

$config['password_signup_message_tooltip'] = 'password must contain at least '.$config['password_min_length_character_limit_validation_signup'].' characters';// This message display in tooltip beside password label on signup form

$config['password_characters_min_length_validation_signup_message'] = 'your password must be at least '.$config['password_min_length_character_limit_validation_signup'].' characters'; // This message on signup form when user entered password less than specified limit
/*
|---------------------------------------------------------------------------------------------------
| Time Config Variables for Signup Process(In minutes)
| if any parameter changed run $pm2 reload all for node to consider the changes / 0 downtime needed
|---------------------------------------------------------------------------------------------------
| 
*/
/* Filename: application\modules\signup\controllers\Signup.php Method name: signup_verification */
$config['signup_verified_code_expire_set_interval'] = 120;

/* Filename: application\modules\cron\controllers\Cron.php Method name: send_reminder_to_unverified_users */
$config['signup_automatic_send_reminder_to_unverified_user_set_interval'] = 120;

/* Filename: application\modules\cron\controllers\Signup.php Method name: signup_verification */
$config['signup_verification_code_manual_request_time_set_interval'] = 60;

/* Filename: application\modules\cron\controllers\Cron.php Method name: remove_unverified_users */
$config['signup_unverified_user_remove_set_interval'] = 7200;

?>