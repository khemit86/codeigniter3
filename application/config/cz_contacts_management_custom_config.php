<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Meta  Variables
|--------------------------------------------------------------------------
|
*/
################  Meta Config Variables for contacts_management page ###########
/* Filename: application\modules\dashboard\controllers\Chat.php */
/* Controller: user Method name: index */

// This variable is used to set page meta tag -> application/modules/chat/controllers/Chat.php
$config['contacts_management_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Správa kontaktů';
$config['contacts_management_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Správa kontaktů';


/*
|--------------------------------------------------------------------------
|  user not exist  custom messages
|--------------------------------------------------------------------------
|
*/

################  Url Routing Variables for contacts_management page ###########
$config['contacts_management_page_url']  = 'sprava-kontaktu';

//This variables used as set heading title on contacts management page -- start
$config['contacts_management_headline_title_contacts_management']  = 'Správa kontaktů';

$config['contacts_management_box_headline_title_contact_requests']  = 'Žádosti o spojení';

$config['contacts_management_box_headline_title_rejected_contact_requests']  = 'Odmítnuté žádosti o spojení';

$config['contacts_management_box_headline_title_blocked_contacts']  = 'Blokované kontakty';

//This variables used as set heading title on contacts management page -- end

$config['get_in_contact_no_contact_requests_record'] = '<h5>Nemáte žádné čekající žádosti o spojení.</h5><p><small>Zde obdržíte oznámení pokaždé, když obdržíte žádost o spojení.</small></p>';

$config['get_in_contact_request_accepted_btn_txt'] = 'Přijatý'; //Used in header get in contact request menu

$config['get_in_contact_request_reject_btn_txt'] = 'Odmítnout'; //Used in header get in contact request menu

$config['get_in_contact_request_rejected_btn_txt'] = 'Odmítnutý'; //Used in header get in contact request menu

$config['get_in_contact_request_view_all_btn_txt'] = 'zobrazit všechny žádosti'; //Used in header get in contact request menu

// Used in header get in contact request menu
$config['get_in_contact_request_contacts_mgt_btn_txt'] = 'Správa kontaktů';


$config['get_in_contact_popup_invite_to_project_option'] = 'Pozvat na inzerát'; // used in get in contact popup

$config['get_in_contact_popup_send_get_in_contact_request_option'] = 'Odeslat žádost o spojení'; // used in get in contact popup


$config['get_in_contact_popup_contact_via_chat_option'] = 'Kontaktovat přes chat'; // used in get in contact popup


$config['get_in_contact_popup_drop_down_option_select_project_for_send_invitations'] = 'vybrat projekt nebo pracovní pozici'; // used in get in contact popup

$config['get_in_contact_popup_send_project_invitation_disclaimer'] = "zasláním pozvání na inzerát souhlasíte s <a href='{terms_and_conditions_page_url}' target='blank'>Obchodními podmínkami</a> a <a href='{privacy_policy_page_url}' target='_blank'>Zásadami ochrany osobních údajů</a>";


$config['get_in_contact_popup_send_contact_request_disclaimer'] = "zasláním žádosti o spojení souhlasíte s <a href='{terms_and_conditions_page_url}' target='_blank'>Obchodními podmínkami</a> a <a href='{privacy_policy_page_url}' target='blank'>Zásadami ochrany osobních údajů</a>";

$config['get_in_contact_popup_send_invitation_btn_txt'] = 'Odeslat';

// start -- This variable used in send get in contact popup to display description based on gender on [project details / find professionals / user profile / favourite employer / stand alone portfolio] pages
$config['get_in_contact_popup_send_contact_request_info_male'] = 'Pokračováním odešlete žádost o spojení s <strong>{user_first_name_last_name}</strong>. Na vybraného uživatele je možné posílat opakovaně žádost jednou měsíčně.<br><br><strong>{user_first_name_last_name}</strong> je tímto informována a bude její rozhodnutí žádost přijmout. Po přijetí žádosti se automaticky objeví ve vašem seznamu kontaktů a budete ve spojení.';

$config['get_in_contact_popup_send_contact_request_info_female'] = 'Pokračováním odešlete žádost o spojení s <strong>{user_first_name_last_name}</strong>. Na vybraného uživatele je možné posílat opakovaně žádost jednou měsíčně.<br><br><strong>{user_first_name_last_name}</strong> je tímto informována a bude její rozhodnutí žádost přijmout. Po přijetí žádosti se automaticky objeví ve vašem seznamu kontaktů a budete ve spojení.';

$config['get_in_contact_popup_send_contact_request_info_company'] = 'Pokračováním odešlete žádost o spojení s <strong>{user_company_name}</strong>. Na vybraného uživatele je možné posílat opakovaně žádost jednou měsíčně.<br><br><strong>{user_company_name}</strong> jsou tímto informováni a bude jejich rozhodnutí žádost přijmout. Po přijetí žádosti se automaticky objeví ve vašem seznamu kontaktů a budete ve spojení.';

$config['get_in_contact_popup_send_contact_request_info_company_app_male'] = 'Pokračováním odešlete žádost o spojení s <strong>{user_first_name_last_name}</strong>. Na vybraného uživatele je možné posílat opakovaně žádost jednou měsíčně.<br><br><strong>{user_first_name_last_name}</strong> je tímto informována a bude její rozhodnutí žádost přijmout. Po přijetí žádosti se automaticky objeví ve vašem seznamu kontaktů a budete ve spojení.';

$config['get_in_contact_popup_send_contact_request_info_company_app_female'] = 'Pokračováním odešlete žádost o spojení s <strong>{user_first_name_last_name}</strong>. Na vybraného uživatele je možné posílat opakovaně žádost jednou měsíčně.<br><br><strong>{user_first_name_last_name}</strong> je tímto informována a bude její rozhodnutí žádost přijmout. Po přijetí žádosti se automaticky objeví ve vašem seznamu kontaktů a budete ve spojení.';
// end

$config['get_in_contact_popup_sender_already_send_request_txt'] = 'Již jste odeslali žádost o spojení s {user_first_name_last_name_or_company_name}.';

// This message will be visible to user when he try to send request to such user whose contact request already in his pending requests --start
$config['get_in_contact_popup_receiver_already_has_pending_request_from_sender_male_txt'] = 'Již máte od <a class="default_popup_blue_text">{user_first_name_last_name}</a> čekající žádost. Přijměte nebo odmítněte jeho žádost ze sekce <a class="default_popup_price" href="{contacts_management_page_url}" target="_blank">Správa kontaktů</a>.';

$config['get_in_contact_popup_receiver_already_has_pending_request_from_sender_female_txt'] = 'Již máte od <a class="default_popup_blue_text">{user_first_name_last_name}</a> čekající žádost. Přijměte nebo odmítněte její žádost ze sekce <a class="default_popup_price" href="{contacts_management_page_url}" target="_blank">Správa kontaktů</a>.';

$config['get_in_contact_popup_receiver_already_has_pending_request_from_sender_company_txt'] = 'Již máte od <a class="default_popup_blue_text">{user_company_name}</a> čekající žádost. Přijměte nebo odmítněte jejich žádost ze sekce <a class="default_popup_price" href="{contacts_management_page_url}" target="_blank">Správa kontaktů</a>.';

$config['get_in_contact_popup_receiver_already_has_pending_request_from_sender_company_app_male_txt'] = 'Již máte od <a class="default_popup_blue_text">{user_first_name_last_name}</a> čekající žádost. Přijměte nebo odmítněte jeho žádost ze sekce <a class="default_popup_price" href="{contacts_management_page_url}" target="_blank">Správa kontaktů</a>.';

$config['get_in_contact_popup_receiver_already_has_pending_request_from_sender_company_app_female_txt'] = 'Již máte od <a class="default_popup_blue_text">{user_first_name_last_name}</a> čekající žádost. Přijměte nebo odmítněte její žádost ze sekce <a class="default_popup_price" href="{contacts_management_page_url}" target="_blank">Správa kontaktů</a>.';
// end

$config['get_in_contact_sender_realtime_notification_confirmation_message'] = 'žádost o spojení s <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> byla odeslána';//this is confirmation message display to sender on successful sending of get in contact request


$config['get_in_contact_receiver_realtime_notification_received_message'] = 'obdrželi jste žádost o spojení od <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>'; //this is realtime notification message display to receiver on successful sent of get in contact request


$config['get_in_contact_sender_sent_request_display_activity_log_message'] = 'Odeslali jste žádost o spojení s <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>.';//this is activity log message display to sender on successful sending of get in contact request

$config['get_in_contact_receiver_received_request_display_activity_log_message'] = 'Obdrželi jste žádost o spojení od <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>.';//this is activity log message display to receiver on successful sent of get in contact request


$config['get_in_contact_receiver_rejected_request_display_activity_log_message'] = 'Odmítli jste žádost o spojení s <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>.';// this is activity log message display to receiver when he reject get in contact request

$config['get_in_contact_receiver_rejected_request_realtime_notification_confirmation_message'] = 'odmítli jste žádost o spojení s <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>';// this is confirmation messsage display to recevier when he reject get in contact request

$config['get_in_contact_receiver_accepted_request_display_activity_log_message'] = 'Přijali jste žádost o spojení s <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>.'; // This activity log display to receiver when he accept get in contact request of sender

$config['get_in_contact_sender_request_accepted_confirmation_display_activity_log_message'] = 'Vaše žádost o spojení s <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> byla přijata.'; // This activity log message display to sender when his request accepted by receiver

$config['get_in_contact_request_popup_user_accepts_contact_request_from_already_rejected_contact_realtime_notification_confirmation_message'] = 'přijali jste žádost o spojení s <a href="{user_profile_url}" target="_blank">{user_first_name_last_name_or_company_name}</a>'; // this confirmation message display to user when he accept request from reject request

$config['get_in_contact_sender_request_accepted_confirmation_realtime_notification_message'] = 'vaše žádost o spojení s <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> byla přijata. nyní jste ve spojení.';// this is realtime notification message display to sender when receiver accept his request

$config['get_in_contact_request_popup_user_accepts_contact_request_from_pending_contact_realtime_notification_confirmation_message'] = 'přijali jste žádost o spojení s <a href="{user_profile_url}" target="_blank">{user_first_name_last_name_or_company_name}</a>'; // // This confirmation message display to user when he accept contact get in contact request [find professionals / project details / favourite employers / user profile / standalone portfolio] page

$config['get_in_contact_request_popup_user_unblock_blocked_contact_realtime_notification_confirmation_message'] = 'uvolnili jste <a href="{user_profile_url}" target="_blank">{user_first_name_last_name_or_company_name}</a> z blokovaných kontaktů'; // This confirmation message display to user when he unblock contact from block contact from [find professionals / project details / favourite employers / user profile / standalone portfolio] page


//This config are using for small pending contact request window as a heading into header.This will show when click on pending contact requested icon into header
$config['top_navigation_small_window_get_in_contact_requests_notifications_heading']  = 'Žádosti o spojení';

$config['get_in_contact_pending_requests_no_request_found'] = "momentálně nemáte žádné žádosti o spojení"; // this message display to user when there is no pending request

$config['get_in_contact_rejected_requests_no_request_found'] = "momentálně nemáte žádné odmítnuté žádosti o spojení"; // this message display to user when there is no rejected request

$config['get_in_contact_blocked_requests_no_request_found'] = "momentálně nemáte žádné blokované kontakty"; // this message display to user when there is no blocked request

// This is block modal realted variable used on chat room page -- start
$config['user_block_contact_modal_body_title'] = 'Opravdu chcete blokovat <a class="default_popup_blue_text">{user_first_name_last_name_or_company_name}</a> ?';

$config['user_block_contact_modal_block_btn_txt'] = 'zablokovat uživatele';

// This activity log message display to user when user block any contact from his contact list
$config['user_block_contact_display_activity_message'] = 'Zablokovali jste <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> z vašeho seznamu kontaktů.';
// end

// contact management page to unblock contact button
$config['get_in_contact_request_unblock_btn_txt'] = 'Odblokovat uživatele';

$config['user_unblock_contact_modal_body_title'] = 'Opravdu chcete odblokovat {user_first_name_last_name_or_company_name} ?';

$config['user_unblock_contact_modal_unblock_btn_txt'] = 'Odblokovat uživatele';
// This button will visible to blocker when he is on find professionals / user profile / favourite employer / project details / standalone portfolio page and try to contact such user who is already in his block list
$config['get_in_contact_modal_blocker_contact_blocked_user_unblock_btn_txt'] = 'Kontaktovat';

// This activity log message display to user when he un-block contact to whom he blocked in past
$config['user_unblock_contact_display_activity_message'] = 'Odblokovali jste <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> a je zpět ve vašem seznamu kontaktů.';

// This message will display to blocked user on chat room page when he try to block user when he is already blocked by that user
$config['chat_room_page_warning_displayed_to_already_blocked_contact_txt'] = 'Tato akce nelze provést, aktualizujte stránku.';



// This message will display to blocker on chat room page when he try block user who is already blocked by him from other instance of his login
$config['chat_room_page_warning_displayed_to_blocker_txt'] = 'Tento uživatel je v seznamu blokovaných kontaktů.';


// This message will visible to blocker when he is on find professionals / user profile / favourite employer  / standalone portfolio page and try to contact such user who is already in his block list -- start
$config['find_professionals_page_user_male_already_in_blocked_contacts_list_txt'] = '<a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a> je na vašem seznamu blokovaných kontaktů. Pokračováním mu umožníte, aby vás kontaktoval.';
$config['find_professionals_page_user_female_already_in_blocked_contacts_list_txt'] = '<a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a> je na vašem seznamu blokovaných kontaktů. Pokračováním jí umožníte, aby vás kontaktovala.';
$config['find_professionals_page_user_company_already_in_blocked_contacts_list_txt'] = '<a class="default_popup_blue_text milestone_blue_text">{user_company_name}</a> jsou na vašem seznamu blokovaných kontaktů. Pokračováním jim umožníte, aby vás kontaktovali.';

$config['find_professionals_page_user_company_app_male_already_in_blocked_contacts_list_txt'] = '<a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a> je na vašem seznamu blokovaných kontaktů. Pokračováním mu umožníte, aby vás kontaktoval.';
$config['find_professionals_page_user_company_app_female_already_in_blocked_contacts_list_txt'] = '<a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a> je na vašem seznamu blokovaných kontaktů. Pokračováním jí umožníte, aby vás kontaktovala.';
// end

// This message will be displayed in to get in contact popup window when user click on "chat with contact" button on [find professionals / user profile ] and contact me butto on project detail page
$config['user_blocked_contact_get_in_contact_popup_message'] = "Nelze odeslat zprávu tomuto uživateli. Vyzkoušejte později.";


// This message will visible to blocker when he click on contact button from bidder list who is already in his block list -- start
$config['project_details_page_user_male_already_in_blocked_contacts_list_txt'] = '<a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a> je na vašem seznamu blokovaných kontaktů. Pokračováním mu umožníte, aby vás kontaktoval.';
$config['project_details_page_user_female_already_in_blocked_contacts_list_txt'] = '<a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a> je na vašem seznamu blokovaných kontaktů. Pokračováním jí umožníte, aby vás kontaktovala.';
$config['project_details_page_user_company_already_in_blocked_contacts_list_txt'] = '<a class="default_popup_blue_text milestone_blue_text">{user_company_name}</a> jsou na vašem seznamu blokovaných kontaktů. Pokračováním jim umožníte, aby vás kontaktovali.';

$config['project_details_page_user_company_app_male_already_in_blocked_contacts_list_txt'] = '<a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a> je na vašem seznamu blokovaných kontaktů. Pokračováním mu umožníte, aby vás kontaktoval.';
$config['project_details_page_user_company_app_female_already_in_blocked_contacts_list_txt'] = '<a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a> je na vašem seznamu blokovaných kontaktů. Pokračováním jí umožníte, aby vás kontaktovala.';
//end

//This message will be displayed into chat room or small chat window when user try to send message to user where he already blocked by that user
$config['blocked_contact_message_sending_failed_error_message'] = "Nelze odeslat zprávu tomuto uživateli. Vyzkoušejte později.";


//This message will be displayed into chat room page or small chat window when user try to send message to user where that user is already blocked by other instance of same login
$config['blocker_contact_already_blocked_message_sending_failed_error_message'] = 'Tento uživatel je aktuálně ve vašem seznamu blokovaných kontaktů. Upravovat kontakt můžete ze sekce "<a class="default_popup_price" href="{contacts_management_page_url}" target="_blank">Správa kontaktů</a>."';



// This message will be displayed to user when he try to reject already accepted get in contact request from [find profesionals / contacts management / project details / favourite employer / user profile / standalone portfolio] page -- start -> TO BE TESTED THE VARIABLES ASSOCIATIONS - SEEMS THERE ARE ERRORS - catalin 23.12.2020
$config['get_in_contact_request_user_try_to_reject_already_accepted_male_user_request_error_message'] = 'Již jste přijal žádost o spojení od <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a>.';

$config['get_in_contact_request_user_try_to_reject_already_accepted_female_user_request_error_message'] = 'Již jste přijala žádost o spojení od <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a>.';

$config['get_in_contact_request_user_try_to_reject_already_accepted_company_user_request_error_message'] = 'Již jste přijali žádost o spojení od <a class="default_popup_blue_text milestone_blue_text">{user_company_name}</a>.';

$config['get_in_contact_request_user_try_to_reject_already_accepted_company_app_male_user_request_error_message'] = 'Již jste přijal žádost o spojení od <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a>.';

$config['get_in_contact_request_user_try_to_reject_already_accepted_company_app_female_user_request_error_message'] = 'Již jste přijala žádost o spojení od <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a>.';
// end

// this error message display to user when he try to accept already rejected request from [find profesionals / contacts management / project details / favourite employer / user profile / standalone portfolio] pages - start -> TO BE TESTED THE VARIABLES ASSOCIATIONS - SEEMS THERE ARE ERRORS - catalin 23.12.2020


//-> COMMENTED VARIABLES TO REPLACE ACTUAL USED ONES - 23.12.2020
//get_in_contact_request_user_ male _ try_to_accept_already_rejected_request_error_message
$config['get_in_contact_request_user_try_to_accept_already_rejected_male_user_request_error_message'] = 'Odmítli jste žádost o spojení od <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a>. Nelze provést tuto volbu.';

//get_in_contact_request_user_ female _ try_to_accept_already_rejected_request_error_message
$config['get_in_contact_request_user_try_to_accept_already_rejected_female_user_request_error_message'] = 'Odmítli jste žádost o spojení od <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a>. Nelze provést tuto volbu.';

//get_in_contact_request_user_ company _ try_to_accept_already_rejected_request_error_message
$config['get_in_contact_request_user_try_to_accept_already_rejected_company_user_request_error_message'] = 'Odmítli jste žádost o spojení od <a class="default_popup_blue_text milestone_blue_text">{user_company_name}</a>. Nelze provést tuto volbu.';

//get_in_contact_request_user_ company_app_male _ try_to_accept_already_rejected_request_error_message
$config['get_in_contact_request_user_try_to_accept_already_rejected_company_app_male_user_request_error_message'] = 'Odmítli jste žádost o spojení od <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a>. Nelze provést tuto volbu.';

//get_in_contact_request_user_ company_app_female _ try_to_accept_already_rejected_request_error_message
$config['get_in_contact_request_user_try_to_accept_already_rejected_company_app_female_user_request_error_message'] = 'Odmítli jste žádost o spojení od <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a>. Nelze provést tuto volbu.';
// end

// This error message display to user when he try to accepet / reject request which is already expired --start
$config['get_in_contact_request_user_try_to_reject_already_expired_request_error_message'] = 'Žádost o spojení vypršela. Nelze provést tuto volbu.';
$config['get_in_contact_request_user_try_to_accept_already_expired_request_error_message'] = 'Žádost o spojení vypršela. Nelze provést tuto volbu.';
// end

// This error message display to user when he try to reject get in contact request from top navigation menu which is already accepted --start -> TO BE TESTED THE VARIABLES ASSOCIATIONS - SEEMS THERE ARE ERRORS - catalin 23.12.2020
$config['get_in_contact_request_top_navigation_window_user_already_accepted_error_message_male_receiver'] = 'Již jste přijali žádost o spojení od <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a>.';

$config['get_in_contact_request_top_navigation_window_user_already_accepted_error_message_female_receiver'] = 'Již jste přijali žádost o spojení od <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a>.';

$config['get_in_contact_request_top_navigation_window_user_already_accepted_error_message_company_receiver'] = 'Již jste přijali žádost o spojení od <a class="default_popup_blue_text milestone_blue_text">{user_company_name}</a>.';

$config['get_in_contact_request_top_navigation_window_user_already_accepted_error_message_company_app_male_receiver'] = 'Již jste přijali žádost o spojení od <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a>.';

$config['get_in_contact_request_top_navigation_window_user_already_accepted_error_message_company_app_female_receiver'] = 'Již jste přijali žádost o spojení od <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a>.';
//end

// This error message display to user when he try to accept request from top navigation menu -- start -> TO BE TESTED THE VARIABLES ASSOCIATIONS - SEEMS THERE ARE ERRORS - catalin 23.12.2020
$config['get_in_contact_request_top_navigation_window_user_already_rejected_error_message_male_receiver'] = 'Již jste odmítli žádost o spojení s <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a>.';

$config['get_in_contact_request_top_navigation_window_user_already_rejected_error_message_female_receiver'] = 'Již jste odmítli žádost o spojení s <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a>.';

$config['get_in_contact_request_top_navigation_window_user_already_rejected_error_message_company_receiver'] = 'Již jste odmítli žádost o spojení s <a class="default_popup_blue_text milestone_blue_text">{user_company_name}</a>.';

$config['get_in_contact_request_top_navigation_window_user_already_rejected_error_message_company_app_male_receiver'] = 'Již jste odmítli žádost o spojení s <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a>.';

$config['get_in_contact_request_top_navigation_window_user_already_rejected_error_message_company_app_female_receiver'] = 'Již jste odmítli žádost o spojení s <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a>.';
// end

//This message will be visible to user when he try to send request to such user to whom he already put in to reject contact list -- start
$config['get_in_contact_popup_receiver_male_already_in_rejected_contacts_list_txt'] = '<a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a> je na vašem seznamu odmítnutých spojení. Pokračováním přijmete jeho žádost a budete v kontaktu.';

$config['get_in_contact_popup_receiver_female_already_in_rejected_contacts_list_txt'] = '<a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a> je na vašem seznamu odmítnutých spojení. Pokračováním přijmete její žádost a budete v kontaktu.';

$config['get_in_contact_popup_receiver_company_already_in_rejected_contacts_list_txt'] = '<a class="default_popup_blue_text milestone_blue_text">{user_company_name}</a> jsou na vašem seznamu odmítnutých spojení. Pokračováním přijmete jejich žádost a budete v kontaktu.';

$config['get_in_contact_popup_receiver_company_app_male_already_in_rejected_contacts_list_txt'] = '<a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a> je na vašem seznamu odmítnutých spojení. Pokračováním přijmete jeho žádost a budete v kontaktu.';

$config['get_in_contact_popup_receiver_company_app_female_already_in_rejected_contacts_list_txt'] = '<a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a> je na vašem seznamu odmítnutých spojení. Pokračováním přijmete její žádost a budete v kontaktu.';
// end

?>