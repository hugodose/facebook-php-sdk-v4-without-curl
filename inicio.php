

<?php

echo 'Hello!';


require_once( 'Facebook/FacebookSession.php' );
require_once( 'Facebook/FacebookRequest.php' );
require_once( 'Facebook/FacebookRequestException.php' );
require_once( 'Facebook/GraphUser.php' );

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;
echo 'Hello2!';
FacebookSession::setDefaultApplication('1560115454240194','e9b7a69cc961d012592996b2dd540e3a');
echo 'Hello!3';
// Use one of the helper classes to get a FacebookSession object.
//   FacebookRedirectLoginHelper
//   FacebookCanvasLoginHelper
//   FacebookJavaScriptLoginHelper
// or create a FacebookSession with a valid access token:
$session = new FacebookSession('access-token-here');
echo 'Hello!4';
// Get the GraphUser object for the current user:

try {
  $me = (new FacebookRequest(
    $session, 'GET', '/me'
  ))->execute()->getGraphObject(GraphUser::className());
  echo $me->getName();
} catch (FacebookRequestException $e) {
  echo 'The Graph API returned an error';
} catch (\Exception $e) {
  echo 'Some other error occurred';
}

echo 'Hello!5';


?>

