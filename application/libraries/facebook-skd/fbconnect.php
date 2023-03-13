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
//FacebookSession::setDefaultApplication ('1698593867076320', '28be1d71cf52a96c39c0e10dfa76af94');
FacebookSession::setDefaultApplication ('1422800897839992', 'ffaab36ab1ca2dcc7da5d4180a264602');
// login helper with redirect_uri
$helper = new FacebookRedirectLoginHelper (site_url () . 'dashboard/facebook');
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
    $request = new FacebookRequest($session, 'GET', '/me?fields=email,first_name,last_name,gender,locale,name,friends,link');
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
    $fblink = $graphObject->getProperty ('link');
    $avatar = '<img src="https://graph.facebook.com/' . $graphObject->getProperty ('id') . '/picture?width=100&height=100" />';
    $fbfriends = $graphObject->getProperty ('friends')->getProperty ('summary')->getProperty ('total_count');
    

    /* ---- Session Variables ----- */
    $_SESSION['FB_ID'] = $fbid;
    $_SESSION['FB_FIRSTNAME'] = $fbfirstname;
    $_SESSION['FB_LASTSNAME'] = $fblastname;
    $_SESSION['EMAIL'] = $femail;
    $_SESSION['FB_GENDER'] = $fgender;
    $_SESSION['AVATAR'] = $avatar;
    $_SESSION['USERNAME'] = $fbfullname;
    $_SESSION['LOCALE'] = $fblocate;
    $_SESSION['FRIENDS'] = $fbfriends;
    $_SESSION['LINK'] = $fblink;
    
    /* ---- header location after session ---- */
    header ("Location: " . base_url ('dashboard/FBupdate'));
}
else
{
    if (isset ($_GET['error']) && $_GET['error'] == 'access_denied')
    {
        $this->session->set_flashdata ('facebook_fail', "Your Facebook account was not connected, please try again later.");
        redirect (SITE_URL . "dashboard/account-management#verifications");
    }
    else
    {
        $loginUrl = $helper->getLoginUrl (array ('scope' => 'public_profile,email,user_friends')); //user_location     
        header ("Location: " . $loginUrl);
    }
}
