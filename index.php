<?php
session_start(); //nao pode ter nada acima dessa linha
header('Content-Type: text/html; charset=utf-8');
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
use Facebook\FacebookServerException;
use Facebook\FacebookCanvasLoginHelper;

$retorno = (new minhaclasse())->usaDB('SELECT * from person LIMIT 1');
foreach($retorno as $row) {
  $appID = $row['FNAME'];
  $appSecret = $row['LNAME'];
}

FacebookSession::setDefaultApplication($appID,$appSecret);

// web: $helper = new FacebookRedirectLoginHelper( 'http://hazel-proxy-88217.appspot.com/' );
$helper = new FacebookCanvasLoginHelper();
try {
  //web: $session = $helper->getSessionFromRedirect();
  $session = $helper->getSession();
} catch( FacebookRequestException $ex ) {
  echo '// When Facebook returns an error';
} catch( Exception $ex ) {
  echo '// When validation fails or other local issues';
}

// see if we have a session
if ( isset( $session ) ) {
  $pessoal = (new FacebookRequest( $session, 'GET', '/me' ))->execute()->getGraphObject()->asArray();
  echo '<pre>' . print_r( $pessoal, 1 ) . '</pre>';
  echo "<img src='https://graph.facebook.com/".$pessoal['id']."/picture?type=large'/>"; 
  echo "<img src='https://graph.facebook.com/".$pessoal['id']."/picture?type=normal'/>";
  echo "Nome: ", $pessoal['name'],'<br>';
  echo "ID: ", $pessoal['id'],'<br>';
  $foto = (new FacebookRequest( $session, 'GET', '/me/picture?type=large' ))->execute()->getGraphObject()->asArray();
  echo '<pre>' . print_r( $foto, 1 ) . '</pre>';
  echo "<img src='".$foto['url']."'/>";
  echo "<img src='".$foto['data']['url']."'/>";
  //ATENCAO: invitable_friends or taggable_friends: the tokens returned through this API are not the same as the IDs returned via/me/friends.
  //friends: retorna o ID real, mas apenas de amigos que usam o aplicativo.
  //$taggable = (new FacebookRequest( $session, 'GET', '/me/invitable_friends' ))->execute()->getGraphObject()->asArray();
  //foreach ($taggable['data'] as $key => $value) {
  //   echo '<img class="friendthumb" src = "',$value->picture->data->url,'"/>';
  //   echo " - ", $value->name,'<br>';
  //} //iterate through friends graph
  $amigos = (new FacebookRequest( $session, 'GET', '/me/friends' ))->execute()->getGraphObject()->asArray();
  echo '<pre>' . print_r( $amigos, 1 ) . '</pre>';
  foreach ($amigos['data'] as $key => $value) {
     echo '<img class="friendthumb" src = "',$value->picture->data->url,'"/>';
     echo " - ", $value->name,'<br>';
  } //iterate through friends graph
} else {
    echo '<br> $session NAO existe <br>';
    $login_params = array(
        'scope' =>'publish_actions',
                  'user_friends',
                  'email',
                  'user_location',
                  'user_birthday'
            );
    $loginUrl = $helper->getLoginUrl($login_params);
    ?>
    <a href="<?php echo $loginUrl; ?>" >Login with Facebook</a>
    <?php
}



echo 'Hello!5 ';

?>
