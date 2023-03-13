<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Meta Variables 
|--------------------------------------------------------------------------
| 
*/

################ Meta Config Variables for find project page ###########
/* Filename: application\modules\find_project\controllers\Find_project.php */
/* Controller: Find_project Method name: index */
$config['find_project_page_title_meta_tag'] = 'Projekty a pracovní pozice - Travai.cz';

$config['find_project_page_description_meta_tag'] = 'Najití projektu nebo pracovní pozice z celé ČR, ve více než 30ti kategoriích, např. asistenty, programátory, řemeslníky...';

################ Url Routing Variables for find project page ###########
/* Filename: application\modules\find_project\controllers\Find_project.php */
/* Filename: application\modules\find_project\views\index.php */
$config['find_projects_page_url'] = 'inzeraty';


// Variable to manage filter dropdown option name
$config['find_project_project_upgrades_dropdown_option_name'] = 'Typ inzerátu';

$config['find_project_agreement_dropdown_option_name'] = 'Druh práce';

$config['find_project_fixed_budget_ranges_dropdown_option_name'] = 'Pevný rozpočet';

$config['find_project_hourly_rate_budgets_ranges_dropdown_option_name'] = 'Hodinová sazba';

$config['find_project_project_based_options_dropdown_option_name'] = 'Platba projektu';

$config['find_project_full_time_jobs_salaries_ranges_dropdown_option_name'] = 'Plat';

$config['find_project_publication_date_dropdown_option_name'] = 'Datum zveřejnění';

// variables for clear button text
$config['find_project_clear_search_btn_text'] = 'Zrušit hledání';

$config['find_project_clear_category_btn_text'] = 'Zrušit výběr';

$config['find_project_clear_filter_btn_text'] = 'Zrušit filtry';

// variable for no project avaiablable and no search found
$config['find_project_no_project_available_message'] = '<h4>V tento okamžik nejsou k dispozici žádné inzeráty.</h4>';

$config['find_project_search_no_results_returned_message'] = '<h4>Vyhledávání neodpovídají žádné výsledky.</h4><p>Upravte výběr a zkuste znovu.</p>';

// variable for filter drop-down
$config['find_project_upgrade_list'] = ['upgrade_all' => 'vše', 'ST' => 'Základní' , 'F' => 'Zvýrazněné', 'U' => 'Urgentní', 'S' => 'Neveřejné'];

$config['find_project_agreement_list'] = ['agreement_all' => 'vše', 'project_based' => 'projekty', 'contract_fulltime' => 'pracovní pozice'];

$config['find_project_based_option_list'] = ['project_based_all' => 'vše', 'fixed' => 'pevný rozpočet', 'hourly' => 'hodinová sazba'];

$config['find_project_publication_date'] = [
 'publication_date_all' => 'vše',
 '24_hours' => 'za posledních 24 hodin',
 '3_days' => 'za posledních 3 dní',
 '7_days' => 'za posledních 7 dní',
 '15_days' => 'za posledních 15 dní',
 'today_expires' => 'platnost do dnes'
];

//show more button text
$config['find_project_search_keyword_placeholder'] = 'např. rekonstrukce bytu, manažer prodeje...';

$config['find_project_search_locality_placeholder'] = 'např. Jihočeský kraj, Olomoucký kraj, Brno, Jihlava...';

$config['find_project_search_btn_txt'] = 'Hledat';

// label text
$config['find_project_search_project_title'] = 'Hledat podle názvu';

$config['find_project_searching_type'] = 'Možnost hledání';

$config['find_project_searching_type_include_txt'] = 'zahrnovat';

$config['find_project_searching_type_exclude_txt'] = 'vylučovat';

$config['find_project_project_categories_menu_name'] = 'Kategorie';

$config['find_project_show_more_project_categories_menu_name'] = '<span>Kategorie<small>( + )</small></span>';
$config['find_project_hide_extra_project_categories_menu_name'] = '<span>Kategorie<small>( - )</small></span>';

$config['find_project_project_categories_all_categories_txt'] = 'Všechny kategorie';


$config['find_project_show_more_subcategories_text'] = 'ukázat další podkategorie (+{remaining_subcategory} podkategorie)';

$config['find_project_show_less_subcategories_text'] = 'skrýt další podkategorie';

// Find Project Categories

$config['find_project_show_more_categories_text'] = 'ukázat další kategorie (+{remaining_category} kategorie)';

$config['find_project_show_less_categories_text'] = 'skrýt další kategorie';

// These config are using for show the text on find professsional page (loggedin+logged)
$config['find_project_show_more_search_options_text'] = 'zobrazit více možností hledání <small>( + )</small>';

$config['find_project_hide_extra_search_options_text'] = 'zavřít více možností hledání <small>( - )</small>';


//This config are using for drop down "all" option on find project/ find professional page 
$config['find_project_professionals_all_option_txt'] = 'vše';

$config['find_project_loader_display_text'] = 'vyhledávání...';

?>