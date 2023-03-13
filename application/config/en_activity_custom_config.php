<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Meta Variables 
|--------------------------------------------------------------------------
| 
*/
################ Meta Config Variables for activity page ###########
/* Filename: application\modules\dashboard\controllers\Dashboard.php */
/* Controller: user Method name: index */
$config['activity_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | User Activity LOG Title Meta Tag';

$config['activity_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | User Activity LOG Description Meta Tag';

/*
|--------------------------------------------------------------------------
| user not exist custom messages 
|--------------------------------------------------------------------------
| 
*/
/* Filename: application\modules\activity\views\404_profile_not_existent.php */
/* Controller: activity Method name: profile_professional */
$config['activity_page_url'] = 'activity';

################ Url Routing Variables for activity page ###########
/* Filename: application\modules\activity\controllers\activity.php */
$config['activity_heading'] = 'activities log';

// This one is using the column heading of activities on activity listing regular page
$config['activity_column_heading'] = 'activities';

//This config are using for small activity window as a heading into header.This will show when click on activity icon into header
$config['top_navigation_small_window_activities_notifications_heading'] = 'latest activities';

$config['activity_date_time'] = 'date / time';

$config['activity_search_text'] = 'search';

$config['activity_no_record'] = '<h5>You do not have any activity yet.</h5><p><small>You will receive notifications here each time when something notable happens within your account.</small></p>';

$config['activity_no_record_found'] = 'No record found respect to your search keyword.';

/**
* User activity messages that will display on activity page
*/ 

/*message displayed to user activity log when user is login*/
$config['login_user_activity_log_displayed_message'] = 'New connection from source IP: {user_connection_source_ip}. Currently online: {user_valid_connections_online_count}';


/*message displayed to user activity log when user is logout*/

$config['logout_user_activity_log_displayed_message'] = 'You logged off from source IP: {user_connection_source_ip}. Currently online: {user_valid_connections_online_count}';


/*message displayed to user activity log when node (updates) counts valid user session entries in user log table*/
$config['user_activity_log_displayed_message_node_counts_valid_user_session_entries'] = 'your connections have been refreshed. currently online: {user_valid_connections_online_count}';

$config['user_activity_log_loader_display_text'] = 'activity log - loading please wait...';

?>