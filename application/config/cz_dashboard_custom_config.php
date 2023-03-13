<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//Left navigation menu name
$config['pa_user_left_nav_dashboard'] = 'Přehled';
$config['ca_user_left_nav_dashboard'] = 'Přehled';
$config['pa_user_left_nav_send_feedback'] = 'Zpětná vazba';
$config['ca_user_left_nav_send_feedback'] = 'Zpětná vazba';

/*
|--------------------------------------------------------------------------
| Meta Variables
|--------------------------------------------------------------------------
|
*/

################ Meta Config Variables for dashboard page ###########
/* Filename: application\modules\user\controllers\dashboard.php */
/* Controller: user Method name: index */

$config['dashboard_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Přehled';
$config['dashboard_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Přehled';

################ Url Routing Variables for Dashboard page ###########
/* Filename: application\modules\dashboard\controllers\dashboard.php */
$config['dashboard_page_url'] = 'prehled';

//Dashboadrd Top Section
$config['dashboard_top_section_member'] = 'členství -';

$config['dashboard_top_section_manage_membership'] = 'Spravovat členství';

// This config is using to show the profile compeletion percentage with text of user on dashboard page
$config['user_profile_completion_percentage_dashboard_txt'] = '<strong>Váš profil je {user_profile_completion_percentage}% kompletní</strong>';


// This config is using when user never upgrade the membership (example login first/second time until not upgrade membership)
$config['dashboard_top_section_manage_membership_initial_view_no_upgrade_yet'] = '<span class="space_right">vylepšit na Perfektní</span><span>(Bonus 10 000 Kč)</span>';

$config['dashboard_top_section_add_hourly_rate'] = 'Přidat hodinovou sazbu';

$config['dashboard_top_section_add_address'] = 'Přidat adresu';

$config['dashboard_top_section_account_balance'] = 'Zůstatek na účtu:';

$config['dashboard_top_section_referral_code'] = 'Referenční kód:';

$config['dashboard_top_section_signup_bonus_balance'] = 'Registrační bonus:';

$config['dashboard_top_section_bonus_balance'] = 'Bonus zůstatek:';

// for user left nav
/* Filename: application\views\user_left_nav.php */

####################################################################################################

//Send Feedback Popup
$config['send_feedback_popup_heading_title_modal_body'] = 'Pošlete nám váš názor';

$config['send_feedback_popup_description_modal_body'] = 'Travai zkoumá a ověřuje příležitosti na základě vaší zpětné vazby. Zapojte se svými nápady, sdílejte své cíle, úspěchy, frustrace a nesplněné potřeby.';

$config['send_feedback_popup_disclaimer_modal_body'] = 'Vaše zpětná vazba, uživatelské jméno a e-mailová adresa, budou zaslány na adresu podpora@travai.cz. Více v <a href="{terms_and_conditions_page_url}" target="_blank">Obchodních podmínkách</a> a <a href="{privacy_policy_page_url}" target="_blank">Zásadách ochrany osobních údajů</a>.';

$config['send_feedback_popup_cancel_btn_modal_footer'] = 'Zrušit';

$config['send_feedback_popup_description_required_error_message'] = 'popis je povinné pole';
$config['send_feedback_popup_user_activity_log_mesage'] = 'Odeslali jste zpětnou vazbu.';

$config['send_feedback_popup_submit_confirmation_mesage'] = 'zpětná vazba byla odeslána';//this is realtime notification sent by php


$config['send_feedback_popup_upload_files_txt'] = 'Nahrát soubor';

$config['send_feedback_popup_attachment_invalid_file_extension_validation_message'] = "Typ souboru, který chcete nehrát, není podporován.";

$config['send_feedback_popup_user_upload_blank_attachment_alert_message'] = "Nelze nahrát prázdnou přílohu.";


$config['send_feedback_popup_attachment_allowed_file_types_js'] = '"png","PNG","gif","GIF","jpeg","JPEG","jpg","JPG","pdf","application/PDF","xls","xlsx","doc","docx","txt"'; 
$config['send_feedback_popup_attachment_allowed_file_types'] = 'image/*,.pdf,.xls, .xlsx, .doc, .docx, .txt */';


// variable for my project section on dashboard
$config['no_draft_project_message'] = 'momentálně není žádný inzerát uložený jako náhled';

$config['no_awaiting_moderation_project_message'] = 'momentálně není žádný inzerát čekající ke schválení';

$config['no_open_bidding_project_message'] = 'momentálně není žádný inzerát otevřený';

$config['no_awarded_project_message'] = 'momentálně není žádný inzerát ve stavu udělený';

$config['no_in_progress_project_message'] = 'momentálně není žádný inzerát ve stavu probíhající';

$config['no_incomplete_project_message'] = 'momentálně není žádný inzerát nedokončený';

$config['no_completed_project_message'] = 'momentálně není žádný inzerát dokončený';

$config['no_expired_project_message'] = 'momentálně není žádný vypršený inzerát';

$config['no_cancelled_project_message'] = 'momentálně není žádný zrušený inzerát';


//VARIABLES USED FOR sp VIEW
$config['no_active_bids_project_message'] = 'ještě nebyla odeslána žádná nabídka';

$config['no_awarded_bids_project_message'] = 'ještě nebyla udělena žádná nabídka';

$config['no_hired_application_project_message'] = 'momentálně nemáte přijetí na pracovní pozici';

$config['no_in_progress_bids_project_message'] = 'momentálně neprobíhá žádná práce';

$config['no_incomplete_bids_project_message'] = 'žádný projekt není nedokončený';

$config['no_completed_bids_project_message'] = 'momentálně není dokončená žádná práce';

############### defines custom config variables when project not available for dedicated table and PO perform activities
// config for draft tab option on dasboard/my project page
$config['project_draft_already_deleted_not_available_for_remove_dashboard_myprojects_po_view'] = 'Inzerát není k dispozici. Už byl smazán.';

$config['fulltime_project_draft_already_deleted_not_available_for_remove_dashboard_myprojects_po_view'] = 'Inzerát není k dispozici. Už byl smazán.';

$config['project_draft_status_changed_not_available_for_remove_dashboard_myprojects_po_view'] = 'Inzerát není k dispozici. Status byl změněn.';

$config['fulltime_project_draft_status_changed_not_available_for_remove_dashboard_myprojects_po_view'] = 'Inzerát není k dispozici. Status byl změněn.';

################ edit
$config['project_draft_already_deleted_not_available_for_edit_dashboard_myprojects_po_view'] = 'Inzerát není k dispozici. Už byl smazán.';

$config['fulltime_project_draft_already_deleted_not_available_for_edit_dashboard_myprojects_po_view'] = 'Inzerát není k dispozici. Už byl smazán.';

$config['project_draft_status_changed_not_available_for_edit_dashboard_myprojects_po_view'] = 'Inzerát není k dispozici. Status byl změněn.';

$config['fulltime_project_draft_status_changed_not_available_for_edit_dashboard_myprojects_po_view'] = 'Inzerát není k dispozici. Status byl změněn.';

####### publish
$config['project_draft_already_deleted_not_available_for_publish_dashboard_myprojects_po_view'] = 'Inzerát není k dispozici. Už byl smazán.';

$config['fulltime_project_draft_already_deleted_not_available_for_publish_dashboard_myprojects_po_view'] = 'Inzerát není k dispozici. Už byl smazán.';

$config['project_draft_status_changed_not_available_for_publish_dashboard_myprojects_po_view'] = 'Inzerát není k dispozici. Status byl změněn.';

$config['fulltime_project_draft_status_changed_not_available_for_publish_dashboard_myprojects_po_view'] = 'Inzerát není k dispozici. Status byl změněn.';


// config for open for bidding tab tab option on dasboard/my project page
// edit project
$config['project_open_for_bidding_admin_deleted_not_available_for_edit_dashboard_myprojects_po_view'] = 'Inzerát byl smazán administrátorem.';

$config['fulltime_project_open_for_bidding_admin_deleted_not_available_for_edit_dashboard_myprojects_po_view'] = 'Inzerát byl smazán administrátorem.';

$config['project_open_for_bidding_status_changed_not_available_for_edit_dashboard_myprojects_po_view'] = 'Inzerát není k dispozici. Status byl změněn.';

$config['fulltime_project_open_for_bidding_status_changed_not_available_for_edit_dashboard_myprojects_po_view'] = 'Inzerát není k dispozici. Status byl změněn.';


// for cancel open for bidding project
$config['project_open_for_bidding_admin_deleted_not_available_for_cancel_dashboard_myprojects_po_view'] = 'Inzerát byl smazán administrátorem.';

$config['fulltime_project_open_for_bidding_admin_deleted_not_available_for_cancel_dashboard_myprojects_po_view'] = 'Inzerát byl smazán administrátorem.';

$config['project_open_for_bidding_status_changed_not_available_for_cancel_dashboard_myprojects_po_view'] = 'Inzerát není k dispozici. Status byl změněn.';

$config['fulltime_project_open_for_bidding_status_changed_not_available_for_cancel_dashboard_myprojects_po_view'] = 'Inzerát není k dispozici. Status byl změněn.';


// this config is using when po trying to cancel the project from open for bidding tab and project have awarded bids
$config['fulltime_project_open_for_bidding_status_awarded_bids_exist_not_available_for_cancel_myprojects_po_view'] = 'Nelze zrušit inzerát, protože máte zahájené přijmutí zaměstnance.';


// for cancel expired project
$config['project_expired_admin_deleted_not_available_for_cancel_dashboard_myprojects_po_view'] = 'Inzerát byl smazán administrátorem.';

$config['fulltime_project_expired_admin_deleted_not_available_for_cancel_dashboard_myprojects_po_view'] = 'Inzerát byl smazán administrátorem.';


// for cancel expired project
$config['project_expired_status_changed_not_available_for_cancel_dashboard_myprojects_po_view'] = 'Inzerát není k dispozici. Status byl změněn.';

$config['fulltime_project_expired_status_changed_not_available_for_cancel_dashboard_myprojects_po_view'] = 'Inzerát není k dispozici. Status byl změněn.';

// this config is using when po trying to cancel the project from expired tab and project have awarded bids
$config['fulltime_project_expired_status_awarded_bids_exist_not_available_for_cancel_myprojects_po_view'] = 'Nelze zrušit inzerát, protože máte zahájené přijmutí zaměstnance.';


// for copy into new
$config['project_open_for_bidding_admin_deleted_not_available_for_copy_into_new_dashboard_myprojects_po_view'] = 'Inzerát byl smazán administrátorem.';

$config['fulltime_project_open_for_bidding_admin_deleted_not_available_for_copy_into_new_dashboard_myprojects_po_view'] = 'Inzerát byl smazán administrátorem.';

/* $config['project_open_for_bidding_status_changed_not_available_for_copy_into_new_dashboard_myprojects_po_view'] = 'Inzerát není k dispozici. Status byl změněn.';

$config['fulltime_project_open_for_bidding_status_changed_not_available_for_copy_into_new_dashboard_myprojects_po_view'] = 'Inzerát není k dispozici. Status byl změněn.'; */

### COPY INTO NEW OPTION WILL SHOW WHEN PROJECT CANCELLED BY ADMIN.
### REPOST OPTION WILL SHOW WHEN PROJECT CANCELLED BY USER.
## IF ADMIN DELETED THE CANCEL PROJECT THAT MEANS WE NEED TO SHOW ERROR POPUP MESSAGE BASED ON WHAT OPTION PO HAVE REPOST/COPY INTO NEW


//for copy into new
$config['project_cancelled_admin_deleted_not_available_for_copy_into_new_dashboard_myprojects_po_view'] = 'Inzerát byl smazán administrátorem.';

$config['fulltime_project_cancelled_admin_deleted_not_available_for_copy_into_new_dashboard_myprojects_po_view'] = 'Inzerát byl smazán administrátorem.';

//for repost
$config['project_cancelled_admin_deleted_not_available_for_repost_dashboard_myprojects_po_view'] = 'Inzerát byl smazán administrátorem.';

$config['fulltime_project_cancelled_admin_deleted_not_available_for_repost_dashboard_myprojects_po_view'] = 'Inzerát byl smazán administrátorem.';

##################################################################################

$config['project_expired_admin_deleted_not_available_for_repost_dashboard_myprojects_po_view'] = 'Inzerát byl smazán administrátorem.';

$config['fulltime_project_expired_admin_deleted_not_available_for_repost_dashboard_myprojects_po_view'] = 'Inzerát byl smazán administrátorem.';

/* $config['project_expired_status_changed_not_available_for_repost_dashboard_myprojects_po_view'] = 'Inzerát není k dispozici. Status byl změněn.';

$config['fulltime_project_expired_status_changed_not_available_for_repost_dashboard_myprojects_po_view'] = 'Inzerát není k dispozici. Status byl změněn.'; */

#######################################################

// for upgrade
$config['project_open_for_bidding_admin_deleted_not_available_for_upgrade_dashboard_myprojects_po_view'] = 'Inzerát byl smazán administrátorem.';

$config['fulltime_project_open_for_bidding_admin_deleted_not_available_for_upgrade_dashboard_myprojects_po_view'] = 'Inzerát byl smazán administrátorem.';

$config['project_open_for_bidding_status_changed_not_available_for_upgrade_dashboard_myprojects_po_view'] = 'Inzerát není k dispozici. Status byl změněn.';

$config['fulltime_project_open_for_bidding_status_changed_not_available_for_upgrade_dashboard_myprojects_po_view'] = 'Inzerát není k dispozici. Status byl změněn.';

// for upgrade as urgent - when project is already featured
$config['project_open_for_bidding_admin_deleted_not_available_for_upgrade_as_urgent_dashboard_myprojects_po_view'] = 'Inzerát byl smazán administrátorem.';

$config['fulltime_project_open_for_bidding_admin_deleted_not_available_for_upgrade_as_urgent_dashboard_myprojects_po_view'] = 'Inzerát byl smazán administrátorem.';

$config['project_open_for_bidding_status_changed_not_available_for_upgrade_as_urgent_dashboard_myprojects_po_view'] = 'Inzerát není k dispozici. Status byl změněn.';

$config['fulltime_project_open_for_bidding_status_changed_not_available_for_upgrade_as_urgent_dashboard_myprojects_po_view'] = 'Inzerát není k dispozici. Status byl změněn.';

###############################

//for upgrade as featured - when project is already urgent
$config['project_open_for_bidding_admin_deleted_not_available_for_upgrade_as_featured_dashboard_myprojects_po_view'] = 'Inzerát byl smazán administrátorem.';

$config['fulltime_project_open_for_bidding_admin_deleted_not_available_for_upgrade_as_featured_dashboard_myprojects_po_view'] = 'Inzerát byl smazán administrátorem.';

$config['project_open_for_bidding_status_changed_not_available_for_upgrade_as_featured_dashboard_myprojects_po_view'] = 'Inzerát není k dispozici. Status byl změněn.';

$config['fulltime_project_open_for_bidding_status_changed_not_available_for_upgrade_as_featured_dashboard_myprojects_po_view'] = 'Inzerát není k dispozici. Status byl změněn.';


// prolong urgent
$config['project_open_for_bidding_admin_deleted_not_available_for_prolong_urgent_upgrade_dashboard_myprojects_po_view'] = 'Inzerát byl smazán administrátorem.';

$config['fulltime_project_open_for_bidding_admin_deleted_not_available_for_prolong_urgent_upgrade_dashboard_myprojects_po_view'] = 'Inzerát byl smazán administrátorem.';

$config['project_open_for_bidding_status_changed_not_available_for_prolong_urgent_upgrade_dashboard_myprojects_po_view'] = 'Inzerát není k dispozici. Status byl změněn.';

$config['fulltime_project_open_for_bidding_status_changed_not_available_for_prolong_urgent_upgrade_dashboard_myprojects_po_view'] = 'Inzerát není k dispozici. Status byl změněn.';

#################################################################################

// prolong featured
$config['project_open_for_bidding_admin_deleted_not_available_for_prolong_featured_upgrade_dashboard_myprojects_po_view'] = 'Inzerát byl smazán administrátorem.';

$config['fulltime_project_open_for_bidding_admin_deleted_not_available_for_prolong_featured_upgrade_dashboard_myprojects_po_view'] = 'Inzerát byl smazán administrátorem.';

$config['project_open_for_bidding_status_changed_not_available_for_prolong_featured_upgrade_dashboard_myprojects_po_view'] = 'Inzerát není k dispozici. Status byl změněn.';

$config['fulltime_project_open_for_bidding_status_changed_not_available_for_prolong_featured_upgrade_dashboard_myprojects_po_view'] = 'Inzerát není k dispozici. Status byl změněn.';

#####################################################################################

$config['project_featured_upgrade_txt_po_dashboard_myprojects_section_view'] = 'Zvýrazněný:';

$config['project_featured_upgrade_expires_on_txt_po_dashboard_myprojects_section_view'] = 'Platnost do';

$config['project_featured_upgrade_prolong_availability_txt_po_dashboard_myprojects_section_view'] = 'Prodloužení platnosti';



$config['project_urgent_upgrade_txt_po_dashboard_myprojects_section_view'] = 'Urgentní:';

$config['project_urgent_upgrade_expires_on_txt_po_dashboard_myprojects_section_view'] = 'Platnost do';

$config['project_urgent_upgrade_prolong_availability_txt_po_dashboard_myprojects_section_view'] = 'Prodloužení platnosti';

#####################################################################################

//For invite friend section on dashboard
$config['dashboard_invite_friends_section_heading'] = 'Pozvání přátel - Budování a rozšiřování sítě kontaktů';


//For contact list section on dashboard
$config['dashboard_contact_list_section_heading'] = 'Kontakty';

//For latest project section on dashboard
$config['dashboard_latest_projects_section_heading'] = 'Nejnovější inzeráty';
$config['dashboard_latest_projects_section_refresh_list_btn_txt'] = 'aktualizovat';


//project status when project cancelled by admin on my projects section on dashboard
$config['dashboard_my_projects_section_project_status_cancelled_by_admin'] = 'Zrušeno administrátorem';


//profile completion parameter based on user fill the information or complete his profile
//texts from invite friends via email for dashboard
$config['dashboard_invite_friends_for_email_contacts'] = 'Vložit emailové adresy';

$config['dashboard_invite_friends_for_separate_with_spaces_commas'] = '(potvrzení emailu použijte klávesu Enter)';

$config['dashboard_invite_friends_better_visibility_and_more_invitations'] = 'Pro budování a rozšiřování vašich doporučení (a zvyšování vašeho pasivního příjmu), sdílejte svůj soukromý odkaz URL prostřednictvím nejznámějších sociálních sítí uvedených níže';

$config['dashboard_invite_friends_for_send_invitations_btn_txt'] = 'Odeslat pozvání';


$config['dashboard_invite_friends_for_copy_url'] = 'Kopírovat';

$config['dashboard_invite_friends_email_contacts_tooltip'] = 'pošlete e-mailové pozvání své rodině, přátelům, kolegům, zaměstnancům, partnerům, aby se připojili k Travai.cz a vaší síti doporučení. pokaždé, kdy uživatelé z vaší sítě nakupují služby na portálu, my Travai se s vámi podělíme procentami (%) z příjmů každého nákupu služeb. čím více lidí ve vaší síti doporučení, tím vyšší je šance na zvýšení vašeho generovaného pasivního příjmu. odesílání pozvání není omezené (ale dbejte na pravidla související se SPAMingem).';

$config['dashboard_invite_friends_your_url_tooltip'] = 'tato URL adresa patří k vašemu Travai účtu. sdílejte URL adresu prostřednictvím sociálních sítí, messengeru, whatsappu, skypu a dalších... v okamžiku kdy se kdokoli na Travai zaregistruje pomocí vaší URL adresy, stane se součástí vaší sítě doporučení. pokaždé, kdy uživatelé z vaší sítě nakupují služby na portálu, my Travai se s vámi podělíme procentami (%) z příjmů každého nákupu služeb. čím více lidí ve vaší síti doporučení, tím vyšší je šance na zvýšení vašeho generovaného pasivního příjmu. URL adresu sdílejte kolikrát chcete, s tolika lidmi, kolik si přejete.';


//For Dashboard Right Side Chat Section When No chat
$config['dashboard_contacts_list_no_record'] = '<h5>Momentálně nemáte žádné kontakty.</h5><p>Zde budou zobrazeny vaše spojení.</p>';

?>