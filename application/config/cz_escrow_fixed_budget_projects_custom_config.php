<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

####Defined configs are used on project detail page (payments tab) as well as on project payment overview page##
########## Start ######

// For Incoming Payment Requests tab
// for Po view
$config['fixed_budget_project_details_page_payment_management_section_incoming_payment_requests_tab_total_requested_amount_txt_po_view'] = "Částka:";

$config['fixed_budget_project_details_page_payment_management_section_incoming_payment_requests_tab_requested_on_txt_po_view'] = "Vytvořeno:";

$config['fixed_budget_project_details_page_payment_management_section_incoming_payment_requests_tab_description_txt_po_view'] = "Popis:";

$config['fixed_budget_project_details_page_payment_management_section_incoming_payment_requests_tab_total_txt_po_view'] = "Celkem (požadované částky):";

// For Sent Payment Requests Tab
// For sp view
$config['fixed_budget_project_details_page_payment_management_section_sent_payment_requests_tab_total_requested_amount_txt_sp_view'] = "Částka:";

$config['fixed_budget_project_details_page_payment_management_section_sent_payment_requests_tab_requested_on_txt_sp_view'] = "Vytvořeno:";

$config['fixed_budget_project_details_page_payment_management_section_sent_payment_requests_tab_description_txt_sp_view'] = "Popis:";

$config['fixed_budget_project_details_page_payment_management_section_sent_payment_requests_tab_total_txt_sp_view'] = "Celkem (požadované částky):";


// For outgoing escrowed payments tab
// For Po View
$config['fixed_budget_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_escrow_amount_txt_po_view'] = "Výše rezervace:";

$config['fixed_budget_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_business_service_fee_txt_po_view'] = "Poplatek:";

$config['fixed_budget_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_total_escrow_txt_po_view'] = "Rezervace vč. poplatku:";

$config['fixed_budget_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_requested_on_txt_po_view'] = "Žádost o platbu:";

$config['fixed_budget_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_created_on_txt_po_view'] = "Rezervace vytvořena:";

$config['fixed_budget_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_description_txt_po_view'] = "Popis:";

$config['fixed_budget_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_total_txt_po_view'] = "Celkem (rezervované částky):";


// For incoming escrowed payments tab
// For Sp View
$config['fixed_budget_project_details_page_payment_management_section_incoming_escrowed_payments_tab_requested_on_txt_sp_view'] = "Žádost o platbu:";

$config['fixed_budget_project_details_page_payment_management_section_incoming_escrowed_payments_tab_escrow_amount_txt_sp_view'] = "Výše rezervace:";

$config['fixed_budget_project_details_page_payment_management_section_incoming_escrowed_payments_tab_created_on_txt_sp_view'] = "Rezervace vytvořena:";

$config['fixed_budget_project_details_page_payment_management_section_incoming_escrowed_payments_tab_description_txt_sp_view'] = "Popis:";

$config['fixed_budget_project_details_page_payment_management_section_incoming_escrowed_payments_tab_total_txt_sp_view'] = "Celkem (rezervované částky):";


// For cancelled escrowed payments tab
// For Po View
$config['fixed_budget_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_reverted_amount_txt_po_view'] = "Vrácená rezervace:";

$config['fixed_budget_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_reverted_business_service_fee_txt_po_view'] = "Vrácený poplatek:";

$config['fixed_budget_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_total_reverted_amount_txt_po_view'] = "Celkem vráceno:";

$config['fixed_budget_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_dispute_id_txt_po_view'] = "CZ-F-PO-Dispute Id:";

$config['fixed_budget_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_dispute_close_date_txt_po_view'] = "CZ-F-PO-Dispute close date:";

$config['fixed_budget_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_description_txt_po_view'] = "Popis:";

$config['fixed_budget_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_cancelled_on_txt_po_view'] = "Zrušeno:";

$config['fixed_budget_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_created_on_txt_po_view'] = "Rezervace vytvořena:";

$config['fixed_budget_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_total_txt_po_view'] = "Celkem (vrácené částky):";


// For cancelled escrowed payments tab
// For Sp View
$config['fixed_budget_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_reverted_amount_topo_txt_sp_view'] = "Vrácená rezervovaná částka pro {user_first_name_last_name_or_company_name}, ve výši";

$config['fixed_budget_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_description_txt_sp_view'] = "Popis:";

$config['fixed_budget_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_cancelled_on_txt_sp_view'] = "Vráceno:";

$config['fixed_budget_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_created_on_txt_sp_view'] = "Rezervace vytvořena:";

$config['fixed_budget_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_dispute_close_date_txt_sp_view'] = "CZ-F-SP-Dispute close date:";

$config['fixed_budget_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_dispute_id_txt_sp_view'] = "CZ-F-SP-Dispute Id:";

$config['fixed_budget_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_total_txt_sp_view'] = "Celkem (zrušené platby):";

// For released escrowed payments tab
// For Po View
$config['fixed_budget_project_details_page_payment_management_section_released_payments_tab_amount_txt_po_view'] = "Platba:";

$config['fixed_budget_project_details_page_payment_management_section_released_payments_tab_business_service_fee_txt_po_view'] = "Poplatek:";

$config['fixed_budget_project_details_page_payment_management_section_released_payments_tab_paid_on_txt_po_view'] = "Zaplaceno:";

$config['fixed_budget_project_details_page_payment_management_section_released_payments_tab_description_txt_po_view'] = "Popis:";

$config['fixed_budget_project_details_page_payment_management_section_released_payments_tab_dispute_id_txt_po_view'] = "CZ-F-PO-Dispute Id:";

$config['fixed_budget_project_details_page_payment_management_section_released_payments_tab_total_paid_txt_po_view'] = "Celkem (provedené platby):";

$config['fixed_budget_project_details_page_payment_management_section_released_payments_tab_total_business_charges_txt_po_view'] = "Celkem (poplatky):";


// For recieved escrowed payments tab
// For Sp View
$config['fixed_budget_project_details_page_payment_management_section_received_payments_tab_amount_txt_sp_view'] = "Platba:";

$config['fixed_budget_project_details_page_payment_management_section_received_payments_tab_paid_on_txt_sp_view'] = "Zaplaceno:";

$config['fixed_budget_project_details_page_payment_management_section_received_payments_tab_description_txt_sp_view'] = "Popis:";

$config['fixed_budget_project_details_page_payment_management_section_received_payments_tab_dispute_id_txt_sp_view'] = "CZ-F-SP-Dispute Id:";

$config['fixed_budget_project_details_page_payment_management_section_received_payments_tab_total_received_txt_sp_view'] = "Celkem (přijaté platby):";


// For rejected payment requests tab
// For Po view
$config['fixed_budget_project_details_page_payment_management_section_rejected_payment_requests_tab_amount_txt_po_view'] = "Částka:";

$config['fixed_budget_project_details_page_payment_management_section_rejected_payment_requests_tab_requested_on_txt_po_view'] = "Žádost o platbu:";

$config['fixed_budget_project_details_page_payment_management_section_rejected_payment_requests_tab_rejected_on_txt_po_view'] = "Odmítnuto:";

$config['fixed_budget_project_details_page_payment_management_section_rejected_payment_requests_tab_description_txt_po_view'] = "Popis:";

$config['fixed_budget_project_details_page_payment_management_section_rejected_payment_requests_tab_total_txt_po_view'] = "Celkem (zamítnuté žádosti):";


// For rejected payment requests tab
// For SP view
$config['fixed_budget_project_details_page_payment_management_section_rejected_payment_requests_tab_requested_on_txt_sp_view'] = "Žádost o platbu:";

$config['fixed_budget_project_details_page_payment_management_section_rejected_payment_requests_tab_amount_txt_sp_view'] = "Částka:";

$config['fixed_budget_project_details_page_payment_management_section_rejected_payment_requests_tab_rejected_on_txt_sp_view'] = "Odmítnuto:";

$config['fixed_budget_project_details_page_payment_management_section_rejected_payment_requests_tab_description_txt_sp_view'] = "Popis:";

$config['fixed_budget_project_details_page_payment_management_section_rejected_payment_requests_tab_total_txt_sp_view'] = "Celkem (zamítnuté žádosti):";
####Defined configs are used on project detail page (payments tab) as well as on project payment overview page##
########## End ######

### For partial release escrow form for fixed type project
$config['fixed_budget_project_partial_release_escrow_confirmation_modal_description_txt'] = 'Popis platby';

$config['fixed_budget_project_partial_release_escrow_confirmation_modal_amount_tobe_released_txt'] = 'Částka pro uvolnění';

$config['fixed_budget_project_partial_release_escrow_confirmation_modal_business_service_fee_txt'] = 'Poplatek';

#### Validation messsage for create/request creation escrow form for fixed type project - displayed to both PO and SPviews
$config['project_details_page_fixed_budget_project_create_escrow_form_escrow_amount_validation_message'] = 'částka je povinné pole';

$config['project_details_page_fixed_budget_project_create_escrow_form_invalid_escrow_amount_validation_message'] = 'neplatná částka';

$config['project_details_page_fixed_budget_project_create_escrow_form_partial_escrow_greater_then_amount_validation_message'] ='částka pro uvolnění nemůže být vyšší než rezervace platby';


$config['project_details_page_fixed_budget_project_create_escrow_request_form_tooltip_message_description_sp_view'] = 'popis transakce slouží k snadnější identifikaci účelu platby a snadnějšímu sledování v historii transakcí';

$config['project_details_page_fixed_budget_project_create_escrow_request_form_tooltip_message_amount_sp_view'] = 'napište výši částky, kterou požadujete odeslat od majitele projektu';

$config['project_details_page_fixed_budget_project_partial_release_escrow_form_tooltip_message_amount_po_view'] = 'napište výši částky, kterou chcete odeslat poskytovateli služeb. částka může být maximálně ve výši vytvořené rezervace.';

$config['project_details_page_fixed_budget_project_partial_release_escrow_form_tooltip_message_description_po_view'] = 'popis transakce slouží k snadnější identifikaci účelu platby a snadnějšímu sledování v historii transakcí';

// config for tooltip messages for create escrows form
$config['project_details_page_fixed_budget_project_create_escrow_form_tooltip_message_description_po_view'] = 'popis platby slouží k snadnější identifikaci účelu platby a snadnějšímu sledování v historii plateb';

$config['project_details_page_fixed_budget_project_create_escrow_form_tooltip_message_amount_po_view'] = 'napište výši částky, kterou chcete odeslat poskytovateli služeb, v jedné transakci můžete odeslat maximálně 9 999 999 999 Kč';

$config['project_details_page_fixed_budget_project_create_escrow_form_tooltip_message_business_service_fee_po_view'] = 'poplatek za službu se počítá procentem z částky odeslané poskytovateli služeb, liší se podle typu členství';

//when PO enter the escrow the tooltip content dynamically update
$config['project_details_page_fixed_budget_project_create_escrow_form_dynamic_tooltip_message_business_service_fee_po_view'] = 'vypočteno na základě vašeho aktuálního členství, které je {po_membership_plan_name} a poplatek je {fixed_budget_business_charges} z výše částky';

/* Tooltip message for created requested escrow model box for business charges for PO view */
$config['project_details_page_fixed_budget_project_create_requested_escrow_tooltip_message_service_fee_charges_po_view'] = 'vypočteno na základě vašeho aktuálního členství, které je {po_membership_subscription_name} a poplatek je {fixed_budget_service_fee_charges} z výše částky';

// For create erscrow by po for requested escrow
$config['create_requested_escrow_confirmation_fixed_budget_project_modal_requested_escrow_txt_po_view'] = 'Částka:';

$config['create_requested_escrow_confirmation_fixed_budget_project_modal_service_fee_txt_po_view'] = 'Poplatek:';

$config['create_requested_escrow_confirmation_fixed_budget_project_modal_total_requested_escrow_txt_po_view'] = 'Celkem:';

######################activity log message regarding escrow created/released/rejected etc
######### Activity log message when SP requested the escrow for PO ##########
// For Sp
$config['fixed_budget_project_message_sent_to_sp_when_sp_created_escrow_request_user_activity_log_displayed_message'] = 'Odeslali jste žádost o platbu ve výši <span>{fixed_budget_project_requested_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

// For Po
$config['fixed_budget_project_message_sent_to_po_when_sp_male_created_escrow_request_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> odeslal žádost o platbu ve výši <span>{fixed_budget_project_requested_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_sp_female_created_escrow_request_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> odeslala žádost o platbu ve výši <span>{fixed_budget_project_requested_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fixed_budget_project_message_sent_to_po_when_sp_company_app_male_created_escrow_request_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> odeslal žádost o platbu ve výši <span>{fixed_budget_project_requested_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_sp_company_app_female_created_escrow_request_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> odeslala žádost o platbu ve výši <span>{fixed_budget_project_requested_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_sp_company_created_escrow_request_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{company_name}</a> odeslali žádost o platbu ve výši <span>{fixed_budget_project_requested_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


######### Activity log message when PO cancelled requested escrow created by SP ##########
//For sp
$config['fixed_budget_project_message_sent_to_sp_when_sp_cancelled_requested_escrow_user_activity_log_displayed_message'] = 'Zrušili jste žádost o platbu ve výši <span>{fixed_budget_project_requested_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


// For Po
$config['fixed_budget_project_message_sent_to_po_when_sp_male_cancelled_escrow_request_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> zrušil žádost o platbu ve výši <span>{fixed_budget_project_requested_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_sp_female_cancelled_escrow_request_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> zrušila žádost o platbu ve výši <span>{fixed_budget_project_requested_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_sp_company_app_male_cancelled_escrow_request_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> zrušil žádost o platbu ve výši <span>{fixed_budget_project_requested_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_sp_company_app_female_cancelled_escrow_request_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> zrušila žádost o platbu ve výši <span>{fixed_budget_project_requested_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_sp_company_cancelled_escrow_request_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{company_name}</a> zrušili žádost o platbu ve výši <span>{fixed_budget_project_requested_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


######### Activity log message when SP cancelled active escrow payment created by PO ##########
// For sp
$config['fixed_budget_project_message_sent_to_sp_when_sp_cancelled_active_escrow_created_by_po_user_activity_log_displayed_message'] = 'Zrušili jste rezervační platbu s výší částky <span>{fixed_buget_project_cancelled_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

// For Po
$config['fixed_budget_project_message_sent_to_po_when_sp_male_cancelled_active_escrow_created_by_po_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> zrušil rezervační platbu na částku <span>{fixed_buget_project_cancelled_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_sp_female_cancelled_active_escrow_created_by_po_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> zrušila rezervační platbu na částku <span>{fixed_buget_project_cancelled_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_sp_company_app_male_cancelled_active_escrow_created_by_po_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> zrušil rezervační platbu na částku <span>{fixed_buget_project_cancelled_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_sp_company_app_female_cancelled_active_escrow_created_by_po_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> zrušila rezervační platbu na částku <span>{fixed_buget_project_cancelled_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_sp_company_cancelled_active_escrow_created_by_po_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{company_name}</a> zrušili rezervační platbu na částku <span>{fixed_buget_project_cancelled_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


######### Activity log message when PO rejected requested escrow created by SP ##########
// For po
$config['fixed_budget_project_message_sent_to_po_when_po_rejected_escrow_creation_request_user_activity_log_displayed_message'] = 'Odmítli jste žádost o platbu s výší částky <span>{fixed_budget_project_requested_escrow_amount}</span> od <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

// For Sp
$config['fixed_budget_project_message_sent_to_sp_when_po_male_rejected_escrow_creation_request_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> odmítl vaši žádost o platbu na částku <span>{fixed_budget_project_requested_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_sp_when_po_female_rejected_escrow_creation_request_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> odmítla vaši žádost o platbu na částku <span>{fixed_budget_project_requested_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_sp_when_po_company_app_male_rejected_escrow_creation_request_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> odmítl vaši žádost o platbu na částku <span>{fixed_budget_project_requested_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_sp_when_po_company_app_female_rejected_escrow_creation_request_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> odmítla vaši žádost o platbu na částku <span>{fixed_budget_project_requested_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_sp_when_po_company_rejected_escrow_creation_request_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{company_name}</a> odmítli vaši žádost o platbu na částku <span>{fixed_budget_project_requested_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


######### Activity log message when PO created requested escrow for SP ##########
// For po
$config['fixed_budget_project_message_sent_to_po_when_po_created_requested_escrow_user_activity_log_displayed_message'] = 'Na žádost <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> jste vytvořili rezervaci platby na částku <span>{fixed_budget_project_requested_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

// For sp
$config['fixed_budget_project_message_sent_to_sp_when_po_male_created_requested_escrow_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> vytvořil rezervaci platby na částku <span>{fixed_budget_project_requested_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_sp_when_po_female_created_requested_escrow_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> vytvořila rezervaci platby na částku <span>{fixed_budget_project_requested_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_sp_when_po_company_app_male_created_requested_escrow_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> vytvořil rezervaci platby na částku <span>{fixed_budget_project_requested_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_sp_when_po_company_app_female_created_requested_escrow_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> vytvořila rezervaci platby na částku <span>{fixed_budget_project_requested_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_sp_when_po_company_created_requested_escrow_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{company_name}</a> vytvořili rezervaci platby na částku <span>{fixed_budget_project_requested_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

######### Activity log message when PO created escrow for SP ##########
//For po
$config['fixed_budget_project_message_sent_to_po_when_po_created_escrow_user_activity_log_displayed_message'] = 'Vytvořili jste rezervaci platby ve výši <span>{fixed_budget_project_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>" pro <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>.';

// For sp
$config['fixed_budget_project_message_sent_to_sp_when_po_male_created_escrow_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> vytvořil rezervaci platby ve výši <span>{fixed_budget_project_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_sp_when_po_female_created_escrow_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> vytvořila rezervaci platby ve výši <span>{fixed_budget_project_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fixed_budget_project_message_sent_to_sp_when_po_company_app_male_created_escrow_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> vytvořil rezervaci platby ve výši <span>{fixed_budget_project_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_sp_when_po_company_app_female_created_escrow_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> vytvořila rezervaci platby ve výši <span>{fixed_budget_project_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fixed_budget_project_message_sent_to_sp_when_po_company_created_escrow_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{company_name}</a> vytvořili rezervaci platby ve výši <span>{fixed_budget_project_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

######### Activity log message when SP make request for release for PO ##########
//For Sp
$config['fixed_budget_project_message_sent_to_sp_when_sp_requested_active_escrow_release_user_activity_log_displayed_message'] = 'Požádali jste <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> o uvolnění platby na částku <span>{fixed_budget_project_request_release_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

// For Po
$config['fixed_budget_project_message_sent_to_po_when_sp_male_requested_active_escrow_release_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> požádal o uvolnění rezervace na částku <span>{fixed_budget_project_request_release_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_sp_female_requested_active_escrow_release_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> požádala o uvolnění rezervace na částku <span>{fixed_budget_project_request_release_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_sp_company_app_male_requested_active_escrow_release_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> požádal o uvolnění rezervace na částku <span>{fixed_budget_project_request_release_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_sp_company_app_female_requested_active_escrow_release_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> požádala o uvolnění rezervace na částku <span>{fixed_budget_project_request_release_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_sp_company_requested_active_escrow_release_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{company_name}</a> požádali o uvolnění rezervace na částku <span>{fixed_budget_project_request_release_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';



######### Activity log message when PO paid full escrow for SP ##########
// For po
$config['fixed_budget_project_message_sent_to_po_when_po_released_escrow_user_activity_log_displayed_message'] = 'Provedli jste platbu ve výši <span>{fixed_budget_project_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>" pro <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>.';


// For Sp
$config['fixed_budget_project_message_sent_to_sp_when_po_male_released_escrow_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> uvolnil platbu ve výši <span>{fixed_budget_project_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_sp_when_po_female_released_escrow_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> uvolnila platbu ve výši <span>{fixed_budget_project_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_sp_when_po_company_app_male_released_escrow_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> uvolnil platbu ve výši <span>{fixed_budget_project_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_sp_when_po_company_app_female_released_escrow_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> uvolnila platbu ve výši <span>{fixed_budget_project_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fixed_budget_project_message_sent_to_sp_when_po_company_released_escrow_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{company_name}</a> uvolnili platbu ve výši <span>{fixed_budget_project_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

######### Activity log message when PO partialy paid escrow for SP ##########
// For Po
$config['fixed_budget_project_message_sent_to_po_when_po_partially_released_escrow_user_activity_log_displayed_message'] = 'Část platby ve výši <span>{fixed_budget_project_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>" byla uvolněna pro <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>.';

//For Sp
$config['fixed_budget_project_message_sent_to_sp_when_po_male_partially_released_escrow_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> uvolnil část platby ve výši <span>{fixed_budget_project_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_sp_when_po_female_partially_released_escrow_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> uvolnila část platby ve výši <span>{fixed_budget_project_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fixed_budget_project_message_sent_to_sp_when_po_company_app_male_partially_released_escrow_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> uvolnil část platby ve výši <span>{fixed_budget_project_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_sp_when_po_company_app_female_partially_released_escrow_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> uvolnila část platby ve výši <span>{fixed_budget_project_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fixed_budget_project_message_sent_to_sp_when_po_company_partially_released_escrow_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{company_name}</a> uvolnili část platby ve výši <span>{fixed_budget_project_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


####Activity log message when PO paid release escrow and after that project moved to completed status ##########
// For PO
$config['fixed_budget_project_message_sent_to_po_when_po_released_escrow_project_completed_user_activity_log_displayed_message'] = 'Provedli jste platbu ve výši <span>{fixed_budget_project_escrow_amount}</span> pro <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> na "<a href="{project_url_link}" target="_blank">{project_title}</a>". Váš projekt "<a href="{project_url_link}" target="_blank">{project_title}</a>" je úspěšně dokončený s <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>.';

//For Sp
$config['fixed_budget_project_message_sent_to_sp_when_po_male_released_escrow_project_completed_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> uvolnil platbu ve výši <span>{fixed_budget_project_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>". Tímto je projekt dokončený.';


$config['fixed_budget_project_message_sent_to_sp_when_po_female_released_escrow_project_completed_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> uvolnila platbu ve výši <span>{fixed_budget_project_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>". Tímto je projekt dokončený.';


$config['fixed_budget_project_message_sent_to_sp_when_po_company_app_male_released_escrow_project_completed_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> uvolnil platbu ve výši <span>{fixed_budget_project_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>". Tímto je projekt dokončený.';


$config['fixed_budget_project_message_sent_to_sp_when_po_company_app_female_released_escrow_project_completed_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> uvolnila platbu ve výši <span>{fixed_budget_project_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>". Tímto je projekt dokončený.';



$config['fixed_budget_project_message_sent_to_sp_when_po_company_released_escrow_project_completed_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{company_name}</a> uvolnili platbu ve výši <span>{fixed_budget_project_escrow_amount}</span> na "<a href="{project_url_link}" target="_blank">{project_title}</a>". Tímto je projekt dokončený.';


###### Realtime notification message regarding payment tab pn project detail page(For escrow amount)
$config['fixed_budget_project_realtime_notification_message_sent_to_sp_when_sp_created_escrow_request'] = 'žádost o platbu byla odeslána pro <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>'; // send by php

$config['fixed_budget_project_realtime_notification_message_sent_to_sp_when_sp_cancelled_requested_escrow'] = 'žádost o platbu byla zrušena';// senT by php

$config['fixed_budget_project_realtime_notification_message_sent_to_po_when_created_requested_escrow'] = 'rezervace byla vytvořena';//send by php

$config['fixed_budget_project_realtime_notification_message_sent_to_sp_when_sp_cancelled_active_escrow'] = "rezervace platby byla zrušena";// By php

$config['fixed_budget_project_realtime_notification_message_sent_to_sp_when_sp_requested_escrow_release'] = 'žádost o platbu byla odeslána pro <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>'; // send by php

// Config of relatime notification for PO when he release last escrow and after release that dedicated escrow project will complete via portal
$config['fixed_budget_project_realtime_notification_message_sent_to_po_when_po_released_escrow_project_completed'] = 'provedli jste platbu ve výši <span>{fixed_budget_project_escrow_amount}</span> pro <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> na "<a href="{project_url_link}" target="_blank">{project_title}</a>", tímto je projekt dokončený.';

$config['fixed_budget_project_realtime_notification_message_sent_to_po_when_po_created_escrow'] = 'rezervace platby byla vytvořena'; // send by php

$config['fixed_budget_project_realtime_notification_message_sent_to_po_when_po_rejected_escrow_creation_request'] = 'odmítli jste žádost o platbu od <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>';// send by php

?>