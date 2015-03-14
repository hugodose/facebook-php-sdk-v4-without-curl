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
  echo "<div><strong>" . $row['FNAME'] . "</strong> wrote <br> " . $row['LNAME'] . "</div>";
  }
} catch (PDOException $ex) {
  echo "An error occurred in reading or writing to db.";
}

$db = null;

?>
