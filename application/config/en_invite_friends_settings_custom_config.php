<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
###this defines the max number of resends (to same email address) user can attempt
$config['resends_available'] = 3;

###this defines the time (hh:mm:ss) format for next resent
$config['time_left_till_next_resent'] = '120:00:00'; 

$config['facebook_share_image_height'] = 200;
$config['facebook_share_image_width'] = 200;

$config['linkedin_share_image_preview_status'] = 1; // 1 for display and 0 for hide

// This variable is used to manange how many invite friends request user can send at one time
$config['send_invite_friends_request_limit'] = 5;

$config['pending_invitations_listing_limit'] = 10;
$config['accepted_invitations_listing_limit'] = 10;
$config['revoked_invitations_listing_limit'] = 10;
$config['manage_email_invitations_number_of_pagination_links'] = 2;

$config['minimum_allowed_referral_earnings_withdraw_funds_amount'] = 150;

$config['minimum_allowed_referral_earnings_withdraw_funds_error_message'] = 'You can not withdraw amount less then '.$config['minimum_allowed_referral_earnings_withdraw_funds_amount'].' '.CURRENCY;

$config['referral_earnings_withdraw_transaction_listing_limit'] = 5;
$config['referral_earnings_withdraw_transaction_history_label_txt'] = "Latest withdrawal funds transactions history - ".$config['referral_earnings_withdraw_transaction_listing_limit']."";


$config['send_invite_friends_request_limit_exceed_error_msg'] = "You can send upto ".$config['send_invite_friends_request_limit']." invite friends request at a time.";

?>