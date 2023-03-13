<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
################ Meta Config Variables for signup page ###########
/* Filename: application\modules\forgot_password\controllers\Forgot_password.php */
/* Controller: Forgotpassword Method name: index */

//$config['forgot_password_page_title_meta_tag'] = 'Forgot Password Title Meta Tag - will be translated in each language (Manish)';
//$config['forgot_password_page_description_meta_tag'] = 'Forgot Password Meta Tag - will be translated in each language(Manish)';
$config['forgot_password_page_title_meta_tag'] = 'Forgot Password page Title Meta Tag - Zapomenuté heslo';
$config['forgot_password_page_description_meta_tag'] = 'Forgot Password page description meta Tag - Zapomenuté heslo - Obnovte si heslo k vašemu Travai účtu';

/* Filename: application\modules\forgot_password\controllers\Forgot_password.php */
/* Controller: Forgotpassword Method name: send_password_reset_confirmation */
$config['forgot_password_reset_confirmation_page_title_meta_tag'] = '{user_first_name_or_company_name}, reset password link was successfully sent to {email}';
$config['forgot_password_reset_confirmation_page_description_meta_tag'] = 'Forgot Password reset confirmation page description Meta Tag';


// config for password reset confirmation page
$config['password_reset_confirmation_page_heading_txt'] = '<p>EN - <small>{user_first_name_or_company_name},</small> odkaz pro resetování hesla byl odeslán před <span id="password_request_time_counter"></span> na emailovou adresu <strong>{user_email}</strong>.</p>';

$config['password_reset_confirmation_page_sub_heading_txt'] = '<p>EN - Zkontrolujte email a postupujte podle pokynů. Pokud náš email nedorazil, podívejte se do složky Spam.</p><p>Žádost pro obnovení hesla může být provedena každé <span>{next_password_reset_request_interval}.</span></p><p>Zbývající čas <span id="next_password_request_countdown"></span> do další možné žádosti.</p>';

$config['password_reset_confirmation_page_continue_click_here_txt'] = 'EN - Pro pokračování <a href="{signin_page_url}">klikněte zde...</a>';


/* Filename: application\modules\forgot_password\controllers\Forgot_password.php */
/* Controller: Forgotpassword Method name: successfull_password_reset */
$config['successful_password_reset_page_title_meta_tag'] = '{user_first_name_or_company_name}, Successfull reset Úspěšné obnovení';
$config['successful_password_reset_page_description_meta_tag'] = 'Úspěšné obnovení hesla k účtu.';
$config['successful_password_reset_page_heading_txt'] = 'En-<strong>{user_first_name_or_company_name}, Vaše heslo bylo úspěšně změněno.</strong>';
$config['successful_password_reset_page_sub_heading_txt'] = 'En-Právě jsme Vám zaslali potvrzení o úspěšném obnovení Vašeho hesla. Pokud jste obdrželi tento e-mail a Vaše heslo nefunguje, napište na podpora@travai.cz nebo volejte +420 515 910 910 pro naši asistenci.';
$config['successful_password_reset_page_continue_click_here_txt'] = 'EN - Pro pokračování <a href="{signin_page_url}">klikněte zde...</a>';



################ Defined the recover password form validation start here
/* Filename: application\modules\forgot_password\controllers\Forgot_password.php */
/* Controller: signup Method name: recover_password */
$config['email_validation_forgot_password_message'] = 'Email address is a required field.';
$config['email_address_not_exists_forgot_password_message'] = 'Email address you use is invalid. Please enter a different one.';
$config['valid_email_validation_forgot_password_message'] = 'Please enter a valid email address.';
################ Defined the recover password form validation end here


################ Defined the reset password form validation start here
/* Filename: application\modules\forgot_password\controllers\Forgot_password.php */
/* Controller: Forgot_password Method name: reset_password */
$config['password_validation_reset_password_message'] = 'Password is a required field.';
$config['confirm_password_validation_reset_password_message'] = 'Confirm password is a required field.';


$config['confirm_password_characters_min_length_validation_reset_password_message'] = 'Password and confirm password MUST match.';
$config['password_and_confirm_password_equal_validation_reset_password_message'] = 'Password and confirm password should be match.';
 
$config['password_reset_link_expired_validation_message'] = 'this link has expired.';
$config['password_reset_invalid_url_validation_message'] = 'Invalid url.';

################ Defined the recover password form validation end here

################ Url Routing Variables for forgot password ###########
/* Filename: application\modules\forgot_password\controllers\Forgot_password.php */
$config['reset_login_password_page_url'] = 'reset-login-password';
//$config['reset_password_page_url'] = 'reset-password';

$config['forgot_password_send_reset_confirmation_page_url'] = 'send-password-reset-confirmation';

$config['forgot_password_successful_password_reset_page_url'] = 'successful-password-reset';

$config['forgot_password_page_success_parameter'] = 'success';

$config['reset_password_page_token_parameter'] = 'token';

// activity log message when user successfully reset his password
$config['reset_login_password_email_user_activity_log_displayed_message'] = 'you have successfully reset your login password';

?>