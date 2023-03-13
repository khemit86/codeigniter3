<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//Left navigation Menu name
$config['pa_user_left_nav_portfolio'] = 'Portfolio';
$config['ca_user_left_nav_portfolio'] = 'Portfolio Comp';
$config['ca_app_user_left_nav_portfolio'] = 'APP:Portfolio Comp';

/*
|--------------------------------------------------------------------------
| Meta Variables 
|--------------------------------------------------------------------------
| 
*/

################ Meta Config Variables for portfolio page ###########
/* Filename: application\modules\dashboard\controllers\Dashboard.php */
/* Controller: user Method name: index */
$config['portfolio_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | portfolio Management Title Meta Tag';
$config['portfolio_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | portfolio Management Description Meta Tag';

//heading title
$config['user_portfolio_section_headline_title'] = 'Portfolio';

//portfolio initial view
$config['user_portfolio_section_initial_view_add_headline_title'] = 'Add New Portfolio';
$config['user_portfolio_section_initial_view_description'] = 'EN - Portfolio je další nejvíce atraktivní sekcí na vaší profilové stránce. Sekce portfolia je skvělým způsobem, jak udělat nejlepší první dojem a také vám pomůže budovat značku, přilákat nové klienty a zároveň prezentovat svou nejlepší práci dobře uspořádaným a jasně zobrazeným způsobem. Ať už právě začínáte ve svém oboru nebo máte za sebou léta zkušeností, pokaždé návštěvníci ocení prezentaci jakékoliv realizace, kterou jste kdy úspěšně dokončili.';

//portfolio popup Add New portfolio heading title
$config['user_portfolio_section_popup_add_headline_title'] = 'Add New Portfolio';
$config['user_portfolio_section_popup_edit_headline_title'] = 'Edit Portfolio';

$config['user_portfolio_section_portfolio_title_required_field_error_message'] = 'Required field';
$config['user_portfolio_section_portfolio_title'] = 'Title';
$config['user_portfolio_section_portfolio_title_placeholder'] = 'Ex. web Design';

//Referrence
//$config['user_portfolio_section_reference_url_required_field_error_message'] = 'Required field';
$config['user_portfolio_section_invalid_reference_url_field_error_message'] = 'Invalid refrence url.';
$config['user_portfolio_section_referrence'] = 'Referrence';
$config['user_portfolio_section_referrence_placeholder'] = 'Ex. http://xyz.com';


//$config['user_portfolio_section_tag_required_field_error_message'] = 'Required field';
$config['user_portfolio_section_tags'] = 'Tags';


$config['user_portfolio_section_description_required_field_error_message'] = 'Required field';
$config['user_portfolio_section_description'] = 'Description';


//pictures

$config['user_portfolio_section_pictures'] = 'Pictures';
$config['user_portfolio_section_upload_images'] = 'Upload Images';

//page URL
$config['portfolio_page_url'] = 'portfolio';
$config['portfolio_standalone_page_url'] = 'portfolio-detail';


$config['portfolio_section_add_another_portfolio_btn_txt'] ="Add Another portfolio";


$config['user_edit_portfolio_entry_already_deleted'] = "Request is invalid. you cant edit this entry, as it has been already deleted.";

$config['user_update_cover_picture_on_portfolio_standalone_page_entry_already_deleted'] = "request is invalid. this portfolio entry has been deleted"; 

$config['delete_portfolio_confirmation_project_modal_body'] = 'Are you sure you want to delete portfolio?';

$config['delete_portfolio_confirmation_project_modal_title'] = 'Delete portfolio';


$config['user_portfolio_section_portfolio_image_invalid_file_extension_validation_message'] = "The file type you are trying to upload is not allowed!";


// button text for cover picture of portfolio standalone page

$config['user_portfolio_standalone_page_upload_cover_picture_btn_txt'] = 'upload cover picture';

$config['user_portfolio_standalone_page_upload_new_cover_picture_btn_txt'] = 'upload new cover picture';


$config['user_portfolio_standalone_page_cover_picture_allowed_file_extension_validation_message'] = 'File type is not allowed';

$config['user_portfolio_standalone_page_cover_picture_size_validation_message'] = "Minimální velikost obrázku musí být {max_width}x{max_height}";

$config['user_portfolio_page_free_membership_subscriber_max_portfolio_entries_membership_upgrade_calltoaction'] = 'English: Byt číslo 1306 na Senovážném náměstí v centru Prahy se stal místem, kde se běžně řešilo, kdo by v tuzemském sportu mohl dostat podporu v řádech milionů korun. <a href="{membership_page_url}">Obchodními podmínkami</a> Zásadami ochrany osobních údajů';

?>