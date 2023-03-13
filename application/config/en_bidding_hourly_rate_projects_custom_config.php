<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['retract_bid_confirmation_hourly_project_modal_title'] = 'Retract Bid(HP)';

$config['award_bid_confirmation_hourly_project_modal_title_po_view'] = 'Confirmation Required - Award Bid(HP - PO)';


$config['project_details_page_hourly_project_bid_form_bid'] = 'Hourly Rate';

//$config['project_details_page_hourly_project_bid_form_hours'] = 'Hours';
$config['project_details_page_hourly_project_bid_form_number_of_hours'] = 'number of hours';



$config['project_details_page_hourly_project_bid_form_tooltip_message_sp_hourly_rate_bidded_amount'] = 'enter the hourly rate';
$config['project_details_page_hourly_project_bid_form_tooltip_message_sp_bidded_number_of_hours'] = 'enter the number of hours';


//project hourly rate
$config['award_bid_confirmation_hourly_project_modal_body_po_view'] = 'hOURLY pROJECT - By continuing you agree to award your "{hourly_rate_based_budget_project_title}" to {user_first_name_last_name_or_company_name}.';

$config['award_bid_confirmation_hourly_project_modal_body_po_view_confidential'] = 'HOURLY - By continuing you agree to award your "{hourly_rate_based_budget_project_title}" to {user_first_name_last_name_or_company_name}</br></br>***ATTENTION*** {user_first_name_last_name_or_company_name} indicated the requested project budget as "Confidential"';

$config['award_bid_confirmation_hourly_project_modal_body_po_view_to_be_agreed'] = 'HOURLY - By continuing you agree to award your "{hourly_rate_based_budget_project_title}" to {user_first_name_last_name_or_company_name}</br></br>***ATTENTION*** {user_first_name_last_name_or_company_name} indicated the requested project budget as "To Be Agreed"';

$config['project_details_page_hourly_rate_bid_amount_validation_hourly_project_bid_form_message'] = 'hourly rate is required';

$config['project_details_page_number_hours_validation_hourly_project_bid_form_message'] = 'number of hours is required';

//used for both PO and SP - to be reviewed and confirmed
$config['project_details_page_minimum_required_bid_amount_validation_hourly_project_bid_form_message'] = 'you cant place a hourly rate less than {project_minimum_hourly_bid_amount} '.CURRENCY.'/hour.';


$config['project_details_page_bidder_listing_bidded_hourly_rate_txt'] = '<span class="bidded_hourly_rate_txt_color">Bidded hourly rate:</span>';

$config['project_details_page_bidder_listing_details_estimated_hours'] = '<span class="estimated_hours_color">Estimated hours:</span>';

######## These config variables using on those files where in progress bidding listing is showing
$config['in_progress_bidding_listing_hourly_rate_based_project_start_date'] = '<span class="in_progress_project_start_date_color">InProgress - Hourly Project Start Date:</span>';

$config['completed_bidding_listing_hourly_rate_based_project_start_date'] = '<span class="completed_project_start_date_color">Completed - Hourly Project Start Date:</span>';

$config['hourly_rate_based_project_project_details_page_bidder_listing_initial_bid_value_txt_po_view'] = '<span class="initial_bid_value_txt_po_view_color">Inital bid value:</span>';

$config['hourly_rate_based_project_project_details_page_bidder_listing_your_initial_bid_value_txt_sp_view'] = '<span class="initial_bid_value_txt_sp_view_color">sp view - Your inital bid value:</span>';

$config['hourly_rate_based_project_award_bid_modal_body_po_view_tooltip_message_sp_bidded_hourly_rate'] = 'this is the hourly rate {user_first_name_last_name_or_company_name} applied on your project';

$config['hourly_rate_based_project_award_bid_modal_body_po_view_tooltip_message_estimated_hours'] = 'this is the estimated hours {user_first_name_last_name_or_company_name} considers that is needed to complete your project';

$config['hourly_rate_based_project_award_bid_modal_body_po_view_tooltip_message_project_value'] = 'this is the value when project will be considered as being successfuly completed via the portal';

$config['hourly_rate_based_project_award_bid_modal_body_po_view_project_value_txt'] = 'Project Value:';

############ activity log message when cancelled the awarded project by admin and SP recived notification for his awarded bid

$config['awarded_hourly_project_cancelled_by_admin_message_sent_to_awarded_sp_user_activity_log_displayed_message'] = 'SP -Hourly rate project "<a href="{project_url_link}" target="_blank">{project_title}</a>" has been cancelled by admin. Your award on this project is cancelled.';

$config['awarded_hourly_project_cancelled_by_admin_message_sent_to_po_user_activity_log_displayed_message'] = 'PO - Your hourly rate project "<a href="{project_url_link}" target="_blank">{project_title}</a>" was cancelled by admin. All the awards you had on this project are cancelled.';

############ activity log message when service provider post the bid
$config['hourly_project_message_sent_to_po_when_new_bid_received_from_sp_male_user_activity_log_displayed_message'] = 'Male:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> have posted a bid on your hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['hourly_project_message_sent_to_po_when_new_bid_received_from_sp_female_user_activity_log_displayed_message'] = 'Female:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> have posted a bid on your hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['hourly_project_message_sent_to_po_when_new_bid_received_from_sp_company_app_male_user_activity_log_displayed_message'] = 'App Male:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> have posted a bid on your hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['hourly_project_message_sent_to_po_when_new_bid_received_from_sp_company_app_female_user_activity_log_displayed_message'] = 'App Female:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> have posted a bid on your hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['hourly_project_message_sent_to_po_when_new_bid_received_from_sp_company_user_activity_log_displayed_message'] = 'Company:<a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> have posted a bid on your hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['hourly_project_message_sent_to_sp_when_new_bid_placed_user_activity_log_displayed_message'] = 'you have successfully placed bid on hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

############ activity log message when service provider edit the bid
$config['hourly_project_message_sent_to_po_when_sp_male_edit_bid_user_activity_log_displayed_message'] = 'Male : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> has updated his bid on your hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['hourly_project_message_sent_to_po_when_sp_female_edit_bid_user_activity_log_displayed_message'] = 'Female : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> has updated her bid on your hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['hourly_project_message_sent_to_po_when_sp_company_app_male_edit_bid_user_activity_log_displayed_message'] = 'App Male : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> has updated his bid on your hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['hourly_project_message_sent_to_po_when_sp_company_app_female_edit_bid_user_activity_log_displayed_message'] = 'App Female : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> has updated her bid on your hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['hourly_project_message_sent_to_po_when_sp_company_edit_bid_user_activity_log_displayed_message'] = 'Company : <a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> has updated its bid on your hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['hourly_project_message_sent_to_sp_when_edit_bid_user_activity_log_displayed_message'] = 'you have successfully updated your bid on hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

############ activity log message when service provider retract the bid
$config['hourly_project_message_sent_to_po_when_sp_male_retract_bid_user_activity_log_displayed_message'] = 'Male : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> retracted his bid from your hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['hourly_project_message_sent_to_po_when_sp_female_retract_bid_user_activity_log_displayed_message'] = 'Female : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> retracted her bid from your hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['hourly_project_message_sent_to_po_when_sp_company_app_male_retract_bid_user_activity_log_displayed_message'] = 'App Male : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> retracted his bid from your hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['hourly_project_message_sent_to_po_when_sp_company_app_female_retract_bid_user_activity_log_displayed_message'] = 'App Female : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> retracted her bid from your hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['hourly_project_message_sent_to_po_when_sp_company_retract_bid_user_activity_log_displayed_message'] = 'Company : <a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> retract its bid from your hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['hourly_project_message_sent_to_sp_when_retract_bid_user_activity_log_displayed_message'] = 'you retracted your bid from hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

############ activity log message when project owner award the bid
$config['hourly_project_message_sent_to_po_when_award_bid_user_activity_log_displayed_message'] = 'you successfully awarded your hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>" to <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>.';

$config['hourly_project_message_sent_to_sp_when_is_awarded_bid_by_po_male_user_activity_log_displayed_message'] = 'Male : <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> awarded to you his hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>". Deadline for accepting the award is {award_expiration_date}.';

$config['hourly_project_message_sent_to_sp_when_is_awarded_bid_by_po_female_user_activity_log_displayed_message'] = 'Female : <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> awarded to you her hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>". Deadline for accepting the award is {award_expiration_date}.';


$config['hourly_project_message_sent_to_sp_when_is_awarded_bid_by_po_company_app_male_user_activity_log_displayed_message'] = 'App Male : <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> awarded to you his hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>". Deadline for accepting the award is {award_expiration_date}.';

$config['hourly_project_message_sent_to_sp_when_is_awarded_bid_by_po_company_app_female_user_activity_log_displayed_message'] = 'App Female : <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> awarded to you her hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>". Deadline for accepting the award is {award_expiration_date}.';


$config['hourly_project_message_sent_to_sp_when_is_awarded_bid_by_po_company_user_activity_log_displayed_message'] = 'Company : <a href="{po_profile_url_link}" target="_blank">{user_company_name}</a> awarded to you its hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>". Deadline for accepting the award is {award_expiration_date}.';

############ activity log message when service provider decline the awarded bid
$config['hourly_project_message_sent_to_sp_when_declined_awarded_bid_user_activity_log_displayed_message'] = 'you declined the award from <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> on hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['hourly_project_message_sent_to_po_when_sp_male_declined_awarded_bid_user_activity_log_displayed_message'] = 'Male:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> declined your award on hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['hourly_project_message_sent_to_po_when_sp_female_declined_awarded_bid_user_activity_log_displayed_message'] = 'Female:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> declined your award on hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['hourly_project_message_sent_to_po_when_sp_company_app_male_declined_awarded_bid_user_activity_log_displayed_message'] = 'App Male: <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> declined your award on hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['hourly_project_message_sent_to_po_when_sp_company_app_female_declined_awarded_bid_user_activity_log_displayed_message'] = 'App Female: <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> declined your award on hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['hourly_project_message_sent_to_po_when_sp_company_declined_awarded_bid_user_activity_log_displayed_message'] = 'Company:<a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> declined your award on hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

############ activity log message when service provider accept the awarded bid
$config['hourly_project_message_sent_to_po_when_awarded_sp_male_accepted_awarded_bid_user_activity_log_displayed_message'] = 'Male:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> accepted your award on your hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['hourly_project_message_sent_to_po_when_awarded_sp_female_accepted_awarded_bid_user_activity_log_displayed_message'] = 'Female:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> accepted your award on your hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['hourly_project_message_sent_to_po_when_awarded_sp_company_app_male_accepted_awarded_bid_user_activity_log_displayed_message'] = 'App Male: <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> accepted your award on your hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['hourly_project_message_sent_to_po_when_awarded_sp_company_app_female_accepted_awarded_bid_user_activity_log_displayed_message'] = 'App Female: <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> accepted your award on your hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['hourly_project_message_sent_to_po_when_awarded_sp_company_accepted_awarded_bid_user_activity_log_displayed_message'] = 'Company:<a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> accepted your award on your hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['hourly_project_message_sent_to_sp_when_accepted_awarded_bid_user_activity_log_displayed_message'] = 'you have successfully accepted the award on hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

############ activity log message when awarded bid by project owner expiration time is passed
$config['hourly_project_message_sent_to_po_when_awarded_bid_sp_male_expiration_time_passed_user_activity_log_displayed_message'] = 'Male:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> did not accept in time your award on hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['hourly_project_message_sent_to_po_when_awarded_bid_sp_female_expiration_time_passed_user_activity_log_displayed_message'] = 'Female:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> did not accept in time your award on hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['hourly_project_message_sent_to_po_when_awarded_bid_sp_company_app_male_expiration_time_passed_user_activity_log_displayed_message'] = 'App Male: <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> did not accept in time your award on hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['hourly_project_message_sent_to_po_when_awarded_bid_sp_company_app_female_expiration_time_passed_user_activity_log_displayed_message'] = 'App Female: <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> did not accept in time your award on hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['hourly_project_message_sent_to_po_when_awarded_bid_sp_company_expiration_time_passed_user_activity_log_displayed_message'] = 'Company:<a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> did not accept in time your award on hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['hourly_project_message_sent_to_sp_when_awarded_bid_expiration_time_passed_user_activity_log_displayed_message'] = 'you did not accept in time the award from <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> on hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

// activity log message when PO sent project mark complete request to sp.
$config['hourly_project_message_sent_to_sp_when_po_male_created_mark_project_complete_request_user_activity_log_displayed_message'] = 'Male : <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> sent request to you to mark his hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>" as complete.';

$config['hourly_project_message_sent_to_sp_when_po_female_created_mark_project_complete_request_user_activity_log_displayed_message'] = 'Female : <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> sent request to you to mark her hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>" as complete.';

$config['hourly_project_message_sent_to_sp_when_po_company_app_male_created_mark_project_complete_request_user_activity_log_displayed_message'] = 'App Male : <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> sent you request to mark his hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>" as complete.';

$config['hourly_project_message_sent_to_sp_when_po_company_app_female_created_mark_project_complete_request_user_activity_log_displayed_message'] = 'App Female : <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> sent you request to mark her hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>" as complete.';

$config['hourly_project_message_sent_to_sp_when_po_company_created_mark_project_complete_request_user_activity_log_displayed_message'] = 'Company : <a href="{po_profile_url_link}" target="_blank">{user_company_name}</a> sent you request to mark its hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>" as complete.';

$config['hourly_project_message_sent_to_po_when_po_created_mark_project_complete_request_user_activity_log_displayed_message'] = 'you have successfully sent request to mark hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>" as complete to <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>.';

//activity log message when SP declines/reject to mark project as complete
$config['hourly_project_message_sent_to_sp_when_sp_declined_mark_project_complete_request_user_activity_log_displayed_message'] = 'you have declined to mark hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>" as complete. Request sent by <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>.';

$config['hourly_project_message_sent_to_po_when_sp_male_declined_mark_project_complete_request_user_activity_log_displayed_message'] = 'Male : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> declined your request to mark hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>" as complete.';

$config['hourly_project_message_sent_to_po_when_sp_female_declined_mark_project_complete_request_user_activity_log_displayed_message'] = 'Female : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> declined your request to mark hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>" as complete.';

$config['hourly_project_message_sent_to_po_when_sp_company_app_male_declined_mark_project_complete_request_user_activity_log_displayed_message'] = 'App Male : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> declined your request to mark hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>" as complete.';

$config['hourly_project_message_sent_to_po_when_sp_company_app_female_declined_mark_project_complete_request_user_activity_log_displayed_message'] = 'App Female : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> declined your request to mark hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>" as complete.';

$config['hourly_project_message_sent_to_po_when_sp_company_declined_mark_project_complete_request_user_activity_log_displayed_message'] = 'Company : <a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> declined your request to mark hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>" as complete.';


//activity log message when SP accepted to mark project as complete
$config['hourly_project_message_sent_to_sp_when_sp_accepted_mark_project_complete_request_user_activity_log_displayed_message'] = 'you have accepted to mark hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>" as complete. Request sent by <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>.';

$config['hourly_project_message_sent_to_po_when_sp_male_accepted_mark_project_complete_request_user_activity_log_displayed_message'] = 'Male : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> accepted your request to mark hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>" as complete.';

$config['hourly_project_message_sent_to_po_when_sp_female_accepted_mark_project_complete_request_user_activity_log_displayed_message'] = 'Female : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> accepted your request to mark hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>" as complete.';

$config['hourly_project_message_sent_to_po_when_sp_company_app_male_accepted_mark_project_complete_request_user_activity_log_displayed_message'] = 'App Male : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> accepted your request to mark hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>" as complete.';

$config['hourly_project_message_sent_to_po_when_sp_company_app_female_accepted_mark_project_complete_request_user_activity_log_displayed_message'] = 'App Female : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> accepted your request to mark hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>" as complete.';

$config['hourly_project_message_sent_to_po_when_sp_company_accepted_mark_project_complete_request_user_activity_log_displayed_message'] = 'Company : <a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> accepted your request to mark hourly project "<a href="{project_url_link}" target="_blank">{project_title}</a>" as complete.';

?>