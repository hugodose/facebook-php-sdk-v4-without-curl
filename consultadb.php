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
  // Show existing guestbook entries.
  foreach($db->query('SELECT * from person') as $row) {
  echo "<div>" . $row['FNAME'] . " " . $row['LNAME'] . "</div>";
  }
} catch (PDOException $ex) {
  echo "An error occurred in reading or writing to db.";
}


try {
  // Show existing guestbook entries.
  $db->query('SELECT from person LIMIT 1');
  echo "<div>" . $db['FNAME'] . " " . $db['LNAME'] . "</div>";
  }
} catch (PDOException $ex) {
  echo "An error occurred in reading or writing to db.";
}



$db = null;

?>
