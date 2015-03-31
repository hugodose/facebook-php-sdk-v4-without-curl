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

if ( isset( $session ) ) {
  $pessoal = (new FacebookRequest( $session, 'GET', '/me' ))->execute()->getGraphObject()->asArray();
  //echo '<pre>' . print_r( $pessoal, 1 ) . '</pre>';
  echo "<img src='https://graph.facebook.com/".$pessoal['id']."/picture?type=normal'/>";
  echo "Nome: ", $pessoal['name'],'<br>';
  echo "ID: ", $pessoal['id'],'<br>';
  //ATENCAO: invitable_friends or taggable_friends: the tokens returned through this API are not the same as the IDs returned via/me/friends.
  //friends: retorna o ID real, mas apenas de amigos que usam o aplicativo.
  //$taggable = (new FacebookRequest( $session, 'GET', '/me/invitable_friends' ))->execute()->getGraphObject()->asArray();
  //foreach ($taggable['data'] as $key => $value) {
  //   echo '<img class="friendthumb" src = "',$value->picture->data->url,'"/>';
  //   echo " - ", $value->name,'<br>';
  //} //iterate through friends graph
  $amigos = (new FacebookRequest( $session, 'GET', '/me/friends' ))->execute()->getGraphObject()->asArray();
  //echo '<pre>' . print_r( $amigos, 1 ) . '</pre>';
  foreach ($amigos['data'] as $key => $value) {
     echo "<img src='https://graph.facebook.com/".$value->id."/picture?type=normal'/>";
     echo " - ", $value->name,'<br>';
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
    $loginUrl = $helper->getLoginUrl($login_params);
    ?>
    <a href="<?php echo $loginUrl; ?>" >Login with Facebook</a>
    <?php
}
echo 'Aposta Gol é um aplicativo gratuito';

?>
//consulta de jogos
            <title>Jogos</title>
            <script language="Javascript">
            function xmlhttpPost(strURL) {
                var xmlHttpReq = false;
                var self = this;
                // Mozilla/Safari
                if (window.XMLHttpRequest) {
                    self.xmlHttpReq = new XMLHttpRequest();
                }
                // IE
                else if (window.ActiveXObject) {
                    self.xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
                }
                self.xmlHttpReq.open('POST', strURL, true);
                self.xmlHttpReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                self.xmlHttpReq.onreadystatechange = function() {
                    if (self.xmlHttpReq.readyState == 4) {
                        updatepage(self.xmlHttpReq.responseText);
                    }
                }
                self.xmlHttpReq.send(getstring());
            }

            function getstring() {
                var form     = document.forms['Test'];
                var firstname = form.firstname.value;
                var secondname = form.secondname.value;
                squery = 'firstname=' + escape(firstname) + '&secondname=' + escape(secondname);  // NOTE: no '?' before querystring
                return squery;
            }

            function updatepage(str){
                document.getElementById("sentback").innerHTML = str;
            }
            </script>
            </head>
            <body>
            <form name="Test">

              This pages does a post without a page refresh. the update div will be filled in below the text entry area in DIV=sentback
                <p>First Name: <input name="firstname" type="text"> 
                <p>Second Name: <input name="secondname" type="text"> 
              <input value="Go" type="button" onclick='JavaScript:xmlhttpPost("div_jogos.php")'></p>
              <div id="sentback"></div>
            </form>
//fim da consulta de jogos


