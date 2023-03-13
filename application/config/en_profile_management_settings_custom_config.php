<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$config['profile_management_user_hourly_rate_per_hour'] = '/hour';
// For headline
$config['profile_management_user_headline_minimum_length_character_limit'] = 10;
$config['profile_management_user_headline_maximum_length_character_limit'] = 100;

$config['ca_profile_management_user_headline_minimum_length_error_message'] = 'Your headline must be at least '.$config['profile_management_user_headline_minimum_length_character_limit'].' characters(c)';
$config['pa_profile_management_user_headline_minimum_length_error_message'] = 'Your headline must be at least '.$config['profile_management_user_headline_minimum_length_character_limit'].' characters(p)';

$config['ca_profile_management_user_headline_maximum_length_error_message'] = 'Your headline should not be more then '.$config['profile_management_user_headline_maximum_length_character_limit'].' characters(c)';
$config['pa_profile_management_user_headline_maximum_length_error_message'] = 'Your headline should not be more then '.$config['profile_management_user_headline_maximum_length_character_limit'].' characters(p)';

//----------- description-----------
$config['profile_management_user_description_minimum_length_word_limit'] = 5;
$config['profile_management_user_description_minimum_length_character_limit'] = 25;
$config['profile_management_user_description_maximum_length_character_limit'] = 5000;

$config['ca_profile_management_user_description_word_minimum_length_error_message'] = 'Your description must be at least '.$config['profile_management_user_description_minimum_length_character_limit'].' characters and '.$config['profile_management_user_description_minimum_length_word_limit'].' words(c)';

$config['pa_profile_management_user_description_word_minimum_length_error_message'] = 'Your description must be at least '.$config['profile_management_user_description_minimum_length_character_limit'].' characters and '.$config['profile_management_user_description_minimum_length_word_limit'].' words(p)';

$config['ca_profile_management_user_description_minimum_length_error_message'] = 'Your description must be at least '.$config['profile_management_user_description_minimum_length_character_limit'].' characters(c)';

$config['pa_profile_management_user_description_minimum_length_error_message'] = 'Your description must be at least '.$config['profile_management_user_description_minimum_length_character_limit'].' characters(p)';

$config['ca_profile_management_user_description_maximum_length_error_message'] = 'Your description should not be more then '.$config['profile_management_user_description_maximum_length_character_limit'].' characters(c)';

$config['pa_profile_management_user_description_maximum_length_error_message'] = 'Your description should not be more then '.$config['profile_management_user_description_maximum_length_character_limit'].' characters(p)';

$config['ca_profile_management_base_information_year_start_from'] = 1850;
$config['ca_profile_management_base_information_year_end_to'] = 2020;


$config['ca_profile_management_company_vision_minimum_length_word_limit'] = 5;
$config['ca_profile_management_company_vision_minimum_length_character_limit'] = 25;
$config['ca_profile_management_company_vision_maximum_length_character_limit'] = 5000;
$config['ca_profile_management_company_vision_required_error_message'] = 'Company vision required';
$config['ca_profile_management_company_vision_minimum_length_error_message'] = 'Company vision must be at least '.$config['ca_profile_management_company_vision_minimum_length_character_limit'].' characters.';
$config['ca_profile_management_company_vision_word_minimum_length_error_message'] = 'Company vision must be at least '.$config['ca_profile_management_company_vision_minimum_length_character_limit'].' characters and '.$config['ca_profile_management_company_vision_minimum_length_word_limit'].' words.';


$config['ca_profile_management_company_mission_minimum_length_word_limit'] = 5;
$config['ca_profile_management_company_mission_minimum_length_character_limit'] = 25;
$config['ca_profile_management_company_mission_maximum_length_character_limit'] = 5000;
$config['ca_profile_management_company_mission_required_error_message'] = 'Company mission required';
$config['ca_profile_management_company_mission_minimum_length_error_message'] = 'Company mission must be at least '.$config['ca_profile_management_company_mission_minimum_length_character_limit'].' characters.';
$config['ca_profile_management_company_mission_word_minimum_length_error_message'] = 'Company mission must be at least '.$config['ca_profile_management_company_mission_minimum_length_character_limit'].' characters and '.$config['ca_profile_management_company_mission_minimum_length_word_limit'].' words.';


$config['ca_profile_management_company_core_values_minimum_length_word_limit'] = 5;
$config['ca_profile_management_company_core_values_minimum_length_character_limit'] = 25;
$config['ca_profile_management_company_core_values_maximum_length_character_limit'] = 5000;
$config['ca_profile_management_company_core_values_required_error_message'] = 'Company core values required';
$config['ca_profile_management_company_core_values_minimum_length_error_message'] = 'Company core values must be at least '.$config['ca_profile_management_company_core_values_minimum_length_character_limit'].' characters.';
$config['ca_profile_management_company_core_values_word_minimum_length_error_message'] = 'Company core values must be at least '.$config['ca_profile_management_company_core_values_minimum_length_character_limit'].' characters and '.$config['ca_profile_management_company_core_values_minimum_length_word_limit'].' words.';

$config['ca_profile_management_company_strategy_goals_minimum_length_word_limit'] = 5;
$config['ca_profile_management_company_strategy_goals_minimum_length_character_limit'] = 25;
$config['ca_profile_management_company_strategy_goals_maximum_length_character_limit'] = 5000;
$config['ca_profile_management_company_strategy_goals_required_error_message'] = 'Company strategy and gloas required';
$config['ca_profile_management_company_strategy_goals_minimum_length_error_message'] = 'Company strategy and goals must be at least '.$config['ca_profile_management_company_strategy_goals_minimum_length_character_limit'].' characters.';
$config['ca_profile_management_company_strategy_goals_word_minimum_length_error_message'] = 'Company strategy and goals must be at least '.$config['ca_profile_management_company_strategy_goals_minimum_length_character_limit'].' characters and '.$config['ca_profile_management_company_strategy_goals_minimum_length_word_limit'].' words.';

/*----------- hourly rate----------------*/
$config['profile_management_user_hourly_rate_max_digit'] = 6;
$config['profile_management_user_hourly_rate_min_value'] = 60;
$config['profile_management_user_hourly_rate_minimum_value_error_message'] = 'You can\'t indicate hourly rate less than '.str_replace(".00","",number_format($config['profile_management_user_hourly_rate_min_value'], 2, '.', ' '))." ".CURRENCY . $config['profile_management_user_hourly_rate_per_hour']; 

/*----------- Skills----------------*/
$config['pa_user_profile_management_competencies_page_free_membership_subscriber_number_skills_slots_allowed'] = 5;
$config['pa_user_profile_management_competencies_page_gold_membership_subscriber_number_skills_slots_allowed'] = 25;


$config['ca_user_profile_management_competencies_page_free_membership_subscriber_number_skills_slots_allowed'] = 5;
$config['ca_user_profile_management_competencies_page_gold_membership_subscriber_number_skills_slots_allowed'] = 25;


$config['profile_management_user_skill_minimum_length_character_limit'] = 15;
$config['profile_management_user_skill_maximum_length_character_limit'] = 100;

$config['pa_profile_management_user_skill_minimum_length_error_message'] = 'Your skill must be at least '.$config['profile_management_user_skill_minimum_length_character_limit'].' characters(p)';
$config['ca_profile_management_user_skill_minimum_length_error_message'] = 'Your skill must be at least '.$config['profile_management_user_skill_minimum_length_character_limit'].' characters(c)';

/*----------- Service Provided----------------*/
$config['pa_user_profile_management_competencies_page_free_membership_subscriber_number_services_provided_slots_allowed'] = 5;
$config['pa_user_profile_management_competencies_page_gold_membership_subscriber_number_services_provided_slots_allowed'] = 25;

$config['ca_user_profile_management_competencies_page_free_membership_subscriber_number_services_provided_slots_allowed'] = 5;
$config['ca_user_profile_management_competencies_page_gold_membership_subscriber_number_services_provided_slots_allowed'] = 25;

$config['profile_management_user_services_provided_minimum_length_character_limit'] = 5;
$config['profile_management_user_services_provided_maximum_length_character_limit'] = 100;

$config['pa_profile_management_user_services_provided_minimum_length_error_message'] = 'Your service provided must be at least '.$config['profile_management_user_services_provided_minimum_length_character_limit'].' characters(p)';
$config['ca_profile_management_user_services_provided_minimum_length_error_message'] = 'Your service provided must be at least '.$config['profile_management_user_services_provided_minimum_length_character_limit'].' characters(c)';


/*----------- Area of Expertise----------------*/
$config['user_profile_management_competencies_page_free_membership_subscriber_number_category_slots_allowed'] = 3;
$config['user_profile_management_competencies_page_free_membership_subscriber_number_subcategory_slots_allowed'] = 3;

$config['user_profile_management_competencies_page_gold_membership_subscriber_number_category_slots_allowed'] = 15;
$config['user_profile_management_competencies_page_gold_membership_subscriber_number_subcategory_slots_allowed'] = 3;


/*----------- spoken foreign languages----------------*/
// For personal account
$config['pa_user_profile_management_spoken_languages_page_free_membership_subscriber_number_spoken_languages_slots_allowed'] = 5;
$config['pa_user_profile_management_spoken_languages_page_gold_membership_subscriber_number_spoken_languages_slots_allowed'] = 25;

// For company app account
$config['ca_app_user_profile_management_spoken_languages_page_free_membership_subscriber_number_spoken_languages_slots_allowed'] = 5;
$config['ca_app_user_profile_management_spoken_languages_page_gold_membership_subscriber_number_spoken_languages_slots_allowed'] = 25;

?>