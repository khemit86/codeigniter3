<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['send_realtime_notification_popup_allow_dismiss'] = true; // this is used to allow user to manually close notification
$config['send_realtime_notification_popup_allow_delay'] = 5000; // this is used to set configuration to how much time notification visible to uesr, values defined in miliseconds

// variable to manage newly posted projects notification listing limit
$config['newly_posted_projects_realtime_notification_listing_limit_per_page'] = 10;


/**
 * This variable is used to show projects notification feeds page pagination links, i.e. if we have 10 records to show and pagination limit 3,
 * codeigniter always try to manange define number of links before or after currently active link also sync with recored limit.
 * 
*/
$config['newly_posted_projects_realtime_notification_number_of_pagination_links'] = 2;

//limit to show how many newly posted projects notification related to professional category mapping and favorite employer in header notifification
$config['newly_posted_projects_realtime_notification_limit'] = 5;

// variable to manage project description character limit on find project page 
$config['newly_posted_projects_description_character_limit_mobile'] = 250; // project description character limit for mobile device
$config['newly_posted_projects_description_character_limit_tablet'] = 250; // project description character limit for tablet device
$config['newly_posted_projects_description_character_limit_desktop'] = 250; // project description character limit for dekstop device

$config['newly_posted_projects_loader_display_text'] = 'newly posted - Loading please wait...';

$config['newly_posted_projects_loader_progressbar_display_time'] = 1000;

$config['newly_posted_projects_email_share_project_description_character_limit'] = 500;

?>