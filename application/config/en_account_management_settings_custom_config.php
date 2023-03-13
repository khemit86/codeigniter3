<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//Account management avatar page functionalty validation messages and validation limits
$config['user_profile_avatar_upload_max_size_allocation'] = 3; //MB

$config['user_profile_avatar_upload_max_size_validation_message'] = "max allowed size is ".$config['user_profile_avatar_upload_max_size_allocation']." MB";

$config['user_profile_avatar_allowed_formats_error'] = "allowed formats are JPG / JPEG / GIF / PNG s maximální velikostí ".$config['user_profile_avatar_upload_max_size_allocation']." MB";

//max allowed extra location for business account
$config['account_management_address_details_free_subscriber_allowed_extra_location'] = 1;

$config['account_management_address_details_gold_subscriber_allowed_extra_location'] = 5;

//Address management page functionalty validation messages and validation limits
$config['account_management_address_details_street_address_minimum_length_character_limit'] = 10;

$config['account_management_address_details_street_address_maximum_length_character_limit'] = 50;

$config['account_management_address_details_street_address_minimum_length_error_message'] = 'EN - Název ulice musí mít minimálně '.$config['account_management_address_details_street_address_minimum_length_character_limit'].' znaků';


//Account management contact page functionalty validation messages and validation limits
//For skype
$config['account_management_contact_details_skype_id_characters_minimum_length_characters_limit'] = 5;

$config['account_management_contact_details_skype_id_characters_maximum_length_characters_limit'] = 10;

$config['account_management_contact_details_skype_id_characters_minimum_length_validation_message'] = 'Minimum '.$config['account_management_contact_details_skype_id_characters_minimum_length_characters_limit'].' character required for skype';

// For additional phone number
$config['account_management_contact_details_additional_phone_number_characters_minimum_length_characters_limit'] = 2;

$config['account_management_contact_details_additional_phone_number_characters_maximum_length_characters_limit'] = 25;

$config['account_management_contact_details_additional_phone_number_characters_minimum_length_validation_message'] = 'Minimum '.$config['account_management_contact_details_additional_phone_number_characters_minimum_length_characters_limit'].' character required for additional phone number';

// For mobile phone number
$config['account_management_contact_details_mobile_phone_number_characters_minimum_length_characters_limit'] = 2;

$config['account_management_contact_details_mobile_phone_number_characters_maximum_length_characters_limit'] = 25;

$config['account_management_contact_details_mobile_phone_number_characters_minimum_length_validation_message'] = 'Minimum '.$config['account_management_contact_details_mobile_phone_number_characters_minimum_length_characters_limit'].' character required for mobile phone number';

// For phone number
$config['account_management_contact_details_phone_number_characters_minimum_length_characters_limit'] = 2;

$config['account_management_contact_details_phone_number_characters_maximum_length_characters_limit'] = 25;

$config['account_management_contact_details_phone_number_characters_minimum_length_validation_message'] = 'Minimum '.$config['account_management_contact_details_phone_number_characters_minimum_length_characters_limit'].' character required for phone number';


//user account password update
$config['account_management_update_password_section_password_min_length_character_limit'] = 6;

$config['account_management_update_password_section_new_password_minimum_length_error_message'] = 'New password greater then '.$config['account_management_update_password_section_password_min_length_character_limit'].' characters';

$config['account_management_update_password_section_confirm_new_password_minimum_length_error_message'] = 'Nové heslo musí mít minimálně (confirm password greater then) '.$config['account_management_update_password_section_password_min_length_character_limit'].' characters';


//close account page functionalty validation messages and validation limits
$config['account_management_close_account_page_minimum_length_character_limit_reason_description'] = 10; //minimum limit of character of 

$config['account_management_close_account_page_minimum_length_words_limit_reason_description'] = 5; //minimum limit of words of project 

$config['account_management_close_account_page_maximum_length_character_limit_reason_description'] = 5000; //maximum limit of character of 

$config['account_management_close_account_page_reason_description_characters_min_length_validation_message'] = 'description must be at least '.$config['account_management_close_account_page_minimum_length_character_limit_reason_description'].' characters';

$config['account_management_close_account_page_reason_description_characters_max_length_validation_message'] = 'description should not be greater the '.$config['account_management_close_account_page_maximum_length_character_limit_reason_description'].' characters';

$config['account_management_close_account_page_reason_description_characters_words_min_length_validation_message'] = 'description be at least '.$config['account_management_close_account_page_minimum_length_character_limit_reason_description'].' characters and '.$config['account_management_close_account_page_minimum_length_words_limit_reason_description'].' words';

?>