<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

################ Meta Config Variables for portfolio detail page ###########
// character limit for meta tag and meta description on portfolio details pages
$config['portfolio_standalone_page_title_meta_tag_character_limit'] = 50; // meta title limit for portfolio detail page
$config['portfolio_standalone_page_description_meta_description_character_limit'] = 140; // meta description limit for portfolio detail page

//Portfolio title
$config['user_portfolio_section_portfolio_title_characters_minimum_length_characters_limit'] = 2;
$config['user_portfolio_section_portfolio_title_characters_maximum_length_characters_limit'] = 50;
$config['user_portfolio_section_portfolio_title_characters_minimum_length_validation_message'] = 'Minimum '.$config['user_portfolio_section_portfolio_title_characters_minimum_length_characters_limit'].' characters required';

//Tags
$config['user_portfolio_section_tags_characters_minimum_length_characters_limit'] = 2;
$config['user_portfolio_section_tags_characters_maximum_length_characters_limit'] = 50;
$config['user_portfolio_section_tags_characters_minimum_length_validation_message'] = 'Minimum '.$config['user_portfolio_section_tags_characters_minimum_length_characters_limit'].' characters required';

$config['user_portfolio_section_tags_maximum'] = 3;


//Description
$config['user_portfolio_section_description_characters_minimum_length_characters_limit'] = 2;
$config['user_portfolio_section_description_characters_maximum_length_characters_limit'] = 5000;
$config['user_portfolio_section_portfolio_description_characters_minimum_length_validation_message'] = 'Minimum '.$config['user_portfolio_section_description_characters_minimum_length_characters_limit'].' characters required';


$config['user_portfolio_section_description_minimum_length_words_limit'] = 5;
$config['user_portfolio_section_portfolio_description_characters_words_minimum_length_validation_message'] = 'portfolio description must be at least '.$config['user_portfolio_section_description_characters_minimum_length_characters_limit'].' characters and '.$config['user_portfolio_section_description_minimum_length_words_limit'].' words';


$config['user_portfolio_section_listing_limit'] = 10;
$config['user_portfolio_section_number_of_pagination_links'] = 2;

// variable to manage description character limit on user portfolio listing section
$config['user_portfolio_section_description_character_limit_mobile'] = 250; // portfolio description character limit for mobile device
$config['user_portfolio_section_description_character_limit_tablet'] = 250; // portfolio description character limit for tablet device
$config['user_portfolio_section_description_character_limit_desktop'] = 250; // portfolio description character limit for dekstop device

// config used to control the character limit of portfolio description portfolio standalone page
$config['user_standalone_portfolio_page_description_display_minimum_length_character_limit_desktop'] = 250; // limited charecter display in standalone portfolio page for dekstop
$config['user_standalone_portfolio_page_description_display_minimum_length_character_limit_tablet'] = 250; // limited charecter display in standalone portfolio page for tablet
$config['user_standalone_portfolio_page_description_display_minimum_length_character_limit_mobile'] = 250; // limited charecter display in standalone portfolio page for mobile

$config['user_portfolio_page_section_free_membership_subscriber_number_portfolio_slots_allowed'] = 10;
$config['user_portfolio_page_section_gold_membership_subscriber_number_portfolio_slots_allowed'] = 25;

$config['user_portfolio_page_free_membership_subscriber_number_tags_allowed_per_portfolio_slot'] = 3;
$config['user_portfolio_page_gold_membership_subscriber_number_tags_allowed_per_portfolio_slot'] = 5;

$config['user_portfolio_page_free_membership_subscriber_number_images_allowed_per_portfolio_slot'] = 5;
$config['user_portfolio_page_gold_membership_subscriber_number_images_allowed_per_portfolio_slot'] = 25;

$config['user_portfolio_section_free_membership_subscriber_number_images_allowed_per_portfolio_slots_exceeded_error_message'] = "You can upload only ".$config['user_portfolio_page_free_membership_subscriber_number_images_allowed_per_portfolio_slot']. " files !";
$config['user_portfolio_section_gold_membership_subscriber_number_images_allowed_per_portfolio_slots_exceeded_error_message'] = "You can upload only ".$config['user_portfolio_page_gold_membership_subscriber_number_images_allowed_per_portfolio_slot']. " files !";


$config['portfolio_id_min_number'] = 100;// minimun number from where portfolio id will start
$config['portfolio_id_max_number'] = 9999999999;// maximum number where portfolio id will be end
// so the portfolio is will be exists on above number

$config['user_portfolio_section_portfolio_image_maximum_size_limit'] = 2; //(Size in MB) size of image allowed on portfolio create popup page

$config['user_portfolio_section_portfolio_image_exceeded_maximum_allowed_size_error_message'] = "The file you are trying to upload has {file_size_mb} MB in size and exceeds current max allowed file size of ".$config['user_portfolio_section_portfolio_image_maximum_size_limit']." MB";

$config['user_portfolio_standalone_page_cover_picture_upload_max_size_allocation'] = 3; //IN mb

$config['user_portfolio_standalone_page_cover_picture_upload_max_size_validation_message'] = "Max allowed size is ".$config['user_portfolio_standalone_page_cover_picture_upload_max_size_allocation']." MB";

$config['email_share_user_portfolio_standalone_page_description_character_limit'] = 500;

?>