<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Meta Variables 
|--------------------------------------------------------------------------
| 
*/

################ Meta Config Variables for login page ###########
/* Filename: application\modules\login\controllers\login.php */
/* Controller: login Method name: index */
$config['signin_page_title_meta_tag'] = 'Travai - přihlášení';

$config['signin_page_description_meta_tag'] = 'Travai - přihlášení';

################ Url Routing Variables for login page ###########
/* Filename: application\modules\login\controllers\login.php */
$config['signin_page_url'] = 'prihlaseni';

################ Url Routing Variables for logout ###########
/* Filename: application\modules\user\controllers\user.php */
$config['logout_page_url'] = 'odhlaseni';

################ Defined the login form validation regarding user login form start here
/* Filename: application\modules\login\models\login.php */
/* Controller: login Method name: login */
$config['signin_page_signin_to_account_heading_txt'] = 'Přihlašte se ke svému účtu';
$config['signin_page_email_address_label_txt'] = 'E-mailová adresa';
$config['signin_page_password_label_txt'] = 'Heslo';
$config['signin_page_forget_password_label_txt'] = 'Zapomněli jste heslo?';
$config['signin_page_reset_password_txt'] = 'Resetovat heslo';
$config['signin_page_signin_with_facebook_account_btn_txt'] = 'Přihlásit přes Facebook';
$config['signin_page_signin_with_linkedin_account_btn_txt'] = 'Přihlásit přes LinkedIn';
$config['signin_page_disclaimer_txt'] = 'pokračováním v používání našich služeb souhlasíte s <a href="{terms_and_conditions_page_url}" target="_blank">Obchodními podmínkami</a> a <a href="{privacy_policy_page_url}" target="_blank">Zásadami ochrany osobních údajů</a>';
$config['signin_page_user_do_not_have_account_heading_txt'] = 'Nemátě ještě travai účet?';


/**
 * Error message definitions
 */
$config['enter_email_validation_signin_message'] = 'zadejte email';

$config['enter_email_validation_signin_message'] = 'zadejte platnou e-mailovou adresu';

$config['enter_password_validation_signin_message'] = 'zadejte heslo';

$config['login_failed_message'] = 'přihlášení se nezdařilo! chybný e-mail, heslo nebo profil neexistuje';

//error messages displayed for social login
$config['linkedin_social_login_failed_message'] = 'This account does not exist or you did not link your LinkedIn account.';
$config['facebook_social_login_failed_message'] = 'This account does not exist or you did not link your Facebook account.';

?>