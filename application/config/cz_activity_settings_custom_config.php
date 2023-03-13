<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// variable to manage user display activity listing limit
$config['user_display_activity_listing_limit_per_page'] = 20;

// variable to manage user display activity pagination link on page
$config['user_display_activity_number_of_pagination_links'] = 2;

//limit to show how many activity in header notifification
$config['activity_notification_limit'] = 5;

$config['user_activity_log_starting_position_to_search'] = 2; // this variable is used to look into db when user entered this many character into textbox

$config['user_activity_log_loader_progressbar_display_time'] = 2000; //means 2 second

?>