<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['project_details_page_bid_description_minimum_length_words_limit'] = 5;

$config['project_details_page_bid_description_minimum_length_character_limit'] = 10; //minimum limit of character of project bid description on project bid form

$config['project_details_page_bid_description_maximum_length_character_limit'] = 2000; //maximum limit of character of project bid description on bid form

// validation message for bid form - both fixed budegt and hourly rate prjects
// For fulltime project
$config['project_details_page_bid_description_characters_words_minimum_length_validation_project_bid_form_message'] = 'popis nabídky musí mít alespoň '.$config['project_details_page_bid_description_minimum_length_character_limit'].' znaků a '.$config['project_details_page_bid_description_minimum_length_words_limit'].' slov';

$config['project_details_page_bid_description_characters_min_length_validation_project_bid_form_message'] = 'popis nabídky musí mít alespoň '.$config['project_details_page_bid_description_minimum_length_character_limit'].' znaků';

$config['project_details_page_bid_description_characters_maximum_length_validation_project_bid_form_message'] = 'popis nabídky musí mít alespoň '.$config['project_details_page_bid_description_maximum_length_character_limit'].' znaků';

// For fulltime project
$config['project_details_page_bid_description_characters_min_length_validation_fulltime_project_bid_form_message'] = 'popis žádosti nemůže být kradší než '.$config['project_details_page_bid_description_minimum_length_character_limit'].' slov';

$config['project_details_page_bid_description_characters_words_minimum_length_validation_fulltime_project_bid_form_message'] = 'popis žádosti musí mít alespoň '.$config['project_details_page_bid_description_minimum_length_character_limit'].' znaků a '.$config['project_details_page_bid_description_minimum_length_words_limit'].' slov';

$config['project_details_page_bid_description_characters_maximum_length_validation_fulltime_project_bid_form_message'] = 'popis žádosti musí mít alespoň '.$config['project_details_page_bid_description_maximum_length_character_limit'].' znaků';


// variable to manage bid description character limit on project_details_page_bidder_listing section
$config['project_details_page_bidder_listing_bid_description_character_limit_mobile'] = 250; // bid description character limit for mobile device
$config['project_details_page_bidder_listing_bid_description_character_limit_tablet'] = 250; // bid description character limit for tablet device
$config['project_details_page_bidder_listing_bid_description_character_limit_desktop'] = 250; // bid description character limit for dekstop device

$config['project_details_page_bid_attachment_maximum_size_limit'] = 3; //(Size in MB) size of attachment allowed on post project page

$config['project_details_page_bid_attachment_maximum_size_validation_project_bid_form_message'] = "Soubor, který chcete nahrát má velikost {file_size_mb} MB a tím překračuje maximální povolenou velikost souboru ".$config['project_details_page_bid_attachment_maximum_size_limit']." MB";


$config['gold_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid'] = 3; //number of file allowed to user to allowed(checking from database) for bid

$config['free_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid'] = 3; //snumber of file allowed to user to allowed(checking from database) for bid

//
$config['free_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid_validation_message'] = "Přiložit je možné maximálně ".$config['free_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid']." soubory.";

$config['gold_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid_validation_message'] = "
Přiložit je možné maximálně ".$config['gold_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid']." soubory.";


$config['project_details_page_bid_attachment_name_character_length_limit'] = 10; 



//bid award expiration time
$config['award_expiration_time'] = "72:00:00"; //time when award expiration will be expired - 3 days


//config for mark as complete section on project detail page
$config['mark_project_complete_request_expiration_time'] = '72:00:00'; //config for expiration/availability of project mark complete request - 3 days

$config['po_send_mark_project_complete_request_time_left_till_next_resent'] = '168:00:00';//config for create mark as complete request by po - 7 days

$config['project_details_page_bid_attachment_allowed_file_types'] = 'image/*,.pdf,.xls, .xlsx, .doc, .docx, .txt';

?>