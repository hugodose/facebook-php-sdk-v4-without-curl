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
    // Prepare statement
    $stmt = $db->prepare($SQLquery);
    // execute the query
    $stmt->execute();
    // echo a message to say the UPDATE succeeded
    echo $SQLquery . ': ';
    echo $stmt->rowCount() . " rows successfully <br>";
  } catch(PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
    echo "An error occurred in reading or writing to db.";
  }
  $db = null;
  return $stmt;
 }


//function selectDB($SQLquery){
//$db = $this->criaDB();
//try {
//  echo $SQLquery;
//  $retorno = $db->query($SQLquery);
//} catch (PDOException $ex) {
//  echo "An error occurred in reading or writing to db.";
//}
//$db = null;
//return $retorno;
//}

//function consultaDB(){
//$db = $this->criaDB();
//try {
//  foreach($db->query('SELECT * from person LIMIT 1') as $row) {
//  echo "<div>" . $row['FNAME'] . " " . $row['LNAME'] . "</div>";
//  }
//} catch (PDOException $ex) {
//  echo "An error occurred in reading or writing to db.";
//}
//$db = null;
//return $row;
//}

}

?>
