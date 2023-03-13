<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| 404 generic page custom messages 
|--------------------------------------------------------------------------
| 
*/

$config['404_page_title_meta_tag'] = 'Travai.cz - 404 Stránka je nedostupná';
$config['404_page_description_meta_tag'] = 'Travai.cz - 404 Stránka je nedostupná';

$config['404_page_heading'] = 'Stránka je nedostupná...';

$config['message_404_without_login'] = 'Odkaz, na který jste klikli je nefunkční, stránka neexistuje.<br><br>Pro pokračování <a href="/">klikněte zde...</a>';

$config['message_404_with_login'] = 'Odkaz, na který jste klikli je nefunkční, stránka neexistuje.<br><br>Pro pokračování <a href="/">klikněte zde...</a>';

// this config are using for shows the footer text  on default 404 error page(views/404defaultpage/404_default.php),system generated 404 page(ex->views/errors/html/error_404_production.php),hidden project default page (modules/projects/view/hidden_project_default_page.php)
$config['footer_message_404_page'] = 'Dosáhněte (téměř) čehokoli, pomůžeme vám dosáhnout úspěchu!';

// These config are using as a meta title/meta description for system generated error pages
// view files-> /application/views/errors/html
$config['system_generated_404_page_title_meta_tag'] = 'Travai.cz - 404 Stránka je nedostupná';

$config['system_generated_404_page_description_meta_tag'] = 'Travai.cz - 404 Stránka je nedostupná';

$config['system_generated_404_page_heading'] = 'Stránka je nedostupná...';

$config['system_generated_404_page_message'] = 'Odkaz, na který jste klikli je nefunkční, stránka neexistuje.<br><br>Pro pokračování <a href="/">klikněte zde...</a>';

$config['or'] = 'nebo';

// this config is using to show cookiew text on header
$config['accept_cookies_banner_txt'] = 'Při poskytování služeb nám pomáhají cookies. Používáním portálu s tímto souhlasíte a vyjadřujete souhlas se sběrem osobních dat za účelem zlepšení fungování portálu. <a href="{privacy_policy_page_url}" target="blank">Další informace.</a>';

// this config is using for scrolling text above the four tab used on top side of home page
//view file application/views/home.php
$config['home_page_top_scrolling_text_correspondent_tab1'] = 'Řízení procesu náboru z jednoho místa. Najděte, sledujte a najímejte odborníky z celé ČR.'; 
$config['home_page_top_scrolling_text_correspondent_tab2'] = 'Najít odborníka nebylo nikdy snazší. Objevte a najměte si nejlepší odborníky z České republiky.'; 
$config['home_page_top_scrolling_text_correspondent_tab3'] = 'Jediná platforma v ČR, která vám dává příležitost maximalizovat svůj skutečný potenciál.'; 
$config['home_page_top_scrolling_text_correspondent_tab4'] = 'Platforma, která vám dává možnost získat nové zaměstnání a zvětšit svůj potenciál.'; 

// config are using on url when user click on top tab on home page regardinh scrolling text
$config['home_page_top_tab2_url_txt'] = 'tab222222';
$config['home_page_top_tab3_url_txt']= 'tab322222';
$config['home_page_top_tab4_url_txt']= 'tab42222';



// Common button text define Start 
$config['signup_btn_txt'] = 'Zaregistrovat';

$config['signin_btn_txt'] = 'Přihlásit';

$config['signout_btn_txt'] = 'Odhlásit';

$config['save_btn_txt'] = 'Uložit';

$config['edit_btn_txt'] = 'Upravit';

$config['cancel_btn_txt'] = 'Zrušit';

$config['remove_btn_txt'] = 'Odstranit';

$config['reply_btn_txt'] = 'Odpověď';

$config['reset_btn_txt'] = 'Smazat';

$config['delete_btn_txt'] = 'Smazat';

$config['close_btn_txt'] = 'Zavřít';

$config['accept_btn_txt'] = 'Přijmout';

$config['decline_btn_txt'] = 'Odmítnout';

$config['send_btn_txt'] = 'Odeslat';

$config['award_btn_txt'] = 'Udělit';

$config['rate_btn_txt'] = 'Odeslat';

$config['view_all_btn_txt'] = 'zobrazit více'; // this config is using in header small notification window 

$config['next_btn_txt'] = 'Další';// this config is using on account managament->account login details page
// config variables success/error popup.
$config['popup_alert_heading'] = "Upozornění!"; // this config is using for general error popup heading 


// config show where we are showing drop down actions
$config['action'] = 'Možnosti';

//Project Owner Details Contact Me Button
$config['contactme_button'] = 'Kontaktovat';

$config['view_all_projects'] = "zobrazit všechny projekty";//This config is using on my project section(all tabs on po/sp view) on dashboard page

$config['show_more_txt'] = "zobrazit více";

//show less button text
$config['show_less_txt'] = "zobrazit méně";

//These config are using to all over the site for paging text.
$config['showing_pagination_txt'] = 'zobrazeno';

$config['out_of_total_pagination_txt'] = 'z celkem';

// This message will be visible to user when in one tab he login with one account then he open other tab and logout from this account and again login with different account then switch back to first tab and do some critical action on any page

//This config are using to show the error message when session is conflict(example from one tab sp update bid now from second tab logout and login with new sp now go to first tab and trying to update bid of first sp)
$config['different_users_session_conflict_message'] = 'Přihlášení není platné. Stránka bude aktualizována.';

// This variable is used to display error message in popup and chat when user try to upload attachment and users folder not exist
$config['users_folder_not_exist_error_message'] = 'došlo k chybě. opakujte později.';

//Month listed here
$config['calendar_months'] = array("1"=>'Leden', "2"=>'Únor', "3"=>'Březen', "4"=>'Duben', "5"=>'Květen', "6"=>'Červen', "7"=>'Červenec', "8"=>'Srpen', "9"=>'Září', "10"=>'Říjen', "11"=>'Listopad', "12"=>'Prosinec');

$config['calendar_months_short_name'] = array("January"=>'Led', "February"=>'Úno', "March"=>'Bře', "April"=>'Dub', "May"=>'Kvě', "June"=>'Čvn', "July"=>'Čvc', "August"=>'Srp', "September"=>'Zář', "October"=>'Říj', "November"=>'Lis', "December"=>'Pro');


$config['calendar_weekdays_short_name'] = array("Sunday"=>'Ne', "Monday"=>'Po', "Tuesday"=>'Út', "Wednesday"=>'St', "Thurshday"=>'Čt', "Friday"=>'Pá', "Saturday"=>'So');

$config['calendar_weekdays_long_name'] = array("1"=>'Pondělí', "2"=>'Úterý', "3"=>'Středa', "4"=>'Čtvrtek', "5"=>'Pátek', "6"=>'Sobota', "7"=>'Neděle');

//common message for character remaing for inputs 
$config['characters_remaining_message'] = 'zbývajících znaků';

$config['present_txt'] = 'současnosti'; //USED ON WORK EXPERIENCE SECTION


// config based on number of days
$config['1_day'] = 'den';

$config['2_4_days'] = 'dny';

$config['more_than_or_equal_5_days'] = 'dní';


// config based on number of hours
$config['1_hour'] = 'hodina';

$config['2_4_hours'] = 'hodiny';

$config['more_than_or_equal_5_hours'] = 'hodin';


// config based on number of minutes
$config['1_minute'] = 'minuta';

$config['2_4_minutes'] = 'minuty';

$config['more_than_or_equal_5_minutes'] = 'minut';

// config based on number of seconds
$config['1_second'] = 'vteřina';

$config['2_4_seconds'] = 'vteřiny';

$config['more_than_or_equal_5_seconds'] = 'vteřin';

$config['1_month'] = 'měsíc';
$config['2_4_months']= 'měsíce';
$config['more_than_or_equal_5_months'] ='měsíců';

$config['1_year'] = 'rok';
$config['2_4_years'] =  'roky';
$config['more_than_or_equal_5_years']= 'let';

$config['continue_click_here_txt'] = 'Pro pokračování <a href="/">klikněte zde...</a>';

//config for attachment name as prefix(upload bid attachment/project attachment/chat attachment)
$config['attachment_prefix_text'] = "trv_";

// this config is used for confirmation message for user (example when po award bid/sp accept/decline)
$config['user_confirmation_check_box_txt'] = "Ano, souhlasím";

$config['select_country'] = 'vybrat zemi';

$config['select_county'] = 'vybrat kraj';

$config['select_locality'] = 'vybrat obec';

$config['select_postal_code'] = 'vybrat PSČ';



$config['membership_plans_names'] = [
1 => 'Základní', 
4 => 'Perfektní'
];

// this alert message is showing when user upload blank attachment.
$config['upload_blank_attachment_alert_message'] = "Nelze nahrát prázdnou přílohu.";


$config['load_more_results'] = "zobrazit více";

$config['drop_zone_drag_and_drop_files_area_message_txt'] = 'přetažení souboru...'; // This variable will text will display to user when he drag and drop any file into drop area on chat room / small chat window /  project detail page

##############################################################################
//post project and bid attachment management there are dedicated varibles in place. 
//Below config are using only those place where we are allowing only the pictures

$config['pictures_allowed_extensions_js'] = '["jpg", "gif", "png", "jpeg", "jfif"]'; //This config is using to check the image type by javascript where we are uploading only the images (portfolio standalone page,featured project cover pciture,user profile cover picture,avatar,portfolio gallery images)


$config['pictures_allowed_extensions_input_file_type'] = '.gif,.png,.jpg,.jpeg,.jfif'; //This config is using to allow the image type by regarding file input type where we are uploading only the images (portfolio standalone page,featured project cover pciture,user profile cover picture,avatar,portfolio gallery images)
//ex:<input type="file" accept=".gif,.png,.jpg,.jpeg" style="display:none;" class="cover_picture_input" />


// text for find professsional/ find project page on header menu/user dashboard


$config['header_top_navigation_post_project_menu_name'] = 'Vytvořit inzerát';
$config['header_top_navigation_chat_room_menu_name'] = 'Zprávy';


$config['browse_projects_txt'] = 'Pracovní pozice & Projekty';
$config['browse_service_providers_txt'] = 'Seznam Odborníků';
$config['manage_sent_invitations_and_affiliate_income'] = 'Správa pozvání & Partnerský program';

// making the config for meta title/meta description/routing
//all these variables are loaded from user controller file - all actions exists into the file
// For home page 
$config['home_page_title_meta_tag'] = 'Příležitosti k růstu - nabízení, najítí a získání pracovních míst a projektů';
$config['home_page_description_meta_tag'] = 'Travai.cz - Platforma pro nabízení a získání pracovních míst a projektů. Najití ověřených odborníků pro všechny potřeby.';

// For contact us page 
$config['contact_us_page_title_meta_tag'] = 'Kontakt | Travai.cz';
$config['contact_us_page_description_meta_tag'] = 'Telefon: (+420) 515 910 910. Volejte pondělí až neděle 8:00 - 18:00 hod. E-mail: podpora@travai.cz.';
$config['contact_us_page_url'] = 'kontakt';

// For faq page 
$config['faq_page_title_meta_tag'] = 'Informace pro uživatele - Často kladené otázky (F.A.Q.) | Travai.cz';
$config['faq_page_description_meta_tag'] = 'Informace pro uživatele - Najděte všechny informace o Travai. V případě dotazů nás kontaktujte telefonicky.';
$config['faq_page_url'] = 'faq';

// For about us page 
$config['about_us_page_title_meta_tag'] = 'O Nás | Travai.cz';
$config['about_us_page_description_meta_tag'] = 'Zjistěte vizi a misi, díky nimž je náš koncept tak výjimečný. Poskytujeme prostředí pro lidi a společnosti, aby se mohli spojit, komunikovat a spolupracovat.'; 
$config['about_us_page_url'] = 'o-nas';

// For terms and conditions page 
$config['terms_and_conditions_page_title_meta_tag'] = 'Obchodní podmínky | Travai.cz';
$config['terms_and_conditions_page_description_meta_tag'] = 'V tomto dokumentu najdete všechny důležitá pravidla a zákony, podle kterých se každý uživatel musí řídit a dodržovat na Travai.cz';
$config['terms_and_conditions_page_url'] = 'obchodni-podminky';

// For code of conduct page 
$config['code_of_conduct_page_title_meta_tag'] = 'Kodex chování | Travai.cz';
$config['code_of_conduct_page_description_meta_tag'] = 'V tomto dokumentu najdete všechny důležitá pravidla a zákony, podle kterých se každý uživatel musí řídit a dodržovat na Travai.cz';
$config['code_of_conduct_page_url'] = 'kodex-chovani';

// For privacy and policy page 
$config['privacy_policy_page_title_meta_tag'] = 'Zásady ochrany osobních údajů | Travai.cz';
$config['privacy_policy_page_description_meta_tag'] = 'Dokument zahrnující všechny informace o používání a ochraně údajů na Travai.cz';
$config['privacy_policy_page_url'] = 'zasady-ochrany-osobnich-udaju';


// For trust and safety page 
$config['trust_and_safety_page_title_meta_tag'] = 'Důvěra a bezpečnost - vaše bezpečnost je naší prioritou | Travai.cz';
$config['trust_and_safety_page_description_meta_tag'] = 'Poskytujeme osobám a společnostem příležitosti k růstu tím, že nabízíme bezpečné online prostředí, kde se mohou setkat a spolupracovat.';
$config['trust_and_safety_page_url'] = 'duvera-bezpecnost';

// For referral program page 
$config['referral_program_page_title_meta_tag'] = 'Partnerský Program | Travai.cz';
$config['referral_program_page_description_meta_tag'] = 'Svezte se na nové vlně pracovního trhu. Kdokoli se může stát propagátorem Travai a vytvořit si tak pasivním příjem.';
$config['referral_program_page_url'] = 'partnersky-program';


// For secure payments process page 
$config['secure_payments_process_page_title_meta_tag'] = 'Travai Bezpečná Platba - Správa plateb v digitální éře | Travai.cz';
$config['secure_payments_process_page_description_meta_tag'] = 'Jednoduchý platební systém ve prospěch všech stran.';
$config['secure_payments_process_page_url'] = 'tbp';

// For fees and charges page 
$config['fees_and_charges_page_title_meta_tag'] = 'Poplatky a Ceny | Travai.cz';
$config['fees_and_charges_page_description_meta_tag'] = 'Dokument zahrnující všechny informace o poplatcích a cenách nabízených služeb Travai.cz';
$config['fees_and_charges_page_url'] = 'poplatky-ceny';

// For we vs them page 
$config['we_vs_them_page_title_meta_tag'] = 'My vs. Ostatní | Travai.cz';
$config['we_vs_them_page_description_meta_tag'] = 'Informace prezentující náš model. Najděte v čem je náš model jiný, než standardní pracovní portál a výhody našeho systému.';
$config['we_vs_them_page_url'] = 'my-ostatni';

// this config is using to set "WE ARE AVAILABLE HERE" section for all presentation pages
$config['presentation_pages_phone_btn_txt'] = 'Telefon (+420) 515 910 910';// Telefon
$config['presentation_pages_contact_us_btn_txt'] = 'Napsat zprávu';// Write a message

$config['footer_company_facebook_page_url'] = 'https://facebook.com/travai.cz';// Facebook footer link
$config['footer_company_twitter_page_url'] = 'https://twitter.com/travai_cz';// Twitter footer link
$config['footer_company_linkedin_page_url'] = 'https://linkedin.com/company/travai-cz';// LinkedIn footer link

?>