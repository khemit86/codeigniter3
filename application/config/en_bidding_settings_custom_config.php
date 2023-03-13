<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// when some unauthorized user(PO/unauthrizedSP) trying post bid or update bid
//$config['project_details_page_place_bid_validation_project_bid_form_message'] = "An error occurred.";

$config['project_details_page_bid_description_minimum_length_words_limit'] = 5;

$config['project_details_page_bid_description_minimum_length_character_limit'] = 10; //minimum limit of character of project bid description on project bid form

$config['project_details_page_bid_description_maximum_length_character_limit'] = 5000; //maximum limit of character of project bid description on bid form

// Validation message for post/update bid form 
$config['project_details_page_bid_description_characters_min_length_validation_project_bid_form_message'] = 'your bid description must be at least '.$config['project_details_page_bid_description_minimum_length_character_limit'].' characters';

$config['project_details_page_bid_description_characters_words_minimum_length_validation_project_bid_form_message'] = 'bid description must be at least '.$config['project_details_page_bid_description_minimum_length_character_limit'].' characters and '.$config['project_details_page_bid_description_minimum_length_words_limit'].' words';

$config['project_details_page_bid_description_characters_maximum_length_validation_project_bid_form_message'] = 'your bid description can not be more then '.$config['project_details_page_bid_description_maximum_length_character_limit'].' characters';

// for fulltime project
$config['project_details_page_bid_description_characters_min_length_validation_fulltime_project_bid_form_message'] = 'your fulltime application description must be at least '.$config['project_details_page_bid_description_minimum_length_character_limit'].' characters';

$config['project_details_page_bid_description_characters_words_minimum_length_validation_fulltime_project_bid_form_message'] = 'fulltime bid description must be at least '.$config['project_details_page_bid_description_minimum_length_character_limit'].' characters and '.$config['project_details_page_bid_description_minimum_length_words_limit'].' words';

$config['project_details_page_bid_description_characters_maximum_length_validation_fulltime_project_bid_form_message'] = 'your fulltime application description can not be more then '.$config['project_details_page_bid_description_maximum_length_character_limit'].' characters';



// variable to manage bid description character limit on project_details_page_bidder_listing section 
$config['project_details_page_bidder_listing_bid_description_character_limit_mobile'] = 250; // bid description character limit for mobile device

$config['project_details_page_bidder_listing_bid_description_character_limit_tablet'] = 375; // bid description character limit for tablet device

$config['project_details_page_bidder_listing_bid_description_character_limit_desktop'] = 500; // bid description character limit for dekstop device

$config['project_details_page_bid_attachment_maximum_size_limit'] = 2; //(Size in MB) size of attachment allowed on post project page

$config['project_details_page_bid_attachment_maximum_size_validation_project_bid_form_message'] = "the file you are trying to upload has {file_size_mb} MB in size and exceeds current max allowed file size of ".$config['project_details_page_bid_attachment_maximum_size_limit']." MB!";


$config['gold_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid'] = 5; // number of file allowed to user to allowed(checking from database) for bid

$config['free_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid'] = 5; // number of file allowed to user to allowed(checking from database) for bid

$config['gold_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid_validation_message'] = "you can upload only ".$config['gold_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid']. " files!";

$config['free_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid_validation_message'] = "you can upload only ".$config['free_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid']. " files!";


$config['project_details_page_bid_attachment_name_character_length_limit'] = 10; 

//bid award expiration time
$config['award_expiration_time'] = "01:30:00"; // time when award expiration will be expired

// config for mark as complete section on project detail page
$config['mark_project_complete_request_expiration_time'] = '00:20:00'; // config for expiration/availability of project mark complete request

$config['po_send_mark_project_complete_request_time_left_till_next_resent'] = '00:25:00'; 
///config for create mark as complete request by po


$config['project_details_page_bid_attachment_allowed_file_types'] = 'image/*, .pdf, .xls, .xlsx, .doc, .docx, .txt'; 

?>