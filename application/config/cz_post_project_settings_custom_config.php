<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
################ Expiration time Variables to remove entry from temp project table ###########
/* Filename: application\modules\post_project\controllers\Post_project.php */
$config['temp_project_expiration_time'] = '00:20:00'; // temp project activity to activity expiration window

################ Post project Upgrade type description Config Variables for post project page ###########
/* Filename: application\modules\post_project\views\post_project.php */
/* Controller: Post_project Method name: index */

//Defination for max number of projects available for drafts
$config['free_membership_subscriber_max_number_of_draft_projects'] = '500';
$config['free_membership_subscriber_max_number_of_open_projects'] = '5';

$config['free_membership_subscriber_max_number_of_draft_fulltime_projects'] = '5';
$config['free_membership_subscriber_max_number_of_open_fulltime_projects'] = '5';

//Defination for max number of simultaneous open for bidding [cumulative value of draft+awaiting-moderation+open-bidding+expired]
$config['gold_membership_subscriber_max_number_of_draft_projects'] = '25';
$config['gold_membership_subscriber_max_number_of_open_projects'] = '100';

//Defination for max number of simultaneous open for bidding [cumulative value of draft+awaiting-moderation+open-bidding]
$config['gold_membership_subscriber_max_number_of_draft_fulltime_projects'] = '25';
$config['gold_membership_subscriber_max_number_of_open_fulltime_projects'] = '100';

$config['number_project_category_post_project'] = 5; // limit of category drop down allowed on post project page

$config['project_title_minimum_length_characters_limit_post_project'] = 3; //minimum limit of character of project title on project post page

$config['project_title_maximum_length_characters_limit_post_project'] = 65; //maximum limit of character of project title on project post page
$config['project_description_minimum_length_characters_limit_post_project'] = 10; //minimum limit of character of project description on project post page
$config['project_description_minimum_length_words_limit_post_project'] = 5; //minimum limit of words of project description on project post page
$config['project_description_maximum_length_characters_limit_post_project'] = 5000; //maximum limit of character of project description on project post page



//attachments
$config['maximum_allowed_number_of_attachments_on_projects'] = 3; // number of file allowed to user to allowed(checking from database)

$config['project_attachment_maximum_size_limit'] = 3; //(Size in MB) size of attachment allowed on post project page

$config['project_attachment_maximum_size_validation_post_project_message'] = "soubor, který chcete nahrát má velikost {file_size_mb} MB, a tím překračuje maximální povolenou velikost souboru ".$config['project_attachment_maximum_size_limit']." MB";


//tags
$config['number_tag_allowed_post_project'] = 3; //limit of tag allowed on post project page
$config['project_tag_minimum_length_characters_limit_post_project'] = 3; //minimum limit of character of project tag on project post page
$config['project_tag_maximum_length_characters_limit_post_project'] = 45; //maximum limit of character of project tag on project post page

$config['project_tag_characters_minimum_length_validation_post_project_message'] = 'tag musí mít alespoň '.$config['project_tag_minimum_length_characters_limit_post_project'].' znaků';



//title+description
$config['project_title_characters_min_length_validation_post_project_message'] = 'název projektu musí mít alespoň '.$config['project_title_minimum_length_characters_limit_post_project'].' znaků';

$config['fulltime_position_name_characters_min_length_validation_post_project_message'] = 'název pracovní pozice musí mít alespoň '.$config['project_title_minimum_length_characters_limit_post_project'].' znaků';

$config['project_description_characters_words_minimum_length_validation_post_project_message'] = 'popis projektu musí mít alespoň '.$config['project_description_minimum_length_words_limit_post_project'].' slov a '.$config['project_description_minimum_length_characters_limit_post_project'].' znaků';

$config['fulltime_position_description_characters_minimum_length_validation_post_project_message'] = 'popis pracovní pozice musí mít alespoň '.$config['project_description_minimum_length_characters_limit_post_project'].' znaků';

$config['fulltime_position_description_characters_words_minimum_length_validation_post_project_message'] = 'popis pracovní pozice musí mít alespoň '.$config['project_description_minimum_length_words_limit_post_project'].' slov a minimálně '.$config['project_description_minimum_length_characters_limit_post_project'].' znaků';



// this will be based on membership - from standard to featured / sealed / urgent / hidden
$config['standard_project_availability'] = "720:00:00"; //normally standard project availability - 30 days
$config['standard_project_refresh_sequence'] = "168:00:00"; // normal standard project refresh sequence - 1x every 7 days - 168:00:00


//DefinItion FOR project upgrade type FEATURED
$config['project_upgrade_price_featured'] = "475"; //1000
$config['project_upgrade_availability_featured'] = "240:00:00"; // this means that featured project availability for 10 days
$config['project_upgrade_refresh_sequence_featured'] = "72:00:00"; // normally featured project will refresh every 3 days (72 hours) - 72:00:00


//Definition for project upgrade type URGENT
$config['project_upgrade_price_urgent'] = "675"; //1000
$config['project_upgrade_availability_urgent'] = "168:00:00"; // this means that urgent project availability for 5 days
$config['project_upgrade_refresh_sequence_urgent'] = "12:00:00"; // normally urgent project will refresh every 2x day - 12:00:00


//Defination for project upgrade type SEALED
$config['project_upgrade_price_sealed'] = "975"; //2500
$config['project_upgrade_availability_sealed'] = "10:00:00"; //for entire life of the project
$config['project_upgrade_refresh_sequence_sealed'] = "120:00:00"; // normally sealed project will refresh 1x every 5 days - 120:00:00

//Definition for project upgrade type HIDDEN
$config['project_upgrade_price_hidden'] = "1450"; //3500
$config['project_upgrade_availability_hidden'] = "00:20:00"; //for entire life of the project

#########################################################
// Definitions for project auto approval intervals
$config['free_membership_subscriber_standard_project_auto_approval_min'] = "00:30:00";
$config['free_membership_subscriber_standard_project_auto_approval_max'] = "00:45:00";

$config['free_membership_subscriber_featured_project_auto_approval_min'] = "00:10:00";
$config['free_membership_subscriber_featured_project_auto_approval_max'] = "00:15:00";

$config['free_membership_subscriber_urgent_project_auto_approval_min'] = "00:10:00";
$config['free_membership_subscriber_urgent_project_auto_approval_max'] = "00:15:00";

$config['free_membership_subscriber_sealed_project_auto_approval_min'] = "00:10:00";
$config['free_membership_subscriber_sealed_project_auto_approval_max'] = "00:15:00";

$config['free_membership_subscriber_hidden_project_auto_approval_min'] = "00:10:00";
$config['free_membership_subscriber_hidden_project_auto_approval_max'] = "00:15:00";

// ** when combinations -> always compare the minimum values and choose the smallest one / compare the max values and cjhoose the smallest one -> than generate the random value between thse 2 values


// functionality creates a value between each upgrade and selects the min one

#################################################
$config['gold_membership_subscriber_standard_project_auto_approval_min'] = "00:05:00";
$config['gold_membership_subscriber_standard_project_auto_approval_max'] = "00:10:00";

$config['gold_membership_subscriber_featured_project_auto_approval_min'] = "00:01:30";
$config['gold_membership_subscriber_featured_project_auto_approval_max'] = "00:05:00";

$config['gold_membership_subscriber_urgent_project_auto_approval_min'] = "00:01:30";
$config['gold_membership_subscriber_urgent_project_auto_approval_max'] = "00:05:00";

$config['gold_membership_subscriber_sealed_project_auto_approval_min'] = "00:01:00";
$config['gold_membership_subscriber_sealed_project_auto_approval_max'] = "00:05:00";

$config['gold_membership_subscriber_hidden_project_auto_approval_min'] = "00:01:00";
$config['gold_membership_subscriber_hidden_project_auto_approval_max'] = "00:05:00";

?>