

<?php

echo 'Hello! ';

//define('FACEBOOK_SDK_V4_SRC_DIR','/src/Facebook/');

//require_once("autoload.php");
//use src\Facebook\FacebookSession;
//use src\Facebook\FacebookRequest;
//use src\Facebook\GraphUser;
//use src\Facebook\FacebookRequestException;
//use src\Facebook\FacebookRedirectLoginHelper;

echo 'Hello!2 ';

echo 'Hello!3 ';

FacebookSession::setDefaultApplication('1560115454240194','e9b7a69cc961d012592996b2dd540e3a');
echo 'Hello!4 ';

$helper = new FacebookRedirectLoginHelper('http://globo.com');
$loginUrl = $helper->getLoginUrl();
// Use the login url on a link or button to redirect to Facebook for authentication

echo 'Hello!5 ';


?>

