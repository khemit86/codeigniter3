<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//position_title
$config['personal_account_work_experience_section_position_title_characters_minimum_length_characters_limit'] = 5;
$config['personal_account_work_experience_section_position_title_characters_maximum_length_characters_limit'] = 50;
$config['personal_account_work_experience_section_position_title_characters_minimum_length_validation_message'] = 'EN povinné je minimálně '.$config['personal_account_work_experience_section_position_title_characters_minimum_length_characters_limit'].' znaků';

$config['company_account_app_work_experience_section_position_title_characters_minimum_length_characters_limit'] = 5;
$config['company_account_app_work_experience_section_position_title_characters_maximum_length_characters_limit'] = 50;
$config['company_account_app_work_experience_section_position_title_characters_minimum_length_validation_message'] = 'EN povinné je minimálně '.$config['company_account_app_work_experience_section_position_title_characters_minimum_length_characters_limit'].' znaků';


//company_name
$config['personal_account_work_experience_section_company_name_characters_minimum_length_characters_limit'] = 5;
$config['personal_account_work_experience_section_company_name_characters_maximum_length_characters_limit'] = 50;
$config['personal_account_work_experience_section_company_name_characters_minimum_length_validation_message'] = 'EN povinné je minimálně '.$config['personal_account_work_experience_section_company_name_characters_minimum_length_characters_limit'].' znaků';

$config['company_account_app_work_experience_section_company_name_characters_minimum_length_characters_limit'] = 5;
$config['company_account_app_work_experience_section_company_name_characters_maximum_length_characters_limit'] = 50;
$config['company_account_app_work_experience_section_company_name_characters_minimum_length_validation_message'] = 'EN povinné je minimálně '.$config['company_account_app_work_experience_section_company_name_characters_minimum_length_characters_limit'].' znaků';

//company address
$config['personal_account_work_experience_section_company_address_characters_minimum_length_characters_limit'] = 5;
$config['personal_account_work_experience_section_company_address_characters_maximum_length_characters_limit'] = 50;
$config['personal_account_work_experience_section_company_address_characters_minimum_length_validation_message'] = 'EN povinné je minimálně '.$config['personal_account_work_experience_section_company_address_characters_minimum_length_characters_limit'].' znaků';

$config['company_account_app_work_experience_section_company_address_characters_minimum_length_characters_limit'] = 5;
$config['company_account_app_work_experience_section_company_address_characters_maximum_length_characters_limit'] = 50;
$config['company_account_app_work_experience_section_company_address_characters_minimum_length_validation_message'] = 'EN povinné je minimálně '.$config['company_account_app_work_experience_section_company_address_characters_minimum_length_characters_limit'].' znaků';


//job description
$config['personal_account_work_experience_section_position_description_characters_minimum_length_characters_limit'] = 10;
$config['personal_account_work_experience_section_position_description_characters_maximum_length_characters_limit'] = 5000;
$config['personal_account_work_experience_section_position_description_characters_minimum_length_words_limit'] = 5;

$config['company_account_app_work_experience_section_position_description_characters_minimum_length_characters_limit'] = 10;
$config['company_account_app_work_experience_section_position_description_characters_maximum_length_characters_limit'] = 5000;
$config['company_account_app_work_experience_section_position_description_characters_minimum_length_words_limit'] = 5;


$config['personal_account_work_experience_section_position_description_words_minimum_length_validation_message'] = 'work experience description must be at least '.$config['personal_account_work_experience_section_position_description_characters_minimum_length_characters_limit'].' characters and '.$config['personal_account_work_experience_section_position_description_characters_minimum_length_words_limit'].' words';

$config['company_account_app_work_experience_section_position_description_words_minimum_length_validation_message'] = 'work experience description must be at least '.$config['company_account_app_work_experience_section_position_description_characters_minimum_length_characters_limit'].' characters and '.$config['company_account_app_work_experience_section_position_description_characters_minimum_length_words_limit'].' words';


$config['personal_account_work_experience_section_year_start_from'] = 1960;
$config['personal_account_work_experience_section_year_end_to'] = 2019;

$config['company_account_app_work_experience_section_year_start_from'] = 1960;
$config['company_account_app_work_experience_section_year_end_to'] = 2019;

$config['personal_account_work_experience_section_listing_limit'] = 2;
$config['personal_account_work_experience_section_number_of_pagination_links'] = 2;

$config['company_account_app_work_experience_section_listing_limit'] = 2;
$config['company_account_app_work_experience_section_number_of_pagination_links'] = 2;


// variable to manage description character limit on user work experience listing section
$config['personal_account_work_experience_section_description_character_limit_mobile'] = 250; // work experience description character limit for mobile device
$config['personal_account_work_experience_section_description_character_limit_tablet'] = 375; // work experience description character limit for tablet device
$config['personal_account_work_experience_section_description_character_limit_desktop'] = 500; // work experience description character limit for dekstop device

// variable to manage description character limit on user work experience listing section
$config['company_account_app_work_experience_section_description_character_limit_mobile'] = 250; // work experience description character limit for mobile device
$config['company_account_app_work_experience_section_description_character_limit_tablet'] = 375; // work experience description character limit for tablet device
$config['company_account_app_work_experience_section_description_character_limit_desktop'] = 500; // work experience description character limit for dekstop device

?>