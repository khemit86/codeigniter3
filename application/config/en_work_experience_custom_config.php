<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//Left navigation Menu name
$config['personal_account_user_left_nav_work_experience'] = 'Work Experience';
$config['company_account_app_user_left_nav_work_experience'] = 'App:Work Experience';

/*
|--------------------------------------------------------------------------
| Meta Variables 
|--------------------------------------------------------------------------
| 
*/

################ Meta Config Variables for work_experience page ###########
/* Filename: application\modules\dashboard\controllers\Dashboard.php */
/* Controller: user Method name: index */
$config['personal_account_work_experience_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | EN Správa pracovních zkušeností';
$config['personal_account_work_experience_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | EN Správa pracovních zkušeností';

$config['company_account_app_work_experience_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | EN Správa pracovních zkušeností[app]';
$config['company_account_app_work_experience_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | EN Správa pracovních zkušeností[app]';


//heading title
$config['personal_account_work_experience_section_headline_title'] = 'Work Experience';
$config['company_account_app_work_experience_section_headline_title'] = 'Work Experience(app)';

//work experience popup Add New Certifications heading title
$config['personal_account_work_experience_section_add_another_work_experience_btn_txt'] ="Add Another Work Experience";
$config['company_account_app_work_experience_section_add_another_work_experience_btn_txt'] ="Add Another Work Experience(app)";


$config['personal_account_work_experience_section_position_title'] = 'Position Title';
$config['personal_account_work_experience_section_position_title_placeholder'] = 'EN - řidič, HR manažer, bankovní poradce';

$config['company_account_app_work_experience_section_position_title'] = 'Position Title(app)';
$config['company_account_app_work_experience_section_position_title_placeholder'] = 'EN - řidič, HR manažer, bankovní poradce(app)';

$config['personal_account_work_experience_section_position_title_required_validation_message'] = 'EN pracovní zkušenost je povinná';
$config['company_account_app_work_experience_section_position_title_required_validation_message'] = 'EN pracovní zkušenost je povinná(app)';


$config['personal_account_work_experience_section_company_name'] = 'Company name';
$config['company_account_app_work_experience_section_company_name'] = 'Company name(app)';

$config['personal_account_work_experience_section_company_name_placeholder'] = 'EN Práce s.r.o.';
$config['company_account_app_work_experience_section_company_name_placeholder'] = 'EN Práce s.r.o.(app)';

$config['personal_account_work_experience_section_company_name_required_validation_message'] = 'Company name is required';
$config['company_account_app_work_experience_section_company_name_required_validation_message'] = 'Company name is required(app)';

$config['personal_account_work_experience_section_company_country_name_required_validation_message'] = 'EN výběr země je povinný';
$config['company_account_app_work_experience_section_company_country_name_required_validation_message'] = 'EN výběr země je povinný(app)';


$config['personal_account_work_experience_section_company_address'] = 'Company Address';
$config['company_account_app_work_experience_section_company_address'] = 'Company Address(app)';

$config['personal_account_work_experience_section_company_address_placeholder'] = 'company Address No.';
$config['company_account_app_work_experience_section_company_address_placeholder'] = 'company Address No.(app)';

$config['personal_account_work_experience_section_company_address_required_validation_message'] = 'EN adresa společnosti je povinná';
$config['company_account_app_work_experience_section_company_address_required_validation_message'] = 'EN adresa společnosti je povinná(app)';

$config['personal_account_work_experience_section_company_address_country'] = 'Country';
$config['company_account_app_work_experience_section_company_address_country'] = 'Country(app)';

//FROM
$config['personal_account_work_experience_section_from'] = 'from';
$config['company_account_app_work_experience_section_from'] = 'from(app)';

$config['personal_account_work_experience_section_month_from_required_validation_message'] = 'EN zařátek měsíce je povinný';
$config['company_account_app_work_experience_section_month_from_required_validation_message'] = 'EN zařátek měsíce je povinný(app)';

$config['personal_account_work_experience_section_year_from_required_validation_message'] = 'EN začátek roku je povinný';
$config['company_account_app_work_experience_section_year_from_required_validation_message'] = 'EN začátek roku je povinný(app)';

//To
$config['personal_account_work_experience_section_to'] = 'to';
$config['company_account_app_work_experience_section_to'] = 'to(app)';

$config['personal_account_work_experience_section_month_to_required_validation_message'] = 'EN konec měsíce je povinný';
$config['company_account_app_work_experience_section_month_to_required_validation_message'] = 'EN konec měsíce je povinný(app)';

$config['personal_account_work_experience_section_year_to_required_validation_message'] = 'EN konec roku je pivinný';
$config['company_account_app_work_experience_section_year_to_required_validation_message'] = 'EN konec roku je pivinný(app)';

//error when to month and year greater then from month and year
$config['personal_account_work_experience_section_to_from_year_month_greater_validation_message'] = 'EN začátek nemůže být větší než datum ukončení';
$config['company_account_app_work_experience_section_to_from_year_month_greater_validation_message'] = 'EN začátek nemůže být větší než datum ukončení(app)';

//still work
$config['personal_account_work_experience_section_still_work'] = 'Still work';
$config['company_account_app_work_experience_section_still_work'] = 'Still work(app)';

$config['personal_account_work_experience_section_position_description'] = 'Position description';
$config['company_account_app_work_experience_section_position_description'] = 'Position description(app)';

//common message
$config['personal_account_work_experience_section_month_select_month'] = 'Select Month';
$config['company_account_app_work_experience_section_month_select_month'] = 'Select Month(app)';

$config['personal_account_work_experience_section_year_select_year'] = 'Select Year';
$config['company_account_app_work_experience_section_year_select_year'] = 'Select Year(app)';

$config['work_experience_page_url'] = 'work-experience';

//Config are use for invalid request of update/delete work experience(example user trying to delete same time from another tab another user delete request)
$config['personal_account_user_edit_work_experience_entry_already_deleted'] = "Request is invalid. you cant edit this entry, as it has been already deleted."; 

$config['company_account_app_user_edit_work_experience_entry_already_deleted'] = "Request is invalid. you cant edit this entry, as it has been already deleted.(app)"; 


//Config are use for confiramtion popup of delete work experience by user
$config['personal_account_delete_work_experience_confirmation_project_modal_body'] = 'Are you sure you want to delete work experience?';

$config['company_account_app_delete_work_experience_confirmation_project_modal_body'] = 'Are you sure you want to delete work experience?(app)';


$config['personal_account_work_experience_section_initial_view_title'] = 'Add New work_experience';
$config['company_account_app_work_experience_section_initial_view_title'] = 'Add New work experience(app)';

$config['personal_account_work_experience_section_initial_view_content'] = 'EN Je dobrým důvodem předvést minulé zkušenosti a ukázat kariérní růst napříč každou pozicí. Profily s vyplněnými pracovními zkušenostmi a kariérami jsou zobrazeny několikrát více než prázdné profily. Shrnutím své profesionální cesty na svém Travai profilu pomůže více vyniknout pro kohokoliv, kdo hledá pracovníky jako jste vy. Jakmile uvedete pracovní zkušenost na profilu, je tak vyšší pravděpodobnost zobrazení vašeho profilu ve výsledcích hledání na stránce seznamu odbroníků.';


$config['company_account_app_work_experience_section_initial_view_content'] = 'EN Je dobrým důvodem předvést minulé zkušenosti a ukázat kariérní růst napříč každou pozicí. Profily s vyplněnými pracovními zkušenostmi a kariérami jsou zobrazeny několikrát více než prázdné profily. Shrnutím své profesionální cesty na svém Travai profilu pomůže více vyniknout pro kohokoliv, kdo hledá pracovníky jako jste vy. Jakmile uvedete pracovní zkušenost na profilu, je tak vyšší pravděpodobnost zobrazení vašeho profilu ve výsledcích hledání na stránce seznamu odbroníků.(app)';

?>