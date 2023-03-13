<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['project_details_page_fixed_budget_project_bid_form_bid'] = 'výše nabídky';

$config['project_details_page_fixed_budget_project_bid_form_days'] = 'dní';

//all the below 5 variables used on award modal when PO award project to SP
$config['fixed_budget_project_award_bid_modal_body_po_view_bid_amount_txt'] = '<span>výše nabídky</span>';

$config['fixed_budget_project_award_bid_modal_body_po_view_project_value_txt'] = 'hodnota projektu';

$config['fixed_budget_project_award_bid_modal_body_po_view_delivery_in_txt'] = '<span class="delivery_in_txt_color">dodání do</span>';
$config['fixed_budget_project_award_bid_modal_body_po_view_confidential_txt'] = 'neveřejná';

$config['fixed_budget_project_award_bid_modal_body_po_view_to_be_agreed_txt'] = 'po dohodě';
///*************


$config['in_progress_bidding_listing_fixed_expected_completion_date'] = '<span>očekávané datum dokončení</span>';


$config['fixed_budget_project_project_details_page_bidder_listing_initial_bid_value_txt_po_view'] = '<span class="initial_bid_value_txt_po_view_color">výše nabídky</span>';

$config['fixed_budget_project_project_details_page_bidder_listing_your_initial_bid_value_txt_sp_view'] = '<span class="initial_bid_value_txt_sp_view_color">výše nabídky</span>';

// Tooltip message of fixed project for place bid form
$config['project_details_page_fixed_budget_project_bid_form_tooltip_message_bid_amount'] = 'výše nabídky by měla být co nejpřesnější (dle vaší kalkulace). níže v detailu nabídky můžete kalkulaci blíže specifikovat (nahrát přílohu s rozpočtem) a tím objasnit výši nabídky, aby měl majitel projektu co nejlepší představu.';

$config['project_details_page_fixed_budget_project_bid_form_tooltip_message_delivery_in'] = 'zadáním termínu dodání se zavazujete datem úspěšného dokončení projektu a předání majiteli projektu. během práce můžete majitele informovat o stavu projektu a případně se dohodnout na jiné datum dodání.';

############### validation config and message fixed type project regarding place bid form ######

$config['project_details_page_bid_amount_validation_fixed_budget_project_bid_form_message'] = 'částka je vyžadována';

$config['project_details_page_number_days_validation_fixed_budget_project_bid_form_message'] = 'počet dní je vyžadován';

$config['project_details_page_minimum_required_bid_amount_validation_fixed_budget_project_bid_form_message'] = 'nabídka nemůže být nižší než {project_minimum_fixed_bid_amount} '.CURRENCY.'';

$config['award_bid_confirmation_fixed_budget_project_modal_body_po_view'] = 'Pokračováním souhlasíte s nabídkou a udělíte "{fixed_budget_project_title}" pro {user_first_name_last_name_or_company_name}.';

$config['award_bid_confirmation_fixed_budget_project_modal_body_po_view_confidential'] = 'Pokračováním souhlasíte s nabídkou a udělíte "{fixed_budget_project_title}" pro {user_first_name_last_name_or_company_name}.';

$config['award_bid_confirmation_fixed_budget_project_modal_body_po_view_to_be_agreed'] = 'Pokračováním souhlasíte s nabídkou a udělíte "{fixed_budget_project_title}" pro {user_first_name_last_name_or_company_name}.';


// This config are used to show the tootltip message/Text when PO award the project to sp by confirmation modal

// tooltip message for bid amount
$config['fixed_budget_project_award_bid_modal_body_po_view_tooltip_message_bid_amount'] = 'toto je výše nabídky od {user_first_name_last_name_or_company_name}, která byla odeslána na váš projekt';

// tooltip message for project delivered time
$config['fixed_budget_project_award_bid_modal_body_po_view_tooltip_message_delivery_in'] = 'toto je navrhovaný čas od {user_first_name_last_name_or_company_name}, který je potřebný k dokončení vašeho projektu';

//tooltip message for project value
$config['fixed_budget_project_award_bid_modal_body_po_view_tooltip_message_project_value'] = 'toto je hodnota, za kterou bude projekt považován jako úspěšně dokončený prostřednictvím portálu';

############ activity log message when SP post the bid ######
// For PO
$config['fixed_budget_project_message_sent_to_po_when_new_bid_received_from_sp_male_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> odeslal nabídku na váš inzerát "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_new_bid_received_from_sp_female_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> odeslala nabídku na váš inzerát "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_new_bid_received_from_sp_company_app_male_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> odeslal nabídku na váš inzerát "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_new_bid_received_from_sp_company_app_female_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> odeslala nabídku na váš inzerát "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_new_bid_received_from_sp_company_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> odeslali nabídku na váš inzerát "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

// For SP
$config['fixed_budget_project_message_sent_to_sp_when_new_bid_placed_user_activity_log_displayed_message'] = 'Odeslali jste nabídku na inzerát "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

############ activity log message when sp update the bid #####
//For PO
$config['fixed_budget_project_message_sent_to_po_when_sp_male_edit_bid_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> upravil nabídku na váš inzerát "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_sp_female_edit_bid_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> upravila nabídku na váš inzerát "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_sp_company_app_male_edit_bid_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> upravil nabídku na váš inzerát "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_sp_company_app_female_edit_bid_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> upravila nabídku na váš inzerát "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fixed_project_message_sent_to_po_when_sp_company_edit_bid_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> upravili nabídku na váš inzerát "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

// For SP
$config['fixed_budget_project_message_sent_to_sp_when_edit_bid_user_activity_log_displayed_message'] = 'Upravili jste nabídku na inzerát "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

############ activity log message when SP retract the bid ######
// For PO
$config['fixed_budget_project_message_sent_to_po_when_sp_male_retract_bid_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> stáhl nabídku na váš inzerát "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_sp_female_retract_bid_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> stáhla nabídku na váš inzerát "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_sp_company_app_male_retract_bid_user_activity_log_displayed_message'] = 'App Male: <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> stáhl nabídku na váš inzerát "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_sp_company_app_male_retract_bid_user_activity_log_displayed_message'] = 'App Female: <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> stáhla nabídku na váš inzerát "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_sp_company_retract_bid_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> stáhli nabídku na váš inzerát "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

//For Sp
$config['fixed_budget_project_message_sent_to_sp_when_retract_bid_user_activity_log_displayed_message'] = 'Stáhli jste nabídku na inzerát "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

############ activity log message when PO award the bid #########
// For PO
$config['fixed_budget_project_message_sent_to_po_when_award_bid_user_activity_log_displayed_message'] = 'Souhlasili jste s nabídkou od <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> a udělili projekt "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

//For SP
$config['fixed_budget_project_message_sent_to_sp_when_is_awarded_bid_by_po_male_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> přijal vaši nabídku na "<a href="{project_url_link}" target="_blank">{project_title}</a>". Termín pro rozhodnutí je do {award_expiration_date}.';


$config['fixed_budget_project_message_sent_to_sp_when_is_awarded_bid_by_po_female_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> přijala vaši nabídku na "<a href="{project_url_link}" target="_blank">{project_title}</a>". Termín pro rozhodnutí je do {award_expiration_date}.';

$config['fixed_budget_project_message_sent_to_sp_when_is_awarded_bid_by_po_company_app_male_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> přijal vaši nabídku na "<a href="{project_url_link}" target="_blank">{project_title}</a>". Termín pro rozhodnutí je do {award_expiration_date}.';

$config['fixed_budget_project_message_sent_to_sp_when_is_awarded_bid_by_po_company_app_female_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> přijala vaši nabídku na "<a href="{project_url_link}" target="_blank">{project_title}</a>". Termín pro rozhodnutí je do {award_expiration_date}.';

$config['fixed_budget_project_message_sent_to_sp_when_is_awarded_bid_by_po_company_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_company_name}</a> přijali vaši nabídku na "<a href="{project_url_link}" target="_blank">{project_title}</a>". Termín pro rozhodnutí je do {award_expiration_date}.';

########### activity log message when awarded bid by po expiration time is passed
// For PO
$config['fixed_budget_project_message_sent_to_po_when_awarded_bid_sp_male_expiration_time_passed_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> v čas neprovedl žádnou volbu na vaše udělení pro projekt "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_awarded_bid_sp_female_expiration_time_passed_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> v čas neprovedla žádnou volbu na vaše udělení pro projekt "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_awarded_bid_sp_company_app_male_expiration_time_passed_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> v čas neprovedl žádnou volbu na vaše udělení pro projekt "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_awarded_bid_sp_company_app_female_expiration_time_passed_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> v čas neprovedla žádnou volbu na vaše udělení pro projekt "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_awarded_bid_sp_company_expiration_time_passed_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> v čas neprovedli žádnou volbu na vaše udělení pro projekt "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

// For SP
$config['fixed_budget_project_message_sent_to_sp_when_awarded_bid_expiration_time_passed_user_activity_log_displayed_message'] = 'Termín pro přijetí udělení na projekt "<a href="{project_url_link}" target="_blank">{project_title}</a>" expiroval.';

### activity log message when cancelled the awarded project by admin for his awarded bid #####
$config['awarded_fixed_budget_project_cancelled_by_admin_message_sent_to_awarded_sp_user_activity_log_displayed_message'] = 'Inzerát projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>" je zrušený administrátorem a vaše nabídka je tímto neplatná.';

$config['awarded_fixed_budget_project_cancelled_by_admin_message_sent_to_po_user_activity_log_displayed_message'] = 'Inzerát projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>" je zrušený administrátorem a všechna udělení jsou tímto neplatná.';

############ activity log message when SP decline the awarded bid #####
// For SP
$config['fixed_budget_project_message_sent_to_sp_when_declined_awarded_bid_user_activity_log_displayed_message'] = 'Odmítli jste udělení na inzerát "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

//For PO
$config['fixed_budget_project_message_sent_to_po_when_sp_male_declined_awarded_bid_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> odmítl udělení na váš inzerát "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_sp_female_declined_awarded_bid_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> odmítla udělení na váš inzerát "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_sp_company_app_male_declined_awarded_bid_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> odmítl udělení na váš inzerát "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_sp_company_app_female_declined_awarded_bid_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> odmítla udělení na váš inzerát "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fixed_budget_project_message_sent_to_po_when_sp_company_declined_awarded_bid_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> odmítli udělení na váš inzerát "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

############ activity log message when sp accept the awarded bid #########
// For SP
$config['fixed_budget_project_message_sent_to_sp_when_accepted_awarded_bid_user_activity_log_displayed_message'] = 'Přijali jste udělení na inzerát "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

//For PO
$config['fixed_budget_project_message_sent_to_po_when_awarded_sp_male_accepted_awarded_bid_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> přijal udělení na váš inzerát "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_awarded_sp_female_accepted_awarded_bid_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> přijala udělení na váš inzerát "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_awarded_sp_company_app_male_accepted_awarded_bid_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> přijal udělení na váš inzerát "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fixed_budget_project_message_sent_to_po_when_awarded_sp_company_app_female_accepted_awarded_bid_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> přijala udělení na váš inzerát "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fixed_budget_project_message_sent_to_po_when_awarded_sp_company_accepted_awarded_bid_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> přijali udělení na váš inzerát "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


// activity log message when PO sent project mark complete request to SP.
// For PO
$config['fixed_budget_project_message_sent_to_po_when_po_created_mark_project_complete_request_user_activity_log_displayed_message'] = 'Odeslali jste žádost <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>, pro označení projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>" jako dokončený.';


// For SP
$config['fixed_budget_project_message_sent_to_sp_when_po_male_created_mark_project_complete_request_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> odeslal žádost pro označení projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>" jako dokončený.';

$config['fixed_budget_project_message_sent_to_sp_when_po_female_created_mark_project_complete_request_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> odeslala žádost pro označení projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>" jako dokončený.';


$config['fixed_budget_project_message_sent_to_sp_when_po_company_app_male_created_mark_project_complete_request_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> odeslal žádost pro oznacení projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>" jako dokoncený.';

$config['fixed_budget_project_message_sent_to_sp_when_po_company_app_female_created_mark_project_complete_request_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> odeslala žádost pro oznacení projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>" jako dokoncený.';


$config['fixed_budget_project_message_sent_to_sp_when_po_company_created_mark_project_complete_request_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_company_name}</a> odeslali žádost pro označení projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>" jako dokončený.';

###########################################################################
// activity log message when sp decline mark complete request.
// For po
$config['fixed_budget_project_message_sent_to_po_when_sp_male_declined_mark_project_complete_request_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> nesouhlasil s vaší žádostí pro označení projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>" jako dokončený.';

$config['fixed_budget_project_message_sent_to_po_when_sp_female_declined_mark_project_complete_request_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> nesouhlasila s vaší žádostí pro označení projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>" jako dokončený.';


$config['fixed_budget_project_message_sent_to_po_when_sp_company_app_male_declined_mark_project_complete_request_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> nesouhlasil s vaší žádostí pro označení projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>" jako dokončený.';

$config['fixed_budget_project_message_sent_to_po_when_sp_company_app_female_declined_mark_project_complete_request_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> nesouhlasila s vaší žádostí pro označení projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>" jako dokončený.';

$config['fixed_budget_project_message_sent_to_po_when_sp_company_declined_mark_project_complete_request_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> nesouhlasili s vaší žádostí pro označení projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>" jako dokončený.';

// For Sp
$config['fixed_budget_project_message_sent_to_sp_when_sp_declined_mark_project_complete_request_user_activity_log_displayed_message'] = 'Nesouhlasili jste s žádostí od <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> pro označení projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>" jako dokončený.';

#########################################################################################
// activity log message when SP accept project mark complete request.
// For Po
$config['fixed_budget_project_message_sent_to_po_when_sp_male_accepted_mark_project_complete_request_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> souhlasil s vaší žádostí pro označení projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>" jako dokončený.';


$config['fixed_budget_project_message_sent_to_po_when_sp_female_accepted_mark_project_complete_request_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> souhlasila s vaší žádostí pro označení projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>" jako dokončený.';


$config['fixed_budget_project_message_sent_to_po_when_sp_company_app_male_accepted_mark_project_complete_request_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> souhlasil s vaší žádostí pro označení projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>" jako dokončený.';


$config['fixed_budget_project_message_sent_to_po_when_sp_company_app_female_accepted_mark_project_complete_request_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> souhlasila s vaší žádostí pro označení projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>" jako dokončený';


$config['fixed_budget_project_message_sent_to_po_when_sp_company_accepted_mark_project_complete_request_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> souhlasili s vaší žádostí pro označení projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>" jako dokončený.';

// For Sp
$config['fixed_budget_project_message_sent_to_sp_when_sp_accepted_mark_project_complete_request_user_activity_log_displayed_message'] = 'Souhlasili jste s žádostí od <a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> pro označení projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>" jako dokončený.';

?>