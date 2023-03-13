<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//Left navigation Menu name
$config['pa_user_left_nav_certifications'] = 'Certifications';
$config['ca_user_left_nav_certifications'] = 'Certifications Comp';
$config['ca_app_user_left_nav_certifications'] = 'App:Certifications Comp';


/*
|--------------------------------------------------------------------------
| Meta Variables 
|--------------------------------------------------------------------------
| 
*/

################ Meta Config Variables for certifications page ###########
/* Filename: application\modules\dashboard\controllers\User.php */
/* Controller: user Method name: index */

$config['certifications_page_url'] = 'certificates';


$config['pa_certifications_page_title_meta_tag'] = 'pa-{user_first_name_last_name} | Správa certifikátů';
$config['pa_certifications_page_description_meta_tag'] = 'pa-{user_first_name_last_name} | Správa certifikátů';

$config['ca_certifications_page_title_meta_tag'] = 'ca-{user_company_name} | Správa certifikátů';
$config['ca_certifications_page_description_meta_tag'] = 'ca-{user_company_name} | Správa certifikátů';

$config['ca_app_certifications_page_title_meta_tag'] = 'ca-{user_first_name_last_name} | Správa certifikátů(app)';
$config['ca_app_certifications_page_description_meta_tag'] = 'ca-{user_first_name_last_name} | Správa certifikátů(app)';


$config['pa_user_certifications_section_initial_view_title'] = 'pa-Adds New Certifications';
$config['pa_user_certifications_section_initial_view_content'] = '<b>pa-</b>Tato sekce je určená pro zveřejnění vašich certifikátů, rozšiřující vaše vzdělání a odborné zaměření. Zvyšte svou prodejnost zaměřením na svůj profesní rozvoj za pomocí sekce Cerfifikáty. Proto uvádějte seznam všech certifikačních kurzů a dalších stintů ve vzdělávacích institucích, které jsou důležité pro vaši kariéru nebo osobní směr.<br><strong>Mějte na paměti, že na Travai lze hledat pomocí klíčových slov, například názvu certfikátů, a tím můžete být zařazeni ve výsledcích hledání na stránce seznamu odbroníků.</strong>';

$config['ca_user_certifications_section_initial_view_title'] = 'ca-Adds New Certifications';
$config['ca_user_certifications_section_initial_view_content'] = '<b>ca-</b>Tato sekce je určená pro zveřejnění vašich certifikátů, rozšiřující vaše vzdělání a odborné zaměření. Zvyšte svou prodejnost zaměřením na svůj profesní rozvoj za pomocí sekce Cerfifikáty. Proto uvádějte seznam všech certifikačních kurzů a dalších stintů ve vzdělávacích institucích, které jsou důležité pro vaši kariéru nebo osobní směr.<br><strong>Mějte na paměti, že na Travai lze hledat pomocí klíčových slov, například názvu certfikátů, a tím můžete být zařazeni ve výsledcích hledání na stránce seznamu odbroníků.</strong>';

$config['ca_app_user_certifications_section_initial_view_title'] = 'ca-Adds New Certifications(app)';
$config['ca_app_user_certifications_section_initial_view_content'] = '<b>ca-</b>Tato sekce je určená pro zveřejnění vašich certifikátů, rozšiřující vaše vzdělání a odborné zaměření. Zvyšte svou prodejnost zaměřením na svůj profesní rozvoj za pomocí sekce Cerfifikáty. Proto uvádějte seznam všech certifikačních kurzů a dalších stintů ve vzdělávacích institucích, které jsou důležité pro vaši kariéru nebo osobní směr.<br><strong>Mějte na paměti, že na Travai lze hledat pomocí klíčových slov, například názvu certfikátů, a tím můžete být zařazeni ve výsledcích hledání na stránce seznamu odbroníků.</strong>(app)';

//heading title
$config['pa_user_certifications_section_headline_title'] = 'pa-Certifications';
$config['ca_user_certifications_section_headline_title'] = 'ca-Certifications';
$config['ca_app_user_certifications_section_headline_title'] = 'ca-Certifications(app)';

$config['pa_user_certifications_section_add_another_certifications_btn_txt'] ="pa-Add Another certifications";
$config['ca_user_certifications_section_add_another_certifications_btn_txt'] ="ca-Add Another certifications";




$config['pa_user_certifications_section_certification_name_required'] = 'pa- Required field';
$config['ca_user_certifications_section_certification_name_required'] = 'ca- Required field';

$config['pa_user_certifications_section_certification_name'] = 'pa-Certification Name';
$config['ca_user_certifications_section_certification_name'] = 'ca-Certification Name';

$config['pa_user_certifications_section_certification_name_placeholder'] = 'paEN-masérský kurz, CCNA, hypoteční poradce';
$config['ca_user_certifications_section_certification_name_placeholder'] = 'caEN-masérský kurz, CCNA, hypoteční poradce';

//Date Acquired
$config['pa_user_certifications_section_date_acquired'] = 'pa-Date Acquired';
$config['ca_user_certifications_section_date_acquired'] = 'ca-Date Acquired';

$config['pa_user_certifications_section_date_acquired_select_year'] = 'pa - Select Year';
$config['ca_user_certifications_section_date_acquired_select_year'] = 'ca - Select Year';

$config['pa_user_certifications_section_date_acquired_select_month'] = 'pa - Select Month';
$config['ca_user_certifications_section_date_acquired_select_month'] = 'ca - Select Month';

$config['pa_user_certifications_section_date_acquired_select_year_required'] = 'pa-year is required';
$config['ca_user_certifications_section_date_acquired_select_year_required'] = 'ca-year is required';

$config['pa_user_certifications_section_date_acquired_select_month_required'] = 'pa-month is required';
$config['ca_user_certifications_section_date_acquired_select_month_required'] = 'ca-month is required';


$config['pa_user_certifications_section_acquired_on'] = 'pa-acquired on';
$config['ca_user_certifications_section_acquired_on'] = 'ca-acquired on';

$config['user_certifications_section_upload_files_txt'] = 'Upload Files';

$config['user_certifications_section_attachment_allowed_file_types_js'] = '"png","PNG","gif","GIF","jpeg","JPEG","jpg","JPG","pdf","application/PDF","xls","xlsx","doc","docx","txt"'; 

$config['user_certifications_section_attachment_allowed_file_types'] = 'image/*,.pdf,.xls, .xlsx, .doc, .docx, .txt'; 

$config['user_certifications_section_attachment_invalid_file_extension_validation_message'] = "The file type you are trying to upload is not allowed";



$config['user_certifications_section_download_attachment_not_exist_error_message'] = "File you are tring to download does not exist. Please refresh the page";

$config['user_certifications_section_user_uploaded_maximum_attachments_error_message'] = "Your attachment will not be uploaded as you already reach max limit to upload, please refresh the page.";

$config['user_certifications_section_user_upload_blank_attachment_alert_message'] = "you cannot upload blank attachment - certificate";

$config['user_certifications_section_uploaded_attachment_txt'] = 'certificate';


//Config are use for invalid request of update/delete work experience(example user trying to delete same time from another tab another user delete request)
$config['pa_user_edit_certifications_entry_already_deleted'] = "Request is invalid. you cant edit this entry, as it has been already deleted.";

$config['ca_user_edit_certifications_entry_already_deleted'] = "Request is invalid. you cant edit this entry, as it has been already deleted.";


//Config are use for confiramtion popup of delete work experience by user
$config['pa_user_delete_certifications_confirmation_project_modal_body'] = 'Are you sure you want to delete certifications?';

$config['ca_user_delete_certifications_confirmation_project_modal_body'] = 'Are you sure you want to delete certifications?';

?>