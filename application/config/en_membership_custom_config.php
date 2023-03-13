<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Meta Variables 
|--------------------------------------------------------------------------
| 
*/
################ Url Routing Variables for login page ###########
/* Filename: application\modules\login\controllers\login.php */
$config['membership_page_url'] = 'membership';

################ Meta Config Variables for login page ###########
/* Filename: application\modules\membership\controllers\memberhip.php */
/* Controller: Method name: membership_plan */

$config['membership_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Membership Title Meta Tag - will be translated in each language';
$config['membership_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Membership Description Meta Tag - will be translated in each language';


$config['downgrade_membership_plan_txt'] = "Are you sure you want to downgrade to FREE membership? By continuing you are going to lose all the benefits that GOLD membership offers.";

$config['downgrade_membership_plan_disclamer'] = 'I confirm that I want to downgrade to Free membership and lose all benefits of GOLD Membership!';

$config['upgrade_membership_btn_text'] = 'Upgrade';
$config['downgrade_membership_btn_text'] = 'Downgrade';




$config['downgrade_membership_popup_proceed_btn_text'] = 'Proceed';

// custom variable for user activity related to membership
$config['user_activity_log_displayed_message_successful_membership_upgrade_plan_confirmation_first_time'] = 'You successfully upgraded your {user_current_membership_plan_name} plan to {user_upgraded_membership_plan_name} membership. We granted you a BONUS of xxxxxx, for you to spend within the portal.';

$config['user_activity_log_displayed_message_successful_membership_upgrade_plan_confirmation'] = 'Membership upgrade from FREE to GOLD has been made.';

$config['user_activity_log_displayed_message_successful_membership_downgrade_plan_confirmation'] = 'Membership downgrade from GOLD to FREE has been done.';

// config variables for confirmation popup of membership plan upgrade/downgrade.
$config['successful_downgrade_gold_to_free_membership_confirmation_popup'] = 'You downgrade to FREE membership plan';

$config['successful_upgrade_free_to_gold_membership_confirmation_popup'] = 'You successfully upgraded your membership plan to GOLD';
?>


