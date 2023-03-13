<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
################ Meta Config Variables for signup page ###########
/* Filename: application\modules\signup\controllers\signup.php */
/* Controller: signup Method name: register */
$config['signup_page_title_meta_tag'] = 'Travai - registrace';

$config['signup_page_description_meta_tag'] = 'Travai.cz - portál služeb v České republice. Využijte všechny možnosti na trhu práce.';
################ Meta Config Variables for verify page ###########
/* Filename: application\modules\signup\views\signup_verify_page.php */
/* Controller: signup Method name: signup_verification */
$config['signup_verify_page_title_meta_tag'] = '{user_first_name_or_company_name} ověřte svoji emailovou adresu!';

$config['signup_verify_page_description_meta_tag'] = 'stránka ověření';

################ Meta Config Variables for signup successfull verification page ###########
/* Filename: application\modules\signup\views\successful_signup_verification.php */
/* Controller: signup Method name: signup_successful_verification */
$config['signup_confirmation_page_title_meta_tag'] = '{user_first_name_or_company_name}, děkujeme za ověření Vašeho účtu!';

$config['signup_confirmation_page_description_meta_tag'] = '{user_first_name_or_company_name}, děkujeme za ověření Vašeho účtu!';

################ Meta Config Variables for registration success confirmation page ###########
/* Filename: application\modules\signup\views\successful_registration_confirmation.php */
/* Controller: signup Method name: signup_confirmation_successful */
$config['signup_success_confirmation_page_title_meta_tag'] = 'Travai - úspěšná registrace';


$config['signup_success_confirmation_page_description_meta_tag'] = 'Travai - úspěšná registrace';

// config for signup page(signup form)
$config['signup_page_account_type_tab_as_personal_account_txt'] = 'Registrovat osobní účet';

$config['signup_page_account_type_tab_as_company_or_authorized_physical_person_account_txt'] = 'Registrovat firemní / OSVČ účet';

$config['signup_page_company_sub_account_type_tab_as_company_account_txt'] = 'firemní účet';
$config['signup_page_company_sub_account_type_tab_as_authorized_physical_person_account_txt'] = 'OSVČ účet';


$config['signup_page_signup_form_first_name_txt'] = 'Jméno';
$config['signup_page_signup_form_last_name_txt'] = 'Příjmení';
$config['signup_page_signup_form_company_name_txt'] = 'Název společnosti';
$config['signup_page_signup_form_male_txt'] = 'Muž';
$config['signup_page_signup_form_female_txt'] = 'Žena';
$config['signup_page_signup_form_email_address_txt'] = 'E-mailová adresa';
$config['signup_page_signup_form_profile_name_txt'] = 'Profilové jméno';
$config['signup_page_signup_form_password_txt'] = 'Heslo';
$config['signup_page_signup_form_i_have_referal_code_txt'] = 'Mám referenční kód';
$config['signup_page_signup_form_already_have_an_account_txt'] = 'Máte již registraci?';

$config['signup_page_signup_form_marketing_agreement_disclaimer_txt'] = 'Souhlasím se zasíláním marketingových informací';

$config['signup_page_signup_form_disclaimer_txt'] = 'Registrací potvrzujete, že souhlasíte s <a href="{terms_and_conditions_page_url}" target="blank">Obchodními podmínkami</a> a <a href="{privacy_policy_page_url}" target="blank">Zásadami ochrany osobních údajů</a>';


$config['signup_page_heading'] = 'Řekněte nám, jak chcete být zaregistrováni';


################ Defined the signup form validation regarding user signup form start here
/* Filename: application\modules\signup\controllers\signup.php */
/* Controller: signup Method name: index */
$config['first_name_validation_signup_message'] = 'jméno je povinné';

$config['last_name_validation_signup_message'] = 'příjmení je povinné';

$config['company_name_validation_signup_message'] = 'název společnosti je povinný';

$config['gender_validation_signup_message'] = 'pohlaví je povinné';

$config['email_validation_signup_message'] = 'e-mailová adresa je povinná';

$config['email_address_already_exists_signup_message'] = 'použitá e-mailová adresa je neplatná, použijte jinou';

$config['valid_email_validation_signup_message'] = 'zadejte platnou e-mailovou adresu';

$config['only_letters_validation_signup_message'] = 'zadejte pouze písmena a tečku';


$config['profile_name_validation_signup_message'] = 'název profilu je povinný';

$config['profile_name_signup_message_tooltip'] = "název profilu může obsahovat pouze čísla, písmena a tyto znaky '.', '-' a '_'. všechny ostatní symboly jsou neplatné.";

$config['password_validation_signup_message'] = 'heslo je povinné';


// config for successful signup confirmation page
$config['successful_signup_confirmation_page_heading_txt'] = 'Registrace byla úspěšná!';
$config['successful_signup_confirmation_page_sub_heading_txt'] = 'Vážený uživateli, ověřte vaši emailovou adresu a pokračujte v aktivaci svého účtu podle instrukcí.';

$config['successful_signup_confirmation_page_message_txt'] = '<p>Pamatujte! Máte <strong>{newly_registered_account_confirmation_expiration_time} hodin</strong> na dokončení aktivace vašeho účtu. Čas pro aktivaci účtu vyprší {newly_registered_account_confirmation_expiration_date}.<p>Pokud vám potvrzení nedorazilo, zkontrolujte svůj SPAM koš.</p>
<p>V případě jakýchkoliv dotazů nás kontaktujte na telefonním čísle <span>(+420) 515 910 910</span> nebo emailem na <a>podpora@travai.cz</a>.</p>';


///// config for signup verify page
$config['signup_verify_page_heading_txt'] = '{user_first_name_last_name_or_company_name}, ověřte svoji emailovou adresu!';


$config['signup_verify_page_sub_heading_txt_male'] = '<p>Zaregistroval jste účet na Travai dne <span class="email-add">{newly_registered_account_registration_date}</span> a stále jste neověřil vaši emailovou adresu, kterou jste použil při registraci - <span class="email-add">{newly_registered_account_user_email}.</span></p><p>Upozorňujeme, že všechny účty, které nebyly ověřeny během prvních <span class="email-add">{newly_registered_account_confirmation_expiration_time}
 hodin</span> po registraci, jsou z naší databáze odstraněny. <span id="account_expiration_text">Stále máte <span class="email-add" id="account_expiration_countdown"></span> pro ověření vaší emailové adresy a aktivaci svého Travai účtu.</span></p>';

$config['signup_verify_page_sub_heading_txt_female'] = '<p>Zaregistrovala jste účet na Travai dne <span class="email-add">{newly_registered_account_registration_date}</span> a stále jste neověřila vaši emailovou adresu, kterou jste použila při registraci - <span class="email-add">{newly_registered_account_user_email}</span>.</p><p>Upozorňujeme, že všechny účty, které nebyly ověřeny během prvních <span class="email-add">{newly_registered_account_confirmation_expiration_time}
hodin</span> po registraci, jsou z naší databáze odstraněny. <span id="account_expiration_text">Stále máte <span class="email-add" id="account_expiration_countdown"></span> pro ověření vaší emailové adresy a aktivaci svého Travai účtu.</span></p>';
 
 
$config['signup_verify_page_sub_heading_txt_app_male'] = '<p>Zaregistroval jste účet na Travai dne <span class="email-add">{newly_registered_account_registration_date}</span> a stále jste neověřil vaši emailovou adresu, kterou jste použil při registraci - <span class="email-add">{newly_registered_account_user_email}</span>.</p><p>Upozorňujeme, že všechny účty, které nebyly ověřeny během prvních <span class="email-add">{newly_registered_account_confirmation_expiration_time}
hodin</span> po registraci, jsou z naší databáze odstraněny. <span id="account_expiration_text">Stále máte <span class="email-add" id="account_expiration_countdown"></span> pro ověření vaší emailové adresy a aktivaci svého Travai účtu.</span></p>';

$config['signup_verify_page_sub_heading_txt_app_female'] = '<p>Zaregistrovala jste účet na Travai dne <span class="email-add">{newly_registered_account_registration_date}</span> a stále jste neověřila vaši emailovou adresu, kterou jste použila při registraci - <span class="email-add">{newly_registered_account_user_email}</span>.</p><p>Upozorňujeme, že všechny účty, které nebyly ověřeny během prvních <span class="email-add">{newly_registered_account_confirmation_expiration_time}
hodin</span> po registraci, jsou z naší databáze odstraněny. <span id="account_expiration_text">Stále máte <span class="email-add" id="account_expiration_countdown"></span> pro ověření vaší emailové adresy a aktivaci svého Travai účtu.</span></p>';
 

$config['signup_verify_page_sub_heading_txt_company'] = '<p>Zaregistrovali jste účet na Travai dne <span class="email-add">{newly_registered_account_registration_date}</span> a stále jste neověřili vaši emailovou adresu, kterou jste použili při registraci - <span class="email-add">{newly_registered_account_user_email}</span>.</p><p>Upozorňujeme, že všechny účty, které nebyly ověřeny během prvních <span class="email-add">{newly_registered_account_confirmation_expiration_time}
 hodin</span> po registraci, jsou z naší databáze odstraněny. <span id="account_expiration_text">Stále máte <span class="email-add" id="account_expiration_countdown"></span> pro ověření vaší emailové adresy a aktivaci svého Travai účtu.</span></p>';

############################################################################################################
$config['signup_verify_page_newly_registered_user_valid_verification_code_and_send_manual_request_code_option_available_message_txt'] = '<p>Zkontrolujte svoji emailovou schránku a hledejte zprávu s předmětem "{registartion_email_subject}", případně hledejte také ve složce Spam.</p>

<p>Klikněte v emailu na aktivační adresu URL (nebo zkopírujte adresu URL do okna prohlížeče), případně zkopírujte a vložte kód z emailu do pole níže na stránce.</p>

<p>Pokud nemáte náš email, klikněte na tlačítko "Generovat ověřovací kód" níže na této stránce a nový ověřovací kód bude znovu odeslán na vaši emailovou adresu. Zkontrolujte v doručené poště aktivační email s předmětem "Váš nový ověřovací kód".

<p class="email-add">Ověřte svůj kód a aktivujte svůj účet.</p>';


########################################
// This config is using when user currently verification code is expired but also have option to send manual request code.
$config['signup_verify_page_newly_registered_user_verification_code_expired_and_send_manual_request_code_option_available_message_txt'] = '<p>Platnost vašeho ověřovacího kódu vypršela. Pro zaslání nového ověřovacího kódu, klikněte na tlačítko "Generovat ověřovací kód" a nový ověřovací kód vám bude znovu odeslán.</p>

<p>Obdržíte email s předmětem "{send_manual_verification_code_email_subject}", ve zprávě klikněte na aktivační adresu URL nebo zkopírujte adresu URL do okna prohlížeče.</p>';


// reminder text when verification code is expired and user have option to send _manual request code
$config['signup_verify_page_newly_registered_user_verification_code_expired_and_send_manual_request_code_option_available_reminder_message_txt'] = '<p><b>Pamatujte:</b> automaticky generujeme a posíláme nový aktivační kód každých {signup_automatic_send_reminder_interval} hodin. <span id="next_auto_request_text">Další nový ověřovací kód bude automaticky generován za <span id="next_auto_request"></span>.</span></p>';

############################################
// this config is using when user generated manually code, code is still valid, user has also option to regenerate a new code
$config['signup_verify_page_newly_registered_user_generate_verification_code_by_manualy_request_valid_verification_code_and_send_manual_request_code_option_available_message_txt'] = '<p>Ručně jste generovali nový ověřovací kód před <span id="manual_request"></span>. Obdrželi jste email s předmětem "{send_manual_verification_code_email_subject}", klikněte na aktivační adresu URL (nebo zkopírujte adresu URL do okna prohlížeče), případně zkopírujte a vložte kód z emailu do pole níže na stránce.</p><p class="email-add">Ověřte svůj kód a aktivujte svůj účet.</p>';

######################################################################
// this config is using when user generated manually code, code is still valid, user has noption to regenerate a new code
$config['signup_verify_page_newly_registered_user_generate_verification_code_by_manualy_request_valid_verification_code_and_send_manual_request_code_option_not_available_message_txt'] = '
<p>Ručně jste generovali nový ověřovací kód před <span id="manual_request"></span>. Obdrželi jste email s předmětem "{send_manual_verification_code_email_subject}", klikněte na aktivační adresu URL (nebo zkopírujte adresu URL do okna prohlížeče), případně zkopírujte a vložte kód z emailu do pole níže na stránce.</p><p class="email-add">Ověřte svůj kód a aktivujte svůj účet.</p>';

#####################################################################
// This config is using when user currently verification code is valid(recieved code automatcally) but also have option to send manual request code.
$config['signup_verify_page_newly_registered_user_received_verification_code_by_automatically_cron_valid_verification_code_and_send_manual_request_code_option_available_message_txt'] = '<p>Nový ověřovací kód byl generován před <span id="automatic_request"></span>.</p><p>Obdrželi jste email s předmětem "{send_reminder_verification_code_email_subject}", klikněte na aktivační adresu URL (nebo zkopírujte adresu URL do okna prohlížeče), případně zkopírujte a vložte kód z emailu do pole níže na stránce.</p><p class="email-add">Ověřte svůj kód a aktivujte svůj účet.</p>';

###########################################################################
// This config is using when user currently verification code is expired he dont have option to send manual request code also not recived new varificode by cron.
$config['signup_verify_page_newly_registered_user_verification_code_expired_not_received_verification_code_by_automatically_cron_and_send_manual_request_code_option_not_available_manual_request_code_option_reminder_message_txt'] = '
<p>Ručně jste generovali nový ověřovací kód {verification_code_manual_request_date} {verification_code_manual_request_time}. <span id="next_manual_request_text">Další kód je možné vygenerovat za <span id="next_manual_request"></span>{next_request_generated_time}.</span></p>';


$config['signup_verify_page_newly_registered_user_verification_code_expired_not_received_verification_code_by_automatically_cron_and_send_manual_request_code_option_not_available_message_txt'] = '<p><b>Pamatujte:</b> automaticky generujeme a posíláme nový aktivační kód každých {signup_automatic_send_reminder_to_unverified_user_set_interval} hodin.</p>';


// reminder message for automatic reminder
$config['signup_verify_page_newly_registered_user_verification_code_expired_not_received_verification_code_by_automatically_cron_and_send_manual_request_code_option_not_available_automatically_cron_reminder_message_txt'] = '<p id="next_automatic_request_text" class="adjustTop">Další nový kód bude automaticky generován za <span><span id="next_automatic_request"></span>{automatic_request}.</span></p>';


#######################################################################################

// These config are using for button text on signup verification page
$config['signup_verify_page_code_txt'] = 'Kód';
$config['signup_verify_page_validate_code_button_txt'] = 'Ověřit kód';
$config['signup_verify_page_generate_verification_code_button_txt'] = 'Generovat ověřovací kód';



///// config for successful signup verification page
$config['successful_signup_verification_page_heading'] = 'Účet je aktivován!';
$config['successful_signup_verification_page_message_txt'] = '<p>{user_first_name_or_company_name}, děkujeme za ověření emailové adresy {newly_registered_account_user_email}.</p><p>Váš Travai účet je aktivován. Nyní můžete přejít na <a class="redirection">hlavní stránku účtu</a>.</p>';

################ Defined the account verification message store in user log and user display activity log
$config['user_account_verification_success_message_for_log'] = 'Vítejte na Travai, účet je schválený!';

$config['user_account_verification_success_message_for_display_activity'] = 'Za registraci získáváte 1 000 Kč jako Bonus. Tento bonus lze použít pro nákupy na portálu.';

/*
|--------------------------------------------------------------------------
| Url Variables 
|--------------------------------------------------------------------------
| 
*/
################ Url Routing Variables for signup page ###########
/* Filename: application\modules\signup\controllers\signup.php */

$config['referrer_page_url'] = 'strnk';

$config['signup_page_url'] = 'registrace';

$config['signup_confirmation_page_url'] = 'registrace/dokonceni';

$config['signup_activate_page_url'] = 'registrace/aktivace';

$config['signup_page_success_parameter'] = 'overeni';

$config['signup_page_code_parameter'] = 'kod';

$config['signup_verify_redirection_page_url'] = 'signup/verify_redirection';

$config['signup_verified_page_url'] = 'registrace/overeno';

$config['signup_verified_activation_code_wrong_message'] = 'aktivační kód je neplatný';

$config['signup_verified_activation_code_new_message'] = 'nový ověřovací kód byl odeslán';

?>