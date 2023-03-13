<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
################ Url Routing Variables for payments section on project detail page ###########
//$config['project_detail_page_payments_section_paging_url'] = 'load_payments';
$config['project_detail_page_payments_section_paging_url'] = 'nacitani_platby';


$config['project_details_page_project_create_escrow_request_form_request_payment_button_txt_sp_view'] = 'Žádost o platbu';

$config['project_details_page_project_create_escrow_request_form_heading_sp_view'] = 'Vytvoření žádosti pro <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name_or_company_name}</a>';

$config['project_details_page_project_create_escrow_request_form_create_escrow_request_button_txt_sp_view'] = 'Žádost o platbu';

//message for initial view for sp view in payment tab
$config['project_details_page_project_description_create_escrow_request_form_sp_view'] = 'Popis platby';

$config['project_details_page_project_amount_create_escrow_request_form_sp_view'] = 'Částka';

############ for create escrow by PO #######
$config['project_details_page_project_create_escrow_form_heading_po_view'] = 'Vytvoření platby pro <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name_or_company_name}</a>';

$config['project_details_page_project_description_create_escrow_form_po_view'] = 'Popis platby';

$config['project_details_page_project_amount_create_escrow_form_po_view'] = 'Částka';

$config['project_details_page_project_business_service_fee_create_escrow_form_po_view'] = 'Poplatek';

$config['project_details_page_project_total_amount_create_escrow_form_po_view'] = 'Celkem';

//message for initial view for po view in payment tab
$config['project_details_page_project_create_escrow_form_create_escrow_payment_button_txt_po_view'] = 'Vytvořit rezervaci pro platbu';

$config['project_details_page_project_create_escrow_form_create_escrow_button_txt_po_view'] = 'Vytvořit rezervaci';

// config variable for validation messages on create escrow request form for PO view when PO has not (enough) funds available to create escrow payment
$config['project_details_page_po_not_sufficient_balance_validation_project_escrow_form_message'] = 'nízký zůstatek na účtu pro vytvoření platby';

##### this config variables are used when there is no requested/escrow/released escrow
$config['no_sent_payment_request_message_sp_view'] = "momentálně nejsou odeslané žádné žádosti";


$config['no_incoming_payment_request_message_po_view'] = "momentálně nejsou přijímané žádné žádosti";
$config['no_incoming_escrow_payment_message_sp_view'] = "momentálně nejsou žádné příchozí rezervační platby";


$config['no_outgoing_escrow_payment_message_po_view'] = "momentálně nejsou žádné odchozí rezervační platby";


$config['no_cancelled_escrow_creation_request_message_sp_view'] = "momentálně není zrušená žádná rezervační platba";
$config['no_cancelled_escrow_creation_request_message_po_view'] = "momentálně není zrušená žádná rezervační platba";

//--------

$config['no_received_escrow_payment_message_sp_view'] = "momentálně nejsou žádné příchozí platby";
$config['no_released_escrow_payment_message_po_view'] = "momentálně nejsou žádné odchozí platby";

//--------

$config['no_rejected_requested_escrow_creation_message_sp_view'] = "momentálně nejsou žádné zamítnuté žádosti";
$config['no_rejected_requested_escrow_creation_message_po_view'] = "momentálně nejsou žádné zamítnuté žádosti";

##### this config variables for drop down option regarding requested/escrow/released escrow section
$config['project_requested_escrow_section_option_cancel_sp_view'] = "Zrušit žádost";
$config['project_requested_escrow_section_option_reject_po_view'] = "Odmítnout žádost";
$config['project_requested_escrow_section_option_pay_po_view'] = "Vytvořit rezervaci";

$config['project_active_escrow_section_option_release_po_view'] = "Zaplatit";
$config['project_active_escrow_section_option_partial_release_po_view'] = "Částečně zaplatit";
$config['project_active_escrow_section_option_cancel_sp_view'] = "Zrušit rezervaci";
$config['project_active_escrow_section_option_request_release_sp_view'] = "Žádost o zaplacení";

#####config variable when SP make request for escrow
//TRANSLATED BUT NOT TESTED - 01.05.2020
$config['project_details_page_sp_view_create_escrow_request_deleted_project'] = 'Inzerát byl smazán. Nelze provést tuto volbu.';

############### config variable for confirmation modal when SP cancel the requested escrow#####
//TRANSLATED BUT NOT TESTED - 01.05.2020
$config['project_details_page_sp_view_cancel_requested_escrow_deleted_project'] = "Inzerát byl smazán. Nelze provést tuto volbu.";

//TRANSLATED BUT NOT TESTED - 01.05.2020
$config['project_details_page_sp_view_cancel_invalid_requested_escrow_project'] = "Stav žádosti byl změněn. Nelze provést tuto volbu.";

$config['cancel_requested_escrow_confirmation_project_modal_body'] = 'Opravdu chcete zrušit žádost o <span class="display-inline-block">platbu ?</span>';

$config['cancel_requested_escrow_confirmation_project_modal_cancel_btn_txt'] = 'Zrušit žádost';

############### config variable for confirmation modal when PO rejected the requested escrow#####
$config['reject_requested_escrow_confirmation_project_modal_body'] = 'Opravdu chcete odmítnout <span class="display-inline-block">žádost?</span>';

$config['reject_requested_escrow_confirmation_project_modal_reject_btn_txt'] = 'Odmítnout žádost';

//TRANSLATED BUT NOT TESTED - 01.05.2020
$config['project_details_page_po_view_reject_requested_escrow_deleted_project'] = "Inzerát byl smazán. Nelze provést tuto volbu.";

$config['project_details_page_po_view_reject_invalid_requested_escrow_project'] = "Stav žádosti byl změněn. Nelze provést tuto volbu.";

########## config variable for confirmation modal when PO created requested escrow#####
$config['create_requested_escrow_confirmation_project_modal_body'] = 'Pokračováním vytvoříte rezervaci';

$config['create_requested_escrow_confirmation_project_modal_confirm_btn_txt'] = 'Vytvořit rezervaci';

//TRANSLATED BUT NOT TESTED - 01.05.2020
$config['project_details_page_po_view_create_invalid_requested_escrow_project'] = "Stav žádosti byl změněn. Nelze provést tuto volbu.";

//TRANSLATED BUT NOT TESTED - 01.05.2020
$config['project_details_page_po_view_create_requested_escrow_deleted_project'] = "Inzerát byl smazán. Nelze provést tuto volbu.";

########## config variable for PO create escrow #####
$config['project_details_page_po_view_create_escrow_deleted_project'] = "PO - Inzerát byl smazán. Nelze provést tuto volbu.";

########## config variable for confirmation modal when PO released escrow#####
//release_escrow_amount_project_modal_body_txt - 
// for fixed budget
$config['fixed_budget_project_release_escrow_confirmation_project_modal_body'] = 'Pokračováním uvolníte platbu ve výši <span class="touch_line_break">{fixed_budget_project_release_escrow_amount}</span> pro <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name_or_company_name}</a>.';

// for hourly
$config['hourly_rate_based_project_release_escrow_confirmation_project_modal_body'] = 'Pokračováním uvolníte platbu za {number_of_hours} odpracovaných hodin s hodinovou sazbou <span class="touch_line_break">{hourly_rate}</span> ve výši <span class="touch_line_break">{hourly_rate_based_project_total_release_escrow_amount}</span> pro <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name_or_company_name}</a>.';

// for fulltime
$config['fulltime_project_release_escrow_confirmation_project_modal_body'] = 'Pokračováním uvolníte platbu ve výši <span class="touch_line_break">{fulltime_project_release_escrow_amount}</span> pro <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name_or_company_name}</a>.';


$config['release_escrow_confirmation_project_modal_confirm_btn_txt'] = 'Provést platbu';

$config['project_details_page_po_view_invalid_active_escrow_project'] = "Nelze provést platbu. Proces není platný.";

//TRANSLATED BUT NOT TESTED - 01.05.2020
$config['project_details_page_po_view_release_escrow_deleted_project'] = "Inzerát byl smazán. Nelze provést tuto volbu.";

########## config variable for confirmation modal when PO partial release escrow#####
//TRANSLATED BUT NOT TESTED - 01.05.2020
$config['fixed_budget_project_partial_release_escrow_confirmation_project_modal_available_escrowed_amount_txt'] = 'Výše rezervace';

$config['fulltime_project_partial_release_escrow_confirmation_project_modal_available_escrowed_amount_txt'] = 'Výše rezervace';

$config['partial_release_escrow_confirmation_project_modal_confirm_btn_txt'] = 'Provést platbu';

############### config variable for confirmation modal when SP cancel the escrow which is created by PO #####
//TRANSLATED BUT NOT TESTED - 01.05.2020
$config['project_details_page_sp_view_cancel_escrow_deleted_project'] = "Inzerát byl smazán. Nelze provést tuto volbu.";

//TRANSLATED BUT NOT TESTED - 01.05.2020
$config['project_details_page_sp_view_cancel_invalid_escrow_project'] = "Stav rezervace byl změněn. Nelze provést tuto volbu.";

$config['cancel_escrow_confirmation_project_modal_body'] = 'Opravdu chcete zrušit rezervaci?';


//$config['cancel_escrow_confirmation_project_modal_cancel_btn_txt'] = 'Cancel Escrow';
$config['cancel_escrow_confirmation_project_modal_cancel_btn_txt'] = 'Zrušit rezervaci';

############### config variable for confirmation modal when SP make request release for the escrow which is created by PO #####
//TRANSLATED BUT NOT TESTED - 01.05.2020
$config['project_details_page_sp_view_request_release_escrow_deleted_project'] = "Inzerát byl smazán. Nelze provést tuto volbu.";

//TRANSLATED BUT NOT TESTED - 01.05.2020
$config['project_details_page_sp_view_request_release_invalid_escrow_project'] = "Stav rezervace byl změněn. Nelze provést tuto volbu.";

$config['request_release_escrow_confirmation_project_modal_body'] = 'Opravdu chcete odeslat žádost pro uvolnění platby?';

$config['request_release_escrow_confirmation_project_modal_request_release_btn_txt'] = 'Odeslat žádost';


$config['project_details_page_sp_view_project_requested_release_escrow_message'] = '* požádali jste o uvolnění platby na částku <strong class="touch_line_break">{requested_release_escrow_amount}</strong> od <strong class ="touch_line_break_name">{user_first_name_last_name_or_company_name}</strong> dne <strong class ="touch_line_break">{sp_requested_release_date}</strong>';

$config['project_details_page_po_view_project_requested_release_escrow_message'] = '* <strong class ="touch_line_break_name">{user_first_name_last_name_or_company_name}</strong> žádá o uvolnění rezervace na částku <strong class ="touch_line_break">{requested_release_escrow_amount}</strong> dne <strong class ="touch_line_break">{sp_requested_release_date}</strong>';


/* Define the config for escrows related tab for SP/PO on project detail page*/
//For PO View
$config['project_details_page_payment_management_section_incoming_payment_requests_tab_po_view'] = 'Příchozí žádosti o platbu';
$config['project_details_page_payment_management_section_incoming_payment_requests_tab_tooltip_message_po_view'] = 'zde obdržíte informaci pokaždé, kdy {user_first_name_last_name_or_company_name} požádá o platbu';


$config['project_details_page_payment_management_section_outgoing_escrowed_payments_tab_po_view'] = 'Rezervace platby';

$config['project_details_page_payment_management_section_outgoing_escrowed_payments_tab_tooltip_message_po_view'] = 'zde budete mít informaci pokaždé, kdy vytvoříte rezervaci platby pro {user_first_name_last_name_or_company_name}';

//this one is common for both SP and PO views
$config['project_details_page_payment_management_section_cancelled_escrowed_payments_tab'] = 'Zrušené rezervační platby';

$config['project_details_page_payment_management_section_cancelled_escrowed_payments_tab_tooltip_message_po_view'] = 'zde jsou zobrazeny všechny zrušené rezervační platby';

$config['project_details_page_payment_management_section_released_payments_tab_po_view'] = 'Provedené platby';

$config['project_details_page_payment_management_section_released_payments_tab_tooltip_message_po_view'] = 'zde budete mít informaci pokaždé, kdy provedete platbu pro {user_first_name_last_name_or_company_name}';

$config['project_details_page_payment_management_section_rejected_payment_requests_tab_po_view'] = 'Zamítnuté žádosti o platbu';

$config['project_details_page_payment_management_section_rejected_payment_requests_tab_tooltip_message_po_view'] = 'zde budete mít informací pokaždé, kdy obdržíte žádost o platbu od {user_first_name_last_name_or_company_name} a rozhodli jste se jí zamítnout';

//For SP view
$config['project_details_page_payment_management_section_sent_payment_requests_tab_sp_view'] = 'Odeslání žádosti o platbu';

$config['project_details_page_payment_management_section_sent_payment_requests_tab_tooltip_message_sp_view'] = 'zde budete mít informaci pokaždé, kdy požádáte {user_first_name_last_name_or_company_name} o platbu';

$config['project_details_page_payment_management_section_incoming_escrowed_payments_tab_sp_view'] = 'Příchozí rezervační platby';

$config['project_details_page_payment_management_section_incoming_escrowed_payments_tab_tooltip_message_sp_view'] = 'zde budete mít informaci pokaždé, kdy {user_first_name_last_name_or_company_name} provede rezervaci platby';

$config['project_details_page_payment_management_section_received_payments_tab_sp_view'] = 'Příchozí platby';

$config['project_details_page_payment_management_section_received_payments_tab_tooltip_message_sp_view'] = 'zde budete mít informaci pokaždé, kdy obdržíte platbu od {user_first_name_last_name_or_company_name}';

$config['project_details_page_payment_management_section_rejected_payment_requests_tab_sp_view'] = 'Zamítnuté žádosti o platbu';

$config['project_details_page_payment_management_section_rejected_payment_requests_tab_tooltip_message_sp_view'] = 'zde budete mít informací pokaždé, kdy odešlete žádost o platbu a {user_first_name_last_name_or_company_name} tuto žádost odmítne';
?>