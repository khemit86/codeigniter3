<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

################ Meta Config Variables for project detail/preview page ###########
// character limit for meta tag and meta description on project details pages
$config['project_title_meta_tag_character_limit'] = 50; // meta title limit for project detail page
$config['project_description_meta_description_character_limit'] = 140; // meta description limit for project detail page

################ Defined the edit project form validation regarding edit project form start here
/* Filename: application\modules\projects\controllers\Projects.php */
/* Controller: Projects Method name: edit_project */
$config['project_additional_information_minimum_length_character_limit_post_project'] = 10; //minimum limit of character of project additional information on edit project

$config['project_additional_information_minimum_length_words_limit_post_project'] = 5; //minimum limit of words of project additional information on edit project

$config['project_additional_information_maximum_length_character_limit_post_project'] = 5000; //maximum limit of character of project additional information on edit project


//PROJECT ADDITIONAL INFORMATION LENGTH AND REQUIREMENTS
$config['project_additional_information_characters_min_length_validation_message'] = 'project additional information must be at least '.$config['project_additional_information_minimum_length_character_limit_post_project'].' characters';

$config['project_additional_information_characters_words_min_length_validation_message'] = 'project additional information must be at least '.$config['project_additional_information_minimum_length_character_limit_post_project'].' characters and '.$config['project_additional_information_minimum_length_words_limit_post_project'].' words';


//fulltimePROJECT ADDITIONAL INFORMATION LENGTH AND REQUIREMENTS
$config['fulltime_position_additional_information_characters_min_length_validation_post_project_message'] = 'Your fulltime position additional information must be at least '.$config['project_additional_information_minimum_length_character_limit_post_project'].' characters';

$config['fulltime_position_additional_information_characters_words_min_length_validation_message'] = 'fulltime project additional information must be at least '.$config['project_additional_information_minimum_length_character_limit_post_project'].' characters and '.$config['project_additional_information_minimum_length_words_limit_post_project'].' words';


$config['featured_project_cover_picture_maximum_size_allocation'] = 3; // This is the size of featured cover picture uploaded by PO in MB

//define for dashboard latest projects
$config['dashboard_latest_projects_categories_show_more_less'] = 2;

################ Defined variable for user dashboard my project section
/* Filename: application\modules\projects\controllers\Projects.php */
/* Filename: application\modules\dashboard\controllers\dashboard.php */
$config['user_dashboard_po_view_draft_projects_listing_limit'] = 5;
$config['user_dashboard_po_view_awaiting_moderation_projects_listing_limit'] = 5;
$config['user_dashboard_po_view_open_bidding_projects_listing_limit'] = 5;
$config['user_dashboard_po_view_awarded_projects_listing_limit'] = 5;
$config['user_dashboard_po_view_in_progress_projects_listing_limit'] = 5;
$config['user_dashboard_po_view_incomplete_projects_listing_limit'] = 5;
$config['user_dashboard_po_view_completed_projects_listing_limit'] = 5;
$config['user_dashboard_po_view_expired_projects_listing_limit'] = 5;
$config['user_dashboard_po_view_cancelled_projects_listing_limit'] = 5;

############# config variables of active bid history of SP on my project page dashboard section###
$config['user_dashboard_sp_view_active_bids_listing_limit'] = 5;
$config['user_dashboard_sp_view_awarded_bids_listing_limit'] = 5;
$config['user_dashboard_sp_view_in_progress_bids_listing_limit'] = 5;
$config['user_dashboard_sp_view_incomplete_bids_listing_limit'] = 5;
$config['user_dashboard_sp_view_completed_bids_listing_limit'] = 5;
$config['user_dashboard_fulltime_projects_employee_view_hired_listing_limit'] = 5;

################ Defined variable for user dashboard my project section

$config['my_projects_po_view_draft_projects_listing_limit'] = 10;
$config['my_projects_po_view_awaiting_moderation_projects_listing_limit'] = 10;
$config['my_projects_po_view_open_bidding_projects_listing_limit'] = 10;
$config['my_projects_po_view_awarded_projects_listing_limit'] = 10;
$config['my_projects_po_view_in_progress_projects_listing_limit'] = 10;
$config['my_projects_po_view_incomplete_projects_listing_limit'] = 10;
$config['my_projects_po_view_completed_projects_listing_limit'] = 10;
$config['my_projects_po_view_expired_projects_listing_limit'] = 10;
$config['my_projects_po_view_cancelled_projects_listing_limit'] = 10;

############# config variables of active bid history of SP on my project page section###
$config['my_projects_sp_view_active_bids_listing_limit'] = 10;
$config['my_projects_sp_view_awarded_bids_listing_limit'] = 10;
$config['my_projects_sp_view_in_progress_bids_listing_limit'] = 10;
$config['my_projects_sp_view_incomplete_bids_listing_limit'] = 10;
$config['my_projects_sp_view_completed_bids_listing_limit'] = 10;
$config['my_projects_fulltime_projects_employee_view_hired_listing_limit'] = 10;

/**
 * This variable is used to show dedicated my project page pagination links, i.e. if we have 10 records to show and pagination limit 3,
 * codeigniter always try to manange define number of links before or after currently active link also sync with recored limit.
 * 
*/
$config['my_projects_number_of_pagination_links'] = 2;

$config['project_details_page_violation_report_popup_detail_violation_max_character_limit'] = 1000;
$config['project_details_page_violation_report_popup_minimum_length_word_limit'] = 5;
$config['project_details_page_violation_report_popup_minimum_length_character_limit'] = 25;

$config['project_details_page_violation_report_popup_minimum_length_error_message'] = 'pv-Detail must be at least '.$config['project_details_page_violation_report_popup_minimum_length_character_limit'].' characters.';

$config['project_details_page_violation_report_popup_word_minimum_length_error_message'] = 'pv-Detail must be at least '.$config['project_details_page_violation_report_popup_minimum_length_character_limit'].' characters and '.$config['project_details_page_violation_report_popup_minimum_length_word_limit'].' words.';

$config['project_details_page_email_share_project_description_character_limit'] = 500;

?>