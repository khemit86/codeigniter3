<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Meta Variables 
|--------------------------------------------------------------------------
| 
*/
################ Url Routing Variables for payments section on project detail page ###########
//PROJECT_DETAILS
$config['project_detail_page_payments_section_paging_url'] = 'load_payments';

$config['project_details_page_project_create_escrow_request_form_request_payment_button_txt_sp_view'] = 'Request Payment';

$config['project_details_page_project_create_escrow_request_form_heading_sp_view'] = 'Create escrow Request for <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name_or_company_name}</a>';

$config['project_details_page_project_create_escrow_request_form_create_escrow_request_button_txt_sp_view'] = 'Create PAYMENT Request';

//message for initial view for sp view in payment tab
$config['project_details_page_project_description_create_escrow_request_form_sp_view'] = 'SP VIEW - DescriptionSP-PJ';

$config['project_details_page_project_amount_create_escrow_request_form_sp_view'] = 'sp view - AmountSP-PJ';

############ for create escrow by PO #######
$config['project_details_page_project_create_escrow_form_heading_po_view'] = 'Create Payment for <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name_or_company_name}</a>';

$config['project_details_page_project_description_create_escrow_form_po_view'] = 'PO VIEW - transaction description';

$config['project_details_page_project_amount_create_escrow_form_po_view'] = 'PO VIEW - amount';

$config['project_details_page_project_business_service_fee_create_escrow_form_po_view'] = 'Business Service Fee';


$config['project_details_page_project_total_amount_create_escrow_form_po_view'] = 'PO VIEW - Total';

//message for initial view for po view in payment tab
$config['project_details_page_project_create_escrow_form_create_escrow_payment_button_txt_po_view'] = 'PO VIEW - Create payment';

$config['project_details_page_project_create_escrow_form_create_escrow_button_txt_po_view'] = 'Vytvořit platbu (Create Escrow)';


// config variables for validation messages on create escrow request form for SP view
$config['project_details_page_po_not_sufficient_balance_validation_project_escrow_form_message'] = 'you dont have sufficient account balance to create this payment';



##### this config variables are used when there is no requested/escrow/released escrow
$config['no_sent_payment_request_message_sp_view'] = "You do not currently have any sent payment creation request";

$config['no_incoming_payment_request_message_po_view'] = "You do not currently have any incoming payment request";

//--------
$config['no_incoming_escrow_payment_message_sp_view'] = "You do not currently have any incoming active escrow payment";

$config['no_outgoing_escrow_payment_message_po_view'] = "You do not currently any outgoing active escrow payment";

//--------
$config['no_cancelled_escrow_creation_request_message_sp_view'] = "You do not currently have any cancelled escrow creation request payment SP";

$config['no_cancelled_escrow_creation_request_message_po_view'] = "You do not currently have any cancelled Escrow creation request payment PO";

//--------
$config['no_received_escrow_payment_message_sp_view'] = "You do not currently have any received payment.";

$config['no_released_escrow_payment_message_po_view'] = "You do not currently have any released payment.";

//--------
$config['no_rejected_requested_escrow_creation_message_sp_view'] = "You do not currently have any rejected requested payment creation. SP";

$config['no_rejected_requested_escrow_creation_message_po_view'] = "You do not currently have any rejected requested payment creation. PO";

##### this config variables for drop down option regarding requested/escrow/released escrow section
$config['project_requested_escrow_section_option_cancel_sp_view'] = "SP - Cancel Requested Escrow";
$config['project_requested_escrow_section_option_reject_po_view'] = "PO - Reject Requested Escrow";
$config['project_requested_escrow_section_option_pay_po_view'] = "PO - Create Escrow Payment";

$config['project_active_escrow_section_option_release_po_view'] = "PO - Release Payment";
$config['project_active_escrow_section_option_partial_release_po_view'] = "PO - Partial Release Payment";
$config['project_active_escrow_section_option_cancel_sp_view'] = "SP - Cancel Escrow PAYMENT";
$config['project_active_escrow_section_option_request_release_sp_view'] = "SP - Request for release";

#####config variable when SP make request for escrow
$config['project_details_page_sp_view_create_escrow_request_deleted_project'] = 'SP - project has been deleted. you cannot create ESCROW PAYMENT request';

############### config variable for confirmation modal when SP cancel the requested escrow#####
$config['project_details_page_sp_view_cancel_requested_escrow_deleted_project'] = "SP - project has been deleted. you cannot cancel requested escrow";
$config['project_details_page_sp_view_cancel_invalid_requested_escrow_project'] = "SP - You cannot cancel the requested escrow because request is invalid.";

$config['cancel_requested_escrow_confirmation_project_modal_body'] = 'Are you sure you want to cancel the requested escrow of project?';
$config['cancel_requested_escrow_confirmation_project_modal_cancel_btn_txt'] = 'Cancel Requested Escrow';

############### config variable for confirmation modal when PO rejected the requested escrow#####
$config['reject_requested_escrow_confirmation_project_modal_body'] = 'Are you sure you want to reject the requested escrow of project?';
$config['reject_requested_escrow_confirmation_project_modal_reject_btn_txt'] = 'Reject Requested Escrow';

$config['project_details_page_po_view_reject_requested_escrow_deleted_project'] = "PO - project has been deleted. you cannot reject requested escrow";
$config['project_details_page_po_view_reject_invalid_requested_escrow_project'] = "PO - You cannot reject the requested escrow because request is invalid.";

########## config variable for confirmation modal when PO created requested escrow#####
$config['create_requested_escrow_confirmation_project_modal_body'] = 'Are you sure you want to create the requested escrow of project?';
$config['create_requested_escrow_confirmation_project_modal_confirm_btn_txt'] = 'Create Requested Escrow';
$config['project_details_page_po_view_create_invalid_requested_escrow_project'] = "PO - You cannot create the requested escrow because request is invalid.";
$config['project_details_page_po_view_create_requested_escrow_deleted_project'] = "PO - project has been deleted. you cannot create requested escrow";


########## config variable for PO create escrow #####
$config['project_details_page_po_view_create_escrow_deleted_project'] = "PO - project has been deleted. you cannot create escrow";

########## config variable for confirmation modal when PO released escrow#####
// for fixed budget
$config['fixed_budget_project_release_escrow_confirmation_project_modal_body'] = 'PRJ FB - by continuing you are going to release the amount of <span class="touch_line_break">{fixed_budget_project_release_escrow_amount}</span> to <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name_or_company_name}</a>';

// for hourly
$config['hourly_rate_based_project_release_escrow_confirmation_project_modal_body'] = 'HR FB - by continue you are going to release {number_of_hours} hrs with a rate of <span class="touch_line_break">{hourly_rate}</span>, for a total of <span class="touch_line_break">{hourly_rate_based_project_total_release_escrow_amount}</span>, to <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name_or_company_name}</a>';

// for fulltime
$config['fulltime_project_release_escrow_confirmation_project_modal_body'] = 'Fulltime: by continuing you are going to release the amount of <span class="touch_line_break">{fulltime_project_release_escrow_amount}</span> to <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name_or_company_name}</a>';


$config['release_escrow_confirmation_project_modal_confirm_btn_txt'] = 'Release Escrow';
$config['project_details_page_po_view_invalid_active_escrow_project'] = "PO - You cannot release escrow because request is invalid.";
$config['project_details_page_po_view_release_escrow_deleted_project'] = "PO - project has been deleted. you cannot release escrow";

########## config variable for confirmation modal when PO partial release escrow#####
$config['partial_release_escrow_confirmation_project_modal_confirm_btn_txt'] = 'Partial Release Escrow';

$config['fixed_budget_project_partial_release_escrow_confirmation_project_modal_available_escrowed_amount_txt'] = 'Escrow Amount-';

$config['fulltime_project_partial_release_escrow_confirmation_project_modal_available_escrowed_amount_txt'] = 'Fulltime:Escrow Amount-';
############### config variable for confirmation modal when SP cancel the escrow which is created by PO #####
$config['project_details_page_sp_view_cancel_escrow_deleted_project'] = "SP - project has been deleted. you cannot cancel escrow";

$config['project_details_page_sp_view_cancel_invalid_escrow_project'] = "SP - You cannot cancel the escrow because request is invalid.";

$config['cancel_escrow_confirmation_project_modal_body'] = 'Are you sure you want to cancel the escrow of project?';
$config['cancel_escrow_confirmation_project_modal_cancel_btn_txt'] = 'Cancel Escrow';

############### config variable for confirmation modal when SP make request release for the escrow which is created by PO #####
$config['project_details_page_sp_view_request_release_escrow_deleted_project'] = "SP - project has been deleted.you cannot make request";

$config['project_details_page_sp_view_request_release_invalid_escrow_project'] = "SP - You cannot make request for release the escrow because request is invalid.";


$config['request_release_escrow_confirmation_project_modal_body'] = 'Are you sure you want to make request for release the escrow of project?';


$config['request_release_escrow_confirmation_project_modal_request_release_btn_txt'] = 'Request Release';

$config['project_details_page_sp_view_project_requested_release_escrow_message'] = '* you requested for release escrow of value <strong class="touch_line_break">{requested_release_escrow_amount}</strong> to <strong class ="touch_line_break_name">{user_first_name_last_name_or_company_name}</strong> on <strong class ="touch_line_break">{sp_requested_release_date}</strong>';

$config['project_details_page_po_view_project_requested_release_escrow_message'] = '* <strong class ="touch_line_break_name">{user_first_name_last_name_or_company_name}</strong> requested to release escrow of value <strong class ="touch_line_break">{requested_release_escrow_amount}</strong> on <strong class ="touch_line_break">{sp_requested_release_date}</strong>';

/* Define the config for escrows related tab for SP/PO on project detail page*/ 

//For PO View
$config['project_details_page_payment_management_section_incoming_payment_requests_tab_po_view'] = 'PO - Incoming Payment Requests';
$config['project_details_page_payment_management_section_incoming_payment_requests_tab_tooltip_message_po_view'] = 'PO - Here you will have displayed each time when {user_first_name_last_name_or_company_name} will request a payment from you';

$config['project_details_page_payment_management_section_outgoing_escrowed_payments_tab_po_view'] = 'PO - Outgoing Escrowed Payments';
$config['project_details_page_payment_management_section_outgoing_escrowed_payments_tab_tooltip_message_po_view'] = 'PO - Here you will have displayed each time when you create a escrow payment to {user_first_name_last_name_or_company_name}';

$config['project_details_page_payment_management_section_cancelled_escrowed_payments_tab'] = 'Cancelled Escrow Payments';
$config['project_details_page_payment_management_section_cancelled_escrowed_payments_tab_tooltip_message_po_view'] = 'PO - Here you will have displayed cancelled escrow';

$config['project_details_page_payment_management_section_released_payments_tab_po_view'] = 'PO - Released Payments';
$config['project_details_page_payment_management_section_released_payments_tab_tooltip_message_po_view'] = 'PO - here you will have displayed all payments you released to {user_first_name_last_name_or_company_name}';

$config['project_details_page_payment_management_section_rejected_payment_requests_tab_po_view'] = 'PO - Rejected Payment Requests';
$config['project_details_page_payment_management_section_rejected_payment_requests_tab_tooltip_message_po_view'] = 'PO - here you will have displayed all payments requests you received from {user_first_name_last_name_or_company_name} and you decided to reject';

//For SP view
$config['project_details_page_payment_management_section_sent_payment_requests_tab_sp_view'] = 'SP - Sent Payment Requests';
$config['project_details_page_payment_management_section_sent_payment_requests_tab_tooltip_message_sp_view'] = 'SP - here you will have displayed your payment requests you asked {user_first_name_last_name_or_company_name} to make to you';

$config['project_details_page_payment_management_section_incoming_escrowed_payments_tab_sp_view'] = 'SP - Incoming Escrowed Payments';
$config['project_details_page_payment_management_section_incoming_escrowed_payments_tab_tooltip_message_sp_view'] = 'SP - here you will have displayed each time when {user_first_name_last_name_or_company_name} will create a milestone/escrow payment to you';

$config['project_details_page_payment_management_section_received_payments_tab_sp_view'] = 'SP - Received Payments';
$config['project_details_page_payment_management_section_received_payments_tab_tooltip_message_sp_view'] = 'SP - here you will have displayed all payments released to you by {user_first_name_last_name_or_company_name}';

$config['project_details_page_payment_management_section_rejected_payment_requests_tab_sp_view'] = 'SP - Rejected Payment Requests';
$config['project_details_page_payment_management_section_rejected_payment_requests_tab_tooltip_message_sp_view'] = 'SP - here you will have displayed all payment requests you sent to {user_first_name_last_name_or_company_name} and were rejected';

?>