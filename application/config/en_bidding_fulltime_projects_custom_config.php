<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['project_details_page_fulltime_project_bid_form_bid'] = 'Expected Salary';

$config['project_details_page_fulltime_bid_form_description_section_heading'] = 'Describe your application';

$config['project_details_page_fulltime_project_bid_form_tooltip_message_bid_amount'] = 'Enter the expected salary';

$config['project_details_page_fulltime_project_bid_form_place_application_btn_txt'] = 'Place Application';

$config['project_details_page_fulltime_project_active_bidder_listing_edit_bid_btn_txt'] = 'FT - Edit Application';

$config['project_details_page_fulltime_project_active_bidder_listing_retract_bid_btn_txt'] = 'FT - Retract Application';

$config['project_details_page_fulltime_project_active_bidder_listing_award_bid_bid_btn_txt'] = 'FT - Award Application';

$config['project_details_page_fulltime_project_bid_form_cancel_application_btn_txt'] = 'Cancel';

$config['project_details_page_fulltime_project_bid_form_edit_application_btn_txt'] = 'Update ApplicationFT';

$config['fulltime_project_details_page_inprogress_awaiting_acceptance_fulltime_project_tab_employee_view_txt'] = 'Awarded-FT';

$config['fulltime_project_details_page_hired_fulltime_project_tab_employee_view_txt'] = 'My Hire - FT';


$config['fulltime_project_details_page_awaiting_acceptance_applications_list_singular'] = 'Awarded Application';
$config['fulltime_project_details_page_awaiting_acceptance_applications_list_plural'] = 'Awarded Applications';


$config['fulltime_project_details_page_hired_applications_list_singular'] = 'Hired Application';
$config['fulltime_project_details_page_hired_applications_list_plural'] = 'Hired Applications';


$config['fulltime_projects_employee_view_project_value'] = '<span class="fulltime_projects_employee_view_project_value_color">employee:FT-(project value):</span>';

$config['fulltime_projects_employer_view_total_project_value'] = '<span class="employer_view_total_project_value_color">employer:FT-Total project Value -</span>'; //this variable is using to show the "Total project Value" label in "[user profile own projects tab / open bidding / expired]" tab in my project section of PO view

// config is using when attachment is not exists and project is in open for bidding status and po trying to open attachment from bidder list
$config['project_details_page_open_for_bidding_project_bidder_list_open_bid_attachment_not_exist_validation_message_po_view_fulltime_project'] = "Po View FT:Attachment of application not exists";


############ activity log message when service provider post the application

//WHAT MEANS - ACCEPT/DECLINE APPLICATION SP VIEW ? HOW EMPLOYEE CAN DECLINE APPLICATION?

// variable for confirmation confirmation popup for accept award for service provider (for fulltime project)
$config['accept_award_confirmation_fulltime_project_modal_title_employee_view'] = 'Accept Award(fulltime)';
$config['accept_award_confirmation_fulltime_project_modal_body_employee_view'] = 'Are you sure you want to accept the award?(fulltime)';

// variable for confirmation confirmation popup for decline award for service provider(for fulltime project)

$config['decline_award_confirmation_fulltime_project_modal_title_employee_view'] = 'Decline Award(fulltime)';
$config['decline_award_confirmation_fulltime_project_modal_body_employee_view'] = 'Are you sure you want to decline the award?(fulltime)';

############ activity log message when project owner award the application
$config['fulltime_project_message_sent_to_employer_when_employer_awarded_application_user_activity_log_displayed_message'] = 'you have successfully awarded fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>" to <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>';

$config['fulltime_project_message_sent_to_employee_when_employer_male_awarded_application_user_activity_log_displayed_message'] = 'Male : <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> have awarded to you HIS fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>". Deadline for accepting this award is {award_expiration_date}';

$config['fulltime_project_message_sent_to_employee_when_employer_female_awarded_application_user_activity_log_displayed_message'] = 'Female : <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> have awarded to you HER fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>". Deadline for accepting this award is {award_expiration_date}';

$config['fulltime_project_message_sent_to_employee_when_employer_company_app_male_awarded_application_user_activity_log_displayed_message'] = 'App Male : <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> have awarded to you HIS fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>". Deadline for accepting this award is {award_expiration_date}';

$config['fulltime_project_message_sent_to_employee_when_employer_company_app_female_awarded_application_user_activity_log_displayed_message'] = 'App Female : <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> have awarded to you HER Fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>". Deadline for accepting this award is {award_expiration_date}';


$config['fulltime_project_message_sent_to_employee_when_employer_company_awarded_application_user_activity_log_displayed_message'] = 'Company : <a href="{po_profile_url_link}" target="_blank">{user_company_name}</a> have awarded to you ITS fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>". Deadline for accepting this award is {award_expiration_date}';

//fulltime
$config['award_application_confirmation_fulltime_project_modal_title_employer_view'] = 'Confirmation Required - Award Application';

$config['award_application_confirmation_fulltime_project_modal_body_employer_view'] = 'Fulltime - By continuing you agree to award your "{fulltime_project_title}" to {user_first_name_last_name_or_company_name} for <span class="default_popup_price">{requested_salary_value} '.CURRENCY.'/mo</span>';

$config['award_application_confirmation_fulltime_project_modal_body_employer_view_confidential'] = 'Fulltime - By continuing you agree to award your "{fulltime_project_title}" to {user_first_name_last_name_or_company_name}</br></br>***ATTENTION*** {user_first_name_last_name_or_company_name} indicated the requested salary as "Confidential"';

$config['award_application_confirmation_fulltime_project_modal_body_employer_view_to_be_agreed'] = 'Fulltime - By continuing you agree to award your "{fulltime_project_title}" to {user_first_name_last_name_or_company_name}</br></br>***ATTENTION*** {user_first_name_last_name_or_company_name} indicated the requested salary as "To Be Agreed"';

$config['award_application_confirmation_fulltime_project_modal_award_btn_txt_employer_view'] = 'Award Application';

$config['project_details_page_retract_application_confirmation_fulltime_project_application_form_message'] = 'Application retract successfully !';

// variable for confirmation popup for retract application
$config['retract_application_confirmation_fulltime_project_modal_title'] = 'Retract Application';

$config['retract_application_confirmation_fulltime_project_modal_body'] = 'Are you sure you want to retract the application?';

$config['project_details_page_minimum_required_salary_amount_validation_fulltime_project_application_form_message'] = 'You cant place a salary less than {project_minimum_salary_amount}.';

$config['fulltime_project_message_sent_to_employer_when_new_application_received_from_employee_male_user_activity_log_displayed_message'] = 'Male : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> have applied on your fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employer_when_new_application_received_from_employee_female_user_activity_log_displayed_message'] = 'Female : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> have applied on your fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employer_when_new_application_received_from_employee_company_app_male_user_activity_log_displayed_message'] = 'App Male : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> have applied on your fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employer_when_new_application_received_from_employee_company_app_female_user_activity_log_displayed_message'] = 'App Female : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> have applied on your fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';


$config['fulltime_project_message_sent_to_employer_when_new_application_received_from_employee_company_user_activity_log_displayed_message'] = 'Company : <a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> have applied on your fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employee_when_new_application_placed_from_employee_user_activity_log_displayed_message'] = 'You have successfully applied on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

############ activity log message when service provider retract the application
$config['fulltime_project_message_sent_to_employer_when_employee_male_retracted_application_user_activity_log_displayed_message'] = 'Male : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> have retracted HIS application FROM your fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employer_when_employee_female_retracted_application_user_activity_log_displayed_message'] = 'Female : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> have retracted HER application FROM your fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employer_when_employee_company_app_male_retracted_application_user_activity_log_displayed_message'] = 'App Male : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> have retracted HIS application FROM your fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employer_when_employee_company_app_female_retracted_application_user_activity_log_displayed_message'] = 'App Female : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> have retracted HER application FROM your fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';


$config['fulltime_project_message_sent_to_employer_when_employee_company_retracted_application_user_activity_log_displayed_message'] = 'Company : <a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> have retracted ITS application FROM your fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employee_when_employee_retracted_application_user_activity_log_displayed_message'] = 'you have successfully retracted YOUR application FROM fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

############ activity log message when service provider edit the application
$config['fulltime_project_message_sent_to_employer_when_employee_male_edit_application_user_activity_log_displayed_message'] = 'Male : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> have updated HIS application on your fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employer_when_employee_female_edit_application_user_activity_log_displayed_message'] = 'Female : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> have updated HER application on your fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employer_when_employee_company_app_male_edit_application_user_activity_log_displayed_message'] = 'AppMale : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> have updated HIS application on your fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employer_when_employee_company_app_female_edit_application_user_activity_log_displayed_message'] = 'AppFemale : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> have updated HER application on your fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employer_when_employee_company_edit_application_user_activity_log_displayed_message'] = 'Company : <a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> have updated ITS application on your fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';


$config['fulltime_project_message_sent_to_employee_when_employee_edit_application_user_activity_log_displayed_message'] = 'you have successfully updated YOUR application on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

############ activity log message when service provider decline the awarded application

$config['fulltime_project_message_sent_to_employer_when_employee_male_declined_award_user_activity_log_displayed_message'] = 'Male: <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> declined your award on your fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employer_when_employee_female_declined_award_user_activity_log_displayed_message'] = 'Female: <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> declined your award on your fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fulltime_project_message_sent_to_employer_when_employee_company_app_male_declined_award_user_activity_log_displayed_message'] = 'App Male: <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> declined your award on your fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employer_when_employee_company_app_female_declined_award_user_activity_log_displayed_message'] = 'App Female: <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> declined your award on your fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employer_when_employee_company_declined_award_user_activity_log_displayed_message'] = 'Company:<a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> declined your award on your fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fulltime_project_message_sent_to_employee_when_employee_declined_award_user_activity_log_displayed_message'] = 'you declined the award from <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

############ activity log message when service provider accept the awarded application
$config['fulltime_project_message_sent_to_employer_when_awarded_employee_male_accepted_awarded_application_user_activity_log_displayed_message'] = 'Male : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> have accepted your award on your fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employer_when_awarded_employee_female_accepted_awarded_application_user_activity_log_displayed_message'] = 'Female : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> have accepted your award on your fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employer_when_awarded_employee_company_app_male_accepted_awarded_application_user_activity_log_displayed_message'] = 'App Male : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> have accepted your award on your fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employer_when_awarded_employee_company_app_female_accepted_awarded_application_user_activity_log_displayed_message'] = 'App Female : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> have accepted your award on your fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employer_when_awarded_employee_company_accepted_awarded_application_user_activity_log_displayed_message'] = 'Company : <a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> have accepted your award on your fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';


$config['fulltime_project_message_sent_to_employee_when_employee_accepted_awarded_application_user_activity_log_displayed_message'] = 'you have successfully accepted the award on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

###################################################################################################


$config['project_details_page_sp_view_place_bid_expired_fulltime_project'] = "Apply FT - Fulltime project has expired. You cannot place bid.";

$config['project_details_page_sp_view_place_bid_upload_bid_attachment_expired_fulltime_project'] = "Fulltime Project has expired. You cannot upload the attachment anymore.";

$config['project_details_page_sp_view_place_bid_delete_bid_attachment_expired_fulltime_project'] = "Fulltime Project has expired. You cannot delete file.";

$config['project_details_page_sp_view_place_bid_cancelled_fulltime_project'] = "Fulltime project has been cancelled. You cannot place bid.";

$config['project_details_page_sp_view_place_bid_upload_bid_attachment_cancelled_fulltime_project'] = "Fulltime Project has been cancelled. You cannot upload the attachment anymore.";

$config['project_details_page_sp_view_place_bid_open_bid_attachment_cancelled_fulltime_project'] = "fulltime Project has been cancelled. You cannot open the attachment file.";

$config['project_details_page_sp_view_place_bid_delete_bid_attachment_cancelled_fulltime_project'] = "Fulltime project has been cancelled. You cannot delete the attachment.";




$config['project_details_page_sp_view_place_bid_already_posted_bid_same_fulltime_project'] = "You have already placed bid on this fulltime project. you cannot place bid again.";

$config['project_details_page_sp_view_place_bid_upload_bid_attachment_already_posted_bid_fulltime_project'] = "You have already placed bid on this fulltime project. So you cannot upload bid attachment anymore.";

$config['project_details_page_sp_view_place_bid_awarded_same_sp_fulltime_project'] = "Fulltime project has been already awarded to you. You cannot place the bid anymore.";

$config['project_details_page_sp_view_place_bid_upload_bid_attachment_awarded_same_sp_fulltime_project'] = "Fulltime project has been already awarded to you. You cannot upload the attachment anymore.";

$config['project_details_page_sp_view_place_bid_in_progress_same_sp_fulltime_project'] = "You have already been hired on this fulltime job. You started the work already. You cannot place bib again anymore.";

$config['project_details_page_sp_view_place_bid_upload_bid_attachment_in_progress_same_sp_fulltime_project'] = "You have already been hired on this fulltime job. You started the work already. You cannot upload the attachment anymore.";

$config['project_details_page_sp_view_place_bid_delete_bid_attachment_already_posted_same_sp_fulltime_project'] = "You already placed application on this fulltime job. You cannot delete the file.";

$config['project_details_page_sp_view_place_bid_delete_bid_attachment_awarded_same_sp_fulltime_project'] = "Position is already awarded to you. You cannot delete the file.";

$config['project_details_page_sp_view_place_bid_delete_bid_attachment_in_progress_same_sp_fulltime_project'] = "You have already been hired on this fulltime job. You started the work already. You cannot delete the file.";

$config['project_details_page_sp_view_update_bid_cancelled_fulltime_project'] = "This fulltime project has been cancelled. You cannot update your bid.";

$config['project_details_page_sp_view_update_bid_already_retracted_fulltime_project'] = "You have already retracted your application from this fulltime job. You can not update your application anymore.";

$config['project_details_page_sp_view_update_bid_awarded_same_sp_fulltime_project'] = "This fulltime project has been awarded to you. You cannot update your bid.";

$config['project_details_page_sp_view_update_bid_in_progress_same_sp_fulltime_project'] = "You started to work on this job. You cannot update the bid anymore.";

$config['project_details_page_sp_view_update_bid_upload_bid_attachment_awarded_same_sp_fulltime_project'] = "This fulltime project has been awarded to you. You cannot upload attachment anymore.";

$config['project_details_page_sp_view_update_bid_upload_bid_attachment_in_progress_same_sp_fulltime_project'] = "You already started to work on this job. You can not upload atatchment anymore.";

$config['project_details_page_sp_view_update_bid_already_retracted_upload_bid_attachment_same_sp_fulltime_project'] = "You have already retracted your application from this fulltime job. You cannot upload attachment anymore.";

$config['project_details_page_sp_view_update_bid_upload_bid_attachment_cancelled_fulltime_project'] = "This fulltime job has been cancelled. You cannot upload attachment anymore.";

$config['project_details_page_sp_view_update_bid_open_bid_attachment_cancelled_fulltime_project'] = "This fulltime project has been cancelled. You cannot open the file.";

$config['project_details_page_sp_view_update_bid_already_retracted_open_bid_attachment_same_sp_fulltime_project'] = "You have already retracted bid. You cannot open the file.";

$config['project_details_page_sp_view_update_bid_delete_bid_attachment_cancelled_fulltime_project'] = "This fulltime project has been cancelled. You cannot delete the attachment.";

$config['project_details_page_sp_view_update_bid_delete_bid_attachment_awarded_same_sp_fulltime_project'] = "This fulltime job has been awarded to you. You cannot delete the file anymore.";

$config['project_details_page_sp_view_update_bid_delete_bid_attachment_in_progress_same_sp_fulltime_project'] = "You started to work on this job. You cannot delete the file.";

##################################################################################################################

$config['retract_bid_confirmation_fulltime_project_modal_retract_btn_txt'] = 'Retract Application';

$config['project_details_page_sp_view_retract_bid_cancelled_fulltime_project'] = "Fulltime project has been cancelled. You cannot retract your bid.";

$config['project_details_page_sp_view_retract_bid_awarded_same_sp_fulltime_project'] = "This fulltime project has been awarded to you. You cannot retract your bid.";

$config['project_details_page_sp_view_retract_bid_in_progress_same_sp_fulltime_project'] = "You started to work on this job. You cannot retract your bid.";

$config['project_details_page_sp_view_retract_bid_already_retracted_fulltime_project'] = "You have already retracted your application from this fulltime job already.";



$config['project_details_page_po_view_award_bid_cancelled_fulltime_project'] = "You have already canceled this fulltime project. You cannot award to this user.";



$config['project_details_page_po_view_award_bid_already_awarded_same_sp_fulltime_project'] = 'You have already awarded this fulltime project to <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name_or_company_name}</a> on this fulltime project. You cannot award again.';

$config['project_details_page_po_view_award_bid_in_progress_same_sp_fulltime_project'] = 'You have already hired <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name_or_company_name}</a> on this fulltime project. You cannot award again.';


$config['project_details_page_po_view_award_bid_already_retracted_fulltime_project'] = "This application is already retracted. You cannot award the position.";

################# error message when trying to decline the awarded bid
// when some unauthorized user(PO/unauthrizedSP) trying to decline bid
$config['project_details_page_sp_view_decline_award_cancelled_fulltime_project'] = "This fulltime project has been cancelled. You cannot decline the award.";

$config['project_details_page_sp_view_try_decline_award_in_progress_fulltime_project'] = "You already accepted this award, therefore your application status has changed to hired (in progress). You cannot decline bid.";

$config['project_details_page_sp_view_decline_award_award_already_declined_or_expired_fulltime_project'] = 'Status of your application has changed. You cannot decline award.';

$config['project_details_page_sp_view_decline_award_already_retracted_fulltime_project'] = "You have already retracted your application on this fulltime project. You cannot decline the award.";

################# error message when trying to accept the awarded bid
// when some unauthorized user(PO/unauthrizedSP) trying to accept bid
$config['project_details_page_sp_view_accept_award_cancelled_fulltime_project'] = "This fulltime project has been cancelled. You cannot accept the award anymore.";

$config['project_details_page_sp_view_accept_award_application_already_retracted_fulltime_project'] = "You have already retracted your application from this fulltime project. You cannot accept the award anymore.";

$config['project_details_page_sp_view_accept_award_award_already_declined_or_expired_fulltime_project'] = "Status of your application has changed. You cannot accept award.";

$config['project_details_page_sp_view_validation_fulltime_project_accept_award_award_acceptance_deadline_already_expired_message'] = "You cannot accept the award because award acceptance time has expired";

$config['project_details_page_sp_view_accept_award_in_progress_fulltime_project'] = "You have already accepted the award on this fulltime project. You are now hired and started the work. You cannot accept the award again.";


// validation message for bid form
$config['project_details_page_bid_amount_validation_fulltime_project_bid_form_message'] = 'salary is required';

$config['project_details_page_bid_description_validation_fulltime_project_bid_form_message'] = 'application description is required';

$config['project_details_page_bid_posted_confirmation_fulltime_project_bid_form_message'] = 'Application posted successfully!';

$config['project_details_page_bid_updated_confirmation_fulltime_project_bid_form_message'] = 'Application updated successfully!';

################ config for download the bid attachments from active bids/awarded bids/in progress bids
// config is using when attachment is not exists and project is in open for bidding status

// config when sp trying to open bid attachments from bidder list project status is open for bidding but attachment not exists or corrupted
$config['project_details_page_open_for_bidding_project_bidder_list_open_bid_attachment_not_exist_validation_message_bidder_view_fulltime_project'] = "sp view:Attachment of application not exists of fulltime project.";

$config['project_details_page_cancelled_fulltime_project_bidder_list_open_bid_attachment_not_exist_validation_message_sp_po_view'] = "This Fulltime Project has been cancelled. So you cannot open the attachment.";//this config is using as common for sp/po when they are trying to open bid atatchments from bidding list and in the background project has been cancelled

// Config is used when po trying to open the bid attachment from bidder list and project status is expired
$config['project_details_page_expired_project_bidder_list_open_bid_attachment_not_exist_validation_message_po_view_fulltime_project'] = "Expired Fulltime Project - Po View - Bidder list - Application Attachment not exists.";

// Config is used when sp trying to open the bid attachment from bidder list and project status is expired
$config['project_details_page_expired_project_bidder_list_open_bid_attachment_not_exist_validation_message_bidder_view_fulltime_project'] = "Fulltime Expired Project - Sp view - Bid Attachment not exists.";

$config['fulltime_project_details_page_sp_awarded_status_sp_try_open_bid_attachment_not_exist_validation_message_bidder_view'] = "Sp View - Fulltime Project Awarded - attachment not exists.";

$config['project_details_page_bid_form_bid_attachment_not_exist_validation_message_bidder_view_fulltime_project'] = "Fulltime Project - Bid form - Bid attachment not exist - An error has been occurred. Please either remove the attachment and try to upload again!";

$config['project_details_page_bidder_listing_expected_salary_txt'] = '<span class="bidder_listing_expected_salary_txt_color">Expected salary:</span>';


############ activity log message when awarded bid by project owner expiration time is passed

$config['fulltime_project_message_sent_to_employer_when_awarded_bid_employee_male_expiration_time_passed_user_activity_log_displayed_message'] = 'Male:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> did not accept in time your award on YOUR fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employer_when_awarded_bid_employee_female_expiration_time_passed_user_activity_log_displayed_message'] = 'Female:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> did not accept in time your award on YOUR fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fulltime_project_message_sent_to_employer_when_awarded_bid_employee_company_app_male_expiration_time_passed_user_activity_log_displayed_message'] = 'App Male: <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> did not accept in time your award on YOUR fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employer_when_awarded_bid_employee_company_app_female_expiration_time_passed_user_activity_log_displayed_message'] = 'App Female: <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> did not accept in time your award on YOUR fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fulltime_project_message_sent_to_employer_when_awarded_bid_employee_company_expiration_time_passed_user_activity_log_displayed_message'] = 'Company:<a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> did not accept in time your award on YOUR fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fulltime_project_message_sent_to_employee_when_awarded_bid_expiration_time_passed_user_activity_log_displayed_message'] = 'you did not accept in time THE AWARD FROM <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


############ activity log message when cancelled the awarded project by admin and SP recived notification for his awarded bid
$config['fulltime_project_cancelled_by_admin_awarded_user_activity_log_displayed_message_sent_to_po'] = 'Employer view - FULLTIME AwardED:Inzerát pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>" je zrušený administrátorem. Máte-li jakékoli dotazy, kontaktujte nás xxxxx@xxxxx.';

$config['fulltime_project_cancelled_by_admin_awarded_user_activity_log_displayed_message_sent_to_awarded_sp'] = 'Employee VIew: AWARDED Fulltime:"<a href="{project_url_link}" target="_blank">{project_title}</a>" cancelled by admin your award on this FULLTIME project is cancelled.';

// for expired project
$config['fulltime_expired_project_cancelled_by_admin_awarded_user_activity_log_displayed_message_sent_to_po'] = 'FULLTIME Awarded:Employer->Expired: Inzerát pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>" je zrušený administrátorem. Máte-li jakékoli dotazy, kontaktujte nás XXXX@XXXXX.';

$config['fulltime_expired_project_cancelled_by_admin_awarded_user_activity_log_displayed_message_sent_to_awarded_sp'] = 'FULLTIME AWARDED->Expired->Employee: "<a href="{project_url_link}" target="_blank">{project_title}</a>" HAS BEEN cancelled by admin. Your award on this project is cancelled';

######## These config variables using on those files where in progress bidding listing is showing
$config['fulltime_project_hired_sp_employment_start_date'] = '<span class="fulltime_project_hired_sp_employment_start_date_color">Employment Start Date:</span>';

?>