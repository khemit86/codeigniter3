<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Meta Variables 
|--------------------------------------------------------------------------
| 
*/
################ Url Routing Variables for login page ###########
/* Filename: application\modules\login\controllers\login.php */
$config['membership_page_url'] = 'clenstvi';

################ Meta Config Variables for login page ###########
/* Filename: application\modules\membership\controllers\memberhip.php */
/* Controller: Method name: membership_plan */

$config['membership_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Členství Travai.cz';
$config['membership_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Členství Travai.cz';


//downgrade popup
$config['downgrade_membership_plan_txt'] = "Opravdu chcete snížit své členství? Pokračováním ztratíte všechny benefity, které jsou nabízené v Perfektním členství.";

$config['downgrade_membership_plan_disclamer'] = 'Ano, souhlasím, chci snížit své členství na Základní';
/////////////////////////////////////////////

$config['upgrade_membership_btn_text'] = 'Vybrat';

$config['downgrade_membership_btn_text'] = 'Snížit';

$config['downgrade_membership_popup_proceed_btn_text'] = 'Provést';

//$config['user_activity_log_displayed_message_successful_membership_upgrade_plan_confirmation_first_time'] = 'You successfully upgraded your {user_current_membership_plan_name} plan to {user_upgraded_membership_plan_name} membership. We granted you a BONUS of 10 000 Kč, for you to spend within Travai portal.';
$config['user_activity_log_displayed_message_successful_membership_upgrade_plan_confirmation_first_time'] = 'Změna členství ze Základní na Perfektní byla provedena. Za tuto změnu jste získali bonus 10 000 Kč. Tento bonus lze použít pro nákupy na portálu.';

$config['user_activity_log_displayed_message_successful_membership_upgrade_plan_confirmation'] = 'Změna členství ze Základní na Perfektní byla provedena.';

$config['user_activity_log_displayed_message_successful_membership_downgrade_plan_confirmation'] = 'Snížení členství z Perfektní na Základní bylo provedeno.';



$config['successful_downgrade_gold_to_free_membership_confirmation_popup'] = 'Snížení členství z Perfektní na Základní bylo provedeno.';

$config['successful_upgrade_free_to_gold_membership_confirmation_popup'] = 'Změna členství na Perfektní byla provedena.';

?>