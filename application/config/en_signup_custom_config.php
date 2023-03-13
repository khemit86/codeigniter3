<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
################ Meta Config Variables for signup page ###########
/* Filename: application\modules\signup\controllers\signup.php */
/* Controller: signup Method name: register */

$config['signup_page_title_meta_tag'] = 'Signup Title Meta Tag - portál služeb v České republice. Využijte všechny možnosti na trhu práce';
$config['signup_page_description_meta_tag'] = 'Signup Description Meta Tag - portál služeb v České republice. Využijte všechny možnosti na trhu práce';

################ Meta Config Variables for verify page ###########
/* Filename: application\modules\signup\views\signup_verify_page.php */
/* Controller: signup Method name: signup_verification */
$config['signup_verify_page_title_meta_tag'] = '{user_first_name_or_company_name} please verify/confirm your email address!';
$config['signup_verify_page_description_meta_tag'] = 'Signup verify page Description Meta Tag - portál služeb v České republice. Využijte všechny možnosti na trhu práce';

################ Meta Config Variables for signup successfull verification page ###########
/* Filename: application\modules\signup\views\successful_signup_verification.php */
/* Controller: signup Method name: signup_successful_verification */

$config['signup_confirmation_page_title_meta_tag'] = '{user_first_name_or_company_name}, thank you for verifying your account!';
$config['signup_confirmation_page_description_meta_tag'] = 'Signup Description Meta Tag - {user_first_name_or_company_name} - Využijte všechny možnosti na trhu práce';

################ Meta Config Variables for registration success confirmation page ###########
/* Filename: application\modules\signup\views\successful_registration_confirmation.php */
/* Controller: signup Method name: signup_confirmation_successful */
$config['signup_success_confirmation_page_title_meta_tag'] = 'Signup Title Meta Tag - úspěšná registrace';
$config['signup_success_confirmation_page_description_meta_tag'] = 'Signup Description Meta Tag - úspěšná registrace';


// config for signup page(signup form) label -- start
$config['signup_page_account_type_tab_as_personal_account_txt'] = 'Personal account';
$config['signup_page_account_type_tab_as_company_or_authorized_physical_person_account_txt'] = 'Company account';
$config['signup_page_company_sub_account_type_tab_as_company_account_txt'] = 'Register company';
$config['signup_page_company_sub_account_type_tab_as_authorized_physical_person_account_txt'] = 'Register authorized person';
$config['signup_page_signup_form_first_name_txt'] = 'Firstname';
$config['signup_page_signup_form_last_name_txt'] = 'Lastname';
$config['signup_page_signup_form_company_name_txt'] = 'Company name';
$config['signup_page_signup_form_male_txt'] = 'Male';
$config['signup_page_signup_form_female_txt'] = 'Female';
$config['signup_page_signup_form_email_address_txt'] = 'E-mail address';
$config['signup_page_signup_form_profile_name_txt'] = 'Profile name';
$config['signup_page_signup_form_password_txt'] = 'Password';
$config['signup_page_signup_form_i_have_referal_code_txt'] = 'I have a referal code';

$config['signup_page_signup_form_already_have_an_account_txt'] = 'Already have an account?';

$config['signup_page_signup_form_marketing_agreement_disclaimer_txt'] = 'Souhlasím se zasíláním marketingových informací';

$config['signup_page_signup_form_disclaimer_txt'] = 'by registering you confirm that agree with <a href="{terms_and_conditions_page_url}">Obchodními podmínkami</a> and <a href="{privacy_policy_page_url}">Zásadami ochrany osobních údajů</a>';

$config['signup_page_heading'] = 'Tell what you whant to register on <strong style="color: #132c78">xxxxxx.xxx</strong>';
// end



// config for successful signup confirmation page
$config['successful_signup_confirmation_page_heading_txt'] = 'EN - Registrace byla úspěšná!';
$config['successful_signup_confirmation_page_sub_heading_txt'] = 'EN - Vážený uživateli, ověřte vaši emailovou adresu a pokračujte v aktivaci svého účtu podle instrukcí.';

$config['successful_signup_confirmation_page_message_txt'] = '<p>EN - Pamatujte! Máte <strong>{newly_registered_account_confirmation_expiration_time} hodin</strong> na dokončení aktivace Vašeho účtu. Vaše expirace účtu vyprší {newly_registered_account_confirmation_expiration_date}.<p>Pokud Vám potvrzení nedorazilo, zkontrolujte svůj SPAM koš.</p>
<p>V případě jakýchkoliv dotazů nás kontaktujte na telefonním čísle <span>(+420) 777 777 777</span> nebo emailem na <a href="mailto:registrace@travai.cz">registrace@travai.cz</a>.</p>';

// config for signup verify page
$config['signup_verify_page_heading_txt'] = '{user_first_name_last_name_or_company_name}, please verify your email <span>address!</span>';

$config['signup_verify_page_sub_heading_txt_male'] = '<p>MALE - You registered an account with Travai on <span class="email-add">{newly_registered_account_registration_date}</span>, and you still did not confirm the email address you used during registration process - <span class="email-add">{newly_registered_account_user_email}.</span></p><p>Be informed that all accounts not verified during first <span class="email-add">{newly_registered_account_confirmation_expiration_time}
 hodiny </span> after registration, are removed from our database. <span id="account_expiration_text">You still have <span class="email-add" id="account_expiration_countdown"></span> pro potvrzení Vaší e-mailové adresy a aktivaci účtu Travai.</span></p>';
 
$config['signup_verify_page_sub_heading_txt_female'] = '<p>FEMALE - You registered an account with Travai on <span class="email-add">{newly_registered_account_registration_date}</span>, and you still did not confirm the email address you used during registration process - <span class="email-add">{newly_registered_account_user_email}</span>.</p><p>Be informed that all accounts not verified during first <span class="email-add">{newly_registered_account_confirmation_expiration_time}
 hodiny </span> after registration, are removed from our database. <span id="account_expiration_text">You still have <span class="email-add" id="account_expiration_countdown"></span> pro potvrzení Vaší e-mailové adresy a aktivaci účtu Travai.</span></p>';
 
 
 $config['signup_verify_page_sub_heading_txt_app_male'] = '<p>App MALE - You registered an account with Travai on <span class="email-add">{newly_registered_account_registration_date}</span>, and you still did not confirm the email address you used during registration process - <span class="email-add">{newly_registered_account_user_email}</span>.</p><p>Be informed that all accounts not verified during first <span class="email-add">{newly_registered_account_confirmation_expiration_time}
 hodiny </span> after registration, are removed from our database. <span id="account_expiration_text">You still have <span class="email-add" id="account_expiration_countdown"></span> pro potvrzení Vaší e-mailové adresy a aktivaci účtu Travai.</span></p>';
 
 
 $config['signup_verify_page_sub_heading_txt_app_female'] = '<p>App FEMALE - You registered an account with Travai on <span class="email-add">{newly_registered_account_registration_date}</span>, and you still did not confirm the email address you used during registration process - <span class="email-add">{newly_registered_account_user_email}</span>.</p><p>Be informed that all accounts not verified during first <span class="email-add">{newly_registered_account_confirmation_expiration_time}
 hodiny </span> after registration, are removed from our database. <span id="account_expiration_text">You still have <span class="email-add" id="account_expiration_countdown"></span> pro potvrzení Vaší e-mailové adresy a aktivaci účtu Travai.</span></p>';
 
 
 
$config['signup_verify_page_sub_heading_txt_company'] = '<p>Company - You registered an account with Travai on <span class="email-add">{newly_registered_account_registration_date}</span>, and you still did not confirm the email address you used during registration process - <span class="email-add">{newly_registered_account_user_email}</span>.</p><p>Be informed that all accounts not verified during first <span class="email-add">{newly_registered_account_confirmation_expiration_time}
 hodiny </span> after registration, are removed from our database. <span id="account_expiration_text">You still have <span class="email-add" id="account_expiration_countdown"></span> pro potvrzení Vaší e-mailové adresy a aktivaci účtu Travai.</span></p>';
 
//// Config is using for bullet message and reminder message based on cron reminder and manual request time parameters 
######################################################################################### 
 
// This config is using when user have valid verification code and also have option to send manual request code.
$config['signup_verify_page_newly_registered_user_valid_verification_code_and_send_manual_request_code_option_available_message_txt'] = '<p>1. First of all check your email and look for the email message (subject "{registartion_email_subject}") sent by iPrace.online upon your registration - search in your Spam folder too</p><p>2. If you do not have our email, then click the "Generate Verification Code" button below to have a new verification code re-sent to the email address you signed up with</p><p>3. Check your inbox for the activation email - subject "You have requested a Generate Verification Code code"</p><p class="leftalign">4. Click on the activation URL (or copy/paste the URL in your browser window) from the email or copy and paste the code sent on the following field</p><p>5. Validate your code</p>';

###########################################
// This config is using when user currently verification code is expired but also have option to send manual request code.
$config['signup_verify_page_newly_registered_user_verification_code_expired_and_send_manual_request_code_option_available_message_txt'] = '<p>Your verification code is expired.Follow the instruction here below to get a new verification code sent to your email <span class="email-add">{newly_registered_account_user_email}</span></p>';

// reminder text when verification code is expired and user have option to send _manual request code
$config['signup_verify_page_newly_registered_user_verification_code_expired_and_send_manual_request_code_option_available_reminder_message_txt'] = '<p>Remember : we will automatically generate and send to you a new activation code every {signup_automatic_send_reminder_interval} hodiny. <span id="next_auto_request_text">Nový ověřovací kód bude automatically generován za <span id="next_auto_request"></span>.</span></p>';

#####################################################################################
// This config is using when user currently verification code is expired he dont have option to send manual request code also not recived new varificode by cron.

$config['signup_verify_page_newly_registered_user_verification_code_expired_not_received_verification_code_by_automatically_cron_and_send_manual_request_code_option_not_available_message_txt'] = '
<p><b>Remember:</b> we automatically generate and send to you new activation code every {signup_automatic_send_reminder_to_unverified_user_set_interval} hodiny (email subject - "{send_reminder_verification_code_email_subject}").</p>';

// reminder message for manual request code
$config['signup_verify_page_newly_registered_user_verification_code_expired_not_received_verification_code_by_automatically_cron_and_send_manual_request_code_option_not_available_manual_request_code_option_reminder_message_txt'] = '<p>You manually generated the verification code on {verification_code_manual_request_date} at {verification_code_manual_request_time}.
<span id="next_manual_request_text">You can manually generate a new verification code in <span id="next_manual_request"></span>{next_request_generated_time}.</span></p>';

// reminder message for automatic reminder
$config['signup_verify_page_newly_registered_user_verification_code_expired_not_received_verification_code_by_automatically_cron_and_send_manual_request_code_option_not_available_automatically_cron_reminder_message_txt'] = 'Just monitor your email address, get the verification and activate your iPrace.online account.
<p id="next_automatic_request_text" class="adjustTop">You will receive next Reminder email in <span><span id="next_automatic_request"></span>{automatic_request}.</span></p>';

##################################################################

// this config is using when user generated manually code, code is still valid, user has also option to regenerate a new code
$config['signup_verify_page_newly_registered_user_generate_verification_code_by_manualy_request_valid_verification_code_and_send_manual_request_code_option_available_message_txt'] = '<p>Nový ověřovací kód (manual) byl úspěšně vygenerován před <span id="manual_request"></span>. Postupujte podle níže uvedených pokynů a aktivujte účet.</p>';

// this config is using when user generated manually code, code is still valid, user has noption to regenerate a new code
$config['signup_verify_page_newly_registered_user_generate_verification_code_by_manualy_request_valid_verification_code_and_send_manual_request_code_option_not_available_message_txt'] = '<p>New verification code was manually generated successfully <span id="manual_request"></span> ago. Follow instructions from below and get your account activated.</p>';

// This config is using when user currently verification code is valid(recieved code automatcally) but also have option to send manual request code.
$config['signup_verify_page_newly_registered_user_received_verification_code_by_automatically_cron_valid_verification_code_and_send_manual_request_code_option_available_message_txt'] = '<p>New verification code was automatically generated successfully <span id="automatic_request"></span>.Follow instructions from below and get your account activated.</p>';

##############################################################################################

// These config are using for button text on signup verification page 
$config['signup_verify_page_code_txt'] = 'Code';
$config['signup_verify_page_validate_code_button_txt'] = 'Validate Code';
$config['signup_verify_page_generate_verification_code_button_txt'] = 'Generate Verification Code';


///// config for successful signup verification page
$config['successful_signup_verification_page_heading'] = 'Account is activated!';

$config['successful_signup_verification_page_message_txt'] = '<p><small>{user_first_name_or_company_name}</small>, thank you for verifying your email address <strong>{newly_registered_account_user_email}</strong>.</p><p>Your account is activated. You can now go to the main account page.</p><p>Váš účet je aktivován. Nyní můžete přejít na <span><a class="redirection">hlavní stránku účtu</a>.</span></p>';
/*
|--------------------------------------------------------------------------
| Signup Config Variables for Signup Form
|--------------------------------------------------------------------------
| 
*/
################ Defined the signup form validation regarding user signup form start here
/* Filename: application\modules\signup\controllers\signup.php */
/* Controller: signup Method name: index */
$config['first_name_validation_signup_message'] = 'First name is required.';

$config['last_name_validation_signup_message'] = 'Last name is required.';

$config['company_name_validation_signup_message'] = 'Company name is required.';

$config['gender_validation_signup_message'] = 'Please select your gender.';

$config['email_validation_signup_message'] = 'Email address is a required field';

$config['email_address_already_exists_signup_message'] = 'Email address you use is invalid. Please enter a different one.';

$config['valid_email_validation_signup_message'] = 'Please enter a valid email address.';

$config['only_letters_validation_signup_message'] = 'Please enter only letters and dot.'; // this variable used to display error message on firstname and last name field when user try to insert character which are not alphabets

$config['profile_name_validation_signup_message'] = 'Profile name is a required field.';

$config['password_validation_signup_message'] = 'Password is a required field.';

################ Defined the signup form validation regarding user signup form end here
$config['profile_name_signup_message_tooltip'] = "Profile Name can only contain numbers, letters, '.', ' - ' and ' _ '. All other symbols are invalid"; // This messsage display in tooltip besides profile name label

################ Defined the account verification message store in user log and user display activity log
$config['user_account_verification_success_message_for_log'] = '{user_first_name_or_company_name}, you successfully verified your account.';

$config['user_account_verification_success_message_for_display_activity'] = '{user_first_name_or_company_name}, you successfully verified your account. At Registration time you receive <span>1 000 Kč</span> as Bonus. This Bonus can be used for purchases on the portal.';
/*
|--------------------------------------------------------------------------
| Url Variables 
|--------------------------------------------------------------------------
| 
*/

################ Url Routing Variables for signup page ###########
/* Filename: application\modules\signup\controllers\signup.php */

$config['referrer_page_url'] = 'site';

$config['signup_page_url'] = 'signup';

$config['signup_confirmation_page_url'] = 'signup/confirmation';

$config['signup_activate_page_url'] = 'signup/activate';

$config['signup_page_success_parameter'] = 'success';

$config['signup_page_code_parameter'] = 'code';

$config['signup_verify_redirection_page_url'] = 'signup/verify_redirection';

$config['signup_verified_page_url'] = 'signup/verified';

$config['signup_verified_activation_code_wrong_message'] = 'Activation code is invalid.';

$config['signup_verified_activation_code_new_message'] = 'New Verification Code successfully sent.';


?>