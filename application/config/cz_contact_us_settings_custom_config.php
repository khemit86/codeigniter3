<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['contact_us_page_description_minimum_length_character_limit_reason_description'] = 10; //minimum limit of character of 

$config['contact_us_page_description_minimum_length_words_limit_reason_description'] = 5; //minimum limit of words of project 

$config['contact_us_page_description_maximum_length_character_limit_reason_description'] = 2000; //maximum limit of character of 

$config['contact_us_page_description_characters_min_length_validation_message'] = 'popis musí mít alespoň '.$config['contact_us_page_description_minimum_length_character_limit_reason_description'].' znaků';

$config['contact_us_page_description_characters_words_min_length_validation_message'] = 'popis musí mít alespoň '.$config['contact_us_page_description_minimum_length_character_limit_reason_description'].' znaků a '.$config['contact_us_page_description_minimum_length_words_limit_reason_description'].' slov';

$config['contact_us_page_description_characters_max_length_validation_message'] = 'popis nemůže být delší než '.$config['contact_us_page_description_maximum_length_character_limit_reason_description'].' znaků';

?>