<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//Left navigation Menu name
$config['pa_projects_management_left_nav_favorite_employers'] = 'Oblíbení zaměstnavatelé';
$config['ca_projects_management_left_nav_favorite_partners'] = 'Oblíbení partneři';
$config['ca_app_projects_management_left_nav_favorite_partners'] = 'Oblíbení partneři';


/*
|--------------------------------------------------------------------------
| Meta Variables 
|--------------------------------------------------------------------------
| 
*/

################ Meta Variables ###########
$config['pa_favorite_employers_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Oblíbení zaměstnavatelé';
$config['pa_favorite_employers_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Oblíbení zaměstnavatelé';

$config['ca_favorite_employers_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Oblíbení partneři';
$config['ca_favorite_employers_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Oblíbení partneři';

$config['ca_app_favorite_employers_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Oblíbení partneři';
$config['ca_app_favorite_employers_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Oblíbení partneři';


################ Page text Variables ###########
$config['pa_favorite_employers_headline_title_my_favourite_employers'] = 'Oblíbení zaměstnavatelé';
$config['ca_favorite_employers_headline_title_my_favourite_employers'] = 'Oblíbení partneři';
$config['ca_app_favorite_employers_headline_title_my_favourite_employers'] = 'Oblíbení partneři';


$config['pa_favorite_employers_no_favourite_employer_available'] = 'Momentálně nemáte žádného zaměstnavatele ve vašem seznamu oblíbených.';

$config['ca_favorite_employers_no_favourite_employer_available'] = 'Momentálně nemáte žádného partnera ve vašem seznamu oblíbených.';

$config['ca_app_favorite_employers_no_favourite_employer_available'] = 'Momentálně nemáte žádného partnera ve vašem seznamu oblíbených.';

################ Url Routing Variables ###########
//favorite_employers
$config['pa_favorite_employers_page_url'] = 'oblibeni-zamestnavatele';
$config['ca_favorite_employers_page_url'] = 'oblibeni-partneri';


// variable for favorite employers subscribe success and un-subscribe success
$config['favorite_employers_subscribe_success_message'] = 'přihlásili jste se k odběru oznámení <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> pokaždé, když zveřejní nový inzerát';

$config['favorite_employers_unsubscribe_success_message'] = 'odhlásili jste se k odběru oznámení o nově zveřejněných inzerátech od <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>';

//variable for favorite employers sunscribe success and un-subscribe success user display activity log
$config['favorite_employers_subscribe_success_user_activity_log_displayed_message'] = 'Přihlásili jste se k odběru oznámení <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a> pokaždé, když zveřejní nový inzerát.';

$config['favorite_employers_unsubscribe_user_activity_log_displayed_message'] = 'Odhlásili jste se k odběru oznámení o nově zveřejněných inzerátech od <a href="{user_profile_url_link}" target="_blank">{user_first_name_last_name_or_company_name}</a>.';

// variable for error message when user has open multiple project details page and he already reached to subscription limit
$config['pa_favorite_employers_subcription_limit_reached_error_message'] = "Nelze provést tuto volbu. Dosáhli jste maximálního počtu oblíbených zaměstnavatelů ve vašem členství.";
$config['ca_favorite_employers_subcription_limit_reached_error_message'] = "Nelze provést tuto volbu. Dosáhli jste maximálního počtu oblíbených zaměstnavatelů ve vašem členství.";

//favorite employers short listed project
$config['favourite_employers_page_total_published_listings'] = "Zveřejněné inzeráty:";

$config['favourite_employers_page_total_published_projects'] = "Zveřejněné projekty:";

$config['favourite_employers_page_total_published_fulltime_projects'] = "Zveřejněné pracovny pozice:";

$config['favourite_employers_page_projects_completed_via_portal'] = "Dokončené projekty přes Travai:";

$config['favourite_employers_page_hires_on_fulltime_projects_via_portal'] = "Zaměstnaných přes Travai:";


$config['favourite_employers_page_total_avg_rating_and_reviews_as_po_txt'] = '; přijato {total_projects_reviews} jako dodavatel a průměrné hodnocení je {project_user_total_avg_rating_as_po}';

$config['favourite_employers_page_total_avg_rating_and_reviews_as_employer_txt'] = '; přijato {total_fulltime_projects_reviews} jako zaměstnanec a průměrné hodnocení je {fulltime_project_user_total_avg_rating_as_employer}';

$config['favourite_employers_un_favourite_btn_txt'] = 'Smazat';


$config['favorite_employers_show_more_notifications_consent_text'] = 'přijímat oznámení o nově zveřejněných inzerátech <span><small>( + )</small></span>';

$config['favorite_employers_hide_extra_notifications_consent_text'] = 'přijímat oznámení o nově zveřejněných inzerátech <span><small>( - )</small></span>';


$config['pa_favorite_employers_newly_posted_projects_user_notifications_consent_txt'] = 'souhlasím s odběrem upozornění od oblíbených zaměstnavatelů, pokaždé když zveřejní nový inzerát';

$config['ca_favorite_employers_newly_posted_projects_user_notifications_consent_txt'] = 'souhlasím s odběrem upozornění od partnerů, pokaždé když zveřejní nový inzerát';

$config['ca_app_favorite_employers_newly_posted_projects_user_notifications_consent_txt'] = 'souhlasím s odběrem upozornění od partnerů, pokaždé když zveřejní nový inzerát';

?>