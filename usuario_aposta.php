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
   $sql = "SELECT Campeonato, Time1, Time2, DataJogo, Casa, Empate, Fora, Resultado from Jogos";
   $retorno = (new minhaclasse())->usaDB("$sql");
   foreach($retorno as $row) {
     echo "<form action='' method='POST'>";
     echo "<div>"; 
     echo "<textarea name='Campeonato' rows='1' cols='20'>" . $row[0] . "</textarea>";
     echo "<textarea name='Time1' rows='1' cols='20'>" . $row[1] . "</textarea>";
     echo "<textarea name='Time2' rows='1' cols='20'>" . $row[2] . "</textarea>";
     echo "<textarea name='DataJogo' rows='1' cols='20'>" . $row[3] . "</textarea>";
     echo "<textarea name='Casa' rows='1' cols='20'>" . $row[4] . "</textarea>";
     echo "<textarea name='Empate' rows='1' cols='20'>" . $row[5] . "</textarea>";
     echo "<textarea name='Fora' rows='1' cols='20'>" . $row[6] . "</textarea>";
     echo "<textarea name='Resultado' rows='1' cols='20'>" . $row[7] . "</textarea>";
     echo "<textarea name='Notional' rows='1' cols='20'> ..notional.. </textarea>";
     echo "<textarea name='Escolha' rows='1' cols='20'> ..escolha.. </textarea>";
     echo "<input type='submit' name='Input' value='Envia'>";
     echo "</div>"; 
     echo "</form>";
   }
 echo '<br>';
 }
 

 if (isset($_POST['Input'])) {
 
   $Campeonato = $_POST['Campeonato'];
   $Time1 = $_POST['Time1'];
   $Time2 = $_POST['Time2'];
   $DataJogo =  $_POST['DataJogo'];
   $Casa =  $_POST['Casa'];
   $Empate =  $_POST['Empate'];
   $Fora =  $_POST['Fora'];
   $Notional =  $_POST['Notional'];
   $Escolha =  $_POST['Escolha'];
   
   //Confirma Odds na tablea Jogos
   $sql = "SELECT $Escolha from Jogos WHERE Time1='$Time1' AND Time2='$Time2' AND DataJogo='$DataJogo'";
   $retorno = (new minhaclasse())->usaDB("$sql");
   foreach($retorno as $row) {
      $odds = $row[$Escolha];
   }
   echo $odds . '<br>';
   
   if ($odds == $_POST[$Escolha]){
      echo $odds . ' = ' . $_POST[$Escolha];
      
      
      date_default_timezone_set('Europe/London');
      $date = date('Y-m-d H:i:s');
      echo $date;
      $userid = '12001';
      
      $sql = "INSERT INTO Apostas (data, userid, campeonato, time1, time2, datajogo, notional, escolha, odds) VALUES ($date, $userid, $Campeonato, $Time1, $Time2, $DataJogo, $Notional, $Escolha, $odds)";
      $retorno = (new minhaclasse())->usaDB("$sql");

      $sql = "SELECT * from Apostas WHERE userid = $userid";
      $retorno = (new minhaclasse())->usaDB("$sql");
      foreach($retorno as $row) {
        echo "<div>" . $row[0] . " | " . $row[1] . " | " . $row[2] . " | " . $row[3] . " | " . $row[4] . " | " . $row[5] . " | " . $row[6] . " | " . $row[7] . " | " . $row[8] . " | " . $row[9] . " | " . $row[10] . " | " . $row[11] . " | " . $row[12] . "</div>";
      }
   } else {
      echo "Odds invalido: " . $odds . '<>' . $row[$Escolha];
   }  
 }
  
  
 ?>

  </body>
</html>
