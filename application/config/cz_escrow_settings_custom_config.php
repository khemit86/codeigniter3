<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//release escrow popup variables
$config['escrow_description_minimum_length_character_limit_escrow_form'] = 2; //minimum limit of character of escrow description on escrow form

$config['escrow_description_maximum_length_character_limit_escrow_form'] = 65; //maximum limit of character of escrow description on escrow form

$config['escrow_amount_length_character_limit_escrow_form'] = 15; //maximum limit of character of escrow amount on escrow form

$config['escrow_amount_length_character_limit_before_decimal_point_escrow_form'] = 12; //maximum limit of character before decimal


$config['project_details_page_hourly_rate_based_project_create_escrow_request_form_hourly_rate_length_digits_limit'] = 5; //maximum limit of character of escrow amount on escrow form

$config['project_details_page_hourly_rate_based_project_create_escrow_request_form_number_of_hours_length_digits_limit'] = 5; //maximum limit of character of escrow amount on escrow form

$config['project_details_page_escrow_description_characters_min_length_validation_project_escrow_form_message'] = 'popis transakce musí mít minimálně '.$config['escrow_description_minimum_length_character_limit_escrow_form'].' znaků';

$config['project_details_page_escrow_description_characters_min_length_validation_fulltime_project_escrow_form_message'] = 'popis transakce musí mít minimálně '.$config['escrow_description_minimum_length_character_limit_escrow_form'].' znaků';

############# config variables for paging regarding requested/eschrow/paid/rejected miletone###

$config['project_detail_page_requested_escrow_listing_limit'] = 5;
$config['project_detail_page_rejected_requested_escrow_listing_limit'] = 5;
$config['project_detail_page_active_escrow_listing_limit'] = 5;
$config['project_detail_page_cancelled_escrow_listing_limit'] = 5;
$config['project_detail_page_paid_escrow_listing_limit'] = 5;

$config['project_detail_page_escrow_number_of_pagination_links'] = 1;

?>