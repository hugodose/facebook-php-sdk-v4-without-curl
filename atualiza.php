<html>
 <body>
  <h2>Resultados</h2>
  <br>
  <form action="" method="POST">
  <input type="Submit" name="Load" value="Carrega"/>
  </form>
  <br>

  <br>
  </form>
  
 
 <?php
 require_once($_SERVER['DOCUMENT_ROOT'].'/consultadb.php');

 if (isset($_POST['Load'])) {
   //$sql = "SELECT Time1, Time2, DataJogo from Jogos WHERE Resultado = ''";
   $sql = "SELECT Time1, Time2, DataJogo, Resultado from Jogos";
   $retorno = (new minhaclasse())->usaDB("$sql");
   foreach($retorno as $row) {
     echo "<form action='' method='POST'>";
     echo "<div>"; 
     echo "<textarea name='Time1' rows='1' cols='20'>" . $row[0] . "</textarea>";
     echo "<textarea name='Time2' rows='1' cols='20'>" . $row[1] . "</textarea>";
     echo "<textarea name='DataJogo' rows='1' cols='20'>" . $row[2] . "</textarea>";
     echo "<textarea name='Resultado' rows='1' cols='20'>" . $row[3] . "</textarea>";
     echo "<input type='submit' name='Input' value='Envia'>";
     echo "</div>"; 
     echo "</form>";
   }
 echo '<br>';
 }
 

 if (isset($_POST['Input'])) {
 
   $Time1 = $_POST['Time1'];
   $Time2 = $_POST['Time2'];
   $DataJogo =  $_POST['DataJogo'];
   $Resultado =  $_POST['Resultado'];
   
 
   $sql = "UPDATE Jogos SET Resultado='$Resultado' WHERE Time1='$Time1' AND Time2='$Time2' AND DataJogo='$DataJogo'";
   $retorno = (new minhaclasse())->usaDB("$sql");
   
   $sql = "UPDATE Apostas SET Resultado='$Resultado' WHERE Time1='$Time1' AND Time2='$Time2' AND DataJogo='$DataJogo'";
   $retorno = (new minhaclasse())->usaDB("$sql");
   
   $sql = "UPDATE Apostas SET Pnl=(Notional * (Odds-1)) WHERE Time1='$Time1' AND Time2='$Time2' AND DataJogo='$DataJogo' AND Escolha=Resultado";
   $retorno = (new minhaclasse())->usaDB("$sql");
   $sql = "UPDATE Apostas SET Pnl=0 WHERE Time1='$Time1' AND Time2='$Time2' AND DataJogo='$DataJogo' AND Escolha<>Resultado";
   $retorno = (new minhaclasse())->usaDB("$sql");
   $sql = "SELECT userid, sum(pnl) from Apostas GROUP BY userid";
   $retorno = (new minhaclasse())->usaDB("$sql");
   foreach($retorno as $row) {
     echo "<div>" . $row[0] . " | " . $row[1] . " | " . $row[2] . " | " . $row[3] . " | " . $row[4] . " | " . $row[5] . " | " . $row[6] . " | " . $row[7] . " | " . $row[8] . " | " . $row[9] . " | " . $row[10] . " | " . $row[11] . " | " . $row[12] . "</div>";
   }
   
   
 }
  
  
 ?>

  </body>
</html>
