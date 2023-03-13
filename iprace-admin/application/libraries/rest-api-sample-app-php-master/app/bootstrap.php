<?php

// Include the composer autoloader
if(!file_exists(__DIR__ .'/../vendor/autoload.php')) {
	echo "The 'vendor' folder is missing. You must run 'composer update' to resolve application dependencies.\nPlease see the README for more information.\n";
	exit(1);
}
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/common/user.php';
require_once __DIR__ . '/common/order.php';
require_once __DIR__ . '/common/paypal.php';
require_once __DIR__ . '/common/util.php';
session_start();
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

// Define connection parameters
//define('MYSQL_HOST', 'localhost:3306');
//define('MYSQL_USERNAME', 'root');
//define('MYSQL_PASSWORD', '');

return getApiContext();

// SDK Configuration
function getApiContext() {


    // Define the location of the sdk_config.ini file
    if (!defined("PP_CONFIG_PATH")) {
        define("PP_CONFIG_PATH", dirname(__DIR__));
    }        
    
	$apiContext = new ApiContext(new OAuthTokenCredential(
		'ARmNaL0dWss4WHYjy6rTmp2i0ilmJfzILV-TaLiSNe4D5cVOqkfGNlMiAEMv1ti3-5J08LvaWOtTrjfs',
		'EDqHbtF7vb7zc5Akx-F9egeWawTlbm0Shbq_sZEdaNiDTFgzwBmcdLbqh_EAn_64DEihnVT20gaS9dvQ'
	));

	
	// Alternatively pass in the configuration via a hashmap.
	// The hashmap can contain any key that is allowed in
	// sdk_config.ini	
	/*
	$apiContext->setConfig(array(
		'http.ConnectionTimeOut' => 30,
		'http.Retry' => 1,
		'mode' => 'sandbox',
		'log.LogEnabled' => true,
		'log.FileName' => '../PayPal.log',
		'log.LogLevel' => 'INFO'		
	));
	*/
    
    $_SESSION['apicontext'] = $apiContext;
    
	return $apiContext;
}
