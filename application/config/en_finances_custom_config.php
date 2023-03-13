<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// Left Navigation Menu name
$config['pa_user_left_nav_finance'] = 'Finance';
$config['ca_user_left_nav_finance'] = 'Finance Comp';
$config['ca_app_user_left_nav_finance'] = 'Finance (app)';

// Left Navigation Finance Menu sub-menu name --start
$config['finance_left_nav_invoicing_details'] = 'Invoicing Details';
$config['finance_left_nav_invoices'] = 'Invoices ';
$config['finance_left_nav_transactions_history'] = 'Transactions History';
$config['finance_left_nav_deposit_funds'] = 'Deposit Funds';
$config['finance_left_nav_withdraw_funds'] = 'Withdraw Funds';
// end

$config['deposit_funds_via_paypal_processing_fees_info'] = 'Transaction fees are charged as 10% for amounts of up to 600 Kč, and 5% for amounts more than 601 Kč.'; // This variable used to display processing fees related info on deposit funds via paypal page

$config['deposit_funds_via_paypal_total_amount_tooltip_info'] = 'deposit_funds_via_paypal_total_amount_tooltip_info'; // This variable used to display deposit funds via paypal total amount tooltip info

$config['deposit_funds_via_payment_card_total_amount_tooltip_info'] = 'deposit_funds_via_payment_card_total_amount_tooltip_info'; // This variable used to display deposit funds via payment card total amount tooltip info

$config['deposit_funds_via_paypal_success_message'] = 'Transaction was successfull <span>{transaction_amount} Kč.</span> Částka was added to your account balance.'; // This message display to user when he successfully deposited amount on paypal and redirect to site

$config['deposit_funds_via_paypal_display_activity_log_message'] = 'you deposited <span>{transaction_amount} '.CURRENCY.'</span> via paypal.'; // This activity log message display to user when payment successfully done on paypal

// Deposit funds via paypal tab labels -- start
$config['deposit_funds_via_paypal_deposit_amount_label_txt'] = "Deposit Amt";

$config['deposit_funds_via_paypal_processing_fee_label_txt'] = "Processing Fee";

$config['deposit_funds_via_paypal_total_label_txt'] = "Total";

$config['deposit_funds_via_paypal_confirm_payment_btn_txt'] = 'Confirm and Transfer';

$config['deposit_funds_via_paypal_deposited_amount_label_txt'] = 'Deposited Amount';

$config['deposit_funds_via_paypal_transaction_date_label_txt'] = 'Transaction Date';

$config['deposit_funds_via_paypal_paypal_account_label_txt'] = 'Paypal Account';

$config['deposit_funds_via_paypal_transaction_id_label_txt'] = 'Transaction ID';

$config['deposit_funds_via_paypal_transaction_charge_label_txt'] = 'Transaction Charge';

$config['deposit_funds_via_paypal_transaction_history_label_txt'] = "Latest deposit funds transactions";
// end

// Deposit funds
//page heading
$config['finance_headline_title_deposit_funds'] = 'Deposit Funds';

//Meta Tag
$config['deposit_funds_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Deposit Funds TO YOUR ACCOUNT - title_meta_tag';
//Description Meta Tag
$config['deposit_funds_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Deposit Funds TO YOUR ACCOUNT - description_meta_tag';

//url
$config['finance_deposit_funds_page_url'] = 'deposit-funds';

//checkbox text
$config['deposit_funds_page_checkbox_deposit_via_paypal'] = 'deposit via PayPal';
$config['deposit_funds_page_checkbox_deposit_via_payment_cards'] = 'deposit via Payment Card - Online Transfers';
$config['deposit_funds_page_checkbox_deposit_via_bank_transfer'] = 'deposit via Bank Transfer';


//withdraw funds 
//page heading
$config['finance_headline_title_withdraw_funds'] = 'Withdraw Funds';

//Meta Tag
$config['withdraw_funds_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Withdraw Funds from your account - title_meta_tag';
//Description Meta Tag
$config['withdraw_funds_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Withdraw Funds from your account - description_meta_tag';

//url
$config['finance_withdraw_funds_page_url'] = 'withdraw-funds';

//checkbox text
$config['finance_page_checkbox_withdraw_via_paypal'] = 'withdraw via Paypal';
$config['finance_page_checkbox_withdraw_via_bank_transfer'] = 'withdraw via Bank Transfer';

// This error message display to user when he try to withdraw amount more then available
$config['withdraw_funds_via_paypal_insufficent_balance_error_message'] = "You don't have sufficent account balance to withdraw.";

// Withdraw funds via paypal labels and error message --start
$config['withdraw_funds_via_paypal_email_account_label_txt'] = 'Paypal Email Account';
$config['withdraw_funds_amount_to_withdraw_via_paypal_label_txt'] = 'Amount required to withdraw';
$config['withdraw_funds_amount_receive_in_paypal_label_txt'] = 'Amount to receive in PayPal account';
$config['withdraw_funds_via_paypal_btn_txt'] = 'Withdraw funds';
$config['confirm_withdraw_funds_via_paypal_withdraw_amount_btn_txt'] = 'Confirm and Withdraw<span>{withdraw_amount}' .' '.CURRENCY.'</span>';
$config['withdraw_funds_via_paypal_invalid_email_error_message'] = 'Please enter valid email id.';
// end

// This activity log message display to user when he request for withdraw funds via paypal
$config['withdraw_funds_request_via_paypal_display_activity_log_message'] = 'You made withdraw fund request for <span>{transaction_amount} '.CURRENCY.'</span> via paypal.';
// This activity log message display to user when withdraw funds request rejected by admin
$config['withdraw_funds_request_rejected_by_admin_display_activity_log_message'] = 'Your withdraw fund request for <span>{transaction_amount} '.CURRENCY.'</span> was rejected by admin.';

// Withdraw funds via paypal labels  --start
$config['withdrawal_request_amount_label_txt'] = 'Withdrawal Requested Amount';
$config['withdrawal_request_submit_date_label_txt'] = 'Withdrawal Request Submit Date';
$config['withdrawal_to_paypal_account_label_txt'] = 'Withdraw to Paypal Account';
$config['withdrawal_request_status_label_txt'] = 'Request Status';
$config['withdraw_amount_label_txt'] = 'Amount';
$config['transaction_date_label_txt'] = 'Transaction Date';
$config['paypal_account_label_txt'] = 'Paypal Account';
$config['transaction_id_label_txt'] = 'Transaction Id';
$config['transaction_status_label_txt'] = 'Transaction status';
$config['transaction_failure_reason_label_txt'] = 'Failure Reason';
$config['withdrawal_request_rejection_date_label_txt'] = 'Withdrawal Request Rejection Date';
$config['withdrawal_request_approval_date_label_txt'] = 'Withdrawal Request Approval Date';
// end

// This variable used to display processing fees info on withdraw funds page
$config['withdraw_funds_processing_fees_info'] = 'calculated as 2% of withdraw amount for upto 20 000 '.CURRENCY.' and flat 400 '.CURRENCY.' if withdrawn amount exceeds more than 20 000 '.CURRENCY.'';

// These variables used to display different statuses of withdraw funds via paypal --start
$config['withdrawal_request_via_paypal_admin_review_status_txt'] = 'Under Admin Review';
$config['withdrawal_request_via_paypal_rejected_by_admin_status_txt'] = 'Rejected By Admin';
$config['withdrawal_request_via_paypal_transaction_success_status_txt'] = 'Success';
$config['withdrawal_request_via_paypal_transaction_failed_status_txt'] = 'Failed';
// end

// This variable used at admin side to store transaction failure reason which will be displayed to user
$config['withdraw_funds_failure_reason'] = [
 'RECEIVER_UNREGISTERED' => "invalid paypal account",
 'CURRENCY_COMPLIANCE' => 'Due to currency compliance regulations, we are not allowed to make this transaction.',
 'GAMER_FAILED_COUNTRY_OF_RESIDENCE_CHECK' => 'Your living country is not allowed to accept this payment.',
 'RECEIVER_ACCOUNT_LOCKED' => 'Your account is inactive or restricted',
 'RECEIVER_COUNTRY_NOT_ALLOWED' => 'Your living country is not allowed to accept this payment.',
 'RECEIVER_STATE_RESTRICTED' => 'Your living state is not allowed to accept this payment.',
 'RECEIVER_UNCONFIRMED' => 'Your email or phone number is unconfirmed.',
 'RECEIVER_YOUTH_ACCOUNT' => 'You have youth account, please provide alternate account',
 'RECEIVING_LIMIT_EXCEEDED' => 'Your receiving amount limit has been exceeds.',
 'RECEIVER_REFUSED' => 'Your account is not accepting payment in specified currency'
];


################################################ Bank Deposit ###############################################################################


$config['deposit_funds_bank_heading_txt'] = 'Heading - xxxxx is denominated in '.CURRENCY.' ONLY. We are accepting deposits in '.CURRENCY.' (Czech Crowns) ONLY. Any deposits in other currency different than '.CURRENCY.' will be subject of currency exchange rates applied by the banks. Your Travai account balance will be credited with the exact amount in '.CURRENCY.' received in our bank account.<br><br>Pomocí níže uvedeného formuláře můžete zaregistrovat jakoukoli bankovní transakci, kterou jste provedli ručně, na Travai bankovní účet. Naše bankovní informace jsou zobrazeny níže.';

$config['deposit_funds_bank_body_txt'] = 'Body - xxxx.cz is denominated in '.CURRENCY.' ONLY. We are accepting deposits in '.CURRENCY.' (Czech Crowns) ONLY. Any deposits in other currency different than '.CURRENCY.' will be subject of currency exchange rates applied by the banks. Your Travai account balance will be credited with the exact amount in '.CURRENCY.' received in our bank account.<br><br>Pomocí níže uvedeného formuláře můžete zaregistrovat jakoukoli bankovní transakci, kterou jste provedli ručně, na Travai bankovní účet. Naše bankovní informace jsou zobrazeny níže.';

// deposit funds bank transfer labels -- start
$config['deposit_funds_bank_information_lbl'] = 'Bank Information';
$config['deposit_funds_bank_account_owner_lbl'] = 'Account owner';
$config['deposit_funds_bank_account_number_lbl'] = 'Account Number';
$config['deposit_funds_bank_name_lbl'] = 'Bank name';
$config['deposit_funds_bank_code_lbl'] = 'Bank code';
$config['deposit_funds_iban_lbl'] = 'IBAN';
$config['deposit_funds_bank_bic_or_swift_code_lbl'] = 'BIC / SWIFT Code';
$config['deposit_funds_bank_country_lbl'] = 'Country';
$config['deposit_funds_deposited_amount_lbl'] = 'Deposited amount';
$config['deposit_funds_transaction_date_lbl'] = 'Transaction Date';
$config['deposit_funds_transaction_id_lbl'] = 'Transaction ID';

$config['deposit_funds_bank_transaction_id_lbl'] = 'Bank Transaction ID';
$config['deposit_funds_bank_transaction_date_lbl'] = 'Bank Transaction Date';

$config['deposit_funds_user_bank_variable_symbol_lbl'] = 'D-Variable Symbol';
// end

$config['deposit_funds_register_transaction_btn'] = 'Register Transaction';

// These variables used to display site bank information where user can deposit funds --start
$config['deposit_funds_bank_account_owner_value'] = 'XXXXXX agentura';
$config['deposit_funds_bank_account_number_value'] = '0123456789';
$config['deposit_funds_bank_name_value'] = 'Česká spořitelna Bank';
$config['deposit_funds_bank_code_value'] = '012345';
$config['deposit_funds_iban_value'] = '543210';
$config['deposit_funds_bank_bic_or_swift_code_value'] = '9875';
$config['deposit_funds_bank_country_value'] = 'Česká';
$config['deposit_funds_user_bank_variable_symbol_prefix_value'] = '77777'; // this variable used to indicate site variable symbol prefix
$config['deposit_funds_user_bank_variable_symbol_suffix_number_of_digits'] = 5; // This variable used to control generated variabil symbol suffix digit
//end



$config['deposit_funds_bank_reference_heading_txt'] = 'Reference/INFO for receiver';

$config['deposit_funds_bank_reference_txt'] = 'Deposit to my account from xxxxx';

$config['deposit_funds_bank_reference_username_lbl'] = 'Username';

$config['deposit_funds_bank_note_lbl'] = 'Note';

$config['deposit_funds_bank_note_txt'] = 'Any transation fees charged by your bank will be deducted from the total transfer amount.Funds will be credited to your balance on the next business day after the funds are received by xxxxx\'s Bank.<br><br>If you have any questions please contact Online Support.';

$config['deposit_funds_bank_country'] = 'Select Country';

// Error messages for deposit funds via bank transfer --start
$config['deposit_funds_deposited_amount_required_error_message'] = 'Deposited amount is required';
$config['deposit_funds_deposited_amount_invalid_error_message'] = 'Please enter valid deposited amount';
$config['deposit_funds_account_owner_required_error_message'] = 'Account owner is required';
$config['deposit_funds_account_number_required_error_message'] = 'Account number is required';
$config['deposit_funds_bank_name_required_error_message'] = 'Bank name is required';
$config['deposit_funds_bank_code_required_error_message'] = 'Bank code is required';
$config['deposit_funds_bank_iban_required_error_message'] = 'IBAN is required';
$config['deposit_funds_bank_bic_swift_code_required_error_message'] = 'Bank code is required';
$config['deposit_funds_country_required_error_message'] = 'Country name is required';
$config['deposit_funds_transaction_date_required_error_message'] = 'Transaction date is required';
$config['deposit_funds_transaction_date_invalid_format_error_message'] = 'Please enter transaction date in following format (dd.mm.yyyy)'; // Deposit funds via direct bank transfer - This messsage will display to user when transaction date he enter shouldn't match (dd.mm.yyyy) format

$config['deposit_funds_transaction_date_invalid_error_message'] = 'Please enter valid transaction date'; // Deposit funds via direct bank transfer - This message will display to user when he enter wrong date like (31.02.2020 / 32.01.2020 etc)

$config['deposit_funds_transaction_id_required_error_message'] = 'transaction id is required';
//end

$config['deposit_funds_direct_bank_transfer_confirmation_message'] = 'Your transaction was successfully submitted to admin review.'; // This is confirmation display to user when bank transfer transaction successfully submitted to admin reivew
$config['deposit_funds_direct_bank_transfer_user_activity_log_message'] = 'Your transaction for amount <span>{deposited_amount} '.CURRENCY.'</span> has been sent to admin review.'; // This is activity log message display to user when bank transfer transaction successfully submitted to admin reivew

$config['deposit_funds_direct_bank_transfer_transaction_confirmed_by_admin_user_activity_log'] = 'Your transaction for amount <span>{deposited_amount} '.CURRENCY.'</span> has been confirmed by admin and fund was added to your account balance.'; // This activity log message display to user when admin confirm requested bank transfer transaction

// These varaibles used to display transaction statuses in deposit funds via bank transfer listing --start
$config['deposit_funds_direct_bank_transfer_pending_confirmation_status_txt'] = 'Pending admin confirmation';
$config['deposit_funds_direct_bank_transfer_confirmed_status_txt'] = 'Confirmed by admin';
$config['deposit_funds_direct_bank_transfer_added_by_admin_status_txt'] = 'Confirmed (Added By Admin)';
// end


############################################################################################################################################

########################################################## Deposit funds via payment processor #######################################################

$config['deposit_funds_via_payment_processor_heading_txt'] = 'EN - PC-Přijímáme vklady pouze v měně '.CURRENCY.'. Jakékoli vklady v jiné měně než '.CURRENCY.' budou směněny podle aktuálního platného kurzu vydaný bankou. Zůstatek vašeho Travai účtu bude navýšen přesnou částkou v '.CURRENCY.' obdrženou na náš bankovní účet.<br><br>Pomocí níže uvedeného formuláře můžete zaregistrovat jakoukoli bankovní transakci, kterou jste provedli ručně, na XXXXX bankovní účet. Jakmile vaší transkaci identifikujeme na našem bankovním účtu, bude převáděná částka ihned připsána na vašem XXXXX účtu.';

// These variables used to display labels and options name on deposit funds via payment processor tab --start
$config['deposit_funds_via_payment_processor_deposit_amount_label_txt'] = 'PC-Deposit Amount';
$config['deposit_funds_via_payment_processor_processing_fee_label_txt'] = 'PC-Processing Fee';
$config['deposit_funds_via_payment_processor_processing_fee_info'] = 'PC-Processing Fee charges <span>{fee_amount} '.CURRENCY.'</span>';
$config['deposit_funds_via_payment_processor_total_label_txt'] = 'PC-Total';
$config['deposit_funds_via_payment_processor_confirm_payment_btn_txt'] = 'PC-Confirm and Deposit';
$config['deposit_funds_via_payment_processor_credit_card_option_name'] = 'Platba kartou';
$config['deposit_funds_via_payment_processor_payment_24_option_name'] = 'Payment24';
$config['deposit_funds_via_payment_processor_mojeplatba_option_name'] = 'MojePlatba';
$config['deposit_funds_via_payment_processor_ekonto_option_name'] = 'eKonto';
$config['deposit_funds_via_payment_processor_mpenize_option_name'] = 'mpenize';
$config['deposit_funds_via_payment_processor_moneta_option_name'] = 'moneta';
$config['deposit_funds_via_payment_processor_csob_option_name'] = 'ČSOB';
$config['deposit_funds_via_payment_processor_fio_bank_option_name'] = 'Fio Banka';
$config['deposit_funds_via_payment_processor_equa_bank_option_name'] = 'Equa Bank';
// end

// This variable used to identify and store deposit funds via payment processor method id
$config['deposit_funds_via_payment_processor_methods_id'] = [
 'platba_kartou' => 31,
 'Platba24' => 23,
 'mojeplatba' => 11,
 'ekonto' => 1,
 'mpenize' => 12,
 'moneta' => 13,
 'csbo' => 19,
 'fio_bank' => 17,
 'equa_bank' => 22
];

// This variable used to indentify and store bank name associated to payment processor method
$config['deposit_funds_via_payment_processor_method_id_associated_bank_name'] = [
 23 => 'Česká Spořitelna',
 11 => 'Komerční banka',
 1 => 'Raiffeisenbank',
 12 => 'mBank',
 13 => 'Moneta Money Bank',
 19 => 'ČSOB',
 17 => 'Fio Banka',
 22 => 'Equa Bank'
];
// This variable used to display bank name in transaction listing based on method id stored in db
$config['deposit_funds_via_payment_processor_method_id_associated_method_name'] = [
 23 => 'Payment24',
 11 => 'MojePlatba',
 1 => 'eKonto',
 12 => 'mpenize',
 13 => 'Moneta',
 19 => 'ČSOB InternetBanking 24',
 17 => 'Fio Banka (Internetbanking)',
 22 => 'Equa bank (internetového bankovnictví)'
];


$config['deposit_funds_thepay_description_txt'] = 'Testovací produkt'; 

// Deposit funds via payment processor Transaction related message -- start
$config['deposit_funds_via_payment_processor_transaction_success_msg'] = 'Transaction done successfully, <span >{transaction_amount} '.CURRENCY.'</span> was added to your account balance.';
$config['deposit_funds_via_payment_processor_transaction_success_waiting_for_confirmation_msg'] = 'Transaction done successfully but we are waiting for confirmation from site once confirmation will get amount will be added to your account balance.';
$config['deposit_funds_via_payment_processor_transaction_cancelled_error_msg'] = 'Transaction you performed was cancelled';
$config['deposit_funds_via_payment_processor_transaction_failed_error_msg'] = 'Transaction failed';
$config['deposit_funds_via_payment_processor_user_activity_log_message'] = 'Your transaction for amount <span >{deposited_amount} '.CURRENCY.'</span> done successfully, please review your account balance.';
// end

// Deposit funds via payment processor Transaction related label -- start
$config['deposit_funds_via_payment_processor_transaction_history_label_txt'] = 'Latest deposit funds via payment processor transactions';
$config['deposit_funds_via_payment_processor_deposited_amount_lbl'] = 'PP-Deposited Amount:';
$config['deposit_funds_via_payment_processor_transaction_amount_lbl'] = 'PP-Transaction Amount:';
$config['deposit_funds_via_payment_processor_transaction_date_lbl'] = 'PP-Transaction Date:';
$config['deposit_funds_via_payment_processor_transaction_id_lbl'] = 'PP-Transaction id:';
$config['deposit_funds_via_payment_processor_transaction_charge_lbl'] = 'PP-Transaction charge:';

$config['deposit_funds_via_payment_processor_card_number_lbl'] = 'PP-Card Number:';
$config['deposit_funds_via_payment_processor_card_brand_lbl'] = 'PP-Card Brand:';
$config['deposit_funds_via_payment_processor_country_code_lbl'] = 'PP-Country Code:';
$config['deposit_funds_via_payment_processor_card_bank_name_lbl'] = 'PP-Card Bank Name:';
$config['deposit_funds_via_payment_processor_card_type_lbl'] = 'PP-Card Type:';

$config['deposit_funds_via_payment_processor_bank_name_lbl'] = 'PP-Bank Name:';
$config['deposit_funds_via_payment_processor_bank_account_number_lbl'] = 'PP-Bank Account Number:';
$config['deposit_funds_via_payment_processor_bank_owner_name_lbl'] = 'PP-Bank Owner Name:';
// end

// Deposit funds via payment processor transaction statuses -- start
$config['deposit_funds_via_payment_processor_transaction_status_lbl'] = 'PP-Transaction Status:';
$config['deposit_funds_via_payment_processor_transaction_success_status_txt'] = 'Successfull';
$config['deposit_funds_via_payment_processor_transaction_failed_status_txt'] = 'Failed';
$config['deposit_funds_via_payment_processor_transaction_cancelled_status_txt'] = 'Cancelled';
$config['deposit_funds_via_payment_processor_transaction_waiting_for_confirmation_status_txt'] = 'waiting for confirmation';
// end
##########################################################################################################################################

################################################### Bank withdraw #########################################################################
$config['withdraw_funds_direct_bank_transfer_transaction_listing_limit'] = 5;
$config['withdraw_funds_bank_heading'] = 'Once you have deoposited your funds,click the button to proceed. Enter your deposit details so we can identify your payment and finish the deposit faster. Please take a receipt or reference number from your bank after depositing.';

// withdraw funds via bank transafer labels --start
$config['withdraw_funds_bank_account_owner_lbl'] = 'Account owner';
$config['withdraw_funds_bank_account_number_lbl'] = 'Account Number';
$config['withdraw_funds_bank_name_lbl'] = 'Bank name';
$config['withdraw_funds_bank_code_lbl'] = 'Bank code';
$config['withdraw_funds_iban_lbl'] = 'IBAN';
$config['withdraw_funds_bank_bic_or_swift_code_lbl'] = 'BIC / SWIFT Code';
$config['withdraw_funds_bank_country_lbl'] = 'Country';
$config['withdraw_funds_withdrawal_amount_lbl'] = 'Withdraw amount';
$config['transaction_request_date'] = 'Transaction request date';

$config['withdraw_funds_withdraw_request_date_lbl'] = 'Withdraw Request Date';
$config['withdraw_funds_withdraw_request_status_lbl'] = 'Withdraw Request Status';
$config['withdraw_funds_bank_transaction_id_lbl'] = 'Bank Transaction ID';
$config['withdraw_funds_bank_transaction_date_lbl'] = 'Bank Transaction Date';

$config['withdraw_funds_register_transaction_btn'] = 'Register Transaction';

$config['withdraw_funds_bank_note_lbl'] = 'Note';

$config['withdraw_funds_user_bank_variable_symbol_lbl'] = 'W-Variable Symbol';
// end 

$config['withdraw_funds_bank_note_txt'] = 'Any transation fees charged by your bank will be deducted from the total transfer amount.Funds will be credited to your balance on the next business day after the funds are received by XXXXX\'s Bank.If you have any questions please contact Online Support.';


$config['withdraw_funds_user_bank_variable_symbol_prefix_value'] = '55555'; // this variable used to indicate site variable symbol prefix
$config['withdraw_funds_user_bank_variable_symbol_suffix_number_of_digits'] = 5; // This variable used to control generated variabil symbol suffix digit

$config['withdraw_funds_bank_country'] = 'WF - Select Country';

// withdraw funds via bank transafer Error messages --start
$config['withdraw_funds_withdrawal_amount_required_error_message'] = 'Withdraw amount is required';
$config['withdraw_funds_withdrawal_amount_invalid_error_message'] = 'Please enter valid deposited amount';
$config['withdraw_funds_withdrawal_amount_greater_than_available_balance_error_message'] = 'You cannot withdraw amount more than available balance.';
$config['withdraw_funds_account_owner_required_error_message'] = 'Account owner is required';
$config['withdraw_funds_account_number_required_error_message'] = 'Account number is required';
$config['withdraw_funds_bank_name_required_error_message'] = 'Bank name is required';
$config['withdraw_funds_bank_code_required_error_message'] = 'Bank code is required';
$config['withdraw_funds_bank_iban_required_error_message'] = 'IBAN is required';
$config['withdraw_funds_bank_bic_swift_code_required_error_message'] = 'Bank code is required';
$config['withdraw_funds_country_required_error_message'] = 'County name is required';
// end

// This is the confirmation messsage sent to user when he register direct bank transfer withdraw fund request
$config['withdraw_funds_direct_bank_transfer_confirmation_message'] = 'Your transaction successfully submitted to admin review.';
$config['withdraw_funds_direct_bank_transfer_user_activity_log_message'] = 'Your transaction request for amount <span>{withdraw_amount} '.CURRENCY.'</span> has been sent to admin review.';

$config['withdraw_funds_direct_bank_transfer_transaction_request_confirmed_by_admin_user_activity_log'] = 'Your transaction request for amount <span>{withdraw_amount} '.CURRENCY.'</span> is processed from our side, please check with your bank for further details.';
$config['withdraw_funds_direct_bank_transfer_transaction_request_rejected_by_admin_user_activity_log'] = 'Your transaction request for amount <span>{withdraw_amount} '.CURRENCY.'</span> has been rejected by admin and fund was added to your account balance.';

// withdraw funds via bank transafer transactions statuses --start
$config['withdraw_funds_direct_bank_transfer_admin_pending_confirmation_status_txt'] = 'Pending admin confirmation';
$config['withdraw_funds_direct_bank_transfer_confirmed_by_admin_status_txt'] = 'Admin Confirmed';
$config['withdraw_funds_direct_bank_transfer_rejected_by_admin_status_txt'] = 'Rejected by admin';
$config['withdraw_funds_direct_bank_transfer_added_by_admin_status_txt'] = 'Confirmed (Added By Admin)';
// end

#########################################################################################################################################


################################################### Transaction History #########################################################################

$config['transactions_history_search_btn_text'] = 'Search';

$config['transactions_history_clear_filter_btn_text'] = 'Clear Filter';

$config['transactions_history_from_lbl'] = 'From';
$config['transactions_history_to_lbl'] = 'To';

$config['transactions_history_from_date_prior_than_to_date_error_message'] = 'From date should not be prior to to date.'; // This error message display to user when he try to select from date prior than to date for filter


$config['transactions_history_show_more_search_options_text'] = 'zobrazit více možností hledání <small>( + )</small>';
$config['transactions_history_hide_extra_search_options_text'] = 'zavřít více možností hledání <small>( - )</small>';

// This variable used to display filter options related to date filter
$config['transactions_history_date_filter_checkboxes_lbl'] = [
 'all' => 'All',
 'today' => 'Today',
 'this_month' => 'This Month',
 'last_month' => 'Last Month',
 'begining_of_year' => 'From Beginning of the year',
 'custom_date' => 'Custom Date Selection'
];


//This config are using for drop down "all" option on transaction history page 
$config['transactions_history_all_option_txt'] = 'all';

// Transaction history filter dropdown option name -- start
$config['transactions_history_deposits_dropdown_option_name'] = 'Deposits';

$config['transactions_history_withdrawals_dropdown_option_name'] = 'withdrawals';

$config['transactions_history_project_upgrades_dropdown_option_name'] = 'Projects Upgrades';

$config['transactions_history_payments_on_projects_dropdown_option_name'] = 'Payments on project';

$config['transactions_history_payments_on_fulltime_jobs_dropdown_option_name'] = 'Payments on fulltime job';

$config['transactions_history_received_payments_on_projects_dropdown_option_name'] = 'Received payments on projects';

$config['transactions_history_salary_payments_received_on_fulltime_jobs_dropdown_option_name'] = 'Received payments on fulltime jobs';

$config['transactions_history_service_fees_payments_dropdown_option_name'] = 'Service fees payments';
// end

// Transaction history filter dropdown option list -- start
$config['transactions_history_deposits_option_list'] = [
 'deposits_all' => 'All', 'deposits_via_paypal' => 'Via Paypal', 'deposit_via_payment_card' => 'Via Payment Card', 'deposits_via_bank_transfer' => 'Via Bank Transfer', 'deposits_via_bank' => 'Via Direct Bank Transfer', 'deposits_none' => 'None'
];

$config['transactions_history_withdraws_option_list'] = [
 'withdraws_all' => 'All', 'withdraws_via_paypal' => 'Via Paypal', 'withdraws_via_bank' => 'Via Direct Bank Transfer', 'referral_earnings_withdraws' => 'Referral Earnings' , 'withdraws_none' => 'None'
];

$config['transactions_history_project_upgrades_option_list'] = [
 'project_upgrades_all' => 'All', 'featured' => 'Featured', 'urgent' => 'Urgent', 'sealed' => 'SEALED' , 'hidden' => 'Hidden', 'Featured_upgrade_prolongations' => 'Featured upgrade prolongations' , 'Urgent_upgrade_prolongations' => 'Urgent upgrade prolongation' , 'project_upgrades_none' => 'None'
];

$config['transactions_history_payments_on_projects_option_list'] = [
 'payments_projects_all' => 'All', 'payments_on_fixed_budget_projects' => 'Fixed Budget', 'payments_on_hourly_rate_based_projects' => 'Hourly Rate', 'payments_projects_none' => 'None'
];

$config['transactions_history_salary_payments_on_fulltime_jobs_option_list'] = ['salary_payments_on_fulltime_jobs_all' => 'All', 'salary_payments_on_fulltime_jobs_none' => 'none'];

$config['transactions_history_payments_received_on_projects_option_list'] = [
 'payments_received_projects_all' => 'All' , 'payments_received_fixed_budget_projects' => 'Fixed Budget', 'payments_received_hourly_rate_based_projects' => 'Hourly Rate', 'payments_received_projects_none' => 'none'
];

$config['transactions_history_salary_payments_received_on_fulltime_jobs_option_list'] = ['salary_payments_received_fulltime_jobs_all' => 'All', 'salary_payments_received_fulltime_jobs_none' => 'none'];

$config['transactions_history_service_fees_payments_option_list'] = [
 'service_fees_payments_all' => 'All', 'service_fees_payments_fixed_budget_projects' => 'On Fixed budget Projects', 'service_fees_payments_hourly_rate_based_projects' => 'On Hourly rate projects', 'service_fees_payments_fulltime_jobs' => 'On Fulltime jobs', 'service_fees_payments_none' => 'none' 
];
// end

// Transaction history labels -- start
$config['transactions_history_project_upgrade_name_lbl'] = 'Project Upgrade Name';
$config['transactions_history_purchase_on_project_name_lbl'] = 'Purchase on project';
$config['transactions_history_project_upgrade_purchase_on_lbl'] = 'Upgrade Purchase Value';
$config['transactions_history_project_upgrade_purchase_date_lbl'] = 'Upgrade Purchase Date';
$config['transactions_history_project_upgrade_payment_source_lbl'] = 'Payment Source';
$config['transactions_history_payment_reference_id_lbl'] = 'Payment Referrence ID';

$config['transactions_history_project_name_lbl'] = 'Project Name';
$config['transactions_history_service_provider_name_lbl'] = 'Service Provider Name';
$config['transactions_history_project_owner_name_lbl'] = 'Project Owner Name';

$config['transactions_history_employer_name_lbl'] = 'Employer Name';

$config['transactions_history_project_paid_amount_lbl'] = 'Paid Amount';
$config['transactions_history_project_received_payment_amount_lbl'] = 'Received Payment Amount';
$config['transactions_history_project_paid_service_fees_amount_lbl'] = 'Paid Service Fees';

$config['transactions_history_payment_date_lbl'] = 'Payment Date';
$config['transactions_history_employee_name_lbl'] = 'Employee Name';
$config['transactions_history_fulltime_job_name_lbl'] = 'Fulltime job Name';

$config['transactions_history_salary_paid_amount_lbl'] = 'Salary paid amount';
$config['transactions_history_salary_received_amount_lbl'] = 'Salary received amount';
// end

$config['transactions_history_project_id_txt'] = '<span class="finance_project_id">project id:</span>';

$config['transactions_history_project_upgrade_purchase_included_membership_txt'] = 'Included in membership';
$config['transactions_history_project_upgrade_purchase_bonus_balance_txt'] = 'Bonus Balance';
$config['transactions_history_project_upgrade_purchase_account_balance_txt'] = 'Account Balance';



// 06-04-2020 - start
$config['transactions_history_no_transaction_available_message'] = '<h4>There are no available transactions at the moment</h4>';
$config['transactions_history_search_no_results_returned_message'] = '<h4>There are no results that match your search</h4><p>Please try adjusting your search filters and try again.</p>';
$config['transactions_history_loader_display_text'] = 'Loading please wait...';


// - end

$config['transaction_history_project_upgrades_upgrade_types'] = [
 'featured' => 'FEATURED',
 'urgent' => 'URGENT',
 'sealed' => 'SEALED',
 'hidden' => 'HIDDEN',
];
###############################################################################################################################################

######################################################### Invoices ###########################################################################

$config['invoices_tracking_heading'] = 'You currently receive 1 invoice a month. The invoice will be created on the following day: {next_invoice_date}';
$config['invoices_tracking_all_years_option_name'] = 'All Years';
$config['invoices_tracking_search_btn'] = 'Search';
$config['invoices_tracking_total_invoices_txt'] = 'Total invoices';
$config['invoices_tracking_invoice_number_txt'] = 'Invoice Number';
$config['invoices_tracking_invoices_number_txt'] = 'Invoice Numbers';

$config['invoices_tracking_invoice_generated_month_txt'] = 'Invoice Months';
$config['invoices_tracking_invoice_amount_txt'] = 'Amount';

$config['invoices_tracking_download_invoice_as_pdf_extension_txt'] = '.pdf';
$config['invoices_tracking_for_invoice_contact_support_txt'] = 'For invoice contact support'; // this text will display when invoice value more exceed more then specified limit in settings


$config['invoices_tracking_no_record'] = '<h4>currently you do not have any generated invoice.You will receive 1 invoice a month.</h4><p>The next invoice will be created on the following day: {next_invoice_date}</p>';


// Following variable is used in invoice format which will download as pdf
$config['invoice_format_form_lbl'] = 'dodavatel';
$config['invoice_format_to_lbl'] = 'odběratel';
$config['invoice_format_invoice_number_lbl'] = 'FAKTURA - DAŇOVÝ DOKLAD č.';
$config['invoice_format_invoice_date_lbl'] = 'Invoice Date';
$config['invoice_format_table_heading_pos_txt'] = 'Pos';
$config['invoice_format_table_heading_description_txt'] = 'Description';
$config['invoice_format_table_heading_unit_amount_txt'] = 'Unit Amount';
$config['invoice_format_table_heading_amount_excluding_vat_txt'] = 'Amount (excl. VAT)';
$config['invoice_format_table_heading_total_excluded_vat_txt'] = 'Total (excl. VAT)';
$config['invoice_format_table_heading_vat_percentage_txt'] = 'VAT ({vat_percentage}%)';
$config['invoice_format_table_heading_total_amount_txt'] = 'Total';

$config['invoice_download_file_prefix'] = 'travai_'; // this variable used to set download invoice file name prefix
$config['invoice_format_footer_txt'] = 'This text will be displayed in footer of downloaded invoice';

$config['invoice_format_service_fees_related_project_payment_txt'] = 'Service fees related to payment done to {user_first_name_last_name_or_company_name} on <a href="{project_url}">{project_title}</a>';
$config['invoice_format_service_fees_related_salary_payment_txt'] = 'Service fees related to salary payment done to {user_first_name_last_name_or_company_name} on fulltime <a href="{project_url}">{project_title}</a>';

$config['invoice_format_admin_dispute_moderation_fee_on_project_txt'] = 'Admin dispute moderation service fees related to <a href="{project_url}">Dispute id: {dispute_id}</a> on project <a href="{project_url}">{project_title}</a>';
$config['invoice_format_admin_dispute_moderation_fee_on_fulltime_project_txt'] = 'Admin dispute moderation service fees related to <a href="{project_url}">Dispute id: {dispute_id}</a> on fulltime project <a href="{project_url}">{project_title}</a>';

$config['invoice_format_upgrade_purchase_on_project_txt'] = '<span class="upgrade_type">{project_upgrade_type}</span> project upgrade purchase on project <a href="{project_url}">{project_title}</a>';
$config['invoice_format_upgrade_purchase_on_fulltime_job_txt'] = '<span class="upgrade_type">{project_upgrade_type}</span> project upgrade purchase on fulltime jpb <a href="{project_url}">{project_title}</a>';

$config['invoice_format_deposit_funds_via_paypal_transaction_fee_txt'] = 'Transaction fee related to deposit via PayPal Transaction ID {transaction_id} on date {transaction_date}';

$config['invoice_format_deposit_funds_via_payment_processor_transaction_fee_using_bank_transfer_txt'] = 'Transaction fee related to deposit funds via Payment processor Transaction ID {transaction_id} Bank Name - {bank_name} bank method - {payment_method} on date {transaction_date}';
$config['invoice_format_deposit_funds_via_payment_processor_transaction_fee_using_payment_card_transfer_txt'] = 'Transaction fee related to deposit funds via Payment processor Transaction ID {transaction_id} Payment Card used - {card_brand} on date {transaction_date}';
############################################################################################################################################


################################################################ Invoicing Details #########################################################

//page heading
$config['finance_headline_title_invoicing_details'] = 'Invoicing Details';
//Meta Tag
$config['company_invoicing_details_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Invoicing Details';
$config['company_app_invoicing_details_page_title_meta_tag'] = 'app-{user_first_name_last_name_or_company_name} | Invoicing Details';
//Description Meta Tag
$config['company_invoicing_details_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Invoicing Details';
$config['company_app_invoicing_details_description_meta_tag'] = 'app-{user_first_name_last_name_or_company_name} | Invoicing Details';
//url
$config['finance_invoicing_details_page_url'] = 'invoicing-details';

$config['company_invoicing_details_inital_view_title'] = 'Invoicing Details';
$config['company_app_invoicing_details_inital_view_title'] = 'app-Invoicing Details';

$config['company_invoicing_details_initial_view_content'] = 'Vložením adresy zkvalitňujete svůj profil pro ostatní uživatele, kteří mohou hledat odborníka ve svém kraji či obci. Váš profil může být dohledatelný v katalogu profesionálů pomocí filtru dané lokality.';
$config['company_app_invoicing_details_initial_view_content'] = 'app-Vložením adresy zkvalitňujete svůj profil pro ostatní uživatele, kteří mohou hledat odborníka ve svém kraji či obci. Váš profil může být dohledatelný v katalogu profesionálů pomocí filtru dané lokality.';

$config['company_invoicing_details_company_name_lbl'] = 'Company Name';
$config['company_app_invoicing_details_company_name_lbl'] = 'app-Company Name';

$config['company_invoicing_details_company_address_lbl'] = 'Company Address';
$config['company_app_invoicing_details_company_address_lbl'] = 'app-Company Address';

$config['company_invoicing_details_select_country_lbl'] = 'Country';
$config['company_app_invoicing_details_select_country_lbl'] = 'app-Country';

$config['company_invoicing_details_company_registration_number_lbl'] = 'Registration Number';
$config['company_app_invoicing_details_company_registration_number_lbl'] = 'app-Registration Number';

$config['company_invoicing_details_company_vat_number_lbl'] = 'VAT Number';
$config['company_app_invoicing_details_company_vat_number_lbl'] = 'app-VAT Number';

$config['company_invoicing_details_company_no_vat_registered_lbl'] = 'No vat registered';
$config['company_app_invoicing_details_company_no_vat_registered_lbl'] = 'app-No vat registered';

$config['company_invoicing_details_company_name_required_error_message'] = 'Company Name required';
$config['company_app_invoicing_details_company_name_required_error_message'] = 'app-Company Name required';

$config['company_invoicing_details_company_address_required_error_message'] = 'Company Address required';
$config['company_app_invoicing_details_company_address_required_error_message'] = 'app-Company Address required';

$config['company_invoicing_details_country_required_error_message'] = 'Country required';
$config['company_app_invoicing_details_country_required_error_message'] = 'app-Country required';

$config['company_invoicing_details_company_registration_number_required_error_message'] = 'Company Registration Number required';
$config['company_app_invoicing_details_company_registration_number_required_error_message'] = 'app-Company Registration Number required';

$config['company_invoicing_details_company_vat_number_required_error_message'] = 'VAT required';
$config['company_app_invoicing_details_company_vat_number_required_error_message'] = 'app-VAT required';

$config['company_invoicing_details_confirmation_modal_body_heading'] = 'Are you sure you want to save?';
$config['company_app_invoicing_details_confirmation_modal_body_heading'] = 'app-Are you sure you want to save?';

$config['company_invoicing_details_confirmation_modal_body'] = 'EN - Můžete odeslat pouze 1 žádost o spojení s nejmodernější která.**nejmodernější** je tímto informován a bude jeho rozhodnutí přijmout nebo ne. Po přijetí žádosti se automaticky objeví ve vašem seznamu kontaktů a budete ve spojení.';
$config['company_app_invoicing_details_confirmation_modal_body'] = 'app-EN - Můžete odeslat pouze 1 žádost o spojení s nejmodernější která.**nejmodernější** je tímto informován a bude jeho rozhodnutí přijmout nebo ne. Po přijetí žádosti se automaticky objeví ve vašem seznamu kontaktů a budete ve spojení.';

$config['company_invoicing_details_confirmation_modal_data_already_saved_error_message'] = 'currently you can not save this entry. please refresh the page.';
$config['company_app_invoicing_details_confirmation_modal_data_already_saved_error_message'] = 'app-currently you can not save this entry. please refresh the page.';

$config['company_invoicing_details_edit_view_top_heading_txt'] = '<b>EN edit -</b> Když zaparkovala před domem, přepadli ji únosci a násilím ji odvlekli do přistaveného vozu. Poté ji jeden z mužů zpacifikoval několika ranami pěstí do obličeje, <br>nasadili jí pouta, pytel přes hlavu a odvezli ji za město';
$config['company_app_invoicing_details_edit_view_top_heading_txt'] = '<b>app-EN edit -</b> Když zaparkovala před domem, přepadli ji únosci a násilím ji odvlekli do přistaveného vozu. Poté ji jeden z mužů zpacifikoval několika ranami pěstí do obličeje, <br>nasadili jí pouta, pytel přes hlavu a odvezli ji za město';

$config['company_invoicing_details_edit_view_bottom_heading_txt'] = '<b>EN edit -</b> policisty, který sestavil nějaký svůj plán, a za součinnosti špatné práce státního zastupitelství se podařilo odsoudit člověka k deseti letům.';
$config['company_app_invoicing_details_edit_view_bottom_heading_txt'] = '<b>app-EN edit -</b> policisty, který sestavil nějaký svůj plán, a za součinnosti špatné práce státního zastupitelství se podařilo odsoudit člověka k deseti letům.';

$config['company_invoicing_details_non_edit_view_top_heading_txt'] = '<b>EN nedit -</b> Když zaparkovala před domem, přepadli ji únosci a násilím ji odvlekli do přistaveného vozu. Poté ji jeden z mužů zpacifikoval několika ranami pěstí do obličeje, <br>nasadili jí pouta, pytel přes hlavu a odvezli ji za město';
$config['company_app_invoicing_details_non_edit_view_top_heading_txt'] = '<b>app-EN nedit -</b> Když zaparkovala před domem, přepadli ji únosci a násilím ji odvlekli do přistaveného vozu. Poté ji jeden z mužů zpacifikoval několika ranami pěstí do obličeje, <br>nasadili jí pouta, pytel přes hlavu a odvezli ji za město';

$config['company_invoicing_details_non_edit_view_bottom_heading_txt'] = '<b>EN nedit -</b> policisty, který sestavil nějaký svůj plán, a za součinnosti špatné práce státního zastupitelství se podařilo odsoudit člověka k deseti letům. ';
$config['company_app_invoicing_details_non_edit_view_bottom_heading_txt'] = '<b>app-EN nedit -</b> policisty, který sestavil nějaký svůj plán, a za součinnosti špatné práce státního zastupitelství se podařilo odsoudit člověka k deseti letům. ';

###########################################################################################################################################

//transactions history 
//page heading
$config['finance_headline_title_transactions_history'] = 'Transactions History';
//Meta Tag
$config['transactions_history_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | transactions_history_page_title';
//Description Meta Tag
$config['transactions_history_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | transactions_history_page_description';
//url
$config['finance_transactions_history_page_url'] = 'transactions-history';


//Invoices
//page heading
$config['finance_headline_title_invoices'] = 'Invoices';
//Meta Tag
$config['invoices_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | invoices_page_title';
//Description Meta Tag
$config['invoices_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | invoices_page_description'; 
//url
$config['finance_invoices_page_url'] = 'invoices';


//Invoices Details


?>
