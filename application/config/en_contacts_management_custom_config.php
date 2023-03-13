<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Meta Variables 
|--------------------------------------------------------------------------
| 
*/
################ Meta Config Variables for contacts_management page ###########
/* Filename: application\modules\dashboard\controllers\Chat.php */
/* Controller: user Method name: index */

// This variable is used to set page meta tag -> application/modules/chat/controllers/Chat.php
$config['contacts_management_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Contacts Management';
$config['contacts_management_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Contacts Management Description';


/*
|--------------------------------------------------------------------------
| user not exist custom messages 
|--------------------------------------------------------------------------
| 
*/

################ Url Routing Variables for contacts_management page ###########
$config['contacts_management_page_url'] = 'contacts-management';

//This variables used as set heading title on contacts management page -- start
$config['contacts_management_headline_title_contacts_management'] = 'Contacts Management';

$config['contacts_management_box_headline_title_contact_requests'] = 'contact requests';

$config['contacts_management_box_headline_title_rejected_contact_requests'] = 'rejected contact requests';

$config['contacts_management_box_headline_title_blocked_contacts'] = 'blocked contacts';

//This variables used as set heading title on contacts management page -- end

$config['get_in_contact_no_contact_requests_record'] = '<h5>You do not have any pending get in contact requests.</h5><p><small>You will receive notifications here each time you receives get in contact request from any user.</small></p>';


$config['get_in_contact_request_accepted_btn_txt'] = 'Accepted'; // Used in header get in contact request menu

$config['get_in_contact_request_reject_btn_txt'] = 'Reject'; // Used in header get in contact request menu

$config['get_in_contact_request_rejected_btn_txt'] = 'Rejected'; // Used in header get in contact request menu

$config['get_in_contact_request_view_all_btn_txt'] = 'view all requests'; // Used in header get in contact request menu

// Used in header get in contact request menu
$config['get_in_contact_request_contacts_mgt_btn_txt'] = 'Contacts management';


$config['get_in_contact_popup_invite_to_project_option'] = 'Invite to a project'; // used in get in contact popup

$config['get_in_contact_popup_send_get_in_contact_request_option'] = 'Send Get in Contact Request'; // used in get in contact popup

$config['get_in_contact_popup_contact_via_chat_option'] = 'contact via chat'; // used in get in contact popup


$config['get_in_contact_popup_drop_down_option_select_project_for_send_invitations'] = 'Select project for send invitations'; // used in get in contact popup

$config['get_in_contact_popup_send_project_invitation_disclaimer'] = "EN - zasláním pozvání na inzerát souhlasíte s <a href='{terms_and_conditions_page_url}' target='blank'>Obchodními podmínkami</a> a <a href='{privacy_policy_page_url}' target='_blank'>Zásadami ochrany osobních údajů</a>";


//post_project_page_locality_drop_down_option_select_locality
$config['get_in_contact_popup_send_contact_request_disclaimer'] = "EN - zasláním žádosti o spojení souhlasíte s <a href='{terms_and_conditions_page_url}' target='blank'>Obchodními podmínkami</a> a <a href='{privacy_policy_page_url}' target='_blank'>Zásadami ochrany osobních údajů</a>";

$config['get_in_contact_popup_send_invitation_btn_txt'] = 'Send Request';

// start -- This variable used in send get in contact popup to display description based on gender on [project details / find professionals / user profile / favourite employer / stand alone portfolio] pages 
$config['get_in_contact_popup_send_contact_request_info_male'] = '<span>You will be able to send only 1 (one) connect request to <strong>{user_first_name_last_name}</strong>. We are going to notify him and will be solely HIS decision to accept your request or not.</span> <span>The moment he accepts, HE will automatically appear in your contact list and you will be able to start exchanging chat messages.</span>';
$config['get_in_contact_popup_send_contact_request_info_female'] = '<span>You will be able to send only 1 (one) connect request to <strong>{user_first_name_last_name}</strong>. We are going to notify her and will be solely HER decision to accept your request or not.</span> <span>The moment she accepts, SHE will automatically appear in your contact list and you will be able to start exchanging chat messages.</span>';
$config['get_in_contact_popup_send_contact_request_info_company'] = '<span>You will be able to send only 1 (one) connect request to <strong>{user_company_name}</strong>. We are going to notify it and will be solely ITS decision to accept your request or not.</span> <span>The moment it accepts, IT will automatically appear in your contact list and you will be able to start exchanging chat messages.</span>';

$config['get_in_contact_popup_send_contact_request_info_company_app_male'] = '<span>APP male-You will be able to send only 1 (one) connect request to <strong>{user_first_name_last_name}</strong>. We are going to notify him and will be solely HIS decision to accept your request or not.</span> <span>The moment he accepts, HE will automatically appear in your contact list and you will be able to start exchanging chat messages.</span>';
$config['get_in_contact_popup_send_contact_request_info_company_app_female'] = '<span>APP female-You will be able to send only 1 (one) connect request to <strong>{user_first_name_last_name}</strong>. We are going to notify her and will be solely HER decision to accept your request or not.</span> <span>The moment she accepts, SHE will automatically appear in your contact list and you will be able to start exchanging chat messages.</span>';
// end


$config['get_in_contact_popup_sender_already_send_request_txt'] = 'You have already sent get in contact request to <a class="default_popup_blue_text">{user_first_name_last_name_or_company_name}</a>';

// This message will be visible to user when user try to send request to such user whose contact request already in his pending requests --start
$config['get_in_contact_popup_receiver_already_has_pending_request_from_sender_male_txt'] = 'You have pending request from <a class="default_popup_blue_text">{user_first_name_last_name}</a>, please accept / reject his request from <a class="default_popup_price" href="{contacts_management_page_url}" target="_blank">Contacts Management</a>.';
$config['get_in_contact_popup_receiver_already_has_pending_request_from_sender_female_txt'] = 'You have pending request from <a class="default_popup_blue_text">{user_first_name_last_name}</a>, please accept / reject her request from <a class="default_popup_price" href="{contacts_management_page_url}" target="_blank">Contacts Management</a>.';
$config['get_in_contact_popup_receiver_already_has_pending_request_from_sender_company_txt'] = 'You have pending request from <a class="default_popup_blue_text">{user_company_name}</a>, please accept / reject it request <a class="default_popup_price" href="{contacts_management_page_url}" target="_blank">Contacts Management</a>.';

$config['get_in_contact_popup_receiver_already_has_pending_request_from_sender_company_app_male_txt'] = 'App male-You have pending request from <a class="default_popup_blue_text">{user_first_name_last_name}</a>, please accept / reject his request <a class="default_popup_price" href="{contacts_management_page_url}" target="_blank">Contacts Management</a>.';
$config['get_in_contact_popup_receiver_already_has_pending_request_from_sender_company_app_female_txt'] = 'App female-You have pending request from <a class="default_popup_blue_text">{user_first_name_last_name}</a>, please accept / reject her request <a class="default_popup_price" href="{contacts_management_page_url}" target="_blank">Contacts Management</a>.';
// end

$config['get_in_contact_sender_realtime_notification_confirmation_message'] = 'Get in contact request successfully sent to <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>'; //this is confirmation message display to sender on successful sending of get in contact request

$config['get_in_contact_receiver_realtime_notification_received_message'] = 'You received contact request from <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>'; //this is realtime notification message display to receiver on successful sent of get in contact request

$config['get_in_contact_sender_sent_request_display_activity_log_message'] = 'You have sent contact request to <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>'; //this is activity log message display to sender on successful sending of get in contact request


$config['get_in_contact_receiver_received_request_display_activity_log_message'] = 'You received contact request from <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>'; //this is activity log message display to receiver on successful sent of get in contact request


$config['get_in_contact_receiver_rejected_request_display_activity_log_message'] = 'You have rejected contact request of <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>';// this is activity log message display to receiver when he reject get in contact request

$config['get_in_contact_receiver_rejected_request_realtime_notification_confirmation_message'] = 'You have rejected contact request of <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>'; // this is confirmation messsage display to recevier when he reject get in contact request


$config['get_in_contact_receiver_accepted_request_display_activity_log_message'] = 'You have accepted contact request of <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>'; // This activity log display to receiver when he accept get in contact request of sender

$config['get_in_contact_sender_request_accepted_confirmation_display_activity_log_message'] = 'Your contact request has been accepted by <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>'; // This activity log message display to sender when his request accepted by receiver

$config['get_in_contact_request_popup_user_accepts_contact_request_from_already_rejected_contact_realtime_notification_confirmation_message'] = 'You accepted contact request of <a href="{user_profile_url}" target="_blank">{user_first_name_last_name_or_company_name}</a>'; // this confirmation message display to user when he accept request from reject request

$config['get_in_contact_sender_request_accepted_confirmation_realtime_notification_message'] = 'Your contact request has been accepted by <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>'; // this is realtime notification message display to sender when receiver accept his request

$config['get_in_contact_request_popup_user_accepts_contact_request_from_pending_contact_realtime_notification_confirmation_message'] = 'You accepted contact request of <a href="{user_profile_url}" target="_blank">{user_first_name_last_name_or_company_name}</a>'; // This confirmation message display to user when he accept contact get in contact request [find professionals / project details / favourite employers / user profile / standalone portfolio] page

$config['get_in_contact_request_popup_user_unblock_blocked_contact_realtime_notification_confirmation_message'] = 'You unblocked <a href="{user_profile_url}" target="_blank">{user_first_name_last_name_or_company_name}</a> in your contacts list'; // This confirmation message display to user when he unblock contact from block contact from [find professionals / project details / favourite employers / user profile / standalone portfolio] page


//This config are using for small pending contact request window as a heading into header.This will show when click on pending contact requested icon into header
$config['top_navigation_small_window_get_in_contact_requests_notifications_heading'] = 'pending contact requests';

$config['get_in_contact_pending_requests_no_request_found'] = "Currently you don't have any pending requests"; // this message display to user when there is no pending request

$config['get_in_contact_rejected_requests_no_request_found'] = "Currently you don't have any rejected requests"; // this message display to user when there is no rejected request

$config['get_in_contact_blocked_requests_no_request_found'] = "Currently you don't have any blocked requests"; // this message display to user when there is no blocked request

// This is block modal realted variable used on chat room page -- start
$config['user_block_contact_modal_body_title'] = 'Please confirm you really want to block this <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name_or_company_name}</a>?';

$config['user_block_contact_modal_block_btn_txt'] = 'Block this user';

// This activity log message display to user when user block any contact from his contact list
$config['user_block_contact_display_activity_message'] = 'You blocked <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> from your contacts list.';
// end

// contact management page to unblock contact button
$config['get_in_contact_request_unblock_btn_txt'] = 'Unblock user';

$config['user_unblock_contact_modal_body_title'] = 'Please confirm you really want to un-block this {user_first_name_last_name_or_company_name}?';

$config['user_unblock_contact_modal_unblock_btn_txt'] = 'Unblock user';

// This button will visible to blocker when he is on find professionals / user profile / favourite employer / project details / standalone portfolio page and try to contact such user who is already in his block list
$config['get_in_contact_modal_blocker_contact_blocked_user_unblock_btn_txt'] = 'Contact';

// This activity log message display to user when he un-block contact to whom he blocked in past
$config['user_unblock_contact_display_activity_message'] = 'You un-blocked <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> from blocked contacts.';


// This message will display to blocked user on chat room page when he try to block user when he is already blocked by that user
$config['chat_room_page_warning_displayed_to_already_blocked_contact_txt'] = 'There is an issue with your action, please refresh page.';


// This message will display to blocker on chat room page when he try block user who is already blocked by him from other instance of his login
$config['chat_room_page_warning_displayed_to_blocker_txt'] = 'This user is in your blocklist, Please manage it from contacts management page.';


// This message will visible to blocker when he is on find professionals / user profile / favourite employer / standalone portfolio page and try to contact such user who is already in his block list -- start
$config['find_professionals_page_user_male_already_in_blocked_contacts_list_txt'] = '<a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a> is currently in your blocklist. By continuing you will enable him to be able to contact you again in the future.';
$config['find_professionals_page_user_female_already_in_blocked_contacts_list_txt'] = '<a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a> is currently in your blocklist. By continuing you will enable her to be able to contact you again in the future.';
$config['find_professionals_page_user_company_already_in_blocked_contacts_list_txt'] = '<a class="default_popup_blue_text milestone_blue_text">{user_company_name}</a> is currently in your blocklist. By continuing you will enable it to be able to contact you again in the future.';

$config['find_professionals_page_user_company_app_male_already_in_blocked_contacts_list_txt'] = 'APP male-<a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a> is currently in your blocklist. By continuing you will enable him to be able to contact you again in the future.';
$config['find_professionals_page_user_company_app_female_already_in_blocked_contacts_list_txt'] = 'APP female-<a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a> is currently in your blocklist. By continuing you will enable her to be able to contact you again in the future.';
// end

// This message will be displayed in to get in contact popup window when user click on "chat with contact" button on [find professionals / user profile ] and contact me butto on project detail page
$config['user_blocked_contact_get_in_contact_popup_message'] = "Currently you can not send message to this user. Please try again later.";


// This message will visible to blocker when he click on contact button from bidder list who is already in his block list -- start
$config['project_details_page_user_male_already_in_blocked_contacts_list_txt'] = '<a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a> is currently in your blocklist. By continuing you will enable him to be able to contact you again in the future.';
$config['project_details_page_user_female_already_in_blocked_contacts_list_txt'] = '<a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a> is currently in your blocklist. By continuing you will enable her to be able to contact you again in the future.';
$config['project_details_page_user_company_already_in_blocked_contacts_list_txt'] = '<a class="default_popup_blue_text milestone_blue_text">{user_company_name}</a> is currently in your blocklist. By continuing you will enable it to be able to contact you again in the future.';

$config['project_details_page_user_company_app_male_already_in_blocked_contacts_list_txt'] = 'APP male-<a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a> is currently in your blocklist. By continuing you will enable him to be able to contact you again in the future.';
$config['project_details_page_user_company_app_female_already_in_blocked_contacts_list_txt'] = 'APP female-<a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a> is currently in your blocklist. By continuing you will enable her to be able to contact you again in the future.';
//end


//This message will be displayed into chat room or small chat window when user try to send message to user where he already blocked by that user
$config['blocked_contact_message_sending_failed_error_message'] = "Currently you can not send message to this user. Please try again later.";

//This message will be displayed into chat room page or small chat window when user try to send message to user where that user is already blocked by other instance of same login
$config['blocker_contact_already_blocked_message_sending_failed_error_message'] = 'This user is currently in your blocklist. Please manage him from <a class="default_popup_price" href="{contacts_management_page_url}" target="_blank">Contacts Management</a>.';

// This message will be displayed to user when he try to reject already accepted get in contact request from [find profesionals / contacts management / project details / favourite employer / user profile / standalone portfolio] page -- start
$config['get_in_contact_request_user_try_to_reject_already_accepted_male_user_request_error_message'] = 'You have already accepted get in contact request of <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a>. you can not contact him for the moment.';
$config['get_in_contact_request_user_try_to_reject_already_accepted_female_user_request_error_message'] = 'You have already accepted get in contact request of <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a>. you can not contact her for the moment.';
$config['get_in_contact_request_user_try_to_reject_already_accepted_company_user_request_error_message'] = 'You have already accepted get in contact request of <a class="default_popup_blue_text milestone_blue_text">{user_company_name}</a>. you can not contact it for the moment.';

$config['get_in_contact_request_user_try_to_reject_already_accepted_company_app_male_user_request_error_message'] = 'APP male-You have already accepted get in contact request of <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a>. you can not contact him for the moment.';
$config['get_in_contact_request_user_try_to_reject_already_accepted_company_app_female_user_request_error_message'] = 'APP female-You have already accepted get in contact request of <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a>. you can not contact her for the moment.';
// end

// this error message display to user when he try to accept already rejected request from [find profesionals / contacts management / project details / favourite employer / user profile / standalone portfolio] pages - start
$config['get_in_contact_request_user_try_to_accept_already_rejected_male_user_request_error_message'] = 'You have already rejected get in contact request of <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a>. you can not contact him for the moment.';
$config['get_in_contact_request_user_try_to_accept_already_rejected_female_user_request_error_message'] = 'You have already rejected get in contact request of <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a>. you can not contact her for the moment.';
$config['get_in_contact_request_user_try_to_accept_already_rejected_company_user_request_error_message'] = 'You have already rejected get in contact request of <a class="default_popup_blue_text milestone_blue_text">{user_company_name}</a>. you can not contact it for the moment.';

$config['get_in_contact_request_user_try_to_accept_already_rejected_company_app_male_user_request_error_message'] = 'APP male-You have already rejected get in contact request of <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a>. you can not contact him for the moment.';
$config['get_in_contact_request_user_try_to_accept_already_rejected_company_app_female_user_request_error_message'] = 'APP female-You have already rejected get in contact request of <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a>. you can not contact her for the moment.';
// end

// This error message display to user when he try to accepet / reject request which is already expired --start
$config['get_in_contact_request_user_try_to_reject_already_expired_request_error_message'] = 'This get in contact request expired. you cant reject for now.';
$config['get_in_contact_request_user_try_to_accept_already_expired_request_error_message'] = 'This get in contact request expired. you cant accept for now.';
// end

// This error message display to user when he try to reject get in contact request from top navigation menu which is already accepted --start
$config['get_in_contact_request_top_navigation_window_user_already_accepted_error_message_male_receiver'] = 'Male-You have already accepted get in contact request of <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a>.';
$config['get_in_contact_request_top_navigation_window_user_already_accepted_error_message_female_receiver'] = 'Female-You have already accepted get in contact request of <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a>.';
$config['get_in_contact_request_top_navigation_window_user_already_accepted_error_message_company_receiver'] = 'Company-You have already accepted get in contact request of <a class="default_popup_blue_text milestone_blue_text">{user_company_name}</a>.';

$config['get_in_contact_request_top_navigation_window_user_already_accepted_error_message_company_app_male_receiver'] = 'APP male-You have already accepted get in contact request of <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a>.';
$config['get_in_contact_request_top_navigation_window_user_already_accepted_error_message_company_app_female_receiver'] = 'APP female-You have already accepted get in contact request of <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a>.';
//end

// This error message display to user when he try to accept request from top navigation menu -- start
$config['get_in_contact_request_top_navigation_window_user_already_rejected_error_message_male_receiver'] = 'Male-You have already rejected get in contact request of <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a>.';
$config['get_in_contact_request_top_navigation_window_user_already_rejected_error_message_female_receiver'] = 'Female-You have already rejected get in contact request of <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a>.';
$config['get_in_contact_request_top_navigation_window_user_already_rejected_error_message_company_receiver'] = 'Company-You have already rejected get in contact request of <a class="default_popup_blue_text milestone_blue_text">{user_company_name}</a>.';

$config['get_in_contact_request_top_navigation_window_user_already_rejected_error_message_company_app_male_receiver'] = 'APP male-You have already rejected get in contact request of <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a>.';
$config['get_in_contact_request_top_navigation_window_user_already_rejected_error_message_company_app_female_receiver'] = 'APP female-You have already rejected get in contact request of <a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a>.';
// end

//This message will be visible to user when he try to send request to such user to whom he already put in to reject contact list -- start
$config['get_in_contact_popup_receiver_male_already_in_rejected_contacts_list_txt'] = '<a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a> is in your rejected users list. By continuing you are going to accept this user in your contacts list and he will be able to contact you in future.';
$config['get_in_contact_popup_receiver_female_already_in_rejected_contacts_list_txt'] = '<a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a> is in your rejected users list. By continuing you are going to accept this user in your contacts list and she will be able to contact you in future.';
$config['get_in_contact_popup_receiver_company_already_in_rejected_contacts_list_txt'] = '<a class="default_popup_blue_text milestone_blue_text">{user_company_name}</a> is in your rejected users list. By continuing you are going to accept this user in your contacts list and it will be able to contact you in future.';

$config['get_in_contact_popup_receiver_company_app_male_already_in_rejected_contacts_list_txt'] = 'APP male-<a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a> is in your rejected users list. By continuing you are going to accept this user in your contacts list and he will be able to contact you in future.';
$config['get_in_contact_popup_receiver_company_app_female_already_in_rejected_contacts_list_txt'] = 'APP female-<a class="default_popup_blue_text milestone_blue_text">{user_first_name_last_name}</a> is in your rejected users list. By continuing you are going to accept this user in your contacts list and she will be able to contact you in future.';
// end
?>