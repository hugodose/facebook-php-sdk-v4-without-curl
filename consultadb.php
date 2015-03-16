 <?php
class minhaclasse {

private function criaDB(){
$db = null;
if (isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'],'Google App Engine') !== false) {
// Connect from App Engine.
  try{
    $db = new pdo('mysql:unix_socket=/cloudsql/hazel-proxy-88217:jogo;dbname=MinhaDB', 'root', '');
    return $db;
  }catch(PDOException $ex){
    die(json_encode(
        array('outcome' => false, 'message' => 'Unable to connect.')
        )
    );
  }
} else {
  echo 'NOT connected from Google App Engine environment.';
}
}

 
function updateDB($SQLquery){
$db = $this->criaDB();
try {
    $sql = "UPDATE person SET FNAME='zzz91560115454240194', LNAME='ze9b7a69cc961d012592996b2dd540e3a' LIMIT 1";
    //echo $SQLquery;
    //$sql = $SQLquery;
    // Prepare statement
    $stmt = $db->prepare($sql);
    //echo $stmt;
    // execute the query
    $stmt->execute();
    // echo a message to say the UPDATE succeeded
    echo $stmt->rowCount() . " records UPDATED successfully";
} catch(PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
}
$db = null;
//return $row;
}


function selectDB($SQLquery){
$db = $this->criaDB();
try {
  echo $SQLquery;
  $retorno = $db->query($SQLquery);
  //$retorno->execute();
} catch (PDOException $ex) {
  echo "An error occurred in reading or writing to db.";
}
$db = null;
return $retorno;
}

function consultaDB(){
$db = $this->criaDB();

try {
  // Show existing guestbook entries.
  // Show existing guestbook entries.
  foreach($db->query('SELECT * from person LIMIT 1') as $row) {
  echo "<div>" . $row['FNAME'] . " " . $row['LNAME'] . "</div>";
  }
} catch (PDOException $ex) {
  echo "An error occurred in reading or writing to db.";
}
$db = null;
return $row;
}

}

?>
