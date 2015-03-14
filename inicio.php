

<?php
session_start();

echo 'Hello! '.$_SERVER['DOCUMENT_ROOT'].'/src/Facebook/FacebookSession.php';


//require_once( 'src/Facebook/FacebookSession.php' );
//require_once( 'src/Facebook/FacebookRedirectLoginHelper.php' );
//require_once( 'src/Facebook/FacebookRequest.php' );
//require_once( 'src/Facebook/FacebookResponse.php' );
//require_once( 'src/Facebook/FacebookSDKException.php' );
//require_once( 'src/Facebook/FacebookRequestException.php' );
//require_once( 'src/Facebook/FacebookAuthorizationException.php' );
//require_once( 'src/Facebook/GraphObject.php' );

require_once($_SERVER['DOCUMENT_ROOT'].'/src/Facebook/FacebookSession.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/src/Facebook/FacebookRedirectLoginHelper.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/src/Facebook/FacebookRequest.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/src/Facebook/FacebookResponse.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/src/Facebook/FacebookSDKException.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/src/Facebook/FacebookRequestException.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/src/Facebook/FacebookAuthorizationException.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/src/Facebook/GraphObject.php');

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\GraphUser;



echo 'Hello!2 ';

echo 'Hello!3 ';

FacebookSession::setDefaultApplication('1560115454240194','e9b7a69cc961d012592996b2dd540e3a');
echo 'Hello!4 ';


// login helper with redirect_uri
$helper = new FacebookRedirectLoginHelper( 'http://hazel-proxy-88217.appspot.com/' );

// see if a existing session exists
if ( isset( $_SESSION ) && isset( $_SESSION['fb_token'] ) ) {
  // create new session from saved access_token
  $session = new FacebookSession( $_SESSION['fb_token'] );

  // validate the access_token to make sure it's still valid
  try {
    if ( !$session->validate() ) {
      $session = null;
    }
  } catch ( Exception $e ) {
    // catch any exceptions
    $session = null;
  }

} else {
  // no session exists

  try {
    $session = $helper->getSessionFromRedirect();
  } catch( FacebookRequestException $ex ) {
    // When Facebook returns an error
  } catch( Exception $ex ) {
    // When validation fails or other local issues
    echo $ex->message;
  }

}

// see if we have a session
if ( isset( $session ) ) {

  // save the session
  $_SESSION['fb_token'] = $session->getToken();
  // create a session using saved token or the new one we generated at login
  $session = new FacebookSession( $session->getToken() );

  // graph api request for user data
  $request = new FacebookRequest( $session, 'GET', '/me' );
  $response = $request->execute();
  // get response
  $graphObject = $response->getGraphObject()->asArray();

  // print profile data
  echo '<pre>' . print_r( $graphObject, 1 ) . '</pre>';

  // print logout url using session and redirect_uri (logout.php page should destroy the session)
  echo '<a href="' . $helper->getLogoutUrl( $session, 'http://yourwebsite.com/app/logout.php' ) . '">Logout</a>';

} else {
  // show login url
  echo '<a href="' . $helper->getLoginUrl( array( 'email', 'user_friends' ) ) . '">Login</a>';
  }

echo 'Hello!5 ';


?>
