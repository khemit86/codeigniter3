<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//Left navigation Menu name
$config['pa_user_left_nav_account_management'] = 'Account Management';
$config['ca_user_left_nav_account_management'] = 'Account Management Comp';

$config['account_management_left_nav_account_overview'] = 'Account Overview.';
$config['account_management_left_nav_avatar'] = 'Avatar.';

$config['pa_user_account_management_left_nav_address'] = 'Adresa.';
$config['ca_user_account_management_left_nav_address'] = 'Company Adresa';

$config['account_management_left_nav_contact_details'] = 'Contact Details.';
$config['account_management_left_nav_account_login_details'] = 'Account Login Details.';
$config['account_management_left_nav_close_account'] = 'Close Account.';


// CONFIG FOR ACCOUNT MANAGEMENT ACCOUNT OVERVIEW PAGE
$config['account_management_account_overview_page_url'] = 'account-overview';
$config['account_management_account_overview_page_headline_title'] = 'Account Overview';

###### Meta config for account management account overview page #######
$config['account_management_account_overview_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Account Overview';

$config['account_management_account_overview_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Account Overview';

###### Config for tab names on account management account overview page #######
$config['account_management_account_overview_page_account_details_tab'] = 'Account Details';
$config['account_management_account_overview_page_membership_tab'] = 'Membership';

// CONFIG FOR ACCOUNT MANAGEMENT AVATAR PAGE
$config['account_management_avatar_details_page_url'] = 'avatar';
$config['account_management_avatar_page_headline_title'] = 'Avatar';

###### Meta config for account management avatar page #######
$config['account_management_avatar_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Avatar';
$config['account_management_avatar_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Avatar';


$config['user_profile_avatar_allowed_file_extension_validation_message'] = 'file type is not allowed';

//Account management avatar page button text
$config['user_profile_upload_avatar_btn_txt'] = 'Select and crop image';
$config['user_profile_upload_new_avatar_btn_txt'] = 'upload new avatar';


// CONFIG FOR ACCOUNT MANAGEMENT ADDRESS PAGE
$config['account_management_address_details_page_url'] = 'address';

###### Meta config for account management address page #######
$config['account_management_address_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Address';
$config['account_management_address_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Address';

//account management address page text
$config['pa_account_management_address_details_view_title'] = 'pa-Address Details';
$config['pa_account_management_address_details_initial_view_content'] = 'pa-Vložením adresy zkvalitňujete svůj profil pro ostatní uživatele, kteří mohou hledat odborníka ve svém kraji či obci. Váš profil může být dohledatelný v katalogu profesionálů pomocí filtru dané lokality.';

$config['ca_account_management_address_details_view_title'] = 'ca-Address Details';
$config['ca_account_management_address_details_initial_view_content'] = 'ca-Vložením adresy zkvalitňujete svůj profil pro ostatní uživatele, kteří mohou hledat odborníka ve svém kraji či obci. Váš profil může být dohledatelný v katalogu profesionálů pomocí filtru dané lokality.';


// for app user
$config['ca_app_account_management_address_details_view_title'] = 'Adresa a provozovny(app)';
$config['ca_app_account_management_headline_title_address'] = 'Adresa a provozovny(app)';
$config['ca_app_account_management_address_details_initial_view_content'] = '(app)Uvedením adresy spolecnosti a dalších provozoven zkvalitnujete profil své spolecnosti pro ostatní uživatele a návštevníky, kterí mohou hledat spolecnost s vaším zamerením ve svém kraji ci obci. Váš profil spolecnosti muže být dohledatelný v seznamu odborníku pomocí filtru dané lokality.';

//account management address page tooltip messages
$config['account_management_address_details_street_address_tooltip'] = 'K ulici a číslu popisném může být uvedeno i číslo orientační, např. Domovní 511/20';

//account management address page functionalty validation messages and validation limits
$config['account_management_address_details_locality_required_field_error_message'] = 'Locality required';
$config['account_management_address_details_county_required_field_error_message'] = 'County required';
$config['account_management_address_details_country_required_field_error_message'] = 'Country required';
$config['account_management_address_details_postal_code_required_field_error_message'] = 'Postal code required';
$config['account_management_address_details_duplicate_location_error_message'] = 'Duplicate entry can not be saved for location';


$config['account_management_address_details_street_address_maximum_length_characters_remaining_txt'] = 'characters remaining';


// CONFIG FOR ACCOUNT MANAGEMENT CONTACT PAGE
$config['account_management_contact_details_page_url'] = 'contact-details';
$config['account_management_contact_page_headline_title'] = 'Contact Details';
###### Meta config for account management contact details page #######
$config['account_management_contact_details_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | contact details';
$config['account_management_contact_details_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | contact details';

//Account management contact page functionalty validation messages
//For skype
$config['account_management_contact_details_skype_id_required_field_error_message'] = 'Skype required.';
$config['account_management_contact_details_skype_id_input_placeholder'] = 'Skype..';

// For contact email 
$config['account_management_contact_details_contact_email_required_field_error_message'] = 'Contact Email required.';
$config['account_management_contact_details_contact_email_invalid_format_field_error_message'] = 'please enter valid contact email format.';
$config['account_management_contact_details_contact_email_input_placeholder'] = 'Contact Email..';

// For website url
$config['account_management_contact_details_website_url_required_field_error_message'] = 'Website url required.';
$config['account_management_contact_details_invalid_website_url_field_error_message'] = 'Invalid Website url.';
$config['account_management_contact_details_website_url_input_placeholder'] = 'website url: http(s)://yourdomain.com';

// For additional phone number
$config['account_management_contact_details_additional_phone_number_required_field_error_message'] = 'additional phone number required.';
$config['account_management_contact_details_additional_phone_number_input_placeholder'] = 'Additional phone number..';

// For mobile phone number
$config['account_management_contact_details_mobile_phone_number_required_field_error_message'] = 'mobile phone number required.';
$config['account_management_contact_details_mobile_phone_number_input_placeholder'] = 'mobile phone number..';

// For phone number
$config['account_management_contact_details_phone_number_required_field_error_message'] = 'phone number required.';
$config['account_management_contact_details_phone_number_input_placeholder'] = 'phone number..';

#################################################################################################################

$config['pa_account_management_address_details_page_title_meta_tag'] = 'pa-{user_first_name_last_name_or_company_name} | Address Details';
$config['ca_account_management_address_details_page_title_meta_tag'] = 'ca-{user_first_name_last_name_or_company_name} | Address Details';

$config['pa_account_management_address_details_page_description_meta_tag'] = 'pa-{user_first_name_last_name_or_company_name} | Address Details';
$config['ca_account_management_address_details_page_description_meta_tag'] = 'ca-{user_first_name_last_name_or_company_name} | Address Details';

// for app user
$config['ca_app_account_management_address_details_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Address Details';

$config['ca_app_account_management_address_details_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Address Details';


/*
|--------------------------------------------------------------------------
| user not exist custom messages 
|--------------------------------------------------------------------------
| 
*/
/* Filename: application\modules\activity\views\404_profile_not_existent.php */
/* Controller: activity Method name: profile_professional */

// config for tabs for login details page regarding account management 
$config['account_management_account_login_details_page_login_email_tab_txt'] = 'En-Login Email';
$config['account_management_account_login_details_page_login_password_tab_txt'] = 'En-Login Password';
$config['account_management_account_login_details_page_verfication_tab_txt'] = 'En-Verification';

// meta config for account management account login detail page 
$config['account_management_account_login_details_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | login Details';

$config['account_management_account_login_details_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | login Details';

$config['account_management_account_login_details_page_url'] = 'account-login-details';

$config['pa_account_management_headline_title_address'] = 'pa-Address';
$config['ca_account_management_headline_title_address'] = 'ca-Address';
$config['account_management_headline_title_contact_details'] = 'Contact Details';
$config['account_management_headline_title_account_login_details'] = 'Account Login Details';
$config['account_management_headline_title_close_account'] = 'Close Account';

//Account Management Account title tab End

//Account Management Address Details tab Start
$config['account_management_address_details_tab_street_address'] = 'Street Address:';

$config['account_management_address_details_tab_country'] = 'Select country';

$config['account_management_address_details_tab_reset_field'] = 'Reset Field';

$config['account_management_address_details_tab_reset_selection'] = 'Reset Selection';


$config['company_user_account_management_address_details_tab_add_another_business_location'] = 'Add another business location';

//Account Management Address Details tab End


//Account Management Account Title Contact Details tab Start
$config['account_management_contact_details_page_phone_number_tab_txt'] = 'Phone Number';
$config['account_management_contact_details_page_mobile_phone_number_tab_txt'] = 'Mobile Number';
$config['account_management_contact_details_page_addtional_phone_number_tab_txt'] = 'Aaddtional Phone Number';
$config['account_management_contact_details_page_contact_email_tab_txt'] = 'Contact Email';
$config['account_management_contact_details_page_skype_id_tab_txt'] = 'Skype ID';
$config['account_management_contact_details_page_website_url_tab_txt'] = 'Website';


//Account Management Account Title Contact Details tab End

//Account Management Membership Title Start
$config['account_management_membership_title_manage'] = 'Manage Membership';
//Account Management Membership Title End

/* config variable to show text on account management page membership section */
$config['account_management_membership_plan_heading'] = 'Current Membership Plan:';

############### For social media section for account login page 
$config['account_management_account_login_details_page_social_media_verification_section_user_current_email_address_txt'] = 'your current Email Address is:';

$config['account_management_account_login_details_page_social_media_verification_section_facebook_label_txt'] = 'Facebook';

$config['account_management_account_login_details_page_social_media_verification_section_linkedin_label_txt'] = 'LinkedIn';

$config['account_management_account_login_details_page_social_media_verification_section_social_media_account_not_connected_label_txt'] = 'Not Connected';

$config['account_management_account_login_details_page_social_media_verification_section_social_media_account_connected_label_txt'] = 'Connected';

$config['account_management_account_login_details_page_social_media_verification_section_no_information_publicaly_displayed_disclaimer_label_txt'] = 'None of this informaiton will be publicaly displayed on the site';

$config['account_management_account_login_details_page_social_media_verification_section_connect_facebook_account_label_txt'] = 'CONNECT your Facebook account to your account.';

$config['account_management_account_login_details_page_social_media_verification_section_connect_facebook_account_error_msg'] = 'FB-an error occurred. please try again.'; // This error message will display when we didn't get all info [ id / email] from fb which we aksed for
$config['account_management_account_login_details_page_social_media_verification_section_user_attempts_connect_already_used_facebook_account_error_msg'] = 'You are trying to connect already used facebook account, please try with another.';

$config['account_management_account_login_details_page_social_media_verification_section_connect_linkedin_account_label_txt'] = 'CONNECT your LinkedIn account to your account.';

$config['account_management_account_login_details_page_social_media_verification_section_connect_linkedin_account_error_msg'] = 'ln-an error occurred. please try again.';

$config['account_management_account_login_details_page_social_media_verification_section_user_attempts_connect_already_used_linkedin_account_error_msg'] = 'You are trying to connect already used facebook account, please try with another.';


$config['account_management_account_login_details_page_social_media_verification_section_user_connected_facebook_account_confirmation_label_txt'] = 'Your CONNECTED Facebook account is: {user_facebook_email_id}';

$config['account_management_account_login_details_page_social_media_verification_section_user_connected_linkedin_account_confirmation_label_txt'] = 'Your CONNECTED Linkedin account is: {user_linkedin_email_id}';

$config['account_management_account_login_details_page_social_media_verification_section_revoke_btn_txt'] = 'Revoke';

$config['account_management_account_login_details_page_social_media_verification_section_connect_facebook_account_btn_txt'] = 'Connect your facebook account';

$config['account_management_account_login_details_page_social_media_verification_section_connect_linkedin_account_btn_txt'] = 'Connect your linkedin account';

$config['account_management_account_login_details_page_social_media_verification_section_bottom_disclaimer_txt'] = "<p>Ces vérifications permettent d'améliorer la confiance entre les membres de xxxxxxx en rendant plus difficile la création de comptes crédibles pour les personnes nuisibles. Aucune de ces informations ne sera utilisée à des fins marketing.</p>";

############## For Update email section 

//Account Management Verifications Title Start
$config['account_management_verifications_title_email_successfully_update'] = 'Email Successfully Updated';

//Account Management Verifications Title End

//Account Management Email Title Start
$config['account_management_email_title_update_email'] = 'Update Email';

$config['account_management_email_initial_view_content'] = 'E-mailovou adresu můžete kdykoliv změnit zadáním nové e-mailové adresy. Po změně se nový e-mail stane platným, který bude v používán k ověření účtu při dalším přihlášení. Veškerá komunikace bude probíhat přes tuto novou adresu.';

$config['account_management_email_current_email_placeholder'] = "Current Email";

$config['account_management_email_current_password_placeholder'] = "Current Password";

$config['account_management_email_new_email_placeholder'] = "New E-Mail-";

$config['account_management_email_confirm_new_email_placeholder'] = "Confirm New E-Mail-";


// For current email and current password
$config['account_management_update_email_section_current_email_required_field_error_message'] = "Current email address is required.";

$config['account_management_update_email_section_current_email_invalid_format_field_error_message'] = "Current email address is invalid format.";

$config['account_management_update_email_section_current_email_password_incorrect_combination_field_error_message'] = "Incorrect email and password";

$config['account_management_update_email_section_current_password_required_field_error_message'] = "Current password is required.";


// For new email
$config['account_management_update_email_section_new_email_required_field_error_message'] = "New email address is required.";

$config['account_management_update_email_section_new_email_invalid_format_field_error_message'] = "new email address is invalid format.";

$config['account_management_update_email_section_confirm_new_email_required_field_error_message'] = "Confirm new email address is required.";

$config['account_management_update_email_section_confirm_new_email_invalid_format_field_error_message'] = "new confirm email address is invalid format.";

$config['account_management_update_email_section_new_email_confirmation_not_match_new_email_field_error_message'] = "New email and confirm new email is not match";

$config['account_management_update_email_section_new_email_match_old_email_field_error_message'] = "New email and old email should not be same";

$config['account_management_update_email_section_new_email_not_unique_error_message'] = "please enter unique email.";

// email config for update email section
$config['account_management_update_email_user_activity_log_displayed_message'] = 'you updated login email address';

### update password section start
//Account Management Email Title End

//Account Management Password Title Start
$config['account_management_password_title_update_password'] = 'Update Password';

$config['account_management_password_initial_view_content'] = 'Rady pro vytváření hesla : pouzivat dlouhé heslo / aby heslo bylo nesmyslné fráze / patří mezi ně čísla, symboly, velká a malá písmena. Nedávejte svá hesla nikomu jinému. <strong>Nezapomeňte pravidelně měnit heslo</strong>.';


//$config['account_management_password_current_password_placeholder'] = "Current Password";
$config['account_management_password_current_email_placeholder'] = "current email";

$config['account_management_password_current_password_placeholder'] = "Current Password";

$config['account_management_password_new_password_placeholder'] = "New password";

$config['account_management_password_confirm_new_password_placeholder'] = "Confirm New password";

$config['account_management_update_password_section_current_email_required_field_error_message'] = "Current email address is required...";

$config['account_management_update_password_section_current_email_invalid_format_field_error_message'] = "Current email address is invalid format...";

$config['account_management_update_password_section_current_email_password_incorrect_combination_field_error_message'] = "Incorrect email and password....";

$config['account_management_update_password_section_current_password_required_field_error_message'] = "Current password is required...";

$config['account_management_update_password_section_new_password_required_field_error_message'] = "new password is required.";

$config['account_management_update_password_section_confirm_new_password_required_field_error_message'] = "confirm new password is required.";

$config['account_management_update_password_section_new_password_confirmation_not_match_new_password_field_error_message'] = "New password and confirm new password is not match";

$config['account_management_update_password_section_new_password_match_old_password_field_error_message'] = "New password and old password should not be same";

$config['account_management_update_password_user_activity_log_displayed_message'] = 'you updated login password';

$config['account_management_password_title_password_successfully_update'] = 'Password Successfully Update';
//Account Management Password Title End


/* config variable to show text on account management page account details section */

$config['account_management_account_details_account_type_heading'] = 'Account Type:';

$config['account_management_account_details_name_heading'] = 'Name:';

$config['account_management_account_details_company_name_heading'] = 'Company Name:';

$config['account_management_account_details_gender_heading'] = 'Gender:';

$config['account_management_account_details_company_account_type_txt'] = 'Company';

$config['account_management_account_details_company_app_account_type_txt'] = '(OSVC)';

$config['account_management_account_details_personal_account_type_txt'] = 'Personal';

$config['account_management_account_details_personal_account_type_gender_male_txt'] = 'Male';

$config['account_management_account_details_personal_account_type_gender_female_txt'] = 'Female';


//start section for Zavření účtu / Close Account
// meta config for account management close account page 
$config['account_management_account_close_account_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Close Account';

$config['account_management_account_close_account_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Close Account';

$config['account_management_close_account_page_url'] = 'close-account';

$config['account_management_close_account_page_terms_first_description_text'] = "<p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour.</p>";

$config['account_management_close_account_page_terms_second_description_text'] = "<p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour.</p>";

$config['account_management_close_account_page_terms_third_description_text'] = "<p>Rádi bychom věděli, proč chcete zavřít účet, prosím vyberte důvod a napište nám zpětnou vazbu.</p>
	<p>Vaši žádost o zavření účtu budeme neprodleně řešit. Během tohoto procesu můžete být kontaktováni.</p>";

$config['account_management_close_account_page_close_account_reason_default_option_name'] = 'Please Choose One';

$config['account_management_close_account_page_close_account_reason_of_close_txt'] = "Reason of Close";

$config['account_management_close_account_page_close_account_reasons_dropdown'] = array("Reason 1","Reason 2","Reason 3");

$config['account_management_close_account_page_reason_description_txt'] = "Description";

$config['account_management_close_account_page_confirmation_btn_text'] = "Yes, close my account";

// config regarding validation
$config['account_management_close_account_page_close_account_reason_required_validation_message'] = 'Reason is required.';

$config['account_management_close_account_page_close_account_reason_description_required_validation_message'] = 'Descrption is required.';


// config when user alrady send the close account request
$config['user_close_account_request_already_sent_message'] = "En:You submitted request to close your account on {close_account_request_sent_time}. The request is currently under review with our support team and we will contact you soon.
Should you change your mind, and want to keep your account active, or have any question, do not  hesitate to contact us anytime. We don't want to see you go, and we'll do everything we can to sort out any issues you may have.";

$config['user_close_account_request_sent_confirmation_message'] = 'En:Your request was submitted to our support department. We will review it and will give an answer to you soon';

// Config for close account confirmation popup
$config['close_account_confirmation_modal_title'] = "Close Account";

$config['close_account_confirmation_modal_close_account_send_request_btn_txt'] = "Send Request";

$config['close_account_confirmation_modal_body'] = 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour.
There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour.';

?>