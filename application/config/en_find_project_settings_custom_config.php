<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// variable to manage project description character limit on find project page 
$config['find_project_description_character_limit_mobile'] = 250; // project description character limit for mobile device
$config['find_project_description_character_limit_tablet'] = 250; // project description character limit for tablet device
$config['find_project_description_character_limit_desktop'] = 250; // project description character limit for dekstop device


// variable to manage to start search after how many character entered into textbox
$config['find_project_starting_position_to_search'] = 0; // this variable is used to look into db when user entered this many character into textbox
$config['find_project_starting_position_to_location_search'] = 0; // this variable is used to look into db when user entered this many character into textbox
$config['find_project_location_search_dropdown_results_suggestion_limit'] = 10; // this variable is used to manage how many suggesstion we will show to user when user search for location

// variable to manage find project listing limit
$config['find_project_listing_limit_per_page'] = 10;
// variable to manage find project pagination link on page
/**
 * This variable is used to show find project page pagination links, i.e. if we have 10 records to show and pagination limit 3,
 * codeigniter always try to manange define number of links before or after currently active link also sync with recored limit.
 * 
*/
$config['find_project_number_of_pagination_links'] = 2; // this is a value used in all paginations

//define for show more/less count
$config['find_projects_categories_show_more_less'] = 2;

// Find Project Subcategories
$config['find_project_maximum_subcategories_show'] = 5;

// Find Project Categories
$config['find_project_maximum_categories_show'] = 15;

$config['find_project_loader_progressbar_display_time'] = 2000;

$config['find_project_email_share_project_description_character_limit'] = 500;


?>