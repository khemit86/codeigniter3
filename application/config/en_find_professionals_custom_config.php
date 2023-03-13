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

$config['find_professionals_page_title_meta_tag'] = 'Najít odborníky z celé ČR - xxxxx.xx';

$config['find_professionals_page_description_meta_tag'] = 'Rychle najdete a najmete odborníky z rostoucí sítě špičkových odborníků celé ČR pro projekty a volné pracovní pozice.';


################ Url Routing Variables for find professionals page ###########
/* Filename: application\modules\find_professionals\controllers\Find_professionals.php */
/* Filename: application\modules\find_professionals\views\index.php */
$config['find_professionals_page_url'] = 'find-professionals';

$config['find_professionals_user_hourly_rate_per_hour'] = '/hour';

$config['find_professionals_search_keyword_placeholder'] = 'EN např. administrativa, řidič, doktor, manažer...';

$config['find_professionals_search_locality_placeholder'] = 'EN např. Jihočeský kraj, Olomoucký kraj, Brno, Jihlava...';

// variable for no professionals avaiablable and no search found
$config['find_professionals_no_professionals_available_message'] = '<h4>EN V tento okamžík nejsou k dispozici žádní odborníci.</h4>';

$config['find_professionals_search_no_results_returned_message'] = '<h4>EN Vyhledávání neodpovídají žádné výsledky.</h4><p>Upravte výběr a zkuste znovu.</p>';

// variable to display budget

$config['find_professionals_rate_range_and'] = 'and';

$config['find_professionals_rate_range_more_then'] = 'more than';

$config['find_professionals_rate_per_hour'] = '/hour';

$config['find_professionals_search_btn_txt'] = 'Search';

// variables for clear button text
$config['find_professionals_clear_search_btn_text'] = 'Clear Search';

$config['find_professionals_clear_category_btn_text'] = 'Reset Selection';

$config['find_professionals_clear_filter_btn_text'] = 'Clear All Filters';


// Variable to manage filter dropdown option name
$config['find_professionals_account_type_dropdown_option_name'] = 'Account Type';

$config['find_professionals_hourly_rate_dropdown_option_name'] = 'Hourly Rate';

$config['find_professionals_profile_last_update_time_dropdown_option_name'] = 'Profile last update time';

$config['find_professionals_user_registration_time_dropdown_option_name'] = 'User Registration';

// variable for filter drop-down
$config['find_professionals_account_type_dropdown_list_options'] = ['account_type_all' => 'All', '1' => 'Personal' , '2' => 'Companies'];

$config['find_professionals_profile_last_update_time_dropdown_list_options'] = [
 'profile_last_update_time_all' => 'ENvše',
 'during_last_24_hours' => 'during last 24 hrs',
 'during_last_3_days' => 'during last 3 days',
 'during_last_7_days' => 'during last 7 days',
 'during_last_15_days' => 'during last 15 days',
 'during_last_30_days' => 'during last 30 days'
];

$config['find_professionals_user_registration_time_dropdown_list_options'] = [
 'user_registration_time_all' => 'ENvše',
 'during_last_24_hours' => 'during last 24 hrs',
 'during_last_7_days' => 'during last 7 days',
 'during_last_15_days' => 'during last 15 days',
 'during_last_30_days' => 'during last 30 days',
 'during_last_60_days' => 'during last 60 days',
];

// label text
$config['find_professionals_search_professionals_name'] = 'Search in names only';

$config['find_professionals_searching_type'] = 'Searching Type';

$config['find_professionals_searching_type_include_txt'] = 'Include';

$config['find_professionals_searching_type_exclude_txt'] = 'Exclude';

$config['find_professionals_professionals_categories_menu_name'] = 'Professionals Activities';

$config['find_professionals_show_more_professionals_categories_menu_name'] = 'Professionals <span>Activities<small>( + )</small></span>';
$config['find_professionals_hide_extra_professionals_categories_menu_name'] = 'Professionals <span>Activities<small>( - )</small></span>';

$config['find_professionals_professionals_categories_all_categories_txt'] = 'All Categories';


$config['find_professionals_show_more_subcategories_text'] = 'show more subcategories (+{remaining_subcategory} subcategories)';
$config['find_professionals_show_less_subcategories_text'] = 'show less subcategories';


$config['find_professionals_show_more_categories_text'] = 'show more categories (+{remaining_category} categories)';
$config['find_professionals_show_less_categories_text'] = 'show less categories';

// These config are using for show the text on find professsional page (loggedin+logged)
$config['find_professionals_show_more_search_options_text'] = 'show. more search options <small>( + )</small>';

$config['find_professionals_hide_extra_search_options_text'] = 'hide. extra search options <small>( - )</small>';

$config['find_professionals_loader_display_text'] = 'fprofessional - Loading please wait...';

?>