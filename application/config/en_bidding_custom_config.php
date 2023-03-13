<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
	
##### Config variables for bid form on project detail page

$config['project_details_page_project_bid_form_delivery_in'] = 'Delivery In';

$config['project_details_page_bidder_listing_details_delivery_in'] = '<span class="delivery_in_color">Delivery In</span>';

$config['project_details_page_bid_form_description_section_heading'] = 'Describe your bid';

$config['project_details_page_project_bid_form_place_bid_btn_txt'] = 'Place Bid';

$config['project_details_page_project_active_bidder_listing_edit_bid_btn_txt'] = 'Edit Bid';

$config['project_details_page_project_active_bidder_listing_retract_bid_btn_txt'] = 'Retract Bid';

$config['project_details_page_project_bid_form_cancel_btn_txt'] = 'Cancel';

$config['project_details_page_project_bid_form_edit_bid_btn_txt'] = 'Update Bid';


$config['project_details_page_awarded_bidder_listing_details_awaiting_acceptance_expires_txt'] = 'Award Acceptance Deadline:';

// These config are using on project detail page for showing heading of project tab for sp view(like sp login on project detail page and job is awarded/completed/inprogress to hisself the he will see his heading) 
$config['project_details_page_awaiting_acceptance_project_tab_sp_view_txt'] = 'AwardedCZ';

$config['project_details_page_inprogress_project_tab_sp_view_txt'] = 'In Progress';

$config['project_details_page_active_dispute_tab_sp_view_txt'] = 'Active Dispute Sp view';

$config['fulltime_project_details_page_active_dispute_tab_employee_view_txt'] = 'Fulltime-Active Dispute Sp view';

$config['project_details_page_incomplete_project_tab_sp_view_txt'] = 'InComplete SP View';

$config['project_details_page_completed_project_tab_sp_view_txt'] = 'Completed';

$config['project_details_page_marked_completed_project_tab_sp_view_txt'] = 'Marked as complete';

// config are using for project completed listing(project completed outside the portal)
$config['project_details_page_project_marked_as_complete_snippet_txt_sp_view'] = 'Marked as complete snipet';

$config['displayed_text_project_details_page_bidder_listing_details_to_be_agreed_option_selected'] = 'To Be Agreed';

$config['displayed_text_project_details_page_bidder_listing_details_confidential_option_selected'] = 'Confidential';

##### Error messages when user trying to POST BID / APPLY NOW in invalid project
####EXPIRED PROJECTS STATUS
$config['project_details_page_sp_view_place_bid_expired_project'] = "Apply PROJ - Project has expired. You cannot place bid."; // this variable used for when user is on project details page and clicks on - apply now - button // also used for when user has the bid form open and clicks on place bid button too

$config['project_details_page_sp_view_place_bid_upload_bid_attachment_expired_project'] = "SP VIEW - Project has expired. You cannot upload the attachment anymore.";

$config['project_details_page_sp_view_place_bid_delete_bid_attachment_expired_project'] = "Apply PROJ - Project has expired. You cannot delete file.";

####CANCELLED
$config['project_details_page_sp_view_place_bid_cancelled_project'] = "Project has been cancelled. You cannot place bid."; // cancelled by PO or admin - used for when user is on project details page and clicks on - apply now - button // also used for when user has the bid form open and clicks on place bid button too

$config['project_details_page_sp_view_place_bid_upload_bid_attachment_cancelled_project'] = "Apply PROJ - Project has been cancelled. You cannot upload the attachment anymore.";

$config['project_details_page_sp_view_place_bid_open_bid_attachment_cancelled_project'] = "Apply PROJ - Project has been cancelled. You cannot open the attachment file.";

$config['project_details_page_sp_view_place_bid_delete_bid_attachment_cancelled_project'] = "Apply PROJ - Project has been cancelled. You cannot delete the attachment.";

####DELETED PROJECTS
$config['project_details_page_sp_view_place_bid_deleted_project'] = "project has been deleted. you cannot place bid anymore.";

$config['project_details_page_sp_view_place_bid_upload_bid_attachment_deleted_project'] = "Project has been deleted. You cannot upload the attachment anymore.";

$config['project_details_page_sp_view_place_bid_open_bid_attachment_deleted_project'] = "project has been deleted so you cannot open the file.";

$config['project_details_page_sp_view_place_bid_delete_attachment_deleted_project'] = "project has been deleted so you cannot delete the attachment";

#############################################################################

$config['project_details_page_sp_view_place_bid_already_posted_bid_same_project'] = "You have already placed a bid. So you cannot place bid again.";

$config['project_details_page_sp_view_place_bid_upload_bid_attachment_already_posted_bid_project'] = "You have already placed a bid. So you cannot upload attachment bid.";

###################ERROR MESSAGES REGARDING AWARDED PROJECT
$config['project_details_page_sp_view_place_bid_awarded_different_sp_project'] = "Project has been awarded. You cannot place bid.";

$config['project_details_page_sp_view_place_bid_upload_bid_attachment_awarded_different_sp_project'] = "Project has been awarded. You cannot upload the attachment anymore.";

//AWARDED TO SAME USER
$config['project_details_page_sp_view_place_bid_awarded_same_sp_project'] = "Project has been awarded to you. You cannot place bid.";

$config['project_details_page_sp_view_place_bid_upload_bid_attachment_awarded_same_sp_project'] = "Project has been awarded to you. You cannot upload the attachment anymore.";

$config['project_details_page_sp_view_same_sp_try_place_bid_on_completed_project'] = "You have already completed the project. So you cannot place the bid.";

$config['project_details_page_sp_view_different_sp_try_place_bid_on_completed_project'] = "Project has been completed. you cannot place the bid";

$config['project_details_page_sp_view_place_bid_in_progress_same_sp_project'] = "Project has been assigned to you. You already started to work on the project. You cannot place bid.";

$config['project_details_page_sp_view_place_bid_in_progress_different_sp_project'] = "Project has been moveD to in progress. You cannot place bid anymore.";

$config['project_details_page_sp_view_place_bid_upload_bid_attachment_in_progress_same_sp_project'] = "Project has been assigned to you. You already started to work on the project. You cannot upload the attachment anymore.";

$config['project_details_page_sp_view_same_sp_try_place_bid_upload_bid_attachment_on_completed_project'] = "Project has been completed by you. You cannot upload the attachment anymore.";

$config['project_details_page_sp_view_different_sp_try_place_bid_upload_bid_attachment_on_completed_project'] = "Project has been completed. You cannot upload the attachment anymore.";

$config['project_details_page_sp_view_place_bid_upload_bid_attachment_in_progress_different_sp_project'] = "Project has been moved to in progress status. You cannot upload the attachment anymore.";

$config['project_details_page_sp_view_place_bid_delete_bid_attachment_already_posted_same_sp_project'] = "you already placed a bid. You cannot delete file.";

$config['project_details_page_sp_view_place_bid_delete_bid_attachment_awarded_same_sp_project'] = "Project is awarded to you. You cannot delete the file.";

$config['project_details_page_sp_view_place_bid_delete_bid_attachment_in_progress_same_sp_project'] = "Project has been assigned to you. You already started to work on the project. You cannot delete the file.";

$config['project_details_page_sp_view_same_sp_try_place_bid_delete_bid_attachment_on_completed_project'] = "Project has been completed by you. You cannot delete the attachment anymore.";

####### error message when user trying to edit bid in invalid project
$config['project_details_page_sp_view_update_bid_cancelled_project'] = "Project has been cancelled. You cannot update bid.";

$config['project_details_page_sp_view_update_bid_deleted_project'] = "project has been deleted. You cannot update bid.";

$config['project_details_page_sp_view_update_bid_already_retracted_project'] = "Bid is already retracted so you cannot update bid.";

$config['project_details_page_sp_view_update_bid_awarded_same_sp_project'] = "Project has been awarded to you. So you cannot update bid.";

$config['project_details_page_sp_view_update_bid_in_progress_same_sp_project'] = "Project has been assigned to you. You already started to work on the project. So you cannot update bid.";

$config['project_details_page_sp_view_same_sp_try_update_bid_on_completed_project'] = "Project has been already completed by you. So you cannot update the bid";

$config['project_details_page_sp_view_update_bid_upload_bid_attachment_awarded_same_sp_project'] = "Project has been awarded to you. You cannot upload attachment.";

$config['project_details_page_sp_view_update_bid_upload_bid_attachment_in_progress_same_sp_project'] = "Project has been assigned to you. You already started to work on the project. You cannot upload attachment.";

$config['project_details_page_sp_view_same_sp_try_update_bid_upload_bid_attachment_on_completed_project'] = "Project has been completed by you. You cannot upload the attachment anymore.";

$config['project_details_page_sp_view_update_bid_already_retracted_upload_bid_attachment_same_sp_project'] = "Bid is already retracted. You cannot upload attachment.";

$config['project_details_page_sp_view_update_bid_upload_bid_attachment_deleted_project'] = "Project as been deleted. You cannot upload attachment.";

$config['project_details_page_sp_view_update_bid_upload_bid_attachment_cancelled_project'] = "Project has been cancelled. You cannot upload attachment.";

$config['project_details_page_sp_view_update_bid_open_bid_attachment_cancelled_project'] = "Project has been cancelled. You cannot open the file.";

$config['project_details_page_sp_view_update_bid_already_retracted_open_bid_attachment_same_sp_project'] = "You have already retracted bid. So you cannot open the file.";

$config['project_details_page_sp_view_update_bid_open_bid_attachment_deleted_project'] = "project has been deleted so you cannot open the file.";

$config['project_details_page_sp_view_update_bid_delete_attachment_deleted_project'] = "project has been deleted. you cannot delete the file.";

$config['project_details_page_sp_view_update_bid_delete_bid_attachment_cancelled_project'] = "Project has been cancelled. You cannot delete the file.";

$config['project_details_page_sp_view_update_bid_delete_bid_attachment_awarded_same_sp_project'] = "Project is awarded to you. You cannot delete the file.";

$config['project_details_page_sp_view_update_bid_delete_bid_attachment_in_progress_same_sp_project'] = "Project has been assigned to you. You already started to work on the project. You cannot delete the file.";

$config['retract_bid_confirmation_project_modal_body'] = 'Are you sure you want to retract bid?';

$config['retract_bid_confirmation_project_modal_retract_btn_txt'] = 'Retract Bid';

################# error message when trying to retract bid
// when some unauthrized user(PO/unauthrizedSP) trying to retract bid
$config['project_details_page_sp_view_retract_bid_cancelled_project'] = "Project has been cancelled. You cannot retract bid.";

$config['project_details_page_sp_view_retract_bid_awarded_same_sp_project'] = "Project has been awarded to you. You cannot retract bid.";

$config['project_details_page_sp_view_retract_bid_in_progress_same_sp_project'] = "Project has been assigned to you. You already started to work on the project. You cannot retract bid.";

$config['project_details_page_sp_view_same_sp_try_retract_bid_on_completed_project'] = "Project has been completed by you. You cannot retract bid";

$config['project_details_page_sp_view_retract_bid_deleted_project'] = "project has been deleted. you cannot retract bid";

$config['project_details_page_sp_view_retract_bid_already_retracted_project'] = "You have already retracted bid.";

################# error message when trying to award bid
// when some unauthrized user(PO/unauthrizedSP) trying to award bid
$config['project_details_page_po_view_award_bid_cancelled_project'] = "Project has been cancelled. You cannot award bid.";

$config['project_details_page_po_view_award_bid_deleted_project'] = "project has been deleted by admin. you cannot award bid.";

$config['project_details_page_po_view_award_bid_already_awarded_same_sp_project'] = 'Project has been already awarded to <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name_or_company_name}</a>.';

$config['project_details_page_po_view_award_bid_in_progress_same_sp_project'] = 'You have already hired <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name_or_company_name}</a> on this project. You cannot award again.';

$config['project_details_page_po_view_same_sp_try_award_bid_on_completed_project'] = '<a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name_or_company_name}</a> already completed this project.So you cannot award again.';

$config['project_details_page_po_view_award_bid_already_retracted_project'] = "Bid already retracted. So you cannot award the project.";

// variable for confirmation confirmation popup for decline bid for service provider
$config['decline_award_confirmation_project_modal_body_sp_view'] = 'Are you sure you want to Decline the AWARD?';

################# error message when trying to decline the awarded bid
// when some unauthorized user(PO/unauthrizedSP) trying to decline bid
$config['project_details_page_sp_view_decline_award_cancelled_project'] = "Project has been cancelled. You cannot decline AWARD.";

$config['project_details_page_sp_view_decline_award_deleted_project'] = "project has been deleted. you cannot decline AWARD.";

$config['project_details_page_sp_view_decline_award_in_progress_project'] = "You already accepted this award, therefore project status has changed to in progress. You cannot decline AWARD.";


$config['project_details_page_sp_view_same_sp_try_decline_award_on_completed_project'] = "You already completed this project, therefore project status has changed to completed. You cannot decline the AWARD.";

$config['project_details_page_sp_view_decline_award_award_already_declined_or_expired_project'] = "Status of your award has changed. You cannot decline the award.";

$config['project_details_page_sp_view_decline_award_already_retracted_project'] = "Bid already retracted. So you cannot decline the award.";

// variable for confirmation confirmation popup for accept bid for service provider
$config['accept_award_confirmation_project_modal_body_sp_view'] = 'Are you sure you want to Accept AWARD?';


################# error message when trying to accept the awarded bid
// when some unauthorized user(PO/unauthrizedSP) trying to accept bid
$config['project_details_page_sp_view_accept_award_cancelled_project'] = "Project has been cancelled. You cannot accept the award.";

$config['project_details_page_sp_view_accept_award_deleted_project'] = "project has been deleted. you cannot accept the award.";

$config['project_details_page_sp_view_accept_award_already_retracted_project'] = "Bid already retracted. you cannot accept the award.";

$config['project_details_page_sp_view_accept_award_award_already_declined_or_expired_project'] = "Status of your award has changed. You cannot accept the award.";


//updated on 18.09.2020 14.15
$config['project_details_page_sp_view_validation_project_accept_award_award_acceptance_deadline_already_expired_message'] = "you cannot accept the award because acceptance time has passed / is expired."; // when service provider trying to accept the awarded bid after awarded expiration time is past


$config['project_details_page_sp_view_accept_award_in_progress_project'] = "You already accepted this award, therefore project status has changed to in progress. You cannot accept award.";

$config['project_details_page_sp_view_same_sp_try_accept_award_on_completed_project'] = "You already completed this project, therefore project status has changed to completed. You cannot accept the award.";

//Config for upload file button text for place bid/update bid
$config['project_details_page_bid_form_upload_file_button_txt'] = 'upload file';

// Validation message for post/update bid form 
//For fixed/hourly project
$config['project_details_page_bid_description_validation_project_bid_form_message'] = 'bid description is required';


$config['project_details_page_biding_form_drop_down_options'] = array('to_be_agreed'=>'To Be Agreed','confidential'=>'Confidential');


$config['project_details_page_custom_bid_attachment_allowed_file_extensions'] = '"png","PNG","gif","GIF","jpeg","JPEG","jpg","JPG","pdf","application/PDF","txt","xls","xlsx","doc","docx"'; 
//$config['project_details_page_plugin_bid_attachment_attachment_allowed_file_extensions'] = 'image/*,.pdf,.xls, .xlsx, .doc, .docx, .txt'; //exclusion list :


$config['project_details_page_bid_attachment_allowed_files_validation_project_bid_form_message'] = "You can upload one single file at a time!";

$config['project_details_page_bid_attachment_invalid_file_extension_validation_project_bid_form_message'] = "The file type you are trying to upload is not allowed!";


$config['project_details_page_bid_posted_confirmation_project_bid_form_message'] = 'Bid posted successfully!';

$config['project_details_page_bid_updated_confirmation_project_bid_form_message'] = 'Bid updated successfully!';

$config['project_details_page_retract_bid_confirmation_project_bid_form_message'] = 'Bid retracted successfully!';

################ config for download the bid attachments from active bids/awarded bids/in progress bids
// config is using when attachment is not exists and project is in open for bidding status and po trying to open attachment from bidder list
$config['project_details_page_open_for_bidding_project_bidder_list_open_bid_attachment_not_exist_validation_message_po_view_project'] = "PO View: Attachment of bid not exists of project";

// config is using when attachment is not exists and project is in open for bidding status and sp trying to open attachment from bidder list
$config['project_details_page_open_for_bidding_project_bidder_list_open_bid_attachment_not_exist_validation_message_bidder_view_project'] = "Sp View: Attachment of bid not exists of project";

// this config is using when po/sp trying to open bid attachment from awarded bidder list project status is awarded and attachment not exists
$config['project_details_page_awarded_project_awarded_open_bid_attachment_not_exist_validation_message_bidder_list_sp_po_view_project'] = "Awarded:sp po view-Bid Attachment not exists of project";

// this config is using when po/sp trying to open bid attachment from in progress bidder list project status is in progress and attachment not exists
$config['project_details_page_in_progress_project_open_bid_attachment_not_exist_validation_message_bidder_list_awarded_sp_po_view_project'] = "Inprogress:sp po view Bid Attachment not exists of project";

// this config is using when po/sp trying to open bid attachment from incomplete bidder list project status is incomplete and attachment not exists
$config['project_details_page_incomplete_project_open_bid_attachment_not_exist_validation_message_bidder_list_awarded_sp_po_view_project'] = "Incomplete:Bid Attachment not exists of project";

// this config is using when po/sp trying to open bid attachment from completed bidder list project status is complete and attachment not exists
$config['project_details_page_completed_project_open_bid_attachment_not_exist_validation_message_bidder_list_awarded_sp_po_view_project'] = "completed:Bid Attachment not exists of project";

//this config is using as common for sp/po when they are trying to open bid atatchments from bidding list and in the background project hasbeen cancelled
$config['project_details_page_cancelled_project_bidder_list_open_bid_attachment_not_exist_validation_message_sp_po_view'] = "Project has been cancelled. So you cannot open the attachment";


// Config is used when po trying to open the bid attachment from bidder list and project status is expired
$config['project_details_page_expired_project_bidder_list_open_bid_attachment_not_exist_validation_message_po_view_project'] = "Expired:Po View:Bid Attachment not exists of project.";//this is used for PO view - there is no vistor view as visitors are not allowed to see attachments of other bids


// Config is used when sp trying to open the bid attachment from bidder list and project status is expired
$config['project_details_page_expired_project_bidder_list_open_bid_attachment_not_exist_validation_message_bidder_view_project'] = "Expired: sp view:Bid Attachment not exists of project.";

$config['project_details_page_deleted_project_bidder_list_open_bid_attachment_not_exist_validation_message_sp_po_view'] = "Project has been deleted. So you cannot open the attachment.";//config message is common for both po/sp view when they are trying to open attachments from open bidding list and in the background admin deleted the project

$config['project_details_page_bid_form_bid_attachment_not_exist_validation_message_bidder_view_project'] = "An error has occurred. Please remove the attachment and try to upload again!";

######## These config variables using on those files where in progress bidding listing is showing

$config['in_progress_bidding_listing_project_start_date'] = '<span class="in_progress_project_start_date_color">Project Start Date:</span>';

$config['completed_bidding_listing_project_start_date'] = '<span class="completed_project_start_date_color">Project Start Date:</span>';

$config['completed_bidding_listing_project_completion_date'] = '<span class="completed_bidding_completion_date_color">Project Completion Date:</span>';

$config['fixed_or_hourly_project_value'] = '<span class="fixed_or_hourly_project_value_color">project value:</span>';

$config['completed_bidding_listing_project_value'] = '<span class="completed_bidding_project_value_color">Project value:</span>';

// button text for manual project complete request send by PO
$config['project_details_page_request_project_mark_as_complete_po_view_btn_txt'] = 'Mark as complete';


$config['project_details_page_no_mark_project_complete_request_msg_po_view'] = 'You have not yet requested to mark this project as complete';


$config['project_details_page_po_view_create_mark_project_complete_invalid_request'] = "You cannot send request to mark as complete because request is invalid.";

$config['project_details_page_po_view_create_mark_project_complete_request_resent_time_expired'] = "You cannot send request. Time left to send next request is {next_mark_project_as_complete_request_send_available_time}";

$config['create_mark_project_complete_request_confirmation_project_modal_body'] = 'By continuing, you confirm your request to <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name_or_company_name}</a>, to mark this project as complete.';

$config['project_details_page_sp_view_decline_mark_project_complete_invalid_request'] = "You cannot decline request because request is invalid.";

$config['decline_mark_project_complete_request_confirmation_project_modal_body'] = 'By continuing, you confirm you decline request recieved from <a class="default_popup_blue_text">{user_first_name_last_name_or_company_name}</a> to mark this project as complete.';

$config['project_details_page_sp_view_accept_mark_project_complete_invalid_request'] = "You cannot accept request because request is invalid.";

$config['accept_mark_project_complete_request_confirmation_project_modal_body'] = 'By continuing, you confirm you accept request recieved from <a class="default_popup_blue_text">{user_first_name_last_name_or_company_name}</a> to mark this project as complete.';



// config for project mark complete request section on project detail page
$config['project_details_page_mark_complete_project_request_listing_requested_on_txt_po_view'] = 'Requested on:';

$config['project_details_page_mark_complete_project_request_listing_request_received_on_txt_sp_view'] = 'Request received on:';

$config['project_details_page_mark_complete_project_request_listing_request_expires_on_txt'] = 'Expires on:';

$config['project_details_page_mark_complete_project_request_listing_request_expired_on_txt'] = 'Expired on:';

$config['project_details_page_mark_complete_project_request_listing_request_declined_on_txt'] = 'Declined on:';

$config['project_details_page_mark_complete_project_request_listing_request_accepted_on_txt'] = 'Accepted on:';

$config['project_details_page_mark_complete_project_request_listing_waiting_for_acceptance_txt_po_view'] = 'Waiting for acceptance';

$config['project_details_page_mark_complete_project_request_listing_request_declined_txt'] = 'Request Declined';

$config['project_details_page_mark_complete_project_request_listing_request_accepted_txt'] = 'Request Accepted';

$config['project_details_page_mark_complete_project_request_listing_time_left_send_next_request_txt_po_view'] = 'Time left to send next request is';

################## config for error message for project mark complete section ######
$config['project_details_page_po_view_po_try_create_mark_project_complete_request_already_accepted_by_sp_message'] = 'Project status has changed to marked as completed. please refresh the page.';

$config['project_details_page_po_view_po_try_create_mark_project_complete_request_project_already_completed_via_portal_message']= 'project status has changed. project has been completed via portal. you cant send mark as complete request anymore.';

$config['project_details_page_sp_view_sp_try_accept_mark_project_complete_request_already_accepted_by_sp_message'] = 'Project status has changed. You have already accepted the request from {user_first_name_last_name_or_company_name} and marked this project as completed. please refresh the page.';

$config['project_details_page_sp_view_sp_try_accept_mark_project_complete_request_project_already_completed_via_portal_message'] = 'project status has changed. project has been completed via portal. you cant mark it as complete anymore.';


$config['project_details_page_sp_view_sp_try_decline_mark_project_complete_request_already_declined_by_sp_message'] = 'Project status has changed. You have already declined the request from {user_first_name_last_name_or_company_name} for marking this project as completed. please refresh the page.';

$config['project_details_page_sp_view_sp_try_decline_mark_project_complete_request_already_accepted_by_sp_message'] = 'Project status has changed. You have already accepted the request from {user_first_name_last_name_or_company_name} and marked this project as completed. please refresh the page.';

$config['project_details_page_sp_view_sp_try_decline_mark_project_complete_request_project_already_completed_via_portal_message'] = 'project status has changed. project has been completed via portal. you cant decline anymore.';

$config['project_details_page_sp_view_sp_try_accept_mark_project_complete_request_already_declined_by_sp_message'] = 'Project status has changed. You have already declined the request from {user_first_name_last_name_or_company_name} for marking this project as completed. please refresh the page.';

$config['project_details_page_sp_view_sp_try_accept_mark_project_complete_request_already_expired_message'] = 'You cannot accept request because acceptance time is expired.';

?>