

<?php
session_start();

echo 'Hello! ';


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
  echo '// When Facebook returns an error';
} catch( Exception $ex ) {
  echo '// When validation fails or other local issues';
}

// see if we have a session
if ( isset( $session ) ) {
    //echo "<script>
    //      window.close();
    //      window.opener.location.reload();
    //      </script>";
  // graph api request for user data
  $request = new FacebookRequest( $session, 'GET', '/me' );
  $response = $request->execute();
  // get response
  $graphObject = $response->getGraphObject();

  // print data
  echo '<pre>' . print_r( $graphObject, 1 ) . '</pre>';
} else {
    // show login url
    //$helper = new FacebookRedirectLoginHelper('https://apps.facebook.com/yourappname/');
    
    $login_params = array(
        'display' => 'popup',
        'next' => 'http://hazel-proxy-88217.appspot.com/',
        'redirect_uri' => 'http://hazel-proxy-88217.appspot.com/',
        'scope' =>'publish_actions',
                 'email',
                 'user_location',
                 'user_birthday'
            );
    //$permissions = array(
    //    'display' => 'popup',
    //    'scope' =>'publish_actions',
    //             'email',
    //             'user_location',
    //             'user_birthday'
    //);
    // Get login URL
    $loginUrl = $helper->getLoginUrl($login_params);
    //$auth_url = $helper->getLoginUrl(array('email'));
    ?>
    <a href="<?php echo $loginUrl; ?>">Login with Facebook</a>
    <?php
}



echo 'Hello!5 ';

?>
