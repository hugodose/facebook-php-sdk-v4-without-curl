

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
try {
  $session = $helper->getSessionFromRedirect();
} catch( FacebookRequestException $ex ) {
  // When Facebook returns an error
} catch( Exception $ex ) {
  // When validation fails or other local issues
}
// see if we have a session
if ( isset( $session ) ) {
  // graph api request for user data
  $request = new FacebookRequest( $session, 'GET', '/me' );
  $response = $request->execute();
  // get response
  $graphObject = $response->getGraphObject();
     $fbid = $graphObject->getProperty('id');              // To Get Facebook ID
    $fbfullname = $graphObject->getProperty('name'); // To Get Facebook full name
    $femail = $graphObject->getProperty('email');    // To Get Facebook email ID
/* ---- Session Variables -----*/
    $_SESSION['FBID'] = $fbid;          
        $_SESSION['FULLNAME'] = $fbfullname;
    $_SESSION['EMAIL'] =  $femail;
  //checkuser($fuid,$ffname,$femail);
  header("Location: index.php");
} else {
  $loginUrl = $helper->getLoginUrl();
header("Location: ".$loginUrl);
}


echo 'Hello!5 ';


?>
