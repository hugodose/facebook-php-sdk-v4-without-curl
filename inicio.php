

<?php
session_start();

echo 'Hello! ';


require_once( 'src/Facebook/FacebookSession.php' );
require_once( 'src/Facebook/FacebookRedirectLoginHelper.php' );
require_once( 'src/Facebook/FacebookRequest.php' );
require_once( 'src/Facebook/FacebookResponse.php' );
require_once( 'src/Facebook/FacebookSDKException.php' );
require_once( 'src/Facebook/FacebookRequestException.php' );
require_once( 'src/Facebook/FacebookAuthorizationException.php' );
require_once( 'src/Facebook/GraphObject.php' );
    
use src\Facebook\FacebookSession;
use src\Facebook\FacebookRequest;
use src\Facebook\GraphUser;
use src\Facebook\FacebookRequestException;
use src\Facebook\FacebookRedirectLoginHelper;

echo 'Hello!2 ';

echo 'Hello!3 ';

FacebookSession::setDefaultApplication('1560115454240194','e9b7a69cc961d012592996b2dd540e3a');
echo 'Hello!4 ';

$helper = new FacebookRedirectLoginHelper('http://globo.com');
$loginUrl = $helper->getLoginUrl();
// Use the login url on a link or button to redirect to Facebook for authentication

echo 'Hello!5 ';


?>

