<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

####Defined configs are used on project detail page (payments tab) as well as on project payment overview page##
########## Start ######

// For Incoming Payment Requests tab
// for Po view
$config['fulltime_project_details_page_payment_management_section_incoming_payment_requests_tab_total_requested_amount_txt_employer_view'] = "Částka:";
$config['fulltime_project_details_page_payment_management_section_incoming_payment_requests_tab_requested_on_txt_employer_view'] = "Vytvořeno:";
$config['fulltime_project_details_page_payment_management_section_incoming_payment_requests_tab_description_txt_employer_view'] = "Popis:";
$config['fulltime_project_details_page_payment_management_section_incoming_payment_requests_tab_total_txt_employer_view'] = "Celkem (požadované částky):";

// For Sent Payment Requests Tab
// For sp view
$config['fulltime_project_details_page_payment_management_section_sent_payment_requests_tab_total_requested_amount_txt_employee_view'] = "Částka:";
$config['fulltime_project_details_page_payment_management_section_sent_payment_requests_tab_requested_on_txt_employee_view'] = "Vytvořeno:";
$config['fulltime_project_details_page_payment_management_section_sent_payment_requests_tab_description_txt_employee_view'] = "Popis:";

$config['fulltime_project_details_page_payment_management_section_sent_payment_requests_tab_total_txt_employee_view'] = "Celkem (požadované částky):";

// For outgoing escrowed payments tab
// For Po View
$config['fulltime_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_escrow_amount_txt_employer_view'] = "Výše rezervace:";
$config['fulltime_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_business_service_fee_txt_employer_view'] = "Poplatek:";
$config['fulltime_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_total_escrow_txt_employer_view'] = "Rezervace vč. poplatku:";
$config['fulltime_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_created_on_txt_employer_view'] = "Rezervace vytvořena:";
$config['fulltime_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_requested_on_txt_employer_view'] = "Žádost o platbu:";
$config['fulltime_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_description_txt_employer_view'] = "Popis:";
$config['fulltime_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_total_txt_employer_view'] = "Celkem (rezervované částky):";

// For incoming escrowed payments tab
// For Sp View
$config['fulltime_project_details_page_payment_management_section_incoming_escrowed_payments_tab_escrow_amount_txt_employee_view'] = "Výše rezervace:";
$config['fulltime_project_details_page_payment_management_section_incoming_escrowed_payments_tab_created_on_txt_employee_view'] = "Rezervace vytvořena:";
$config['fulltime_project_details_page_payment_management_section_incoming_escrowed_payments_tab_requested_on_txt_employee_view'] = "Žádost o platbu:";
$config['fulltime_project_details_page_payment_management_section_incoming_escrowed_payments_tab_description_txt_employee_view'] = "Popis:";
$config['fulltime_project_details_page_payment_management_section_incoming_escrowed_payments_tab_total_txt_employee_view'] = "Celkem (rezervované částky):";

// For cancelled escrowed payments tab
// For Po View
$config['fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_reverted_amount_txt_employer_view'] = "Vrácená rezervace:";

$config['fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_reverted_business_service_fee_txt_employer_view'] = "Vrácený poplatek:";

$config['fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_total_reverted_amount_txt_employer_view'] = "Celkem vráceno:";

$config['fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_created_on_txt_employer_view'] = "Rezervace vytvořena:";

$config['fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_cancelled_on_txt_employer_view'] = "Zrušeno:";

$config['fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_description_txt_employer_view'] = "Popis:";


$config['fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_dispute_id_txt_employer_view'] = "CZ-FT-PO-Dispute Id:";
$config['fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_dispute_close_date_txt_employer_view'] = "CZ-FT-PO-Dispute close date:";


$config['fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_total_txt_employer_view'] = "Celkem (vrácené částky):";

// For cancelled escrowed payments tab
// For Sp View
$config['fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_reverted_amount_topo_txt_employee_view'] = "Vrácená rezervovaná částka pro {user_first_name_last_name_or_company_name}, ve výši";
$config['fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_cancelled_on_txt_employee_view'] = "Vráceno:";
$config['fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_description_txt_employee_view'] = "Popis:";
$config['fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_created_on_txt_employee_view'] = "Rezervace vytvořena:";

$config['fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_dispute_close_date_txt_employee_view'] = "CZ-FT-SP-Dispute close date:";
$config['fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_dispute_id_txt_employee_view'] = "CZ-FT-SP-Dispute Id:";

$config['fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_total_txt_employee_view'] = "Celkem (zrušené platby):";

// For released escrowed payments tab
// For Po View
$config['fulltime_project_details_page_payment_management_section_released_payments_tab_amount_txt_employer_view'] = "Platba:";
$config['fulltime_project_details_page_payment_management_section_released_payments_tab_business_service_fee_txt_employer_view'] = "Poplatek:";
$config['fulltime_project_details_page_payment_management_section_released_payments_tab_paid_on_txt_employer_view'] = "Zaplaceno:";
$config['fulltime_project_details_page_payment_management_section_released_payments_tab_description_txt_employer_view'] = "Popis:";

$config['fulltime_project_details_page_payment_management_section_released_payments_tab_dispute_id_txt_employer_view'] = "CZ-FT-PO-Dispute Id:";

$config['fulltime_project_details_page_payment_management_section_released_payments_tab_total_paid_txt_employer_view'] = "Celkem (provedené platby):";
$config['fulltime_project_details_page_payment_management_section_released_payments_tab_total_business_charges_txt_employer_view'] = "Celkem (poplatky):";

// For recieved escrowed payments tab
// For Sp View
$config['fulltime_project_details_page_payment_management_section_received_payments_tab_amount_txt_employee_view'] = "Platba:";
$config['fulltime_project_details_page_payment_management_section_received_payments_tab_paid_on_txt_employee_view'] = "Zaplaceno:";
$config['fulltime_project_details_page_payment_management_section_received_payments_tab_description_txt_employee_view'] = "Popis:";

$config['fulltime_project_details_page_payment_management_section_received_payments_tab_dispute_id_txt_employee_view'] = "CZ-FT-SP-Dispute Id:";

$config['fulltime_project_details_page_payment_management_section_received_payments_tab_total_received_txt_employee_view'] = "Celkem (přijaté platby):";

// For rejected payment requests tab
// For Po view
$config['fulltime_project_details_page_payment_management_section_rejected_payment_requests_tab_amount_txt_employer_view'] = "Částka:";
$config['fulltime_project_details_page_payment_management_section_rejected_payment_requests_tab_requested_on_txt_employer_view'] = "Žádost o platbu:";
$config['fulltime_project_details_page_payment_management_section_rejected_payment_requests_tab_rejected_on_txt_employer_view'] = "Odmítnuto:";
$config['fulltime_project_details_page_payment_management_section_rejected_payment_requests_tab_description_txt_employer_view'] = "Popis:";

$config['fulltime_project_details_page_payment_management_section_rejected_payment_requests_tab_total_txt_employer_view'] = "Celkem (zamítnuté žádosti):";


// For rejected payment requests tab
// For SP view
$config['fulltime_project_details_page_payment_management_section_rejected_payment_requests_tab_amount_txt_employee_view'] = "Částka:";
$config['fulltime_project_details_page_payment_management_section_rejected_payment_requests_tab_requested_on_txt_employee_view'] = "Žádost o platbu:";
$config['fulltime_project_details_page_payment_management_section_rejected_payment_requests_tab_rejected_on_txt_employee_view'] = "Odmítnuto:";
$config['fulltime_project_details_page_payment_management_section_rejected_payment_requests_tab_description_txt_employee_view'] = "Popis:";
$config['fulltime_project_details_page_payment_management_section_rejected_payment_requests_tab_total_txt_employee_view'] = "Celkem (zamítnuté žádosti):";

####Defined configs are used on project detail page (payments tab) as well as on project payment overview page##
########## End ######

$config['project_details_page_min_salary_amount'] = 1250; // This variable will used when employer posted project with not specifed budget and employee try to place application on it

//used for both PO and SP - to be reviewed and confirmed
$config['project_details_page_minimum_required_salary_amount_validation_fulltime_project_create_escrow_form_message'] = 'částka nemůže být nižší než {project_minimum_salay_amount} '.CURRENCY.'/měs';



$config['project_details_page_fulltime_project_create_escrow_request_form_create_escrow_request_button_txt_employee_view'] = 'Žádost o platbu';

$config['project_details_page_fulltime_project_create_escrow_request_form_heading_employee_view'] = 'Vytvoření žádosti pro <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name_or_company_name}</a>';
//message for initial view for sp view in payment tab
$config['project_details_page_fulltime_project_description_create_escrow_request_form_employee_view'] = 'Popis platby';
$config['project_details_page_fulltime_project_amount_create_escrow_request_form_employee_view'] = 'Částka';

//config variables used on project detail page for create escrow request form regarding Employee view
$config['project_details_page_fulltime_project_create_escrow_request_form_tooltip_message_description_employee_view'] = 'popis platby slouží k snadnější identifikaci účelu platby a snadnějšímu sledování v historii plateb';
$config['project_details_page_fulltime_project_create_escrow_request_form_tooltip_message_amount_employee_view'] = 'částka, kterou požadujete odeslat od zaměstnavatele';

$config['project_details_page_fulltime_project_create_escrow_request_form_request_payment_button_txt_employee_view'] = 'Žádost o platbu';
$config['project_details_page_fulltime_project_create_escrow_request_form_cancel_button_txt_employee_view'] = 'Zrušit';



$config['project_details_page_employee_view_fulltime_project_requested_release_escrow_message'] = '* požádali jste o uvolnění platby na částku <strong>{fulltime_request_release_escrow_value}</strong> od <strong>{user_first_name_last_name_or_company_name}</strong> dne <strong>{employee_requested_release_date}</strong>';

$config['project_details_page_employer_view_fulltime_project_requested_release_escrow_message'] = '* <strong>{user_first_name_last_name_or_company_name}</strong> žádá o uvolnění platby na částku <strong><span class="touch_line_break">{fulltime_request_release_escrow_value}</span></strong> dne <strong>{employee_requested_release_date}</strong>';

######## config for partial release escrow ######
$config['project_details_page_fulltime_project_partial_release_escrow_form_tooltip_message_description_employer_view'] = 'popis platby slouží k snadnější identifikaci účelu platby a snadnějšímu sledování v historii plateb';
$config['project_details_page_fulltime_project_partial_release_escrow_form_tooltip_message_amount_employer_view'] = 'napište výši částky, kterou chcete uvolnit zaměstnanci';
$config['fulltime_project_partial_release_escrow_confirmation_modal_description_txt'] = 'Popis platby';
$config['fulltime_project_partial_release_escrow_confirmation_modal_amount_tobe_released_txt'] = 'Částka pro uvolnění';
$config['fulltime_project_partial_release_escrow_confirmation_modal_business_service_fee_txt'] = 'Poplatek';


// config variables for validation messages on create milestone request form for SP view
$config['project_details_page_escrow_amount_validation_fulltime_project_escrow_form_message'] = 'částka je vyžadována';

$config['project_details_page_invalid_escrow_amount_validation_fulltime_project_escrow_form_message'] = 'špatná částka';

$config['project_details_page_partial_escrow_greater_then_amount_validation_fulltime_project_escrow_form_message'] = 'částka pro uvolnění nemůže být vyšší než rezervace platby';

$config['partial_release_escrow_confirmation_fulltime_project_modal_confirm_btn_txt'] = 'Provést platbu';

### Employer created Escrow
$config['fulltime_project_realtime_notification_message_sent_to_employer_when_employer_created_escrow'] = 'rezervace byla vytvořena'; // send by php

$config['fulltime_project_message_sent_to_employee_when_employer_male_created_escrow_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> vytvořil rezervaci platby ve výši <span>{fulltime_project_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employee_when_employer_female_created_escrow_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> vytvořila rezervaci platby ve výši <span>{fulltime_project_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fulltime_project_message_sent_to_employee_when_employer_company_app_male_created_escrow_user_activity_log_displayed_message'] = 'App Male: <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> vytvořil rezervaci platby ve výši <span>{fulltime_project_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employee_when_employer_company_app_female_created_escrow_user_activity_log_displayed_message'] = 'App Female: <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> vytvořila rezervaci platby ve výši <span>{fulltime_project_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';



$config['fulltime_project_message_sent_to_employee_when_employer_company_created_escrow_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{company_name}</a> vytvořili rezervaci platby ve výši <span>{fulltime_project_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';



$config['fulltime_project_message_sent_to_employer_when_employer_created_escrow_user_activity_log_displayed_message'] = 'Vytvořili jste rezervaci platby ve výši <span>{fulltime_project_escrow_amount}</span> pro <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

######### Activity log message when Employee requested the escrow for Employer ##########
$config['fulltime_project_realtime_notification_message_sent_to_employee_when_employee_created_escrow_request'] = 'žádost o platbu byla odeslána pro <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>'; // send by php

$config['fulltime_project_message_sent_to_employer_when_employee_male_created_escrow_request_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> odeslal žádost o platbu ve výši <span>{fulltime_project_requested_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employer_when_employee_female_created_escrow_request_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> odeslala žádost o platbu ve výši <span>{fulltime_project_requested_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fulltime_project_message_sent_to_employer_when_employee_company_app_male_created_escrow_request_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> odeslal žádost o platbu ve výši <span>{fulltime_project_requested_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employer_when_employee_company_app_female_created_escrow_request_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> odeslala žádost o platbu ve výši <span>{fulltime_project_requested_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fulltime_project_message_sent_to_employer_when_employee_company_created_escrow_request_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{company_name}</a> odeslali žádost o platbu ve výši <span>{fulltime_project_requested_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fulltime_project_message_sent_to_employee_when_employee_created_escrow_request_user_activity_log_displayed_message'] = 'Odeslali jste žádost o platbu ve výši <span>{fulltime_project_requested_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

############################################################################################################

##### Realtime notification and activity log when employer reject requested escrow
$config['fulltime_project_realtime_notification_message_sent_to_employer_when_employer_rejected_escrow_creation_request'] = 'odmítli jste žádost o platbu od <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>';// send by php

$config['fulltime_project_message_sent_to_employee_when_employer_male_rejected_escrow_creation_request_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> odmítl vaši žádost o platbu na částku <span>{fulltime_project_requested_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employee_when_employer_female_rejected_escrow_creation_request_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> odmítla vaši žádost o platbu na částku <span>{fulltime_project_requested_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fulltime_project_message_sent_to_employee_when_employer_company_app_male_rejected_escrow_creation_request_user_activity_log_displayed_message'] = 'App Male: <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> odmítl vaši žádost o platbu na částku <span>{fulltime_project_requested_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employee_when_employer_company_app_female_rejected_escrow_creation_request_user_activity_log_displayed_message'] = 'App Female: <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> odmítla vaši žádost o platbu na částku <span>{fulltime_project_requested_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fulltime_project_message_sent_to_employee_when_employer_company_rejected_escrow_creation_request_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{company_name}</a> odmítli vaši žádost o platbu na částku <span>{fulltime_project_requested_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employer_when_employer_rejected_escrow_creation_request_user_activity_log_displayed_message'] = 'Odmítli jste žádost o platbu s výší částky <span>{fulltime_project_requested_escrow_amount}</span> od <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

######### Activity log message when Employer created requested escrow for Employee ##########
$config['fulltime_project_message_sent_to_employee_when_employer_male_created_requested_escrow_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> na vaši žádost vytvořil rezervaci platby ve výši <span>{fulltime_project_requested_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employee_when_employer_female_created_requested_escrow_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> na vaši žádost vytvořila rezervaci platby ve výši <span>{fulltime_project_requested_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fulltime_project_message_sent_to_employee_when_employer_company_app_male_created_requested_escrow_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> na vaši žádost vytvořil rezervaci platby ve výši <span>{fulltime_project_requested_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employee_when_employer_company_app_female_created_requested_escrow_user_activity_log_displayed_message'] = 'App Female: <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> na vaši žádost vytvořila rezervaci platby ve výši <span>{fulltime_project_requested_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fulltime_project_message_sent_to_employee_when_employer_company_created_requested_escrow_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{company_name}</a> na vaši žádost vytvořili rezervaci platby ve výši <span>{fulltime_project_requested_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fulltime_project_message_sent_to_employer_when_employer_created_requested_escrow_user_activity_log_displayed_message'] = 'Na žádost <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> jste vytvořili rezervaci platby na částku <span>{fulltime_project_requested_escrow_amount}</span> pro pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_realtime_notification_message_sent_to_employer_when_employer_created_requested_escrow'] = 'rezervace byla vytvořena';//send by php

####### Realtime notification and activity log when employee cancel requested escrow
$config['fulltime_project_realtime_notification_message_sent_to_employee_when_employee_cancelled_requested_escrow'] = 'žádost o platbu byla zrušena';// send by php

$config['fulltime_project_realtime_notification_message_sent_to_employee_when_employee_cancelled_active_escrow'] = "rezervace platby byla zrušena"; // By php

$config['fulltime_project_message_sent_to_employer_when_employee_male_cancelled_escrow_request_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> zrušil žádost o platbu ve výši <span>{fulltime_project_requested_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employer_when_employee_female_cancelled_escrow_request_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> zrušila žádost o platbu ve výši <span>{fulltime_project_requested_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fulltime_project_message_sent_to_employer_when_employee_company_app_male_cancelled_escrow_request_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> zrušil žádost o platbu ve výši <span>{fulltime_project_requested_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employer_when_employee_company_app_female_cancelled_escrow_request_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> zrušila žádost o platbu ve výši <span>{fulltime_project_requested_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fulltime_project_message_sent_to_employer_when_employee_company_cancelled_escrow_request_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{company_name}</a> zrušili žádost o platbu ve výši <span>{fulltime_project_requested_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employee_when_employee_cancelled_requested_escrow_user_activity_log_displayed_message'] = 'Zrušili jste žádost o platbu ve výši <span>{fulltime_project_requested_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

######### Activity log message when employee cancelled active escrow payment created by employer ##########
// For employee
$config['fulltime_project_message_sent_to_employee_when_employee_cancelled_active_escrow_created_by_employer_user_activity_log_displayed_message'] = 'Zrušili jste rezervační platbu s výší částky <span>{fulltime_project_cancelled_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

// For employer
$config['fulltime_project_message_sent_to_employer_when_employee_male_cancelled_active_escrow_created_by_employer_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> zrušil rezervační platbu na částku <span>{fulltime_project_cancelled_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employer_when_employee_female_cancelled_active_escrow_created_by_employer_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> zrušila rezervační platbu na částku <span>{fulltime_project_cancelled_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employer_when_employee_company_app_male_cancelled_active_escrow_created_by_employer_user_activity_log_displayed_message'] = 'App Male: <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> zrušil rezervační platbu na částku <span>{fulltime_project_cancelled_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employer_when_employee_company_app_female_cancelled_active_escrow_created_by_employer_user_activity_log_displayed_message'] = 'App Female: <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> zrušila rezervační platbu na částku <span>{fulltime_project_cancelled_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fulltime_project_message_sent_to_employer_when_employee_company_cancelled_active_escrow_created_by_employer_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{company_name}</a> zrušili rezervační platbu na částku <span>{fulltime_project_cancelled_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


####### Realtime notification and activity log message when employee sent request for release #######
$config['fulltime_project_realtime_notification_message_sent_to_employee_when_employee_requested_escrow_release'] = 'žádost o platbu byla odeslána <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>'; // send by php

$config['fulltime_project_message_sent_to_employer_when_employee_male_requested_active_escrow_release_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> požádal o uvolnění rezervace na částku <span>{fulltime_project_request_release_escrow_value}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employer_when_employee_female_requested_active_escrow_release_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> požádala o uvolnění rezervace na částku <span>{fulltime_project_request_release_escrow_value}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employer_when_employee_company_app_male_requested_active_escrow_release_user_activity_log_displayed_message'] = 'App Male: <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> požádal o uvolnění rezervace na částku <span>{fulltime_project_request_release_escrow_value}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employer_when_employee_company_app_female_requested_active_escrow_release_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> požádala o uvolnění rezervace na částku <span>{fulltime_project_request_release_escrow_value}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fulltime_project_message_sent_to_employer_when_employee_company_requested_active_escrow_release_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{company_name}</a> požádali o uvolnění rezervace na částku <span>{fulltime_project_request_release_escrow_value}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employee_when_employee_requested_active_escrow_release_user_activity_log_displayed_message'] = 'Požádali jste <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> o uvolnění platby na částku <span>{fulltime_project_request_release_escrow_value}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


//employer release active escrow
$config['fulltime_project_message_sent_to_employee_when_employer_male_released_escrow_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> uvolnil platbu ve výši <span>{fulltime_project_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employee_when_employer_female_released_escrow_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> uvolnila platbu ve výši <span>{fulltime_project_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fulltime_project_message_sent_to_employee_when_employer_company_app_male_released_escrow_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> uvolnil platbu ve výši <span>{fulltime_project_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employee_when_employer_company_app_female_released_escrow_user_activity_log_displayed_message'] = 'App Female: <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> uvolnila platbu ve výši <span>{fulltime_project_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';



$config['fulltime_project_message_sent_to_employee_when_employer_company_released_escrow_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{company_name}</a> uvolnili platbu ve výši <span>{fulltime_project_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employer_when_employer_released_escrow_user_activity_log_displayed_message'] = 'Provedli jste platbu ve výši <span>{fulltime_project_escrow_amount}</span> pro <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employee_when_employer_male_partially_released_escrow_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> uvolnil část platby ve výši <span>{fulltime_project_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';
$config['fulltime_project_message_sent_to_employee_when_employer_female_partially_released_escrow_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> uvolnila část platby ve výši <span>{fulltime_project_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employee_when_employer_company_app_male_partially_released_escrow_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> uvolnil část platby ve výši <span>{fulltime_project_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employee_when_employer_company_app_female_partially_released_escrow_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> uvolnila část platby ve výši <span>{fulltime_project_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employee_when_employer_company_partially_released_escrow_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{company_name}</a> uvolnili část platby ve výši <span>{fulltime_project_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employer_when_employer_partially_released_escrow_user_activity_log_displayed_message'] = 'Uvolnili jste část platby ve výši <span>{fulltime_project_escrow_amount}</span> na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>" pro <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>.';



$config['fulltime_project_realtime_notification_message_sent_to_employer_when_employee_created_escrow_request'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> created the escrow request of value <span>{fulltime_project_requested_escrow_amount}</span> of fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>'; // send by node

############ Employer create escrow form #######
$config['project_details_page_fulltime_project_create_milestone_form_heading_employer_view'] = 'Vytvoření platby pro <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name_or_company_name}</a>';

$config['project_details_page_fulltime_project_description_create_milestone_form_employer_view'] = 'Popis platby';

$config['project_details_page_fulltime_project_amount_create_milestone_form_employer_view'] = 'Částka';

$config['project_details_page_fulltime_project_business_service_fee_create_milestone_form_employer_view'] = 'Poplatek';

$config['project_details_page_fulltime_project_total_amount_create_milestone_milestone_form_employer_view'] = 'Celkem';

$config['project_details_page_fulltime_project_create_milestone_form_tooltip_message_description_employer_view'] = 'popis platby slouží k snadnější identifikaci účelu platby a snadnějšímu sledování v historii plateb';

$config['project_details_page_fulltime_project_create_milestone_form_tooltip_message_amount_employer_view'] = 'napište výši částky, kterou chcete odeslat zaměstnanci';

$config['project_details_page_fulltime_project_create_milestone_form_tooltip_message_business_service_fee_employer_view'] = 'poplatek za službu se počítá procentem z částky odeslané poskytovateli služeb, liší se podle typu členství';

$config['project_details_page_fulltime_project_create_escrow_form_dynamic_tooltip_message_service_fee_employer_view'] = 'vypočteno na základě vašeho aktuálního členství, které je {employer_membership_plan_name} a poplatek je {fulltime_service_fees_charges} z výše částky';

$config['project_details_page_fulltime_project_create_milestone_form_create_milestone_button_txt_employer_view'] = 'Vytvořit rezervaci';
$config['project_details_page_fulltime_project_create_milestone_form_cancel_button_txt_employer_view'] = 'Zrušit';


//message for initial view for po view in payment tab
$config['project_details_page_fulltime_project_create_milestone_form_create_milestone_payment_button_txt_employer_view'] = 'Vytvořit platbu';


##### this config variables for drop down option regarding requested/escrow/released milestone section
$config['fulltime_project_requested_escrow_section_option_cancel_employee_view'] = "Zrušit žádost";

$config['fulltime_project_requested_escrow_section_option_reject_employer_view'] = "Odmítnout žádost";
$config['fulltime_project_requested_escrow_section_option_pay_employer_view'] = "Vytvořit rezervaci";

$config['fulltime_project_active_escrow_section_option_release_employer_view'] = "Zaplatit";
$config['fulltime_project_active_escrow_section_option_partial_release_employer_view'] = "Částečně zaplatit";

$config['fulltime_project_active_escrow_section_option_cancel_employee_view'] = "Zrušit rezervaci";
$config['fulltime_project_active_escrow_section_option_request_release_employee_view'] = "Žádost o zaplacení";

############### config variable for confirmation modal when Employee cancel the requested milestone#####
$config['project_details_page_employee_view_cancel_invalid_requested_escrow_fulltime_project'] = "Nelze zrušit tuto žádost o platbu.";

$config['cancel_requested_escrow_confirmation_fulltime_project_modal_body'] = 'Opravdu chcete zrušit žádost o <span class="display-inline-block">platbu?</span>';

$config['cancel_requested_escrow_confirmation_fulltime_project_modal_cancel_btn_txt'] = 'Zrušit žádost';

############### config variable for confirmation modal when employer rejected the requested escrow #####
$config['reject_requested_escrow_confirmation_fulltime_project_modal_body'] = 'Opravdu chcete odmítnout <span class="display-inline-block">žádost?</span>';

$config['reject_requested_escrow_confirmation_fulltime_project_modal_reject_btn_txt'] = 'Odmítnout žádost';


//sp already deleted his request - while PO tries to reject on his side
$config['project_details_page_employer_view_reject_invalid_requested_escrow_fulltime_project'] = "Nelze odmítnout tuto žádost.";

########## config variable for confirmation modal when PO created requested milestone#####
$config['create_requested_escrow_confirmation_fulltime_project_modal_body'] = 'Pokračováním vytvoříte rezervaci';

$config['create_requested_escrow_confirmation_fulltime_project_modal_requested_escrow_txt_employer_view'] = 'Částka:';
$config['create_requested_escrow_confirmation_fulltime_project_modal_service_fee_txt_employer_view'] = 'Poplatek:';
$config['create_requested_escrow_confirmation_fulltime_project_modal_total_requested_escrow_txt_employer_view'] = 'Celkem:';


$config['create_requested_escrow_confirmation_fulltime_project_modal_confirm_btn_txt'] = 'Vytvořit rezervaci';
$config['project_details_page_fulltime_project_create_requested_escrow_tooltip_message_service_fee_charges_employer_view'] = 'vypočteno na základě vašeho aktuálního členství, které je {employer_membership_subscription_name} a poplatek je {fulltime_service_fee_charges} z výše částky';

//po tries to create escrow payment to sp on a requested amount - while sp in the meantime cancelled that request on his side
$config['project_details_page_employer_view_create_invalid_requested_escrow_fulltime_project'] = "Nelze vytvořit rezervaci na tuto žádost.";

########## config variable for confirmation modal when employer released escrow #####
$config['release_escrow_confirmation_fulltime_project_modal_confirm_btn_txt'] = 'Provést platbu';

//employee cancelled incoming escrow - while employer tries to release the same payment entry
$config['project_details_page_employer_view_invalid_active_escrow_fulltime_project'] = "Nelze provést platbu. Proces není platný.";


############### config variable for confirmation modal when SP cancel the milestone which is created by PO #####
$config['project_details_page_employee_view_cancel_invalid_escrow_fulltime_project'] = "Stav rezervace byl změn. Nelze provést tuto volbu.";

$config['cancel_escrow_confirmation_fulltime_project_modal_body'] = 'Opravdu chcete zrušit <span class="display-inline-block">rezervaci?</span>';

$config['cancel_escrow_confirmation_fulltime_project_modal_cancel_btn_txt'] = 'Zrušit rezervaci';

############### config variable for confirmation modal when employee make request release for the escrow which is created by employer #####
$config['request_release_escrow_confirmation_fulltime_project_modal_body'] = 'Opravdu chcete odeslat žádost pro uvolnění <span class="display-inline-block">platby?</span>';

$config['request_release_escrow_confirmation_fulltime_project_modal_request_release_btn_txt'] = 'Odeslat žádost';

$config['project_details_page_employee_view_request_release_invalid_escrow_fulltime_project'] = "Stav rezervace byl změn. Nelze provést tuto volbu.";

?>