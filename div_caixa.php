<?php
session_start(); //nao pode ter nada acima dessa linha
require_once($_SERVER['DOCUMENT_ROOT'].'/consultadb.php');
  //$FBid = '12001';
  //$FBid = $_POST['FBid'];
  echo "CAIXA<br>";
  $userid = $_SESSION['FBid'];
  
  $CaixaInicial = 10000;
  
  $sql = "SELECT Sum(Notional) FROM Apostas WHERE userid = '$userid'  AND Pnl IS NULL ";
  $retorno = (new minhaclasse())->usaDB("$sql");
   foreach($retorno as $row) {
      $Margem = $row[0];
   }
  
  $sql = "SELECT Sum(Notional) FROM Apostas WHERE userid = '$userid' AND Pnl IS NOT NULL";
  $retorno = (new minhaclasse())->usaDB("$sql");
   foreach($retorno as $row) {
      $Risco = $row[0];
   } 
   
  $sql = "SELECT Sum(PnL) FROM Apostas WHERE userid = '$userid'";
  $retorno = (new minhaclasse())->usaDB("$sql");
   foreach($retorno as $row) {
      $Pnl = $row[0];
   }
  
  $sql = "UPDATE Clientes SET Caixa = $CaixaInicial - $Margem + $Pnl , Margem = $Margem, Pnl = $Pnl, Risco = $Risco WHERE userid = '$userid'";
  $retorno = (new minhaclasse())->usaDB("$sql");
  
  
  $sql = "SELECT caixa, margem, pnl, risco from Clientes WHERE userid = '$userid'";
  $retorno = (new minhaclasse())->usaDB("$sql");
  foreach($retorno as $row) {
     $formname = 'Caixa'; 
     $Ativos = $row[0] + $row[1];
     echo "<form name='" . $formname . "'>";
     echo "<div>"; 
     echo "<input type='text' size='20' name='Ativos' value='" . $Ativos . "'></input>";
     echo "<input type='text' size='20' name='Caixa' value='" . $row[0] . "'></input>";
     echo "<input type='text' size='20' name='Margem' value='" . $row[1] . "'></input>";
     echo "<input type='text' size='18' name='Retorno' value='" . $row[2] . "'></input>";
     echo "<input type='text' size='18' name='Risco' value='" . $row[3] . "'></input>";
     echo "<input type='text' size='20' name='Retorno/Risco' value='" . $row[2]/$row[3]*100 . "%'></input>";
     echo "</div>"; 
     echo "</form>";
  }
echo '<br>';
?>
