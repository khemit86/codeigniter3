<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//Left navigation Menu name
$config['personal_account_user_left_nav_education_and_training'] = 'Education & Training';
$config['company_account_app_user_left_nav_education_and_training'] = 'Education & Training(app)';

/*
|--------------------------------------------------------------------------
| Meta Variables 
|--------------------------------------------------------------------------
| 
*/

################ Meta Config Variables for education & training page ###########
/* Filename: application\modules\user\controllers\User.php */
/* Controller: user Method name: index */

$config['personal_account_education_training_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Education Training Title Meta Tag';
$config['personal_account_education_training_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Education Training Description Meta Tag';

$config['company_account_app_education_training_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Education Training Title Meta Tag[app]';
$config['company_account_app_education_training_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Education Training Description Meta Tag[app]';


//heading title
$config['personal_account_education_section_headline_title'] = 'Education & Training';
$config['company_account_app_education_section_headline_title'] = 'Education & Training(app)';



//diploma name
$config['personal_account_education_section_diploma_name'] = 'Diploma Degree';
$config['company_account_app_education_section_diploma_name'] = 'Diploma Degree(app)';

$config['personal_account_education_section_diploma_name_placeholder'] = 'EN - vzdělání s výučním listem, maturitní vzdělání, vysokoškolské vzdělání';
$config['company_account_app_education_section_diploma_name_placeholder'] = 'EN - vzdělání s výučním listem, maturitní vzdělání, vysokoškolské vzdělání(app)';

//school name
$config['personal_account_education_section_school_name'] = 'School Name';
$config['company_account_app_education_section_school_name'] = 'School Name(app)';

$config['personal_account_education_section_school_name_placeholder'] = 'EN - odborné učiliště a střední škola, vyšší odborná škola';
$config['company_account_app_education_section_school_name_placeholder'] = 'EN - odborné učiliště a střední škola, vyšší odborná škola(app)';

//school address
$config['personal_account_education_section_school_address'] = 'School Address';
$config['company_account_app_education_section_school_address'] = 'School Address(app)';

$config['personal_account_education_section_school_address_placeholder'] = 'Street Address, Street No., Locality Name';
$config['company_account_app_education_section_school_address_placeholder'] = 'Street Address, Street No., Locality Name(app)';

$config['personal_account_education_section_school_address_country'] = 'Select Country';
$config['company_account_app_education_section_school_address_country'] = 'Select Country(app)';


//graduate In
$config['personal_account_education_section_graduated_in'] = 'Graduated In';
$config['company_account_app_education_section_graduated_in'] = 'Graduated In(app)';

$config['personal_account_education_section_graduated_in_select_year'] = 'Select Year';
$config['company_account_app_education_section_graduated_in_select_year'] = 'Select Year(app)';

$config['personal_account_education_section_graduated_in_progress'] = 'In progress';
$config['company_account_app_education_section_graduated_in_progress'] = 'In progress(app)';


//Comments
$config['personal_account_education_section_diploma_name_required_validation_message'] = 'diploma name is required';
$config['personal_account_education_section_school_name_required_validation_message'] = 'school name is required';
$config['personal_account_education_section_school_address_required_validation_message'] = 'school address is required';
$config['personal_account_education_section_school_country_required_validation_message'] = 'school Country is required';
$config['personal_account_education_section_graduate_year_required_validation_message'] = 'graduate year is required';

$config['company_account_app_education_section_diploma_name_required_validation_message'] = 'diploma name is required(app)';
$config['company_account_app_education_section_school_name_required_validation_message'] = 'school name is required(app)';
$config['company_account_app_education_section_school_address_required_validation_message'] = 'school address is required(app)';
$config['company_account_app_education_section_school_country_required_validation_message'] = 'school Country is required(app)';
$config['company_account_app_education_section_graduate_year_required_validation_message'] = 'graduate year is required(app)';


$config['personal_account_education_section_comments'] = 'Comments';
$config['company_account_app_education_section_comments'] = 'Comments(app)';


$config['education_training_page_url'] = 'education-training';


//Config are use for confiramtion popup of delete education by user
$config['personal_account_delete_education_training_confirmation_project_modal_body'] = 'Are you sure you want to delete education?';
$config['company_account_app_delete_education_training_confirmation_project_modal_body'] = 'Are you sure you want to delete education?(app)';


//Config are use for invalid request of update/delete education(example user trying to delete same time from another tab another user delete request)
$config['personal_account_user_edit_education_training_entry_already_deleted'] = "Request is invalid. you cant edit this entry, as it has been already deleted.";
$config['company_account_app_user_edit_education_training_entry_already_deleted'] = "Request is invalid. you cant edit this entry, as it has been already deleted.(app)";


// Config for edit/update education popup heading
$config['personal_account_education_training_section_add_another_education_btn_txt'] ="Add Another Education";
$config['company_account_app_education_training_section_add_another_education_btn_txt'] ="Add Another Education(app)";

//$config['personal_account_education_training_section_initial_view_title'] = 'Addd New Education';
$config['personal_account_education_training_section_initial_view_title'] = 'Add New Education';
$config['personal_account_education_training_section_initial_view_content'] = 'EN - Získané vzdělání o vás napoví. Je mnoho potenciálních zaměstnavatů, kteří hledají lidi s vaším vzděláním a možná jsou bývalí spolužáci, kteří se s vámi chtějí znovu spojit. Můžete zahrnout vše od mateřské školy až po poslední získané vzdělání. Je to na vás, ale mějte na paměti, že čím více informací uvedete, tím vyšší je pravděpodobnost být vyhledáváni na seznamu odborníků.';

$config['company_account_app_education_training_section_initial_view_title'] = 'Add New Education(app)';
$config['company_account_app_education_training_section_initial_view_content'] = 'EN - Získané vzdělání o vás napoví. Je mnoho potenciálních zaměstnavatů, kteří hledají lidi s vaším vzděláním a možná jsou bývalí spolužáci, kteří se s vámi chtějí znovu spojit. Můžete zahrnout vše od mateřské školy až po poslední získané vzdělání. Je to na vás, ale mějte na paměti, že čím více informací uvedete, tím vyšší je pravděpodobnost být vyhledáváni na seznamu odborníků.(app)';

?>