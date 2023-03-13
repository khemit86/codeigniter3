<?php

// added in v4.0.0
if(!$_SESSION)
	session_start ();
require_once 'autoload.php';

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\Entities\AccessToken;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\HttpClients\FacebookHttpable;

// init app with app id and secret
FacebookSession::setDefaultApplication ('1698593867076320', '28be1d71cf52a96c39c0e10dfa76af94');

// login helper with redirect_uri
$helper = new FacebookRedirectLoginHelper (base_url () . 'signup/facebook');
try
{
    $session = $helper->getSessionFromRedirect ();
}
catch (FacebookRequestException $ex)
{
    // When Facebook returns an error
    var_dump ($ex->getMessage ());
}
catch (Exception $ex)
{
    // When validation fails or other local issues
    var_dump ($ex->getMessage ());
    exit;
}

// see if we have a session
if (isset ($session))
{

    // graph api request for user data  
    $request = new FacebookRequest ($session, 'GET', '/me?fields=email,first_name,last_name,gender,locale,name');
    $response = $request->execute ();
    // get response
    $graphObject = $response->getGraphObject ();
    $fbid = $graphObject->getProperty ('id');              // To Get Facebook ID
    $fbfirstname = $graphObject->getProperty ('first_name'); // To Get Facebook full name
    $fblastname = $graphObject->getProperty ('last_name'); // To Get Facebook last name
    $femail = $graphObject->getProperty ('email');    // To Get Facebook email ID
    $fgender = $graphObject->getProperty ('gender');
    $fblocate = $graphObject->getProperty ('locale');
    $fbfullname = $graphObject->getProperty ('name');
    $avatar = '<img src="https://graph.facebook.com/' . $graphObject->getProperty ('id') . '/picture?width=100&height=100" />';

    /* ---- Session Variables ----- */
    $_SESSION['FB_ID'] = $fbid;
    $_SESSION['FB_FIRSTNAME'] = $fbfirstname;
    $_SESSION['FB_LASTSNAME'] = $fblastname;
    $_SESSION['EMAIL'] = $femail;
    $_SESSION['FB_GENDER'] = $fgender;
    $_SESSION['AVATAR'] = $avatar;
    $_SESSION['USERNAME'] = $fbfullname;
    $_SESSION['LOCALE'] = $fblocate;
    /* ---- header location after session ---- */

    header ("Location: " . base_url ('signup/'));
    //header("Location: ".base_url('account/register/fb/check'));
}
else
{
    //var_dump('adsdsf');die;
    $loginUrl = $helper->getLoginUrl (array ('scope' => 'email')); //user_location

    header ("Location: " . $loginUrl);
}
?>