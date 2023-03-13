<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//$config['site_url'] = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'];
/*
|--------------------------------------------------------------------------
| 404 generic page custom messages 
|--------------------------------------------------------------------------
| 
*/

$config['404_page_title_meta_tag'] = 'EN 404 Stránka je nedostupná';
$config['404_page_description_meta_tag'] = 'EN 404 Stránka je nedostupná';
$config['404_page_heading'] = 'EN - 404 Stránka je nedostupná...';

// $config['message_404_without_login'] = 'This is 404 ERROR page to be displayed for logged off users.';
$config['message_404_without_login'] = 'EN - Odkaz, na který jste klikli je nefunkční, stránka neexistuje.(logged off users) to continue <a href="/">click here...</a>';

//$config['message_404_with_login'] = 'This is 404 ERROR page to be displayed for logged in users. ro/pl/cz';
$config['message_404_with_login'] = 'EN - Odkaz, na který jste klikli je nefunkční, stránka neexistuje.(logged in users) to continue <a href="/">click here...</a>';

// this config are using for shows the footer text  on default 404 error page(views/404defaultpage/404_default.php),system generated 404 page(ex->views/errors/html/error_404_production.php),hidden project default page (modules/projects/view/hidden_project_default_page.php)
$config['footer_message_404_page'] = 'FooterEN-Dosáhněte (téměř) čehokoli, pomůžeme vám dosáhnout úspěchu!';


// These config are using as a meta title/meta description for system generated error pages
// view files-> /application/views/errors/html
$config['system_generated_404_page_title_meta_tag'] = '(System) EN - 404 Stránka je nedostupná';
$config['system_generated_404_page_description_meta_tag'] = '(System) EN - 404 Stránka je nedostupná';
$config['system_generated_404_page_heading'] = 'EN 404 Stránka je nedostupná...';
$config['system_generated_404_page_message'] = 'EN Odkaz, na který jste klikli je nefunkční. Stránka inzerátu neexistuje. to continue <a href="/">click here....</a> (system generated)';


// this config is using to show cookiew text on header
$config['accept_cookies_banner_txt'] = 'This website uses cookies to improve sevice and provide tailored ads. By using this site,you agree to this use. See our <a href="{privacy_policy_page_url}">Cookie Policy</a>';


// this config is using for scrolling text above the four tab used on top side of home page
//view file application/views/home.php
$config['home_page_top_scrolling_text_correspondent_tab1'] = 'Řízení procesu náboru z jednoho místa. Najděte, sledujte a najímejte odborníky z celé ČR.'; 
$config['home_page_top_scrolling_text_correspondent_tab2'] = 'Najít odborníka nebylo nikdy snazší. Objevte a najměte si nejlepší odborníky z České republiky.'; 
$config['home_page_top_scrolling_text_correspondent_tab3'] = 'Jediná platforma v ČR, která vám dává příležitost maximalizovat svůj skutečný potenciál.'; 
$config['home_page_top_scrolling_text_correspondent_tab4'] = 'Platforma, která vám dává možnost získat nové zaměstnání a zvětšit svůj potenciál.'; 

// config are using on url when user click on top tab on home page regardinh scrolling text
$config['home_page_top_tab2_url_txt'] = 'cztab2';
$config['home_page_top_tab3_url_txt']= 'cztab3';
$config['home_page_top_tab4_url_txt']= 'cztab4';

$config['or'] = 'or';


// text for find professsional/ find project page on header menu/user dashboard
$config['header_top_navigation_post_project_menu_name'] = 'Post Project';
$config['header_top_navigation_chat_room_menu_name'] = 'Chat Room';


$config['browse_projects_txt'] = 'Projects and Jobs';
$config['browse_service_providers_txt'] = 'Find Professionals';
$config['manage_sent_invitations_and_affiliate_income'] = 'EN Správa pozvání & Partnerský program';


// Common button text define Start 
$config['signup_btn_txt'] = 'Register';
$config['signin_btn_txt'] = 'Log In';
$config['signout_btn_txt'] = 'Log Out';

$config['save_btn_txt'] = 'Save';

$config['edit_btn_txt'] = 'Edit';

$config['cancel_btn_txt'] = 'Cancel';

$config['remove_btn_txt'] = 'Remove';

$config['reply_btn_txt'] = 'Reply';

$config['reset_btn_txt'] = 'Reset';

$config['delete_btn_txt'] = 'Delete';

$config['close_btn_txt'] = 'Close';

$config['accept_btn_txt'] = 'Accept';

$config['decline_btn_txt'] = 'Decline';

$config['send_btn_txt'] = 'Send';

$config['award_btn_txt'] = 'Award';

$config['rate_btn_txt'] = 'Rate';

// this config is using in header small notification window
$config['view_all_btn_txt'] = 'View All';

$config['next_btn_txt'] = 'Next';// this config is using on account managament->account login details page

// config variables success/error popup.
$config['popup_alert_heading'] = "Alert!"; // this config is using for general error popup heading 


// config show where we are showing drop down actions
$config['action'] = 'Action';

//Project Owner Details Contact Me Button
$config['contactme_button'] = 'Contact Me';

//This config is using on my project section(all tabs on po/sp view) on dashboard page
$config['view_all_projects'] = "view all projects";

//used to show more categs for projects and professionals
$config['show_more_txt'] = "show more";
$config['show_less_txt'] = "show less";

//These config are using to all over the site for paging text.
$config['showing_pagination_txt'] = 'Showing';

$config['out_of_total_pagination_txt'] = 'of total';

//This config are using to show the error message when session is conflict(example from one tab sp update bid now from second tab logout and login with new sp now go to first tab and trying to update bid of first sp)
$config['different_users_session_conflict_message'] = 'your session expired. please refresh the page -> refresh the page automatically, once refreshed user will be displayed with different option on the page';

// This variable is used to display error message in popup and chat when user try to upload attachment and users folder not exist
$config['users_folder_not_exist_error_message'] = 'There is an error occured, Please try again later.';

//Month listed here
//$config['calendar_months'] = array("1"=>"January", "2"=>"February", "3"=>"March", "4"=>"April", "5"=>"May", "6"=>"June", "7"=>"July", "8"=>"August", "9"=>"September", "10"=>"October", "11"=>"November", "12"=>"December");
$config['calendar_months'] = array("1"=>'Jan', "2"=>'Feb', "3"=>'Mar', "4"=>'April', "5"=>'May', "6"=>'June', "7"=>'July', "8"=>'Aug', "9"=>'Sept', "10"=>'Oct', "11"=>'Nov', "12"=>'Dec');

$config['calendar_months_short_name'] = array("January"=>'Jan', "February"=>'Feb', "March"=>'Mar', "April"=>'Apr', "May"=>'May', "June"=>'Jun', "July"=>'Jul', "August"=>'Aug', "September"=>'Sep', "October"=>'Oct', "November"=>'Nov', "December"=>'Dec');

$config['calendar_weekdays_short_name'] = array("Sunday"=>'Sun', "Monday"=>'Mon', "Tuesday"=>'Tue', "Wednesday"=>'Wed', "Thurshday"=>'Thu', "Friday"=>'Fri', "Saturday"=>'Sat');

$config['calendar_weekdays_long_name'] = array("1"=>'Monday', "2"=>'Tuesday', "3"=>'Wednesday', "4"=>'Thurshday', "5"=>'Friday', "6"=>'Saturday', "7"=>'Sunday');

//common message for character remaing for inputs 
$config['characters_remaining_message'] = 'characters remaining';

$config['present_txt'] = 'Present';

// config based on number of days
$config['1_day'] = '(1) day';
$config['2_4_days'] = '(2-4) days';
$config['more_than_or_equal_5_days'] = '(+5) days';


// config based on number of hours
$config['1_hour'] = '(1) hour';
$config['2_4_hours'] = '(2-4) hours';
$config['more_than_or_equal_5_hours'] = '(5-23) hours';


// config based on number of minutes
$config['1_minute'] = '(1) minute';
$config['2_4_minutes'] = '(2-4) minutes';
$config['more_than_or_equal_5_minutes'] = '(5-59) minutes';

// config based on number of seconds
$config['1_second'] = '(1) second';
$config['2_4_seconds'] = '(2-4) seconds';
$config['more_than_or_equal_5_seconds'] = '(5-59) seconds';

// config based on number of months
$config['1_month'] = '(1) month';
$config['2_4_months'] = '(2-4) months';
$config['more_than_or_equal_5_months'] = '(5-11) months';

$config['1_year'] = '(1) year';
$config['2_4_years'] = '(2-4) years';
$config['more_than_or_equal_5_years']= '(+5) years';

$config['continue_click_here_txt'] = 'EN - Pro pokračování <a href="/">klikněte zde...</a>';

//config for attachment name as prefix(upload bid attachment/project attachment/chat attachment)
$config['attachment_prefix_text'] = "trv_";

// this config is used for confirmation message for user (example when po award bid/sp accept/decline)
$config['user_confirmation_check_box_txt'] = "Yes, I confirm";

$config['select_country'] = 'select country';
$config['select_locality'] = 'select locality';
$config['select_county'] = 'select county';
$config['select_postal_code'] = 'select postal code';


$config['membership_plans_names'] = [
1 => 'Free',
4 => 'Gold'
];

// this alert message is showing when user upload blank attachment.
$config['upload_blank_attachment_alert_message'] = "you cannot upload blank attachment";

//this variable iused on user profile page portfolio/projects posted/projects hired sections
$config['load_more_results'] = "Load More Results";

$config['drop_zone_drag_and_drop_files_area_message_txt'] = 'drop files here...'; // This variable will text will display to user when he drag and drop any file into drop area on chat room / small chat window / project detail page


##############################################################################
//post project and bid attachment management there are dedicated varibles in place. 
//Below config are using only those place where we are allowing only the pictures

$config['pictures_allowed_extensions_js'] = '["jpge", "gif", "png", "jpeg", "jfif"]'; //This config is using to check the image type by javascript where we are uploading only the images (portfolio standalone page,featured project cover pciture,user profile cover picture,avatar,portfolio gallery images)


$config['pictures_allowed_extensions_input_file_type'] = '.gif,.png,.jpg,.jpeg,.jfif'; //This config is using to allow the image type by regarding file input type where we are uploading only the images (portfolio standalone page,featured project cover pciture,user profile cover picture,avatar,portfolio gallery images)

// making the config for meta title/meta description/routing
// For home page 
$config['home_page_title_meta_tag'] = 'Home | Travai.cz';
$config['home_page_description_meta_tag'] = 'Home je na Travai.cz'; // 

// For contact us page 
$config['contact_us_page_title_meta_tag'] = 'Contact Us | Travai.cz';
$config['contact_us_page_description_meta_tag'] = 'Contact Us je na Travai.cz'; // 
$config['contact_us_page_url'] = 'contact-us';

// For faq page 
$config['faq_page_title_meta_tag'] = 'Faq | Travai.cz';
$config['faq_page_description_meta_tag'] = 'Faq je na Travai.cz'; // 
$config['faq_page_url'] = 'faq';

// For about us page 
$config['about_us_page_title_meta_tag'] = 'About Us | Travai.cz';
$config['about_us_page_description_meta_tag'] = 'About Us je na Travai.cz'; // 
$config['about_us_page_url'] = 'about-us';

// For terms and conditions page 
$config['terms_and_conditions_page_title_meta_tag'] = 'Terms and Conditions | Travai.cz';
$config['terms_and_conditions_page_description_meta_tag'] = 'Terms and Conditions je na Travai.cz'; // 
$config['terms_and_conditions_page_url'] = 'terms-and-conditions';

// For code of conduct page 
$config['code_of_conduct_page_title_meta_tag'] = 'Code of Conduct | Travai.cz';
$config['code_of_conduct_page_description_meta_tag'] = 'Code of Conduct je na Travai.cz'; // 
$config['code_of_conduct_page_url'] = 'code-of-conduct';

// For privacy and policy page 
$config['privacy_policy_page_title_meta_tag'] = 'privacy policy | Travai.cz';
$config['privacy_policy_page_description_meta_tag'] = 'privacy policy je na Travai.cz'; // 
$config['privacy_policy_page_url'] = 'privacy-policy';

// For trust and safety page 
$config['trust_and_safety_page_title_meta_tag'] = 'trust and safety | Travai.cz';
$config['trust_and_safety_page_description_meta_tag'] = 'trust and safety je na Travai.cz'; // 
$config['trust_and_safety_page_url'] = 'trust-and-safety';

// For referral program page 
$config['referral_program_page_title_meta_tag'] = 'referral program | Travai.cz';
$config['referral_program_page_description_meta_tag'] = 'referral program je na Travai.cz'; // 
$config['referral_program_page_url'] = 'referral-program';

// For secure payments process page 
$config['secure_payments_process_page_title_meta_tag'] = 'secure payments process | Travai.cz';
$config['secure_payments_process_page_description_meta_tag'] = 'secure payments process je na Travai.cz'; // 
$config['secure_payments_process_page_url'] = 'secure-payments-process';

// For fees and charges page 
$config['fees_and_charges_page_title_meta_tag'] = 'fees and charges | Travai.cz';
$config['fees_and_charges_page_description_meta_tag'] = 'fees and charges je na Travai.cz'; // 
$config['fees_and_charges_page_url'] = 'fees-and-charges';

// For we vs them page 
$config['we_vs_them_page_title_meta_tag'] = 'we vs them | Travai.cz';
$config['we_vs_them_page_description_meta_tag'] = 'we vs them je na Travai.cz'; // 
$config['we_vs_them_page_url'] = 'we-vs-them';

// this config is using to set "WE ARE AVAILABLE HERE" section for all presentation pages
$config['presentation_pages_phone_btn_txt'] = 'Telefon (+420) 777 777 777';// Telefon
$config['presentation_pages_contact_us_btn_txt'] = 'Napsat zprávu';// Write a message

$config['footer_company_facebook_page_url'] = 'https://www.facebook.com/';// Facebook footer link
$config['footer_company_twitter_page_url'] = 'https://twitter.com/';// Twitter footer link
$config['footer_company_linkedin_page_url'] = 'https://www.linkedin.com/';// LinkedIn footer link

?>