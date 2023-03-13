<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
//diploma name
$config['personal_account_education_section_diploma_name_characters_minimum_length_characters_limit'] = 5;
$config['personal_account_education_section_diploma_name_characters_maximum_length_characters_limit'] = 50;
$config['personal_account_education_section_diploma_name_characters_minimum_length_validation_message'] = 'EN - povinné je minimálně '.$config['personal_account_education_section_diploma_name_characters_minimum_length_characters_limit'].' znaků';

$config['company_account_app_education_section_diploma_name_characters_minimum_length_characters_limit'] = 5;
$config['company_account_app_education_section_diploma_name_characters_maximum_length_characters_limit'] = 50;
$config['company_account_app_education_section_diploma_name_characters_minimum_length_validation_message'] = 'EN - povinné je minimálně '.$config['company_account_app_education_section_diploma_name_characters_minimum_length_characters_limit'].' znaků';

//school name
$config['personal_account_education_section_school_name_characters_minimum_length_characters_limit'] = 5;
$config['personal_account_education_section_school_name_characters_maximum_length_characters_limit'] = 50;
$config['personal_account_education_section_school_name_characters_minimum_length_validation_message'] = 'EN - povinné je minimálně '.$config['personal_account_education_section_school_name_characters_minimum_length_characters_limit'].' znaků';

$config['company_account_app_education_section_school_name_characters_minimum_length_characters_limit'] = 5;
$config['company_account_app_education_section_school_name_characters_maximum_length_characters_limit'] = 50;
$config['company_account_app_education_section_school_name_characters_minimum_length_validation_message'] = 'EN - povinné je minimálně '.$config['company_account_app_education_section_school_name_characters_minimum_length_characters_limit'].' znaků';

//school address
$config['personal_account_education_section_school_address_characters_minimum_length_characters_limit'] = 5;
$config['personal_account_education_section_school_address_characters_maximum_length_characters_limit'] = 50;

$config['company_account_app_education_section_school_address_characters_minimum_length_characters_limit'] = 5;
$config['company_account_app_education_section_school_address_characters_maximum_length_characters_limit'] = 50;

$config['personal_account_education_section_school_address_characters_minimum_length_validation_message'] = 'EN - povinné je minimálně '.$config['personal_account_education_section_school_address_characters_minimum_length_characters_limit'].' znaků';

$config['company_account_app_education_section_school_address_characters_minimum_length_validation_message'] = 'EN - povinné je minimálně '.$config['company_account_app_education_section_school_address_characters_minimum_length_characters_limit'].' znaků';


//graduate In
$config['personal_account_education_section_graduated_in_year_start_from'] = 1960;
$config['personal_account_education_section_graduated_in_year_end_to'] = 2019;

$config['company_account_app_education_section_graduated_in_year_start_from'] = 1960;
$config['company_account_app_education_section_graduated_in_year_end_to'] = 2019;

$config['personal_account_education_section_comments_characters_minimum_length_characters_limit'] = 10;
$config['personal_account_education_section_comments_characters_maximum_length_characters_limit'] = 5000;
$config['personal_account_education_section_comments_characters_minimum_length_words_limit'] = 5;
$config['personal_account_education_section_comments_characters_minimum_length_validation_message'] = 'EN - povinné je minimálně '.$config['personal_account_education_section_comments_characters_minimum_length_characters_limit'].' znaků';

$config['company_account_app_education_section_comments_characters_minimum_length_characters_limit'] = 10;
$config['company_account_app_education_section_comments_characters_maximum_length_characters_limit'] = 5000;
$config['company_account_app_education_section_comments_characters_minimum_length_words_limit'] = 5;
$config['company_account_app_education_section_comments_characters_minimum_length_validation_message'] = 'EN - povinné je minimálně '.$config['company_account_app_education_section_comments_characters_minimum_length_characters_limit'].' znaků';

$config['personal_account_work_education_section_comments_words_minimum_length_validation_message'] = 'education description must be at least '.$config['personal_account_education_section_comments_characters_minimum_length_characters_limit'].' characters and '.$config['personal_account_education_section_comments_characters_minimum_length_words_limit'].' words';

$config['company_account_app_work_education_section_comments_words_minimum_length_validation_message'] = 'education description must be at least '.$config['company_account_app_education_section_comments_characters_minimum_length_characters_limit'].' characters and '.$config['company_account_app_education_section_comments_characters_minimum_length_words_limit'].' words';

$config['personal_account_education_section_listing_limit'] = 10;
$config['personal_account_education_section_number_of_pagination_links'] = 2;

$config['company_account_app_education_section_listing_limit'] = 10;
$config['company_account_app_education_section_number_of_pagination_links'] = 2;

// variable to manage comment character limit on user education listing section
$config['personal_account_education_section_comment_character_limit_mobile'] = 250; // education comment character limit for mobile device
$config['personal_account_education_section_comment_character_limit_tablet'] = 250; // education comment character limit for tablet device
$config['personal_account_education_section_comment_character_limit_desktop'] = 250; // education comment character limit for dekstop device

$config['company_account_app_education_section_comment_character_limit_mobile'] = 250; // education comment character limit for mobile device
$config['company_account_app_education_section_comment_character_limit_tablet'] = 250; // education comment character limit for tablet device
$config['company_account_app_education_section_comment_character_limit_desktop'] = 250; // education comment character limit for dekstop device

?>