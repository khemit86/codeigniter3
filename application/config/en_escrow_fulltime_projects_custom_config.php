<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
//10.11.2019

####Defined configs are used on project detail page (payments tab) as well as on project payment overview page##
########## Start ######

// For Incoming Payment Requests tab 
// for Po view
$config['fulltime_project_details_page_payment_management_section_incoming_payment_requests_tab_total_requested_amount_txt_employer_view'] = "FT-PO-Requested amount:"; 
$config['fulltime_project_details_page_payment_management_section_incoming_payment_requests_tab_requested_on_txt_employer_view'] = "FT-PO-Requested On:"; 
$config['fulltime_project_details_page_payment_management_section_incoming_payment_requests_tab_description_txt_employer_view'] = "FT-PO-Description:"; 

$config['fulltime_project_details_page_payment_management_section_incoming_payment_requests_tab_total_txt_employer_view'] = "F-PO-Total(requested tab):";

// For Sent Payment Requests Tab
// For sp view
$config['fulltime_project_details_page_payment_management_section_sent_payment_requests_tab_total_requested_amount_txt_employee_view'] = "FT-SP-Requested amount:"; 
$config['fulltime_project_details_page_payment_management_section_sent_payment_requests_tab_requested_on_txt_employee_view'] = "FT-SP-Requested On:"; 
$config['fulltime_project_details_page_payment_management_section_sent_payment_requests_tab_description_txt_employee_view'] = "FT-SP-Description:"; 

$config['fulltime_project_details_page_payment_management_section_sent_payment_requests_tab_total_txt_employee_view'] = "F-SP-Total(requested tab):"; 

// For outgoing escrowed payments tab
// For Po View
$config['fulltime_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_requested_on_txt_employer_view'] = "FT-PO-Requested On:"; 
$config['fulltime_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_escrow_amount_txt_employer_view'] = "FT-PO-Escrow Amount:"; 
$config['fulltime_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_business_service_fee_txt_employer_view'] = "FT-PO-Business Service Fee:"; 
$config['fulltime_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_total_escrow_txt_employer_view'] = "FT-PO-Total Escrow:"; 
$config['fulltime_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_created_on_txt_employer_view'] = "FT-PO-Created On:";
$config['fulltime_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_description_txt_employer_view'] = "FT-PO-Description:";
$config['fulltime_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_total_txt_employer_view'] = "FT-PO-Total(escrow tab):";

// For incoming escrowed payments tab 
// For Sp View
$config['fulltime_project_details_page_payment_management_section_incoming_escrowed_payments_tab_requested_on_txt_employee_view'] = "FT-SP-Requested On:"; 
$config['fulltime_project_details_page_payment_management_section_incoming_escrowed_payments_tab_escrow_amount_txt_employee_view'] = "FT-SP-Escrow Amount:"; 
$config['fulltime_project_details_page_payment_management_section_incoming_escrowed_payments_tab_created_on_txt_employee_view'] = "FT-SP-Created On:"; 
$config['fulltime_project_details_page_payment_management_section_incoming_escrowed_payments_tab_description_txt_employee_view'] = "FT-SP-Description:"; 
$config['fulltime_project_details_page_payment_management_section_incoming_escrowed_payments_tab_total_txt_employee_view'] = "FT-SP-Total(escrow tab):"; 

// For cancelled escrowed payments tab
// For Po View
$config['fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_reverted_amount_txt_employer_view'] = "FT-PO-Reverted amount:"; 
$config['fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_reverted_business_service_fee_txt_employer_view'] = "FT-PO-Reverted Business Service Fee:"; 
$config['fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_total_reverted_amount_txt_employer_view'] = "FT-PO-Total Reverted Amount:"; 
$config['fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_dispute_id_txt_employer_view'] = "FT-PO-Dispute Id:"; 
$config['fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_dispute_close_date_txt_employer_view'] = "FT-PO-Dispute close date:";
$config['fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_description_txt_employer_view'] = "FT-PO-Description:"; 
$config['fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_cancelled_on_txt_employer_view'] = "FT-PO-Cancelled on:";
$config['fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_created_on_txt_employer_view'] = "FT-PO-Outgoing escrow payment created on:"; 
$config['fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_total_txt_employer_view'] = "FT-PO-Total(cancelled tab):"; 

// For cancelled escrowed payments tab 
// For Sp View
$config['fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_dispute_close_date_txt_employee_view'] = "FT-SP-Dispute close date:"; 
$config['fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_dispute_id_txt_employee_view'] = "FT-SP-Dispute Id:"; 
$config['fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_reverted_amount_topo_txt_employee_view'] = "FT-Reverted amount to {user_first_name_last_name_or_company_name}"; 
$config['fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_description_txt_employee_view'] = "FT-SP-Description:"; 
$config['fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_cancelled_on_txt_employee_view'] = "FT-SP-Cancelled on:"; 
$config['fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_created_on_txt_employee_view'] = "FT-SP-Incoming escrow payment created on:"; 
$config['fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_total_txt_employee_view'] = "FT-SP-Total(cancelled tab):"; 

// For released escrowed payments tab
// For Po View
$config['fulltime_project_details_page_payment_management_section_released_payments_tab_amount_txt_employer_view'] = "FT-PO-Amount:"; 
$config['fulltime_project_details_page_payment_management_section_released_payments_tab_business_service_fee_txt_employer_view'] = "FT-PO-Business Service Fee:"; 
$config['fulltime_project_details_page_payment_management_section_released_payments_tab_paid_on_txt_employer_view'] = "FT-PO-Paid On:"; 
$config['fulltime_project_details_page_payment_management_section_released_payments_tab_description_txt_employer_view'] = "FT-PO-Description:"; 
$config['fulltime_project_details_page_payment_management_section_released_payments_tab_dispute_id_txt_employer_view'] = "FT-PO-Dispute Id:"; 
$config['fulltime_project_details_page_payment_management_section_released_payments_tab_total_paid_txt_employer_view'] = "F-PO-Total Paid:"; 
$config['fulltime_project_details_page_payment_management_section_released_payments_tab_total_business_charges_txt_employer_view'] = "FT-PO-Total Business Charges:"; 

// For recieved escrowed payments tab
// For Sp View
$config['fulltime_project_details_page_payment_management_section_received_payments_tab_amount_txt_employee_view'] = "FT-SP-Amount:"; 
$config['fulltime_project_details_page_payment_management_section_received_payments_tab_paid_on_txt_employee_view'] = "FT-SP-Paid On:"; 
$config['fulltime_project_details_page_payment_management_section_received_payments_tab_description_txt_employee_view'] = "FT-SP-Description:"; 
$config['fulltime_project_details_page_payment_management_section_received_payments_tab_dispute_id_txt_employee_view'] = "FT-SP-Dispute Id:"; 
$config['fulltime_project_details_page_payment_management_section_received_payments_tab_total_received_txt_employee_view'] = "FT-SP-Total Received:"; 

// For rejected payment requests tab
// For Po view
$config['fulltime_project_details_page_payment_management_section_rejected_payment_requests_tab_requested_on_txt_employer_view'] = "FT-PO-Requested On:";
$config['fulltime_project_details_page_payment_management_section_rejected_payment_requests_tab_amount_txt_employer_view'] = "FT-PO-Amount:";
$config['fulltime_project_details_page_payment_management_section_rejected_payment_requests_tab_rejected_on_txt_employer_view'] = "FT-PO-Rejected On:";
$config['fulltime_project_details_page_payment_management_section_rejected_payment_requests_tab_description_txt_employer_view'] = "FT-PO-Description:";
$config['fulltime_project_details_page_payment_management_section_rejected_payment_requests_tab_total_txt_employer_view'] = "FT-PO-Total(rejected tab):"; 


// For rejected payment requests tab
// For SP view
$config['fulltime_project_details_page_payment_management_section_rejected_payment_requests_tab_requested_on_txt_employee_view'] = "FT-SP-Requested On:";
$config['fulltime_project_details_page_payment_management_section_rejected_payment_requests_tab_amount_txt_employee_view'] = "FT-SP-Amount:";
$config['fulltime_project_details_page_payment_management_section_rejected_payment_requests_tab_rejected_on_txt_employee_view'] = "FT-SP-Rejected On:";
$config['fulltime_project_details_page_payment_management_section_rejected_payment_requests_tab_description_txt_employee_view'] = "FT-SP-Description:";
$config['fulltime_project_details_page_payment_management_section_rejected_payment_requests_tab_total_txt_employee_view'] = "FT-SP-Total(rejected tab):";

####Defined configs are used on project detail page (payments tab) as well as on project payment overview page##
########## End ######

##############################################################
$config['project_details_page_min_salary_amount'] = 50000; // This variable will used when employer posted project with not specifed budget and employee try to place application on it
//used for both PO and SP - to be reviewed and confirmed
$config['project_details_page_minimum_required_salary_amount_validation_fulltime_project_create_escrow_form_message'] = 'You cant enter salary amount less than {project_minimum_salay_amount} '.CURRENCY.'/mo.';


//config variables used on project detail page for create escrow request form regarding Employee view
$config['project_details_page_fulltime_project_create_escrow_request_form_tooltip_message_description_employee_view'] = 'CER SP - Fulltime Description';
$config['project_details_page_fulltime_project_create_escrow_request_form_tooltip_message_amount_employee_view'] = 'CER SP - Fulltime Salary';

######## config for partial release escrow ######
$config['project_details_page_fulltime_project_partial_release_escrow_form_tooltip_message_amount_employer_view'] = 'PRE - Fulltime Salary';
$config['project_details_page_fulltime_project_partial_release_escrow_form_tooltip_message_description_employer_view'] = 'PRE - Fulltime Description';

$config['project_details_page_fulltime_project_create_escrow_request_form_request_payment_button_txt_employee_view'] = 'SP Request Salary';
$config['project_details_page_fulltime_project_create_escrow_request_form_cancel_button_txt_employee_view'] = 'SP - Cancel';


$config['project_details_page_fulltime_project_create_escrow_request_form_heading_employee_view'] = 'SP - Create Salary Request for <a href="#" class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name_or_company_name}</a>';

$config['project_details_page_fulltime_project_create_escrow_request_form_create_escrow_request_button_txt_employee_view'] = 'SP - Create Milestone Request Salary';


$config['project_details_page_employee_view_fulltime_project_requested_release_escrow_message'] = 'FT SP - * you requested for release escrow of value <strong>{fulltime_request_release_escrow_value}</strong> to <strong>{user_first_name_last_name_or_company_name}</strong> on <strong>{employee_requested_release_date}</strong>';

$config['project_details_page_employer_view_fulltime_project_requested_release_escrow_message'] = 'FT PO - -* <strong>{user_first_name_last_name_or_company_name}</strong> requested to release escrow of value <strong><span class="touch_line_break">{fulltime_request_release_escrow_value}</span></strong> on <strong>{employee_requested_release_date}</strong>';

//message for initial view for sp view in payment tab
$config['project_details_page_fulltime_project_description_create_escrow_request_form_employee_view'] = 'CER - Description SP - FT';
$config['project_details_page_fulltime_project_amount_create_escrow_request_form_employee_view'] = 'CER - Amount SP - FT';


### confIg for partial release escrow form
$config['fulltime_project_partial_release_escrow_confirmation_modal_description_txt'] = 'PRE - fulltime Description';
$config['fulltime_project_partial_release_escrow_confirmation_modal_amount_tobe_released_txt'] = 'PRE - fulltime project Amount';
$config['fulltime_project_partial_release_escrow_confirmation_modal_business_service_fee_txt'] = 'PRE - Fulltime Business Service Fee';

### Employer created Escrow
$config['fulltime_project_realtime_notification_message_sent_to_employer_when_employer_created_escrow'] = 'FT - Escrow payment created successfully.'; // send by php

$config['fulltime_project_message_sent_to_employee_when_employer_male_created_escrow_user_activity_log_displayed_message'] = 'Male: <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> created escrow payment of value of <span>{fulltime_project_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employee_when_employer_female_created_escrow_user_activity_log_displayed_message'] = 'Female: <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> created escrow payment of value of <span>{fulltime_project_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employee_when_employer_company_app_male_created_escrow_user_activity_log_displayed_message'] = 'App Male: <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> created escrow payment of value of <span>{fulltime_project_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employee_when_employer_company_app_female_created_escrow_user_activity_log_displayed_message'] = 'App Female: <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> created escrow payment of value of <span>{fulltime_project_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employee_when_employer_company_created_escrow_user_activity_log_displayed_message'] = 'Company: <a href="{po_profile_url_link}" target="_blank">{company_name}</a> created escrow payment of value of <span>{fulltime_project_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employer_when_employer_created_escrow_user_activity_log_displayed_message'] = 'you have successfully created the escrow of value of <span>{fulltime_project_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>" for <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>';

######### Activity log message when Employee requested the escrow for Employer ##########
$config['fulltime_project_realtime_notification_message_sent_to_employee_when_employee_created_escrow_request'] = 'FT - Escrow request creation successfully sent to <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>'; // send by php

$config['fulltime_project_message_sent_to_employer_when_employee_male_created_escrow_request_user_activity_log_displayed_message'] = 'Male : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> created the escrow request of value <span>{fulltime_project_requested_escrow_amount}</span> of fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employer_when_employee_female_created_escrow_request_user_activity_log_displayed_message'] = 'Female : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> created the escrow request of value <span>{fulltime_project_requested_escrow_amount}</span> of fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employer_when_employee_company_app_male_created_escrow_request_user_activity_log_displayed_message'] = 'App Male : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> created the escrow request of value <span>{fulltime_project_requested_escrow_amount}</span> of fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employer_when_employee_company_app_female_created_escrow_request_user_activity_log_displayed_message'] = 'App Female : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> created the escrow request of value <span>{fulltime_project_requested_escrow_amount}</span> of fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employer_when_employee_company_created_escrow_request_user_activity_log_displayed_message'] = 'Company : <a href="{sp_profile_url_link}" target="_blank">{company_name}</a> created the escrow request of value <span>{fulltime_project_requested_escrow_amount}</span> of fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employee_when_employee_created_escrow_request_user_activity_log_displayed_message'] = 'you have successfully create escrow request of value <span>{fulltime_project_requested_escrow_amount}</span> for fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


####### Realtime notification and activity log when employee cancel requested escrow
$config['fulltime_project_realtime_notification_message_sent_to_employee_when_employee_cancelled_requested_escrow'] = 'FT - Escrow payment successfully cancelled';// send by php

$config['fulltime_project_message_sent_to_employer_when_employee_male_cancelled_escrow_request_user_activity_log_displayed_message'] = 'Male : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> cancelled escrow creation request of value of <span>{fulltime_project_requested_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';
$config['fulltime_project_message_sent_to_employer_when_employee_female_cancelled_escrow_request_user_activity_log_displayed_message'] = 'Female : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> cancelled escrow creation request of value of <span>{fulltime_project_requested_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employer_when_employee_company_app_male_cancelled_escrow_request_user_activity_log_displayed_message'] = 'App Male : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> cancelled escrow creation request of value of <span>{fulltime_project_requested_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employer_when_employee_company_app_female_cancelled_escrow_request_user_activity_log_displayed_message'] = 'App Female : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> cancelled escrow creation request of value of <span>{fulltime_project_requested_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employer_when_employee_company_cancelled_escrow_request_user_activity_log_displayed_message'] = 'Company : <a href="{sp_profile_url_link}" target="_blank">{company_name}</a> cancelled escrow creation request of value of <span>{fulltime_project_requested_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employee_when_employee_cancelled_requested_escrow_user_activity_log_displayed_message'] = 'you cancelled the escrow creation request of value of <span>{fulltime_project_requested_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


##### Realtime notification and activity log when employer reject requested escrow
$config['fulltime_project_realtime_notification_message_sent_to_employer_when_employer_rejected_escrow_creation_request'] = 'FT - You rejected the escrow creation request coming from <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>';// send by php

$config['fulltime_project_message_sent_to_employee_when_employer_male_rejected_escrow_creation_request_user_activity_log_displayed_message'] = 'Male: <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> rejected escrow creation request of <span>{fulltime_project_requested_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employee_when_employer_female_rejected_escrow_creation_request_user_activity_log_displayed_message'] = 'Female: <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> rejected escrow creation request of <span>{fulltime_project_requested_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employee_when_employer_company_app_male_rejected_escrow_creation_request_user_activity_log_displayed_message'] = 'App Male: <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> rejected escrow creation request of <span>{fulltime_project_requested_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employee_when_employer_company_app_female_rejected_escrow_creation_request_user_activity_log_displayed_message'] = 'App Female: <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> rejected escrow creation request of <span>{fulltime_project_requested_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

//$config['fulltime_project_message_sent_to_employee_when_employer_male_rejected_escrow_creation_request_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> rejected escrow creation request of <span>{fulltime_project_requested_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

//$config['fulltime_project_message_sent_to_employee_when_employer_female_rejected_escrow_creation_request_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> rejected escrow creation request of <span>{fulltime_project_requested_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employee_when_employer_company_rejected_escrow_creation_request_user_activity_log_displayed_message'] = 'Company: <a href="{po_profile_url_link}" target="_blank">{company_name}</a> rejected escrow creation request of <span>{fulltime_project_requested_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employer_when_employer_rejected_escrow_creation_request_user_activity_log_displayed_message'] = 'you rejected the escrow creation request from <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> of value <span>{fulltime_project_requested_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';


######### Activity log message when Employer created requested escrow for Employee ##########
$config['fulltime_project_message_sent_to_employee_when_employer_male_created_requested_escrow_user_activity_log_displayed_message'] = 'Male : <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> created escrow payment for your requested value of <span>{fulltime_project_requested_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employee_when_employer_female_created_requested_escrow_user_activity_log_displayed_message'] = 'Female : <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> created escrow payment for your requested value of <span>{fulltime_project_requested_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employee_when_employer_company_app_male_created_requested_escrow_user_activity_log_displayed_message'] = 'App Male : <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> created escrow payment for your requested value of <span>{fulltime_project_requested_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employee_when_employer_company_app_female_created_requested_escrow_user_activity_log_displayed_message'] = 'App Female : <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> created escrow payment for your requested value of <span>{fulltime_project_requested_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employee_when_employer_company_created_requested_escrow_user_activity_log_displayed_message'] = 'Company : <a href="{po_profile_url_link}" target="_blank">{company_name}</a> created escrow payment for your requested value of <span>{fulltime_project_requested_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employer_when_employer_created_requested_escrow_user_activity_log_displayed_message'] = 'You have successfully created the requested escrow of value of <span>{fulltime_project_requested_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>" for <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>';

$config['fulltime_project_realtime_notification_message_sent_to_employer_when_employer_created_requested_escrow'] = 'PO FT - Escrow payment created successfully';//send by php

######## Realtime notification and activity log message when employee cancelled created escrow #######
$config['fulltime_project_realtime_notification_message_sent_to_employee_when_employee_cancelled_requested_escrow'] = 'SP FT - Escrow payment successfully cancelled.';// send by php

$config['fulltime_project_realtime_notification_message_sent_to_employee_when_employee_cancelled_active_escrow'] = "SP Fulltime:Active escrow successfully cancelled"; // By php

######### Activity log message when employee cancelled active escrow payment created by employer ##########
// For employee
$config['fulltime_project_message_sent_to_employee_when_employee_cancelled_active_escrow_created_by_employer_user_activity_log_displayed_message'] = 'you cancelled the escrow payment of <span>{fulltime_project_cancelled_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';



// For employer
$config['fulltime_project_message_sent_to_employer_when_employee_male_cancelled_active_escrow_created_by_employer_user_activity_log_displayed_message'] = 'Male:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> cancelled escrow payment of <span>{fulltime_project_cancelled_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employer_when_employee_female_cancelled_active_escrow_created_by_employer_user_activity_log_displayed_message'] = 'Female:<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> cancelled escrow payment of <span>{fulltime_project_cancelled_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employer_when_employee_company_app_male_cancelled_active_escrow_created_by_employer_user_activity_log_displayed_message'] = 'App Male: <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> cancelled escrow payment of <span>{fulltime_project_cancelled_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employer_when_employee_company_app_female_cancelled_active_escrow_created_by_employer_user_activity_log_displayed_message'] = 'App Female: <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> cancelled escrow payment of <span>{fulltime_project_cancelled_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employer_when_employee_company_cancelled_active_escrow_created_by_employer_user_activity_log_displayed_message'] = 'Company:<a href="{sp_profile_url_link}" target="_blank">{company_name}</a> cancelled escrow payment of <span>{fulltime_project_cancelled_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

####### Realtime notification and activity log message when employee sent request for release #######
$config['fulltime_project_realtime_notification_message_sent_to_employee_when_employee_requested_escrow_release'] = 'FT - Escrow request release sent successfully to <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>'; // send by php

$config['fulltime_project_message_sent_to_employer_when_employee_male_requested_active_escrow_release_user_activity_log_displayed_message'] = 'Male : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> request to release escrow payment of value <span>{fulltime_project_request_release_escrow_value}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employer_when_employee_female_requested_active_escrow_release_user_activity_log_displayed_message'] = 'Female : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> request to release escrow payment of value <span>{fulltime_project_request_release_escrow_value}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employer_when_employee_company_app_male_requested_active_escrow_release_user_activity_log_displayed_message'] = 'App Male : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> request to release escrow payment of value <span>{fulltime_project_request_release_escrow_value}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employer_when_employee_company_app_female_requested_active_escrow_release_user_activity_log_displayed_message'] = 'App Female : <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> request to release escrow payment of value <span>{fulltime_project_request_release_escrow_value}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employer_when_employee_company_requested_active_escrow_release_user_activity_log_displayed_message'] = 'Company : <a href="{sp_profile_url_link}" target="_blank">{company_name}</a> request to release escrow payment of value <span>{fulltime_project_request_release_escrow_value}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employee_when_employee_requested_active_escrow_release_user_activity_log_displayed_message'] = 'you requested to <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> to release the escrow of value <span>{fulltime_project_request_release_escrow_value}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employee_when_employer_male_released_escrow_user_activity_log_displayed_message'] = 'Male : <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> released escrow payment for your of <span>{fulltime_project_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employee_when_employer_female_released_escrow_user_activity_log_displayed_message'] = 'Female : <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> released escrow payment of <span>{fulltime_project_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employee_when_employer_company_app_male_released_escrow_user_activity_log_displayed_message'] = 'App Male : <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> released escrow payment for your of <span>{fulltime_project_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';
$config['fulltime_project_message_sent_to_employee_when_employer_company_app_female_released_escrow_user_activity_log_displayed_message'] = 'App Female : <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> released escrow payment of <span>{fulltime_project_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employee_when_employer_company_released_escrow_user_activity_log_displayed_message'] = 'Company : <a href="{po_profile_url_link}" target="_blank">{company_name}</a> released escrow payment of <span>{fulltime_project_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employer_when_employer_released_escrow_user_activity_log_displayed_message'] = 'You have successfully released escrow of value of <span>{fulltime_project_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>" for <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>';

$config['fulltime_project_message_sent_to_employee_when_employer_male_partially_released_escrow_user_activity_log_displayed_message'] = 'Male : <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> released partial escrow payment of <span>{fulltime_project_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';
$config['fulltime_project_message_sent_to_employee_when_employer_female_partially_released_escrow_user_activity_log_displayed_message'] = 'Female : <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> released partial escrow payment of <span>{fulltime_project_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employee_when_employer_company_app_male_partially_released_escrow_user_activity_log_displayed_message'] = 'App Male : <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> released partial escrow payment of <span>{fulltime_project_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employee_when_employer_company_app_female_partially_released_escrow_user_activity_log_displayed_message'] = 'App Female : <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> released partial escrow payment of <span>{fulltime_project_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employee_when_employer_company_partially_released_escrow_user_activity_log_displayed_message'] = 'Company : <a href="{po_profile_url_link}" target="_blank">{company_name}</a> released partial escrow payment of <span>{fulltime_project_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_message_sent_to_employer_when_employer_partially_released_escrow_user_activity_log_displayed_message'] = 'You have successfully released partial escrow of value of <span>{fulltime_project_escrow_amount}</span> on fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>" for <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>';

//$config['fulltime_project_realtime_notification_message_sent_to_employer_when_employer_rejected_escrow_creation_request'] = 'You rejected the escrow creation request coming from <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>';// send by php

############ Employer create escrow form #######
$config['project_details_page_fulltime_project_create_milestone_form_heading_employer_view'] = 'Create Salary Escrow For <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name_or_company_name}</a>';

$config['project_details_page_fulltime_project_description_create_milestone_form_employer_view'] = 'CM PO - Description-FT';

$config['project_details_page_fulltime_project_amount_create_milestone_form_employer_view'] = 'CM PO - Salary Amount - FT';

$config['project_details_page_fulltime_project_business_service_fee_create_milestone_form_employer_view'] = 'CM PO - Bussiness Service Fee-FT';

$config['project_details_page_fulltime_project_total_amount_create_milestone_milestone_form_employer_view'] = 'Total Amount Salary-FT';

$config['project_details_page_fulltime_project_create_milestone_form_tooltip_message_description_employer_view'] = 'Fulltime Description-FT';

$config['project_details_page_fulltime_project_create_milestone_form_tooltip_message_amount_employer_view'] = 'Fulltime Salary - FT';

$config['project_details_page_fulltime_project_create_milestone_form_tooltip_message_business_service_fee_employer_view'] = 'cm po - Bussiness Service Fee-FT';

$config['project_details_page_fulltime_project_create_escrow_form_dynamic_tooltip_message_service_fee_employer_view'] = 'your current membership {employer_membership_plan_name} - service fees charges - {fulltime_service_fees_charges}';

//message for initial view for po view in payment tab
$config['project_details_page_fulltime_project_create_milestone_form_create_milestone_payment_button_txt_employer_view'] = 'Create Milestone Payment Salary';

$config['project_details_page_fulltime_project_create_milestone_form_create_milestone_button_txt_employer_view'] = 'Create Escrow For Salary-FT';

$config['project_details_page_fulltime_project_create_milestone_form_cancel_button_txt_employer_view'] = 'Cancel-FT';

// config variables for validation messages on create milestone request form for SP view
$config['project_details_page_escrow_amount_validation_fulltime_project_escrow_form_message'] = 'Fulltime Escrow amount is required';
$config['project_details_page_invalid_escrow_amount_validation_fulltime_project_escrow_form_message'] = 'Invalid Escrow amount.';

$config['project_details_page_partial_escrow_greater_then_amount_validation_fulltime_project_escrow_form_message'] = 'Partial salary should be less then created salary value.'; 

##### this config variables for drop down option regarding requested/escrow/released milestone section
$config['fulltime_project_requested_escrow_section_option_cancel_employee_view'] = "po ft - Cancel Requested Milestone";
$config['fulltime_project_requested_escrow_section_option_reject_employer_view'] = "po ft - Reject Requested Milestone";
$config['fulltime_project_requested_escrow_section_option_pay_employer_view'] = "po ft - Create Milestone Payment";
$config['fulltime_project_active_escrow_section_option_release_employer_view'] = "po ft - Release Salary";
$config['fulltime_project_active_escrow_section_option_partial_release_employer_view'] = "po ft - Partial Release Salary";
$config['fulltime_project_active_escrow_section_option_cancel_employee_view'] = "sp ft - Cancel Salary";
$config['fulltime_project_active_escrow_section_option_request_release_employee_view'] = "sp ft - Request for release";

############### config variable for confirmation modal when Employee cancel the requested milestone#####
$config['project_details_page_employee_view_cancel_invalid_requested_escrow_fulltime_project'] = "sp ft - You cannot cancel the requested escrow because request is invalid for fulltime";

$config['cancel_requested_escrow_confirmation_fulltime_project_modal_body'] = 'sp ft - Are you sure you want to cancel the requested escrow of fulltime project?';

$config['cancel_requested_escrow_confirmation_fulltime_project_modal_cancel_btn_txt'] = 'sp ft - Cancel Requested escrow';

############### config variable for confirmation modal when employer rejected the requested escrow #####
$config['reject_requested_escrow_confirmation_fulltime_project_modal_body'] = 'PO - Are you sure you want to Reject the requested escrow of fulltime project?';

$config['reject_requested_escrow_confirmation_fulltime_project_modal_reject_btn_txt'] = 'PO - FT Reject Requested Escrow';

$config['project_details_page_employer_view_reject_invalid_requested_escrow_fulltime_project'] = "PO FT - You cannot reject the requested escrow because request is invalid for fulltime";

########## config variable for confirmation modal when PO created requested milestone#####
$config['create_requested_escrow_confirmation_fulltime_project_modal_body'] = 'PO FT - Are you sure you want to create the requested escrow of fulltime project?';
$config['create_requested_escrow_confirmation_fulltime_project_modal_confirm_btn_txt'] = 'PO FT - Create Requested Escrow';
$config['project_details_page_employer_view_create_invalid_requested_escrow_fulltime_project'] = "PO FT - You cannot create the requested escrow because request is invalid for fulltime";

$config['project_details_page_fulltime_project_create_requested_escrow_tooltip_message_service_fee_charges_employer_view'] = 'PO FT - Your current membership {employer_membership_subscription_name} - service charges - {fulltime_service_fee_charges}';

$config['create_requested_escrow_confirmation_fulltime_project_modal_requested_escrow_txt_employer_view'] = 'PO Requested Escrow-FT:';

$config['create_requested_escrow_confirmation_fulltime_project_modal_service_fee_txt_employer_view'] = 'PO Service Fees Charges-FT:';

$config['create_requested_escrow_confirmation_fulltime_project_modal_total_requested_escrow_txt_employer_view'] = 'PO Total-FT:';

########## config variable for confirmation modal when employer released escrow #####
$config['release_escrow_confirmation_fulltime_project_modal_confirm_btn_txt'] = 'PO FT Release Escrow';

$config['project_details_page_employer_view_invalid_active_escrow_fulltime_project'] = "PO FT - You cannot release escrow because request is invalid for fulltime";

########## config variable for confirmation modal when employer partial release escrow #####
$config['partial_release_escrow_confirmation_fulltime_project_modal_confirm_btn_txt'] = 'PO FT - Partial Release Escrow';

############### config variable for confirmation modal when SP cancel the milestone which is created by PO #####
$config['project_details_page_employee_view_cancel_invalid_escrow_fulltime_project'] = "SP FT - You cannot cancel the escrow because request is invalid for fulltime";

$config['cancel_escrow_confirmation_fulltime_project_modal_body'] = 'SP FT - Are you sure you want to cancel the escrow of fulltime project?';

$config['cancel_escrow_confirmation_fulltime_project_modal_cancel_btn_txt'] = 'SP FT - Cancel Escrow';

############### config variable for confirmation modal when employee make request release for the escrow which is created by employer #####
$config['request_release_escrow_confirmation_fulltime_project_modal_body'] = 'SP FT - Are you sure you want to make request for release the escrow of fulltime project?';

$config['request_release_escrow_confirmation_fulltime_project_modal_request_release_btn_txt'] = 'SP FT - Request For Release';

$config['project_details_page_employee_view_request_release_invalid_escrow_fulltime_project'] = "SP FT - You cannot make request for release the escrow because request is invalid fulltime.";
