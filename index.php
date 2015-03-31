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
            function xmlhttpPost(strURL){
                console.log(strURL);
                //console.log(varqq);
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
                console.log();
            }

            function getstring() {
                var form     = document.forms['29'];
                squery = 'Campeonato=' + escape(form.Campeonato.value) + '&Time1=' + escape(form.Time1.value) + '&Time2=' + escape(form.Time2.value) + '&DataJogo=' + escape(form.DataJogo.value) + '&Casa=' + escape(form.Casa.value) + '&Empate=' + escape(form.Empate.value) + '&Fora=' + escape(form.Fora.value) + '&Notional=' + escape(form.Notional.value) + '&Escolha=' + escape(form.Escolha.value);
                console.log(squery);      
                return squery;
            }

            function updatepage(str){
                document.getElementById("sentback").innerHTML = str;
            }
            </script>
<?php
   $sql = "SELECT Campeonato, Time1, Time2, DataJogo, Casa, Empate, Fora, Resultado from Jogos";
   $retorno = (new minhaclasse())->usaDB("$sql");
   $formname = 0;
   foreach($retorno as $row) {
     $formname = $formname + 1;
     echo "<form name='29'>";
     echo "<div>"; 
     echo "<textarea name='Campeonato' rows='1' cols='20'>" . $row[0] . "</textarea>";
     echo "<textarea name='Time1' rows='1' cols='20'>" . $row[1] . "</textarea>";
     echo "<textarea name='Time2' rows='1' cols='20'>" . $row[2] . "</textarea>";
     echo "<textarea name='DataJogo' rows='1' cols='20'>" . $row[3] . "</textarea>";
     echo "<textarea name='Casa' rows='1' cols='20'>" . $row[4] . "</textarea>";
     echo "<textarea name='Empate' rows='1' cols='20'>" . $row[5] . "</textarea>";
     echo "<textarea name='Fora' rows='1' cols='20'>" . $row[6] . "</textarea>";
     echo "<textarea name='Resultado' rows='1' cols='20'>" . $row[7] . "</textarea>";
     echo "<textarea name='Notional' rows='1' cols='20'> ..notional.. </textarea>";
     echo "<textarea name='Escolha' rows='1' cols='20'> ..escolha.. </textarea>";
     
     //<input value="Go" type="button" onclick='JavaScript:xmlhttpPost("div_enviaapostas.php")'>
     
     echo "<input value='Go' type='button' onclick='JavaScript:xmlhttpPost('div_enviaapostas.php')'>";
     echo "</div>"; 
     echo "</form>";
     }
     echo "<div id='sentback'></div>";
   
?>
//fim da consulta de jogos


