<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// variables for favorite employer subscription limit
$config['free_subscribers_max_number_of_favorite_employers_subscriptions'] = 5;
$config['gold_subscribers_max_number_of_favorite_employers_subscriptions'] = 25;

// variable to manage favourite employers limit
$config['favorite_employers_listing_limit_per_page'] = 5;
/**
 * This variable is used to show favorite employers page pagination links, i.e. if we have 10 records to show and pagination limit 3,
 * codeigniter always try to manange define number of links before or after currently active link also sync with recored limit.
 * 
*/
$config['favorite_employers_number_of_pagination_links'] = 2;

$config['favorite_employers_description_display_minimum_length_character_limit_desktop'] = 250; // limited charecter display in favorite_employers for desktop

$config['favorite_employers_description_display_minimum_length_character_limit_tablet'] = 250; // limited charecter display in favorite_employers for tablet

$config['favorite_employers_description_display_minimum_length_character_limit_mobile'] = 250; // limited charecter display in favorite_employers for mobile

?>