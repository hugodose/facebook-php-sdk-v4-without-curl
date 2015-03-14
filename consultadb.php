 <?php



$db = null;
if (isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'],'Google App Engine') !== false) {
// Connect from App Engine.
  try{
    $db = new pdo('mysql:unix_socket=/cloudsql/hazel-proxy-88217:jogo;dbname=MinhaDB', 'root', '');
  }catch(PDOException $ex){
    die(json_encode(
        array('outcome' => false, 'message' => 'Unable to connect.')
        )
    );
  }
} else {
  echo 'NOT connected from Google App Engine environment.';
}



try {
    $sql = "UPDATE person SET FNAME='1560115454240194', LNAME='e9b7a69cc961d012592996b2dd540e3a' LIMIT 1";
    // Prepare statement
    $stmt = $db->prepare($sql);
    // execute the query
    $stmt->execute();
    // echo a message to say the UPDATE succeeded
    echo $stmt->rowCount() . " records UPDATED successfully";
} catch(PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
}




try {
  // Show existing guestbook entries.
  foreach($db->query('SELECT * from person') as $row) {
  echo "<div>" . $row['FNAME'] . " " . $row['LNAME'] . "</div>";
  }
} catch (PDOException $ex) {
  echo "An error occurred in reading or writing to db.";
}


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

?>
