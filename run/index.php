<?php 
session_start();

require_once( 'Facebook/FacebookSession.php' );
require_once( 'Facebook/FacebookRedirectLoginHelper.php' );
require_once( 'Facebook/FacebookRequest.php' );
require_once( 'Facebook/FacebookResponse.php' );
require_once( 'Facebook/FacebookSDKException.php' );
require_once( 'Facebook/FacebookRequestException.php' );
require_once( 'Facebook/FacebookAuthorizationException.php' );
require_once( 'Facebook/GraphObject.php' );
require_once('Facebook/Entities/AccessToken.php');
require_once('Facebook/HttpClients/FacebookHttpable.php');
require_once('Facebook/HttpClients/FacebookCurl.php');
require_once('Facebook/HttpClients/FacebookCurlHttpClient.php');
require_once('Facebook/FacebookPermissionException.php');



use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;

// Requested permissions for the app - optional
$permissions = array(
  'email',
  'user_location',
  'user_birthday',
  'manage_pages', 
  'publish_stream',
  'publish_actions',
  );
$returnurl = 'https://admega.vn/appfacebook/';

// init app with app id (APPID) and secret (SECRET)
FacebookSession::setDefaultApplication('904786406215923','bf439a796ab18259317c21d27546272f');

// login helper with redirect_uri
$helper = new FacebookRedirectLoginHelper( 'https://admega.vn/appfacebook/' );

try {
  $session = $helper->getSessionFromRedirect();
} catch( FacebookRequestException $ex ) {
  // When Facebook returns an error
} catch( Exception $ex ) {
  // When validation fails or other local issues
}

// see if we have a session
if ( isset( $session ) ) {
   //-------------------------------GET PROFILE -----------------------------------------------
  // graph api request for user data
  // $request = new FacebookRequest( $session, 'GET', '/me' );
  // $response = $request->execute();
  // // get response
  // $graphObject = $response->getGraphObject()->asArray();

  // // print data
  // print_r( $graphObject );


  /**--------------------------GET PICTURES----------------------------------------------
* Get User’s Profile Picture
*/
// Graph API to request profile picture
// $request = (new FacebookRequest( $session, 'GET', '/me/picture?type=large&redirect=false' ))->execute();

// // Get response as an array
// $picture = $request->getGraphObject()->asArray();

// print_r( $picture );

//---------------------------POST COMMENT STATUS---------------------------------------------

/**
* Publish to User’s Timeline
*/
// Graph API to publish to timeline
try{
  $request = (new FacebookRequest( $session, 'POST', '/me/feed', array(
    'message' => 'I love articles on Admega.vn!')))->execute();
// Get response as an array, returns ID of post
  $response = $request->getGraphObject()->asArray();
  if($response){
    echo 'Successfully posted to Facebook Wall...';
  }

}catch(FacebookApiException $e){
  echo $e->getMessage();
}

// Graph API to publish to timeline with additional parameters
// $request = (new FacebookRequest( $session, 'POST', '/me/feed', array(
// 'name' => 'Facebook SDK PHP v4 — a complete guide!',
// 'caption' => 'Learn how to easily use the Facebook SDK PHP v4 library.',
// 'link' => 'https://play.google.com/store/apps/details?id=vn.nad.android.aoeclips',
// 'message' => 'Check out how to integrate Facebook with your website.'
// )))->execute();

// // Get response as an array, returns ID of post
// $response = $request->getGraphObject()->asArray();

// print_r( $response );



} else {
  // show login url
 echo '<a href="' . $helper->getLoginUrl($permissions) . '">Login</a>';

}
?>
