<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);
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
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);
/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

$protocol = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on') ? 'https' : 'http';
define('FOLDER_PATH', '');
define('URL', $protocol.'://' . $_SERVER['SERVER_NAME'] .'/'.FOLDER_PATH);
define('VPATH', $protocol.'://' . $_SERVER['SERVER_NAME'] .'/'.FOLDER_PATH);
define('APATH', $_SERVER['DOCUMENT_ROOT'] .'/'.FOLDER_PATH);
define('NODE_SERVERS', URL . "node_servers/");
define('ASSETS', URL . "assets/");
define('ASSETSEXT',  URL ."assets/");
define('ASSETSEXTJS',  URL ."assets/js/");
define('CSS', ASSETS . "css/");
define('JS', ASSETS . "js/");
define('IMAGE', ASSETS . "images/");
define('HTTP_HOST', $_SERVER['HTTP_HOST']);
define('WEBSITE_HOST',  'manish.devserver1.info');// web site host

define('SITE_URL', $protocol.'://' . HTTP_HOST . "/".FOLDER_PATH);

//define('SITE_URL', $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME']);

define('NODE_SERVER_IP', $_SERVER['SERVER_ADDR']);
define('USER_PERSONAL_ACCOUNT_TYPE', 1);
define('USER_COMPANY_ACCOUNT_TYPE', 2);
define('DATE_TIME_FORMAT','d.m.Y H:i:s');
define('DATE_FORMAT','d.m.Y');
define('TIME_FORMAT','H:i:s');
define('HTTP_HOST_CLI', URL);

define('ERROR_THRESHOLD_DEBUG_LEVEL',1); //control the level of errors reporting
/*
0 = Disables logging, Error logging TURNED OFF
|	1 = Error Messages (including PHP errors)
|	2 = Debug Messages
|	3 = Informational Messages
|	4 = All Messages
*/

define('CURRENCY','Kč');
define('PROJECT_MANAGEMENT_SOCKET_URL', $protocol.'://'.HTTP_HOST.':17524'); // host for node to call url which handle through node server

define('DATE_TIME_FORMAT_EXCLUDE_SECOND','d.m.Y H:i');
//define('SITE_LANGUAGE', 'cz'); // This variable is used to set whole site language, it values should be in following [en / cz]
define('SITE_LANGUAGE', 'cz');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code


define('CASSANDRA_DB_HOST','195.201.26.126');
define('CASSANDRA_DB_PORT', 9042);
define('CASSANDRA_DB_USER','travai');
define('CASSANDRA_DB_PASSWORD','travai');
define('CASSANDRA_DB_KEYSPACE','travai');


// config for site

//node server related config variables


//control user's session availability length time - after how many miuntes user session is automatically expires
define('USER_SESSION_AVAILABILITY_TIME', '00:60:00'); // user session time on system 	

define('CDN_SERVER_LOAD_FILES_PROTOCOL', 'https://');//this is the protocol used to load the files from the cdn server - user cover picture, user portfolio pictures, js and css files
define('CDN_SERVER_DOMAIN_NAME', 'manish.devserver1.info');//domain of remote server where we create/load the files from
define('CDN_FTP_SERVER_HOST_IP', '195.201.113.232');//IP of remote server where we create/load the files from
define('FTP_USERNAME', 'xYKsz2rn1QyrnVHi');
define('FTP_PASSWORD', 'SbLcastz5mpCQ6AR');
define('FTP_PORT', 57919);

define('TEMP_DIR', 'temp/');// upload the temp attachments of post project page logged off version also/user can download attachments

define('USERS_FTP_DIR', '/users/');//folder name where we define users related files and folders
define('USER_AVATAR', '/avatar/');//folder name where is hosted user avatar picture - both original and cropped one
define('USER_COVER_PICTURE', '/cover_picture/');//folder name where user cover picture is hosted
define('PROJECT_FEATURED_UPGRADE_COVER_PICTURE', '/project_featured_upgrade_cover_picture/');//folder name where is hosted featured_upgrade_cover_picture - both original and cropped one
define('USER_STANDALONE_PORTFOLIO_PAGE_COVER_PICTURE', '/standalone_portfolio_page_cover_picture/');

define('USER_PORTFOLIO', '/portfolio/');
define('USER_CERTIFICATES', '/certificates/');
define('SEND_FEEDBACK_ATTACHMENTS_DIR', '/send_feedback_attachments/');
define('PROJECTS_FTP_DIR', '/projects/');
define('PROJECT_TEMPORARY_DIR', 'temporary/');
define('LOGGED_OFF_USERS_TEMPORARY_PROJECTS_ATTACHMENTS_DIR', 'logged_off_users_temporary_projects_attachments/');// This config is used when visitor upload temporary project attachment from logged off version of post project page(make a directory standalone_portfolio_page_cover_picture in temp folder for attachemnts)
define('USERS_BID_ATTACHMENTS_DIR', '/users_bid_attachments/');
define('PROJECT_DRAFT_DIR', 'draft/');// folder name which conatin the attachment of projects whoose status is draft
define('PROJECT_AWAITING_MODERATION_DIR', 'awaiting_moderation/');// folder name which contains the attachment of projects whoose status is awaiting_moderation
define('PROJECT_OPEN_FOR_BIDDING_DIR', 'open_for_bidding/');// folder name which contains the attachments of projects whoose status is open for bidding
define('PROJECT_AWARDED_DIR', 'awarded/');// folder name which contains the attachments of projects and bidder attachments whoose status is awarded
define('PROJECT_IN_PROGRESS_DIR', 'in_progress/');// folder name which contains the attachments of projects and bidder attachments whoose status is in progress
define('PROJECT_COMPLETED_DIR', 'completed/');// folder name which contains the attachments of projects and bidder attachments whoose status is completed
define('PROJECT_INCOMPLETE_DIR', 'incomplete/');// folder name which contains the attachments of projects and bidder attachments whoose status is incomplete
define('PROJECT_DISPUTE_DIR', 'dispute/');// folder name which contains the attachments of diputed projects
define('PROJECT_EXPIRED_DIR', 'expired/');// folder name which contains the attachments of projects whoose status is expired
define('PROJECT_CANCELLED_DIR', 'cancelled/');// folder name which contains projects whoose status is cancelled
define('PROJECT_OWNER_ATTACHMENTS_DIR', '/project_owner_attachments/');// folder which contains the attachments of projects uploaded by project owner 

define('CHAT_ATTACHMENTS_DIR', '/chat_attachments/');// /users/{file_sender_profile_name}/chat_attachments/file_receiver_id/{project_id-channel id}/{file}

define('PROTOCOL', 'smtp');
define('SMTP_HOST', 'in-v3.mailjet.com');
// define('SMTP_HOST', 'smtp.mailgun.org'); // mailgun credentials

define('SMTP_PORT', '587');
define('SMTP_TIMEOUT', '10');

define('SMTP_USER', '4a5f90ae4a6d30fedac0c8dcd3950ead');
//define('SMTP_USER', 'postmaster@manish.devserver1.info'); // mailgun credentials

define('SMTP_PASS', 'd9f6cec78ce8f8978fb8c0a918f37689');
//define('SMTP_PASS', '6a1c43ade85491669b9a909508e3b2cc-c1fe131e-5c4372f3'); // mailgun credentials


define('CHARSET', 'utf-8');
define('MAILTYPE', 'html');
define('NEWLINE', '\r\n');
define('NODE_URL_AUTHORIZATION_KEY', 'qil6Nbbg40Z0zt7Cqil6Nbbg40Z0zt7C');/**
 * This variable used when we called any method defined in node through php, so this key send via url and it match with key defined in node_config.js -> node_url_authrization_key and if match then it allow to execute
 */

// This variable will use to set paid milestone reference id initial which later reflect on invoice
define('FIXED_BUDGET_PROJECT_RELEASED_ESCROW_REFERENCE_ID_PREFIX', 'FBPRE');
define('HOURLY_RATE_PROJECT_RELEASED_ESCROW_REFERENCE_ID_PREFIX', 'HRPRE');
define('FULLTIME_PROJECT_RELEASED_ESCROW_REFERENCE_ID_PREFIX', 'FTPRE');
define('PROJECTS_CHARGED_SERVICE_FEES_REFERENCE_ID_PREFIX', 'PCSF');
define('PROJECT_BONUS_BASED_UPGRADE_PURCHASE_REFERENCE_ID_PREFIX', 'PUBP');
define('PROJECT_MEMBERSHIP_INCLUDED_UPGRADE_PURCHASE_REFERENCE_ID_PREFIX', 'PUMP');
define('PROJECT_REAL_MONEY_UPGRADE_PURCHASE_REFERENCE_ID_PREFIX', 'PURMP');
define('REFERRAL_EARNINGS_WITHDRAWAL_TRANSACTION_ID_PREFIX', 'REWT');
define('INVOICE_REFERENCE_ID_PREFIX', 'INVOR');


// This variable will use to set project dispute reference id 
define('FIXED_BUDGET_PROJECT_DISPUTE_REFERENCE_ID_PREFIX', 'FBPD');// for fixed budget
define('HOURLY_RATE_BASED_PROJECT_DISPUTE_REFERENCE_ID_PREFIX', 'HRBPD');// for hourly based budget
define('FULLTIME_PROJECT_DISPUTE_REFERENCE_ID_PREFIX', 'FTPD');// for fulltime budget

define('USER_ACTIVITY_NULL_INVOICE_REFERENCE_ID_PREFIX', 'ANINVOR');
define('USER_NO_ACTIVITY_NULL_INVOICE_REFERENCE_ID_PREFIX', 'NANINVOR');
