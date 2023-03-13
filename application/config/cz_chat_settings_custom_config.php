<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// number of message to be displayed initially when user load message window
$config['project_details_page_messages_tab_users_conversation_listing_limit'] = 20;

// number of message to be displayed initially when user load small chat window
$config['small_window_chat_users_conversation_listing_limit'] = 20;

// number of message to be display initially when user click on contact from contact list of chat room
$config['chat_room_page_users_conversation_listing_limit'] = 35;

// this variable is used to display progress bar to user for specified miliseconds
$config['chat_room_loaderprogressbar_display_time'] = 1000;

// this variable is used to handle error message display time [specify time in miliseconds] 
$config['connection_issue_for_sender_display_error_message_timeout'] = 3000;

// this variable is used to handle error message display time [specify time in miliseconds]
$config['sender_session_not_exist_display_error_message_timeout'] = 3000;

$config['chat_attachment_maximum_size_limit'] = 3; //(Size in MB) size of attachment allowed on for chat to be upload

$config['chat_attachment_maximum_size_validation_message'] = "soubor, který chcete nahrát má velikost {file_size_mb}MB a tím překračuje maximální povolenou velikost souboru ".$config['chat_attachment_maximum_size_limit']." MB";


$config['maximum_allowed_number_of_attachments_on_chat_room_and_project_detail'] = 3; //number of file allowed to user to upload on chat room and project detail page

$config['maximum_allowed_number_of_attachments_on_small_chat_window'] = 2; //number of file allowed to user to upload on small chat window


$config['chat_attachment_allowed_number_of_files_validation_message'] = "nahrávat je možné maximálně ".$config['maximum_allowed_number_of_attachments_on_chat_room_and_project_detail']." soubory";


$config['chat_attachment_allowed_number_of_files_validation_small_chat_window_message'] = "nahrávat je možné maximálně ".$config['maximum_allowed_number_of_attachments_on_small_chat_window']." soubory";

$config['chat_attachment_display_error_timeout'] = 3000; // provide value in millisecond

$config['chat_room_starting_position_to_search'] = 2; // this variable is used to look into db when user typed this many character into textbox

$config['chat_room_contacts_listing_limit'] = 15; // This is limit which used to display contacts on chat room page

$config['chat_messages_grouping_time_limit'] = 1; // This variable value should specified in minutes from [0-59] to group sent messages in chatroom / project details / small chat window

?>