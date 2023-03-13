<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 /** classes and methods that dont' require users to log in */
 $noLogin = array(
  'index'=>array()
 );
 $noLogin['index']['logout'] = true;
 $noLogin['index']['index'] = true;
 $noLogin['index']['not_allowed'] = true;

 $privs =array();

 /** admin, user id = 1, dont' need to define anything since all is allowed */

 /** non-admin, usertype_id = 2 , list the class and method that non-admin have no access to. */
// "2" is the usertype_id on database
 $privs[2]['account']['add_account'] = true; //literally means: user with usertype_id = 2 don't have access to method "add_account" on "account" class/controller
 $privs[2]['account']['delete_account'] = true;