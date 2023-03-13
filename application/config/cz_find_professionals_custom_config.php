<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Meta Variables 
|--------------------------------------------------------------------------
| 
*/

################ Meta Config Variables for find professionals page ###########
/* Filename: application\modules\find_professionals\controllers\Find_professionals.php */
/* Controller: Find_professionals Method name: index */
$config['find_professionals_page_title_meta_tag'] = 'Najít odborníky z celé ČR - Travai.cz';

$config['find_professionals_page_description_meta_tag'] = 'Rychle najdete a najmete odborníky z rostoucí sítě špičkových odborníků celé ČR pro projekty a volné pracovní pozice.';

################ Url Routing Variables for find professionals page ###########
/* Filename: application\modules\find_professionals\controllers\Find_professionals.php */
/* Filename: application\modules\find_professionals\views\index.php */
$config['find_professionals_page_url'] = 'seznam-odborniku';

$config['find_professionals_user_hourly_rate_per_hour'] = '/hod';

$config['find_professionals_search_keyword_placeholder'] = 'např. administrativa, řidič, doktor, manažer...';

$config['find_professionals_search_locality_placeholder'] = 'např. Jihočeský kraj, Olomoucký kraj, Brno, Jihlava...';

// variable for no professionals avaiablable and no search found
$config['find_professionals_no_professionals_available_message'] = '<h4>V tento okamžik nejsou k dispozici žádní odborníci.</h4>';

$config['find_professionals_search_no_results_returned_message'] = '<h4>Vyhledávání neodpovídají žádné výsledky.</h4><p>Upravte výběr a zkuste znovu.</p>';

// variable to display budget
$config['find_professionals_rate_range_and'] = 'až';

$config['find_professionals_rate_range_more_then'] = 'více než';

$config['find_professionals_rate_per_hour'] = '/hod';

$config['find_professionals_search_btn_txt'] = 'Hledat';

// variables for clear button text
$config['find_professionals_clear_search_btn_text'] = 'Zrušit hledání';

$config['find_professionals_clear_category_btn_text'] = 'Zrušit výběr';

$config['find_professionals_clear_filter_btn_text'] = 'Zrušit filtry';

// Variable to manage filter dropdown option name
$config['find_professionals_account_type_dropdown_option_name'] = 'Typ účtu';

$config['find_professionals_hourly_rate_dropdown_option_name'] = 'Hodinová sazba';

$config['find_professionals_profile_last_update_time_dropdown_option_name'] = 'Profile last update time';
$config['find_professionals_user_registration_time_dropdown_option_name'] = 'User Registration';

// variable for filter drop-down
$config['find_professionals_account_type_dropdown_list_options'] = ['account_type_all' => 'vše', '1' => 'osobní' , '2' => 'firemní'];

$config['find_professionals_profile_last_update_time_dropdown_list_options'] = [
 'profile_last_update_time_all' => 'vše',
 'during_last_24_hours' => 'during last 24 hrs',
 'during_last_3_days' => 'during last 3 days',
 'during_last_7_days' => 'during last 7 days',
 'during_last_15_days' => 'during last 15 days',
 'during_last_30_days' => 'during last 30 days'
];

$config['find_professionals_user_registration_time_dropdown_list_options'] = [
 'user_registration_time_all' => 'vše',
 'during_last_24_hours' => 'during last 24 hrs',
 'during_last_7_days' => 'during last 7 days',
 'during_last_15_days' => 'during last 15 days',
 'during_last_30_days' => 'during last 30 days',
 'during_last_60_days' => 'during last 60 days',
];

// label text
$config['find_professionals_search_professionals_name'] = 'Hledat podle jména';

$config['find_professionals_searching_type'] = 'Možnost hledání';

$config['find_professionals_searching_type_include_txt'] = 'zahrnovat';

$config['find_professionals_searching_type_exclude_txt'] = 'vylučovat';

$config['find_professionals_professionals_categories_menu_name'] = 'Činnosti odborníků';

$config['find_professionals_show_more_professionals_categories_menu_name'] = 'Činnosti <span>odborníků<small>( + )</small></span>';
$config['find_professionals_hide_extra_professionals_categories_menu_name'] = 'Činnosti <span>odborníků<small>( - )</small></span>';

$config['find_professionals_professionals_categories_all_categories_txt'] = 'Všechny kategorie';


$config['find_professionals_show_more_subcategories_text'] = 'ukázat další podkategorie (+{remaining_subcategory} podkategorie)';

$config['find_professionals_show_less_subcategories_text'] = 'skrýt další podkategorie';

$config['find_professionals_show_more_categories_text'] = 'ukázat další kategorie (+{remaining_category} kategorie)';

$config['find_professionals_show_less_categories_text'] = 'skrýt další kategorie';

// These config are using for show the text on find professsional page (loggedin+logged)
$config['find_professionals_show_more_search_options_text'] = 'zobrazit více možností hledání <small>( + )</small>';

$config['find_professionals_hide_extra_search_options_text'] = 'zavřít více možností hledání <small>( - )</small>';

$config['find_professionals_loader_display_text'] = 'vyhledávání...';

?>