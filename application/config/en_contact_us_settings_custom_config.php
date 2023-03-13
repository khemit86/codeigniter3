<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//close account page functionalty validation messages and validation limits
$config['contact_us_page_description_minimum_length_character_limit_reason_description'] = 10; //minimum limit of character of 

$config['contact_us_page_description_minimum_length_words_limit_reason_description'] = 5; //minimum limit of words of project 

$config['contact_us_page_description_maximum_length_character_limit_reason_description'] = 5000; //maximum limit of character of 

$config['contact_us_page_description_characters_min_length_validation_message'] = 'description must be at least '.$config['contact_us_page_description_minimum_length_character_limit_reason_description'].' characters';

$config['contact_us_page_description_characters_words_min_length_validation_message'] = 'description be at least '.$config['contact_us_page_description_minimum_length_character_limit_reason_description'].' characters and '.$config['contact_us_page_description_minimum_length_words_limit_reason_description'].' words';

$config['contact_us_page_description_characters_max_length_validation_message'] = 'description should not be greater the '.$config['contact_us_page_description_maximum_length_character_limit_reason_description'].' characters';

?>