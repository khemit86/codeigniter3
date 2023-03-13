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

$config['signin_page_title_meta_tag'] = 'Signin Page Title Meta Tag';
$config['signin_page_description_meta_tag'] = 'Signin Page Description Meta Tag - will be translated in each language';

################ Url Routing Variables for login page ###########
/* Filename: application\modules\login\controllers\login.php */
$config['signin_page_url'] = 'signin';

################ Url Routing Variables for logout ###########
/* Filename: application\modules\user\controllers\user.php */
$config['logout_page_url'] = 'logout';

################ Defined the login form validation regarding user login form start here
/* Filename: application\modules\login\models\login.php */
/* Controller: login Method name: login */
$config['signin_page_signin_to_account_heading_txt'] = 'Login to your account';
$config['signin_page_email_address_label_txt'] = 'E-mail address';
$config['signin_page_password_label_txt'] = 'Password';
$config['signin_page_forget_password_label_txt'] = 'Forgot password?';
$config['signin_page_reset_password_txt'] = 'Reset it';
$config['signin_page_signin_with_facebook_account_btn_txt'] = 'Login with Facebook account';
$config['signin_page_signin_with_linkedin_account_btn_txt'] = 'Login with LinkedIn account';
$config['signin_page_disclaimer_txt'] = 'by using our services you agree with our <a href="{terms_and_conditions_page_url}" target="_blank">Obchodními podmínkami</a> and <a href="{privacy_policy_page_url}" target="_blank">Zásadami ochrany osobních údajů</a>';
$config['signin_page_user_do_not_have_account_heading_txt'] = 'Donot have an account yet?';


/**
 * Error message definitions
 */
$config['enter_email_validation_signin_message'] = 'Enter email.';
$config['invalid_email_validation_signin_message'] = 'Enter valid email.';
$config['enter_password_validation_signin_message'] = 'Enter Password.';

$config['login_failed_message'] = 'Login failed! Wrong email, password or profile does not exist';

//error messages displayed for social login
$config['linkedin_social_login_failed_message'] = 'This account does not exist or you did not link your LinkedIn account.';
$config['facebook_social_login_failed_message'] = 'This account does not exist or you did not link your Facebook account.';

?>