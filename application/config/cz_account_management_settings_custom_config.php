<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//Account management avatar page functionalty validation messages and validation limits
$config['user_profile_avatar_upload_max_size_allocation'] = 5; //MB
$config['user_profile_avatar_upload_max_size_validation_message'] = "maximální velikost pro nahrátí je ".$config['user_profile_avatar_upload_max_size_allocation']." MB";
$config['user_profile_avatar_allowed_formats_error'] = "povolené formáty jsou .jpg / .jpeg / .jfif / .png s maximální velikostí ".$config['user_profile_avatar_upload_max_size_allocation']." MB";

//max allowed extra location for business account
$config['account_management_address_details_free_subscriber_allowed_extra_location'] = 250;
$config['account_management_address_details_gold_subscriber_allowed_extra_location'] = 250;

//Address management page functionalty validation messages and validation limits
$config['account_management_address_details_street_address_minimum_length_character_limit'] = 2;
$config['account_management_address_details_street_address_maximum_length_character_limit'] = 50;
$config['account_management_address_details_street_address_minimum_length_error_message'] = 'název ulice musí mít minimálně '.$config['account_management_address_details_street_address_minimum_length_character_limit'].' znaků';

//Account management contact page functionalty validation messages and validation limits
//For skype
$config['account_management_contact_details_skype_id_characters_minimum_length_characters_limit'] = 6;
$config['account_management_contact_details_skype_id_characters_maximum_length_characters_limit'] = 32;
$config['account_management_contact_details_skype_id_characters_minimum_length_validation_message'] = 'minimální délka skype jména '.$config['account_management_contact_details_skype_id_characters_minimum_length_characters_limit'].' znaků';

// For additional phone number
$config['account_management_contact_details_additional_phone_number_characters_minimum_length_characters_limit'] = 2;
$config['account_management_contact_details_additional_phone_number_characters_maximum_length_characters_limit'] = 15;
$config['account_management_contact_details_additional_phone_number_characters_minimum_length_validation_message'] = 'minimální délka čísla jsou '.$config['account_management_contact_details_additional_phone_number_characters_minimum_length_characters_limit'].' znaky';

// For mobile phone number
$config['account_management_contact_details_mobile_phone_number_characters_minimum_length_characters_limit'] = 2;
$config['account_management_contact_details_mobile_phone_number_characters_maximum_length_characters_limit'] = 15;
$config['account_management_contact_details_mobile_phone_number_characters_minimum_length_validation_message'] = 'minimální délka čísla jsou '.$config['account_management_contact_details_mobile_phone_number_characters_minimum_length_characters_limit'].' znaky';

// For phone number
$config['account_management_contact_details_phone_number_characters_minimum_length_characters_limit'] = 2;
$config['account_management_contact_details_phone_number_characters_maximum_length_characters_limit'] = 15;
$config['account_management_contact_details_phone_number_characters_minimum_length_validation_message'] = 'minimální délka čísla jsou '.$config['account_management_contact_details_phone_number_characters_minimum_length_characters_limit'].' znaky';

$config['account_management_update_password_section_password_min_length_character_limit'] = 6;
$config['account_management_update_password_section_new_password_minimum_length_error_message'] = 'nové heslo musí mít minimálně '.$config['account_management_update_password_section_password_min_length_character_limit'].' znaků';
$config['account_management_update_password_section_confirm_new_password_minimum_length_error_message'] = 'nové heslo musí mít minimálně '.$config['account_management_update_password_section_password_min_length_character_limit'].' znaků';


//close account page functionalty validation messages and validation limits
$config['account_management_close_account_page_minimum_length_character_limit_reason_description'] = 10; //minimum limit of character of
$config['account_management_close_account_page_minimum_length_words_limit_reason_description'] = 5; //minimum limit of words of project
$config['account_management_close_account_page_maximum_length_character_limit_reason_description'] = 1000; //maximum limit of character of

$config['account_management_close_account_page_reason_description_characters_min_length_validation_message'] = 'popis musí mít alespoň '.$config['account_management_close_account_page_minimum_length_character_limit_reason_description'].' znaků';

$config['account_management_close_account_page_reason_description_characters_words_min_length_validation_message'] = 'popis musí mít alespoň '.$config['account_management_close_account_page_minimum_length_character_limit_reason_description'].' znaků a '.$config['account_management_close_account_page_minimum_length_words_limit_reason_description'].' slov';

$config['account_management_close_account_page_reason_description_characters_max_length_validation_message'] = 'popis nemůže být delší než '.$config['account_management_close_account_page_maximum_length_character_limit_reason_description'].' znaků';

?>