

<?php

echo 'Hello! ';

require_once( 'src/Facebook/FacebookSession.php' );

echo 'Hello!2 ';
use src\Facebook\FacebookSession;
use src\Facebook\FacebookRequest;
use src\Facebook\GraphUser;
use src\Facebook\FacebookRequestException;

echo 'Hello!3 ';

FacebookSession::setDefaultApplication('1560115454240194','e9b7a69cc961d012592996b2dd540e3a');


$helper = new FacebookRedirectLoginHelper('http://globo.com');
$loginUrl = $helper->getLoginUrl();
// Use the login url on a link or button to redirect to Facebook for authentication

echo 'Hello!4 ';


?>

