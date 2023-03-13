<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//country name which come first into drop down on education training/work experience
$config['countries_drop_down_top_displayed_option_country_db_id']  = 40;
$config['mother_tongue_language_drop_down_top_displayed_option_language_db_id']  = 16;

// this config always hold the czech country id. We are matching this config value with country selected on address page of account management. if selected country not equal to config then county/locality will be 0.This config also using for profile completion calculation for column country_is_cz based on config update profile completion parameters
$config['reference_country_id']  = 40;


$config['reference_id_digits_limit'] = 10;
$config['vat_percentage'] = 21;


// this config are using for hide the notification message automatically 
$config['notification_messages_timeout_interval'] = 5; //in seconds

// this config is using to set the life time of cookie for 365 days regarding banner shows on logged out page
$config['accept_cookies_banner_lifetime'] = 365;// in days

$config['FB_APPID'] = "414737902829697"; // Used in /application/views/header.php
$config['FB_API_VERSION'] = "v8.0"; // Used in /application/views/header.php

$config['LINKEDIN_CLIENT_ID'] = "78sfpld3j5lwt1"; 
$config['LINKEDIN_CLIENT_SECRETE'] = "tnrxDyrdbCqDlArJ"; 

$config['twitter_share_project_description_character_limit'] = 70;
$config['twitter_share_user_profile_description_character_limit'] = 70;


$config['facebook_and_linkedin_share_project_description_character_limit'] = 250;
$config['facebook_and_linkedin_share_user_profile_description_character_limit'] = 250;

?>