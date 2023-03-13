<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//Left navigation Menu name
$config['personal_account_user_left_nav_education_and_training'] = 'Vzdělání';
$config['company_account_app_user_left_nav_education_and_training'] = 'Vzdělání';

/*
|--------------------------------------------------------------------------
| Meta Variables
|--------------------------------------------------------------------------
|
*/

################ Meta Config Variables for education & training page ###########
/* Filename: application\modules\user\controllers\User.php */
/* Controller: user Method name: index */
$config['personal_account_education_training_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Správa vzdělání';
$config['personal_account_education_training_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Správa vzdělání';

$config['company_account_app_education_training_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Správa vzdělání';
$config['company_account_app_education_training_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Správa vzdělání';


//heading title
$config['personal_account_education_section_headline_title'] = 'Vzdělání';
$config['company_account_app_education_section_headline_title'] = 'Vzdělání';

//diploma name
$config['personal_account_education_section_diploma_name'] = 'Studium';
$config['company_account_app_education_section_diploma_name'] = 'Studium';

$config['personal_account_education_section_diploma_name_placeholder'] = 'vzdělání s výučním listem, maturitní vzdělání, vysokoškolské vzdělání';
$config['company_account_app_education_section_diploma_name_placeholder'] = 'vzdělání s výučním listem, maturitní vzdělání, vysokoškolské vzdělání';

//school name
$config['personal_account_education_section_school_name'] = 'Název instituce';
$config['company_account_app_education_section_school_name'] = 'Název instituce';

$config['personal_account_education_section_school_name_placeholder'] = 'odborné učiliště a střední škola, vyšší odborná škola';
$config['company_account_app_education_section_school_name_placeholder'] = 'odborné učiliště a střední škola, vyšší odborná škola';

//school address
$config['personal_account_education_section_school_address'] = 'Adresa instituce';
$config['company_account_app_education_section_school_address'] = 'Adresa instituce';

$config['personal_account_education_section_school_address_placeholder'] = 'ulice (číslo popisné), město, PSČ';
$config['company_account_app_education_section_school_address_placeholder'] = 'ulice (číslo popisné), město, PSČ';

$config['personal_account_education_section_school_address_country'] = 'Země instituce';
$config['company_account_app_education_section_school_address_country'] = 'Země instituce';

//graduate In
$config['personal_account_education_section_graduated_in'] = 'Získané';
$config['company_account_app_education_section_graduated_in'] = 'Získané';

$config['personal_account_education_section_graduated_in_select_year'] = 'vybrat rok';
$config['company_account_app_education_section_graduated_in_select_year'] = 'vybrat rok';

$config['personal_account_education_section_graduated_in_progress'] = 'Probíhá';
$config['company_account_app_education_section_graduated_in_progress'] = 'Probíhá';

//Comments
$config['personal_account_education_section_diploma_name_required_validation_message'] = 'název vzdělání je povinné';
$config['personal_account_education_section_school_name_required_validation_message'] = 'název instituce je povinné';
$config['personal_account_education_section_school_address_required_validation_message'] = 'adresa instituce je povinná';
$config['personal_account_education_section_school_country_required_validation_message'] = 'země instituce je povinná';
$config['personal_account_education_section_graduate_year_required_validation_message'] = 'datum absolvování je povinné';

$config['company_account_app_education_section_diploma_name_required_validation_message'] = 'název vzdělání je povinné';
$config['company_account_app_education_section_school_name_required_validation_message'] = 'název instituce je povinné';
$config['company_account_app_education_section_school_address_required_validation_message'] = 'adresa instituce je povinná';
$config['company_account_app_education_section_school_country_required_validation_message'] = 'země instituce je povinná';
$config['company_account_app_education_section_graduate_year_required_validation_message'] = 'datum absolvování je povinné';

$config['personal_account_education_section_comments'] = 'Popis';
$config['company_account_app_education_section_comments'] = 'Popis';

$config['education_training_page_url'] = 'vzdelani';



//Config are use for confirmation popup of delete education by user
$config['personal_account_delete_education_training_confirmation_project_modal_body'] = 'Opravdu chcete smazat vzdělání?';
$config['company_account_app_delete_education_training_confirmation_project_modal_body'] = 'Opravdu chcete smazat vzdělání?';


//Config are use for invalid request of update/delete education(example user trying to delete same time from another tab another user delete request)
$config['personal_account_user_edit_education_training_entry_already_deleted'] = "Vzdělání nelze upravit. Již bylo smazáno.";
$config['company_account_app_user_edit_education_training_entry_already_deleted'] = "Vzdělání nelze upravit. Již bylo smazáno.";

// Config for edit/update education popup heading
$config['personal_account_education_training_section_add_another_education_btn_txt'] ="Přidat další vzdělání";
$config['company_account_app_education_training_section_add_another_education_btn_txt'] ="Přidat další vzdělání";

$config['personal_account_education_training_section_initial_view_title'] = 'Přidat vzdělání';
$config['personal_account_education_training_section_initial_view_content'] = 'Získané vzdělání o vás napoví. Je mnoho potenciálních zaměstnavatů, kteří hledají lidi s vaším vzděláním a možná jsou bývalí spolužáci, kteří se s vámi chtějí znovu spojit. Můžete zahrnout vše od mateřské školy až po poslední získané vzdělání. Je to na vás, ale mějte na paměti, že čím více informací uvedete, tím vyšší je pravděpodobnost být nalazeni při hledání v seznamu odborníků.';

$config['company_account_app_education_training_section_initial_view_title'] = 'Přidat vzdělání';
$config['company_account_app_education_training_section_initial_view_content'] = 'Získané vzdělání o vás napoví. Je mnoho potenciálních partnerů a zaměstnavatů, kteří hledají odborníky s vaším vzděláním a možná jsou bývalí spolužáci, kteří se s vámi chtějí znovu spojit. Můžete zahrnout vše od mateřské školy až po poslední získané vzdělání. Je to na vás, ale mějte na paměti, že čím více informací uvedete, tím vyšší je pravděpodobnost být nalazeni při hledání v seznamu odborníků.';

?>