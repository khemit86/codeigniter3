<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['pa_user_certifications_section_certification_name_characters_minimum_length_characters_limit'] = 2;
$config['ca_user_certifications_section_certification_name_characters_minimum_length_characters_limit'] = 2;

$config['pa_user_certifications_section_certification_name_characters_maximum_length_characters_limit'] = 50;
$config['ca_user_certifications_section_certification_name_characters_maximum_length_characters_limit'] = 50;

$config['pa_user_certifications_section_certification_name_characters_minimum_length_validation_message'] = 'povinné je minimálně '.$config['pa_user_certifications_section_certification_name_characters_minimum_length_characters_limit'].' znaků';

$config['ca_user_certifications_section_certification_name_characters_minimum_length_validation_message'] = 'povinné je minimálně '.$config['ca_user_certifications_section_certification_name_characters_minimum_length_characters_limit'].' znaků';

$config['pa_user_certifications_section_date_acquired_start_from'] = 1990;
$config['ca_user_certifications_section_date_acquired_start_from'] = 1990;

$config['pa_user_certifications_section_date_acquired_end_to'] = 2020;
$config['ca_user_certifications_section_date_acquired_end_to'] = 2020;


$config['user_certifications_section_attachment_maximum_size_limit'] = 3; //(Size in MB) size of attachment allowed on for chat to be upload

$config['user_certifications_section_attachment_maximum_size_validation_message'] = "soubor, který chcete nahrát má velikost {file_size_mb} MB, a tím překračuje maximální povolenou velikost souboru ".$config['user_certifications_section_attachment_maximum_size_limit']." MB";

$config['user_certifications_section_maximum_allowed_number_of_attachments'] = 2; //number of file allowed to user to upload on certificate page

$config['user_certifications_section_allowed_number_of_files_validation_message'] = "nahrát lze maximálně ".$config['user_certifications_section_maximum_allowed_number_of_attachments']." soubory";


$config['user_certifications_section_attachment_name_character_length_limit'] = 10; // This variable will use when user select file from upload file button or drag and drop file

$config['user_certifications_section_listing_limit'] = 10;
$config['user_certifications_section_number_of_pagination_links'] = 1;

?>