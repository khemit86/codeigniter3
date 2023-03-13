<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['project_details_page_fixed_budget_project_bid_form_bid'] = 'Bid Amount';

$config['project_details_page_fixed_budget_project_bid_form_days'] = 'Number of Days';

$config['fixed_budget_project_award_bid_modal_body_po_view_bid_amount_txt'] = '<span>Bid Value:</span>';

$config['fixed_budget_project_award_bid_modal_body_po_view_project_value_txt'] = 'Project Value:';

$config['fixed_budget_project_award_bid_modal_body_po_view_delivery_in_txt'] = '<span class="delivery_in_txt_color">To be delivered in:</span>';

$config['fixed_budget_project_award_bid_modal_body_po_view_confidential_txt'] = 'Confidential';

$config['fixed_budget_project_award_bid_modal_body_po_view_to_be_agreed_txt'] = 'To be agreed';


$config['in_progress_bidding_listing_fixed_expected_completion_date'] = '<span>expected completion date:</span>';

$config['fixed_budget_project_project_details_page_bidder_listing_initial_bid_value_txt_po_view'] = '<span class="initial_bid_value_txt_po_view_color">Inital bid value:</span>';

$config['fixed_budget_project_project_details_page_bidder_listing_your_initial_bid_value_txt_sp_view'] = '<span class="initial_bid_value_txt_sp_view_color">Your inital bid value:</span>';

// Tooltip message of fixed project for place bid form
$config['project_details_page_fixed_budget_project_bid_form_tooltip_message_bid_amount'] = 'enter the bid amount';

$config['project_details_page_fixed_budget_project_bid_form_tooltip_message_delivery_in'] = 'enter delivery date for fixed';

############### validation config and message fixed type project regarding place bid form ######
$config['project_details_page_bid_amount_validation_fixed_budget_project_bid_form_message'] = 'Bid amount is required.';

$config['project_details_page_number_days_validation_fixed_budget_project_bid_form_message'] = 'Number of days is required.';

$config['project_details_page_minimum_required_bid_amount_validation_fixed_budget_project_bid_form_message'] = 'You cant place a bid less than {project_minimum_fixed_bid_amount}.';


// variable for confirmation confirmation popup for award bid for project owner
$config['award_bid_confirmation_fixed_budget_project_modal_body_po_view'] = 'By continuing you agree to award "{fixed_budget_project_title}" to {user_first_name_last_name_or_company_name}.';

$config['award_bid_confirmation_fixed_budget_project_modal_body_po_view_confidential'] = 'By continuing you agree to award "{fixed_budget_project_title}" to {user_first_name_last_name_or_company_name}.';

$config['award_bid_confirmation_fixed_budget_project_modal_body_po_view_to_be_agreed'] = 'By continuing you agree to award your "{fixed_budget_project_title}" to {user_first_name_last_name_or_company_name}.';


// This config are used to show the tootltip message/Text when PO award the project to sp by confirmation modal 
// tooltip message for bid amount
$config['fixed_budget_project_award_bid_modal_body_po_view_tooltip_message_bid_amount'] = 'This is the bid value {user_first_name_last_name_or_company_name} applied on your project';

// tooltip message for project delivered time
$config['fixed_budget_project_award_bid_modal_body_po_view_tooltip_message_delivery_in'] = 'This is the estimated time {user_first_name_last_name_or_company_name} considers that is needed to complete your project';

//tooltip message for project value
$config['fixed_budget_project_award_bid_modal_body_po_view_tooltip_message_project_value'] = 'This is the value when project will be considered as being successfuly completed via the portal.';

############ activity log message when SP post the bid ######
// For PO
$config['fixed_budget_project_message_sent_to_po_when_new_bid_received_from_sp_male_user_activity_log_displayed_message'] = 'Male:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> have posted a bid on your fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>."';

$config['fixed_budget_project_message_sent_to_po_when_new_bid_received_from_sp_female_user_activity_log_displayed_message'] = 'Female:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> have posted a bid on your fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_new_bid_received_from_sp_company_app_male_user_activity_log_displayed_message'] = 'App Male:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> have posted a bid on your fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_new_bid_received_from_sp_company_app_female_user_activity_log_displayed_message'] = 'AppFemale:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> have posted a bid on your fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fixed_budget_project_message_sent_to_po_when_new_bid_received_from_sp_company_user_activity_log_displayed_message'] = 'Company:<a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> have posted a bid on your fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

// For SP
$config['fixed_budget_project_message_sent_to_sp_when_new_bid_placed_user_activity_log_displayed_message'] = 'you have successfully placed bid on fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

############ activity log message when sp update the bid #####
//For PO
$config['fixed_budget_project_message_sent_to_po_when_sp_male_edit_bid_user_activity_log_displayed_message'] = 'Male:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> have updated his bid on your fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_sp_female_edit_bid_user_activity_log_displayed_message'] = 'Female:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> have updated her bid on your fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_sp_company_app_male_edit_bid_user_activity_log_displayed_message'] = 'Male App:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> have updated his bid on your fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_sp_company_app_female_edit_bid_user_activity_log_displayed_message'] = 'Female App:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> have updated her bid on your fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_project_message_sent_to_po_when_sp_company_edit_bid_user_activity_log_displayed_message'] = 'Company:<a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> have updated its bid on your fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

// For SP
$config['fixed_budget_project_message_sent_to_sp_when_edit_bid_user_activity_log_displayed_message'] = 'you have successfully updated your bid on fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

############ activity log message when sp retract the bid ######
// For Po
$config['fixed_budget_project_message_sent_to_po_when_sp_male_retract_bid_user_activity_log_displayed_message'] = 'Male:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> retracted his bid on your fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_sp_female_retract_bid_user_activity_log_displayed_message'] = 'Female:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> have retracted her bid on your fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_sp_company_app_male_retract_bid_user_activity_log_displayed_message'] = 'App Male: <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> have retracted his bid on your fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_sp_company_app_female_retract_bid_user_activity_log_displayed_message'] = 'App Female: <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> have retracted her bid on your fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fixed_budget_project_message_sent_to_po_when_sp_company_retract_bid_user_activity_log_displayed_message'] = 'Company:<a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> have retracted its bid on your fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

//For Sp
$config['fixed_budget_project_message_sent_to_sp_when_retract_bid_user_activity_log_displayed_message'] = 'you have successfully retracted your bid from fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

############ activity log message when PO award the bid #########
// For PO
$config['fixed_budget_project_message_sent_to_po_when_award_bid_user_activity_log_displayed_message'] = 'you have successfully awarded to <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> your fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

//For SP
$config['fixed_budget_project_message_sent_to_sp_when_is_awarded_bid_by_po_male_user_activity_log_displayed_message'] = 'male:<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> have awarded to you his fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>". deadline for accepting this award is {award_expiration_date}.';

$config['fixed_budget_project_message_sent_to_sp_when_is_awarded_bid_by_po_female_user_activity_log_displayed_message'] = 'Female:<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> have awarded to you her fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>". deadline for accepting this award is {award_expiration_date}.';

$config['fixed_budget_project_message_sent_to_sp_when_is_awarded_bid_by_po_company_app_male_user_activity_log_displayed_message'] = 'App Male: <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> have awarded to you his fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>". deadline for accepting this award is {award_expiration_date}.';

$config['fixed_budget_project_message_sent_to_sp_when_is_awarded_bid_by_po_company_app_female_user_activity_log_displayed_message'] = 'App Female: <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> have awarded to you her fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>". deadline for accepting this award is {award_expiration_date}.';


$config['fixed_budget_project_message_sent_to_sp_when_is_awarded_bid_by_po_company_user_activity_log_displayed_message'] = 'Company:<a href="{po_profile_url_link}" target="_blank">{user_company_name}</a> have awarded to you its fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>". deadline for accepting this award i) {award_expiration_date}.';

########### activity log message when awarded bid by po expiration time is passed
// For PO

$config['fixed_budget_project_message_sent_to_po_when_awarded_bid_sp_male_expiration_time_passed_user_activity_log_displayed_message'] = 'Male:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> did not accept in time your award on your fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_awarded_bid_sp_female_expiration_time_passed_user_activity_log_displayed_message'] = 'Female:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> did not accept in time your award on your fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_awarded_bid_sp_company_app_male_expiration_time_passed_user_activity_log_displayed_message'] = 'App Male: <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> did not accept in time your award on your fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_awarded_bid_sp_company_app_female_expiration_time_passed_user_activity_log_displayed_message'] = 'App Female: <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> did not accept in time your award on your fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fixed_budget_project_message_sent_to_po_when_awarded_bid_sp_company_expiration_time_passed_user_activity_log_displayed_message'] = 'Company:<a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> did not accept in time your award on your fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

// For SP
$config['fixed_budget_project_message_sent_to_sp_when_awarded_bid_expiration_time_passed_user_activity_log_displayed_message'] = 'you did not accept in time award on fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>" from <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>.';


### activity log message when cancelled the awarded project by admin for his awarded bid #####
$config['awarded_fixed_budget_project_cancelled_by_admin_message_sent_to_awarded_sp_user_activity_log_displayed_message'] = 'SP View:"<a href="{project_url_link}" target="_blank">{project_title}</a>" was cancelled by admin. Your award on this project is cancelled.';

$config['awarded_fixed_budget_project_cancelled_by_admin_message_sent_to_po_user_activity_log_displayed_message'] = 'Po View "<a href="{project_url_link}" target="_blank">{project_title}</a>" was cancelled by admin. All awards on this project are cancelled';

############ activity log message when SP decline the awarded bid #####
// For SP
$config['fixed_budget_project_message_sent_to_sp_when_declined_awarded_bid_user_activity_log_displayed_message'] = 'you declined the award from <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> on fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

//For PO
$config['fixed_budget_project_message_sent_to_po_when_sp_male_declined_awarded_bid_user_activity_log_displayed_message'] = 'Male:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> declined your award on yout fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_sp_female_declined_awarded_bid_user_activity_log_displayed_message'] = 'Female:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> declined your award on your fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fixed_budget_project_message_sent_to_po_when_sp_company_app_male_declined_awarded_bid_user_activity_log_displayed_message'] = 'App Male: <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> declined your award on your fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_sp_company_app_female_declined_awarded_bid_user_activity_log_displayed_message'] = 'App Female: <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> declined your award on your fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fixed_budget_project_message_sent_to_po_when_sp_company_declined_awarded_bid_user_activity_log_displayed_message'] = 'Company:<a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> declined your award on your fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

############ activity log message when sp accept the awarded bid #########
//For Po
$config['fixed_budget_project_message_sent_to_po_when_awarded_sp_male_accepted_awarded_bid_user_activity_log_displayed_message'] = 'Male:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> accepted your award on your fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_awarded_sp_female_accepted_awarded_bid_user_activity_log_displayed_message'] = 'Female:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> accepted your award on your fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fixed_budget_project_message_sent_to_po_when_awarded_sp_company_app_male_accepted_awarded_bid_user_activity_log_displayed_message'] = 'App Male: <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> accepted your award on your fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_awarded_sp_company_app_female_accepted_awarded_bid_user_activity_log_displayed_message'] = 'App Female: <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> accepted your award on your fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_awarded_sp_company_accepted_awarded_bid_user_activity_log_displayed_message'] = 'Company:<a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> accepted your award on your fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

// For SP
$config['fixed_budget_project_message_sent_to_sp_when_accepted_awarded_bid_user_activity_log_displayed_message'] = 'you have successfully accepted the award on fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>". now is time to start the work.';

#################### #######################################
// activity log message when PO sent project mark complete request to sp.
// For Sp
$config['fixed_budget_project_message_sent_to_sp_when_po_male_created_mark_project_complete_request_user_activity_log_displayed_message'] = 'Male : <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> requested you to accept mark fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>" as complete.';

$config['fixed_budget_project_message_sent_to_sp_when_po_female_created_mark_project_complete_request_user_activity_log_displayed_message'] = 'Female : <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> requested you to accept mark fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>" as complete.';

$config['fixed_budget_project_message_sent_to_sp_when_po_company_app_male_created_mark_project_complete_request_user_activity_log_displayed_message'] = 'App Male : <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> requested you to accept mark fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>" as complete.';

$config['fixed_budget_project_message_sent_to_sp_when_po_company_app_female_created_mark_project_complete_request_user_activity_log_displayed_message'] = 'App Female : <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> requested you to accept mark fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>" as complete.';

$config['fixed_budget_project_message_sent_to_sp_when_po_company_created_mark_project_complete_request_user_activity_log_displayed_message'] = 'Company : <a href="{po_profile_url_link}" target="_blank">{user_company_name}</a> requested you to accept mark fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>" as complete.';

// For Po
$config['fixed_budget_project_message_sent_to_po_when_po_created_mark_project_complete_request_user_activity_log_displayed_message'] = 'you have successfully sent mark project as complete request to <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>, on fixed project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

###########################################################################
// activity log message when sp decline mark complete request.
// For po
$config['fixed_budget_project_message_sent_to_po_when_sp_male_declined_mark_project_complete_request_user_activity_log_displayed_message'] = 'Male : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> declined your request to mark your fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>" as complete.';


$config['fixed_budget_project_message_sent_to_po_when_sp_female_declined_mark_project_complete_request_user_activity_log_displayed_message'] = 'Female : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> declined your request to mark your fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>" as complete.';


$config['fixed_budget_project_message_sent_to_po_when_sp_company_app_male_declined_mark_project_complete_request_user_activity_log_displayed_message'] = 'Male app: <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> declined your request to mark your fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>" as complete.';


$config['fixed_budget_project_message_sent_to_po_when_sp_company_app_female_declined_mark_project_complete_request_user_activity_log_displayed_message'] = 'Female app: <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> declined your request to mark your fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>" as complete.';


$config['fixed_budget_project_message_sent_to_po_when_sp_company_declined_mark_project_complete_request_user_activity_log_displayed_message'] = 'Company : <a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> declined your request yo mark your fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>" as complete.';

// For Sp
$config['fixed_budget_project_message_sent_to_sp_when_sp_declined_mark_project_complete_request_user_activity_log_displayed_message'] = 'you declined the request sent by <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> to mark fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>" as complete.';

#########################################################################################
// activity log message when Sp accept project mark complete request.
// For Po
$config['fixed_budget_project_message_sent_to_po_when_sp_male_accepted_mark_project_complete_request_user_activity_log_displayed_message'] = 'Male : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> accepted your request to mark you fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>" as complete.';

$config['fixed_budget_project_message_sent_to_po_when_sp_female_accepted_mark_project_complete_request_user_activity_log_displayed_message'] = 'Female : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> accepted your request to mark you fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>" as complete.';

$config['fixed_budget_project_message_sent_to_po_when_sp_company_app_male_accepted_mark_project_complete_request_user_activity_log_displayed_message'] = 'App Male : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> accepted your request to mark you fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>" as complete.';

$config['fixed_budget_project_message_sent_to_po_when_sp_company_app_female_accepted_mark_project_complete_request_user_activity_log_displayed_message'] = 'App Female : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> accepted your request to mark you fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>" as complete.';

$config['fixed_budget_project_message_sent_to_po_when_sp_company_accepted_mark_project_complete_request_user_activity_log_displayed_message'] = 'Company : <a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> accepted your request to mark you fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>" as complete.';

// For Sp
$config['fixed_budget_project_message_sent_to_sp_when_sp_accepted_mark_project_complete_request_user_activity_log_displayed_message'] = 'you have accepted the request sent by <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> to mark fixed budget project "<a href="{project_url_link}" target="_blank">{project_title}</a>" as complete.';

?>