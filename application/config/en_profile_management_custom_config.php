<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//Left navigation Menu name
$config['pa_user_left_nav_profile_management'] = 'Profile Management';
$config['ca_user_left_nav_profile_management'] = 'Profile Management Comp';

$config['ca_user_profile_management_left_nav_company_base_information'] = 'Company Base Information.';

$config['profile_management_left_nav_profile_definitions'] = 'Profile Definitions.';

$config['pa_user_profile_management_left_nav_competencies'] = 'pa:Competencies.';
$config['ca_user_profile_management_left_nav_competencies'] = 'ca:Competencies.';
$config['ca_app_user_profile_management_left_nav_competencies'] = 'app:Competencies.';

$config['pa_user_profile_management_left_nav_mother_tongue'] = 'Mother Tongue.';
$config['pa_user_profile_management_left_nav_spoken_foreign_languages'] = 'Spoken foreign languages.';

$config['ca_app_user_profile_management_left_nav_mother_tongue'] = 'App:Mother Tongue.';
$config['ca_app_user_profile_management_left_nav_spoken_foreign_languages'] = 'App:Spoken foreign languages.';

$config['ca_user_profile_management_left_nav_company_values_and_principles'] = 'Company values and principles.';
$config['ca_app_user_profile_management_left_nav_company_values_and_principles'] = 'App:Company values and principles.';

/*
|--------------------------------------------------------------------------
| Meta Variables 
|--------------------------------------------------------------------------
| 
*/

################ Meta Config Variables for profile_management page ###########
/* Filename: application\modules\dashboard\controllers\Dashboard.php */
/* Controller: user Method name: index */
$config['profile_management_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | profile_management_page_title Správa profilu';
$config['profile_management_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | profile_management_page_description Nastavení a správa uživatelského profilu';

################ Url Routing Variables for profile_management page ###########
/* Filename: application\modules\dashboard\controllers\dashboard.php */

$config['profile_management_profile_definitions_page_url'] = 'profile-definitions';
$config['profile_management_mother_tongue_page_url'] = 'mother-tongue';
$config['profile_management_spoken_foreign_languages_page_url'] = 'spoken-foreign-languages';
$config['profile_management_competencies_page_url'] = 'competencies';
$config['profile_management_company_values_and_principles_page_url'] = 'company-values-and-principles';
$config['profile_management_company_base_information_page_url'] = 'company-base-information';

// Config for meta tag for profile definations page of profile management
$config['user_profile_management_profile_definitions_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Profile Definitions';
$config['user_profile_management_profile_definitions_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Profile Definitions';


// for persoanl account
$config['pa_profile_management_headline_section_initial_view_title'] = 'Přidat nadpis(p)';
$config['pa_profile_management_headline_section_initial_view_content'] = 'Nadpis Travai účtu se zobrazuje pod vašim jménem a je jednou z nejviditelnějších částí profilu. Představuje vás na stránce seznamu odborníků. Ať už se na něj dívají vaše obchodní kontakty, zaměstnavatelé nebo náboráři, váš Travai nadpis je klíčem k vytvoření pozitivního dojmu. V nadpisu lze uvést krátké sdělení, motto nebo přísloví, které vás vykreslí jako důvěryhodného člena vašeho oboru. Ale měl by také obsahovat strategická klíčová slova, která vám pomohou při vyhledávání na Travai seznamu odborníků.(p)';

// for company account
$config['ca_profile_management_headline_section_initial_view_title'] = 'Add Headline(c)';
$config['ca_profile_management_headline_section_initial_view_content'] = 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cice(c)';

// for company account(app)
$config['ca_app_profile_management_headline_section_initial_view_title'] = 'Add Headline(app)';
$config['ca_app_profile_management_headline_section_initial_view_content'] = 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cice(app)';

//----------- description-----------



// For perosonal account
$config['pa_profile_management_description_section_initial_view_title'] = 'EN Přidat popis(p)';
$config['pa_profile_management_description_section_initial_view_content'] = 'EN Popis je primární částí na každém profilu. V této části si sami uvádíte informace podle vlastních slov, bez omezení a počátečních dat. Ať už jej použijete k tomu, jestli uvedete všechny své úspěchy a tím předvedli svoji osobnost. Tento popis je vaší volbou pro vložení svého nejlepšího já. Je také jedním z nejdůležitějších faktorů zvažovaných ve vyhledávacím Travai algoritmu.(p)';

// For company account
$config['ca_profile_management_description_section_initial_view_title'] = 'EN Přidat popis(c)';
$config['ca_profile_management_description_section_initial_view_content'] = 'EN Popis je primární částí na každém profilu. V této části si sami uvádíte informace podle vlastních slov, bez omezení a počátečních dat. Ať už jej použijete k tomu, jestli uvedete všechny své úspěchy a tím předvedli svoji osobnost. Tento popis je vaší volbou pro vložení svého nejlepšího já. Je také jedním z nejdůležitějších faktorů zvažovaných ve vyhledávacím Travai algoritmu.(c)';


// For company account (authorized physical person)
$config['ca_app_profile_management_description_section_initial_view_title'] = 'EN Přidat popis(app)';
$config['ca_app_profile_management_description_section_initial_view_content'] = 'EN Popis je primární částí na každém profilu. V této části si sami uvádíte informace podle vlastních slov, bez omezení a počátečních dat. Ať už jej použijete k tomu, jestli uvedete všechny své úspěchy a tím předvedli svoji osobnost. Tento popis je vaší volbou pro vložení svého nejlepšího já. Je také jedním z nejdůležitějších faktorů zvažovaných ve vyhledávacím Travai algoritmu.(app)';

// For company account
$config['ca_profile_management_base_information_page_title_meta_tag'] = 'CA {user_first_name_last_name_or_company_name} | Company base information title';
$config['ca_profile_management_base_information_page_description_meta_tag'] = 'CA {user_first_name_last_name_or_company_name} | Company base information description';

// for authorized physical person
$config['ca_app_profile_management_base_information_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Company base information title(app)';
$config['ca_app_profile_management_base_information_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Company base information description(app)';

$config['ca_profile_management_base_information_company_founded_in_section_initial_view_title'] = 'Add Founded In(c)';
$config['ca_profile_management_base_information_company_founded_in_section_initial_view_content'] = 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cice(c)';

$config['ca_app_profile_management_base_information_company_founded_in_section_initial_view_title'] = 'Add Founded In(app c)';
$config['ca_app_profile_management_base_information_company_founded_in_section_initial_view_content'] = 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cice(app c)';


$config['ca_profile_management_base_information_company_size_section_initial_view_title'] = 'Add Company Size(c)';
$config['ca_profile_management_base_information_company_size_section_initial_view_content'] = 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cice Company size(c)';

$config['ca_app_profile_management_base_information_company_size_section_initial_view_title'] = 'Add Company Size(c app)';
$config['ca_app_profile_management_base_information_company_size_section_initial_view_content'] = 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cice Company size(c app)';


$config['ca_profile_management_base_information_company_opening_hours_section_initial_view_title'] = 'Add Opening Hours(c)';
$config['ca_profile_management_base_information_company_opening_hours_section_location_available_initial_view_content'] = 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cice Opening Hours(c) - <b>location available</b>';
$config['ca_profile_management_base_information_company_opening_hours_section_location_not_available_initial_view_content'] = 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cice Opening Hours(c) - <b>location not available</b>';

$config['ca_app_profile_management_base_information_company_opening_hours_section_initial_view_title'] = 'Add Opening Hours(c app)';
$config['ca_app_profile_management_base_information_company_opening_hours_section_location_available_initial_view_content'] = 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cice Opening Hours(c app) - <b>location available</b>';
$config['ca_app_profile_management_base_information_company_opening_hours_section_location_not_available_initial_view_content'] = 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cice Opening Hours(c app) - <b>location not available</b>';


$config['ca_profile_management_base_information_founded_in_tab_label'] = 'Founded In';
$config['ca_profile_management_base_information_company_size_tab_label'] = 'Company Size';
$config['ca_profile_management_base_information_opening_hours_tab_label'] = 'Opening Hours';

$config['ca_app_profile_management_base_information_founded_in_tab_label'] = 'Founded In(app)';
$config['ca_app_profile_management_base_information_company_size_tab_label'] = 'Company Size(app)';
$config['ca_app_profile_management_base_information_opening_hours_tab_label'] = 'Opening Hours(app)';


$config['ca_profile_management_base_information_year_select_year'] = 'Select Year';

$config['ca_profile_management_base_information_founded_in_required_error_message'] = 'Founded in required';
$config['ca_app_profile_management_base_information_founded_in_required_error_message'] = 'App:Founded in required';

$config['ca_profile_management_base_information_select_company_size_option'] = 'Select Company Size';
$config['ca_app_profile_management_base_information_select_company_size_option'] = 'App:Select Company Size';
$config['ca_profile_management_base_information_company_size_dropdown_option'] = [
	'solo owner',
	'1 - 9 Employees' ,
	'10 - 25 Employees',
	'26 - 49 Employees',
	'50 - 149 Employees',
	'150 - 249 Employees' ,
	'more than 250 Employees'
];


$config['ca_app_profile_management_base_information_company_size_dropdown_option'] = [
	'1 - 9 Employees' ,
	'10 - 25 Employees' ,
	'26 - 49 Employees' ,
	'50 - 149 Employees' ,
	'150 - 249 Employees',
	'more than 250 Employees'
];
$config['ca_profile_management_base_information_company_size_required_error_message'] = 'Company size required';
$config['ca_app_profile_management_base_information_company_size_required_error_message'] = 'App:Company size required';

$config['ca_profile_management_base_information_company_opened_on_selected_hours_label'] = 'Open on Select Hours';
$config['ca_app_profile_management_base_information_company_opened_on_selected_hours_label'] = 'App:Open on Select Hours';

$config['ca_profile_management_base_information_select_company_location_option'] = 'Select Company Location-op';
$config['ca_app_profile_management_base_information_select_company_location_option'] = 'App: Select Company Location-op';

$config['ca_profile_management_base_information_company_always_opened_label'] = 'Always Open';
$config['ca_app_profile_management_base_information_company_always_opened_label'] = 'App:Always Open';

$config['ca_profile_management_base_information_company_permanently_closed_label'] = 'Permanently Closed';
$config['ca_app_profile_management_base_information_company_permanently_closed_label'] = 'App:Permanently Closed';

$config['ca_profile_management_base_information_company_telephone_appointment_label'] = 'By Telephone Appointment';
$config['ca_app_profile_management_base_information_company_telephone_appointment_label'] = 'App:By Telephone Appointment';

$config['ca_profile_management_base_information_select_company_opening_time_option'] = 'Select Opening Time';
$config['ca_app_profile_management_base_information_select_company_opening_time_option'] = 'App:Select Opening Time';

$config['ca_profile_management_base_information_select_company_closing_time_option'] = 'Select Close Time';
$config['ca_app_profile_management_base_information_select_company_closing_time_option'] = 'App:Select Close Time';

$config['ca_profile_management_base_information_company_opening_hours_dropdown_option'] = [
	'00:00' => '00:00', '00:15' => '00:15','00:30' => '00:30','00:45' => '00:45','01:00' => '01:00','01:15' => '01:15','01:30' => '01:30','01:45' => '01:45','02:00' => '02:00','02:15' => '02:15','02:30' => '02:30',
	'02:45' => '02:45','03:00' => '03:00','03:15' => '03:15','03:30' => '03:30','03:45' => '03:45','04:00' => '04:00','04:15' => '04:15','04:30' => '04:30','04:45' => '04:45','05:00' => '05:00','05:15' => '05:15',
	'05:30' => '05:30','05:45' => '05:45','06:00' => '06:00','06:15' => '06:15','06:30' => '06:30','06:45' => '06:45','07:00' => '07:00','07:15' => '07:15','07:30' => '07:30','07:45' => '07:45','08:00' => '08:00',
	'08:15' => '08:15','08:30' => '08:30','08:45' => '08:45','09:00' => '09:00','09:15' => '09:15','09:30' => '09:30','09:45' => '09:45', '10:00' => '10:00',

	'10:15' => '10:15','10:30' => '10:30','10:45' => '10:45','11:00' => '11:00','11:15' => '11:15','11:30' => '11:30','11:45' => '11:45','12:00' => '12:00','12:15' => '12:15','12:30' => '12:30',
	'12:45' => '12:45','13:00' => '13:00','13:15' => '13:15','13:30' => '13:30','13:45' => '13:45','14:00' => '14:00','14:15' => '14:15','14:30' => '14:30','14:45' => '14:45','15:00' => '15:00','15:15' => '15:15',
	'15:30' => '15:30','15:45' => '15:45','16:00' => '16:00','16:15' => '16:15','16:30' => '16:30','16:45' => '16:45','17:00' => '17:00','17:15' => '17:15','17:30' => '17:30','17:45' => '17:45','18:00' => '18:00',
	'18:15' => '18:15','18:30' => '18:30','18:45' => '18:45','19:00' => '19:00','19:15' => '19:15','19:30' => '19:30','19:45' => '19:45', '20:00' => '20:00',

	'20:15' => '20:15','20:30' => '20:30','20:45' => '20:45','21:00' => '21:00','21:15' => '21:15','21:30' => '21:30','21:45' => '21:45','22:00' => '22:00','22:15' => '22:15','22:30' => '22:30',
	'22:45' => '22:45','23:00' => '23:00','23:15' => '23:15','23:30' => '23:30','23:45' => '23:45'

];
$config['ca_profile_management_base_information_company_opened_hours_select_at_least_one_day_error_message'] = 'Please select at least one day as open day.';
$config['ca_app_profile_management_base_information_company_opened_hours_select_at_least_one_day_error_message'] = 'App:Please select at least one day as open day.';



$config['ca_profile_management_base_information_company_opened_hours_opening_time_required_error_message'] = 'Opening time requiredd';
$config['ca_app_profile_management_base_information_company_opened_hours_opening_time_required_error_message'] = 'App:Opening time required';


$config['ca_profile_management_base_information_company_opened_hours_closing_time_required_error_message'] = 'Closing time required';
$config['ca_app_profile_management_base_information_company_opened_hours_closing_time_required_error_message'] = 'App:Closing time required';

$config['ca_profile_management_base_information_company_opening_hours_saved_confirmation_message'] = 'Opening hours saved successfully.';
$config['ca_app_profile_management_base_information_company_opening_hours_saved_confirmation_message'] = 'App:Opening hours saved successfully.';



$config['ca_profile_management_base_information_company_opening_hours_conflict_no_location_available_warning_message'] = "Warning: status has changed. please refresh the page.";
$config['ca_app_profile_management_base_information_company_opening_hours_conflict_no_location_available_warning_message'] = "App:Warning: status has changed. please refresh the page.";
/**
 * This error message will display to user when there is multiple locations available now user try save opening hours for particular location and in background that location removed
 */

$config['ca_profile_management_base_information_company_opening_hours_location_not_valid_error_message'] = "This location is invalid."; // 
$config['ca_app_profile_management_base_information_company_opening_hours_location_not_valid_error_message'] = "App:This location is invalid."; // 



/* -------------------------------------- Company values and principles --------------------------------------------------------------------------------------- */


$config['ca_profile_management_company_values_and_principles_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Company values and principles title';
$config['ca_profile_management_company_values_and_principles_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Company values and principles description';

// company app account
$config['ca_app_profile_management_company_values_and_principles_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Company values and principles title(app)';
$config['ca_app_profile_management_company_values_and_principles_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Company values and principles description(app)';


$config['ca_profile_management_company_values_and_principles_vision_tab_label'] = 'Vision';
$config['ca_profile_management_company_values_and_principles_mission_tab_label'] = 'Mission';
$config['ca_profile_management_company_values_and_principles_core_values_tab_label'] = 'Core Values';
$config['ca_profile_management_company_values_and_principles_strategy_goals_tab_label'] = 'Strategy and Goals';


$config['ca_app_profile_management_company_values_and_principles_vision_tab_label'] = 'Vision(app)';
$config['ca_app_profile_management_company_values_and_principles_mission_tab_label'] = 'Mission(app)';
$config['ca_app_profile_management_company_values_and_principles_core_values_tab_label'] = 'Core Values(app)';
$config['ca_app_profile_management_company_values_and_principles_strategy_goals_tab_label'] = 'Strategy and Goals(app)';


$config['ca_profile_management_company_values_and_principles_vision_section_initial_view_title'] = 'Company Vision(c)';
$config['ca_profile_management_company_values_and_principles_vision_section_initial_view_content'] = 'EN Popis je primární částí na každém profilu. V této části si sami uvádíte informace podle vlastních slov, bez omezení a počátečních dat. Ať už jej použijete k tomu, jestli uvedete všechny své úspěchy a tím předvedli svoji osobnost. Tento popis je vaší volbou pro vložení svého nejlepšího já. Je také jedním z nejdůležitějších faktorů zvažovaných ve vyhledávacím Travai algoritmu.(c)';



// for app(authorized physical person)
$config['ca_app_profile_management_company_values_and_principles_vision_section_initial_view_title'] = 'Company Vision(app)';
$config['ca_app_profile_management_company_values_and_principles_vision_section_initial_view_content'] = 'EN Popis je primární částí na každém profilu. V této části si sami uvádíte informace podle vlastních slov, bez omezení a počátečních dat. Ať už jej použijete k tomu, jestli uvedete všechny své úspěchy a tím předvedli svoji osobnost. Tento popis je vaší volbou pro vložení svého nejlepšího já. Je také jedním z nejdůležitějších faktorů zvažovaných ve vyhledávacím Travai algoritmu.(app)';


$config['ca_profile_management_company_values_and_principles_mission_section_initial_view_title'] = 'Company Mission(c)';
$config['ca_profile_management_company_values_and_principles_mission_section_initial_view_content'] = 'EN Popis je primární částí na každém profilu. V této části si sami uvádíte informace podle vlastních slov, bez omezení a počátečních dat. Ať už jej použijete k tomu, jestli uvedete všechny své úspěchy a tím předvedli svoji osobnost. Tento popis je vaší volbou pro vložení svého nejlepšího já. Je také jedním z nejdůležitějších faktorů zvažovaných ve vyhledávacím Travai algoritmu.(c)';
// for app(authorized physical person)
$config['ca_app_profile_management_company_values_and_principles_mission_section_initial_view_title'] = 'Company Mission(app)';
$config['ca_app_profile_management_company_values_and_principles_mission_section_initial_view_content'] = 'EN Popis je primární částí na každém profilu. V této části si sami uvádíte informace podle vlastních slov, bez omezení a počátečních dat. Ať už jej použijete k tomu, jestli uvedete všechny své úspěchy a tím předvedli svoji osobnost. Tento popis je vaší volbou pro vložení svého nejlepšího já. Je také jedním z nejdůležitějších faktorů zvažovaných ve vyhledávacím Travai algoritmu.(app)';


$config['ca_profile_management_company_values_and_principles_core_values_section_initial_view_title'] = 'Company Core Values(c)';
$config['ca_profile_management_company_values_and_principles_core_values_section_initial_view_content'] = 'EN Popis je primární částí na každém profilu. V této části si sami uvádíte informace podle vlastních slov, bez omezení a počátečních dat. Ať už jej použijete k tomu, jestli uvedete všechny své úspěchy a tím předvedli svoji osobnost. Tento popis je vaší volbou pro vložení svého nejlepšího já. Je také jedním z nejdůležitějších faktorů zvažovaných ve vyhledávacím Travai algoritmu.(c)';
// for app(authorized physical person)
$config['ca_app_profile_management_company_values_and_principles_core_values_section_initial_view_title'] = 'Company Core Values(app)';
$config['ca_app_profile_management_company_values_and_principles_core_values_section_initial_view_content'] = 'EN Popis je primární částí na každém profilu. V této části si sami uvádíte informace podle vlastních slov, bez omezení a počátečních dat. Ať už jej použijete k tomu, jestli uvedete všechny své úspěchy a tím předvedli svoji osobnost. Tento popis je vaší volbou pro vložení svého nejlepšího já. Je také jedním z nejdůležitějších faktorů zvažovaných ve vyhledávacím Travai algoritmu.(app)';


$config['ca_profile_management_company_values_and_principles_strategy_goals_section_initial_view_title'] = 'Company Strategy and Goals(c)';
$config['ca_profile_management_company_values_and_principles_strategy_goals_section_initial_view_content'] = 'EN Popis je primární částí na každém profilu. V této části si sami uvádíte informace podle vlastních slov, bez omezení a počátečních dat. Ať už jej použijete k tomu, jestli uvedete všechny své úspěchy a tím předvedli svoji osobnost. Tento popis je vaší volbou pro vložení svého nejlepšího já. Je také jedním z nejdůležitějších faktorů zvažovaných ve vyhledávacím Travai algoritmu.(c)';
// for app(authorized physical person)
$config['ca_app_profile_management_company_values_and_principles_strategy_goals_section_initial_view_title'] = 'Company Strategy and Goals(app)';
$config['ca_app_profile_management_company_values_and_principles_strategy_goals_section_initial_view_content'] = 'EN Popis je primární částí na každém profilu. V této části si sami uvádíte informace podle vlastních slov, bez omezení a počátečních dat. Ať už jej použijete k tomu, jestli uvedete všechny své úspěchy a tím předvedli svoji osobnost. Tento popis je vaší volbou pro vložení svého nejlepšího já. Je také jedním z nejdůležitějších faktorů zvažovaných ve vyhledávacím Travai algoritmu.(app)';


/*----------- hourly rate----------------*/


// For perosonal account
$config['pa_profile_management_hourly_rate_section_initial_view_title'] = 'EN Přidat hodinovou sazbu(p)';
$config['pa_profile_management_hourly_rate_section_initial_view_content'] = 'EN Hodinová sazba je uváděná pod fotkou profilu a hodnocením na stránce seznamu odborníků a profilu odborníka. Motivace pracovat na "volné noze" poskytuje cestu k vydělávání vyššího příjmu a možností stát se nezávislým pracovníkem. Je důležité účtovat za svoji odbornou činnost správnou hodinovou sazbu. Je dobré najít vyváženou rovnováhu založenou na vašich zkušenostech, úrovni dovedností a na tom, co považujete za oprávněnou kompenzaci za svůj čas.
<p><strong>Nezapomeňte</strong> na stránce seznamu odbroníků lze nastavit filtr a upřesnit hledání dle výše hodinové sazby. Tímto lze hledat odborníky s preferovanou hodinovou sazbou. Profily, které nemají uvedenou hodinovou sazbu, nebudou zobrazeni ve výsledcích hledání.(p)</p>';

// For company account
$config['ca_profile_management_hourly_rate_section_initial_view_title'] = 'Add Hourly Rate(c)';
$config['ca_profile_management_hourly_rate_section_initial_view_content'] = 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cice(c)';

// For company account(app)
$config['ca_app_profile_management_hourly_rate_section_initial_view_title'] = 'Add Hourly Rate(app)';
$config['ca_app_profile_management_hourly_rate_section_initial_view_content'] = 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cice(app)';


/*---------- mother tongue --------------*/
$config['pa_profile_management_mother_tongue_section_select_mother_tongue_initial_selection'] = 'Select Mother Tongue(p)';
$config['pa_profile_management_mother_tongue_section_initial_view_title'] = 'EN Přidat rodilý jazyk(p)';
$config['pa_profile_management_mother_tongue_section_initial_view_content'] = 'EN Vyplňte svůj rodilý jazyk. V České republice je mnoho tuzemských i zahraničních společností, které upřednostňují pracovníky s rodilým jazykem. Vyplněním rodilého jazyka může být výhodou pro získání některých projektů či zaměstnání.(p)';
$config['pa_profile_management_mother_tongue_page_headline_title'] = 'mother tongue';

// for company account app 
$config['ca_app_profile_management_mother_tongue_section_select_mother_tongue_initial_selection'] = 'Select Mother Tongue(app)';
$config['ca_app_profile_management_mother_tongue_section_initial_view_title'] = 'EN Přidat rodilý jazyk(app)';
$config['ca_app_profile_management_mother_tongue_section_initial_view_content'] = 'EN Vyplňte svůj rodilý jazyk. V České republice je mnoho tuzemských i zahraničních společností, které upřednostňují pracovníky s rodilým jazykem. Vyplněním rodilého jazyka může být výhodou pro získání některých projektů či zaměstnání.(app)';
$config['ca_app_profile_management_mother_tongue_page_headline_title'] = 'mother tongue(app)';


// config for meta tag regarding mother tongue language page
$config['pa_user_profile_management_mother_tongue_language_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | mother tongue';
$config['pa_user_profile_management_mother_tongue_language_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | mother tongue';

// config for meta tag regarding mother tongue language page
$config['ca_app_user_profile_management_mother_tongue_language_page_title_meta_tag'] = 'App:{user_first_name_last_name_or_company_name} | mother tongue';
$config['ca_app_user_profile_management_mother_tongue_language_page_description_meta_tag'] = 'App:{user_first_name_last_name_or_company_name} | mother tongue';


/*----------- Base Information Spoken Languages----------------*/
$config['pa_profile_management_spoken_foreign_languages_section_initial_view_title'] = 'EN Přidat cizí jazyk(p)';
$config['pa_profile_management_spoken_foreign_languages_section_initial_view_content'] = 'EN Globalizované společnosti se spoléhají zejména na nadnárodní týmy, aby šířily osvědčené postupy a využívaly inovace odkudkoli na světě. Přidáním jazykových znalostí a definováním úrovně komunikace a psaní zvýšíte svoje příležitosti k získání kvalitnějších nabídek. Zaměstnavatelé neustále hledají pracovníky nebo partnery, pro realizaci projektů, kteří mají vaše dovednosti. Pokud jste neuvedli své znalosti cizích jazyků ve svém profilu, může to být pro vás nevýhodou.(p)';

// for company app account 
$config['ca_app_profile_management_spoken_foreign_languages_section_initial_view_title'] = 'EN Přidat cizí jazyk(app)';
$config['ca_app_profile_management_spoken_foreign_languages_section_initial_view_content'] = 'EN Globalizované společnosti se spoléhají zejména na nadnárodní týmy, aby šířily osvědčené postupy a využívaly inovace odkudkoli na světě. Přidáním jazykových znalostí a definováním úrovně komunikace a psaní zvýšíte svoje příležitosti k získání kvalitnějších nabídek. Zaměstnavatelé neustále hledají pracovníky nebo partnery, pro realizaci projektů, kteří mají vaše dovednosti. Pokud jste neuvedli své znalosti cizích jazyků ve svém profilu, může to být pro vás nevýhodou.(app)';


// config for meta tag regarding spoken language page(for personal account)
$config['pa_user_profile_management_spoken_languages_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | spoken languages';
$config['pa_user_profile_management_spoken_languages_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | spoken languages';

// config for meta tag regarding spoken language page (for company app account)
$config['ca_app_user_profile_management_spoken_languages_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | spoken languages(app)';
$config['ca_app_user_profile_management_spoken_languages_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | spoken languages(app)';


$config['pa_user_profile_management_spoken_languages_page_free_membership_subscriber_max_spoken_languages_entries_membership_upgrade_calltoaction'] = 'Spoken language: English:Byt císlo 1306 na Senovážném námestí v centru Prahy se stal místem, kde se bežne rešilo, kdo by v tuzemském sportu mohl dostat podporu v rádech milionu korun. <a href="{membership_page_url}">Obchodními podmínkami</a> Zásadami ochrany osobních údaju';

$config['ca_app_user_profile_management_spoken_languages_page_free_membership_subscriber_max_spoken_languages_entries_membership_upgrade_calltoaction'] = 'App Spoken language: English:Byt císlo 1306 na Senovážném námestí v centru Prahy se stal místem, kde se bežne rešilo, kdo by v tuzemském sportu mohl dostat podporu v rádech milionu korun. <a href="{membership_page_url}">Obchodními podmínkami</a> Zásadami ochrany osobních údaju';


/*----------- Skills----------------*/
// for persoanl account
$config['pa_profile_management_skills_section_initial_view_title'] = 'EN Přidat dovednosti(p)';
$config['pa_profile_management_skills_section_initial_view_content'] = 'EN Jednou z nejdůležitějších částí vašeho profilu je sekce Dovednosti. Dovednosti jsou uvadeny na stránce každého profilu. Je také jedním z nejdůležitějších faktorů zvažovaných ve vyhledávacím Travai algoritmu, podle které může být profil vyhledatelný na stránce seznamu odborníků. Váš seznam dovedností jsou klíčová slova, která napovídají návštěvníkům profilu, zaměstnavatelům na první pohled v čem jste kvalifikovaní. Usnadňuje všem hledání a kontaktování právě vás. Čím více dovedností uvedete, tím vyšší jsou vaše šance být kontaktován lidmi a firmami které potřebují vaše služby.<p><strong>Pro vytvoření dovednosti napište slovo nebo slovní spojení a stiskněte klávesu Enter.</strong>(p)</p>';

// for company account
$config['ca_profile_management_skills_section_initial_view_title'] = 'EN Add Skills(c)';
$config['ca_profile_management_skills_section_initial_view_content'] = 'EN The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cice(c)';

// for company account(app)
$config['ca_app_profile_management_skills_section_initial_view_title'] = 'EN Add Skills(app)';
$config['ca_app_profile_management_skills_section_initial_view_content'] = 'EN The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cice(app)';


/*----------- ServiceS Provided----------------*/
$config['pa_profile_management_services_provided_section_initial_view_title'] = 'EN Přidat nabízené služby(p)';
$config['pa_profile_management_services_provided_section_initial_view_content'] = 'EN Nabízené služby se zobrazují na stránce profilu. Tato sekce vám dává příležitost zobrazit službu, kterou poskytujete ve svém profilu, doplňující oblasti odborných znalostí a rozšiřující sekci portfolia, která může vytvářet vyšší poptávku a možné další kontakty s jinými perspektivami. Je to také jeden z nejdůležitějších faktorů uvažovaných ve vyhledávacím algoritmu Travai, podle kterého lze profil vyhledávat na stránce odborníků. Seznam vašich poskytovaných služeb na stránce vašeho profilu usnadní každému nalezení a kontaktování.<p><strong>Pro vytvoření nabízených služeb napište slovo nebo slovní spojení a stiskněte klávesu Enter.</strong>(p)<p>';

// for company account
$config['ca_profile_management_services_provided_section_initial_view_title'] = 'Add Services Provided(c)';
$config['ca_profile_management_services_provided_section_initial_view_content'] = 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cice(c)';

// for company account(app)
$config['ca_app_profile_management_services_provided_section_initial_view_title'] = 'Add Services Provided(app)';
$config['ca_app_profile_management_services_provided_section_initial_view_content'] = 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cice(app)';


/*----------- Areas Of Expertise----------------*/
$config['pa_user_profile_management_competencies_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | competencies(pa)';
$config['pa_user_profile_management_competencies_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | competencies(pa)';

$config['ca_user_profile_management_competencies_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | competencies(ca)';
$config['ca_user_profile_management_competencies_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | competencies(ca)';

$config['ca_app_user_profile_management_competencies_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | competencies(app)';
$config['ca_app_user_profile_management_competencies_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | competencies(app)';

// competencies page tabs text of profile management
$config['pa_profile_management_competencies_page_areas_of_expertise_tab_txt'] = 'Areas of Expertise(p)';
$config['pa_profile_management_competencies_page_skills_tab_txt'] = 'Skills(p)';
$config['pa_profile_management_competencies_page_services_provided_tab_txt'] = 'Services Provided(p)';

$config['ca_profile_management_competencies_page_areas_of_expertise_tab_txt'] = 'Areas of Expertise(c)';
$config['ca_profile_management_competencies_page_skills_tab_txt'] = 'Skills(c)';
$config['ca_profile_management_competencies_page_services_provided_tab_txt'] = 'Services Provided(c)';

$config['ca_app_profile_management_competencies_page_areas_of_expertise_tab_txt'] = 'Areas of Expertise(app)';
$config['ca_app_profile_management_competencies_page_skills_tab_txt'] = 'Skills(app)';
$config['ca_app_profile_management_competencies_page_services_provided_tab_txt'] = 'Services Provided(app)';


// For personal account
$config['pa_profile_management_areas_of_expertise_section_initial_view_title'] = 'EN Přidat odborné činnosti(p)';
$config['pa_profile_management_areas_of_expertise_section_initial_view_content'] = 'EN Vzdělání a odbronost by měly korespondovat s výběrem sekcí odbroných činností. Pečlivý výběr odborných činností na základě vašeho vzdělání a odborností je nejúčinnějším způsobem získávání vyhovujícím poptávkám projektů a pracovních míst.<p><strong>Nezapomeňte:</strong> při volbě sekcí odborných činností je omezen na základě předplatného členství. Pro zvýšení počtu sekcí je nutné mít GOLD členství.(p)</p>';

// For company account
$config['ca_profile_management_areas_of_expertise_section_initial_view_title'] = 'Add Areas of Expertise(c)';
$config['ca_profile_management_areas_of_expertise_section_initial_view_content'] = 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cice(c)';

// For company account(app)
$config['ca_app_profile_management_areas_of_expertise_section_initial_view_title'] = 'Add Areas of Expertise(app)';
$config['ca_app_profile_management_areas_of_expertise_section_initial_view_content'] = 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cice(app)';


// config for personal account
$config['pa_profile_management_areas_of_expertise_section_select_areas_of_expertise_category_initial_selection'] = 'EN Vybrat kategorii(p)';
$config['pa_profile_management_areas_of_expertise_section_select_areas_of_expertise_subcategory_initial_selection'] = 'EN Vybrat podkategorii(p)';

// config for company account
$config['ca_profile_management_areas_of_expertise_section_select_areas_of_expertise_category_initial_selection'] = 'Select Category(c)';
$config['ca_profile_management_areas_of_expertise_section_select_areas_of_expertise_subcategory_initial_selection'] = 'Select Subcategory(c)';


$config['user_profile_management_free_membership_subscriber_category_maximum_slots_reached_error_message'] = 'you reached the maximum slots available of category.think to upgrade your current membership plan';

$config['user_profile_management_gold_membership_subscriber_category_maximum_slots_reached_error_message'] = 'you reached the maximum slots available of category';


$config['user_profile_management_areas_of_expertise_page_free_membership_subscriber_max_categories_entries_membership_upgrade_calltoaction'] = 'Area of expertise: En:Byt císlo 1306 na Senovážném námestí v centru Prahy se stal místem, kde se bežne rešilo, kdo by v tuzemském sportu mohl dostat podporu v rádech milionu korun. <a href="{membership_page_url}">Obchodními podmínkami</a> Zásadami ochrany osobních údaju';



$config['profile_management_headline_title_profile_definitions'] = 'Profile Definitions';
$config['profile_management_headline_title_mother_tongue'] = 'Mother Tongue';
$config['profile_management_headline_title_spoken_foreign_languages'] = 'Spoken Foreign Languages';
$config['profile_management_headline_title_competencies'] = 'Competencies';
$config['ca_profile_management_company_values_and_principles_title'] = 'Company values and principles';
$config['ca_app_profile_management_company_values_and_principles_title'] = 'Company values and principles(app)';
$config['ca_profile_management_headline_title_company_base_information'] = 'Company Base Information';
$config['ca_app_profile_management_headline_title_company_base_information'] = 'Company Base Information(app)';

// error messages variable definitaion
$config['profile_management_areas_of_expertise_valid_category_not_existent_popup_message'] = "An error has occured! Please refresh this page.";// this message will show when admin deactive/delete the category from admin and user is using these category and process


// for personal account
$config['pa_profile_management_spoken_foreign_languages_page_headline_title'] = 'spoken foreign languages';
// for company app account
$config['ca_app_profile_management_spoken_foreign_languages_page_headline_title'] = 'spoken foreign languages(app)';

// profile definitions page tab heading of profile management
$config['pa_profile_management_profile_definitions_page_headline_tab_txt'] = 'Headline(p)';
$config['pa_profile_management_profile_definitions_page_description_tab_txt'] = 'Description(p)';
$config['pa_profile_management_profile_definitions_page_hourly_rate_tab_txt'] = 'Hourly Rate(p)';

$config['ca_profile_management_profile_definitions_page_headline_tab_txt'] = 'Headline(c)';
$config['ca_profile_management_profile_definitions_page_description_tab_txt'] = 'Description(c)';
$config['ca_profile_management_profile_definitions_page_hourly_rate_tab_txt'] = 'Hourly Rate(c)';

$config['ca_app_profile_management_profile_definitions_page_headline_tab_txt'] = 'Headline(app)';
$config['ca_app_profile_management_profile_definitions_page_description_tab_txt'] = 'Description(app)';
$config['ca_app_profile_management_profile_definitions_page_hourly_rate_tab_txt'] = 'Hourly Rate(app)';



// Base Information Spoken Language variable definitaion
// for personal account
$config['pa_profile_management_spoken_foreign_languages_section_select_language_initial_selection'] = 'vybrat jazyk(p)';
$config['pa_profile_management_spoken_foreign_languages_section_select_level_initial_selection'] = 'vybrat úroveň(p)';

// for company app account
$config['ca_app_profile_management_spoken_foreign_languages_section_select_language_initial_selection'] = 'vybrat jazyk(app)';
$config['ca_app_profile_management_spoken_foreign_languages_section_select_level_initial_selection'] = 'vybrat úroveň(app)';

// Base Information Spoken Language Add Another Language Button variable definitaion
$config['pa_profile_management_spoken_foreign_languages_section_add_another_category_btn'] = 'Add Another L.(p)';
$config['ca_app_profile_management_spoken_foreign_languages_section_add_another_category_btn'] = 'Add Another L.(app)';

// Areas Of Expertise variable definitaion
// For personal account
$config['pa_profile_management_areas_of_expertise_section_title_categories'] = 'EN Kategorie(p)';
$config['pa_profile_management_areas_of_expertise_section_title_subcategories'] = 'EN Podkategorie(p)';
$config['pa_profile_management_areas_of_expertise_section_title_add_another_category_btn'] = 'EN Add Another Area of Expertise(p)';

// for company account
$config['ca_profile_management_areas_of_expertise_section_title_categories'] = 'Category(c)';
$config['ca_profile_management_areas_of_expertise_section_title_subcategories'] = 'Subcategories(c)';
$config['ca_profile_management_areas_of_expertise_section_title_add_another_category_btn'] = 'Add Another Area of Expertise(c)';

// Base Information variable definitaion + also used on user profile page (for personal account)
$config['pa_profile_management_spoken_foreign_languages_section_title'] = 'Spoken Language(p)';
$config['pa_profile_management_spoken_foreign_languages_section_title_understanding'] = 'Understanding(p)';
$config['pa_profile_management_spoken_foreign_languages_section_title_speaking'] = 'Speaking(p)';
$config['pa_profile_management_spoken_foreign_languages_section_title_writing'] = 'Writing(p)';

// Base Information variable definitaion + also used on user profile page (for company app account)
$config['ca_app_profile_management_spoken_foreign_languages_section_title'] = 'Spoken Language(app)';
$config['ca_app_profile_management_spoken_foreign_languages_section_title_understanding'] = 'Understanding(app)';
$config['ca_app_profile_management_spoken_foreign_languages_section_title_speaking'] = 'Speaking(app)';
$config['ca_app_profile_management_spoken_foreign_languages_section_title_writing'] = 'Writing(app)';

################ validation message for profile management #####
$config['ca_profile_management_user_headline_required_field_error_message'] = 'Headline required(c)';
$config['pa_profile_management_user_headline_required_field_error_message'] = 'Headline required(p)';

$config['ca_profile_management_user_description_required_field_error_message'] = 'Description required(c)';
$config['pa_profile_management_user_description_required_field_error_message'] = 'Description required(p)';

$config['pa_profile_management_user_skills_required_field_error_message'] = 'Skill is required.(p)';
$config['ca_profile_management_user_skills_required_field_error_message'] = 'Skill is required.(c)';

$config['profile_management_free_membership_subscriber_user_skills_maximum_slots_reached_error_message'] = 'you reached the maximum slots available for skills.think to upgrade your current mebership plan';
$config['profile_management_gold_membership_subscriber_user_skills_maximum_slots_reached_error_message'] = 'you reached the maximum slots available for skills.';

$config['pa_profile_management_user_services_provided_required_field_error_message'] = 'Services provided is required.(p)';
$config['ca_profile_management_user_services_provided_required_field_error_message'] = 'Services provided is required.(c)';

$config['profile_management_free_membership_subscriber_user_services_provided_maximum_slots_reached_error_message'] = 'you reached the maximum slots available of services provided..think to upgrade your current mebership plan';
$config['profile_management_gold_membership_subscriber_user_services_provided_maximum_slots_reached_error_message'] = 'you reached the maximum slots available of services provided..think to upgrade your current mebership plan';

$config['profile_management_user_hourly_rate_required_field_error_message'] = 'Hourly rate is required.';

$config['pa_profile_management_user_mother_tongue_language_required_field_error_message'] = 'Mother tongue language is required.';
$config['pa_profile_management_user_mother_tongue_language_already_selected_as_foreign_spoken_language_field_error_message'] = 'you have already saved language as a foreign language.please select another one.';

//options for user spoken language levels used on profile management(spoken language tab)
// for personal account
$config['pa_profile_management_spoken_languages_understanding_drop_down_options'] = array('A1','A2','B1','B2','C1','C2');
$config['pa_profile_management_spoken_languages_speaking_drop_down_options'] = array('A1','A2','B1','B2','C1','C2');
$config['pa_profile_management_spoken_languages_writing_drop_down_options'] = array('A1','A2','B1','B2','C1','C2');

// For company app account
$config['ca_app_profile_management_spoken_languages_understanding_drop_down_options'] = array('A1','A2','B1','B2','C1','C2');
$config['ca_app_profile_management_spoken_languages_speaking_drop_down_options'] = array('A1','A2','B1','B2','C1','C2');
$config['ca_app_profile_management_spoken_languages_writing_drop_down_options'] = array('A1','A2','B1','B2','C1','C2');

//Config are use for invalid request of delete user spoken language(example user trying to delete same time from another tab another user delete request)
$config['pa_user_spoken_language_entry_already_deleted'] = "Request is invalid. you cant delete this entry, as it has been already deleted(pa spoken language)."; 
$config['ca_app_user_spoken_language_entry_already_deleted'] = "Request is invalid. you cant delete this entry, as it has been already deleted(app spoken language)."; 

$config['pa_profile_management_user_foreign_spoken_language_already_selected_as_mother_tongue_language_field_error_message'] = 'you have already saved language as a mother tongue language.please select another one.';
$config['ca_app_profile_management_user_foreign_spoken_language_already_selected_as_mother_tongue_language_field_error_message'] = 'you have already saved language as a mother tongue language.please select another one.(app)';


$config['pa_profile_management_free_membership_subscriber_user_foreign_spoken_languages_maximum_slots_reached_error_message'] = 'you reached the maximum slots available of spoken language. think to upgrade your current mebership plan';
$config['pa_profile_management_gold_membership_subscriber_user_foreign_spoken_languages_maximum_slots_reached_error_message'] = 'you reached the maximum slots available of spoken language';

$config['ca_app_profile_management_free_membership_subscriber_user_foreign_spoken_languages_maximum_slots_reached_error_message'] = 'you reached the maximum slots available of spoken language. think to upgrade your current mebership plan(app)';
$config['ca_app_profile_management_gold_membership_subscriber_user_foreign_spoken_languages_maximum_slots_reached_error_message'] = 'you reached the maximum slots available of spoken language(app)';

$config['profile_management_areas_of_expertise_newly_posted_projects_user_notifications_consent_txt'] = 'Receive realtime notifications about newly posted projects that fit your areas of expertise';

?>