

<?php

session_start();


echo 'depois $_SESSION: '.$_SESSION.'<br>';
echo 'depois $session: '.$session.'<br>';
echo 'Print $_SESSION <br>';
print_r($_SESSION);
echo '<br>';
echo 'Print $session <br>';
print_r($session);
echo '<br>';


echo 'Hello! <br>';


require_once( 'autoload.php' );
require_once($_SERVER['DOCUMENT_ROOT'].'/consultadb.php');


use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\GraphUser;
use Facebook\FacebookPermissionException;

echo 'Hello!2 ';
echo 'CONSULTA: ';


//$appObj = new minhaclasse();
//$appID = $appObj->consultadb()[0];
//$appSecret = $appObj->consultadb()[1];
//funciona, mas roda consulta() 2x

$appObj = new minhaclasse();
$appArray = $appObj->consultaDB();
$appID = $appArray[0];
$appSecret = $appArray[1];
echo 'FIM CONSULTA ';
echo $appID;
echo '<br>';
echo $appSecret;

FacebookSession::setDefaultApplication($appID,$appSecret);
echo '<br> Hello!4 <br>';


// login helper with redirect_uri
$helper = new FacebookRedirectLoginHelper( 'http://hazel-proxy-88217.appspot.com/' );
try {
  $session = $helper->getSessionFromRedirect();
  echo 'Try: <br> ';
  print_r($session);
  echo '<br>';
} catch( FacebookRequestException $ex ) {
  echo '// When Facebook returns an error';
} catch( Exception $ex ) {
  echo '// When validation fails or other local issues';
}

// see if we have a session
if ( isset( $session ) ) {
  echo '<br> $session existe: <br>';
  print_r($session);
  echo '<br>';
  // graph api request for user data
  $request = new FacebookRequest( $session, 'GET', '/me' );
  $response = $request->execute();
  // get response
  $graphObject = $response->getGraphObject();
  // print data
  echo '<pre>' . print_r( $graphObject, 1 ) . '</pre>';
  
  $taggable = (new FacebookRequest( $session, 'GET', '/me/taggable_friends' ))->execute()->getGraphObject()->asArray();
  echo '<pre>' . print_r( $taggable, 1 ) . '</pre>';
  foreach($taggable['data'] as $indice) {
      echo "<div>" . $indice->name . " ... " . $indice->id . "</div>";
      //echo "<img src='" . $indice->picture->data->url . "'></img><br>";
      //echo "https://graph.facebook.com/" . $indice->id . "/picture?type=large";
      
  }
  foreach ($taggable['data'] as $key => $value) {
     //echo '<img class="friendthumb" src = "',$value['picture']['data']['url'],'"/>';
     echo '<img class="friendthumb" src = "',$value->picture->data->url,'"/>';
     echo "<h4>", $value['name'],'</h4>';
  } //iterate through friends graph
  
  $friends = (new FacebookRequest($session, 'GET', '/me/friends'))->execute()->getGraphObject(GraphUser::className())->asArray();
  echo '<pre>: ' . print_r( $friends, 1 ) . '</pre>';
  foreach($friends['data'] as $friend) {
      $this->_friends[$friend->id] = $friend->name;
      echo "<div>" . $friend->name . " ... " . $friend->id . "</div>";
      //echo "<div>" . $friend['name'] . " ... " . $friend['id'] . "</div>";
  
  }
  
  
  
} else {
    echo '<br> $session NAO existe <br>';
    $login_params = array(
        'scope' =>'publish_actions',
                  'user_friends',
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
    <a href="<?php echo $loginUrl; ?>" >Login with Facebook</a>
    <?php
}



echo 'Hello!5 ';

?>
