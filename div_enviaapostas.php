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

        
        $CaixaInicial = 10000;
  
        $sql = "SELECT Sum(Notional) FROM Apostas WHERE userid = '$userid'  AND Pnl IS NULL ";
        $retorno = (new minhaclasse())->usaDB("$sql");
        foreach($retorno as $row) {
            $Margem = $row[0];
            if (empty($Margem)){
               $Margem = 0;
            }
        }
  
        $sql = "SELECT Sum(Notional) FROM Apostas WHERE userid = '$userid' AND Pnl IS NOT NULL";
        $retorno = (new minhaclasse())->usaDB("$sql");
        foreach($retorno as $row) {
           $Risco = $row[0];
           if (empty($Risco)){
               $Risco = 0;
           }
        } 
   
        $sql = "SELECT Sum(PnL) FROM Apostas WHERE userid = '$userid'";
        $retorno = (new minhaclasse())->usaDB("$sql");
        foreach($retorno as $row) {
           $Pnl = $row[0];
           if (empty($Pnl)){
               $Pnl = 0;
           }
        }
  
        $sql = "UPDATE Clientes SET Caixa = $CaixaInicial - $Margem + $Pnl , Margem = $Margem, Pnl = $Pnl, Risco = $Risco WHERE userid = '$userid'";
        $retorno = (new minhaclasse())->usaDB("$sql");







        $sql = "SELECT Caixa from Clientes WHERE userid='$userid'";
        $retorno = (new minhaclasse())->usaDB("$sql");
        foreach($retorno as $row) {
          $Caixa = $row[0];
        }
        if ($Caixa >= $Notional){
      
        $sql = "INSERT INTO Apostas (data, userid, campeonato, time1, time2, datajogo, notional, escolha, odds) VALUES ('$date', '$userid', '$Campeonato', '$Time1', '$Time2', '$DataJogo', $Notional, '$Escolha', $odds)";
        $retorno = (new minhaclasse())->usaDB("$sql");

        
        
        } else {
           echo "Caixa insuficiente";
        }
        

     } else {
        echo "Odds invalido: " . $odds . '<>' . $_POST[$Escolha] . '<br><br>';
     }  
   } else {
     echo 'Hora Atual: ' . $date . ' | Hora do Jogo: ' . $DataJogo . ' | Janela de Apostas Fechada <br><br>' ;
   }

?>
