<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


// This variable is use to set sent get in contact request avaialability, so once this time expired then user can again able to send request to same person
$config['get_in_contact_request_availability'] = '720:00:00'; // this is once per month
//limit to show how many pending contact request display in header notifification
$config['get_in_contact_requests_notification_limit'] = 5;


// variable to manage contacts management pending contact request listing limit - used in generate_pagination_links method under chat controller
$config['get_in_contact_pending_requests_listing_limit_per_page'] = 10;
$config['get_in_contact_pending_requests_number_of_pagination_links'] = 1;

// variable to manage contacts management rejected contact request listing limit - used in generate_pagination_links method under chat controller
$config['get_in_contact_rejected_requests_listing_limit_per_page'] = 10;
$config['get_in_contact_rejected_requests_number_of_pagination_links'] = 1;

// variable to manage contacts management blocked contact request listing limit - used in generate_pagination_links method under chat controller
$config['get_in_contact_blocked_requests_listing_limit_per_page'] = 10;
$config['get_in_contact_blocked_requests_number_of_pagination_links'] = 1;

?>