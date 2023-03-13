<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

################ Meta Config Variables for signup page ###########
/* Filename: application\modules\user\controllers\signup.php */
/* Controller: signup Method name: user_profile */
$config['user_profile_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Travai.cz';

$config['user_profile_page_default_description_meta_tag'] = '{user_first_name_last_name_or_company_name} je na Travai.cz'; // This config is using when user is not fill his description on user profile page as a meta tag description

################ Defined the validation message for user profile page
/* Filename: application\modules\user\controllers\User.php */
/* Controller: User Method name: user_profile */
$config['user_profile_upload_cover_picture_not_allowed'] = 'Nelze nahrát titulní obrázek.';

################ Defined the user profile cover picture validation regarding on user profile page(gold plan only)
/* Filename: application\modules\projects\views\open_for_bidding_project_detail.php */
$config['invalid_user_profile_cover_picture_validation_message'] = "Nahrajte titulní obrázek znovu.";

$config['user_profile_cover_picture_extension_validation_message'] = "Typ obrázku pro nahrátí není podporován!";

$config['user_profile_cover_picture_size_validation_message'] = "Minimální velikost obrázku musí být {max_width}X{max_height}";


// This is the text showing on badge in front of mannual complete project on "sp projects won" tab 
$config['user_profile_page_project_status_marked_as_complete_sp_projects_won_tab'] = 'Označeno dokončený';

$config['user_profile_page_fulltime_project_status_hired_sp_projects_won_tab'] = 'Zaměstnán';

$config['user_profile_page_statistics_as_service_provider'] = 'Poskytovatel';

$config['user_profile_page_statistics_as_project_owner'] = 'Zadavatel';


//SP view - perspective - pa_user_profile_page_sp_statistics_
$config['user_profile_page_sp_statistics_hourly_rate'] = "Hodinová sazba";

// number of won projects of sp (fixed/hourly)
$config['user_profile_page_sp_statistics_total_won_projects'] = "Vysoutěžené projekty";

// number of in progress projects of sp (fixed/hourly) 
$config['user_profile_page_sp_statistics_projects_in_progress'] = "Probíhající práce";

// number of completed projects of sp (fixed/hourly via portal/ out side portal) 
$config['user_profile_page_sp_statistics_total_completed_projects'] = "Dokončené projekty";

// number of completed projects via portal of sp (fixed/hourly) 
$config['user_profile_page_sp_statistics_projects_completed_via_portal'] = "Dokončené projekty přes Travai";

// number of fulltime projects on which sp hired
$config['user_profile_page_sp_statistics_hires_on_fulltime_projects_via_portal'] = "Zaměstnání přes Travai";

//PO perspective - pa_user_profile_page_po_statistics_
// number of total created projects of po
$config['user_profile_page_po_statistics_total_published_listings'] = "Vytvořené inzeráty";

// number of published projects of po (fixed/hourly)
$config['user_profile_page_po_statistics_total_published_projects'] = "Vytvořené projekty";

// number of published fulltime projects of po (fulltime)
$config['user_profile_page_po_statistics_total_published_fulltime_projects'] = "Vytvořené pracovny pozice";

// number of hired sp by po for fulltime projects
$config['user_profile_page_po_statistics_hires_on_fulltime_projects_via_portal'] = "Zaměstnanci přes Travai";

// number of in progress projects of po (fixed/hourly)
$config['user_profile_page_po_statistics_projects_in_progress'] = "Probíhající inzeráty";

// number of completed projects of po (fixed/hourly outside portal/via portal)
$config['user_profile_page_po_statistics_total_completed_projects'] = "Dokončené inzeráty";

// number of completed projects of po (via portal)
$config['user_profile_page_po_statistics_projects_completed_via_portal'] = "Dokončené přes Travai";

$config['user_profile_page_user_contact_details'] = 'Kontakty'; // This config is using to show heading on user profile page for contact details
$config['user_profile_page_user_member_since'] = "Uživatelem od";
$config['user_profile_page_user_last_login'] = "Poslední přihlášení";

$config['user_profile_page_user_profile_viewed'] = "Počet návštěv";

//to be commented for CZ lang - not to be deleted
//$config['user_profile_page_user_profile_viewed_time'] = "time";
//$config['user_profile_page_user_profile_viewed_times'] = "times";

$config['user_profile_page_user_number_of_followers'] = "Sledující";

$config['user_profile_page_user_number_of_contacts'] = "Kontakty";

// ca means company account
$config['ca_user_profile_page_informations'] = 'Status';

$config['ca_user_profile_page_description'] = 'Popis';

$config['ca_user_profile_page_our_vision_label_txt'] = 'Vize';

$config['ca_user_profile_page_our_mission_label_txt'] = 'Mise';

$config['ca_user_profile_page_core_values_label_txt'] = 'Základní hodnoty';

$config['ca_user_profile_page_strategy_goals_label_txt'] = 'Obchodní strategie';

$config['ca_user_profile_page_areas_of_expertise'] = 'Odborné činnosti';

$config['ca_user_profile_page_skills_txt'] = 'Dovednosti';

$config['ca_user_profile_page_services_provided_txt'] = 'Nabízené služby';
$config['ca_user_profile_page_certifications'] = 'Certifikáty';

$config['ca_user_profile_page_company_profile_heading'] = "Provozní informace";

$config['ca_user_profile_page_company_founded_label_txt'] = "Založení společnosti";

$config['ca_user_profile_page_company_size_label_txt'] = "Velikost společnosti";

//$config['ca_user_profile_page_company_employees_label_txt'] = "zaměstnanců";

$config['ca_user_profile_page_company_open_hours_status_closed_label_txt'] = "zavřeno";

$config['ca_user_profile_page_company_opened_now_label_txt'] = "aktuálně otevřeno";

$config['ca_user_profile_page_company_closed_now_label_txt'] = "aktuálně zavřeno";

$config['ca_user_profile_page_company_open_hours_status_always_opened_label_txt'] = "stále otevřeno";

$config['ca_user_profile_page_company_open_hours_status_permanently_closed_label_txt'] = "stále zavřeno";

$config['ca_user_profile_page_company_open_hours_status_by_telephone_appointment_label_txt'] = "po telefonické dohodě";

$config['ca_user_profile_page_user_company_location_show_more_opening_hours_text'] = 'ukázat otevírací dobu <small>( + )</small>';

$config['ca_user_profile_page_user_company_location_hide_extra_opening_hours_text'] = 'skrýt otevírací dobu <small>( - )</small>';

$config['ca_user_profile_page_user_company_location_company_headquarter_heading'] = 'Provozovna'; // location indicated only headquarter - no info about hrs

$config['ca_user_profile_page_user_company_location_company_headquarter_opening_hours_heading'] = 'Provozovna a otevírací doba'; // location indicated only headquarter - info about hrs

$config['ca_user_profile_page_user_company_location_company_locations_heading'] = 'Provozovny'; // location headquarter + 1 or more additional locations - no info about hrs

$config['ca_user_profile_page_user_company_location_company_locations_opening_hours_heading'] = 'Provozovny a otevírací doby'; // location headquarter + 1 or more additional locations - info about hrs (on any of the headquarter or locations)


################# For app account start //

$config['ca_app_user_profile_page_informations'] = 'Status';
$config['ca_app_user_profile_page_description'] = 'Popis';
$config['ca_app_user_profile_page_areas_of_expertise'] = 'Odborné činnosti';
$config['ca_app_user_profile_page_company_profile_heading'] = "Provozní informace";
$config['ca_app_user_profile_page_company_founded_label_txt'] = "Založení živnosti";
$config['ca_app_user_profile_page_company_size_label_txt'] = "Počet zaměstnanců";


$config['ca_app_user_profile_page_skills_txt'] = 'Dovednosti';
$config['ca_app_user_profile_page_services_provided_txt'] = 'Nabízené služby';

$config['ca_app_user_profile_page_work_experience'] = 'Pracovní zkušenosti';
$config['ca_app_user_profile_page_education_training'] = 'Vzdělání a dovednosti';
$config['ca_app_user_profile_page_spoken_languages'] = 'Jazykové znalosti';

$config['ca_app_user_profile_page_certifications'] = 'Certifikáty';
$config['ca_app_user_profile_page_mother_tongue'] = 'Rodilý jazyk';


$config['ca_app_user_profile_page_our_vision_label_txt'] = 'Vize';
$config['ca_app_user_profile_page_our_mission_label_txt'] = 'Mise';
$config['ca_app_user_profile_page_core_values_label_txt'] = 'Základní hodnoty';
$config['ca_app_user_profile_page_strategy_goals_label_txt'] = 'Obchodní strategie';



$config['ca_app_user_profile_page_user_company_location_company_headquarter_heading'] = 'Provozovna'; // location indicated only headquarter - no info about hrs
$config['ca_app_user_profile_page_user_company_location_company_headquarter_opening_hours_heading'] = 'Provozovna a otevírací doba'; // location indicated only headquarter - info about hrs
$config['ca_app_user_profile_page_user_company_location_company_locations_heading'] = 'Provozovny'; // location headquarter + 1 or more additional locations - no info about hrs
$config['ca_app_user_profile_page_user_company_location_company_locations_opening_hours_heading'] = 'Provozovny a otevírací doby'; // location headquarter + 1 or more additional locations - info about hrs (on any of the headquarter or locations)



// this indicate the spoken language levels description
$config['ca_app_user_profile_page_spoken_languages_levels_description_msg'] = 'A1/A2 (úplný začátečník/začátečník); B1/B2 (mírně pokročilý/středně pokročilý); C1/C2 (pokročilý/expert)';
// singular text when user have only 1 spoken language
$config['ca_app_user_profile_page_spoken_languages_txt_singular'] = 'Cizí jazyk';
// plural text when user have only multiple spoken language
$config['ca_app_user_profile_page_spoken_languages_txt_plural'] = 'Cizí jazyky';
//############################## For app account end //


// PA means Personal Account
$config['pa_user_profile_page_description'] = 'Popis';

$config['pa_user_profile_page_skills'] = 'Dovednosti';

$config['pa_user_profile_page_services_provided'] = 'Nabízené služby';

$config['pa_user_profile_page_areas_of_expertise'] = 'Odborné činnosti';

$config['pa_user_profile_page_work_experience'] = 'Pracovní zkušenosti';

$config['pa_user_profile_page_education_training'] = 'Vzdělání a dovednosti';

$config['pa_user_profile_page_spoken_languages'] = 'Jazykové znalosti';

$config['pa_user_profile_page_certifications'] = 'Certifikáty';

$config['pa_user_profile_page_mother_tongue'] = 'Rodilý jazyk';

$config['pa_user_profile_page_user_address'] = "Adresa";

$config['pa_user_profile_page_informations'] = 'Status';

$config['user_profile_page_information_tab'] = 'Informace';

$config['user_profile_page_portfolio_tab'] = 'Portfolio';

$config['user_profile_page_reviews_tab'] = 'Hodnocení';

$config['user_profile_page_projects_created_tab'] = 'Inzeráty';

$config['user_profile_page_projects_won_tab'] = 'Práce';

// config are using on url when user click on profile page tabs 
$config['user_profile_page_portfolio_tab_url_txt'] = 'cz_portfolio';
$config['user_profile_page_reviews_tab_url_txt'] = 'cz_reviews';
$config['user_profile_page_projects_created_tab_url_txt'] = 'cz_posted_projects';
$config['user_profile_page_projects_won_tab_url_txt'] = 'cz_won_projects';



// config are using under reviews tab on user profile page
$config['user_profile_page_reviews_tab_service_provider_txt'] = 'Poskytovatel';

$config['user_profile_page_reviews_tab_project_owner_txt'] = 'Zadavatel';


// this indicate the spoken language levels description
$config['pa_user_profile_page_spoken_languages_levels_description_msg'] = 'A1/A2 (úplný začátečník/začátečník); B1/B2 (mírně pokročilý/středně pokročilý); C1/C2 (pokročilý/expert)';

// singular text when user have only 1 spoken language
$config['pa_user_profile_page_spoken_languages_txt_singular'] = 'Cizí jazyk';

// plural text when user have only multiple spoken language
$config['pa_user_profile_page_spoken_languages_txt_plural'] = 'Cizí jazyky';


//No data message for information tab on profile page when there is no data based on account type/gender
$config['user_profile_page_information_tab_no_data_same_user_view'] = 'nejsou vyplněné žádné informace';

$config['user_profile_page_information_tab_no_data_male_visitor_view'] = '{user_first_name_last_name} neuvedl žádné informace';

$config['user_profile_page_information_tab_no_data_female_visitor_view'] = '{user_first_name_last_name} neuvedla žádné informace';

$config['user_profile_page_information_tab_no_data_company_app_male_visitor_view'] = '{user_first_name_last_name} neuvedl žádné informace';

$config['user_profile_page_information_tab_no_data_company_app_female_visitor_view'] = '{user_first_name_last_name} neuvedla žádné informace';

$config['user_profile_page_information_tab_no_data_company_visitor_view'] = '{user_company_name} neuvedli žádné informace';


// Config for portfolio tab on user profile page when there is no data
$config['user_profile_page_portfolio_tab_no_data_same_user_view'] = 'není vytvořené žádné portfolio';

$config['user_profile_page_portfolio_tab_no_data_male_visitor_view'] = '{user_first_name_last_name} neuvedl žádné portfolio';

$config['user_profile_page_portfolio_tab_no_data_female_visitor_view'] = '{user_first_name_last_name} neuvedla žádné portfolio';

$config['user_profile_page_portfolio_tab_no_data_company_app_male_visitor_view'] = '{user_first_name_last_name} neuvedl žádné portfolio';

$config['user_profile_page_portfolio_tab_no_data_company_app_female_visitor_view'] = '{user_first_name_last_name} neuvedla žádné portfolio';

$config['user_profile_page_portfolio_tab_no_data_company_visitor_view'] = '{user_company_name} neuvedli žádné portfolio';


// Config for project created tab on user profile page when there is no data
$config['user_profile_page_projects_created_tab_no_data_po_view'] = 'nejsou vytvořené žádné inzeráty';

$config['user_profile_page_projects_created_tab_no_data_male_visitor_view'] = '{user_first_name_last_name} nevytvořil žádné inzeráty';
$config['user_profile_page_projects_created_tab_no_data_female_visitor_view'] = '{user_first_name_last_name} nevytvořila žádné inzeráty';

$config['user_profile_page_projects_created_tab_no_data_company_app_male_visitor_view'] = '{user_first_name_last_name} nevytvořil žádné inzeráty';

$config['user_profile_page_projects_created_tab_no_data_company_app_female_visitor_view'] = '{user_first_name_last_name} nevytvořila žádné inzeráty';

$config['user_profile_page_projects_created_tab_no_data_company_visitor_view'] = '{user_company_name} nevytvořili žádné inzeráty';


// Config for project won tab on user profile page when there is no data
$config['user_profile_page_projects_won_tab_no_data_sp_view'] = 'žádný projekt ani pracovní pozice';

$config['user_profile_page_projects_won_tab_no_data_male_visitor_view'] = '{user_first_name_last_name} nepracoval na žádném projektu ani pracovní pozici';

$config['user_profile_page_projects_won_tab_no_data_female_visitor_view'] = '{user_first_name_last_name} nepracovala na žádném projektu ani pracovní pozici';

$config['user_profile_page_projects_won_tab_no_data_company_app_male_visitor_view'] = '{user_first_name_last_name} nepracoval na žádném projektu ani pracovní pozici';

$config['user_profile_page_projects_won_tab_no_data_company_app_female_visitor_view'] = '{user_first_name_last_name} nepracovala na žádném projektu ani pracovní pozici';

$config['user_profile_page_projects_won_tab_no_data_company_visitor_view'] = '{user_company_name} nepracovali na žádném projektu ani pracovní pozici';


// button text for cover picture of portfolio standalone page
$config['user_profile_page_upload_cover_picture_btn_txt'] = 'nahrát obrázek';

$config['user_profile_page_upload_new_cover_picture_btn_txt'] = 'změnit obrázek';


// config is using to show more/ show less text for area of experise section on user profile page
$config['user_profile_page_show_more_area_of_expertise_text'] = 'ukázat více (+{remaining_category} odborné činnosti)';
$config['user_profile_page_show_less_area_of_expertise_text'] = 'skrýt';


// config is using to show more/ show less text for services provided on user profile page
$config['user_profile_page_show_more_services_provided_text'] = 'ukázat více (+{remaining_services_provided} nabízené služby)';
$config['user_profile_page_show_less_services_provided_text'] = 'skrýt';

// config is using to show more/ show less text for skills on user profile page
$config['user_profile_page_show_more_skills_text'] = 'ukázat více (+{remaining_skills} dovednosti)';
$config['user_profile_page_show_less_skills_text'] = 'skrýt';


// config is using to show more/ show less text for Work Experience on user profile page
$config['user_profile_page_show_more_work_experience_text'] = 'ukázat více (+{remaining_work_experience} pracovní zkušenosti)';
$config['user_profile_page_show_less_work_experience_text'] = 'skrýt';


// config is using to show more/ show less text for Education Training on user profile page
$config['user_profile_page_show_more_education_training_text'] = 'ukázat více (+{remaining_education_training} vzdělání a dovednosti)';
$config['user_profile_page_show_less_education_training_text'] = 'skrýt';


// config is using to show more/ show less text for spoken languages on user profile page
$config['user_profile_page_show_more_spoken_languages_text'] = 'ukázat více (+{remaining_spoken_languages} jazykové znalosti)';
$config['user_profile_page_show_less_spoken_languages_text'] = 'skrýt';

// config is using to show more/ show less text for certifications on user profile page
$config['user_profile_page_show_more_certifications_text'] = 'ukázat více (+{certifications} certifikáty)';
$config['user_profile_page_show_less_certifications_text'] = 'skrýt';

// config is using to show more/ show less text for company locations on user profile page
$config['user_profile_page_show_more_company_locations_text'] = 'ukázat více (+{remaining_company_locations} provozovny)';
$config['user_profile_page_show_less_company_locations_text'] = 'skrýt';

?>