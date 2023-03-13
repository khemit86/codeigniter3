<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| AUTO-LOADER
| -------------------------------------------------------------------
| This file specifies which systems should be loaded by default.
|
| In order to keep the framework as light-weight as possible only the
| absolute minimal resources are loaded by default. For example,
| the database is not connected to automatically since no assumption
| is made regarding whether you intend to use it.  This file lets
| you globally define which systems you would like loaded with every
| request.
|
| -------------------------------------------------------------------
| Instructions
| -------------------------------------------------------------------
|
| These are the things you can load automatically:
|
| 1. Packages
| 2. Libraries
| 3. Drivers
| 4. Helper files
| 5. Custom config files
| 6. Language files
| 7. Models
|
*/

/*
| -------------------------------------------------------------------
|  Auto-load Packages
| -------------------------------------------------------------------
| Prototype:
|
|  $autoload['packages'] = array(APPPATH.'third_party', '/usr/local/shared');
|
*/
$autoload['packages'] = array();

/*
| -------------------------------------------------------------------
|  Auto-load Libraries
| -------------------------------------------------------------------
| These are the classes located in system/libraries/ or your
| application/libraries/ directory, with the addition of the
| 'database' library, which is somewhat of a special case.
|
| Prototype:
|
|	$autoload['libraries'] = array('database', 'email', 'session');
|
| You can also supply an alternative library name to be assigned
| in the controller:
|
|	$autoload['libraries'] = array('user_agent' => 'ua');
*/
$autoload['libraries'] = array('database', 'session', 'form_validation', 'Layout'); 

/*
| -------------------------------------------------------------------
|  Auto-load Drivers
| -------------------------------------------------------------------
| These classes are located in system/libraries/ or in your
| application/libraries/ directory, but are also placed inside their
| own subdirectory and they extend the CI_Driver_Library class. They
| offer multiple interchangeable driver options.
|
| Prototype:
|
|	$autoload['drivers'] = array('cache');
|
| You can also supply an alternative property name to be assigned in
| the controller:
|
|	$autoload['drivers'] = array('cache' => 'cch');
|
*/
$autoload['drivers'] = array();

/*
| -------------------------------------------------------------------
|  Auto-load Helper Files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['helper'] = array('url', 'file');
*/
$autoload['helper'] = array('ci_helper', 'user_source_connection_tracking_helper', 'url', 'form', 'html', 'cookie', 'text', 'string');

/*
| -------------------------------------------------------------------
|  Auto-load Config files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['config'] = array('config1', 'config2');
|
| NOTE: This item is intended for use ONLY if you have created custom
| config files.  Otherwise, leave it blank.
|
*/
$autoload['config'] = array(
	SITE_LANGUAGE.'_server_custom_config',
	SITE_LANGUAGE.'_dashboard_custom_config',
	SITE_LANGUAGE.'_user_custom_config',
	SITE_LANGUAGE.'_invite_friends_custom_config',
	SITE_LANGUAGE.'_signup_custom_config',
	SITE_LANGUAGE.'_signin_custom_config',
	SITE_LANGUAGE.'_user_reset_login_password_custom_config',
	SITE_LANGUAGE.'_membership_custom_config',
	SITE_LANGUAGE.'_post_project_custom_config',
	SITE_LANGUAGE.'_activity_custom_config',
	SITE_LANGUAGE.'_projects_custom_config',
	SITE_LANGUAGE.'_find_project_custom_config',
	SITE_LANGUAGE.'_find_professionals_custom_config',
	SITE_LANGUAGE.'_bidding_custom_config',
	SITE_LANGUAGE.'_account_management_custom_config',
	SITE_LANGUAGE.'_profile_management_custom_config',
	SITE_LANGUAGE.'_chat_custom_config',
	SITE_LANGUAGE.'_favorite_employers_custom_config',
	SITE_LANGUAGE.'_escrow_custom_config',
	SITE_LANGUAGE.'_newly_posted_projects_realtime_notifications_custom_config',
	SITE_LANGUAGE.'_contacts_management_custom_config',
	SITE_LANGUAGE.'_work_experience_custom_config',
	SITE_LANGUAGE.'_education_training_custom_config',
	SITE_LANGUAGE.'_certifications_custom_config',
	SITE_LANGUAGE.'_portfolio_custom_config',
	SITE_LANGUAGE.'_finances_custom_config',
	SITE_LANGUAGE.'_bidding_fixed_budget_projects_custom_config',
	SITE_LANGUAGE.'_bidding_hourly_rate_projects_custom_config',
	SITE_LANGUAGE.'_escrow_fixed_budget_projects_custom_config',
	SITE_LANGUAGE.'_escrow_hourly_rate_based_projects_custom_config',
	SITE_LANGUAGE.'_escrow_fulltime_projects_custom_config',
	SITE_LANGUAGE.'_user_projects_payments_overview_custom_config',
	SITE_LANGUAGE.'_bidding_fulltime_projects_custom_config',
	SITE_LANGUAGE.'_invite_to_project_custom_config',
	SITE_LANGUAGE.'_users_ratings_feedbacks_custom_config',
	SITE_LANGUAGE.'_projects_disputes_custom_config',
	SITE_LANGUAGE.'_contact_us_custom_config',
	SITE_LANGUAGE.'_signup_emails_sent_to_users_custom_config',
	SITE_LANGUAGE.'_user_reset_login_password_emails_sent_to_users_custom_config',
	SITE_LANGUAGE.'_account_management_emails_sent_to_users_custom_config',
	SITE_LANGUAGE.'_dashboard_emails_sent_to_users_custom_config',
	SITE_LANGUAGE.'_invite_friends_emails_sent_to_users_custom_config',
	SITE_LANGUAGE.'_invite_to_project_emails_sent_to_users_custom_config',
	SITE_LANGUAGE.'_projects_emails_sent_to_users_custom_config',
	SITE_LANGUAGE.'_projects_disputes_emails_sent_to_users_custom_config',
	SITE_LANGUAGE.'_account_management_settings_custom_config',
	SITE_LANGUAGE.'_activity_settings_custom_config',
	SITE_LANGUAGE.'_bidding_settings_custom_config',
	SITE_LANGUAGE.'_bidding_fixed_budget_projects_settings_custom_config',
	SITE_LANGUAGE.'_bidding_hourly_rate_projects_settings_custom_config',
	SITE_LANGUAGE.'_bidding_fulltime_projects_settings_custom_config',
	SITE_LANGUAGE.'_certifications_settings_custom_config',
	SITE_LANGUAGE.'_dashboard_settings_custom_config',
	SITE_LANGUAGE.'_chat_settings_custom_config',
	SITE_LANGUAGE.'_escrow_settings_custom_config',
	SITE_LANGUAGE.'_favorite_employers_settings_custom_config',
	SITE_LANGUAGE.'_finances_settings_custom_config',
	SITE_LANGUAGE.'_find_professionals_settings_custom_config',
	SITE_LANGUAGE.'_find_project_settings_custom_config',
	SITE_LANGUAGE.'_invite_friends_settings_custom_config',
	SITE_LANGUAGE.'_newly_posted_projects_realtime_notifications_settings_custom_config',
	SITE_LANGUAGE.'_post_project_settings_custom_config',
	SITE_LANGUAGE.'_projects_settings_custom_config',
	SITE_LANGUAGE.'_projects_disputes_settings_custom_config',
	SITE_LANGUAGE.'_signup_settings_custom_config',
	SITE_LANGUAGE.'_user_projects_payments_overview_settings_custom_config',
	SITE_LANGUAGE.'_user_reset_login_password_settings_custom_config',
	SITE_LANGUAGE.'_users_ratings_feedbacks_settings_custom_config',
	SITE_LANGUAGE.'_portfolio_settings_custom_config',
	SITE_LANGUAGE.'_work_experience_settings_custom_config',
	SITE_LANGUAGE.'_education_training_settings_custom_config',
	SITE_LANGUAGE.'_contacts_management_settings_custom_config',
	SITE_LANGUAGE.'_invite_to_project_settings_custom_config',
	SITE_LANGUAGE.'_server_settings_custom_config',
	SITE_LANGUAGE.'_profile_management_settings_custom_config',
	SITE_LANGUAGE.'_user_settings_custom_config',
	SITE_LANGUAGE.'_contact_us_settings_custom_config',
	SITE_LANGUAGE.'_contact_us_emails_sent_to_users_custom_config'
	
);
/*
| -------------------------------------------------------------------
|  Auto-load Language files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['language'] = array('lang1', 'lang2');
|
| NOTE: Do not include the "_lang" part of your file.  For example
| "codeigniter_lang.php" would be referenced as array('codeigniter');
|
*/
$autoload['language'] = array();

/*
| -------------------------------------------------------------------
|  Auto-load Models
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['model'] = array('first_model', 'second_model');
|
| You can also supply an alternative model name to be assigned
| in the controller:
|
|	$autoload['model'] = array('first_model' => 'first');
*/
$autoload['model'] = array('auto_model','basemodel','autoload_model');
