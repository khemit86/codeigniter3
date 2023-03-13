<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
	
$config['password_min_length_character_limit_validation_reset_password'] = 6;

$config['password_characters_min_length_validation_reset_password_message'] = 'heslo musí mít alespoň '.$config['password_min_length_character_limit_validation_reset_password'].' znaků';

/* Filename: application\modules\forgot_password\controllers\Forgot_password.php Method name: recover_password_ajax */
$config['forgot_password_token_request_time_set_interval'] = 240;  //next password request time

$config['forgot_password_token_expire_set_interval'] = 180; //token expiration time

?>