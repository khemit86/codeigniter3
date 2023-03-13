<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
	
//// CONFIG FOR VALIDATIONS/////// 
//Validation parameter for Feedback and rating form	
$config['users_ratings_feedbacks_popup_feedback_characters_minimum_length_characters_limit'] = 3;
$config['users_ratings_feedbacks_popup_feedback_characters_maximum_length_characters_limit'] = 2500;
$config['users_ratings_feedbacks_popup_feedback_characters_minimum_length_words_limit'] = 5;

$config['users_ratings_feedbacks_popup_feedback_characters_minimum_length_validation_message'] = 'feedback description must be atleast '.$config['users_ratings_feedbacks_popup_feedback_characters_minimum_length_characters_limit'].' znaků';

$config['users_ratings_feedbacks_popup_feedback_words_minimum_length_validation_message'] = 'popis musí obsahovat minimálně '.$config['users_ratings_feedbacks_popup_feedback_characters_minimum_length_characters_limit'].' znaků a '.$config['users_ratings_feedbacks_popup_feedback_characters_minimum_length_words_limit'].' slov';

// variable to manage feedback description on project detail page under feedback tab
$config['projects_users_ratings_feedbacks_project_details_page_feedback_description_character_limit_mobile'] = 250; // feedback description character limit for mobile device
$config['projects_users_ratings_feedbacks_project_details_page_feedback_description_character_limit_tablet'] = 250; // feedback description character limit for tablet device
$config['projects_users_ratings_feedbacks_project_details_page_feedback_description_character_limit_desktop'] = 250; // feedback description character limit for dekstop device

// paging config for feedbacks and ratings detail under feedback tab
$config['user_profile_page_ratings_feedbacks_on_projects_as_sp_tab_limit'] = 5;
$config['user_profile_page_ratings_feedbacks_on_projects_as_po_tab_limit'] = 5;
$config['user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employee_tab_limit'] = 5;
$config['user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employer_tab_limit'] = 5;


// config used to control the character limit of feedback description on user profile page(feedbacks tab)
$config['user_profile_page_feedbacks_section_description_display_minimum_length_character_limit_desktop'] = 250; // limited charecter display in user_profile for desktop
$config['user_profile_page_feedbacks_section_description_display_minimum_length_character_limit_tablet'] = 250; // limited charecter display in user_profile for tablet
$config['user_profile_page_feedbacks_section_description_display_minimum_length_character_limit_mobile'] = 250; // limited charecter display in user_profile for mobile



$config['users_ratings_feedbacks_reply_characters_minimum_length_characters_limit'] = 3;
$config['users_ratings_feedbacks_reply_characters_maximum_length_characters_limit'] = 2500;
$config['users_ratings_feedbacks_reply_characters_minimum_length_words_limit'] = 5;

$config['users_ratings_feedbacks_reply_characters_minimum_length_validation_message'] = 'Reply description must be atleast '.$config['users_ratings_feedbacks_reply_characters_minimum_length_characters_limit'].' znaků';

$config['users_ratings_feedbacks_reply_words_minimum_length_validation_message'] = 'reply description must be at least '.$config['users_ratings_feedbacks_reply_characters_minimum_length_characters_limit'].' characters and '.$config['users_ratings_feedbacks_reply_characters_minimum_length_words_limit'].' words';

// config used to control the character limit of reply on feedback on user profile page(feedbacks tab)
$config['user_profile_page_feedbacks_section_reply_display_minimum_length_character_limit_desktop'] = 250; // limited charecter display in user_profile for desktop
$config['user_profile_page_feedbacks_section_reply_display_minimum_length_character_limit_tablet'] = 250; // limited charecter display in user_profile for tablet
$config['user_profile_page_feedbacks_section_reply_display_minimum_length_character_limit_mobile'] = 250; // limited charecter display in user_profile for mobile

$config['user_projects_pending_ratings_feedbacks_listing_limit'] =1;
$config['user_projects_pending_ratings_feedbacks_number_of_pagination_links'] = 1;

?>