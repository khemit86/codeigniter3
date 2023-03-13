<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//Left navigation Menu name
$config['projects_left_nav_payments_overview'] = 'Projects Payments Overview';

//page heading
$config['user_projects_payments_overview_page_headline_title'] = 'Projects Payments Overview';

//Meta Tag
$config['user_projects_payments_overview_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Payments Management';
//Description Meta Tag
$config['user_projects_payments_overview_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Payments Management';

//project owner checkbox
$config['user_projects_payments_overview_page_project_owner'] = 'As Project Owner';
//service provider checkbox
$config['user_projects_payments_overview_page_checkbox_service_provider'] = 'As Service Provider';

//url
$config['user_projects_payments_overview_page_url'] = 'projects-payments-overview';

$config['user_projects_payments_overview_page_reset_field_btn_txt'] = 'Reset Field';


// Confif varable regarding the po/sp view payments tabs

//For PO View
$config['user_projects_payments_overview_page_incoming_payment_requests_tab_po_view'] = 'po:Incoming Payment Requests';
$config['user_projects_payments_overview_page_outgoing_escrowed_payments_tab_po_view'] = 'po:Outgoing Escrowed Payments';
$config['user_projects_payments_overview_page_cancelled_escrowed_payments_tab'] = 'Cancelled Escrow Payments';
$config['user_projects_payments_overview_page_released_payments_tab_po_view'] = 'po:Released Payments';
$config['user_projects_payments_overview_page_rejected_payment_requests_tab_po_view'] = 'po:Rejected Payment Requests';

//For SP view
$config['user_projects_payments_overview_page_sent_payment_requests_tab_sp_view'] = 'sp:Sent Payment Requests';
$config['user_projects_payments_overview_page_incoming_escrowed_payments_tab_sp_view'] = 'sp:Incoming Escrowed Payments';
$config['user_projects_payments_overview_page_received_payments_tab_sp_view'] = 'sp:Received Payments';
$config['user_projects_payments_overview_page_rejected_payment_requests_tab_sp_view'] = 'sp:Rejected Payment Requests';



// Initial view message for po view on projects payment overvie page
$config['no_published_project_message_po_view'] = "You do not currently have any published project (po)";
$config['no_financial_activity_on_published_project_message_po_view_singular'] = "You do not any financial activity on project (po)";
$config['no_financial_activity_on_published_projects_message_po_view_plural'] = "You do not any financial activity on projects (po)";


// Initial view message for sp view on projects payment overvie page
$config['no_project_message_sp_view'] = "You do not currently have any activity on any project (sp)";
$config['no_financial_activity_on_project_message_sp_view_singular'] = "You do not currently have any financial activity on any project (sp)";
$config['no_financial_activity_on_projects_message_sp_view_plural'] = "You do not currently have any financial activity on any projects (sp)";

$config['user_projects_payments_overview_page_paging_url'] = 'page';


///////////// config for requested payment tab for PO view
// If po[po view] not select any project from project drop down and there is no incoming payment request then config are using.
$config['user_projects_payments_overview_page_no_incoming_payment_request_message_po_view'] = "You do not currently have any incoming payment request.PO";

// If po[po view] select the project from project drop down and selected project type is fixed/hourly and there is no incoming payment request then config are using.
$config['user_projects_payments_overview_page_no_incoming_payment_request_project_message_po_view'] = "You do not currently have any incoming payment request for project.PO";

// If po[po view] select the project from project drop down and selected project type is fulltime and there is no incoming payment request then config are using.
$config['user_projects_payments_overview_page_no_incoming_payment_request_fulltime_project_message_po_view'] = "You do not currently have any incoming payment request for fulltime project.PO";


/////////////////// config for requested payment tab for sp view
// If sp[sp view] not select any project from project drop down and there is no incoming payment request then config are using.
$config['user_projects_payments_overview_page_no_sent_payment_request_message_sp_view'] = "You do not currently sent any payment request.SP";

// If sp[sp view] select the project from project drop down and selected project type is fixed/hourly and there is no incoming payment request then config are using.
$config['user_projects_payments_overview_page_no_sent_payment_request_project_message_sp_view'] = "You do not have sent any payment request for project.SP";

// If sp[sp view] select the project from project drop down and selected project type is fulltime and there is no incoming payment request then config are using.
$config['user_projects_payments_overview_page_no_sent_payment_request_fulltime_project_message_sp_view'] = "You do not have any sent payment request for fulltime project.SP";


////////////// config for outgoing payment tab for PO view
// If po[po view] not select any project from project drop down and there is no outgoing payment then config are using.
$config['user_projects_payments_overview_page_no_outgoing_payment_message_po_view'] = "You do not currently have any outgoing request.PO";

// If po[po view] select the project from project drop down and selected project type is fixed/hourly and there is no outgoing payment then config are using.
$config['user_projects_payments_overview_page_no_outgoing_payment_project_message_po_view'] = "You do not currently have any outgoing payment for project.PO";

// If po[po view] select the project from project drop down and selected project type is fulltime and there is no outgoing payment then config are using.
$config['user_projects_payments_overview_page_no_outgoing_payment_fulltime_project_message_po_view'] = "You do not currently have any outgoing payment for fulltime project.PO";

/////////////// config for incoming payment tab for sp view
// If sp[sp view] not select any project from project drop down and there is no incoming payment then config are using.
$config['user_projects_payments_overview_page_no_incoming_payment_message_sp_view'] = "You do not have incoming payment.SP";

// If sp[sp view] select the project from project drop down and selected project type is fixed/hourly and there is no incoming payment then config are using.
$config['user_projects_payments_overview_page_incoming_payment_project_message_sp_view'] = "You do not have incoming payment for project.SP";

// If sp[sp view] select the project from project drop down and selected project type is fulltime and there is no incoming payment then config are using.
$config['user_projects_payments_overview_page_incoming_payment_fulltime_project_message_sp_view'] = "You do not have incoming payment for fulltime project.SP";


////////////////// config for cancelled payment tab for PO view
// If po[po view] not select any project from project drop down and there is no cancelled payment then config are using.
$config['user_projects_payments_overview_page_no_cancelled_payment_creation_request_message_po_view'] = "You do not currently have any cancelled Escrow payment. PO";

// If po[po view] select the project from project drop down and selected project type is fixed/hourly and there is no cancelled payment then config are using.
$config['user_projects_payments_overview_page_no_cancelled_payment_creation_request_project_message_po_view'] = "You do not currently have any cancelled Escrow payment for project.PO";

// If po[po view] select the project from project drop down and selected project type is fulltime and there is no cancelled payment then config are using.
$config['user_projects_payments_overview_page_no_cancelled_payment_creation_request_fulltime_project_message_po_view'] = "You do not currently have any cancelled Escrow payment fulltime project.PO";


/////////config for cancelled payment tab for SP view
// If sp[sp view] not select any project from project drop down and there is no cancelled payment then config are using.
$config['user_projects_payments_overview_page_no_cancelled_payment_creation_request_message_sp_view'] = "You do not currently have any cancelled Escrow payment. SP";

// If sp[sp view] select the project from project drop down and selected project type is fixed/hourly and there is no cancelled payment then config are using.
$config['user_projects_payments_overview_page_no_cancelled_payment_creation_project_message_sp_view'] = "You do not currently have any cancelled Escrow payment for project.SP";

// If sp[sp view] select the project from project drop down and selected project type is fulltime and there is no cancelled payment then config are using.
$config['user_projects_payments_overview_page_no_cancelled_payment_creation_request_fulltime_project_message_sp_view'] = "You do not currently have any cancelled Escrow payment fulltime project.SP";


////////////// config for released payment tab for po view
// If po[po view] not select any project from project drop down and there is no released payment then config are using.
$config['user_projects_payments_overview_page_no_released_payment_message_po_view'] = "You do not currently have any released payment.Po";

// If po[po view] select the project from project drop down and selected project type is fixed/hourly and there is no released payment then config are using.
$config['user_projects_payments_overview_page_no_released_payment_project_message_po_view'] = "You do not currently have any released payment for project.Po";

// If po[po view] select the project from project drop down and selected project type is fulltime and there is no released payment then config are using.
$config['user_projects_payments_overview_page_no_released_payment_fulltime_project_message_po_view'] = "You do not currently have any released fulltime payment.PO";


/////////// config for recieved payment tab for sp view
// If sp[sp view] not select any project from project drop down and there is no recieved payment then config are using.
$config['user_projects_payments_overview_page_no_received_payment_message_sp_view'] = "You do not currently have any received payment.SP";

// If sp[sp view] select the project from project drop down and selected project type is fixed/hourly and there is no recieved payment then config are using.
$config['user_projects_payments_overview_page_no_received_payment_project_message_sp_view'] = "You do not currently have any received payment for projectSP";

// If sp[sp view] select the project from project drop down and selected project type is fulltime and there is no recieved payment then config are using.
$config['user_projects_payments_overview_page_no_received_payment_fulltime_project_message_sp_view'] = "You do not currently have any received payment for fulltime projectSP";


// config for rejected payment tab for po view
// If po[po view] not select any project from project drop down and there is no rejected payment then config are using.
$config['user_projects_payments_overview_page_no_rejected_requested_escrow_payment_creation_message_po_view'] = "You do not currently have any rejected requested payment. PO";

// If po[po view] select the project from project drop down and selected project type is fixed/hourly and there is no rejected payment then config are using.
$config['user_projects_payments_overview_page_no_rejected_requested_escrow_payment_creation_project_message_po_view'] = "You do not currently have any rejected requested payment for project. PO";

// If po[po view] select the project from project drop down and selected project type is fulltime and there is no rejected payment then config are using.
$config['user_projects_payments_overview_page_no_rejected_requested_escrow_payment_creation_fulltime_project_message_po_view'] = "You do not currently have any rejected requested payment for fulltime project. PO";


// config for rejected payment tab for sp view
// If sp[sp view] not select any project from project drop down and there is no rejected payment then config are using.
$config['user_projects_payments_overview_page_no_rejected_requested_escrow_payment_creation_message_sp_view'] = "You do not currently have any rejected requested payment. SP";

// If sp[sp view] select the project from project drop down and selected project type is fixed/hourly and there is no rejected payment then config are using.
$config['user_projects_payments_overview_page_no_rejected_requested_escrow_payment_creation_project_message_sp_view'] = "You do not currently have any rejected requested payment for project. SP";

// If sp[sp view] select the project from project drop down and selected project type is fulltime and there is no rejected payment then config are using.
$config['user_projects_payments_overview_page_no_rejected_requested_escrow_payment_creation_fulltime_project_message_sp_view'] = "You do not currently have any rejected requested payment for fulltime project. SP";


// Config are used on user projects payments overview page for both po/sp view for all payments tabs
$config['user_projects_payments_overview_page_project_title_txt'] = 'Project Title:';
$config['user_projects_payments_overview_page_fulltime_project_title_txt'] = 'Fulltime Project Title:';

$config['user_projects_payments_overview_page_project_type_txt'] = 'Project type:';
$config['user_projects_payments_overview_page_fixed_budget_project_type_txt'] = 'Fixed Budget';
$config['user_projects_payments_overview_page_hourly_rate_based_project_type_txt'] = 'Hourly';

$config['user_projects_payments_overview_page_service_provider_name_txt'] = 'Service Provider Name:';
$config['user_projects_payments_overview_page_employee_name_txt'] = 'Employee Name:';

$config['user_projects_payments_overview_page_project_owner_name_txt'] = 'Project Owner Name:';
$config['user_projects_payments_overview_page_employer_name_txt'] = 'Employer Name:';

$config['user_projects_payments_overview_page_amount_txt'] = 'Amount:';

$config['user_projects_payments_overview_page_requested_on_txt'] = 'Requested On:';
$config['user_projects_payments_overview_page_rejected_payment_requests_tab_requested_on_txt'] = 'Requested On:';

$config['user_projects_payments_overview_page_requested_amount_txt'] = 'Requested Amount:';
$config['user_projects_payments_overview_page_escrow_amount_txt'] = 'Escrowed Amount:';
$config['user_projects_payments_overview_page_created_on_txt'] = 'Created On:';


$config['user_projects_payments_overview_page_cancelled_escrowed_payments_tab_created_on_txt_sp_view'] = "sp->Incoming escrow payment created on:"; 
$config['user_projects_payments_overview_page_cancelled_escrowed_payments_tab_created_on_txt_po_view'] = "po->Outgoing escrow payment created on:"; 

$config['user_projects_payments_overview_page_cancelled_escrowed_payments_tab_cancelled_on_txt_po_view'] = "po->Cancelled On:";
$config['user_projects_payments_overview_page_cancelled_escrowed_payments_tab_cancelled_on_txt_sp_view'] = "sp->Cancelled On:";



$config['user_projects_payments_overview_page_dispute_close_txt'] = 'Dispute close date:';
$config['user_projects_payments_overview_page_dispute_id_txt'] = 'Dispute ID:';
$config['user_projects_payments_overview_page_rejected_on_txt'] = 'Rejected On:';
$config['user_projects_payments_overview_page_description_txt'] = 'Description:';
$config['user_projects_payments_overview_page_reverted_amount_txt'] = 'Reverted Amount:';
$config['user_projects_payments_overview_page_paid_on_txt'] = 'Paid On:';


// For payment request tab on payment over view page 
$config['user_projects_payments_overview_page_incoming_payment_requests_tab_total_txt_po_view'] = '[request]:po:Total:';
$config['user_projects_payments_overview_page_sent_payment_requests_tab_total_txt_sp_view'] = '[request]:sp:Total:';

// For payment escrowed payments tab on payment over view page 
$config['user_projects_payments_overview_page_outgoing_escrowed_payments_tab_total_txt_po_view'] = "[escrow]:po:Total:";
$config['user_projects_payments_overview_page_incoming_escrowed_payments_tab_total_txt_sp_view'] = "[escrow]:sp:Total:";

// For cancelled payment tab on payment over view page 

$config['user_projects_payments_overview_page_cancelled_escrowed_payments_tab_total_txt_po_view'] = "[cancelled]:po:Total:";
$config['user_projects_payments_overview_page_cancelled_escrowed_payments_tab_total_txt_sp_view'] = "[cancelled]:sp:Total:";

// For released payment tab on payment over view page 
$config['user_projects_payments_overview_page_released_payments_tab_total_paid_txt_po_view'] = "[released]po:Total Released:";
$config['user_projects_payments_overview_page_received_payments_tab_total_received_txt_sp_view'] = "[released]sp:Total Recieved:";

// For rejected payment requests tab on payment over view page 
$config['user_projects_payments_overview_page_rejected_payment_requests_tab_total_txt_po_view'] = "[rejected_request]po:Total:";
$config['user_projects_payments_overview_page_rejected_payment_requests_tab_total_txt_sp_view'] = "[rejected_request]sp:Total:";

$config['user_projects_payments_overview_page_select_project_dropdown_option_txt'] = '--Select Project--';

?>