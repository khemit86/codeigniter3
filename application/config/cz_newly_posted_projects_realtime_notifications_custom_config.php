<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
	
#####################################################################################################

$config['projects_realtime_notification_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Přizpůsobené inzeráty'; //meta title
$config['projects_realtime_notification_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Přizpůsobené inzeráty'; //meta description

$config['projects_realtime_notification_feed_page_url'] = 'prizpusobene-inzeraty'; //url	
################ Send notification popup Variables ###########
$config['send_realtime_notification_popup_type'] = 'minimalist'; // this is type of popup which visible to user [success/info/warning/danger]
 
$config['send_realtime_notification_popup_placement_from'] = 'bottom'; // this is used to manage popup position [top/bottom]
$config['send_realtime_notification_popup_placement_align'] = 'left'; // this is used to manage popup alignment [left / center / right]
$config['send_realtime_notification_popup_animate_enter'] = 'bounceOutUp'; // this is used to animate popup based on popup placement its value can vary
$config['send_realtime_notification_popup_animate_exit'] = 'bounceInDown'; // this is used to animate popup based on popup placement its value can vary
$config['send_realtime_notification_popup_url_traget'] = '_blank'; // this is used to manage url to be open in tab when user click on popup notification [_blank / _self]



#####################################################################################################
//This config are using for small pending project notification window as a heading into header.This will show when click on project notifications icon into header
$config['top_navigation_small_window_projects_notifications_heading'] = 'Přizpůsobené inzeráty';

$config['newly_posted_projects_headline_title_projects_notification_feed'] = 'Přizpůsobené inzeráty';

$config['projects_notification_feed_page_no_project_available_message'] = 'V tento okamžik nejsou k dispozici žádné inzeráty.';

$config['projects_notification_feed_page_search_no_results_returned_message'] = '<h4>Vyhledávání neodpovídají žádné výsledky.</h4><p>Upravte výběr a zkuste znovu.</p>';

$config['pa_projects_realtime_notification_no_record'] = '<h5>V tento okamžik nemáte žádné přizpůsobené inzeráty.</h5><p><small>Zde obdržíte informaci o novém vytvořeném inzerátu, který se shoduje s vašimi vybranými kategoriemi na vašem profilu a od vašich oblíbených zaměstnavatelů.</small></p>';

$config['ca_projects_realtime_notification_no_record'] = '<h5>V tento okamžik nemáte žádné přizpůsobené inzeráty.</h5><p><small>Zde obdržíte informaci o novém vytvořeném inzerátu, který se shoduje s vašimi vybranými kategoriemi na vašem profilu a od vašich oblíbených partnerů.</small></p>';

$config['realtime_notification_project_posted_in_following_category_singular_txt'] = 'Kategorie:';

$config['realtime_notification_project_posted_in_following_categories_plural_txt'] = 'Kategorie:';

$config['realtime_notification_project_posting_date_txt'] = 'Zveřejněno:';

$config['realtime_notification_project_budget_txt'] = 'Rozpočet:';

$config['realtime_notification_fulltime_project_salary_txt'] = 'Mzda:';

$config['realtime_notification_posted_by_txt'] = 'Vlastník inzerátu:';



//notifications displayed to user when selects the check box from TOP of the page - AREAS OF EXPERTISE & FAV EMPLOYERS
$config['user_consent_newly_posted_projects_areas_of_expertise_receive_realtime_notification_success_message'] = 'přihlásili jste se k odběru oznámení v reálném čase, pokaždé kdy je zveřejněný nový inzerát, který vyhovuje vašim odborným znalostem';

$config['user_consent_newly_posted_projects_areas_of_expertise_stop_receiving_realtime_notification_success_message'] = 'odhlásili jste se z odběru oznámení v reálném čase, pokaždé kdy je zveřejněný nový inzerát, který vyhovuje vašim odborným znalostem';

$config['pa_user_consent_newly_posted_projects_favorite_employer_receive_realtime_notification_success_message'] = 'přihlásili jste se k odběru oznámení v reálném čase, pokaždé kdy váš oblíbený zaměstnavatel vytvoří nový inzerát';

$config['pa_user_consent_newly_posted_projects_favorite_employer_stop_receiving_realtime_notification_success_message'] = 'odhlásili jste se z odběru oznámení v reálném čase, pokaždé kdy váš oblíbený zaměstnavatel vytvoří nový inzerát';

$config['ca_user_consent_newly_posted_projects_favorite_employer_receive_realtime_notification_success_message'] = 'přihlásili jste se k odběru oznámení v reálném čase, pokaždé kdy váš oblíbený partner vytvoří nový inzerát';

$config['ca_user_consent_newly_posted_projects_favorite_employer_stop_receiving_realtime_notification_success_message'] = 'odhlásili jste se z odběru oznámení v reálném čase, pokaždé kdy váš oblíbený partner vytvoří nový inzerát';

/**
 * Define config variables to manage send notification message to mapped category whenever any project moved to open bidding stage
 * each time a project is posted on a category (or categories) all users having their profiles listed in mapped category (or categories) to that category, will receive a notification regarding that project
 **/
$config['post_fixed_budget_project_mapped_projects_professionals_categories_realtime_notification_message_singular'] = '<a href="{project_url_link}" target="_blank">{fixed_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>{fixed_budget_range}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_category_singular_txt'].'</b>{category_list}';

$config['post_fixed_budget_project_mapped_projects_professionals_categories_realtime_notification_message_plural'] = '<a href="{project_url_link}" target="_blank">{fixed_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>{fixed_budget_range}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_categories_plural_txt'].'</b>{categories_list}';

$config['post_fixed_budget_confidential_project_mapped_projects_professionals_categories_realtime_notification_message_singular'] = '<a href="{project_url_link}" target="_blank">{fixed_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>neveřejněný<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_category_singular_txt'].'</b>{category_list}';

$config['post_fixed_budget_confidential_project_mapped_projects_professionals_categories_realtime_notification_message_plural'] = '<a href="{project_url_link}" target="_blank">{fixed_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>neveřejněný<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_categories_plural_txt'].'</b>{categories_list}';

$config['post_fixed_budget_not_sure_project_mapped_projects_professionals_categories_realtime_notification_message_singular'] = '<a href="{project_url_link}" target="_blank">{fixed_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>není jistý<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_category_singular_txt'].'</b>{category_list}';

$config['post_fixed_budget_not_sure_project_mapped_projects_professionals_categories_realtime_notification_message_plural'] = '<a href="{project_url_link}" target="_blank">{fixed_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>není jistý<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_categories_plural_txt'].'</b>{categories_list}';


$config['post_hourly_rate_budget_project_mapped_projects_professionals_categories_realtime_notification_message_singular'] = '<a href="{project_url_link}" target="_blank">{hourly_rate_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>{hourly_rate_budget_range}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_category_singular_txt'].'</b>{category_list}';

$config['post_hourly_rate_budget_project_mapped_projects_professionals_categories_realtime_notification_message_plural'] = '<a href="{project_url_link}" target="_blank">{hourly_rate_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>{hourly_rate_budget_range}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_categories_plural_txt'].'</b>{categories_list}';

$config['post_hourly_rate_budget_confidential_project_mapped_projects_professionals_categories_realtime_notification_message_singular'] = '<a href="{project_url_link}" target="_blank">{hourly_rate_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>neveřejněný<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_category_singular_txt'].'</b>{category_list}';

$config['post_hourly_rate_budget_confidential_project_mapped_projects_professionals_categories_realtime_notification_message_plural'] = '<a href="{project_url_link}" target="_blank">{hourly_rate_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>neveřejněný<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_categories_plural_txt'].'</b>{categories_list}';

$config['post_hourly_rate_budget_not_sure_project_mapped_projects_professionals_categories_realtime_notification_message_singular'] = '<a href="{project_url_link}" target="_blank">{hourly_rate_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>není jistý<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_category_singular_txt'].'</b>{category_list}';

$config['post_hourly_rate_budget_not_sure_project_mapped_projects_professionals_categories_realtime_notification_message_plural'] = '<a href="{project_url_link}" target="_blank">{hourly_rate_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>není jistý<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_categories_plural_txt'].'</b>{categories_list}';

$config['post_fulltime_salary_project_mapped_projects_professionals_categories_realtime_notification_message_singular'] = '<a href="{project_url_link}" target="_blank">{fulltime_salary_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_fulltime_project_salary_txt'].'</b>{fulltime_salary_range}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_category_singular_txt'].'</b>{category_list}';

$config['post_fulltime_salary_project_mapped_projects_professionals_categories_realtime_notification_message_plural'] = '<a href="{project_url_link}" target="_blank">{fulltime_salary_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_fulltime_project_salary_txt'].'</b>{fulltime_salary_range}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_categories_plural_txt'].'</b>{categories_list}';

$config['post_fulltime_salary_confidential_project_mapped_projects_professionals_categories_realtime_notification_message_singular'] = '<a href="{project_url_link}" target="_blank">{fulltime_salary_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_fulltime_project_salary_txt'].'</b>neveřejněný<br/> <b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_category_singular_txt'].'</b>{category_list}';


$config['post_fulltime_salary_confidential_project_mapped_projects_professionals_categories_realtime_notification_message_plural'] = '<a href="{project_url_link}" target="_blank">{fulltime_salary_project_title}</a> <br/> <b class="default_black_bold">'.$config['realtime_notification_fulltime_project_salary_txt'].'</b>neveřejněný<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_categories_plural_txt'].'</b>{categories_list}';

$config['post_fulltime_salary_not_sure_project_mapped_projects_professionals_categories_realtime_notification_message_singular'] = '<a href="{project_url_link}" target="_blank">{fulltime_salary_project_title}</a> <br/> <b class="default_black_bold">'.$config['realtime_notification_fulltime_project_salary_txt'].'</b>není jistý<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_category_singular_txt'].'</b>{category_list}';

$config['post_fulltime_salary_not_sure_project_mapped_projects_professionals_categories_realtime_notification_message_plural'] = '<a href="{project_url_link}" target="_blank">{fulltime_salary_project_title}</a> <br/> <b class="default_black_bold">'.$config['realtime_notification_fulltime_project_salary_txt'].'</b>není jistý<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_categories_plural_txt'].'</b>{categories_list}';


/**
 * Define config variables to manage send notification message to favorite employer whenever any project moved to open bidding stage
 * each time favorite employer posted any new project then all users who mark that employer as favorite will get notification for the same
 **/
$config['favorite_employers_post_fixed_budget_realtime_notification_message_singular'] = '<a href="{project_url_link}" target="_blank">{fixed_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>{fixed_budget_range}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_category_singular_txt'].'</b>{category_list}';

$config['favorite_employers_post_fixed_budget_realtime_notification_message_plural'] = '<a href="{project_url_link}" target="_blank">{fixed_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>{fixed_budget_range}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_categories_plural_txt'].'</b>{categories_list}';

$config['favorite_employers_post_fixed_budget_confidential_realtime_notification_message_singular'] = '<a href="{project_url_link}" target="_blank">{fixed_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>neveřejněný<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_category_singular_txt'].'</b>{category_list}';

$config['favorite_employers_post_fixed_budget_confidential_realtime_notification_message_plural'] = '<a href="{project_url_link}" target="_blank">{fixed_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>neveřejněný<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_categories_plural_txt'].'</b>{categories_list}';


$config['favorite_employers_post_fixed_budget_not_sure_realtime_notification_message_singular'] = '<a href="{project_url_link}" target="_blank">{fixed_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>není jistý<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_category_singular_txt'].'</b>{category_list}';

$config['favorite_employers_post_fixed_budget_not_sure_realtime_notification_message_plural'] = '<a href="{project_url_link}" target="_blank">{fixed_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>není jistý<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_categories_plural_txt'].'</b>{categories_list}';


$config['favorite_employers_post_hourly_rate_budget_realtime_notification_message_singular'] = '<a href="{project_url_link}" target="_blank">{hourly_rate_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>{hourly_rate_budget_range}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_category_singular_txt'].'</b>{category_list}';

$config['favorite_employers_post_hourly_rate_budget_realtime_notification_message_plural'] = '<a href="{project_url_link}" target="_blank">{hourly_rate_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>{hourly_rate_budget_range}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_categories_plural_txt'].'</b>{categories_list}';


$config['favorite_employers_post_hourly_rate_budget_confidential_realtime_notification_message_singular'] = '<a href="{project_url_link}" target="_blank">{hourly_rate_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>neveřejněný<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_category_singular_txt'].'</b>{category_list}';

$config['favorite_employers_post_hourly_rate_budget_confidential_realtime_notification_message_plural'] = '<a href="{project_url_link}" target="_blank">{hourly_rate_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>neveřejněný<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_categories_plural_txt'].'</b>{categories_list}';


$config['favorite_employers_post_hourly_rate_budget_not_sure_realtime_notification_message_singular'] = '<a href="{project_url_link}" target="_blank">{hourly_rate_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>není jistý<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_category_singular_txt'].'</b>{category_list}';

$config['favorite_employers_post_hourly_rate_budget_not_sure_realtime_notification_message_plural'] = '<a href="{project_url_link}" target="_blank">{hourly_rate_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>není jistý<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_categories_plural_txt'].'</b>{categories_list}';




$config['favorite_employers_post_fulltime_salary_realtime_notification_message_singular'] = '<a href="{project_url_link}" target="_blank">{fulltime_salary_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_fulltime_project_salary_txt'].'</b>{fulltime_salary_range}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_category_singular_txt'].'</b>{category_list}';

$config['favorite_employers_post_fulltime_salary_realtime_notification_message_plural'] = '<a href="{project_url_link}" target="_blank">{fulltime_salary_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_fulltime_project_salary_txt'].'</b>{fulltime_salary_range}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_categories_plural_txt'].'</b>{categories_list}';



$config['favorite_employers_post_fulltime_salary_confidential_realtime_notification_message_singular'] = '<a href="{project_url_link}" target="_blank">{fulltime_salary_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_fulltime_project_salary_txt'].'</b>neveřejněný<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_category_singular_txt'].'</b>{category_list}';

$config['favorite_employers_post_fulltime_salary_confidential_realtime_notification_message_plural'] = '<a href="{project_url_link}" target="_blank">{fulltime_salary_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_fulltime_project_salary_txt'].'</b>neveřejněný<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_categories_plural_txt'].'</b>{categories_list}';



$config['favorite_employers_post_fulltime_salary_not_sure_realtime_notification_message_singular'] = '<a href="{project_url_link}" target="_blank">{fulltime_salary_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_fulltime_project_salary_txt'].'</b>není jistý<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_category_singular_txt'].'</b>{category_list}';

$config['favorite_employers_post_fulltime_salary_not_sure_realtime_notification_message_plural'] = '<a href="{project_url_link}" target="_blank">{fulltime_salary_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_fulltime_project_salary_txt'].'</b>není jistý<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_categories_plural_txt'].'</b>{categories_list}';

$config['newly_posted_projects_loader_display_text'] = 'vyhledávání...';

?>