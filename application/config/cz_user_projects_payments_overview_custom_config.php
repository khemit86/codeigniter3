<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//Left navigation Menu name
$config['projects_left_nav_payments_overview'] = 'Přehled plateb';

//page heading
$config['user_projects_payments_overview_page_headline_title'] = 'Přehled plateb';

//Meta Tag
$config['user_projects_payments_overview_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Přehled plateb';

//Description Meta Tag
$config['user_projects_payments_overview_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Přehled plateb';

//project owner checkbox
$config['user_projects_payments_overview_page_project_owner'] = 'Zadavatel & Zaměstnavatel';

//service provider checkbox
$config['user_projects_payments_overview_page_checkbox_service_provider'] = 'Poskytovatel & Zaměstnanec';

//url
$config['user_projects_payments_overview_page_url'] = 'prehled-plateb';


$config['user_projects_payments_overview_page_reset_field_btn_txt'] = ' Smazat Pole';

// Confif varable regarding the po/sp view payments tabs
//For PO View
$config['user_projects_payments_overview_page_incoming_payment_requests_tab_po_view'] = 'Příchozí žádosti o platbu';
$config['user_projects_payments_overview_page_outgoing_escrowed_payments_tab_po_view'] = 'Rezervace platby';
$config['user_projects_payments_overview_page_cancelled_escrowed_payments_tab'] = 'Zrušené rezervační platby';
$config['user_projects_payments_overview_page_released_payments_tab_po_view'] = 'Provedené platby';
$config['user_projects_payments_overview_page_rejected_payment_requests_tab_po_view'] = 'Zamítnuté žádosti o platbu';

//For SP view
$config['user_projects_payments_overview_page_sent_payment_requests_tab_sp_view'] = 'Odeslání žádosti o platbu';
$config['user_projects_payments_overview_page_incoming_escrowed_payments_tab_sp_view'] = 'Příchozí rezervační platby';
$config['user_projects_payments_overview_page_received_payments_tab_sp_view'] = 'Příchozí platby';
$config['user_projects_payments_overview_page_rejected_payment_requests_tab_sp_view'] = 'Zamítnuté žádosti o platbu';

// Initial view message for po view on projects payment overvie page
$config['no_published_project_message_po_view'] = "momentálně není žádný inzerát otevřený";

$config['no_financial_activity_on_published_project_message_po_view_singular'] = "momentálně nemáte žádné finanční aktivity";

$config['no_financial_activity_on_published_projects_message_po_view_plural'] = "momentálně nemáte žádné finanční aktivity";

// Initial view message for sp view on projects payment overvie page
$config['no_project_message_sp_view'] = "momentálně nemáte žádné finanční aktivity";

$config['no_financial_activity_on_project_message_sp_view_singular'] = "momentálně nemáte žádné finanční aktivity";

$config['no_financial_activity_on_projects_message_sp_view_plural'] = "momentálně nemáte žádné finanční aktivity";

$config['user_projects_payments_overview_page_paging_url'] = 'strk';


//////////// config for requested payment tab for PO view
// If po[po view] not select any project from project drop down and there is no incoming payment request then config are using.
//$config['user_projects_payments_overview_page_no_incoming_payment_request_message_po_view'] = "You do not currently have any incoming payment request.PO";
$config['user_projects_payments_overview_page_no_incoming_payment_request_message_po_view'] = "momentálně nejsou přijímané žádné žádosti";

// If po[po view] select the project from project drop down and selected project type is fixed/hourly and there is no incoming payment request then config are using.
$config['user_projects_payments_overview_page_no_incoming_payment_request_project_message_po_view'] = "nemáte žádnou žádost o platbu";

// If po[po view] select the project from project drop down and selected project type is fulltime and there is no incoming payment request then config are using.
$config['user_projects_payments_overview_page_no_incoming_payment_request_fulltime_project_message_po_view'] = "nemáte žádné příchozí žádosti o platbu";

/////////// config for requested payment tab for sp view
// If sp[sp view] not select any project from project drop down and there is no incoming payment request then config are using.
$config['user_projects_payments_overview_page_no_sent_payment_request_message_sp_view'] = "momentálně nejsou odeslané žádné žádosti";

// If sp[sp view] select the project from project drop down and selected project type is fixed/hourly and there is no incoming payment request then config are using.
$config['user_projects_payments_overview_page_no_sent_payment_request_project_message_sp_view'] = "nemáte žádnou žádost o platbu";

// If sp[sp view] select the project from project drop down and selected project type is fulltime and there is no incoming payment request then config are using.
$config['user_projects_payments_overview_page_no_sent_payment_request_fulltime_project_message_sp_view'] = "nemáte žádné odeslané žádosti o platbu";

/////////// config for outgoing payment tab for PO view
// If po[po view] not select any project from project drop down and there is no outgoing payment then config are using.
$config['user_projects_payments_overview_page_no_outgoing_payment_message_po_view'] = "momentálně nejsou žádné odchozí rezervační platby";

// If po[po view] select the project from project drop down and selected project type is fixed/hourly and there is no outgoing payment then config are using.
$config['user_projects_payments_overview_page_no_outgoing_payment_project_message_po_view'] = "nemáte žádné rezervace platby";

// If po[po view] select the project from project drop down and selected project type is fulltime and there is no outgoing payment then config are using.
$config['user_projects_payments_overview_page_no_outgoing_payment_fulltime_project_message_po_view'] = "nemáte vytvořené žádné rezervační platby";

//////////////config for incoming payment tab for sp view
// If sp[sp view] not select any project from project drop down and there is no incoming payment then config are using.
$config['user_projects_payments_overview_page_no_incoming_payment_message_sp_view'] = "momentálně nejsou žádné příchozí rezervační platby";

// If sp[sp view] select the project from project drop down and selected project type is fixed/hourly and there is no incoming payment then config are using.
$config['user_projects_payments_overview_page_incoming_payment_project_message_sp_view'] = "nemáte žádnou příchozí rezervační platbu";

// If sp[sp view] select the project from project drop down and selected project type is fulltime and there is no incoming payment then config are using.
$config['user_projects_payments_overview_page_incoming_payment_fulltime_project_message_sp_view'] = "nemáte vytvořené žádné rezervační platby";

///////////////// config for cancelled payment tab for PO view
// If po[po view] not select any project from project drop down and there is no cancelled payment then config are using.
$config['user_projects_payments_overview_page_no_cancelled_payment_creation_request_message_po_view'] = "momentálně není zrušená žádná rezervační platba";

// If po[po view] select the project from project drop down and selected project type is fixed/hourly and there is no cancelled payment then config are using.
$config['user_projects_payments_overview_page_no_cancelled_payment_creation_request_project_message_po_view'] = "nemáte žádné zrušené rezervační platby";

// If po[po view] select the project from project drop down and selected project type is fulltime and there is no cancelled payment then config are using.
$config['user_projects_payments_overview_page_no_cancelled_payment_creation_request_fulltime_project_message_po_view'] = "nemáte zrušené žádné rezervační platby";

/////////////// config for cancelled payment tab for SP view
// If sp[sp view] not select any project from project drop down and there is no cancelled payment then config are using.
$config['user_projects_payments_overview_page_no_cancelled_payment_creation_request_message_sp_view'] = "momentálně není zrušená žádná rezervační platba";

// If sp[sp view] select the project from project drop down and selected project type is fixed/hourly and there is no cancelled payment then config are using.
$config['user_projects_payments_overview_page_no_cancelled_payment_creation_project_message_sp_view'] = "nemáte žádné zrušené platby";

// If sp[sp view] select the project from project drop down and selected project type is fulltime and there is no cancelled payment then config are using.
$config['user_projects_payments_overview_page_no_cancelled_payment_creation_request_fulltime_project_message_sp_view'] = "nemáte zrušené žádné rezervační platby";

/////////////// config for released payment tab for po view
// If po[po view] not select any project from project drop down and there is no released payment then config are using.
$config['user_projects_payments_overview_page_no_released_payment_message_po_view'] = "momentálně není žádná provedená platba";

// If po[po view] select the project from project drop down and selected project type is fixed/hourly and there is no released payment then config are using.
$config['user_projects_payments_overview_page_no_released_payment_project_message_po_view'] = "nemáte žádné provedené platby";

// If po[po view] select the project from project drop down and selected project type is fulltime and there is no released payment then config are using.
$config['user_projects_payments_overview_page_no_released_payment_fulltime_project_message_po_view'] = "nemáte žádné provedené platby";

////////////////// config for recieved payment tab for sp view
// If sp[sp view] not select any project from project drop down and there is no recieved payment then config are using.
$config['user_projects_payments_overview_page_no_received_payment_message_sp_view'] = "momentálně není žádná příchozí platba";

// If sp[sp view] select the project from project drop down and selected project type is fixed/hourly and there is no recieved payment then config are using.
$config['user_projects_payments_overview_page_no_received_payment_project_message_sp_view'] = "nemáte žádné příchozí platby";

// If sp[sp view] select the project from project drop down and selected project type is fulltime and there is no recieved payment then config are using.
$config['user_projects_payments_overview_page_no_received_payment_fulltime_project_message_sp_view'] = "nemáte žádné provedené platby";

//////////////// config for rejected payment tab for po view
// If po[po view] not select any project from project drop down and there is no rejected payment then config are using.
$config['user_projects_payments_overview_page_no_rejected_requested_escrow_payment_creation_message_po_view'] = "momentálně nejsou žádné zamítnuté žádosti";

// If po[po view] select the project from project drop down and selected project type is fixed/hourly and there is no rejected payment then config are using.
$config['user_projects_payments_overview_page_no_rejected_requested_escrow_payment_creation_project_message_po_view'] = "nemáte žádné zamítnuté žádosti o platbu";

// If po[po view] select the project from project drop down and selected project type is fulltime and there is no rejected payment then config are using.
$config['user_projects_payments_overview_page_no_rejected_requested_escrow_payment_creation_fulltime_project_message_po_view'] = "nemáte žádné zamítnuté žádosti o platbu";

/////////////// config for rejected payment tab for sp view
// If sp[sp view] not select any project from project drop down and there is no rejected payment then config are using.
$config['user_projects_payments_overview_page_no_rejected_requested_escrow_payment_creation_message_sp_view'] = "momentálně nejsou žádné zamítnuté žádosti";

// If sp[sp view] select the project from project drop down and selected project type is fixed/hourly and there is no rejected payment then config are using.
$config['user_projects_payments_overview_page_no_rejected_requested_escrow_payment_creation_project_message_sp_view'] = "nemáte žádné zamítnuté žádosti o platbu";

// If sp[sp view] select the project from project drop down and selected project type is fulltime and there is no rejected payment then config are using.
$config['user_projects_payments_overview_page_no_rejected_requested_escrow_payment_creation_fulltime_project_message_sp_view'] = "nemáte žádné zamítnuté žádosti o platbu";


// Config are used on user projects payments overview page for both po/sp view for all payments tabs
$config['user_projects_payments_overview_page_project_title_txt'] = 'Projekt:';

$config['user_projects_payments_overview_page_fulltime_project_title_txt'] = 'Pracovní pozice:';

$config['user_projects_payments_overview_page_project_type_txt'] = 'Typ projektu:';

$config['user_projects_payments_overview_page_fixed_budget_project_type_txt'] = 'platba fixní';

$config['user_projects_payments_overview_page_hourly_rate_based_project_type_txt'] = 'platba za hodinu';

$config['user_projects_payments_overview_page_service_provider_name_txt'] = 'Poskytovatel:';

$config['user_projects_payments_overview_page_employee_name_txt'] = 'Zaměstnanec:';

$config['user_projects_payments_overview_page_project_owner_name_txt'] = 'Zadavatel:';

$config['user_projects_payments_overview_page_employer_name_txt'] = 'Zaměstnavatel:';

$config['user_projects_payments_overview_page_amount_txt'] = 'Částka:';
 

$config['user_projects_payments_overview_page_requested_amount_txt'] = 'Částka:';

$config['user_projects_payments_overview_page_requested_on_txt'] = 'Vytvořeno:';

$config['user_projects_payments_overview_page_rejected_payment_requests_tab_requested_on_txt'] = 'Žádost o platbu:';

$config['user_projects_payments_overview_page_description_txt'] = 'Popis:';

$config['user_projects_payments_overview_page_escrow_amount_txt'] = 'Výše rezervace:';

$config['user_projects_payments_overview_page_created_on_txt'] = 'Rezervace vytvořena:';

$config['user_projects_payments_overview_page_reverted_amount_txt'] = 'Vrácená rezervace:';

$config['user_projects_payments_overview_page_cancelled_escrowed_payments_tab_created_on_txt_po_view'] = "Rezervace vytvořena:";

$config['user_projects_payments_overview_page_cancelled_escrowed_payments_tab_created_on_txt_sp_view'] = "Rezervace vytvořena:";

$config['user_projects_payments_overview_page_cancelled_escrowed_payments_tab_cancelled_on_txt_po_view'] = " Zrušeno:";

$config['user_projects_payments_overview_page_cancelled_escrowed_payments_tab_cancelled_on_txt_sp_view'] = "Vráceno:";

$config['user_projects_payments_overview_page_paid_on_txt'] = 'Zaplaceno:';

$config['user_projects_payments_overview_page_rejected_on_txt'] = 'Odmítnuto:';


$config['user_projects_payments_overview_page_dispute_close_txt'] = 'CZ-Dispute close date:';
$config['user_projects_payments_overview_page_dispute_id_txt'] = 'CZ-Dispute Id:';


// For payment request tab on payment over view page 
$config['user_projects_payments_overview_page_incoming_payment_requests_tab_total_txt_po_view'] = 'Celkem (požadované částky):';
$config['user_projects_payments_overview_page_sent_payment_requests_tab_total_txt_sp_view'] = 'Celkem (požadované částky):';

// For payment escrowed payments tab on payment over view page 
$config['user_projects_payments_overview_page_outgoing_escrowed_payments_tab_total_txt_po_view'] = "Celkem (rezervované částky):";
$config['user_projects_payments_overview_page_incoming_escrowed_payments_tab_total_txt_sp_view'] = "Celkem (rezervované částky):";

// For cancelled payment tab on payment over view page 
$config['user_projects_payments_overview_page_cancelled_escrowed_payments_tab_total_txt_po_view'] = "Celkem (vrácené částky):";
$config['user_projects_payments_overview_page_cancelled_escrowed_payments_tab_total_txt_sp_view'] = "Celkem (zrušené platby):";

// For released payment tab on payment over view page 
$config['user_projects_payments_overview_page_released_payments_tab_total_paid_txt_po_view'] = "Celkem (provedené platby):";
$config['user_projects_payments_overview_page_received_payments_tab_total_received_txt_sp_view'] = "Celkem (přijaté platby):";

// For rejected payment requests tab on payment over view page 
$config['user_projects_payments_overview_page_rejected_payment_requests_tab_total_txt_po_view'] = "Celkem (zamítnuté žádosti):";
$config['user_projects_payments_overview_page_rejected_payment_requests_tab_total_txt_sp_view'] = "Celkem (zamítnuté žádosti):";


$config['user_projects_payments_overview_page_select_project_dropdown_option_txt'] = 'vybrat inzerát';

?>