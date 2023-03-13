<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// error message regarding project invitation
$config['po_already_sent_project_invitation_to_sp_error_message'] = 'you have already sent invitation to {user_first_name_last_name_or_company_name}';

$config['po_sent_project_invitation_to_sp_project_status_changed_error_message'] = 'project status has changed. you cant send invitation';

################################################################################################################################################################################
//Gender based config when sp already applied bid on project(fixed/hourly)
$config['po_sent_project_invitation_to_male_sp_already_apply_bid_on_project_error_message'] = 'Project[Male]:{user_first_name_last_name} already applied bid to your project.';

$config['po_sent_project_invitation_to_female_sp_already_apply_bid_on_project_error_message'] = 'Project[Female]:{user_first_name_last_name} already applied bid to your project.';

$config['po_sent_project_invitation_to_company_sp_already_apply_bid_on_project_error_message'] = 'Project[Company]:{user_company_name} already applied bid to your project.';

//Gender based config when sp already applied bid on fulltime project
$config['po_sent_project_invitation_to_male_sp_already_apply_bid_on_fulltime_project_error_message'] = 'Fulltime Project[Male]:{user_first_name_last_name} already applied bid to your project.';

$config['po_sent_project_invitation_to_female_sp_already_apply_bid_on_fulltime_project_error_message'] = 'Fulltime Project[Female]:{user_first_name_last_name} already applied bid to your project.';

$config['po_sent_project_invitation_to_company_sp_already_apply_bid_on_fulltime_project_error_message'] = 'Fulltime Project:{user_company_name} already applied bid to your project.';

################################################################################################################################################################################
//Gender based config when po award project to sp(fixed/hourly)
$config['po_sent_project_invitation_to_male_sp_already_awarded_same_sp_on_project_error_message'] = 'Project[Male]:You already awarded this project to {user_first_name_last_name}.';

$config['po_sent_project_invitation_to_female_sp_already_awarded_same_sp_on_project_error_message'] = 'Project[Female]:You already awarded this project to {user_first_name_last_name}.';

$config['po_sent_project_invitation_to_company_sp_already_awarded_same_sp_on_project_error_message'] = 'Project[company]:You already awarded this project to {user_company_name}.';

//Gender based config when po award on fulltime project 
$config['po_sent_project_invitation_to_male_sp_already_awarded_same_sp_on_fulltime_project_error_message'] = 'Fulltime Project[Male]:You already awarded this ft project to {user_first_name_last_name}.';

$config['po_sent_project_invitation_to_female_sp_already_awarded_same_sp_on_fulltime_project_error_message'] = 'Fulltime Project[Female]:You already awarded this ft project to {user_first_name_last_name}.';

$config['po_sent_project_invitation_to_company_sp_already_awarded_same_sp_on_fulltime_project_error_message'] = 'Fulltime Project[Company]:You already awarded this ft project to {user_company_name}.';


################################################################################################################################################################################
//Gender based config when po assign job to sp(fixed/hourly) (project status->in progress/incomplete)
$config['po_sent_project_invitation_to_male_sp_in_progress_same_sp_on_project_error_message'] = 'Project[Male]:{user_first_name_last_name} started to work on this project already.';

$config['po_sent_project_invitation_to_female_sp_in_progress_same_sp_on_project_error_message'] = 'Project[Female]:{user_first_name_last_name} started to work on this project already.';

$config['po_sent_project_invitation_to_company_sp_in_progress_same_sp_on_project_error_message'] = 'Project[company]:{user_company_name} started to work on this project already.';

//Gender based config when po hired sp on fulltime project 
$config['po_sent_project_invitation_to_male_sp_hired_same_sp_on_fulltime_project_error_message'] = 'Fulltime Project[Male]:You already hired {user_first_name_last_name} on this ft project.';

$config['po_sent_project_invitation_to_female_sp_hired_same_sp_on_fulltime_project_error_message'] = 'Fulltime Project[Female]:You already hired {user_first_name_last_name} on this ft project.';

$config['po_sent_project_invitation_to_company_sp_hired_same_sp_on_fulltime_project_error_message'] = 'Fulltime Project[company]:You already hired {user_company_name} on this ft project.';

################################################################################################################################################################################
//Gender based config when sp already completed project(fixed/hourly)
$config['po_sent_project_invitation_to_male_sp_completed_same_sp_on_project_error_message'] = 'Project[Male]:Project has been already completed by {user_first_name_last_name}.';

$config['po_sent_project_invitation_to_female_sp_completed_same_sp_on_project_error_message'] = 'Project[Female]:Project has been already completed by {user_first_name_last_name}.';

$config['po_sent_project_invitation_to_company_sp_completed_same_sp_on_project_error_message'] = 'Project[company]:Project has been already completed by {user_company_name}.';

##################################################################################################################
$config['po_sent_project_invitation_to_same_user_no_projects_available_left_error_message'] = 'You have not projects available left to send invitation to {user_first_name_last_name_or_company_name}';

$config['po_sent_project_invitation_open_projects_not_available_error_message'] = 'You have no open projects to send invitation';

$config['po_sent_project_invitation_allowed_sent_invites_per_month_error_message'] = 'You have no available slot left to send invitations for this month';

$config['po_sent_project_invitation_allowed_sent_invites_per_project_error_message'] = 'You have reached the limit of allowed sent invitations on this project';

$config['po_sent_project_invitation_allowed_sent_invites_per_fulltime_project_error_message'] = 'Fulltime:You have reached the limit of allowed sent invitations on this ft project';

// This config is using when not options available in drop down example per month limit 10 and per project limit 3 now invition alrady sent to three users if trying to sending inviation to fourth user 
$config['po_sent_project_invitation_no_listing_available_to_send_invitation_to'] = 'you have no listing available to send invitations to.';


// Ackowldegement message when PO send project invitation successfully
//By Php
$config['project_invitation_realtime_notification_message_sent_to_po'] = 'invitation to project was successfully sent to <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>';


$config['fulltime_project_invitation_realtime_notification_message_sent_to_po'] = 'invitation to ft project was successfully sent to <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> fulltime Project';


//By Node
$config['project_invitation_realtime_notification_message_sent_to_sp'] = 'you received invitation to bid on project "<a href="{project_url_link}" target="_blank">{project_title}</a>" from <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>';

$config['fulltime_project_invitation_realtime_notification_message_sent_to_sp'] = 'you received invitation to apply on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>" from <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>';

######################################################################################################################

// Activity log message
//For SP
$config['project_invitation_message_sent_to_sp_user_activity_log_displayed_message'] = 'you received invitation to apply on project "<a href="{project_url_link}" target="_blank">{project_title}</a>" from <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>.';

$config['fulltime_project_invitation_message_sent_to_sp_user_activity_log_displayed_message'] = 'you received invitation to apply on fulltime job "<a href="{project_url_link}" target="_blank">{project_title}</a>" from <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>.';



// For PO
$config['project_invitation_message_sent_to_po_user_activity_log_displayed_message'] = 'you sent invitation to apply on your project "<a href="{project_url_link}" target="_blank">{project_title}</a>" to <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>.';

$config['fulltime_project_invitation_message_sent_to_po_user_activity_log_displayed_message'] = 'you sent invitation to apply on your fulltime job "<a href="{project_url_link}" target="_blank">{project_title}</a>" to <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>.';

?>