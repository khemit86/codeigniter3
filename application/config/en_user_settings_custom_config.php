<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['user_profile_page_description_meta_tag_character_limit'] = 150; //limit of user description show on user profile page in meta description

$config['user_profile_page_cover_picture_maximum_size_allocation'] = 3; // This is the size of user profile cover picture uploaded by gold member in MB


$config['user_profile_cover_picture_maximum_size_validation_message'] = "Image maximum allowed size is ".$config['user_profile_page_cover_picture_maximum_size_allocation']." MB";

// variable to manage project description character limit on user profile page section 
$config['user_profile_page_project_owner_posted_project_listing_project_description_character_limit_mobile'] = 250; // project description character limit for mobile device

$config['user_profile_page_project_owner_posted_project_listing_project_description_character_limit_tablet'] = 375; // project description character limit for tablet device

$config['user_profile_page_project_owner_posted_project_listing_project_description_character_limit_dekstop'] = 500; // project description character limit for dekstop device



$config['user_profile_description_display_minimum_length_character_limit_desktop'] = 500; // limited charecter display in user_profile for desktop
$config['user_profile_description_display_minimum_length_character_limit_tablet'] = 375; // limited charecter display in user_profile for tablet
$config['user_profile_description_display_minimum_length_character_limit_mobile'] = 250; // limited charecter display in user_profile for mobile

$config['ca_user_profile_company_vision_display_minimum_length_character_limit_desktop'] = 500; // limited charecter display in user_profile for desktop
$config['ca_user_profile_company_vision_display_minimum_length_character_limit_tablet'] = 375; // limited charecter display in user_profile for tablet
$config['ca_user_profile_company_vision_display_minimum_length_character_limit_mobile'] = 250; // limited charecter display in user_profile for mobile

$config['ca_user_profile_company_mission_display_minimum_length_character_limit_desktop'] = 500; // limited charecter display in user_profile for desktop
$config['ca_user_profile_company_mission_display_minimum_length_character_limit_tablet'] = 375; // limited charecter display in user_profile for tablet
$config['ca_user_profile_company_mission_display_minimum_length_character_limit_mobile'] = 250; // limited charecter display in user_profile for mobile

$config['ca_user_profile_company_core_values_display_minimum_length_character_limit_desktop'] = 500; // limited charecter display in user_profile for desktop
$config['ca_user_profile_company_core_values_display_minimum_length_character_limit_tablet'] = 375; // limited charecter display in user_profile for tablet
$config['ca_user_profile_company_core_values_display_minimum_length_character_limit_mobile'] = 250; // limited charecter display in user_profile for mobile

$config['ca_user_profile_company_strategy_goals_display_minimum_length_character_limit_desktop'] = 500; // limited charecter display in user_profile for desktop
$config['ca_user_profile_company_strategy_goals_display_minimum_length_character_limit_tablet'] = 375; // limited charecter display in user_profile for tablet
$config['ca_user_profile_company_strategy_goals_display_minimum_length_character_limit_mobile'] = 250; // limited charecter display in user_profile for mobile

// config used to control the character limit of education comment on user profile page(education training section on)
$config['user_profile_education_training_section_comments_display_minimum_length_character_limit_desktop'] = 500; // limited charecter display in user_profile for desktop
$config['user_profile_education_training_section_comments_display_minimum_length_character_limit_tablet'] = 375; // limited charecter display in user_profile for tablet
$config['user_profile_education_training_section_comments_display_minimum_length_character_limit_mobile'] = 250; // limited charecter display in user_profile for mobile

// config used to control the character limit of work experience description on user profile page(work experience section on)
$config['user_profile_work_experience_section_comments_display_minimum_length_character_limit_desktop'] = 500; // limited charecter display in user_profile for desktop
$config['user_profile_work_experience_section_comments_display_minimum_length_character_limit_tablet'] = 375; // limited charecter display in user_profile for tablet
$config['user_profile_work_experience_section_comments_display_minimum_length_character_limit_mobile'] = 250; // limited charecter display in user_profile for mobile


// config used to control the character limit of portfolio description on user profile page(portfolio tab)
$config['user_profile_portfolio_section_description_display_minimum_length_character_limit_desktop'] = 500; // limited charecter display in user_profile for desktop
$config['user_profile_portfolio_section_description_display_minimum_length_character_limit_tablet'] = 375; // limited charecter display in user_profile for tablet
$config['user_profile_portfolio_section_description_display_minimum_length_character_limit_mobile'] = 250; // limited charecter display in user_profile for mobile


$config['user_profile_page_portfolio_tab_limit'] = 5;// record limit on porfolio tab on profile page for load more
$config['user_profile_page_posted_projects_tab_limit'] = 5;// record limit on posted project tab on profile page for load more
$config['user_profile_page_won_projects_tab_limit'] = 5;// record limit on won project tab on profile page for load more

$config['email_share_user_profile_description_character_limit'] = 500;

// This config is using of show more/show less on user profile page
$config['user_profile_page_maximum_area_of_expertise_show'] = 1;
$config['user_profile_page_maximum_services_provided_show'] = 2;
$config['user_profile_page_maximum_skills_show'] = 2;
$config['user_profile_page_maximum_work_experience_show'] = 2;
$config['user_profile_page_maximum_education_training_show'] = 2;
$config['user_profile_page_maximum_spoken_languages_show'] = 2;
$config['user_profile_page_maximum_certifications_show'] = 2;
$config['user_profile_page_maximum_company_locations_show'] = 2;

?>