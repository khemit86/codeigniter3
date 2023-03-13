<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');



/*

|--------------------------------------------------------------------------

| File and Directory Modes

|--------------------------------------------------------------------------

|

| These prefs are used when checking and setting modes when working

| with the file system.  The defaults are fine on servers with proper

| security, but you may wish (or even need) to change the values in

| certain environments (Apache running a separate process for each

| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should

| always be used to set the mode correctly.

|

*/

define('FILE_READ_MODE', 0644);

define('FILE_WRITE_MODE', 0666);

define('DIR_READ_MODE', 0755);

define('DIR_WRITE_MODE', 0777);



/*

|--------------------------------------------------------------------------

| File Stream Modes

|--------------------------------------------------------------------------

|

| These modes are used when working with fopen()/popen()

|

*/



define('FOPEN_READ',							'rb');

define('FOPEN_READ_WRITE',						'r+b');

define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care

define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care

define('FOPEN_WRITE_CREATE',					'ab');

define('FOPEN_READ_WRITE_CREATE',				'a+b');

define('FOPEN_WRITE_CREATE_STRICT',				'xb');

define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

define('FOLDER_PATH', '');

$protocol = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on') ? 'https' : 'http';

define('URL',		'https://'.$_SERVER['HTTP_HOST']."/". FOLDER_PATH ."iprace-admin/");

define('VPATH',		'https://'.$_SERVER['HTTP_HOST']."/". FOLDER_PATH ."iprace-admin/");

define('APATH',		$_SERVER['DOCUMENT_ROOT']."/". FOLDER_PATH ."iprace-admin/");

define('ASSETS',		URL."assets/");

define('HTTP_HOST', $protocol.'://' .$_SERVER['HTTP_HOST']);

define('CSS',		ASSETS."css/");

define('JS',		ASSETS."js/");

define('IMAGE',		ASSETS."images/");

define('SITE_URL',      'https://'.$_SERVER['HTTP_HOST']."/".FOLDER_PATH."iprace-admin/");


define('USER_PERSONAL_ACCOUNT_TYPE', 1);
define('USER_COMPANY_ACCOUNT_TYPE', 2);
define('DATE_TIME_FORMAT','d.m.Y H:i:s');
define('DATE_FORMAT','d.m.Y');
define('TIME_FORMAT','H:i:s');
define('DATE_TIME_FORMAT_EXCLUDE_SECOND','d.m.Y H:i');
define('CURRENCY','Kč');

define('PAGING_LIMIT',5);
define('HTTP_WEBSITE_HOST',  $protocol.'://manish.devserver1.info/');// Url user when cron hit through node
define('WEBSITE_HOST',  'manish.devserver1.info');// web site host
define('PROJECT_MANAGEMENT_SOCKET_URL', HTTP_HOST.':17524'); // host for node to call url which handle through node server
//define('SITE_LANGUAGE', 'en'); 
define('SITE_LANGUAGE', 'cz');

define('CDN_FTP_SERVER_HOST_IP', '195.201.113.232');//IP of remote server where we create/load the files from
define('FTP_USERNAME', 'xYKsz2rn1QyrnVHi');
define('FTP_PASSWORD', 'SbLcastz5mpCQ6AR');
define('FTP_PORT', 57919);
 
define('TEMP_DIR', 'temp/');// upload the temp attachments of post project page logged off version also/user can download attachments
define('USERS_FTP_DIR', '/users/');//folder name where we define users related files and folders
define('PROJECTS_FTP_DIR', '/projects/');
define('PROJECT_TEMPORARY_DIR', 'temporary/');
define('PROJECT_DRAFT_DIR', 'draft/');// folder name which conatin the attachment of projects whoose status is draft
define('PROJECT_AWAITING_MODERATION_DIR', 'awaiting_moderation/');// folder name which contains the attachment of projects whoose status is awaiting_moderation
define('PROJECT_OPEN_FOR_BIDDING_DIR', 'open_for_bidding/');// folder name which contains the attachments of projects whoose status is open for bidding
define('PROJECT_AWARDED_DIR', 'awarded/');// folder name which contains the attachments of projects and bidder attachments whoose status is awarded
define('PROJECT_IN_PROGRESS_DIR', 'in_progress/');// folder name which contains the attachments of projects and bidder attachments whoose status is in progress
define('PROJECT_COMPLETED_DIR', 'completed/');// folder name which contains the attachments of projects and bidder attachments whoose status is completed
define('PROJECT_EXPIRED_DIR', 'expired/');// folder name which contains the attachments of projects whoose status is expired
define('PROJECT_OWNER_ATTACHMENTS_DIR', '/project_owner_attachments/');// folder which contains the attachments of projects uploaded by project owner 

define('PROJECT_CANCELLED_DIR', 'cancelled/');// folder name which contains projects whoose status is cancelled
define('USERS_BID_ATTACHMENTS_DIR', '/users_bid_attachments/');
define('CHAT_ATTACHMENTS_DIR', '/chat_attachments/');// /users/{file_sender_profile_name}/chat_attachments/file_receiver_id/{project_id-channel id}/{file}

define('FIXED_BUDGET_PROJECT_RELEASED_ESCROW_REFERENCE_ID_PREFIX', 'FBPRE');
define('HOURLY_RATE_PROJECT_RELEASED_ESCROW_REFERENCE_ID_PREFIX', 'HRPRE');
define('FULLTIME_PROJECT_RELEASED_ESCROW_REFERENCE_ID_PREFIX', 'FTPRE');
define('PROJECTS_CHARGED_SERVICE_FEES_REFERENCE_ID_PREFIX', 'PCSF');
define('PROJECT_BONUS_BASED_UPGRADE_PURCHASE_REFERENCE_ID_PREFIX', 'PUBP');
define('PROJECT_MEMBERSHIP_INCLUDED_UPGRADE_PURCHASE_REFERENCE_ID_PREFIX', 'PUMP');
define('PROJECT_REAL_MONEY_UPGRADE_PURCHASE_REFERENCE_ID_PREFIX', 'PURMP');


define('PROTOCOL', 'smtp');
define('SMTP_HOST', 'in-v3.mailjet.com');
define('SMTP_PORT', '587');
define('SMTP_TIMEOUT', '10');
define('SMTP_USER', '4a5f90ae4a6d30fedac0c8dcd3950ead');
define('SMTP_PASS', 'd9f6cec78ce8f8978fb8c0a918f37689');
define('CHARSET', 'utf-8');
define('MAILTYPE', 'html');
define('NEWLINE', '\r\n');


/**
* This variable used when we called any method defined in node through php, so this key send via url and it match with key defined in node_config.js -> node_url_authrization_key and if match then it allow to execute
*/
define('NODE_URL_AUTHORIZATION_KEY', 'qil6Nbbg40Z0zt7Cqil6Nbbg40Z0zt7C');
?>
