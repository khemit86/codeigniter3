<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Meta Variables 
|--------------------------------------------------------------------------
| 
*/

################ Meta Variables ###########
$config['chat_room_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Chat ROOM';

$config['chat_room_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Chat ROOM';

################ Url Routing Variables ###########
//Chat
$config['chat_room_page_url'] = 'chat-room';

$config['chat_room_page_block_contact_btn_txt'] = 'Block user';

// This variable is used to display label on see more in chat room button
$config['dashboard_see_more_in_chat_room_label_text'] = 'see more in chat room';


// variable to display new message text between horizontal line 
$config['users_chat_new_message_text'] = 'New Message';

// this variable is used to display loader text when user clicks on contact from chat room
$config['chat_room_loader_display_text'] = 'loading please wait...';

// this variable is used to display error message indeside small chat window / inprogress project detail page message tab / chat room
$config['connection_issue_for_sender_display_error_message'] = 'message sending failed. please try again later.';


// this variable is used to display message under contact list see more chat room button when user loss connection from internet
$config['dashboard_user_not_connected_websocket_display_error_message'] = 'Zprávy nejsou k dispozici, nejste připojeni.';


// This variable is used to display error message in [small chat window / project detail / chat room] when user session expired and he tries to send message

$config['sender_session_not_exist_display_error_message'] = 'Message not delivered. Try to refresh the page.';
// This message will display to user when his session expired or there is different session conflict and he try to send message either from already open small chat window or chat room page window

// This variable is used to display error message into popup when user is not connected with internet and try to click on chat room button
$config['connection_issue_for_sender_display_error_message_chat_room_button_clicked'] = 'Chat functionality is currently disabled. It seems you are not connected to internet.';


// This variable is used to display error message in message window when user is disconnect with internet
$config['connection_issue_for_sender_display_error_message_on_chat_room_page'] = 'Chat functionality is currently disabled. It seems you are not connected to internet.';


$config['custom_chat_attachment_allowed_file_extensions'] = '"png","PNG","gif","GIF","jpeg","JPEG","jpg","JPG","pdf","application/PDF","txt","xls","xlsx","doc","docx","zip","rar"'; //exclusion list : 
$config['plugin_chat_attachment_allowed_file_extensions'] = 'image/*,.pdf,.xls, .xlsx, .doc, .docx, .txt, .zip'; //exclusion list : 

$config['chat_attachment_invalid_file_extension_validation_message'] = "The file type you are trying to upload is not allowed!";



$config['chat_attachment_uploading_progress_text'] = 'Uploading...';

//Small Version Show More and Show Less
$config['chat_room_contacts_list_show_more_text'] = 'Show More ( + )';
$config['chat_room_contacts_list_show_less_text'] = 'Show Less ( - )';

$config['chat_attachment_file_uploading_failed_message'] = '{files_name} sending failed. try to send it again.';

// This message will display to user when user click on download and file not exist on server
$config['chat_attachments_download_failed_error_message'] = 'An error occured. please refresh the page';


$config['chat_attachments_download_failed_error_message_small_chat_window'] = 'an error occured. please reopen window.';
// This message will display to user when user click on download and file not exist on server


$config['chat_room_search_filter_no_result_found_message'] = 'No contacts found.'; // This message will display to user when search filter doesen't return any result


$config['chat_room_search_keyword_placeholder'] = 'search...'; // This is a placeholder for search input box on chat room page


// This is an error message which will be display to user when project is deleted by admin and he try to send message on that channel
$config['communication_disabled_on_chat_project_channel_project_deleted_by_admin_txt'] = 'Communication on this channel is disabled.';

// Error message display in error popup when sp already place bid on project / fulltim job and try to contact po from contact me button where project / fulltime job already deleted by admin - and no past discussion / with past discussion on the project between the 2
$config['project_details_page_sp_view_contact_po_deleted_project'] = 'project has been deleted. please refresh the page';

// This is placeholder text which will be display in chat window input
$config['chat_default_type_here_message_placeholder_txt'] = 'type message here-en';

?>