<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
################ Expiration time Variables to remove entry from temp project table ###########
/* Filename: application\modules\post_project\controllers\Post_project.php */
$config['temp_project_expiration_time'] = '00:20:00'; // temp project activity to activity expiration window

################ Post project Upgrade type description Config Variables for post project page ###########
/* Filename: application\modules\post_project\views\post_project.php */
/* Controller: Post_project Method name: index */
//Defination for max number of projects available for drafts
$config['free_membership_subscriber_max_number_of_draft_projects'] = '500';
$config['free_membership_subscriber_max_number_of_open_projects'] = '500';

$config['free_membership_subscriber_max_number_of_draft_fulltime_projects'] = '500';
$config['free_membership_subscriber_max_number_of_open_fulltime_projects'] = '500';

//Defination for max number of simultaneous open for bidding [cumulative value of draft+awaiting-moderation+open-bidding+expired]
$config['gold_membership_subscriber_max_number_of_draft_projects'] = '500';
$config['gold_membership_subscriber_max_number_of_open_projects'] = '500';

//Defination for max number of simultaneous open for bidding [cumulative value of draft+awaiting-moderation+open-bidding]
$config['gold_membership_subscriber_max_number_of_draft_fulltime_projects'] = '500';
$config['gold_membership_subscriber_max_number_of_open_fulltime_projects'] = '500';


$config['number_project_category_post_project'] = 5; // limit of category drop down allowed on post project page


$config['project_title_minimum_length_characters_limit_post_project'] = 3; //minimum limit of character of project title on project post page

$config['project_title_maximum_length_characters_limit_post_project'] = 65; //maximum limit of character of project title on project post page

$config['project_description_minimum_length_characters_limit_post_project'] = 20; //minimum limit of character of project description on project post page

$config['project_description_maximum_length_characters_limit_post_project'] = 6000; //maximum limit of character of project description on project post page

$config['project_description_minimum_length_words_limit_post_project'] = 5;


$config['maximum_allowed_number_of_attachments_on_projects'] = 3; // number of file allowed to user to allowed(checking from database)
$config['project_attachment_maximum_size_limit'] = 3; //(Size in MB) size of attachment allowed on post project page



$config['number_tag_allowed_post_project'] = 3; //limit of tag allowed on post project page
$config['project_tag_minimum_length_characters_limit_post_project'] = 3; //minimum limit of character of project tag on project post page
$config['project_tag_maximum_length_characters_limit_post_project'] = 45; //maximum limit of character of project tag on project post page


//PROJECT TITLE AND DESCRIPTION LENGTH AND REQUIREMENTS
$config['project_title_characters_min_length_validation_post_project_message'] = 'Your project title must be at least '.$config['project_title_minimum_length_characters_limit_post_project'].' characters';

$config['project_description_characters_words_minimum_length_validation_post_project_message'] = 'project description must be at least '.$config['project_description_minimum_length_characters_limit_post_project'].' characters and '.$config['project_description_minimum_length_words_limit_post_project'].' words';



$config['fulltime_position_name_characters_min_length_validation_post_project_message'] = 'Your position name title must be at least '.$config['project_title_minimum_length_characters_limit_post_project'].' characters';

$config['fulltime_position_description_characters_minimum_length_validation_post_project_message'] = 'your ft position description must be at least '.$config['project_description_minimum_length_characters_limit_post_project'].' characters';

$config['fulltime_position_description_characters_words_minimum_length_validation_post_project_message'] = 'fulltime project description must be at least '.$config['project_description_minimum_length_characters_limit_post_project'].' characters and '.$config['project_description_minimum_length_words_limit_post_project'].' words';


$config['project_attachment_maximum_size_validation_post_project_message'] = "The file you are trying to upload has {file_size_mb} MB in size and exceeds current max allowed file size of ".$config['project_attachment_maximum_size_limit']." MB!";

$config['project_tag_characters_minimum_length_validation_post_project_message'] = 'tag must be at least '.$config['project_tag_minimum_length_characters_limit_post_project'].' characters';

// this will be based on membership - from standard to featured / sealed / urgent / hidden
$config['standard_project_availability'] = "72:00:00"; //hh:mm:ss
$config['standard_project_refresh_sequence'] = "00:10:00"; // this means that standard project will refresh every xxxx secs/mins/hours

//DefinItion FOR project upgrade type FEATURED
$config['project_upgrade_price_featured'] = "5000";
$config['project_upgrade_availability_featured'] = "240:00:00"; // this means that featured project availability for xxx
$config['project_upgrade_refresh_sequence_featured'] = "00:04:30"; // this means that featured project will refresh every

$config['project_upgrade_price_urgent'] = "2000";
$config['project_upgrade_availability_urgent'] = "168:00:00"; // this means that urgent project availability for xx
$config['project_upgrade_refresh_sequence_urgent'] = "00:01:30"; // this means that urgent project will refresh every xx

//Defination for project upgrade type SEALED
$config['project_upgrade_price_sealed'] = "1000";
$config['project_upgrade_availability_sealed'] = "00:05:00"; //for entire life of the project
$config['project_upgrade_refresh_sequence_sealed'] = "00:03:15"; // this means that sealed project will never refresh

//Definition for project upgrade type HIDDEN
$config['project_upgrade_price_hidden'] = "1350";
$config['project_upgrade_availability_hidden'] = "00:20:00"; // this means that it will stay for entire life of the project


#########################################################
// Definitions for project auto approval intervals
$config['free_membership_subscriber_standard_project_auto_approval_min'] = "00:60:00";
$config['free_membership_subscriber_standard_project_auto_approval_max'] = "00:75:00";

$config['free_membership_subscriber_featured_project_auto_approval_min'] = "00:15:00";
$config['free_membership_subscriber_featured_project_auto_approval_max'] = "00:25:00";

$config['free_membership_subscriber_urgent_project_auto_approval_min'] = "00:10:00";
$config['free_membership_subscriber_urgent_project_auto_approval_max'] = "00:20:00";

$config['free_membership_subscriber_sealed_project_auto_approval_min'] = "00:10:00";
$config['free_membership_subscriber_sealed_project_auto_approval_max'] = "00:15:00";

$config['free_membership_subscriber_hidden_project_auto_approval_min'] = "00:05:00";
$config['free_membership_subscriber_hidden_project_auto_approval_max'] = "00:10:00";

// ** when combinations -> always compare the minimum values and choose the smallest one / compare the max values and cjhoose the smallest one -> than generate the random value between thse 2 values

// functionality creates a value between each upgrade and selects the smallest one

#################################################
//standard projects for gold members - go diretly to open for bididng status if 00:0:00 / 00:00:00
$config['gold_membership_subscriber_standard_project_auto_approval_min'] = "00:40:00";
$config['gold_membership_subscriber_standard_project_auto_approval_max'] = "00:45:00";

$config['gold_membership_subscriber_featured_project_auto_approval_min'] = "00:35:00";
$config['gold_membership_subscriber_featured_project_auto_approval_max'] = "00:40:00";

$config['gold_membership_subscriber_urgent_project_auto_approval_min'] = "00:30:00";
$config['gold_membership_subscriber_urgent_project_auto_approval_max'] = "00:35:00";

$config['gold_membership_subscriber_sealed_project_auto_approval_min'] = "00:25:00";
$config['gold_membership_subscriber_sealed_project_auto_approval_max'] = "00:30:00";

$config['gold_membership_subscriber_hidden_project_auto_approval_min'] = "00:10:00";
$config['gold_membership_subscriber_hidden_project_auto_approval_max'] = "00:20:00";

?>