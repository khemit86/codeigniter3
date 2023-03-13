<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//Left navigation Menu name
$config['pa_projects_management_left_nav_favorite_employers'] = 'Favorite employers';
$config['ca_projects_management_left_nav_favorite_partners'] = 'Favorite partners';
$config['ca_app_projects_management_left_nav_favorite_partners'] = 'App->Favorite partners';

/*
|--------------------------------------------------------------------------
| Meta Variables 
|--------------------------------------------------------------------------
| 
*/

################ Meta Variables ###########

//$config['title_meta_tag'] = ' - will be translated in each language';
//$config['description_meta_tag'] = 'Description Meta Tag - will be translated in each language';

$config['pa_favorite_employers_page_title_meta_tag'] = 'pa-{user_first_name_last_name_or_company_name} | FAV EMPLOYERS Title Meta Tag';
$config['pa_favorite_employers_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | FAV EMPLOYERS dESCRIPTION Meta Tag';

$config['ca_favorite_employers_page_title_meta_tag'] = 'ca-{user_first_name_last_name_or_company_name} | FAV PARTNERS Title Meta Tag';
$config['ca_favorite_employers_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | FAV PARTNERS dESCRIPTION Meta Tag';

$config['ca_app_favorite_employers_page_title_meta_tag'] = 'ca-app-{user_first_name_last_name_or_company_name} | FAV EMPLOYERS dESCRIPTION Meta Tag';
$config['ca_app_favorite_employers_page_description_meta_tag'] = 'ca-app{user_first_name_last_name_or_company_name} | FAV EMPLOYERS dESCRIPTION Meta Tag';

################ Page text Variables ###########
$config['pa_favorite_employers_headline_title_my_favourite_employers'] = 'en - Oblíbení zaměstnavatelé';

$config['ca_favorite_employers_headline_title_my_favourite_employers'] = 'EN - Oblíbení dodavatelé';

$config['ca_app_favorite_employers_headline_title_my_favourite_employers'] = 'EN - App:Oblíbení dodavatelé';


$config['pa_favorite_employers_no_favourite_employer_available'] = 'EN - Momentálně nemáte žádného zaměstnavatele ve vašem seznamu oblíbených.';

$config['ca_favorite_employers_no_favourite_employer_available'] = 'EN - Momentálně nemáte žádného zaměstnavatele ve vašem seznamu oblíbených.';

$config['ca_app_favorite_employers_no_favourite_employer_available'] = 'EN - app:Momentálně nemáte žádného zaměstnavatele ve vašem seznamu oblíbených.';

################ Url Routing Variables ###########
//favorite_employers
$config['pa_favorite_employers_page_url'] = 'oblibeni-pa';
$config['ca_favorite_employers_page_url'] = 'oblibeni-ca';


// variable for favorite employers subscribe success and un-subscribe success
$config['favorite_employers_subscribe_success_message'] = 'you have successfully subscribed to receive notifications each time <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> will post a new listing';

$config['favorite_employers_unsubscribe_success_message'] = 'you un-subscribed from receiving notifications about newly posted projects by <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>';

//variable for favorite employers sunscribe success and un-subscribe success user display activity log
$config['favorite_employers_subscribe_success_user_activity_log_displayed_message'] = 'you have successfully subscribed to receive notifications each time <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> will post a new listing';

$config['favorite_employers_unsubscribe_user_activity_log_displayed_message'] = 'you un-subscribed from receiving notifications about newly posted projects by <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>';

// variable for error message when user has open multiple project details page and he already reached to subscription limit
$config['pa_favorite_employers_subcription_limit_reached_error_message'] = "pa-Sorry!, you can't proceed further as you reached to your maximum subscription limit.";
$config['ca_favorite_employers_subcription_limit_reached_error_message'] = "ca-Sorry!, you can't proceed further as you reached to your maximum subscription limit.";

//favorite employers short listed project
$config['favourite_employers_page_total_published_listings'] = "Total Published Listings:";
$config['favourite_employers_page_total_published_projects'] = "Total Published Projects";
$config['favourite_employers_page_total_published_fulltime_projects'] = "Total Published Fulltime";
$config['favourite_employers_page_projects_completed_via_portal'] = "Projects completed via portal";
$config['favourite_employers_page_hires_on_fulltime_projects_via_portal'] = "Hires on fulltime jobs via portal";

$config['favourite_employers_page_total_avg_rating_and_reviews_as_po_txt'] = '; received {total_projects_reviews} hodnocení jako dodavatel with a total rating of {project_user_total_avg_rating_as_po}';

$config['favourite_employers_page_total_avg_rating_and_reviews_as_employer_txt'] = '; received {total_fulltime_projects_reviews} hodnocení jako zaměstnanec with a total rating of {fulltime_project_user_total_avg_rating_as_employer}';

$config['favourite_employers_un_favourite_btn_txt'] = 'Unfavourite';


$config['favorite_employers_show_more_notifications_consent_text'] = 'favorite employers newly posted projects user notifications <span>consent <small>( + )</small></span>';

$config['favorite_employers_hide_extra_notifications_consent_text'] = 'favorite employers newly posted projects user notifications <span>consent <small>( - )</small></span>';

$config['pa_favorite_employers_newly_posted_projects_user_notifications_consent_txt'] = 'pa-Receive realtime notifications about newly posted projects by your favourite employers';

$config['ca_favorite_employers_newly_posted_projects_user_notifications_consent_txt'] = 'ca-Receive realtime notifications about newly posted projects by your favourite employers';

$config['ca_app_favorite_employers_newly_posted_projects_user_notifications_consent_txt'] = 'ca-app-Receive realtime notifications about newly posted projects by your favourite employers';

?>