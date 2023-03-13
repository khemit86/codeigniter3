<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
//Left navigation menu name
$config['pa_user_left_nav_project_management'] = 'Projects Management';
$config['ca_user_left_nav_project_management'] = 'Projects Management Comp';

$config['projects_management_left_nav_my_projects'] = 'My Projects.';

################ Url Routing Variables for payments section on project detail page ###########

$config['my_projects_page_url'] = 'load_myprojects';
################ Meta Config Variables for project not exist 404 page ###########
/* Filename: application\modules\projects\vies\project_not_existent_404.php */

$config['project_not_existent_404_page_heading'] = 'EN - 404 Stránka je nedostupná...';
$config['project_not_existent_404_page_message_without_login'] = 'EN - Odkaz, na který jste klikli je nefunkční. Stránka inzerátu neexistuje.(logged off projects) to continue <a href="/">click here...</a>';

$config['project_not_existent_404_page_message_with_login'] = 'EN - Odkaz, na který jste klikli je nefunkční. Stránka inzerátu neexistuje.(logged in projects) to continue <a href="/">click here...</a>';

########## Meta config/ description for hidden project
$config['hidden_project_page_title_meta_tag'] = 'HIDDEN PROJECT - Access Denied!';
$config['hidden_project_page_description_meta_tag'] = 'HIDDEN PROJECT - Access Denied!';

$config['hidden_project_page_heading'] = 'Access Denied!';

$config['hidden_project_page_message_without_login'] = 'You are not allowed to access this page...(logged off ) to continue <a href="/">click here...</a>';

$config['hidden_project_page_message_with_login'] = 'You are not allowed to access this page...(logged in ) to continue <a href="/">click here...</a>';


##################################################################
$config['project_details_page_listing_id'] = 'EN ID:';

$config['project_details_page_history'] = 'History:';

$config['project_details_page_revisions'] = 'Revisions:';

$config['project_details_page_report_violation'] = 'Report Violation';

$config['project_details_page_views'] = 'views';

################ Url Routing Variables for edit project draft page ###########
/* Filename: application\modules\projects\controllers\Projects.php */
/* Filename: application\modules\dashboard\views\user_dashboard.php */
$config['edit_draft_project_page_url'] = 'edit-draft';

$config['edit_project_page_url'] = 'edit';

/* Filename: application\modules\projects\controllers\Projects.php */
/* Filename: application\modules\projects\views\edit_draft_project.php */
$config['preview_draft_project_page_url'] = 'preview-draft';

/* Filename: application\modules\projects\controllers\Projects.php */
$config['project_detail_page_url'] = 'project';

//these 2 work well
$config['edit_draft_project_page_heading'] = 'Edit Draft Project';
$config['edit_draft_fulltime_project_page_heading'] = 'Edit Draft Fulltime Project';

//the following 2 variables are used for edit project/fulltime heading
$config['edit_project_page_heading'] = 'Edit Project';
$config['edit_fulltime_project_page_heading'] = 'Edit Fulltime Project';

$config['refresh_page_validation_message'] = "An error occured. Please refresh the page.";

####################################################################################################################

//PROJECT ADDITIONAL INFORMATION LENGTH AND REQUIREMENTS

$config['project_details_page_additional_information'] = 'Additional Information';

$config['project_details_page_updated_on'] = 'Updated On:';

$config['project_apply_now_button_txt'] = 'Apply Now P';

$config['fulltime_project_apply_now_button_txt'] = 'Apply Now FP';

##################################################

//This variable is using when project is expired and po trying to upload/edit/save project covered picture
$config['project_details_page_featured_project_deleted_error_message'] = "project has been deleted. you cannot perform actions.";
$config['project_details_page_featured_fulltime_project_deleted_error_message'] = "fulltime project has been deleted. you cannot perform actions.";

$config['project_details_page_featured_project_status_changed_error_message'] = "Status has been changed of project. you cannot perform actions.";
$config['project_details_page_featured_fulltime_project_status_changed_error_message'] = "Status has been changed of fulltime project. you cannot perform actions.";


$config['project_details_page_expired_featured_project_upload_cover_picture_po_view_error_message'] = 'Your project has been expired';

$config['fulltime_project_details_page_expired_featured_project_upload_cover_picture_po_view_error_message'] = 'Your project has been expired';

###############################################################################################

//This varible is using when featured upgrade is expired and po trying to upload/edit/save project covered picture
$config['project_details_page_expired_featured_upgrade_upload_cover_picture_po_view_error_message'] = 'Your featured upgrade expired. PRJ';

$config['fulltime_project_details_page_expired_featured_upgrade_upload_cover_picture_po_view_error_message'] = 'Your featured upgrade expired. FT';


//ATTACHEMNTS NOT EXIST ERROR MESSAGES - PROJECT STATUS - OPEN FOR BIDDING (PO ON EDIT PROJECT PAGE)
$config['project_attachment_not_exist_validation_edit_project_page_message'] = "EDIT PROJECT - OPEN FOR BIDDING - ATTACHMENT NOT EXIST - An error has been occurred. Please remove the attachment and upload again!";

$config['fulltime_project_attachment_not_exist_validation_edit_project_page_message'] = "EDIT FULLTIME - OPEN FOR BIDDING - ATTACHMENT NOT EXIST - An error has been occurred. Please remove the attachment of fulltime project and try to upload again!";

############ Defined the variable for project detail page regarding the project attachment if its not exists
$config['deleted_open_project_attachment_not_exist_validation_project_detail_message_visitor_view'] = "Project has been deleted. So you cannot open the attachment";

//ATTACHEMNTS NOT EXIST ERROR MESSAGES - PROJECT STATUS - AWAITING MODERATION
$config['project_attachment_not_exist_validation_awaiting_moderation_status_page_message_project_owner_view_project'] = "Project Owner - Awaiting Moderation - PJ - Attachment not exists of project.So please upload again";

$config['project_attachment_not_exist_validation_awaiting_moderation_status_page_message_project_owner_view_fulltime_project'] = "Project Owner - Awaiting Moderation - Fulltime - Attachment not exists of fulltime project.";

##############

//ATTACHEMNTS NOT EXIST ERROR MESSAGES - PROJECT STATUS - OPEN FOR BIDDING
$config['project_attachment_not_exist_validation_open_for_bidding_status_page_message_project_owner_view_project'] = "open for bidding - project owner - project - Attachment is not exist of project please upload again.";

$config['project_attachment_not_exist_validation_open_for_bidding_status_page_message_project_owner_view_fulltime_project'] = "open for bidding - project owner - fulltime - Attachment is not exist of fulltime please upload again. ";

#############

//ATTACHEMNTS NOT EXIST ERROR MESSAGES - PROJECT STATUS - AWARDED
$config['project_attachment_not_exist_validation_awarded_project_status_page_message_project_owner_view'] = "PROJECT AWARDED - project owner - Project - Attachment is not exist of project.";

//ATTACHEMNTS NOT EXIST ERROR MESSAGES - PROJECT STATUS - IN PROGRESS
$config['project_attachment_not_exist_validation_in_progress_project_status_page_message_project_owner_view_project'] = "PROJECT INPROGRESS - Project Owner - Project - Attachment is not exist of project.";

$config['project_attachment_not_exist_validation_incomplete_project_status_page_message_project_owner_view_project'] = "PROJECT INCOMPLETE - Project Owner - Project - Attachment is not exist of project.";


//ATTACHEMNTS NOT EXIST ERROR MESSAGES - PROJECT STATUS - EXPIRED
$config['project_attachment_not_exist_validation_expired_project_status_page_message_project_owner_view_project'] = "PROJECT EXPIRED - Project Owner - Project - Attachment is not exist of project.";

$config['project_attachment_not_exist_validation_expired_project_status_page_message_project_owner_view_fulltime_project'] = "PROJECT EXPIRED - Project Owner - Fulltime Project - Attachment is not exist of fulltime project.";


//ATTACHEMNTS NOT EXIST ERROR MESSAGES - PROJECT STATUS - CANCELLED
$config['project_attachment_not_exist_validation_cancelled_project_status_page_message_project_owner_view_project'] = "PROJECT CANCELLED - Project Owner - Project - Attachment is not exist of project.";

$config['project_attachment_not_exist_validation_cancelled_project_status_page_message_project_owner_view_fulltime_project'] = "PROJECT CANCELLED - Project Owner - FULLTIME Project - Attachment is not exist of fulltime project.";


//ATTACHEMNTS NOT EXIST ERROR MESSAGES - PROJECT STATUS - COMPLETED
$config['project_attachment_not_exist_validation_completed_project_status_page_message_project_owner_view_project'] = "PROJECT COMPLETED - Project Owner - Project - Attachment is not exist of project.";


//ATTACHEMNTS NOT EXIST ERROR MESSAGES - VISITOR VIEW
$config['project_attachment_not_exist_validation_project_detail_page_message_visitor_view_project'] = "Project - SP - BIDDER - VISITOR - LOGGED IN / LOGGED OUT - Attachment not exists of project.";

$config['project_attachment_not_exist_validation_project_detail_page_message_visitor_view_fulltime_project'] = "FullTime - SP - BIDDER - VISITOR - LOGGED IN / LOGGED OUT - Attachment not exists of fulltime project.";

################ Defined the featured project cover picture validation regarding on project detail page(open for bidding status)
/* Filename: application\modules\projects\views\open_for_bidding_project_detail.php */

$config['featured_project_cover_picture_maximum_size_validation_message'] = "FP CP Max allowed size is {featured_project_cover_picture_max_file_size_mb} MB";

$config['invalid_featured_project_cover_picture_validation_message'] = "Invalid Picture. Upload the cover picture again.";

$config['featured_project_cover_picture_extension_validation_message'] = "The file type you are trying to upload is not allowed!";

$config['featured_project_cover_picture_size_validation_message'] = "Image size must be of at least {max_width}x{max_height}";

################ Defined the variable for user display activity for proejct and upgrade expired
$config['project_expired_user_activity_log_displayed_message_sent_to_po'] = 'Your project "<a href="{project_url_link}" target="_blank">{project_title}</a>" has expired on {project_expiration_date}. You can find it and continue to manage it from Expired tab of My Projects section.';

$config['fulltime_project_expired_user_activity_log_displayed_message_sent_to_po'] = 'Your fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>" has expired on {project_expiration_date}. You can find it and continue to manage it from Expired tab of My Projects section.';

#################################user activity log messages when user upgrade project -> These config are using when featured/urgent upgrade expired and in the table "serv_projects_open_bidding" the featured/urgent columns value is "Y".
//Then cron checking the expiration time of upgrade and switch the value of featured/urgent columns value "Y" to "N" and use above config for showing message to user//
$config['project_featured_upgrade_expired_user_activity_log_displayed_message_sent_to_po'] = 'PROJECT - Availability of featured upgrade of your project "<a href="{project_url_link}" target="_blank">{project_title}</a>" has expired on {featured_upgrade_expiration_date}.';

$config['fulltime_project_featured_upgrade_expired_user_activity_log_displayed_message_sent_to_po'] = 'FT-Availability of featured upgrade of your fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>" has expired on {featured_upgrade_expiration_date}.';


$config['project_urgent_upgrade_expired_user_activity_log_displayed_message_sent_to_po'] = 'PROJECT - Availability of urgent upgrade of your project "<a href="{project_url_link}" target="_blank">{project_title}</a>" has expired on {urgent_upgrade_expiration_date}.';

$config['fulltime_project_urgent_upgrade_expired_user_activity_log_displayed_message_sent_to_po'] = 'FT-Availability of urgent upgrade of your fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>" has expired on {urgent_upgrade_expiration_date}.';

################
//ACTIVITY LOG MESSAGES POSTED TO USERS
$config['project_submited_by_po_for_awaiting_moderation_user_activity_log_displayed_message_sent_to_po'] = 'Your project "<a href="{project_url_link}" target="_blank">{project_title}</a>" was successfully published for awaiting moderation.';

$config['fulltime_project_submited_by_po_for_awaiting_moderation_user_activity_log_displayed_message_sent_to_po'] = 'Your fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>" was successfully published for awaiting moderation.';

// this message will be displayed when project in awaiting moderation is auto approved by node cron
$config['project_auto_approve_user_activity_log_displayed_message_sent_to_po'] = 'Your project "<a href="{project_url_link}" target="_blank">{project_title}</a>" was successfully published. - node';

$config['fulltime_project_auto_approve_user_activity_log_displayed_message_sent_to_po'] = 'Your fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>" was successfully published. - node';


// this message will be displayed when admin approves the project in awaiting moderation
$config['project_approved_by_admin_user_activity_log_displayed_message_sent_to_po'] = 'Your project "<a href="{project_url_link}" target="_blank">{project_title}</a>" was successfully published. - admin';

$config['fulltime_project_approved_by_admin_user_activity_log_displayed_message_sent_to_po'] = 'Your fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>" was successfully published. - admin';

// This variable will use when we set auto approval time min/max to 00:00:00
$config['post_project_directly_move_open_bidding_user_activity_log_displayed_message_sent_to_po'] = 'Your project "<a href="{project_url_link}" target="_blank">{project_title}</a>" has been published.';

$config['post_fulltime_project_directly_move_open_bidding_user_activity_log_displayed_message_sent_to_po'] = 'Your fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>" has been published.';

// this message will be displayed when admin reject the project in awaiting moderation - TESTED OK 05.07.2019
$config['project_rejected_by_admin_user_activity_log_displayed_message_sent_to_po'] = 'Your project "{project_title}" was not approved to be published. Please review it, and re-submit it for approval.';

$config['fulltime_project_rejected_by_admin_user_activity_log_displayed_message_sent_to_po'] = 'Your fulltime project "{project_title}" was not approved to be published. Please review it, and re-submit it for approval.';


//this message will be displayed when admin deletes the project in awaiting moderation
$config['project_deleted_by_admin_user_activity_log_displayed_message_sent_to_po'] = 'Your project "{project_title}" was deleted by admin.';

$config['fulltime_project_deleted_by_admin_user_activity_log_displayed_message_sent_to_po'] = 'Your fulltime project "{project_title}" was deleted by admin.';

//this message will be displayed when admin cancelled the project from open for bidding status
$config['project_cancelled_by_admin_open_for_bidding_user_activity_log_displayed_message_sent_to_po'] = 'Your project "<a href="{project_url_link}" target="_blank">{project_title}</a>" was cancelled by admin. Should you have any questions, feel free to contact xxxx@domain.com.';

$config['fulltime_project_cancelled_by_admin_open_for_bidding_user_activity_log_displayed_message_sent_to_po'] = 'Your fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>" was cancelled by admin. Should you have any questions, feel free to contact xxxx@domain.com.';


// This message will display when po cancel project from open for bidding status
$config['project_cancelled_by_po_male_open_for_bidding_user_activity_log_displayed_message_sent_to_po'] = 'Male : you cancelled project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['project_cancelled_by_po_female_open_for_bidding_user_activity_log_displayed_message_sent_to_po'] = 'Female : you cancelled project "<a href="{project_url_link}" target="_blank">{project_title}</a>".';

$config['project_cancelled_by_po_company_app_male_open_for_bidding_user_activity_log_displayed_message_sent_to_po'] = 'AppMale : you cancelled project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['project_cancelled_by_po_company_app_female_open_for_bidding_user_activity_log_displayed_message_sent_to_po'] = 'AppFemale : you cancelled project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['project_cancelled_by_po_company_open_for_bidding_user_activity_log_displayed_message_sent_to_po'] = 'Company : you cancelled project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_cancelled_by_po_male_open_for_bidding_user_activity_log_displayed_message_sent_to_po'] = 'Male : you cancelled fulltime job "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_cancelled_by_po_female_open_for_bidding_user_activity_log_displayed_message_sent_to_po'] = 'Female : you cancelled fulltime job "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_cancelled_by_po_company_app_male_open_for_bidding_user_activity_log_displayed_message_sent_to_po'] = 'AppMale : you cancelled fulltime job "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_cancelled_by_po_company_app_female_open_for_bidding_user_activity_log_displayed_message_sent_to_po'] = 'AppFemale : you cancelled fulltime job "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_cancelled_by_po_company_open_for_bidding_user_activity_log_displayed_message_sent_to_po'] = 'Company : you cancelled fulltime job "<a href="{project_url_link}" target="_blank">{project_title}</a>"';


//this message will be displayed when admin cancelled the project from expired status
$config['expired_project_cancelled_by_admin_user_activity_log_displayed_message_sent_to_po'] = 'Your project "<a href="{project_url_link}" target="_blank">{project_title}</a>" was cancelled by admin. Should you have any questions, feel free to contact xxxx@domain.com.';

$config['fulltime_expired_project_cancelled_by_admin_user_activity_log_displayed_message_sent_to_po'] = 'Employer->Expired:Your fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>" was canceleld by admin. Should you have any questions, feel free to contact xxxx@domain.com.';

// This message will display when po cancel project from expired status
$config['project_cancelled_by_po_male_expired_user_activity_log_displayed_message_sent_to_po'] = 'Male : you cancelled project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['project_cancelled_by_po_female_expired_user_activity_log_displayed_message_sent_to_po'] = 'Female : you cancelled project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['project_cancelled_by_po_company_app_male_expired_user_activity_log_displayed_message_sent_to_po'] = 'AppMale : you cancelled project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['project_cancelled_by_po_company_app_female_expired_user_activity_log_displayed_message_sent_to_po'] = 'App Female : you cancelled project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['project_cancelled_by_po_company_expired_user_activity_log_displayed_message_sent_to_po'] = 'Company : you cancelled project "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_cancelled_by_po_male_expired_user_activity_log_displayed_message_sent_to_po'] = 'Male : you cancelled fulltime job "<a href="{project_url_link}" target="_blank">{project_title}</a>"';
$config['fulltime_project_cancelled_by_po_female_expired_user_activity_log_displayed_message_sent_to_po'] = 'Female : you cancelled fulltime job "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_cancelled_by_po_company_app_male_expired_user_activity_log_displayed_message_sent_to_po'] = 'Male App : you cancelled fulltime job "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_cancelled_by_po_company_app_female_expired_user_activity_log_displayed_message_sent_to_po'] = 'Female App : you cancelled fulltime job "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

$config['fulltime_project_cancelled_by_po_company_expired_user_activity_log_displayed_message_sent_to_po'] = 'Company : you cancelled fulltime job "<a href="{project_url_link}" target="_blank">{project_title}</a>"';

// This variable will use when user save project as draft / remove draft project from list
$config['post_project_save_as_draft_user_activity_log_displayed_message_sent_to_po'] = 'Your project "{project_title}" has been saved as draft.';

$config['fulltime_post_project_save_as_draft_user_activity_log_displayed_message_sent_to_po'] = 'Your fulltime project "{project_title}" has been saved as draft.';

$config['remove_draft_user_activity_log_displayed_message_sent_to_po'] = 'You removed project "{project_title}" from draft.';

$config['fulltime_remove_draft_user_activity_log_displayed_message_sent_to_po'] = 'You removed fulltime project "{project_title}" from draft.';


//project refresh user activity log messages
// This message is for standard and sealed project refresh
$config['standard_or_sealed_project_refresh_user_activity_log_displayed_message_sent_to_po'] = 'Your project "<a href="{project_url_link}" target="_blank">{project_title}</a>" has been successfully refreshed on top of projects list.';

$config['fulltime_standard_or_sealed_project_refresh_user_activity_log_displayed_message_sent_to_po'] = 'Your fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>" has been successfully refreshed on top of projects list.'; 

// This message is for featured project refresh
$config['featured_project_refresh_user_activity_log_displayed_message_sent_to_po'] = 'Your <b>Featured</b> project "<a href="{project_url_link}" target="_blank">{project_title}</a>" has been successfully refreshed on top of projects list.';

$config['fulltime_featured_project_refresh_user_activity_log_displayed_message_sent_to_po'] = 'Your <b>Featured</b> fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>" has been successfully refreshed on top of projects list.';


// This message is for urgent project refresh
$config['urgent_project_refresh_user_activity_log_displayed_message_sent_to_po'] = 'Your <b>Urgent</b> project "<a href="{project_url_link}" target="_blank">{project_title}</a>" has been successfully refreshed on top of projects list.';

$config['fulltime_urgent_project_refresh_user_activity_log_displayed_message_sent_to_po'] = 'Your <b>Urgent</b> fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>" has been successfully refreshed on top of projects list.';


// this message will be displayed untill next refresh time for any project type is not null
$config['project_next_refresh_user_activity_log_displayed_message_sent_to_po'] = 'Next refresh will happen on <b>{next_refresh_time}</b>.';


$config['fulltime_project_next_refresh_user_activity_log_displayed_message_sent_to_po'] = 'Next refresh of fulltime project will happen on <b>{next_refresh_time}</b>.';


//FEATURED UPGRADE

// config is using when po upgrade his project as featured from my project section  "open for bidding" tab
$config['project_featured_upgrade_user_activity_log_displayed_message_sent_to_po'] = 'You have upgraded your project "<a href="{project_url_link}" target="_blank">{project_title}</a>" as Featured, for <span>{project_featured_upgrade_price}</span>. The expiration of your upgrade is {project_featured_upgrade_expiration_date}.';

$config['fulltime_project_featured_upgrade_user_activity_log_displayed_message_sent_to_po'] = 'You have upgraded your fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>" as Featured, for <span>{project_featured_upgrade_price}</span>. The expiration of your upgrade is {project_featured_upgrade_expiration_date}.';

// config is using when po prolong his project as featured from my project section  "open for bidding" tab
$config['project_featured_upgrade_prolong_user_activity_log_displayed_message_sent_to_po'] = 'Dostupnost vylepšení <strong>ZVÝRAZNĚNÝ</strong> inzerátu projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>", je prodloužený o další <span>{project_featured_upgrade_prolong_availability}</span>, za <span>{project_featured_upgrade_price}</span>. Datum další expirace {project_featured_upgrade_prolong_next_expiration_date}.';

$config['fulltime_project_featured_upgrade_prolong_user_activity_log_displayed_message_sent_to_po'] = 'FT-Dostupnost vylepšení <strong>ZVÝRAZNĚNÝ</strong> inzerátu pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>", je prodloužený o další <span>{project_featured_upgrade_prolong_availability}</span>, za <span>{project_featured_upgrade_price}</span>. Datum další expirace {project_featured_upgrade_prolong_next_expiration_date}.';

//URGENT UPGRADE

// config is using when po upgrade his project as urgent from my project section  "open for bidding" tab
$config['project_urgent_upgrade_user_activity_log_displayed_message_sent_to_po'] = 'You have upgraded your project "<a href="{project_url_link}" target="_blank">{project_title}</a>" as Urgent, for <span>{project_urgent_upgrade_price}</span>. The expiration of your upgrade is {project_urgent_upgrade_expiration_date}.';

$config['fulltime_project_urgent_upgrade_user_activity_log_displayed_message_sent_to_po'] = 'You have upgraded your fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>" as Urgent, for <span>{project_urgent_upgrade_price}</span>. The expiration of your upgrade is {project_urgent_upgrade_expiration_date}.';


// config is using when po prolong his project as urgent from my project section  "open for bidding" tab
$config['project_urgent_upgrade_prolong_user_activity_log_displayed_message_sent_to_po'] = 'Dostupnost vylepšení <strong>URGENTNÍ</strong> inzerátu projektu "<a href="{project_url_link}" target="_blank">{project_title}</a>", je prodloužený o další <span>{project_urgent_upgrade_prolong_availability}</span>, za <span>{project_urgent_upgrade_price}</span>. Datum další expirace {project_urgent_upgrade_prolong_next_expiration_date}.';

$config['fulltime_project_urgent_upgrade_prolong_user_activity_log_displayed_message_sent_to_po'] = 'FT-Dostupnost vylepšení <strong>URGENTNÍ</strong> inzerátu pracovní pozice "<a href="{project_url_link}" target="_blank">{project_title}</a>", je prodloužený o další <span>{project_urgent_upgrade_prolong_availability}</span>, za <span>{project_urgent_upgrade_price}</span>. Datum další expirace {project_urgent_upgrade_prolong_next_expiration_date}.';


//Define the config variables for project detail page
$config['project_details_page_preview'] = 'Preview';
$config['project_details_page_draft'] = 'Draft Preview';

//projects types
$config['project_details_page_project_details'] = 'Project Details';
$config['project_details_page_fulltime_details'] = 'Fulltime Details';


// project statuses
$config['project_status_awaiting_moderation'] = 'Awaiting Moderation';

$config['project_status_expired'] = 'Expired';

$config['project_status_open_for_bidding'] = 'Open For Bidding';

$config['project_status_awarded'] = 'Awarded';

$config['project_status_in_progress'] = 'In Progress';

$config['project_status_completed'] = 'Completed';

$config['project_status_incomplete'] = 'Incomplete';

$config['project_status_cancelled'] = 'Cancelled';

$config['project_status_cancelled_by_admin'] = 'Cancelled By Admin';


//project details page
$config['project_details_page_posted_on'] = 'Posted On:';

$config['project_details_page_completed_on'] = 'Completed On:';

$config['project_details_page_cancelled_on'] = 'Cancelled On:';

$config['project_details_page_time_left'] = 'Time left:';

$config['project_details_page_expires_on'] = 'Expires on:';

$config['project_details_page_expired_on'] = 'Expired on:';

$config['project_details_page_project_type'] = 'Project type:';

$config['project_details_page_fixed_budget'] = 'Fixed Budget';

$config['project_details_page_hourly_budget'] = 'Hourly Rate Budget';

$config['project_details_page_payment_method'] = 'Payment Method:';

$config['project_details_page_location'] = 'Location:';

$config['project_details_page_project_bid_history'] = 'Bid history:';

$config['project_details_page_fulltime_bid_history'] = 'Applications history:';

$config['project_details_page_project_budget'] = 'Project Budget:';

$config['project_details_page_fulltime_project'] = 'Fulltime Job';

$config['project_details_page_fulltime_salary'] = 'Salary:';

$config['project_details_page_fulltime_project_description'] = 'Fulltime Job Description';
$config['project_details_page_project_description'] = 'Description';

$config['project_details_page_project_owner_details'] = 'Project Owner';

$config['project_details_page_fulltime_employer_details'] = 'Employer';

$config['project_details_page_project_bidders_list_singular'] = 'Bidder';
$config['project_details_page_project_bidders_list_plural'] = 'Bidders';

$config['fulltime_project_details_page_applicants_list_singular'] = 'Application';
$config['fulltime_project_details_page_applicants_list_plural'] = 'Applications';

#####config of project payment method for project detail page
$config['project_details_page_payment_method_escrow_system'] = 'via portal';
$config['project_details_page_payment_method_offline_system'] = 'outside portal';

#####config of project payment method for find project page/latest projects section on dashboard
$config['find_projects_project_payment_method_escrow_system'] = ' - Payment Method: via portal';
$config['find_projects_project_payment_method_offline_system'] = ' - Payment Method: outside portal';

#####config of project payment method for my projects section on dashboard/dedicated page
$config['my_projects_project_payment_method_escrow_system'] = ' - Payment Method: via portal';
$config['my_projects_project_payment_method_offline_system'] = ' - Payment Method: outside portal';

#####config of project payment method for user profile page
$config['po_profile_page_project_payment_method_escrow_system'] = ' - Payment Method: via portal';
$config['po_profile_page_project_payment_method_offline_system'] = ' - Payment Method: outisde portal';

##############################################################################################################
$config['project_details_page_fulltime_project_continue_editing_button_txt'] = 'Continue Editing Fulltime';
$config['project_details_page_project_continue_editing_button_txt'] = 'Continue Editing';

// used on find project page / latest projects / my projects section / project detail page
$config['project_description_snippet_bid_history_0_bids_received'] = '(0) bids r';
$config['project_description_snippet_bid_history_1_bid_received'] = '(1) bid r';
$config['project_description_snippet_bid_history_2_to_4_bids_received'] = '(2-4) bids r';
$config['project_description_snippet_bid_history_5_or_more_bids_received'] = '(5+) bids r';

$config['fulltime_project_description_snippet_bid_history_0_applications_received'] = '(0) applications r';
$config['fulltime_project_description_snippet_bid_history_1_application_received'] = '(1) application r';
$config['fulltime_project_description_snippet_bid_history_2_to_4_applications_received'] = '(2-4) applications r';
$config['fulltime_project_description_snippet_bid_history_5_or_more_applications_received'] = '(5+) applications r';

########## these config are using to show number of hires for project on my projects section for PO on dashboard/my project page
// Hired snippet for project description
$config['project_description_snippet_hire_history_0_sps_hired'] = '(0) hires';
$config['project_description_snippet_hire_history_1_sp_hire'] = '(1) hire';
$config['project_description_snippet_hire_history_2_to_4_sps_hired'] = '(2-4) hires';
$config['project_description_snippet_hire_history_5_or_more_sps_hired'] = '(5+) hires';

// Hired snippet for fulltime project description
$config['fulltime_project_description_snippet_hire_history_0_employees_hired'] = '(0) employed';
$config['fulltime_project_description_snippet_hire_history_1_employee_hire'] = '(1) employed';
$config['fulltime_project_description_snippet_hire_history_2_to_4_employees_hired'] = '(2-4) employed';
$config['fulltime_project_description_snippet_hire_history_5_or_more_employees_hired'] = '(5+) employed';

$config['project_details_page_project_awaiting_acceptance_bid_list_singular'] = 'Awarded Bid';
$config['project_details_page_project_awaiting_acceptance_bid_list_plural'] = 'Awarded Bids';

$config['project_details_page_project_in_progress_bids_list_singular'] = 'In Progress Bid';
$config['project_details_page_project_in_progress_bids_list_plural'] = 'In Progress Bids';


$config['project_details_page_project_completed_bids_list_singular'] = 'Completed Bid';
$config['project_details_page_project_completed_bids_list_plural'] = 'Completed Bids';

$config['project_details_page_project_incomplete_bids_list_singular'] = 'Incomplete Bid';
$config['project_details_page_project_incomplete_bids_list_plural'] = 'Incomplete Bids';


$config['project_details_page_project_active_disputed_bids_list_singular'] = 'Active Disputed Bid';
$config['project_details_page_project_active_disputed_bids_list_plural'] = 'Active Disputed Bids';

$config['fulltime_project_details_page_active_disputed_applications_list_singular'] = 'Active Disputed Application';
$config['fulltime_project_details_page_active_disputed_applications_list_plural'] = 'Active Disputed Applications';

##############################################################################################

/* config variables for my project section(dashboard and dedicated my project page) also used on user profile page */
$config['project_posted_on'] = '<span class="project_posted_on_color">Posted On:</span>';

$config['fulltime_project_posted_on'] = '<span class="project_posted_on_color">Fulltime Posted On:</span>';

// config is using on active bids tabs/awarded bid tab on my projects
$config['fulltime_project_cancelled_on_sp_view_myprojects_section'] = '<span class="cancelled_on_sp_view_myprojects_section_color">FT-Cancelled:</span>';

$config['bid_awarded_on'] = '<span class="bid_awarded_on_color">Awarded On:</span>';

$config['my_projects_sp_view_awarded_bid_expiration_time'] = '<span class="awarded_bid_expiration_time_color">Award Expiration Date:</span>';

$config['bid_date'] = '<span class="bid_date_color">Bid Date:</span>';
$config['application_date'] = '<span class="application_date_color">Application Date:</span>';

$config['project_start_date'] = '<span class="project_start_date_color">Project Start Date:</span>';
$config['project_completion_date'] = '<span class="completion_date_color">Project Completion Date:</span>';

$config['project_save_as_draft_date'] = '<span class="save_as_draft_date_color">Project Save As Draft Date:</span>';
$config['fulltime_project_save_as_draft_date'] = '<span class="fulltime_save_as_draft_date_color">Fulltime Save As Draft Date:</span>';

$config['project_submission_date_for_moderation'] = '<span class="project_submission_date_for_moderation_on_color">Project Submission Date For Moderation:</span>';
$config['fulltime_project_submission_date_for_moderation'] = '<span class="fulltime_project_submission_date_for_moderation_on_color">Fulltime Submission Date For Moderation:</span>';

$config['project_expired_on'] = '<span class="expired_on_color">Project Expired On:</span>';
$config['fulltime_project_expired_on'] = '<span class="fulltime_expired_on_color">Fulltime Expired On:</span>';

$config['project_cancelled_by_admin_on'] = '<span class="cancelled_by_admin_on_color">Project cancelled By Admin On:</span>';
$config['fulltime_project_cancelled_by_admin_on'] = '<span class="fulltime_cancelled_by_admin_on_color">Fulltime cancelled By Admin On:</span>';

$config['project_cancelled_by_po_on'] = '<span class="cancelled_by_po_on_color">Project cancelled On:</span>';
$config['fulltime_project_cancelled_by_po_on'] = '<span class="fulltime_cancelled_by_po_on_color">Fulltime cancelled On:</span>';

##############################################################################################

// Config used on find project page / latest projects / my projects section/profile page(All project status except in progress)
$config['project_listing_window_snippet_fixed_budget_project'] = '<span><small class="fixed_budget_color">Project - Fixed budget</small> - Budget:</span>';

$config['project_listing_window_snippet_hourly_based_budget_project'] = '<span><small class="hourly_based_budget_project_color">Project - Hourly Based</small> - Hourly Budget:</span>';

$config['project_listing_window_snippet_fulltime_project'] = '<span><small class="fulltime_job_color">Fulltime Job</small> - Salary:</span>';

################ Defined variable for user dashboard my project section Actions drop down
// Options for draft tab in my projects section
$config['myprojects_section_draft_tab_option_remove_draft_po_view'] = 'Remove Draft';
$config['myprojects_section_draft_tab_option_edit_draft_po_view'] = 'Edit Draft';
$config['myprojects_section_draft_tab_option_publish_project_po_view'] = 'Publish Project';


// Options for open for biddind tab in my projects section
$config['myprojects_section_open_for_bidding_tab_option_upgrade_project_po_view'] = 'Upgrade Project';
$config['myprojects_section_open_for_bidding_tab_option_upgrade_as_featured_project_po_view'] = 'Upgrade As Featured';
$config['myprojects_section_open_for_bidding_tab_option_upgrade_as_urgent_project_po_view'] = 'Upgrade As Urgent';
$config['myprojects_section_open_for_bidding_tab_option_copy_into_new_project_po_view'] = 'Copy into new';
$config['myprojects_section_open_for_bidding_tab_option_cancel_project_po_view'] = 'Cancel Project';
$config['myprojects_section_open_for_bidding_tab_option_edit_project_po_view'] = 'Edit Project';

// Options for awarded tab in my projects section
$config['myprojects_section_awarded_tab_option_copy_into_new_project_po_view'] = 'Awarded-Copy into new';

// Options for in progress tab in my projects section
$config['myprojects_section_in_progress_tab_option_copy_into_new_project_po_view'] = 'In progress-Copy into new';

// Options for incomplete tab in my projects section
$config['myprojects_section_incomplete_tab_option_copy_into_new_project_po_view'] = 'Incomplete-Copy into new';

// Options for completed tab in my projects section
$config['myprojects_section_completed_tab_option_copy_into_new_project_po_view'] = 'completed-Copy into new';

// Options for expired tab in my projects section
$config['myprojects_section_expired_tab_option_repost_project_po_view'] = 'Repost Project';
$config['myprojects_section_expired_tab_option_cancel_project_po_view'] = 'Cancel Project';


// Options for cancelled tab in my projects section
$config['myprojects_section_cancelled_tab_option_copy_into_new_project_po_view'] = 'Copy into new';
$config['myprojects_section_cancelled_tab_option_repost_project_po_view'] = 'Repost Project';

##########################################################################################

// variable for my project section, project cancellation process on dashboard
$config['cancel_open_for_bidding_project_modal_body'] = 'OPEN PROJECT - Confirm that you want to cancel project "{project_title}"?';

//variable for my project section, fulltime project cancellation process on dashboard for open for bidding fulltime project (cancel popup model)
$config['cancel_open_for_bidding_fulltime_project_modal_body'] = 'Cancel open fulltime - Confirm that you want to cancel fulltime project "{project_title}" ?';

####################################################################################################
// variable for my project section, project cancellation process on dashboard for expired project(cancel popup model)
$config['cancel_expired_project_modal_body'] = 'Expired Project - Please confirm you want to cancel expired project "{project_title}"';
$config['cancel_expired_fulltime_project_modal_body'] = 'Please confirm you want to cancel expired fulltime project "{project_title}"';

###############################################################################################

//featured project - cover picture management
$config['project_detail_page_upload_cover_picture_btn_txt'] = 'Upload Cover Picture';
$config['project_detail_page_upload_new_cover_picture_btn_txt'] = 'Upload New Cover Picture';

###############################################################################################
//project details page log off version - bottom banner
$config['project_detail_page_hire_freelancer_btn_txt'] = 'hire';
$config['project_detail_page_register_freelancer_btn_txt'] = 'register';

$config['project_detail_page_start_working_right_now_txt'] = 'Start Working Right Now';
$config['project_detail_page_get_best_freelancer_just_minutes_txt'] = 'Get the best in just minutes';

###############################################################################################

//project details page - text used for notification bell activity
$config['project_details_page_tooltip_subscribe_to_po_new_projects_posted_notifications_txt'] = 'Subscribe to receive notifications each time {user_first_name_last_name_or_company_name} publish a new project';

$config['project_details_page_tooltip_unsubscribe_to_po_new_projects_posted_notifications_txt'] = 'You are already subscribed to receive notifications each time {user_first_name_last_name_or_company_name} publish a new project';

#########################################################################################################

//project upgrades popups modals texts
#########config for FEATURED upgrade ########
$config['project_upgrade_popup_featured_upgrade_heading'] = 'prj - Vylepšit inzerát "{project_title}" jako ZVÝRAZNĚNÝ na  {project_featured_upgrade_availability}!';
$config['project_upgrade_popup_featured_upgrade_description'] = "PRJ - Inzerát bude označen jako <strong>ZVÝRAZNĚNÝ</strong>. Zvýrazněné inzeráty přitahují větší počet kvalitních nabídek. Zobrazují se prominentně na stránce pracovní pozice a projekty se žlutým posadím a specifickým označením. Aktualizace probíhá každé 3 dny, aby zůstal inzerát na vrcholu stránky pracovní pozice a projekty.";


$config['fulltime_project_upgrade_popup_featured_upgrade_heading'] = 'ft - Vylepšit inzerát "{project_title}" jako ZVÝRAZNĚNÝ na {project_featured_upgrade_availability}!';
$config['fulltime_project_upgrade_popup_featured_upgrade_description'] = "fulltime - Inzerát bude označen jako <strong>ZVÝRAZNĚNÝ</strong>. Zvýrazněné inzeráty přitahují větší počet kvalitních nabídek. Zobrazují se prominentně na stránce pracovní pozice a projekty se žlutým posadím a specifickým označením. Aktualizace probíhá každé 3 dny, aby zůstal inzerát na vrcholu stránky pracovní pozice a projekty.";


#########config for URGENT upgrade ########

$config['project_upgrade_popup_urgent_upgrade_heading'] = 'prj - Vylepšit inzerát "{project_title}" jako URGENTNÍ na  {project_urgent_upgrade_availability}!';
$config['project_upgrade_popup_urgent_upgrade_description'] = "PRJ - Inzerát bude označen jako <strong>URGENTNÍ</strong>. Získáte rychlejší odpověď od odborníků, aby váš projekt začal v co nejkratší době! Aktualizace probíhá každý den, aby zůstal inzerát na vrcholu stránky pracovní pozice a projekty.";

$config['fulltime_project_upgrade_popup_urgent_upgrade_heading'] = 'ft - Vylepšit inzerát "{project_title}" jako URGENTNÍ na  {project_urgent_upgrade_availability}!';
$config['fulltime_project_upgrade_popup_urgent_upgrade_description'] = "fulltime - Inzerát bude označen jako <strong>URGENTNÍ</strong>. Získáte rychlejší odpověď od odborníků, aby váše pracovní pozice byla obsazena v co nejkratší době! Aktualizace probíhá každý den, aby zůstal inzerát na vrcholu stránky pracovní pozice a projekty.";

#########################################################################################################

######## upgrade popup cancel button txt
$config['project_upgrade_modal_proceed_btn_txt'] = 'proceed with upgrade';
$config['project_upgrade_prolong_modal_proceed_btn_txt'] = 'proceed with prolong';

####################################################################################################

#########config for FEATURED upgrade prolong########
$config['project_upgrade_popup_prolong_featured_upgrade_heading'] = 'prj - Prodloužit inzerát "{project_title}" jako ZVÝRAZNĚNÝ o dalších {project_featured_upgrade_prolong_availability} za {project_featured_upgrade_price}!';
$config['project_upgrade_popup_prolong_featured_upgrade_description'] = 'prj - Aktuální platnost <strong>ZVÝRAZNĚNÝ</strong> inzerátu vyprší <strong>{featured_upgrade_availability_expire_date}</strong>. Pokud platnost nyní prodloužíte, datum příští expirace bude <strong>{featured_upgrade_availability_extended_date}</strong>.';


$config['fulltime_project_upgrade_popup_prolong_featured_upgrade_heading'] = 'ft - Prodloužit inzerát "{project_title}" jako ZVÝRAZNĚNÝ o dalších {project_featured_upgrade_prolong_availability} za {project_featured_upgrade_price}!';
$config['fulltime_project_upgrade_popup_prolong_featured_upgrade_description'] = 'ft - Aktuální platnost <strong>ZVÝRAZNĚNÝ</strong> inzerátu vyprší <strong>{featured_upgrade_availability_expire_date}</strong>. Pokud platnost nyní prodloužíte, datum příští expirace bude <strong>{featured_upgrade_availability_extended_date}</strong>.';

#########config for URGENT upgrade prolong########
$config['project_upgrade_popup_prolong_urgent_upgrade_heading'] = 'prj - Prodloužit inzerát "{project_title}" jako URGENTNÍ o dalších {project_urgent_upgrade_prolong_availability} za {project_urgent_upgrade_price}!';
$config['project_upgrade_popup_prolong_urgent_upgrade_description'] = 'prj - Aktuální platnost <strong>URGENTNÍ</strong> inzerátu vyprší <strong>{urgent_upgrade_availability_expire_date}</strong>. Pokud platnost nyní prodloužíte, datum příští expirace bude <strong>{urgent_upgrade_availability_extended_date}</strong>.';


$config['fulltime_project_upgrade_popup_prolong_urgent_upgrade_heading'] = 'ft - Prodloužit inzerát "{project_title}" jako URGENTNÍ o dalších {project_urgent_upgrade_prolong_availability} za {project_urgent_upgrade_price}!';
$config['fulltime_project_upgrade_popup_prolong_urgent_upgrade_description'] = 'ft - Aktuální platnost <strong>URGENTNÍ</strong> inzerátu vyprší <strong>{urgent_upgrade_availability_expire_date}</strong>. Pokud platnost nyní prodloužíte, datum příští expirace bude <strong>{urgent_upgrade_availability_extended_date}</strong>.';

#####################################################################################################

//RED WARNING FOR INSUFFICIENT FUNDS FOR PROJECT UPGRADE for already posted projects (from my projects section/page)
// This variable is used to check user account balance with project upgrade price and if price doesn't cover account balance then this message will be displayed (either featured or urgent).

$config['user_upgrade_project_insufficient_funds_error_message_singular'] = 'Váš aktuální zůstatek na účtu nepokrývá poplatek za vybranou službu.';

// This variable is used to check user account balance with project upgrade price and if price doesn't cover account balance then this message will be displayed(featured and urgent both).
$config['user_upgrade_project_insufficient_funds_error_message_plural'] = 'Váš aktuální zůstatek na účtu nepokrývá poplatek za vybrané služby.';


//RED WARNING FOR INSUFFICIENT FUNDS FOR PROLONG PROJECT UPGRADE
// This variable is used to check user account balance with project upgrade price when prolong featured upgrade.
$config['user_prolong_featured_upgrade_project_insufficient_funds_error_message'] = 'Váš aktuální zůstatek na účtu nepokrývá poplatek za vybranou službu.';


// This variable is used to check user account balance with project upgrade price when prolong urgent upgrade.
$config['user_prolong_urgent_upgrade_project_insufficient_funds_error_message'] = 'Váš aktuální zůstatek na účtu nepokrývá poplatek za vybranou službu.';


############### definitions of custom config for myproject page
################ My Project page Meta Variables ###########

$config['myprojects_page_title_meta_tag'] = '{user_first_name_last_name_or_company_name} | my projects title meta tag';
$config['myprojects_page_description_meta_tag'] = '{user_first_name_last_name_or_company_name} | My Projects description meta tag';
################ Page text Variables ###########
$config['myprojects_headline_title'] = 'My Projects';
################ Url Routing Variables ###########
//my-projects
$config['myprojects_page_url'] = 'my-projects';

########## this message is showing on project detail page if project type is sealed. It will show to all user except project owner
$config['project_details_page_project_sealed_disclaimer_message'] = '* as SEALED project, the list of professionals which placed a bid on this project, is hidden - PRJ';

$config['project_details_page_fulltime_project_sealed_disclaimer_message'] = '* as SEALED fulltime project, the list of professionals which placed a bid on this fulltime project, is hidden - FT';

#############################################################################
//variables used for project details page PO details
$config['project_details_page_total_projects_published'] = 'total projects published:';
$config['project_details_page_total_fulltime_projects_published'] = 'total ft projects published:';

$config['project_details_page_total_completed_projects_via_portal'] = 'completed projects (via portal):';
$config['project_details_page_total_hires_on_fulltime_projects_via_portal'] = 'hires on fulltime jobs:';

##################################################################################################

###############################################################################

// Real time notification message when project is rejected by admin(Send by Node)
$config['fulltime_project_rejected_by_admin_realtime_notification_message_sent_to_po'] = 'Your fulltime project "{project_title}" was rejected by admin';

$config['project_rejected_by_admin_realtime_notification_message_sent_to_po'] = 'Your project "{project_title}" was rejected by admin';

// Real time notification message when project is auto approved or approved by admin(Send by Node)
$config['fulltime_project_approved_by_admin_realtime_notification_message_sent_to_po'] = 'Your fulltime project "<a href="{project_url_link}" target="_blank">{project_title}</a>" was approved by admin and was successfully posted on the portal';

$config['project_approved_by_admin_realtime_notification_message_sent_to_po'] = 'Your project "<a href="{project_url_link}" target="_blank">{project_title}</a>" was approved by admin and was successfully posted on the portal';

$config['project_deleted_by_admin_realtime_notification_message_sent_to_po'] = 'Your project "{project_title}" was deleted by admin from the portal';

#################################################################

// config for my project section tabs heading for po
$config['my_projects_po_view_draft_tab_heading'] = "Drafts";
$config['my_projects_po_view_awaiting_moderation_tab_heading'] = "Awaiting Moderation"; 

$config['my_projects_po_view_open_for_bidding_tab_heading'] = "Open for Bidding";

$config['my_projects_po_view_awarded_tab_heading'] = "Awarded";

$config['my_projects_po_view_work_in_progress_tab_heading'] = "Work in Progress";

$config['my_projects_po_view_work_incomplete_tab_heading'] = "Incomplete";

$config['my_projects_po_view_completed_tab_heading'] = "Completed";

$config['my_projects_po_view_expired_tab_heading'] = "Expired";

$config['my_projects_po_view_cancelled_tab_heading'] = "Cancelled";

##################################################################################################

// config for my project section tabs heading for sp
$config['my_projects_sp_view_active_bids_tab_heading'] = "Active bids";

$config['my_projects_sp_view_awarded_bids_tab_heading'] = "Awarded Bids";

$config['my_projects_sp_view_projects_in_progress_tab_heading'] = "Projects In Progress";

$config['my_projects_sp_view_projects_incomplete_tab_heading'] = "Incomplete Projects";

$config['my_projects_employee_view_fulltime_projects_hired_tab_heading'] = "Hired";

$config['my_projects_sp_view_completed_tab_heading'] = "Completed";

##################################################################################################

// config for my project section
$config['my_projects_section_heading'] = "My Projects";

$config['my_projects_section_as_employer'] = "Project Owner / Employer";

$config['my_projects_section_as_service_provider'] = "Service Provider / Employee";


$config['my_projects_po_view_total_project_value'] = '<span class="po_view_total_project_value_color">Total project Value:</span>'; //this variable is using to show the "Total project Value" label in "inprogress,completed" tab in my project section of PO view

##################################################################################################

// Define the config variables for tab in inprogress/completed section tabs
$config['project_details_page_description_section_tab'] = 'Description';
$config['fulltime_project_details_page_description_section_tab'] = 'Description FT';

$config['project_details_page_payment_management_section_tab'] = 'Payments';

$config['project_details_page_messages_section_tab'] = 'Chat';

$config['project_details_page_feedback_section_tab'] = 'Review / Feedback';

$config['project_details_page_mark_project_complete_section_tab'] = 'Mark Project as Complete';

################################################################### violation report ###################################################################

$config['project_details_page_violation_report_popup_heading'] = 'en-Violation Report';
$config['project_details_page_violation_report_popup_sub_heading_txt'] = 'en-Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem';
$config['project_details_page_violation_report_popup_url_lbl'] = 'en-Url of Violation:';
$config['project_details_page_violation_report_popup_reason_lbl'] = 'en-Reason:';
$config['project_details_page_violation_report_popup_detail_violation_lbl'] = 'en-Detail Violation:';


$config['project_details_page_violation_report_popup_submit_report_btn_txt'] = 'en-Submit Report';
$config['project_details_page_violation_report_popup_reason_default_option_name'] = 'Please Choose One';

$config['project_details_page_violation_report_popup_disclaimer_modal_body'] = 'PD - Vaše zpětná vazba, uživatelské jméno a e-mailová adresa, budou zaslány na adresu xxxxx.cz. Více v <a href="{terms_and_conditions_page_url}" target="_blank">Obchodních podmínkách</a> a <a href="{privacy_policy_page_url}" target="_blank">Zásadách ochrany osobních údajů</a>.';

$config['project_details_page_violation_report_popup_reasons_option_name'] = [
 'Posting Contact Information',
 'Advertising Another Contact',
 'Fake Project Posted',
 'Other'
];

$config['project_details_page_violation_report_popup_reason_required_error_message'] = 'Reason is required.';
$config['project_details_page_violation_report_popup_detail_required_error_message'] = 'Detail is required.';


$config['project_details_page_violation_report_user_activity_log_mesage'] = 'You reported violation on project <a href="{project_url}" target="_blank">{project_title}</a>, reason: {project_violation_reason}';

$config['project_details_page_violation_report_submit_confirmation_mesage'] = 'You reported violation on project <a href="{project_url}" target="_blank">{project_title}</a>';


########################################################################################################################################################

?>