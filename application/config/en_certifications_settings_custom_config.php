<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//Certification name
$config['pa_user_certifications_section_certification_name_characters_minimum_length_characters_limit'] = 3;
$config['ca_user_certifications_section_certification_name_characters_minimum_length_characters_limit'] = 3;

$config['pa_user_certifications_section_certification_name_characters_maximum_length_characters_limit'] = 50;
$config['ca_user_certifications_section_certification_name_characters_maximum_length_characters_limit'] = 50;

$config['pa_user_certifications_section_certification_name_characters_minimum_length_validation_message'] = 'paEN-povinné je minimálně '.$config['pa_user_certifications_section_certification_name_characters_minimum_length_characters_limit'].' znaků';

$config['ca_user_certifications_section_certification_name_characters_minimum_length_validation_message'] = 'caEN-povinné je minimálně '.$config['ca_user_certifications_section_certification_name_characters_minimum_length_characters_limit'].' znaků';



$config['pa_user_certifications_section_date_acquired_start_from'] = 1960;
$config['ca_user_certifications_section_date_acquired_start_from'] = 1960;

$config['pa_user_certifications_section_date_acquired_end_to'] = 2020;
$config['ca_user_certifications_section_date_acquired_end_to'] = 2020;


$config['user_certifications_section_attachment_maximum_size_limit'] = 3; //(Size in MB) size of attachment allowed on for chat to be upload
$config['user_certifications_section_attachment_maximum_size_validation_message'] = "The file you are trying to upload has {file_size_mb} MB in size and exceeds current max allowed file size of ".$config['user_certifications_section_attachment_maximum_size_limit']." MB!";

$config['user_certifications_section_maximum_allowed_number_of_attachments'] = 2; // number of file allowed to user to upload on certificate page

$config['user_certifications_section_allowed_number_of_files_validation_message'] = "You can upload only ".$config['user_certifications_section_maximum_allowed_number_of_attachments']. " files !";

$config['user_certifications_section_attachment_name_character_length_limit'] = 10; // This variable will use when user select file from upload file button or drag and drop file

$config['user_certifications_section_listing_limit'] = 10;

$config['user_certifications_section_number_of_pagination_links'] = 2;

?>