<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['user_projects_disputes_management_page_active_disputes_listing_limit'] =10;
$config['user_projects_disputes_management_page_closed_disputes_listing_limit'] =7;
$config['user_projects_disputes_management_page_number_of_pagination_links'] = 2;


$config['project_dispute_details_page_attachment_maximum_size_allocation'] = 1; // This is the size of project dispute attachment in MB

$config['project_dispute_details_page_maximum_allowed_number_of_attachments_on_disputed_projects'] = 2 ; 

$config['project_dispute_description_minimum_length_characters_limit'] = 20;
$config['project_dispute_description_maximum_length_characters_limit'] = 6000;
$config['project_dispute_description_minimum_length_words_limit'] = 5;





$config['project_dispute_details_page_dispute_description_characters_min_length_validation_project_dispute_form_message'] = 'Your project dispute description must be at least '.$config['project_dispute_description_minimum_length_characters_limit'].' characters';

$config['project_dispute_details_page_dispute_description_characters_maximum_length_validation_project_dispute_form_message'] = 'Your project dispute description can not be more then '.$config['project_dispute_description_maximum_length_characters_limit'].' characters';

$config['project_dispute_details_page_dispute_description_characters_words_minimum_length_validation_project_dispute_form_message'] = 'project->popis nabídky musí mít alespoň '.$config['project_dispute_description_minimum_length_characters_limit'].' znaků a '.$config['project_dispute_description_minimum_length_words_limit'].' slov';






$config['project_dispute_details_page_dispute_description_characters_min_length_validation_fulltime_project_dispute_form_message'] = 'Your fulltime project dispute description must be at least '.$config['project_dispute_description_minimum_length_characters_limit'].' characters';

$config['project_dispute_details_page_dispute_description_characters_maximum_length_validation_fulltime_project_dispute_form_message'] = 'Your fulltime project dispute description can not be more then '.$config['project_dispute_description_maximum_length_characters_limit'].' characters';

$config['project_dispute_details_page_dispute_description_characters_words_minimum_length_validation_fulltime_project_dispute_form_message'] = 'fuultime project->popis nabídky musí mít alespoň '.$config['project_dispute_description_minimum_length_characters_limit'].' znaků a '.$config['project_dispute_description_minimum_length_words_limit'].' slov';

$config['dispute_negotiation_availability_time'] = "00:180:00"; //180 mins
$config['minimum_required_disputed_fixed_budget_project_value_for_admin_arbitration'] = 2000; 
$config['minimum_required_disputed_hourly_rate_based_project_value_for_admin_arbitration'] = 12000; 
$config['minimum_required_disputed_fulltime_project_value_for_admin_arbitration'] = 17000; 
//$config['admin_dispute_arbitration_fee'] = 10;
$config['fixed_budget_project_admin_dispute_arbitration_percentage_fee'] = 10;
$config['hourly_rate_based_project_admin_dispute_arbitration_percentage_fee'] = 20;
$config['fulltime_project_admin_dispute_arbitration_percentage_fee'] = 30;

?>