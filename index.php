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


//web $helper = new FacebookRedirectLoginHelper( 'http://apostagol20152016.appspot.com/' );
$helper = new FacebookCanvasLoginHelper();
try {
  //web $session = $helper->getSessionFromRedirect();
  $session = $helper->getSession();
} catch( FacebookRequestException $ex ) {
  echo '// When Facebook returns an error';
} catch( Exception $ex ) {
  echo '// When validation fails or other local issues<br>';
  echo $ex->getMessage();
}

if ( isset( $session ) ) {
  $pessoal = (new FacebookRequest( $session, 'GET', '/me' ))->execute()->getGraphObject()->asArray();
  //echo '<pre>' . print_r( $pessoal, 1 ) . '</pre>';
  echo "<img src='https://graph.facebook.com/".$pessoal['id']."/picture?type=normal'/>";
  echo "Nome: ", $pessoal['name'],'<br>';
  echo "<h1 id='FBid'>", $pessoal['id'],'</h1><br>';
  $FBid = $pessoal['id'];
  $_SESSION['FBid'] = $FBid;
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
        'scope' =>'user_friends',
                  'email',
                  'user_location',
                  'user_birthday'
            );
            //'publish_actions',
?>
<html>
<body>

<p><a href="#" onClick="logInWithFacebook()">Log In with the JavaScript SDK</a></p>

<script>
  logInWithFacebook = function() {
    FB.login(function(response) {
      if (response.authResponse) {
        //alert('You are logged in & cookie set!');
        //window.location.replace("https://apps.facebook.com/1560115454240194/");
        //window.location.href = "https://apps.facebook.com/1560115454240194/";
        top.location.href="https://apps.facebook.com/1560115454240194/"
        // Now you can redirect the user or do an AJAX request to
        // a PHP script that grabs the signed request from the cookie.
      } else {
        alert('User cancelled login or did not fully authorize.');
      }
    });
    return false;
  };
  window.fbAsyncInit = function() {
    FB.init({
      appId: '1560115454240194',
      cookie: true, // This is important, it's not enabled by default
      version: 'v2.2'
    });
  };

  (function(d, s, id){
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
</script>
</body>
</html>        
<?php           
            
            
            
            
           
    /*$loginUrl = $helper->getLoginUrl($login_params);  //apenas para web
    ?>
    <a href="<?php echo $loginUrl; ?>" >Login with Facebook</a>
    <?php
    */
    
    
} //fecha os NAO TEM SESSION
echo 'Aposta Gol é um aplicativo gratuito<br>';

if ( isset( $session ) ) {
  
  $sql = "SELECT count(*) FROM Clientes WHERE userid = '$FBid'";
  $retorno = (new minhaclasse())->usaDB("$sql");
  foreach($retorno as $row) {
      echo $row[1];
   }
  if ( ($retorno)!= 1){
    date_default_timezone_set('Europe/London');
    $date = date('Y-m-d H:i:s');
    $sql = "INSERT INTO Clientes (data, userid, nome, email, caixa) VALUES ('$date', '$FBid', 'Fulano Ciclano da Silva', 'emaildofulano.ciclano@dominio.com', 10000)";
    $retorno = (new minhaclasse())->usaDB("$sql");
    echo 'Novo Cliente cadastrado<br>';
  }
  
?>
<body onload="Inicia()">
<!-- #########   INICIO CAIXA   ######### -->
<script language="Javascript">
  function Inicia(){
    xmlhttpPostCAIXA('div_caixa.php','caixa');
  } 
  function xmlhttpPostCAIXA(strURL, divretorno){
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
              updatepageCAIXA(self.xmlHttpReq.responseText, divretorno);
        }
      }
      self.xmlHttpReq.send();
  }
  function updatepageCAIXA(str, divretorno){
      document.getElementById(divretorno).innerHTML = str;
      xmlhttpPost2('div_minhasapostas.php');
  }
</script>
<div id='caixa'></div>
<!--  #########   FIM CAIXA   #########    -->
<!-- #########   INICIO MINHAS APOSTAS   #########    -->
<script language="Javascript">
   
  function xmlhttpPost2(strURL){
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
              updatepage2(self.xmlHttpReq.responseText);
        }
      }
      self.xmlHttpReq.send();
  }
  function updatepage2(str){
      document.getElementById("minhasapostas").innerHTML = str;
  }
</script>
<div id='minhasapostas'></div>
<!-- #########   FIM MINHAS APOSTAS   #########    -->
<!-- #########   INICIO JOGOS   #########    -->
<script language="Javascript">
  function xmlhttpPost(strURL, formID){
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
      self.xmlHttpReq.send(getstring(formID));
  }

  function getstring(formID) {
     var form = document.forms[formID];
          //squery = 'FBid=' + input_FBid + '&Campeonato=' + escape(form.Campeonato.value) + '&Time1=' + escape(form.Time1.value) + '&Time2=' + escape(form.Time2.value) + '&DataJogo=' + escape(form.DataJogo.value) + '&Casa=' + escape(form.Casa.value) + '&Empate=' + escape(form.Empate.value) + '&Fora=' + escape(form.Fora.value) + '&Notional=' + escape(form.Notional.value) + '&Escolha=' + escape(form.Escolha.value);
          squery = 'Campeonato=' + escape(form.Campeonato.value) + '&Time1=' + escape(form.Time1.value) + '&Time2=' + escape(form.Time2.value) + '&DataJogo=' + escape(form.DataJogo.value) + '&Casa=' + escape(form.Casa.value) + '&Empate=' + escape(form.Empate.value) + '&Fora=' + escape(form.Fora.value) + '&Notional=' + escape(form.Notional.value) + '&Escolha=' + escape(form.Escolha.value);
          return squery;
     }

  function updatepage(str){
      document.getElementById("sentback").innerHTML = str;
      xmlhttpPost2('div_minhasapostas.php'); // reload minhas apostas
  }
</script>
            
<?php
   echo "<div id='sentback'></div>";
   $sql = "SELECT Id, Campeonato, Time1, Time2, DataJogo, Casa, Empate, Fora, Resultado from Jogos";
   $retorno = (new minhaclasse())->usaDB("$sql");
   foreach($retorno as $row) {
     $formname = 'Jogo' . $row[0]; 
     echo "<form name='" . $formname . "'>";
     echo "<div>"; 
     
     echo "<input type='text' size='20' name='Campeonato' value='" . $row[1] . "'></input>";
     echo "<input type='text' size='18' name='Time1' value='" . $row[2] . "'></input>";
     echo "<input type='text' size='18' name='Time2' value='" . $row[3] . "'></input>";
     echo "<input type='text' size='20' name='DataJogo' value='" . $row[4] . "'></input>";
     echo "<input type='text' size='4' name='Casa' value='" . $row[5] . "'></input>";
     echo "<input type='text' size='4' name='Empate' value='" . $row[6] . "'></input>";
     echo "<input type='text' size='4' name='Fora' value='" . $row[7] . "'></input>";
     echo "<input type='text' size='6' name='Resultado' value='" . $row[8] . "'></input>";
     echo "<input type='text' size='4' name='Notional' value='.$.'></input>";
     echo "<input type='text' size='6' name='Escolha' value='escolha'></input>";
  
     
     //echo "<textarea name='Campeonato' rows='1' cols='20'>" . $row[1] . "</textarea>";
     //echo "<textarea name='Time1' rows='1' cols='20'>" . $row[2] . "</textarea>";
     //echo "<textarea name='Time2' rows='1' cols='20'>" . $row[3] . "</textarea>";
     //echo "<textarea name='DataJogo' rows='1' cols='20'>" . $row[4] . "</textarea>";
     //echo "<textarea name='Casa' rows='1' cols='5'>" . $row[5] . "</textarea>";
     //echo "<textarea name='Empate' rows='1' cols='5'>" . $row[6] . "</textarea>";
     //echo "<textarea name='Fora' rows='1' cols='5'>" . $row[7] . "</textarea>";
     //echo "<textarea name='Resultado' rows='1' cols='10'>" . $row[8] . "</textarea>";
     //echo "<textarea name='Notional' rows='1' cols='5'> .$. </textarea>";
     //echo "<textarea name='Escolha' rows='1' cols='10'> ..escolha.. </textarea>";
     echo '<input value="Go" type="button" onclick="JavaScript:xmlhttpPost(\'div_enviaapostas.php\',\'' . $formname . '\')">';
     echo "</div>"; 
     echo "</form>";
     }
     
     
} // fecha IS SESSION
?>
<!-- #########   FIM JOGOS   #########    -->



