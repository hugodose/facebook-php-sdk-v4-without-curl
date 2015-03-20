<html>
 <body>
  <h2>Resultados</h2>
  
 <?php
 require_once($_SERVER['DOCUMENT_ROOT'].'/consultadb.php');

 $sql = 'SELECT Time1, Time2, DataJogo from Jogos WHERE Resultado = '''
 $retorno = (new minhaclasse())->usaDB("$sql");
 foreach($retorno as $row) {
 echo "<form action="" method="POST">";
 echo "<div><textarea name="Time1" rows="1" cols="20">" . $row[0] . "</textarea></div>";
 echo "<div><textarea name="Time2" rows="1" cols="20">" . $row[1] . "</textarea></div>";
 echo "<div><textarea name="DataJogo" rows="1" cols="20">" . $row[2] . "</textarea></div>";
 echo "<div><input type="submit" value="submit"></div>";
 echo "</form>";
 }
 echo '<br>';
 

 if (isset($_POST['submit'])===true) {
 
 echo $_POST['Time1'];
 echo $_POST['Time2'];
 echo $_POST['Data'];
  
 }
  
  
 ?>

  </body>
</html>
