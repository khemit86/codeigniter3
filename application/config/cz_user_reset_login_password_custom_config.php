<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
################ Meta Config Variables for signup page ###########
/* Filename: application\modules\forgot_password\controllers\Forgot_password.php */
/* Controller: Forgotpassword Method name: index */

$config['forgot_password_page_title_meta_tag'] = 'Zapomenuté heslo - Travai.cz';

$config['forgot_password_page_description_meta_tag'] = 'Zapomenuté heslo - Obnovte si heslo k vašemu Travai účtu.';

/* Filename: application\modules\forgot_password\controllers\Forgot_password.php */
/* Controller: Forgotpassword Method name: send_password_reset_confirmation */
$config['forgot_password_reset_confirmation_page_title_meta_tag'] = '{user_first_name_or_company_name}, obnovení hesla bylo úspěšně odesláno na {email}';

$config['forgot_password_reset_confirmation_page_description_meta_tag'] = 'Obnovení hesla pro Travai účet';


// config for password reset confirmation page
$config['password_reset_confirmation_page_heading_txt'] = '<p><strong>{user_first_name_or_company_name}</strong>, odkaz pro resetování hesla byl odeslán před <span id="password_request_time_counter"></span> na emailovou adresu <strong>{user_email}</strong>.</p>';

$config['password_reset_confirmation_page_sub_heading_txt'] = '<p>Zkontrolujte email a postupujte podle pokynů. Pokud náš email nedorazil, podívejte se do složky Spam.</p><p>Žádost pro obnovení hesla může být provedena každé <span>{next_password_reset_request_interval}.</span></p><p>Zbývající čas <span id="next_password_request_countdown"></span> do další možné žádosti.</p>';

$config['password_reset_confirmation_page_continue_click_here_txt'] = 'Pro pokračování <a href="{signin_page_url}">klikněte zde...</a>';


/* Filename: application\modules\forgot_password\controllers\Forgot_password.php */
/* Controller: Forgotpassword Method name: successfull_password_reset */
$config['successful_password_reset_page_title_meta_tag'] = '{user_first_name_or_company_name}, Úspěšné obnovení';

$config['successful_password_reset_page_description_meta_tag'] = 'Úspěšné obnovení hesla k Travai účtu.';

$config['successful_password_reset_page_heading_txt'] = '<strong>{user_first_name_or_company_name}, Vaše heslo bylo úspěšně změněno.</strong>';
$config['successful_password_reset_page_sub_heading_txt'] = 'Právě jsme Vám zaslali potvrzení o úspěšném obnovení Vašeho hesla. Pokud jste obdrželi tento e-mail a Vaše heslo nefunguje, napište na podpora@travai.cz nebo volejte (+420) 515 910 910 pro naši asistenci.';
$config['successful_password_reset_page_continue_click_here_txt'] = 'Pro pokračování <a href="{signin_page_url}">klikněte zde...</a>';

################ Defined the recover password form validation start here
/* Filename: application\modules\forgot_password\controllers\Forgot_password.php */
/* Controller: signup Method name: recover_password */
$config['email_validation_forgot_password_message'] = 'e-mailová adresa je povinné pole';

$config['email_address_not_exists_forgot_password_message'] = 'použitá e-mailová adresa je neplatná';

$config['valid_email_validation_forgot_password_message'] = 'zadejte prosím platnou e-mailovou adresu';
################ Defined the recover password form validation end here

################ Defined the reset password form validation start here
/* Filename: application\modules\forgot_password\controllers\Forgot_password.php */
/* Controller: Forgot_password Method name: reset_password */
$config['password_validation_reset_password_message'] = 'heslo je povinné pole';

$config['confirm_password_validation_reset_password_message'] = 'potvrzení hesla je povinné pole';

$config['confirm_password_characters_min_length_validation_reset_password_message'] = 'heslo a potvrzení hesla musí být stejné';

$config['password_and_confirm_password_equal_validation_reset_password_message'] = 'heslo a potvrzení hesla musí být stejné';

$config['password_reset_link_expired_validation_message'] = 'platnost odkazu vypršela';

$config['password_reset_invalid_url_validation_message'] = 'neplatná url adresa';

################ Defined the recover password form validation end here
/*
|---------------------------------------------------------------------------------------------------
| Time Config Variables for Forgot Password Process(In minutes)
|---------------------------------------------------------------------------------------------------
|
*/
################ Url Routing Variables for forgot password ###########
/* Filename: application\modules\forgot_password\controllers\Forgot_password.php */
$config['reset_login_password_page_url'] = 'resetovani-hesla';

//$config['reset_password_page_url'] = 'reset-hesla';

$config['forgot_password_send_reset_confirmation_page_url'] = 'zadost-resetovani';


$config['forgot_password_successful_password_reset_page_url'] = 'potvrzeni-reset-hesla';

$config['forgot_password_page_success_parameter'] = 'potvrzeni';

$config['reset_password_page_token_parameter'] = 'token';

// activity log message when user successfully reset his password
$config['reset_login_password_email_user_activity_log_displayed_message'] = 'Heslo bylo resetováno.';

?>