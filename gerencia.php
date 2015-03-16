<html>
 <body>
  <h2>Entries</h2>
  <?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/consultadb.php');
 
  $varum = $_POST['name'];
  $vardois = $_POST['content'];
  
  //$sqltodo = "INSERT INTO person (FNAME, LNAME) VALUES ('um','dois')";
  $sqltodo = $_POST['name'];;
  
  echo $sqltodo;
  //funciona: $retorno = (new minhaclasse())->selectDB('ALTER TABLE person add column Id INT NOT NULL AUTO_INCREMENT FIRST, ADD primary KEY Id(Id)');
  //funciona: $retorno = (new minhaclasse())->usaDB("UPDATE person SET FNAME='91560115454240194', LNAME='ze9b7a69cc961d012592996b2dd540e3a' LIMIT 1" );
  echo '<br>'; 
  //funciona: $retorno = (new minhaclasse())->usaDB("INSERT INTO person (FNAME, LNAME) VALUES ('$varum','$vardois')" );
  echo '<br>';
  
  $retorno = (new minhaclasse())->usaDB("$sqltodo");
  echo '<br>';
 
  $retorno = (new minhaclasse())->usaDB('SELECT * from person');
  foreach($retorno as $row) {
  echo "<div>" . $row['FNAME'] . " " . $row['LNAME'] . " " . $row['Id'] . "</div>";
  }

  ?>



  <h2>Input</h2>
  <form action="/gerencia.php" method="post">
    <div><textarea name="name" rows="3" cols="60"></textarea></div>
    <div><textarea name="content" rows="3" cols="60"></textarea></div>
    <div><input type="submit" value="Input"></div>
  </form>
  </body>
</html>
