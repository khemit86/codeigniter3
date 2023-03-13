<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//Left navigation Menu name
$config['pa_user_left_nav_portfolio'] = 'Portfolio';
$config['ca_user_left_nav_portfolio'] = 'Portfolio';
$config['ca_app_user_left_nav_portfolio'] = 'Portfolio';

/*
|--------------------------------------------------------------------------
| Meta Variables 
|--------------------------------------------------------------------------
| 
*/

################ Meta Config Variables for portfolio page ###########
/* Filename: application\modules\dashboard\controllers\Dashboard.php */
/* Controller: user Method name: index */

$config['portfolio_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Správa portfolia';
$config['portfolio_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Správa portfolia';

//heading title
$config['user_portfolio_section_headline_title'] = 'Portfolio';

//portfolio initial view
$config['user_portfolio_section_initial_view_add_headline_title'] = 'Přidat portfolio';
$config['user_portfolio_section_initial_view_description'] = 'Portfolio je další nejvíce atraktivní sekcí na vaší profilové stránce. Sekce portfolia je skvělý způsob, jak udělat nejlepší první dojem a také vám pomůže budovat značku, přilákat nové klienty a zároveň prezentovat svou nejlepší práci dobře uspořádaným a jasně zobrazeným způsobem. Ať už právě začínáte ve svém oboru nebo máte za sebou léta zkušeností, pokaždé návštěvníci ocení prezentaci jakékoli realizace, kterou jste kdy úspěšně dokončili.';


//Portfolio title
$config['user_portfolio_section_portfolio_title'] = 'Název';

$config['user_portfolio_section_portfolio_title_placeholder'] = 'Rekonstrukce domu, vytvoření loga, hlídání dětí';

$config['user_portfolio_section_portfolio_title_required_field_error_message'] = 'název portfolia je povinný';

$config['user_portfolio_section_portfolio_title_characters_minimum_length_characters_limit'] = 2;
$config['user_portfolio_section_portfolio_title_characters_maximum_length_characters_limit'] = 50;
$config['user_portfolio_section_portfolio_title_characters_minimum_length_validation_message'] = 'povinné je minimálně '.$config['user_portfolio_section_portfolio_title_characters_minimum_length_characters_limit'].' znaků';


//Description
$config['user_portfolio_section_description'] = 'Popis';
$config['user_portfolio_section_description_required_field_error_message'] = 'popis portfolia je povinný';


//Referrence
$config['user_portfolio_section_referrence'] = 'Reference';

$config['user_portfolio_section_referrence_placeholder'] = 'http(s)://odkaz-na-referenci';

$config['user_portfolio_section_invalid_reference_url_field_error_message'] = 'URL adresa není správně zadaná';


//Tags
$config['user_portfolio_section_tags'] = 'Tagy';


//pictures
$config['user_portfolio_section_upload_images'] = 'Nahrát obrázek';

$config['user_portfolio_section_pictures'] = 'obrázek';




//page URL
$config['portfolio_page_url'] = 'portfolio';

$config['portfolio_standalone_page_url'] = 'portfolio-detail';

$config['portfolio_section_add_another_portfolio_btn_txt'] ="Přidat další portfolio";

$config['user_edit_portfolio_entry_already_deleted'] = "Portfolio nelze upravit. Již bylo smazáno."; 

$config['user_update_cover_picture_on_portfolio_standalone_page_entry_already_deleted'] = "Nelze provést tuto volbu. Aktuální portfolio bylo smazáno.";

$config['delete_portfolio_confirmation_project_modal_body'] = 'Opravdu chcete smazat portfolio?';


$config['user_portfolio_section_portfolio_image_invalid_file_extension_validation_message'] = "typ souboru není podporován";

$config['user_portfolio_standalone_page_upload_cover_picture_btn_txt'] = 'Nahrát obrázek portfolia';

$config['user_portfolio_standalone_page_upload_new_cover_picture_btn_txt'] = 'Vybrat nový obrázek';


$config['user_portfolio_standalone_page_cover_picture_allowed_file_extension_validation_message'] = 'Typ souboru není povolen.';

$config['user_portfolio_standalone_page_cover_picture_size_validation_message'] = "Minimální velikost obrázku musí být {max_width}x{max_height}";

$config['user_portfolio_page_free_membership_subscriber_max_portfolio_entries_membership_upgrade_calltoaction'] = 'dosáhli jste maximálního počtu portfolio slotů v Základním členství. zvyšte své členství na Perfektní, které nabízí 15 slotů. <a href="{membership_page_url}">změnit zde...</a>';

?>