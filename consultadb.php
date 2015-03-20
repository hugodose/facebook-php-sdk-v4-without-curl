 <?php
class minhaclasse {

 private function connDB(){
  $db = null;
  if (isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'],'Google App Engine') !== false) {
  // Connect from App Engine.
    try{
      $db = new pdo('mysql:unix_socket=/cloudsql/hazel-proxy-88217:jogo;dbname=MinhaDB', 'root', '');
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      return $db;
    }catch(PDOException $ex){
      var_dump($e->getMessage());
      die(json_encode(
          array('outcome' => false, 'message' => 'Unable to connect.')
          )
      );
    }
  } else {
    echo 'NOT connected from Google App Engine environment.';
  }
 }

 
 function usaDB($SQLquery){
  $db = $this->connDB();
  try {
    // Prepare statement
    $stmt = $db->prepare($SQLquery);
    //$stmt->bindValue(1, $id, PDO::PARAM_INT);
    // execute the query
    $stmt->execute();
    // echo a message to say the UPDATE succeeded
    echo $SQLquery . ' : ';
    echo $stmt->rowCount() . " rows<br><br>";
  } catch(PDOException $e) {
    echo "An error occurred in reading or writing to db. <br>";
    echo $SQLquery . "<br>" . $e->getMessage();
    echo "<br>" ;
    echo "<br>" ;
    var_dump($e->getMessage());
    echo "<br>" ;
    echo "<br>" ;
    var_dump($db->errorInfo());
    echo "<br>" ;
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
