<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once SITE_LANGUAGE.'_invite_friends_custom_config.php';
include_once SITE_LANGUAGE.'_server_custom_config.php';
include_once SITE_LANGUAGE.'_signup_custom_config.php';
include_once SITE_LANGUAGE.'_signin_custom_config.php';
include_once SITE_LANGUAGE.'_dashboard_custom_config.php';
include_once SITE_LANGUAGE.'_account_management_custom_config.php';
include_once SITE_LANGUAGE.'_profile_management_custom_config.php';
include_once SITE_LANGUAGE.'_work_experience_custom_config.php';
include_once SITE_LANGUAGE.'_education_training_custom_config.php';
include_once SITE_LANGUAGE.'_certifications_custom_config.php';
include_once SITE_LANGUAGE.'_portfolio_custom_config.php';
include_once SITE_LANGUAGE.'_user_reset_login_password_custom_config.php';
include_once SITE_LANGUAGE.'_membership_custom_config.php';
include_once SITE_LANGUAGE.'_post_project_custom_config.php';
include_once SITE_LANGUAGE.'_activity_custom_config.php';
include_once SITE_LANGUAGE.'_projects_custom_config.php';
include_once SITE_LANGUAGE.'_find_project_custom_config.php';
include_once SITE_LANGUAGE.'_find_professionals_custom_config.php';
include_once SITE_LANGUAGE.'_bidding_custom_config.php';
include_once SITE_LANGUAGE.'_user_custom_config.php';
include_once SITE_LANGUAGE.'_chat_custom_config.php';
include_once SITE_LANGUAGE.'_favorite_employers_custom_config.php';
include_once SITE_LANGUAGE.'_escrow_custom_config.php';
include_once SITE_LANGUAGE.'_newly_posted_projects_realtime_notifications_custom_config.php';
include_once SITE_LANGUAGE.'_contacts_management_custom_config.php';
include_once SITE_LANGUAGE.'_finances_custom_config.php';
include_once SITE_LANGUAGE.'_user_projects_payments_overview_custom_config.php';
include_once SITE_LANGUAGE.'_users_ratings_feedbacks_custom_config.php';
include_once SITE_LANGUAGE.'_projects_disputes_custom_config.php';

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
// session_start();
$route['default_controller'] = "user/home";
$route['404_override'] = 'custom404page';
$route['translate_uri_dashes'] = FALSE;





$route[$config['account_management_account_overview_page_url']] = 'user/account_management_account_overview';
$route[$config['account_management_avatar_details_page_url']] = 'user/account_management_avatar';
$route[$config['account_management_address_details_page_url']] = 'user/account_management_address';

$route[$config['account_management_contact_details_page_url']] = 'user/account_management_contact_details';

$route[$config['account_management_account_login_details_page_url']] = 'user/account_management_account_login_details';

/*$route[$config['account_management_verifications_page_url']] = 'user/account_management_verifications';
 $route[$config['account_management_manage_email_page_url']] = 'user/account_management_email';
$route[$config['account_management_manage_password_page_url']] = 'user/account_management_password'; */
$route[$config['account_management_close_account_page_url']] = 'user/account_management_close_account';



$route[$config['profile_management_profile_definitions_page_url']] = 'user/profile_management_page_profile_definitions';
$route[$config['profile_management_company_base_information_page_url']] = 'user/profile_management_page_company_base_information';
$route[$config['profile_management_competencies_page_url']] = 'user/profile_management_page_competencies';
$route[$config['profile_management_mother_tongue_page_url']] = 'user/profile_management_page_mother_tongue';
$route[$config['profile_management_spoken_foreign_languages_page_url']] = 'user/profile_management_page_spoken_foreign_languages';
$route[$config['profile_management_company_values_and_principles_page_url']] = 'user/profile_management_page_company_values_and_principles';

$route[$config['work_experience_page_url']] = 'user/work_experience';

$route[$config['invite_friends_page_url']] = "invite_friends/invite_friend_view";

$route[$config['activity_page_url']] = 'user_activity_log/user_activity';
$route[$config['education_training_page_url']] = 'user/education_training';
$route[$config['certifications_page_url']] = 'user/certifications';
$route[$config['portfolio_page_url']] = 'user/portfolio';
$route[$config['portfolio_standalone_page_url']] = 'user/portfolio_standalone_page';

$route[$config['projects_realtime_notification_feed_page_url']] = 'newly_posted_projects_realtime_notifications/projects_notification_feed';




//definition for register page url - applications/signup module
$route[$config['referrer_page_url']] = 'signup/signup/user_signup';
$route[$config['signup_page_url']] = "signup/signup/user_signup";
$route[$config['signup_confirmation_page_url']] = 'signup/signup/signup_confirmation_successful';
$route[$config['signup_activate_page_url']] = 'signup/signup/signup_verification';
//$route[$config['switch_account_page_url']] = 'signup/signup/switch_account';
$route[$config['signup_verify_redirection_page_url']] = 'signup/signup/verify_redirection';
$route[$config['signup_verified_page_url']] = 'signup/signup/user_signup_successful_verification';

//definition for register page url - applications/modules/signin module
$route[$config['signin_page_url']] = "signin/signin/signin";
$route[$config['logout_page_url']] = "user/user/logout";

//definition for forgot password page url - applications/user_reset_login_password module
$route[$config['reset_login_password_page_url']] = "user_reset_login_password/User_reset_login_password/reset_login_password";
$route[$config['forgot_password_send_reset_confirmation_page_url']] = "user_reset_login_password/User_reset_login_password/send_password_reset_confirmation";
$route[$config['forgot_password_successful_password_reset_page_url']] = "user_reset_login_password/User_reset_login_password/successfull_password_reset";


//definition for post project page url - applications/post_project module

$route[$config['post_project_page_url']] = "post_project/Post_project/index"; // routing for post project page

$route[$config['post_project_temp_project_attachment_download_page_url']] = "post_project/Post_project/download_project_attachment_temp"; // routing for download attachment from temporary table

$route[$config['post_project_temporary_project_preview_page_url']] = "post_project/Post_project/temporary_project_preview"; // routing for temporary preview page in post project
$route[$config['post_project_edit_temporary_project_preview_page_url']] = "post_project/Post_project/edit_temporary_project_preview";// routing for edit temporary preview page in post project

$route[$config['edit_draft_project_page_url']] = "projects/Projects/edit_draft_project"; // routing for edit draft page in projects module

$route[$config['preview_draft_project_page_url']] = "projects/Projects/preview_draft_project";
// routing for preview draft page in projects module
 
$route[$config['project_detail_page_url']] = "projects/Projects/project_detail";
// routing for project detail page in projects module

$route[$config['edit_project_page_url']] = "projects/Projects/edit_project";
// routing for edit project page

$route[$config['pending_feedbacks_management_page_url']] = "users_ratings_feedbacks/Users_ratings_feedbacks/user_projects_pending_ratings_feedbacks";
// routing for pending feedbacks for project

$route[$config['find_projects_page_url']] = "find_project/Find_project/index";
// routing for find project page in find_project module

$route[$config['find_professionals_page_url']] = "find_professionals/Find_professionals/index";
// routing for find project page in find_professionals module


$route[$config['project_detail_page_payments_section_paging_url']] = "escrow/Escrow/load_pagination_escrows";
// routing for payments section on project detail page

$route[$config['my_projects_page_url']] = "projects/Projects/load_pagination_my_projects";
// routing for my projects paging
//user projects payment management page overview url
$route[$config['user_projects_payments_overview_page_url']] = 'user_projects_payments_overview/User_projects_payments_overview/user_projects_payments_overview';

//projects disputes management page url
$route[$config['projects_disputes_page_url']] = 'projects_disputes/Projects_disputes/projects_disputes';

// definition for membership page url
$route[$config['membership_page_url']] = 'membership/membership_plan';


//definition for dashboard page url - applications/dashboard module
$route[$config['dashboard_page_url']] = "dashboard/dashboard/user_dashboard";

//favorite_employers_page_url
$route[$config['pa_favorite_employers_page_url']] = 'favorite_employers/favorite_employer_list';
$route[$config['ca_favorite_employers_page_url']] = 'favorite_employers/favorite_employer_list';

//disputes_page_url
$route[$config['project_dispute_details_page_url']] = 'projects_disputes/project_dispute_details';



// myprojects_page_url
$route[$config['myprojects_page_url']] = 'projects/my_projects_listing';

//Chat
$route[$config['chat_room_page_url']] = 'chat/chat_room';

//contacts_management
$route[$config['contacts_management_page_url']] = 'chat/contacts_management';


// for module user_projects_payments_overview
$route[$config['user_projects_payments_overview_page_paging_url']] = "user_projects_payments_overview/User_projects_payments_overview/load_user_projects_payments_overview_section_tabs_next_page_data";

// for module project disputes modules
$route[$config['user_projects_disputes_management_page_paging_url']] = "project_disputes/Projects_disputes/load_user_projects_disputes_page_section_tabs_next_page_data";

//finance module
//deposit funds url
$route[$config['finance_deposit_funds_page_url']] = 'finances/deposit_funds';
//deposit funds url
$route[$config['finance_withdraw_funds_page_url']] = 'finances/withdraw_funds';
//transaction history url
$route[$config['finance_transactions_history_page_url']] = 'finances/transactions_history';
//invoices url
$route[$config['finance_invoices_page_url']] = 'finances/invoices';
//invoicing details url
$route[$config['finance_invoicing_details_page_url']] = 'finances/company_invoicing_details';

############# routing for static pages start here ######################

$route[$config['contact_us_page_url']] = 'user/user/contact_us';
$route[$config['faq_page_url']] = 'user/user/faq';

$route[$config['about_us_page_url']] = 'user/user/about_us';
$route[$config['terms_and_conditions_page_url']] = 'user/user/terms_and_conditions';
$route[$config['code_of_conduct_page_url']] = 'user/user/code_of_conduct';
$route[$config['privacy_policy_page_url']] = 'user/user/privacy_policy';

$route[$config['trust_and_safety_page_url']] = 'user/user/trust_and_safety';
$route[$config['referral_program_page_url']] = 'user/user/referral_program';
$route[$config['secure_payments_process_page_url']] = 'user/user/secure_payments_process';
$route[$config['fees_and_charges_page_url']] = 'user/user/fees_and_charges';
$route[$config['we_vs_them_page_url']] = 'user/user/we_vs_them';

############# routing for static pages end here ########################

$route['(:any)'] = 'user/user/user_profile/$1';

