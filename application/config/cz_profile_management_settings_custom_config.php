<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['profile_management_user_hourly_rate_per_hour'] = '/hodina';
//----------- headline-----------
$config['profile_management_user_headline_minimum_length_character_limit'] = 3;
$config['profile_management_user_headline_maximum_length_character_limit'] = 100;

$config['ca_profile_management_user_headline_minimum_length_error_message'] = 'nadpis musí mít alespoň '.$config['profile_management_user_headline_minimum_length_character_limit'].' znaků';

$config['pa_profile_management_user_headline_minimum_length_error_message'] = 'nadpis musí mít alespoň '.$config['profile_management_user_headline_minimum_length_character_limit'].' znaků';

$config['ca_profile_management_user_headline_maximum_length_error_message'] = 'nadpis nemůže být delší než '.$config['profile_management_user_headline_maximum_length_character_limit'].' znaků';

$config['pa_profile_management_user_headline_maximum_length_error_message'] = 'nadpis nemůže být delší než '.$config['profile_management_user_headline_maximum_length_character_limit'].' znaků';


//----------- description-----------
$config['profile_management_user_description_minimum_length_word_limit'] = 5;
$config['profile_management_user_description_minimum_length_character_limit'] = 25;
$config['profile_management_user_description_maximum_length_character_limit'] = 5000;

$config['ca_profile_management_user_description_word_minimum_length_error_message'] = 'popis musí mít alespoň '.$config['profile_management_user_description_minimum_length_character_limit'].' znaků a '.$config['profile_management_user_description_minimum_length_word_limit'].' slov';


$config['pa_profile_management_user_description_word_minimum_length_error_message'] = 'popis musí mít alespoň '.$config['profile_management_user_description_minimum_length_character_limit'].' znaků a '.$config['profile_management_user_description_minimum_length_word_limit'].' slov';

$config['ca_profile_management_user_description_minimum_length_error_message'] = 'popis musí mít alespoň '.$config['profile_management_user_description_minimum_length_character_limit'].' znaků';


$config['pa_profile_management_user_description_minimum_length_error_message'] = 'popis musí mít alespoň '.$config['profile_management_user_description_minimum_length_character_limit'].' znaků';

$config['ca_profile_management_user_description_maximum_length_error_message'] = 'popis nemůže být delší než '.$config['profile_management_user_description_maximum_length_character_limit'].' znaků';

$config['pa_profile_management_user_description_maximum_length_error_message'] = 'popis nemůže být delší než '.$config['profile_management_user_description_maximum_length_character_limit'].' znaků';


$config['ca_profile_management_base_information_year_start_from'] = 1800;
$config['ca_profile_management_base_information_year_end_to'] = 2020;


$config['ca_profile_management_company_vision_minimum_length_word_limit'] = 5;
$config['ca_profile_management_company_vision_minimum_length_character_limit'] = 25;
$config['ca_profile_management_company_vision_maximum_length_character_limit'] = 1500;
$config['ca_profile_management_company_vision_required_error_message'] = 'pole nemůže být prázdné';
$config['ca_profile_management_company_vision_minimum_length_error_message'] = 'text musí mít alespoň '.$config['ca_profile_management_company_vision_minimum_length_character_limit'].' znaků';
$config['ca_profile_management_company_vision_word_minimum_length_error_message'] = 'text musí mít alespoň '.$config['ca_profile_management_company_vision_minimum_length_character_limit'].' znaků a '.$config['ca_profile_management_company_vision_minimum_length_word_limit'].' slov';

$config['ca_profile_management_company_mission_minimum_length_word_limit'] = 5;
$config['ca_profile_management_company_mission_minimum_length_character_limit'] = 25;
$config['ca_profile_management_company_mission_maximum_length_character_limit'] = 1500;
$config['ca_profile_management_company_mission_required_error_message'] = 'pole nemůže být prázdné';
$config['ca_profile_management_company_mission_minimum_length_error_message'] = 'text musí mít alespoň '.$config['ca_profile_management_company_mission_minimum_length_character_limit'].' znaků';
$config['ca_profile_management_company_mission_word_minimum_length_error_message'] = 'text musí mít alespoň '.$config['ca_profile_management_company_mission_minimum_length_character_limit'].' znaků a '.$config['ca_profile_management_company_mission_minimum_length_word_limit'].' slov';


$config['ca_profile_management_company_core_values_minimum_length_word_limit'] = 5;
$config['ca_profile_management_company_core_values_minimum_length_character_limit'] = 25;
$config['ca_profile_management_company_core_values_maximum_length_character_limit'] = 1500;
$config['ca_profile_management_company_core_values_required_error_message'] = 'pole nemůže být prázdné';
$config['ca_profile_management_company_core_values_minimum_length_error_message'] = 'text musí mít alespoň '.$config['ca_profile_management_company_core_values_minimum_length_character_limit'].' znaků';
$config['ca_profile_management_company_core_values_word_minimum_length_error_message'] = 'text musí mít alespoň '.$config['ca_profile_management_company_core_values_minimum_length_character_limit'].' znaků a '.$config['ca_profile_management_company_core_values_minimum_length_word_limit'].' slov';


$config['ca_profile_management_company_strategy_goals_minimum_length_word_limit'] = 5;
$config['ca_profile_management_company_strategy_goals_minimum_length_character_limit'] = 25;
$config['ca_profile_management_company_strategy_goals_maximum_length_character_limit'] = 1500;
$config['ca_profile_management_company_strategy_goals_required_error_message'] = 'pole nemůže být prázdné';
$config['ca_profile_management_company_strategy_goals_minimum_length_error_message'] = 'text musí mít alespoň '.$config['ca_profile_management_company_strategy_goals_minimum_length_character_limit'].' znaků';
$config['ca_profile_management_company_strategy_goals_word_minimum_length_error_message'] = 'text musí mít alespoň '.$config['ca_profile_management_company_strategy_goals_minimum_length_character_limit'].' znaků a '.$config['ca_profile_management_company_strategy_goals_minimum_length_word_limit'].' slov';

/*----------- hourly rate----------------*/
$config['profile_management_user_hourly_rate_max_digit'] = 5;
$config['profile_management_user_hourly_rate_min_value'] = 60;

$config['profile_management_user_hourly_rate_minimum_value_error_message'] = 'minimální hodinová sazba je '.str_replace(".00","",number_format($config['profile_management_user_hourly_rate_min_value'], 2, '.', ' '))." ".CURRENCY . $config['profile_management_user_hourly_rate_per_hour']; 

/*----------- Skills----------------*/
$config['pa_user_profile_management_competencies_page_free_membership_subscriber_number_skills_slots_allowed'] = 5;
$config['pa_user_profile_management_competencies_page_gold_membership_subscriber_number_skills_slots_allowed'] = 50;

$config['ca_user_profile_management_competencies_page_free_membership_subscriber_number_skills_slots_allowed'] = 5;
$config['ca_user_profile_management_competencies_page_gold_membership_subscriber_number_skills_slots_allowed'] = 50;

$config['profile_management_user_skill_minimum_length_character_limit'] = 3;
$config['profile_management_user_skill_maximum_length_character_limit'] = 100;

$config['pa_profile_management_user_skill_minimum_length_error_message'] = 'dovednost musí mít alespoň '.$config['profile_management_user_skill_minimum_length_character_limit'].' znaků';
$config['ca_profile_management_user_skill_minimum_length_error_message'] = 'dovednost musí mít alespoň '.$config['profile_management_user_skill_minimum_length_character_limit'].' znaků';


/*----------- Service Provided----------------*/
$config['pa_user_profile_management_competencies_page_free_membership_subscriber_number_services_provided_slots_allowed'] = 5;
$config['pa_user_profile_management_competencies_page_gold_membership_subscriber_number_services_provided_slots_allowed'] = 50;

$config['ca_user_profile_management_competencies_page_free_membership_subscriber_number_services_provided_slots_allowed'] = 5;
$config['ca_user_profile_management_competencies_page_gold_membership_subscriber_number_services_provided_slots_allowed'] = 50;

$config['profile_management_user_services_provided_minimum_length_character_limit'] = 3;
$config['profile_management_user_services_provided_maximum_length_character_limit'] = 100;

$config['pa_profile_management_user_services_provided_minimum_length_error_message'] = 'nabízená služba musí mít alespoň '.$config['profile_management_user_services_provided_minimum_length_character_limit'].' znaků';
$config['ca_profile_management_user_services_provided_minimum_length_error_message'] = 'nabízená služba musí mít alespoň '.$config['profile_management_user_services_provided_minimum_length_character_limit'].' znaků';

/*----------- Area of Expertise----------------*/
$config['user_profile_management_competencies_page_free_membership_subscriber_number_category_slots_allowed'] = 3;
$config['user_profile_management_competencies_page_free_membership_subscriber_number_subcategory_slots_allowed'] = 3;

$config['user_profile_management_competencies_page_gold_membership_subscriber_number_category_slots_allowed'] = 15;
$config['user_profile_management_competencies_page_gold_membership_subscriber_number_subcategory_slots_allowed'] = 3;

/*----------- spoken foreign languages----------------*/
// For personal account
$config['pa_user_profile_management_spoken_languages_page_free_membership_subscriber_number_spoken_languages_slots_allowed'] = 3;
$config['pa_user_profile_management_spoken_languages_page_gold_membership_subscriber_number_spoken_languages_slots_allowed'] = 25;

// For company app account
$config['ca_app_user_profile_management_spoken_languages_page_free_membership_subscriber_number_spoken_languages_slots_allowed'] = 3;
$config['ca_app_user_profile_management_spoken_languages_page_gold_membership_subscriber_number_spoken_languages_slots_allowed'] = 25;

?>