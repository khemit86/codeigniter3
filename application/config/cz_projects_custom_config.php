<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//Left navigation menu name
$config['pa_user_left_nav_project_management'] = 'Moje činnosti';
$config['ca_user_left_nav_project_management'] = 'Naše činnosti';

$config['projects_management_left_nav_my_projects'] = 'Přehled činností';

################ Url Routing Variables for payments section on project detail page ###########

$config['my_projects_page_url'] = 'load_myprojects';
################ Meta Config Variables for project not exist 404 page ###########
/* Filename: application\modules\projects\vies\project_not_existent_404.php */
$config['project_not_existent_404_page_heading'] = 'Stránka je nedostupná...';

$config['project_not_existent_404_page_message_without_login'] = 'Odkaz, na který jste klikli je nefunkční. Stránka inzerátu neexistuje.<br><br>Pro pokračování <a href="/">klikněte zde...</a>';

$config['project_not_existent_404_page_message_with_login'] = 'Odkaz, na který jste klikli je nefunkční. Stránka inzerátu neexistuje.<br><br>Pro pokračování <a href="/">klikněte zde...</a>';

########## Meta config/ description for hidden project 
$config['hidden_project_page_title_meta_tag'] = 'Travai.cz - Přístup odepřen!';
$config['hidden_project_page_description_meta_tag'] = 'Travai.cz - Přístup odepřen!';

$config['hidden_project_page_heading'] = 'Přístup odepřen!';

$config['hidden_project_page_message_without_login'] = 'Nemáte oprávnění k nahlížení tohoto inzerátu.<br><br>Pro pokračování <a href="/">klikněte zde...</a>';

$config['hidden_project_page_message_with_login'] = 'Nemáte oprávnění k nahlížení tohoto inzerátu.<br><br>Pro pokračování <a href="/">klikněte zde...</a>';

##################################################################
$config['project_details_page_listing_id'] = 'ID:';

$config['project_details_page_history'] = 'Historie:';

$config['project_details_page_revisions'] = 'Úpravy:';

$config['project_details_page_report_violation'] = 'Nahlásit porušení pravidel';

$config['project_details_page_views'] = 'zobrazení';

################ Url Routing Variables for edit project draft page ###########
/* Filename: application\modules\projects\controllers\Projects.php */
/* Filename: application\modules\dashboard\views\user_dashboard.php */
$config['edit_draft_project_page_url'] = 'upravit-navrh';

/* Filename: application\modules\projects\controllers\Projects.php */
/* Filename: application\modules\projects\views\edit_draft_project.php */
$config['preview_draft_project_page_url'] = 'nahled-navrhu';

/* Filename: application\modules\projects\controllers\Projects.php */
$config['project_detail_page_url'] = 'inzerat';

$config['edit_project_page_url'] = 'upravit-inzerat';

$config['edit_draft_project_page_heading'] = 'Upravit návrh';

$config['edit_draft_fulltime_project_page_heading'] = 'Upravit návrh';

//the following 2 variables are used for edit project/fulltime heading
$config['edit_project_page_heading'] = 'Upravit projekt';

$config['edit_fulltime_project_page_heading'] = 'Upravit pracovní pozici';

$config['refresh_page_validation_message'] = "Zřejmě došlo k chybě. Obnovte stránku.";

####################################################################################################################
//PROJECT ADDITIONAL INFORMATION LENGTH AND REQUIREMENTS
$config['project_details_page_additional_information'] = 'Doplňující informace';

$config['project_details_page_updated_on'] = 'doplněno dne:';

$config['project_apply_now_button_txt'] = 'Poslat nabídku';

$config['fulltime_project_apply_now_button_txt'] = 'Poslat žádost';

##################################################
//This variable is using when project is cancelled by po and po trying to upload/edit/save project covered picture
$config['project_details_page_featured_project_status_changed_error_message'] = "Stav inzerátu byl změněn. Nelze provést tuto volbu.";

$config['project_details_page_featured_fulltime_project_status_changed_error_message'] = "Stav inzerátu byl změněn. Nelze provést tuto volbu.";

//This variable is using when project is deleted by admin and po trying to upload/edit/save project covered picture
$config['project_details_page_featured_project_deleted_error_message'] = "Inzerát byl smazán. Nelze provést úpravy.";

$config['project_details_page_featured_fulltime_project_deleted_error_message'] = "Inzerát byl smazán. Nelze provést úpravy.";

//This variable is using when project is expired and po trying to upload/edit/save project covered picture
$config['project_details_page_expired_featured_project_upload_cover_picture_po_view_error_message'] = 'Platnost inzerátu vypršela.';

$config['fulltime_project_details_page_expired_featured_project_upload_cover_picture_po_view_error_message'] = 'Platnost inzerátu vypršela.';

//This varible is using when featured upgrade is expired and po trying to upload/edit/save project covered picture
$config['project_details_page_expired_featured_upgrade_upload_cover_picture_po_view_error_message'] = 'Platnost vyplepšení inzerátu vypršela.';

$config['fulltime_project_details_page_expired_featured_upgrade_upload_cover_picture_po_view_error_message'] = 'Platnost vyplepšení inzerátu vypršela.';


//ATTACHEMNTS NOT EXIST ERROR MESSAGES - PROJECT STATUS - OPEN FOR BIDDING (PO ON EDIT PROJECT PAGE)
$config['project_attachment_not_exist_validation_edit_project_page_message'] = "Zřejmě došlo k chybě. Odstraňte přílohu a nahrajte znovu.";

$config['fulltime_project_attachment_not_exist_validation_edit_project_page_message'] = "Zřejmě došlo k chybě. Odstraňte přílohu a nahrajte znovu.";

############ Defined the variable for project detail page regarding the project attachment if its not exists
$config['deleted_open_project_attachment_not_exist_validation_project_detail_message_visitor_view'] = "Inzerát je smazaný. Příloha nelze otevřít."; //ERROR - CATALIN 21.07.2019 - THIS VARIABLE IS used for PO and visitor view - both logged in and logged out views - NAME OF VARIABLE IS WRONG - THIS VARIABLE IS USED IN ALL VIEWS ALL PO AND VISTORS - KO


//ATTACHEMNTS NOT EXIST ERROR MESSAGES - PROJECT STATUS - AWAITING MODERATION
$config['project_attachment_not_exist_validation_awaiting_moderation_status_page_message_project_owner_view_project'] = "Příloha inzerátu neexistuje.";

$config['project_attachment_not_exist_validation_awaiting_moderation_status_page_message_project_owner_view_fulltime_project'] = "Příloha inzerátu neexistuje.";

##############
//ATTACHMENTS NOT EXIST ERROR MESSAGES - PROJECT STATUS - OPEN FOR BIDDING
$config['project_attachment_not_exist_validation_open_for_bidding_status_page_message_project_owner_view_project'] = "Příloha inzerátu neexistuje.";

$config['project_attachment_not_exist_validation_open_for_bidding_status_page_message_project_owner_view_fulltime_project'] = "Příloha inzerátu neexistuje.";

#############
//ATTACHEMNTS NOT EXIST ERROR MESSAGES - PROJECT STATUS - AWARDED
$config['project_attachment_not_exist_validation_awarded_project_status_page_message_project_owner_view'] = "Příloha inzerátu neexistuje.";

//ATTACHEMNTS NOT EXIST ERROR MESSAGES - PROJECT STATUS - IN PROGRESS
$config['project_attachment_not_exist_validation_in_progress_project_status_page_message_project_owner_view_project'] = "Příloha inzerátu neexistuje.";

//to be verified later
$config['project_attachment_not_exist_validation_incomplete_project_status_page_message_project_owner_view_project'] = "PROJECT INCOMPLETE - Project Owner - Project - Příloha inzerátu neexistuje.";

//ATTACHEMNTS NOT EXIST ERROR MESSAGES - PROJECT STATUS - EXPIRED
$config['project_attachment_not_exist_validation_expired_project_status_page_message_project_owner_view_project'] = "Příloha inzerátu neexistuje.";

$config['project_attachment_not_exist_validation_expired_project_status_page_message_project_owner_view_fulltime_project'] = "Příloha inzerátu neexistuje."; // catalin 20.06.2020 - seems is used same variables as for visitor login/logout view


//ATTACHEMNTS NOT EXIST ERROR MESSAGES - PROJECT STATUS - CANCELLED
$config['project_attachment_not_exist_validation_cancelled_project_status_page_message_project_owner_view_project'] = "Příloha inzerátu neexistuje.";

$config['project_attachment_not_exist_validation_cancelled_project_status_page_message_project_owner_view_fulltime_project'] = "PROJECT CANCELLED - Project Owner - FULLTIME Project - Příloha inzerátu neexistuje.";//OK ALLOCATION - 20.06.2020 Catalin

//ATTACHEMNTS NOT EXIST ERROR MESSAGES - PROJECT STATUS - COMPLETED
$config['project_attachment_not_exist_validation_completed_project_status_page_message_project_owner_view_project'] = "Příloha inzerátu neexistuje.";

//ATTACHEMNTS NOT EXIST ERROR MESSAGES - VISITOR VIEW
$config['project_attachment_not_exist_validation_project_detail_page_message_visitor_view_project'] = "Příloha inzerátu neexistuje.";

$config['project_attachment_not_exist_validation_project_detail_page_message_visitor_view_fulltime_project'] = "Příloha inzerátu neexistuje.";//"FullTime - SP - BIDDER - VISITOR - LOGGED IN / LOGGED OUT


################ Defined the featured project cover picture validation regarding on project detail page(open for bidding status)
/* Filename: application\modules\projects\views\open_for_bidding_project_detail.php */
$config['featured_project_cover_picture_maximum_size_validation_message'] = "Maximální povolená velikost je {featured_project_cover_picture_max_file_size_mb} MB.";

$config['invalid_featured_project_cover_picture_validation_message'] = "Nahrajte obrázek znovu";

$config['featured_project_cover_picture_extension_validation_message'] = "Typ obrázku, který chcete nehrát, není podporován!";

$config['featured_project_cover_picture_size_validation_message'] = "Minimální velikost obrázku musí být {max_width}x{max_height}.";

################ Defined the variable for user display activity for proejct and upgrade expired
$config['project_expired_user_activity_log_displayed_message_sent_to_po'] = 'Inzerát projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>" expiroval {project_expiration_date}.';

$config['fulltime_project_expired_user_activity_log_displayed_message_sent_to_po'] = 'Inzerát pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>" expiroval {project_expiration_date}.';
#################################user activity log messages when user upgrade project -> 14.07.2019 (catalin) - //These config are using when featured/urgent upgrade expired and in the table "serv_projects_open_bidding" the featured/urgent columns value is "Y".
//Then cron checking the expiration time of upgrade and switch the value of featured/urgent columns value "Y" to "N" and use above config for showing message to user//
$config['project_featured_upgrade_expired_user_activity_log_displayed_message_sent_to_po'] = 'Dostupnost vylepšení <strong>ZVÝRAZNĚNÝ</strong> pro inzerát "<a href="{project_url_link}" target="_blank">{project_title}</a>" vypršelo {featured_upgrade_expiration_date}.';

$config['fulltime_project_featured_upgrade_expired_user_activity_log_displayed_message_sent_to_po'] = 'Dostupnost vylepšení <strong>ZVÝRAZNĚNÝ</strong> pro inzerát "<a href="{project_url_link}" target="_blank">{project_title}</a>" vypršelo {featured_upgrade_expiration_date}.';

$config['project_urgent_upgrade_expired_user_activity_log_displayed_message_sent_to_po'] = 'Dostupnost vylepšení <strong>URGENTNÍ</strong> pro inzerát "<a href="{project_url_link}" target="_blank">{project_title}</a>" vypršelo {urgent_upgrade_expiration_date}.';

$config['fulltime_project_urgent_upgrade_expired_user_activity_log_displayed_message_sent_to_po'] = 'Dostupnost vylepšení <strong>URGENTNÍ</strong> pro inzerát "<a href="{project_url_link}" target="_blank">{project_title}</a>" vypršelo {urgent_upgrade_expiration_date}.';

################
//ACTIVITY LOG MESSAGES POSTED TO USERS
$config['project_submited_by_po_for_awaiting_moderation_user_activity_log_displayed_message_sent_to_po'] = 'Inzerát projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>" je úspěšně vytvořený a čeká na schválení.';

$config['fulltime_project_submited_by_po_for_awaiting_moderation_user_activity_log_displayed_message_sent_to_po'] = 'Inzerát pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>" je úspěšně vytvořený a čeká na schválení.';


// this message will be displayed when project in awaiting moderation is auto approved by node cron
$config['project_auto_approve_user_activity_log_displayed_message_sent_to_po'] = 'Inzerát projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>" je úspěšně schválený a zveřejněný na portálu.';

$config['fulltime_project_auto_approve_user_activity_log_displayed_message_sent_to_po'] = 'Inzerát pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>" je úspěšně schválený a zveřejněný na portálu.';


// this message will be displayed when admin approves the project in awaiting moderation - TESTED OK 05.07.2019
$config['project_approved_by_admin_user_activity_log_displayed_message_sent_to_po'] = 'Inzerát projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>" je úspěšně schválený a zveřejněný na portálu.';

$config['fulltime_project_approved_by_admin_user_activity_log_displayed_message_sent_to_po'] = 'Inzerát pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>" je úspěšně schválený a zveřejněný na portálu.';

// This variable will use when we set auto approval time min/max to 00:00:00
$config['post_project_directly_move_open_bidding_user_activity_log_displayed_message_sent_to_po'] = 'Inzerát projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>" je zveřejněný na portálu.';

$config['post_fulltime_project_directly_move_open_bidding_user_activity_log_displayed_message_sent_to_po'] = 'Inzerát pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>" je zveřejněný na portálu.';


// this message will be displayed when admin reject the project in awaiting moderation
$config['project_rejected_by_admin_user_activity_log_displayed_message_sent_to_po'] = 'Inzerát projektu "{project_title}" není schválený ke zveřejnění. Zkontrolujte ho a znovu vytvořte pro schválení.';

$config['fulltime_project_rejected_by_admin_user_activity_log_displayed_message_sent_to_po'] = 'Inzerát pracovní pozice "{project_title}" není schválený ke zveřejnění. Zkontrolujte ho a znovu vytvořte pro schválení.';


//this message will be displayed when admin deletes the project in awaiting moderation - TESTED OK 05.07.2019
$config['project_deleted_by_admin_user_activity_log_displayed_message_sent_to_po'] = 'Inzerát projektu "{project_title}" je smazán administrátorem.';

$config['fulltime_project_deleted_by_admin_user_activity_log_displayed_message_sent_to_po'] = 'Inzerát pracovní pozice "{project_title}" je smazán administrátorem.';

//this message will be displayed when admin cancelled the project from open for bidding status
$config['project_cancelled_by_admin_open_for_bidding_user_activity_log_displayed_message_sent_to_po'] = 'Inzerát projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>" je zrušený administrátorem.';

$config['fulltime_project_cancelled_by_admin_open_for_bidding_user_activity_log_displayed_message_sent_to_po'] = 'Inzerát pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>" je zrušený administrátorem.';

// This message will display when po cancel project from open for bidding status
$config['project_cancelled_by_po_male_open_for_bidding_user_activity_log_displayed_message_sent_to_po'] = 'Zrušil jste inzerát projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['project_cancelled_by_po_female_open_for_bidding_user_activity_log_displayed_message_sent_to_po'] = 'Zrušila jste inzerát projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

// For app user
$config['project_cancelled_by_po_company_app_male_open_for_bidding_user_activity_log_displayed_message_sent_to_po'] = '
app:male-Zrušili jste inzerát projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['project_cancelled_by_po_company_app_female_open_for_bidding_user_activity_log_displayed_message_sent_to_po'] = 'app:Female-Zrušili jste inzerát projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['project_cancelled_by_po_company_open_for_bidding_user_activity_log_displayed_message_sent_to_po'] = 'Zrušili jste inzerát projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fulltime_project_cancelled_by_po_male_open_for_bidding_user_activity_log_displayed_message_sent_to_po'] = 'Zrušil jste inzerát pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_cancelled_by_po_female_open_for_bidding_user_activity_log_displayed_message_sent_to_po'] = 'Zrušila jste inzerát pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

//For app user
$config['fulltime_project_cancelled_by_po_company_app_male_open_for_bidding_user_activity_log_displayed_message_sent_to_po'] = 'App:male-Zrušili jste inzerát pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_cancelled_by_po_company_app_female_open_for_bidding_user_activity_log_displayed_message_sent_to_po'] = 'App:Female-Zrušili jste inzerát pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fulltime_project_cancelled_by_po_company_open_for_bidding_user_activity_log_displayed_message_sent_to_po'] = 'Zrušili jste inzerát pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


//this message will be displayed when admin cancelled the project from expired status
$config['expired_project_cancelled_by_admin_user_activity_log_displayed_message_sent_to_po'] = 'Inzerát projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>" je zrušený administrátorem.';


$config['fulltime_expired_project_cancelled_by_admin_user_activity_log_displayed_message_sent_to_po'] = 'Inzerát pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>" je zrušený administrátorem.';

// This message will display when po cancel project from expired status
$config['project_cancelled_by_po_male_expired_user_activity_log_displayed_message_sent_to_po'] = 'Zrušil jste inzerát projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['project_cancelled_by_po_female_expired_user_activity_log_displayed_message_sent_to_po'] = 'Zrušila jste inzerát projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['project_cancelled_by_po_company_app_male_expired_user_activity_log_displayed_message_sent_to_po'] = 'Zrušil jste inzerát projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['project_cancelled_by_po_company_app_female_expired_user_activity_log_displayed_message_sent_to_po'] = 'Zrušila jste inzerát projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['project_cancelled_by_po_company_expired_user_activity_log_displayed_message_sent_to_po'] = 'Zrušili jste inzerát projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


$config['fulltime_project_cancelled_by_po_male_expired_user_activity_log_displayed_message_sent_to_po'] = 'Zrušil jste inzerát pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_cancelled_by_po_female_expired_user_activity_log_displayed_message_sent_to_po'] = 'Zrušila jste inzerát pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_cancelled_by_po_company_app_male_expired_user_activity_log_displayed_message_sent_to_po'] = 'Zrušil jste inzerát pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['fulltime_project_cancelled_by_po_company_app_female_expired_user_activity_log_displayed_message_sent_to_po'] = 'Zrušila jste inzerát pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>".';



$config['fulltime_project_cancelled_by_po_company_expired_user_activity_log_displayed_message_sent_to_po'] = 'Zrušili jste inzerát pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>".';


// This variable will use when user save project as draft / remove draft project from list - TESTED OK 05.07.2019
$config['post_project_save_as_draft_user_activity_log_displayed_message_sent_to_po'] = 'Inzerát projektu "{project_title}" je uložený jako koncept.';

$config['fulltime_post_project_save_as_draft_user_activity_log_displayed_message_sent_to_po'] = 'Inzerát pracovní pozice "{project_title}" je uložený jako koncept.';


$config['remove_draft_user_activity_log_displayed_message_sent_to_po'] = 'Inzerát projektu "{project_title}" je smazaný z konceptu.';


$config['fulltime_remove_draft_user_activity_log_displayed_message_sent_to_po'] = 'Inzerát pracovní pozice "{project_title}" je smazaný z konceptu.';


//project refresh user activity log messages
// This message is for standard and sealed project refresh
$config['standard_or_sealed_project_refresh_user_activity_log_displayed_message_sent_to_po'] = 'Inzerát projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>" byl aktualizován na stránce pracovní pozice a projekty.';

$config['fulltime_standard_or_sealed_project_refresh_user_activity_log_displayed_message_sent_to_po'] = 'Inzerát pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>" byl aktualizován na stránce pracovní pozice a projekty.';


// This message is for featured project refresh
$config['featured_project_refresh_user_activity_log_displayed_message_sent_to_po'] = '<strong>ZVÝRAZNĚNÝ</strong> inzerát projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>" byl aktualizován na stránce pracovní pozice a projekty.';

$config['fulltime_featured_project_refresh_user_activity_log_displayed_message_sent_to_po'] = '<strong>ZVÝRAZNĚNÝ</strong> inzerát pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>" byl aktualizován na stránce pracovní pozice a projekty.';


// This message is for urgent project refresh
$config['urgent_project_refresh_user_activity_log_displayed_message_sent_to_po'] = '<strong>URGENTNÍ</strong> inzerát projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>" byl aktualizován na stránce pracovní pozice a projekty.';

$config['fulltime_urgent_project_refresh_user_activity_log_displayed_message_sent_to_po'] = '<strong>URGENTNÍ</strong> inzerát pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>" byl aktualizován na stránce pracovní pozice a projekty.';


// this message will be displayed untill next refresh time for any project type is not null - tested ok - 06.07.2019
$config['project_next_refresh_user_activity_log_displayed_message_sent_to_po'] = 'Další aktualizace proběhne <strong>{next_refresh_time}</strong>.';

$config['fulltime_project_next_refresh_user_activity_log_displayed_message_sent_to_po'] = 'Další aktualizace proběhne <strong>{next_refresh_time}<strong>.';


//FEATURED UPGRADE
$config['project_featured_upgrade_user_activity_log_displayed_message_sent_to_po'] = 'Inzerát projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>" je vylepšený jako <strong>ZVÝRAZNĚNÝ</strong>, za <span>{project_featured_upgrade_price}</span>. Platnost vylepšení vyprší {project_featured_upgrade_expiration_date}.';

$config['fulltime_project_featured_upgrade_user_activity_log_displayed_message_sent_to_po'] = 'Inzerát pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>" je vylepšený jako <strong>ZVÝRAZNĚNÝ</strong>, za <span>{project_featured_upgrade_price}</span>. Platnost vylepšení vyprší {project_featured_upgrade_expiration_date}.';


//featured prolong
$config['project_featured_upgrade_prolong_user_activity_log_displayed_message_sent_to_po'] = 'Dostupnost vylepšení <strong>ZVÝRAZNĚNÝ</strong> inzerátu projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>", je prodloužený o další <span>{project_featured_upgrade_prolong_availability}</span>, za <span>{project_featured_upgrade_price}</span>. Datum další expirace {project_featured_upgrade_prolong_next_expiration_date}.';

$config['fulltime_project_featured_upgrade_prolong_user_activity_log_displayed_message_sent_to_po'] = 'Dostupnost vylepšení <strong>ZVÝRAZNĚNÝ</strong> inzerátu pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>", je prodloužený o další <span>{project_featured_upgrade_prolong_availability}</span>, za <span>{project_featured_upgrade_price}</span>. Datum další expirace {project_featured_upgrade_prolong_next_expiration_date}.';


//URGENT UPGRADE
$config['project_urgent_upgrade_user_activity_log_displayed_message_sent_to_po'] = 'Inzerát projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>" je vylepšený jako <strong>URGENTNÍ</strong>, za <span>{project_urgent_upgrade_price}</span>. Platnost vylepšení vyprší {project_urgent_upgrade_expiration_date}.';

$config['fulltime_project_urgent_upgrade_user_activity_log_displayed_message_sent_to_po'] = 'Inzerát pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>" je vylepšený jako <strong>URGENTNÍ</strong>, za <span>{project_urgent_upgrade_price}</span>. Platnost vylepšení vyprší {project_urgent_upgrade_expiration_date}.';


//prolong URGENT
$config['project_urgent_upgrade_prolong_user_activity_log_displayed_message_sent_to_po'] = 'Dostupnost vylepšení <strong>URGENTNÍ</strong> inzerátu projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>", je prodloužený o další <span>{project_urgent_upgrade_prolong_availability}</span>, za <span>{project_urgent_upgrade_price}</span>. Datum další expirace {project_urgent_upgrade_prolong_next_expiration_date}.';

$config['fulltime_project_urgent_upgrade_prolong_user_activity_log_displayed_message_sent_to_po'] = 'Dostupnost vylepšení <strong>URGENTNÍ</strong> inzerátu pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>", je prodloužený o další <span>{project_urgent_upgrade_prolong_availability}</span>, za <span>{project_urgent_upgrade_price}</span>. Datum další expirace {project_urgent_upgrade_prolong_next_expiration_date}.';

############### definitions of custom config for myproject page
################ My Project page Meta Variables ###########
$config['myprojects_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Prehled cinností';
$config['myprojects_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Prehled cinností';
################ Page text Variables ###########
$config['myprojects_headline_title'] = 'Přehled činností';
################ Url Routing Variables ###########
//my-projects
$config['myprojects_page_url'] = 'prehled-cinnosti';

//Define the config variables for project detail page
$config['project_details_page_preview'] = 'Náhled';

$config['project_details_page_draft'] = 'Náhled';

//projects types
$config['project_details_page_project_details'] = 'Projekt';

$config['project_details_page_fulltime_details'] = 'Pracovní pozice';


// projects status
$config['project_status_awaiting_moderation'] = 'Čeká na schválení';

$config['project_status_expired'] = 'Expirovaný';

$config['project_status_open_for_bidding'] = 'Otevřený';

$config['project_status_awarded'] = 'Udělený';

$config['project_status_in_progress'] = 'Probíhající';

$config['project_status_completed'] = 'Dokončený';

$config['project_status_incomplete'] = 'Nedokončený';

$config['project_status_cancelled'] = 'Zrušený';

$config['project_status_cancelled_by_admin'] = 'Zrušeno admin';

//project details
$config['project_details_page_posted_on'] = 'Zveřejněno:';

$config['project_details_page_completed_on'] = 'Dokončeno:';

$config['project_details_page_cancelled_on'] = 'Zrušeno:';

$config['project_details_page_time_left'] = 'Zbývající čas:';

$config['project_details_page_expires_on'] = 'Vyprší:';

$config['project_details_page_expired_on'] = 'Vypršelo:';


$config['project_details_page_project_type'] = 'Typ projektu:';

$config['project_details_page_fixed_budget'] = 'fixní rozpočet';

$config['project_details_page_hourly_budget'] = 'platba za hodinu';

$config['project_details_page_payment_method'] = 'Platební metoda:';

$config['project_details_page_location'] = 'Adresa:';

$config['project_details_page_project_bid_history'] = 'Nabídky:';

$config['project_details_page_fulltime_bid_history'] = 'Žádosti:';

$config['project_details_page_project_budget'] = 'Rozpočet:';

$config['project_details_page_fulltime_project'] = 'Pracovní pozice';

$config['project_details_page_fulltime_salary'] = 'Mzda:';

$config['project_details_page_fulltime_project_description'] = 'Popis';

$config['project_details_page_project_description'] = 'Popis';

$config['project_details_page_project_owner_details'] = 'Vlastník projektu';

$config['project_details_page_fulltime_employer_details'] = 'Zaměstnavatel';



$config['project_details_page_project_bidders_list_singular'] = 'Nabídka';

$config['project_details_page_project_bidders_list_plural'] = 'Nabídky';

$config['fulltime_project_details_page_applicants_list_singular'] = 'Žádost';

$config['fulltime_project_details_page_applicants_list_plural'] = 'Žádosti';


#####config of project payment method for project detail page
$config['project_details_page_payment_method_escrow_system'] = 'Travai Bezpečná Platba';

$config['project_details_page_payment_method_offline_system'] = 'Vlastní (mimo Travai)';


#####config of project payment method for find project page/latest projects section on dashboard
$config['find_projects_project_payment_method_escrow_system'] = '- platební metoda: Travai Bezpečná Platba';

$config['find_projects_project_payment_method_offline_system'] = '- platební metoda: vlastní';

#####config of project payment method for my projects section on dashboard/dedicated page
$config['my_projects_project_payment_method_escrow_system'] = '- platební metoda: Travai Bezpečná Platba';

$config['my_projects_project_payment_method_offline_system'] = '- platební metoda: vlastní';

#####config of project payment method for user profile page
$config['po_profile_page_project_payment_method_escrow_system'] = '- platební metoda: Travai Bezpečná Platba';

$config['po_profile_page_project_payment_method_offline_system'] = '- platební metoda: vlastní';
##############################################################################################################
$config['project_details_page_fulltime_project_continue_editing_button_txt'] = 'Pokračovat v úpravách';

$config['project_details_page_project_continue_editing_button_txt'] = 'Pokračovat v úpravách';

// used on find project page / latest projects / my projects section / project detail page
$config['project_description_snippet_bid_history_0_bids_received'] = 'nabídek';

$config['project_description_snippet_bid_history_1_bid_received'] = 'nabídka';

$config['project_description_snippet_bid_history_2_to_4_bids_received'] = 'nabídky';

$config['project_description_snippet_bid_history_5_or_more_bids_received'] = 'nabídek';

$config['fulltime_project_description_snippet_bid_history_0_applications_received'] = 'žádostí';

$config['fulltime_project_description_snippet_bid_history_1_application_received'] = 'žádost';

$config['fulltime_project_description_snippet_bid_history_2_to_4_applications_received'] = 'žádosti';

$config['fulltime_project_description_snippet_bid_history_5_or_more_applications_received'] = 'žádostí';

########## these config are using to show number of hires for project on my projects section for PO on dashboard/my project page
// Hired snippet for project description
$config['project_description_snippet_hire_history_0_sps_hired'] = 'najatých';

$config['project_description_snippet_hire_history_1_sp_hire'] = 'najatý';

$config['project_description_snippet_hire_history_2_to_4_sps_hired'] = 'najatí';

$config['project_description_snippet_hire_history_5_or_more_sps_hired'] = 'najatých';

// Hired snippet for fulltime project description
$config['fulltime_project_description_snippet_hire_history_0_employees_hired'] = 'zaměstnaných';

$config['fulltime_project_description_snippet_hire_history_1_employee_hire'] = 'zaměstnaný';

$config['fulltime_project_description_snippet_hire_history_2_to_4_employees_hired'] = 'zaměstnaní';

$config['fulltime_project_description_snippet_hire_history_5_or_more_employees_hired'] = 'zaměstnaných';


$config['project_details_page_project_awaiting_acceptance_bid_list_singular'] = 'Vybraná nabídka';
$config['project_details_page_project_awaiting_acceptance_bid_list_plural'] = 'Vybrané nabídky';


$config['project_details_page_project_in_progress_bids_list_singular'] = 'Probíhající práce';
$config['project_details_page_project_in_progress_bids_list_plural'] = 'Probíhající práce';


$config['project_details_page_project_completed_bids_list_singular'] = 'Dokončená práce';
$config['project_details_page_project_completed_bids_list_plural'] = 'Dokončené práce';


$config['project_details_page_project_incomplete_bids_list_singular'] = 'CZ-Incomplete Bid';
$config['project_details_page_project_incomplete_bids_list_plural'] = 'CZ-Incomplete Bids';


$config['project_details_page_project_active_disputed_bids_list_singular'] = 'Probíhající spor';

$config['project_details_page_project_active_disputed_bids_list_plural'] = 'Probíhající spory';


$config['fulltime_project_details_page_active_disputed_applications_list_singular'] = 'CZ-Active Disputed Application';
$config['fulltime_project_details_page_active_disputed_applications_list_plural'] = 'CZ-Active Disputed Applications';

##############################################################################################
/* config variables for my project section(dashboard and dedicated my project page) also used on user profile page */
$config['project_posted_on'] = '<span class="project_posted_on_color">zveřejněno</span>';

$config['fulltime_project_posted_on'] = '<span class="project_posted_on_color">zveřejněno</span>';

$config['fulltime_project_cancelled_on_sp_view_myprojects_section'] = '<span class="cancelled_on_sp_view_myprojects_section_color">zrušeno</span>';

$config['bid_awarded_on'] = '<span class="bid_awarded_on_color">uděleno</span>';

//termín pro rozhodnutí - used on both my projects and project details page - for both sp and po
$config['my_projects_sp_view_awarded_bid_expiration_time'] = '<span class="awarded_bid_expiration_time_color">termín pro rozhodnutí</span>';

$config['bid_date'] = '<span class="bid_date_color">datum nabídky</span>';

$config['application_date'] = '<span class="application_date_color">datum žádosti</span>';

$config['project_start_date'] = '<span class="project_start_date_color">zahájení</span>';

$config['project_completion_date'] = '<span class="completion_date_color">dokončení</span>';

$config['project_save_as_draft_date'] = '<span class="save_as_draft_date_color">uložení návrhu</span>';

$config['fulltime_project_save_as_draft_date'] = '<span class="fulltime_save_as_draft_date_color">uložení návrhu</span>';

$config['project_submission_date_for_moderation'] = '<span class="project_submission_date_for_moderation_on_color">odesláno ke schválení</span>';

$config['fulltime_project_submission_date_for_moderation'] = '<span class="fulltime_project_submission_date_for_moderation_on_color">odesláno ke schválení</span>';

$config['project_expired_on'] = '<span class="expired_on_color">vypršelo</span>';

$config['fulltime_project_expired_on'] = '<span class="fulltime_expired_on_color">vypršelo</span>';

$config['project_cancelled_by_admin_on'] = '<span class="cancelled_by_admin_on_color">zrušeno administrátorem</span>';

$config['fulltime_project_cancelled_by_admin_on'] = '<span class="fulltime_cancelled_by_admin_on_color">zrušeno administrátorem</span>';

$config['project_cancelled_by_po_on'] = '<span class="cancelled_by_po_on_color">zrušeno</span>';

$config['fulltime_project_cancelled_by_po_on'] = '<span class="fulltime_cancelled_by_po_on_color">zrušeno</span>';

##############################################################################################

// Config used on find project page / latest projects / my projects section/profile page(All project status except in progress)
$config['project_listing_window_snippet_fixed_budget_project'] = '<span><small class="fixed_budget_color">projekt (fixní)</small> - rozpočet</span>'; // 

$config['project_listing_window_snippet_hourly_based_budget_project'] = '<span><small class="hourly_based_budget_project_color">projekt (platba za hodinu)</small> - hodinová sazba</span>';

$config['project_listing_window_snippet_fulltime_project'] = '<span><small class="fulltime_job_color">pracovní pozice</small> - plat</span>';

#############################################################################
################ Defined variable for user dashboard my project section Actions drop down
// Options for draft tab in my projects section
$config['myprojects_section_draft_tab_option_remove_draft_po_view'] = 'Smazat návrh';

$config['myprojects_section_draft_tab_option_edit_draft_po_view'] = 'Upravit návrh';

$config['myprojects_section_draft_tab_option_publish_project_po_view'] = 'Zveřejnit inzerát';

// Options for open for biddind tab in my projects section
$config['myprojects_section_open_for_bidding_tab_option_upgrade_project_po_view'] = 'Vylepšit inzerát';

$config['myprojects_section_open_for_bidding_tab_option_upgrade_as_featured_project_po_view'] = 'Vylepšit jako zvýrazněný';

$config['myprojects_section_open_for_bidding_tab_option_upgrade_as_urgent_project_po_view'] = 'Vylepšit jako urgentní';

$config['myprojects_section_open_for_bidding_tab_option_copy_into_new_project_po_view'] = 'Kopírovat pro nový';

$config['myprojects_section_open_for_bidding_tab_option_cancel_project_po_view'] = 'Zrušit inzerát';

$config['myprojects_section_open_for_bidding_tab_option_edit_project_po_view'] = 'Upravit inzerát';



// Options for awarded tab in my projects section
$config['myprojects_section_awarded_tab_option_copy_into_new_project_po_view'] = 'Kopírovat pro nový';

// Options for in progress tab in my projects section
$config['myprojects_section_in_progress_tab_option_copy_into_new_project_po_view'] = 'Kopírovat pro nový';

// Options for incomplete tab in my projects section
$config['myprojects_section_incomplete_tab_option_copy_into_new_project_po_view'] = 'Incomplete-Kopírovat pro nový';

// Options for completed tab in my projects section
$config['myprojects_section_completed_tab_option_copy_into_new_project_po_view'] = 'Kopírovat pro nový';




// Options for expired tab in my projects section
$config['myprojects_section_expired_tab_option_repost_project_po_view'] = 'Kopírovat pro nový';

$config['myprojects_section_expired_tab_option_cancel_project_po_view'] = 'Zrušit inzerát';

// Options for cancelled tab in my projects section
$config['myprojects_section_cancelled_tab_option_copy_into_new_project_po_view'] = 'Kopírovat pro nový';

$config['myprojects_section_cancelled_tab_option_repost_project_po_view'] = 'Kopírovat pro nový';

##########################################################################################
// variable for my project section, project cancellation process on dashboard

// variable for my project section, project cancellation process on dashboard for open for bidding project(cancel popup model)
$config['cancel_open_for_bidding_project_modal_body'] = 'Opravdu chcete zrušit inzerát projektu "{project_title}"?';

$config['cancel_open_for_bidding_fulltime_project_modal_body'] = 'Opravdu chcete zrušit inzerát "{project_title}"?';

####################################################################################################
// variable for my project section, project cancellation process on dashboard for expired project(cancel popup model)
$config['cancel_expired_project_modal_body'] = 'Opravdu chcete zrušit inzerát "{project_title}"?';

$config['cancel_expired_fulltime_project_modal_body'] = 'Opravdu chcete zrušit inzerát "{project_title}"?';

###############################################################################################
//featured project - cover picture management
$config['project_detail_page_upload_cover_picture_btn_txt'] = 'Nahrát obrázek';

$config['project_detail_page_upload_new_cover_picture_btn_txt'] = 'Změnit obrázek';

###############################################################################################
//project details page log off version - bottom banner


$config['project_detail_page_hire_freelancer_btn_txt'] = 'Najmout odborníky';
$config['project_detail_page_register_freelancer_btn_txt'] = 'Registrovat na Travai';

$config['project_detail_page_start_working_right_now_txt'] = 'Vytvořit účet a začít budovat svou profesní historii';

$config['project_detail_page_get_best_freelancer_just_minutes_txt'] = 'Najít odborníky z celé ČR a získávat nejlepší nabídky';

###############################################################################################
//project details page - text used for notification bell activity
$config['project_details_page_tooltip_subscribe_to_po_new_projects_posted_notifications_txt'] = 'kliknutím na zvoneček pro získávání oznámení pokaždé, když {user_first_name_last_name_or_company_name} zveřejní nový inzerát';

$config['project_details_page_tooltip_unsubscribe_to_po_new_projects_posted_notifications_txt'] = 'jste přihlášeni k přijímání oznámení pokaždé, když {user_first_name_last_name_or_company_name} zveřejní nový projekt';
#########################################################################################################
#########config for FEATURED upgrade ########
$config['project_upgrade_popup_featured_upgrade_heading'] = 'Vylepšit inzerát "{project_title}" jako ZVÝRAZNĚNÝ na {project_featured_upgrade_availability}!';

$config['project_upgrade_popup_featured_upgrade_description'] = "Inzerát bude označen jako <strong>ZVÝRAZNĚNÝ</strong>. Zvýrazněné inzeráty přitahují větší počet kvalitních nabídek. Zobrazují se prominentně na stránce pracovní pozice a projekty se žlutým posadím a specifickým označením. Aktualizace probíhá každé 3 dny, aby zůstal inzerát na vrcholu stránky pracovní pozice a projekty.";


$config['fulltime_project_upgrade_popup_featured_upgrade_heading'] = 'Vylepšit inzerát "{project_title}" jako ZVÝRAZNĚNÝ na {project_featured_upgrade_availability}!';

$config['fulltime_project_upgrade_popup_featured_upgrade_description'] = "Inzerát bude označen jako <strong>ZVÝRAZNĚNÝ</strong>. Zvýrazněné inzeráty přitahují větší počet kvalitních nabídek. Zobrazují se prominentně na stránce pracovní pozice a projekty se žlutým posadím a specifickým označením. Aktualizace probíhá každé 3 dny, aby zůstal inzerát na vrcholu stránky pracovní pozice a projekty.";


#########config for URGENT upgrade ########
$config['project_upgrade_popup_urgent_upgrade_heading'] = 'Vylepšit inzerát "{project_title}" jako URGENTNÍ na {project_urgent_upgrade_availability}!';

$config['project_upgrade_popup_urgent_upgrade_description'] = "Inzerát bude označen jako <strong>URGENTNÍ</strong>. Získáte rychlejší odpověď od odborníků, aby váš projekt začal v co nejkratší době! Aktualizace probíhá 2x denně, aby zůstal inzerát na vrcholu stránky pracovní pozice a projekty.";


$config['fulltime_project_upgrade_popup_urgent_upgrade_heading'] = 'Vylepšit inzerát "{project_title}" jako URGENTNÍ na {project_urgent_upgrade_availability} !';

$config['fulltime_project_upgrade_popup_urgent_upgrade_description'] = "Inzerát bude označen jako <strong>URGENTNÍ</strong>. Získáte rychlejší odpověď od odborníků, aby váše pracovní pozice byla obsazena v co nejkratší době! Aktualizace probíhá 2x denně, aby zůstal inzerát na vrcholu stránky pracovní pozice a projekty.";
#########################################################################################################

######## upgrade popup cnacel button txt
$config['project_upgrade_modal_proceed_btn_txt'] = 'Pokračovat ve volbě';

$config['project_upgrade_prolong_modal_proceed_btn_txt'] = 'Pokračovat ve volbě';

####################################################################################################

#########config for FEATURED upgrade prolong########
$config['project_upgrade_popup_prolong_featured_upgrade_heading'] = 'Prodloužit inzerát "{project_title}" jako ZVÝRAZNĚNÝ o dalších {project_featured_upgrade_prolong_availability} za {project_featured_upgrade_price} !';

$config['project_upgrade_popup_prolong_featured_upgrade_description'] = 'Aktuální platnost <strong>ZVÝRAZNĚNÝ</strong> inzerátu vyprší <strong>{featured_upgrade_availability_expire_date}</strong>. Pokud platnost nyní prodloužíte, datum příští expirace bude <strong>{featured_upgrade_availability_extended_date}</strong>.';


$config['fulltime_project_upgrade_popup_prolong_featured_upgrade_heading'] = 'Prodloužit inzerát "{project_title}" jako ZVÝRAZNĚNÝ o dalších {project_featured_upgrade_prolong_availability} za {project_featured_upgrade_price} !';

$config['fulltime_project_upgrade_popup_prolong_featured_upgrade_description'] = 'Aktuální platnost <strong>ZVÝRAZNĚNÝ</strong> inzerátu vyprší <strong>{featured_upgrade_availability_expire_date}</strong>. Pokud platnost nyní prodloužíte, datum příští expirace bude <strong>{featured_upgrade_availability_extended_date}</strong>.';

#########config for URGENT upgrade prolong########
$config['project_upgrade_popup_prolong_urgent_upgrade_heading'] = 'Prodloužit inzerát "{project_title}" jako URGENTNÍ o dalších {project_urgent_upgrade_prolong_availability} za {project_urgent_upgrade_price} !';

$config['project_upgrade_popup_prolong_urgent_upgrade_description'] = 'Aktuální platnost <strong>URGENTNÍ</strong> inzerátu vyprší <strong>{urgent_upgrade_availability_expire_date}</strong>. Pokud platnost nyní prodloužíte, datum příští expirace bude <strong>{urgent_upgrade_availability_extended_date}</strong>.';

$config['fulltime_project_upgrade_popup_prolong_urgent_upgrade_heading'] = 'Prodloužit inzerát "{project_title}" jako URGENTNÍ o dalších {project_urgent_upgrade_prolong_availability} za {project_urgent_upgrade_price} !';

$config['fulltime_project_upgrade_popup_prolong_urgent_upgrade_description'] = 'Aktuální platnost <strong>URGENTNÍ</strong> inzerátu vyprší <strong>{urgent_upgrade_availability_expire_date}</strong>. Pokud platnost nyní prodloužíte, datum příští expirace bude <strong>{urgent_upgrade_availability_extended_date}</strong>.';

#####################################################################################################
//RED WARNING FOR INSUFFICIENT FUNDS FOR PROJECT UPGRADE for already posted projects (from my projects section/page)
// This variable is used to check user account balance with project upgrade price and if price doesn't cover account balance then this message will be displayed (either featured or urgent).
$config['user_upgrade_project_insufficient_funds_error_message_singular'] = 'Váš aktuální zůstatek na účtu nepokrývá poplatek za vybranou službu.';

// This variable is used to check user account balance with project upgrade price and if price doesn't cover account balance then this message will be displayed(featured and urgent both).
$config['user_upgrade_project_insufficient_funds_error_message_plural'] = 'Váš aktuální zůstatek na účtu nepokrývá poplatek za vybrané služby.';


//RED WARNING FOR INSUFFICIENT FUNDS FOR PROLONG PROJECT UPGRADE
// This variable is used to check user account balance with project upgrade price when prolong featured upgrade.
$config['user_prolong_featured_upgrade_project_insufficient_funds_error_message'] = 'Váš aktuální zůstatek na účtu nepokrývá poplatek za vybranou službu.';


// This variable is used to check user account balance with project upgrade price when prolong urgent upgrade.
$config['user_prolong_urgent_upgrade_project_insufficient_funds_error_message'] = 'Váš aktuální zůstatek na účtu nepokrývá poplatek za vybranou službu.';

########## this message is showing on project detail page if project type is sealed. It will show to all user except project owner
$config['project_details_page_project_sealed_disclaimer_message'] = '* nabídky od odborníků jsou skryté, protože typ inzerátu je NEVEŘEJNÝ';

$config['project_details_page_fulltime_project_sealed_disclaimer_message'] = '* nabídky od odborníků jsou skryté, protože typ inzerátu je NEVEŘEJNÝ';
#############################################################################
//variables used for project details page PO details
$config['project_details_page_total_projects_published'] = 'vytvořené inzeráty';
$config['project_details_page_total_fulltime_projects_published'] = 'vytvořené inzeráty';

$config['project_details_page_total_completed_projects_via_portal'] = 'dokončené projekty';
$config['project_details_page_total_hires_on_fulltime_projects_via_portal'] = 'počet zaměstnaných';

##################################################################################################
###############################################################################
// Real time notification message when project is rejected by admin(Send by Node)
$config['fulltime_project_rejected_by_admin_realtime_notification_message_sent_to_po'] = 'inzerát pracovní pozice "{project_title}" není schválený ke zveřejnění';
$config['project_rejected_by_admin_realtime_notification_message_sent_to_po'] = 'inzerát projektu "{project_title}" není schválený ke zveřejnění';

// Real time notification message when project is auto approved or approved by admin(Send by Node)
$config['fulltime_project_approved_by_admin_realtime_notification_message_sent_to_po'] = 'inzerát pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>" je úspěšně schválený a zveřejněný na portálu';

$config['project_approved_by_admin_realtime_notification_message_sent_to_po'] = 'inzerát projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>" je úspěšně schválený a zveřejněný na portálu';

$config['project_deleted_by_admin_realtime_notification_message_sent_to_po'] = 'inzerát "{project_title}" je smazán administrátorem';
#################################################################
// config for my project section tabs heading for po
$config['my_projects_po_view_draft_tab_heading'] = "Návrhy";

$config['my_projects_po_view_awaiting_moderation_tab_heading'] = "Čeká ke schválení"; 

$config['my_projects_po_view_open_for_bidding_tab_heading'] = "Otevřeno";

$config['my_projects_po_view_awarded_tab_heading'] = "Uděleno";

$config['my_projects_po_view_work_in_progress_tab_heading'] = "Probíhající";

$config['my_projects_po_view_work_incomplete_tab_heading'] = "Nedokončeno";

$config['my_projects_po_view_completed_tab_heading'] = "Dokončeno";

$config['my_projects_po_view_expired_tab_heading'] = "Vypršelo";

$config['my_projects_po_view_cancelled_tab_heading'] = "Zrušeno";

##################################################################################################
// config for my project section tabs heading for sp
$config['my_projects_sp_view_active_bids_tab_heading'] = "Nabídky & Žádosti";

$config['my_projects_sp_view_awarded_bids_tab_heading'] = "Udělení";

$config['my_projects_sp_view_projects_in_progress_tab_heading'] = "Probíhající";

$config['my_projects_sp_view_projects_incomplete_tab_heading'] = "Nedokončeno";

$config['my_projects_employee_view_fulltime_projects_hired_tab_heading'] = "Zaměstnán";

$config['my_projects_sp_view_completed_tab_heading'] = "Dokončené";

##################################################################################################
// config for my project section
$config['my_projects_section_heading'] = "Přehled činností";

$config['my_projects_section_as_employer'] = "Zadavatel & Zaměstnavatel"; 

$config['my_projects_section_as_service_provider'] = "Poskytovatel & Zaměstnanec";

$config['my_projects_po_view_total_project_value'] = '<span class="po_view_total_project_value_color">celková hodnota projektu</span>'; //this variable is using to show the "Total project Value" label in "inprogress,completed" tab in my project section of PO view

##################################################################################################
// Define the config variables for tab in inprogress/completed section tabs

$config['project_details_page_description_section_tab'] = 'Nabídka';

$config['fulltime_project_details_page_description_section_tab'] = 'Nabídka';

$config['project_details_page_payment_management_section_tab'] = 'Platby';

$config['project_details_page_messages_section_tab'] = 'Chat';

$config['project_details_page_feedback_section_tab'] = 'Hodnocení'; //Vyměnit recenze // Recenze práce

$config['project_details_page_mark_project_complete_section_tab'] = 'Označit jako dokončený';

################################################################### violation report ###################################################################
$config['project_details_page_violation_report_popup_heading'] = 'Porušení pravidel';

$config['project_details_page_violation_report_popup_sub_heading_txt'] = 'Neustále monitorujeme a snažíme se, aby všechny inzeráty byly podle našich zákonů. Pomocí této funkce můžete nahlásit jakýkoli inzerát, který porušuje Travai zákony a mohl by poškodit naši obchodní činnost a důvěryhodnost Travai portálu nebo by potenciálně mohl poškodit a způsobit možnou ztrátu našich uživatelů.<br><br>Je tedy důležité, abyste nás kontaktovali v případě zjištění, která vedou k porušení zákonů. Tímto způsobem můžeme spolupracovat a udržovat portál bezpečný a na dobré úrovni.';

$config['project_details_page_violation_report_popup_url_lbl'] = 'URL inzerátu';

$config['project_details_page_violation_report_popup_reason_lbl'] = 'Potencionální porušení';

$config['project_details_page_violation_report_popup_detail_violation_lbl'] = 'Popis';

$config['project_details_page_violation_report_popup_reason_default_option_name'] = 'vybrat možnost';

$config['project_details_page_violation_report_popup_reasons_option_name'] = [
	'žádost pro falšování dokumentů',
	'pokus o hackerství',
 'diskriminace a pornografie',
 'prodej nebo výkup odcizených předmětů',
 'použití nebo zneužití odcizených dokumentů',
 'nezákonný prodej léků a omamných látek',
 'podvodný inzerát',
 'jiné porušení'
];

$config['project_details_page_violation_report_popup_disclaimer_modal_body'] = 'Vaše oznámení o porušení pravidel, uživatelské jméno a e-mailová adresa, budou zaslány na adresu podpora@travai.cz. Více v <a href="{terms_and_conditions_page_url}" target="_blank">Obchodních podmínkách</a> a <a href="{privacy_policy_page_url}" target="_blank">Zásadách ochrany osobních údajů</a>.';


$config['project_details_page_violation_report_popup_submit_report_btn_txt'] = 'Odeslat';

$config['project_details_page_violation_report_popup_reason_required_error_message'] = 'povinný výběr';

$config['project_details_page_violation_report_popup_detail_required_error_message'] = 'povinné pole';


$config['project_details_page_violation_report_user_activity_log_mesage'] = 'Odeslali jste informaci o porušení pravidel na inzerátu "<a href="{project_url}" target="_blank">{project_title}</a>", s potencionálním porušením: {project_violation_reason}.';

$config['project_details_page_violation_report_submit_confirmation_mesage'] = 'informace o porušení pravidel byla odeslána';
########################################################################################################################################################

?>