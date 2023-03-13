<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

#####################################################################################################

$config['projects_realtime_notification_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | projects_notification Title Meta Tag'; //meta title
$config['projects_realtime_notification_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | projects_notification Description Meta Tag'; //meta description

$config['projects_realtime_notification_feed_page_url'] = 'projects-notification-feed'; //url

################ Send notification popup Variables ###########
$config['send_realtime_notification_popup_type'] = 'minimalist'; // this is type of popup which visible to user [success/info/warning/danger]
  
$config['send_realtime_notification_popup_placement_from'] = 'bottom'; // this is used to manage popup position [top/bottom]
$config['send_realtime_notification_popup_placement_align'] = 'left'; // this is used to manage popup alignment [left / center / right]
$config['send_realtime_notification_popup_animate_enter'] = 'bounceOutUp'; // this is used to animate popup based on popup placement its value can vary
$config['send_realtime_notification_popup_animate_exit'] = 'bounceInDown'; // this is used to animate popup based on popup placement its value can vary
$config['send_realtime_notification_popup_url_traget'] = '_blank'; // this is used to manage url to be open in tab when user click on popup notification [_blank / _self]


#####################################################################################################

//This config are using for small pending project notification window as a heading into header.This will show when click on project notifications icon into header
$config['top_navigation_small_window_projects_notifications_heading'] = 'project notification-';
$config['newly_posted_projects_headline_title_projects_notification_feed'] = 'Projects Notification Feeds';

$config['projects_notification_feed_page_no_project_available_message'] = 'V tento okamžík nejsou k dispozici žádné inzeráty. - projects_notification_feed_page'; //-> move this to cz_newly_posted_projects_realtime_notifications_custom_config.php
$config['projects_notification_feed_page_search_no_results_returned_message'] = '<h4>Vyhledávání neodpovídají žádné výsledky.</h4><p>Upravte výběr a zkuste znovu. - projects_notification_feed_page</p>'; //-> move this to cz_newly_posted_projects_realtime_notifications_custom_config.php

$config['pa_projects_realtime_notification_no_record'] = '<h5>V tento okamžik nemáte žádné přizpůsobené inzeráty.</h5><p><small>Zde obdržíte informaci o novém vytvořeném inzerátu, který se shoduje s vašimi vybranými kategoriemi na vašem profilu a od vašich oblíbených zaměstnavatelů.</small></p>';
$config['ca_projects_realtime_notification_no_record'] = '<h5>V tento okamžik nemáte žádné přizpůsobené inzeráty.</h5><p><small>Zde obdržíte informaci o novém vytvořeném inzerátu, který se shoduje s vašimi vybranými kategoriemi na vašem profilu a od vašich oblíbených partnerů.</small></p>';



//$config['projects_realtime_notification_search_no_results_returned_message'] = '<h3>There are no results that match your search</h3><p>Please try adjusting your search filters and try again.</p>'; // no record found
// $config['projects_realtime_notification_search_no_results_returned_message'] = '<h3>Vyhledávání neodpovídají žádné výsledky. (here are no results that match your search)</h3><p>Upravte výběr a zkuste znovu. (adjust your search filters and try again)</p>';


$config['realtime_notification_project_posted_in_following_category_singular_txt'] = 'Posted in following category1:';
$config['realtime_notification_project_posted_in_following_categories_plural_txt'] = 'Posted in following categories1:';
$config['realtime_notification_project_posting_date_txt'] = 'Posting date1:';
$config['realtime_notification_project_budget_txt'] = 'Project Budget1:';
$config['realtime_notification_fulltime_project_salary_txt'] = 'Salary1:';
$config['realtime_notification_posted_by_txt'] = 'Posted By1:';

$config['user_consent_newly_posted_projects_areas_of_expertise_receive_realtime_notification_success_message'] = 'From now on you will receive real time notifications each time a new project that fits your areas of expertise is posted on the portal.';

$config['user_consent_newly_posted_projects_areas_of_expertise_stop_receiving_realtime_notification_success_message'] = 'You stopped to receive real time notifications each time a new project that fits your areas of expertise is posted on the portal.';

$config['pa_user_consent_newly_posted_projects_favorite_employer_receive_realtime_notification_success_message'] = 'Přihlásili jste se k odběru oznámení v reálném čase, pokaždé kdy váš oblíbený zaměstnavatel vytvoří nový inzerát.';
$config['pa_user_consent_newly_posted_projects_favorite_employer_stop_receiving_realtime_notification_success_message'] = 'Odhlásili jste se z odběru oznámení v reálném čase, pokaždé kdy váš oblíbený zaměstnavatel vytvoří nový inzerát.';

$config['ca_user_consent_newly_posted_projects_favorite_employer_receive_realtime_notification_success_message'] = 'Přihlásili jste se k odběru oznámení v reálném čase, pokaždé kdy váš oblíbený partner vytvoří nový inzerát.';

$config['ca_user_consent_newly_posted_projects_favorite_employer_stop_receiving_realtime_notification_success_message'] = 'Odhlásili jste se z odběru oznámení v reálném čase, pokaždé kdy váš oblíbený partner vytvoří nový inzerát.';


/**
 * Define config variables to manage send notification message to mapped category whenever any project moved to open bidding stage
 * each time a project is posted on a category (or categories) all users having their profiles listed in mapped category (or categories) to that category, will receive a notification regarding that project
 **/
$config['post_fixed_budget_project_mapped_projects_professionals_categories_realtime_notification_message_singular'] = 'En:Fixed:<a href="{project_url_link}" target="_blank">{fixed_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>{fixed_budget_range}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_category_singular_txt'].'</b>{category_list}';

$config['post_fixed_budget_project_mapped_projects_professionals_categories_realtime_notification_message_plural'] = 'En:Fixed:<a href="{project_url_link}" target="_blank">{fixed_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>{fixed_budget_range}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_categories_plural_txt'].'</b>{categories_list}';

$config['post_fixed_budget_confidential_project_mapped_projects_professionals_categories_realtime_notification_message_singular'] = 'En:Fixed:<a href="{project_url_link}" target="_blank">{fixed_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>confidential<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_category_singular_txt'].'</b>{category_list}';

$config['post_fixed_budget_confidential_project_mapped_projects_professionals_categories_realtime_notification_message_plural'] = 'En:Fixed:<a href="{project_url_link}" target="_blank">{fixed_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>confidential<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_categories_plural_txt'].'</b>{categories_list}';

$config['post_fixed_budget_not_sure_project_mapped_projects_professionals_categories_realtime_notification_message_singular'] = 'En:Fixed:<a href="{project_url_link}" target="_blank">{fixed_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>not sure<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_category_singular_txt'].'</b>{category_list}';

$config['post_fixed_budget_not_sure_project_mapped_projects_professionals_categories_realtime_notification_message_plural'] = 'En:Fixed:<a href="{project_url_link}" target="_blank">{fixed_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>not sure<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_categories_plural_txt'].'</b>{categories_list}';


$config['post_hourly_rate_budget_project_mapped_projects_professionals_categories_realtime_notification_message_singular'] = 'En:hourly:<a href="{project_url_link}" target="_blank">{hourly_rate_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>{hourly_rate_budget_range}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_category_singular_txt'].'</b>{category_list}';

$config['post_hourly_rate_budget_project_mapped_projects_professionals_categories_realtime_notification_message_plural'] = 'En:hourly:<a href="{project_url_link}" target="_blank">{hourly_rate_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>{hourly_rate_budget_range}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_categories_plural_txt'].'</b>{categories_list}';

$config['post_hourly_rate_budget_confidential_project_mapped_projects_professionals_categories_realtime_notification_message_singular'] = 'En:hourly:<a href="{project_url_link}" target="_blank">{hourly_rate_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>confidential<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_category_singular_txt'].'</b>{category_list}';

$config['post_hourly_rate_budget_confidential_project_mapped_projects_professionals_categories_realtime_notification_message_plural'] = 'En:hourly:<a href="{project_url_link}" target="_blank">{hourly_rate_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>confidential<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_categories_plural_txt'].'</b>{categories_list}';

$config['post_hourly_rate_budget_not_sure_project_mapped_projects_professionals_categories_realtime_notification_message_singular'] = 'En:hourly:<a href="{project_url_link}" target="_blank">{hourly_rate_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>not sure<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_category_singular_txt'].'</b>{category_list}';

$config['post_hourly_rate_budget_not_sure_project_mapped_projects_professionals_categories_realtime_notification_message_plural'] = 'En:hourly:<a href="{project_url_link}" target="_blank">{hourly_rate_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>not sure<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_categories_plural_txt'].'</b>{categories_list}';

$config['post_fulltime_salary_project_mapped_projects_professionals_categories_realtime_notification_message_singular'] = 'En:Fulltime:<a href="{project_url_link}" target="_blank">{fulltime_salary_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_fulltime_project_salary_txt'].'</b>{fulltime_salary_range}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_category_singular_txt'].'</b>{category_list}';

$config['post_fulltime_salary_project_mapped_projects_professionals_categories_realtime_notification_message_plural'] = 'En:Fulltime:<a href="{project_url_link}" target="_blank">{fulltime_salary_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_fulltime_project_salary_txt'].'</b>{fulltime_salary_range}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_categories_plural_txt'].'</b>{categories_list}';

$config['post_fulltime_salary_confidential_project_mapped_projects_professionals_categories_realtime_notification_message_singular'] = 'En:Fulltime:<a href="{project_url_link}" target="_blank">{fulltime_salary_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_fulltime_project_salary_txt'].'</b>confidential<br/> <b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_category_singular_txt'].'</b>{category_list}';


$config['post_fulltime_salary_confidential_project_mapped_projects_professionals_categories_realtime_notification_message_plural'] = 'En:Fulltime:<a href="{project_url_link}" target="_blank">{fulltime_salary_project_title}</a> <br/> <b class="default_black_bold">'.$config['realtime_notification_fulltime_project_salary_txt'].'</b>confidential<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_categories_plural_txt'].'</b>{categories_list}';
$config['post_fulltime_salary_not_sure_project_mapped_projects_professionals_categories_realtime_notification_message_singular'] = 'En:Fulltime:<a href="{project_url_link}" target="_blank">{fulltime_salary_project_title}</a> <br/> <b class="default_black_bold">'.$config['realtime_notification_fulltime_project_salary_txt'].'</b>not sure<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_category_singular_txt'].'</b>{category_list}';

$config['post_fulltime_salary_not_sure_project_mapped_projects_professionals_categories_realtime_notification_message_plural'] = 'En:Fulltime:<a href="{project_url_link}" target="_blank">{fulltime_salary_project_title}</a> <br/> <b class="default_black_bold">'.$config['realtime_notification_fulltime_project_salary_txt'].'</b>not sure<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_categories_plural_txt'].'</b>{categories_list}';


/**
 * Define config variables to manage send notification message to favorite employer whenever any project moved to open bidding stage
 * each time favorite employer posted any new project then all users who mark that employer as favorite will get notification for the same
 **/
$config['favorite_employers_post_fixed_budget_realtime_notification_message_singular'] = 'En:Fixed:<a href="{project_url_link}" target="_blank">{fixed_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>{fixed_budget_range}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_category_singular_txt'].'</b>{category_list}';

$config['favorite_employers_post_fixed_budget_realtime_notification_message_plural'] = 'En:Fixed:<a href="{project_url_link}" target="_blank">{fixed_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>{fixed_budget_range}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_categories_plural_txt'].'</b>{categories_list}';

$config['favorite_employers_post_fixed_budget_confidential_realtime_notification_message_singular'] = 'En:Fixed:<a href="{project_url_link}" target="_blank">{fixed_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>confidential<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_category_singular_txt'].'</b>{category_list}';

$config['favorite_employers_post_fixed_budget_confidential_realtime_notification_message_plural'] = 'En:Fixed:<a href="{project_url_link}" target="_blank">{fixed_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>confidential<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_categories_plural_txt'].'</b>{categories_list}';


$config['favorite_employers_post_fixed_budget_not_sure_realtime_notification_message_singular'] = 'En:Fixed:<a href="{project_url_link}" target="_blank">{fixed_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>not sure<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_category_singular_txt'].'</b>{category_list}';

$config['favorite_employers_post_fixed_budget_not_sure_realtime_notification_message_plural'] = 'En:Fixed:<a href="{project_url_link}" target="_blank">{fixed_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>not sure<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_categories_plural_txt'].'</b>{categories_list}';

$config['favorite_employers_post_hourly_rate_budget_realtime_notification_message_singular'] = 'En:Hourly:<a href="{project_url_link}" target="_blank">{hourly_rate_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>{hourly_rate_budget_range}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_category_singular_txt'].'</b>{category_list}';


$config['favorite_employers_post_hourly_rate_budget_realtime_notification_message_plural'] = 'En:Hourly:<a href="{project_url_link}" target="_blank">{hourly_rate_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>{hourly_rate_budget_range}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_categories_plural_txt'].'</b>{categories_list}';

$config['favorite_employers_post_hourly_rate_budget_confidential_realtime_notification_message_singular'] = 'En:Hourly:<a href="{project_url_link}" target="_blank">{hourly_rate_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>confidential<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_category_singular_txt'].'</b>{category_list}';

$config['favorite_employers_post_hourly_rate_budget_confidential_realtime_notification_message_plural'] = 'En:Hourly:<a href="{project_url_link}" target="_blank">{hourly_rate_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>confidential<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_categories_plural_txt'].'</b>{categories_list}';

$config['favorite_employers_post_hourly_rate_budget_not_sure_realtime_notification_message_singular'] = 'En:Hourly:<a href="{project_url_link}" target="_blank">{hourly_rate_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>not sure<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_category_singular_txt'].'</b>{category_list}';

$config['favorite_employers_post_hourly_rate_budget_not_sure_realtime_notification_message_plural'] = 'En:Hourly:<a href="{project_url_link}" target="_blank">{hourly_rate_budget_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_project_budget_txt'].'</b>not sure<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_categories_plural_txt'].'</b>{categories_list}';

$config['favorite_employers_post_fulltime_salary_realtime_notification_message_singular'] = 'En:Fulltime:<a href="{project_url_link}" target="_blank">{fulltime_salary_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_fulltime_project_salary_txt'].'</b>{fulltime_salary_range}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_category_singular_txt'].'</b>{category_list}';



$config['favorite_employers_post_fulltime_salary_realtime_notification_message_plural'] = 'En:Fulltime:<a href="{project_url_link}" target="_blank">{fulltime_salary_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_fulltime_project_salary_txt'].'</b>{fulltime_salary_range}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_categories_plural_txt'].'</b>{categories_list}';

$config['favorite_employers_post_fulltime_salary_confidential_realtime_notification_message_singular'] = 'En:Fulltime:<a href="{project_url_link}" target="_blank">{fulltime_salary_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_fulltime_project_salary_txt'].'</b>confidential<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_category_singular_txt'].'</b>{category_list}';

$config['favorite_employers_post_fulltime_salary_confidential_realtime_notification_message_plural'] = 'En:Fulltime:<a href="{project_url_link}" target="_blank">{fulltime_salary_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_fulltime_project_salary_txt'].'</b>confidential<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_categories_plural_txt'].'</b>{categories_list}';

$config['favorite_employers_post_fulltime_salary_not_sure_realtime_notification_message_singular'] = 'En:Fulltime:<a href="{project_url_link}" target="_blank">{fulltime_salary_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_fulltime_project_salary_txt'].'</b>not sure<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_category_singular_txt'].'</b>{category_list}';

$config['favorite_employers_post_fulltime_salary_not_sure_realtime_notification_message_plural'] = 'En:Fulltime:<a href="{project_url_link}" target="_blank">{fulltime_salary_project_title}</a><br/><b class="default_black_bold">'.$config['realtime_notification_fulltime_project_salary_txt'].'</b>not sure<br/><b class="default_black_bold">'.$config['realtime_notification_project_posting_date_txt'].'</b>{project_posting_date}<br/><b class="default_black_bold">'.$config['realtime_notification_project_posted_in_following_categories_plural_txt'].'</b>{categories_list}';

?>