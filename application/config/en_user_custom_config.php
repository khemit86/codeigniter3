<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

################ Meta Config Variables for signup page ###########
/* Filename: application\modules\user\controllers\signup.php */
/* Controller: signup Method name: user_profile */
$config['user_profile_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | xxxx.xx';
$config['user_profile_page_default_description_meta_tag'] = '{user_first_name_last_name_or_company_name} is on xxx.xx'; // This config is using when user is not fill his description on user profile page as a meta tag description

############### Defined the validation message for user profile page
/* Filename: application\modules\user\controllers\User.php */
/* Controller: User Method name: user_profile */
$config['user_profile_upload_cover_picture_not_allowed'] = 'you are not allowed to upload cover picture';

################ Defined the user profile cover picture validation regarding on user profile page(gold plan only)
/* Filename: application\modules\projects\views\open_for_bidding_project_detail.php */
$config['invalid_user_profile_cover_picture_validation_message'] = "Invalid image. Please upload the cover picture again.";
$config['user_profile_cover_picture_extension_validation_message'] = "The file type you are trying to upload is not allowed!";

$config['user_profile_cover_picture_size_validation_message'] = "Minimum image size must be {max_width}X{max_height}";


// This is the text showing on badge in front of mannual complete project on "sp projects won" tab 
$config['user_profile_page_project_status_marked_as_complete_sp_projects_won_tab'] = 'Marked as complete PRJ';
$config['user_profile_page_fulltime_project_status_hired_sp_projects_won_tab'] = 'Hired';

$config['user_profile_page_statistics_as_service_provider'] = 'Service Provider';
$config['user_profile_page_statistics_as_project_owner'] = 'Project Owner';


//SP view - perspective - pa_user_profile_page_sp_statistics_
$config['user_profile_page_sp_statistics_hourly_rate'] = "Hourly Rate";

// number of won projects of sp (fixed/hourly)
$config['user_profile_page_sp_statistics_total_won_projects'] = "Won Projects";

// number of in progress projects of sp (fixed/hourly)
$config['user_profile_page_sp_statistics_projects_in_progress'] = "Work In Progress (sp)";

// number of completed projects of sp (fixed/hourly via portal/ out side portal)
$config['user_profile_page_sp_statistics_total_completed_projects'] = "Completed Projects (sp)";

// number of completed projects via portal of sp (fixed/hourly) 
$config['user_profile_page_sp_statistics_projects_completed_via_portal'] = "Projects Completed via portal (sp)";

// number of fulltime projects on which sp hired
$config['user_profile_page_sp_statistics_hires_on_fulltime_projects_via_portal'] = "Hires on fulltime via portal (sp)";

//PO perspective - pa_user_profile_page_po_statistics_
// number of total created projects of po
$config['user_profile_page_po_statistics_total_published_listings'] = "Total Published Listings";

// number of published projects of po (fixed/hourly)
$config['user_profile_page_po_statistics_total_published_projects'] = "Published Projects";

// number of published fulltime projects of po (fulltime)
$config['user_profile_page_po_statistics_total_published_fulltime_projects'] = "Published fulltime projects";

// number of hired sp by po for fulltime projects
$config['user_profile_page_po_statistics_hires_on_fulltime_projects_via_portal'] = "Hires on fulltime via portal (po)";

// number of in progress projects of po (fixed/hourly)
$config['user_profile_page_po_statistics_projects_in_progress'] = "Work In Progress (po)";

// number of completed projects of po (fixed/hourly outside portal/via portal)
$config['user_profile_page_po_statistics_total_completed_projects'] = "Completed Projects (po)";

// number of completed projects of po (via portal)
$config['user_profile_page_po_statistics_projects_completed_via_portal'] = "Projects Completed via portal (po)";

$config['user_profile_page_user_contact_details'] = 'Contact Details'; // This config is using to show heading on user profile page for contact details
$config['user_profile_page_user_member_since'] = "Member since";
$config['user_profile_page_user_last_login'] = "Last login";
$config['user_profile_page_user_profile_viewed'] = "Profile viewed";
$config['user_profile_page_user_profile_viewed_time'] = "time";
$config['user_profile_page_user_profile_viewed_times'] = "times";
$config['user_profile_page_user_number_of_followers'] = "Followers";
$config['user_profile_page_user_number_of_contacts'] = "Contacts";

// ca means company account
/* $config['ca_user_profile_page_statistics_as_service_provider'] = 'Statistics as Service Provider';
$config['ca_user_profile_page_statistics_as_project_owner'] = 'Statistics as Project Owner'; */
$config['ca_user_profile_page_informations'] = 'Information';
$config['ca_user_profile_page_description'] = 'Company Description';
$config['ca_user_profile_page_areas_of_expertise'] = 'Areas of Expertise';
$config['ca_user_profile_page_company_profile_heading'] = "Company Profile";
$config['ca_user_profile_page_company_founded_label_txt'] = "Founded";
$config['ca_user_profile_page_company_size_label_txt'] = "Company Size";

$config['ca_user_profile_page_skills_txt'] = 'Skills(c)';
$config['ca_user_profile_page_services_provided_txt'] = 'Services Provided(c)';
$config['ca_user_profile_page_certifications'] = 'Certifications(c)';


$config['ca_user_profile_page_our_vision_label_txt'] = 'Company Vision';
$config['ca_user_profile_page_our_mission_label_txt'] = 'Company Mision';
$config['ca_user_profile_page_core_values_label_txt'] = 'Company Core Values';
$config['ca_user_profile_page_strategy_goals_label_txt'] = 'Company Strategy and goals';

$config['ca_user_profile_page_company_open_hours_status_closed_label_txt'] = "Closed";

$config['ca_user_profile_page_company_opened_now_label_txt'] = "Opened Now";
$config['ca_user_profile_page_company_closed_now_label_txt'] = "Closed Now";
$config['ca_user_profile_page_company_open_hours_status_always_opened_label_txt'] = "Always Opened";

$config['ca_user_profile_page_company_open_hours_status_permanently_closed_label_txt'] = "Permanently Closed";
$config['ca_user_profile_page_company_open_hours_status_by_telephone_appointment_label_txt'] = "By Telephone Appointment";

$config['ca_user_profile_page_user_company_location_show_more_opening_hours_text'] = 'show opening hours <small>( + )</small>';
$config['ca_user_profile_page_user_company_location_hide_extra_opening_hours_text'] = 'close opening hours <small>( - )</small>';

$config['ca_user_profile_page_user_company_location_company_headquarter_heading'] = 'Company Headquarter'; // location indicated only headquarter - no info about hrs
$config['ca_user_profile_page_user_company_location_company_headquarter_opening_hours_heading'] = 'Company Headquarter and Opening Hours'; // location indicated only headquarter - info about hrs
$config['ca_user_profile_page_user_company_location_company_locations_heading'] = 'Company Locations'; // location headquarter + 1 or more additional locations - no info about hrs
$config['ca_user_profile_page_user_company_location_company_locations_opening_hours_heading'] = 'Company Locations and Opening Hours'; // location headquarter + 1 or more additional locations - info about hrs (on any of the headquarter or locations)

// PA means Personal Account
$config['pa_user_profile_page_description'] = 'Description';

$config['pa_user_profile_page_skills'] = 'Skills';

$config['pa_user_profile_page_services_provided'] = 'Services Provided';

$config['pa_user_profile_page_areas_of_expertise'] = 'Areas of Expertise';

$config['pa_user_profile_page_work_experience'] = 'Work Experience';

$config['pa_user_profile_page_education_training'] = 'Education & Training';

$config['pa_user_profile_page_spoken_languages'] = 'Spoken languages';

$config['pa_user_profile_page_certifications'] = 'Certifications';

$config['pa_user_profile_page_mother_tongue'] = 'Mother Tongue';

$config['pa_user_profile_page_user_address'] = "Address";

$config['pa_user_profile_page_informations'] = 'Status';


//############## For app account start //
$config['ca_app_user_profile_page_informations'] = 'Information(app)';
$config['ca_app_user_profile_page_description'] = 'Company Description(app)';
$config['ca_app_user_profile_page_areas_of_expertise'] = 'Areas of Expertise(app)';
$config['ca_app_user_profile_page_company_profile_heading'] = "Company Profile(app)";
$config['ca_app_user_profile_page_company_founded_label_txt'] = "Founded(app)";
$config['ca_app_user_profile_page_company_size_label_txt'] = "Company Size(app)";

$config['ca_app_user_profile_page_skills_txt'] = 'Skills(app)';
$config['ca_app_user_profile_page_services_provided_txt'] = 'Services Provided(app)';

$config['ca_app_user_profile_page_work_experience'] = 'Work Experience(app)';
$config['ca_app_user_profile_page_education_training'] = 'Education & Training(app)';
$config['ca_app_user_profile_page_spoken_languages'] = 'Spoken Languages(app)';

$config['ca_app_user_profile_page_certifications'] = 'Certifications(app)';
$config['ca_app_user_profile_page_mother_tongue'] = 'Mother Tongue(app)';

$config['ca_app_user_profile_page_our_vision_label_txt'] = 'Vision(app)';
$config['ca_app_user_profile_page_our_mission_label_txt'] = 'Mision(app)';
$config['ca_app_user_profile_page_core_values_label_txt'] = 'Core values(app)';
$config['ca_app_user_profile_page_strategy_goals_label_txt'] = 'Strategy and goals(app)';


$config['ca_app_user_profile_page_user_company_location_company_headquarter_heading'] = 'Company Headquarter(app)'; // location indicated only headquarter - no info about hrs
$config['ca_app_user_profile_page_user_company_location_company_headquarter_opening_hours_heading'] = 'Company Headquarter and Opening Hours(app)'; // location indicated only headquarter - info about hrs
$config['ca_app_user_profile_page_user_company_location_company_locations_heading'] = 'Company Locations(app)'; // location headquarter + 1 or more additional locations - no info about hrs
$config['ca_app_user_profile_page_user_company_location_company_locations_opening_hours_heading'] = 'Company Locations and Opening Hours(app)'; // location headquarter + 1 or more additional locations - info about hrs (on any of the headquarter or locations)

// this indicate the spoken language levels description
$config['ca_app_user_profile_page_spoken_languages_levels_description_msg'] = 'A1/A2 basic user; B1/B2 independent user; C1/C2 proficient user (app)';
// singular text when user have only 1 spoken language
$config['ca_app_user_profile_page_spoken_languages_txt_singular'] = 'APP spoken language singular(app)';
// plural text when user have only multiple spoken language
$config['ca_app_user_profile_page_spoken_languages_txt_plural'] = 'APP spoken language plural(app)';
//############ For app account end //

// config are using as a tab heading on profile page
$config['user_profile_page_information_tab'] = 'Information';
$config['user_profile_page_portfolio_tab'] = 'Portfolio';
$config['user_profile_page_reviews_tab'] = 'Reviews';
$config['user_profile_page_projects_created_tab'] = 'Projects Created';
$config['user_profile_page_projects_won_tab'] = 'Projects Won';

// config are using on url when user click on profile page tabs 
$config['user_profile_page_portfolio_tab_url_txt'] = 'portfolio';
$config['user_profile_page_reviews_tab_url_txt'] = 'reviews';
$config['user_profile_page_projects_created_tab_url_txt'] = 'posted_projects';
$config['user_profile_page_projects_won_tab_url_txt'] = 'won_projects';


// config are using under reviews tab on user profile page
$config['user_profile_page_reviews_tab_service_provider_txt'] = 'Service Provider / Employee';
$config['user_profile_page_reviews_tab_project_owner_txt'] = 'Project Owner / Employer';


// this indicate the spoken language levels description
$config['pa_user_profile_page_spoken_languages_levels_description_msg'] = 'PA A1/A2 basic user; B1/B2 independent user; C1/C2 proficient user';
// singular text when user have only 1 spoken language
$config['pa_user_profile_page_spoken_languages_txt_singular'] = 'PA spoken language singular';
// plural text when user have only multiple spoken language
$config['pa_user_profile_page_spoken_languages_txt_plural'] = 'PA spoken languages plural';
 

//No data message for information tab on profile page when there is no data based on account type/gender
$config['user_profile_page_information_tab_no_data_male_visitor_view'] = 'Male:{user_first_name_last_name} have not fill the profile information yet';

$config['user_profile_page_information_tab_no_data_female_visitor_view'] = 'Female:{user_first_name_last_name} have not fill the profile information yet';

$config['user_profile_page_information_tab_no_data_company_app_male_visitor_view'] = 'App Male:{user_first_name_last_name} have not fill the profile information yet';

$config['user_profile_page_information_tab_no_data_company_app_female_visitor_view'] = 'App Female:{user_first_name_last_name} have not fill the profile information yet';

$config['user_profile_page_information_tab_no_data_same_user_view'] = 'you have not fill the profile information yet';

$config['user_profile_page_information_tab_no_data_company_visitor_view'] = 'Company: {user_company_name} has not filled any information yet';

//$config['user_profile_portfolio_section_listing_limit'] = 2;

// Config for project created tab on user profile page when there is no data
$config['user_profile_page_projects_created_tab_no_data_male_visitor_view'] = 'Male: {user_first_name_last_name} nevytvořil žádné inzeráty.';

$config['user_profile_page_projects_created_tab_no_data_female_visitor_view'] = 'Female: {user_first_name_last_name} nevytvořil žádné inzeráty.';

$config['user_profile_page_projects_created_tab_no_data_company_app_male_visitor_view'] = 'App Male: {user_first_name_last_name} nevytvořil žádné inzeráty.';

$config['user_profile_page_projects_created_tab_no_data_company_app_female_visitor_view'] = 'App Female: {user_first_name_last_name} nevytvořil žádné inzeráty.';

$config['user_profile_page_projects_created_tab_no_data_company_visitor_view'] = 'Company: {user_company_name} nevytvořil žádné inzeráty.';

$config['user_profile_page_projects_created_tab_no_data_po_view'] = 'nevytvořil žádné inzeráty';


// Config for project won tab on user profile page when there is no data
$config['user_profile_page_projects_won_tab_no_data_male_visitor_view'] = 'Male:{user_first_name_last_name} žádný projekt ani pracovní pozici.';

$config['user_profile_page_projects_won_tab_no_data_female_visitor_view'] = 'Female:{user_first_name_last_name} žádný projekt ani pracovní pozici.';

$config['user_profile_page_projects_won_tab_no_data_company_app_male_visitor_view'] = 'App Male:{user_first_name_last_name} žádný projekt ani pracovní pozici.';

$config['user_profile_page_projects_won_tab_no_data_company_app_female_visitor_view'] = 'App Female:{user_first_name_last_name} žádný projekt ani pracovní pozici.';

$config['user_profile_page_projects_won_tab_no_data_company_visitor_view'] = 'Company:{user_company_name} žádný projekt ani pracovní pozici.';

$config['user_profile_page_projects_won_tab_no_data_sp_view'] = 'žádný projekt ani pracovní pozici.';


// Config for portfolio tab on user profile page when there is no data
$config['user_profile_page_portfolio_tab_no_data_male_visitor_view'] = 'Male:{user_first_name_last_name} not created portfolio yet';

$config['user_profile_page_portfolio_tab_no_data_female_visitor_view'] = 'Female:{user_first_name_last_name} not created portfolio yet.';

$config['user_profile_page_portfolio_tab_no_data_company_app_male_visitor_view'] = 'App Male:{user_first_name_last_name} not created portfolio yet';

$config['user_profile_page_portfolio_tab_no_data_company_app_female_visitor_view'] = 'App Female:{user_first_name_last_name} not created portfolio yet.';

$config['user_profile_page_portfolio_tab_no_data_company_visitor_view'] = 'Company:{user_company_name} not created portfolio yet';

$config['user_profile_page_portfolio_tab_no_data_same_user_view'] = 'you not created portfolio yet';


// button text for cover picture of portfolio standalone page
$config['user_profile_page_upload_cover_picture_btn_txt'] = 'upload cover picture';
$config['user_profile_page_upload_new_cover_picture_btn_txt'] = 'upload new cover picture';


// config is using to show more/ show less text for area of experise section on user profile page
$config['user_profile_page_show_more_area_of_expertise_text'] = 'show more area of expertise (+{remaining_category} area of expertise)';
$config['user_profile_page_show_less_area_of_expertise_text'] = 'show less area of expertise';


// config is using to show more/ show less text for services provided on user profile page
$config['user_profile_page_show_more_services_provided_text'] = 'show more services provided (+{remaining_services_provided} services provided)';
$config['user_profile_page_show_less_services_provided_text'] = 'show less services provided';

// config is using to show more/ show less text for skills on user profile page
$config['user_profile_page_show_more_skills_text'] = 'show more skills (+{remaining_skills} skills)';
$config['user_profile_page_show_less_skills_text'] = 'show less skills';


// config is using to show more/ show less text for Work Experience on user profile page
$config['user_profile_page_show_more_work_experience_text'] = 'show more work experience (+{remaining_work_experience} work experience)';
$config['user_profile_page_show_less_work_experience_text'] = 'show less work experience';


// config is using to show more/ show less text for Education Training on user profile page
$config['user_profile_page_show_more_education_training_text'] = 'show more education training (+{remaining_education_training} education training)';
$config['user_profile_page_show_less_education_training_text'] = 'show less education training';


// config is using to show more/ show less text for spoken languages on user profile page
$config['user_profile_page_show_more_spoken_languages_text'] = 'show more spoken languages (+{remaining_spoken_languages} spoken languages)';
$config['user_profile_page_show_less_spoken_languages_text'] = 'show less spoken languages';

// config is using to show more/ show less text for certifications on user profile page
$config['user_profile_page_show_more_certifications_text'] = 'show more certifications (+{certifications} certifications)';
$config['user_profile_page_show_less_certifications_text'] = 'show less certifications';


// config is using to show more/ show less text for company locations on user profile page
$config['user_profile_page_show_more_company_locations_text'] = 'show more locations (+{remaining_company_locations} locations)';
$config['user_profile_page_show_less_company_locations_text'] = 'show less locations';




?>