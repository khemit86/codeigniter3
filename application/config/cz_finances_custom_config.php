<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// Left Navigation Menu name
$config['pa_user_left_nav_finance'] = 'Finance';
$config['ca_user_left_nav_finance'] = 'Finance';
$config['ca_app_user_left_nav_finance'] = 'Finance';

// Left Navigation Finance Menu sub-menu name --start
$config['finance_left_nav_deposit_funds'] = 'Dobití účtu';
$config['finance_left_nav_withdraw_funds'] = 'Výběr z účtu';
$config['finance_left_nav_transactions_history'] = 'Historie transakcí';
$config['finance_left_nav_invoices'] = 'Faktury';
$config['finance_left_nav_invoicing_details'] = 'Fakturační údaje';
// end


$config['deposit_funds_via_paypal_processing_fees_info'] = 'Transaction fees are charged as 10% for amounts of up to 600 Kč, and 5% for amounts more than 601 Kč.'; // This variable used to display processing fees related info on deposit funds via paypal page

$config['deposit_funds_via_paypal_total_amount_tooltip_info'] = 'deposit_funds_via_paypal_total_amount_tooltip_info'; // This variable used to display deposit funds via paypal total amount tooltip info

$config['deposit_funds_via_payment_card_total_amount_tooltip_info'] = 'deposit_funds_via_payment_card_total_amount_tooltip_info'; // This variable used to display deposit funds via payment card total amount tooltip info

$config['deposit_funds_via_paypal_success_message'] = 'Transakce proběhla úspěšně s částkou <span>{transaction_amount} Kč.</span> Částka byla připsána na váš Travai účet.'; // This message display to user when he successfully deposited amount on paypal and redirect to site

$config['deposit_funds_via_paypal_display_activity_log_message'] = 'Vklad <span>{transaction_amount} '.CURRENCY.'</span> byl proveden přes PayPal.'; // This activity log message display to user when payment successfully done on paypal

// Deposit funds via paypal tab labels -- start
$config['deposit_funds_via_paypal_deposit_amount_label_txt'] = "Výše vkladu";

$config['deposit_funds_via_paypal_processing_fee_label_txt'] = "Transakční poplatek";

$config['deposit_funds_via_paypal_total_label_txt'] = "Celkem";

$config['deposit_funds_via_paypal_confirm_payment_btn_txt'] = 'Potvrdit a dobít účet';

$config['deposit_funds_via_paypal_deposited_amount_label_txt'] = 'Částka';
$config['deposit_funds_via_paypal_transaction_date_label_txt'] = 'Datum transakce';
$config['deposit_funds_via_paypal_paypal_account_label_txt'] = 'Paypal email';
$config['deposit_funds_via_paypal_transaction_id_label_txt'] = 'ID transakce';
$config['deposit_funds_via_paypal_transaction_charge_label_txt'] = 'Transaction Charge';

$config['deposit_funds_via_paypal_transaction_history_label_txt'] = "Transakční historie";
// end

// Deposit funds
//page heading
$config['finance_headline_title_deposit_funds'] = 'Dobití účtu';
//Meta Tag
$config['deposit_funds_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Dobití kreditu na Travai účet';
//Description Meta Tag
$config['deposit_funds_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Dobití kreditu na Travai účet';
//url
$config['finance_deposit_funds_page_url'] = 'dobiti-uctu';
//checkbox text
$config['deposit_funds_page_checkbox_deposit_via_paypal'] = 'PayPal';

$config['deposit_funds_page_checkbox_deposit_via_payment_cards'] = 'Kreditní karta & Rychlý převod';

$config['deposit_funds_page_checkbox_deposit_via_bank_transfer'] = 'Bankovní převod';

//withdraw funds 
//page heading
$config['finance_headline_title_withdraw_funds'] = 'Výběr z účtu';
//Meta Tag
$config['withdraw_funds_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Výběr z Travai účtu';
//Description Meta Tag
$config['withdraw_funds_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Výběr z Travai účtu';
//url
$config['finance_withdraw_funds_page_url'] = 'vyberzuctu';
//checkbox text
$config['finance_page_checkbox_withdraw_via_paypal'] = 'PayPal';
$config['finance_page_checkbox_withdraw_via_bank_transfer'] = 'Bankovní převod';

// This error message display to user when he try to withdraw amount more then available
$config['withdraw_funds_via_paypal_insufficent_balance_error_message'] = "pro požadovaný výběr nemáte dostatečný zůstatek na vašem účtu";

// Withdraw funds via paypal labels and error message --start
$config['withdraw_funds_via_paypal_email_account_label_txt'] = 'Paypal email';

$config['withdraw_funds_amount_to_withdraw_via_paypal_label_txt'] = 'Částka k výběru';

$config['withdraw_funds_amount_receive_in_paypal_label_txt'] = 'Částka k přijetí na PayPal';

$config['withdraw_funds_via_paypal_btn_txt'] = 'Provést výběr';

$config['confirm_withdraw_funds_via_paypal_withdraw_amount_btn_txt'] = 'Potvrdit a provést výběr <span>{withdraw_amount}' .' '.CURRENCY.'</span>';

$config['withdraw_funds_via_paypal_invalid_email_error_message'] = 'zadejte správnou emailovou adresu';

$config['withdraw_funds_via_paypal_transaction_history_label_txt'] = "Transakční historie";
// end

// This activity log message display to user when he request for withdraw funds via paypal
$config['withdraw_funds_request_via_paypal_display_activity_log_message'] = 'Požádali jste o výběr <span>{transaction_amount} '.CURRENCY.'</span> na váš PayPal účet. Žádost čeká na kontrolu a schválení administrátorem.';
// This activity log message display to user when withdraw funds request rejected by admin
$config['withdraw_funds_request_rejected_by_admin_display_activity_log_message'] = 'Žádost o výběr <span>{transaction_amount} '.CURRENCY.'</span> byla zamítnuta administrátorem.';

// Withdraw funds via paypal labels  --start
$config['withdrawal_request_amount_label_txt'] = 'Částka požadovaná k výběru';

$config['withdrawal_request_submit_date_label_txt'] = 'Datum žádosti';

$config['withdrawal_to_paypal_account_label_txt'] = 'Výběr do PayPal účtu';

$config['withdrawal_request_status_label_txt'] = 'Stav požadavku';

$config['withdraw_amount_label_txt'] = 'Částka';

$config['transaction_date_label_txt'] = 'Datum transakce';

$config['paypal_account_label_txt'] = 'PayPal email';

$config['transaction_id_label_txt'] = 'ID transakce';

$config['transaction_status_label_txt'] = 'Status transakce';

$config['transaction_failure_reason_label_txt'] = 'Důvod selhání';

$config['withdrawal_request_rejection_date_label_txt'] = 'Datum zamítnutí';

$config['withdrawal_request_approval_date_label_txt'] = 'Datum schválení';
// end

// This variable used to display processing fees info on withdraw funds page
$config['withdraw_funds_processing_fees_info'] = 'poplatek účtovaný 2% z částky do 20 000 '.CURRENCY.' a fixní 400 '.CURRENCY.' když jsou částky vyšší než 20 000 '.CURRENCY.'';

// These variables used to display different statuses of withdraw funds via paypal --start
$config['withdrawal_request_via_paypal_admin_review_status_txt'] = 'čekání na schválení';

$config['withdrawal_request_via_paypal_rejected_by_admin_status_txt'] = 'odmítnuto';

$config['withdrawal_request_via_paypal_transaction_success_status_txt'] = 'převedeno';

$config['withdrawal_request_via_paypal_transaction_failed_status_txt'] = 'chyba';
// end

// This variable used at admin side to store transaction failure reason which will be displayed to user
$config['withdraw_funds_failure_reason'] = [
 'RECEIVER_UNREGISTERED' => "neexistující PayPal účet", //invalid paypal account
 'CURRENCY_COMPLIANCE' => 'tato transakce není povolena', //Due to currency compliance regulations, we are not allowed to make this transaction
 'GAMER_FAILED_COUNTRY_OF_RESIDENCE_CHECK' => 'tato platba není povolená pro zvolenou zemi', //Your living country is not allowed to accept this payment
 'RECEIVER_ACCOUNT_LOCKED' => 'PayPal účet je neaktivní nebo zakázaný', //Your account is inactive or restricted
 'RECEIVER_COUNTRY_NOT_ALLOWED' => 'pro vybranou zemi PayPal neposkytuje platby', //Your living country is not allowed to accept this payment.
 'RECEIVER_STATE_RESTRICTED' => 'tato platba není povolená pro zvolenou stát', //Your living state is not allowed to accept this payment
 'RECEIVER_UNCONFIRMED' => 'PayPal účet není ověřený', //Your email or phone number is unconfirmed
 'RECEIVER_YOUTH_ACCOUNT' => 'PayPal účet je zamítnutý, zvolte jiný účet', //You have youth account, please provide alternate account
 'RECEIVING_LIMIT_EXCEEDED' => 'převáděná částka přesahuje nastavený limit PayPal účtu', //Your receiving amount limit has been exceeds.
 'RECEIVER_REFUSED' => 'PayPal účet nepodporuje druh převáděné měny' //Your account is not accepting payment in specified currency
];


################################################ Bank Deposit ###############################################################################
$config['deposit_funds_bank_heading_txt'] = 'Přijímáme vklady pouze v měně '.CURRENCY.'. Jakékoli vklady v jiné měně než '.CURRENCY.' budou směněny podle aktuálního platného kurzu vydaný bankou. Zůstatek vašeho Travai účtu bude navýšen přesnou částkou v '.CURRENCY.' obdrženou na náš bankovní účet.<br><br>Pomocí níže uvedeného formuláře můžete zaregistrovat jakoukoli bankovní transakci, kterou jste provedli ručně, na Travai bankovní účet. Jakmile vaší transkaci identifikujeme na našem bankovním účtu, bude převáděná částka ihned připsána na vašem Travai účtu.';

$config['deposit_funds_bank_body_txt'] = 'Jakmile převedete prostředky, zkopírujte všechny informace z vaší provedené transakce a níže na stránce vyplňte formulář. Po vypnlění klikněte na tlačítko "Registrace transakce", a tím zaznamenáte svou transakci do našeho systému.';


$config['deposit_funds_direct_bank_transfer_transaction_listing_limit'] = 3;

// deposit funds bank transfer labels -- start
$config['deposit_funds_bank_information_lbl'] = 'Bankovní informace jsou zde';

$config['deposit_funds_bank_account_owner_lbl'] = 'Majitel účtu';

$config['deposit_funds_bank_account_number_lbl'] = 'Číslo účtu';

$config['deposit_funds_bank_name_lbl'] = 'Název banky';

$config['deposit_funds_bank_code_lbl'] = 'Kód banky';

$config['deposit_funds_iban_lbl'] = 'IBAN';
$config['deposit_funds_bank_bic_or_swift_code_lbl'] = 'BIC / SWIFT';

$config['deposit_funds_bank_country_lbl'] = 'Stát';

$config['deposit_funds_deposited_amount_lbl'] = 'Výše vkladu';

$config['deposit_funds_transaction_date_lbl'] = 'Datum transakce';

$config['deposit_funds_transaction_id_lbl'] = 'ID transakce';

$config['deposit_funds_bank_transaction_id_lbl'] = 'Bankovní ID transakce';

$config['deposit_funds_bank_transaction_date_lbl'] = 'Bankovní datum transakce';

$config['deposit_funds_user_bank_variable_symbol_lbl'] = 'Variabilní symbol';
// end

$config['deposit_funds_register_transaction_btn'] = 'Registrace transakce';

// These variables used to display site bank information where user can deposit funds --start
$config['deposit_funds_bank_account_owner_value'] = 'Travai agentura, s.r.o.';
$config['deposit_funds_bank_account_number_value'] = '115-7337820217';
$config['deposit_funds_bank_name_value'] = 'Komerční banka';
$config['deposit_funds_bank_code_value'] = '0100';
$config['deposit_funds_iban_value'] = 'CZ7301000001157337820217';
$config['deposit_funds_bank_bic_or_swift_code_value'] = 'KOMBCZPPXXX';
$config['deposit_funds_bank_country_value'] = 'Česká';
$config['deposit_funds_user_bank_variable_symbol_prefix_value'] = '314'; // this variable used to indicate site variable symbol prefix
$config['deposit_funds_user_bank_variable_symbol_suffix_number_of_digits'] = 2; // This variable used to control generated variabil symbol suffix digit
// end

$config['deposit_funds_bank_reference_heading_txt'] = 'Zpráva pro příjemce';

$config['deposit_funds_bank_reference_txt'] = 'Do pole Zpráva pro příjemce zkopírujte vaše';

$config['deposit_funds_bank_reference_username_lbl'] = 'profilové jméno';

$config['deposit_funds_bank_note_lbl'] = 'Poznámka';

$config['deposit_funds_bank_note_txt'] = 'Veškeré transakční poplatky účtované vaší bankou budou odečteny z celkové částky převodu. Finanční prostředky budou připsány na váš Travai účet následující pracovní den poté, co budou peněžní prostředky přijaty naší bankou.<br><br>Máte-li jakékoli dotazy nebo potřebujete jakékoli informace nebo podporu při zadávání, neváhejte nás kontaktovat.';

$config['deposit_funds_bank_country'] = 'vybrat zemi';

// Error messages for deposit funds via bank transfer --start
$config['deposit_funds_deposited_amount_required_error_message'] = 'výše vkladu je povinné pole';

$config['deposit_funds_deposited_amount_invalid_error_message'] = 'pole není správně vyplněno';

$config['deposit_funds_account_owner_required_error_message'] = 'majitel účtu je povinné pole';

$config['deposit_funds_account_number_required_error_message'] = 'číslo účtu je povinné pole';

$config['deposit_funds_bank_name_required_error_message'] = 'název banky je povinné pole';

$config['deposit_funds_bank_code_required_error_message'] = 'kód banky je povinné pole';

$config['deposit_funds_bank_iban_required_error_message'] = 'IBAN je povinné pole';

$config['deposit_funds_bank_bic_swift_code_required_error_message'] = 'BIC / SWIFT je povinné pole';

$config['deposit_funds_country_required_error_message'] = 'stát je povinné pole';

$config['deposit_funds_transaction_date_required_error_message'] = 'datum transakce je povinné pole';

$config['deposit_funds_transaction_date_invalid_format_error_message'] = 'vložte datum transakce ve správném formátu (dd.mm.rrrr)';

$config['deposit_funds_transaction_date_invalid_error_message'] = 'datum není správně zadáno';

$config['deposit_funds_transaction_id_required_error_message'] = 'ID transakce je povinné pole';
// end

$config['deposit_funds_direct_bank_transfer_confirmation_message'] = 'Transakce byla přijata ke zpracování.'; // This is confirmation display to user when bank transfer transaction successfully submitted to admin reivew

$config['deposit_funds_direct_bank_transfer_user_activity_log_message'] = 'Transakce ve výši <span>{deposited_amount} '.CURRENCY.'</span> byla přijata ke zpracování.'; // This is activity log message display to user when bank transfer transaction successfully submitted to admin reivew

$config['deposit_funds_direct_bank_transfer_transaction_confirmed_by_admin_user_activity_log'] = 'Transakce ve výši <span>{deposited_amount} '.CURRENCY.'</span> byla potvrzena a částka byla připočtena na váš Travai účet.'; // This activity log message display to user when admin confirm requested bank transfer transaction

// These varaibles used to display transaction statuses in deposit funds via bank transfer listing --start
$config['deposit_funds_direct_bank_transfer_pending_confirmation_status_txt'] = 'čeká na potvrzení';

$config['deposit_funds_direct_bank_transfer_confirmed_status_txt'] = 'potvrzeno';

$config['deposit_funds_direct_bank_transfer_added_by_admin_status_txt'] = 'potvrzeno (provedeno Travai)';
//end
############################################################################################################################################

########################################################## Deposit funds via payment processor #######################################################
$config['deposit_funds_via_payment_processor_heading_txt'] = 'Níže na stránce nejprve vepište výši částky pro vklad na váš Travai účet. Toto je možnost jak rychle doplnit váš Travai účet. Připomínáme, že při volbě této metody jsou spojené transakční poplatky. Účtované poplatky se liší podle částky, která má být uskutečněna.<br><br>Na řádku "Celkem" se zobrazuje částka, kterou obdržíte na svůj Travai účet, jakmile je transakce úspěšně dokončena. Po provedení transakce, bude váš Travai účet okamžitě připsán převedenou částkou.<br><br>Tímto způsobem je možné provádět vklady pouze v '.CURRENCY.'. Pro vklady v jakékoli jiné měně nás prosím kontaktujte.';

// These variables used to display labels and options name on deposit funds via payment processor tab --start
$config['deposit_funds_via_payment_processor_deposit_amount_label_txt'] = 'Výše vkladu';
$config['deposit_funds_via_payment_processor_processing_fee_label_txt'] = 'Transakční poplatek';

$config['deposit_funds_via_payment_processor_processing_fee_info'] = 'účtovaný poplatek za transakci';

$config['deposit_funds_via_payment_processor_total_label_txt'] = 'Celkem';

$config['deposit_funds_via_payment_processor_confirm_payment_btn_txt'] = 'Potvrdit a dobít účet';

$config['deposit_funds_via_payment_processor_credit_card_option_name'] = 'Platba kartou';
$config['deposit_funds_via_payment_processor_payment_24_option_name'] = 'Platba24';
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
 23 => 'Platba24',
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
$config['deposit_funds_via_payment_processor_transaction_success_msg'] = 'Transakce na částku <span >{transaction_amount} '.CURRENCY.'</span> je provedena, zkontrolujte svůj Travai účet.';

$config['deposit_funds_via_payment_processor_transaction_success_waiting_for_confirmation_msg'] = 'Transaction done successfully but we are waiting for confirmation from site once confirmation will get amount will be added to your account balance.';

$config['deposit_funds_via_payment_processor_transaction_cancelled_error_msg'] = 'Transakci jste zrušili.';

$config['deposit_funds_via_payment_processor_transaction_failed_error_msg'] = 'Transaction failed.';

$config['deposit_funds_via_payment_processor_user_activity_log_message'] = 'Transakce ve výši <span >{deposited_amount} '.CURRENCY.'</span> byla potvrzena a částka byla připočtena na váš Travai účet.';
// end

// Deposit funds via payment processor Transaction related label -- start
$config['deposit_funds_via_payment_processor_transaction_history_label_txt'] = 'Transakční historie';

$config['deposit_funds_via_payment_processor_transaction_amount_lbl'] = 'Výše vkladu:';
$config['deposit_funds_via_payment_processor_deposited_amount_lbl'] = 'Výše vkladu:';

$config['deposit_funds_via_payment_processor_transaction_date_lbl'] = 'Datum transakce:';
$config['deposit_funds_via_payment_processor_transaction_id_lbl'] = 'ID transakce:';
$config['deposit_funds_via_payment_processor_transaction_charge_lbl'] = 'PP-Transaction charge:';

$config['deposit_funds_via_payment_processor_card_number_lbl'] = 'Číslo karty:';
$config['deposit_funds_via_payment_processor_card_brand_lbl'] = 'Karta:';
$config['deposit_funds_via_payment_processor_country_code_lbl'] = 'ID země:';
$config['deposit_funds_via_payment_processor_card_bank_name_lbl'] = 'Název banky:';
$config['deposit_funds_via_payment_processor_card_type_lbl'] = 'Typ karty:';

$config['deposit_funds_via_payment_processor_bank_name_lbl'] = 'Název banky:';
$config['deposit_funds_via_payment_processor_bank_account_number_lbl'] = 'PP-Bank Account Number:'; //?
$config['deposit_funds_via_payment_processor_bank_owner_name_lbl'] = 'PP-Bank Owner Name:'; //?
// end

// Deposit funds via payment processor transaction statuses -- start
$config['deposit_funds_via_payment_processor_transaction_status_lbl'] = 'Status transakce:';
$config['deposit_funds_via_payment_processor_transaction_success_status_txt'] = 'provedeno';
$config['deposit_funds_via_payment_processor_transaction_failed_status_txt'] = 'neprovedeno';
$config['deposit_funds_via_payment_processor_transaction_cancelled_status_txt'] = 'zrušeno';
$config['deposit_funds_via_payment_processor_transaction_waiting_for_confirmation_status_txt'] = 'čeká na potvrzení';
// end
##########################################################################################################################################

################################################### Bank withdraw #########################################################################
$config['withdraw_funds_bank_heading'] = 'Při zadávání svých bankovních údajů, dbejte zvýšené opatrnosti, abychom mohli vaši žádost plynule zpracovat a v nejkratším možném čase vám odeslat požadovanou částku výběru. Nezodpovídáme za žádné vzniklé chyby nebo zpoždění, které by mohly vzniknout na základě nesprávně uvedených údajů. V případě chyb a nutného vynaloženého další úsilí našich administrátorů, mohou být účtovány další poplatky.';

// withdraw funds via bank transafer labels --start
$config['withdraw_funds_bank_account_owner_lbl'] = 'Majitel účtu';

$config['withdraw_funds_bank_account_number_lbl'] = 'Číslo účtu';

$config['withdraw_funds_bank_name_lbl'] = 'Název banky';

$config['withdraw_funds_bank_code_lbl'] = 'Kód banky';

$config['withdraw_funds_iban_lbl'] = 'IBAN';

$config['withdraw_funds_bank_bic_or_swift_code_lbl'] = 'BIC / SWIFT';

$config['withdraw_funds_bank_country_lbl'] = 'Stát';

$config['withdraw_funds_withdrawal_amount_lbl'] = 'Výše výběru';

$config['transaction_request_date'] = 'Transaction request date';

$config['withdraw_funds_withdraw_request_date_lbl'] = 'Datum žádosti';

$config['withdraw_funds_withdraw_request_status_lbl'] = 'Stav požadavku';

$config['withdraw_funds_bank_transaction_id_lbl'] = 'Bankovní ID';

$config['withdraw_funds_bank_transaction_date_lbl'] = 'Datum transakce';

$config['withdraw_funds_register_transaction_btn'] = 'Registrace transakce';

$config['withdraw_funds_bank_note_lbl'] = 'Poznámka';

$config['withdraw_funds_user_bank_variable_symbol_lbl'] = 'Variabilní symbol';
// end

$config['withdraw_funds_bank_note_txt'] = 'Jakmile vyplníte všechny potřebné údaje a zaregistrujete žádost o výběr, naši administrátoři vše překontrolují a po schválení odešlou zvolenou částku na váš bankovní účet. Při provedení platby, obdržíte ihned informaci v sekci Aktuální činnosti. Veškeré transakční poplatky účtované vaší bankou budou odečteny z celkové částky převodu.<br><br>Máte-li jakékoli dotazy nebo potřebujete jakékoli informace nebo podporu při zadávání, neváhejte nás kontaktovat.';

$config['withdraw_funds_bank_country'] = 'vybrat zemi';

$config['withdraw_funds_user_bank_variable_symbol_prefix_value'] = '221'; // this variable used to indicate site variable symbol prefix
$config['withdraw_funds_user_bank_variable_symbol_suffix_number_of_digits'] = 2; // This variable used to control generated variabil symbol suffix digit


// withdraw funds via bank transafer Error messages --start
$config['withdraw_funds_withdrawal_amount_required_error_message'] = 'výše výběru je povinné pole';

$config['withdraw_funds_withdrawal_amount_invalid_error_message'] = 'chybně zadaná částka';

$config['withdraw_funds_withdrawal_amount_greater_than_available_balance_error_message'] = 'nelze zadat vyšší částku, než máte na účtu';

$config['withdraw_funds_account_owner_required_error_message'] = 'majitel účtu je povinné pole';

$config['withdraw_funds_account_number_required_error_message'] = 'číslo účtu je povinné pole';

$config['withdraw_funds_bank_name_required_error_message'] = 'název banky je povinné pole';

$config['withdraw_funds_bank_code_required_error_message'] = 'kód banky je povinné pole';

$config['withdraw_funds_bank_iban_required_error_message'] = 'IBAN je povinné pole';

$config['withdraw_funds_bank_bic_swift_code_required_error_message'] = 'BIC / SWIFT je povinné pole';

$config['withdraw_funds_country_required_error_message'] = 'stát je povinné pole';
// end

// This is the confirmation messsage sent to user when he register direct bank transfer withdraw fund request
$config['withdraw_funds_direct_bank_transfer_confirmation_message'] = 'Vaše žádost byla přijata ke zpracování.';

$config['withdraw_funds_direct_bank_transfer_user_activity_log_message'] = 'Vaše žádost o výběr částky <span>{withdraw_amount} '.CURRENCY.'</span> byla přijata ke zpracování.';

$config['withdraw_funds_direct_bank_transfer_transaction_request_confirmed_by_admin_user_activity_log'] = 'Vaše žádost o výběr částky <span>{withdraw_amount} '.CURRENCY.'</span> byla schválena a částka byla odeslána na váš bankovní účet.';

$config['withdraw_funds_direct_bank_transfer_transaction_request_rejected_by_admin_user_activity_log'] = 'Vaše žádost o výběr částky <span>{withdraw_amount} '.CURRENCY.'</span> byla zrušena administrátorem a částka byla vrácena zpět na váš Travai účet.';

// withdraw funds via bank transafer transactions statuses --start
$config['withdraw_funds_direct_bank_transfer_admin_pending_confirmation_status_txt'] = 'čeká ke schválení';

$config['withdraw_funds_direct_bank_transfer_confirmed_by_admin_status_txt'] = 'částka odeslána';

$config['withdraw_funds_direct_bank_transfer_rejected_by_admin_status_txt'] = 'zrušeno administrátorem';

$config['withdraw_funds_direct_bank_transfer_added_by_admin_status_txt'] = 'potvrzeno (provedeno Travai)';
// end

#########################################################################################################################################


################################################### Transaction History #########################################################################
$config['transactions_history_search_btn_text'] = 'Hledat';

$config['transactions_history_clear_filter_btn_text'] = 'Zrušit filtry';

$config['transactions_history_from_lbl'] = 'od';

$config['transactions_history_to_lbl'] = 'do';

$config['transactions_history_from_date_prior_than_to_date_error_message'] = 'počáteční datum nemůže být vyšší než konečné';// This error message display to user when he try to select from date prior than to date for filter

$config['transactions_history_show_more_search_options_text'] = 'zobrazit více možností hledání <small>( + )</small>';
$config['transactions_history_hide_extra_search_options_text'] = 'zavřít více možností hledání <small>( - )</small>';

// This variable used to display filter options related to date filter
$config['transactions_history_date_filter_checkboxes_lbl'] = [
 'all' => 'Vše',
 'today' => 'Dnes',
 'this_month' => 'Tento měsíc',
 'last_month' => 'Minulý měsíc',
 'begining_of_year' => 'Od začátku roku',
 'custom_date' => 'Vlastní datum'
];

//This config are using for drop down "all" option on transaction history page 
$config['transactions_history_all_option_txt'] = 'vše';

// Transaction history filter dropdown option name and it's values -- start
$config['transactions_history_deposits_dropdown_option_name'] = 'Vklady';

$config['transactions_history_deposits_option_list'] = [
 'deposits_all' => 'vše', 'deposits_via_paypal' => 'PayPal','deposit_via_payment_card' => 'platba kartou', 'deposits_via_bank_transfer' => 'přímá platba', 'deposits_via_bank' => 'bankovní převod', 'deposits_none' => 'žádný'
];

$config['transactions_history_withdrawals_dropdown_option_name'] = 'Výběry';

$config['transactions_history_withdraws_option_list'] = [
 'withdraws_all' => 'vše', 'withdraws_via_paypal' => 'PayPal', 'withdraws_via_bank' => 'bankovní převod', 'referral_earnings_withdraws' => 'zisk z pozvání' , 'withdraws_none' => 'žádný'
];


$config['transactions_history_project_upgrades_dropdown_option_name'] = 'Vylepšení inzerátů';

$config['transactions_history_project_upgrades_option_list'] = [
 'project_upgrades_all' => 'vše', 'featured' => 'Zvýrazněný', 'urgent' => 'Urgentní', 'sealed' => 'Neveřejný' , 'hidden' => 'Skrytý', 'Featured_upgrade_prolongations' => 'Zvýrazněný - prodloužení platnosti' , 'Urgent_upgrade_prolongations' => 'Urgentní - prodloužení platnosti' , 'project_upgrades_none' => 'žádný'
];


$config['transactions_history_payments_on_projects_dropdown_option_name'] = 'Druh platby za projekty';

$config['transactions_history_payments_on_projects_option_list'] = [
 'payments_projects_all' => 'vše', 'payments_on_fixed_budget_projects' => 'fixní rozpočet', 'payments_on_hourly_rate_based_projects' => 'platba za hodinu', 'payments_projects_none' => 'žádný'
];

$config['transactions_history_payments_on_fulltime_jobs_dropdown_option_name'] = 'Druh mzdy';

$config['transactions_history_salary_payments_on_fulltime_jobs_option_list'] = ['salary_payments_on_fulltime_jobs_all' => 'vše', 'salary_payments_on_fulltime_jobs_none' => 'žádný'];

$config['transactions_history_received_payments_on_projects_dropdown_option_name'] = 'Druh přijaté platby za projekty';

$config['transactions_history_payments_received_on_projects_option_list'] = [
 'payments_received_projects_all' => 'vše' , 'payments_received_fixed_budget_projects' => 'fixní rozpočet', 'payments_received_hourly_rate_based_projects' => 'platba za hodinu', 'payments_received_projects_none' => 'žádný'
];

$config['transactions_history_salary_payments_received_on_fulltime_jobs_dropdown_option_name'] = 'Druh přijaté mzdy';

$config['transactions_history_salary_payments_received_on_fulltime_jobs_option_list'] = ['salary_payments_received_fulltime_jobs_all' => 'vše', 'salary_payments_received_fulltime_jobs_none' => 'žádný'];

$config['transactions_history_service_fees_payments_dropdown_option_name'] = 'Poplatky za platby';

$config['transactions_history_service_fees_payments_option_list'] = [
 'service_fees_payments_all' => 'vše', 'service_fees_payments_fixed_budget_projects' => 'fixní rozpočet', 'service_fees_payments_hourly_rate_based_projects' => 'platba za hodinu', 'service_fees_payments_fulltime_jobs' => 'mzdy', 'service_fees_payments_none' => 'žádný' 
];
// end
// Transaction history labels -- start
$config['transactions_history_project_upgrade_name_lbl'] = 'Typ vylepšení';

$config['transactions_history_purchase_on_project_name_lbl'] = 'Název inzerátu';

$config['transactions_history_project_upgrade_purchase_on_lbl'] = 'Částka';

$config['transactions_history_project_upgrade_purchase_date_lbl'] = 'Datum vylepšení';

$config['transactions_history_project_upgrade_payment_source_lbl'] = 'Původ platby';

$config['transactions_history_payment_reference_id_lbl'] = 'ID platby';


$config['transactions_history_project_name_lbl'] = 'Projekt';

$config['transactions_history_service_provider_name_lbl'] = 'Poskytovatel';

$config['transactions_history_project_owner_name_lbl'] = 'Zadavatel';


$config['transactions_history_fulltime_job_name_lbl'] = 'Pracovní pozice';

$config['transactions_history_employer_name_lbl'] = 'Zaměstnavatel';

$config['transactions_history_employee_name_lbl'] = 'Zaměstnanec';


$config['transactions_history_project_paid_amount_lbl'] = 'Uhrazená částka';

$config['transactions_history_project_received_payment_amount_lbl'] = 'Přijatá částka';

$config['transactions_history_project_paid_service_fees_amount_lbl'] = 'Servisní poplatek';

$config['transactions_history_payment_date_lbl'] = 'Datum platby';


$config['transactions_history_salary_paid_amount_lbl'] = 'Uhrazená částka';

$config['transactions_history_salary_received_amount_lbl'] = 'Přijatá částka';
// end

$config['transactions_history_project_id_txt'] = '<span class="finance_project_id">ID inzerátu:</span>';


$config['transactions_history_project_upgrade_purchase_included_membership_txt'] = 'Členství';

$config['transactions_history_project_upgrade_purchase_bonus_balance_txt'] = 'Bonus účet';

$config['transactions_history_project_upgrade_purchase_account_balance_txt'] = 'Travai účet';



$config['transactions_history_no_transaction_available_message'] = 'Momentálně nemáte žádnou historii plateb a transakcí.';

$config['transactions_history_search_no_results_returned_message'] = 'Filtrování neodpovídají žádné výsledky';

$config['transactions_history_loader_display_text'] = 'nahrávání...';


$config['transaction_history_project_upgrades_upgrade_types'] = [
 'featured' => 'Zvýrazněný',
 'urgent' => 'Urgentní',
 'sealed' => 'Neveřejný',
 'hidden' => 'Skrytý',
];
###############################################################################################################################################

######################################################### Invoices ###########################################################################
$config['invoices_tracking_heading'] = 'Faktury jsou generovány měsíčně. Vaše příští faktura bude generována {next_invoice_date}.';

$config['invoices_tracking_no_record'] = '<h4>Momentálně nemáte generovanou žádnou fakturu.<br>Každý měsíc se generuje 1 faktura.</h4><p>příští faktura bude vygenerována dne {next_invoice_date}</p>';

$config['invoices_tracking_all_years_option_name'] = 'Všechny roky';

$config['invoices_tracking_search_btn'] = 'Hledat';

$config['invoices_tracking_total_invoices_txt'] = 'Celkem faktur';

$config['invoices_tracking_invoice_number_txt'] = 'Číslo faktury';
$config['invoices_tracking_invoices_number_txt'] = 'Číslo faktury';

$config['invoices_tracking_invoice_generated_month_txt'] = 'Datum';

$config['invoices_tracking_invoice_amount_txt'] = 'Částka';


// Following variable is used in invoice format which will download as pdf
$config['invoice_format_form_lbl'] = 'dodavatel';

$config['invoice_format_to_lbl'] = 'odběratel';

$config['invoice_format_invoice_number_lbl'] = 'FAKTURA - DAŇOVÝ DOKLAD č.';

$config['invoice_format_invoice_date_lbl'] = 'Datum';

$config['invoice_format_table_heading_pos_txt'] = 'Č.';

$config['invoice_format_table_heading_description_txt'] = 'Popis';

$config['invoice_format_table_heading_unit_amount_txt'] = 'Částka';

$config['invoice_format_table_heading_amount_excluding_vat_txt'] = 'Částka (bez DPH)';

$config['invoice_format_table_heading_total_excluded_vat_txt'] = 'Celkem (bez DPH)';

$config['invoice_format_table_heading_vat_percentage_txt'] = 'DPH ({vat_percentage}%)';

$config['invoice_format_table_heading_total_amount_txt'] = 'Celkem';

$config['invoices_tracking_download_invoice_as_pdf_extension_txt'] = '.pdf';

$config['invoices_tracking_for_invoice_contact_support_txt'] = 'pro stažení faktury, kontaktujte podporu'; // this text will display when invoice value more exceed more then specified limit in settings

$config['invoice_download_file_prefix'] = 'trv_'; // this variable used to set download invoice file name prefix

$config['invoice_format_footer_txt'] = 'Děkujeme za používání Travai. Děkujeme za váše obchody.';

$config['invoice_format_service_fees_related_project_payment_txt'] = 'Servisní poplatek související s provedenou platbou pro {user_first_name_last_name_or_company_name} na projektu <a href="{project_url}">{project_title}</a>.';

$config['invoice_format_service_fees_related_salary_payment_txt'] = 'Servisní poplatek související s provedenou platbou pro {user_first_name_last_name_or_company_name} na pracovní pozici <a href="{project_url}">{project_title}</a>.';

$config['invoice_format_admin_dispute_moderation_fee_on_project_txt'] = 'Admin dispute moderation service fees related to <a href="{project_url}">Dispute id: {dispute_id}</a> on project <a href="{project_url}">{project_title}</a>';

$config['invoice_format_admin_dispute_moderation_fee_on_fulltime_project_txt'] = 'Admin dispute moderation service fees related to <a href="{project_url}">Dispute id: {dispute_id}</a> on fulltime project <a href="{project_url}">{project_title}</a>';

$config['invoice_format_upgrade_purchase_on_project_txt'] = 'Nákup <span class="upgrade_type">{project_upgrade_type}</span> vylepšení pro inzerát <a href="{project_url}">{project_title}</a>.';

$config['invoice_format_upgrade_purchase_on_fulltime_job_txt'] = 'Nákup <span class="upgrade_type">{project_upgrade_type}</span> vylepšení pro inzerát <a href="{project_url}">{project_title}</a>.';

$config['invoice_format_deposit_funds_via_paypal_transaction_fee_txt'] = 'Transaction fee related to deposit via PayPal Transaction ID {transaction_id} on date {transaction_date}';

$config['invoice_format_deposit_funds_via_payment_processor_transaction_fee_using_bank_transfer_txt'] = 'Transaction fee related to deposit funds via Payment processor Transaction ID {transaction_id} Bank Name - {bank_name} bank method - {payment_method} on date {transaction_date}';
$config['invoice_format_deposit_funds_via_payment_processor_transaction_fee_using_payment_card_transfer_txt'] = 'Transaction fee related to deposit funds via Payment processor Transaction ID {transaction_id} Payment Card used - {card_brand} on date {transaction_date}';
############################################################################################################################################

################################################################ Invoicing Details #########################################################

//Invoices Details
//page heading
$config['finance_headline_title_invoicing_details'] = 'Fakturační údaje';
//Meta Tag
$config['company_invoicing_details_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Fakturační údaje';
$config['company_app_invoicing_details_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Fakturační údaje';
//Description Meta Tag
$config['company_invoicing_details_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Fakturační údaje'; 
$config['company_app_invoicing_details_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Fakturační údaje'; 
//url
$config['finance_invoicing_details_page_url'] = 'fakturacni-udaje';

$config['company_invoicing_details_inital_view_title'] = 'Fakturační údaje';
$config['company_app_invoicing_details_inital_view_title'] = 'Fakturační údaje';

$config['company_invoicing_details_initial_view_content'] = 'V každoměsíčním cyklu obdržíte fakturu za čerpané placené Travai služby. Proto je dobré mít nastavené správné fakturační údaje, zvláště pokud jste plátci DPH. Pokračováním dále na stránce pečlivě všechny údaje vyplníte.';
$config['company_app_invoicing_details_initial_view_content'] = 'V každoměsíčním cyklu obdržíte fakturu za čerpané placené Travai služby. Proto je dobré mít nastavené správné fakturační údaje, zvláště pokud jste plátci DPH. Pokračováním dále na stránce pečlivě všechny údaje vyplníte.';

$config['company_invoicing_details_company_name_lbl'] = 'Název společnosti';
$config['company_app_invoicing_details_company_name_lbl'] = 'Jméno a Příjmení';

$config['company_invoicing_details_company_address_lbl'] = 'Adresa';
$config['company_app_invoicing_details_company_address_lbl'] = 'Adresa';

$config['company_invoicing_details_select_country_lbl'] = 'Země';
$config['company_app_invoicing_details_select_country_lbl'] = 'Země';

$config['company_invoicing_details_company_registration_number_lbl'] = 'IČ';
$config['company_app_invoicing_details_company_registration_number_lbl'] = 'IČ';

$config['company_invoicing_details_company_vat_number_lbl'] = 'DIČ';
$config['company_app_invoicing_details_company_vat_number_lbl'] = 'DIČ';

$config['company_invoicing_details_company_no_vat_registered_lbl'] = 'nejsme plátci DPH';
$config['company_app_invoicing_details_company_no_vat_registered_lbl'] = 'nejsem plátce DPH';


$config['company_invoicing_details_company_name_required_error_message'] = 'pole je povinné';
$config['company_app_invoicing_details_company_name_required_error_message'] = 'pole je povinné';

$config['company_invoicing_details_company_address_required_error_message'] = 'pole je povinné';
$config['company_app_invoicing_details_company_address_required_error_message'] = 'pole je povinné';

$config['company_invoicing_details_country_required_error_message'] = 'výběr je povinný';
$config['company_app_invoicing_details_country_required_error_message'] = 'výběr je povinný';

$config['company_invoicing_details_company_registration_number_required_error_message'] = 'pole je povinné';
$config['company_app_invoicing_details_company_registration_number_required_error_message'] = 'pole je povinné';

$config['company_invoicing_details_company_vat_number_required_error_message'] = 'pole je povinné';
$config['company_app_invoicing_details_company_vat_number_required_error_message'] = 'pole je povinné';


$config['company_invoicing_details_confirmation_modal_body_heading'] = 'Pokračováním uložíte fakturační údaje';
$config['company_app_invoicing_details_confirmation_modal_body_heading'] = 'Pokračováním uložíte fakturační údaje';

$config['company_invoicing_details_confirmation_modal_body'] = 'Vaše fakturační údaje budou uloženy a nebudete moct dále upravovat. V případě dalších úprav nebo jakékoli změny nás kontaktujte.';
$config['company_app_invoicing_details_confirmation_modal_body'] = 'Vaše fakturační údaje budou uloženy a nebudete moct dále upravovat. V případě dalších úprav nebo jakékoli změny nás kontaktujte.';

$config['company_invoicing_details_confirmation_modal_data_already_saved_error_message'] = 'fakturační údaje nelze uložit. zavřete okno, stránka bude aktualizována.';
$config['company_app_invoicing_details_confirmation_modal_data_already_saved_error_message'] = 'fakturační údaje nelze uložit. zavřete okno, stránka bude aktualizována.';

$config['company_invoicing_details_edit_view_top_heading_txt'] = '<b>Níže na stránce</b> pečlivě vyplňte všechny řádky. Dbejte na opravdovou správnost vložených údajů. Jakmile fakturační údaje uložíte, nebudete se moct vrátit zpět a opravit je.';
$config['company_app_invoicing_details_edit_view_top_heading_txt'] = '<b>Níže na stránce</b> pečlivě vyplňte všechny řádky. Dbejte na opravdovou správnost vložených údajů. Jakmile fakturační údaje uložíte, nebudete se moct vrátit zpět a opravit je.';

$config['company_invoicing_details_edit_view_bottom_heading_txt'] = '<b>Náhled:</b> Před uložením si pečlivě náhled fakturačních údajů překontrolujte. ';
$config['company_app_invoicing_details_edit_view_bottom_heading_txt'] = '<b>Náhled:</b> Před uložením si pečlivě náhled fakturačních údajů překontrolujte. ';

$config['company_invoicing_details_non_edit_view_top_heading_txt'] = '<b>Vaše vyplněné fakturační údaje</b> již nemůžete měnit. Pokud budete potřebovat provést jakoukoli změnu, kontaktujte nás emailem nebo telefonicky a spolu změnu provedeme.';
$config['company_app_invoicing_details_non_edit_view_top_heading_txt'] = '<b>Vaše vyplněné fakturační údaje</b> již nemůžete měnit. Pokud budete potřebovat provést jakoukoli změnu, kontaktujte nás emailem nebo telefonicky a spolu změnu provedeme.';

$config['company_invoicing_details_non_edit_view_bottom_heading_txt'] = '<b>Náhled:</b> Fakturační údaje stejně jako zde v náhledu budou použité v hlavičce faktur.';
$config['company_app_invoicing_details_non_edit_view_bottom_heading_txt'] = '<b>Náhled:</b> Fakturační údaje stejně jako zde v náhledu budou použité v hlavičce faktur.';
###########################################################################################################################################
//transactions history 
//page heading
$config['finance_headline_title_transactions_history'] = 'Historie transakcí';
//Meta Tag
$config['transactions_history_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Stránka historie transakcí';
//Description Meta Tag
$config['transactions_history_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Stránka historie transakcí';
//url
$config['finance_transactions_history_page_url'] = 'historie-transakci';


//Invoices
//page heading
$config['finance_headline_title_invoices'] = 'Faktury';
//Meta Tag
$config['invoices_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Stránka faktury';
//Description Meta Tag
$config['invoices_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Stránka faktury'; 
//url
$config['finance_invoices_page_url'] = 'faktury';

?>