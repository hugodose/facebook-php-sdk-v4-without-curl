<html>
 <body>
  <h2>Entries</h2>
  <?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/consultadb.php');
 
  //$retorno = (new minhaclasse())->selectDB('ALTER TABLE person add column Id INT NOT NULL AUTO_INCREMENT FIRST, ADD primary KEY Id(Id)');
  $retorno = (new minhaclasse())->usaDB("UPDATE person SET FNAME='1560115454240194', LNAME='e9b7a69cc961d012592996b2dd540e3a' LIMIT 1" );
  echo '<br>'; 
  $retorno = (new minhaclasse())->usaDB("INSERT INTO person (FNAME, LNAME) VALUES ('oi','oi2')" );
  echo '<br>';
 
  $retorno = (new minhaclasse())->usaDB('SELECT * from person');
  foreach($retorno as $row) {
  echo "<div>" . $row['FNAME'] . " " . $row['LNAME'] . " " . $row['Id'] . "</div>";
  }

  ?>

  <h2>Input</h2>
  <form action="/sign" method="post">
    <div><textarea name="name" rows="3" cols="60"></textarea></div>
    <div><textarea name="content" rows="3" cols="60"></textarea></div>
    <div><input type="submit" value="Input"></div>
  </form>
  </body>
</html>
