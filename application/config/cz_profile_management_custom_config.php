<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//Left navigation Menu name
$config['pa_user_left_nav_profile_management'] = 'Správa profilu';
$config['ca_user_left_nav_profile_management'] = 'Správa profilu';

$config['profile_management_left_nav_profile_definitions'] = 'Všeobecné informace';

$config['ca_user_profile_management_left_nav_company_base_information'] = 'Provozní informace';

$config['pa_user_profile_management_left_nav_competencies'] = 'Kompetence';

$config['ca_user_profile_management_left_nav_competencies'] = 'Kompetence';
$config['ca_app_user_profile_management_left_nav_competencies'] = 'Kompetence';


$config['pa_user_profile_management_left_nav_mother_tongue'] = 'Rodilý jazyk';
$config['ca_app_user_profile_management_left_nav_mother_tongue'] = 'Rodilý jazyk';

$config['pa_user_profile_management_left_nav_spoken_foreign_languages'] = 'Cizí jazyky';
$config['ca_app_user_profile_management_left_nav_spoken_foreign_languages'] = 'Cizí jazyky';


$config['ca_user_profile_management_left_nav_company_values_and_principles'] = 'Hodnoty a strategie';
$config['ca_app_user_profile_management_left_nav_company_values_and_principles'] = 'Hodnoty a strategie';
/*
|--------------------------------------------------------------------------
| Meta Variables 
|--------------------------------------------------------------------------
| 
*/
################ Meta Config Variables for profile_management page ###########
/* Filename: application\modules\dashboard\controllers\Dashboard.php */
/* Controller: user Method name: index */
$config['profile_management_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Správa profilu';

$config['profile_management_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Nastavení a správa uživatelského profilu';

################ Url Routing Variables for profile_management page ###########
/* Filename: application\modules\dashboard\controllers\dashboard.php */
$config['profile_management_profile_definitions_page_url'] = 'vseob-info';

$config['profile_management_mother_tongue_page_url'] = 'rodily-jazyk';

$config['profile_management_spoken_foreign_languages_page_url'] = 'cizi-jazyky';

$config['profile_management_competencies_page_url'] = 'kompetence';

$config['profile_management_company_values_and_principles_page_url'] = 'hodnoty-strategie';

$config['profile_management_company_base_information_page_url'] = 'provozni-informace';


// for persoanl account
$config['pa_profile_management_headline_section_initial_view_title'] = 'Přidat nadpis';
$config['pa_profile_management_headline_section_initial_view_content'] = 'Nadpis Travai profilu se zobrazuje pod vašim jménem a je jednou z nejviditelnějších částí profilu. Představuje vás na stránce seznamu odborníků. Ať už se na něj dívají vaši obchodní přátelé, zaměstnavatelé nebo náboráři, váš Travai nadpis je klíčem k vytvoření pozitivního dojmu.<br><br>V nadpisu lze uvést krátké sdělení, motto nebo přísloví, které vás vykreslí jako důvěryhodného člena vašeho oboru. Ale měl by také obsahovat strategická klíčová slova, která vám pomohou při vyhledávání na Travai seznamu odborníků.';

// for company account
$config['ca_profile_management_headline_section_initial_view_title'] = 'Přidat nadpis';
$config['ca_profile_management_headline_section_initial_view_content'] = 'Nadpis Travai profilu se zobrazuje pod vašim názvem společnosti a je jednou z nejviditelnějších částí profilu. Představuje vás na stránce seznamu odborníků. Ať už se na něj dívají vaši obchodní partneři, zákazníci nebo potenciální zákazníci, váš Travai nadpis je klíčem k vytvoření pozitivního dojmu. V nadpisu lze uvést krátké sdělení, motto nebo přísloví, které vaši společnost vykreslí jako důvěryhodného člena vašeho oboru. Ale měl by také obsahovat strategická klíčová slova, která vám pomohou při vyhledávání na Travai seznamu odborníků.';

// for company account (app)
$config['ca_app_profile_management_headline_section_initial_view_title'] = 'Přidat nadpis';
$config['ca_app_profile_management_headline_section_initial_view_content'] = 'Nadpis Travai profilu se zobrazuje pod vašim jménem a je jednou z nejviditelnějších částí profilu. Představuje vás na stránce seznamu odborníků. Ať už se na něj dívají vaši obchodní partneři, zákazníci nebo potenciální zákazníci, váš Travai nadpis je klíčem k vytvoření pozitivního dojmu. V nadpisu lze uvést krátké sdělení, motto nebo přísloví, které vaše podnikání vykreslí jako důvěryhodného člena vašeho oboru. Ale měl by také obsahovat strategická klíčová slova, která vám pomohou při vyhledávání na Travai seznamu odborníků.';

//----------- description-----------
// For personal account
$config['pa_profile_management_description_section_initial_view_title'] = 'Přidat popis';
$config['pa_profile_management_description_section_initial_view_content'] = 'Popis je primární částí na každém profilu. V této části si sami uvádíte informace podle vlastních slov, bez omezení a počátečních dat. Ať už jej použijete k tomu, jestli uvedete všechny své úspěchy, a tím předvedli svoji osobnost. Tento popis je vaší volbou pro vložení svého nejlepšího já. Popis je také jedním z nejdůležitějších faktorů zvažovaných ve vyhledávacím Travai algoritmu.';


// For company account
$config['ca_profile_management_description_section_initial_view_title'] = 'Přidat popis';
$config['ca_profile_management_description_section_initial_view_content'] = 'Popis je primární částí na každém profilu. V této části si sami uvádíte informace podle vlastních slov, bez omezení a počátečních dat. Využijte tuto část na maximum a předveďte svou společnost v tom nejlepším světle a oslovte hned úvodem největší počet návštěvníků profilu. Popis je také jedním z nejdůležitějších faktorů zvažovaných ve vyhledávacím Travai algoritmu.';

// For company account(authorized physical person)
$config['ca_app_profile_management_description_section_initial_view_title'] = 'Přidat popis';
$config['ca_app_profile_management_description_section_initial_view_content'] = 'Popis je primární částí na každém profilu. V této části si sami uvádíte informace podle vlastních slov, bez omezení a počátečních dat. Využijte tuto část na maximum a předveďte své podnikání v tom nejlepším světle a oslovte hned úvodem největší počet návštěvníků profilu. Popis je také jedním z nejdůležitějších faktorů zvažovaných ve vyhledávacím Travai algoritmu.';

// For company account
$config['ca_profile_management_base_information_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Provozní informace';
$config['ca_profile_management_base_information_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Provozní informace';

// for authorized physical person
$config['ca_app_profile_management_base_information_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Provozní informace';
$config['ca_app_profile_management_base_information_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Provozní informace';

$config['ca_profile_management_headline_title_company_base_information'] = 'Provozní informace';
$config['ca_app_profile_management_headline_title_company_base_information'] = 'Provozní informace';

$config['ca_profile_management_base_information_company_founded_in_section_initial_view_title'] = 'Přidat založení společnosti';
$config['ca_profile_management_base_information_company_founded_in_section_initial_view_content'] = 'Zveřejněte datum založení společnosti, aby návštěvníci profilu mohli zjistit, zda jste společnost s dlouholetou tradicí nebo mladou dynamicky rozvíjející společností.';

$config['ca_app_profile_management_base_information_company_founded_in_section_initial_view_title'] = 'Přidat založení živnosti';
$config['ca_app_profile_management_base_information_company_founded_in_section_initial_view_content'] = 'Zveřejněte datum založení živnosti, aby návštěvníci profilu mohli zjistit, zda jste osobou samostatně výdělečně činnou s dlouholetou tradicí nebo právě začínáte rozvíjet své podnikání.';


$config['ca_profile_management_base_information_company_size_section_initial_view_title'] = 'Přidat velikost společnosti';
$config['ca_profile_management_base_information_company_size_section_initial_view_content'] = 'Informujte návštěvníky svého profilu o celkovém počtu zaměstnanců. Informace je určitě důležitá pro potenciální zaměstnance, personálním obsazení nebo při získávání projektů a navázání nových partnerů společnosti.';

$config['ca_app_profile_management_base_information_company_size_section_initial_view_title'] = 'Přidat počet zaměstnanců';
$config['ca_app_profile_management_base_information_company_size_section_initial_view_content'] = 'Informujte návštěvníky svého profilu o celkovém počtu zaměstnanců. Informace je určitě důležitá pro potenciální zaměstnance, personálním obsazení nebo při získávání projektů a navázání nových partnerů.';


$config['ca_profile_management_base_information_company_opening_hours_section_initial_view_title'] = 'Přidat otevírací dobu';
$config['ca_profile_management_base_information_company_opening_hours_section_location_available_initial_view_content'] = 'Informujte návštěvníky o otevírací době. Mít zveřejněnou otevírací dobu ocení všichni návštěvníci, kteří hledají lokálního partnera pro řešení svých potřeb. Pokaždé kdy přidáte další adresu a provozovnu (Správa účtu > Adresa a provozovny) lze přiřadit k jednotlivým adresám otevírací doba.';

$config['ca_profile_management_base_information_company_opening_hours_section_location_not_available_initial_view_content'] = '<b>Momentálně nelze přidat otevírací dobu.</b><br><br> Přidáním adresy <b>(Správa účtu > Adresa a provozovny)</b> budete moct pro každou vytvořenou adresu od sídla společnosti a jednotlivé provozy přidat aktuální otevírací dobu.<br><br> Mít zveřejněnou otevírací dobu ocení všichni návštěvníci, kteří hledají lokálního partnera pro řešení svých potřeb.';

$config['ca_app_profile_management_base_information_company_opening_hours_section_initial_view_title'] = 'Přidat otevírací dobu';
$config['ca_app_profile_management_base_information_company_opening_hours_section_location_available_initial_view_content'] = 'Informujte návštěvníky o otevírací době. Mít zveřejněnou otevírací dobu ocení všichni návštěvníci, kteří hledají lokálního partnera pro řešení svých potřeb. Pokaždé kdy přidáte další adresu a provozovnu (Správa účtu > Adresa a provozovny) lze přiřadit k jednotlivým adresám otevírací doba.';

$config['ca_app_profile_management_base_information_company_opening_hours_section_location_not_available_initial_view_content'] = '<b>Momentálně nelze přidat otevírací dobu.</b><br><br> Přidáním adresy <b>(Správa účtu > Adresa a provozovny)</b> budete moct pro každou vytvořenou adresu od sídla společnosti a jednotlivé provozy přidat aktuální otevírací dobu.<br><br> Mít zveřejněnou otevírací dobu ocení všichni návštěvníci, kteří hledají lokálního partnera pro řešení svých potřeb.';

$config['ca_profile_management_base_information_founded_in_tab_label'] = 'Založení společnosti';
$config['ca_app_profile_management_base_information_founded_in_tab_label'] = 'Založení živnosti';

$config['ca_profile_management_base_information_company_size_tab_label'] = 'Velikost společnosti';
$config['ca_app_profile_management_base_information_company_size_tab_label'] = 'Počet zaměstnanců';

$config['ca_profile_management_base_information_opening_hours_tab_label'] = 'Otevírací doba';
$config['ca_app_profile_management_base_information_opening_hours_tab_label'] = 'Otevírací doba';

$config['ca_profile_management_base_information_year_select_year'] = 'vybrat rok';

$config['ca_profile_management_base_information_founded_in_required_error_message'] = 'výběr nemůže být prázdný';
$config['ca_app_profile_management_base_information_founded_in_required_error_message'] = 'výběr nemůže být prázdný';


$config['ca_profile_management_base_information_select_company_size_option'] = 'velikost společnosti';
$config['ca_app_profile_management_base_information_select_company_size_option'] = 'počet zaměstnanců';

$config['ca_profile_management_base_information_company_size_dropdown_option'] = [
	'jednatel',
	'1 - 9 zaměstnanců',
	'10 - 25 zaměstnanců',
	'26 - 49 zaměstnanců',
	'50 - 149 zaměstnanců',
	'150 - 249 zaměstnanců',
	'více než 250 zaměstnanců'
];

$config['ca_app_profile_management_base_information_company_size_dropdown_option'] = [
	'1 - 9',
	'10 - 25',
	'26 - 49',
	'50 - 149',
	'150 - 249',
	'více než 250'
];

$config['ca_profile_management_base_information_company_size_required_error_message'] = 'výběr nemůže být prázdný';
$config['ca_app_profile_management_base_information_company_size_required_error_message'] = 'výběr nemůže být prázdný';

$config['ca_profile_management_base_information_select_company_location_option'] = 'vybrat adresu provozovny';
$config['ca_app_profile_management_base_information_select_company_location_option'] = 'vybrat adresu provozovny';

$config['ca_profile_management_base_information_company_opened_on_selected_hours_label'] = 'Otevírací doba';
$config['ca_app_profile_management_base_information_company_opened_on_selected_hours_label'] = 'Otevírací doba';

$config['ca_profile_management_base_information_company_always_opened_label'] = 'Stále otevřeno';
$config['ca_app_profile_management_base_information_company_always_opened_label'] = 'Stále otevřeno';

$config['ca_profile_management_base_information_company_permanently_closed_label'] = 'Stále zavřeno';
$config['ca_app_profile_management_base_information_company_permanently_closed_label'] = 'Stále zavřeno';

$config['ca_profile_management_base_information_company_telephone_appointment_label'] = 'Po telefonické dohodě';
$config['ca_app_profile_management_base_information_company_telephone_appointment_label'] = 'Po telefonické dohodě';

$config['ca_profile_management_base_information_select_company_opening_time_option'] = 'otevírací doba';
$config['ca_app_profile_management_base_information_select_company_opening_time_option'] = 'otevírací doba';

$config['ca_profile_management_base_information_select_company_closing_time_option'] = 'zavírací doba';
$config['ca_app_profile_management_base_information_select_company_closing_time_option'] = 'zavírací doba';

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

$config['ca_profile_management_base_information_company_opened_hours_select_at_least_one_day_error_message'] = 'vyplňte pracovní dobu minimálně u 1 dne';
$config['ca_app_profile_management_base_information_company_opened_hours_select_at_least_one_day_error_message'] = 'vyplňte pracovní dobu minimálně u 1 dne';

$config['ca_profile_management_base_information_company_opened_hours_opening_time_required_error_message'] = 'výběr nemůže být prázdný';
$config['ca_app_profile_management_base_information_company_opened_hours_opening_time_required_error_message'] = 'výběr nemůže být prázdný';

$config['ca_profile_management_base_information_company_opened_hours_closing_time_required_error_message'] = 'výběr nemůže být prázdný';
$config['ca_app_profile_management_base_information_company_opened_hours_closing_time_required_error_message'] = 'výběr nemůže být prázdný';

$config['ca_profile_management_base_information_company_opening_hours_saved_confirmation_message'] = 'otevírací doba byla uložena';
$config['ca_app_profile_management_base_information_company_opening_hours_saved_confirmation_message'] = 'otevírací doba byla uložena';

$config['ca_profile_management_base_information_company_opening_hours_conflict_no_location_available_warning_message'] = "<b>Upozornění: nastavení bylo změněno. Aktualizujte stránku.</b>";
$config['ca_app_profile_management_base_information_company_opening_hours_conflict_no_location_available_warning_message'] = "<b>Upozornění: nastavení bylo změněno. Aktualizujte stránku.</b>";

/**
 * This error message will display to user when there is multiple locations available now user try save opening hours for particular location and in background that location removed
 */
$config['ca_profile_management_base_information_company_opening_hours_location_not_valid_error_message'] = "adresa neexistuje";

$config['ca_app_profile_management_base_information_company_opening_hours_location_not_valid_error_message'] = "adresa neexistuje";

/* -------------------------------------- Company values and principles --------------------------------------------------------------------------------------- */
$config['ca_profile_management_company_values_and_principles_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Hodnoty, principy a strategie';
$config['ca_profile_management_company_values_and_principles_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Hodnoty, principy a strategie';


// company app account
$config['ca_app_profile_management_company_values_and_principles_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Hodnoty, principy a strategie';
$config['ca_app_profile_management_company_values_and_principles_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Hodnoty, principy a strategie';


$config['ca_profile_management_company_values_and_principles_title'] = 'Hodnoty, principy a strategie';
$config['ca_app_profile_management_company_values_and_principles_title'] = 'Hodnoty, principy a strategie';

$config['ca_profile_management_company_values_and_principles_vision_tab_label'] = 'Vize';
$config['ca_profile_management_company_values_and_principles_mission_tab_label'] = 'Mise';
$config['ca_profile_management_company_values_and_principles_core_values_tab_label'] = 'Základní hodnoty';
$config['ca_profile_management_company_values_and_principles_strategy_goals_tab_label'] = 'Obchodní strategie';

$config['ca_app_profile_management_company_values_and_principles_vision_tab_label'] = 'Vize';
$config['ca_app_profile_management_company_values_and_principles_mission_tab_label'] = 'Mise';
$config['ca_app_profile_management_company_values_and_principles_core_values_tab_label'] = 'Základní hodnoty';
$config['ca_app_profile_management_company_values_and_principles_strategy_goals_tab_label'] = 'Obchodní strategie';


$config['ca_profile_management_company_values_and_principles_vision_section_initial_view_title'] = 'Přidat vizi společnosti';
$config['ca_profile_management_company_values_and_principles_vision_section_initial_view_content'] = 'Vaše vize představuje směr a budouctnost společnosti. Pro mnoho zákazníků a partnerů je určitě důležité vědět, jakým způsobem chcete dál růst, jaké jsou vaše sny, naděje a čím se inspirujete do budouctnosti.
<br><br>Nezapomeňte, že cílená společnost vždy bere úspěch jako jasný cíl pro neustálý rozvoj a jasnou strategii pohybu vzhůru.';

// for app(authorized physical person)
$config['ca_app_profile_management_company_values_and_principles_vision_section_initial_view_title'] = 'Přidat vizi';
$config['ca_app_profile_management_company_values_and_principles_vision_section_initial_view_content'] = 'Vize představuje směr a budouctnost vašeho podnikání. Pro mnoho zákazníků a partnerů je určitě důležité vědět, jakým způsobem chcete dál růst, jaké jsou vaše sny, naděje a čím se inspirujete do budouctnosti.
<br><br>Nezapomeňte, že cílený podnikatel vždy bere úspěch jako jasný cíl pro neustálý rozvoj a jasnou strategii pohybu vzhůru.';

$config['ca_profile_management_company_values_and_principles_mission_section_initial_view_title'] = 'Přidat misi společnosti';
$config['ca_profile_management_company_values_and_principles_mission_section_initial_view_content'] = 'Dejte jasnou odpověď vašim partnerům, zákazníkům a potenciálním zákazníkům co je vašim smyslem podnikání. Co přinášíte, jakým způsobem fungujete a na koho cílíte své služby, vyplněním této části vašeho profilu.
<br><br>Ať už jste malá, střední nebo velká společnost, tak každý podnikající subjekt by měl mít svůj smysl podnikání. Předveďte se a zaujměte šíroké spektrum návštěvníků profilu.';

// for app(authorized physical person)
$config['ca_app_profile_management_company_values_and_principles_mission_section_initial_view_title'] = 'Přidat misi';
$config['ca_app_profile_management_company_values_and_principles_mission_section_initial_view_content'] = 'Dejte jasnou odpověď vašim partnerům, zákazníkům a potenciálním zákazníkům co je vašim smyslem podnikání. Co přinášíte, jakým způsobem fungujete a na koho cílíte své služby, vyplněním této části vašeho profilu.
<br><br>Ať už začínáte nebo dlouhodobě podnikáte, tak každý podnikatel by měl mít svůj smysl podnikání. Předveďte se a zaujměte šíroké spektrum návštěvníků profilu.';


$config['ca_profile_management_company_values_and_principles_core_values_section_initial_view_title'] = 'Přidat základní hodnoty společnosti';
$config['ca_profile_management_company_values_and_principles_core_values_section_initial_view_content'] = 'Uvést základní hodnoty neboli základní přesvědčení společnosti je jednou z důležitých strategických hodnot. Tyto hodnoty nejen, že pomáhají vám určit, zda jste na "správné cestě" při plnění stanovených cílů, ale jednoduše sdělíte partnerům a zákazníkům vaše poslání.
<br><br>Popis základních hodnot se může lišit v závislosti na druhu společnosti. Nejefektivněji je dobré tyto hodnoty shrnout do jedné ucelené a výstižné věty nebo motta společnosti.';

// for app(authorized physical person)
$config['ca_app_profile_management_company_values_and_principles_core_values_section_initial_view_title'] = 'Přidat základní hodnoty';
$config['ca_app_profile_management_company_values_and_principles_core_values_section_initial_view_content'] = 'Uvést základní hodnoty neboli základní přesvědčení podnikání je jednou z důležitých strategických hodnot. Tyto hodnoty nejen, že pomáhají vám určit, zda jste na "správné cestě" při plnění stanovených cílů, ale jednoduše sdělíte partnerům a zákazníkům vaše poslání.
<br><br>Popis základních hodnot se může lišit v závislosti na druhu podnikatele. Nejefektivněji je dobré tyto hodnoty shrnout do jedné ucelené a výstižné věty nebo motta.';


$config['ca_profile_management_company_values_and_principles_strategy_goals_section_initial_view_title'] = 'Přidat obchodní strategii';
$config['ca_profile_management_company_values_and_principles_strategy_goals_section_initial_view_content'] = 'Obchodní strategie není a neměla by být tajnou informací. V této sekci informujete své návštěvníky profilu, zákazníky a partnery o plnění aktuální mise a které aktuální cíle plníte. Obchodní strategie koresponduje s aktuální misí a cílem v daném období, proto i nadále nezapomínejte aktualizovat tuto sekci.';

// for app(authorized physical person)
$config['ca_app_profile_management_company_values_and_principles_strategy_goals_section_initial_view_title'] = 'Přidat obchodní strategii';
$config['ca_app_profile_management_company_values_and_principles_strategy_goals_section_initial_view_content'] = 'Obchodní strategie není a neměla by být tajnou informací. V této sekci informujete své návštěvníky profilu, zákazníky a partnery o plnění aktuální mise a které aktuální cíle plníte. Obchodní strategie koresponduje s aktuální misí a cílem v daném období, proto i nadále nezapomínejte aktualizovat tuto sekci.';

/*----------- hourly rate----------------*/

// For perosonal account
$config['pa_profile_management_hourly_rate_section_initial_view_title'] = 'Přidat hodinovou sazbu';
$config['pa_profile_management_hourly_rate_section_initial_view_content'] = 'Hodinová sazba je uváděná pod vaší profilovou fotkou a hodnocením na stránce seznamu odborníků nebo na detailu profilu. Motivace pracovat na "volné noze" poskytuje cestu k vydělávání vyššího příjmu a možností stát se nezávislým pracovníkem. Je důležité účtovat za svoji odbornou činnost správnou hodinovou sazbu. Je dobré najít vyváženou rovnováhu založenou na vašich zkušenostech, úrovni dovedností a na tom, co považujete za oprávněnou kompenzaci za svůj čas.
	<br><br><p><strong>Nezapomeňte</strong> na stránce seznamu odbroníků lze nastavit filtr a upřesnit hledání dle výše hodinové sazby. Tímto lze hledat odborníky s preferovanou hodinovou sazbou. Profily, které nemají uvedenou hodinovou sazbu, nebudou zobrazené ve výsledcích hledání.</p>';

// For company account
$config['ca_profile_management_hourly_rate_section_initial_view_title'] = 'Přidat hodinovou sazbu';
$config['ca_profile_management_hourly_rate_section_initial_view_content'] = 'Hodinová sazba je uváděná pod vašim logem/profilovou fotkou a hodnocením na stránce seznamu odborníků nebo na detailu profilu. Jako nově roustoucí nebo stálá společnost s tradicí vypočítáváte své služby v poměru odbornosti a dovednosti. Výše částky se v závislosti na konkrétních službách zajisté mění, ale na profil je dobré uvést alespoň průměrnou hodinovou sazbu vycházející z vašeho ceníku služeb.
	<br><br><p><strong>Nezapomeňte</strong> na stránce seznamu odbroníků lze nastavit filtr a upřesnit hledání dle výše hodinové sazby. Tímto lze hledat odborníky s preferovanou hodinovou sazbou. Profily, které nemají uvedenou hodinovou sazbu, nebudou zobrazené ve výsledcích hledání.';

// For company account(app)
$config['ca_app_profile_management_hourly_rate_section_initial_view_title'] = 'Přidat hodinovou sazbu';
$config['ca_app_profile_management_hourly_rate_section_initial_view_content'] = 'Hodinová sazba je uváděná pod vašim logem/profilovou fotkou a hodnocením na stránce seznamu odborníků nebo na detailu profilu. Jako začínající nebo stálý podnikatel vypočítáváte své služby v poměru odbornosti a dovednosti. Výše částky se v závislosti na konkrétních službách zajisté mění, ale na profil je dobré uvést alespoň průměrnou hodinovou sazbu vycházející z vašeho ceníku služeb.
	<br><br><p><strong>Nezapomeňte</strong> na stránce seznamu odbroníků lze nastavit filtr a upřesnit hledání dle výše hodinové sazby. Tímto lze hledat odborníky s preferovanou hodinovou sazbou. Profily, které nemají uvedenou hodinovou sazbu, nebudou zobrazené ve výsledcích hledání.';


/*---------- mother tongue --------------*/
$config['pa_profile_management_mother_tongue_section_select_mother_tongue_initial_selection'] = 'přidat rodilý jazyk';
$config['pa_profile_management_mother_tongue_section_initial_view_title'] = 'Přidat rodilý jazyk';
$config['pa_profile_management_mother_tongue_section_initial_view_content'] = 'Vyplňte svůj rodilý jazyk. V České republice je mnoho tuzemských i zahraničních společností, které upřednostňují pracovníky s rodilým jazykem. Vyplněním rodilého jazyka může být výhodou pro získání některých projektů či zaměstnání.';

// for company account app 
$config['ca_app_profile_management_mother_tongue_section_select_mother_tongue_initial_selection'] = 'přidat rodilý jazyk';
$config['ca_app_profile_management_mother_tongue_section_initial_view_title'] = 'Přidat rodilý jazyk';
$config['ca_app_profile_management_mother_tongue_section_initial_view_content'] = 'Vyplňte svůj rodilý jazyk. V České republice je mnoho tuzemských i zahranicních spolecností, které uprednostnují odborníky s rodilým jazykem. Vyplněním rodilého jazyka může být výhodou pro získání některých projektů či zaměstnání.';
$config['ca_app_profile_management_mother_tongue_page_headline_title'] = 'Rodilý jazyk';

//mother tongue option name which come first into drop down on mother tongue on profile management(for perosonal account)
$config['pa_profile_management_user_mother_tongue_language_required_field_error_message'] = 'výběr nemůže být prázdný';

$config['pa_profile_management_user_mother_tongue_language_already_selected_as_foreign_spoken_language_field_error_message'] = 'nelze vybrat tento jazyk, byl vybrán jako cizí jazyk';

// config for meta tag regarding mother tongue language page
$config['pa_user_profile_management_mother_tongue_language_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Rodilý jazyk';
$config['pa_user_profile_management_mother_tongue_language_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Rodilý jazyk';

// config for meta tag regarding mother tongue language page
$config['ca_app_user_profile_management_mother_tongue_language_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Rodilý jazyk';
$config['ca_app_user_profile_management_mother_tongue_language_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Rodilý jazyk';


/*----------- Skills----------------*/
// for persoanl account
$config['pa_profile_management_skills_section_initial_view_title'] = 'Přidat dovednosti';
$config['pa_profile_management_skills_section_initial_view_content'] = 'Uveďte své dovednosti, kterými vynikáte. Zobrazují se na profilu a jsou jeho důležitou částí. Také patří mezi zvažované faktory ve vyhledávacím Travai algoritmu, podle kterého může být profil vyhledatelný na stránce seznamu odborníků.<br><br>Váš seznam dovedností jsou klíčová slova, která napovídají návštěvníkům profilu, zaměstnavatelům na první pohled v čem jste kvalifikovaní. Usnadňuje všem hledání a kontaktování právě vás. Čím více dovedností uvedete, tím vyšší jsou vaše šance být kontaktováni lidmi a firmami, které potřebují vaše dovednosti.';


// for company account
$config['ca_profile_management_skills_section_initial_view_title'] = 'Přidat dovednosti';
$config['ca_profile_management_skills_section_initial_view_content'] = 'Uveďte dovednosti, kterými vaše společnost vyniká. Zobrazují se na profilu a jsou jeho důležitou částí. Také patří mezi zvažované faktory ve vyhledávacím Travai algoritmu, podle kterého může být profil vyhledatelný na stránce seznamu odborníků.<br><br>Váš seznam dovedností jsou klíčová slova, která napovídají návštěvníkům profilu, zákazníkům, partnerům na první pohled jak je vaše společnost kvalifikovaná. Usnadňuje všem hledání a kontaktování právě vás. Čím více dovedností uvedete, tím vyšší jsou vaše šance být kontaktováni lidmi a firmami, které potřebují vaše dovednosti.';

// for company account(app)
$config['ca_app_profile_management_skills_section_initial_view_title'] = 'Přidat dovednosti';
$config['ca_app_profile_management_skills_section_initial_view_content'] = 'Uveďte dovednosti, kterými vynikáte. Zobrazují se na profilu a jsou jeho důležitou částí. Také patří mezi zvažované faktory ve vyhledávacím Travai algoritmu, podle kterého může být profil vyhledatelný na stránce seznamu odborníků.<br><br>Váš seznam dovedností jsou klíčová slova, která napovídají návštěvníkům profilu, zákazníkům, partnerům na první pohled jak je vaše podnikání kvalifikované. Usnadňuje všem hledání a kontaktování právě vás. Čím více dovedností uvedete, tím vyšší jsou vaše šance být kontaktováni lidmi a firmami, které potřebují vaše dovednosti.';

/*----------- Service Provided----------------*/
// for persoanl account
$config['pa_profile_management_services_provided_section_initial_view_title'] = 'Přidat nabízené služby';
$config['pa_profile_management_services_provided_section_initial_view_content'] = 'Uveďte nabízené služby, které jsou zobrazovány na stránce profilu. Nabízené služby doplňují obory odborných dovedností a rozšiřují portfolio, které pak vede k získávání vyššího počtu poptávek a možné další spojení s jinými perspektivami.<br><br>Jsou také jedením z nejdůležitějších faktorů uvažovaných ve vyhledávacím algoritmu Travai, podle kterého lze profil vyhledávat na stránce odborníků. Seznam vašich nabízených služeb na stránce vašeho profilu usnadní každému návštěvníkovi orientaci při hledání a kontaktování.';

// for company account
$config['ca_profile_management_services_provided_section_initial_view_title'] = 'Přidat nabízené služby';
$config['ca_profile_management_services_provided_section_initial_view_content'] = 'Uveďte nabízené služby, které jsou zobrazovány na stránce profilu. Nabízené služby doplňují obory odborných dovedností a rozšiřují portfolio, které pak vede k získávání vyššího počtu poptávek a možné další spojení s jinými perspektivami.<br><br>Jsou také jedením z nejdůležitějších faktorů uvažovaných ve vyhledávacím algoritmu Travai, podle kterého lze profil vyhledávat na stránce odborníků. Seznam vašich nabízených služeb na stránce vašeho profilu usnadní každému návštěvníkovi orientaci při hledání a kontaktování.';

// for company account(app)
$config['ca_app_profile_management_services_provided_section_initial_view_title'] = 'Přidat nabízené služby';
$config['ca_app_profile_management_services_provided_section_initial_view_content'] = 'Uveďte nabízené služby, které jsou zobrazovány na stránce profilu. Nabízené služby doplňují obory odborných dovedností a rozšiřují portfolio, které pak vede k získávání vyššího počtu poptávek a možné další spojení s jinými perspektivami.<br><br>Jsou také jedením z nejdůležitějších faktorů uvažovaných ve vyhledávacím Travai algoritmu, podle kterého lze profil vyhledávat na stránce odborníků. Seznam vašich nabízených služeb na stránce vašeho profilu usnadní každému návštěvníkovi orientaci při hledání a kontaktování.';


/*----------- Areas Of Expertise----------------*/
$config['pa_user_profile_management_competencies_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Kompetence';
$config['pa_user_profile_management_competencies_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Kompetence';

$config['ca_user_profile_management_competencies_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Kompetence';
$config['ca_user_profile_management_competencies_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Kompetence';

$config['ca_app_user_profile_management_competencies_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Kompetence';
$config['ca_app_user_profile_management_competencies_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Kompetence';


// competencies page tabs text of profile management
$config['pa_profile_management_competencies_page_areas_of_expertise_tab_txt'] = 'Odborné činnosti';
$config['pa_profile_management_competencies_page_skills_tab_txt'] = 'Dovednosti';
$config['pa_profile_management_competencies_page_services_provided_tab_txt'] = 'Nabízené služby';

$config['ca_profile_management_competencies_page_areas_of_expertise_tab_txt'] = 'Odborné činnosti';
$config['ca_profile_management_competencies_page_skills_tab_txt'] = 'Dovednosti';
$config['ca_profile_management_competencies_page_services_provided_tab_txt'] = 'Nabízené služby';

$config['ca_app_profile_management_competencies_page_areas_of_expertise_tab_txt'] = 'Odborné činnosti';
$config['ca_app_profile_management_competencies_page_skills_tab_txt'] = 'Dovednosti';
$config['ca_app_profile_management_competencies_page_services_provided_tab_txt'] = 'Nabízené služby';


// For personal account
$config['pa_profile_management_areas_of_expertise_section_initial_view_title'] = 'Přidat odborné činnosti';
$config['pa_profile_management_areas_of_expertise_section_initial_view_content'] = 'Vzdělání a odbronost by měly korespondovat s výběrem kategorií odbroných činností. Pečlivý výběr odborných činností na základě vašeho vzdělání a odborností je nejúčinnějším způsobem získávání vyhovujícím poptávkám projektů a pracovních míst.<br><br><strong>Nezapomeňte</strong> při volbě kategorií odborných činností je omezen na základě předplatného členství. Pro možnost uvedení více kategorií oborů je nutné zvýšit své členství.';

// For company account
$config['ca_profile_management_areas_of_expertise_section_initial_view_title'] = 'Přidat odborné činnosti';
$config['ca_profile_management_areas_of_expertise_section_initial_view_content'] = 'Obory společnosti a odbronost by měly korespondovat s výběrem kategorií odbroných činností. Pečlivý výběr odborných činností na základě vašich nabízených služeb a odborností je nejúčinnějším způsobem k získávání vyhovujícím poptávkám projektů.<br><br><strong>Nezapomeňte</strong> při volbě kategorií odborných činností je omezen na základě předplatného členství. Pro možnost uvedení více kategorií oborů je nutné zvýšit své členství.';

// For company account(app)
$config['ca_app_profile_management_areas_of_expertise_section_initial_view_title'] = 'Přidat odborné činnosti';
$config['ca_app_profile_management_areas_of_expertise_section_initial_view_content'] = 'Obory podnikání a odbronost by měly korespondovat s výběrem kategorií odbroných činností. Pečlivý výběr odborných činností na základě vašich nabízených služeb a odborností je nejúčinnějším způsobem k získávání vyhovujícím poptávkám projektů.<br><br><strong>Nezapomeňte</strong> při volbě kategorií odborných činností je omezen na základě předplatného členství. Pro možnost uvedení více kategorií oborů je nutné zvýšit své členství.';


// For personal account
$config['pa_profile_management_areas_of_expertise_section_title_categories'] = 'Kategorie';
$config['pa_profile_management_areas_of_expertise_section_title_subcategories'] = 'Pod kategorie';
$config['pa_profile_management_areas_of_expertise_section_title_add_another_category_btn'] = 'Přidat další odborné činnosti';

// for company account
$config['ca_profile_management_areas_of_expertise_section_title_categories'] = 'Kategorie';
$config['ca_profile_management_areas_of_expertise_section_title_subcategories'] = 'Pod kategorie';
$config['ca_profile_management_areas_of_expertise_section_title_add_another_category_btn'] = 'Přidat další odborné činnosti';

// config for personal account
$config['pa_profile_management_areas_of_expertise_section_select_areas_of_expertise_category_initial_selection'] = 'vybrat kategorii';
$config['pa_profile_management_areas_of_expertise_section_select_areas_of_expertise_subcategory_initial_selection'] = 'vybrat podkategorii';

// config for company account
$config['ca_profile_management_areas_of_expertise_section_select_areas_of_expertise_category_initial_selection'] = 'vybrat kategorii';
$config['ca_profile_management_areas_of_expertise_section_select_areas_of_expertise_subcategory_initial_selection'] = 'vybrat podkategorii';


$config['profile_management_areas_of_expertise_newly_posted_projects_user_notifications_consent_txt'] = 'získávat oznámení o nově zveřejněných inzerátech v mých vybraných kategoriích odborných činností';



$config['user_profile_management_free_membership_subscriber_category_maximum_slots_reached_error_message'] = 'momentálně nelze uložit tento výběr, aktualizujte stránku';
$config['user_profile_management_gold_membership_subscriber_category_maximum_slots_reached_error_message'] = 'momentálně nelze uložit tento výběr, aktualizujte stránku';

$config['user_profile_management_areas_of_expertise_page_free_membership_subscriber_max_categories_entries_membership_upgrade_calltoaction'] = 'dosáhli jste maximálního počtu odborných činností v Základním členství. zvyšte své členství na Perfektní, které nabízí přidání až 15 odborných činností. <a href="{membership_page_url}">změnit zde....</a>';

// error messages variable definitaion
$config['profile_management_areas_of_expertise_valid_category_not_existent_popup_message'] = "Zrejmě došlo k chybě. Aktualizujte stránku.";// this message will show when admin deactive/delete the category from admin and user is using these category and process


// Config for meta tag for profile definations page of profile management
$config['user_profile_management_profile_definitions_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Všeobecné informace';
$config['user_profile_management_profile_definitions_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Všeobecné informace';

$config['profile_management_headline_title_profile_definitions'] = 'Všeobecné informace';

$config['profile_management_headline_title_competencies'] = 'Kompetence';

$config['profile_management_headline_title_mother_tongue'] = 'Rodilý jazyk';

$config['profile_management_headline_title_spoken_foreign_languages'] = 'Cizí jazyky';

$config['pa_profile_management_mother_tongue_page_headline_title'] = 'Rodilý jazyk';

// for personal account
$config['pa_profile_management_spoken_foreign_languages_page_headline_title'] = 'Cizí jazyk';

// for company app account
$config['ca_app_profile_management_spoken_foreign_languages_page_headline_title'] = 'Cizí jazyk';

// profile definitions page tab heading of profile management
$config['pa_profile_management_profile_definitions_page_headline_tab_txt'] = 'Nadpis';
$config['pa_profile_management_profile_definitions_page_description_tab_txt'] = 'Popis';
$config['pa_profile_management_profile_definitions_page_hourly_rate_tab_txt'] = 'Hodinová sazba';

$config['ca_profile_management_profile_definitions_page_headline_tab_txt'] = 'Nadpis';
$config['ca_profile_management_profile_definitions_page_description_tab_txt'] = 'Popis';
$config['ca_profile_management_profile_definitions_page_hourly_rate_tab_txt'] = 'Hodinová sazba';

$config['ca_app_profile_management_profile_definitions_page_headline_tab_txt'] = 'Nadpis';
$config['ca_app_profile_management_profile_definitions_page_description_tab_txt'] = 'Popis';
$config['ca_app_profile_management_profile_definitions_page_hourly_rate_tab_txt'] = 'Hodinová sazba';



/*----------- Base Information Spoken Languages----------------*/
// for personal account
$config['pa_profile_management_spoken_foreign_languages_section_initial_view_title'] = 'Přidat cizí jazyk';
$config['pa_profile_management_spoken_foreign_languages_section_initial_view_content'] = 'Globalizované společnosti se spoléhají zejména na nadnárodní týmy, aby šířily osvědčené postupy a využívaly inovace odkudkoli na světě. Přidáním jazykových znalostí a definováním úrovně komunikace a psaní zvýšíte svoje příležitosti k získání kvalitnějších nabídek. Zaměstnavatelé neustále hledají pracovníky nebo partnery, pro realizaci projektů, kteří mají vaše dovednosti. Pokud jste neuvedli své znalosti cizích jazyků ve svém profilu, může to být pro vás nevýhodou.';

// for company app account 
$config['ca_app_profile_management_spoken_foreign_languages_section_initial_view_title'] = 'Přidat cizí jazyk';
$config['ca_app_profile_management_spoken_foreign_languages_section_initial_view_content'] = 'Globalizované společnosti se spoléhají zejména na nadnárodní týmy, aby šířily osvědčené postupy a využívaly inovace odkudkoli na světě. Přidáním jazykových znalostí a definováním úrovně komunikace a psaní zvýšíte svoje příležitosti k získání kvalitnějších nabídek. Zaměstnavatelé neustále hledají pracovníky nebo partnery, pro realizaci projektů, kteří mají vaše dovednosti. Pokud jste neuvedli své znalosti cizích jazyků ve svém profilu, může to být pro vás nevýhodou.';

// config for meta tag regarding spoken language page (for personal account)
$config['pa_user_profile_management_spoken_languages_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Cizí jazyky';
$config['pa_user_profile_management_spoken_languages_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Cizí jazyky';

// config for meta tag regarding spoken language page (for company app account)
$config['ca_app_user_profile_management_spoken_languages_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Cizí jazyky';
$config['ca_app_user_profile_management_spoken_languages_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Cizí jazyky';

// Base Information variable definitaion + also used on user profile page(For personal account)
$config['pa_profile_management_spoken_foreign_languages_section_title'] = 'Jazyk';
$config['pa_profile_management_spoken_foreign_languages_section_title_understanding'] = 'Porozumění';
$config['pa_profile_management_spoken_foreign_languages_section_title_speaking'] = 'Mluvení';
$config['pa_profile_management_spoken_foreign_languages_section_title_writing'] = 'Psaní';

// Base Information variable definitaion + also used on user profile page(For company app account)
$config['ca_app_profile_management_spoken_foreign_languages_section_title'] = 'Jazyk';
$config['ca_app_profile_management_spoken_foreign_languages_section_title_understanding'] = 'Porozumění';
$config['ca_app_profile_management_spoken_foreign_languages_section_title_speaking'] = 'Mluvení';
$config['ca_app_profile_management_spoken_foreign_languages_section_title_writing'] = 'Psaní';


// Base Information Spoken Language variable definitaion
// for personal account
$config['pa_profile_management_spoken_foreign_languages_section_select_language_initial_selection'] = 'vybrat jazyk';
$config['pa_profile_management_spoken_foreign_languages_section_select_level_initial_selection'] = 'vybrat úroveň';

// for company app account
$config['ca_app_profile_management_spoken_foreign_languages_section_select_language_initial_selection'] = 'vybrat jazyk';
$config['ca_app_profile_management_spoken_foreign_languages_section_select_level_initial_selection'] = 'vybrat úroveň';

$config['pa_user_profile_management_spoken_languages_page_free_membership_subscriber_max_spoken_languages_entries_membership_upgrade_calltoaction'] = 'dosáhli jste maximálního počtu vyplnění cizích jazyků v Základním členství. zvyšte své členství na Perfektní, které nabízí přidání až 25 cizích jazyků. <a href="{membership_page_url}" target="blank">změnit zde...</a>';

$config['ca_app_user_profile_management_spoken_languages_page_free_membership_subscriber_max_spoken_languages_entries_membership_upgrade_calltoaction'] = 'dosáhli jste maximálního počtu vyplnění cizích jazyků v Základním členství. zvyšte své členství na Perfektní, které nabízí přidání až 25 cizích jazyků. <a href="{membership_page_url}" target="blank">změnit zde...</a>';


// Base Information Spoken Language Add Another Language Button variable definitaion
$config['pa_profile_management_spoken_foreign_languages_section_add_another_category_btn'] = 'Přidat další jazyk';
$config['ca_app_profile_management_spoken_foreign_languages_section_add_another_category_btn'] = 'Přidat další jazyk';

################ validation message for profile management #####
$config['ca_profile_management_user_headline_required_field_error_message'] = 'pole nemůže být prázdné';

$config['pa_profile_management_user_headline_required_field_error_message'] = 'pole nemůže být prázdné';

$config['ca_profile_management_user_description_required_field_error_message'] = 'pole nemůže být prázdné';

$config['pa_profile_management_user_description_required_field_error_message'] = 'pole nemůže být prázdné';


$config['pa_profile_management_user_skills_required_field_error_message'] = 'pole nemůže být prázdné';
$config['ca_profile_management_user_skills_required_field_error_message'] = 'pole nemůže být prázdné';



$config['profile_management_free_membership_subscriber_user_skills_maximum_slots_reached_error_message'] = 'momentálně nelze uložit tento výběr, aktualizujte stránku';

$config['profile_management_gold_membership_subscriber_user_skills_maximum_slots_reached_error_message'] = 'momentálně nelze uložit tento výběr, aktualizujte stránku';


$config['pa_profile_management_user_services_provided_required_field_error_message'] = 'pole nemůže být prázdné';
$config['ca_profile_management_user_services_provided_required_field_error_message'] = 'pole nemůže být prázdné';


$config['profile_management_free_membership_subscriber_user_services_provided_maximum_slots_reached_error_message'] = 'momentálně nelze uložit tento výběr, aktualizujte stránku';
$config['profile_management_gold_membership_subscriber_user_services_provided_maximum_slots_reached_error_message'] = 'momentálně nelze uložit tento výběr, aktualizujte stránku';

$config['profile_management_user_hourly_rate_required_field_error_message'] = 'pole nemůže být prázdné';

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
$config['pa_user_spoken_language_entry_already_deleted'] = "Nelze provést tuto volbu. Jazyk byl smazán.";
$config['ca_app_user_spoken_language_entry_already_deleted'] = "Nelze provést tuto volbu. Jazyk byl smazán.";

$config['pa_profile_management_user_foreign_spoken_language_already_selected_as_mother_tongue_language_field_error_message'] = 'nelze vybrat tento jazyk, byl vybrán jako rodilý jazyk';
$config['ca_app_profile_management_user_foreign_spoken_language_already_selected_as_mother_tongue_language_field_error_message'] = 'nelze vybrat tento jazyk, byl vybrán jako rodilý jazyk';

$config['pa_profile_management_free_membership_subscriber_user_foreign_spoken_languages_maximum_slots_reached_error_message'] = 'momentálně nelze uložit tento výběr, aktualizujte stránku';
$config['pa_profile_management_gold_membership_subscriber_user_foreign_spoken_languages_maximum_slots_reached_error_message'] = 'momentálně nelze uložit tento výběr, aktualizujte stránku';

$config['ca_app_profile_management_free_membership_subscriber_user_foreign_spoken_languages_maximum_slots_reached_error_message'] = 'momentálně nelze uložit tento výběr, aktualizujte stránku';
$config['ca_app_profile_management_gold_membership_subscriber_user_foreign_spoken_languages_maximum_slots_reached_error_message'] = 'momentálně nelze uložit tento výběr, aktualizujte stránku';

?>