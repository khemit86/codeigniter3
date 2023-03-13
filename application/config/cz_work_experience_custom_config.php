<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//Left navigation Menu name

$config['personal_account_user_left_nav_work_experience'] = 'Pracovní zkušenosti';
$config['company_account_app_user_left_nav_work_experience'] = 'Pracovní zkušenosti';
/*
|--------------------------------------------------------------------------
| Meta  Variables 
|--------------------------------------------------------------------------
| 
*/

################  Meta Config Variables for work_experience page ###########
/* Filename: application\modules\dashboard\controllers\Dashboard.php */
/* Controller: user Method name: index */

//$config['work_experience_title_meta_tag'] = '{user_first_name_last_name_or_company_name} |  profile Management Title Meta Tag - will be translated in each language';
//$config['work_experience_description_meta_tag'] = '{user_first_name_last_name_or_company_name} |  profile Management Description Meta Tag - will be translated in each language';

$config['personal_account_work_experience_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Správa pracovních zkušeností';
$config['personal_account_work_experience_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Správa pracovních zkušeností';

$config['company_account_app_work_experience_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Správa pracovních zkušeností';
$config['company_account_app_work_experience_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Správa pracovních zkušeností';


//heading title
$config['personal_account_work_experience_section_headline_title'] = 'Pracovní zkušenosti';
$config['company_account_app_work_experience_section_headline_title'] = 'Pracovní zkušenosti';

//work experience popup Add New Certifications heading title
$config['personal_account_work_experience_section_add_another_work_experience_btn_txt'] ="Přidat další pracovní zkušenost";
$config['company_account_app_work_experience_section_add_another_work_experience_btn_txt'] ="Přidat další pracovní zkušenost";

$config['personal_account_work_experience_section_position_title'] = 'Pracovní zkušenost';
$config['personal_account_work_experience_section_position_title_placeholder'] = 'řidič, HR manažer, bankovní poradce';

$config['company_account_app_work_experience_section_position_title'] = 'Pracovní zkušenost';
$config['company_account_app_work_experience_section_position_title_placeholder'] = 'řidič, HR manažer, bankovní poradce';

$config['personal_account_work_experience_section_position_title_required_validation_message'] = 'pracovní zkušenost je povinná';
$config['company_account_app_work_experience_section_position_title_required_validation_message'] = 'pracovní zkušenost je povinná';

$config['personal_account_work_experience_section_company_name'] = 'Název společnosti';
$config['company_account_app_work_experience_section_company_name'] = 'Název společnosti';

$config['personal_account_work_experience_section_company_name_placeholder'] = 'Název firmy, s.r.o.';
$config['company_account_app_work_experience_section_company_name_placeholder'] = 'Název firmy, s.r.o.';

$config['personal_account_work_experience_section_company_name_required_validation_message'] = 'název společnosti je povinný';
$config['company_account_app_work_experience_section_company_name_required_validation_message'] = 'název společnosti je povinný';

//company address
//$config['personal_account_work_experience_section_company_address'] = 'Company Address';
$config['personal_account_work_experience_section_company_address'] = 'Adresa společnosti';
$config['company_account_app_work_experience_section_company_address'] = 'Adresa společnosti';

//$config['personal_account_work_experience_section_company_address_placeholder'] = 'company Address No.';
$config['personal_account_work_experience_section_company_address_placeholder'] = 'ulice (číslo popisné), město, PSČ';
$config['company_account_app_work_experience_section_company_address_placeholder'] = 'ulice (číslo popisné), město, PSČ';

//$config['personal_account_work_experience_section_company_address_required_validation_message'] = 'Company address is required.';
$config['personal_account_work_experience_section_company_address_required_validation_message'] = 'adresa společnosti je povinná';
$config['company_account_app_work_experience_section_company_address_required_validation_message'] = 'adresa společnosti je povinná';


//$config['personal_account_work_experience_section_company_address_country'] = 'Select Country';
$config['personal_account_work_experience_section_company_address_country'] = 'Země společnosti';
$config['company_account_app_work_experience_section_company_address_country'] = 'Země společnosti';

//$config['personal_account_work_experience_section_company_country_name_required_validation_message'] = 'Country name is required.';
$config['personal_account_work_experience_section_company_country_name_required_validation_message'] = 'výběr země je povinný';
$config['company_account_app_work_experience_section_company_country_name_required_validation_message'] = 'výběr země je povinný';



//From
$config['personal_account_work_experience_section_from'] = 'od';
$config['company_account_app_work_experience_section_from'] = 'od';

//$config['personal_account_work_experience_section_month_from_required_validation_message'] = 'From month required.';
$config['personal_account_work_experience_section_month_from_required_validation_message'] = 'začátek měsíce je povinný';
$config['company_account_app_work_experience_section_month_from_required_validation_message'] = 'začátek měsíce je povinný';

//$config['personal_account_work_experience_section_year_from_required_validation_message'] = 'From year is required.';
$config['personal_account_work_experience_section_year_from_required_validation_message'] = 'začátek roku je povinný';
$config['company_account_app_work_experience_section_year_from_required_validation_message'] = 'začátek roku je povinný';
//To
//$config['personal_account_work_experience_section_to'] = 'To';
$config['personal_account_work_experience_section_to'] = 'do';
$config['company_account_app_work_experience_section_to'] = 'do';

//$config['personal_account_work_experience_section_month_to_required_validation_message'] = 'To month required.';
$config['personal_account_work_experience_section_month_to_required_validation_message'] = 'konec měsíce je povinný';
$config['company_account_app_work_experience_section_month_to_required_validation_message'] = 'konec měsíce je povinný';

//$config['personal_account_work_experience_section_year_to_required_validation_message'] = 'To year is required.';
$config['personal_account_work_experience_section_year_to_required_validation_message'] = 'konec roku je povinný';
$config['company_account_app_work_experience_section_year_to_required_validation_message'] = 'konec roku je povinný';

//error when to month and year greater then from month and year
//$config['personal_account_work_experience_section_to_from_year_month_greater_validation_message'] = 'To date should not greater then end date.';
$config['personal_account_work_experience_section_to_from_year_month_greater_validation_message'] = 'začátek nemůže být větší než datum ukončení';
$config['company_account_app_work_experience_section_to_from_year_month_greater_validation_message'] = 'začátek nemůže být větší než datum ukončení';

//still work
//$config['personal_account_work_experience_section_still_work'] = 'Still work';
$config['personal_account_work_experience_section_still_work'] = 'do součastnosti';
$config['company_account_app_work_experience_section_still_work'] = 'do součastnosti';




//$config['personal_account_work_experience_section_position_description'] = 'Position description';
$config['personal_account_work_experience_section_position_description'] = 'Popis';
$config['company_account_app_work_experience_section_position_description'] = 'Popis';

//common message
//$config['personal_account_work_experience_section_month_select_month'] = 'Select Month';
$config['personal_account_work_experience_section_month_select_month'] = 'vybrat měsíc';
$config['company_account_app_work_experience_section_month_select_month'] = 'vybrat měsíc';

//$config['personal_account_work_experience_section_year_select_year'] = 'Select Year';
$config['personal_account_work_experience_section_year_select_year'] = 'vybrat rok';
$config['company_account_app_work_experience_section_year_select_year'] = 'vybrat rok';

//$config['work_experience_page_url']  = 'work-experience';
$config['work_experience_page_url']  = 'pracovni-zkusenosti';



//Config are use for invalid request of update/delete work experience(example user trying to delete same time from another tab another user delete request)

//$config['personal_account_user_edit_work_experience_entry_already_deleted'] = "Request is invalid. you cant edit this entry, as it has been already deleted."; 
$config['personal_account_user_edit_work_experience_entry_already_deleted'] = "Pracovní zkušenost nelze upravit. Již byla smazána."; 
$config['company_account_app_user_edit_work_experience_entry_already_deleted'] = "Pracovní zkušenost nelze upravit. Již byla smazána."; 
//$config['personal_account_user_delete_work_experience_entry_already_deleted'] = "Request is invalid. you cant delete this entry, as it has been already deleted."; 
//$config['personal_account_user_delete_work_experience_entry_already_deleted'] = "Pracovní zkušenost nelze smazat. Již byla smazána.";


//Config are use for confiramtion popup of delete work experience by user
//$config['personal_account_delete_work_experience_confirmation_project_modal_body'] = 'Are you sure you want to delete work experience?';
$config['personal_account_delete_work_experience_confirmation_project_modal_body'] = 'Opravdu chcete smazat tuto pracovní zkušenost?';
$config['company_account_app_delete_work_experience_confirmation_project_modal_body'] = 'Opravdu chcete smazat tuto pracovní zkušenost?';


$config['personal_account_work_experience_section_initial_view_title']    = 'Vytvořit pracovní zkušenost';
$config['personal_account_work_experience_section_initial_view_content']  = 'Je dobrým důvodem předvést minulé zkušenosti a ukázat kariérní růst napříč každou pozicí. Profily s vyplněnými pracovními zkušenostmi a kariérami jsou zobrazovány vícekrát, než prázdné profily. Shrnutím své profesní cesty na svém Travai profilu, pomůže více vyniknout pro kohokoli, kdo hledá pracovníky jako jste vy. Jakmile uvedete pracovní zkušenost na profilu, tím vyšší je pravděpodobnost zobrazení vašeho profilu ve výsledcích hledání na stránce seznamu odbroníků.';

$config['company_account_app_work_experience_section_initial_view_title']    = 'Vytvořit pracovní zkušenost';
$config['company_account_app_work_experience_section_initial_view_content']  = 'Je dobrým důvodem předvést minulé zkušenosti a ukázat své předešlé partnerství nebo kariérní růst napříč každou pozicí. Profily s vyplněnými pracovními zkušenostmi a kariérami jsou zobrazovány vícekrát, než prázdné profily. Shrnutím své profesní cesty na svém Travai profilu, pomůže více vyniknout pro kohokoli, kdo hledá odborníky jako jste vy. Jakmile uvedete pracovní zkušenost na profilu, tím vyšší je pravděpodobnost zobrazení vašeho profilu ve výsledcích hledání na stránce seznamu odbroníků.';



?>