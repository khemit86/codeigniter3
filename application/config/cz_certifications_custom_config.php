<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//Left navigation Menu name
$config['pa_user_left_nav_certifications'] = 'Certifikáty';
$config['ca_user_left_nav_certifications'] = 'Certifikáty';
$config['ca_app_user_left_nav_certifications'] = 'Certifikáty';

/*
|--------------------------------------------------------------------------
| Meta Variables
|--------------------------------------------------------------------------
|
*/

################ Meta Config Variables for certifications page ###########
/* Filename: application\modules\dashboard\controllers\User.php */
/* Controller: user Method name: index */

$config['pa_certifications_page_title_meta_tag'] = '{user_first_name_last_name} | Správa certifikátů';
$config['pa_certifications_page_description_meta_tag'] = '{user_first_name_last_name} | Správa certifikátů';

$config['ca_certifications_page_title_meta_tag'] = '{user_company_name} | Správa certifikátů';
$config['ca_certifications_page_description_meta_tag'] = '{user_company_name} | Správa certifikátů';

$config['ca_app_certifications_page_title_meta_tag'] = '{user_first_name_last_name} | Správa certifikátů';
$config['ca_app_certifications_page_description_meta_tag'] = '{user_first_name_last_name} | Správa certifikátů';

$config['pa_user_certifications_section_initial_view_title'] = 'Přidat certifikát';
$config['pa_user_certifications_section_initial_view_content'] = 'Zveřejněte získané certifikáty, rozšiřující vaše vzdělání, odborné zaměření a zvyšte svoji prodejnost zaměřením na svůj profesní rozvoj. Uvádějte seznam všech certifikačních kurzů a dalších stintů ve vzdělávacích institucích, které jsou důležité pro vaši kariéru nebo osobní směr.<br><br><strong>Mějte na paměti, že na Travai lze hledat pomocí klíčových slov, například názvu certfikátů, a tím můžete být zařazeni ve výsledcích hledání na stránce seznamu odbroníků.</strong>';

$config['ca_user_certifications_section_initial_view_title'] = 'Přidat certifikát';
$config['ca_user_certifications_section_initial_view_content'] = 'Zveřejněte získané certifikáty, rozšiřující vaše nabízené služby, odborné zaměření a zvyšte prodejnost vaší společnosti, zaměřením se na odbornost. Uvádějte seznam všech certifikačních kurzů a dalších stintů ve vzdělávacích institucích, které jsou důležité pro vaši společnost a směr nabízených služeb.<br><br><strong>Mějte na paměti, že na Travai lze hledat pomocí klíčových slov, například názvu certfikátů, a tím můžete být zařazeni ve výsledcích hledání na stránce seznamu odbroníků.</strong>';

$config['ca_app_user_certifications_section_initial_view_title'] = 'Přidat certifikát';
$config['ca_app_user_certifications_section_initial_view_content'] = 'Zveřejněte získané certifikáty, rozšiřující vaše nabízené služby, odborné zaměření a zvyšte prodejnost, zaměřením se na odbornost. Uvádějte seznam všech certifikačních kurzů a dalších stintů ve vzdělávacích institucích, které jsou důležité pro vaše podnikání a směr nabízených služeb.<br><br><strong>Mějte na paměti, že na Travai lze hledat pomocí klíčových slov, například názvu certfikátů, a tím můžete být zařazeni ve výsledcích hledání na stránce seznamu odbroníků.</strong>';

//heading title
$config['pa_user_certifications_section_headline_title'] = 'Certifikáty';
$config['ca_user_certifications_section_headline_title'] = 'Certifikáty';
$config['ca_app_user_certifications_section_headline_title'] = 'Certifikáty';

$config['pa_user_certifications_section_add_another_certifications_btn_txt'] ="Přidat další certifikát";
$config['ca_user_certifications_section_add_another_certifications_btn_txt'] ="Přidat další certifikát";

//Certification name
$config['pa_user_certifications_section_certification_name'] = 'Název certifikátu';
$config['ca_user_certifications_section_certification_name'] = 'Název certifikátu';

$config['pa_user_certifications_section_certification_name_placeholder'] = 'masérský kurz, CCNA, hypoteční poradce';
$config['ca_user_certifications_section_certification_name_placeholder'] = 'ISO certifikát, CCNA, SCC certifikát';

$config['pa_user_certifications_section_certification_name_required'] = 'název certifikátu je povinný';
$config['ca_user_certifications_section_certification_name_required'] = 'název certifikátu je povinný';



//Date Acquired
$config['pa_user_certifications_section_date_acquired'] = 'Získaný';
$config['ca_user_certifications_section_date_acquired'] = 'Získaný';

$config['pa_user_certifications_section_date_acquired_select_month'] = 'vybrat měsíc';
$config['ca_user_certifications_section_date_acquired_select_month'] = 'vybrat měsíc';


$config['pa_user_certifications_section_date_acquired_select_year'] = 'vybrat rok';
$config['ca_user_certifications_section_date_acquired_select_year'] = 'vybrat rok';

$config['pa_user_certifications_section_date_acquired_select_month_required'] = 'měsíc je povinný';
$config['ca_user_certifications_section_date_acquired_select_month_required'] = 'měsíc je povinný';

$config['pa_user_certifications_section_date_acquired_select_year_required'] = 'rok je povinný';
$config['ca_user_certifications_section_date_acquired_select_year_required'] = 'rok je povinný';


$config['pa_user_certifications_section_acquired_on'] = 'získaný';
$config['ca_user_certifications_section_acquired_on'] = 'získaný';

$config['user_certifications_section_upload_files_txt'] = 'Nahrát certifikát';

$config['user_certifications_section_attachment_allowed_file_types_js'] = '"image/*","png","PNG","jpeg","JPEG","jpg","JPG","pdf","application/PDF","xls","xlsx","doc","docx","txt"';

$config['user_certifications_section_attachment_allowed_file_types'] = 'image/*,.pdf,.xls, .xlsx, .doc, .docx,.txt*/';

$config['user_certifications_section_attachment_invalid_file_extension_validation_message'] = "typ souboru není podporován, certifikát nelze nahrát";



$config['user_certifications_section_download_attachment_not_exist_error_message'] = "Soubor, který chcete otevřít nebo stáhnout, neexistuje.";

$config['user_certifications_section_user_uploaded_maximum_attachments_error_message'] = "Certifikát nelze uložit, zavřete okno. Stránka bude aktualizována.";

$config['user_certifications_section_user_upload_blank_attachment_alert_message'] = "Nelze nahrát prázdný soubor.";

$config['user_certifications_section_uploaded_attachment_txt'] = 'certifikát';

$config['certifications_page_url'] = 'certifikaty';


//Config are use for invalid request of update/delete work experience(example user trying to delete same time from another tab another user delete request)
$config['pa_user_edit_certifications_entry_already_deleted'] = "Certifikát nelze upravit. Již byl smazán.";
$config['ca_user_edit_certifications_entry_already_deleted'] = "Certifikát nelze upravit. Již byl smazán.";

//Config are use for confiramtion popup of delete work experience by user
$config['pa_user_delete_certifications_confirmation_project_modal_body'] = 'Opravdu chcete smazat certifikát?';
$config['ca_user_delete_certifications_confirmation_project_modal_body'] = 'Opravdu chcete smazat certifikát?';

?>
