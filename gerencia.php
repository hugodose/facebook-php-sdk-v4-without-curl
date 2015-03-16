<html>
 <body>
  <h2>Entries</h2>
  <?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/consultadb.php');
 
  //$retorno = (new minhaclasse())->selectDB('ALTER TABLE person add column Id INT NOT NULL AUTO_INCREMENT FIRST, ADD primary KEY Id(Id)');
  
  $n1 = "oi1";
  $n2 = "oi2";
  $retorno = (new minhaclasse())->selectDB('INSERT INTO person VALUES (' . $n1 . ',' . $n2 . ')' );
  foreach($retorno as $row) {
  echo "<div>" . $row['FNAME'] . " " . $row['LNAME'] . "</div>";
  }
 
 
 
  $retorno = (new minhaclasse())->selectDB('SELECT * from person');
  foreach($retorno as $row) {
  echo "<div>" . $row['FNAME'] . " " . $row['LNAME'] . "</div>";
  }
  
  $retorno = (new minhaclasse())->selectDB('SELECT * from person LIMIT 1');
  foreach($retorno as $row) {
  echo "<div>" . $row['FNAME'] . " " . $row['LNAME'] . "</div>";
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
