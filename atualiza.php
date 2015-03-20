<html>
 <body>
  <h2>Resultados</h2>
  <br>
  <form action="" method="POST">
  <input type="Submit" name="Load" value="Carrega"/>
  </form>
  <br>
  <form action="" method="POST">
  <div><textarea name="Time1" rows="1" cols="20"></textarea></div>
  <div><textarea name="Time2" rows="1" cols="20"></textarea></div>
  <div><textarea name="DataJogo" rows="1" cols="20"></textarea></div>
  <input type="Submit" name="Input" value="Envia"/>
  <br>
  </form>
  
 
 <?php
 require_once($_SERVER['DOCUMENT_ROOT'].'/consultadb.php');

 if (isset($_POST['Load'])) {
   $sql = "SELECT Time1, Time2, DataJogo from Jogos WHERE Resultado = ''";
   $retorno = (new minhaclasse())->usaDB("$sql");
   foreach($retorno as $row) {
     echo "<form action='' method='POST'>";
     echo "<div>"; 
     echo "<textarea name='Time1' rows='1' cols='20'>" . $row[0] . "</textarea>";
     echo "<textarea name='Time2' rows='1' cols='20'>" . $row[1] . "</textarea>";
     echo "<textarea name='DataJogo' rows='1' cols='20'>" . $row[2] . "</textarea>";
     echo "<input type='submit' value='submit2'>";
     echo "</div>"; 
     echo "</form>";
   }
 echo '<br>';
 }
 

 if (isset($_POST['Input'])) {
 
 echo $_POST['Time1'];
 echo $_POST['Time2'];
 echo $_POST['DataJogo'];
  
 }
  
  
 ?>

  </body>
</html>
