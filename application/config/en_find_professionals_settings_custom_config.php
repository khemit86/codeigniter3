<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// variable to manage user description character limit on find professionals page section 
$config['find_professionals_user_description_character_limit_mobile'] = 250; // user description character limit for mobile device
$config['find_professionals_user_description_character_limit_tablet'] = 250; // user description character limit for tablet device
$config['find_professionals_user_description_character_limit_dekstop'] = 250; // user description character limit for dekstop device

// variable to manage to start search after how many character entered into textbox
$config['find_professionals_starting_position_to_search'] = 0; // this variable is used to look into db when user entered this many character into textbox

$config['find_professionals_starting_position_to_location_search'] = 0; // this variable is used to look into db when user entered this many character into textbox

$config['find_professionals_location_search_dropdown_results_suggestion_limit'] = 10; // this variable is used to manage how many suggesstion we will show to user when user search for location

// variable to manage find professionals listing limit - used in generate_pagination_links method under find professionals controller
$config['find_professionals_listing_limit_per_page'] = 10;
// variable to manage find project pagination link on page

/**
 * This variable is used to show find professionals page pagination links, i.e. if we have 10 records to show and pagination limit 3,
 * codeigniter always try to manange define number of links before or after currently active link also sync with recored limit.
 * 
*/
$config['find_professionals_number_of_pagination_links'] = 2; // this is a value used in all paginations

//define for show more/less count
$config['find_professionals_areas_of_expertise_show_more_less'] = 2;

//
$config['find_professionals_maximum_subcategories_show'] = 5;

// Find Professional Categories
$config['find_professionals_maximum_categories_show'] = 15;
$config['find_professionals_loader_progressbar_display_time'] = 2000;

$config['find_professionals_email_share_user_descripition_character_limit'] = 500;

?>