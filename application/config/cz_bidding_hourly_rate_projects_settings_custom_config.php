<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['project_details_page_project_delivery_hours_input_field_length_bid_form'] = 6; // variable used in input field(number of hours) at bid/edit bid forms of hourly project

$config['project_details_page_bidded_hourly_rate_amount_input_field_length_bid_form'] = 6; // variable used in input fields(hourly rate) at bid/edit bid forms of hourly project

$config['project_details_page_min_hourly_rate_value'] = 60; 
// This variable will used when po posted project with not specifed budget 
//-sp try to place bid on it
//-sp try to update bid on it
//-po try to create escrow
//-sp try to create requested escrow

?>