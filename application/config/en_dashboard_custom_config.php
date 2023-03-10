<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//Left navigation menu name
$config['pa_user_left_nav_dashboard'] = 'Dashboard';
$config['ca_user_left_nav_dashboard'] = 'Dashboard Comp';
$config['pa_user_left_nav_send_feedback'] = 'Send Feedback';
$config['ca_user_left_nav_send_feedback'] = 'Send Feedback Comp';


/*
|--------------------------------------------------------------------------
| Meta Variables 
|--------------------------------------------------------------------------
| 
*/

################ Meta Config Variables for dashboard page ###########
/* Filename: application\modules\user\controllers\dashboard.php */
/* Controller: user Method name: index */
$config['dashboard_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | Dashboard Title Meta Tag';
$config['dashboard_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | Dashboard Description Meta Tag';

################ Url Routing Variables for Dashboard page ###########
/* Filename: application\modules\dashboard\controllers\dashboard.php */
$config['dashboard_page_url'] = 'dashboard';

//Dashboadrd Top Section
$config['dashboard_top_section_member'] = 'Membership:';


$config['dashboard_top_section_manage_membership'] = 'Manage Membership';

// This config is using to show the profile compeletion percentage with text of user on dshboard page
$config['user_profile_completion_percentage_dashboard_txt'] = 'Your Profile is<b>{user_profile_completion_percentage}%</b>complete';

// This config is using when user never upgrade the membership (example login first/second time until not upgrade membership)
$config['dashboard_top_section_manage_membership_initial_view_no_upgrade_yet'] = '<span class="space_right">upgrade to GOLD</span><span>(Bonus 10 000 Kc)</span>';

$config['dashboard_top_section_add_hourly_rate'] = 'Add Your Hourly Rate';

$config['dashboard_top_section_add_address'] = 'Add Address';

$config['dashboard_top_section_account_balance'] = 'Account Balance:';

$config['dashboard_top_section_referral_code'] = 'Referral Code:';


$config['dashboard_top_section_signup_bonus_balance'] = 'Signup Bonus Balance:';

$config['dashboard_top_section_bonus_balance'] = 'Bonus Balance:';

####################################################################################################

//Send Feedback Popup
$config['send_feedback_popup_heading_title_modal_body'] = 'Send Us Your Feedback';

$config['send_feedback_popup_description_modal_body'] = 'EN - Travai zkoum?? a ov????uje p????le??itosti na z??klad?? va???? zp??tn?? vazby. Zapojte se sv??mi n??pady, sd??lejte sv?? c??le, ??sp??chy, frustrace a nespln??n?? pot??eby.';

$config['send_feedback_popup_disclaimer_modal_body'] = 'EN - Va??e zp??tn?? vazba, u??ivatelsk?? jm??no a e-mailov?? adresa, budou zasl??ny na adresu xxxxx.cz. V??ce v <a href="{terms_and_conditions_page_url}" target="_blank">Obchodn??ch podm??nk??ch</a> a <a href="{privacy_policy_page_url}" target="_blank">Z??sad??ch ochrany osobn??ch ??daj??</a>.';

//$config['send_feedback_popup_send_btn_modal_footer'] = 'Send Feedback';
//$config['send_feedback_popup_send_btn_modal_footer'] = 'Odeslat';

$config['send_feedback_popup_cancel_btn_modal_footer'] = 'Cancel';

$config['send_feedback_popup_description_required_error_message'] = 'Description is required.';

$config['send_feedback_popup_user_activity_log_mesage'] = 'You have submited feedback.';

$config['send_feedback_popup_submit_confirmation_mesage'] = 'Your feedback has been successfully submitted.';



$config['send_feedback_popup_upload_files_txt'] = 'sf-Upload Files';

$config['send_feedback_popup_attachment_allowed_file_types_js'] = '"png","PNG","gif","GIF","jpeg","JPEG","jpg","JPG","pdf","application/PDF","xls","xlsx","doc","docx","txt"';

$config['send_feedback_popup_attachment_allowed_file_types'] = 'image/*,.pdf,.xls, .xlsx, .doc, .docx, .txt';

$config['send_feedback_popup_attachment_invalid_file_extension_validation_message'] = "sf-The file type you are trying to upload is not allowed";

$config['send_feedback_popup_user_upload_blank_attachment_alert_message'] = "sf-you cannot upload blank attachment - send feedback";

###########################################################################################

// variable for my project section on dashboard
$config['no_draft_project_message'] = 'You do not currently have any project saved as Draft';
// Display when there is no project available in cancelled project tab

$config['no_awaiting_moderation_project_message'] = 'You do not currently have any project in awaiting for moderation stage';
// Display when there is no project available in awaiting moderation project tab

$config['no_open_bidding_project_message'] = 'You do not currently have any project in open for bidding stage';
// Display when there is no project available in awaiting moderation project tab

$config['no_awarded_project_message'] = 'You do not currently have any Awarded Project';
// Display when there is no project available in awarded project tab

$config['no_in_progress_project_message'] = 'You do not currently have any In Progress Project';
// Display when there is no project available in progress project tab

$config['no_incomplete_project_message'] = 'You do not currently have any Incomplete Project';
// Display when there is no project available incomplete project tab

$config['no_completed_project_message'] = 'you do not currently have any completed project';
// Display when there is no project available in completed project tab

$config['no_expired_project_message'] = 'You do not currently have any Expired Project';
// Display when there is no project available in expired project tab

$config['no_cancelled_project_message'] = 'You do not currently have any Cancelled Project';
// Display when there is no project available in cancelled project tab


//VARIABLES USED FOR sp VIEW
$config['no_active_bids_project_message'] = 'You do not currently have any active bids';
// Display when there is no project available in active bids my project tab

$config['no_awarded_bids_project_message'] = 'You do not currently have any awarded bids';
// Display when there is no project available in awarded bids my project tab

$config['no_hired_application_project_message'] = 'You are not hired yet on any projects';
// Display when there is no project available in hired tab my project section

$config['no_in_progress_bids_project_message'] = 'You do not currently have any in progress projects.';
// Display when there is no project available in in progress work my project tab

$config['no_incomplete_bids_project_message'] = 'You do not currently have any incomplete project';
// Display when there is no project available in in complete work my project tab

$config['no_completed_bids_project_message'] = 'You do not currently have any completed projects.';
// Display when there is no project available in completed work my project tab


############### defines custom config variables when project not available for dedicated table and PO perform activities 
// config for draft tab option on dasboard/my project page
$config['project_draft_already_deleted_not_available_for_remove_dashboard_myprojects_po_view'] = 'Proj DRAFT - Draft not available for delete because it has been deleted.';

$config['fulltime_project_draft_already_deleted_not_available_for_remove_dashboard_myprojects_po_view'] = 'Fulltime Draft not available for delete because it has been already deleted.';

$config['project_draft_status_changed_not_available_for_remove_dashboard_myprojects_po_view'] = 'PROJ Draft not available for delete because its status has been changed';

$config['fulltime_project_draft_status_changed_not_available_for_remove_dashboard_myprojects_po_view'] = 'fulltime Draft not available for delete because its status has been changed';

################ edit
$config['project_draft_already_deleted_not_available_for_edit_dashboard_myprojects_po_view'] = 'PROJ Draft not available for edit because it has been deleted';

$config['fulltime_project_draft_already_deleted_not_available_for_edit_dashboard_myprojects_po_view'] = 'Fulltime Draft not available for edit because it has been deleted';

$config['project_draft_status_changed_not_available_for_edit_dashboard_myprojects_po_view'] = 'PROJ Draft not available for edit because its status has been changed';

$config['fulltime_project_draft_status_changed_not_available_for_edit_dashboard_myprojects_po_view'] = 'Fulltime Draft not available for edit because its status has been changed';

####### publish
$config['project_draft_already_deleted_not_available_for_publish_dashboard_myprojects_po_view'] = 'PROJ Draft not available for publish because it has been deleted';

$config['fulltime_project_draft_already_deleted_not_available_for_publish_dashboard_myprojects_po_view'] = 'Fulltime Draft not available for publish because it has been deleted';

$config['project_draft_status_changed_not_available_for_publish_dashboard_myprojects_po_view'] = 'PROJ Draft not available for publish because its status has been changed';

$config['fulltime_project_draft_status_changed_not_available_for_publish_dashboard_myprojects_po_view'] = 'fulltime Draft not available for publish because it has been deleted';


// config for open for bidding tab tab option on dasboard/my project page
// edit project
$config['project_open_for_bidding_admin_deleted_not_available_for_edit_dashboard_myprojects_po_view'] = 'Edit open Proj - project already deleted by admin not available for edit';

$config['fulltime_project_open_for_bidding_admin_deleted_not_available_for_edit_dashboard_myprojects_po_view'] = 'Edit open Fulltime - Fulltime project already deleted by admin not available for edit';

$config['project_open_for_bidding_status_changed_not_available_for_edit_dashboard_myprojects_po_view'] = 'Project not available for edit because its status has been changed';

$config['fulltime_project_open_for_bidding_status_changed_not_available_for_edit_dashboard_myprojects_po_view'] = 'Fulltime Project not available for edit because its status has been changed';

// for cancel open for bidding project
$config['project_open_for_bidding_admin_deleted_not_available_for_cancel_dashboard_myprojects_po_view'] = 'Cancel open Proj - project already deleted by admin not available for cancel';

$config['fulltime_project_open_for_bidding_admin_deleted_not_available_for_cancel_dashboard_myprojects_po_view'] = 'Cancel open fulltime Proj - Fulltime project already deleted by admin not available for cancel';


$config['project_open_for_bidding_status_changed_not_available_for_cancel_dashboard_myprojects_po_view'] = 'Cancel open Proj - Project not available for cancel because its status has been changed';

$config['fulltime_project_open_for_bidding_status_changed_not_available_for_cancel_dashboard_myprojects_po_view'] = 'Cancel open fulltime Proj - Fulltime project not available for cancel because its status has been changed';


//this config are using when po trying to cancel the project from open for bidding tab and project have awarded bids
$config['fulltime_project_open_for_bidding_status_awarded_bids_exist_not_available_for_cancel_myprojects_po_view'] = 'You cannot cancel the fulltime project because award exists->cancel Open for bidding fulltime project - award exists';


// for cancel expired project
$config['project_expired_admin_deleted_not_available_for_cancel_dashboard_myprojects_po_view'] = 'Cancel expired Proj - project already deleted by admin not available for cancel';

$config['fulltime_project_expired_admin_deleted_not_available_for_cancel_dashboard_myprojects_po_view'] = 'Cancel expired fulltime Proj - Fulltime project already deleted by admin not available for cancel';


// for cancel expired project
$config['project_expired_status_changed_not_available_for_cancel_dashboard_myprojects_po_view'] = 'Cancel Project Expired - Project not available for cancel because its status has been changed';

$config['fulltime_project_expired_status_changed_not_available_for_cancel_dashboard_myprojects_po_view'] = 'Cancel FulltimeProject Expired - Fulltime project not available for cancel because its status has been changed';


// this config are using when po trying to cancel the project from expired tab and project have awarded bids
$config['fulltime_project_expired_status_awarded_bids_exist_not_available_for_cancel_myprojects_po_view'] = 'You cannot cancel the fulltime project because award existsCancel Fulltime Project Expired - award exists';

// for copy into new
$config['project_open_for_bidding_admin_deleted_not_available_for_copy_into_new_dashboard_myprojects_po_view'] = 'PRJ - OPEN BIDDING - COPYINTONEW - project already deleted by admin not available for copy into new';

$config['fulltime_project_open_for_bidding_admin_deleted_not_available_for_copy_into_new_dashboard_myprojects_po_view'] = 'FT - OPEN BIDDING - COPYINTONEW - Fulltime project already deleted by admin not available for copy into new';

/* $config['project_open_for_bidding_status_changed_not_available_for_copy_into_new_dashboard_myprojects_po_view'] = 'PRJ - OPEN BIDDING - COPYINTONEW - Project not available for copy into new because its status has been changed';

$config['fulltime_project_open_for_bidding_status_changed_not_available_for_copy_into_new_dashboard_myprojects_po_view'] = 'FT - OPEN BIDDING - COPYINTONEW - Fulltime project not available for copy into new because its status has been changed'; */

### COPY INTO NEW OPTION WILL SHOW WHEN PROJECT CANCELLED BY ADMIN.
### REPOST OPTION WILL SHOW WHEN PROJECT CANCELLED BY USER.
## IF ADMIN DELETED THE CANCEL PROJECT THAT MEANS WE NEED TO SHOW ERROR POPUP MESSAGE BASED ON WHAT OPTION PO HAVE REPOST/COPY INTO NEW
//for copy into new
$config['project_cancelled_admin_deleted_not_available_for_copy_into_new_dashboard_myprojects_po_view'] = 'PRJ - DELETED - COPYINTONEW - project already deleted by admin not available for copy into new';

$config['fulltime_project_cancelled_admin_deleted_not_available_for_copy_into_new_dashboard_myprojects_po_view'] = 'ft - CANCELLED - COPYINTONEW - Fulltime project already deleted by admin not available for copy into new';


//for repost
$config['project_cancelled_admin_deleted_not_available_for_repost_dashboard_myprojects_po_view'] = 'Cancelled project - project already deleted by admin not available for repost';

$config['fulltime_project_cancelled_admin_deleted_not_available_for_repost_dashboard_myprojects_po_view'] = 'Cancelled fulltime project - Fulltime project already deleted by admin not available for repost';

##################################################################################

$config['project_expired_admin_deleted_not_available_for_repost_dashboard_myprojects_po_view'] = 'Expired project - project already deleted by admin not available for repost';

$config['fulltime_project_expired_admin_deleted_not_available_for_repost_dashboard_myprojects_po_view'] = 'Expired fulltime project - Fulltime project already deleted by admin not available for repost';

/* $config['project_expired_status_changed_not_available_for_repost_dashboard_myprojects_po_view'] = 'Expired project - Project not available for repost because its status has been changed';

$config['fulltime_project_expired_status_changed_not_available_for_repost_dashboard_myprojects_po_view'] = 'Expired fulltime project - Fulltime project not available for repost because its status has been changed'; */

#######################################################
// for upgrade
$config['project_open_for_bidding_admin_deleted_not_available_for_upgrade_dashboard_myprojects_po_view'] = 'project already deleted by admin not available for upgrade';

$config['fulltime_project_open_for_bidding_admin_deleted_not_available_for_upgrade_dashboard_myprojects_po_view'] = 'Fulltime project already deleted by admin not available for upgrade';

$config['project_open_for_bidding_status_changed_not_available_for_upgrade_dashboard_myprojects_po_view'] = 'project status has been changed not available for upgrade';

$config['fulltime_project_open_for_bidding_status_changed_not_available_for_upgrade_dashboard_myprojects_po_view'] = 'fulltime project status has been changed not available for upgrade';


// for upgrade as urgent - when project is already featured
$config['project_open_for_bidding_admin_deleted_not_available_for_upgrade_as_urgent_dashboard_myprojects_po_view'] = 'project already deleted by admin not available for upgrade as urgent';

$config['fulltime_project_open_for_bidding_admin_deleted_not_available_for_upgrade_as_urgent_dashboard_myprojects_po_view'] = 'Fulltime project already deleted by admin not available for upgrade as urgent';

$config['project_open_for_bidding_status_changed_not_available_for_upgrade_as_urgent_dashboard_myprojects_po_view'] = 'project status has been changed not available for upgrade as urgent';

$config['fulltime_project_open_for_bidding_status_changed_not_available_for_upgrade_as_urgent_dashboard_myprojects_po_view'] = 'fulltime project status has been changed not available for upgrade as urgent';


###############################

//for upgrade as featured - when project is already urgent
$config['project_open_for_bidding_admin_deleted_not_available_for_upgrade_as_featured_dashboard_myprojects_po_view'] = 'project already deleted by admin not available for upgrade as featured';

$config['fulltime_project_open_for_bidding_admin_deleted_not_available_for_upgrade_as_featured_dashboard_myprojects_po_view'] = 'Fulltime project already deleted by admin not available for upgrade as featured';

$config['project_open_for_bidding_status_changed_not_available_for_upgrade_as_featured_dashboard_myprojects_po_view'] = 'project status has been changed not available for upgrade as featured';

$config['fulltime_project_open_for_bidding_status_changed_not_available_for_upgrade_as_featured_dashboard_myprojects_po_view'] = 'fulltime project status has been changed not available for upgrade as featured';


// prolong urgent
$config['project_open_for_bidding_admin_deleted_not_available_for_prolong_urgent_upgrade_dashboard_myprojects_po_view'] = 'project already deleted by admin not available for prolong urgent upgrade';

$config['fulltime_project_open_for_bidding_admin_deleted_not_available_for_prolong_urgent_upgrade_dashboard_myprojects_po_view'] = 'Fulltime project already deleted by admin not available for prolong urgent upgrade';

$config['project_open_for_bidding_status_changed_not_available_for_prolong_urgent_upgrade_dashboard_myprojects_po_view'] = 'project status has been changed not available for prolong urgent upgrade';

$config['fulltime_project_open_for_bidding_status_changed_not_available_for_prolong_urgent_upgrade_dashboard_myprojects_po_view'] = 'fulltime project status has been changed not available for prolong urgent upgrade';

#################################################################################
// prolong featured
$config['project_open_for_bidding_admin_deleted_not_available_for_prolong_featured_upgrade_dashboard_myprojects_po_view'] = 'project already deleted by admin not available for prolong featured upgrade';

$config['fulltime_project_open_for_bidding_admin_deleted_not_available_for_prolong_featured_upgrade_dashboard_myprojects_po_view'] = 'Fulltime project already deleted by admin not available for prolong featured upgrade';

$config['project_open_for_bidding_status_changed_not_available_for_prolong_featured_upgrade_dashboard_myprojects_po_view'] = 'project status has been changed not available for prolong featured upgrade';

$config['fulltime_project_open_for_bidding_status_changed_not_available_for_prolong_featured_upgrade_dashboard_myprojects_po_view'] = 'fulltime project status has been changed not available for prolong featured upgrade';

#####################################################################################

$config['project_featured_upgrade_txt_po_dashboard_myprojects_section_view'] = 'Featured Upgrade:';

$config['project_featured_upgrade_expires_on_txt_po_dashboard_myprojects_section_view'] = 'Expires on';

$config['project_featured_upgrade_prolong_availability_txt_po_dashboard_myprojects_section_view'] = 'Prolong Availability';

$config['project_urgent_upgrade_txt_po_dashboard_myprojects_section_view'] = 'Urgent Upgrade:';

$config['project_urgent_upgrade_expires_on_txt_po_dashboard_myprojects_section_view'] = 'Expires on';

$config['project_urgent_upgrade_prolong_availability_txt_po_dashboard_myprojects_section_view'] = 'Prolong Availability';

#####################################################################################

//For invite friend section on dashboard
$config['dashboard_invite_friends_section_heading'] = 'Invite Friends -> Grow Your Network -> Maximize Your Passive Income';

//For contact list section on dashboard
$config['dashboard_contact_list_section_heading'] = 'Contacts List';

//For latest project section on dashboard
$config['dashboard_latest_projects_section_heading'] = 'Latest Projects';
$config['dashboard_latest_projects_section_refresh_list_btn_txt'] = 'Refresh List';


// project status when project cancelled by admin on my projects section on dashboard
$config['dashboard_my_projects_section_project_status_cancelled_by_admin'] = 'Cancelled by Admin';

// texts from invite friends via email for dashboard 
//$config['dashboard_invite_friends_for_email_contacts'] = 'Email Contacts';
$config['dashboard_invite_friends_for_email_contacts'] = 'Add email addresses';

$config['dashboard_invite_friends_for_separate_with_spaces_commas'] = '(separate with spaces or commas)';

$config['dashboard_invite_friends_better_visibility_and_more_invitations'] = 'For better visibility and more invitations share your URL via the common networks below';

$config['dashboard_invite_friends_for_send_invitations_btn_txt'] = 'Send Invitations';


$config['dashboard_invite_friends_for_copy_url'] = 'Copy';

$config['dashboard_invite_friends_email_contacts_tooltip'] = 'EN - Po??lete e-mailov?? pozv??nky sv?? rodin??, p????tel??m, koleg??m, zam??stnanc??m, aby se p??ipojili k Travai.cz. Ka??d?? e-mail bude obsahovat va??i jedine??nou odkazovou adresu URL. Registrac?? na na??em webu prost??ednictv??m referen??n?? adresy URL obsa??en?? v e-mailu budou automaticky p??i??azeny k va???? referen??n?? s??ti. Co to pro v??s znamen?? (jak?? je v???? p????nos)? Poka??d??, kdy u??ivatel?? z va???? s??t?? nakupuj?? slu??by na Travai.cz, my (travai.cz) se s v??mi pod??l??me procentami (%) z p????jm?? ka??d??ho n??kupu slu??eb. Kolik kontakt?? m????ete pos??lat e-mailem s pozv??nkou, nen?? nijak omezeno. Ve va???? s??ti nen?? ????dn?? limit doporu??en??. ????m v??ce lid?? ve va???? s??ti doporu??en??, t??m vy?????? je ??ance na zv????en?? va??eho generovan??ho pasivn??ho p????jmu. D??sledn?? v??ak sledujeme ??innosti na??ich u??ivatel?? prov??d??n?? na na??em webu a v p????pad??, ??e na va??em ????tu zjist??me aktivity souvisej??c?? se SPAMMINGem, podnikneme n??pravn?? opat??en??.';


$config['dashboard_invite_friends_your_url_tooltip'] = 'EN - Toto je vyhrazen?? adresa URL pro v???? Travai ????et. Tento odkaz m????ete sd??let prost??ednictv??m nejzn??m??j????ch soci??ln??ch s??t?? nebo p????mo se svou rodinou, p????teli, kolegy, zam??stnanci, pomoc?? n??stroj??, jako je e-mail, skype, whatsapp atd.. <br>V okam??iku, kdy se zaregistruj?? na Travai prost??ednictv??m va??ich referen??n??ch adres URL (nebo pomoc?? va??eho jedine??n??ho referen??n??ho k??du - viz ????st ??ast?? dotazy / ???referen??n?? k??dy???), budou automaticky p??id??leny do va???? referen??n?? s??t??. Co to pro v??s znamen?? (jak?? je v???? p????nos)? Poka??d??, kdy u??ivatel?? z va???? s??t?? nakupuj?? slu??by na Travai.cz, my (travai.cz) se s v??mi pod??l??me procentami (%) z p????jm?? ka??d??ho n??kupu slu??eb. M????ete sd??let sv??j referen??n?? URL (nebo k??d) tolikr??t, kolikr??t chcete, s tolika lidmi, kolik si p??ejete. Ve va???? s??ti nen?? ????dn?? limit doporu??en??. V??ce lid?? ve va???? s??ti doporu??en??, je v??t???? ??ance na zv????en?? va??eho generovan??ho pasivn??ho p????jmu. M????ete z??skat generovan?? pasivn?? p????jem a?? ze 2 ??rovn?? doporu??en?? z va???? s??t?? (??rove??1 - va??e p????m?? doporu??en?? / ??rove??2 - doporu??en?? z ??rovn??1 doporu??en??) na z??klad?? ??lenstv?? p??edplatn?? (zdarma - pouze z ??rovn??1 / bronzov?? a vy?????? - ??rove??1 + ??rove??2). V??ce informac?? naleznete na str??nce ??ast?? dotazy.';


//For Dashboard Right Side Chat Section When No chat
$config['dashboard_contacts_list_no_record'] = '<h5>You do not have any contacts yet.</h5><p>You will have displayed here all the contacts that you will aquire over the portal.</p>';

?>