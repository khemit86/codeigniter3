<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//Left navigation Menu name
$config['pa_user_left_nav_account_management'] = 'Správa účtu';
$config['ca_user_left_nav_account_management'] = 'Správa účtu';

$config['account_management_left_nav_account_overview'] = 'Detaily účtu';

$config['account_management_left_nav_avatar'] = 'Profilová fotka';


$config['pa_user_account_management_left_nav_address'] = 'Adresa';
$config['ca_user_account_management_left_nav_address'] = 'Adresa a provozovny';

$config['account_management_left_nav_contact_details'] = 'Kontakty';

$config['account_management_left_nav_account_login_details'] = 'Přihlašovací údaje';

$config['account_management_left_nav_close_account'] = 'Zavření účtu';


// CONFIG FOR ACCOUNT MANAGEMENT ACCOUNT OVERVIEW PAGE
$config['account_management_account_overview_page_url'] = 'detaily-uctu';

$config['account_management_account_overview_page_headline_title'] = 'Detaily účtu';

###### Meta config for account management account overview page #######
$config['account_management_account_overview_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Správa účtu';
$config['account_management_account_overview_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Nastavení pro správu uživatelských účtů';

###### Config for tab names on account management account overview page #######
$config['account_management_account_overview_page_account_details_tab'] = 'Správa účtu';
$config['account_management_account_overview_page_membership_tab'] = 'Členství';


// CONFIG FOR ACCOUNT MANAGEMENT AVATAR PAGE
$config['account_management_avatar_details_page_url'] = 'profilova-fotka';

$config['account_management_avatar_page_headline_title'] = 'Profilová fotka';
###### Meta config for account management avatar page #######
$config['account_management_avatar_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Profilová fotka';
$config['account_management_avatar_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Nastavení pro správu uživatelských účtů (Profilová fotka)';

$config['user_profile_avatar_allowed_file_extension_validation_message'] = 'typ souboru není povolen';

//Account management avatar page button text
$config['user_profile_upload_avatar_btn_txt'] = 'Vybrat obrázek';
$config['user_profile_upload_new_avatar_btn_txt'] = 'Nahrát nový obrázek';

// CONFIG FOR ACCOUNT MANAGEMENT ADDRESS PAGE
$config['account_management_address_details_page_url'] = 'adresa';
###### Meta config for account management address page #######
$config['pa_account_management_address_details_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Správa adresy';
$config['ca_account_management_address_details_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Správa adresy';

$config['pa_account_management_address_details_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Nastavení pro správu uživatelské adresy';
$config['ca_account_management_address_details_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Nastavení pro správu uživatelské adresy';

// for app user
$config['ca_app_account_management_address_details_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Správa adresy';
$config['ca_app_account_management_address_details_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Nastavení pro správu uživatelské adresy';


//account management address page text
$config['pa_account_management_address_details_view_title'] = 'Adresa';
$config['pa_account_management_headline_title_address'] = 'Adresa';
$config['pa_account_management_address_details_initial_view_content'] = 'Uvedením adresy zkvalitňujete svůj profil pro ostatní uživatele, kteří mohou hledat odborníka ve svém kraji či obci. Váš profil může být dohledatelný v seznamu odborníků pomocí filtru dané lokality.';

$config['ca_account_management_address_details_view_title'] = 'Adresa a provozovny';
$config['ca_account_management_headline_title_address'] = 'Adresa a provozovny';
$config['ca_account_management_address_details_initial_view_content'] = 'Uvedením adresy společnosti a dalších provozoven zkvalitňujete profil své společnosti pro ostatní uživatele a návštevníky, kteří mohou hledat společnost s vaším zaměřením ve svém kraji či obci. Váš profil společnosti může být dohledatelný v seznamu odborníků pomocí filtru dané lokality.';

// for app user
$config['ca_app_account_management_address_details_view_title'] = 'Adresa a provozovny';
$config['ca_app_account_management_headline_title_address'] = 'Adresa a provozovny';
$config['ca_app_account_management_address_details_initial_view_content'] = 'Uvedením adresy a provozovny zkvalitňujete svůj profil pro ostatní uživatele a návštevníky, kteří mohou hledat odborníka ve svém kraji či obci. Váš profil může být dohledatelný v seznamu odborníků pomocí filtru dané lokality.';


//account management address page tooltip messages
$config['account_management_address_details_street_address_tooltip'] = 'k ulici a číslu popisném může být uvedeno i číslo orientační, např. Domovní 511/20';

//account management address page functionalty validation messages and validation limits
$config['account_management_address_details_locality_required_field_error_message'] = 'obec (MČ) je povinný výběr';
$config['account_management_address_details_county_required_field_error_message'] = 'kraj je povinný výběr';
$config['account_management_address_details_country_required_field_error_message'] = 'stát je povinný výběr';
$config['account_management_address_details_postal_code_required_field_error_message'] = 'PSČ je povinný výběr';
$config['account_management_address_details_duplicate_location_error_message'] = 'nelze mít více stejných adres';
$config['account_management_address_details_street_address_maximum_length_characters_remaining_txt'] = 'zbývající znaky';

// CONFIG FOR ACCOUNT MANAGEMENT CONTACT PAGE
$config['account_management_contact_details_page_url'] = 'kontakty';
$config['account_management_contact_page_headline_title'] = 'Kontakty';

###### Meta config for account management contact details page #######
$config['account_management_contact_details_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Správa kontaktů';
$config['account_management_contact_details_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Nastavení pro správu uživatelských kontaktů';

//Account management contact page functionalty validation messages and validation limits
//For skype
$config['account_management_contact_details_skype_id_required_field_error_message'] = 'Skype je povinný';
$config['account_management_contact_details_skype_id_input_placeholder'] = 'xxxxxxx';

// For contact email
$config['account_management_contact_details_contact_email_required_field_error_message'] = 'email je povinný';
$config['account_management_contact_details_contact_email_invalid_format_field_error_message'] = 'nesprávný formát emailu';
$config['account_management_contact_details_contact_email_input_placeholder'] = 'email@nazevdomena.cz';

// For website url
$config['account_management_contact_details_website_url_required_field_error_message'] = 'web je povinný';
$config['account_management_contact_details_invalid_website_url_field_error_message'] = 'nesprávný formát url';
$config['account_management_contact_details_website_url_input_placeholder'] = 'http(s)://nazevdomena.cz';

// For additional phone number
$config['account_management_contact_details_additional_phone_number_input_placeholder'] = 'xxx xxx xxx';
$config['account_management_contact_details_additional_phone_number_required_field_error_message'] = 'číslo je povinné';


// For mobile phone number
$config['account_management_contact_details_mobile_phone_number_input_placeholder'] = 'xxx xxx xxx';
$config['account_management_contact_details_mobile_phone_number_required_field_error_message'] = 'číslo je povinné';


$config['account_management_contact_details_phone_number_input_placeholder'] = 'xxx xxx xxx';
// For phone number
$config['account_management_contact_details_phone_number_required_field_error_message'] = 'číslo je povinné';


########BELOW THE CONFIG WILL SLOWLY BY SLOWLY REMOVED ABOVE CONFIG ARE FINAL###
// meta config for account management account login detail page
$config['account_management_account_login_details_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Přihlašovací údaje';
$config['account_management_account_login_details_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Přihlašovací údaje';

$config['account_management_account_login_details_page_url'] = 'prihlasovaci-udaje';

$config['account_management_account_login_details_page_login_email_tab_txt'] = 'Přihlašovací email';
$config['account_management_account_login_details_page_login_password_tab_txt'] = 'Heslo';
$config['account_management_account_login_details_page_verfication_tab_txt'] = 'Ověření & Spárování';

$config['account_management_headline_title_contact_details'] = 'Kontakty';
$config['account_management_headline_title_account_login_details'] = 'Přihlašovací údaje';
$config['account_management_headline_title_close_account'] = 'Zavření účtu';

//Account Management Account title tab End

//Account Management Address Details tab Start
$config['account_management_address_details_tab_street_address'] = 'Ulice a číslo popisné';

$config['account_management_address_details_tab_country'] = 'Zemi';

$config['account_management_address_details_tab_reset_field'] = 'Smazat pole';

$config['account_management_address_details_tab_reset_selection'] = 'Smazat výběr';

$config['company_user_account_management_address_details_tab_add_another_business_location'] = 'Přidat další provozovnu';

//Account Management Address Details tab End

//Account Management Account Title Contact Details tab Start
$config['account_management_contact_details_page_phone_number_tab_txt'] = 'Telefonní číslo';
$config['account_management_contact_details_page_mobile_phone_number_tab_txt'] = 'Mobilní číslo';
$config['account_management_contact_details_page_addtional_phone_number_tab_txt'] = 'Další Telefonní číslo';
$config['account_management_contact_details_page_contact_email_tab_txt'] = 'Emailová adresa';
$config['account_management_contact_details_page_skype_id_tab_txt'] = 'Skype jméno';
$config['account_management_contact_details_page_website_url_tab_txt'] = 'Webová stránka';



//Account Management Account Title Contact Details tab End
$config['account_management_membership_title_manage'] = 'Spravovat';
//Account Management Membership Title End

/* config variable to show text on account management page membership section */
$config['account_management_membership_plan_heading'] = 'Druh členství:';

// For social media section for account login page
$config['account_management_account_login_details_page_social_media_verification_section_user_current_email_address_txt'] = 'Stávající přihlašovací email:';

$config['account_management_account_login_details_page_social_media_verification_section_facebook_label_txt'] = 'Facebook';
$config['account_management_account_login_details_page_social_media_verification_section_linkedin_label_txt'] = 'LinkedIn';

$config['account_management_account_login_details_page_social_media_verification_section_social_media_account_not_connected_label_txt'] = 'Není připojeno';

$config['account_management_account_login_details_page_social_media_verification_section_social_media_account_connected_label_txt'] = 'Připojeno';

$config['account_management_account_login_details_page_social_media_verification_section_no_information_publicaly_displayed_disclaimer_label_txt'] = 'Žádná z těchto přihlašovacích informací nebude veřejně zobrazena.';


$config['account_management_account_login_details_page_social_media_verification_section_connect_facebook_account_label_txt'] = 'Připojte váš Facebook profil k Travai profilu.';

$config['account_management_account_login_details_page_social_media_verification_section_connect_facebook_account_btn_txt'] = 'Připojit Facebook profil';


$config['account_management_account_login_details_page_social_media_verification_section_connect_linkedin_account_label_txt'] = 'Připojte váš LinkedIn profil k Travai profilu.';

$config['account_management_account_login_details_page_social_media_verification_section_connect_linkedin_account_btn_txt'] = 'Připojit LinkedIn profil';


$config['account_management_account_login_details_page_social_media_verification_section_user_connected_facebook_account_confirmation_label_txt'] = 'Připojený Facebook profil: {user_facebook_email_id}';

$config['account_management_account_login_details_page_social_media_verification_section_user_connected_linkedin_account_confirmation_label_txt'] = 'Připojený LinkedIn profil: {user_linkedin_email_id}';

$config['account_management_account_login_details_page_social_media_verification_section_revoke_btn_txt'] = 'Zrušit';

//DO NOT DELETE - CATALIN 20.12.2020
//$config['account_management_account_login_details_page_social_media_verification_section_bottom_disclaimer_txt'] = "Tyto připojení pomáhají zlepšit důvěru mezi Travai uživateli a návštěvníky. Žádná z těchto informací nebudou použité pro marketingové účely.";

$config['account_management_account_login_details_page_social_media_verification_section_bottom_disclaimer_txt'] = "Žádná z těchto informací nebudou použité pro marketingové účely.";

//errors with fb and ln
$config['account_management_account_login_details_page_social_media_verification_section_connect_facebook_account_error_msg'] = 'Došlo k chybě. Opakujte připojení znovu. (fbx01095z)'; //This error message will display when we didn't get all info [ id / email] from fb which we aksed for

$config['account_management_account_login_details_page_social_media_verification_section_user_attempts_connect_already_used_facebook_account_error_msg'] = 'Došlo k chybě. Opakujte připojení znovu. (fbx0548zd7)';

$config['account_management_account_login_details_page_social_media_verification_section_connect_linkedin_account_error_msg'] = 'Došlo k chybě. Opakujte připojení znovu. (lnx01095z)';//not tested, only translated

$config['account_management_account_login_details_page_social_media_verification_section_user_attempts_connect_already_used_linkedin_account_error_msg'] = 'Došlo k chybě. Opakujte připojení znovu. (lnx0548zd7)';

############## For Update email section
//Account Management Verifications Title Start
$config['account_management_verifications_title_email_successfully_update'] = 'Emailová adresa úspěšně změněna';
//Account Management Verifications Title End

//Account Management Email Title Start
$config['account_management_email_title_update_email'] = 'Změnit emailovou adresu';

$config['account_management_email_initial_view_content'] = 'E-mailovou adresu můžete kdykoli změnit zadáním nové e-mailové adresy. Po změně se nový e-mail stane platným, který bude používán k ověření účtu při dalším přihlášení. Veškerá komunikace bude probíhat přes tuto novou adresu.';

$config['account_management_email_current_email_placeholder'] = "aktuální email";

$config['account_management_email_current_password_placeholder'] = "aktuální heslo";

$config['account_management_email_new_email_placeholder'] = "nový email";

$config['account_management_email_confirm_new_email_placeholder'] = "potvrzení nového emailu";


// For current email and current password
$config['account_management_update_email_section_current_email_required_field_error_message'] = "aktuální email je povinný";

$config['account_management_update_email_section_current_email_invalid_format_field_error_message'] = "email nemá správný formát";

$config['account_management_update_email_section_current_email_password_incorrect_combination_field_error_message'] = "email a heslo nejsou správné";

$config['account_management_update_email_section_current_password_required_field_error_message'] = "aktuální heslo je povinné";


// For new email
$config['account_management_update_email_section_new_email_required_field_error_message'] = "nový email je povinný";

$config['account_management_update_email_section_new_email_invalid_format_field_error_message'] = "nový email nemá správný formát";

$config['account_management_update_email_section_confirm_new_email_required_field_error_message'] = "ověření nového emailu je povinné";

$config['account_management_update_email_section_confirm_new_email_invalid_format_field_error_message'] = "email nemá správný formát";


$config['account_management_update_email_section_new_email_confirmation_not_match_new_email_field_error_message'] = "emaily nejsou stejné";

$config['account_management_update_email_section_new_email_match_old_email_field_error_message'] = "nový a aktuální email nemůže být stejný";

$config['account_management_update_email_section_new_email_not_unique_error_message'] = "email není platný";

// email config for update email section
$config['account_management_update_email_user_activity_log_displayed_message'] = 'Změna emailové adresy byla provedena.';

### update password section start
//Account Management Email Title End
//Account Management Password Title Start
$config['account_management_password_title_update_password'] = 'Změnit heslo';

$config['account_management_password_initial_view_content'] = 'Rady pro vytváření hesla: používat více znaků / využít symboly nahrazující písmena ve slovech / použít čísla, symboly, velká a malá písmena. Nedávejte své přístupové údaje nikomu jinému.<br><br><strong>Nezapomeňte pravidelně měnit heslo.</strong>';


$config['account_management_password_current_email_placeholder'] = "aktuální email";
$config['account_management_password_current_password_placeholder'] = "aktuální heslo";

$config['account_management_password_new_password_placeholder'] = "nové heslo";

$config['account_management_password_confirm_new_password_placeholder'] = "ověření nového hesla";


$config['account_management_update_password_section_current_email_required_field_error_message'] = "aktuální email je povinný";
$config['account_management_update_password_section_current_email_invalid_format_field_error_message'] = "email nemá správný formát";

$config['account_management_update_password_section_current_email_password_incorrect_combination_field_error_message'] = "email a heslo nejsou správné";

$config['account_management_update_password_section_current_password_required_field_error_message'] = "aktuální heslo je povinné";

$config['account_management_update_password_section_new_password_required_field_error_message'] = "nové heslo je povinné";


$config['account_management_update_password_section_confirm_new_password_required_field_error_message'] = "ověření nového hesla je vyžadováno";

$config['account_management_update_password_section_new_password_confirmation_not_match_new_password_field_error_message'] = "nové a ověřující nové heslo se neshodují";

$config['account_management_update_password_section_new_password_match_old_password_field_error_message'] = "aktuální a nové heslo nesmí být stejné";

$config['account_management_update_password_user_activity_log_displayed_message'] = 'Změna hesla byla provedena.';


$config['account_management_password_title_password_successfully_update'] = 'Heslo úspěšně změněno';

//Account Management Password Title End



/* config variable to show text on account management page account details section */
$config['account_management_account_details_account_type_heading'] = 'Typ účtu:';

$config['account_management_account_details_name_heading'] = 'Jméno:';

$config['account_management_account_details_company_name_heading'] = 'Název společnosti:';

$config['account_management_account_details_gender_heading'] = 'Pohlaví:';

$config['account_management_account_details_company_account_type_txt'] = 'Firemní';

$config['account_management_account_details_company_app_account_type_txt'] = 'OSVČ';



$config['account_management_account_details_personal_account_type_txt'] = 'Osobní';

$config['account_management_account_details_personal_account_type_gender_male_txt'] = 'Muž';

$config['account_management_account_details_personal_account_type_gender_female_txt'] = 'Žena';


//start section for Zavření účtu / Close Account

// meta config for account management close account page
$config['account_management_account_close_account_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Zavření účtu';
$config['account_management_account_close_account_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Zavření účtu';
$config['account_management_close_account_page_url'] = 'zavreni-uctu';


$config['account_management_close_account_page_terms_first_description_text'] = "<p>Zavřením svého účtu zabráníte dalšímu používání Travai funkcí a služeb, které jsou pouze pro registrované a přihlášené uživatele.</p>
<br>
<p>Pokud opravdu chcete zavřít svůj Travai účet, je zapotřebí ukončit tyto aktivity:</p>
<p>- finanční zůstatek na Travai kontu musí být roven 0 Kč</p>
<p>- všechny otevřené inzeráty musí být zrušeny</p>
<p>- všechna udělení na inzerát musí být zrušena</p>
<p>- všechny probíhající projektové inzeráty označit jako dokončené nebo zrušeny</p>
<p>- všechny aktivní inzeráty pracovních pozic zrušit nebo ukončit</p>
<p>- ukončit všechny aktivní platby v Travai Bezpečná Platba</p>
<p>- ukončit aktivní Řešení Sporů</p>
<br>
<p>Pro uzavření účtu opravdu nelze ukončit jen jednu z výše vypsaných aktivit a ostatní ponechat.</p>";

$config['account_management_close_account_page_terms_second_description_text'] = "<p>Ještě než definitivně ukončíte svůj účet, věnujte pozornost ještě nekterým dalším Travai funkcím, které používáte nebo máte uložené informace, jako je Travai chat (veškerá komunikace), Travai seznam kontaktů (všechny nasbírané odborníky a partnery), získávání finančních prostředků z generovaného pasivního příjmu z vaší sítě pozvání a další...</p>
<br>
<p>Před smazáním účtu si nazepomeňte přečíst Obchodní podmínky a Zásady ochrany osobních údajů.</p>";

$config['account_management_close_account_page_terms_third_description_text'] = "<p>Rádi bychom věděli, proč chcete zavřít účet, prosím vyberte důvod a napište nám zpětnou vazbu.</p>
<p>Vaši žádost o zavření účtu budeme neprodleně řešit. Během tohoto procesu můžete být kontaktováni.</p>";

$config['account_management_close_account_page_close_account_reason_of_close_txt'] = "Důvod zavření účtu";
$config['account_management_close_account_page_close_account_reason_default_option_name'] = 'vybrat důvod';
$config['account_management_close_account_page_close_account_reasons_dropdown'] = array("portál má jiné funkce, než potřebuji","nenalezení očekávaných služeb","špatní zaměstnavatelé","špatná kvalita dodavatelů","drahé služby portálu","jiné důvody");
$config['account_management_close_account_page_reason_description_txt'] = "Zpětná vazba";
$config['account_management_close_account_page_confirmation_btn_text'] = "Ano, souhlasím se zavřením účtu";


// config regarding validation
$config['account_management_close_account_page_close_account_reason_required_validation_message'] = 'výběr je povinný';
$config['account_management_close_account_page_close_account_reason_description_required_validation_message'] = 'povinné pole';

// config when user alrady sent the close account request
$config['user_close_account_request_already_sent_message'] = "Žádost o zavření účtu jste odeslali dne {close_account_request_sent_time}. Vaše žádost je v současné době posuzována našim týmem podpory a brzy budete kontaktováni.
<br><br>Pokud změníte názor a chcete stáhnout žádost o zavření účtu nebo máte jakékoli dotazy, neváhejte nás kontaktovat. Rádi Vás podpoříme v dalším používání našich nabízených služeb a uděláme vše, abychom vyřešili případné problémy.";

$config['user_close_account_request_sent_confirmation_message'] = 'Vaše žádost byla odeslána na naše oddělení podpory. Prověříme ji a brzy budete kontaktováni.';

// Config for close account confirmation popup
$config['close_account_confirmation_modal_close_account_send_request_btn_txt'] = "Odeslat";

$config['close_account_confirmation_modal_body'] = 'Pokračováním souhlasíte se zavřením svého účtu. Žádost bude odeslána našim pracovníkům podpory k vyřízení, pokud je vše v pořádku, účet bude zavřen.';

?>