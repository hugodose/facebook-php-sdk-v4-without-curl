<html>
 <body>
  <h2>Guestbook Entries</h2>
  <?php
  //echo 'Hello, ' . htmlspecialchars($user->getNickname());

  // Create a connection.
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
  } else {
    // Connect from a development environment.
    try{
       $db = new pdo('mysql:host=127.0.0.1:3306;dbname=MinhaDB', 'root', '');
    }catch(PDOException $ex){
        die(json_encode(
            array('outcome' => false, 'message' => 'Unable to connect')
            )
        );
    }
  }
  try {
    // Show existing guestbook entries.
    foreach($db->query('SELECT * from person') as $row) {
            echo "<div><strong>" . $row['FNAME'] . "</strong> wrote <br> " . $row['LNAME'] . "</div>";
     }
  } catch (PDOException $ex) {
    echo "An error occurred in reading or writing to guestbook.";
  }
  $db = null;
  ?>

  <h2>Sign the Guestbook</h2>
  <form action="/sign" method="post">
    <div><textarea name="name" rows="3" cols="60"></textarea></div>
    <div><textarea name="content" rows="3" cols="60"></textarea></div>
    <div><input type="submit" value="Sign Guestbook"></div>
  </form>
  </body>
</html>
