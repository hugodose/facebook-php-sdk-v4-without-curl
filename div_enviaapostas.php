<?php
session_start(); //nao pode ter nada acima dessa linha
require_once($_SERVER['DOCUMENT_ROOT'].'/consultadb.php');



   $Campeonato = $_POST['Campeonato'];
   $Time1 = $_POST['Time1'];
   $Time2 = $_POST['Time2'];
   $DataJogo =  $_POST['DataJogo'];
   $Casa =  $_POST['Casa'];
   $Empate =  $_POST['Empate'];
   $Fora =  $_POST['Fora'];
   $Notional =  $_POST['Notional'];
   $Escolha =  $_POST['Escolha'];
   //$userid = $_POST['FBid'];
   $userid = $_SESSION['FBid'];
   echo $Escolha . '<br>';
   
   //Valida Horario e Odds na tabela Jogos
   $sql = "SELECT $Escolha from Jogos WHERE Campeonato='$Campeonato' AND Time1='$Time1' AND Time2='$Time2' AND DataJogo='$DataJogo'";
   $retorno = (new minhaclasse())->usaDB("$sql");
   foreach($retorno as $row) {
      $odds = $row[$Escolha];
   }
   
   date_default_timezone_set('Europe/London');
   $date = date('Y-m-d H:i:s');
   //echo $date . '<br>';
   
   if ($date < $_POST['DataJogo']){
     echo 'Hora Atual: ' . $date . ' | Hora do Jogo: ' . $DataJogo . ' | Janela de Apostas Aberta <br>' ;

     if ($odds == $_POST[$Escolha]){
        echo "Odds valido: " .$odds . ' = ' . $_POST[$Escolha] . '<br><br>';
        //$userid = '12001';
      
        $sql = "INSERT INTO Apostas (data, userid, campeonato, time1, time2, datajogo, notional, escolha, odds) VALUES ('$date', '$userid', '$Campeonato', '$Time1', '$Time2', '$DataJogo', $Notional, '$Escolha', $odds)";
        $retorno = (new minhaclasse())->usaDB("$sql");

        //$sql = "SELECT * from Apostas WHERE userid = $userid";
        //$retorno = (new minhaclasse())->usaDB("$sql");
        //foreach($retorno as $row) {
        //    echo "<div>" . $row[0] . " | " . $row[1] . " | " . $row[2] . " | " . $row[3] . " | " . $row[4] . " | " . $row[5] . " | " . $row[6] . " | " . $row[7] . " | " . $row[8] . " | " . $row[9] . " | " . $row[10] . " | " . $row[11] . " | " . $row[12] . "</div>";
        //}
     } else {
        echo "Odds invalido: " . $odds . '<>' . $row[$Escolha] . '<br><br>';
     }  
   } else {
     echo 'Hora Atual: ' . $date . ' | Hora do Jogo: ' . $DataJogo . ' | Janela de Apostas Fechada <br><br>' ;
   }

?>
