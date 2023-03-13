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

$config['find_project_page_title_meta_tag'] = 'Find Projects Title Meta Tag';

$config['find_project_page_description_meta_tag'] = 'Find Projects Description Meta Tag';

################ Url Routing Variables for find project page ###########
/* Filename: application\modules\find_project\controllers\Find_project.php */
/* Filename: application\modules\find_project\views\index.php */
$config['find_projects_page_url'] = 'find-projects';

// Variable to manage filter dropdown option name
$config['find_project_project_upgrades_dropdown_option_name'] = 'Project Upgrades';

$config['find_project_agreement_dropdown_option_name'] = 'Work Agreement';

$config['find_project_fixed_budget_ranges_dropdown_option_name'] = 'Fixed Budgets Ranges';

$config['find_project_hourly_rate_budgets_ranges_dropdown_option_name'] = 'Hourly Rate Budgets Ranges';

$config['find_project_project_based_options_dropdown_option_name'] = 'Project Based Options';

$config['find_project_full_time_jobs_salaries_ranges_dropdown_option_name'] = 'Fulltime Jobs Salaries Ranges';

$config['find_project_publication_date_dropdown_option_name'] = 'Publication Date';


// variables for clear button text
$config['find_project_clear_search_btn_text'] = 'Clear Search';

$config['find_project_clear_category_btn_text'] = 'Reset Selection';

$config['find_project_clear_filter_btn_text'] = 'Clear All Filters';

// variable for no project avaiablable and no search found
$config['find_project_no_project_available_message'] = '<h4>There are no available projects at the moment</h4>';

$config['find_project_search_no_results_returned_message'] = '<h4>There are no results that match your search</h4><p>Please try adjusting your search filters and try again.</p>';

// variable for filter drop-down
$config['find_project_upgrade_list'] = ['upgrade_all' => 'All projects', 'ST' => 'Standard projects' , 'F' => 'Featured projects', 'U' => 'Urgent projects', 'S' => 'Sealed projects'];

$config['find_project_agreement_list'] = ['agreement_all' => 'All', 'project_based' => 'Project Based', 'contract_fulltime' => 'Contract Fulltime'];

$config['find_project_based_option_list'] = ['project_based_all' => 'All', 'fixed' => 'Fixed Budget', 'hourly' => 'Hourly Based'];

$config['find_project_publication_date'] = [
  'publication_date_all' => 'All',
  '24_hours' => 'published during last 24 hours',
  '3_days' => 'published during last 3 days',
  '7_days' => 'published during last 7 days',
  '15_days' => 'published during last 15 days',
  'today_expires' => 'expiring today'
];


$config['find_project_search_keyword_placeholder'] = 'EN např. rekonstrukce bytu, manažer prodeje...';

$config['find_project_search_locality_placeholder'] = 'EN např. Jihočeský kraj, Olomoucký kraj, Brno, Jihlava...';

$config['find_project_search_btn_txt'] = 'Search';

// label text
$config['find_project_search_project_title'] = 'Search in job title only';

$config['find_project_searching_type'] = 'Searching Type';

$config['find_project_searching_type_include_txt'] = 'Include';

$config['find_project_searching_type_exclude_txt'] = 'Exclude';

$config['find_project_project_categories_menu_name'] = 'Projects Categories';

$config['find_project_show_more_project_categories_menu_name'] = 'Projects <span>Categories<small>( + )</small></span>';
$config['find_project_hide_extra_project_categories_menu_name'] = 'Projects <span>Categories<small>( + )</small></span>';

$config['find_project_project_categories_all_categories_txt'] = 'All Categories';


$config['find_project_show_more_subcategories_text'] = 'show more subcategories (+{remaining_subcategory} subcategories)';

$config['find_project_show_less_subcategories_text'] = 'show less subcategories';

$config['find_project_show_more_categories_text'] = 'show more categories (+{remaining_category} categories)';

$config['find_project_show_less_categories_text'] = 'show less categories';

// These config are using for show the text on find professsional page (loggedin+logged)
$config['find_project_show_more_search_options_text'] = 'show. more search options <small>( + )</small>';

$config['find_project_hide_extra_search_options_text'] = 'hide. extra search options <small>( - )</small>';

//This config are using for drop down "all" option on find project/ find professional page 
$config['find_project_professionals_all_option_txt'] = 'All';

$config['find_project_loader_display_text'] = 'fproject - Loading please wait...';

?>