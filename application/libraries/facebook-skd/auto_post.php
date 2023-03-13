<?php 
//require_once 'autoload.php';
//use Facebook\FacebookSession;
//use Facebook\FacebookRequest;
//use Facebook\GraphObject;
//use Facebook\FacebookRequestException;
//
//
//$APP_ID = '262028517184991'; //app id
//$APP_SECRET = 'a8eac43be3b35cc7380f9b96b0250cb8'; //app secret
////token
//$TOKEN = "EAACEdEose0cBAKMbDI9cf8AyKhwAvAwNH8oPsm1xwyDAM9LDNVWUcbtCIzuZACNmbxGLQUaaepQSZC7ITZBUZAcjjW6uACtDraoc8MgWwUDEgL2rONzNlk4ahIZADHSpnbUbwoHRHFXABs44sZC4i5pXeuw8eSLvDPqAooKWIhGjBIyJeTpDNV"; //access token
//$ID = "904298132986799"; // facebook page id
//
//FacebookSession::setDefaultApplication($APP_ID, $APP_SECRET);
//
//$session_facebook = new FacebookSession($TOKEN);
//
//$params = array(
//  "message" => 'Message',
//  "link" => "http://www.iprace.online",
//  "picture" => "http://iprace.online/assets/images/banner1.png",
//  "name" => "How to Auto Post on Facebook with PHP",
//  "caption" => "www.haha.com",
//  "description" => "Automatically post on Facebook with PHP using Facebook PHP SDK."
//);
//
////$request = new FacebookRequest($session_facebook, 'POST', '/'.$ID.'/feed', $params);
////$response = $request->execute();
////  // get response
////  $graphObject = $response->getGraphObject();
////var_dump($graphObject);exit;
//$response = (new FacebookRequest($session_facebook, 'POST', '/'.$ID.'/feed', $params))->execute()->getGraphObject();
//


$page_access_token = 'EAAZAg2anZAf0QBANTsbAxacbZAyPL73wYzvlkYGZCx3WizcfQujhTei43kjx0mnwNpnZBz0pZBs1ilgXgWPcx3GNwAkj9WgbAQoHJ0k4IN9mGpb4JCt2qK3xUJZAfAZBIFU5GfbsQRYOW4YXcU8ZBZCuSc5ZCwaYZChcZCtRr46M0voKkcQZDZD';
$page_id = '339986000111613';
// $page_id = '280271725693410';

$data['picture'] = "";
$data['link'] = "http://www.example.com/";
$data['message'] = "New project posted on iPrace.online - click on following URL to access the project page and apply on it";
$data['name'] = "How to Auto Post on Facebook with PHP";
$data['caption'] = "www.haha.com";
$data['description'] = "Automatically post on Facebook with PHP using Facebook PHP SDK.";

$data['access_token'] = $page_access_token;

$post_url = 'https://graph.facebook.com/'.$page_id.'/feed';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $post_url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$return = curl_exec($ch);

curl_close($ch);

?>