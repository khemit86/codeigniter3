<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


$config['sandbox'] = true;
$config['paypal_business_email'] = 'catalin.basturescu-facilitator@gmail.com'; // this email id will receive funds
$config['paypal_client_id'] = 'AWF9x5q-x1ov6WSI1jmb_6DldBr_OJvqFzyHK1tA5nh5rO7ZePD5opFf42TKRR9bb1WFWbYHOOn34qbv';
$config['paypal_secret_key'] = 'EG4GkKDmdRU-YVdSKIvcK-KeTUDF8YaQ7KhKyrE8UcElNd7F8sLp_jp2rp3MjBBOSRKJwW7XyB9d13DL';

$config['deposit_funds_via_paypal_processing_fees_percentage_charge_first_amounts_range'] = 10; // this variable used in relation with first min / max amount deposit via paypal
$config['deposit_funds_via_paypal_first_amounts_range_min_value'] = 150;
$config['deposit_funds_via_paypal_first_amounts_range_max_value'] = 600;

$config['deposit_funds_via_paypal_processing_fees_percentage_charge_second_amounts_range'] = 5; // this variable used in relation with second min / max amount deposit via paypal
$config['deposit_funds_via_paypal_second_amounts_range_min_value'] = 601;
$config['deposit_funds_via_paypal_second_amounts_range_max_value'] = 50000;

$config['deposit_funds_via_paypal_min_amount_error_msg'] = 'minimum amount to be deposited via PayPal is <span>'.str_replace(".00","",number_format($config['deposit_funds_via_paypal_first_amounts_range_min_value'],2,'.',' ')).' '.CURRENCY.'</span>';
$config['deposit_funds_via_paypal_max_amount_error_msg'] = 'maximum amount to be deposited via PayPal is <span>'.str_replace(".00","", number_format($config['deposit_funds_via_paypal_second_amounts_range_max_value'],2,'.',' ')).' '.CURRENCY.'</span>';

$config['deposit_funds_via_paypal_success_message_timeout'] = 5000;//in seconds
$config['deposit_funds_via_paypal_amount_length_character_limit'] = 8;

$config['deposit_funds_via_paypal_transaction_listing_limit'] = 5;

$config['withdraw_funds_via_paypal_min_amount'] = 703;
$config['withdraw_funds_via_paypal_max_amount'] = 9999999;
$config['withdraw_funds_via_paypal_amount_length_character_limit'] = 12;

$config['withdraw_funds_via_paypal_min_max_amount_error_msg'] = "You can withdraw minimum <span>".str_replace(".00", "", number_format($config['withdraw_funds_via_paypal_min_amount'],2,'.',' '))." ".CURRENCY."</span> and maximum <span>".str_replace(".00", "", number_format($config['withdraw_funds_via_paypal_max_amount'],2,'.',' '))." ".CURRENCY.",</span> per each transaction.";


$config['withdraw_funds_via_paypal_max_amount_error_msg'] = "You can withdraw maximum <span>".str_replace(".00", "", number_format($config['withdraw_funds_via_paypal_max_amount'],2,'.',' '))." ".CURRENCY."</span>";

$config['withdraw_funds_via_paypal_transaction_listing_limit'] = 5;

$config['withdraw_funds_via_paypal_transaction_history_label_txt'] = "Latest ".$config['withdraw_funds_via_paypal_transaction_listing_limit']." withdraw funds Transactions History";

$config['withdraw_funds_processing_fees_percentage_charge'] = 2; // This is percetage charge which paypal take when sender send amount to outside us account and this will be applicable upto 20000 KC
$config['withdraw_funds_processing_fees_flat_charge'] = 400; // This is flat charge will be apply on transaction when sender send amount to outside us account and this will be applicable after 20000 KC

$config['deposit_funds_direct_bank_transfer_transaction_listing_limit'] = 5;

$config['deposit_funds_direct_bank_transfer_amount_length_character_limit'] = 25;

$config['deposit_funds_via_payment_processor_transaction_listing_limit'] = 5;
$config['deposit_funds_via_payment_processor_amount_length_character_limit'] = 12;

$config['deposit_funds_via_payment_processor_min_deposit_amount'] = 100;
$config['deposit_funds_via_payment_processor_min_amount_error_msg'] = 'minimum amount to be deposited via card is <span>'.str_replace(".00","",number_format($config['deposit_funds_via_payment_processor_min_deposit_amount'],2,'.',' ')).' '.CURRENCY.'</span>';

$config['deposit_funds_via_payment_processor_max_deposit_amount'] = 9999999;
$config['deposit_funds_via_payment_processor_max_deposit_amount_error_msg'] = 'maximum amount to be deposited via payment processor is <span>'.str_replace(".00","", number_format($config['deposit_funds_via_payment_processor_max_deposit_amount'],2,'.',' ')).' '.CURRENCY.'</span>';

$config['deposit_funds_via_payment_processor_is_use_test_url'] = true;// Change value of this variable to false when you need to make it live
$config['deposit_funds_thepay_merchant_id'] = 1; 
$config['deposit_funds_thepay_account_id'] = 3; 
$config['deposit_funds_thepay_password'] = 'my$up3rsecr3tp4$$word'; 

$config['deposit_funds_via_payment_processor_response_message_timeout'] = 3000;

$config['deposit_funds_via_payment_processor_processing_fees_percentage_charge_for_payment_getway'] = 1.99; // this variable is used to calculate how much amount payment getway charged in particular transaction
$config['deposit_funds_via_payment_processor_fixed_charge_for_payment_getway'] = 2; // this variable value will be added after percentage value calculated for payment getway charges

$config['withdraw_funds_direct_bank_transfer_amount_length_character_limit'] = 12;

$config['transactions_history_listing_limit'] = 25;

$config['transactions_history_loader_progressbar_display_time'] = 1000;

$config['invoices_tracking_listing_limit'] = 1;

$config['invoices_tracking_number_of_pagination_links'] = 1;


// Company details which will display in invoice
$config['invoice_format_company_name'] = 'Catalin Basturescu';
$config['invoice_format_company_address_line_1'] = 'namesti SNP 1139';
$config['invoice_format_company_address_line_2'] = '61300 Brno';
$config['invoice_format_company_country_name'] = 'Czech Republic';
$config['invoice_format_company_identification_number_lbl'] = 'Identification Number';
$config['invoice_format_company_identification_number_value'] = '123456789';
$config['invoice_format_company_vat_registration_number_lbl'] = 'VAT Reg. No.';
$config['invoice_format_company_vat_registration_number_value'] = '123456789';
$config['invoice_format_company_telephone_lbl'] = 'Telephone';
$config['invoice_format_company_telephone_value'] = '957954146512';
$config['invoice_format_company_email_lbl'] = 'Email';
$config['invoice_format_company_email_value'] = 'xxxx@xxxx';

/**
 * This variable is used to set limit for generated invoice amount and if it exceed then specify limit then user has to contact supports
*/
$config['invoices_tracking_invoice_value_more_than_minimum_allowed_amount'] = 9999;

$config['company_invoicing_details_company_name_maximum_length_character_limit'] = 50;
$config['company_invoicing_details_company_address_line_1_maximum_length_character_limit'] = 50;
$config['company_invoicing_details_company_address_line_2_maximum_length_character_limit'] = 50;
$config['company_invoicing_details_company_registration_number_maximum_length_character_limit'] = 10;
$config['company_invoicing_details_company_vat_number_maximum_length_character_limit'] = 10;

?>