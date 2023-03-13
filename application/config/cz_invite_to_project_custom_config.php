<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// error message regarding project nvitation
$config['po_already_sent_project_invitation_to_sp_error_message'] = 'pozvání pro {user_first_name_last_name_or_company_name} už bylo odesláno';

$config['po_sent_project_invitation_to_sp_project_status_changed_error_message'] = 'status inzerátu byl změněn, nelze odeslat pozvání';
################################################################################################################################################################################
//Gender based config when sp already applied bid on project(fixed/hourly)
$config['po_sent_project_invitation_to_male_sp_already_apply_bid_on_project_error_message'] = '{user_first_name_last_name} už odeslal nabídku na tento projekt';

$config['po_sent_project_invitation_to_female_sp_already_apply_bid_on_project_error_message'] = '{user_first_name_last_name} už odeslala nabídku na tento projekt';

$config['po_sent_project_invitation_to_company_sp_already_apply_bid_on_project_error_message'] = '{user_company_name} už odeslali nabídku na tento projekt';

//Gender based config when sp already applied bid on fulltime project
$config['po_sent_project_invitation_to_male_sp_already_apply_bid_on_fulltime_project_error_message'] = '{user_first_name_last_name} už odeslal žádost na tuto pracovní pozici';

$config['po_sent_project_invitation_to_female_sp_already_apply_bid_on_fulltime_project_error_message'] = '{user_first_name_last_name} už odeslala žádost na tuto pracovní pozici';

$config['po_sent_project_invitation_to_company_sp_already_apply_bid_on_fulltime_project_error_message'] = '{user_company_name} už odeslali žádost na tuto pracovní pozici';

################################################################################################################################################################################
//Gender based config when po award project to sp(fixed/hourly)
$config['po_sent_project_invitation_to_male_sp_already_awarded_same_sp_on_project_error_message'] = 'už jste udělili tento projekt pro {user_first_name_last_name}';

$config['po_sent_project_invitation_to_female_sp_already_awarded_same_sp_on_project_error_message'] = 'už jste udělili tento projekt pro {user_first_name_last_name}';

$config['po_sent_project_invitation_to_company_sp_already_awarded_same_sp_on_project_error_message'] = 'už jste udělili tento projekt pro {user_company_name}';

//Gender based config when po award on fulltime project 
$config['po_sent_project_invitation_to_male_sp_already_awarded_same_sp_on_fulltime_project_error_message'] = 'už jste udělili tuto pracovní pozici pro {user_first_name_last_name}';

$config['po_sent_project_invitation_to_female_sp_already_awarded_same_sp_on_fulltime_project_error_message'] = 'už jste udělili tuto pracovní pozici pro {user_first_name_last_name}';

$config['po_sent_project_invitation_to_company_sp_already_awarded_same_sp_on_fulltime_project_error_message'] = 'už jste udělili tuto pracovní pozici pro {user_company_name}';

################################################################################################################################################################################
//Gender based config when po assign job to sp(fixed/hourly) (project status->in progress/incomplete)
$config['po_sent_project_invitation_to_male_sp_in_progress_same_sp_on_project_error_message'] = 'už jste najali {user_first_name_last_name} na tento projekt';

$config['po_sent_project_invitation_to_female_sp_in_progress_same_sp_on_project_error_message'] = 'už jste najali {user_first_name_last_name} na tento projekt';

$config['po_sent_project_invitation_to_company_sp_in_progress_same_sp_on_project_error_message'] = 'už jste najali {user_company_name} na tento projekt';

//Gender based config when po hired sp on fulltime project 
$config['po_sent_project_invitation_to_male_sp_hired_same_sp_on_fulltime_project_error_message'] = 'už jste zaměstnali {user_first_name_last_name} na tuto pracovní pozici';

$config['po_sent_project_invitation_to_female_sp_hired_same_sp_on_fulltime_project_error_message'] = 'už jste zaměstnali {user_first_name_last_name} na tuto pracovní pozici';

$config['po_sent_project_invitation_to_company_sp_hired_same_sp_on_fulltime_project_error_message'] = 'už jste zaměstnali {user_company_name} na tuto pracovní pozici';

################################################################################################################################################################################
//Gender based config when sp already completed project(fixed/hourly)
$config['po_sent_project_invitation_to_male_sp_completed_same_sp_on_project_error_message'] = 'tento projekt s {user_first_name_last_name} je již dokončený';

$config['po_sent_project_invitation_to_female_sp_completed_same_sp_on_project_error_message'] = 'tento projekt s {user_first_name_last_name} je již dokončený';

$config['po_sent_project_invitation_to_company_sp_completed_same_sp_on_project_error_message'] = 'tento projekt s {user_company_name} je již dokončený';


$config['po_sent_project_invitation_to_same_user_no_projects_available_left_error_message'] = 'nelze odeslat pozvání, nemáte k dispozici žádné inzeráty';

$config['po_sent_project_invitation_open_projects_not_available_error_message'] = 'nemáte žádný otevřený inzerát';


$config['po_sent_project_invitation_allowed_sent_invites_per_month_error_message'] = 'máte vyčerpaný měsíční limit pro poslání pozvání na inzeráty';

$config['po_sent_project_invitation_allowed_sent_invites_per_project_error_message'] = 'máte vyčerpaný limit pro poslání pozvání na tento projekt';

$config['po_sent_project_invitation_allowed_sent_invites_per_fulltime_project_error_message'] = 'máte vyčerpaný limit pro poslání pozvání na tuto pracovní pozici';

// This config is using when not options available in drop down example per month limit 10 and per project limit 3 now invition alrady sent to three users if trying to sending inviation to fourth user
// user has ONLY 1 open for bidding fulltime or project to which sends max number of invitations allowed per membership (3 for free) but per month can send 10 invitations. user goes to find professionals and tries to send invitation -> nemáte k dispozici inzerát na který lze poslat pozvání / you have no listing available to send invitations for
//* same applies when PO has many projects in open for bidding to which sends max invitations allowed per each but total invitations sent is less than total alowed invitations per month - example -> 500 x 3 - 1500 / 2000
$config['po_sent_project_invitation_no_listing_available_to_send_invitation_to'] = 'nemáte k dispozici inzerát na který lze poslat pozvání';


// Ackowldegement message when PO send project invitation successfully
//By Php
$config['project_invitation_realtime_notification_message_sent_to_po'] = 'pozvání na projekt pro <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> bylo odesláno';

$config['fulltime_project_invitation_realtime_notification_message_sent_to_po'] = 'pozvání na pracovní pozici pro <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> bylo odesláno';


// Activity log message
//For SP
$config['project_invitation_message_sent_to_sp_user_activity_log_displayed_message'] = 'Pozvání na projekt "<a href="{project_url_link}" target="_blank">{project_title}</a>" od <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>.';

$config['fulltime_project_invitation_message_sent_to_sp_user_activity_log_displayed_message'] = 'Pozvání na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>" od <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>.';


// For PO
$config['project_invitation_message_sent_to_po_user_activity_log_displayed_message'] = 'Odeslali jste pozvání na projekt "<a href="{project_url_link}" target="_blank">{project_title}</a>" pro <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>.';

$config['fulltime_project_invitation_message_sent_to_po_user_activity_log_displayed_message'] = 'Odeslali jste pozvání na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>" pro <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>.';


//NOTIFICATIONS sent By Node
$config['project_invitation_realtime_notification_message_sent_to_sp'] = 'pozvání na projekt "<a href="{project_url_link}" target="_blank">{project_title}</a>" od <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>';

$config['fulltime_project_invitation_realtime_notification_message_sent_to_sp'] = 'pozvání na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>" od <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>';

?>