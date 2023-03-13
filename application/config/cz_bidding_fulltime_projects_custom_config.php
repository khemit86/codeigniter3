<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['project_details_page_fulltime_project_bid_form_bid'] = 'Očekávaná mzda';

$config['project_details_page_fulltime_project_bid_form_tooltip_message_bid_amount'] = 'zde zadejte svojí očekávanou mzdu, která vás nijak nezavazuje';

$config['project_details_page_fulltime_bid_form_description_section_heading'] = 'Detail žádosti';

$config['project_details_page_fulltime_project_bid_form_place_application_btn_txt'] = 'Odeslat';

$config['project_details_page_fulltime_project_bid_form_cancel_application_btn_txt'] = 'Zrušit';

// validation message for bid form
$config['project_details_page_bid_amount_validation_fulltime_project_bid_form_message'] = 'částka je vyžadována';

$config['project_details_page_bid_description_validation_fulltime_project_bid_form_message'] = 'popis žádosti je povinný';


$config['project_details_page_minimum_required_salary_amount_validation_fulltime_project_application_form_message'] = 'částka nemůže být nižší než {project_minimum_salary_amount}';

$config['project_details_page_bid_posted_confirmation_fulltime_project_bid_form_message'] = 'žádost byla vytvořena';

$config['project_details_page_bid_updated_confirmation_fulltime_project_bid_form_message'] = 'žádost byla upravena';

$config['project_details_page_fulltime_project_active_bidder_listing_edit_bid_btn_txt'] = 'Upravit žádost';

$config['project_details_page_fulltime_project_active_bidder_listing_retract_bid_btn_txt'] = 'Stáhnout žádost';

$config['retract_bid_confirmation_fulltime_project_modal_retract_btn_txt'] = 'Stáhnout žádost';

$config['project_details_page_fulltime_project_active_bidder_listing_award_bid_bid_btn_txt'] = 'Zaměstnat';

$config['project_details_page_fulltime_project_bid_form_edit_application_btn_txt'] = 'Upravit žádost';

//$config['project_details_page_bidder_listing_expected_salary_txt'] = "očekávaná mzda";

$config['fulltime_project_details_page_inprogress_awaiting_acceptance_fulltime_project_tab_employee_view_txt'] = 'Moje žádost';

$config['fulltime_project_details_page_hired_fulltime_project_tab_employee_view_txt'] = 'Moje zaměstnání';

$config['fulltime_project_details_page_awaiting_acceptance_applications_list_singular'] = 'Vybraná žádost';

$config['fulltime_project_details_page_awaiting_acceptance_applications_list_plural'] = 'Vybrané žádosti';

$config['fulltime_project_details_page_hired_applications_list_singular'] = 'Zaměstnanec';

$config['fulltime_project_details_page_hired_applications_list_plural'] = 'Zaměstnanci';

//my projects+user profile page+project details page - employee view
$config['fulltime_projects_employee_view_project_value'] = '<span class="fulltime_projects_employee_view_project_value_color">přijaté platby</span>';

$config['fulltime_projects_employer_view_total_project_value'] = '<span class="employer_view_total_project_value_color">provedené platby</span>';

// variable for confirmation popup for retract application
$config['retract_application_confirmation_fulltime_project_modal_body'] = 'Opravdu chcete stáhnout žádost?';

$config['project_details_page_retract_application_confirmation_fulltime_project_application_form_message'] = 'žádost byla stažena';

############ activity log message when service provider post the application
// variable for confirmation confirmation popup for accept application for service provider
$config['accept_award_confirmation_fulltime_project_modal_body_employee_view'] = 'Pokračováním souhlasíte s udělením pracovní pozice a budete zaměstnáni.';

// variable for confirmation confirmation popup for decline bid for service provider
$config['decline_award_confirmation_fulltime_project_modal_body_employee_view'] = 'Pokračováním nesouhlasíte s udělením pracovní pozice a nebudete zaměstnáni.';

############ activity log message when project owner award the application
// For ER
$config['fulltime_project_message_sent_to_employer_when_employer_awarded_application_user_activity_log_displayed_message'] = 'Souhlasili jste s žádostí od <a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> na vaši pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

// For EE
$config['fulltime_project_message_sent_to_employee_when_employer_male_awarded_application_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> souhlasil s vaší žádostí na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>". Termín pro rozhodnutí máte do {award_expiration_date}.';

$config['fulltime_project_message_sent_to_employee_when_employer_female_awarded_application_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> souhlasila s vaší žádostí na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>". Termín pro rozhodnutí máte do {award_expiration_date}.';

$config['fulltime_project_message_sent_to_employee_when_employer_company_app_male_awarded_application_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> souhlasil s vaší žádostí na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>". Termín pro rozhodnutí máte do {award_expiration_date}.';

$config['fulltime_project_message_sent_to_employee_when_employer_company_app_female_awarded_application_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_first_name_last_name}</a> souhlasila s vaší žádostí na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>". Termín pro rozhodnutí máte do {award_expiration_date}.';

$config['fulltime_project_message_sent_to_employee_when_employer_company_awarded_application_user_activity_log_displayed_message'] = '<a href="{po_profile_url_link}" target="_blank">{user_company_name}</a> souhlasili s vaší žádostí na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>". Termín pro rozhodnutí máte do {award_expiration_date}.';

//fulltime award
$config['award_application_confirmation_fulltime_project_modal_body_employer_view'] = 'Pokračováním zaměstnáte {user_first_name_last_name_or_company_name} na vaši pracovní pozici "{fulltime_project_title}" za <span class="default_popup_price">{requested_salary_value} '.CURRENCY.'/měs</span>.';

$config['award_application_confirmation_fulltime_project_modal_body_employer_view_confidential'] = 'Pokračováním zaměstnáte {user_first_name_last_name_or_company_name} na vaši pracovní pozici "{fulltime_project_title}".';

$config['award_application_confirmation_fulltime_project_modal_body_employer_view_to_be_agreed'] = 'Pokračováním zaměstnáte {user_first_name_last_name_or_company_name} na vaši pracovní pozici "{fulltime_project_title}".';

$config['award_application_confirmation_fulltime_project_modal_award_btn_txt_employer_view'] = 'Zaměstnat';

//############ activity log message when new application
// For ER
$config['fulltime_project_message_sent_to_employer_when_new_application_received_from_employee_male_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> odeslal žádost na vaši pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employer_when_new_application_received_from_employee_female_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> odeslala žádost na vaši pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employer_when_new_application_received_from_employee_company_app_male_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> odeslal žádost na vaši pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employer_when_new_application_received_from_employee_company_app_female_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> odeslala žádost na vaši pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fulltime_project_message_sent_to_employer_when_new_application_received_from_employee_company_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> odeslali žádost na vaši pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

// For EE
$config['fulltime_project_message_sent_to_employee_when_new_application_placed_from_employee_user_activity_log_displayed_message'] = 'Odeslali jste žádost na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

############ activity log message when service provider retract the application
// For ER
$config['fulltime_project_message_sent_to_employer_when_employee_male_retracted_application_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> stáhl žádost na vaši pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employer_when_employee_female_retracted_application_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> stáhla žádost na vaši pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fulltime_project_message_sent_to_employer_when_employee_company_app_male_retracted_application_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> stáhl žádost na vaši pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employer_when_employee_company_app_female_retracted_application_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> stáhla žádost na vaši pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fulltime_project_message_sent_to_employer_when_employee_company_retracted_application_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> stáhli žádost na vaši pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

// For EE
$config['fulltime_project_message_sent_to_employee_when_employee_retracted_application_user_activity_log_displayed_message'] = 'Stáhli jste žádost na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

############ activity log message when service provider edit the application
// For ER
$config['fulltime_project_message_sent_to_employer_when_employee_male_edit_application_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> upravil žádost na vaši pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employer_when_employee_female_edit_application_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> upravila žádost na vaši pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employer_when_employee_company_app_male_edit_application_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> upravil žádost na vaši pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employer_when_employee_company_app_female_edit_application_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> upravila žádost na vaši pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employer_when_employee_company_edit_application_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> upravili žádost na vaši pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

// For EE
$config['fulltime_project_message_sent_to_employee_when_employee_edit_application_user_activity_log_displayed_message'] = 'Upravili jste žádost na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

############ activity log message when service provider decline the awarded application
// For ER
$config['fulltime_project_message_sent_to_employer_when_employee_male_declined_award_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> nesouhlasil s udělením vaší pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employer_when_employee_female_declined_award_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> nesouhlasila s udělením vaší pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employer_when_employee_company_app_male_declined_award_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> nesouhlasil s udělením vaší pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employer_when_employee_company_app_female_declined_award_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> nesouhlasila s udělením vaší pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fulltime_project_message_sent_to_employer_when_employee_company_declined_award_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> nesouhlasili s udělením vaší pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

// For EE
$config['fulltime_project_message_sent_to_employee_when_employee_declined_award_user_activity_log_displayed_message'] = 'Nesouhlasili jste s udělením pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

############ activity log message when service provider accept the awarded application
// For ER
$config['fulltime_project_message_sent_to_employer_when_awarded_employee_male_accepted_awarded_application_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> souhlasil s udělením vaší pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employer_when_awarded_employee_female_accepted_awarded_application_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> souhlasila s udělením vaší pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fulltime_project_message_sent_to_employer_when_awarded_employee_company_app_male_accepted_awarded_application_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> souhlasil s udělením vaší pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employer_when_awarded_employee_company_app_female_accepted_awarded_application_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> souhlasila s udělením vaší pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fulltime_project_message_sent_to_employer_when_awarded_employee_company_accepted_awarded_application_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> souhlasili s udělením vaší pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

// For EE
$config['fulltime_project_message_sent_to_employee_when_employee_accepted_awarded_application_user_activity_log_displayed_message'] = 'Souhlasili jste s udělením pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>". Jste zaměstnáni.';

############################################################################################################
$config['project_details_page_sp_view_place_bid_expired_fulltime_project'] = "Inzerát vypršel. Žádost nelze odeslat.";

$config['project_details_page_sp_view_place_bid_upload_bid_attachment_expired_fulltime_project'] = "Inzerát vypršel. Příloha nelze nahrát.";

$config['project_details_page_sp_view_place_bid_delete_bid_attachment_expired_fulltime_project'] = "Inzerát vypršel. Příloha nelze smazat.";


//cancelled fulltime
$config['project_details_page_sp_view_place_bid_cancelled_fulltime_project'] = "Inzerát byl zrušen. Nelze odeslat žádost.";

$config['project_details_page_sp_view_place_bid_upload_bid_attachment_cancelled_fulltime_project'] = "Inzerát byl zrušen. Příloha nelze nahrát.";

$config['project_details_page_sp_view_place_bid_open_bid_attachment_cancelled_fulltime_project'] = "Inzerát byl zrušen. Příloha nelze otevřít.";

$config['project_details_page_sp_view_place_bid_delete_bid_attachment_cancelled_fulltime_project'] = "Inzerát byl zrušen. Příloha nelze smazat.";


$config['project_details_page_sp_view_update_bid_delete_bid_attachment_cancelled_fulltime_project'] = "Inzerát byl zrušen. Příloha nelze smazat.";

$config['project_details_page_sp_view_update_bid_upload_bid_attachment_cancelled_fulltime_project'] = "Inzerát byl zrušen. Příloha nelze nahrát.";

$config['project_details_page_sp_view_update_bid_open_bid_attachment_cancelled_fulltime_project'] = "Inzerát byl zrušen. Příloha nelze otevřít.";

$config['project_details_page_sp_view_update_bid_cancelled_fulltime_project'] = "Inzerát byl zrušen. Nelze upravit žádost.";

$config['project_details_page_sp_view_retract_bid_cancelled_fulltime_project'] = "Inzerát byl zrušen. Nelze stáhnout žádost.";

$config['project_details_page_sp_view_accept_award_cancelled_fulltime_project'] = "Inzerát byl zrušen. Nelze přijmout zaměstnání.";

$config['project_details_page_sp_view_decline_award_cancelled_fulltime_project'] = "Inzerát byl zrušen. Nelze odmítnout zaměstnání.";

$config['project_details_page_po_view_award_bid_cancelled_fulltime_project'] = "Inzerát byl zrušen. Pracovní pozice nelze udělit.";


$config['project_details_page_sp_view_place_bid_already_posted_bid_same_fulltime_project'] = "Již jste poslali žádost na tuto pracovní pozici.";

$config['project_details_page_sp_view_place_bid_delete_bid_attachment_awarded_same_sp_fulltime_project'] = "Pracovní pozice vám byla udělena. Příloha nelze smazat.";

$config['project_details_page_sp_view_update_bid_delete_bid_attachment_awarded_same_sp_fulltime_project'] = "Pracovní pozice vám byla udělena. Příloha nelze smazat.";


$config['project_details_page_sp_view_place_bid_delete_bid_attachment_already_posted_same_sp_fulltime_project'] = "Již jste poslali žádost na tuto pracovní pozici. Příloha nelze smazat.";

$config['project_details_page_sp_view_place_bid_upload_bid_attachment_already_posted_bid_fulltime_project'] = "Již jste poslali žádost na tuto pracovní pozici. Příloha nelze nahrát.";

$config['project_details_page_sp_view_update_bid_upload_bid_attachment_awarded_same_sp_fulltime_project'] = "Pracovní pozice vám byla udělena. Příloha nelze nahrát.";


$config['project_details_page_sp_view_retract_bid_already_retracted_fulltime_project'] = "Žádost již byla stažena. Nelze stáhnout žádost.";

$config['project_details_page_sp_view_update_bid_already_retracted_fulltime_project'] = "Žádost již byla stažena. Nelze upravit žádost.";

$config['project_details_page_sp_view_update_bid_already_retracted_upload_bid_attachment_same_sp_fulltime_project'] = "Žádost již byla stažena. Nelze nahrát přílohu.";

$config['project_details_page_sp_view_update_bid_already_retracted_open_bid_attachment_same_sp_fulltime_project'] = "Žádost již byla stažena. Nelze otevřít přílohu.";


//fulltime award - same SP action
$config['project_details_page_sp_view_place_bid_awarded_same_sp_fulltime_project'] = "Pracovní pozice vám byla udělena. Žádost nelze odeslat.";

$config['project_details_page_sp_view_place_bid_upload_bid_attachment_awarded_same_sp_fulltime_project'] = "Pracovní pozice vám byla udělena. Nelze nahrát přílohu.";


//fulltime HIRED - SP action
$config['project_details_page_sp_view_place_bid_in_progress_same_sp_fulltime_project'] = "Nyní jste zaměstnáni na tuto pracovní pozici. Nelze odeslat žádost.";

$config['project_details_page_sp_view_place_bid_upload_bid_attachment_in_progress_same_sp_fulltime_project'] = "Nyní jste zaměstnáni na tuto pracovní pozici. Nelze nahrát přílohu.";

$config['project_details_page_sp_view_place_bid_delete_bid_attachment_in_progress_same_sp_fulltime_project'] = "Nyní jste zaměstnáni na tuto pracovní pozici. Nelze smazat přílohu.";


$config['project_details_page_sp_view_update_bid_upload_bid_attachment_in_progress_same_sp_fulltime_project'] = "Nyní jste zaměstnáni na tuto pracovní pozici. Nelze nahrát přílohu.";


$config['project_details_page_sp_view_update_bid_awarded_same_sp_fulltime_project'] = "Byli jste vybráni na pracovní pozici. Žádost nelze upravit.";


$config['project_details_page_sp_view_retract_bid_awarded_same_sp_fulltime_project'] = "Byli jste vybráni na pracovní pozici. Žádost nelze stáhnout.";

$config['project_details_page_sp_view_retract_bid_in_progress_same_sp_fulltime_project'] = "Nyní jste zaměstnáni na tuto pracovní pozici. Nelze stáhnout žádost.";

$config['project_details_page_sp_view_update_bid_in_progress_same_sp_fulltime_project'] = "Nyní jste zaměstnáni na tuto pracovní pozici. Nelze upravit žádost.";

$config['project_details_page_sp_view_update_bid_delete_bid_attachment_in_progress_same_sp_fulltime_project'] = "Nyní jste zaměstnáni na tuto pracovní pozici. Nelze smazat přílohu.";


//PO view
$config['project_details_page_po_view_award_bid_already_awarded_same_sp_fulltime_project'] = 'Již je udělena pracovní pozice pro <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name_or_company_name}</a>.';

$config['project_details_page_po_view_award_bid_in_progress_same_sp_fulltime_project'] = "Již zaměstnáváte tuto osobu. Nelze provést tuto volbu.";

$config['project_details_page_po_view_award_bid_already_retracted_fulltime_project'] = "Žádost byla stažena. Nelze provést tuto volbu.";

################ error message when trying to accept the awarded bid
// when some unauthorized user(PO/unauthrizedSP) trying to accept bid
$config['project_details_page_sp_view_accept_award_application_already_retracted_fulltime_project'] = "Žádost byla stažena. Nelze přijmout udělení.";

$config['project_details_page_sp_view_decline_award_already_retracted_fulltime_project'] = "Žádost byla stažena. Nelze odmítnout udělení.";

$config['project_details_page_sp_view_validation_fulltime_project_accept_award_award_acceptance_deadline_already_expired_message'] = "Nelze provést tuto volbu. Termín pro přijetí expiroval.";

$config['project_details_page_sp_view_decline_award_award_already_declined_or_expired_fulltime_project'] = "Nelze provést tuto volbu. Termín pro přijetí expiroval.";

$config['project_details_page_sp_view_accept_award_award_already_declined_or_expired_fulltime_project'] = "Nelze provést tuto volbu. Termín pro přijetí expiroval.";


$config['project_details_page_sp_view_try_decline_award_in_progress_fulltime_project'] = "Již jste zaměstnáni na tuto pracovní pozici. Nelze odmítnout pracovní pozici.";

$config['project_details_page_sp_view_accept_award_in_progress_fulltime_project'] = "Již jste zaměstnáni na tuto pracovní pozici. Nelze přijmout pracovní pozici.";

################ config for download the bid attachments from active bids/awarded bids/in progress bids
// config when sp trying to open bid attachments from bidder list project status is open for bidding but attachment not exists or corrupted
$config['project_details_page_open_for_bidding_project_bidder_list_open_bid_attachment_not_exist_validation_message_bidder_view_fulltime_project'] = "Došlo k chybě. Nahrajte přílohu znovu.";

// Config is used when sp trying to open the bid attachment from bidder list and project status is expired
$config['project_details_page_expired_project_bidder_list_open_bid_attachment_not_exist_validation_message_bidder_view_fulltime_project'] = "Došlo k chybě. Nahrajte přílohu znovu.";

$config['fulltime_project_details_page_sp_awarded_status_sp_try_open_bid_attachment_not_exist_validation_message_bidder_view'] = "Příloha žádosti neexistuje.";

$config['project_details_page_bid_form_bid_attachment_not_exist_validation_message_bidder_view_fulltime_project'] = "Došlo k chybě. Smažte přílohu a nahrajte znovu.";

$config['project_details_page_cancelled_fulltime_project_bidder_list_open_bid_attachment_not_exist_validation_message_sp_po_view'] = "Inzerát je zrušený. Nelze otevřít přílohu.";//this config is using as common for sp/po when they are trying to open bid atatchments from bidding list and in the background project hasbeen cancelled

// Config is used when po trying to open the bid attachment from bidder list and project status is expired
$config['project_details_page_expired_project_bidder_list_open_bid_attachment_not_exist_validation_message_po_view_fulltime_project'] = "Příloha žádosti neexistuje.";

// config is using when attachment is not exists and project is in open for bidding (applicable for when PO trying to open atatchment from awarded SP) status and po trying to open attachment from bidder list
$config['project_details_page_open_for_bidding_project_bidder_list_open_bid_attachment_not_exist_validation_message_po_view_fulltime_project'] = "Příloha žádosti neexistuje.";

$config['project_details_page_bidder_listing_expected_salary_txt'] = '<span class="bidder_listing_expected_salary_txt_color">očekávaná mzda</span>';

############ activity log message when awarded bid by project owner expiration time is passed

$config['fulltime_project_message_sent_to_employer_when_awarded_bid_employee_male_expiration_time_passed_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> v čas neprovedl žádnou volbu na vaše udělení na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employer_when_awarded_bid_employee_female_expiration_time_passed_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> v čas neprovedla žádnou volbu na vaše udělení na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fulltime_project_message_sent_to_employer_when_awarded_bid_employee_company_app_male_expiration_time_passed_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> v čas neprovedl žádnou volbu na vaše udělení na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_message_sent_to_employer_when_awarded_bid_employee_company_app_female_expiration_time_passed_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_first_name_last_name}</a> v čas neprovedla žádnou volbu na vaše udělení na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fulltime_project_message_sent_to_employer_when_awarded_bid_employee_company_expiration_time_passed_user_activity_log_displayed_message'] = '<a href="{sp_profile_url_link}" target="_blank">{user_company_name}</a> v čas neprovedli žádnou volbu na vaše udělení na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fulltime_project_message_sent_to_employee_when_awarded_bid_expiration_time_passed_user_activity_log_displayed_message'] = 'Termín pro přijetí udělení na pracovní pozici "<a href="{project_url_link}" target="_blank">{project_title}</a>" expiroval.';

############ activity log message when cancelled the awarded project by admin and SP recived notification for his awarded bid
$config['fulltime_project_cancelled_by_admin_awarded_user_activity_log_displayed_message_sent_to_po'] = 'Inzerát pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>" je zrušený administrátorem a všechna zaměstnání jsou tímto neplatná.';
$config['fulltime_project_cancelled_by_admin_awarded_user_activity_log_displayed_message_sent_to_awarded_sp'] = 'Inzerát pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>" je zrušený administrátorem a vaše zaměstnání je tímto neplatné.';

// for expired project
$config['fulltime_expired_project_cancelled_by_admin_awarded_user_activity_log_displayed_message_sent_to_po'] = 'Inzerát pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>" je zrušený administrátorem a všechna zaměstnání jsou tímto neplatná.';

$config['fulltime_expired_project_cancelled_by_admin_awarded_user_activity_log_displayed_message_sent_to_awarded_sp'] = 'Inzerát pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>" je zrušený administrátorem a vaše zaměstnání je tímto neplatné.';


######## These config variables using on those files where in progress bidding listing is showing
$config['fulltime_project_hired_sp_employment_start_date'] = '<span class="fulltime_project_hired_sp_employment_start_date_color">datum zaměstnání</span>';

?>