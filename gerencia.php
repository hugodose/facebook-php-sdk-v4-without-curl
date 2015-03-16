<html>
 <body>
  <h2>Entries</h2>
  <?php
  $db = null;
  if (isset($_SERVER['SERVER_SOFTWARE']) &&
  strpos($_SERVER['SERVER_SOFTWARE'],'Google App Engine') !== false) {
    // Connect from App Engine.
    try{
       $db = new pdo('mysql:unix_socket=/cloudsql/hazel-proxy-88217:jogo;dbname=MinhaDB', 'root', '');
    }catch(PDOException $ex){
        die(json_encode(
            array('outcome' => false, 'message' => 'Unable to connect.')
            )
        );
    }
  }
  try {
    // Show existing guestbook entries.
    foreach($db->query('SELECT * from person') as $row) {
            echo "<div>" . $row['FNAME'] . " : " . $row['LNAME'] . "</div><br>";
     }
  } catch (PDOException $ex) {
    echo "An error occurred in reading or writing to guestbook.";
  }
  $db = null;
  ?>

  <h2>Input</h2>
  <form action="/sign" method="post">
    <div><textarea name="name" rows="3" cols="60"></textarea></div>
    <div><textarea name="content" rows="3" cols="60"></textarea></div>
    <div><input type="submit" value="Input"></div>
  </form>
  </body>
</html>
