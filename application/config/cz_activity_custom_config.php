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

$config['activity_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Historie činností';

$config['activity_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Historie činností';

/*
|--------------------------------------------------------------------------
| user not exist custom messages
|--------------------------------------------------------------------------
|
*/
$config['activity_page_url'] = 'cinnosti';

################ Url Routing Variables for activity page ###########
/* Filename: application\modules\activity\controllers\activity.php */
$config['activity_heading'] = 'Historie činností';

// This one is using the column heading of activities on activity listing regular page
$config['activity_column_heading'] = 'činnost';

//This config are using for small activity window as a heading into header.This will show when click on activity icon into header
$config['top_navigation_small_window_activities_notifications_heading'] = 'Aktuální činnosti';


$config['activity_date_time'] = 'datum a čas';

$config['activity_search_text'] = 'hledat...';

$config['activity_no_record'] = '<h5>Momentálně nemáte žádné činnosti.</h5><p><small>Zde se zobrazí veškeré informace týkající se aktivit vašeho účtu provedených na travai.cz.</small></p>';

$config['activity_no_record_found'] = '<h4>Vyhledávání neodpovídají žádné výsledky.</h4>';


/**
* User activity messages that will display on activity page
*/

//COMMENTED VARIABLES FROM BELOW NOT TO BE DELETED
/*message displayed to user activity log when user is login*/
$config['login_user_activity_log_displayed_message'] = 'Přihlášení z IP adresy {user_connection_source_ip}.';
//$config['login_user_activity_log_displayed_message'] = 'Přihlášení z IP adresy: {user_connection_source_ip}. Currently online : {user_valid_connections_online_count}';


/*message displayed to user activity log when user is logout*/
$config['logout_user_activity_log_displayed_message'] = 'Odhlášení z IP adresy {user_connection_source_ip}.';
//$config['logout_user_activity_log_displayed_message'] = 'Odhlášení z IP adresy: {user_connection_source_ip}. Currently online : {user_valid_connections_online_count}';

/*message displayed to user activity log when node (updates) counts valid user session entries in user log table*/
//$config['user_activity_log_displayed_message_node_counts_valid_user_session_entries'] = 'your connections have been refreshed. currently online : {user_valid_connections_online_count}';
//$config['user_activity_log_displayed_message_node_counts_valid_user_session_entries'] = 'V současné době jste {user_valid_connections_online_count}x přihlášení';
#########################################################################################################

$config['user_activity_log_loader_display_text'] = 'nahrávání...';

?>