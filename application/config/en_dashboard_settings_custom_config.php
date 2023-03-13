<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['send_feedback_popup_description_max_character_limit'] = 1000;

$config['send_feedback_popup_minimum_length_word_limit'] = 5;

$config['send_feedback_popup_minimum_length_character_limit'] = 25;

$config['send_feedback_popup_minimum_length_error_message'] = 'sf-description must be at least '.$config['send_feedback_popup_minimum_length_character_limit'].' characters.';

$config['send_feedback_popup_word_minimum_length_error_message'] = 'sf-description must be at least '.$config['send_feedback_popup_minimum_length_character_limit'].' characters and '.$config['send_feedback_popup_minimum_length_word_limit'].' words.';

$config['send_feedback_popup_attachment_maximum_size_limit'] = 3;//(Size in MB) size of attachment allowed on for chat to be upload

$config['send_feedback_popup_attachment_maximum_size_validation_message'] = "sf-the file you are trying to upload has {file_size_mb}MB in size and exceeds current max allowed file size of ".$config['send_feedback_popup_attachment_maximum_size_limit']."MB !";

$config['send_feedback_popup_maximum_allowed_number_of_attachments'] = 3; // number of file allowed to user to upload on certificate page

$config['send_feedback_popup_allowed_number_of_files_validation_message'] = "sf-you can upload only ".$config['send_feedback_popup_maximum_allowed_number_of_attachments']. " files!";

$config['send_feedback_popup_attachment_name_character_length_limit'] = 10; // This variable will use when user select file from upload file button or drag and drop file

//variable to manage dashboard latest projects section
$config['dashboard_latest_projects_section_number_of_displayed_listings'] = 5; 

// variable to manage project description character limit on dashboard my projects section 
$config['dashboard_my_projects_section_project_description_character_limit_mobile'] = 250; // project description character limit for mobile device
$config['dashboard_my_projects_section_project_description_character_limit_tablet'] = 250; // project description character limit for tablet device
$config['dashboard_my_projects_section_project_description_character_limit_desktop'] = 250; // project description character limit for dekstop device

// variable to manage project description character limit on dashboard latest projects section 
$config['dashboard_latest_projects_section_project_description_character_limit_mobile'] = 250; // project description character limit for mobile device
$config['dashboard_latest_projects_section_project_description_character_limit_tablet'] = 250; // project description character limit for tablet device
$config['dashboard_latest_projects_section_project_description_character_limit_desktop'] = 250; // project description character limit for dekstop device

$config['dashboard_latest_projects_section_email_share_project_description_character_limit'] = 500; // project description character limit for email share

// this config are using for profile calculation parameter and related with table users_accounts_profile_completion_tracking
//19 factors considered for company account

$config['user_personal_account_type_profile_completion_parameters_tracking_options_value'] =array(
	'avatar_strength_value'=>5,
	'street_address_strength_value'=>2,
	'street_address_when_country_not_cz_strength_value'=>6,
	'county_address_strength_value'=>2,
	'locality_address_strength_value'=>2,
	'country_address_strength_value'=>5,
	'phone_or_mobile_number_strength_value'=>5,
	'contact_email_strength_value'=>5,
	'headline_strength_value'=>9,
	'description_strength_value'=>8,
	'areas_of_expertise_strength_value'=>8,
	'skills_strength_value'=>8,
	'services_provided_strength_value'=>8,
	'mother_tongue_strength_value'=>5,
	'spoken_foreign_languages_strength_value'=>5,
	'work_experience_strength_value'=>5,
	'education_training_strength_value'=>5,
	'certifications_strength_value'=>5,
	'portfolio_strength_value'=>8
);

//23 factors considered for company account
$config['user_company_account_type_profile_completion_parameters_tracking_options_value'] =array(
	'avatar_strength_value'=>5,
	'street_address_strength_value'=>2,
	'street_address_when_country_not_cz_strength_value'=>6,
	'county_address_strength_value'=>2,
	'locality_address_strength_value'=>2,
	'country_address_strength_value'=>5,
	'phone_or_mobile_number_strength_value'=>4,
	'contact_email_strength_value'=>4,
	'headline_strength_value'=>6,
	'description_strength_value'=>6,
	'areas_of_expertise_strength_value'=>5,
	'skills_strength_value'=>5,
	'services_provided_strength_value'=>5,
	'certifications_strength_value'=>4,
	'portfolio_strength_value'=>5,
	'company_founded_year_strength_value'=>4,
	'company_size_strength_value'=>4,
	'company_opening_hours_strength_value'=>5,
	'company_values_strength_value'=>6,
	'company_mission_strength_value'=>6,
	'company_core_values_strength_value'=>6,
	'company_strategy_goals_strength_value'=>6,
	'company_invoicing_details_strength_value'=>3
);

?>